<template>
  <div class="c-job-listings px-4 pb-4 w-80" style="margin-left: 14vw;">
    <!-- Loader -->
    <Loader msg="Loading Job Listings..." v-model="isLoader" />

    <header-component />
    <!-- Header with Add Job Button and Search -->
    <div class="px-4">
      <div class="flex items-center justify-between my-5">
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
            <th class="w-32 text-sm text-left">Candidate's Name</th>
            <th class="w-32 text-sm text-left">Job Title</th>
            <th class="w-32 text-sm text-left">Location</th>
            <th class="w-32 text-sm text-left">Applied Date</th>
            <th class="w-20 text-sm leading-none px-2 py-2">Detail of Candidates</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="!applications.length">
            <td colspan="6" class="text-center">No job applications found</td>
          </tr>
          <tr v-for="(application, index) in applications" :key="index">
            <td class="text-center">{{ index + 1 }}</td>
            <td>{{ application.name }}</td>
            <td>{{ application.jobTitle }}</td>
            <td>{{ application.location }}</td>
            <td>{{ application.appliedDate }}</td>
            <td class="text-center">
              <font-awesome-icon :icon="['fas', 'eye']" />
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
export default {
  data() {
    return {
      isLoader: false,
      applications: [
        {
          name: "Piyush",
          jobTitle: "Software Developer",
          location: "New York",
          appliedDate: "2024-11-22",
        },
        {
          name: "Kewal",
          jobTitle: "Finance",
          location: "Los Angeles",
          appliedDate: "2024-11-20",
        },
      ],
      searchQuery: "",
    };
  },
};
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
</style>
