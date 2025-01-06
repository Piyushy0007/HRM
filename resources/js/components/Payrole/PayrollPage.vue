<template>
  <div>
    <header-component />
    <div class="p-6 bg-gray-100" style="margin-left: 242px;">
      <h2 class="text-xl mb-4"> <span class="font-bold"> Pay Period:</span> <span>20 Mar,2022 - 19 Apr,2022</span> <span class="bg-green-600 text-white text-sm">Processed</span></h2>
      <div class="flex flex-col md:flex-row gap-2 w-full">
        <!-- Payroll Summary -->
        <div class="bg-white w-full md:w-1/2 rounded shadow p-4 mb-4">
          <h2 class="text-xl font-bold mb-4">Payroll Summary</h2>
          <div class="grid grid-cols-2 gap-3 text-md">
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
  </div>
</template>

<script>
export default {
  name: "tdscalculation",
};
</script>

