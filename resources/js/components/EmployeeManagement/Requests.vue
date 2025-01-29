<template>
    <div>
      <Loader msg="Loading Leave Request Listings..." v-model="isLoader" />
        <header-component />
        <div style="margin-left: 242px;">
            <div class="max-w-7xl mx-auto p-4 h-screen flex flex-col">
              <div
                class="flex items-center justify-between bg-white p-4 shadow-md rounded-lg mb-5"
              >
                <!-- Left Section -->
                <div class="flex items-center">
                  <span class="font-semibold text-lg text-gray-800">Leave Requests</span>
                </div>
                <!-- Center Section - Search Bar with Font Awesome Icon -->
                <div class="flex items-center flex-grow mx-12 relative">
                  <input
                    type="text"
                    placeholder="Search..."
                    class="w-full border border-gray-300 rounded-md pl-3 pr-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    style="height: 36px;"
                    v-model="searchQuery"
                    @input="handleSearch"
                  />
                  <div>
                    <font-awesome-icon
                    icon="search"
                    class="absolute right-0 mr-2 transform -translate-y-1/2 top-1/2 cursor-pointer text-gray-500"
                    style="font-size: 20px"
                    />
                  </div>
                </div>
              <!-- Right Section -->
              <div class="flex items-center space-x-4">
                <!-- From Date Selector -->
                <div class="flex items-center">
                  <label for="fromDate" class="text-gray-600 mr-2">From</label>
                  <input
                    type="date"
                    id="fromDate"
                    v-model="fromDate"
                    class="border border-gray-300 rounded-md px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                  />
                </div>
                <!-- To Date Selector -->
                <div class="flex items-center">
                  <label for="toDate" class="text-gray-600 mr-2">To</label>
                  <input
                    type="date"
                    id="toDate"
                    v-model="toDate"
                    @change="fetchLeaveRequests"
                    class="border border-gray-300 rounded-md px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                  />
                </div>
                <div class="flex items-center">
                  <button @click="leaveapply" class="bg-custom-button text-white px-3 py-1 text-sm rounded-md hover:bg-blue-600">Apply Leave</button>
                </div>
              </div>
            </div>
            <!-- Table Container -->
            <div class="bg-white shadow-lg rounded-lg flex-grow flex flex-col">
              <!-- Table Header -->
              <div class="flex-grow overflow-auto">
                <table class="min-w-full border border-custom-border rounded-lg border-collapse">
                  <thead class="bg-custom-bg_table_head_primary border-custom-border">
                    <tr class="text-gray-600 border border-custom-border rounded-lg">
                      <th class="p-3 border border-custom-border">Employee</th>
                      <th class="p-3 border border-custom-border">Designation</th>
                      <th class="p-3 border border-custom-border">Date from</th>
                      <th class="p-3 border border-custom-border">Date to</th>
                      <th class="p-3 border border-custom-border">Status</th>
                      <th class="p-3 border border-custom-border">Reason</th>
                      <th class="p-3 border border-custom-border">Details</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-if="isLoader">
                      <td colspan="7" class="text-center loader py-4">Loading...</td>
                    </tr>
                    <tr v-else-if="employees.length === 0">
                      <td colspan="7" class="text-center py-4">No leave requests found for the selected date range.</td>
                    </tr>
                    <tr v-else v-for="(employee, index) in paginatedData" :key="index" class="border-custom-border">
                      <td class="py-3 px-4 text-center flex items-center gap-2">
                        <img
                        :src="employee.employee.employee_image || 'default-avatar.png'"
                        alt="Employee"
                        class="rounded-full w-10 h-10 border-custom-border"
                      />
                      {{ truncateText(employee.employee.firstname + ' ' + employee.employee.lastname, 15) }}
                      </td>
                      <td class="border border-custom-border p-3" >{{ employee.employee.role }}</td>
                      <td class="border border-custom-border p-3" >{{ employee.leave_date }}</td>
                      <td class="border border-custom-border p-3" >{{ employee.leave_date }}</td>
                      <td class="border border-custom-border p-3" >{{ 
                        employee.status === 0 
                          ? 'Pending' 
                          : employee.status === 1 
                            ? 'Approved' 
                            : 'Rejected' 
                        }}
                      </td>
                      <td class="border border-custom-border p-3" >{{ truncateText(employee.reason, 35) }}</td>
                      <td class="border border-custom-border p-3" >
                        <div class="gap-2 flex justify-center items-center">
                            <button 
                            @click="openViewModal(employee.reason)"
                            class="text-blue-500 border border-blue-400 px-2 py-1 rounded-full hover:bg-blue-100 text-sm flex justify-center items-center gap-1">
                            <b-icon-list-task />View
                            </button>
                            <button 
                            v-if="employee.status === 0"
                            class="text-green-500 border border-green-400 px-2 py-1 rounded-full text-sm hover:bg-green-100 flex justify-center items-center gap-1"
                            @click="openModal(employee.id)"
                            >
                            <b-icon-check-circle />Approve
                            </button>
                            <button 
                            v-if="employee.status === 0"
                            @click="openRejectModal(employee.id)"
                            class="text-red-500 border border-red-400 px-2 text-sm py-1 rounded-full hover:bg-red-100 flex justify-center items-center gap-1">
                            <b-icon-x-circle /> Reject
                            </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>            
              </div>
              <!-- Pagination Section -->
              <div class="flex items-center justify-between p-4 border-t">
                <div class="flex items-center gap-2">
                  <label for="show" class="text-gray-600">Show</label>
                  <select
                    id="show"
                    v-model="itemsPerPage"
                    class="border border-gray-300 rounded px-2 py-1 focus:outline-none"
                  >
                    <option value="6">6</option>
                    <option value="12">12</option>
                    <option value="24">24</option>
                  </select>
                </div>
                <div class="flex items-center gap-1">
                  <button 
                  @click="prevPage"
                  :disabled="currentPage === 1"
                  class="text-blue-500 px-2 py-1 hover:bg-blue-100 disabled:text-gray-400">&laquo;</button>
                  <button
                        v-for="page in totalPages"
                        :key="page"
                        @click="goToPage(page)"
                        :class="[
                            'px-3 py-1 rounded',
                            page === currentPage
                            ? 'bg-blue-500 text-white hover:bg-blue-600'
                            : 'text-blue-500 border border-blue-400 hover:bg-blue-100',
                        ]"
                        >
                        {{ page }}
                    </button>
  
                    <button
                    @click="nextPage"
                    :disabled="currentPage === totalPages"
                    class="text-blue-500 px-2 py-1 hover:bg-blue-100 disabled:text-gray-400"
                  >
                    &raquo;
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 w-1/3">
              <h2 class="text-lg font-bold mb-4">Approval Confirmation</h2>
              <p class="mb-4">Are you sure you want to approve this?</p>
              <div class="flex justify-end gap-2">
                <button class="bg-gray-300 px-4 py-2 rounded" @click="closeModal">Cancel</button>
                <button class="bg-blue-500 text-white px-4 py-2 rounded" @click="updateLeaveRequestStatus(tempEmployeeId, 1)">Confirm</button>
              </div>
            </div>
        </div>
        <div v-if="isRejectModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
          <div class="bg-white rounded-lg p-6 w-1/3">
            <h2 class="text-lg font-bold mb-4">Reject Confirmation</h2>
            <p class="mb-4">Are you sure you want to reject this leave request?</p>
            <div class="flex justify-end gap-2">
              <button class="bg-gray-300 px-4 py-2 rounded" @click="closeRejectModal">Cancel</button>
              <button class="bg-red-500 text-white px-4 py-2 rounded" @click="updateLeaveRequestStatus(tempEmployeeId, 2)">Confirm</button>
            </div>
          </div>
        </div>
        <div v-if="isViewModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
          <div class="bg-white rounded-lg p-6 w-1/3">
            <h2 class="text-lg font-bold mb-4">Leave Reason</h2>
            <p class="mb-4">{{ selectedReason }}</p>
            <div class="flex justify-end gap-2">
              <button class="bg-gray-300 px-4 py-2 rounded" @click="closeViewModal">Close</button>
            </div>
          </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
export default {
    name: "requests",
    data() {
        return {
            isLoader: false,
            currentPage: 1, 
            itemsPerPage: 6,
            isModalOpen: false,
            isRejectModalOpen: false,
            tempEmployeeId: null,
            fromDate: "", 
            isViewModalOpen: false, 
            selectedReason: '',
            toDate: "",
            employees: [],
        };
    },
    computed: {
      paginatedData() {
        if (!Array.isArray(this.employees)) {
            return []; // Ensure it always returns an array
        }
        const start = (this.currentPage - 1) * this.itemsPerPage;
        return this.employees.slice(start, start + this.itemsPerPage);
      },
      totalPages() {
          return Math.ceil((this.employees || []).length / this.itemsPerPage);
      },
    },
    
    methods: {
      async fetchLeaveRequests() {
        if (this.fromDate && this.toDate) {
          this.error = null;
          this.isLoader = true;
        try {
          const response = await axios.get(`/api/leave-requests?start_date=${this.fromDate}&end_date=${this.toDate}`) 
          console.log("API Response:", response.data);
          if (response.status === 200 && Array.isArray(response.data)) {
            this.employees = response.data;
            console.log(this.employees,"this.employees")
          } else {
            console.error("Unexpected API response:", response);
            this.employees = [];
          }
        } catch (error) {
          console.error("Error fetching leave requests:", error);
          this.employees = []; // Reset employees on error
        } finally {
        this.isLoader = false; 
        }
      }
    },
    async updateLeaveRequestStatus(employeeId, status) {
      console.log("gasjhfgjsd", employeeId);
      this.isLoader = true;
      try{
        const response = await axios.put(`/api/LeaveRequest/${employeeId}/Status`, 
        {
        status: status,
        });
        if(response.status === 200) {
          alert(
            status === 1
              ? "Leave Request approved successfully"
              : "Leave request rejected successfully"
          );
          this.fetchLeaveRequests();
          this.closeModal();
        } else {
            alert("Failed to approve leave request.");
        }
      } catch(error) {
        console.error("Error approving leave request:", error);
        alert("An error occurred while approving the leave request.");
      } finally {
        this.isLoader = false;
      }
    },
    leaveapply() {
            // Navigate to the RunPayrollScr screen with the required month parameter
            this.$router.push({ name: 'leave'});
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    goToPage(page) {
      this.currentPage = page;
    },
    truncateText(text, maxLength) {
            return text.length > maxLength ? text.slice(0, maxLength) + "...." : text;
    },
    openModal(employeeId) {
      this.tempEmployeeId = employeeId;
      this.isModalOpen = true; 
    },
    closeModal() {
      this.tempEmployeeId = null,
      this.isModalOpen = false; 
    },
    openRejectModal(employeeId) {
      this.tempEmployeeId = employeeId;
      this.isRejectModalOpen = true; 
    },
    closeRejectModal() {
      this.tempEmployeeId = null;
      this.isRejectModalOpen = false; 
    },
    openViewModal(reason) {
      this.selectedReason = reason;
      this.isViewModalOpen = true; 
    },
    closeViewModal() {
      this.selectedReason = '';
      this.isViewModalOpen = false;
    },
    formatDate(date) {
      if (!date) return "";
      const [year, month, day] = date.split("-");
      return `${day}-${month}-${year}`;
    },
    },
    mounted() {
    const today = new Date();
    const yesterday = new Date();
    yesterday.setDate(today.getDate() - 1);

    const formatDate = (date) => {
      const yyyy = date.getFullYear();
      const mm = String(date.getMonth() + 1).padStart(2, "0");
      const dd = String(date.getDate()).padStart(2, "0");
      return `${yyyy}-${mm}-${dd}`;
    }

    this.fromDate = formatDate(yesterday);
    this.toDate = formatDate(today);

    this.fetchLeaveRequests();
  },
  };
</script>

<style lang="scss" scoped>
  .loader {
      text-align: center;
      font-size: 20px;
      color: #007bff;
  }
</style>
