<template>
    <div>
      <Loader msg="Loading Leave Request Listings..." v-model="isLoader" />
      <header-component />
      <div style="margin-left: 242px;">
        <div class="max-w-7xl mx-auto p-4 h-screen flex flex-col">
          <div class="flex items-center justify-between bg-white p-4 shadow-md rounded-lg mb-5">
            <div class="flex items-center">
              <span class="font-semibold text-lg text-gray-800">Assets</span>
            </div>
            <div class="flex items-center">
              <button class="py-1 px-3 text-white bg-blue-600 rounded" @click="openModal">
                New Asset
              </button>              
            </div>
          </div>
  
          <div class="bg-white shadow-lg rounded-lg flex-grow flex flex-col">
            <div class="flex-grow overflow-auto">
              <table class="w-full table-auto">
                <thead>
                  <tr class="text-center border-b">
                    <th class="py-3 px-4 text-blue-600">Name</th>
                    <th class="py-6 px-4 text-blue-600 flex items-center gap-1 justify-center cursor-pointer">
                      Category <b-icon-arrow-down-up />
                    </th>
                    <th class="py-3 px-4 text-blue-600">Serial Number</th>
                    <th class="py-3 px-4 text-blue-600">Brand Name</th>
                    <th class="py-3 px-4 text-blue-600">Model Number</th>
                    <th class="py-3 px-4 text-blue-600">RAM</th>
                    <th class="py-3 px-4 text-blue-600">Storage Capacity</th>
                    <th class="py-3 px-4 text-blue-600">IMEI Number</th>
                    <th class="py-3 px-4 text-blue-600">IP Address</th>
                    <th class="py-3 px-4 text-blue-600">Previous State</th>
                    <th class="py-3 px-4 text-blue-600">Tag</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="isLoader">
                      <td colspan="11" class="text-center loader py-4">Loading...</td>
                    </tr>
                    <tr v-else-if="assets.length === 0">
                      <td colspan="11" class="text-center py-4">No leave requests found for the selected date range.</td>
                    </tr>
                    <tr v-for="asset in assets.data" :key="asset.id" class="text-center border-b">
                      <td class="py-3 px-4">{{ asset.name }}</td>
                      <td class="py-3 px-4">{{ asset.category }}</td>
                      <td class="py-3 px-4">{{ asset.serial_number }}</td>
                      <td class="py-3 px-4">{{ asset.brand_name }}</td>
                      <td class="py-3 px-4">{{ asset.model_number }}</td>
                      <td class="py-3 px-4">{{ asset.ram }}</td>
                      <td class="py-3 px-4">{{ asset.storage_capacity }}</td>
                      <td class="py-3 px-4">{{ asset.imei_number }}</td>
                      <td class="py-3 px-4">{{ asset.ip_address }}</td>
                      <td class="py-3 px-4">{{ asset.previous_state }}</td>
                      <td class="py-3 px-4">{{ asset.tag }}</td>
                    </tr>               
                </tbody>
              </table>
              <!-- Pagination Controls -->
              <div class="flex items-center justify-end mt-4">
                <button
                  class="px-4 py-1 bg-blue-600 text-white rounded disabled:opacity-50 mr-5"
                  :disabled="!assets.prev_page_url"
                  @click="fetchAssets(assets.current_page - 1)"
                >
                  Previous
                </button>
                <span>Page {{ assets.current_page }} of {{ assets.last_page }}</span>
                <button
                  class="px-4 py-1 bg-blue-600 text-white rounded disabled:opacity-50 ml-5 mr-5"
                  :disabled="!assets.next_page_url"
                  @click="fetchAssets(assets.current_page + 1)"
                >
                  Next
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- Modal -->
    <div
      v-if="isModalOpen"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
    >
      <div class="bg-white rounded-lg shadow-lg p-6 w-2/3 max-w-4xl h-[80vh] overflow-y-auto relative" style="max-height: calc(100vh - 40px);">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold">Assets</h2>
          <button class="text-3xl font-bold text-gray-600 hover:text-gray-900" @click="closeModal">
            &times;
          </button>
        </div>
        <form @submit.prevent="handleFormSubmit">
          <div class="grid grid-cols-2 gap-4">
            <!-- Brand Name -->
            <div>
              <label class="block text-gray-700">Brand Name<span class="text-red-500">*</span></label>
              <input
                type="text"
                class="border rounded w-full p-2"
                placeholder="Brand Name"
                v-model="formData.brand_name"
              />
            </div>
            <div>
              <label class="block text-gray-700">Name<span class="text-red-500">*</span></label>
              <input
                type="text"
                class="border rounded w-full p-2"
                placeholder="Name"
                v-model="formData.name"
              />
            </div>
            <!-- Model Number -->
            <div>
              <label class="block text-gray-700">Model Number<span class="text-red-500">*</span></label>
              <input
                type="text"
                class="border rounded w-full p-2"
                placeholder="Model Number"
                v-model="formData.model_number"
              />
            </div>
            <div>
              <label class="block text-gray-700">Serial Number</label>
              <input
                type="text"
                class="border rounded w-full p-2"
                placeholder="Serial Number"
                v-model="formData.serial_number"
              />
            </div>
            <!-- RAM -->
            <div>
              <label class="block text-gray-700">RAM</label>
              <input
                type="text"
                class="border rounded w-full p-2"
                placeholder="RAM"
                v-model="formData.ram"
              />
            </div>
            <!-- Storage Capacity -->
            <div>
              <label class="block text-gray-700">Storage Capacity</label>
              <input
                type="text"
                class="border rounded w-full p-2"
                placeholder="Storage Capacity"
                v-model="formData.storage_capacity"
              />
            </div>
            <!-- IMEI Number -->
            <div>
              <label class="block text-gray-700">IMEI Number</label>
              <input
                type="text"
                class="border rounded w-full p-2"
                placeholder="IMEI Number"
                v-model="formData.imei_number"
              />
            </div>
            <!-- IP Address -->
            <div>
              <label class="block text-gray-700">IP Address</label>
              <input
                type="text"
                class="border rounded w-full p-2"
                placeholder="IP Address"
                v-model="formData.ip_address"
              />
            </div>
            <!-- Current Location -->
            <div>
              <label class="block text-gray-700">Current Location</label>
              <input
                type="text"
                class="border rounded w-full p-2"
                placeholder="Current Location"
                v-model="formData.tag"
              />
            </div>
            <!-- Purchased From -->
            <div>
              <label class="block text-gray-700">Purchased From</label>
              <input
                type="text"
                class="border rounded w-full p-2"
                placeholder="Purchased From"
                v-model="formData.purchasedFrom"
              />
            </div>
            <!-- Previous State -->
            <div>
              <label class="block text-gray-700">Previous State</label>
              <input
                type="text"
                class="border rounded w-full p-2"
                placeholder="Previous State"
                v-model="formData.previous_state"
              />
            </div>
            <!-- Purchased Date -->
            <div>
              <label class="block text-gray-700">Purchased Date</label>
              <input
                type="date"
                class="border rounded w-full p-2"
                v-model="formData.purchasedDate"
              />
            </div>
            <!-- Warranty Start Date -->
            <div>
              <label class="block text-gray-700">Warranty Start Date</label>
              <input
                type="date"
                class="border rounded w-full p-2"
                v-model="formData.warrantyStartDate"
              />
            </div>
            <!-- Warranty End Date -->
            <div>
              <label class="block text-gray-700">Warranty End Date</label>
              <input
                type="date"
                class="border rounded w-full p-2"
                v-model="formData.warrantyEndDate"
              />
            </div>
            <!-- Image Upload -->
            <div>
              <label class="block text-gray-700">Upload Image</label>
              <input
                type="file"
                class="border rounded w-full p-2"
                @change="handleFileChange"
              />
            </div>
            <div>
              <label class="block text-gray-700">Select Category</label>
              <select v-model="formData.category" class="w-full border-gray-300 rounded p-2" style="height: 45px;" id="employeeSelect">
                <option disabled value="">Select Category</option>
                <option value="1">Bag</option>
                <option value="2">Laptop</option>
                <option value="3">Mobile</option>
                <option value="4">Tablet</option>
              </select>
            </div>
          </div>
          <div class="w-full flex justify-end gap-4 mt-4">
            <button
              type="button"
              class="bg-gray-300 px-4 py-2 rounded"
              @click="closeModal"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="bg-blue-600 text-white px-4 py-2 rounded"
            >
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  export default {
    name: "assets",
    data() {
      return {
        isLoader: false,
        isModalOpen: false,
        assets: {
          data: [], // Holds the paginated assets data
          current_page: 1,
          last_page: 1,
          prev_page_url: null,
          next_page_url: null,
        },
        perPage: 10, // Items per page
        formData: {
          brand_name: "",
          name: "",
          model_number: "",
          serial_number: "",
          ram: "",
          category: "",
          storage_capacity: "",
          imei_number: "",
          ip_address: "",
          tag: "",
          purchasedFrom: "",
          previous_state: "",
          purchasedDate: "",
          warrantyStartDate: "",
          warrantyEndDate: "",
          assignTo: "",
          previousOwner: "",
          imageUpload: "",
        },        
      };
    },
    methods: {
      openModal() {
        this.isModalOpen = true;
      },
      closeModal() {
        this.isModalOpen = false;
      },
      async handleFormSubmit() {
        try {
          this.isLoader = true; 
          const response = await axios.post(`/api/assets`, this.formData);
          alert(response.data.message);
          this.errorMessage = "";
          console.log(response.data);
          this.resetForm();
          this.closeModal();
        } catch (error) {
          alert(error.response?.data?.message || "An error occurred.")
          this.successMessage = "";
          alert(errorMessage);
          console.error(error);
          this.closeModal();
        } finally {
          this.isLoader = false;
        }
      },
      // Updated fetchEmployees method
      async fetchAssets(page = 1) {
        await axios.get(`/api/assets?page=${page}&per_page=${this.perPage}`)
          // .then((response) => response.json())
          .then((data) => {
            console.log('data=', data?.data);
            this.assets = data?.data;
          })
          .catch((error) => {
            console.error('Error fetching assets:', error);
          });
      },  
      resetForm() {
        // Reset form fields to their initial state
        this.formData = {
          brand_name: "",
          name: "",
          model_number: "",
          serial_number: "",
          category: "",
          ram: "",
          storageCapacity: "",
          imeiNumber: "",
          ipAddress: "",
          tag: "",
          purchasedFrom: "",
          previousState: "",
          purchasedDate: "",
          warrantyStartDate: "",
          warrantyEndDate: "",
          assignTo: "",
          previousOwner: "",
          imageUpload: "",
        };
      },
    },
    mounted() {    
      this.fetchAssets();
    },
  };
  </script>
  
  <style scoped>
  
  </style>
  