<template>
    <div class="c-hr-analytics-page px-4 pb-4 w-80" style="margin-right: 1vw;">
      <header-component />
  
      <div class="selection-function px-4 pt-2 pb-3 flex justify-between">
        <div class="text-right">
          <span class="block mb-1" style="float:left;">HR Analytics</span>
        </div>
      </div>
  
      <div class="px-4">
        <table class="w-full border-gray-300">
          <thead>
            <tr class="bg-gray-300">
              <th class="text-center">Metric Name</th>
              <th class="text-center">Value</th>
              <th class="text-center">Description</th>
            </tr>
          </thead>
          <tbody>
            <!-- Static Data -->
            <tr>
              <td class="text-center">Employee Turnover Rate</td>
              <td class="text-center">12%</td>
              <td class="text-center">Percentage of employees leaving the company.</td>
            </tr>
            <tr>
              <td class="text-center">Average Tenure</td>
              <td class="text-center">4.2 years</td>
              <td class="text-center">Average time employees stay in the company.</td>
            </tr>
            <tr>
              <td class="text-center">Absenteeism Rate</td>
              <td class="text-center">8%</td>
              <td class="text-center">Percentage of workdays missed by employees.</td>
            </tr>
            <tr>
              <td class="text-center">Diversity Ratio</td>
              <td class="text-center">45:55 (Male:Female)</td>
              <td class="text-center">Gender ratio in the workforce.</td>
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
          <li>Key HR metrics help track organizational health and employee satisfaction.</li>
          <li>Monitor trends and take timely actions for improvement.</li>
        </ul>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "hr-analytics",
    data() {
      return {
        // Static data handled in the template directly
      };
    },
  };
  </script>
  
  <style lang="scss" scoped>
  .c-hr-analytics-page {
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
  