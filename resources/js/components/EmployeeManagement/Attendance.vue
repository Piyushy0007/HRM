<template>
   <div>
       <header-component />
    <div style="margin-left: 242px;">
        <div class="max-w-7xl mx-auto p-4 h-screen flex flex-col">
           <div
            class="flex items-center justify-between bg-white p-4 shadow-md rounded-lg mb-5"
          >
            <!-- Left Section -->
            <div class="flex items-center">
              <span class="font-semibold text-lg text-gray-800">Attendance</span>
              <span class="text-gray-600 ml-1">Month- {{ selectedMonth }}</span>
            </div>
    
            <!-- Right Section -->
            <div class="flex items-center space-x-4">
              <!-- Year Select -->
              <div class="flex items-center">
                <label for="year" class="text-gray-600 mr-1">Year</label>
                <select
                  id="year"
                  class="border border-gray-300 rounded-md px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                  <option>2021</option>
                  <option>2022</option>
                  <option>2023</option>
                  <option>2024</option>
                </select>
              </div>
    
              <!-- Month Select -->
              <div class="flex items-center">
                <label for="month" class="text-gray-600 mr-1">Month</label>
                <select
                  v-model="selectedMonth"
                  id="month"
                  class="border border-gray-300 rounded-md px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                <option value="January">Jan</option>
                <option value="February">Feb</option>
                <option value="March">Mar</option>
                <option value="April">Apr</option>
                <option value="May">May</option>
                <option value="June">Jun</option>
                <option value="July">Jul</option>
                <option value="August" selected>Aug</option>
                <option value="September">Sep</option>
                <option value="October">Oct</option>
                <option value="November">Nov</option>
                <option value="December">Dec</option>
                </select>
              </div>
            </div>   
            </div>   
         <!-- Table Container -->
         <div class="bg-white shadow-lg rounded-lg flex-grow flex flex-col">
           <!-- Table Header -->
           <div class="flex-grow overflow-auto">
             <table class="w-full table-auto">
               <thead>
                 <tr class="text-left border-b">
                   <th class="py-3 px-4 text-blue-600">Employee</th>
                   <th class="py-3 px-4 text-blue-600">Designation</th>
                   <th class="py-3 px-4 text-blue-600">Date</th>
                   <th class="py-3 px-4 text-blue-600">Check-in Time</th>
                   <th class="py-3 px-4 text-blue-600">Checkout Time</th>
                   <th class="py-3 px-4 text-blue-600">Status</th>
                   <th class="py-3 px-4 text-blue-600">Details</th>
                 </tr>
               </thead>
               <tbody>
                 <!-- Row 1 -->
                 <tr class="border-b">
                   <td class="py-3 px-4 flex items-center gap-2">
                     <img
                       src="https://via.placeholder.com/40"
                       alt="Employee"
                       class="rounded-full"
                     />
                     John Doe
                   </td>
                   <td class="py-3 px-4">Software Engineer</td>
                   <td class="py-3 px-4">20-08-2021</td>
                   <td class="py-3 px-4">05-12-1992</td>
                   <td class="py-3 px-4">03008951423</td>
                   <td class="py-3 px-4">Leave</td>
                   <td class="py-3 px-4">
                     <button
                       class="text-blue-500 border border-blue-400 px-4 py-1 rounded-full hover:bg-blue-100"
                     >
                       View
                     </button>
                   </td>
                 </tr>
       
                 <!-- Row 2 -->
                 <tr class="border-b">
                   <td class="py-3 px-4 flex items-center gap-2">
                     <img
                       src="https://via.placeholder.com/40"
                       alt="Employee"
                       class="rounded-full"
                     />
                     John Doe
                   </td>
                   <td class="py-3 px-4">Software Engineer</td>
                   <td class="py-3 px-4">20-08-2021</td>
                   <td class="py-3 px-4">05-12-1992</td>
                   <td class="py-3 px-4">03008951423</td>
                   <td class="py-3 px-4">Present</td>
                   <td class="py-3 px-4">
                     <button
                       class="text-blue-500 border border-blue-400 px-4 py-1 rounded-full hover:bg-blue-100"
                     >
                       View
                     </button>
                   </td>
                 </tr>
       
                 <!-- Row 3 -->
                 <tr class="border-b">
                   <td class="py-3 px-4 flex items-center gap-2">
                     <img
                       src="https://via.placeholder.com/40"
                       alt="Employee"
                       class="rounded-full"
                     />
                     John Doe
                   </td>
                   <td class="py-3 px-4">UI/UX Designer</td>
                   <td class="py-3 px-4">20-08-2021</td>
                   <td class="py-3 px-4">05-12-1992</td>
                   <td class="py-3 px-4">03008951423</td>
                   <td class="py-3 px-4">Absent</td>
                   <td class="py-3 px-4">
                     <button
                       class="text-blue-500 border border-blue-400 px-4 py-1 rounded-full hover:bg-blue-100"
                     >
                       View
                     </button>
                   </td>
                 </tr>
               </tbody>
             </table>            
           </div>
     
           <!-- Pagination Section -->
           <div class="flex items-center justify-between p-4 border-t">
             <div class="flex items-center gap-2">
               <label for="show" class="text-gray-600">Show</label>
               <select
                 id="show"
                 class="border border-gray-300 rounded px-2 py-1 focus:outline-none"
               >
                 <option value="6">6</option>
                 <option value="12">12</option>
                 <option value="24">24</option>
               </select>
             </div>
             <div class="flex items-center gap-1">
               <button 
               @click="prevPage"
               :disabled="currentPage === 1"
               class="text-blue-500 px-2 py-1 hover:bg-blue-100 disabled:text-gray-400">&laquo;</button>
               <button
                    v-for="page in totalPages"
                    :key="page"
                    @click="goToPage(page)"
                    :class="[
                        'px-3 py-1 rounded',
                        page === currentPage
                        ? 'bg-blue-500 text-white hover:bg-blue-600'
                        : 'text-blue-500 border border-blue-400 hover:bg-blue-100',
                    ]"
                    >
                    {{ page }}
                </button>

                <button
                @click="nextPage"
                :disabled="currentPage === totalPages"
                class="text-blue-500 px-2 py-1 hover:bg-blue-100 disabled:text-gray-400"
              >
                &raquo;
              </button>
             </div>
           </div>
         </div>
       </div>
    </div>

   </div>
</template>

<script>
export default {
    name: "attendance",
    data() {
        return {
            selectedYear: "2021", // Default year
            selectedMonth: "August", // Default month
            currentPage: 1, // Tracks the current active page
            totalPages: 3, // Total number of pages (adjustable)
        };
    },
    methods: {
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    goToPage(page) {
      this.currentPage = page;
    },
  },
};
</script>

<style lang="scss" scoped>
.main-of-attandance {
  margin-left: 220px;
}
.content {
  overflow-y: auto; 
}


.c-attendance-page {
    .selection-function {
        background-color: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    table {
      th {
        background-color: #e5e7eb;
        padding: 10px;
      }
      td {
        padding: 10px;
        border: 1px solid #e5e7eb;
      }
    }

    .information {
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
    }
    .sidebar {
        height: 100vh;
    }
}
</style>
