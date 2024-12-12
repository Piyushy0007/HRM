<template>
    <div class="c-salary-page px-4 pb-4" style="margin-left: 240px;">
      <header-component />
  
      <div class="selection-function px-4 pt-2 pb-3 flex justify-between">
        <div class="text-right">
          <span class="text-lg font-bold" style="float:left;">Total Salaries</span>
        </div>
      </div>
  
      <div>
        <table class="w-full border-gray-300">
          <thead>
            <tr class="bg-gray-300">
              <th class="text-center">Employee Name</th>
              <th class="text-center">Amount</th>
              <th class="text-center">Company Name</th>
            </tr>
          </thead>
          <tbody>
            <!-- Static Data -->
            <tr>
              <td class="text-center">John Doe</td>
              <td class="text-center">$3,500</td>
              <td class="text-center">ABC Corp</td>
            </tr>
            <tr>
              <td class="text-center">Jane Smith</td>
              <td class="text-center">$4,200</td>
              <td class="text-center">XYZ Ltd</td>
            </tr>
            <tr>
              <td class="text-center">Michael Brown</td>
              <td class="text-center">$2,800</td>
              <td class="text-center">Tech Solutions</td>
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
          <li>All salary details are listed here.</li>
        </ul>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "salary",
    data() {
      return {
        // Static data handled in the template directly
      };
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
  