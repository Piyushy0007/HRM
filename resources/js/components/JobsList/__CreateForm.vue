
  


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


    <!-- Job posting location -->
          
<div class="mb-6">
        <!-- <h2 class="text-lg font-semibold text-gray-800 mb-4">Job posting location</h2> -->
<div class="mb-4 mt-4">
    <label
      for="locationOption"
      class="text-base font-bold text-gray-800 mb-4"
    >
      Number of People you wish to hire for this job<span class="text-red-500">*</span>
    </label>
    <select
      id="locationOption"
      class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
    >
      <option value="">Select and option</option>
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
      <option value="6">6</option>
      <option value="7">7</option>
      <option value="8">8</option>
      <option value="9">9</option>
      <option value="10">10</option>
    </select>
  </div>
  <div class="mb-4">
    <label
      for="locationOption"
      class="text-base font-semibold text-gray-800 mb-4"
    >
      Recruitment timeline for this job<span class="text-red-500">*</span>
    </label>
    <select
      id="locationOption"
      class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
    >
    <option value="">Select and option</option>

      <option value="1">1 to 3 days</option>
      <option value="2">3 to 7 days</option>
      <option value="3">1 to 2 weeks</option>
      <option value="4">2 to 4 weeks</option>
      <option value="5">More than 4 weeks</option>
    </select>
  </div>

  <div class="schedule-filter">
    <h3 class="text-base font-semibold text-gray-800 mb-4">Schedule</h3>
    <div class="filter-options">
      <!-- Loop through schedule types array and display each item in a row -->
      <div
        v-for="(label, index) in scheduleTypes"
        :key="index"
        class="filter-option"
        :class="{ 'active': selectedSchedule === label }"
        @click="toggleSchedule(label)"
      >
        <span class="plus-icon">+</span> {{ label }}
      </div>
    </div>
  </div>

  <div class="mb-4 mt-4">
    <label for="startDateOption" class="text-base font-semibold text-gray-800 mb-4">
      Is there a planned start date for this job?<span class="text-red-500">*</span>
    </label>
    <div class="flex flex-col space-y-4">
      <!-- Radio buttons for Yes/No options -->
      <div class="flex items-center space-x-4">
        <div class="flex items-center">
          <input
            type="radio"
            id="yesOption"
            name="startDateOption"
            value="yes"
            class="focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            v-model="startDateOption"
            @change="toggleDatePicker"
          />
          <label for="yesOption" class="ml-2 text-gray-800">Yes</label>
        </div>
        <div class="flex items-center">
          <input
            type="radio"
            id="noOption"
            name="startDateOption"
            value="no"
            class="focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            v-model="startDateOption"
            @change="toggleDatePicker"
          />
          <label for="noOption" class="ml-2 text-gray-800">No</label>
        </div>
      </div>

      <!-- Date picker section, shown below radio buttons -->
      <div v-show="showDatePicker" id="datePickerSection" class="mt-4">
        <label for="startDate" class="text-base font-semibold text-gray-800 mb-4">
          Select a start date for the job:
        </label>
        <input
          type="date"
          id="startDate"
          class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        />
      </div>
    </div>
  </div>

  </div>
  </div>
  <div class="job-filter">
    <h3 class="text-base font-semibold text-gray-800 mb-4">Job type</h3>
   <div class="filter-options">
      <div
        v-for="(label, index) in jobTypes"
        :key="index"
        class="filter-option"
        :class="{ 'active': selectedJobType === label }"
        @click="toggleJobType(label)"
      >
        <span class="plus-icon">+</span> {{ label }}
      </div>
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
              class="bg-[#2C1977] text-white px-6 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2"
            style="background-color:#2C1977 "
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
      startDateOption: null, // Tracks selected radio button value
      showDatePicker: false,
      selectedJobType: "Full-time",  
      jobTypes: [
        "Full-time",
        "Permanent",
        "Fresher",
        "Part-time",
        "Internship",
        "Contractual / Temporary",
        "Freelance",
        "Volunteer"
      ],
      selectedSchedule: "Day shift",  // Default selected schedule type
      // Schedule types available
      scheduleTypes: [
        "Day shift",
        "Morning shift",
        "Rotational shift",
        "Night shift",
        "Monday to Friday",
        "Evening shift",
        "Weekend availability",
        "Fixed shift",
        "US shift",
        "UK shift",
        "Weekend only",
        "Other"
      ]
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
    toggleDatePicker() {
      this.showDatePicker = this.startDateOption === 'yes';
    },
    toggleJobType(jobType) {
      if (this.selectedJobType === jobType) {
        this.selectedJobType = ""; 
      } else {
        this.selectedJobType = jobType;
      }
    },
    

    toggleSchedule(schedule) {
     
      if (this.selectedSchedule === schedule) {
        this.selectedSchedule = ""; 
      } else {
        this.selectedSchedule = schedule;
      }
    }
  
  
},

  };
 
  
  </script>
  
  <style scoped>
 
  body {
    background-color: #f8fafc;
  }
  
  h1 {
    font-family: "Inter", sans-serif;
  }
  
  form {
    font-family: "Roboto", sans-serif;
  }

  .job-filter {
  font-family: Arial, sans-serif;
}

.filter-options {
  display: flex;
  gap: 10px;
  flex-wrap: wrap; 
}

.filter-option {
  display: flex;
  align-items: center;
  padding: 5px 10px;
  border: 1px solid #ccc;
  border-radius: 20px;
  cursor: pointer;
  transition: background-color 0.3s ease, border-color 0.3s ease;
}

.filter-option.active {
  background-color: #2C1977;
  color: white;
  border-color: #4caf50;
}

.filter-option:hover {
  background-color: #f1f1f1;
}

.plus-icon {
  margin-right: 5px; 
  font-weight: bold;
}

h3 {
  font-size: 18px;
  margin-bottom: 10px;
}
  </style>

   