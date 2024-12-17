<template>
  <div>
    <Loader msg="Loading Job Listings..." v-model="isLoader" />
    <header-component />
    <div class="c-job-listings" style="margin-left: 242px;">
      <div class="flex items-center justify-between p-2">
        <div class="flex flex-wrap -mx-3">
          <div class="w-full md:w-50 px-3">
            <div class="flex items-center relative">
              <input
                class="appearance-none block w-full rounded py-2 px-4 pl-5 leading-tight border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                type="text"
                placeholder="Search Job Titles"
                v-model="searchQuery"
              />
              <font-awesome-icon
                icon="search"
                class="absolute right-3 text-gray-500"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Job Listings Table -->
      <table class="w-full table-fixed">
        <thead>
          
          <tr>
            <th class="w-6">#</th>
            <th class="w-32 text-sm text-left">Candidate Name</th>
            <!-- <th class="w-32 text-sm text-left">Job Title</th> -->
            <th class="w-32 text-sm text-left">Candidate Email</th>
            <th class="w-32 text-sm text-left">Location</th>
            <th class="w-32 text-sm text-left">Applied Date</th>
            <th class="w-20 text-sm leading-none px-2 py-2">Detail of Candidates</th>
          </tr>
        </thead>
        <tbody>

          

          <tr v-if="isLoader">
            <td colspan="6" class="text-center loader">Loading...</td>
          </tr>
        
          <tr v-else-if="dataFromApi === null || dataFromApi.length === 0">
            <td colspan="6" class="text-center loader">No applicant found</td>
          </tr>
          
          <tr v-for="(job, index) in dataFromApi" :key="index">
            <td class="text-center" >{{ index + 1 }}</td>
            <td>{{ job.name }}</td>
            <td>{{ job.email }}</td>
            <td>{{ job.location }}</td>
            <td>{{ job.applied_date }}</td>
            <td > <a href="#" @click="navigateToApplicant(job.id)"> <font-awesome-icon :icon="['fas', 'eye']" /></a>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Information Section -->
      <div class="information mt-5 p-4 rounded-lg">
        <h4 class="text-2xl font-semibold mb-2">Information</h4>
        <ul class="ml-2 mb-1 instructions">
          <li class="mb-1">Check the boxes for jobs to edit or delete them.</li>
          <li class="mb-1">Click the pencil icon to edit job details.</li>
          <li>Optional: Use the "Add Job" button to add new job listings.</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      isLoader: false,
      dataFromApi: [], 
      error: null


    };
   
},
created() {
  const jobId = this.$route.query.jobId; 
  console.log(jobId, "candidate job number");

  if (jobId) {
    this.fetchData(jobId); 
  } else {
    console.log("Job number is missing");
  }
},

methods: {

  navigateToApplicant(id) {
      this.$router.push({ path: '/applicant', query: {id } });
    },
  
   
    async fetchData(jobId) {
      
      
      this.isLoader = true;
      this.error = null;
      try {
        const response = await axios.get(`api/applications/job/${jobId}`);
      this.dataFromApi = response.data; 
      } catch (err) {
        console.log(err,"error in api")
        this.error = err.message || 'An error occurred while fetching data.';
        this.dataFromApi = [];
      } finally {
        this.isLoader = false; 
      }
    }
  },
    
  
}
</script>

<style scoped>
.c-job-listings {
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.btn-add-job,
.btn-save-jobs {
  background-color: #2d477f;
}

.btn-add-job:hover,
.btn-save-jobs:hover {
  background-color: #2d477f;
}

.table-fixed th,
.table-fixed td {
  padding: 8px;
  border: 1px solid #ddd;
  text-align: left;
}

.table-fixed th {
  background-color: #f2f2f2;
}
.loader {
text-align: center;
  font-size: 20px;
  color: #007bff;
}

.error {
  color: red;
  font-size: 18px;
}

</style>
