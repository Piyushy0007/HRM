<template>
    <div class="c-request-form">
      <header-component />
      <div style="margin-left: 242px;">
        <div class="p-8 bg-gray-50 min-h-screen">
          <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="mb-4 text-center text-2xl font-semibold mb-4 text-blue-600">Create Request Form</h1>
        <form
          class="p-10 rounded-2xl max-w-5xl w-full mx-auto space-y-8"
          @submit.prevent="submitForm"
        >
          <!-- Select Employee -->
          <div>
            <label class="block text-sm font-bold text-black-700 mb-1">Select Employee</label>
            <select
              v-model="form.selectedEmployee"
              class="w-full border-gray-300 rounded-lg text-sm p-2"
              id="employeeSelect"
            >
              <option disabled value="">Select Employee</option>
              <option v-for="employee in employees" :key="employee.id" :value="employee.id">
                {{ employee.firstname }} {{ employee.lastname }}
              </option>
            </select>
            <span v-if="errors.selectedEmployee" class="text-red-500 text-sm">{{ errors.selectedEmployee }}</span>
          </div>
          <!-- Request Type -->
          <div>
            <label class="block text-sm font-bold text-black-700 mb-1">Request Type</label>
            <select
              id="requestType"
              v-model="form.requestType"
              class="w-full border-gray-300 rounded-lg text-sm p-2"
            >
              <option disabled value="">Select Request Type</option>
              <option v-for="type in requestTypes" :key="type" :value="type">{{ type }}</option>
            </select>
            <span v-if="errors.requestType" class="text-red-500 text-sm">{{ errors.requestType }}</span>
          </div>
          <!-- Reason/Description -->
          <div>
            <label for="reason" class="block text-sm font-bold text-black-700 mb-1">
              Reason/Description
            </label>
            <textarea
              id="reason"
              v-model="form.reason"
              rows="4"
              placeholder="Enter the reason for your request"
              class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500"
            ></textarea>
            <span v-if="errors.reason" class="text-red-500 text-sm">{{ errors.reason }}</span>
          </div>
          <!-- Image Upload -->
          <div>
            <label for="image" class="block text-sm font-bold text-black-700 mb-1">
              Upload Supporting Image (Optional)
            </label>
            <input
              type="file"
              id="image"
              @change="handleFileUpload($event, 'document')"
              class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500"
            />
            <span v-if="errors.document" class="text-red-500 text-sm">{{ errors.document }}</span>
          </div>
          <!-- Submit Button -->
          <div class="flex justify-center mt-4" style="margin-top: 5%;">
            <button
                type="submit"
                class="bg-[#2C1977] text-white px-6 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2"
                style="background-color:#2C1977 "
             >
              Submit
            </button>
          </div>
        </form>
      </div>
      </div>
      </div>
    </div>
  </template>  
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        form: {
          selectedEmployee: "",
          requestType: "",
          reason: "",
          image: null,
          
        },
        employees: [], // Employees array
        requestTypes: [
          "Change of email",
          "Change of phone",
          "Change of DOB",
          "Change of bank account",
          "Profile update",
          "Appraisal meeting",
        ],
        errors: {},
      };
    },
    methods: {

      
      async fetchEmployees() {
        try {
          const response = await axios.get("/api/employees");
          this.employees = response.data;
        } catch (error) {
          const errorMessage =
            error.response?.data?.message || "An error occurred while fetching employees.";
          console.error("Error fetching employees:", errorMessage);
          alert(errorMessage);
        }
      },
      handleFileUpload(event) {
  const file = event.target.files[0];
  const allowedTypes = ["image/jpeg", "image/png", "application/pdf"];
  if (file && !allowedTypes.includes(file.type)) {
    this.errors.image = "Only JPG, PNG, and PDF files are allowed.";
    this.form.image = null;
  } else {
    this.errors.image = null;
    this.form.image = file;
  }
},

      validateForm() {
        this.errors = {};
        if (!this.form.selectedEmployee) {
          this.errors.selectedEmployee = "Please select an employee.";
        }
        if (!this.form.requestType) {
          this.errors.requestType = "Please select a request type.";
        }
        if (!this.form.reason) {
          this.errors.reason = "Please provide a reason for your request.";
        }
        if (!this.form.image) { 
    this.errors.image = "Please upload a (JPG, PNG, or PDF) document."; 
  }

        return Object.keys(this.errors).length === 0;
      },
      async submitForm() {
  if (!this.validateForm()) {
    return;
  }
  const formData = new FormData();
  formData.append("selected_employee", this.form.selectedEmployee);
  formData.append("request_type", this.form.requestType);
  formData.append("reason", this.form.reason);
  if (this.form.image) {
    formData.append("image", this.form.image);
  }
  console.log("Image file:", this.form.image);

  
  try {
    const response = await axios.post("/api/create-requests", formData);
    console.log("Form submitted successfully:", response.data,{
        headers: {
            "Content-Type": "multipart/form-data",
          },
    });
    alert("Request submitted successfully!");
    this.$router.push('/my-request-list') 

    // this.form = { selectedEmployee: "", requestType: "", reason: "", image: null, document: null };
  } catch (error) {
    const errorMessage =
      error.response?.data?.message || "An error occurred while submitting the form.";
    console.error("Error submitting form:", errorMessage);
    alert(errorMessage);
  }
},

    },
    mounted() {
      this.fetchEmployees();
    },
  };
  </script>
  
  <style>
  .c-request-form {
    font-family: Arial, sans-serif;
  }
  .text-red-500 {
    color: #f56565;
  }
  </style>
  