<template>
    <div>
      <Loader msg="Loading Leave Request Listings..." v-model="isLoader" />
      <header-component />
      <div style="margin-left: 242px;">
        <div class="max-w-7xl mx-auto p-4 h-screen flex flex-col">
            <div class="bg-white shadow-md rounded-lg p-4 mb-5">
                <div class="font-semibold text-lg text-gray-800">Run Payroll for {{ month }}</div>
            </div>
            <div class="bg-white shadow-lg rounded-lg flex-grow flex flex-col p-4">
                <h2 class="text-lg font-semibold mb-4">Payroll Information for {{ month }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Total Employees -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700">Total Employees</h3>
                    <p class="text-xl font-bold">{{ totalEmployees }}</p>
                    </div>

                    <!-- Gross Salary -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700">Gross Salary</h3>
                    <p class="text-xl font-bold">{{ grossSalary | currency }}</p>
                    </div>

                    <!-- Net Salary -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700">Net Salary</h3>
                    <p class="text-xl font-bold">{{ netSalary | currency }}</p>
                    </div>

                    <!-- Tax Deduction -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700">Tax Deduction</h3>
                    <p class="text-xl font-bold">{{ taxDeduction | currency }}</p>
                    </div>

                    <!-- Status -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700">Status</h3>
                    <p class="text-xl font-bold">{{ status }}</p>
                    </div>
                </div>
                <div class="p-4">
                    <button
                        class="mt-4 bg-blue-500 text-white py-1 px-4 rounded w-1/12"
                    >
                        Run Now
                    </button>
                </div>
            </div>
        </div>
        </div>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  export default {
    name: "runpayrollscr",
    data() {
        return {
            month: this.$route.query.month || 'Not Selected',  // Retrieve the month from the route query parameter
            totalEmployees: 150,  // Example static data
            grossSalary: "₹1,000,000",  
            netSalary: "₹9,50,000",  
            taxDeduction: "₹50,000",  
            status: 'Completed',
        };
    },
    created() {
        // Adding the currency filter locally in the component
        Vue.filter('currency', function(value) {
        if (!value) return '₹0';
        return new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR',
        }).format(value);
        });
    },
    watch: {
        // Watch for route changes to update the month if needed
        '$route.query.month'(newMonth) {
        this.month = newMonth || 'Not Selected';
        }
    },
    methods: {  
        async runNow() {
            if (this.fromDate && this.toDate) {
            this.error = null;
            this.isLoader = true;
            try {
            const response = await axios.get(`/api/run-payroll/${$route.query.month}/${$route.query.year}`) 
            console.log("API Response:", response.data);
            if (response.status === 200 && Array.isArray(response.data)) {
                this.employees = response.data;
                console.log(response.data)
            } else {
                console.error("Unexpected API response:", response);
                this.employees = [];
            }
            } catch (error) {
            console.error("Error fetching leave requests:", error);
            this.employees = []; // Reset employees on error
            } finally {
            this.isLoader = false; 
            }
        }
        },
    },
  };
  </script>
  