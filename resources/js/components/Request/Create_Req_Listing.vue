<template>
  <div class="c-request-form">
    <header-component />
    <div style="margin-left: 242px;">
      <div class="w-full mx-auto p-2 h-screen flex flex-col">
        <div class="flex items-center justify-between bg-white p-3 mb-2 flex-wrap ">
          <div class="flex items-center">
            <span class="font-semibold text-lg text-gray-800">Create Requests List</span>
          </div>

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
        <div class="flex-grow flex flex-col">
          <div class="flex-grow overflow-auto">
            <table class="w-full border border-custom-border">
              <thead class="bg-custom-bg_table_head_primary border-custom-border">
                <tr class="text-gray-600 border border-custom-border rounded-lg text-left">

                  <th class="text-center p-2 border border-custom-border">Employee</th>
                  <th class="text-center p-2 border border-custom-border">Request Type</th>
                  <th class="text-center p-2 border border-custom-border">Reason</th>
                  <th class="text-center p-2 border border-custom-border">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="isLoader">
                  <td colspan="7" class="text-center loader py-4">Loading...</td>
                </tr>
                <tr v-else-if="employeesData.length === 0">
                  <td colspan="7" class="text-center py-4">No leave requests found for the selected date range.</td>
                </tr>
                <!-- Iterate over the employeesData array to display the data -->
                <tr  v-else v-for="(employee, index) in employeesData" :key="index" class="border-b">
                  <td class="text-center p-2 border border-custom-border">{{ employee.selected_employee }}</td>
                  <td class="text-center p-2 border border-custom-border">{{ employee.request_type }}</td>
                  <td class="text-center p-2 border border-custom-border">{{ employee.reason }}</td>
                  <!-- <td class="text-center p-2 border border-custom-border">
                    <img :src="`/storage/${employee.image_path}`" alt="Request Image" class="w-12 h-12 object-cover rounded" />
                  </td> -->
                  <td class="text-center p-2 border border-custom-border">
                    <button @click="openModal" class="bg-custom-button text-white px-3 py-1 text-sm rounded-md hover:bg-blue-600">
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
      employeesData: [],  
      error: null,
      isLoader: false,
      fromDate: '',
      toDate: '',
      itemsPerPage: 6, 
    };
  },
  methods: {
    async fetchCreateRequests() {
      this.isLoader = true;
      try {
        const response = await axios.get("/api/create-requests");
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

    viewRequest(employee) {
      console.log("Viewing request for employee:", employee);
    }
  },
  mounted() {
    this.fetchCreateRequests(); 
  },
};
</script>

<style scoped>
.c-request-form {
  font-family: Arial, sans-serif;
}
</style>
