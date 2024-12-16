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
        @click="redirectToApply(jobDetails.job_number)"
        class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-3 px-8 rounded-lg font-bold text-lg hover:shadow-xl hover:scale-105 transition-transform" style="background-color: skyblue;color: black;"
      >
        Apply Now
      </button>
    </div>
  </div>
</template>

  
<script>
import axios from 'axios';

export default {
  data() {
    return {
      jobDetails: {},
      isLoader: false,
      error: null
    };
  },

  created() {
    const jobId = this.$route.params.job_number;
    

    

    // Make an API call to get the job details based on jobId
    this.fetchJobDetails(jobId);
  },
  methods: {
    async fetchJobDetails(jobId) {
      
      this.isLoader = true;
     

      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/jobs/${jobId}`);  
        console.log(jobId,"igioid")
        this.jobDetails = response.data.data; // Assuming the response structure is similar to your example
      } catch (err) {
        console.error(err); // For debugging
        this.error = err.response?.data?.message || err.message || 'An error occurred while fetching data.';
      } finally {
        this.isLoader = false;
      }
    },
    redirectToApply(jobId) {
      this.$router.push({ path: '/apply', query: {jobId } });
    }
  },
  // mounted() {
  //   this.fetchJobDetails();
  // }
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
  