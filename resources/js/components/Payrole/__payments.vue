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
              <span class="font-semibold text-lg text-gray-800">Salary Management</span>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-white shadow-lg rounded-lg flex-grow flex flex-col">
          <!-- Table Header -->
          <div class="flex-grow overflow-auto">
            <table class="w-full table-auto">
              <thead>
                <tr class="text-left border-b">
                  <th class="py-3 px-4 text-blue-600">Name</th>
                  <th class="py-3 px-4 text-blue-600">Email</th>
                  <th class="py-3 px-4 text-blue-600">Designation</th>
                  <th class="py-3 px-4 text-blue-600">Gross Salary</th>
                  <th class="py-3 px-4 text-blue-600">Net Salary</th>
                  <th class="py-3 px-4 text-blue-600">Payday</th>
                  <th class="py-3 px-4 flex justify-center text-blue-600">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(employee, index) in paginatedData" :key="index" class="border-b ">
                  <td class="py-3 px-4">{{ employee.name }}</td>
                  <td class="py-2 px-4">{{ employee.email }}</td>
                  <td class="py-2 px-4">{{ employee.designation }}</td>
                  <td class="py-2 px-4">{{ employee.grossSalary }}</td>
                  <td class="py-2 px-4">{{ employee.netSalary }}</td>
                  <td class="py-2 px-4">{{ employee.payDay }}</td>
                  <td class="py-2 px-4">
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
  