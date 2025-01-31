<template>
    <div>
      <header-component />
      <div style="margin-left: 242px;">
        <div class="max-w-7xl mx-auto p-2 h-screen flex flex-col">
          <div
            class="flex items-center justify-between bg-custom-light_grey p-2 rounded-lg mb-2"
          >
            <!-- Left Section -->
            <div class="flex items-center justify-between p-3 flex-wrap w-full ">
              <div class="flex items-center">
                <span class="font-semibold text-lg text-gray-800">Salary Management</span>
              </div>
              <div class="flex items-center flex-grow mx-12 relative">
                <input
                  type="text"
                  placeholder="Search..."
                  class="w-full border border-gray-300 rounded-md pl-3 pr-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                  style="height: 36px;"
                />
                <div>
                  <font-awesome-icon
                  icon="search"
                  class="absolute right-0 mr-2 transform -translate-y-1/2 top-1/2 cursor-pointer text-gray-500"
                  style="font-size: 20px"
                  />
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <div class="flex items-center">
                  <label for="fromDate" class="text-gray-600 mr-2">From</label>
                  <input type="date" id="fromDate" class="border border-custom-border rounded-md px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div class="flex items-center">
                  <label for="toDate" class="text-gray-600 mr-2">To</label>
                  <input type="date" id="toDate"  class="border border-custom-border rounded-md px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
              </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-white shadow-lg rounded-lg flex-grow flex flex-col">
          <!-- Table Header -->
          <div class="flex-grow overflow-auto">
            <table class="min-w-full border border-custom-border rounded-lg border-collapse">
              <thead class="bg-custom-bg_table_head_primary border-custom-border">
                <tr class="text-left text-gray-600 border border-custom-border rounded-lg">
                  <th class="p-3 border border-custom-border">Name</th>
                  <th class="p-3 border border-custom-border">Email</th>
                  <th class="p-3 border border-custom-border">Designation</th>
                  <th class="p-3 border border-custom-border">Gross Salary</th>
                  <th class="p-3 border border-custom-border">Net Salary</th>
                  <th class="p-3 border border-custom-border">Payday</th>
                  <th class="py-3 px-4 flex justify-center text-blue-600">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(employee, index) in paginatedData" :key="index" class="p-3 border border-custom-border">
                  <td class="p-3 border border-custom-border">{{ employee.name }}</td>
                  <td class="p-3 border border-custom-border">{{ employee.email }}</td>
                  <td class="p-3 border border-custom-border">{{ employee.designation }}</td>
                  <td class="p-3 border border-custom-border">{{ employee.grossSalary }}</td>
                  <td class="p-3 border border-custom-border">{{ employee.netSalary }}</td>
                  <td class="p-3 border border-custom-border">{{ employee.payDay }}</td>
                  <td class="p-3 border border-custom-border">
                    <div class="gap-2 flex justify-center items-center">
                        <button class="text-blue-500 border border-blue-400 px-2 py-1 rounded-full hover:bg-blue-100 text-sm flex justify-center items-center gap-1">
                        <font-awesome-icon :icon="['fas', 'coins']" />
                        TDS
                        </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>            
          </div>
    
          <!-- Pagination Section -->

          <div class="flex items-center justify-end mt-2 mb-2">
            <button
              class="px-4 py-1 bg-blue-600 text-white rounded disabled:opacity-50 mr-5"
              @click="prevPage"
              :disabled="currentPage === 1"
            >
              Previous
            </button>
            <span> {{ page }}</span>
            <button
              class="px-4 py-1 bg-blue-600 text-white rounded disabled:opacity-50 ml-5 mr-5"
              @click="nextPage"
              :disabled="currentPage === totalPages"
            >
              Next
            </button>
          </div>
        
        </div>
      </div>
    </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "Salary",
    data() {
      return {
        currentPage: 1, 
        itemsPerPage: 6,
        employees: [
          {
              "name": "Aarav Sharma",
              "email": "aarav.sharma@example.com",
              "designation": "Software Engineer",
              "grossSalary": "₹80,000",
              "netSalary": "₹72,000",
              "payDay": "25th of every month"
          },
          {
              "name": "Isha Verma",
              "email": "isha.verma@example.com",
              "designation": "Data Analyst",
              "grossSalary": "₹60,000",
              "netSalary": "₹54,000",
              "payDay": "30th of every month"
          },
          {
              "name": "Rahul Khanna",
              "email": "rahul.khanna@example.com",
              "designation": "Project Manager",
              "grossSalary": "₹1,20,000",
              "netSalary": "₹1,05,000",
              "payDay": "28th of every month"
          },
          {
              "name": "Sneha Patel",
              "email": "sneha.patel@example.com",
              "designation": "UI/UX Designer",
              "grossSalary": "₹70,000",
              "netSalary": "₹63,000",
              "payDay": "27th of every month"
          },
          {
              "name": "Aditya Singh",
              "email": "aditya.singh@example.com",
              "designation": "QA Engineer",
              "grossSalary": "₹55,000",
              "netSalary": "₹50,000",
              "payDay": "26th of every month"
          },
          {
              "name": "Meera Kapoor",
              "email": "meera.kapoor@example.com",
              "designation": "HR Manager",
              "grossSalary": "₹85,000",
              "netSalary": "₹77,000",
              "payDay": "25th of every month"
          },
          {
              "name": "Rajesh Mehta",
              "email": "rajesh.mehta@example.com",
              "designation": "DevOps Engineer",
              "grossSalary": "₹95,000",
              "netSalary": "₹85,000",
              "payDay": "29th of every month"
          },
          {
              "name": "Nisha Roy",
              "email": "nisha.roy@example.com",
              "designation": "Marketing Specialist",
              "grossSalary": "₹50,000",
              "netSalary": "₹45,000",
              "payDay": "30th of every month"
          },
          {
              "name": "Vikram Malhotra",
              "email": "vikram.malhotra@example.com",
              "designation": "Backend Developer",
              "grossSalary": "₹75,000",
              "netSalary": "₹68,000",
              "payDay": "27th of every month"
          },
          {
              "name": "Priya Das",
              "email": "priya.das@example.com",
              "designation": "Frontend Developer",
              "grossSalary": "₹65,000",
              "netSalary": "₹59,000",
              "payDay": "28th of every month"
          }
      ],
      prev_page_url: null,
      next_page_url: null,
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
  