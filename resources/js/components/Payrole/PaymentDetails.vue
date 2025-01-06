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
              <span class="font-semibold text-lg text-gray-800">Payment Details</span>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-white shadow-lg rounded-lg flex-grow flex flex-col">
          <!-- Table Header -->
          <div class="flex-grow overflow-auto">
            <table class="w-full table-auto">
              <thead>
                <tr class="text-center border-b">
                  <th class="py-3 px-4 text-blue-600 flex items-center gap-1 justify-center">Name <b-icon-arrow-down-up class="cursor-pointer"/></th>
                  <th class="py-3 px-4 text-blue-600">Salary Period</th>
                  <th class="py-3 px-4 text-blue-600">Designation</th>
                  <th class="py-3 px-4 text-blue-600">Gross Salary</th>
                  <th class="py-3 px-4 text-blue-600">Net Salary</th>
                  <th class="py-3 px-4 text-blue-600">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(employee, index) in paginatedData" :key="index" class="border-b text-center ">
                  <td class="py-3 px-4">{{ employee.name }}</td>
                  <td class="py-2 px-4">{{ employee.email }}</td>
                  <td class="py-2 px-4">{{ employee.designation }}</td>
                  <td class="py-2 px-4">{{ employee.grossSalary }}</td>
                  <td class="py-2 px-4">{{ employee.netSalary }}</td>
                  <td class="py-2 px-4">
                    <div class="gap-2 flex justify-center items-center">
                        <button 
                            class="text-blue-500 border border-blue-400 px-2 py-1 rounded-full hover:bg-blue-100 text-sm flex justify-center items-center gap-1">
                            <b-icon-list-task />Pending
                            </button>
                            <button 
                            class="text-green-500 border border-green-400 px-2 py-1 rounded-full text-sm hover:bg-green-100 flex justify-center items-center gap-1"
                            >
                            <b-icon-check-circle />Processed
                            </button>
                            <!-- <button 
                            v-if="employee.status === 0"
                            class="text-red-500 border border-red-400 px-2 text-sm py-1 rounded-full hover:bg-red-100 flex justify-center items-center gap-1">
                            <b-icon-x-circle /> Reject
                            </button> -->
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
    </div>
  </template>
  
  <script>
  export default {
    name: "paymentdetails",
    data() {
      return {
        currentPage: 1, 
        itemsPerPage: 6,
        employees: [
          {
              "name": "Aarav Sharma",
              "email": "1 Oct. 2024 - 31 Oct. 2024",
              "designation": "Software Engineer",
              "grossSalary": "₹80,000",
              "netSalary": "₹72,000",
          },
          {
              "name": "Isha Verma",
              "email": "1 Dec. 2024 - 31 Dec. 2024",
              "designation": "Data Analyst",
              "grossSalary": "₹60,000",
              "netSalary": "₹54,000",
          },
          {
              "name": "Rahul Khanna",
              "email": "1 Jan. 2024 - 31 Jan. 2024",
              "designation": "Project Manager",
              "grossSalary": "₹1,20,000",
              "netSalary": "₹1,05,000",
          },
          {
              "name": "Sneha Patel",
              "email": "1 Apr. 2024 - 30 Apr. 2024",
              "designation": "UI/UX Designer",
              "grossSalary": "₹70,000",
              "netSalary": "₹63,000",
          },
          {
              "name": "Aditya Singh",
              "email": "15 Jun. 2024 - 15 Jul. 2024",
              "designation": "QA Engineer",
              "grossSalary": "₹55,000",
              "netSalary": "₹50,000",
          },
          {
              "name": "Meera Kapoor",
              "email": "1 Aug. 2024 - 31 Aug. 2024",
              "designation": "HR Manager",
              "grossSalary": "₹85,000",
              "netSalary": "₹77,000",
          },
          {
              "name": "Rajesh Mehta",
              "email": "1 Sep. 2024 - 30 Sep. 2024",
              "designation": "DevOps Engineer",
              "grossSalary": "₹95,000",
              "netSalary": "₹85,000",
          },
          {
              "name": "Nisha Roy",
              "email": "15 Feb. 2024 - 15 Mar. 2024",
              "designation": "Marketing Specialist",
              "grossSalary": "₹50,000",
              "netSalary": "₹45,000",
          },
          {
              "name": "Vikram Malhotra",
              "email": "1 Mar. 2024 - 31 Mar. 2024",
              "designation": "Backend Developer",
              "grossSalary": "₹75,000",
              "netSalary": "₹68,000",
          },
          {
              "name": "Priya Das",
              "email": "1 Jul. 2024 - 31 Jul. 2024",
              "designation": "Frontend Developer",
              "grossSalary": "₹65,000",
              "netSalary": "₹59,000",
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
    },
  };
  </script>
  
  <style lang="scss" scoped>
  .c-salary-page {
    .selection-function {
      background-color: #f9fafb;
      border-bottom: 1px solid #e5e7eb;
    }
  
    table {
      th {
        background-color: #e5e7eb;
        padding: 10px;
      }
      td {
        padding: 10px;
        border: 1px solid #e5e7eb;
      }
    }
  
    .information {
      background-color: #f1f5f9;
      border: 1px solid #e2e8f0;
    }
  }
  </style>
  