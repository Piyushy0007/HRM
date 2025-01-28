<template>
    <div class="c-request-form">
      <header-component />
      <div style="margin-left: 242px;">
        <div class="max-w-7xl mx-auto p-4 h-screen flex flex-col">
          <div class="flex items-center justify-between bg-white p-4 shadow-md rounded-lg mb-5">
            <div class="flex items-center">
              <span class="font-semibold text-lg text-gray-800">Create Request List</span>
            </div>
  
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
                  @change="fetchCreateRequests"
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
                  <tr class="text-center border-b">
                    <th class="py-3 px-4 text-blue-600">Employee</th>
                    <th class="py-3 px-4 text-blue-600">Request Type</th>
                    <th class="py-3 px-4 text-blue-600">Reason</th>
                    <th class="py-3 px-4 text-blue-600">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Iterate over the employeesData array to display the data -->
                  <tr v-for="(employee, index) in employeesData" :key="index" class="border-b">
                    <td class="text-center py-4">{{ employee.selected_employee }}</td>
                    <td class="text-center py-4">{{ employee.request_type }}</td>
                    <td class="text-center py-4">{{ employee.reason }}</td>
                    <!-- <td class="text-center py-4">
                      <img :src="`/storage/${employee.image_path}`" alt="Request Image" class="w-12 h-12 object-cover rounded" />
                    </td> -->
                    <td class="text-center py-4">
                      <button
                        @click="viewRequest(employee)"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
                      >
                        View
                      </button>
                    </td> <!-- Action Button -->
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        employeesData: [],  // Store data fetched from API
        error: null,
        isLoader: false,
        fromDate: '',
        toDate: '',
        itemsPerPage: 6, // You can adjust the default items per page as per your needs
      };
    },
    methods: {
      // Method to fetch create requests from the API
      async fetchCreateRequests() {
        this.isLoader = true;
        try {
          const response = await axios.get("/api/create-requests", {
            params: {
              fromDate: this.fromDate,
              toDate: this.toDate
            }
          });
          if (response.status === 200) {
            this.employeesData = response.data;
            console.log(this.employeesData, "Fetched data successfully");
          } else {
            console.error("Unexpected API response:", response);
            this.employeesData = [];
          }
        } catch (err) {
          console.error("Error fetching data:", err);
          this.error = "Failed to load data.";
        } finally {
          this.isLoader = false;
        }
      },
  
      // Action for "View" button (This is a placeholder for now)
      viewRequest(employee) {
        console.log("Viewing request for employee:", employee);
      }
    },
    mounted() {
      this.fetchCreateRequests(); // Fetch the data when the component is mounted
    },
  };
  </script>
  
  <style scoped>
  .c-request-form {
    font-family: Arial, sans-serif;
  }
  </style>
  