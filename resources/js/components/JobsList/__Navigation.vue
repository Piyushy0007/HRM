<template>
  <div class="c-job-details min-h-screen max-w-6xl px-10 pb-12 my-2 mx-auto rounded-2xl shadow-2xl bg-gradient-to-br from-blue-50 to-white">
    <div>
       <div class="flex justify-between items-start mb-4 mt-4">
         <div>
           <h1 class="text-2xl text-blue-800 font-bold">{{ jobDetails.job_title }}</h1>
           <p class="text-gray-600">Microsoft Inc.</p>
           <p class="text-gray-400 text-sm">Los Angeles, USA • 14 hours ago</p>
         </div>
         <div class="flex items-center">
           <button class="flex items-center space-x-2">
            <font-awesome-icon icon="bookmark" class="text-lg" />
            <span class="text-black-600">Save</span>
          </button>
           <button 
           @click="redirectToApply(jobDetails.job_number)"
           class="ml-4 bg-green-600 text-white py-2 px-4 rounded-lg"
           >Apply Now</button>
         </div>
       </div>
       <div class="flex md:flex-row flex-col justify-between items-stretch gap-6">
        <!-- Left Div: 70% Width -->
        <div class="p-6 bg-white shadow-lg max-w-3xl w-full rounded-xl mt-4 border border-gray-300">
          <h2 class="text-xl font-bold text-blue-700 mb-4">About the Job</h2>
          <p class="text-gray-700 text-sm mb-4">
            {{ jobDetails.job_description }}
          </p>          
          <!-- Benefits -->
          <h2 class="mt-4 text-xl font-bold text-blue-700 mb-4">Benefits</h2>
          <span class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm">{{ jobDetails.benefits }}</span>
          <!-- Application Information -->
          <h2 class="text-xl font-bold mt-4 text-blue-700 mb-4">Application Details</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <ul class="space-y-3 text-gray-800 text-md">
                <li><strong>CV Option:</strong> {{ jobDetails.cv_option === 1 ? "Required" : "Not Required" }}</li>
              </ul>
            </div>
            <div>
              <ul class="space-y-3 text-gray-800 text-md">
                <li><strong>Email for Applications:</strong> {{ jobDetails.email_1 }}</li>
              </ul>
            </div>
          </div>
        </div>      
        <!-- Right Div: 30% Width -->
        <div class="max-w-md w-full p-6 bg-gray-300 border border-gray-100 rounded-lg mt-4">
          <ul class="space-y-3 text-gray-800 text-sm">
            <li><strong>Location:</strong> {{ jobDetails.city }}, {{ jobDetails.area }} ({{ jobDetails.pincode }})</li>
            <li><strong>Posting Location:</strong> {{ jobDetails.job_posting_location }}</li>
            <li><strong>Job Type:</strong> {{ jobDetails.job_type }}</li>
            <li><strong>Schedule:</strong> {{ jobDetails.schedule }}</li>
            <li><strong>Salary Range:</strong> ₹{{ jobDetails.pay_minimum }} - ₹{{ jobDetails.pay_maximum }} ({{ jobDetails.pay_rate_type }})</li>
            <li><strong>People to Hire:</strong> {{ jobDetails.people_to_hire }}</li>
            <li><strong>Recruitment Timeline:</strong> {{ jobDetails.recruitment_timeline }}</li>
            <li><strong>Start Date:</strong> {{ jobDetails.start_date }}</li>
          </ul>
        </div>
      </div>
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
    /* background: linear-gradient(to bottom, #e0f7fa, #ffffff); */
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
  