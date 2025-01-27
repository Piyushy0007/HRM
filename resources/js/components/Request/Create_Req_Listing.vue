<template>
    <div class="c-request-form">
      <header-component />
      <div style="margin-left: 242px;">
        <div class="max-w-7xl mx-auto p-4 h-screen flex flex-col">
          <div class="flex items-center justify-between bg-white p-4 shadow-md rounded-lg mb-5">
            <div class="flex items-center">
              <span class="font-semibold text-lg text-gray-800">Create Requests</span>
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
                  <tr class="text-center border-b">
                    <th class="py-3 px-4 text-blue-600">Employee</th>
                    <th class="py-3 px-4 text-blue-600">Request Type</th>
                    <th class="py-3 px-4 text-blue-600">Reason</th>
                    <th class="py-3 px-4 text-blue-600">Action</th> <!-- Added Action column -->
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(request, index) in requestsData" :key="index" class="border-b">
                    <td class="text-center py-4">{{ request.selectedEmployee }}</td>
                    <td class="text-center py-4">{{ request.requestType }}</td>
                    <td class="text-center py-4">{{ request.reason }}</td>
                    <td class="text-center py-4">
                      <button
                        @click="viewRequest(request)"
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
  export default {
    data() {
      return {
        requestsData: [
          { selectedEmployee: "John Doe", requestType: "Change of email", reason: "Email update required" },
          { selectedEmployee: "Jane Smith", requestType: "Profile update", reason: "Profile details update" },
        ], 
        fromDate: null, // Store from date
      toDate: null,
      itemsPerPage: 6, // Default items per page
      error: null, // For error handling
      isLoader: false,
      };
    },
    methods: {
      viewRequest(request) {
        alert(`Viewing details for: ${request.selectedEmployee}`);
      },

      async fetchLeaveRequests() {
        if (this.fromDate && this.toDate) {
          this.error = null;
          this.isLoader = true;
        try {
          const response = await axios.get(`/api/create-requests`) 
          console.log("API Response:", response.data);
          if (response.status === 200 && Array.isArray(response.data)) {
            this.employees = response.data;
            console.log(response.data)
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
    }
    
  },
  };

  </script>
  
  <style scoped>
  .c-request-form {
    font-family: Arial, sans-serif;
  }
  </style>
  