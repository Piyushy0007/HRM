<template>
  <div class="c-job-details px-10 pb-12 max-w-7xl mx-auto rounded-2xl shadow-2xl bg-gradient-to-br from-blue-50 to-white" >
    <!-- Two-Column Layout -->
    <div class="flex flex-wrap -mx-4">
      
      <!-- Left Section: Application Form -->
      <div class="w-full md:w-1/2 px-8">
        <div class="p-6 bg-white shadow-lg rounded-xl border-t-4 border-blue-600" >
          <h2 class="text-3xl font-bold text-blue-700 mb-4">Application Form</h2>
          
          <form @submit.prevent="submitApplication" class="space-y-6">
            <!-- Name -->
            <div>
              <label for="name" class="block text-lg font-medium text-gray-700">Full Name</label>
              <input
                type="text"
                id="name"
                v-model="form.name"
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
                v-model="form.email"
                class="mt-2 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Enter your email"
                required
              />
            </div>

            <!-- Phone Number -->
            <div>
              <label for="phone" class="block text-lg font-medium text-gray-700">Phone Number</label>
              <input
                type="tel"
                id="phone"
                v-model="form.phone"
                class="mt-2 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Enter your phone number"
                required
              />
            </div>
            <div>
              <label for="phone" class="block text-lg font-medium text-gray-700">Job Number</label>
              <input
                type="tel"
                id="phone"
                v-model="form.job_number"
                class="mt-2 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Enter your phone number"
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
  class="bg-[#2C1977] text-white px-6 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 disabled:opacity-50" style="background-color: #2C1977;"
  @click.prevent="submitApplication"
  :disabled="isSubmitting"
  aria-label="Apply for the job"
>
  <span v-if="!isSubmitting">Apply Job</span>
  <span v-else>Submitting...</span>
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
      uploadedFileName: null,
      isDescriptionVisible: false,

      form: {
        name: '',
        email: '',
        phone: '',
        resume: null,
        job_number: 0,
      },
    };
  },
  methods: {
    triggerFileInput() {
      this.$refs.fileInput.click();
    },
    handleFileUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.form.resume = file; // Save the file in form data
        this.uploadedFileName = file.name;
      }
    },
    async submitApplication() {
  if (this.form.name && this.form.email && this.form.phone && this.form.resume) {
    try {
      const formData = new FormData();
      formData.append('name', this.form.name);
      formData.append('email', this.form.email);
      formData.append('phone', this.form.phone);
      formData.append('resume', this.form.resume);
      formData.append('job_number', this.form.job_number || 0);

      // Debugging
      for (let [key, value] of formData.entries()) {
        console.log(key, value);
      }

      const response = await axios.post('http://127.0.0.1:8000/api/apply-job', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      alert('Application submitted successfully!');
      console.log('Response:', response.data.data);

      // Reset form
      this.form = { name: '', email: '', phone: '', resume: null, job_number: 0 };
      this.uploadedFileName = null;
    } catch (error) {
      console.error('Error submitting application:', error.response.data);
      alert(error.response.data.message || 'Failed to submit application. Please try again later.');
    }
  } else {
    alert('Please fill out all fields and upload your resume.');
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
