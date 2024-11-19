<template>
    <div class="c-leave-page px-4 pb-4 w-80" style="margin-right: 1vw;">
      <header-component />
  
      <div class="selection-function px-4 pt-2 pb-3 flex justify-between">
        <div class="text-right">
          <span class="block mb-1" style="float:left;">Employee Leave Details</span>
        </div>
      </div>
  
      <div class="px-4">
        <table class="w-full border-gray-300">
          <thead>
            <tr class="bg-gray-300">
              <th class="text-center">Employee Name</th>
              <th class="text-center">Reason</th>
              <th class="text-center">Status</th>
            </tr>
          </thead>
          <tbody>
            <!-- Static Data -->
            <tr>
              <td class="text-center">John Doe</td>
              <td class="text-center">Family Emergency</td>
              <td class="text-center">Approved</td>
            </tr>
            <tr>
              <td class="text-center">Jane Smith</td>
              <td class="text-center">Medical Leave</td>
              <td class="text-center">Pending</td>
            </tr>
            <tr>
              <td class="text-center">Michael Brown</td>
              <td class="text-center">Vacation</td>
              <td class="text-center">Rejected</td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <div class="information mx-4 mt-5 p-4 rounded-lg">
        <div class="flex justify-between items-center mb-2">
          <h4 class="text-2xl font-semibold">Information</h4>
          <a href="#" class="text-sm">Help on this topic <strong>More</strong></a>
        </div>
        <ul class="list-inside">
          <li>Leave requests and their status are displayed here.</li>
        </ul>
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
  