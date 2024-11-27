
  


 <template>
    <div class="c-create-page px-8 pb-8 w-3/3 mx-auto  relative" style="margin-right: 1vw;">
      <header-component class="sticky top-0 z-50 bg-white shadow-md" />
      <div class="container mx-auto pt-5 justify-center w-full" style="margin-left: 10%;">
        <h1 class="text-2xl font-bold mb-4 text-center text-gray-800">Create Job Form</h1>
        <form class="bg-white p-10 rounded-2xl shadow-lg border border-gray-200 max-w-5xl w-full mx-auto space-y-8">
          

          <div v-if="step === 1">

          <div>
            <label for="jobTitle" class="block text-sm font-bold text-black-700 mb-1">Job Title</label>
            <input
              type="text"
               v-model="formData.job_title"
               name="job_title"
                @input="handleChange"
              id="jobTitle"
              placeholder="Enter job title"
              class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500"
            />
            <span v-if="validationErrors.job_title" class="text-red-500 text-sm">
        {{ validationErrors.job_title }}
      </span>
          </div>

           <!-- Salary -->
           <div>
            <label for="jobSalary" class="block text-sm font-bold text-black-700 mb-1">Salary</label>
            <input
              type="number"
               v-model="formData.salary"
               name="salary"
               @input="handleChange"

              id="jobSalary"
              placeholder="Enter salary"
              class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500"
            />
            <span v-if="validationErrors.salary" class="text-red-500 text-sm">
        {{ validationErrors.salary }}
      </span>
          </div>
          <!-- Job Location -->
          <div>
            <label for="jobLocation" class="block text-sm font-bold text-black-700 mb-1">City</label>
            <input
              type="text"
               v-model="formData.city"
               name="city"
               @input="handleChange"

              id="jobLocation"
              placeholder="Enter job location"
              class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500"
            />
            <span v-if="validationErrors.city" class="text-red-500 text-sm">
        {{ validationErrors.city }}
      </span>
          </div>
          

         
<!-- Job posting location -->
          


  <!-- Pin code Area -->
    <div class="grid grid-cols-2 gap-4">
                <div>
                <label
                    for="area"
                    class="block text-sm font-bold text-black-700 mb-1"
                >
                    Area
                </label>
                <select
                    id="area"
                    class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                 v-model="formData.area"
                 name="area"
               @change="handleChange"

                    >
                    <option value="">Select area</option>
                    <option value="area1">Area 1</option>
                    <option value="area2">Area 2</option>
                </select>
                </div>
                <div>
                <label
                    for="pincode"
                    class="block text-sm font-bold text-black-700 mb-1"
                >
                    Pincode
                </label>
                <input
                    type="text"
                 v-model="formData.pincode"
                 name="pincode"
               @input="handleChange"


                    id="pincode"
                    placeholder="Enter pincode"
                    class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                />
                </div>
            </div>

<div class="mt-4">

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
      
      v-model="formData.job_posting_location"
               name="job_posting_location"
               @input="handleChange"
      class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
    
      >
      <option value="on-site">On-site</option>
      <option value="remote">Remote</option>
      <option value="hybrid">Hybrid</option>
    </select>
  </div>
</div>


    
         
    
          <div class="flex justify-end mt-4">
            <!-- <button type="button" class="bg-gray-300 text-black px-6 py-2 rounded-lg hover:bg-gray-400" disabled>Back</button> -->
            <button type="button" @click.prevent="validateAndContinue" class="bg-[#2C1977] text-white px-6 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2" 
            style="background-color:#2C1977"
            >Continue</button>
          </div>
          
        </div>
    
          
     
    
          
          <!-- Resume Upload -->
    <!-- Icon as File Upload Button -->
    <!-- <label for="resumeUpload" class="block text-sm font-bold text-black-700 mb-1">
      Upload Resume
    </label> -->
    <!-- <div
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
    </div> -->



    <!-- Display Uploaded File -->
    <!-- <div v-if="uploadedFile" class="mt-4">
      <p class="text-sm text-gray-700">Uploaded File:</p>
      <a
        :href="uploadedFileUrl"
        target="_blank"
        class="text-blue-500 hover:underline"
      >{{ uploadedFile.name }}</a>
    </div> -->


    <!-- Job posting location -->


    <div  v-if="step === 2">
     

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
      v-model="formData.people_to_hire"
      name="people_to_hire"
      @change="handleChange"


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

      v-model="formData.recruitment_timeline"
name="recruitment_timeline"
      @change="handleChange"


      class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
    >
    <option value="">Select and option</option>

      <option value="1 to 3 days">1 to 3 days</option>
      <option value="3 to 7 days">3 to 7 days</option>
      <option value="1 to 2 weeks">1 to 2 weeks</option>
      <option value="2 to 4 weeks">2 to 4 weeks</option>
      <option value="More than 4 weeks">More than 4 weeks</option>
    </select>
  </div>
    <h2 class="text-lg font-semibold text-gray-800 mb-4" style="margin-top: 5%;">Application Preferences</h2>

    <!-- Dropdown for CV -->
    <div class="mb-6">
      <label for="cv-option" class="text-base font-semibold text-gray-800 mb-4">
        Ask potential candidates for a CV?
      </label>
      <select
        id="cv-option"
        class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        v-model="formData.cv_option"
name="cv_option"
      @change="handleChange"
      >
        <option value="">Select and option</option>
        <option value="1">Yes,Require a CV</option>
        <option value="2">Make it optional</option>
        <!-- <option value="include">Give the option to include a CV</option> -->
      </select>
    </div>

    <!-- Radio buttons for deadline -->
    <div class="mb-6">
      <label class="text-base font-semibold text-gray-800 mb-4">
        Is there an application deadline?
      </label>
      <div class="flex space-x-4">
       
        <label class="flex items-center space-x-2">
          <input
            type="radio"
            value="true"
            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
           v-model="formData.application_deadline"
          />
          <span class="text-gray-700">Yes</span>
        </label>
        <label class="flex items-center space-x-2">
          <input
            type="radio"
            value="false"
           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500"
          v-model="formData.application_deadline"
           />
          <span class="text-gray-700">No</span>
        </label>
      </div>

      <div class="mb-4" style="margin-top: 5%;">
    <label for="startDateOption"class="text-base font-semibold text-gray-800 mb-4">
      Is there a planned start date for this job?<span class="text-red-500">*</span>
    </label>
    <div class="flex flex-col space-y-4">
      <!-- Radio buttons for Yes/No options -->
      <div class="flex items-center space-x-4">
        <div class="flex items-center">
          <input
            type="radio"
            id="yes"
            name="planned_start_date"

            value="true"
            class="focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            v-model="formData.planned_start_date"

            @change="toggleDatePicker"
          />
          <label for="true" class="ml-2 text-gray-800">Yes</label>
        </div>
        <div class="flex items-center">
          <input
            type="radio"
            id="no"
            name="planned_start_date"
            value="false"
            class="focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            v-model="formData.planned_start_date"

            @change="toggleDatePicker"
          />
          <label for="false" class="ml-2 text-gray-800">No</label>
        </div>
      </div>

      <!-- Date picker section, shown below radio buttons -->
      <div v-show="formData.planned_start_date === 'true'" id="datePickerSection" class="mt-4">
        <label for="start_date" class="text-base font-semibold text-gray-800 mb-4">
          Select a start date for the job:
        </label>
        <input
          type="date"
          id="start_date"
          class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          v-model="formData.start_date" 
           @change="handleChange"
          />
      </div>
    </div>
  </div>
    </div>

   
  


  <div class="flex justify-between mt-4">
            <button type="button" class="bg-gray-300 text-black px-6 py-2 rounded-lg hover:bg-gray-400" 
             @click="prevStep"
            >Back</button>
            <button type="button"@click.prevent="validateAndContinue" class="bg-[#2C1977] text-white px-6 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2" 
            style="background-color:#2C1977"
            >Continue</button>
          </div>

    </div>

    <div v-if="step === 3">

          
<div class="mb-6" style="margin-top: 2%;">


  <div class="schedule-filter" style="margin-top: 8%;">
    <h3 class="text-base font-semibold text-gray-800 mb-4">Schedule</h3>
    <div class="filter-options">
      <!-- Loop through schedule types array and display each item in a row -->
      <div
        v-for="(label, index) in scheduleTypes"
        :key="index"
        class="filter-option"
        :class="{ 'active': formData.schedule === label }"
        @click="toggleSchedule(label)"
      >
        <span class="plus-icon">+</span> {{ label }}
      </div>
    </div>
  </div>

  

  </div>
 
  <div class="job-filter" style="margin-top: 8%;">
    <h3 class="text-base font-semibold text-gray-800 mb-4">Job type</h3>
   <div class="filter-options">
      <div
        v-for="(label, index) in jobTypes"
        :key="index"
        class="filter-option"
        :class="{ 'active': formData.job_type === label }"
        @click="toggleJobType(label)"
      >
        <span class="plus-icon">+</span> {{ label }}
      </div>
    </div>
  </div>

    <div class="job-filter" style="margin-top: 8%;">
      <h2 class="text-base font-semibold text-gray-800 mb-4">Benefits</h2>
      

      <div class="filter-options">
      <div
        v-for="(label, index) in benefits"
        :key="index"
        class="filter-option"
        :class="{ 'active': formData.benefits === label }"
        @click="toggleBenefits(label)"
      >
        <span class="plus-icon">+</span> {{ label }}
      </div>
    </div>
    </div>
  <div class="flex justify-between mt-4">
            <button type="button" class="bg-gray-300 text-black px-6 py-2 rounded-lg hover:bg-gray-400" 
           
             @click="prevStep" 
           >Back</button>
            <button type="button" @click.prevent="validateAndContinue" class="bg-[#2C1977] text-white px-6 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2" 
            style="background-color:#2C1977"
            >Continue</button>
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



          
<div v-if="step === 4">
   <!-- Job Description -->
   <div>
            <label for="jobDescription" class="block text-sm font-bold text-black-700 mb-1">Job Description</label>
            <textarea
              id="jobDescription"
               v-model="formData.job_description"
               name="job_description"
      @input="handleChange"

              placeholder="Enter job description"
              class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500"
              rows="5"
            ></textarea>
          </div>

          <div class="flex justify-between mt-4">
            <button type="button" class="bg-gray-300 text-black px-6 py-2 rounded-lg hover:bg-gray-400" 
           
             @click="prevStep" 
           >Back</button>
            <button type="button" @click.prevent="validateAndContinue" class="bg-[#2C1977] text-white px-6 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2" 
            style="background-color:#2C1977"
            >Continue</button>
          </div>
</div>

          <div v-if="step === 5">

    <!-- Pay Section -->
    <div>
      <h2 class="text-lg font-semibold text-gray-800 mb-2">Pay</h2>
      <p class="text-sm text-gray-600 flex items-center">
        Review the pay we estimated for your job and adjust it as needed. Check your local minimum wage.
        <span
          class="ml-1 text-gray-500 hover:text-gray-800 cursor-pointer"
          title="Information about pay"
        >
          ℹ️
        </span>
      </p>
    </div>

     <!-- Rate Dropdown -->
     <div class="mt-4">
      <label for="rate" class="block text-sm font-bold text-black-700 mb-1">Rate</label>
      <select
        id="rate"
        
        v-model="formData.pay_rate_type"
               name="pay_rate_type"
      @input="handleChange"

        class="block w-full mt-1 p-2 border border-gray-300 rounded-md text-gray-800 focus:ring focus:ring-blue-300"
      
        >
        <option value="">Select Option</option>
        <option value="monthly">per month</option>
        <option value="yearly">per day</option>
      </select>
    </div>

    <!-- Minimum and Maximum Inputs -->
    <div class="flex items-center gap-4 mt-4">
      <!-- Minimum -->
      <div class="flex-1">
        <label for="minimum" class="block text-sm font-bold text-black-700 mb-1">Minimum</label>
        <div class="relative mt-1">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₹</span>
          <input
            type="text"
            id="minimum"
             v-model="formData.pay_minimum"
             name="pay_minimum"
      @input="handleChange"

            class="block w-full pl-8 p-2 border border-gray-300 rounded-md text-gray-800 focus:ring focus:ring-blue-300"
            value="10,466.49"
          />
        </div>
      </div>

      <!-- Maximum -->
      <div class="flex-1">
        <label for="maximum" class="block text-sm font-bold text-black-700 mb-1">Maximum</label>
        <div class="relative mt-1">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₹</span>
          <input
            type="text"
            id="maximum"
            v-model="formData.pay_maximum"
            name="pay_maximum"
            @input="handleChange"

            class="block w-full pl-8 p-2 border border-gray-300 rounded-md text-gray-800 focus:ring focus:ring-blue-300"
            value="28,854.26"
          />
        </div>
      </div>
    </div>


    <!-- Dropdown -->

    <!-- <div class="mt-4">
      <label for="payBy" class="block text-sm font-bold text-black-700 mb-1">Show pay by</label>
      <select
        id="payBy"
        class="block w-full mt-1 p-2 border border-gray-300 rounded-md text-gray-800 focus:ring focus:ring-blue-300"
      >
        <option>Range</option>
        <option>Fixed Amount</option>
      </select>
    </div> -->
   

      <h2 class="text-2xl font-semibold text-gray-800 mb-4" style="margin-top: 5%;">Communication Preferences</h2>

      <div class="mb-4">
        <label for="email" class="block text-sm font-bold text-black-700 mb-1">Send daily updates to</label>
        <input
          v-model="formData.email"
          type="email"
          id="email"
          class="mt-2 block w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
          placeholder="example@domain.com"
        />
      </div>

      <button @click="addEmail" class="text-blue-500 font-medium text-sm mb-4">+ Add email</button>

      <div class="mb-4">
        <label for="send-update" class="flex items-center space-x-2">
          <input
            type="checkbox"
            id="send-update"
            class="h-5 w-5 text-blue-600 border-gray-300 rounded"
          />
          <span class="text-sm text-gray-800">Plus, send an individual email update each time someone applies.</span>
        </label>
      </div>

      <hr class="my-4 border-gray-300" />

      <div class="mb-4">
        <label for="contact" class="flex items-center space-x-2">
          <input
            type="checkbox"
            id="contact"
            v-model="formData.communication_preference_email"
            class="h-5 w-5 text-blue-600 border-gray-300 rounded"
          />
          <span class="text-sm text-gray-800">Let potential candidates contact you about this job</span>
        </label>
      </div>

     
    
          <div class="flex justify-between mt-4" style="margin-top: 5%;">
            <button type="button" class="bg-gray-300 text-black px-6 py-2 rounded-lg hover:bg-gray-400"
            @click="prevStep">Back</button>

            <button
              type="submit"
              class="bg-[#2C1977] text-white px-6 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2"
            style="background-color:#2C1977 "
            @click.prevent="submitForm" >
              Create Job
            </button>
          </div>

          

        </div>
        <!-- <div v-if="step === 6">
    <!-- Header Section -->
    <!-- <div class="bg-white shadow-md sticky top-0 z-50 px-8 py-4">
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Review</h1>
        <!-- Add your header illustration or avatar here -->
        <!-- <div class="flex space-x-4">
          <img class="w-12 h-12 rounded-full" src="https://randomuser.me/api/portraits/men/1.jpg" alt="User" />
          <div class="flex items-center">
            <span class="text-sm text-gray-600">HR Executive</span>
            <button class="ml-2 text-blue-500 hover:text-blue-700">
              <i class="fas fa-edit"></i> Edit
            </button>
          </div>
        </div>
      </div> -->
    <!-- </div> -->

    <!-- Job Details Section -->
    <!-- <div class="container mx-auto p-6">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Job Details</h2>
      
      <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 space-y-4">
        <div class="flex justify-between items-center">
          <span class="font-medium text-gray-700">Job title</span>
          <div class="flex items-center">
            <span class="text-gray-600">HR Executive</span>
            <button class="ml-2 text-blue-500 hover:text-blue-700">
              <i class="fas fa-edit"></i> Edit
            </button>
          </div>
        </div>

        <div class="flex justify-between items-center">
          <span class="font-medium text-gray-700">Company for this job</span>
          <div class="flex items-center">
            <span class="text-gray-600">ARECLE</span>
            <button class="ml-2 text-blue-500 hover:text-blue-700">
              <i class="fas fa-edit"></i> Edit
            </button>
          </div>
        </div>

        <div class="flex justify-between items-center">
          <span class="font-medium text-gray-700">Number of openings</span>
          <div class="flex items-center">
            <span class="text-gray-600">1</span>
            <button class="ml-2 text-blue-500 hover:text-blue-700">
              <i class="fas fa-edit"></i> Edit
            </button>
          </div>
        </div>
      </div>
    </div> -->
<!-- </div> -->

          
        </form>
      </div>


      
    </div>
  </template>
  
  <script>
  import axios from 'axios'

  export default {

    data() {
    return {
      step: 1,
      uploadedFile: null, 
      uploadedFileUrl: null, 
      startDateOption: null,
      showDatePicker: false,
      formData: {
        job_title: "",
        salary: 0,
        city: "",
        area: "ABC",
        pincode: "",
        job_posting_location: "",
        people_to_hire: 1,
        recruitment_timeline: "",
        schedule: "9:00 AM - 6:00 PM",
        job_type: "Full-Time",
        benefits: "Health insurance",
        job_description: "",
        pay_minimum: 0,
        pay_maximum: 0,
        communication_preference_email: true,
        employee_id: null,
        // deadlineOption:"yes",
        // sendUpdate:"",
        cv_option:false,
        job_status:1,
        application_deadline:false,
        planned_start_date:true,
        start_date:"",
        pay_rate_type:""
        
      },
    validationErrors: {},
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
      ],
      
      benefits: [
        "Health insurance",
        "Paid sick time",
        "Life insurance",
        "Leave encashment",
        "Provident Fund",
        "Work from home",
        "Internet reimbursement",
        "Flexible schedule",
        "Cell phone reimbursement",
        "Paid time off",
        "Food provided",
        "Commuter assistance",
        "Other"
      ]
    };
    
  },
    name: "CreateJobPage",
    name: "PayUI",
    name: 'JobReview',

    mounted() {
    const storedEmployeeId = localStorage.getItem('userId');
    
    if (storedEmployeeId) {
      this.formData.employee_id = storedEmployeeId;
    } else {
      console.log('Employee ID not found in localStorage');
    }
  },

    methods: {

      
      
      
    validateForm() {
      this.validationErrors = {}; 
      if (this.step === 1) {
        if (!this.formData.job_title) {
          this.validationErrors.job_title = "Job title is required.";
          alert("Job title is required.")
        }
        if (!this.formData.salary || isNaN(this.formData.salary)) {
          this.validationErrors.salary = "Salary must be a valid number.";
        }
        if (!this.formData.city) {
          this.validationErrors.city = "City is required.";
        }
      }
      if (this.step === 2) {
       
      }
      if (this.step === 3) {
       
      }
      if (this.step === 4) {
       
      }

      return Object.keys(this.validationErrors).length === 0;

    
},
validateAndContinue() {
      if (this.validateForm()) {
        this.nextStep(); 
      }
    },

      nextStep() {
  if (this.step < 5) this.step++
},
prevStep() {
  if (this.step > 1) this.step--; 
},

    handleFileUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.uploadedFile = file;

        this.uploadedFileUrl = URL.createObjectURL(file);
      }
    },
    toggleDatePicker() {
      // this.planned_start_date = this.planned_start_date === true;
      console.log(this.formData.planned_start_date);
    },
    toggleJobType(jobType) {
      if (this.formData.job_type   === jobType) {
        this.formData.job_type = ""; 
      } else {
        this.formData.job_type = jobType;
      }
    },
    

    toggleSchedule(schedule) {
     
      if (this.formData.schedule === schedule) {
        this.formData.schedule  = ""; 
      } else {
        this.formData.schedule  = schedule;
      }
    },
    toggleBenefits(benefits) {
     
     if (this.formData.benefits === benefits) {
       this.formData.benefits = ""; 
     } else {
       this.formData.benefits = benefits;
     }
   },
   addEmail() {
      alert('You can add more email fields here!');
    },


    handleDateChange(event) {
      console.log("Selected start date:", this.formData.start_date);
    },
    handleChange(event) {
      const { name, value } = event.target;
      if (name) {
    if (["pay_minimum", "pay_maximum","salary","people_to_hire"].includes(name)) {
      const numericValue = parseInt(value.replace(/,/g, ""), 10) || 0;
      this.formData[name] = numericValue; 
    } else {
      this.formData[name] = value;
    }
  }
      // console.log("Updated formData:", this.formData); 
    },

async submitForm() {
  // if (!this.validateForm()) {
  //   return; 
  // }
  try {
    console.log("Submitting formData:", this.formData);
    const response = await axios.post("/api/jobs", this.formData, {
  headers: {
    "Content-Type": "application/json",
  },
});
    console.log("Job created successfully:", response.data);
    alert("Job created successfully!");
    this.$router.push('/listing')
  } catch (error) {
    console.error("Error creating job:", error);
    if (error.response) {
      console.error("Backend response:", error.response.data);
      alert("Failed to create job. Check console for details.");
    } else {
      alert("Network or unexpected error occurred.");
    }
  }
},

  
  
  
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

   