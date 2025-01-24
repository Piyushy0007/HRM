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
                <label class="block text-black-600 text-sm mb-1">From Date</label>
                <input
                  v-model="formData.start_date"
                  type="date"
                  class="w-full border-gray-300 rounded-lg text-sm p-2 border"
                  placeholder="dd/mm/yyyy"
                />
              </div>
              <div class="mb-4">
                <label class="block text-black-600 text-sm mb-1">Leave Type</label>
                <select
                  v-model="formData.leave_type"
                  class="w-full border-gray-300 rounded-lg text-sm p-2"
                >
                  <option value="Casual">Casual</option>
                  <option value="Medical">Medical</option>
                  <option value="Annual Leave">Annual Leave</option>
                </select>
              </div>
            </div>
            <!-- Right Column -->
            <div>
              <div class="mb-4">
                <label class="block text-black-600 text-sm mb-1">To Date</label>
                <input
                  v-model="formData.end_date"
                  type="date"
                  class="w-full border-gray-300 rounded-lg text-sm p-2 border"
                  placeholder="dd/mm/yyyy"
                />
              </div>
              <div class="mb-4">
                <label class="block text-black-600 text-sm mb-1">Select Employee</label>
                <select v-model="selectedEmployee" class="w-full border-gray-300 rounded-lg text-sm p-2" id="employeeSelect">
                  <option disabled value="">Select Employee</option>
                  <option v-for="employee in employees" :key="employee.id" :value="employee.id">
                    {{ employee.firstname }} {{ employee.lastname }}
                  </option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Reason -->
        <div class="bg-white shadow-md rounded-lg p-6 mt-6">
          <h2 class="text-lg font-semibold mb-4 text-blue-600">Reason</h2>
          <textarea
            v-model="formData.reason"
            class="w-full border-gray-300 rounded-lg text-sm p-2 border"
            placeholder="Reason for Leave"
            rows="3"
          ></textarea>
        </div>

        <!-- Manager Approval -->
        <div class="bg-white shadow-md rounded-lg p-6 mt-6">
          <div class="flex items-center space-x-4">
            <button
              @click="submitLeaveRequest"
              class="bg-green-500 text-white px-4 py-1 rounded-lg"
            >
              Submit
            </button>
            <button 
              @click="resetForm"
              class="bg-red-500 text-white px-4 py-1 rounded-lg">
              Cancel
            </button>
          </div>
          <!-- <div v-if="successMessage" class="text-green-600 mt-4">
            {{ successMessage }}
          </div>
          <div v-if="errorMessage" class="text-red-600 mt-4">
            {{ errorMessage }}
          </div> -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "leave",
  data() {
    return {
      formData: {
        start_date: "",
        end_date: "",
        leave_type: "Casual",
        reason: "",
      },
      successMessage: "",
      errorMessage: "",
      employees: [], // List of employees
      selectedEmployee: "", // Currently selected employee ID
    };
  },
  methods: {
    async submitLeaveRequest() {
      try {
        const response = await axios.post("/api/employee/1/LeaveRequest", this.formData);
        alert(response.data.message);
        this.errorMessage = "";
        console.log(response.data);
        this.resetForm();
      } catch (error) {
        alert(error.response?.data?.message || "An error occurred.")
        this.successMessage = "";
        alert(errorMessage);
        console.error(error);
      }
    },
    // Updated fetchEmployees method
    async fetchEmployees() {
      try {
        const response = await axios.get('/api/employees');
        this.employees = response.data;
        console.log('Employees fetched successfully:', response.data);
      } catch (error) {
        const errorMessage = error.response?.data?.message || "An error occurred while fetching employees.";
        console.error('Error fetching employees:', errorMessage);
        alert(errorMessage);
      }
    },
    resetForm() {
      // Reset form fields to their initial state
      this.formData = {
        start_date: "",
        end_date: "",
        leave_type: "Casual",
        reason: "",
      };
    },
  },
  mounted() {
    // Fetch employees when the component mounts
    this.fetchEmployees();
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
