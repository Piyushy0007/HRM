<template>
  <div class="c-job-details px-10 pb-12 max-w-7xl mx-auto rounded-2xl shadow-2xl bg-gradient-to-br from-blue-50 to-white" >
    <!-- Two-Column Layout -->
    <div class="flex flex-wrap -mx-4">
      
      <!-- Left Section: Application Form -->
      <div class="w-full md:w-1/2 px-8">
        <div class="p-6 bg-white shadow-lg rounded-xl border-t-4 border-blue-600" >
          <h2 class="text-3xl font-bold text-blue-700 mb-4">Application Form</h2>
          
          <form   @submit.prevent="submitApplication"  class="space-y-6">
            <!-- Name -->
            <div>
              <label for="name" class="block text-lg font-medium text-gray-700">Full Name</label>
              <input
                type="text"
                id="name"
                name="name"
                v-model="formData.name"
                 @input="handleChange"

                class="mt-2 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Enter your full name"
                required
              />
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-lg font-medium text-gray-700">Email</label>
              <input
                type="email"
                id="email"
                name="email"
                v-model="formData.email"
                 @input="handleChange"

                class="mt-2 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Enter your email"
                required
              />
            </div>

            <!-- Phone Number -->
            <div>
              <label for="phone" class="block text-lg font-medium text-gray-700">Phone Number</label>
              <input
                type="text"
                id="phone"
                name="phone_number"
                v-model="formData.phone_number"
                 @input="handleChange"

                class="mt-2 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Enter your phone number"
                required
              />
            </div>
            <div>
              <label for="phone" class="block text-lg font-medium text-gray-700">Job Number</label>
              <input
                type="text"
                id="phone"
                name="job_number"
                v-model="formData.job_number"
                 @input="handleChange"

                class="mt-2 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Enter your job number"
                required
              />
            </div>

            <!-- Resume Upload -->
            <div>
              <div 
                @click="triggerFileInput" 
                class="cv-option flex items-center cursor-pointer border p-3 rounded-lg hover:bg-gray-50"
              >
                <div class="icon text-xl">📄</div>
                <div class="content ml-3">
                  <p class="label font-semibold">{{ uploadedFileName || "Upload Resume" }}</p>
                  <p class="info text-sm text-gray-500">
                    {{ uploadedFileName ? "Click to replace the file" : "No file uploaded yet" }}
                  </p>
                </div>
              </div>

              <!-- Visually hidden file input -->
              <input
                type="file"
                id="resume"
                name="resume"
                @change="handleFileUpload"
                class="hidden"
                ref="fileInput"
                
              />
            </div>

            <div class="flex justify-end mt-4" style="margin-top: 5%;">
            <!-- <button type="button" class="bg-gray-300 text-black px-6 py-2 rounded-lg hover:bg-gray-400"
            @click="prevStep">Back</button> -->
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
      
      <!-- Right Section: Job Description -->
      <div class="w-full md:w-1/2 px-4">
    <div class="p-6 bg-white shadow-lg rounded-xl border-t-4 border-gray-400">
      <h2 class="text-3xl font-bold text-gray-800 mb-4">Job Description</h2>
      <p class="text-gray-700 leading-relaxed mb-4">
        <strong>Position:</strong> Production Planning and Quality Incharge
      </p>
      <p class="text-gray-700 leading-relaxed mb-4">
        <strong>Company:</strong> BROHAWK EXPORTS
      </p>
      <p class="text-gray-700 leading-relaxed mb-4">
        <strong>Location:</strong> Mohali, Punjab
      </p>
      <p class="text-gray-700 leading-relaxed">
        Knowledge of Raw Material, Castings, Welding, Pressing Inspection.
        Able to do daily production planning and capable of managing workstations efficiently.
      </p>
      <!-- Hidden content -->
      <div v-if="isDescriptionVisible" class="mt-4">
        <p class="text-gray-700 leading-relaxed">
          <strong>Additional Responsibilities:</strong> Ensuring quality standards for all production activities. Coordinating with the purchasing department for raw material procurement.
        </p>
        <p class="text-gray-700 leading-relaxed mt-2">
          <strong>Requirements:</strong> Strong knowledge of manufacturing processes and excellent team management skills.
        </p>
      </div>
      <!-- Toggle button -->
      <button
        @click="toggleDescription"
        class="text-blue-500 hover:text-blue-700 font-semibold mt-4"
      >
        {{ isDescriptionVisible ? "Hide full job description ⬆️" : "View full job description ⬇️" }}
      </button>
    </div>
  </div>
    </div>
  </div>
</template>

<script>

import axios from 'axios'; // Import Axios if not already added

export default {
  data() {
    return {

    
      

      formData: {
        name: '',
        email: '',
        phone_number: '',
        resume: null,
        job_number: this.$route.query.jobId,
        location:"delhi" 
      },
      

      uploadedFileName: null,
      isDescriptionVisible: false,
       isSubmitting: false,
    };
  },
  methods: {




    handleChange(event) {
      const { name, value } = event.target;
      this.formData[name] = value;  
    },
    triggerFileInput() {
      this.$refs.fileInput.click();
    },
    handleFileUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.formData.resume = file; 
        this.uploadedFileName = file.name;
      }
    },




    async submitApplication() {
 console.log("Submitting formData:", this.formData)

    const formDataToSend = new FormData();

     for (const key in this.formData) {
    if (this.formData[key] !== null && this.formData[key] !== undefined) {
      formDataToSend.append(key, this.formData[key]);
    }
  }
  if (this.formData.resume) {
    formDataToSend.append("resume", this.formData.resume);
  }

       

     try {
   
    const response = await axios.post("/api/apply-job", formDataToSend, {
  headers: {
     "Content-Type": "multipart/form-data",
  },
});
console.log(response,"kkkkkkkkkkkkk")
    console.log("Job created successfully:", response.data);
    alert("Job created successfully!");
    
  } catch (error) {
    console.error("Error creating job:", error);
    if (error.response) {
      console.error("Backend response:", error.response.data);
      alert("Failed to create job. Check console for details.");
    } else {
      alert("Network or unexpected error occurred.");
    }
  }
 
}
,
    toggleDescription() {
      this.isDescriptionVisible = !this.isDescriptionVisible; // Toggle visibility
    },
  },
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

.cv-option {
  border: 2px dashed #2C1977;
}

.cv-option:hover {
  background-color: #f0f4ff;
}

button:hover {
  transform: scale(1.05);
}

</style>
