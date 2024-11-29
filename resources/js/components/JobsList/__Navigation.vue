<template>
    <div class="c-job-details px-10 pb-12 max-w-5xl mx-auto rounded-2xl shadow-2xl bg-gradient-to-br from-blue-50 to-white">
      <!-- Job Details Header -->
      <header class="text-center py-8">
        <h1 class="text-5xl font-extrabold text-blue-800 truncate">
          Job Opportunity: {{ jobDetails.job_title }}
        </h1>
        <p class="text-gray-700 mt-4 text-xl">
          Salary: <strong>\${{ jobDetails.salary }}</strong>
        </p>
      </header>
  
      <!-- Job Information -->
      <div class="p-6 bg-white shadow-lg rounded-xl mb-8 border-t-4 border-blue-600">
        <h2 class="text-3xl font-bold text-blue-700 mb-4">
          Job Details
        </h2>
        <ul class="space-y-3 text-gray-800 text-lg">
          <li><strong>Location:</strong> {{ jobDetails.city }}, {{ jobDetails.area }} ({{ jobDetails.pincode }})</li>
          <li><strong>Posting Location:</strong> {{ jobDetails.job_posting_location }}</li>
          <li><strong>Job Type:</strong> {{ jobDetails.job_type }}</li>
          <li><strong>Schedule:</strong> {{ jobDetails.schedule }}</li>
          <li><strong>Salary Range:</strong> \${{ jobDetails.pay_minimum }} - \${{ jobDetails.pay_maximum }} ({{ jobDetails.pay_rate_type }})</li>
          <li><strong>People to Hire:</strong> {{ jobDetails.people_to_hire }}</li>
          <li><strong>Recruitment Timeline:</strong> {{ jobDetails.recruitment_timeline }}</li>
          <li><strong>Start Date:</strong> {{ jobDetails.start_date }}</li>
        </ul>
      </div>
  
      <!-- Benefits -->
      <div class="p-6 bg-white shadow-lg rounded-xl mb-8 border-t-4 border-blue-600">
        <h2 class="text-3xl font-bold text-blue-700 mb-4">
          Benefits
        </h2>
        <p class="text-gray-800 text-lg">
          {{ jobDetails.benefits }}
        </p>
      </div>
  
      <!-- Job Description -->
      <div class="p-6 bg-white shadow-lg rounded-xl mb-8 border-t-4 border-blue-600">
        <h2 class="text-3xl font-bold text-blue-700 mb-4">
          Job Description
        </h2>
        <p class="text-gray-800 text-lg">
          {{ jobDetails.job_description }}
        </p>
      </div>
  
      <!-- Application Information -->
      <div class="p-6 bg-white shadow-lg rounded-xl mb-8 border-t-4 border-blue-600">
        <h2 class="text-3xl font-bold text-blue-700 mb-4">
          Application Details
        </h2>
        <ul class="space-y-3 text-gray-800 text-lg">
          <li><strong>CV Option:</strong> {{ jobDetails.cv_option === 1 ? "Required" : "Not Required" }}</li>
          <li><strong>Email for Applications:</strong> {{ jobDetails.email_1 }}</li>
        </ul>
      </div>
  
      <!-- Apply Button -->
      <div class="text-center mt-8">
        <button
          @click="redirectToApply"
          class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-3 px-8 rounded-lg font-bold text-lg hover:shadow-xl hover:scale-105 transition-transform"
        >
          Apply Now
        </button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        jobDetails: {
          employee_id: 3,
          job_title: "Software Developer",
          salary: 60000,
          city: "New York",
          area: "Downtown",
          pincode: "10001",
          job_posting_location: "hybrid",
          people_to_hire: 3,
          recruitment_timeline: "2 weeks",
          schedule: "Monday to Friday, 9 AM - 5 PM",
          job_type: "Full-time",
          benefits: "Health insurance, Paid leave",
          job_description: "Develop and maintain software applications.",
          pay_minimum: 50000,
          pay_maximum: 70000,
          communication_preference_email: true,
          application_deadline: false,
          planned_start_date: true,
          start_date: "2024-12-01",
          job_status: 1,
          cv_option: 1,
          pay_rate_type: "monthly",
          email_1: "hr@example.com",
        },
      };
    },
    methods: {
      redirectToApply() {
        // Navigate to the application page (replace '/apply' with the correct path)
        this.$router.push('/apply');
      },
    },
  };
  </script>
  
  <style scoped>
  .c-job-details {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to bottom, #e0f7fa, #ffffff);
    border-radius: 16px;
    box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }
  ul {
    word-wrap: break-word;
  }
  button {
    transition: all 0.3s ease-in-out;
  }
  </style>
  