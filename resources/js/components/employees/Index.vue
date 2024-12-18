<template>
  <div class=" pb-4 custom-main col-xm-10" >
    
    <Loader msg="Processing ..." v-model="isLoader" />

    <ul class="flex c-secondary-nav">
      <li class="w-1/3 text-center"><a href="#" @click.prevent="openModal('AddNewEmployee')">Add New User with a role</a></li>
      <li class="w-1/3 text-center"><a href="#" @click.prevent="openModal('AddEditPositions')">Add/Edit Positions</a></li>
      <li class="w-1/3 text-center"><a href="#" @click.prevent="openModal('SignIn')">Email Sign in Instructions</a></li>
    </ul>

    <div class="selection-function px-4 py-3 flex flex-wrap justify-between items-start rounded-md">
      
      <div class="flex flex-col md:flex-row items-start">
        <div class="flex items-center text-sm mb-3 md:mb-0 md:mr-4">
          <font-awesome-icon icon="columns" class="mr-2" />
          Select columns
        </div>
        <div class="options py-2 px-3 rounded-md bg-light shadow-sm" >
          <label class="font-weight-bold text-sm mb-2 block">Selected users:</label>
          <ul class="list-unstyled flex flex-wrap">
            <li class="mr-3 mb-2">
              <a href="#" @click.prevent="SendMessageCheck('SendReminders')" class="flex items-center text-sm text-decoration-none " title="Send scheduled reminder to those checked who are also working in a date range">
                <font-awesome-icon :icon="['far', 'list-alt']" class="mr-1"></font-awesome-icon>
                Send reminder
              </a>
            </li>
            <li class="mr-3 mb-2">
              <a href="#" @click.prevent="SendMessageCheck('SendMessage')" class="flex items-center text-sm text-decoration-none">
                <font-awesome-icon :icon="['far', 'envelope']" class="mr-1"></font-awesome-icon>
                Message
              </a>
            </li>
            <li class="mr-3 mb-2">
              <vue-confirm-dialog ref="sendselect"></vue-confirm-dialog>
              <a href="#" @click.prevent="SendMessageCheck('SendSignIn')" class="flex items-center text-sm text-decoration-none">
                <font-awesome-icon :icon="['far', 'list-alt']" class="mr-1"></font-awesome-icon>
                Send Sign In
              </a>
            </li>
            <li class="mr-3 mb-2">
              <a href="#" @click.prevent="SendMessageCheck('ExportData')" class="flex items-center text-sm text-decoration-none">
                <font-awesome-icon icon="external-link-square-alt" class="mr-1"></font-awesome-icon>
                Export to Clipboard
              </a>
            </li>
          </ul>
        </div>
      </div>       

        <!-- <div class="options py-1 pb-2 px-6 rounded-md">
          <label class="font-bold text-sm">Selected users:</label>
          <ul class="flex">
            <li class="mr-4">
              <a href="#" @click.prevent="SendMessageCheck('SendReminders')" class="flex items-center text-sm" title="Send scheduled reminder to those checked who are also working in a date range">
                <font-awesome-icon :icon="['far', 'list-alt']" class="mr-1" />
                Send reminder
              </a>
            </li>
            <li class="mr-4">
              <a href="#"  @click.prevent="SendMessageCheck('SendMessage')" class="flex items-center text-sm">
                <font-awesome-icon :icon="['far', 'envelope']" class="mr-1" />
                Message
              </a>
            </li>
            <li class="mr-4">
              <vue-confirm-dialog ref="sendselect"></vue-confirm-dialog>
              <a href="#" @click.prevent="SendMessageCheck('SendSignIn')" class="flex items-center text-sm">
                <font-awesome-icon :icon="['far', 'list-alt']" class="mr-1" />
                Send Sign In.....
                
              </a>
            </li>
            <li class="mr-4">
              <a href="#"  @click.prevent="SendMessageCheck('ExportData')" class="flex items-center text-sm">
                <font-awesome-icon icon="external-link-square-alt" class="mr-1" />
                Export to Clipboard
              </a>
            </li>
          </ul>
        </div> -->
      <!-- <li>
        <a href="#" 
         @click.prevent="SendMessageCheck('BulkEdit')"
        class="flex items-center text-sm">
          <font-awesome-icon icon="pencil-alt" class="-mr-1" />
          <font-awesome-icon icon="pencil-alt" class="mr-1" />
          Bulk Edit
        </a>
      </li> -->

      <div class="text-right">
        <span class="block mb-2">Total users: {{ countTotalEmployees }}*</span>
        <div class="flex flex-wrap -mx-3">
          <div class="w-full md:w-1/2 px-3">
            <div class="relative">
              <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" placeholder="Find" v-model="searchKeyword" @keyup="search">
              <div class="absolute inset-y-0 right-0 flex items-center px-2 text-custom-border rounded-r border-l-0">
                <font-awesome-icon icon="search" class="fill-current" />
              </div>
            </div>
          </div>
          <div class="w-full md:w-1/2 pr-3">
            <div class="relative">
              <select class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none" v-model="index.selectPosition" @change="filterResultViaPosition(index.selectPosition)">
                <option value="">All Positions</option>
                <option v-for="data in index.positions" :value="data.id" :key="data.id">{{ data.position }}</option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="px-4">
      <table class="w-full">
        <thead>
          <tr>
            <th>
              <input v-model="allSelected" type="checkbox" @click="selectAll">
            </th>
            <th>Edit</th>
            <th class="text-left">First
            
           </th>
            <th class="text-left">Last 
           
           </th>
            <th class="text-left">Phone 
           
           </th>
            <th class="text-left">Email*  </th>
            <th class="text-left">User Type</th>

           <th class="leading-tight py-2" v-if="modal.getUserRole==0">Max Wkly<span class="block">Hours</span>
           </th>
            <th class="leading-tight" v-if="modal.getUserRole==0">Max Wkly<span class="block">Days</span> 
           </th>
            <th class="leading-tight" v-if="modal.getUserRole==0">Max Day<span class="block">Hours</span> 
            </th>
            <th class="leading-tight" v-if="modal.getUserRole==0">Max Day<span class="block">Shifts</span> </th>
            <th style="width: 9%;">Zip Code</th>
            <th style="width: 9%;">Hire Date</th>
            <th class="leading-tight">Priority<span class="block">Group</span>
           
           </th>
            <th style="width: 9%;">Action </th>
          </tr>
        </thead>
        <tbody v-if="index.employees.length===0">
          <tr><td colspan="13" class="px-2">No Records Found</td></tr>
        </tbody>
        <tbody v-else>
          <tr v-for="(data, index) in index.employees" :key="data.id">
            <td class="text-center"><input :id="data.id" :index="index" :value="data" v-model="checkedNames"   type="checkbox"></td>
           
            <td class="text-center">
              <a href="#" @click.prevent="openModal('EditEmployee', data, index)" v-if="data.employee.added_by==modal.getUserId"><font-awesome-icon icon="pencil-alt" /></a>
            </td>
            <td>{{ data.employee.firstname }}</td>
            <td>{{ data.employee.lastname }}</td>
            <td>{{ data.employee.phone }}</td> 
            <td>{{ data.employee.email || '-----' }}</td>
            <td v-if="data.employee.enable_security_officer==0">
              Concerned Citizen
            </td>
            <td v-if="data.employee.enable_security_officer==1">
                Watcher
            </td>
            <td class="text-center" v-if="modal.getUserRole==0">{{ data.employee.max_weekly_hours }}</td>
            <td class="text-center" v-if="modal.getUserRole==0">{{ data.employee.max_weekly_days }}</td>
            <td class="text-center" v-if="modal.getUserRole==0">{{ data.employee.max_day_hours }}</td>
            <td class="text-center" v-if="modal.getUserRole==0">{{ data.employee.max_day_shifts }}</td>
            <td class="text-center">{{ data.employee.zip}}</td>
            <td class="text-center">{{ data.employee.hired_date | moment('MM-DD-YYYY')}}</td>
            <td class="text-center">{{ data.employee.priority_group }}</td>
            <td class="text-center">
              <a v-if="data.employee.added_by==modal.getUserId" href="#" @click.prevent="SetClockinTime(data.employee.id)" class="flex items-center text-sm" style="float: left;">
                <div style="background:#ff0000;border-radius: 25px;padding: 4px;margin:4px 0 4px 15px">
                  <img src="/images/clockin.png" class="h-5 mx-auto" alt="clockin">  
                </div>   
              </a>
              <a v-if="data.employee.added_by==modal.getUserId" href="#" @click.prevent="removeEmployee(data.employee.id,data.employee.firstname,data.employee.lastname)">
              <b-button class="mt-1" variant="success"> 
                <font-awesome-icon :icon="['far', 'trash-alt']" class="text-gray-500 font-size-24" /></b-button>
              </a>


            </td>
          </tr>
        </tbody>
      </table>
    </div> -->
    <div class="px-4">
      <div>
        <table class="table-auto w-full border-collapse border border-gray-300">
          <thead class="thead-light">
            <tr>
              <th>
                <input v-model="allSelected" type="checkbox" @click="selectAll">
              </th>
              <th>Edit</th>
              <th class="text-left">First</th>
              <th class="text-left">Last</th>
              <th class="text-left">Phone</th>
              <th class="text-left">Email</th>
              <th class="text-left">User Type</th>
              <th class="text-left" v-if="modal.getUserRole==0">Max Wkly Hours</th>
              <th class="text-left" v-if="modal.getUserRole==0">Max Wkly Days</th>
              <th class="text-left" v-if="modal.getUserRole==0">Max Day Hours</th>
              <th class="text-left" v-if="modal.getUserRole==0">Max Day Shifts</th>
              <th class="text-left">Zip Code</th>
              <th class="text-left">Hire Date</th>
              <th class="text-left">Priority Group</th>
              <th class="text-left">Action</th>
            </tr>
          </thead>
          <tbody v-if="index.employees.length === 0">
            <tr>
              <td colspan="15" class="text-center">No Records Found</td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr v-for="(data, index) in index.employees" :key="data.id">
              <td class="text-center">
                <input :id="data.id" :index="index" :value="data" v-model="checkedNames" type="checkbox">
              </td>
              <td class="text-center">
                <a href="#" @click.prevent="openModal('EditEmployee', data, index)" v-if="data.employee.added_by==modal.getUserId">
                  <font-awesome-icon icon="pencil-alt" />
                </a>
              </td>
              <td>{{ data.employee.firstname }}</td>
              <td>{{ data.employee.lastname }}</td>
              <td>{{ data.employee.phone }}</td>
              <td class="text-truncate" style="max-width: 150px;">{{ data.employee.email || '-----' }}</td>
              <td v-if="data.employee.enable_security_officer == 0">Concerned Citizen</td>
              <td v-if="data.employee.enable_security_officer == 1">Watcher</td>
              <td class="text-center" v-if="modal.getUserRole == 0">{{ data.employee.max_weekly_hours }}</td>
              <td class="text-center" v-if="modal.getUserRole == 0">{{ data.employee.max_weekly_days }}</td>
              <td class="text-center" v-if="modal.getUserRole == 0">{{ data.employee.max_day_hours }}</td>
              <td class="text-center" v-if="modal.getUserRole == 0">{{ data.employee.max_day_shifts }}</td>
              <td class="text-center">{{ data.employee.zip }}</td>
              <td class="text-center">{{ data.employee.hired_date | moment('MM-DD-YYYY') }}</td>
              <td class="text-center">{{ data.employee.priority_group }}</td>
              <td class="text-center">
                <a v-if="data.employee.added_by == modal.getUserId" href="#" @click.prevent="SetClockinTime(data.employee.id)" class="btn btn-sm btn-outline-danger">
                  <img src="/images/clockin.png" class="h-5" alt="clockin">
                </a>
                <a v-if="data.employee.added_by == modal.getUserId" href="#" @click.prevent="removeEmployee(data.employee.id, data.employee.firstname, data.employee.lastname)" class="btn btn-sm btn-outline-success">
                  <font-awesome-icon :icon="['far', 'trash-alt']" />
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    

      <div class="w-full px-4">

        <div style="display: none;" ref="content" class="tabletopbackground">
        <table class="w-full tabletopposition" ref="content">
        <thead>
          <tr>
            <th class="text-left" style="width: 11%;">First</th>
            <th class="text-left" style="width: 11%;">Last</th>
            <th class="text-left" style="width: 11%;">Phone</th>
            <th class="text-left" style="width: 14%;">Email*</th>
            <th class="leading-tight py-2" style="width: 7%;">Max Wkly Hours</th>
            <th class="leading-tight" style="width: 7%;">Max Wkly Days</th>
            <th class="leading-tight" style="width: 7%;">Max DayHours</th>
            <th class="leading-tight" style="width: 9%;">Max Day Shifts</th>
            <th style="width: 7%;">Hire Date</th>
            <th class="leading-tight" style="width: 9%;">Priority Group</th>
          </tr>
        </thead>
        
        <tbody>
          <tr v-for="data in checkedNames" :key="data.id">
            <td>{{ data.employee.firstname }}</td>
            <td>{{ data.employee.lastname }}</td>
            <td>{{ data.employee.mobile }}</td>
            <td>{{ data.employee.email || '-----' }}</td>
            <td class="text-center">{{ data.employee.max_weekly_hours }}</td>
            <td class="text-center">{{ data.employee.max_weekly_days }}</td>
            <td class="text-center">{{ data.employee.max_day_hours }}</td>
            <td class="text-center">{{ data.employee.max_day_shifts }}</td>
            <td class="text-center">{{ data.employee.hired_date | moment( 'MM-DD-YYYY' ) }}</td>
            <td class="text-center">{{ data.employee.priority_group }}</td>
          </tr>
        </tbody>
      </table>
        </div>
      </div>

    <div class="information mx-4 mt-5 p-4 rounded-lg" >
      <div class="flex flex-col md:flex-row justify-between md:items-center mb-2">
        <h4 class="text-lg font-semibold mb-2 mb-md-0">Information</h4>
        <a href="#" class="text-sm inline-flex items-center">
          Help on this topic &nbsp;<font-awesome-icon icon="arrow-circle-right" />&nbsp;<strong>More</strong>
        </a>
      </div>
      <ul class="mb-3">
        <li class="mb-1">
          <span class="inline-block w-20 px-2" style="background-color: #FCFF3C">None: </span>
          No notifications selected for this email.
        </li>
        <li class="mb-1">
          <span class="inline-block w-20 px-2" style="background-color: #FF0000">Invalid </span>
          The system adds "Invalid" when notifications to this email were returned as undeliverable so now notifications are no longer being sent to that address.
        </li>
        <li class="mb-1">
          <span class="inline-block w-20" style="background-color: #FFA700">&nbsp;</span>
          Changes not saved.
        </li>
        <!-- <li class="mb-1 flex items-center">
          <span class="inline-block w-20 mr-1">
            <img src="/images/icon-google.png" class="h-5 mx-auto" alt="google">
          </span>
          Exporting to Google Calendar
        </li> -->
        <!-- <li class="flex items-center">
          <span class="inline-block w-20 mr-1">
            <img src="/images/icon-googlebroken.png" class="h-5 mx-auto" alt="broken google">
          </span>
          Google Calendar export NOT updating
        </li> -->
      </ul>
      
      <!-- <p class="mb-1">* Your subscription is included under a group of linked accounts for billing purposes. Total shown on this page only shows the employees in your one linked account.</p> -->

      <ul class="list-inside">
        <li class="mb-1">To delete users click the pencil icon next to their name and then click "Delete" (users cannot be bulk deleted.)</li>
        <!-- <li class="mb-1">To give an users manager-level access please go to <strong>Settings>Add/Edit Managers</strong> page. users will then have two logins, one to access as manager and one as employee.</li> -->
        <!-- <li>Trouble with grid displaying?</li> -->
      </ul>
    </div>


		<!-- ================================================ modal ================================================ -->
     <!-- Bulk edit start -->
     <modal v-model="modal.BulkEditEmp" class="modal-add-edit-positions BulkEditEmp" size="md:w-5/12" title="Users bulk EDIT">
		    <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(updateEmployeeBulk)" novalidate ref="bulkEditEmpl">

          <!-- ================================= Contact ================================= -->
          <div class="contact px-6 pb-6 mb-4">
            <h4 class="text-xl font-semibold mb-4">Contact</h4>
            <div class="flex">
              <div class="md:w-3/12">
                <label class="block md:text-right mb-1 mt-1 md:mb-0 pr-4">City, State, Zip</label>
              </div>
              <div class="md:w-5/12">
                <ValidationProvider rules="required" v-slot="v">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.reqEditEmployeeBulk.city">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
              </div>
              <div class="md:w-2/12 mx-1">
                <ValidationProvider rules="required" v-slot="v">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.reqEditEmployeeBulk.state">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
              </div>
              <div class="md:w-2/12">
                <ValidationProvider rules="required" v-slot="v">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.reqEditEmployeeBulk.zip">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
              </div>
            </div>
          </div>
          <!-- ================================= ./Contact ================================= -->


          <!-- ================================= Auto Fill Options ================================= -->
          <div class="auto-fill-options px-6 pb-6 mb-4">
            <h4 class="text-xl font-semibold mb-2">AutoFill options</h4>
            <div>
              
              <!-- ====== Maximums ====== -->
              <div class="c-bg-card flex px-5 py-3 rounded-lg w-5/6 mx-auto">
                <h6 class="w-1/4 font-bold">Maximums</h6>
                <div class="w-3/4">

                  <div class="flex items-center mb-1">
                    <span class="block w-2/12 text-right">per week</span>
                    <div class="w-3/12 mx-2">
                      <ValidationProvider rules="required|min_value:1" v-slot="v">
                        <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-center" type="text" v-model="modal.reqEditEmployeeBulk.max_weekly_hours" maxlength="4" @keypress="isNumberOnly($event)">
                        <small class="text-red-600">{{ v.errors[0] }}</small>
                      </ValidationProvider>
                    </div>
                    <span class="block w-1/12">hrs</span>

                    <div class="w-3/12 ml-6 mr-2">
                      <ValidationProvider rules="required|min_value:1" v-slot="v">
                        <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-center" type="text" v-model="modal.reqEditEmployeeBulk.max_weekly_days" maxlength="4" @keypress="isNumberOnly($event)">
                        <small class="text-red-600">{{ v.errors[0] }}</small>
                      </ValidationProvider>
                    </div>
                    <span class="block w-1/12">days</span>
                  </div>

                  <div class="flex items-center">
                    <span class="block w-2/12 text-right">per day</span>
                    <div class="w-3/12 mx-2">
                      <ValidationProvider rules="required|min_value:1" v-slot="v">
                        <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-center" type="text" v-model="modal.reqEditEmployeeBulk.max_day_hours" maxlength="4" @keypress="isNumberOnly($event)">
                        <small class="text-red-600">{{ v.errors[0] }}</small>
                      </ValidationProvider>
                    </div>
                    <span class="block w-1/12">hrs</span>

                    <div class="w-3/12 ml-6 mr-2">
                      <ValidationProvider rules="required|min_value:1" v-slot="v">
                        <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-center" type="text" v-model="modal.reqEditEmployeeBulk.max_day_shifts" maxlength="4" @keypress="isNumberOnly($event)">
                        <small class="text-red-600">{{ v.errors[0] }}</small>
                      </ValidationProvider>
                    </div>
                    <span class="block w-1/12">shifts</span>
                  </div>

                </div>
              </div>
              <!-- ====== ./Maximums ====== -->


              <!-- ====== Seniority ====== -->
              <div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-5/6 mx-auto my-2">
                <h6 class="w-1/4 font-bold">By Seniority</h6>
                <div class="w-3/4 flex items-center">
                  <span class="block w-2/12 text-right">Hire Date</span>
                  <div class="w-8/12 ml-2">
                    <ValidationProvider rules="required" v-slot="v">
                      <date-picker valueType="format" v-model="modal.reqEditEmployeeBulk.hired_date"
                        input-class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border border-custom-border"
                        :clearable="false"
                        :shortcuts="[
                          { text: 'Yesterday', onClick: () => {
                            let date = new Date()
                            date.setTime( date.getTime() - 3600 * 1000 * 24 )
                            return date
                          } },
                          { text: 'Today', onClick: () => new Date() },
                        ]"></date-picker>
                      <small class="text-red-600 block">{{ v.errors[0] }}</small>
                    </ValidationProvider>
                  </div>
                </div>
              </div>
              <!-- ====== ./Seniority ====== -->

              <!-- ====== Priority Group ====== -->
              <div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-5/6 mx-auto">
                <h6 class="w-1/4 font-bold">By Priority Group</h6>
                <div class="w-3/4 flex">
                  <span class="block w-2/12 text-right"></span>
                  <div class="w-8/12 ml-2">
                    <div class="relative">
                      <select class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none" v-model="modal.reqEditEmployeeBulk.priority_group">
                        <option value="0">Leave unchanged</option>
                        <option value="1">First</option>
                        <option value="2">Second</option>
                        <option value="3">Third</option>
                        <option value="4">Fourth</option>
                        <option value="5">Fifth</option>
                        <option value="6">Sixth</option>
                        <option value="7">Seventh</option>
                        <option value="8">Eight</option>
                        <option value="9">Ninth</option>
                        <option value="10">Tenth</option>
                      </select>
                      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ====== ./Priority Group ====== -->

            </div>
          </div>
          <!-- ================================= ./Auto Fill Options ================================= -->

          
          <!-- ================================= Pay Rate ================================= -->
          <div class="pay-rate px-6 pb-4 mb-4 flex mt-1">
            <h4 class="text-xl font-semibold w-3/12">Pay Rate</h4>
            <div class="w-4/12 mr-2">
              <ValidationProvider rules="required" v-slot="v">
                <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-right" type="text" v-model="modal.reqEditEmployeeBulk.pay_rate" maxlength="10" @keypress="isNumberOnlyAndDecimalPoint($event)">
                <small class="text-red-600 block">{{ v.errors[0] }}</small>
              </ValidationProvider>
            </div>
            <span class="mt-1">per hour default</span>
          </div>
          <!-- ================================= ./Pay Rate ================================= -->
          <!-- ================================= Alert Date ================================= -->
          <div class="pay-rate px-6 pb-4 mb-4 flex mt-1">
            <h4 class="text-xl font-semibold w-3/12">Alert Date </h4>
            <div class="w-4/12 mr-2">
                    <ValidationProvider rules="required" v-slot="v">
                      <date-picker valueType="format" v-model="modal.reqEditEmployeeBulk.alert_date "
                        input-class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border border-custom-border"
                        :clearable="false"
                        :shortcuts="[
                          { text: 'Yesterday', onClick: () => {
                            let date = new Date()
                            date.setTime( date.getTime() - 3600 * 1000 * 24 )
                            return date
                          } },
                          { text: 'Today', onClick: () => new Date() },
                        ]"></date-picker>
                      <small class="text-red-600 block">{{ v.errors[0] }}</small>
                    </ValidationProvider>
            </div>
            <span class="mt-1"> default</span>
          </div>
          <!-- ================================= ./Alert Date================================= -->


          <!-- ================================= Custom Fields ================================= -->
          <div class="custom-fields px-6 pb-4 mb-4 flex">
            <h4 class="text-xl font-semibold w-3/12">Custom Fields</h4>
            <div class="w-9/12">
              <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none mb-1" v-model="modal.reqEditEmployeeBulk.cf_1" type="text">
              <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none"  v-model="modal.reqEditEmployeeBulk.cf_2" type="text">
            </div>
          </div>
          <!-- ================================= ./Custom Fields ================================= -->


          <!-- ================================= Comments ================================= -->
          <div class="comments px-6 pb-4 mb-4 flex">
            <h4 class="text-xl font-semibold w-3/12">Comments</h4>
            <div class="w-9/12">
              <textarea rows="5" class="w-full rounded p-2 leading-tight focus:outline-none" style="border: 1px solid #707070;"  v-model="modal.reqEditEmployeeBulk.comments"></textarea>
            </div>
          </div>
          <!-- ================================= ./Comments ================================= -->

          <div class="flex justify-between mt-10 mb-8 text-center">
            <div style="width:100%; text-align:center;">
              <button class="text-white py-3 px-12 rounded-full bg-custom-primary focus:outline-none" ref="editEmployeeSave" type="submit">Save</button>
               <button class="text-white py-3 px-12 rounded-full bg-custom-primary focus:outline-none" ref="editEmployeeClose" @click.prevent="close" type="button">Cancel</button>
            </div>
          </div>
        </form>
      </ValidationObserver>

        
        </modal>
    
    <!-- bulk edit end -->
        <!-- Send email to one start -->

      <modal v-model="modal.SendMessageEmail"  class="modal-add-new-employee" size="md:w-4/12" title="Send Email">
        <ValidationObserver v-slot="{ handleSubmit }">
          <form @submit.prevent="handleSubmit(SendMessageToOne)" ref="sendmessage" novalidate>
             <!-- ================================= To ================================= -->
            <div class=" px-6 pb-4 pt-4 mb-4 flex">
              <h4 class="text-xl font-semibold w-3/12">To:</h4>
              <div class="w-9/12">
                 <!-- <label class="text-sm" v-for="names in checkedNames" :key="names.id">{{names.employee.email}}</label> -->
                <b class="titleBox text-xl font-semibold comma_after_content"><span class="CheckedDescription"> {{sendEmailtoOne.email}}</span></b>
              </div>
            </div>
            <!-- ================================= ./To ================================= -->
             <!-- ================================= Subject ================================= -->
            <div class=" px-6 pb-4 mb-4 flex">
              <h4 class="text-xl font-semibold w-3/12">Subject</h4>
              <div class="w-9/12">
                 <input type="text" class="w-full rounded p-2 leading-tight focus:outline-none border border-custom-border" v-model="modal.SendMessageToAll.subject" />
              </div>
            </div>
            <!-- ================================= ./Subject ================================= -->
            <!-- ================================= Message ================================= -->
            <div class=" px-6 pb-4 mb-4 flex">
              <h4 class="text-xl font-semibold w-3/12">Message</h4>
              <div class="w-9/12">
                <textarea rows="5" class="w-full rounded p-2 leading-tight focus:outline-none border border-custom-border" v-model="modal.SendMessageToAll.message"></textarea>
              </div>
            </div>
            <!-- ================================= ./Message ================================= -->
            <div class="text-center">
            <input type="submit" value="Send"  class="send_ins">
            </div>
          </form>
        </ValidationObserver>
      </modal>
      <!-- Send Email to one end -->
    <!-- Send Message start -->

      <modal v-model="modal.SendMessage"  class="modal-add-new-employee" size="md:w-4/12" title="Write a Message">
        <ValidationObserver v-slot="{ handleSubmit }">
          <form @submit.prevent="handleSubmit(SendMessageToAll)" ref="sendmessage" novalidate>
             <!-- ================================= To ================================= -->
            <div class=" px-6 pb-4 pt-4 mb-4 flex">
              <h4 class="text-xl font-semibold w-3/12">To:</h4>
              <div class="w-9/12">
                 <!-- <label class="text-sm" v-for="names in checkedNames" :key="names.id">{{names.employee.email}}</label> -->
                <b class="titleBox text-xl font-semibold comma_after_content"><span class="CheckedDescription" v-for="names in checkedNames" :key="names.id"> {{names.employee.email}}</span></b>
              </div>
            </div>
            <!-- ================================= ./To ================================= -->
             <!-- ================================= Subject ================================= -->
            <div class=" px-6 pb-4 mb-4 flex">
              <h4 class="text-xl font-semibold w-3/12">Subject</h4>
              <div class="w-9/12">
                 <input type="text" class="w-full rounded p-2 leading-tight focus:outline-none border border-custom-border" v-model="modal.SendMessageToAll.subject" />
              </div>
            </div>
            <!-- ================================= ./Subject ================================= -->
            <!-- ================================= Message ================================= -->
            <div class=" px-6 pb-4 mb-4 flex">
              <h4 class="text-xl font-semibold w-3/12">Message</h4>
              <div class="w-9/12">
                <textarea rows="5" class="w-full rounded p-2 leading-tight focus:outline-none border border-custom-border" v-model="modal.SendMessageToAll.message"></textarea>
              </div>
            </div>
            <!-- ================================= ./Message ================================= -->
            <div class="text-center">
              <input type="submit" value="Send"  class="send_ins">
            </div>
          </form>
        </ValidationObserver>
      </modal>
      <!-- Send Message end -->

      <!-- Clockin start -->
      <modal v-model="modal.SetClockIn"  class="modal-add-new-employee" size="md:w-4/12" title="Clockin / Clockout">
        <ValidationObserver v-slot="{ handleSubmit }">
          <form @submit.prevent="handleSubmit(SendMessageToAll)" ref="sendmessage" novalidate>
            <div class=" px-6 pb-4 pt-4 mb-4 flex">
              <h4 class="text-xl font-semibold w-3/12">Shift:</h4>
              <div class="w-12/12" style="margin-left:10px" v-if="showAddressData">
                  <select class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none" v-model="modal.locationId" ref="addShiftPosition">
                      <option value="">Select a shift</option>
                      <option :value="data.property.id" v-for="data in showAddressnewData"  :key="data.property.id">{{ data.property.address }}</option>
                    </select>
              </div>
              <div class="w-9/12" v-else>
                  <p>No Shift found</p> 
              </div>
            </div>

            <div class=" px-6 pb-4 mb-4 flex" v-if="showclockin">
              <div class="w-12/12 text-center">
                    <div style="color: #ffffff;background:#ff0000;border-radius: 45px;padding: 20px 10px;margin-left:150px;width: 90px;height: 90px;">
                        <img src="/images/clockin.png" class="h-5 mx-auto" alt="clockin">  
                        <p>Clock Out</p>
                    </div> 

                    <a href="#" @click.prevent="callclockinapi()"  class="flex items-center text-sm"  style="margin-left:155px;margin-top: 20px;">
                      <div class="text-center">
                        <input type="button" value="Clock in"  class="send_ins">
                      </div>
                    </a>

              </div>
            </div>
            <div class=" px-6 pb-4 mb-4 flex" v-if="showclockout">
              <div class="w-12/12 text-center" style="width:100%">
                  
                    <div style="color: #ffffff;background:#006400;border-radius: 45px;padding: 20px 10px;margin-left: 36%;width: 90px;height: 90px;">
                        <img src="/images/clockin.png" class="h-5 mx-auto" alt="clockin">  
                        <p>Clocked In</p>
                    </div>  
                 
                  <a href="#" v-on:click="seen = !seen" class="flex items-center text-sm"  style="margin-left: 36%;margin-top: 20px;">
                    <div class="text-center">
                      <input type="button" value="Clock Out"  class="send_ins">
                    </div>
                  </a> 
              </div>
              </div>
              <div class=" px-6 pb-4 mb-4 flex" v-if="seen">
              
                <div class="w-2/12" style="margin-left:15px;margin-right: 20px;">
                      <div class="text-center">
                        <input type="button" value="Lunch"  class="send_ins" @click.prevent="callclockoutapi(1)">
                      </div>
                </div>
                <div class="w-2/12" style="margin-right: 20px;">
                      <div class="text-center">
                        <input type="button" value="Break"  class="send_ins"  @click.prevent="callclockoutapi(2)">
                      </div>
                </div>
                <div class="w-2/12" style="margin-right: 20px;">
                      <div class="text-center">
                        <input type="button" value="Travel"  class="send_ins"  @click.prevent="callclockoutapi(3)">
                      </div>
                </div>
                <div class="w-2/12">
                      <div class="text-center">
                        <input type="button" value="End Shift"  class="send_ins"  @click.prevent="callclockoutapi(4)">
                      </div>
                </div>
              
            </div>
            
            
          </form>
        </ValidationObserver>
      </modal>
      <!-- Clockin end -->



    <!-- Send Reminder start -->
      <modal v-model="modal.SendReminders" class="modal-add-edit-positions" size="md:w-4/12" title="Send Schedule Reminders">
		     <ValidationObserver v-slot="{ handleSubmit }">
          <form @submit.prevent="handleSubmit(SendReminderToAll)" ref="frmSendReminder" novalidate>
        <div align="center">
        <table class="module">
        <tbody><tr><td class="wgt" width="20%" style="padding-bottom:10px;" valign="top">
        <b class="titleBox text-xl font-semibold">Include</b></td>
        <td class="wgt" width="80%" style="padding-bottom:10px;" valign="top"><b class="titleBox text-xl font-semibold comma_after_content"><span class="CheckedDescription" v-for="names in checkedNames" :key="names.id"> {{names.employee.email}}</span></b></td></tr>
        </tbody></table>
        <table class="module">
        <tbody><tr><td class="wgt"><b class="titleBox text-xl font-semibold">Reminder</b></td></tr>
        <tr><td align="center">
              					<!-- ====== Choose range ====== -->
    					<div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-11/12 mx-auto ">
    						<div class="w-12/12">
    							<span class="block w-12/12 text-right"></span>
    							<div class="w-8/12 ml-2">
    		            <div class="relative">
    		              <select class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none" @change="chooseDateRange"
                      v-model="options.chooseDateRange">
                      <option value="">Choose a Date Range</option>
                      <option value="today">Today</option>
                      <option value="tomorrow">Tomorrow</option>
                      <option value="this-week">This Week</option>
                      <option value="last-week">Last Week</option>
                      <option value="next-week">Next Week</option>
                      <option value="this-month">This Month</option>
                      <option value="last-month">Last Month</option>
                      <option value="next-month">Next Month</option>
    		              </select>
    		              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
    		                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
    		              </div>
    		            </div>
                    <br><span class="small">or click on calendars below to select dates</span>
    							</div>
    						</div>
    					</div>
    					<!-- ====== ./choose range====== -->
        </td></tr>
        <tr><td class="bwgt">
        <table width="100%">
        <tbody>
         <tr><td align="center">
        	<!-- ====== Begin ====== -->
    					<div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-11/12 mx-auto my-2">
    						<div class="w-12/12 flex">
    							<span class="block w-4/12 mt-1 text-right">Begin</span>
    							<div class="w-8/12 ml-2">
                    <ValidationProvider rules="required" v-slot="v">
                        <date-picker valueType="format" v-model="options.beginDate"
		                   input-class="appearance-none block w-full rounded py-1 pl-2 pr-4 leading-tight focus:outline-none border border-custom-border placeholder-black"
                        placeholder="mm/dd/yyyy"
                        format="MM/DD/YYYY"
                        :clearable="false">
                        <i slot="icon-calendar" style="color: #000">
                          <font-awesome-icon :icon="['far', 'calendar']" />
                        </i>
                      </date-picker>
                      <small class="text-red-600 block">{{ v.errors[0] }}</small>
                    </ValidationProvider>
    							</div>
    						</div>
    					</div>
    					<!-- ====== ./Begin ====== -->
              <!-- ====== End ====== -->
    					<div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-11/12 mx-auto my-2">
    						<div class="w-12/12 flex">
    							<span class="block w-4/12 mt-1 text-right">End</span>
    							<div class="w-8/12 ml-2">
                    <ValidationProvider rules="required" v-slot="v">
                      <date-picker valueType="format" v-model="options.endDate"
                        input-class="appearance-none block w-full rounded py-1 pl-2 pr-4 leading-tight focus:outline-none border border-custom-border placeholder-black"
                        placeholder="mm/dd/yyyy"
                        format="MM/DD/YYYY"
                        :clearable="false">
                        <i slot="icon-calendar" style="color: #000">
                          <font-awesome-icon :icon="['far', 'calendar']" />
                        </i> 
                      </date-picker>
                      <small class="text-red-600 block">{{ v.errors[0] }}</small>
                    </ValidationProvider>
    							</div>
    						</div>
    					</div>
    					<!-- ====== ./End ====== -->
              <!-- ====== Subject ====== -->
    					<div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-11/12 mx-auto my-2">
    						<div class="w-12/12 flex">
    							<span class="block w-4/12 mt-1 text-right">Subject</span>
    							<div class="w-8/12 ml-2">
                    <ValidationProvider rules="required" v-slot="v">
                      <input name="Subject" v-model="options.subject" style="width:95%" value="W2W Schedule" type="text" class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border border-custom-border">
                      <small class="text-red-600 block">{{ v.errors[0] }}</small>
                    </ValidationProvider>
    							</div>
    						</div>
    					</div>
    					<!-- ====== ./Subject ====== -->
              <!-- ====== Comment ====== -->
    					<div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-11/12 mx-auto my-2">
    						<div class="w-12/12 flex">
    							<span class="block w-4/12 mt-1 text-left">Comment to Include</span>
    							<div class="w-8/12 ml-2">
                    <ValidationProvider rules="required" v-slot="v">
                      <textarea rows="3" v-model="options.comment" name="Comments" style="width:100%;" class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border border-custom-border"></textarea>
                      <small class="text-red-600 block">{{ v.errors[0] }}</small>
                    </ValidationProvider>
    							</div>
    						</div>
    					</div>
    					<!-- ====== ./Comment ====== -->
          </td></tr>
        

        </tbody></table></td></tr></tbody></table>


        <input type="submit" value="Send Reminders"  class="send_ins">
        <table class="module">
        <tbody><tr><td class="wgt" width="20%">
        <b class="titleBox text-xl font-semibold">Information</b></td> <td class="infomore text-right pr-6">
           <a href="https://www.google.com" class="text-sm inline-flex items-center">
          Help on this topic &nbsp;<font-awesome-icon icon="arrow-circle-right" />&nbsp;<strong>More</strong>
        </a>
          </td>
        </tr>
        <tr><td class="bwgt px-6" style="padding-bottom:10px;" colspan="2">- Included users only sent a reminder if they are scheduled during chosen date range AND have email notification set to send published schedule information.<br>
        <br>- Reminder emails include the comment you enter and the users
        assigned shifts for that date range.<br><br>
        - Sending these reminders will NOT affect the Schedules section status icons for "emailed" or "confirmed."</td></tr>
        </tbody></table>
        </div>
       
  </form>
        </ValidationObserver>        
      </modal>
        <!-- Send Reminder end -->
        <!-- Sign in ins -->
        <modal v-model="modal.SignIn" class="modal-add-edit-positions" size="md:w-7/12" title="Send Users Sign In Instructions">
		    
        <tbody style="display:block;">
          <!-- <tr class="flex items-center justify-between mb-5 mt-5">
            <td class="wgt" colspan="2">
              <b class="titleBox text-xl font-semibold">Send users Sign In Instructions</b>
            </td>
          </tr> -->
          <tr class="flex items-center justify-between mb-5 mt-5" style="display:block;">
            <td class="bwgt" align="center" colspan="2" style=" display:block; padding-bottom:20px;
            text-align: center;font: normal normal normal 18px/23px Source Sans Pro;letter-spacing: 0px;color: #302369;opacity: 1;width: 70%;margin: 0 auto;">
              Click the button below to send individual sign in instructions to all users who have email addresses entered but who have not already signed in.
            </td>
          </tr>
        </tbody>
		   
        <div class="text-center">
        <vue-confirm-dialog ref="sendsign"></vue-confirm-dialog>
         <!-- click showMsgBoxOne sendEmailToAll -->
        <input class="send_ins" type="button" value="Send Instructions" @click="sendEmailToAll()" title="Click to open a window and choose to send out sign in instructions to all users who have e-mails but have not received them">
         </div>

        <tbody style="display: block;
    border-top: 1px solid;
    margin-top: 40px;">
          <tr class="flex items-center justify-between mb-5 mt-5">
            <!-- <td class="wgt" colspan="2"> -->
            <td colspan="2">
              <b class="titleBox text-xl font-semibold">Information</b>
            </td>
            <!-- <td class="infomore">
              <a href="https://www.google.com" class="text-sm inline-flex items-center">
          Help on this topic &nbsp;<font-awesome-icon icon="arrow-circle-right" />&nbsp;<strong>More</strong>
        </a></td> -->
          </tr>
          <tr><td class="bwgt" colspan="2" style="padding-bottom:20px;">
         - Use this feature to send sign in instructions via email to users who have not yet signed in for the first time.
          </td></tr>
          <tr><td class="bwgt" colspan="2" style="padding-bottom:20px;">
       - To send instructions to just one particular users, please use their users Details page. 
          </td></tr>
          <tr><td class="bwgt" colspan="2" style="padding-bottom:20px;">
         - Note you also can PRINT users sign in instructions from their Edit users page.
          </td></tr>
        </tbody>

        
        </modal>
    		<modal v-model="modal.SignInSent" class="modal-add-edit-positions" size="md:w-4/12" title="Send users Sign In Instructions">
		    
        <tbody style="display:block;">
          <tr class="flex items-center justify-between mb-5 mt-5" style="display:block;">
            <td class="wgt" colspan="2" style="display:block;">
              <b style="display:block;text-align: center;font: normal normal 600 22px/28px Source Sans Pro;" class="titleBox text-xl font-semibold mb-5">Login information has been emailed to</b>
                <div class="flex" style="flex-flow: wrap;">
                <li style="list-style: none;text-transform: capitalize;color: #302369; width: 30%;  height: auto;  margin: 8px;" class="text-sm" v-for="data in modal.SignInSuccess" :key="data.name">{{ data.name }}</li>
                </div>              	
            </td>
          </tr>
        </tbody>
		  
		   
        <div class="text-center">
        <input class="send_ins" type="button" value="OK" @click="showMsgBoxThree" title="OK">
         </div>

        
        </modal>
        <!-- Send remoinder success start -->
    		<modal v-model="modal.SendReminderSuccess" class="modal-add-edit-positions" size="md:w-4/12" title="Confirmation">
		    
        <tbody>
          <tr class="flex items-center justify-between mb-5 mt-5">
            <td class="wgt" colspan="2">
              <b class="titleBox text-xl font-semibold">Schedule Reminders</b>
              <div v-show="SendReminderSuccessData.sent">
                <li class="text-sm" v-for="data in SendReminderSuccessData.sent" :key="data.id"> <b>{{ data.firstname }} {{data.lastname}}</b> -Schedule Reminder has been sent</li>
              </div>
              <div v-show="SendReminderSuccessData.sentNot">
               
                  <li class="text-sm" v-for="data in SendReminderSuccessData.sentNot" :key="data.id"> <b>{{ data.firstname }} {{data.lastname}} </b> -Published shifts were not found, so no email was sent.</li>
              
              </div>
              
            </td>
          </tr>
        </tbody>
		  
		   
       

        
        </modal>
        <!-- Send remoinder success start -->
		<modal v-model="modal.addNewEmployee" class="modal-add-new-employee" size="md:w-4/12" title="Add New User with a role" >
      <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(storeEmployee)" ref="frmAddEmployee" novalidate>
          <div
            class="flex flex-col mb-6 mt-3">
            <label class="block text-gray-700 font-semibold mb-2">Select Community:</label>
            <select
              class="block w-full py-2 px-3 rounded-lg border border-gray-300 focus:ring focus:ring-custom-primary focus:border-custom-primary"
              
            >
              <option value="">Select Community</option>
              <option value="">HR</option>
              <option value="">Developer</option>
              <!-- <option :value="data.id" v-for="data in clientlist" :key="data.id">{{ data.clientname }}</option> -->
            </select>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <ValidationProvider rules="required|alpha_spaces" v-slot="{ errors }">
              <div>
                <label class="block text-gray-700 font-semibold mb-2">First Name<span class="text-red-500">*</span></label>
                <input
                  type="text"
                  placeholder="Enter First Name"

                  v-model="modal.addEmployee.firstname"
                  class="block w-full py-2 px-3 rounded-lg border border-gray-300 focus:ring focus:ring-custom-primary focus:border-custom-primary"
                />
                <small class="text-red-600">{{ errors[0] }}</small>
              </div>
            </ValidationProvider>
    
            <ValidationProvider rules="required|alpha_spaces" v-slot="{ errors }">
              <div>
                <label class="block text-gray-700 font-semibold mb-2">Last Name<span class="text-red-500">*</span></label>
                <input
                  type="text"
                  v-model="modal.addEmployee.lastname"
                  placeholder="Enter Last Name"
                  class="block w-full py-2 px-3 rounded-lg border border-gray-300 focus:ring focus:ring-custom-primary focus:border-custom-primary"
                />
                <small class="text-red-600">{{ errors[0] }}</small>
              </div>
            </ValidationProvider>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <ValidationProvider rules="required|email" v-slot="{ errors }">
              <div>
                <label class="block text-gray-700 font-semibold mb-2">Email Address<span class="text-red-500">*</span></label>
                <input
                  type="email"
                  placeholder="Enter your email"

                  v-model="modal.addEmployee.email"
                  class="block w-full py-2 px-3 rounded-lg border border-gray-300 focus:ring focus:ring-custom-primary focus:border-custom-primary"
                />
                <small class="text-red-600">{{ errors[0] }}</small>
              </div>
            </ValidationProvider>
    
            <ValidationProvider rules="required|min:10|numeric" v-slot="{ errors }">
              <div>
                <label class="block text-gray-700 font-semibold mb-2">Phone Number<span class="text-red-500">*</span></label>
                <input
                  type="text"
                  placeholder="Enter Phone Number"

                  v-model="modal.addEmployee.phone"
                  class="block w-full py-2 px-3 rounded-lg border border-gray-300 focus:ring focus:ring-custom-primary focus:border-custom-primary"
                />
                <small class="text-red-600">{{ errors[0] }}</small>
              </div>
            </ValidationProvider>
          </div>

          <div class="mt-6">
            <ValidationProvider rules="required" v-slot="{ errors }">
              <div>
                <label class="block text-gray-700 font-semibold mb-2">Designation<span class="text-red-500">*</span></label>
                <input
                  type="text"
                  placeholder="Enter your designation"

                  v-model="modal.addEmployee.designation"
                  class="block w-full py-2 px-3 rounded-lg border border-gray-300 focus:ring focus:ring-custom-primary focus:border-custom-primary"
                />
                <small class="text-red-600">{{ errors[0] }}</small>
              </div>
            </ValidationProvider>
          </div>


      <div class="text-center mt-10">
        <button
          type="submit"
          class="bg-custom-primary text-white py-3 px-12 rounded-full font-semibold shadow-md hover:bg-custom-primary-dark focus:ring focus:ring-custom-primary"
        >
          Add User
        </button>
      </div>
        <!-- <div>



    			<!-- ================================= Full Name ================================= -->

          <!-- <div class="fullname flex py-5 justify-center px-6 items-center" >
            <div class="w-2/5">
                <div class="md:flex md:items-center">
                  <div class="md:w-2/3">
                    <input class="mr-1 leading-tight font-semibold" type="radio" v-model="modal.addEmployee.enable_security_officer" value="0" style="float: left;margin-top: 7px;margin-right: 10px;"> 
                    <label class="block md:text-left md:mb-0">Concerned Citizen</label>              
                  </div>
                </div>
            </div>

            <div class="w-1/5">
                <div class="md:flex md:items-center">
                  
                  <div class="md:w-2/3">
                     <input class="mr-1 leading-tight font-semibold" type="radio" v-model="modal.addEmployee.enable_security_officer" value="1" style="float: left;margin-top: 7px;margin-right: 10px;">
                     <label class="block md:text-left md:mb-0">Watcher</label>
                  </div>
                </div>
            </div>
          </div> -->
          <!-- <div class="fullname flex py-5 justify-center px-6" v-if="modal.getUserRole==0">
                <div style="width:190px;">Select Community:</div>
                    <select style="appearance:menulist;" class="block appearance-none w-full py-1 px-4 pr-8 rounded-10 height-41 leading-tight focus:outline-none" v-model="modal.addEmployee.clientid" @change="clientselected">
                        <option value="">Select Community</option>  
                        <option :value="data.id" v-for="data in clientlist" :key="data.id">{{ data.clientname }}</option>
                    </select>
          </div> -->

    			<!-- <div class="fullname flex py-5 justify-center px-6">
            <div class="w-1/2">
              <ValidationProvider rules="required|alpha_spaces" v-slot="v">
        				<div class="md:flex md:items-center">
        					<div class="md:w-1/3">
        						<label class="block md:text-right mb-1 md:mb-0 pr-2">First Name<span class="req_form_fields">*</span></label>
        					</div>
        					<div class="md:w-2/3">
        						<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.addEmployee.firstname">                 
        					</div>
        				</div>
                <div class="md:flex md:items-center mb-1">
                  <div class="md:w-1/3"></div>
                  <div class="md:w-2/3">
                    <small class="text-red-600">{{ v.errors[0] }}</small>
                  </div>
                </div>
              </ValidationProvider>
            </div>
 
            <div class="w-1/2">
              <ValidationProvider rules="required|alpha_spaces" v-slot="v">
                <div class="md:flex md:items-center">
        					<div class="md:w-1/3">
        						<label class="block md:text-right mb-1 md:mb-0 pr-2">Last Name<span class="req_form_fields">*</span></label>
        					</div>
        					<div class="md:w-2/3">
        						<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.addEmployee.lastname">
        					</div>
                </div>
                <div class="md:flex md:items-center mb-1">
                  <div class="md:w-1/3"></div>
                  <div class="md:w-2/3">
                    <small class="text-red-600">{{ v.errors[0] }}</small>
                  </div>
                </div>
              </ValidationProvider>
            </div>
          </div> -->
          <!-- <div class="fullname flex py-5 justify-center px-6" v-if="modal.addEmployee.enable_security_officer==1">
            <div class="w-1/2">
            
                <div class="md:flex md:items-center">
        					<div class="md:w-1/3">
        						<label class="block md:text-right mb-1 md:mb-0 pr-2">Profile Picture</label>
        					</div>
        					<div class="md:w-2/3">
        						<!-- <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="file" :value="modal.addEmployee.employee_image"> -->
        					  <!-- <input type="file"  accept="image/*" id="file1" ref="myFiles1" @change="handleFileUpload1"  />
                  </div>
                </div>
            </div>
    			</div> -->
    			<!-- ================================= ./Full Name ================================= -->
    			

          <!-- ================================= Positions ================================= -->
    		  <!-- <div class="positions py-5 mb-4" v-if="modal.addEmployee.enable_security_officer==1">
    		  	<div class="flex justify-between items-center mb-4 px-6">
    		  		<div class="flex items-center leading-none">
    		  			<h4 class="text-xl font-semibold mr-4">Positions<span class="req_form_fields">*</span></h4>
    		  			<a href="#" class="text-sm add-new-position" @click.prevent="openModal('AddNewPosition')"><strong>&plus;</strong> Add New</a>
    		  		</div>
    		  		<ul class="flex grp-select-unselect">
    		  			<li class="pr-2 mr-2" @click="checkAllPositions(true)">
    		  				<label class="flex items-center" for="checkboxSelectAll">
                    <!-- <font-awesome-icon :icon="['far', 'check-square']" class="mr-1" style="color: #3B86FF" /> -->
                    <!-- <input type="checkbox" class="mr-1" v-model="modal.checkBoxOption.selectAll">
    		            <span class="text-sm" id="checkboxSelectAll">Select all</span>
    		          </label>
    		  			</li>
    		  			<li @click="checkAllPositions(false)">
    		  				<label class="flex items-center" for="checkboxUnSelectAll">
                    <font-awesome-icon :icon="['far', 'square']" class="mr-1" /> -->
                    <!-- <input type="checkbox" class="mr-1" v-model="modal.checkBoxOption.unSelectAll">
    		            <span class="text-sm" id="checkboxUnSelectAll">Clear all</span>
    		          </label>
    		  			</li>
    		  		</ul>
    		  	</div> -->

            <!-- <ul class="flex flex-col flex-wrap ml-10 mr-6" v-if="modal.positions.length===0">
              <li>No Records Found</li>
            </ul>
    				<ul class="flex flex-col flex-wrap ml-10 mr-6" v-else>
    					<li v-for="data in modal.positions" :key="data.id">
    						<label class="flex items-center">
    	            <input class="mr-1 leading-tight" type="checkbox" v-model="modal.selectedPositions" :value="data.id">
    	            <span class="text-sm">{{ data.position }}</span>
    	          </label>
    					</li>
    				</ul> -->
    		  <!-- </div> --> 
    		  <!-- ================================= ./Positions ================================= -->




    			<!-- ================================= Contact ================================= -->
    		  <!-- <div class="contact px-6 pb-6 mb-4">
    		  	<h4 class="text-xl font-semibold mb-4">Contact</h4>


            <ValidationProvider rules="email" v-slot="v">
      				<div class="md:flex md:items-center">
      					<div class="md:w-1/4">
      						<label class="block md:text-right mb-1 md:mb-0 pr-4">Email<span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-3/4">
      						<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="email" v-model="modal.addEmployee.email">
      					</div>
      				</div>

              <div class="md:flex md:items-center mb-1">
                <div class="md:w-1/4"></div>
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>
            <ValidationProvider v-slot="v" v-if="modal.addEmployee.enable_security_officer==1">
      				<div class="md:flex md:items-center">
      					<div class="md:w-1/4">
      						<label class="block md:text-right mb-1 md:mb-0 pr-4">Primary Phone</label>
      					</div>
      					<div class="md:w-3/4">
      						<input id="textbox" class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model.number="modal.addEmployee.phone" @input="acceptNumber">
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-1/4"></div>
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>
      				<div class="md:flex md:items-center" v-if="modal.addEmployee.enable_security_officer==1">
      					<div class="md:w-1/4">
      						<label class="block md:text-right mb-1 md:mb-0 pr-4">Secondary Phone</label>
      					</div>
      					<div class="md:w-3/4">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model.number="modal.addEmployee.phone2"  @input="acceptNumber2">
      					</div>
      				</div>
            <!-- <ValidationProvider rules="required" v-slot="v">
      				<div class="md:flex md:items-center">
      					<div class="md:w-1/4">
      						<label class="block md:text-right mb-1 md:mb-0 pr-4">Cell</label>
      					</div>
      					<div class="md:w-3/4">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model.number="modal.addEmployee.mobile" @keypress="isNumberOnly($event)" maxlength="15">
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-1/4"></div>
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider> -->


    				<!-- <div class="md:flex md:items-center mb-1" v-if="modal.addEmployee.enable_security_officer==1">
    					<div class="md:w-1/4"></div>
    					<div class="md:w-3/4">
    						<p class="text-xs note">(for reference only - entering cell here does NOT enable any automated text notifications which are set up in the notifications section)</p>
    					</div>
    				</div> -->

            <!-- <ValidationProvider rules="required" v-slot="v">
      				<div class="md:flex md:items-center">
      					<div class="md:w-1/4">
      						<label class="block md:text-right mb-1 md:mb-0 pr-4">Watchers #</label>
      					</div>
      					<div class="md:w-3/4">
      						<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model.number="modal.addEmployee.employee_no" @keypress="isNumberOnly($event)" maxlength="15">
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-1/4"></div>
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider> -->


            <!-- <ValidationProvider v-slot="v" v-if="modal.addEmployee.enable_security_officer==1">
      				<div class="md:flex md:items-center">
      					<div class="md:w-1/4">
      						<label class="block md:text-right mb-1 md:mb-0 pr-4">Address</label>
      					</div>
      					<div class="md:w-3/4">
        					<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.addEmployee.address">
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-1/4"></div>
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider> -->


    				<!-- <div class="md:flex md:items-center mb-1" v-if="modal.addEmployee.enable_security_officer==1">
    					<div class="md:w-1/4">
    						<label class="block md:text-right mb-1 md:mb-0 pr-4">Address 2</label>
    					</div>
    					<div class="md:w-3/4">
    						<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.addEmployee.address2">
    					</div>
    				</div> -->


    				<!-- <div class="md:flex" v-if="modal.addEmployee.enable_security_officer==1">
    					<div class="md:w-3/12">
    						<label class="block md:text-right mb-1 mt-1 md:mb-0 pr-4">City, State, Zip</label>
    					</div>
    					<div class="md:w-5/12">
                <ValidationProvider v-slot="v">
    						  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.addEmployee.city">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
    					</div>
    					<div class="md:w-2/12 mx-1">
                <ValidationProvider v-slot="v">
    						  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.addEmployee.state">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
    					</div>
    					<div class="md:w-2/12">
                <ValidationProvider v-slot="v">
    						  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.addEmployee.zip">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
    					</div>
    				</div> -->

    		  <!-- </div> -->
    		  <!-- ================================= ./Contact ================================= -->


    			

          <!-- ================================= Auto Fill Options ================================= -->
    			<!-- <div class="auto-fill-options px-6 pb-6 mb-4" v-if="modal.addEmployee.enable_security_officer==1 && modal.getUserRole==99990">
    				<h4 class="text-xl font-semibold mb-2">AutoFill options</h4>
    				<div>
    					
    					<!-- ====== Maximums ====== -->
    					<!-- <div class="c-bg-card flex px-5 py-3 rounded-lg w-5/6 mx-auto">
    						<h6 class="w-1/4 font-bold">Maximums<span class="req_form_fields">*</span></h6>
    						<div class="w-3/4">

    							<div class="flex items-center mb-1">
    								<span class="block w-2/12 text-right">per week</span>
    								<div class="w-3/12 mx-2">
                      <ValidationProvider rules="required|min_value:1" v-slot="v">
                        <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-center" type="text"  v-model.number="modal.addEmployee.max_weekly_hours" maxlength="4" @keypress="isNumberOnly($event)">
                        <small class="text-red-600">{{ v.errors[0] }}</small>
                      </ValidationProvider>
    								</div>
    								<span class="block w-1/12">hrs</span>

    								<div class="w-3/12 ml-6 mr-2">
                      <ValidationProvider rules="required|min_value:1" v-slot="v">
                        <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-center" type="text" v-model.number="modal.addEmployee.max_weekly_days" maxlength="4" @keypress="isNumberOnly($event)">
                        <small class="text-red-600">{{ v.errors[0] }}</small>
                      </ValidationProvider>
    								</div>
    								<span class="block w-1/12">days</span>
    							</div>

    							<div class="flex items-center">
    								<span class="block w-2/12 text-right">per day</span>
    								<div class="w-3/12 mx-2">
                      <ValidationProvider rules="required|min_value:1" v-slot="v">
                        <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-center" type="text" v-model.number="modal.addEmployee.max_day_hours" maxlength="4" @keypress="isNumberOnly($event)">
                        <small class="text-red-600">{{ v.errors[0] }}</small>
                      </ValidationProvider>
    								</div>
    								<span class="block w-1/12">hrs</span>

    								<div class="w-3/12 ml-6 mr-2">
                      <ValidationProvider rules="required|min_value:1" v-slot="v">
                        <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-center" type="text" v-model.number="modal.addEmployee.max_day_shifts" maxlength="4" @keypress="isNumberOnly($event)">
                        <small class="text-red-600">{{ v.errors[0] }}</small>
                      </ValidationProvider>
    								</div>
    								<span class="block w-1/12">shifts</span>
    							</div>

    						</div>
    					</div> -->
    					<!-- ====== ./Maximums ====== -->
    					<!-- ====== Seniority ====== -->
    					<!-- <div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-5/6 mx-auto my-2">

    						<h6 class="w-1/4 font-bold">By Seniority<span class="req_form_fields">*</span></h6>
    						<div class="w-3/4 flex">
    							<span class="block w-2/12 mt-1 text-right">Hire Date</span>
    							<div class="w-8/12 ml-2">
                    <ValidationProvider rules="required" v-slot="v">
                      <date-picker valueType="format" v-model="modal.addEmployee.hired_date"
                        input-class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border border-custom-border"
                        :clearable="false"
                        :shortcuts="[
                          { text: 'Yesterday', onClick: () => {
                            let date = new Date()
                            date.setTime( date.getTime() - 3600 * 1000 * 24 )
                            return date
                          } },
                          { text: 'Today', onClick: () => new Date() },
                        ]"></date-picker>
                      <small class="text-red-600 block">{{ v.errors[0] }}</small>
                    </ValidationProvider>
    							</div>
    						</div>
    					</div> -->
    					<!-- ====== ./Seniority ====== -->

    					<!-- ====== Priority Group ====== -->
    					<!-- <div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-5/6 mx-auto ">
    						<h6 class="w-1/4 font-bold">By Priority Group</h6>
    						<div class="w-3/4 flex">
    							<span class="block w-2/12 text-right"></span>
    							<div class="w-8/12 ml-2">
    		            <div class="relative">
    		              <select class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none" v-model="modal.addEmployee.priority_group">
    		                <option value="1">First</option>
                        <option value="2">Second</option>
                        <option value="3">Third</option>
                        <option value="4">Fourth</option>
    		                <option value="5">Fifth</option>
    		              </select>
    		              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
    		                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
    		              </div>
    		            </div>
    							</div>
    						</div>
    					</div> -->
    					<!-- ====== ./Priority Group ====== -->
<!-- 
    				</div>
    			</div> --> 
    			<!-- ================================= ./Auto Fill Options ================================= -->




    			<!-- ================================= Pay Rate ================================= -->
    			<!-- <div class="pay-rate px-6 pb-4 mb-4 flex" v-if="modal.addEmployee.enable_security_officer==1 && modal.getUserRole==99990">
    				<h4 class="text-xl font-semibold w-3/12">Pay Rate<span class="req_form_fields">*</span></h4>
    				<div class="w-4/12 mr-2">
              <ValidationProvider rules="required" v-slot="v">
                <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-right" type="text" maxlength="10" v-model="modal.addEmployee.pay_rate" @keypress="isNumberOnlyAndDecimalPoint($event)">
                <small class="text-red-600 block">{{ v.errors[0] }}</small>
              </ValidationProvider>
    				</div>
    				<span class="mt-1">per hour default</span>
    			</div> -->
    			<!-- ================================= ./Pay Rate ================================= -->





    			<!-- ================================= Custom Fields ================================= -->
    			<!-- <div class="custom-fields px-6 pb-4 mb-4 flex">
    				<h4 class="text-xl font-semibold w-3/12">Custom Fields*</h4>
    				<div class="w-9/12">
    					<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none mb-1" type="text">
    					<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text">
    				</div>
    			</div> -->
    			<!-- ================================= ./Custom Fields ================================= -->


    			<!-- ================================= Comments ================================= -->
    			<!-- <div class="comments px-6 pb-4 mb-4 flex" v-if="modal.addEmployee.enable_security_officer==1">
    				<h4 class="text-xl font-semibold w-3/12">Comments</h4>
    				<div class="w-9/12">
    					<label class="text-sm">Your Settings currently DO NOT ALLOW users to see this comment</label>
    					<textarea rows="5" class="w-full rounded p-2 leading-tight focus:outline-none border border-custom-border" v-model="modal.addEmployee.comments"></textarea>
    				</div>
    			</div> -->
    			<!-- ================================= ./Comments ================================= -->


    			<!-- ================================= Options ================================= -->
    			<!-- <div class="options px-6 pb-4 flex items-center" v-if="modal.addEmployee.enable_security_officer==1">
    				<h4 class="text-xl font-semibold w-3/12">Options</h4>
    				<div class="w-9/12">
    			  	<label class="flex items-center">
    		        <input class="mr-1 leading-tight font-semibold" type="checkbox" v-model="modal.addEmployee.enable_screen_reader">
    		        <span class="text-sm">This users uses a screen reader and prefers high contrast display</span>
    		      </label>
    				</div>
    			</div> -->

          

    			<!-- ================================= ./Options ================================= -->
    			
    			<!-- <div class="text-center mt-10 mb-8">
    				<button class="text-white py-3 px-12 rounded-full mb-5 bg-custom-primary" type="submit">Add User</button> -->
    		  	<!-- <label class="flex items-center justify-center">
    	        <input class="mr-1 leading-tight font-semibold" v-model="modal.addEmployee.SendEmail" type="checkbox" value="true" checked="checked">
    	        <span class="font-semibold">Email sign in instructions now</span>
    	      </label> -->
          <!-- </div> -->
        
        
        
        
        
        
        <!-- </div> -->
        
        </form>
        
      </ValidationObserver>

			<!-- <div class="information">
				<h4 class="text-xl mb-2">Information</h4>
				<ul class="list-inside">
					<li class="text-sm">* The account main manager can define two custom fields labels.</li>
					<li class="text-sm">** Optional default pay rate can be set here. Pay rates per position can be set later.</li>
				</ul>
			</div> -->
		</modal>


    <modal v-model="modal.editEmployee" class="modal-edit-employee" size="md:w-7/12" title="Edit User">
      <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(updateEmployee)" novalidate>
          <!-- <div class="mt-8 mb-4">
            <div class="flex" style="float:right;">
              <ul class="flex justify-around options">
                  <li> 
                    <a href="#" class="text-custom-primary" @click="resetPassword(modal.reqEditEmployee.id)">
                      <font-awesome-icon icon="print" class="mr-1" />
                      Reset Pass &amp; Print
                    </a>
                  </li>
                </ul>
            </div>
          </div> -->
          <div class="w-3/5 mx-auto mt-8 mb-4">
            <div class="flex justify-around items-center">
              <div>
                <!-- <a href="#" class="text-2xl text-custom-primary" @click.prevent="getPrevNextEmployeeDetail('prev')" v-if="modal.getEmployeeRecord.prev.show">
                  &#9664;
                </a> -->
              </div>
              <div>
                <span class="text-xl font-semibold">Edit {{ modal.fullname }}</span>
              </div>
              <div>
                <!-- <a href="#" class="text-2xl text-custom-primary" @click.prevent="getPrevNextEmployeeDetail('next')" v-if="modal.getEmployeeRecord.next.show">
                  &#9654;
                </a> -->
              </div>
            </div>
          </div>

          <div class="fullname flex py-5 justify-center px-6">
            <div class="w-2/5">
                <div class="md:flex md:items-center">
                  <div class="md:w-2/3">
                    <input class="mr-1 leading-tight font-semibold" type="radio" v-model="modal.reqEditEmployee.enable_security_officer" value="0" style="float: left;margin-top: 7px;margin-right: 10px;"> 
                    <label class="block md:text-left md:mb-0">Concerned Citizen</label>              
                  </div>
                </div>
            </div>

            <div class="w-1/5">
                <div class="md:flex md:items-center">
                  
                  <div class="md:w-2/3">
                     <input class="mr-1 leading-tight font-semibold" type="radio" v-model="modal.reqEditEmployee.enable_security_officer" value="1" style="float: left;margin-top: 7px;margin-right: 10px;">
                     <label class="block md:text-left md:mb-0">Watcher</label>
                  </div>
                </div>
            </div>
          </div>
          <div class="fullname flex py-5 justify-center px-6" v-if="modal.getUserRole==0">
                <div style="width:190px;">Select Community:</div>
                    <select style="appearance:menulist;" class="block appearance-none w-full py-1 px-4 pr-8 rounded-10 height-41 leading-tight focus:outline-none" v-model="modal.reqEditEmployee.clientid" @change="clientselected">
                        <option value="">Select Community</option>  
                        <option :value="data.id" v-for="data in clientlist" :key="data.id">{{ data.clientname }}</option>
                    </select>
          </div>
          <!-- ================================= Full Name ================================= -->
          <div class=" flex py-5 justify-center px-6">
            <div class="w-1/2">
              <ValidationProvider rules="required|alpha_spaces" v-slot="v">
                <div class="md:flex md:items-center">
                  <div class="md:w-1/3">
                    <label class="block md:text-right mb-1 md:mb-0 pr-2">First Name</label>
                  </div>
                  <div class="md:w-2/3">
                    <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.reqEditEmployee.firstname">
                  </div>
                </div>
                <div class="md:flex md:items-center mb-1">
                  <div class="md:w-1/3"></div>
                  <div class="md:w-2/3">
                    <small class="text-red-600">{{ v.errors[0] }}</small>
                  </div>
                </div>
              </ValidationProvider>
            </div>

            <div class="w-1/2">
              <ValidationProvider rules="required|alpha_spaces" v-slot="v">
                <div class="md:flex md:items-center">
                  <div class="md:w-1/3">
                    <label class="block md:text-right mb-1 md:mb-0 pr-2">Last Name</label>
                  </div>
                  <div class="md:w-2/3">
                    <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.reqEditEmployee.lastname">
                  </div>
                </div>
                <div class="md:flex md:items-center mb-1">
                  <div class="md:w-1/3"></div>
                  <div class="md:w-2/3">
                    <small class="text-red-600">{{ v.errors[0] }}</small>
                  </div>
                </div>
              </ValidationProvider>
            </div>
          </div>
          <!-- ================================= Full Name ================================= -->
    			<div class="flex py-5 justify-center px-6" v-if="modal.reqEditEmployee.enable_security_officer==1">
            <div class="w-2/3">
            
        				<div class="md:flex md:items-center">
        					<div class="md:w-1/3">
        						<label class="block md:text-right mb-1 md:mb-0 pr-2">Profile Picture</label>
        					</div>
        					<div class="flex"> 
        						<!-- <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="file" :value="modal.addEmployee.employee_image">                  -->
                    <input type="file" id="file2" ref="myFiles" @change="handleFileUpload" style="display:none;"  />
                    <label for="file2" style="border: 1px solid #707070; padding: 5px;margin: 0px 5px;">Choose File </label> <input type="text" readonly v-model="modal.reqEditEmployee.employee_image" /> 
                  </div>
        				</div>
               
             
            </div>

            <div class="w-1/2">
              
            </div>
    			</div>
    			<!-- ================================= ./Full Name ================================= -->
          <div class="flex py-5 justify-center px-6">
            <div class="w-1/2">
              <ValidationProvider rules="required" v-slot="v">
                <div class="md:flex md:items-center">
                  <div class="md:w-1/3">
                    <label class="block md:text-right mb-1 md:mb-0 pr-2">Email</label>
                  </div>
                  <div class="md:w-2/3">
                   <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="email" v-model="modal.reqEditEmployee.email"> 
                  </div>
                </div>
                <div class="md:flex md:items-center mb-1">
                  <div class="md:w-1/3"></div>
                  <div class="md:w-2/3">
                    <small class="text-red-600">{{ v.errors[0] }}</small>
                  </div>
                </div>
              </ValidationProvider>
            
            </div>

            <div class="w-1/2">
              <ul class="flex justify-around options">
                  <li>
                    <a href="#" class="text-custom-primary" @click="changeEmail(modal.reqEditEmployee.id , modal.reqEditEmployee.email)">
                      <font-awesome-icon icon="pencil-alt" class="mr-1" />
                      Change
                    </a>
                  </li>
               
                </ul>
            </div>
          </div>
                    <div class="fullname flex py-5 justify-center px-6">
            <div class="w-1/2">
              <ValidationProvider rules="required" v-slot="v">
                <div class="md:flex md:items-center">
                  <div class="md:w-1/3">
                    <label class="block md:text-right mb-1 md:mb-0 pr-2">Password</label>
                  </div>
                  <div class="md:w-2/3">
                   <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="password" v-model="modal.reqEditEmployee.password"> 
                  </div>
                </div>
                <div class="md:flex md:items-center mb-1">
                  <div class="md:w-1/3"></div>
                  <div class="md:w-2/3">
                    <small class="text-red-600">{{ v.errors[0] }}</small>
                  </div>
                </div>
              </ValidationProvider>
            
            </div>

            <div class="w-1/2">
             
            </div>
          </div>

          <!-- ================================= ./Full Name ================================= -->

          <!-- ================================= Sign in ================================= -->
          <!-- <div class="sign-in py-5 px-6 mb-4">
            <div class="flex justify-between items-center mb-4">
              <h4 class="text-xl font-semibold mr-4">Sign In</h4>
            </div>

            <div class="flex justify-between">
              <div class="w-1/2">
                <div class="md:flex md:items-center mb-1">
                  <div class="w-2/6">
                    <label class="block mb-1 md:mb-0 pr-4">Email:</label>
                  </div>
                  <div class="w-3/6 text-left">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="email" v-model="modal.reqEditEmployee.email"> 
                 
                   
                  </div>
                </div>
              </div>
              <div class="w-1/2">
                <ul class="flex justify-around options">
                  <li>
                    <a href="#" class="text-custom-primary" @click="changeEmail(modal.reqEditEmployee.id , modal.reqEditEmployee.email)">
                      <font-awesome-icon icon="pencil-alt" class="mr-1" />
                      Change
                    </a>
                  </li>
                  <li>
                    <a href="#" class="text-custom-primary flex items-center" @click="sendEmail(modal.reqEditEmployee)">
                      <img src="/images/icon-email.svg" class="h-4 mr-1" alt="email">
                      Email
                    </a>
                   </li> 
                  <li> 
                    <a href="#" class="text-custom-primary" @click="resetPassword(modal.reqEditEmployee.id)">
                      <font-awesome-icon icon="print" class="mr-1" />
                      Reset Pass &amp; Print
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div> -->
          <!-- ================================= ./Sign in ================================= -->

          <!-- ================================= Positions ================================= -->
          <div class="positions py-5 mb-4" v-if="modal.reqEditEmployee.enable_security_officer==1 ">
            <div class="flex justify-between items-center mb-4 px-6">
              <h4 class="text-xl font-semibold mr-4">Positions</h4>
              <a href="#" class="text-sm add-new-position" @click.prevent="openModal('AddNewPosition')"><strong>&plus;</strong> Add New</a>
            </div>

            <ul class="flex flex-col flex-wrap ml-10 mr-6" v-if="modal.positions.length===0">
              <li>No Records Found</li>
            </ul>
            <ul class="flex flex-col flex-wrap ml-10 mr-6" v-else>
              <li v-for="data in modal.positions" :key="data.id">
                <label class="flex items-center">
                  <input class="mr-1 leading-tight" type="checkbox" v-model="modal.selectedPositions" :value="data.id">
                  <span class="text-sm">{{ data.position }}</span>
                </label>
              </li>
            </ul>
          </div>
          <!-- ================================= ./Positions ================================= -->


          <!-- ================================= Contact ================================= -->
          <div class="contact px-6 pb-6 mb-4">
            <h4 class="text-xl font-semibold mb-4">Contact</h4>
            <div class="md:flex md:items-center mb-1">
              <div class="md:w-1/4">
                <label class="block md:text-right mb-1 md:mb-0 pr-4 flex items-center justify-end">
                  <font-awesome-icon icon="lock" class="mr-1" size="xs" />Email
                </label>
              </div>
              <div class="md:w-3/4 flex items-center">
                <div class="w-3/4">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.reqEditEmployee.email" readonly>
                </div>
                <div class="w-1/4 pl-2">
                  <!-- <a href="#" class="text-sm text-custom-primary"><strong>&plus;</strong> Add Email</a> -->
                </div>
              </div>
            </div>
            <div class="md:flex md:items-center mb-1" v-if="modal.reqEditEmployee.enable_security_officer==1">
              <div class="md:w-1/4">
                <label class="block md:text-right mb-1 md:mb-0 pr-4 flex items-center justify-end">
                  <font-awesome-icon icon="lock" class="mr-1" size="xs" />Phone
                </label>
              </div>
              <div class="md:w-3/4">
                <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.reqEditEmployee.phone" readonly>
              </div>
            </div>


            <!-- <ValidationProvider rules="required" v-slot="v"> -->
              <div class="md:flex md:items-center" v-if="modal.reqEditEmployee.enable_security_officer==1">
                <div class="md:w-1/4">
                  <label class="block md:text-right mb-1 md:mb-0 pr-4">2nd Phone</label>
                </div> 
                <div class="md:w-3/4">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model.number="modal.reqEditEmployee.phone2" @input="acceptNumber2nd">
                </div>
              </div>
              <!-- <div class="md:flex md:items-center mb-1">
                <div class="md:w-1/4"></div>
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div> 
            </ValidationProvider> -->


            <!-- <ValidationProvider rules="required" v-slot="v">
              <div class="md:flex md:items-center">
                <div class="md:w-1/4">
                  <label class="block md:text-right mb-1 md:mb-0 pr-4">Cell</label>
                </div>
                <div class="md:w-3/4">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model.number="modal.reqEditEmployee.mobile" @keypress="isNumberOnly($event)" maxlength="15">
                </div>
              </div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-1/4"></div>
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider> -->


            <!-- <ValidationProvider rules="required" v-slot="v">
              <div class="md:flex md:items-center">
                <div class="md:w-1/4">
                  <label class="block md:text-right mb-1 md:mb-0 pr-4">Watchers #</label>
                </div>
                <div class="md:w-3/4">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model.number="modal.reqEditEmployee.employee_no" @keypress="isNumberOnly($event)" maxlength="15">
                </div>
              </div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-1/4"></div>
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider> -->


            <ValidationProvider v-slot="v" v-if="modal.reqEditEmployee.enable_security_officer==1">
              <div class="md:flex md:items-center">
                <div class="md:w-1/4">
                  <label class="block md:text-right mb-1 md:mb-0 pr-4">Address </label>
                </div>
                <div class="md:w-3/4">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.reqEditEmployee.address">
                </div>
              </div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-1/4"></div>
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small> 
                </div>
              </div>
            </ValidationProvider>


            <div class="md:flex md:items-center mb-1" v-if="modal.reqEditEmployee.enable_security_officer==1">
              <div class="md:w-1/4">
                <label class="block md:text-right mb-1 md:mb-0 pr-4">Address 2</label>
              </div>
              <div class="md:w-3/4">
                <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.reqEditEmployee.address2">
              </div>
            </div>


            <div class="flex" v-if="modal.reqEditEmployee.enable_security_officer==1">
              <div class="md:w-3/12">
                <label class="block md:text-right mb-1 mt-1 md:mb-0 pr-4">City, State, Zip</label>
              </div>
              <div class="md:w-5/12">
                <ValidationProvider v-slot="v">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.reqEditEmployee.city">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
              </div>
              <div class="md:w-2/12 mx-1">
                <ValidationProvider  v-slot="v">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.reqEditEmployee.state">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
              </div>
              <div class="md:w-2/12">
                <ValidationProvider v-slot="v">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.reqEditEmployee.zip">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
              </div>
            </div>
          </div>
          <!-- ================================= ./Contact ================================= -->


          <!-- ================================= Auto Fill Options ================================= -->
          <div class="auto-fill-options px-6 pb-6 mb-4" v-if="modal.reqEditEmployee.enable_security_officer==1 && modal.getUserRole==99990">
            <h4 class="text-xl font-semibold mb-2">AutoFill options</h4>
            <div>
              
              <!-- ====== Maximums ====== -->
              <div class="c-bg-card flex px-5 py-3 rounded-lg w-5/6 mx-auto">
                <h6 class="w-1/4 font-bold">Maximums</h6>
                <div class="w-3/4">

                  <div class="flex items-center mb-1">
                    <span class="block w-2/12 text-right">per week</span>
                    <div class="w-3/12 mx-2">
                      <ValidationProvider rules="required|min_value:1" v-slot="v">
                        <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-center" type="text" v-model="modal.reqEditEmployee.max_weekly_hours" maxlength="4" @keypress="isNumberOnly($event)">
                        <small class="text-red-600">{{ v.errors[0] }}</small>
                      </ValidationProvider>
                    </div>
                    <span class="block w-1/12">hrs</span>

                    <div class="w-3/12 ml-6 mr-2">
                      <ValidationProvider rules="required|min_value:1" v-slot="v">
                        <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-center" type="text" v-model="modal.reqEditEmployee.max_weekly_days" maxlength="4" @keypress="isNumberOnly($event)">
                        <small class="text-red-600">{{ v.errors[0] }}</small>
                      </ValidationProvider>
                    </div>
                    <span class="block w-1/12">days</span>
                  </div>

                  <div class="flex items-center">
                    <span class="block w-2/12 text-right">per day</span>
                    <div class="w-3/12 mx-2">
                      <ValidationProvider rules="required|min_value:1" v-slot="v">
                        <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-center" type="text" v-model="modal.reqEditEmployee.max_day_hours" maxlength="4" @keypress="isNumberOnly($event)">
                        <small class="text-red-600">{{ v.errors[0] }}</small>
                      </ValidationProvider>
                    </div>
                    <span class="block w-1/12">hrs</span>

                    <div class="w-3/12 ml-6 mr-2">
                      <ValidationProvider rules="required|min_value:1" v-slot="v">
                        <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-center" type="text" v-model="modal.reqEditEmployee.max_day_shifts" maxlength="4" @keypress="isNumberOnly($event)">
                        <small class="text-red-600">{{ v.errors[0] }}</small>
                      </ValidationProvider>
                    </div>
                    <span class="block w-1/12">shifts</span>
                  </div>

                </div>
              </div>
              <!-- ====== ./Maximums ====== -->


              <!-- ====== Seniority ====== -->
              <div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-5/6 mx-auto my-2">
                <h6 class="w-1/4 font-bold">By Seniority</h6>
                <div class="w-3/4 flex items-center">
                  <span class="block w-2/12 text-right">Hire Date</span>
                  <div class="w-8/12 ml-2">
                    <ValidationProvider rules="required" v-slot="v">
                      <date-picker valueType="format" v-model="modal.reqEditEmployee.hired_date"
                        input-class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border border-custom-border"
                        :clearable="false"
                        :shortcuts="[
                          { text: 'Yesterday', onClick: () => {
                            let date = new Date()
                            date.setTime( date.getTime() - 3600 * 1000 * 24 )
                            return date
                          } },
                          { text: 'Today', onClick: () => new Date() },
                        ]"></date-picker>
                      <small class="text-red-600 block">{{ v.errors[0] }}</small>
                    </ValidationProvider>
                  </div>
                </div>
              </div>
              <!-- ====== ./Seniority ====== -->

              <!-- ====== Priority Group ====== -->
              <div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-5/6 mx-auto">
                <h6 class="w-1/4 font-bold">By Priority Group</h6>
                <div class="w-3/4 flex">
                  <span class="block w-2/12 text-right"></span>
                  <div class="w-8/12 ml-2">
                    <div class="relative">
                      <select class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none" v-model="modal.reqEditEmployee.priority_group">
                        <option value="1">First</option>
                        <option value="2">Second</option>
                        <option value="3">Third</option>
                        <option value="4">Fourth</option>
                        <option value="5">Fifth</option>
                        <option value="6">Sixth</option>
                        <option value="7">Seventh</option>
                        <option value="8">Eight</option>
                        <option value="9">Ninth</option>
                        <option value="10">Tenth</option>
                      </select>
                      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ====== ./Priority Group ====== -->

            </div>
          </div>
          <!-- ================================= ./Auto Fill Options ================================= -->

          
          <!-- ================================= Pay Rate ================================= -->
          <div class="pay-rate px-6 pb-4 mb-4 flex mt-1" v-if="modal.reqEditEmployee.enable_security_officer==1 && modal.getUserRole==99990">
            <h4 class="text-xl font-semibold w-3/12">Pay Rate**</h4>
            <div class="w-4/12 mr-2">
              <ValidationProvider rules="required" v-slot="v">
                <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-right" type="text" v-model="modal.reqEditEmployee.pay_rate" maxlength="10" @keypress="isNumberOnlyAndDecimalPoint($event)">
                <small class="text-red-600 block">{{ v.errors[0] }}</small>
              </ValidationProvider>
            </div>
            <span class="mt-1">per hour default</span>
          </div>
          <!-- ================================= ./Pay Rate ================================= -->


          <!-- ================================= Custom Fields ================================= -->
          <div class="custom-fields px-6 pb-4 mb-4 flex" v-if="modal.reqEditEmployee.enable_security_officer==1 && modal.getUserRole==99990">
            <h4 class="text-xl font-semibold w-3/12">Custom Fields*</h4>
            <div class="w-9/12">
              <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none mb-1" type="text">
              <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text">
            </div>
          </div>
          <!-- ================================= ./Custom Fields ================================= -->


          <!-- ================================= Comments ============================api/employees===== -->
          <div class="comments px-6 pb-4 mb-4 flex" v-if="modal.reqEditEmployee.enable_security_officer==1">
            <h4 class="text-xl font-semibold w-3/12">Comments</h4>
            <div class="w-9/12">
              <label class="text-sm">Your Settings currently DO NOT ALLOW users to see this comment</label>
              <textarea rows="5" class="w-full rounded p-2 leading-tight focus:outline-none" v-model="modal.reqEditEmployee.comment" style="border: 1px solid;"></textarea>
            </div>
          </div>
          <!-- ================================= ./Comments ================================= -->


          <!-- ================================= Preferences ================================= -->
          <div class="px-6 pb-4 flex items-center" v-if="modal.reqEditEmployee.enable_security_officer==1 && modal.getUserRole==99990">
            <h4 class="text-xl font-semibold w-3/12">Preferences</h4>
            <div class="w-9/12">
              <ul class="flex items-center">
                <li>
                  <a href="#" class="text-custom-primary" @click.prevent="worktimeprefs(modal.reqEditEmployee.id)">
                    <font-awesome-icon icon="pencil-alt" class="mr-1" />
                    Work Time Prefs
                  </a>
                </li>
                <!-- <li class="ml-5">
                  <a href="#" class="text-custom-primary" @click.prevent="testMsg('This function is still on progress')">
                    <font-awesome-icon icon="pencil-alt" class="mr-1" />
                    Position Prefs
                  </a>
                </li> -->
              </ul>
            </div>
          </div>
          <!-- ================================= ./Preferences ================================= -->


          <!-- ================================= Time Off ================================= -->
          <div class="px-6 pb-4 flex items-center" v-if="modal.reqEditEmployee.enable_security_officer==1 && modal.getUserRole==99990">
            <h4 class="text-xl font-semibold w-3/12">Time Off</h4>
            <div class="w-9/12">
              <ul>
                <li>
                  <a href="#" class="text-custom-primary" @click="timeOff(modal.reqEditEmployee.id)">
                    <font-awesome-icon icon="pencil-alt" class="mr-1" />
                    View / Edit
                  </a>
                </li>
              </ul>
            </div>
          </div>
          
          <!-- ================================= ./Time Off ================================= -->

          
          <div class="flex justify-between mt-10 mb-8">
            
            <div>
              <button class="text-white py-3 px-12 rounded-full bg-custom-primary" ref="editEmployeeSave" type="submit">Save</button>
              <!-- <button  v-if="modal.getEmployeeRecord.next.show" class="text-white py-3 px-12 rounded-full ml-2 bg-custom-primary" ref="editEmployeeSaveNext" type="button" @click.prevent="updateAndProceedNext">Save &amp; Next</button> -->
            </div>
          </div>
        
        </form>
      </ValidationObserver>

      <div class="information">
        <h4 class="text-xl mb-2">Information</h4>
        <p class="text-sm mb-1">* The account main manager can add/edit custom field labels.</p>
        <ul class="ml-2">
          <li class="text-sm mb-1">
            Emails marked "None" mean that users has not set up any notifications. Information will not be shown to other users. For privacy reasons, users must sign in and allow this on their INFO page.
          </li>
          <li class="text-sm">
            To make users also a manager go to Settings>Add/Edit Managers page. The users will then have two usernames, one to access as users and one as manager.
          </li>
        </ul>
      </div>
    </modal>

		<modal v-model="modal.addEditPositions" class="modal-add-edit-positions" size="md:w-7/12" title="Add/Edit Positions">
			<div class="new-position px-6 py-6">
        <ValidationObserver v-slot="{ handleSubmit }">
          <form @submit.prevent="handleSubmit(addPosition)" novalidate>
    				<div class="flex items-center justify-between mb-5">
    					<h4 class="text-xl font-semibold">New Position</h4>
    					<button class="text-white py-2 px-16 rounded-full text-sm bg-custom-primary" type="submit">Add</button>
    				</div>
            <ValidationProvider rules="required" v-slot="v">
      				<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none mb-1" type="text" v-model="modal.addPosition.position">
              <small class="text-red-600 block">{{ v.errors[0] }}</small>
      				<p class="text-sm">After adding, set which users can work each position on the <strong>users>Positions Grid</strong></p>
            </ValidationProvider>
          </form>
        </ValidationObserver>
			</div>

		  <div class="list-of-positions py-5 mb-4">
		  	<div class="flex justify-between items-center mb-5 px-6">
		  		<div class="flex items-center leading-none">
		  			<h4 class="text-xl font-semibold">Positions</h4>
		  		</div>
		  		<button class="text-white py-2 px-16 rounded-full text-sm bg-custom-primary" type="button" @click.prevent="openModal('EditDeletePositions')">Delete</button>
		  	</div>

        <ul class="flex flex-col flex-wrap ml-10 mr-6 mb-4" v-if="modal.positions.length===0">
          <li class="text-sm">No Records Found</li>
        </ul>  
				<ul class="flex flex-col flex-wrap ml-10 mr-6 mb-4" v-else>
					<li class="text-sm" v-for="data in modal.positions" :key="data.id">{{ data.position }}</li>
				</ul>
		  </div>

		  <div class="deleted-positions py-5 mb-4">
		  	<div class="flex justify-between items-center mb-5 px-6">
		  		<div class="flex items-center leading-none">
		  			<h4 class="text-xl font-semibold">
		  				Recently Deleted
		  				<span class="text-sm font-normal">Click Position to restore it.</span>
		  			</h4>
		  		</div>
		  	</div>

				<ul class="flex flex-col flex-wrap ml-10 mr-6 mb-4">
          <li v-if="modal.trashedPositions.length===0" class="text-sm">No Records Found</li>
					<li v-else class="text-sm" v-for="(data, index) in modal.trashedPositions" :key="data.id">
            <a href="#" @click.prevent="restorePosition(data.id, index)">{{ data.position }}</a>
          </li>
				</ul>
		  </div>

			<div class="information">
				<h4 class="text-xl mb-2">Information</h4>
				<ul class="list-inside">
					<li class="text-sm">Sort order of positions is alphabetical. You can edit positions to have a leading space or hyphen to list them first</li>
					<li class="text-sm">Only the main manager on the account can delete positions.</li>
				</ul>
			</div>
		</modal>

    <modal v-model="modal.editDeletePositions" class="modal-edit-delete-positions" size="md:w-6/12" :title="`Edit/Delete Positions`">
      <nav class="my-4 edit-delete-positions">
        <ul class="flex justify-center w-5/12 mx-auto">
          <li class="w-2/4 mr-1 rounded-t-lg" :class="{ active: isActive('edit') }" style="display:none;">
            <a href="#" class="block text-center font-semibold text-lg pt-1" @click.prevent="setActive('edit')">Edit</a>
          </li>
          <li class="w-2/4 mr-1 rounded-t-lg" :class="{ active: isActive('delete') }">
            <a href="#" class="block text-center font-semibold text-lg pt-1" @click.prevent="setActive('delete')">Delete</a>
          </li>
        </ul>
      </nav>
      
      <!-- ==================================== Edit position ==================================== -->
      <div v-if="activeItem == 'edit'">
        <div class="flex items-center justify-between">
          <div>
            <h4 class="text-xl font-semibold">Current positions</h4>
            <a href="#" class="text-custom-primary" @click.prevent="sortAlphabetically">Sort alphabetically</a>
          </div>
          <!-- <button class="text-white py-2 px-12 rounded-full text-sm bg-custom-primary" type="button" @click.prevent="saveSortedPosition">Save</button> -->
        </div>

        <draggable v-model="modal.positions" tag="ul" class="w-9/12 mx-auto my-10" handle=".handle"
          @start="isDragging=true" @end="isDragging=false">
          <transition-group type="transition" name="flip-list">
            <li v-for="(data) in modal.positions"  :key="data.id" class="flex items-center justify-between border border-custom-primary mb-2 px-3 py-1 rounded-md">
              <a href="#" @click.prevent="openModal('EditPosition', data)">{{ data.position }}</a>
              <a href="#"><font-awesome-icon icon="bars" class="text-custom-primary handle" /></a>
            </li>
          </transition-group>
        </draggable>

        <div class="text-center">
          <button class="text-white py-2 px-12 rounded-full text-sm bg-custom-primary" type="button" @click.prevent="saveSortedPosition">Save</button>
        </div>
        
        <div class="information mt-5 pt-3">
          <h4 class="text-xl mb-2">Information</h4>
          <ul class="ml-2">
            <li class="text-sm">Click position name to edit and change the name on all schedules (past and future).</li>
            <li class="text-sm">Default sort order of positions is alphabetical. You can change sort order by dragging positions using the [<font-awesome-icon icon="bars" size="xs" />] icon</li>
          </ul>
          <p class="text-red-700 text-sm">* Only the main manager on the account can delete positions.</p>
        </div>
      </div>
      <!-- ==================================== ./Edit position ==================================== -->

      <!-- ==================================== Delete position ==================================== -->
      <div v-if="activeItem == 'delete'">
        <div class="flex items-center justify-between">
          <div>
            <h4 class="text-xl font-semibold">Current positions</h4>
            <a href="#" class="text-custom-primary" @click.prevent="sortAlphabetically">Sort alphabetically</a>
          </div>
          <button class="text-white py-2 px-12 rounded-full text-sm" type="button">Save</button>
        </div>

        <ul class="w-9/12 mx-auto my-10">
          <li v-for="(data, index) in modal.positions" :key="data.id" class="flex items-center justify-between border border-custom-primary mb-2 px-3 py-1 rounded-md">
            <a href="#">{{ data.position }}</a>
            <a href="#" @click.prevent="removePosition(data, index)"><font-awesome-icon :icon="['far', 'trash-alt']" class="text-red-700" /></a>
          </li>
        </ul>

        <div class="text-center">
          <button class="text-white py-2 px-12 rounded-full text-sm" type="button">Save</button>
        </div>
        
        <div class="information mt-5 pt-3">
          <h4 class="text-xl mb-2">Information</h4>
          <ul class="ml-2">
            <li class="text-sm">Default sort order of positions is alphabetical. You can change sort order by dragging positions using the [<font-awesome-icon :icon="['far', 'trash-alt']" size="xs" class="text-red-700" />] icon</li>
          </ul>
          <p class="text-red-700 text-sm">* Only the main manager on the account can delete positions.</p>
        </div>
      </div>
      <!-- ==================================== ./Delete position ==================================== -->
    </modal>

    <modal v-model="modal.showEditPosition" class="modal-edit-delete-positions text-center" size="md:w-4/12" title="Edit this position">
      <p class="w-2/3 mx-auto my-3">This edited position name will display for all existing {{ modal.reqEditPositionDisplay }} shifts in all schedules including PAST schedules.</p>
      <form @submit.prevent="editPosition">
        <input class="appearance-none block w-full rounded py-1 px-4 mb-4 leading-tight focus:outline-none" type="text" v-model="modal.reqEditPosition.position" required>
        <div>
          <button class="text-white text-center py-2 rounded-full bg-custom-primary w-32" type="submit">Ok</button>
          <button class="text-white text-center py-2 rounded-full bg-red-700 w-32 ml-2" type="button" @click.prevent="modal.showEditPosition = !modal.showEditPosition">Cancel</button>
        </div>
      </form>
    </modal>
    <modal v-model="modal.showTimeOff" class="modal-edit-delete-positions text-center" size="md:w-7/12" title="Time Off ">
       <div class="tabletopbackground">
       <table class="w-full tabletopposition">
        <thead>
          <tr>
         
           <th style="width: 33%;" class="text-center">Date Submitted</th>
           <th  style="width: 33%;"class="text-center">Dates Requested</th>
           <th  style="width: 33%;"class="text-center">Status</th>
          
          </tr>
        </thead>
        
        <tbody >
          <template v-if="timeoffdata.length != 0">
          <tr v-for="(data) in timeoffdata" :key="data.id" class="col-md-12">
           
            <td class="col-md-2 text-center tooltip">
              <span v-for="(date , index) in data.time_off" :key="date.id" >
               <span v-if="index == 0  ">{{date | moment('ll')}} - </span>
              <span v-if="index == data.time_off.length -1 ">{{date | moment('ll')}}</span>
                <!-- {{date | moment('ll')}}<br> -->
              </span>
               <span class="tooltiptext">
                   <span v-for="(date) in data.time_off" :key="date.id">
                     {{date | moment('ll')}}<br>
                   </span>
                 </span>
            </td>
            <td class="col-md-2 text-center">{{data.updated_at | moment('ll') }}</td>
            <td class="col-md-2 text-center text-transform-capitalize text-center" >
              <!-- <input class="text-transform-capitalize text-center border-0" style="border:none;" v-model="data.status" readonly/> -->
              <label class="text-transform-capitalize text-center border-0">{{data.status}}</label>
            </td>
         
             
          </tr>
          </template>
          <template v-else>
            <tr>
              <td class="m-2 pl-3">No Records Found</td> 
              <td></td>
              <td></td>
              <td></td>
              <td></td>

            </tr>
          </template>
        </tbody>
      </table>
      </div>
      
    </modal>
    <modal v-model="modal.showworkprefmodal" class="modal-edit-delete-positions text-center" size="md:w-7/12" title="View Work Preference ">
       <div class="tabletopbackground">
       <table class="w-full tabletopposition">
        <thead>
          <tr>
         
           <th style="width: 25%;" class="text-center">Date</th>
           <th style="width: 25%;" class="text-center">Start Time</th>
           <th  style="width: 25%;"class="text-center">Start End</th>
           <th style="width: 25%;" class="text-center"></th>
          
          </tr>
        </thead>
        
        <tbody >
          <template v-if="worktimeprefsdata.length != 0">
          <tr v-for="(data) in worktimeprefsdata" :key="data.id" class="col-md-12">
           
            <td class="col-md-2 text-center tooltip">{{data.work_date | moment('ll') }}</td>
            <td class="col-md-2 text-center text-transform-capitalize">{{data.start_time}}</td>
            <td class="col-md-2 text-center text-transform-capitalize" >{{data.end_time}}</td>
            <td class="col-md-4 text-center text-transform-capitalize flex" >
              <div class="text-center"  @click='openView(data)'><font-awesome-icon icon="pencil-alt"  class="text-gray-500 m-3 font-size-24"  /></div>
              <div class="text-center"  @click='deleteView(data)' ><font-awesome-icon :icon="['far', 'trash-alt']" class="text-gray-500 m-3 font-size-24" /></div>
            </td>
           
         
             
          </tr>
          </template>
          <template v-else>
            <tr>
              <td class="m-2 pl-3">No Records Found</td> 
              <td></td>
              <td></td>
              <td></td>
              <td></td>

            </tr>
          </template>
          <div class="text-center">
          <b-button class="add-blue-button ml-0 mt-2" @click="openworkprefcal(worktimeprefsid)">Add</b-button>
          </div>
        </tbody>
      </table>
      </div>
    </modal>
    <!-- <modal v-model="modal.showworkprefcal" class="modal-add-new-employee" style="margin:0 auto;" size="md:w-7/12" title="Add Work Preference ">
           <b-calendar
              selected-variant="success"
              today-variant="info"
              nav-button-variant="primary"
             
            >
            </b-calendar>
        
        
     
          
          <b-button class="add-blue-button ml-0 mt-2" @click="saveworkpref()">Save</b-button>
     
    
      
    </modal> -->
       <b-modal centered  no-close-on-backdrop  no-close-on-esc hide-header-close class="modal-add-new-employee" id="showworkprefcal" title="Work Preference" style="margin:0 auto;">
	   	
			<b-calendar
				selected-variant="success"
				today-variant="info"
				nav-button-variant="primary"
			  v-model="modal.workprefadd.work_date"
			>
			</b-calendar>

      <label for="timepicker-placeholder1" class="mt-3">Choose Start Time</label>
       <!-- <b-form-timepicker id="timepicker-placeholder1" now-button reset-button @input="validateTimeInterval(modal.workprefadd.start_time,modal.workprefadd.end_time)" v-model="modal.workprefadd.start_time" placeholder="Choose a time" locale="en"></b-form-timepicker> -->
      <vue-timepicker
                    format="hh:mm A"
                    v-model="modal.workprefadd.start_time"
                    input-class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200"
                    @change="validateTimeInterval(modal.workprefadd.start_time,modal.workprefadd.end_time)"
                    close-on-complete
                    hide-clear-button></vue-timepicker>
      <label for="timepicker-placeholder2" class="mt-3">Choose End Time</label> 
       <!-- <b-form-timepicker id="timepicker-placeholder2" now-button reset-button @input="validateTimeInterval(modal.workprefadd.start_time,modal.workprefadd.end_time)" v-model="modal.workprefadd.end_time" placeholder="Choose a time" locale="en"></b-form-timepicker> -->
       <vue-timepicker
                    format="hh:mm A"
                    v-model="modal.workprefadd.end_time"
                    input-class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200"
                    @change="validateTimeInterval(modal.workprefadd.start_time,modal.workprefadd.end_time)"
                    close-on-complete
                    hide-clear-button></vue-timepicker>
      <p class="text-red-500 text-xs italic" v-if="!isValidTimeInterval">Invalid start/end time.</p>
			<template #modal-footer="{ }">  
				<div class="text-center mt-2 mb-2" style="margin:0 auto;">
    				<button v-show="!updateworkpref" @click="saveworkpref()" class="text-white py-3 px-12 rounded-full  bg-custom-primary" type="submit">Save</button>
    				<button v-show="updateworkpref"  @click="updatework()" class="text-white py-3 px-12 rounded-full  bg-custom-primary" type="submit">Update</button>
    				<button v-show="updateworkpref" @click="backworkpref(modal.workprefadd.employee_id)" class="text-white py-3 px-12 rounded-full  bg-custom-primary" type="submit">Back</button>
          		</div>
    		</template>
  </b-modal>
  </div>
</template>

<script>

import Modal from '../shared/Modal'
import Loader from '../shared/Loader'
import axios from 'axios'
import moment from 'moment'
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import draggable from 'vuedraggable'
// import { BModal, VBModal } from 'bootstrap-vue'
import Vue from 'vue';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
// import 'bootstrap/dist/css/bootstrap.css'
// import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'bootstrap-vue/src/components/form-datepicker'
import 'bootstrap-vue/src/components/calendar'

import Modal1 from '../shared/Modal1'
import { BCalendar } from 'bootstrap-vue'
import { BFormDatepicker } from 'bootstrap-vue'
import datetime from 'vuejs-datetimepicker';
import Datepicker from 'vuejs-datepicker';
import VueTimepicker from 'vue2-timepicker'
import 'vue2-timepicker/dist/VueTimepicker.css'
import Header from '../shared/Header.vue';
Vue.component('b-form-datepicker', BFormDatepicker)

  const removeAllSelections = () => {
    window.getSelection().removeAllRanges()
  }

  const selectContent = element => {
    let range = document.createRange()
    range.selectNode(element)
    window.getSelection().addRange(range)
    var txt = window.getSelection().getRangeAt(0).toString();
    document.execCommand('copy')
    removeAllSelections();
  }

  const copySelection = () => {
    setTimeout(() => {
      document.execCommand('copy')
      removeAllSelections()
    })
  }

export default {
	components: {
		Modal,
    Loader,
    DatePicker,
    draggable,
    BootstrapVue,
		IconsPlugin,
		Modal1,
		Modal,
		Loader,
		BCalendar,
		datetime ,
		Datepicker,
		VueTimepicker 
	},
	data() {
		return {
			requestedHeaders: {
				headers: {}
			},
      isLoader: false,

      searchKeyword: '',
      searchTimer: null,
      // select employee
       checkedNames: [],
       bulkids: [],
      // tabs
      activeItem: 'delete',
      seen: false,
      showclockin:false,
      showclockout:false,
      setselectEmyId:'',
      clientlist:[],
      showAddressData:false,
      showAddressnewData:[],
      locationId:'',
			modal: {
        workprefadd:{
          work_date: new Date(),
          start_time : '',
          end_time : '',
        },
        workprefedit:{
          work_date: new Date(),
          start_time : '',
          end_time : '',
        },
        date: new Date(),
        SendMessageToAll:{
        id: [],
        subject: '',
        message: ''
        },
        SignInSuccess : [],
				addNewEmployee: false,
        editEmployee: false,
        addEditPositions: false,
        showTimeOff: false,
        showworkprefmodal: false,
        showworkprefcal: false,
        //sign in ins, bulk edit modals
        SignIn: false,
        BulkEditEmp: false,
        SignInSent: false,
        // send reminder
        SendReminders: false,
        SendReminderSuccess: false,
        SendMessage: false,
        SendMessageEmail : false,
        SetClockIn:false,
        editDeletePositions: false,

        showEditPosition: false,
        reqEditPositionDisplay: '',
        reqEditPosition: '',

        // This is intended for prev/next fn inside EDIT employee
        getEmployeeRecord: {
          prev: {
            show: false,
            data: {},
          },
          next: {
            show: false,
            data: {},
          },
        },

        fullname: '',

        addEmployee: {
          SendEmail : true
        },
        reqEditEmployee: {},
        reqEditEmployeeBulk: {},
        reqEditEmployeeBulk: {
          max_weekly_hours: 40,
          max_weekly_days: 7,
          max_day_hours: 14,
          max_day_shifts: 1
        },


        positions: [],
        trashedPositions: {},
        addPosition: {},

        // Add employee
        selectedPositions: [],
        // This is un/ticking checkbox
        checkBoxOption: {
          selectAll: false,
          unSelectAll: false
        },
      },
       selected : [],
       allSelected: false,
      options: {
				chooseDateRange: '',
				beginDate: '',
        endDate: '',
        id: [],
        subject: '',
        comment: ''
      },
      SendReminderSuccessData:[],

      index: {
        employees: {},
        positions: {},
        selectPosition: '',
        getuserid:''
      },
      sendEmailtoOne:[],
      timeoffdata:[],
      worktimeprefsdata:[],
      worktimeprefsid: '',
      isValidTimeInterval: true,
      savedata: false,
      updateworkpref: false,
      Images:{
        client_image:''
      }
		}
	},
  computed: {
    countTotalEmployees() {
      return this.index.employees.length
    },
    modalEmployeeName() {
      return this.modal.reqEditEmployee.firstname + ' ' + this.modal.reqEditEmployee.lastname
    },
    userRole() {
        let user = localStorage.getItem("user");
        console.log('user get',user);
        if(user){
          return JSON.parse(user).role;
        }else {
         return 0;
        }
    },
    userid() {
      let user = localStorage.getItem('user');
      console.log('user get',user);
        if(user){
          return JSON.parse(user).id;
        }else {
         return 0;
        }
    },
    // ids of selected employees
    BulkIDS(){
      let ids= [];
      this.bulkids = [];
       for (let i = 0; i <= this.checkedNames.length - 1; i++) {
            {
              this.bulkids[i] = this.checkedNames[i].employee_id
            }
        }
        ids = this.bulkids; 
        return Object.values(ids)
    }
  },
  async created() {
    let vm = this
    await vm.indexEmployee()
    await vm.indexPosition('index-position')
    vm.fetchclientlist();
  },
  watch :{
    BulkIDS:{
      handler() {
        console.log(this.BulkIDS, 'checked ids')
      },
       deep: true,
      immediate: true
    }
  },
	methods: {
    // 
    handleFileUpload1(event){
      console.log(event.target.files, 'here')
       this.Images.client_image = event.target.files;
      this.createProfileImage(event.target.files[0]);
    },
    handleFileUpload(event){
      console.log(event.target.files, 'here')
      this.Images.client_image = event.target.files;
      this.modal.addEmployee.employee_image=  event.target.files[0];
      this.modal.reqEditEmployee.employee_image=   event.target.files[0].name;
      // this.createProfileImage(event.target.files[0]);
    },
       createProfileImage(file) {
      var image = new Image();
      var reader = new FileReader();
      var vm = this;
      reader.onload = (e) => {
        vm.image = e.target.result;
         this.modal.addEmployee.employee_image=  this.image;
         console.log(this.modal.addEmployee.employee_image, 'here');
         this.modal.reqEditEmployee.employee_image=  this.image;
         console.log(this.modal.reqEditEmployee.employee_image, 'here');
      };
      reader.readAsDataURL(file);
    },
    clientselected(){
     console.log('id')
    },
    fetchclientlist(){
      let vm = this;
      axios
        .get(`/api/client`)
        .then(res => {
          vm.clientlist = res.data
        })      
    },
    acceptNumber() {
      let y = this.modal.addEmployee.phone;
     if(this.isnumber(y)){
        	var x = y.toString().replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
      this.modal.addEmployee.phone = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
      }
      else{
        alert('invalid')
      }
    },
    acceptNumber2() {
      let y = this.modal.addEmployee.phone2;
     if(this.isnumber(y)){
        	var x = y.toString().replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
      this.modal.addEmployee.phone2 = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
      }
      else{
        alert('invalid')
      }
    },
        acceptNumber2nd() {
      let y = this.modal.reqEditEmployee.phone2;
     if(this.isnumber(y)){
        	var x = y.toString().replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
      this.modal.reqEditEmployee.phone2 = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
      }
      else{
        alert('invalid')
      }
    },
    isnumber(evt){
    evt = evt ? evt : window.event;
      let charCode = evt.which ? evt.which : evt.keyCode;
      // if decimal point (.) is allowed, set this to charCode !== 46
      // otherwise, set this to charCode !== 46
      // if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode == 46) {
      if (
        (charCode > 31 && (charCode < 48 || charCode > 57)) ||
        charCode == 46 ||
        charCode == 9 ||
        charCode == 16
      ) {
        evt.preventDefault();
      } else {
        // this.modal.editClient.phone = evt;
        return true;
      }
    },
    //update work pref
    updatework(){
      let vm =this;
      
      if(vm.savedata){
        let start = vm.modal.workprefadd.start_time ;
        let end = vm.modal.workprefadd.end_time;

        // let startTime = `${start.hh}:${start.mm} ${start.A}`;
        // let endTime = `${end.hh}:${end.mm} ${end.A}`;


      
        
        vm.isLoader = true
        axios
        .post(`/api/updatepreference/${vm.modal.workprefadd.id}`, {employee_id: vm.modal.workprefadd.employee_id , work_date: vm.modal.workprefadd.work_date ,start_time: start ,end_time: end})
        .then(res => {
          if(res.data.status){
          vm.destroystyle();
          vm.worktimeprefs( vm.worktimeprefsid)
          vm.modal.showworkprefmodal = true;
          vm.isLoader = false
          vm.updateworkpref=false;
          }else{
            
          vm.isLoader = false
          vm.savedata = false;
            vm.$swal({
            icon: 'error',
            title: res.data.message,
            showConfirmButton: false,
            timer: 3000
          })
          }
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: res.data.error,
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
        })
       this.$bvModal.hide('showworkprefcal')
         vm.destroystyle();
      }
      else{
          // vm.destroystyle();
        this.$bvModal.show('showworkprefcal')
         vm.$swal({
            icon: 'error',
            title: 'Invalid start/end time',
            showConfirmButton: false,
            timer: 3000
          })
      }
    },
    //delete pref
    deleteView(data){
    let vm = this
        vm.isLoader = true
        axios
        .get(`/api/deletepreference/${data.id}`)
        .then(res => {
          if(res.data.status){
          vm.isLoader = false
          vm.$swal({
            icon: 'success',
            title: res.data.message,
            showConfirmButton: false,
            timer: 3000
          })
          vm.worktimeprefs(data.employee_id)

          }else{
          vm.$swal({
            icon: 'error',
            title: res.data.message,
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
          }
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
        })

    },
    //take back pref 
    backworkpref(id){
      this.updateworkpref = false;
      this.$bvModal.hide('showworkprefcal')
      this.destroystyle();
      this.worktimeprefs(id);
    },
    openView(data){
      let vm = this;
      vm.updateworkpref = true;
      vm.modal.workprefadd = data;
      vm.modal.showworkprefmodal = false;
      this.inject_material_fonts_and_icons_into_header();
      setTimeout(() => {
            this.isValidTimeInterval = true;
            this.$bvModal.show('showworkprefcal')
        }, 500);
    },
    validateTimeInterval(start, end) {
      let vm = this
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

     

      let startTime = moment(`${start.hh}:${start.mm} ${start.A}`, ['h:mm A']),
          endTime = moment(`${end.hh}:${end.mm} ${end.A}`, ['h:mm A'])

      let duration = moment.duration(endTime.diff(startTime)),
          hours = duration.asHours()

      // if total hours is equal to 12, make it positive regardless who"'"s first, am or pm
      // This is for visual only
      if ( hours == -12 ) {
        vm.test = Math.abs(hours).toFixed(2)
        vm.isValidTimeInterval = true
      } else 


      /*if ( startTime == undefined || endTime == undefined || !startTime.isValid() ||  !endTime.isValid() || !startTime.isBefore(endTime) || endTime.isBefore(startTime) ) {*/
      if ( startTime == undefined || endTime == undefined || !startTime.isValid() || !endTime.isValid() || startTime.isSame(endTime) || !startTime.isBefore(endTime) ) {
      // if ( startTime == undefined || endTime == undefined || !startTime.isValid() || !endTime.isValid() || startTime.isSame(endTime) ) {
        vm.isValidTimeInterval = false
        vm.savedata = false
      } else {
        vm.isValidTimeInterval = true
        // vm.test = hours.toFixed(2)
           vm.savedata = true
      }
    },
	destroystyle(){
          $(
      'link[href="https://unpkg.com/bootstrap/dist/css/bootstrap.min.css"]'
    ).remove();
    $(
      'link[href="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css"]'
    ).remove();
    },
        inject_material_fonts_and_icons_into_header() {
      [
        "https://unpkg.com/bootstrap/dist/css/bootstrap.min.css",
        "https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css",
      ].forEach((route) => {
        const link_el = document.createElement("link");

        link_el.setAttribute("rel", "stylesheet");
        link_el.setAttribute("id", "bootstrapcss");
        link_el.setAttribute("href", route);

        document.head.appendChild(link_el);
      });
    },
    //save work pref calendar  
    saveworkpref(){ 
      let vm =this;
      
      if(vm.savedata){
        let start = vm.modal.workprefadd.start_time ;
        let end = vm.modal.workprefadd.end_time;
        let startTime = `${start.hh}:${start.mm} ${start.A}`;
        let endTime = `${end.hh}:${end.mm} ${end.A}`;
        
        vm.isLoader = true
        axios
        .post(`/api/addpreference`, {employee_id: vm.worktimeprefsid , work_date: vm.modal.workprefadd.work_date ,start_time: startTime ,end_time: endTime})
        .then(res => {
          if(res.data.status){
          vm.destroystyle();
          vm.worktimeprefs( vm.worktimeprefsid)
          vm.modal.showworkprefmodal = true;
          vm.isLoader = false
          }else{
            
          vm.isLoader = false
            vm.$swal({
            icon: 'error',
            title: res.data.message,
            showConfirmButton: false,
            timer: 3000
          })
          }
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: res.data.error,
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
        })
       this.$bvModal.hide('showworkprefcal')
         vm.destroystyle();
      }
      else{
          // vm.destroystyle();
        this.$bvModal.show('showworkprefcal')
         vm.$swal({
            icon: 'error',
            title: 'Invalid start/end time',
            showConfirmButton: false,
            timer: 3000
          })
      }

    },
    //open calendar
    openworkprefcal(){
      let vm = this;
      vm.modal.showworkprefmodal = false;
      this.inject_material_fonts_and_icons_into_header();
      vm.modal.workprefadd.start_time = {A:'',hh:'',mm:''}
      vm.modal.workprefadd.end_time = {A:'',hh:'',mm:''}
      setTimeout(() => {
            this.isValidTimeInterval = true;
            this.$bvModal.show('showworkprefcal')
        }, 500);
          // vm.modal.showworkprefcal = true;
    },
    //work time prefs
    worktimeprefs(id){
        let vm = this
        vm.isLoader = true
        axios
        .get(`/api/preference/${id}`)
        .then(res => {
          if(res.data.status){
          vm.worktimeprefsdata = res.data.data
          vm.worktimeprefsdata = vm.worktimeprefsdata.filter(x =>{
            return x.start_time = moment( x.work_date+' '+x.start_time).format('h:mm A')
          }) 
          vm.worktimeprefsdata = vm.worktimeprefsdata.filter(y =>{
            return y.end_time = moment( y.work_date+' '+y.end_time ).format('h:mm A')
          })
          vm.worktimeprefsid = id
          vm.modal.editEmployee = false;
          vm.modal.showworkprefmodal = true;
          vm.isLoader = false
          }else{
        
          vm.isLoader = false
         vm.worktimeprefsid = id
           vm.worktimeprefsdata = [],
            vm.modal.editEmployee = false;
          vm.modal.showworkprefmodal = true;
          }
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
        })
    },
    //TIME OFF
    timeOff(id){
      let vm = this
        vm.isLoader = true
        axios
        .get(`/api/employeetimeoff/${id}`)
        .then(res => {
          if(res.data.status){
          vm.timeoffdata = res.data.data
          vm.modal.editEmployee = false;
          vm.modal.showTimeOff = true;
          vm.isLoader = false
          }else{
          vm.$swal({
            icon: 'error',
            title: res.data.message,
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
          }
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
        })
    },
    //RESET PASSWORD
    resetPassword(id){
    let vm = this
        vm.isLoader = true
        axios
        .get(`/api/resetpassprint/${id}`)
        .then(res => {
          if(res.data.status){
          vm.option = {}
          vm.$swal({
            closeOnClickOutside: false,
            icon: 'success',
            title: 'Reset password successfully generated',
            showConfirmButton: false,
            timer: 2000,
          })
          setTimeout(() => {
          vm.$swal({
            closeOnClickOutside: false,
            icon: '',
            title: res.data.data,
            showConfirmButton: false,
            timer: 10000,
          })
          window.print();
					}, 2500);
          vm.isLoader = false
          }else{
          vm.$swal({
            icon: 'error',
            title: res.data.message,
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
          }
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
        })
    },
    //send email
    sendEmail(data){
      this.sendEmailtoOne = [];
      this.sendEmailtoOne = data;
      this.modal.editEmployee = false
      this.modal.SendMessageEmail = true 
    },
    chooseDateRange() {
			let vm = this
			switch( vm.options.chooseDateRange ) {
				case 'today':
					vm.options.beginDate = moment().format('MM/DD/YYYY')
					vm.options.endDate = moment().format('MM/DD/YYYY')
					break
				case 'tomorrow':
					vm.options.beginDate = moment().add(1, 'day').format('MM/DD/YYYY')
					vm.options.endDate = moment().add(1, 'day').format('MM/DD/YYYY')
					break
				case 'this-week':
					vm.options.beginDate = moment().startOf('isoWeek').format('MM/DD/YYYY')
					vm.options.endDate = moment().endOf('isoWeek').format('MM/DD/YYYY')
					break
				case 'last-week':
					vm.options.beginDate = moment().subtract(1, 'week').startOf('isoWeek').format('MM/DD/YYYY')
					vm.options.endDate = moment().subtract(1, 'week').endOf('isoWeek').format('MM/DD/YYYY')
					break
				case 'next-week':
					vm.options.beginDate = moment().add(1, 'week').startOf('isoWeek').format('MM/DD/YYYY')
					vm.options.endDate = moment().add(1, 'week').endOf('isoWeek').format('MM/DD/YYYY')
					break
				case 'this-month':
					vm.options.beginDate = moment().startOf('month').format('MM/DD/YYYY')
					vm.options.endDate = moment().endOf('month').format('MM/DD/YYYY')
					break
				case 'last-month':
					vm.options.beginDate = moment().subtract(1, 'month').startOf('month').format('MM/DD/YYYY')
					vm.options.endDate = moment().subtract(1, 'month').endOf('month').format('MM/DD/YYYY')
					break
				case 'next-month':
					vm.options.beginDate = moment().add(1, 'month').startOf('month').format('MM/DD/YYYY')
					vm.options.endDate = moment().add(1, 'month').endOf('month').format('MM/DD/YYYY')
					break
				case 'this-quarter':
				case 'last-quarter':
				case 'this-year':
				case 'year-to-date':
				case 'after-today':
				case 'before-today':
				case 'all-dates':
					// vm.options.beginDate = moment().quarter().format('MM/DD/YYYY')
					// vm.options.endDate = moment().quarter().format('MM/DD/YYYY')
					alert('still in progress')
				default:
					vm.options.beginDate = ''
					vm.options.endDate = ''
			}
		},
    // select all checkbox
        selectAll: function() {
            this.checkedNames = [];
            this.allSelected = !this.allSelected
           if(this.allSelected){
                for (let user in this.index.employees) {
                    this.checkedNames.push(this.index.employees[user]);
                    this.allSelected = true
                }
           }
           else{
             this.checkedNames = [];
           }
        },
    // close modal
    close() {
      this.modal.BulkEditEmp = false;
      this.$refs.bulkEditEmpl.reset()
    },
    
    // Select  check message
    SendMessageCheck(func){
      let arr = Object.keys(this.checkedNames).map((k) => this.checkedNames[k])
      if(this.checkedNames.length != 0 && func == 'SendMessage'){
        this.modal.SendMessage = true;
      }
      else if(this.checkedNames.length != 0 && func == 'SendReminders'){
        this.modal.SendReminders = true;
      }
      else if(this.checkedNames.length != 0 && func == 'SendSignIn'){
        this.SendSignIn(this.checkedNames);
      }
      else if(this.checkedNames.length != 0 && func == 'ExportData'){
        this.ExportData(this.checkedNames);
      }
      else if(this.checkedNames.length != 0 && func == 'BulkEdit'){
       if(this.checkedNames.length == 1){
          this.openModal('EditEmployee', this.checkedNames[0]);
       }
       else{
         this.openModal('BulkEditEmp');
       }
      }
      else if(this.checkedNames.length == 0 && (func == 'ExportData' || func == 'BulkEdit' )){
        this.$alert('Please Select at least one row' );
      }
      else{
        this.$alert('First click the checkboxes to the left of each name to select users.' );
      }
    },
    SetClockinTime(employee_id){
      //alert(employee_id);
        this.setselectEmyId = employee_id;
        this.seen = false;
        this.showclockin = false;
        this.showclockout = false;
        this.showAddressData = false;
        let vm = this
        vm.isLoader = true
        vm.modal.locationId = '';
        axios
        .get(`/api/checkshift/${employee_id}`)
        .then(res => {
          console.log('checkshiftrespones',res);
          if(res.data.length>0){
            //vm.showAddressData = res.data[0].property.address;
            vm.showAddressData = true;
            vm.showAddressnewData = res.data;
          }
          vm.modal.SetClockIn = true;
          vm.isLoader = false;
        })

        axios
        .get(`/api/lastshiftentry/${employee_id}`)
        .then(res => {
          console.log('lastshiftresponse',res);
          if(res.data.status==false || res.data.status=='false'){
              vm.showclockin = true;
              vm.showclockout = false;
              vm.modal.locationId = '';
          }else {
            if(res.data.data.onShift==true || res.data.data.onShift=='true'){
              vm.showclockin = false;
              vm.showclockout = true;
              vm.modal.locationId = res.data.data.shift_id;
            }else{
              vm.showclockin = true;
              vm.showclockout = false;
              vm.modal.locationId = '';
            }
          }
           vm.isLoader = false;
        })


    },
    callclockoutapi(reason){
      let vm = this
      var empyId = this.setselectEmyId;
      var getlocationId = vm.modal.locationId;
      vm.isLoader = true
      axios
        .post('/api/clock-out',{employee_id : empyId , reason: reason, locationId:getlocationId})
        .then(res => {
          vm.option = {}
          vm.$swal({
            closeOnClickOutside: false,
            icon: 'success',
            title: `Clocked out Successfully `,
            showConfirmButton: false,
            timer: 5000,
          })
          vm.isLoader = false
          vm.modal.SetClockIn = false;
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: 'Something went wrong, try again',
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
        })
    },
    callclockinapi(){
       let vm = this
       var empyId = this.setselectEmyId;
       var getlocationId = vm.modal.locationId;
       if(getlocationId!=""){
       vm.isLoader = true
       axios
        .post('/api/clock-in',{employee_id : empyId,locationId:getlocationId})
        .then(res => {
          vm.option = {}
          vm.$swal({
            closeOnClickOutside: false,
            icon: 'success',
            title: res.data.message, 
            showConfirmButton: false,
            timer: 5000,
          })
          vm.isLoader = false
          vm.modal.SetClockIn = false;
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: 'Something went wrong, try again',
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
        })
      }else {
        vm.$swal({
            icon: 'error',
            title: 'Shift required',
            showConfirmButton: false,
            timer: 3000
          })
      }
      //alert(this.setselectEmyId)
    },
    // Export data
    ExportData(names){
      let vm = this;
      vm.copyGroupsToClipboard()
    },
    copyGroupsToClipboard() {
        removeAllSelections()
        selectContent(this.$refs.content)
        // copySelection()
         this.$alert('Selected users copied to the clipboard so you can paste in another software system.');
      },
      //Change Email
      changeEmail(id, email){
        let vm = this
        vm.isLoader = true
        axios
        .post('/api/changemail',{employee_id : id , email: email})
        .then(res => {
          if(res.data.status){
          vm.option = {}
          vm.$swal({
            closeOnClickOutside: false,
            icon: 'success',
            title: `Email Changed Successfully `,
            showConfirmButton: false,
            timer: 5000,
          })
          vm.isLoader = false
         vm.modal.reqEditEmployee.email = email
          }else{
          vm.$swal({
            icon: 'error',
            title: res.data.message,
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
          }
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
        })
      },
      // Send Email to one
        SendMessageToOne(){
        let vm = this
        vm.isLoader = true
        vm.modal.SendMessageToAll.id = [vm.sendEmailtoOne.id]
        axios
        .post('/api/employees/send-message', Object.assign(vm.modal.SendMessageToAll))
        .then(res => {
          vm.option = {}
          vm.$swal({
            closeOnClickOutside: false,
            icon: 'success',
            title: `Email Sent `,
            showConfirmButton: false,
            timer: 5000,
          })
          vm.modal.SendMessageEmail = false
          vm.isLoader = false
          vm.$refs.sendmessage.reset();
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
        })
    },
      // Send Message 
    SendMessageToAll(){
        let vm = this
        vm.isLoader = true
        vm.modal.SendMessageToAll.id = vm.bulkids
        axios
        .post('/api/employees/send-message', Object.assign(vm.modal.SendMessageToAll))
        .then(res => {
          vm.option = {}
          vm.$swal({
            closeOnClickOutside: false,
            icon: 'success',
            title: `Message Sent `,
            showConfirmButton: false,
            timer: 5000,
          })
          vm.modal.SendMessage = false
          vm.isLoader = false
          vm.$refs.sendmessage.reset();
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
        })
    },
    //Send Reminder
    SendReminderToAll(){
        let vm = this
        
        vm.isLoader = true
        vm.options.id = vm.bulkids
        axios
        .post('/api/employees/send-reminder', Object.assign(vm.options))
        .then(res => {
          vm.option = {}
          vm.SendReminderSuccessData = res.data
          this.modal.SendReminderSuccess = true;
          vm.modal.SendReminders = false
          vm.isLoader = false
          vm.$refs.frmSendReminder.reset();
        })
    },
    DoMessage(){
       let vm = this
      vm.modal.SendMessage = false;
    },
    // Sign In instructions
    sentIns(){
      let vm = this
      vm.modal.SignIn = false;
      vm.modal.SignInSent = true;
      vm.$confirm.hide();
      },
    SendSignIn(names){
        let vm = this;
        let namelength = names.length

        let ids = [];
         for (let i = 0; i <= names.length - 1; i++) {
            {
              ids[i] = names[i].employee_id
            }
        }
        vm.$confirm(
        {
          message: `Are you sure you want to send sign in instructions to ${namelength} users `,
          button: {
            no: 'No',
            yes: 'Yes'
          },
          /**
          * Callback Function
          * @param {Boolean} confirm
          */
          callback: confirm => {
            if (confirm) {
                   vm.sendEmailToSelectedEmp(ids);
            }
          }
        }
      )
      },
    showMsgBoxThree(){
       let vm = this;
        vm.modal.SignIn = false;
        vm.modal.SignInSent = false;
      },
    showMsgBoxOne() {
       
          let vm = this;
          vm.$confirm(
        {
          message: `Send individual sign in instructions to all users who have email addresses entered but who have not already signed in.`,
          button: {
            no: 'No',
            yes: 'Yes'
          },
          /**
          * Callback Function
          * @param {Boolean} confirm 
          */
          callback: confirm => {
            if (confirm) {
               vm.modal.SignInSent = false;
                vm.modal.SignIn = false;
                vm.sendEmailToAll();
            }
          }
        }
      )
           
    },
    isNumberOnly(evt) {
      evt = (evt) ? evt : window.event
      let charCode = (evt.which) ? evt.which : evt.keyCode
      // if decimal point (.) is allowed, set this to charCode !== 46
      // otherwise, set this to charCode !== 46
      // if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode == 46) {
      if ((charCode > 31 && (charCode < 48 || charCode > 57)) || charCode == 46 || charCode == 9 || charCode == 16) {
        evt.preventDefault()
      } else {
        return true
      }
    },
    isNumberOnlyAndDecimalPoint(evt) {
      evt = (evt) ? evt : window.event
      let charCode = (evt.which) ? evt.which : evt.keyCode
      // if decimal point (.) is allowed, set this to charCode !== 46
      // otherwise, set this to charCode !== 46
      if ((charCode > 31 && (charCode < 48 || charCode > 57)) && (charCode !== 46 || this.modal.addEmployee.pay_rate.split('.').length === 2 || this.modal.reqEditEmployee.pay_rate.split('.').length === 2)) {
        evt.preventDefault()
      } else {
        return true
      }
    },

    /**
     * Search for 
     * @return {[type]} [description]
     */
    search() {
      let vm = this

      if (vm.searchKeyword == " ") return false
      if ( vm.searchTimer ) {
        clearTimeout(vm.searchTimer)
        vm.searchTimer = null
      }
      vm.searchTimer = setTimeout(() => {
        vm.isLoader = true
        axios
          .get(`/api/employee-search?kw=${vm.searchKeyword}&position_id=${vm.index.selectPosition}&userid=${vm.userid}&userRole=${vm.userRole}&has_email=both`)
          .then(res => {
            vm.index.employees = res.data
            vm.isLoader = false
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
      }, 500)
    },
 
		/**
		 * Open modal
		 * 
		 * @param  String modal
		 */
		async openModal(modal, data) {
      console.log(`openModal called with modal: ${modal}, data:`, data);
			let vm = this
			switch(modal) {
				case 'AddNewEmployee':
					vm.modal.addNewEmployee = true
          console.log(`Modal: ${modal}, Data:`, data);
          vm.modal.getUserRole = vm.userRole
          vm.modal.getUserId = vm.userid
          vm.modal.addEmployee = {
            enable_security_officer: 0,
            priority_group:1,
            SendEmail:true,
            hired_date: moment().format('YYYY-MM-DD')
          }
          vm.$refs.frmAddEmployee.reset()
          vm.indexPosition('modal-position')
					break
        case 'EditEmployee':
          vm.modal.editEmployee = true
          vm.modal.getUserRole = vm.userRole
          vm.modal.getUserId = vm.userid
          // Show the prev/next button
          vm.modal.getEmployeeRecord.prev.show = true
          vm.modal.getEmployeeRecord.next.show = true

          vm.isLoader = true

          // This will filter the arrow if it already meet it's first/last record
          let filterPrevNextBtn = await axios.get(`/api/employee/${data.employee.id}/prevnextrecord`)
          vm.modal.getEmployeeRecord.prev.show = (filterPrevNextBtn.data.prev == null)?false:true
          vm.modal.getEmployeeRecord.next.show = (filterPrevNextBtn.data.next == null)?false:true

          // Get the employee's position(s)
          let fetchEmployeePositions = await axios.get(`/api/employee/${data.employee.id}/positions`)
          // Map employee's position to checkbox
          let arrPositionIds = []
          fetchEmployeePositions.data.forEach(val => arrPositionIds.push(val.position_id))
          vm.modal.selectedPositions = arrPositionIds


          vm.modal.fullname = `${data.employee.firstname} ${data.employee.lastname}`

          vm.modal.reqEditEmployee = {
            id: data.employee.id,
            firstname: data.employee.firstname,
            lastname: data.employee.lastname,
            email: data.employee.email,
            employee_image: data.employee.employee_image,
            password: data.employee.plain_password,
            phone: data.employee.phone,
            phone2: data.employee.phone2,
            mobile: data.employee.mobile,
            employee_no: data.employee.employee_no,
            address: data.employee.address,
            address2: data.employee.address2,
            city: data.employee.city,
            state: data.employee.state,
            zip: data.employee.zip,
            comment: data.employee.comment,
            enable_security_officer: data.employee.enable_security_officer,
            max_weekly_hours: data.employee.max_weekly_hours,
            max_weekly_days: data.employee.max_weekly_days,
            max_day_hours: data.employee.max_day_hours,
            max_day_shifts: data.employee.max_day_shifts,

            pay_rate: data.employee.pay_rate,
            hired_date: moment( data.employee.hired_date ).format('YYYY-MM-DD'),
            priority_group: data.employee.priority_group,
            // position: fetchEmployeePositions.data,
          }



          vm.indexPosition('modal-position')
          vm.isLoader = false
          break
        case 'AddNewPosition':
          // vm.modal.addNewEmployee = false
          vm.modal.addEditPositions = true
          break
				case 'AddEditPositions':
					vm.modal.addEditPositions = true
          vm.indexPosition('modal-position')
          vm.trashedPosition()
					break
				case 'SignIn':
          vm.modal.SignIn = true
					break
				case 'BulkEditEmp':
          vm.modal.BulkEditEmp = true
					break
				case 'SignInSent':
					vm.modal.SignIn = false
					vm.modal.SignInSent = true
					break
        case 'EditDeletePositions':
          vm.modal.addEditPositions = false   // close the parent modal
          vm.modal.editDeletePositions = true
          break
        case 'EditPosition':
          vm.modal.showEditPosition = true
          vm.modal.reqEditPositionDisplay = data.position
          vm.modal.reqEditPosition = {
            id: data.id,
            position: data.position
          }
          break
			}
		},

    /**
     * List all employee
     */
    indexEmployee(position='') { 
      let vm = this
      console.log('vm.userid',vm.userid);
      console.log('vm.userRole',vm.userRole);
      vm.isLoader = false
      vm.modal.getUserId = vm.userid
      axios
        .get(`/api/employees?position_id=${position}&userid=${vm.userid}&userRole=${vm.userRole}&kw=${vm.searchKeyword}&has_email=both`)
        .then(res => {
          vm.index.employees = res.data
          
          vm.isLoader = false
        })
    },
    // Send Mail To New Employee Added
    sendMailToNewEmp(){
      let vm = this;
      let ids = [];
      ids[0] = vm.index.employees[0].employee_id ;
                  axios.post('/api/employee/send-signin-instruction-to-employee' , {ids} )
                    .then(res => {
                      vm.$alert('Sign in Instructions have been sent to new employee!');
                    })
                    .catch(err => {
                      console.log('send email to selected error')
                    })

    },

    /**
     * Filter result via position id
     * 
     * @param  int position
     */
    filterResultViaPosition(position) {
      this.indexEmployee(position)
    },

    /**
     * Load positions
     * 
     * @param  String requestedBy [part the API is being requested]
     */
    async indexPosition(requestedBy) {
      let vm = this
      let position = await axios.get('/api/positions')

      switch (requestedBy) {
        case 'index-position':
          vm.index.positions = position.data.data
          break
        case 'modal-position':
          vm.modal.positions = position.data.data
          break
      }
    },

    /**
     * Send Email to all
     * 
     * @param  String requestedBy [part the API is being requested]
     */
    async sendEmailToAll() {
      let vm = this
      vm.isLoader = true;
      await axios.post('/api/employee/send-signin-instruction-to-all').then(res => {
                      if(res.data){
                        vm.isLoader = false;
                        vm.modal.SignInSuccess = res.data;
                         vm.sentIns();
                      }
                      else{
                        vm.isLoader = false;
                        alert('email error')
                      }
                    })
                    .catch(err => {
                      console.log('email error')
                    });
    },
    /**
     * Send Email to selected employee
     * 
     * @param  String requestedBy [part the API is being requested]
     */
    async sendEmailToSelectedEmp(ids) {
      let vm = this
      await  axios.post('/api/employee/send-signin-instruction-to-employee' , {ids} )
                    .then(res => {
                      vm.$alert('Sign in Instructions have been sent to selected users!');
                    })
                    .catch(err => {
                      console.log('send email to selected error')
                    })
    },

    /**
     * Load position(s) of an employee
     * 
     * @param  int empId
     */
    async showEmployeePosition(empId) {
      let vm = this
      // let employeePositions = await axios.get()
    },

    /*
      | =======================================
      |  [ Modal ] - Add Employee
      | =======================================
      */
    checkAllPositions(bool) {
      let vm = this

      let selected = []

      if (bool) {
        vm.modal.positions.forEach(pos => selected.push(pos.id))
        vm.modal.selectedPositions = selected
        vm.modal.checkBoxOption.selectAll = true
        vm.modal.checkBoxOption.unSelectAll = false
      } else {
        vm.modal.selectedPositions = []
        vm.modal.checkBoxOption.selectAll = false
        vm.modal.checkBoxOption.unSelectAll = true
      }
    },
    storeEmployee() {
      let vm = this

      if (vm.modal.addEmployee.enable_security_officer==1 && Object.keys(vm.modal.selectedPositions).length === 0 ) {
        vm.$swal({
          icon: 'error',
          title: 'At least select a position',
          showConfirmButton: false,
          timer: 1500
        })
        return false
      }

      vm.isLoader = true
      const mypostparameters= new FormData();
      mypostparameters.append('firstname', vm.modal.addEmployee.firstname);
      mypostparameters.append('lastname', vm.modal.addEmployee.lastname);
      mypostparameters.append('email', vm.modal.addEmployee.email);  
      mypostparameters.append('employee_image', vm.modal.addEmployee.employee_image);
      mypostparameters.append('priority_group', vm.modal.addEmployee.priority_group);
      mypostparameters.append('phone', vm.modal.addEmployee.phone);
      mypostparameters.append('phone2', vm.modal.addEmployee.phone2);
      mypostparameters.append('city', vm.modal.addEmployee.city);
      mypostparameters.append('state', vm.modal.addEmployee.state);
      mypostparameters.append('zip', vm.modal.addEmployee.zip);
      mypostparameters.append('address', vm.modal.addEmployee.address);
      mypostparameters.append('address2', vm.modal.addEmployee.address2);
      mypostparameters.append('max_weekly_hours', vm.modal.addEmployee.max_weekly_hours);
      mypostparameters.append('max_weekly_days', vm.modal.addEmployee.max_weekly_days);
      mypostparameters.append('max_day_hours', vm.modal.addEmployee.max_day_hours);
      mypostparameters.append('max_day_shifts', vm.modal.addEmployee.max_day_shifts);
      mypostparameters.append('hired_date', vm.modal.addEmployee.hired_date);
      mypostparameters.append('pay_rate', vm.modal.addEmployee.pay_rate);
      mypostparameters.append('comments', vm.modal.addEmployee.comments);
      mypostparameters.append('positions', vm.modal.selectedPositions);
      mypostparameters.append('enable_screen_reader', vm.modal.addEmployee.enable_screen_reader);
      mypostparameters.append('enable_security_officer', vm.modal.addEmployee.enable_security_officer);
      
      if(vm.modal.addEmployee.clientid!=undefined && vm.modal.addEmployee.clientid!=""){
        mypostparameters.append('added_by', vm.modal.addEmployee.clientid);
      }else {
        mypostparameters.append('added_by', vm.userid);
      }
      
      mypostparameters.append('userRole', vm.userRole);
      
      
      mypostparameters.append('SendEmail', vm.modal.addEmployee.SendEmail);
      if(this.Images.client_image){
				for( var i = 0; i <  this.Images.client_image.length; i++ ){
				let file =  this.Images.client_image[i];
				mypostparameters.set('employee_image', file);
				}
			}
      axios
        .post('/api/addemployee',mypostparameters)
        .then(res => {
          vm.indexEmployee()
          // if(vm.modal.addEmployee.SendEmail){
          //    vm.sendMailToNewEmp()
          // }
          vm.modal.addEmployee = {}
          vm.$swal({
            icon: 'success',
            title: 'Successfully Added!',
            showConfirmButton: false,
            timer: 1500
          })
          vm.modal.addNewEmployee = false
          vm.isLoader = false
          // vm.$refs.frmAddEmployee.reset();
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

    /*
      | =======================================
      |  [ Modal ] - Edit Employee
      | =======================================
      */
    /**
     * Get Employee Detail
     * 
     * @param string value [prev || next]
     */
    async getPrevNextEmployeeDetail(value) {
      let vm = this
      vm.isLoader = true
      try {
        let resEmployee = await axios.get(`/api/employee/${vm.modal.reqEditEmployee.id}/prevnextrecord`)

        // Map employee's position to checkbox
        let arrPositionIds = []

        if ('prev' === value) {
          vm.modal.reqEditEmployee = resEmployee.data.prev
          vm.modal.getEmployeeRecord.prev.show = (resEmployee.data.prev == null || resEmployee.data.prev.id == resEmployee.data.first.id)?false:true
          vm.modal.getEmployeeRecord.next.show = true
          vm.modal.fullname = `${resEmployee.data.prev.firstname} ${resEmployee.data.prev.lastname}`

          resEmployee.data.prev.position.forEach(val => arrPositionIds.push(val.position_id))
          vm.modal.selectedPositions = arrPositionIds
        } else {
          vm.modal.reqEditEmployee = resEmployee.data.next
          vm.modal.getEmployeeRecord.prev.show = true
          vm.modal.getEmployeeRecord.next.show = (resEmployee.data.next == null || resEmployee.data.next.id == resEmployee.data.last.id)?false:true
          vm.modal.fullname = `${resEmployee.data.next.firstname} ${resEmployee.data.next.lastname}`

          resEmployee.data.next.position.forEach(val => arrPositionIds.push(val.position_id))
          vm.modal.selectedPositions = arrPositionIds
        }
        vm.isLoader = false
      } catch (e) {
        vm.isLoader = false
      }
    },

    /**
     * Update employee
     */
    async updateEmployee() {
      let vm = this
      if (vm.modal.reqEditEmployee.enable_security_officer==1 && Object.keys(vm.modal.selectedPositions).length === 0 ) {
        vm.$swal({
          icon: 'error',
          title: 'At least select a position',
          showConfirmButton: false,
          timer: 1500
        })
        return false
      }

      vm.isLoader = true
       const mypostparameters= new FormData();
      mypostparameters.append('firstname', vm.modal.reqEditEmployee.firstname);
      mypostparameters.append('lastname', vm.modal.reqEditEmployee.lastname);
      mypostparameters.append('employee_image', vm.modal.reqEditEmployee.employee_image);
      mypostparameters.append('priority_group', vm.modal.reqEditEmployee.priority_group);
      mypostparameters.append('phone', vm.modal.reqEditEmployee.phone);
      mypostparameters.append('phone2', vm.modal.reqEditEmployee.phone2);
      mypostparameters.append('city', vm.modal.reqEditEmployee.city);
      mypostparameters.append('state', vm.modal.reqEditEmployee.state);
      mypostparameters.append('zip', vm.modal.reqEditEmployee.zip);
      mypostparameters.append('address', vm.modal.reqEditEmployee.address);
      mypostparameters.append('address2', vm.modal.reqEditEmployee.address2);
      mypostparameters.append('enable_screen_reader', vm.modal.reqEditEmployee.enable_screen_reader);
      mypostparameters.append('SendEmail', vm.modal.reqEditEmployee.SendEmail);
      mypostparameters.append('max_weekly_hours', vm.modal.reqEditEmployee.max_weekly_hours);
      mypostparameters.append('max_weekly_days', vm.modal.reqEditEmployee.max_weekly_days);
      mypostparameters.append('max_day_hours', vm.modal.reqEditEmployee.max_day_hours);
      mypostparameters.append('max_day_shifts', vm.modal.reqEditEmployee.max_day_shifts);
      mypostparameters.append('hired_date', vm.modal.reqEditEmployee.hired_date);
      mypostparameters.append('pay_rate', vm.modal.reqEditEmployee.pay_rate);
      mypostparameters.append('comments', vm.modal.reqEditEmployee.comments);
      mypostparameters.append('positions', vm.modal.selectedPositions);
      mypostparameters.append('id', vm.modal.reqEditEmployee.id);
      mypostparameters.append('email', vm.modal.reqEditEmployee.email);
      mypostparameters.append('plain_password', vm.modal.reqEditEmployee.password);
      mypostparameters.append('added_by', vm.modal.reqEditEmployee.clientid);
      mypostparameters.append('enable_security_officer', vm.modal.reqEditEmployee.enable_security_officer);
      if(this.Images.client_image){
				for( var i = 0; i <  this.Images.client_image.length; i++ ){
				let file =  this.Images.client_image[i];
				mypostparameters.set('employee_image', file);
				}
			}
      try {
        
        await axios.post(`/api/employees`, mypostparameters)
        vm.indexEmployee()
        vm.$swal({
          icon: 'success',
          title: 'Successfully updated!',
          showConfirmButton: false,
          timer: 1500
        })
        vm.isLoader = false
        vm.modal.editEmployee = false
      } catch (e) {
        vm.isLoader = false
      }
    },

    /**
    /**
     * Bulk Update employee
     */
    async updateEmployeeBulk() {
      let vm = this
      vm.isLoader = true
      vm.modal.reqEditEmployeeBulk.ids = vm.BulkIDS
      try {
        await axios.post(`/api/employees/employee-bulk-edit`, Object.assign(vm.modal.reqEditEmployeeBulk))
        // vm.indexEmployee()
        vm.$swal({
          icon: 'success',
          title: 'Successfully updated!',
          showConfirmButton: false,
          timer: 1500
        })
        vm.isLoader = false
        vm.indexEmployee()
        vm.modal.BulkEditEmp = false
        vm.allSelected = false
      } catch (e) {
        vm.isLoader = false
      }
    },

    /**
     * Update employee then proceed to next
     */
    async updateAndProceedNext() {
      let vm = this
      if ( Object.keys(vm.modal.selectedPositions).length === 0 ) {
        vm.$swal({
          icon: 'error',
          title: 'At least select a position',
          showConfirmButton: false,
          timer: 1500
        })
        return false
      }

      vm.isLoader = true
      try {
        await axios.post(`/api/employees`,{id:vm.modal.reqEditEmployee.id}, Object.assign(vm.modal.reqEditEmployee, { positions: vm.modal.selectedPositions }))
        vm.indexEmployee()
        vm.$swal({
          icon: 'success',
          title: 'Successfully updated!',
          showConfirmButton: false,
          timer: 1500
        })
        vm.getPrevNextEmployeeDetail('next')
        // vm.isLoader = false
      } catch (e) {
        vm.isLoader = false
      }
      
    },

    /**
     * Remove employee
     */
    async removeEmployee(id,firstname,lastname) {
      let vm = this
      try {
        if (confirm('Are you sure you want to remove '+firstname+' '+lastname+'?')) {
          vm.isLoader = true
          await axios.post(`/api/deletemployees`,{id:id})
          vm.indexEmployee()
          vm.$swal({
            icon: 'success',
            title: 'Successfully removed!',
            showConfirmButton: false,
            timer: 1500
          })
          vm.isLoader = false
          vm.modal.editEmployee = false
        }
      } catch (e) {
        console.log('error', e)
      }
    },



    /*
      | =======================================
      |  [ Modal ] - Add/Edit Positions
      | =======================================
      */
    trashedPosition() {
      axios
        .get('/api/positions-trashed')
        .then(res => this.modal.trashedPositions = res.data)
    },
    addPosition() {
      let vm = this
      let inputData = {
        position: vm.modal.addPosition.position
      }

      axios
        .post('/api/positions', inputData)
        .then(res => {
          if(res.data.status){
            vm.$swal({
            icon: 'success',
            title: res.data.message,
            showConfirmButton: false,
            timer: 2000
          })
          vm.modal.addPosition.position = ''

          axios.get('/api/positions').then(res=>{
            vm.modal.positions = res.data.data
          })
          
          }
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 1500
          })
        })
    },
    /**
     * Edit specific position
     */
    editPosition() {
      let vm = this
      vm.isLoader = true
      axios
        // .post(`/api/positions/${vm.modal.reqEditPosition.id}`, {position: vm.modal.reqEditPosition.position})
        .post(`/api/positions/${vm.modal.reqEditPosition.id}`, {position: vm.modal.reqEditPosition.position})
        .then(res => {
          vm.$swal({
            icon: 'success',
            title: 'Successfully updated!',
            showConfirmButton: false,
            timer: 1500
          })
          vm.modal.reqEditPosition = {}
          vm.modal.showEditPosition = false
          vm.indexPosition('modal-position')
          vm.isLoader = false
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

    /**
     * Restore position which has been removed
     * 
     * @param  Int positionId
     * @param  Int index
     */
    restorePosition(positionId, index) {
      let vm = this
      axios
        .delete(`/api/positions-restore/${positionId}`)
        .then(res => {
          vm.modal.trashedPositions.splice(index, 1)
          vm.indexPosition()
          vm.$swal({
            icon: 'success',
            title: 'Successfully restored!',
            showConfirmButton: false,
            timer: 1500
          })
        })
        .catch(err => {
          console.log('error', err.response)
        })
    },

    /*
      | =======================================
      |  [ Tabs ]
      |  @Note - create component for this
      | =======================================
      */
    isActive(menuItem) {
      return this.activeItem === menuItem
    },
    setActive(menuItem) {
      this.indexPosition('modal-position')
      this.activeItem = menuItem
    },

    /**
     * Remove Position
     * 
     * @param  Ojb data
     * @param  Int index    
     */
    removePosition(data, index) {
      let vm = this
      if (confirm(`Are you sure you want to remove ${data.position}?`)) {
        axios
          .get(`/api/positions/${data.id}`)
          .then(() => {
            vm.modal.positions.splice(index, 1)
            vm.$swal({
              icon: 'success',
              title: 'Successfully removed!',
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

    /**
     * Save sorted position [draggable]
     */
    async saveSortedPosition() {
      let vm = this
      let arr = []

      vm.isLoader = true

      vm.modal.positions.forEach((val, index) => {
        arr.push({
          position_id: val.id,
          index: index
        })
      })

      try {
        await axios.patch('/api/position-sort', {data: arr})
        vm.$swal({
          icon: 'success',
          title: 'Sorted!',
          showConfirmButton: false,
          timer: 2000
        })
        vm.indexPosition('modal-position')
        vm.indexPosition('index-position')
        vm.isLoader = false
      } catch(e) {
        vm.isLoader = false
      }
    },

    /**
     * Sort position alphabetically
     */
    sortAlphabetically() {
      let vm = this
      let sortPosition = this.modal.positions.map(val => {
        return { id: val.id, index: val.index, position: val.position }
      })
      // Conditions
      //  - If return -1, it will place the first item before the second. 
      //  - If return 1, it will place the second item before the first.
      //  - If return 0, it will leave them unchanged.
      sortPosition.sort(function(a,b) {
        if ( a.position > b.position ) return 1
        if ( a.position < b.position ) return -1
      })
      vm.modal.positions = sortPosition
    },

    /**
     * Remove this
     */
    testMsg(msg) {
      this.$swal({
        icon: 'error',
        title: msg,
        showConfirmButton: false,
        timer: 1500
      })
    },
	}
}

</script>

<style lang="scss" scoped>
	@import '../../../sass/employees';
  table td:first-child {
    background: none !important;
}
.custom-main {
  margin-left: 250px;
}
.tooltip {
  position: relative;
  /* // display: inline-block;
  // border-bottom: 1px dotted black;  */
}

/* Tooltip text */
.tooltip .tooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  padding: 5px 0;
  border-radius: 6px;
 
  /* Position the tooltip text - see examples below! */
  position: absolute;
  z-index: 1;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip:hover .tooltiptext {
  visibility: visible;
}
.vue__time-picker input.display-time{
  width: 100% !important;
}
.bg-custom-primary {
  background-color: #4a90e2;
}
.bg-custom-primary-dark {
  background-color: #3a78c2;
}
.focus\:ring-custom-primary {
  --tw-ring-color: #4a90e2;
}
</style>