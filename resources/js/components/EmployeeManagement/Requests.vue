<template>
    <div>
        <header-component />
        <div style="margin-left: 242px;">
            <div class="max-w-7xl mx-auto p-4 h-screen flex flex-col">
              <div
                class="flex items-center justify-between bg-white p-4 shadow-md rounded-lg mb-5"
              >
                <!-- Left Section -->
                <div class="flex items-center">
                  <span class="font-semibold text-lg text-gray-800">Leave Requests</span>
                  <!-- <span class="text-gray-600 ml-1">From- {{ fromDate }}</span>
                  <span class="text-gray-600 ml-1">To- {{ toDate }}</span> -->
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
                  @change="fetchLeaveRequests"
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
            </div>
          
          </div>


            <!-- Table Container -->
            <div class="bg-white shadow-lg rounded-lg flex-grow flex flex-col">
              <!-- Table Header -->
              <div class="flex-grow overflow-auto">
                <table class="w-full table-auto">
                  <thead>
                    <tr class="text-left border-b">
                      <th class="py-3 px-4 text-blue-600">Employee</th>
                      <th class="py-3 px-4 text-blue-600">Designation</th>
                      <th class="py-3 px-4 text-blue-600">Date from</th>
                      <th class="py-3 px-4 text-blue-600">Date to</th>
                      <th class="py-3 px-4 text-blue-600">Leave Type</th>
                      <th class="py-3 px-4 text-blue-600">Reason</th>
                      <th class="py-3 px-4 text-blue-600">Details</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-if="paginatedData.length === 0">
                      <td colspan="7" class="text-center py-4">No leave requests found for the selected date range.</td>
                    </tr>
                    <tr v-else v-for="(employee, index) in paginatedData" :key="index" class="border-b ">
                      <td class="py-3 px-4 flex items-center gap-2">
                        <img
                        :src="employee.employee.employee_image || 'default-avatar.png'"
                        alt="Employee"
                        class="rounded-full w-10 h-10"
                      />
                      {{ truncateText(employee.employee.firstname + ' ' + employee.employee.lastname, 15) }}
                      </td>
                      <td class="py-2 px-4">{{ employee.employee.role }}</td>
                      <td class="py-2 px-4">{{ employee.leave_date }}</td>
                      <td class="py-2 px-4">{{ employee.leave_date }}</td>
                      <td class="py-2 px-4">{{ employee.status === 1 ? 'Approved' : 'Pending' }}</td>
                      <td class="py-2 px-4">{{ truncateText(employee.reason, 35) }}</td>
                      <td class="py-2 px-4">
                        <div class="gap-2 flex justify-center items-center">
                            <button class="text-blue-500 border border-blue-400 px-2 py-1 rounded-full hover:bg-blue-100 text-sm flex justify-center items-center gap-1">
                            <b-icon-list-task />View
                            </button>
                            <button 
                            class="text-green-500 border border-green-400 px-2 py-1 rounded-full text-sm hover:bg-green-100 flex justify-center items-center gap-1"
                            @click="openModal"
                            >
                            <b-icon-check-circle />Approve
                            </button>
                            <button class="text-red-500 border border-red-400 px-2 text-sm py-1 rounded-full hover:bg-red-100 flex justify-center items-center gap-1">
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
                <button class="bg-blue-500 text-white px-4 py-2 rounded" @click="confirmApproval">Confirm</button>
              </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "requests",
    data() {
        return {
            currentPage: 1, // Tracks the current active page
            itemsPerPage: 6,
            isModalOpen: false,
            fromDate: "", // Holds the selected "From" date
            toDate: "",
            employees: [],
        };
    },
    computed: {
    paginatedData() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return this.employees.slice(start, start + this.itemsPerPage);
    },
    totalPages() {
      return Math.ceil(this.employees.length / this.itemsPerPage);
    },
    // // Computed property to format the "From" date
    // formattedFromDate() {
    //   return this.formatDate(this.fromDate);
    // },
    // // Computed property to format the "To" date
    // formattedToDate() {
    //   return this.formatDate(this.toDate);
    // },
    },
    methods: {
      async fetchLeaveRequests() {
      if (this.fromDate && this.toDate) {
        console.log("fromDate value:", this.fromDate);
        console.log("toDate value:", this.toDate);
      if (this.fromDate && this.toDate) {
      try {
        const response = await axios.get(`http://192.168.29.127:8000/api/leave-requests?start_date=${this.fromDate}&end_date=${this.toDate}`) 
        console.log("API Response:", response.data);
        if (response.status === 200 && response.data) {
          this.employees = response.data; // Update this based on the actual API response format
        } else {
          console.error("Unexpected API response:", response);
          this.employees = [];
        }
      } catch (error) {
        console.error("Error fetching leave requests:", error);
        this.employees = []; // Reset employees on error
      }
    } else {
      console.log("Both fromDate and toDate are required.");
    }
  }
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
    openModal() {
      this.isModalOpen = true; // Show the modal
    },
    closeModal() {
      this.isModalOpen = false; // Hide the modal
    },
    confirmApproval() {
      // Perform approval action
      alert("Approved!");
      this.closeModal(); // Close the modal
    },
    // Method to format the date into "dd-mm-yyyy"
    formatDate(date) {
      if (!date) return "";
      const [year, month, day] = date.split("-");
      return `${day}-${month}-${year}`;
    },
    },
    mounted() {
    this.fetchLeaveRequests();
    },
};
</script>
