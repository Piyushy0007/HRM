<template>
    <div>
      <header-component />

      <div class="c-performance-page" style="margin-left: 242px;">
        <div class="selection-function px-4 pt-2 pb-3 flex justify-between">
          <div class="text-right">
            <span class="block mb-1" style="float:left;">Employee Performance</span>
          </div>
        </div>
    
        <div>
          <table class="w-full border-gray-300">
            <thead>
              <tr class="bg-gray-300">
                <th class="text-center">Employee Name</th>
                <th class="text-center">Department</th>
                <th class="text-center">Performance Score</th>
                <th class="text-center">Feedback</th>
              </tr>
            </thead>
            <tbody>
              <!-- Static Data -->
              <tr>
                <td class="text-center">John Doe</td>
                <td class="text-center">Sales</td>
                <td class="text-center">85%</td>
                <td class="text-center">Excellent communication skills</td>
              </tr>
              <tr>
                <td class="text-center">Jane Smith</td>
                <td class="text-center">HR</td>
                <td class="text-center">78%</td>
                <td class="text-center">Good at team collaboration</td>
              </tr>
              <tr>
                <td class="text-center">Michael Brown</td>
                <td class="text-center">Finance</td>
                <td class="text-center">92%</td>
                <td class="text-center">Outstanding analytical skills</td>
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
            <li>Performance details include score and feedback for each employee.</li>
            <li>Review scores are based on monthly evaluations.</li>
          </ul>
        </div>

      </div>
  
    </div>
  </template>
  
  <script>
  export default {
    name: "performance",
    data() {
      return {
        // Static data handled in the template directly
      };
    },
  };
  </script>
  
  <style lang="scss" scoped>
  .c-performance-page {
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
  