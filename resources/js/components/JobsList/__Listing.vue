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
              <input
                class="appearance-none block w-full rounded py-2 px-3 leading-tight border border-custom-border focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                type="text"
                placeholder="Search Job Titles"
              />
              <font-awesome-icon
                icon="search"
                class="absolute right-0 mr-2 top-1/2 cursor-pointer text-gray-500 "
                style="margin-left: 8px;"
              />
          </div>
          </div>
        </div>
      </div>

      <!-- Job Listings Table -->
      <table class="min-w-full border border-custom-border rounded-lg border-collapse">
        <thead class="bg-custom-bg_table_head_primary border-custom-border">
          <tr class="text-left text-gray-600 border border-custom-border rounded-lg">
            <th class="p-3 border border-custom-border">#</th>
            
            <th class="p-3 border border-custom-border">Job Title</th>
            <th class="p-3 border border-custom-border">Status</th>
            <!-- <th class="w-32 text-sm text-left">Applicants</th> -->
            <th class="p-3 border border-custom-border">Type</th>
            <th class="p-3 border border-custom-border">Date Created</th>
            <th class="p-3 border border-custom-border">Location</th>
            <th class="p-3 border border-custom-border">Edit</th>
            <th class="p-3 border border-custom-border">Applicants</th>

          </tr>
        </thead>
        <tbody class="bg-white">
          <tr v-if="isLoader" class="border">
            <td colspan="8" class="text-center loader p-3" >Loading...</td>
          </tr>
          <tr v-for="(job, index) in dataFromApi" :key="job.id" class="border border-custom-border">
            <td class="text-center p-3 border border-custom-border" >{{ index + 1 }}
            </td>
           
            <td style="cursor: pointer;" @click="navigateToJob(job.job_number)"
            class="border border-custom-border p-3">{{ job.job_title }}</td>
            <td class="p-3 border border-custom-border">open</td>
            <!-- <td>{{ job.people_to_hire }}</td> -->
            <td class="p-3 border border-custom-border">{{ job.job_type }}</td>
            <!-- <td>{{ job.created_at }}</td> -->
            <td class="p-3 border border-custom-border">{{ job.created_at.split('T')[0] }}</td>
            <td class="p-3 border border-custom-border">{{ job.city }}</td>

            <td class="text-center p-3 border border-custom-border">
              <a href="#"> <font-awesome-icon icon="pencil-alt" style="font-size: 14px;"/> </a>
            </td>
            <td class="border border-custom-border p-3"> <a href="#" @click="navigateToCandidates(job.job_number)"> <font-awesome-icon :icon="['fas', 'eye']" style="font-size: 14px;"/></a>
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
