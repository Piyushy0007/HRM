<template>
   <div class="contsiner-fluid">
       <div class="row">
   
                       <div class="d-none d-md-block col-md-2 pr-0 sidebar">
                           <header-component />
                         </div>
                         <div class="col-12 col-md-10 content px-2">
                           
                             <div class="c-attendance-page p-0" >
                             
                                 <div class="selection-function px-4 pt-2 pb-3 flex justify-between">
                                   <div>
                                     <span class="text-lg font-bold" style="float:left;">Monthly Attendance</span>
                                   </div>
                                 </div>
                               <div>
                                   <table class="w-full border-gray-300">
                                       <thead>
                                           <tr class="bg-gray-300">
                                               <th class="text-center "> Employee Name </th>
                                               <th class="text-center "> Present </th>
                                               <th class="text-center "> Absent </th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                           <tr>
                                               <td class="text-center "> John Doe </td>
                                               <td class="text-center "> 20 </td>
                                               <td class="text-center "> 2 </td>
                                           </tr>
                                           <tr>
                                               <td class="text-center "> Jane Smith </td>
                                               <td class="text-center "> 18 </td>
                                               <td class="text-center "> 4 </td>
                                           </tr>
                                           <tr>
                                               <td class="text-center "> Michael Brown </td>
                                               <td class="text-center "> 22 </td>
                                               <td class="text-center "> 0 </td>
                                           </tr>
                                       </tbody>
                                   </table>
                               </div>
                               <div class="information mt-5 mx-4 p-4 bg-white border border-gray-300 rounded">
                                   <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2">
                                       <h4 class="text-lg font-semibold mb-2 mb-md-0">Information</h4>
                                       <a href="#" class="text-sm text-blue-600"
                                           >Help on this topic <strong>More</strong></a
                                       >
                                   </div>
                                   <ul class="list-disc pl-5">
                                       <li>
                                           Attendance details for employees are displayed
                                           month-wise.
                                       </li>
                                   </ul>
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
            // Static data handled in the template directly
        };
    }
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
