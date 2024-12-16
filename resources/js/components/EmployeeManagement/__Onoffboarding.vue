<template>
    <div>
      <header-component />

      <div class="c-attendance-page" style="margin-left: 242px;">
        <div class="selection-function px-2 pt-2 pb-3 flex justify-between">
          <div class="text-right">
            <span class="block mb-1" style="float:left;">Monthly Attendance</span>
          </div>
        </div>
    
        <div>
          <table class="w-full border-gray-300">
            <thead>
              <tr class="bg-gray-300">
                <th class="text-center">Employee Name</th>
                <th class="text-center">Present</th>
                <th class="text-center">Absent</th>
              </tr>
            </thead>
            <tbody>
              <!-- Static Data -->
              <tr>
                <td class="text-center">John Doe</td>
                <td class="text-center">20</td>
                <td class="text-center">2</td>
              </tr>
              <tr>
                <td class="text-center">Jane Smith</td>
                <td class="text-center">18</td>
                <td class="text-center">4</td>
              </tr>
              <tr>
                <td class="text-center">Michael Brown</td>
                <td class="text-center">22</td>
                <td class="text-center">0</td>
              </tr>
            </tbody>
          </table>
        </div>
    
        <div class="information mx-2 mt-5 p-4 rounded-lg">
          <div class="flex justify-between items-center mb-2">
            <h4 class="text-2xl font-semibold">Information</h4>
            <a href="#" class="text-sm">Help on this topic <strong>More</strong></a>
          </div>
          <ul class="list-inside">
            <li>Attendance details for employees are displayed month-wise.</li>
          </ul>
        </div>

      </div>
  
    </div>
  </template>
  
  <script>
  export default {
    name: "attendance",
    data() {
      return {
        // Static data handled in the template directly
      };
    },
  };
  </script>
  
  <style lang="scss" scoped>
  .c-attendance-page {
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
  