<template>
  <div>
    <Loader msg="Processing ..." v-model="isLoader" />

    <header-component @position="selectedPosition" v-if="this.userRole == 'admin' " />
    <client-header-component @position="selectedPosition" v-if="this.userRole != 'admin' " />

    <div class="bv-example-row mx-auto"  v-if="this.userRole != 'admin' ">
    

      <b-row>
        <b-col cols="2" style="flex: 0 0 auto;width: 16.66666667%;float: left;">
        <ul class="justify-center mx-auto mt-3" style="margin-top: -50px;">
          <router-link  :to="{ name: 'cd' }" class="md:w-40 xl:w-64 mr-1 mb-3 mt-3 ">
            <img style="height:50px; margin-left:80px; margin-top:30px" src="/images/logo.png" alt="Eyewitness" class="">
          </router-link>
          <router-link tag="li" :to="{ name: 'schedules' }"  class="flex mr-1"> 
          <div class="leftindex">
                    <div class="leftimage">
                        <img src="/images/build.png" alt="" v-if="$route.path == '/schedules/' || $route.path == '/schedules'">
                        <img src="/images/propertty_unsel.png" alt="" v-if="$route.path !== '/schedules/' && $route.path !== '/schedules'">
                    </div>
                    <div class="lefttext ">
                      <a href="#target1"  id='target1' class='target' v-if="$route.path !== '/schedules/' && $route.path !== '/schedules'"> Schedules</a>
                        <a href="#target1"  id='target1' class='target' v-if="$route.path == '/schedules/' || $route.path == '/schedules'" style="color:#AD9E58 !important"> Schedules</a>
                    </div>
                </div>
        </router-link>
        
          <router-link tag="li" :to="{ name: 'employees' }"  class="flex mr-1"> 
                <div class="leftindex">
                    <div class="leftimage">
                        <img src="/images/build.png" alt="" v-if="$route.path == '/employees/' || $route.path == '/employees'">
                        <img src="/images/propertty_unsel.png" alt="" v-if="$route.path !== '/employees/' && $route.path !== '/employees'">
                    </div>
                    <div class="lefttext ">
                      <a href="#target1"  id='target1' class='target' v-if="$route.path !== '/employees/' && $route.path !== '/employees'"> Users</a>
                        <a href="#target1"  id='target1' class='target' v-if="$route.path == '/employees/' || $route.path == '/employees'" style="color:#AD9E58 !important"> Users </a>  
                    </div>
                </div>
          </router-link>

          <router-link v-if="this.userRole == 'admin' " tag="li" :to="{ name: 'cproperties' }"  class="flex mr-1"> 
                <div class="leftindex">
                    <div class="leftimage">
                        <img src="/images/build.png" alt="" v-if="$route.path == '/cd/' || $route.path == '/cd'">
                        <img src="/images/propertty_unsel.png" alt="" v-if="$route.path !== '/cd/' && $route.path !== '/cd'">
                    </div>
                    <div class="lefttext ">
                      <a href="#target1"  id='target1' class='target' v-if="$route.path !== '/cd/' && $route.path !== '/cd'"> Properties</a>
                        <a href="#target1"  id='target1' class='target' v-if="$route.path == '/cd/' || $route.path == '/cd'" style="color:#AD9E58 !important"> Properties</a>
                    </div>
                </div>
          </router-link>

          <router-link tag="li" :to="{ name: 'creport' }" class="flex mr-1 ">
            <div class="leftindex">
                    <div class="leftimage">
                        <img src="/images/file_sel.png" alt="" v-if="$route.path == '/cd/creport/' || $route.path == '/cd/creport/ticket'">
                        <img src="/images/file.png" alt="" v-if="$route.path !== '/cd/creport/' && $route.path !== '/cd/creport/ticket'">
                    </div>
                    <div class="lefttext">
                        <a href="#target2"  id='target2' class='target' v-if="$route.path == '/cd/creport/' || $route.path == '/cd/creport/ticket'" style="color:#AD9E58 !important"> Reports</a>
                        
                         <a href="#target2"  id='target2' class='target' v-if="$route.path !== '/cd/creport/' && $route.path !== '/cd/creport/ticket'"> Reports</a>
                    </div>
                </div>
          </router-link>
          <router-link v-if="this.userRole == 'admin' " tag="li" :to="{ name: 'cinvoice' }" class=" flex mr-1">
            <div class="leftindex">
                    <div class="leftimage">
                        <img  style="margin-top: 6px;" src="/images/invlice_sel.png" alt="" v-if="$route.path == '/cd/invoice'" >
                         <img  style="margin-top: 6px;" src="/images/doller.png" alt="" v-if="$route.path !== '/cd/invoice'" >
                    </div>
                    <div class="lefttext">
                       <a href="#target3"  id='target3' class='target' v-if="$route.path == '/cd/invoice'" style="color:#AD9E58 !important"> Invoice</a>
                         <a href="#target3"  id='target3' class='target' v-if="$route.path !== '/cd/invoice'"> Invoice</a>
                    </div>
                </div>
        </router-link>

        <router-link tag="li" :to="{ name: 'cmsg' }" class=" flex mr-1">
            <div class="leftindex">
                    <div class="leftimage1">
                        
                        <img  style="height: 25px; width: 23px; margin-top: 4px;" src="/images/msg_sel.png" alt="" v-if="$route.path == '/cd/msg'">
                        <img  style="height: 25px; width: 23px; margin-top: 4px;" src="/images/message.png" alt="" v-if="$route.path !== '/cd/msg'">
                    </div>
                    <div class="lefttext">
                        <a href="#target4"  id='target4' class='target' v-if="$route.path == '/cd/msg'" style="color:#AD9E58 !important"> Messages</a>
                         <a href="#target4"  id='target4' class='target' v-if="$route.path !== '/cd/msg'"> Messages</a>
                    </div>
                </div>
        </router-link>
      </ul>
    </b-col>

    <b-col cols="10" style="flex: 0 0 auto;width: 83.33333333%;float: left;background: #f2f2f2;margin-top: 85px;">

    <p style="margin-bottom: 0;padding-bottom: 75px;border-left: 2px solid #f9f9f9;margin-top: -105px;padding-left: 30px;font-family: Poppins;font-weight: bold;font-size: 22px; color: #2C1977; "> Schedules</p>

    <ul class="flex c-secondary-nav">
      <li class="w-1/3 text-center"><a href="#" @click.prevent="openModal('AddShift')">Add Shifts</a></li>
      <li class="w-1/3 text-center"><a href="#" @click.prevent="printSchedule" >Print</a></li>
      <li class="w-1/3 text-center"><a href="#" @click.prevent="openModal('Export')" >Export</a></li>
    </ul>

    <div class="flex py-2 c-date-nav">
      <div class="w-2/5 px-2">
        <ul class="flex rounded-md overflow-hidden">
          <li class="flex-1 text-center" v-for="data in dateRange.prev"  :key="data.id">
            <a href="#" @click.prevent="selectDayInAWeek(data)">{{ data | moment('MMM D') }}</a>
          </li>
        </ul>
      </div>

      <div class="w-1/5 text-center">
        <div class="flex justify-around items-center">
          <a href="#" class="text-2xl" @click.prevent="setPrevNextWeek('prev')">&#9664;</a>
          <span class="text-xl font-bold">Week of {{ selectedDate | moment('MMMM D, YYYY') }}</span>
          <a href="#" class="text-2xl" @click.prevent="setPrevNextWeek('next')">&#9654;</a>
        </div>
        <span class="block mt-1 font-semibold">Weekly</span>
      </div>

      <div class="w-2/5 px-2">
        <ul class="flex rounded-md overflow-hidden">
          <li class="flex-1 text-center" v-for="data in dateRange.next"  :key="data.id">
            <a href="#" @click.prevent="selectDayInAWeek(data)">{{ data | moment('MMM D') }}</a>
          </li>
        </ul>
      </div>
    </div>

    <div class="px-4 c-schedules">
      <div class="flex justify-between py-3">
        <a href="#" @click.prevent="openModal('ChangeLayout')" class="flex items-center text-sm">
          <img src="images/change-layout.svg" class="mr-2" alt="">
          Change layout
        </a>
        <div class="c-send-notifications">
          <label class="flex justify-start items-center">
            <!-- <div class="bg-white border rounded border-gray-500 w-3 h-3 flex flex-shrink-0 justify-center items-center mr-1 focus-within:border-blue-500">
              <input type="checkbox" class="opacity-0 absolute mr-1 leading-tight">
              <svg class="fill-current hidden w-2 h-2 text-black pointer-events-none" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg>
            </div> -->
            <div class="bg-white border rounded border-gray-500 w-3 h-3 flex flex-shrink-0 justify-center items-center mr-1 focus-within:border-blue-500">
              <input type="checkbox">
            </div>
            <span class="select-none text-sm">Send a notification for each change</span>
          </label>
        </div>
      </div>

      <table class="table-fixed w-full" ref="scheduletable">
        <thead>
          <tr>
            <th v-for="data in weekDays"  :key="data.id">
              {{ data | moment('dddd') }}
              <span class="block text-sm">{{ data | moment('MMM D') }}</span>
            </th>
          </tr>
        </thead>
        <tbody v-if="schedules.length !== 0">
          <!-- <tr v-for="data in schedules">
            <td class="text-center" v-for="(data1, index1) in 7">
              <div v-if="extractAddedTo(data).added_to[index1] != 0">
                <strong class="block leading-tight">{{ data.position.position }}</strong>
                <div class="text-sm" v-for="dtls in data.shiftDetails">
                  {{ `${dtls.shift.start} - ${dtls.shift.end}` }}
                  <span class="block text-base">{{ `${dtls.employee.firstname} ${dtls.employee.lastname}` }}</span>
                </div>
              </div>
            </td>
          </tr> -->
          <tr v-for="(position) in schedules"  :key="position.id">
              <td v-for="(data1) in weekDays"  :key="data1.id">
                
                <div class="text-sm tooltip text-center w-full" v-for="dtls in position[data1]"  :key="dtls.id" @click="openEditShift(dtls.id)" >
                 <div v-if="dtls.added_to == data1">
                <strong class="block leading-tight" v-if="modal.layout.screenview.position">{{dtls.position.position}}</strong>
                
                 <span class="tooltiptext">Click to edit this shift</span>
                  
                  <font style="display: block; white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    max-width: 200px;
                    padding: 1px 0px 1px 25px;" :color="dtls.color">
                    {{ `${dtls.property.address}` }}
                  </font>
                  <font style="display: block;" :color="dtls.color">
                     {{ `${dtls.start} - ${dtls.end}` }}
                   
                  </font>
                  <span class="block text-base text-transform-capitalize">
                 
                    <font style="display: block;" :color="dtls.color">
                         
                    
                      <template v-if="modal.layout.screenview.name == 'firstlast'">
                          {{ `${dtls.employee.firstname} ${dtls.employee.lastname}` }}
                      </template>
                      <template v-else-if="modal.layout.screenview.name == 'lastfirst'">
                          {{ `${dtls.employee.lastname}, ${dtls.employee.firstname}` }}
                      </template>
                      <template v-else-if="modal.layout.screenview.name == 'firstl'">
                          {{ `${dtls.employee.firstname} ${dtls.employee.lastname.charAt(0)}.` }}
                      </template>
                      <template v-else-if="modal.layout.screenview.name == 'flast'">
                          {{ `${dtls.employee.firstname.charAt(0)}.${dtls.employee.lastname} ` }}
                      </template>
                      <template v-else-if="modal.layout.screenview.name == 'lastf'">
                          {{ `${dtls.employee.lastname},${dtls.employee.firstname.charAt(0)}. ` }}
                      </template>
                     
                  </font>
                  </span>
                  <span v-if="modal.layout.screenview.number"  class="block text-base">
                  {{ `${dtls.employee.mobile}` }}
                  </span>
                  <span v-if="modal.layout.screenview.description" class="block text-sm">
                 Description: {{dtls.description}}
                </span>
                <span v-if="modal.layout.screenview.totalhours" class="block text-sm">
                 {{dtls.paid_hours}} hours
                </span>
              </div>
              <div v-else-if="dtls.added_to != data1 && modal.layout.screenview.off == true">
                OFF
              </div>
                </div>
                
              </td>
          </tr>
          <!-- <tr v-for="(position) in schedules"  :key="position.id">
              <span class="text-center" v-for="(data1) in weekDays"  :key="data1.id">
            <td v-for="(date, index) in position" :key="date.id">
                {{index}}
                  <template  v-if="index == data1">
                <div v-for="(dtls) in date"  :key="dtls.id">
                
                  <strong class="block leading-tight" v-if="modal.layout.screenview.position">{{ dtls.position.position }}</strong>
                <div class="text-sm tooltip" @click="openEditShift(dtls.id)" >  
                
                 <span class="tooltiptext">Click to edit this shift</span>
                  
                  <font style="display: block; white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    max-width: 200px;
                    padding: 1px 0px 1px 25px;" :color="dtls.color">
                    {{ `${dtls.property.address}` }}
                  </font>
                  <font style="display: block;" :color="dtls.color">
                     {{ `${dtls.start} - ${dtls.end}` }}
                   
                  </font>
                  <span class="block text-base text-transform-capitalize">
                 
                    <font style="display: block;" :color="dtls.color">
                         
                    
                      <template v-if="modal.layout.screenview.name == 'firstlast'">
                          {{ `${dtls.employee.firstname} ${dtls.employee.lastname}` }}
                      </template>
                      <template v-else-if="modal.layout.screenview.name == 'lastfirst'">
                          {{ `${dtls.employee.lastname}, ${dtls.employee.firstname}` }}
                      </template>
                      <template v-else-if="modal.layout.screenview.name == 'firstl'">
                          {{ `${dtls.employee.firstname} ${dtls.employee.lastname.charAt(0)}.` }}
                      </template>
                      <template v-else-if="modal.layout.screenview.name == 'flast'">
                          {{ `${dtls.employee.firstname.charAt(0)}.${dtls.employee.lastname} ` }}
                      </template>
                      <template v-else-if="modal.layout.screenview.name == 'lastf'">
                          {{ `${dtls.employee.lastname},${dtls.employee.firstname.charAt(0)}. ` }}
                      </template>
                     
                  </font>
                  </span>
                  <span v-if="modal.layout.screenview.number"  class="block text-base">
                  {{ `${dtls.employee.mobile}` }}
                  </span>
                  <span v-if="modal.layout.screenview.description" class="block text-sm">
                 Description: {{data.description}}
                </span>
                <span v-if="modal.layout.screenview.totalhours" class="block text-sm">
                 {{data.paid_hours}} hours
                </span>
                </div>
                </div>
                   </template>
                  <template  v-else-if="index != data1 && modal.layout.screenview.off == true">
                     <div>
                      OFF
                    </div>
                  </template>
                 
             
            </td>
              </span>
          </tr> -->
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="7" class="text-center">No Records Found</td>
          </tr>
        </tbody>
        <!-- <tfoot v-if="!modal.layout.screenview.dateheader" class="position-sticky">
          <tr>
            <th v-for="data in weekDays"  :key="data.id">
              {{ data | moment('dddd') }}
              <span class="block text-sm">{{ data | moment('MMM D') }}</span>
            </th>
          </tr>
        </tfoot> -->
      </table>
    </div>
     <div  v-if="!modal.layout.screenview.dateheader" class=" flex py-2 c-date-nav position-sticky">
      <div class="w-2/5 px-2">
        <ul class="flex rounded-md overflow-hidden">
          <li class="flex-1 text-center" v-for="data in dateRange.prev"  :key="data.id">
            <a href="#" @click.prevent="selectDayInAWeek(data)">{{ data | moment('MMM D') }}</a>
          </li>
        </ul>
      </div>

      <div class="w-1/5 text-center">
        <div class="flex justify-around items-center">
          <a href="#" class="text-2xl" @click.prevent="setPrevNextWeek('prev')">&#9664;</a>
          <span class="text-xl font-bold">Week of {{ selectedDate | moment('MMMM D, YYYY') }}</span>
          <a href="#" class="text-2xl" @click.prevent="setPrevNextWeek('next')">&#9654;</a>
        </div>
        <span class="block mt-1 font-semibold">Weekly</span>
      </div>

      <div class="w-2/5 px-2">
        <ul class="flex rounded-md overflow-hidden">
          <li class="flex-1 text-center" v-for="data in dateRange.next"  :key="data.id">
            <a href="#" @click.prevent="selectDayInAWeek(data)">{{ data | moment('MMM D') }}</a>
          </li>
        </ul>
      </div>
    </div>
    </b-col>
  </b-row>
</div>


<div class="bv-example-row" style="margin-left: 240px"  v-if="this.userRole == 'admin' ">
     <ul class="flex c-secondary-nav">
      <li class="w-1/3 text-center" style="background-color: #02067e" ><a href="#" @click.prevent="openModal('AddShift')">Add Shifts</a></li>
      <li class="w-1/3 text-center" style="background-color: #02067e"><a href="#" @click.prevent="printSchedule" >Print</a></li>
      <li class="w-1/3 text-center" style="background-color: #02067e"><a href="#" @click.prevent="openModal('Export')" >Export</a></li>
    </ul>

    <div class="flex py-2 c-date-nav">
      <div class="w-2/5 px-2">
        <ul class="flex rounded-md overflow-hidden">
          <li class="flex-1 text-center" v-for="data in dateRange.prev"  :key="data.id">
            <a href="#" @click.prevent="selectDayInAWeek(data)">{{ data | moment('MMM D') }}</a>
          </li>
        </ul>
      </div>

      <div class="w-1/5 text-center">
        <div class="flex justify-around items-center">
          <a href="#" class="text-2xl" @click.prevent="setPrevNextWeek('prev')">&#9664;</a>
          <span class="text-xl font-bold">Week of {{ selectedDate | moment('MMMM D, YYYY') }}</span>
          <a href="#" class="text-2xl" @click.prevent="setPrevNextWeek('next')">&#9654;</a>
        </div>
        <span class="block mt-1 font-semibold">Weekly</span>
      </div>

      <div class="w-2/5 px-2">
        <ul class="flex rounded-md overflow-hidden">
          <li class="flex-1 text-center" v-for="data in dateRange.next"  :key="data.id">
            <a href="#" @click.prevent="selectDayInAWeek(data)">{{ data | moment('MMM D') }}</a>
          </li>
        </ul>
      </div>
    </div>

    <div class="px-4 c-schedules">
      <div class="flex justify-between py-3">
        <a href="#" @click.prevent="openModal('ChangeLayout')" class="flex items-center text-sm">
          <img src="images/change-layout.svg" class="mr-2" alt="">
          Change layout
        </a>
        <div class="c-send-notifications">
          <label class="flex justify-start items-center">
            <!-- <div class="bg-white border rounded border-gray-500 w-3 h-3 flex flex-shrink-0 justify-center items-center mr-1 focus-within:border-blue-500">
              <input type="checkbox" class="opacity-0 absolute mr-1 leading-tight">
              <svg class="fill-current hidden w-2 h-2 text-black pointer-events-none" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg>
            </div> -->
            <div class="bg-white border rounded border-gray-500 w-3 h-3 flex flex-shrink-0 justify-center items-center mr-1 focus-within:border-blue-500">
              <input type="checkbox">
            </div>
            <span class="select-none text-sm">Send a notification for each change</span>
          </label>
        </div>
      </div>

      <table class="table-fixed w-full" ref="scheduletable">
        <thead>
          <tr>
            <th v-for="data in weekDays"  :key="data.id">
              {{ data | moment('dddd') }}
              <span class="block text-sm">{{ data | moment('MMM D') }}</span>
            </th>
          </tr>
        </thead>
        <tbody v-if="schedules.length !== 0">
          <!-- <tr v-for="data in schedules">
            <td class="text-center" v-for="(data1, index1) in 7">
              <div v-if="extractAddedTo(data).added_to[index1] != 0">
                <strong class="block leading-tight">{{ data.position.position }}</strong>
                <div class="text-sm" v-for="dtls in data.shiftDetails">
                  {{ `${dtls.shift.start} - ${dtls.shift.end}` }}
                  <span class="block text-base">{{ `${dtls.employee.firstname} ${dtls.employee.lastname}` }}</span>
                </div>
              </div>
            </td>
          </tr> -->
          <tr v-for="(position) in schedules"  :key="position.id">
              <td v-for="(data1) in weekDays"  :key="data1.id">
                
                <div class="text-sm tooltip text-center w-full" v-for="dtls in position[data1]"  :key="dtls.id" @click="openEditShift(dtls.id)" >
                 <div v-if="dtls.added_to == data1">
                <strong class="block leading-tight" v-if="modal.layout.screenview.position">{{dtls.position.position}}</strong>
                
                 <span class="tooltiptext">Click to edit this shift</span>
                  
                  <font style="display: block; white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    max-width: 200px;
                    padding: 1px 0px 1px 25px;" :color="dtls.color">
                    {{ `${dtls.property.address}` }}
                  </font>
                  <font style="display: block;" :color="dtls.color">
                     {{ `${dtls.start} - ${dtls.end}` }}
                   
                  </font>
                  <span class="block text-base text-transform-capitalize">
                 
                    <font style="display: block;" :color="dtls.color">
                         
                    
                      <template v-if="modal.layout.screenview.name == 'firstlast'">
                          {{ `${dtls.employee.firstname} ${dtls.employee.lastname}` }}
                      </template>
                      <template v-else-if="modal.layout.screenview.name == 'lastfirst'">
                          {{ `${dtls.employee.lastname}, ${dtls.employee.firstname}` }}
                      </template>
                      <template v-else-if="modal.layout.screenview.name == 'firstl'">
                          {{ `${dtls.employee.firstname} ${dtls.employee.lastname.charAt(0)}.` }}
                      </template>
                      <template v-else-if="modal.layout.screenview.name == 'flast'">
                          {{ `${dtls.employee.firstname.charAt(0)}.${dtls.employee.lastname} ` }}
                      </template>
                      <template v-else-if="modal.layout.screenview.name == 'lastf'">
                          {{ `${dtls.employee.lastname},${dtls.employee.firstname.charAt(0)}. ` }}
                      </template>
                     
                  </font>
                  </span>
                  <span v-if="modal.layout.screenview.number"  class="block text-base">
                  {{ `${dtls.employee.mobile}` }}
                  </span>
                  <span v-if="modal.layout.screenview.description" class="block text-sm">
                 Description: {{dtls.description}}
                </span>
                <span v-if="modal.layout.screenview.totalhours" class="block text-sm">
                 {{dtls.paid_hours}} hours
                </span>
              </div>
              <div v-else-if="dtls.added_to != data1 && modal.layout.screenview.off == true">
                OFF
              </div>
                </div>
                
              </td>
          </tr>
          <!-- <tr v-for="(position) in schedules"  :key="position.id">
              <span class="text-center" v-for="(data1) in weekDays"  :key="data1.id">
            <td v-for="(date, index) in position" :key="date.id">
                {{index}}
                  <template  v-if="index == data1">
                <div v-for="(dtls) in date"  :key="dtls.id">
                
                  <strong class="block leading-tight" v-if="modal.layout.screenview.position">{{ dtls.position.position }}</strong>
                <div class="text-sm tooltip" @click="openEditShift(dtls.id)" >  
                
                 <span class="tooltiptext">Click to edit this shift</span>
                  
                  <font style="display: block; white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    max-width: 200px;
                    padding: 1px 0px 1px 25px;" :color="dtls.color">
                    {{ `${dtls.property.address}` }}
                  </font>
                  <font style="display: block;" :color="dtls.color">
                     {{ `${dtls.start} - ${dtls.end}` }}
                   
                  </font>
                  <span class="block text-base text-transform-capitalize">
                 
                    <font style="display: block;" :color="dtls.color">
                         
                    
                      <template v-if="modal.layout.screenview.name == 'firstlast'">
                          {{ `${dtls.employee.firstname} ${dtls.employee.lastname}` }}
                      </template>
                      <template v-else-if="modal.layout.screenview.name == 'lastfirst'">
                          {{ `${dtls.employee.lastname}, ${dtls.employee.firstname}` }}
                      </template>
                      <template v-else-if="modal.layout.screenview.name == 'firstl'">
                          {{ `${dtls.employee.firstname} ${dtls.employee.lastname.charAt(0)}.` }}
                      </template>
                      <template v-else-if="modal.layout.screenview.name == 'flast'">
                          {{ `${dtls.employee.firstname.charAt(0)}.${dtls.employee.lastname} ` }}
                      </template>
                      <template v-else-if="modal.layout.screenview.name == 'lastf'">
                          {{ `${dtls.employee.lastname},${dtls.employee.firstname.charAt(0)}. ` }}
                      </template>
                     
                  </font>
                  </span>
                  <span v-if="modal.layout.screenview.number"  class="block text-base">
                  {{ `${dtls.employee.mobile}` }}
                  </span>
                  <span v-if="modal.layout.screenview.description" class="block text-sm">
                 Description: {{data.description}}
                </span>
                <span v-if="modal.layout.screenview.totalhours" class="block text-sm">
                 {{data.paid_hours}} hours
                </span>
                </div>
                </div>
                   </template>
                  <template  v-else-if="index != data1 && modal.layout.screenview.off == true">
                     <div>
                      OFF
                    </div>
                  </template>
                 
             
            </td>
              </span>
          </tr> -->
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="7" class="text-center">No Records Found</td>
          </tr>
        </tbody>
        <!-- <tfoot v-if="!modal.layout.screenview.dateheader" class="position-sticky">
          <tr>
            <th v-for="data in weekDays"  :key="data.id">
              {{ data | moment('dddd') }}
              <span class="block text-sm">{{ data | moment('MMM D') }}</span>
            </th>
          </tr>
        </tfoot> -->
      </table>
    </div>
     <div  v-if="!modal.layout.screenview.dateheader" class=" flex py-2 c-date-nav position-sticky">
      <div class="w-2/5 px-2">
        <ul class="flex rounded-md overflow-hidden">
          <li class="flex-1 text-center" v-for="data in dateRange.prev"  :key="data.id">
            <a href="#" @click.prevent="selectDayInAWeek(data)">{{ data | moment('MMM D') }}</a>
          </li>
        </ul>
      </div>

      <div class="w-1/5 text-center">
        <div class="flex justify-around items-center">
          <a href="#" class="text-2xl" @click.prevent="setPrevNextWeek('prev')">&#9664;</a>
          <span class="text-xl font-bold">Week of {{ selectedDate | moment('MMMM D, YYYY') }}</span>
          <a href="#" class="text-2xl" @click.prevent="setPrevNextWeek('next')">&#9654;</a>
        </div>
        <span class="block mt-1 font-semibold">Weekly</span>
      </div>

      <div class="w-2/5 px-2">
        <ul class="flex rounded-md overflow-hidden">
          <li class="flex-1 text-center" v-for="data in dateRange.next"  :key="data.id">
            <a href="#" @click.prevent="selectDayInAWeek(data)">{{ data | moment('MMM D') }}</a>
          </li>
        </ul>
      </div>
    </div>
</div>









    	<modal v-model="modal.export" class="modal-add-new-employee" size="md:w-4/12" title="Export Daily Totals">
      <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(ExportFile)" ref="exportFile" novalidate>
    		
    			<!-- ================================= Positions ================================= -->
    		  <div class="positions py-5 mb-4">
            <div class="justify-between items-center mb-4 px-6">
              <div class="items-center leading-none">
                  <h3 class="text-xl font-semibold mr-4">Included</h3>
                    <div class="flex items-center leading-none">
                      <h3 class="mt-2 mr-4">Positions</h3>
                      <!-- <h5 class="mt-2 mr-4" > {{currentPosition == ''? 'All Positions' : currentPosition  }} </h5> -->
                      <template v-if="currentPosition == '0'">
                      <h5 class="mt-2 mr-4" > All Positions </h5>
                      </template>
                      <template v-else>
                        <h5 class="mt-2 mr-4" v-for="pos in modal.positions" :key="pos.id" > {{ currentPosition == pos.id ? pos.position : '' }} </h5>
                      </template>
                      
                    </div>
    		  		</div>
    		  		<div class="items-center leading-none mt-4">
    		  			<h3 class="text-xl font-semibold mr-4">File Info</h3>
    		  			<h5 class="mt-2 mr-4">Include Columns</h5>
    		  		</div>
    		  	</div>
            <ul class="flex flex-col flex-wrap ml-10 mr-6" v-if="weekDays.length===0">
              <li>No Records Found</li>
            </ul>
            
    				<ul class="flex flex-col flex-wrap ml-10 mr-6 export-ul" v-else>
              <li class="export-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.exportData.name">
    	            <span class="text-sm">Employee Name</span>
    	          </label>
              </li>
              <li class="export-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.exportData.firstname">
    	            <span class="text-sm">Employee First Name</span>
    	          </label>
              </li>
              <li class="export-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.exportData.lastname">
    	            <span class="text-sm">Employee Last Name </span>
    	          </label>
              </li>
              <li class="export-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.exportData.number">
    	            <span class="text-sm">Employee Number</span>
    	          </label>
              </li>
              <li class="export-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.exportData.payrate">
    	            <span class="text-sm">Pay Rate </span>
    	          </label>
              </li>
              <ValidationProvider rules="required" style="display:contents;" v-slot="v">
    					<li v-for="(data, index) in weekDays" :key="data.id" class="export-li">
    						<label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight" :id="index" type="checkbox"  v-model="modal.exportData.CheckedNames" :value="data">
    	            <span class="text-sm">{{ data }}</span>
    	          </label>
    					</li>
               <small class="text-red-600 block">{{ v.errors[0] }}</small>
                 </ValidationProvider>
             
    				</ul>
            
             	<div class="items-center flex leading-none mt-4">
                <ValidationProvider rules="required" class="flex" v-slot="v">
    		  			<h5 class="mt-2 mr-4">File Name</h5> 
                <input type="text" class="appearance-none block rounded py-1 px-4 leading-tight focus:outline-none border-2  text-left" v-model="modal.exportData.filename" />
    		  		   <small class="text-red-600 block">{{ v.errors[0] }}</small>
                 </ValidationProvider>
              </div>
             	<div class="items-center flexs leading-none mt-4">
    		  			<h5 class="mt-2 mr-4">Format Comma Separated Values (CSV)</h5> 
    		  		</div>
             	<div class="items-center flexs leading-none mt-4 text-center">
    		  			<button class="send_ins leading-tight focus:outline-none" type="submit">Create Export File</button>
    		  		</div>
              
              <download-csv  style="opacity: 0; position:absolute; cursor: default;"  ref='myBtn' :data = exportfd :name = modal.exportData.filename ></download-csv>

    		  </div>
    		  <!-- ================================= ./Positions ================================= -->
    
        </form>
      </ValidationObserver>

			<div class="information">
				<h4 class="text-xl mb-2">Information</h4>
				<ul class="list-inside">
					<li class="text-sm">Note that CSV files can be opened in Excel or other spreadsheet programs.</li>
				
				</ul>
			</div>
		</modal>

		<modal v-model="modal.shifts.open" class="modal-add-shift" size="md:w-3/6" title="Add shifts">
			<h4 class="text-lg text-center font-semibold pt-2 pb-5">Week of {{ selectedDate | moment('MMMM D, YYYY') }}</h4>

      <ValidationObserver v-slot="{ handleSubmit }">
  			<form @submit.prevent="handleSubmit(storeShift)">
  				<div class="mb-4">
  				  <div class="md:flex md:items-center mb-1">
  				    <div class="md:w-1/3"></div> 
  				    <div class="md:w-1/3">
                <ValidationProvider rules="required" v-slot="v">
                  <div class="flex">
                  <div class="relative">
                    <select  @change="ListOfEmployee()"  class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none" v-model="modal.shifts.requestBody.position" ref="addShiftPosition">
                      <option value="">Select a Position</option>
                      <option :value="data.id" v-for="data in modal.positions"  :key="data.id">{{ data.position }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                      <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                  </div>
                     <span class="req_form_fields pl-1">*</span>
                  </div>
                 
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
  				    </div>
  				    <div class="md:w-1/3"></div>
  				  </div>
  				  <div class="md:flex md:items-center mb-1">
  				    <div class="md:w-1/3">
  				      <label class="block md:text-right mb-1 pr-2" for="form-add-shift-start">Shift Start <span class="req_form_fields">*</span></label>
  				    </div>
  				    <div class="md:w-1/3">
                <ValidationProvider rules="json_min" v-slot="v">
                  <vue-timepicker
                    format="hh:mm A"
                    v-model="modal.shifts.startTime"
                    input-class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none"
                    @change="validateTimeInterval(modal.shifts.startTime, modal.shifts.endTime)"
                    @error="errorHandler"
                    :minute-interval="5"
                    hide-clear-button
                    close-on-complete
                    ></vue-timepicker>
                    <small class="text-red-600" style="display:block;">{{ v.errors[0] }}</small>
                </ValidationProvider>
  				    </div>
  				    <div class="md:w-1/3">
                    <div class="w-1/6 text-right absolute right-0 top-0 mr-6 mt-6">
                      <div class="relative">
                        <select v-model="modal.shifts.requestBody.color" size="1" name="Color" tabindex="6" class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none">
                            <option value="000000" selected="">Color</option>
                            <option style="color:#000000" value="000000">black</option>
                            <option style="color:#A52A2A" value="A52A2A">brown</option>
                            <option style="color:#0000FF" value="0000FF">blue</option>
                            <option style="color:#FF00FF" value="FF00FF">fuchsia</option>
                            <option style="color:#2F4F4F" value="2F4F4F">gray</option>
                            <option style="color:#006400" value="006400">green</option>
                            <option style="color:#000080" value="000080">navy</option>
                            <option style="color:#FF8C00" value="FF8C00">orange</option>
                            <option style="color:#800080" value="800080">purple</option>
                            <option style="color:#FF0000" value="FF0000">red</option>
                            <option style="color:#40E0D0" value="40E0D0">turquoise</option>
                            <option style="color:#CC99FF" value="CC99FF">lavender</option>
                            <option style="color:#99cc00" value="99cc00">lime</option>
                            <option style="color:#ff6699" value="ff6699">salmon</option>
                            <option style="color:#ffcc00" value="ffcc00">gold</option>
                            <option style="color:#51C7FF" value="51C7FF">aqua</option>
                            <option style="color:#D73600" value="D73600">maroon</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                          <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                      </div>
      </div>
              </div>
  				  </div>
  				  <div class="md:flex md:items-center mb-1">
  				    <div class="md:w-1/3">
  				      <label class="block md:text-right mb-1 pr-2" for="form-add-shift-end">Shift End <span class="req_form_fields">*</span></label>
  				    </div>
  				    <div class="md:w-1/3">
                <ValidationProvider rules="json_min" v-slot="v">
                  <vue-timepicker
                    format="hh:mm A"
                    v-model="modal.shifts.endTime"
                    input-class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200"
                    @change="validateTimeInterval(modal.shifts.startTime, modal.shifts.endTime)"
                    @error="captureErrorTimeInterval"
                    close-on-complete
                    hide-clear-button></vue-timepicker>
                  <small class="text-red-600" style="display:block;">{{ v.errors[0] }}</small>
                </ValidationProvider>
                <p class="text-red-500 text-xs italic" v-if="!isValidTimeInterval">Invalid start/end time.</p>
  				    </div>
  				    <div class="md:w-1/3"></div>
  				  </div>
  				  <div class="md:flex md:items-center mb-1">
  				    <div class="md:w-1/3">
  				      <label class="block md:text-right mb-1 pr-2" for="form-add-shift-paid-hours">Enter Paid Hours</label>
  				    </div>
  				    <div class="md:w-1/3">
                <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200 text-right" id="form-add-shift-paid-hours" type="number" min="1" v-model="test" style="border: 1px solid #707070" readonly>
  				    </div>
  				    <div class="md:w-1/3"></div>
  				  </div>
  				  <div class="md:flex mb-1">
  				    <div class="md:w-1/3">
  				      <label class="block md:text-right mb-1 pr-2" for="form-add-shift-description">Description <span class="req_form_fields">*</span></label>
  				    </div>
  				    <div class="md:w-1/3">
                <ValidationProvider rules="required" v-slot="v">
  				        <textarea class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" id="form-add-shift-description" rows="4" v-model="modal.shifts.requestBody.description" style="border: 1px solid #707070"></textarea>
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
  				    </div>
  				    <div class="md:w-1/3"></div>
  				  </div>
              <div class="md:flex md:items-center mb-1">
  				    <div class="md:w-1/3">
  				      <label class="block md:text-right mb-1 pr-2" for="form-add-shift-start">Lunch Time</label>
  				    </div>
  				    <div class="md:w-1/3">
                <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200 text-right" id="form-add-shift-paid-hours" type="number" min="1" max="999" v-model="modal.shifts.requestBody.lunch_time" style="border: 1px solid #707070">
  				    </div>
  				  </div>
             
              <div class="md:flex md:items-center mb-1">
  				    <div class="md:w-1/3">
  				      <label class="block md:text-right mb-1 pr-2" for="form-add-shift-start">Break Time</label>
  				    </div>
  				    <div class="md:w-1/3">
                <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200 text-right" id="form-add-shift-paid-hours" type="number" min="1" max="999" v-model="modal.shifts.requestBody.break_time" style="border: 1px solid #707070">
  				    </div>
  				  </div>
             
  			  </div>

  			  <div class="add-to py-3 mb-4">
  			  	<div class="flex justify-between items-center mb-2 px-6">
  			  		<h4 class="text-lg">Add to</h4>
  			  		<ul class="flex grp-select-unselect">
  			  			<li class="pr-2 mr-2" @click="unCheckAllDays(true)">
  			  				<label class="flex items-center" for="checkboxSelectAll">
  			            <!-- <font-awesome-icon :icon="['far', 'check-square']" class="mr-1" style="color: #3B86FF" /> -->
                    <input type="checkbox" class="mr-1" v-model="modal.shifts.checkBoxOption.selectAll">
  			            <span class="text-sm" id="checkboxSelectAll">Select all ...</span>
  			          </label>
  			  			</li>
  			  			<li @click="unCheckAllDays(false)">
  			  				<label class="flex items-center" for="checkboxUnSelectAll">
  			            <!-- <font-awesome-icon :icon="['far', 'square']" class="mr-1" /> -->
                    <input type="checkbox" class="mr-1" v-model="modal.shifts.checkBoxOption.unSelectAll">
  			            <span class="text-sm" id="checkboxUnSelectAll">Clear all</span>
  			          </label>
  			  			</li>
  			  		</ul>
  			  	</div>

  					<ul class="flex">
  						<li class="flex-1 text-center" v-for="data in modal.shifts.weekDays" :key="data.id">
  		  				<label class="text-sm leading-none">
  		  					{{ data | moment('ddd') }}<span class="block mb-1">{{ data | moment('MMM D') }}</span>
  		            <input class="mr-1" type="checkbox" v-model="modal.shifts.selectedDays" :value="data">
  		          </label>
  						</li>
  					</ul>
  			  </div>

  				<div class="assign-employees w-10/12 mx-auto mb-6">
  					<h4 class="font-semibold text-lg mb-1 text-center">
  						Assign Users
  						<small class="font-normal text-xs">(Click name to assign. To assign more than one Ctrl-click additional names.)</small>
  					</h4>

  					<div class="rounded-md py-4 px-4">
  						<ul class="flex flex-wrap" style="display: flex; justify-content: space-between;">
  							<li style="height: 30px; width: 30%;" class="flex items-center text-sm active px-1" v-for="(data, index) in modal.employees" :key="index" :class="{'text-red-600': data.id == modal.shifts.activeIndexEmployee.filter(res => res == data.id) }">
                  <a href="#" @click.prevent="unSelectEmployee(data.id, index)">
                    {{ `${data.firstname} ${data.lastname}` }}
                  </a>
                </li>
  						</ul>
  					</div>
  				</div>
  				<div class="assign-employees w-10/12 mx-auto mb-6">
  					<h4 class="font-semibold text-lg mb-1 text-center">
  						Assign Community Property
  						<small class="font-normal text-xs">(Click name to assign.To assign more than one Ctrl-click additional names.)</small>
  					</h4>

  					<div class="rounded-md py-4 px-4">
  						<ul class="flex flex-wrap" style="display: flex; justify-content: flex-start;">
  							<li style="height: 30px; width: 30%;" class="flex items-center text-sm active px-1" v-for="(data, index) in modal.client" :key="index" :class="{'text-red-600': data.id == modal.shifts.activeIndexClient.filter(res => res == data.id) }">
                 <template v-if="data.address">
                    <a href="#" @click.prevent="unSelectClient(data.id, index)">
                        {{ `${data.name}` }}
                      </a>
                 </template>
                
                </li>
  						</ul>
  					</div>
            	
  				</div>

  				<div class="action-group flex mx-auto justify-center mb-12">
  					<label class="flex items-center">
              <input class="mr-1 leading-tight" type="checkbox" v-model="modal.shifts.requestBody.notify_affected_employees">
              <span class="font-semibold">Notify Affected Users</span>
            </label>
            <button v-if="isfromsubmit" class="ml-8 bg-blue-500 text-white py-3 px-12 rounded-full" type="submit">Add Shift</button>
  				</div>
  			</form>
      </ValidationObserver>

			<div class="information">
				<h4 class="text-xl">Information</h4>
				<ul class="list-inside">
					<li class="text-sm">Select a position first to see employee list</li>
					<li class="text-sm">Enter a start and end time and if you check off one day you can then click the button to show each Users availability for that date and time</li>
					<li class="text-sm">Note that each shift is separate and must be edited independently</li>
				</ul>

				<p class="text-sm">* When AutoCalculate is checked this automatically sets the "paid hours" to the exact time between entered start and end times. Need to add lunch or break times?</p>
			</div>
		</modal>
    <!-- Modal for Edit Shift Start--->
    <modal v-model="modal.shifts.edits" class="modal-add-shift" size="md:w-3/6" title="Quick Shift Edit">
				<h4 class="text-lg text-center font-semibold pt-2 pb-5">Week of {{ selectedDate | moment('MMMM D, YYYY') }}</h4>

      <ValidationObserver v-slot="{ handleSubmit }">
  			<form @submit.prevent="handleSubmit(editShift)">
  				<div class="mb-4">
  				  <div class="md:flex md:items-center mb-1">
  				    <div class="md:w-1/3">
                <label class="block md:text-right mb-1 pr-2" for="form-add-shift-start">Position <span class="req_form_fields">*</span></label>
              </div>
  				    <div class="md:w-1/3">
                <ValidationProvider rules="required" v-slot="v">
                  <div class="flex">
                  <div class="relative">
                    <select onmousedown="(function(e){ e.preventDefault(); })(event, this)"   class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none" v-model="modal.shifts.edit.position_id" ref="addShiftPosition">
                      <option value="">Select a Position</option>
                      <option :value="data.id" v-for="data in modal.positions"  :key="data.id">{{ data.position }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                      <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                  </div>
                     <!-- <span class="req_form_fields pl-1">*</span> -->
                  </div>
                 
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
  				    </div>
  				    <div class="md:w-1/3"></div>
  				  </div>
  				  <div class="md:flex md:items-center mb-1">
  				    <div class="md:w-1/3">
  				      <label class="block md:text-right mb-1 pr-2" for="form-add-shift-start">Shift Start <span class="req_form_fields">*</span></label>
  				    </div>
  				    <div class="md:w-1/3">
                <ValidationProvider rules="json_min" v-slot="v">
                  <vue-timepicker
                    format="hh:mm A"
                    v-model="modal.shifts.startTime"
                    input-class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none"
                    @change="validateTimeInterval(modal.shifts.startTime, modal.shifts.endTime)"
                    @error="errorHandler"
                    :minute-interval="5"
                    hide-clear-button
                    close-on-complete
                    ></vue-timepicker>
                    <small class="text-red-600" style="display:block;">{{ v.errors[0] }}</small>
                </ValidationProvider>
  				    </div>
  				    <div class="md:w-1/3">
                    <div class="w-1/6 text-right absolute right-0 top-0 mr-6 mt-6">
                      <div class="relative">
                        <select v-model="modal.shifts.edit.color" size="1" name="Color" tabindex="6" class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none">
                            <option style="color:#000000" value="000000">black</option>
                            <option style="color:#A52A2A" value="A52A2A">brown</option>
                            <option style="color:#0000FF" value="0000FF">blue</option>
                            <option style="color:#FF00FF" value="FF00FF">fuchsia</option>
                            <option style="color:#2F4F4F" value="2F4F4F">gray</option>
                            <option style="color:#006400" value="006400">green</option>
                            <option style="color:#000080" value="000080">navy</option>
                            <option style="color:#FF8C00" value="FF8C00">orange</option>
                            <option style="color:#800080" value="800080">purple</option>
                            <option style="color:#FF0000" value="FF0000">red</option>
                            <option style="color:#40E0D0" value="40E0D0">turquoise</option>
                            <option style="color:#CC99FF" value="CC99FF">lavender</option>
                            <option style="color:#99cc00" value="99cc00">lime</option>
                            <option style="color:#ff6699" value="ff6699">salmon</option>
                            <option style="color:#ffcc00" value="ffcc00">gold</option>
                            <option style="color:#51C7FF" value="51C7FF">aqua</option>
                            <option style="color:#D73600" value="D73600">maroon</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                          <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                      </div>
      </div>
              </div>
  				  </div>
  				  <div class="md:flex md:items-center mb-1">
  				    <div class="md:w-1/3">
  				      <label class="block md:text-right mb-1 pr-2" for="form-add-shift-end">Shift End <span class="req_form_fields">*</span></label>
  				    </div>
  				    <div class="md:w-1/3">
                <ValidationProvider rules="json_min" v-slot="v">
                  <vue-timepicker
                    format="hh:mm A"
                    v-model="modal.shifts.endTime"
                    input-class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200"
                    @change="validateTimeInterval(modal.shifts.startTime, modal.shifts.endTime)"
                    @error="captureErrorTimeInterval"
                    close-on-complete
                    hide-clear-button></vue-timepicker>
                  <small class="text-red-600" style="display:block;">{{ v.errors[0] }}</small>
                </ValidationProvider>
                <p class="text-red-500 text-xs italic" v-if="!isValidTimeInterval">Invalid start/end time.</p>
  				    </div>
  				    <div class="md:w-1/3"></div>
  				  </div>
  				  <div class="md:flex md:items-center mb-1">
  				    <div class="md:w-1/3">
  				      <label class="block md:text-right mb-1 pr-2" for="form-add-shift-paid-hours">Paid Hours</label>
  				    </div>
  				    <div class="md:w-1/3">
                <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200 text-right" id="form-add-shift-paid-hours" type="number" min="1" v-model="modal.shifts.edit.paid_hours" style="border: 1px solid #707070">
  				    </div>
  				    <div class="md:w-1/3"></div>
  				  </div>
  				  <div class="md:flex mb-1">
  				    <div class="md:w-1/3">
  				      <label class="block md:text-right mb-1 pr-2" for="form-add-shift-description">Description <span class="req_form_fields">*</span></label>
  				    </div>
  				    <div class="md:w-1/3">
                <ValidationProvider rules="required" v-slot="v">
  				        <textarea class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" id="form-add-shift-description" rows="4" v-model="modal.shifts.edit.description" style="border: 1px solid #707070"></textarea>
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
  				    </div>
  				    <div class="md:w-1/3"></div>
  				  </div>
  			  </div>
          <div class="action-group flex mx-auto justify-center mb-12">
            <button class="ml-8 bg-blue-500 text-white py-3 px-12 rounded-full" @click="closepop()" type="button">Cancel</button>
            <button class="ml-8 bg-blue-500 text-white py-3 px-12 rounded-full" type="submit">Save</button>
            <button class="ml-8 bg-blue-500 text-white py-3 px-12 rounded-full" type="button" @click="deleteShift(modal.shifts.edit.id)">Delete</button>
  				</div>
  			</form>
      </ValidationObserver>
		</modal>
    <!-- Modal for Edit Shift End --->

    <!-- MODAL for Export -->
<modal v-model="modal.changelayout" class="modal-add-new-employee" size="md:w-4/12" title="Display Options">
      <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(changelayout)" ref="changelayout" novalidate>
           <!-- <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<screenview model>>>>>>>>>>>>>>>>>>>>>>>>>>>> -->
    		  <div class="positions screenview py-5 mb-4">
            <div class="justify-between items-center mb-4 px-6">
    		  		<div class="items-center leading-none mt-4">
    		  			<h3 class="text-xl font-semibold mr-4">Screen View</h3>
    		  			<h5 class="mt-2 mr-4">Show</h5>
    		  		</div>
    		  	</div>
    		<ul class="flex flex-col flex-wrap ml-10 mr-6 layout-ul">
              <li class="layout-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.layout.screenview.description">
    	            <span class="text-sm">Description</span>
    	          </label>
              </li>
              <li class="layout-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.layout.screenview.position">
    	            <span class="text-sm">Position</span>
    	          </label>
              </li>
              <li class="layout-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.layout.screenview.off">
    	            <span class="text-sm">Show "OFF" on days with no shifts or time off</span>
    	          </label>
              </li>
			        <li class="layout-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.layout.screenview.totalhours">
    	            <span class="text-sm">Show employee's total hours</span>
    	          </label>
              </li>
			        <li class="layout-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.layout.screenview.number">
    	            <span class="text-sm">Show employee's phone number</span>
    	          </label>
              </li>
			        <li class="layout-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.layout.screenview.dateheader">
    	            <span class="text-sm">Show the date header once</span>
    	          </label>
              </li>
              <li class="layout-li w-50"> 
                <label class="inline-flex items-center">
                   <span class="text-sm mr-1">Name</span>
    	           <select v-model="modal.layout.screenview.name">
                  <option value="firstlast">First Last</option>
                  <option value="lastfirst">Last, First</option>
                  <option value="firstl">First L.</option>
                  <option value="flast">F. Last</option>
                  <option value="lastf">Last, F.</option>
                </select>
    	           
    	          </label>

              </li>
			        <li class="layout-li w-50"> 
                <label class="inline-flex items-center">
                   <span class="text-sm mr-1">Font</span>
    	           <select v-model="modal.layout.screenview.font">
                  <option value="smaller">Small</option>
                  <option value="medium">Medium</option>
                  <option value="larger">Large</option>
                </select>
    	           
    	          </label>

              </li>
    				</ul>
    		  </div>
          <!-- <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<printview model>>>>>>>>>>>>>>>>>>>>>>>>>>>> -->
          <div class="positions printview py-5 mb-4">
            <div class="justify-between items-center mb-4 px-6">
    		  		<div class="items-center leading-none mt-4">
    		  			<h3 class="text-xl font-semibold mr-4">Print View</h3>
    		  			<h5 class="mt-2 mr-4">Show</h5>
    		  		</div>
    		  	</div>
    		<ul class="flex flex-col flex-wrap ml-10 mr-6 layout-ul">
              <li class="layout-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.layout.printview.description">
    	            <span class="text-sm">Description</span>
    	          </label>
              </li>
              <li class="layout-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.layout.printview.position">
    	            <span class="text-sm">Position</span>
    	          </label>
              </li>
              <li class="layout-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.layout.printview.off">
    	            <span class="text-sm">Show "OFF" on days with no shifts or time off</span>
    	          </label>
              </li>
			        <li class="layout-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.layout.printview.totalhours">
    	            <span class="text-sm">Show employee's total hours</span>
    	          </label>
              </li>
			        <li class="layout-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.layout.printview.number">
    	            <span class="text-sm">Show employee's phone number</span>
    	          </label>
              </li>
			        <li class="layout-li"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.layout.printview.dateheader">
    	            <span class="text-sm">Show the date header once</span>
    	          </label>
              </li>
			        <li class="layout-li w-50"> 
                <label class="inline-flex items-center">
                   <span class="text-sm mr-1">Name</span>
    	           <select v-model="modal.layout.printview.name">
                  <option value="firstlast">First Last</option>
                  <option value="lastfirst">Last, First</option>
                  <option value="firstl">First L.</option>
                  <option value="flast">F. Last</option>
                  <option value="lastf">Last, F.</option>
                </select>
    	          </label>
              </li>
			        <li class="layout-li w-50"> 
                <label class="inline-flex items-center">
                   <span class="text-sm mr-1">Font</span>
    	           <select v-model="modal.layout.printview.font">
                  <option value="smaller">Small</option>
                  <option value="medium">Medium</option>
                  <option value="larger">Large</option>
                </select>
    	           
    	          </label>

              </li>
    				</ul>
             	<div class="items-center flexs leading-none mt-4 text-center">
    		  			<button class="send_ins leading-tight focus:outline-none" type="submit">Save</button>
    		  		</div>
    		  </div>
        </form>
      </ValidationObserver>
		</modal>
<!-- MODAL for change layout end -->
   

  </div>
</template>

<script>

import Modal from './shared/Modal'
import Loader from './shared/Loader'
import moment from 'moment'
import axios from 'axios'

import VueTimepicker from 'vue2-timepicker'
// import 'vue2-timepicker/dist/VueTimepicker.css'

export default {
	components: {
		Modal,
    Loader,
    VueTimepicker,
	},
	data() {
		return {
      headers : {
        'Accept': `application/json`,
        'Authorization': `Bearer `+ JSON.parse(localStorage.getItem('accesstoken'))
      }, 
      // token : this.getAccessToken(),
      edata: [],
      exportfd : [],
      currentPosition: '',
			requestedHeaders: {
				headers: {}
			},

      errorStart: false,
      errorEnd: false,


      test: '',
      lunchtest: '',
      breaktest: '',
      isLoader: false,

      selectedDate: moment().startOf('isoWeek'),
      // chosenWeek: null,

      // This is gonna be used on generating week range
      dateRange: {
        prev: [],
        next: []
      },

      // index
      weekDays: [],
      schedules: {},

      modal: {
        changelayout: false,
        //export
        export : false,
        exportData : {
          CheckedNames:[],
          position: '',
          name : false ,
          firstname: false,
          lastname: false,
          number: false,
          payrate: false,
          filename:'EXPORT'
        },
        // generic
        positions: {},
        employees: {},
        property_id: '',
        client:{},
        layout: {
          screenview:{},
          printview:{},
          user_id: '1'  
        },

        // specific
        shifts: {
          edits:false,
          open: false,
          requestBody: {
            color : 0
          },
          edit:{
            color : 0
          },
          weekDays: [],
          selectedDays: [],
          activeIndexEmployee: [],  
          activeIndexClient: [],  
          // This is un/ticking checkbox
          checkBoxOption: {
            selectAll: false,
            unSelectAll: false
          },

          startTime: {},
          endTime: {},
          lunchstartTime: {},
          lunchendTime: {},
          breakstartTime: {},
          breakendTime: {},
        }
      },

      // validate time interval
      isValidTimeInterval: null,
      isfromsubmit: null,
      lunchisValidTimeInterval: null,
      breakisValidTimeInterval: null,
      fetchshiftid: '',

		}
	},
  /*watch: {
    'modal.shifts.requestBody.paid_hours': function(val) {
      if ( val === 0 ) {
        this.modal.shifts.requestBody.paid_hours = 1
      } else {
        this.modal.shifts.requestBody.paid_hours = val
      }
    }
  },*/
  computed: {
    getAccessToken(){
      return  JSON.parse(localStorage.getItem('accesstoken'));
    },
    userid() {
      let user = localStorage.getItem("user");
          console.log('localStorageuser',user)
        if(user){
          return JSON.parse(user).id;
        }else {
         return 0;
        }
    },
    userRole() {
      let user = localStorage.getItem("admin");
        if(user){
          return JSON.parse(user).role;
        }else {
         return 0;
        }
      },
  },
  created() {
    let vm = this;
    this.indexSchedule()
    setTimeout(() => {
      vm.getLayout();
    }, 3000);
     vm.modal.shifts.edits = false;
  },
  
  mounted() {
    let vm = this
    vm.displayWeek()
    vm.displayPrevNextWeek()
  },
	methods: {
    // get list of employee on shift position change
    ListOfEmployee(){
      let vm =this;
          axios
          .post('/api/employeelist', { position_id: vm.modal.shifts.requestBody.position, created_date : vm.selectedDate.format('YYYY-MM-DD')})
          .then(res => {
            if(res.data.status){
              //alert(vm.userRole+'-'+vm.userid)
              if(vm.userRole!='admin'){
                vm.modal.employees = res.data.employee.filter(x=>{
                    return x.added_by == vm.userid
                  })
              }else{
                vm.modal.employees = res.data.employee
              }
              
            }
            else{
               vm.$swal({
              icon: 'error',
              title: res.data.message,
              showConfirmButton: false,
              timer: 1500
            })
            }
          });

    },
    printSchedule(){
      window.open(`/print-schedule`,'','width=700,height=500')
    },

    /**
     * Display the appropriate data when a position has been selected
     * 
     * @param  integer value
     */
    selectedPosition (value) {
      this.indexSchedule(value)
    },

    selectDayInAWeek(date) {
      let vm = this
      vm.selectedDate = moment(date)
      vm.displayWeek()
      vm.indexSchedule()
      vm.displayPrevNextWeek()
    },

    /**
     * This will just add a decimal point
     */
    addDecimal(val) {
      this.modal.shifts.requestBody.paid_hours = val.toFixed(2)
    },

    /**
     * Generate prev/next week
     */
    displayPrevNextWeek() {
      let vm = this
      let prevWeek = vm.selectedDate.clone().startOf('isoWeek'),
          nextWeek = vm.selectedDate.clone().startOf('isoWeek')

      // based on weekstart start of week, then just add +7 days to determine the next week, etc 
      let prev = [],
          next = []
      for (let i=0; i<6; i++) {
        prev.push( prevWeek.subtract(7, 'days').format('YYYY-MM-DD') )
        next.push( nextWeek.add(7, 'days').format('YYYY-MM-DD') )
      }
      vm.dateRange = {
        prev: prev.reverse(),
        next: next
      }
    },

    /**
     * Set Prev/Next Week
     * 
     * @param String action [i.e. prev | next]
     */
    setPrevNextWeek(action) {
      let vm = this
      let selectedWeek = vm.selectedDate.clone().startOf('isoWeek')

      switch(action) {
        case 'prev':
          selectedWeek.subtract(7, 'days').format('YYYY-MM-DD')
          vm.selectedDate = selectedWeek
          vm.displayWeek()
          vm.indexSchedule()
          vm.displayPrevNextWeek()
          break
        case 'next':
          selectedWeek.add(7, 'days').format('YYYY-MM-DD')
          vm.selectedDate = selectedWeek
          vm.displayWeek()
          vm.indexSchedule()
          vm.displayPrevNextWeek()
          break
      }
    },

    displayWeek() {
      let vm = this

      let weekStart = vm.selectedDate.clone().startOf('isoWeek'),
          weekEnd = vm.selectedDate.clone().endOf('isoWeek'),
          days = []

      for (let i=0; i<=6; i++) {
        days.push( moment( weekStart).add(i, 'days').format('YYYY-MM-DD') )
      }
      vm.weekDays = days
    },

    extractAddedTo(data) {
      let weekDays = data.added_to
      return {
        added_to: weekDays,
      }
    },
    openEditShift(id){
      let vm = this;
      vm.fetchshiftid = id;
      vm.openModal('EditShift');
    },

    /**
     * Open specified modal
     * 
     * @param  {[type]} modal [description]
     */
		openModal(modal) {
      let vm = this
      switch (modal) {
        case 'AddShift':
          vm.modal.shifts.open = true
          vm.indexPositions('modal')
          // vm.indexEmployee('modal')
          vm.indexClient('modal')

          let currentDate = vm.selectedDate,
              weekStart = currentDate.clone().startOf('isoWeek'),
              weekEnd = currentDate.clone().endOf('isoWeek'),
              days = []

          for (let i=0; i<=6; i++) {
            days.push( moment( weekStart).add(i, 'days').format('YYYY-MM-DD') )
          }
          vm.modal.shifts.weekDays = days

          // set the default value
          vm.modal.shifts.requestBody.position = ''
          vm.modal.shifts.startTime = {A:'',hh:'',mm:''}
          vm.modal.shifts.endTime = {A:'',hh:'',mm:''}
          vm.modal.shifts.lunchstartTime = {A:'',hh:'',mm:''}
          vm.modal.shifts.lunchendTime = {A:'',hh:'',mm:''}
          vm.modal.shifts.breakstartTime = {A:'',hh:'',mm:''}
          vm.modal.shifts.breakendTime = {A:'',hh:'',mm:''}
          vm.isValidTimeInterval = true
          vm.isfromsubmit=true
          vm.lunchisValidTimeInterval = true
          vm.breakisValidTimeInterval = true
          break
        case 'EditShift':
          console.log('here')
          vm.modal.shifts.startTime = {A:'',hh:'',mm:''}
          vm.modal.shifts.endTime = {A:'',hh:'',mm:''}
          vm.isValidTimeInterval = true
          vm.isfromsubmit = true
          vm.fetchshiftdata();
          vm.modal.shifts.edits = true
          vm.indexPositions('modal')
          break
        case 'Export':
          vm.modal.export = true;
          vm.resetExportModal();
          //  vm.$refs.exportFile.reset();
           vm.indexPositions('modal')

          break
        case 'ChangeLayout':
          vm.getLayout();
          setTimeout(() => {
             vm.modal.changelayout = true;
          }, 1500);
        
          
          break
      }
		},
  async fetchshiftdata(){
    let vm = this;
    let fetchshift = await axios.get(`/api/shift/${this.fetchshiftid}/employee`  , { headers: this.headers});
    vm.modal.shifts.edit = fetchshift.data.data
   let start = moment(vm.modal.shifts.edit.added_to +' '+ vm.modal.shifts.edit.start).format('hh:mm A'); 
   let end = moment( vm.modal.shifts.edit.added_to +' '+ vm.modal.shifts.edit.end).format('hh:mm A'); 
          if( !Array.isArray(start) ||  !Array.isArray(end)){
        if( typeof start === 'string' ||  typeof end === 'string'){

        
              if (start.indexOf(':') > -1) {
              start =  start.replace(":", " ");
              let stime = {A:'',hh:'',mm:''}
              var arr = start.split(" ");
              stime.hh = arr[0]
              stime.mm = arr[1]
              stime.A = arr[2]
              start = stime;
              }
            if (end.indexOf(':') > -1) {
              end =  end.replace(":", " ");
              let etime = {A:'',hh:'',mm:''}
              var arr = end.split(" ");
              etime.hh = arr[0]
              etime.mm = arr[1]
              etime.A = arr[2]
              end = etime;
              }
      }
      }
    
    vm.modal.shifts.startTime = start;
    vm.modal.shifts.endTime = end;
  },

    /**
     * Un/check days in a week
     * 
     * @param  Boolean bool
     */
    unCheckAllDays(bool) {
      let vm = this
      let selected = []

      if (bool) {
        vm.modal.shifts.weekDays.forEach(res => selected.push(res))
        vm.modal.shifts.selectedDays = selected
        vm.modal.shifts.checkBoxOption.selectAll = true
        vm.modal.shifts.checkBoxOption.unSelectAll = false
      } else {
        vm.modal.shifts.selectedDays = []
        vm.modal.shifts.checkBoxOption.selectAll = false
        vm.modal.shifts.checkBoxOption.unSelectAll = true
      }
    },

    /**
     * Un/select employee for this shift
     * 
     * @param  Int employeeId
     * @param  Int index
     */
    unSelectEmployee(employeeId, index) {
      let vm = this

      // Note:
      //  - the index should reset based on the current setup
      //  - perhaps watch the changes so the index will reset too (???)
      // avoid multiple entry
      if ( vm.modal.shifts.activeIndexEmployee.includes(employeeId) === false ) {
        vm.modal.shifts.activeIndexEmployee.push(employeeId)
      } else {
        // Note: bug when there"'"s only 2 selections left
        vm.modal.shifts.activeIndexEmployee.sort() // sort to trace index
        let pos =  vm.modal.shifts.activeIndexEmployee.indexOf(employeeId)
        vm.modal.shifts.activeIndexEmployee.splice(pos ,1)
      }
    },
    unSelectClient(clientId, index) {
      let vm = this

      // Note:
      //  - the index should reset based on the current setup
      //  - perhaps watch the changes so the index will reset too (???)
      // avoid multiple entry
      if ( vm.modal.shifts.activeIndexClient.includes(clientId) === false ) {
        vm.modal.shifts.activeIndexClient.push(clientId)
      } else {
        // Note: bug when there"'"s only 2 selections left
        vm.modal.shifts.activeIndexClient.sort() // sort to trace index
        let pos =  vm.modal.shifts.activeIndexClient.indexOf(clientId)
        vm.modal.shifts.activeIndexClient.splice(pos ,1)
      }
      vm.property_id = clientId;
    },
    /**
     * Un/select client for this shift
     * 
     * @param  Int employeeId
     * @param  Int index
     */
    unSelectClient1(clientId, index) {
      let vm = this
      console.log('here');
      // Note:
      //  - the index should reset based on the current setup
      //  - perhaps watch the changes so the index will reset too (???)
      // avoid multiple entry
      if(( vm.modal.shifts.activeIndexClient.length == '1') &&( vm.modal.shifts.activeIndexClient.includes(clientId) === true ) ){
        vm.modal.shifts.activeIndexClient = [];
      }

      else if (( vm.modal.shifts.activeIndexClient.length == '1') &&( vm.modal.shifts.activeIndexClient.includes(clientId) === false )) {
        alert('Only 1 Client Property will be selected');
        
      }
       else if ( vm.modal.shifts.activeIndexClient.length == '0') {
        vm.modal.shifts.activeIndexClient.push(clientId)
        vm.property_id = clientId;
      } 
      else{
        console.log('do nothing');
      }
     
    },

    /**
     * List all employee
     *
     * @param  String requestedBy [part the API is being requested]
     */
    async indexEmployee(requestedBy) {
      let vm = this
      //let employee = await axios.get(`/api/employees?has_email=both&create_date=${ vm.selectedDate.format('YYYY-MM-DD') }`)
      // old employee list
      let employee1 = await axios.get(`/api/employeelist?create_date=${ vm.selectedDate.format('YYYY-MM-DD') }`  , { headers: this.headers})

      switch (requestedBy) {
        case 'modal':
          vm.modal.employees = employee1.data
          break
      }
    },
    async indexClient(requestedBy) {
      let vm = this
      let client = await axios.get(`/api/property`  , { headers: this.headers})

      if(client.data){
          console.log('client.data',client.data.data);
          console.log('vm.userRole',vm.userRole);
          console.log('vm.userid',vm.userid);

          if(vm.userRole!='admin'){
              let newclient = client.data.data.filter(x=>{
                return x.client_id == vm.userid
              })
              console.log('newclient',newclient);
              switch (requestedBy) {
                case 'modal':
                  vm.modal.client = newclient  
                  break
              }

          }else{
              let newclient = client.data.data
              console.log('newclient',newclient);
              switch (requestedBy) {
                case 'modal':
                  vm.modal.client = newclient  
                  break
              }
          }
          
      }
    },

    /**
     * Load positions
     * 
     * @param  String requestedBy [part the API is being requested]
     */
    async indexPositions(requestedBy) {
      let vm = this
      let position = await axios.get('/api/positions', { headers: this.headers})

      switch (requestedBy) {
        /*case 'index-position':
          vm.index.positions = position.data
          break*/
        case 'modal':
          vm.modal.positions = position.data.data
          break
        case 'export-position':
          vm.modal.exportData.position = position.data
          break
      }
    },

    /**
     * Add shift
     */
    storeShift() {
      let vm = this
       vm.isfromsubmit = true;
      // Validate
      if ( !vm.isValidTimeInterval ) {
        // 
      } else if ( Object.keys(vm.modal.shifts.selectedDays).length == 0 ) {
        alert('You have to at least select a day.');
      } else if ( Object.keys(vm.modal.shifts.activeIndexEmployee).length == 0 ) {
        alert('You have to at least select an employee.');
      } else if ( Object.keys(vm.modal.shifts.activeIndexClient).length == 0 ) {
        alert('You have to at least select client.');
      }  else {

        let start = vm.modal.shifts.startTime,
            end = vm.modal.shifts.endTime
        vm.isLoader = true;
        vm.isfromsubmit = false;
        let startTime = moment(`${start.hh}:${start.mm} ${start.A}`, ['h:mm A']).format('HH:mm'),
            endTime = moment(`${end.hh}:${end.mm} ${end.A}`, ['h:mm A']).format('HH:mm')
        let lunchstartTime = '', lunchendTime= '';
        let breakstartTime = '',  breakendTime = '';
        Object.assign(vm.modal.shifts.requestBody, {
          week_of: moment(vm.selectedDate).format('YYYY-MM-DD'),
          added_to: vm.modal.shifts.selectedDays,
          employees: vm.modal.shifts.activeIndexEmployee,
          client: vm.modal.shifts.activeIndexClient,
          property_id: vm.property_id,
          start: startTime,
          lunch_start: lunchstartTime,
          break_start: breakstartTime,
          end: endTime,
          lunch_end: lunchendTime,
          break_end: breakendTime,
          paid_hours: vm.test
          // color: 
        })

        axios
          .post('/api/shifts', vm.modal.shifts.requestBody  , { headers: this.headers})
          .then(res => {
            if(res.data.status){
               vm.isLoader = false;
               vm.indexSchedule()
                // reset
                
                vm.modal.shifts.requestBody = {}
                vm.modal.shifts.selectedDays = []
                vm.modal.shifts.activeIndexEmployee = []
                vm.modal.shifts.activeIndexClient = []
                vm.test = ''
                vm.modal.shifts.open = false
                vm.isfromsubmit = true;
                vm.$swal({
                  icon: 'success',
                  title: 'Successfully Added!',
                  showConfirmButton: false,
                  timer: 1500
                })
            }else{
              vm.isLoader = false;
              vm.isfromsubmit = true;
              vm.$swal({
              icon: 'error',
              title: res.data.message,
              showConfirmButton: false,
              timer: 1500
            })
            }
          })

      }
    },
    // edit shift
    editShift() {
      let vm = this

      // Validate
      if ( !vm.isValidTimeInterval ) {
        // 
      }  else {
        console.log('heree')
        let start = vm.modal.shifts.startTime,
            end = vm.modal.shifts.endTime
        let startTime = moment(`${start.hh}:${start.mm} ${start.A}`, ['h:mm A']).format('HH:mm'),
            endTime = moment(`${end.hh}:${end.mm} ${end.A}`, ['h:mm A']).format('HH:mm')
        axios
          .post(`/api/editshift`, Object.assign({
          position_id :  vm.modal.shifts.edit.position_id,
          description :  vm.modal.shifts.edit.description,
          start: startTime,
          end: endTime,
          paid_hours: vm.modal.shifts.edit.paid_hours,
          color:  vm.modal.shifts.edit.color, 
          employee_id:vm.modal.shifts.edit.employee_id,
          id:  vm.modal.shifts.edit.id,
          added_to: vm.modal.shifts.edit.added_to
        }) , { headers: this.headers})
          .then(res => {
            if(res.data.status){
               vm.indexSchedule()
            // reset
            vm.modal.shifts.startTime = {A:'',hh:'',mm:''}
            vm.modal.shifts.endTime = {A:'',hh:'',mm:''}
            vm.modal.shifts.edit = {}
            vm.modal.shifts.edits = false
            vm.$swal({
              icon: 'success',
              title: 'Successfully Added!',
              showConfirmButton: false,
              timer: 1500
            })
            }else{
               vm.$swal({
              icon: 'error',
              title: res.data.message,
              showConfirmButton: false,
              timer: 1500
            })
            }
          })
      }
    },
    closepop() {
      let vm = this;
      vm.modal.shifts.edits = false;
    },
    //delete shift
    deleteShift(shift_id){
       let vm = this;
        if (confirm(`Are you sure you want to delete this shift?`)) {
        axios
          .get(`/api/deleteshift/${shift_id}`)
          .then(() => {
            vm.modal.shifts.startTime = {A:'',hh:'',mm:''}
            vm.modal.shifts.endTime = {A:'',hh:'',mm:''}
            vm.modal.shifts.edit = {}
            vm.modal.shifts.edits = false
            vm.indexSchedule()
            vm.$swal({
              icon: 'success',
              title: 'Successfully Deleted!',
              showConfirmButton: false,
              timer: 1500
            })
          })
          .catch(err => {
            vm.$swal({
              icon: 'error',
              title: err.response.data.message,
              showConfirmButton: false,
              timer: 1500
            })
          })
      }
    },
    //get layout
    getLayout(){
      let vm = this;
      axios
        .post('/api/schedules/getlayout', {user_id:'1'} 
        , { headers: this.headers})
        .then(res => {
          vm.isLoader = false;
              vm.modal.layout.printview = JSON.parse(res.data.data.printview.meta_value);
              vm.modal.layout.screenview = JSON.parse(res.data.data.screenview.meta_value);
        })
        .catch(err => {
          // vm.$swal({
          //   icon: 'error',
          //   title: err.response.data,
          //   showConfirmButton: false,
          //   timer: 1500
          // })
          vm.isLoader = false
        })
    },
    // Change Layout
    changelayout(){
      let vm = this
      vm.isLoader = true;
      vm.$refs.scheduletable.style.fontSize =  vm.modal.layout.screenview.font;
      axios
        .post('/api/schedules/changelayout', Object.assign(vm.modal.layout), { headers: this.headers})
        .then(res => {
        vm.isLoader = false;
         vm.modal.changelayout = false;
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 1500
          })
          vm.isLoader = false
        })
    },
    resetExportModal(){
      let vm = this;
      vm.modal.exportData.CheckedNames = [];
      vm.modal.exportData.position = '';
      vm.modal.exportData.name = false;
      vm.modal.exportData.firstname = false;
      vm.modal.exportData.lastname = false;
      vm.modal.exportData.number = false;
      vm.modal.exportData.payrate = false;
      vm.modal.exportData.filename = 'EXPORT';
    },
    // Export File CSV
      ExportFile() {
      let vm = this
      vm.isLoader = true
      vm.modal.exportData.position = this.currentPosition;
      if( vm.modal.exportData.filename!=''){
         vm.modal.exportData.filename =  (vm.modal.exportData.filename).concat('.CSV')
      }
      else{
         vm.$swal({
            icon: 'error',
            title: 'Enter file name',
            showConfirmButton: false,
            timer: 1500
          })
      }
     
      axios
        .post('/api/schedules/exportfile', Object.assign(vm.modal.exportData) , { headers: this.headers})
        .then(res => {
          // vm.modal.exportData = {}
          vm.modal.export = false
          vm.isLoader = false
          // vm.$refs.exportFile.reset();
          vm.exportfd = res.data
          if(vm.exportfd == ''){
             vm.$swal({
            icon: 'error',
            title: 'No Data To Export',
            showConfirmButton: false,
            timer: 1500
          })
          }
          // open csv download
          setTimeout(() => {
             vm.$refs.myBtn.$el.click();
          }, 4000);
         
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 1500
          })
          vm.$refs.myBtn.$el.click();
          vm.modal.exportData.filename =  'EXPORT'
          vm.isLoader = false
        })
    },
    /**
     * Show schedules
     */
    async indexSchedule(positionId='') {
      let vm = this
      let selectedDate = vm.selectedDate.format('YYYY-MM-DD')
      vm.currentPosition = positionId ; 
      if(vm.currentPosition == ''){
        vm.currentPosition = 0;
      }
      vm.isLoader = true
      let schedules = await axios.get(`/api/shifts?selectedDate=${selectedDate}&position_id=${positionId}&client_id=${vm.userid}`, { headers: this.headers})
    
      vm.schedules = schedules.data;
      vm.isLoader = false
    },

    // ====================================================================
    //  [Modal] - Add shift
    // ====================================================================
    //
    /**
     * Validate time interval
     * 
     * @param  time start
     * @param  time end
     */
    validateTimeInterval(start, end) {
      let vm = this
      let startTime = moment(`${start.hh}:${start.mm} ${start.A}`, ['h:mm A']),
          endTime = moment(`${end.hh}:${end.mm} ${end.A}`, ['h:mm A'])

      let duration = moment.duration(endTime.diff(startTime));

      let hours = duration.asHours();
      if ( hours <= -0 ) {
          hours = hours + 24;
      }
      // if total hours is equal to 12, make it positive regardless who"'"s first, am or pm
      // This is for visual only
     // vm.isValidTimeInterval = true
      //vm.test = hours.toFixed(2)
      /*if ( hours == -12 ) {
        vm.test = Math.abs(hours).toFixed(2)
        vm.isValidTimeInterval = true
      } else*/

      //if ( startTime == undefined || endTime == undefined || !startTime.isValid() || !endTime.isValid() || startTime.isSame(endTime) || !startTime.isBefore(endTime) ) {
      if ( startTime == undefined || endTime == undefined || !startTime.isValid() || !endTime.isValid() || startTime.isSame(endTime)) {
        vm.isValidTimeInterval = false
      } else {
        vm.isValidTimeInterval = true
        vm.test = hours.toFixed(2)
      }
    },
    captureErrorTimeInterval(eventData) {
      console.log(eventData)
    },
    errorHandler(eventData) {
      console.log('eventData', eventData)
    },
    lunchvalidateTimeInterval(start, end) {
      let vm = this
      let lunchstartTime = moment(`${start.hh}:${start.mm} ${start.A}`, ['h:mm A']),
          lunchendTime = moment(`${end.hh}:${end.mm} ${end.A}`, ['h:mm A'])

      let lunchduration = moment.duration(lunchendTime.diff(lunchstartTime)),
          lunchhours = lunchduration.asHours()

      // if total hours is equal to 12, make it positive regardless who"'"s first, am or pm
      // This is for visual only
      if ( lunchhours == -12 ) {
        vm.lunchtest = Math.abs(lunchhours).toFixed(2)
        vm.lunchisValidTimeInterval = true
      } else 


      /*if ( startTime == undefined || endTime == undefined || !startTime.isValid() ||  !endTime.isValid() || !startTime.isBefore(endTime) || endTime.isBefore(startTime) ) {*/
      if ( lunchstartTime == undefined || lunchendTime == undefined || !lunchstartTime.isValid() || !lunchendTime.isValid() || lunchstartTime.isSame(lunchendTime) || !lunchstartTime.isBefore(lunchendTime) ) {
      // if ( startTime == undefined || endTime == undefined || !startTime.isValid() || !endTime.isValid() || startTime.isSame(endTime) ) {
        vm.lunchisValidTimeInterval = false
      } else {
        vm.lunchisValidTimeInterval = true
        vm.lunchtest = lunchhours.toFixed(2)
      }
    },
    captureErrorTimeInterval(eventData) {
      console.log(eventData)
    },
    errorHandler(eventData) {
      console.log('eventData', eventData)
    },
    breakvalidateTimeInterval(start, end) {
      let vm = this
      let breakstartTime = moment(`${start.hh}:${start.mm} ${start.A}`, ['h:mm A']),
          breakendTime = moment(`${end.hh}:${end.mm} ${end.A}`, ['h:mm A'])

      let breakduration = moment.duration(breakendTime.diff(breakstartTime)),
          breakhours = breakduration.asHours()

      // if total hours is equal to 12, make it positive regardless who"'"s first, am or pm
      // This is for visual only
      if ( breakhours == -12 ) {
        vm.breaktest = Math.abs(breakhours).toFixed(2)
        vm.breakisValidTimeInterval = true
      } else 


      /*if ( startTime == undefined || endTime == undefined || !startTime.isValid() ||  !endTime.isValid() || !startTime.isBefore(endTime) || endTime.isBefore(startTime) ) {*/
      if ( breakstartTime == undefined || breakendTime == undefined || !breakstartTime.isValid() || !breakendTime.isValid() || breakstartTime.isSame(breakendTime) || !breakstartTime.isBefore(breakendTime) ) {
      // if ( startTime == undefined || endTime == undefined || !startTime.isValid() || !endTime.isValid() || startTime.isSame(endTime) ) {
        vm.breakisValidTimeInterval = false
      } else {
        vm.breakisValidTimeInterval = true
        vm.breaktest = breakhours.toFixed(2)
      }
    },
	}
}

</script>

<style lang="scss" scoped>
	@import '../../sass/schedules';
  @import '../../sass/client/dashboard';
  .c-send-notifications {
    input:checked + svg {
      display: block;
    }    
  }
  .leftindex {
            flex-direction: row;
            display: flex;
            margin-top: 40px;
            text-align: center;
            margin-left: 30px;
     } 

      a:active {
        color: #AD9E58  !important;
       }
        a:focus {
           color: #AD9E58  !important;
        } 
        :target {
          color: #AD9E58;
           }
    .lefttext {
            width: 69px;
            height: 27px;
            font-family: Poppins;
            /* color: #242424; */
            font-size: 18px;
            line-height: 27px;
            margin-left: 14px;
        }
        .leftimagemsg {
            margin-left: 30px;
            width: 26px;
            height: 33px;
        }
        .leftimageone{
          height:100px;
          width:158px;
          margin-top:6px;
        }
        
        .lefttext a {
            color: #242424;
            text-decoration: none;
        }
        .mx-auto{
          background-color:#FFFFFF;
        }
  
</style>
