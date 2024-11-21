
  


 <template>
    <div class="c-create-page px-4 pb-4 w-80" style="margin-right: 1vw;">
      <header-component class="sticky top-0 z-50 bg-white shadow-md" />
      <div class="container mx-auto pt-5 ml-2">
        <h1 class="text-2xl font-bold mb-4 text-center text-gray-800">Create Job Form</h1>
        <form class="bg-white p-6 rounded-lg shadow-lg border border-gray-200 max-w-lg mx-auto space-y-4">
          <!-- Job Title -->
          <div>
            <label for="jobTitle" class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
            <input
              type="text"
              id="jobTitle"
              placeholder="Enter job title"
              class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500"
            />
          </div>

           <!-- Salary -->
           <div>
            <label for="jobSalary" class="block text-sm font-medium text-gray-700 mb-1">Salary</label>
            <input
              type="number"
              id="jobSalary"
              placeholder="Enter salary"
              class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500"
            />
          </div>
          <!-- Job Location -->
          <div>
            <label for="jobLocation" class="block text-sm font-medium text-gray-700 mb-1">City</label>
            <input
              type="text"
              id="jobLocation"
              placeholder="Enter job location"
              class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500"
            />
          </div>

         
<!-- Job posting location -->
          
<div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Job posting location</h2>
<div class="mb-4">
    <label
      for="locationOption"
      class="block text-sm font-medium text-gray-700 mb-1"
    >
      Which option best describes this job's location? <span class="text-red-500">*</span>
    </label>
    <select
      id="locationOption"
      class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
    >
      <option value="on-site">On-site</option>
      <option value="remote">Remote</option>
      <option value="hybrid">Hybrid</option>
    </select>
  </div>
  </div>

  <!-- Pin code Area -->
    <div class="grid grid-cols-2 gap-4">
                <div>
                <label
                    for="area"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Area
                </label>
                <select
                    id="area"
                    class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="">Select area</option>
                    <option value="area1">Area 1</option>
                    <option value="area2">Area 2</option>
                </select>
                </div>
                <div>
                <label
                    for="pincode"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Pincode
                </label>
                <input
                    type="text"
                    id="pincode"
                    placeholder="Enter pincode"
                    class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                />
                </div>
            </div>


    
          <!-- Job Description -->
          <div>
            <label for="jobDescription" class="block text-sm font-medium text-gray-700 mb-1">Job Description</label>
            <textarea
              id="jobDescription"
              placeholder="Enter job description"
              class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500"
              rows="5"
            ></textarea>
          </div>
    
          
    
          
     
    
          
          <!-- Resume Upload -->
          <div>
    <!-- Icon as File Upload Button -->
    <label for="resumeUpload" class="block text-sm font-medium text-gray-700 mb-1">
      Upload Resume
    </label>
    <div
      class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500 flex items-center justify-center cursor-pointer"
    >
      <input
        type="file"
        id="resumeUpload"
        class="hidden"
        accept=".pdf,.doc,.docx"
        @change="handleFileUpload"
      />
      <label for="resumeUpload" class="flex items-center cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span>Click to Upload File</span>
      </label>
    </div>

    <!-- Display Uploaded File -->
    <div v-if="uploadedFile" class="mt-4">
      <p class="text-sm text-gray-700">Uploaded File:</p>
      <a
        :href="uploadedFileUrl"
        target="_blank"
        class="text-blue-500 hover:underline"
      >{{ uploadedFile.name }}</a>
    </div>
  </div>

          <!-- <div>
            <label for="resumeUpload" class="block text-sm font-medium text-gray-700 mb-1">Upload Resume</label>
            <input
              type="file"
              id="resumeUpload"
              class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500"
              accept=".pdf,.doc,.docx"
            />
          </div> -->
          <!-- Submit Button -->
          <div class="flex justify-end">
            <button
              type="submit"
              class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2"
            >
              Create Job
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  export default {

    data() {
    return {
      uploadedFile: null, // Stores the uploaded file object
      uploadedFileUrl: null, // Stores the URL for the uploaded file
    };
  },
    name: "CreateJobPage",


    methods: {
    handleFileUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.uploadedFile = file;

        this.uploadedFileUrl = URL.createObjectURL(file);
      }
    },
}
  };
  </script>
  
  <style scoped>
  /* Custom Styling for a beautiful UI */
  body {
    background-color: #f8fafc;
  }
  
  h1 {
    font-family: "Inter", sans-serif;
  }
  
  form {
    font-family: "Roboto", sans-serif;
  }
  </style>

   