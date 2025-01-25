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
                  <tr class="border-b text-center">
                    <td class="py-3 px-4">John Doe</td>
                    <td class="py-3 px-4">Laptop</td>
                    <td class="py-3 px-4">SN12345</td>
                    <td class="py-3 px-4">Dell</td>
                    <td class="py-3 px-4">Inspiron 15</td>
                    <td class="py-3 px-4">16GB</td>
                    <td class="py-3 px-4">512GB</td>
                    <td class="py-3 px-4">IMEI001234567890</td>
                    <td class="py-3 px-4">192.168.1.1</td>
                    <td class="py-3 px-4">California</td>
                    <td class="py-3 px-4">Office</td>
                  </tr>
                  <tr class="border-b text-center">
                    <td class="py-3 px-4">Jane Smith</td>
                    <td class="py-3 px-4">Mobile</td>
                    <td class="py-3 px-4">SN67890</td>
                    <td class="py-3 px-4">Apple</td>
                    <td class="py-3 px-4">iPhone 13</td>
                    <td class="py-3 px-4">8GB</td>
                    <td class="py-3 px-4">256GB</td>
                    <td class="py-3 px-4">IMEI009876543210</td>
                    <td class="py-3 px-4">192.168.1.2</td>
                    <td class="py-3 px-4">New York</td>
                    <td class="py-3 px-4">Personal</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    <!-- Modal -->
    <div
      v-if="isModalOpen"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
    >
      <div class="bg-white rounded-lg shadow-lg p-6 w-2/3 max-w-4xl h-auto max-h-[85vh] overflow-auto">
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
              <label class="block text-gray-700">Brand Name</label>
              <input
                type="text"
                class="border rounded w-full p-2"
                placeholder="Brand Name"
                v-model="formData.brandName"
              />
            </div>
            <!-- Model Number -->
            <div>
              <label class="block text-gray-700">Model Number</label>
              <input
                type="text"
                class="border rounded w-full p-2"
                placeholder="Model Number"
                v-model="formData.modelNumber"
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
                v-model="formData.storageCapacity"
              />
            </div>
            <!-- IMEI Number -->
            <div>
              <label class="block text-gray-700">IMEI Number</label>
              <input
                type="text"
                class="border rounded w-full p-2"
                placeholder="IMEI Number"
                v-model="formData.imeiNumber"
              />
            </div>
            <!-- IP Address -->
            <div>
              <label class="block text-gray-700">IP Address</label>
              <input
                type="text"
                class="border rounded w-full p-2"
                placeholder="IP Address"
                v-model="formData.ipAddress"
              />
            </div>
            <!-- Current Location -->
            <div>
              <label class="block text-gray-700">Current Location</label>
              <input
                type="text"
                class="border rounded w-full p-2"
                placeholder="Current Location"
                v-model="formData.currentLocation"
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
                v-model="formData.previousState"
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
          </div>
          <div class="flex justify-end gap-4 mt-4">
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
        isModalOpen: false,
        formData: {
          brandName: "",
          modelNumber: "",
          ram: "",
          storageCapacity: "",
          imeiNumber: "",
          ipAddress: "",
          currentLocation: "",
          purchasedFrom: "",
          previousState: "",
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
      handleFormSubmit() {
        console.log(this.formData);
        // Add logic to save form data
        this.closeModal();
      },
    },
    mounted() {
      
      
    },
  };
  </script>
  
  <style scoped>
  
  </style>
  