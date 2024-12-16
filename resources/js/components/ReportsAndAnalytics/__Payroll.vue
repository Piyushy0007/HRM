<template>
    <div>
      <header-component />

      <div  class="c-payroll-page" style="margin-left: 242px;"> 
        <div class="selection-function px-4 pt-2 pb-3 flex justify-between">
          <div class="text-right">
            <span class="block mb-1" style="float:left;">Employee Payroll</span>
          </div>
        </div>
    
        <div>
          <table class="w-full border-gray-300">
            <thead>
              <tr class="bg-gray-300">
                <th class="text-center">Employee Name</th>
                <th class="text-center">Basic Salary</th>
                <th class="text-center">Allowances</th>
                <th class="text-center">Total Salary</th>
              </tr>
            </thead>
            <tbody>
              <!-- Static Data -->
              <tr>
                <td class="text-center">John Doe</td>
                <td class="text-center">₹25,000</td>
                <td class="text-center">₹5,000</td>
                <td class="text-center">₹30,000</td>
              </tr>
              <tr>
                <td class="text-center">Jane Smith</td>
                <td class="text-center">₹30,000</td>
                <td class="text-center">₹7,000</td>
                <td class="text-center">₹37,000</td>
              </tr>
              <tr>
                <td class="text-center">Michael Brown</td>
                <td class="text-center">₹28,000</td>
                <td class="text-center">₹6,000</td>
                <td class="text-center">₹34,000</td>
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
            <li>Payroll details include basic salary, allowances, and total salary for employees.</li>
            <li>Make sure all payroll details are accurate before processing.</li>
          </ul>
        </div>
      </div>  
    </div>
  </template>
  
  <script>
  export default {
    name: "payroll",
    data() {
      return {
        // Static data handled in the template directly
      };
    },
  };
  </script>
  
  <style lang="scss" scoped>
  .c-payroll-page {
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
  