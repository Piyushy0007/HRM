<template>
  <div>
    <Loader msg="Loading Job Listings..." v-model="isLoader" />
    <header-component />
    <div class="c-job-listings" style="margin-left: 242px;">
      <div class="flex items-center justify-between p-2">
      <!--  <button
          class="text-white py-2 px-16 rounded-lg text-sm btn-add-job"
          type="button"
        >
          Add Job
        </button>
        -->
        <div class="flex flex-wrap -mx-3">
          <div class="w-full md:w-50 px-3">
            <div class="flex items-center relative">
              <font-awesome-icon
                icon="search"
                class="absolute right-3 text-gray-500 "
                style="margin-left: 8px;"
              />
            <input
              class="appearance-none block w-full rounded py-2 px-4 pl-5 leading-tight border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
              type="text"
              placeholder="Search Job Titles"
            />
          </div>
          </div>
        </div>
      </div>

      <!-- Job Listings Table -->
      <table class="w-full table-fixed table-responsive-sm">
        <thead>
          <tr>
            <th class="w-6" style="background-color: #3B86FF; color: white;">#</th>
            
            <th class="w-32 text-sm text-left" style="background-color: #3B86FF; color: white;">Job Title</th>
            <th class="w-32 text-sm text-left"style="background-color: #3B86FF; color: white;">Status</th>
            <!-- <th class="w-32 text-sm text-left">Applicants</th> -->
            <th class="w-32 text-sm text-left"style="background-color: #3B86FF; color: white;">Type</th>
            <th class="w-32 text-sm text-left"style="background-color: #3B86FF; color: white;">Date Created</th>
            <th class="w-32 text-sm text-left"style="background-color: #3B86FF; color: white;">Location</th>
            <th class="w-20 text-sm leading-none px-2 py-2"style="background-color: #3B86FF; color: white;">Edit</th>
            <th class="w-20 text-sm leading-none px-2 py-2"style="background-color: #3B86FF; color: white;">Applicants</th>

          </tr>
        </thead>
        <tbody>
          <!-- No Records Found -->
          
          <tr v-if="isLoader">
            <td colspan="8" class="text-center loader">Loading...</td>
          </tr>
          <tr v-for="(job, index) in dataFromApi" :key="job.id">
            <td class="text-center" >{{ index + 1 }}
            </td>
           
            <td style="cursor: pointer;" @click="navigateToJob(job.job_number)">{{ job.job_title }}</td>
            <td>open</td>
            <!-- <td>{{ job.people_to_hire }}</td> -->
            <td>{{ job.job_type }}</td>
            <!-- <td>{{ job.created_at }}</td> -->
            <td>{{ job.created_at.split('T')[0] }}</td>
            <td>{{ job.city }}</td>

            <td class="text-center">
              <a href="#"> <font-awesome-icon icon="pencil-alt" style="font-size: 14px;"/> </a>
            </td>
            <td > <a href="#" @click="navigateToCandidates(job.job_number)"> <font-awesome-icon :icon="['fas', 'eye']" style="font-size: 14px;"/></a>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Save Button -->
      <!-- <div class="text-center my-12">
        <button
          class="text-white py-2 px-16 rounded-lg text-sm btn-save-jobs"
          type="button"
        >
          Save Changes
        </button>
      </div> -->

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
import { method } from 'lodash';
import axios from 'axios';

export default {
  data() {
    return {
      isLoader: false,
      dataFromApi: [], 
      error: null


    };
   
},


methods: {
  
    navigateToCandidates(jobId) {
      this.$router.push({ path: '/applications', query: {jobId } });
    },
    navigateToJob(jobId) {
      this.$router.push(`/navigation/${jobId}`);
    },
    async fetchData() {
      
      
      this.isLoader = true;
      this.error = null;
      try {
        const userId= localStorage.getItem("userId")
        const response = await axios.get(`api/jobs`);
        this.dataFromApi = response.data; 
      } catch (err) {
        this.error = err.message || 'An error occurred while fetching data.';
      } finally {
        this.isLoader = false; 
      }
    }
  },
    
  mounted() {
    this.fetchData(); 
  }
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
