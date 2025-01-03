<template>
    <div class="c-leave-page">
      <header-component />
      <div style="margin-left: 242px;">
        <div class="p-8 bg-gray-50 min-h-screen">
          <!-- Leave Form -->
          <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4 text-blue-600">Leave Form</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Left Column -->
              <div>
                <div class="mb-4">
                  <label class="block text-black-600 text-sm mb-1">Email</label>
                  <input type="email" class="w-full border-gray-300 rounded-lg text-sm p-2 border" placeholder="example@email.com"  />
                </div>
                <div class="mb-4">
                  <label class="block text-black-600 text-sm mb-1">From Date</label>
                  <input type="text" class="w-full border-gray-300 rounded-lg text-sm p-2 border" placeholder="dd/mm/yyyy"  />
                </div>
              </div>
              <!-- Right Column -->
              <div>
                <div class="mb-4">
                  <label class="block text-black-600 text-sm mb-1">Submitted Date</label>
                  <input type="text" class="w-full border-gray-300 rounded-lg text-sm p-2 border" placeholder="dd/mm/yyyy"  />
                </div>
                <div class="mb-4">
                  <label class="block text-black-600 text-sm mb-1">Leave Type</label>
                  <select class="w-full border-gray-300 rounded-lg text-sm p-2 ">
                    <option>Casual</option>
                    <option>Medical</option>
                    <option>General</option>
                  </select>
                </div>
                <div class="mb-4">
                  <label class="block text-black-600 text-sm mb-1">To Date</label>
                  <input type="text" class="w-full border-gray-300 rounded-lg text-sm p-2 border" placeholder="dd/mm/yyyy"  />
                </div>
              </div>
            </div>
          </div>
        
          <!-- Reason -->
          <div class="bg-white shadow-md rounded-lg p-6 mt-6">
            <h2 class="text-lg font-semibold mb-4 text-blue-600">Reason</h2>
            <textarea class="w-full border-gray-300 rounded-lg text-sm p-2 border" placeholder="Reason for Leave" rows="3"></textarea>
          </div>
        
          <!-- Manager Approval -->
          <div class="bg-white shadow-md rounded-lg p-6 mt-6">
            <div class="flex items-center space-x-4">
              <button class="bg-green-500 text-white px-4 py-1 rounded-lg">Submit</button>
              <button class="bg-red-500 text-white px-4 py-1 rounded-lg">Cancel</button>
            </div>
          </div>
        </div>
        

      </div>
  
    </div>
  </template>
  
  <script>
  export default {
    name: "leave",
    data() {
      return {
        // Static data handled in the template directly
      };
    },
  };
  </script>
  
  <style lang="scss" scoped>
  .c-leave-page {
    .selection-function {
      background-color: #f9fafb;
      border-bottom: 1px solid #e5e7eb;
      /* margin-left: 220px; */
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
  