<template>
  <div>
    <header-component />
    <div style="margin-left: 242px;">
      <div class="p-6 bg-gray-100">
        <h2 class="text-xl mb-4"> <span class="font-bold"> Pay Period:</span> <span>20 Mar,2022 - 19 Apr,2022</span> <span class="bg-green-600 text-white text-sm">Processed</span></h2>
        <div class="flex flex-col md:flex-row gap-2 w-full">
          <!-- Payroll Summary -->
          <div class="bg-white w-full md:w-1/2 rounded shadow p-4 mb-4">
            <h2 class="text-xl font-bold mb-4">Payroll Summary</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-md">
              <div>
                <p class="flex"><span class="w-40 font-semibold text-end" style="text-align: end;">Payroll Type:</span><span class="ml-4">Monthly</span></p>
                <p class="flex"><span class="w-40 font-semibold" style="text-align: end;">Batch:</span><span class="ml-4">Full Time Batch</span></p>
                <p class="flex"><span class="w-40 font-semibold" style="text-align: end;">Employee Count:</span><span class="ml-4">4</span></p>
                <p class="flex"><span class="w-40 font-semibold" style="text-align: end;">Total Days:</span><span class="ml-4">28</span></p>
                <p class="flex"><span class="w-40 font-semibold" style="text-align: end;">Working Days:</span><span class="ml-4">28</span></p>
                <p class="flex"><span class="w-40 font-semibold" style="text-align: end;">Weekly Offs:</span><span class="ml-4">0</span></p>
                <p class="flex"><span class="w-40 font-semibold" style="text-align: end;">Processed On:</span><span class="ml-4">Feb 04, 2022</span></p>
                <p class="flex"><span class="w-40 font-semibold" style="text-align: end;">Processed by:</span><span class="ml-4">Mahabalesvara</span></p>
              </div>
              <div>
                <p class="flex"><span class="w-40 font-semibold" style="text-align: end;">Net Amount:</span><span class="ml-4">₹1,85,054.78</span></p>
                <p class="flex"><span class="w-40 font-semibold" style="text-align: end;">Total TDS:</span><span class="ml-4">₹10,420.00</span></p>
                <p class="flex"><span class="w-40 font-semibold" style="text-align: end;">Deductions:</span><span class="ml-4">₹8,515.23</span></p>
                <p class="flex"><span class="w-40 font-semibold" style="text-align: end;">Gross:</span><span class="ml-4">₹2,03,990.00</span></p>
                <p class="flex"><span class="w-40 font-semibold" style="text-align: end;">Loss Of Pay:</span><span class="ml-4">₹0.00</span></p>
              </div>
            </div>
          </div>
          <!-- Direct Deposit -->
          <div class="bg-white w-full md:w-1/2 rounded shadow p-4 mb-4">
            <h2 class="text-xl font-bold mb-4">Direct Deposit</h2>
            <div class="flex items-center flex-col">
              <p class="text-black-600 text-center text-md mx-12 mb-2">Access this service for a hassle-free way to deposit salary to employees' bank accounts.</p>
              <img src="https://via.placeholder.com/100" alt="Direct Deposit" class="w-25 h-25 mr-4" />
            </div>
            <div class="mt-4 flex justify-center">
              <button class="bg-green-500 text-white py-2 px-4 rounded mr-2">Goto Direct Deposit</button>
              <button class="bg-gray-800 text-white py-2 px-4 rounded">How it works</button>
            </div>
          </div>
        </div>
  
        <div class="flex flex-col md:flex-row w-full gap-2 items-stretch">
          <!-- Post Payroll Checklist -->
          <div class="bg-white w-full md:w-1/2 rounded shadow p-4 mb-4">
            <h2 class="text-xl font-bold mb-4">Post Payroll Checklist</h2>
            <ul class="list-none ml-6 text-gray-600 text-md">
              <div class="flex gap-4 mb-4">
                <div class="w-5 flex items-center justify-center h-5 bg-blue-500 rounded-full"> <font-awesome-icon icon="check" class="text-white" style="font-size: 10px;" /> </div>
                <li>Payslips Released on Feb 04, 2022</li>
              </div>
              <div class="flex gap-4 mb-4">
                <div class="w-5 flex items-center justify-center h-5 bg-blue-500 rounded-full"> <font-awesome-icon icon="check" class="text-white" style="font-size: 10px;" /> </div>
                <li>Pay Date: Feb 04, 2022</li>
              </div>
              
              <div class="flex gap-4 mb-4">
                <div class="w-5 flex items-center justify-center h-5 bg-orange-500 rounded-full"> <font-awesome-icon icon="exclamation" class="text-white" style="font-size: 10px;" /> </div>
                <li>TDS Payment Tracking - Due Date: Apr 07, 2022</li>
              </div>
              <div class="flex gap-4 mb-4">
                <div class="w-5 flex items-center justify-center h-5 bg-orange-500 rounded-full"> <font-awesome-icon icon="exclamation" class="text-white" style="font-size: 10px;" /> </div>
                <li>EPF Transfer & Filing Check - Due Date: Apr 15, 2022</li>
              </div>
            </ul>
          </div>
          <!-- Second Parent Div -->
        <div class="w-full md:w-1/2 flex flex-col md:flex-row mb-4">
          <!-- First Half of Second Parent Div -->
          <div class="w-full md:w-1/2  bg-white rounded shadow p-4">
            <h2 class="text-xl font-bold mb-4">TDS Summary</h2>
            <div class="text-md">
              <p><span class="font-semibold">Income Tax:</span> ₹18,120.50</p>
              <p><span class="font-semibold">Surcharge:</span> ₹0.00</p>
              <p><span class="font-semibold">CESS:</span> ₹724.82</p>
              <p><span class="font-semibold">Total TDS:</span> ₹18,846.32</p>
            </div>
          </div>
          <!-- Second Half of Second Parent Div -->
          <div class="w-full md:w-1/2 bg-white rounded shadow p-4">
            <h2 class="text-xl font-bold mb-4">PF Summary</h2>
            <div class="text-md">
              <p><span class="font-semibold">Employee Contribution (EE):</span> ₹10,440.00</p>
              <p><span class="font-semibold">Employer Contribution (ER):</span> ₹11,310.00</p>
              <p><span class="font-semibold">Total PF:</span> ₹21,750.00</p>
            </div>
          </div>
        </div>  
        </div>
      </div>
      <div class="p-4">
        <table class="min-w-full border table-auto border-gray-300 text-sm text-gray-800">
          <thead class="bg-gray-200">
            <tr class="text-center">
              <th class="border border-gray-300 px-4 py-2 ">Quarter(s)</th>
              <th class="border border-gray-300 px-4 py-2 ">Receipt Numbers of original quarterly statements of TDS under sub-section (3) of Section 200</th>
              <th class="border border-gray-300 px-4 py-2 ">Amount paid/credited</th>
              <th class="border border-gray-300 px-4 py-2 ">Amount of tax deducted (Rs.)</th>
              <th class="border border-gray-300 px-4 py-2 ">Amount of tax deposited / remitted (Rs.)</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in tableData" :key="row.quarter" :class="row.quarter === 'Total (Rs.)' ? 'font-bold bg-gray-100' : ''">
              <td class="border border-gray-300 px-4 py-2">{{ row.quarter }}</td>
              <td class="border border-gray-300 px-4 py-2 text-center">
                <div v-if="row.receiptNumbers.length">
                  <div
                    v-for="(receipt, index) in row.receiptNumbers"
                    :key="index"
                    class="bg-yellow-300 text-center rounded px-2 py-1 mb-1 inline-block text-sm"
                  >
                    {{ receipt }}
                  </div>
                </div>
              </td>
              <td class="border border-gray-300 px-4 py-2 text-right">{{ formatNumber(row.amountPaid) }}</td>
              <td class="border border-gray-300 px-4 py-2 text-right">{{ formatNumber(row.taxDeducted) }}</td>
              <td class="border border-gray-300 px-4 py-2 text-right">{{ formatNumber(row.taxRemitted) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="p-8 max-w-2xl mx-auto bg-white shadow-md rounded-md">
        <!-- Header Section -->
        <div class="flex justify-between items-center border-b pb-4 mb-4">
          <div>
            <h1 class="text-lg font-semibold">Manish Patel</h1>
            <p class="text-sm text-gray-500">Emp. ID: 1</p>
          </div>
          <div class="text-right">
            <p class="text-xl font-semibold text-gray-800">₹6,347.00</p>
            <p class="text-sm text-gray-500">Net Pay</p>
          </div>
        </div>
    
        <!-- Earnings Section -->
        <div class="mb-6">
          <h2 class="text-md font-semibold text-gray-700 mb-2">Earnings</h2>
          <ul>
            <li class="flex justify-between text-sm text-gray-600 mb-1">
              <span>Children Education Allowance</span>
              <span>₹1,000.00</span>
            </li>
            <li class="flex justify-between text-sm text-gray-600 mb-1">
              <span>Helper Allowance</span>
              <span>₹1,500.00</span>
            </li>
            <li class="flex justify-between text-sm text-gray-600 mb-1">
              <span>Travelling Allowance</span>
              <span>₹25,000.00</span>
            </li>
            <li class="flex justify-between text-sm text-gray-600 mb-1">
              <span>Canteen Allowance</span>
              <span>₹3,000.00</span>
            </li>
            <li class="flex justify-between text-sm text-gray-600 mb-1">
              <span>Food</span>
              <span>₹2,000.00</span>
            </li>
            <li class="flex justify-between text-sm text-gray-600 mb-1">
              <span>Fixed Allowance</span>
              <span>₹16,283.00</span>
            </li>
          </ul>
          <button class="text-blue-500 text-sm font-semibold mt-2">+ Add Earning</button>
        </div>
    
        <!-- Deductions Section -->
        <div class="mb-6">
          <div class="flex justify-between">
            <h2 class="text-md font-semibold uppercase text-red-600 mb-2">(-) Deductions</h2>
            <h2 class="text-md font-semibold uppercase text-black-600 mb-2">Amount</h2>
          </div>
          <h3 class="text-md font-semibold text-gray-700 mb-2">Pre-Tax Deductions</h3>
          <ul class="mb-4">
            <li class="flex justify-between text-sm text-gray-600 mb-1">
              <span>EPF Contribution</span>
              <span>₹1,800.00</span>
            </li>
          </ul>
          <h3 class="text-md font-semibold text-gray-700 mb-2">Taxes</h3>
          <ul class="mb-4">
            <li class="flex justify-between text-sm text-gray-600 mb-1">
              <span>Income Tax</span>
              <span>₹1,29,770.00</span>
            </li>
            <li class="flex justify-between text-sm text-gray-600 mb-1">
              <span>Professional Tax</span>
              <span>₹200.00</span>
            </li>
          </ul>
          <h3 class="text-md font-semibold text-gray-700 mb-2">Post-Tax Deductions</h3>
          <ul>
            <li class="flex justify-between text-sm text-gray-600 mb-1">
              <span>Personal Loan</span>
              <span>₹10,000.00</span>
            </li>
            <li class="flex justify-between text-sm text-gray-600 mb-1">
              <span>New Loan</span>
              <span>₹10,000.00</span>
            </li>
            <li class="flex justify-between text-sm text-gray-600 mb-1">
              <span>Food Coupon Deduction</span>
              <span>₹2,000.00</span>
            </li>
          </ul>
        </div>
    
        <!-- Additional Deduction Section -->
        <div class="mb-6">
          <div class="flex items-center gap-4">
            <select class="border border-gray-300 rounded-md p-2 text-sm w-full">
              <option value="">Select</option>
              <option value="supermarket">Supermarket Bill</option>
              <option value="canteen">Canteen Bill</option>
            </select>
            <input type="text" placeholder="500" class="border border-gray-300 rounded-md p-2 text-sm w-24">
            <button class="text-red-500 text-sm font-semibold">&#8209;</button>
          </div>
        </div>
    
        <!-- Footer Section -->
        <div class="flex justify-end gap-4">
          <button class="bg-gray-200 text-gray-700 rounded-md px-4 py-2 text-sm">Cancel</button>
          <button class="bg-blue-500 text-white rounded-md px-4 py-2 text-sm">Save</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "tdscalculation",
  data() {
    return {
      tableData: [
          {
            quarter: 'Q1',
            receiptNumbers: ['12345', '67890'],
            amountPaid: 59406.0,
            taxDeducted: 0.0,
            taxRemitted: 0.0,
          },
          {
            quarter: 'Q2',
            receiptNumbers: ['23456', '78901'],
            amountPaid: 51906.0,
            taxDeducted: 0.0,
            taxRemitted: 0.0,
          },
          {
            quarter: 'Q3',
            receiptNumbers: ['34567', '89012'],
            amountPaid: 51906.0,
            taxDeducted: 0.0,
            taxRemitted: 0.0,
          },
          {
            quarter: 'Q4',
            receiptNumbers: ['45678', '90123'],
            amountPaid: 77609.0,
            taxDeducted: 0.0,
            taxRemitted: 0.0,
          },
          {
            quarter: 'Total (Rs.)',
            receiptNumbers: [],
            amountPaid: 240827.0,
            taxDeducted: 0.0,
            taxRemitted: 0.0,
          },
        ],
    };
  },
  methods: {
    formatNumber(value) {
      return value.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },
  },
};
</script>

