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
                    <tr v-for="(employee, index) in paginatedData" :key="index" class="border-b ">
                      <td class="py-3 px-4 flex items-center gap-2">
                        <img :src="employee.image" alt="Employee" class="rounded-full" />
                        {{ truncateText(employee.name, 15) }}
                      </td>
                      <td class="py-2 px-4">{{ employee.designation }}</td>
                      <td class="py-2 px-4">{{ employee.dateFrom }}</td>
                      <td class="py-2 px-4">{{ employee.dateTo }}</td>
                      <td class="py-2 px-4">{{ employee.leaveType }}</td>
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
            employees: [
            {
                "name": "Aarav Sharma",
                "designation": "Software Engineer",
                "dateFrom": "01-01-2022",
                "dateTo": "10-01-2022",
                "leaveType": "Casual Leave",
                "reason": "Family function in hometown.",
                "image": "https://via.placeholder.com/40"
            },
            {
                "name": "Isha Verma",
                "designation": "Data Analyst",
                "dateFrom": "15-03-2022",
                "dateTo": "20-03-2022",
                "leaveType": "Sick Leave",
                "reason": "Health issues.",
                "image": "https://via.placeholder.com/40"
            },
            {
                "name": "Rahul Khanna",
                "designation": "Project Manager",
                "dateFrom": "05-05-2022",
                "dateTo": "15-05-2022",
                "leaveType": "Maternity Leave",
                "reason": "Wife’s delivery scheduled.",
                "image": "https://via.placeholder.com/40"
            },
            {
                "name": "Sneha Patel",
                "designation": "UI/UX Designer",
                "dateFrom": "10-07-2022",
                "dateTo": "12-07-2022",
                "leaveType": "Casual Leave",
                "reason": "Travel with family.",
                "image": "https://via.placeholder.com/40"
            },
            {
                "name": "Aditya Singh",
                "designation": "QA Engineer",
                "dateFrom": "01-06-2022",
                "dateTo": "03-06-2022",
                "leaveType": "Casual Leave",
                "reason": "Attending a wedding.",
                "image": "https://via.placeholder.com/40"
            },
            {
                "name": "Priya Mehta",
                "designation": "Software Developer",
                "dateFrom": "25-08-2022",
                "dateTo": "30-08-2022",
                "leaveType": "Sick Leave",
                "reason": "Severe back pain.",
                "image": "https://via.placeholder.com/40"
            },
            {
                "name": "Amit Kapoor",
                "designation": "Business Analyst",
                "dateFrom": "15-09-2022",
                "dateTo": "20-09-2022",
                "leaveType": "Casual Leave",
                "reason": "Short vacation.",
                "image": "https://via.placeholder.com/40"
            },
            {
                "name": "Kavya Joshi",
                "designation": "Frontend Developer",
                "dateFrom": "20-10-2022",
                "dateTo": "25-10-2022",
                "leaveType": "Medical Leave",
                "reason": "Minor surgery recovery.",
                "image": "https://via.placeholder.com/40"
            },
            {
                "name": "Arjun Nair",
                "designation": "Backend Developer",
                "dateFrom": "05-11-2022",
                "dateTo": "10-11-2022",
                "leaveType": "Casual Leave",
                "reason": "Festival celebration at home.",
                "image": "https://via.placeholder.com/40"
            },
            {
                "name": "Riya Gupta",
                "designation": "HR Manager",
                "dateFrom": "10-12-2022",
                "dateTo": "15-12-2022",
                "leaveType": "Casual Leave",
                "reason": "Trip with friends.",
                "image": "https://via.placeholder.com/40"
            },
            {
                "name": "Mohit Saxena",
                "designation": "DevOps Engineer",
                "dateFrom": "20-01-2023",
                "dateTo": "25-01-2023",
                "leaveType": "Sick Leave",
                "reason": "Hospitalization due to illness.",
                "image": "https://via.placeholder.com/40"
            },
            {
                "name": "Sanjana Roy",
                "designation": "Content Writer",
                "dateFrom": "01-02-2023",
                "dateTo": "05-02-2023",
                "leaveType": "Casual Leave",
                "reason": "Relocation to a new city.",
                "image": "https://via.placeholder.com/40"
            },
            {
                "name": "Karan Malhotra",
                "designation": "Database Administrator",
                "dateFrom": "15-03-2023",
                "dateTo": "20-03-2023",
                "leaveType": "Medical Leave",
                "reason": "Dental surgery recovery.",
                "image": "https://via.placeholder.com/40"
            },
            {
                "name": "Ananya Desai",
                "designation": "Marketing Specialist",
                "dateFrom": "10-04-2023",
                "dateTo": "15-04-2023",
                "leaveType": "Casual Leave",
                "reason": "Family trip to Goa.",
                "image": "https://via.placeholder.com/40"
            },
            {
                "name": "Rohan Choudhary",
                "designation": "Tech Lead",
                "dateFrom": "20-05-2023",
                "dateTo": "25-05-2023",
                "leaveType": "Casual Leave",
                "reason": "Adventure trek.",
                "image": "https://via.placeholder.com/40"
            }
        ]
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
    },
    methods: {
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
    },
};
</script>
