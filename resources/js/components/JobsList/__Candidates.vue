<template>
  <div>
    <Loader msg="Loading Job Listings..." v-model="isLoader" />
    <header-component />
    <div class="c-job-listings" style="margin-left: 242px;">
      <div class="flex items-center justify-between p-2">
        <div class="flex items-center justify-between  p-3 mb-2 flex-wrap w-full">
          <div class="flex items-center">
            <span class="font-semibold text-lg text-gray-800">Candidates List</span>
          </div>
          <div class="flex items-center flex-grow mx-12 relative">
            <input
              type="text"
              placeholder="Search..."
              class="w-full border border-gray-300 rounded-md pl-3 pr-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
              style="height: 36px;"
            />
            <div>
              <font-awesome-icon
              icon="search"
              class="absolute right-0 mr-2 transform -translate-y-1/2 top-1/2 cursor-pointer text-gray-500"
              style="font-size: 20px"
              />
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <div class="flex items-center">
              <label for="fromDate" class="text-gray-600 mr-2">From</label>
              <input type="date" id="fromDate" class="border border-gray-300 rounded-md px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" />
            </div>
            <div class="flex items-center">
              <label for="toDate" class="text-gray-600 mr-2">To</label>
              <input type="date" id="toDate"  class="border border-gray-300 rounded-md px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" />
            </div>
          </div>
        </div>
      </div>

          <table class="min-w-full border border-custom-border rounded-lg border-collapse">
           <thead class="bg-custom-bg_table_head_primary border-custom-border">
           <tr class="text-left text-gray-600 border border-custom-border rounded-lg" >
            <th class="p-3 border border-custom-border">#</th>
            <th class="p-3 border border-custom-border">Candidate Name</th>
            <th class="p-3 border border-custom-border">Candidate Email</th>
            <th class="p-3 border border-custom-border">Location</th>
            <th class="p-3 border border-custom-border">Applied Date</th>
            <th class="p-3 border border-custom-border">Detail of Candidates</th>
          </tr>
        </thead>
        <tbody class="bg-white">

          

          <tr v-if="isLoader">
            <td colspan="6" class="text-center loader">Loading...</td>
          </tr>
        
          <tr v-else-if="dataFromApi === null || dataFromApi.length === 0">
            <td colspan="6" class="text-center loader">No applicant found</td>
          </tr>
          
          <tr v-for="(job, index) in dataFromApi" :key="index">
            <td class="border border-custom-border p-3" >{{ index + 1 }}</td>
            <td class="p-3 border border-custom-border" >{{ job.name }}</td>
            <td class="p-3 border border-custom-border" >{{ job.email }}</td>
            <td class="p-3 border border-custom-border" >{{ job.location }}</td>
            <td class="p-3 border border-custom-border" >{{ job.applied_date }}</td>
            <td class="p-3 border border-custom-border" > <a href="#" title="Candidate Detail" @click="navigateToApplicant(job.id)"> <font-awesome-icon :icon="['fas', 'eye']" /></a>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Information Section -->
      <!-- <div class="information mt-5 p-4 rounded-lg">
        <h4 class="text-2xl font-semibold mb-2">Information</h4>
        <ul class="ml-2 mb-1 instructions">
          <li class="mb-1">Check the boxes for jobs to edit or delete them.</li>
          <li class="mb-1">Click the pencil icon to edit job details.</li>
          <li>Optional: Use the "Add Job" button to add new job listings.</li>
        </ul>
      </div> -->
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
      console.log(id)
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
