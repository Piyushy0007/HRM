<template>
  <div class="c-request-form relative">
    <header-component />
    <div style="margin-left: 242px;">
      <div class="max-w-7xl mx-auto p-4 h-screen flex flex-col">
        <div class="flex items-center justify-between bg-white p-4 shadow-md rounded-lg mb-5">
          <div class="flex items-center">
            <span class="font-semibold text-lg text-gray-800">Create Request List</span>
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
            <div class="flex items-center">
              <label for="fromDate" class="text-gray-600 mr-2">From</label>
              <input type="date" id="fromDate" v-model="fromDate" class="border border-gray-300 rounded-md px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" />
            </div>
            <div class="flex items-center">
              <label for="toDate" class="text-gray-600 mr-2">To</label>
              <input type="date" id="toDate" v-model="toDate" @change="fetchCreateRequests" class="border border-gray-300 rounded-md px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" />
            </div>
          </div>
        </div>
        <div class="bg-white shadow-lg rounded-lg flex-grow flex flex-col">
          <div class="flex-grow overflow-auto">
            <table class="min-w-full border border-custom-border rounded-lg border-collapse">
              <thead class="bg-custom-bg_table_head_primary border-custom-border">
                <tr class="text-gray-600 border border-custom-border rounded-lg">
                  <th class=" p-2 border border-custom-border">Employee</th>
                  <th class=" p-2 border border-custom-border">Request Type</th>
                  <th class=" p-2 border border-custom-border">Reason</th>
                  <th class=" p-2 border border-custom-border">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(employee, index) in employeesData" :key="index" class="border-b">
                  <td class=" p-2 border border-custom-border">{{ employee.selected_employee }}</td>
                  <td class=" p-2 border border-custom-border">{{ employee.request_type }}</td>
                  <td class=" p-2 border border-custom-border">{{ employee.reason }}</td>
                  <td class=" p-2 border border-custom-border">
                    <button @click="openModal" class="bg-custom-button text-white px-3 py-1 text-sm rounded-md hover:bg-blue-600">
                      View
                    </button>
                    
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Modal Component -->
    <div v-if="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white p-6 rounded-lg shadow-lg w-1/3 relative">
       <div class="flex justify-end">
          <button @click="closeModal" class="text-gray-500 hover:text-gray-600">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>

       </div>
        <h2 class="text-xl font-semibold mb-4">Update Status</h2>
        <label class="block text-gray-700 font-semibold mt-4 mb-2">Status:</label>
        <select v-model="selectedStatus" class="border rounded px-3 py-2 w-full mb-4">
          <option value="pending">Pending</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>
        <div class="mt-4 flex justify-end space-x-2">

          <button @click="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Close</button>
          <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Update Status</button>
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
      modal: false,
    };
  },
  methods: {
    async fetchCreateRequests() {
      this.isLoader = true;
      try {
        const response = await axios.get("/api/create-requests", {
          params: { fromDate: this.fromDate, toDate: this.toDate }
        });
        if (response.status === 200) {
          this.employeesData = response.data;
        } else {
          this.employeesData = [];
        }
      } catch (err) {
        this.error = "Failed to load data.";
      } finally {
        this.isLoader = false;
      }
    },
    openModal() {
      this.modal = true;
    },
    closeModal() {
      this.modal = false;
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