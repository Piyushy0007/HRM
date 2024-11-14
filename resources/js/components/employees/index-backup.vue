<template>
  <div class="c-employee-index">

    <Loader msg="Processing ..." v-model="isLoader" />

    <ul class="flex c-secondary-nav">
      <li class="w-1/3 text-center"><a href="#" v-b-modal.addNewEmployee >Add New Employee</a></li>
      <li class="w-1/3 text-center"><a href="#" v-b-modal.addEditPositions >Add/Edit Positions</a></li>
      <li class="w-1/3 text-center"><a href="#" v-b-modal.SignIn>Email Sign in Instructions</a></li>
    </ul>

    <div class="selection-function px-4 py-3 flex justify-between">
      <div class="flex items-start">
        <div class="flex items-center text-sm mr-10">
          <font-awesome-icon icon="columns" class="mr-1" />
          Select columns
        </div>

        <div class="options py-1 pb-2 px-6 rounded-md">
          <label class="font-bold text-sm">Selected Employees:</label>
          <ul class="flex">
            <li class="mr-4">
              <b-link v-b-modal.SendReminders class="flex items-center text-sm" title="Send scheduled reminder to those checked who are also working in a date range">
                <font-awesome-icon :icon="['far', 'list-alt']" class="mr-1" />
                Send reminder
             </b-link>
             
            </li>
            <li class="mr-4">
              <!-- <a href="#" @click.prevent="SendMessage" class="flex items-center text-sm">
                <font-awesome-icon :icon="['far', 'envelope']" class="mr-1" />
                Message!
              </a> -->
                <b-link v-b-modal.SendMessage class="flex items-center text-sm">
                  <font-awesome-icon :icon="['far', 'envelope']" class="mr-1" />
                  Message
                </b-link>
            </li>
            <li class="mr-4">
              <a href="#" class="flex items-center text-sm">
                <font-awesome-icon :icon="['far', 'list-alt']" class="mr-1" />
                Send Sign In
              </a>
            </li>
            <li class="mr-4">
              <a href="#" class="flex items-center text-sm">
                <font-awesome-icon icon="external-link-square-alt" class="mr-1" />
                Export to Clipboard
              </a>
            </li>
            <li>
              <a href="#" class="flex items-center text-sm">
                <font-awesome-icon icon="pencil-alt" class="-mr-1" />
                <font-awesome-icon icon="pencil-alt" class="mr-1" />
                Bulk Edit
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div class="text-right">
        <span class="block mb-1">Total Employees: {{ countTotalEmployees }}*</span>
        <div class="flex flex-wrap -mx-3">
          <div class="w-full md:w-1/2 px-3">
            <div class="relative">
              <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" placeholder="Find" v-model="searchKeyword" @keyup="search">
              <div class="absolute inset-y-0 right-0 flex items-center px-2 text-custom-border rounded-r border border-custom-border border-l-0">
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

    <div class="px-4">
      <table class="w-full">
        <thead>
          <tr>
            <th>
              <input type="checkbox">
            </th>
            <th>View</th>
            <th>Edit</th>
            <th class="text-left">First</th>
            <th class="text-left">Last</th>
            <th class="text-left">Phone</th>
            <th class="text-left">Email*</th>
            <th class="leading-tight py-2">Max Wkly<span class="block">Hours</span></th>
            <th class="leading-tight">Max Wkly<span class="block">Days</span></th>
            <th class="leading-tight">Max Day<span class="block">Hours</span></th>
            <th class="leading-tight">Max Day<span class="block">Shifts</span></th>
            <th>Hire Date</th>
            <th class="leading-tight">Priority<span class="block">Group</span></th>
          </tr>
        </thead>
        <tbody v-if="index.employees.length===0">
          <tr><td colspan="13" class="px-2">No record found!</td></tr>
        </tbody>
        <tbody v-else>
          <tr v-for="(data, index) in index.employees" :key="data.id">
            <td class="text-center"><input type="checkbox"></td>
            <td class="text-center">
              <a href="#"><font-awesome-icon icon="search" /></a>
            </td>
            <td class="text-center">
              <a href="#" @click.prevent="openModal('EditEmployee', data, index)"><font-awesome-icon icon="pencil-alt" /></a>
            </td>
            <td>{{ data.employee.firstname }}</td>
            <td>{{ data.employee.lastname }}</td>
            <td>{{ data.employee.mobile }}</td>
            <td>{{ data.employee.email || '-----' }}</td>
            <td class="text-center">{{ data.employee.max_weekly_hours }}</td>
            <td class="text-center">{{ data.employee.max_weekly_days }}</td>
            <td class="text-center">{{ data.employee.max_day_hours }}</td>
            <td class="text-center">{{ data.employee.max_day_shifts }}</td>
            <td class="text-center">{{ data.employee.hired_date }}</td>
            <td class="text-center">{{ data.employee.priority_group }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="information mx-4 mt-5 p-4 rounded-lg">
      <div class="flex justify-between items-center mb-2">
        <h4 class="text-2xl font-semibold">Information</h4>
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
        <li class="mb-1 flex items-center">
          <span class="inline-block w-20 mr-1">
            <img src="/images/icon-google.png" class="h-5 mx-auto" alt="google">
          </span>
          Exporting to Google Calendar
        </li>
        <li class="flex items-center">
          <span class="inline-block w-20 mr-1">
            <img src="/images/icon-googlebroken.png" class="h-5 mx-auto" alt="broken google">
          </span>
          Google Calendar export NOT updating
        </li>
      </ul>
      
      <p class="mb-1">* Your subscription is included under a group of linked accounts for billing purposes. Total shown on this page only shows the employees in your one linked account.</p>

      <ul class="list-inside">
        <li class="mb-1">To delete Employee click the pencil icon next to their name and then click "Delete" (Employees cannot be bulk deleted.)</li>
        <li class="mb-1">To give an Employee manager-level access please go to <strong>Settings>Add/Edit Managers</strong> page. Employee will then have two logins, one to access as manager and one as employee.</li>
        <li>Trouble with grid displaying?</li>
      </ul>
    </div>


		<!-- ================================================ modal ================================================ -->
    
    <!-- Send Message start -->

      <b-modal id="SendMessage" hide-footer class="modal-add-new-employee" size="md:w-7/12" title="Add New Employee">
        <ValidationObserver v-slot="{ handleSubmit }">
          <form @submit.prevent="handleSubmit(storeEmployee)" ref="frmAddEmployee" novalidate>

            <!-- ================================= Comments ================================= -->
            <div class="comments px-6 pb-4 mb-4 flex">
              <h4 class="text-xl font-semibold w-3/12">Comments</h4>
              <div class="w-9/12">
                <label class="text-sm">Your Settings currently DO NOT ALLOW Employee to see this comment</label>
                <textarea rows="5" class="w-full rounded p-2 leading-tight focus:outline-none border border-custom-border" v-model="modal.addEmployee.comments"></textarea>
              </div>
            </div>
            <!-- ================================= ./Comments ================================= -->

          </form>
        </ValidationObserver>
      </b-modal>
      <!-- Send Message end -->
    <!-- Send Reminder start -->
      <b-modal id="SendReminders" hide-footer class="modal-add-edit-positions" size="md:w-4/12" title="Send Schedule Reminders">
		    <div align="center">
        <table class="module">
        <tbody><tr><td class="wgt" width="20%" style="padding-bottom:10px;" valign="top">
        <b class="titleBox text-xl font-semibold">Include</b></td>
        <td class="wgt" width="80%" style="padding-bottom:10px;" valign="top"><b class="titleBox text-xl font-semibold"><span id="CheckedDescription">- Employee</span></b></td></tr>
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
    		              <select class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none">
    		                <option value="10/06/2020|10/06/2020" selected="">Choose a Date Range</option>
                        <option value="10/06/2020|10/06/2020">Today</option>
                        <option value="10/07/2020|10/07/2020">Tomorrow</option>
                        <option value="10/05/2020|10/11/2020">This Week</option>
                        <option value="10/12/2020|10/18/2020">Next Week</option>
                        <option value="10/06/2020|10/31/2020">This Month</option>
                        <option value="11/01/2020|11/30/2020">Next Month</option>
                        <option value="10/06/2020|12/31/2020">This Quarter</option>
                        <option value="10/06/2020|12/31/2020">This Year</option>
                        <option value="10/06/2020|01/01/2030">After Today</option>
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
                      <date-picker valueType="format" v-model="modal.addEmployee.begin_date"
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
    					<!-- ====== ./Begin ====== -->
              <!-- ====== End ====== -->
    					<div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-11/12 mx-auto my-2">
    						<div class="w-12/12 flex">
    							<span class="block w-4/12 mt-1 text-right">End</span>
    							<div class="w-8/12 ml-2">
                    <ValidationProvider rules="required" v-slot="v">
                      <date-picker valueType="format" v-model="modal.addEmployee.end_date"
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
    					<!-- ====== ./End ====== -->
              <!-- ====== Subject ====== -->
    					<div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-11/12 mx-auto my-2">
    						<div class="w-12/12 flex">
    							<span class="block w-4/12 mt-1 text-right">Subject</span>
    							<div class="w-8/12 ml-2">
                    <ValidationProvider rules="required" v-slot="v">
                      <input name="Subject" style="width:95%" value="W2W Schedule" type="text" class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border border-custom-border">
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
                      <textarea rows="3" name="Comments" style="width:100%;" class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border border-custom-border"></textarea>
                      <small class="text-red-600 block">{{ v.errors[0] }}</small>
                    </ValidationProvider>
    							</div>
    						</div>
    					</div>
    					<!-- ====== ./Comment ====== -->
          </td></tr>
        

        </tbody></table></td></tr></tbody></table>


        <input type="button" value="Send Reminders" @click="DoRemind" class="send_ins">
        <table class="module">
        <tbody><tr><td class="wgt" width="20%">
        <b class="titleBox text-xl font-semibold">Information</b></td> <td class="infomore text-right pr-6">
           <a href="https://www.google.com" class="text-sm inline-flex items-center">
          Help on this topic &nbsp;<font-awesome-icon icon="arrow-circle-right" />&nbsp;<strong>More</strong>
        </a>
          </td>
        </tr>
        <tr><td class="bwgt px-6" style="padding-bottom:10px;" colspan="2">- Included employees only sent a reminder if they are scheduled during chosen date range AND have email notification set to send published schedule information.<br>
        <br>- Reminder emails include the comment you enter and the employees
        assigned shifts for that date range.<br><br>
        - Sending these reminders will NOT affect the Schedules section status icons for "emailed" or "confirmed."</td></tr>
        </tbody></table>
        </div>
       

        
      </b-modal>
        <!-- Send Reminder end -->


        <!-- Sign in ins -->
        <b-modal id="SignIn" centered  hide-footer class="modal-add-edit-positions" size="md:w-7/12" title="Send Employees Sign In Instructions">
		    
        <tbody>
          <tr><td class="bwgt" align="center" colspan="2" style="padding-bottom:20px;">
          Click the button below to send employees that haven't logged in yet their sign in instructions via email.
          </td></tr>
        </tbody>
		   
        <div class="text-center">
        <vue-confirm-dialog></vue-confirm-dialog>
        <input class="send_ins" type="button" value="Send Instructions" @click="showMsgBoxOne" title="Click to open a window and choose to send out sign in instructions to all employees who have e-mails but have not received them">
         </div>

        <tbody>
          <tr class="flex items-center justify-between">
            <td class="wgt" colspan="2">
              <b class="titleBox text-xl font-semibold">Information</b>
            </td>
            <td class="infomore">
              <a href="https://www.google.com" class="text-sm inline-flex items-center">
          Help on this topic &nbsp;<font-awesome-icon icon="arrow-circle-right" />&nbsp;<strong>More</strong>
        </a></td>
          </tr>
          <tr><td class="bwgt" colspan="2" style="padding-bottom:20px;">
         - Use this feature to send sign in instructions via email to employees who have not yet signed in for the first time.
          </td></tr>
          <tr><td class="bwgt" colspan="2" style="padding-bottom:20px;">
       - To send instructions to just one particular employee, please use their <b>Employee Details</b> page. 
          </td></tr>
          <tr><td class="bwgt" colspan="2" style="padding-bottom:20px;">
         - Note you also can PRINT employee sign in instructions from their <b>Edit Employee</b> page.
          </td></tr>
        </tbody>

        
        </b-modal>

    		<b-modal id="SignInSent" centered  class="modal-add-edit-positions" size="md:w-4/12" title="Sent">
		    
        <tbody>
          <tr class="flex items-center justify-between mb-5 mt-5">
            <td class="wgt" colspan="2">
              <b class="titleBox text-xl font-semibold">Login information has been emailed</b>
              	<ul><li class="text-sm" v-for="data in modal.positions" :key="data.id">{{ data.position }}</li></ul>
            </td>
          </tr>
        </tbody>
        <template v-slot:modal-footer="{ ok }">
         <b-button size="sm" variant="success" @click="ok()">
          OK
        </b-button>
        </template>
        </b-modal>
        <!-- Sign in ins end -->

		<b-modal id="addNewEmployee" centered hide-footer class="modal-add-new-employee" size="md:w-7/12" title="Add New Employee">
      <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(storeEmployee)" ref="frmAddEmployee" novalidate>
    			<!-- ================================= Full Name ================================= -->
    			<div class="fullname flex justify-center px-6">
            <div class="w-1/2">
              <ValidationProvider rules="required|alpha_spaces" v-slot="v">
        				<div class="md:flex md:items-center">
        					<div class="md:w-1/3">
        						<label class="block md:text-right mb-1 md:mb-0 pr-2">First Name</label>
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
        						<label class="block md:text-right mb-1 md:mb-0 pr-2">Last Name</label>
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
    			</div>
    			<!-- ================================= ./Full Name ================================= -->


    			<!-- ================================= Positions ================================= -->
    		  <div class="positions py-1">
    		  	<div class="flex justify-between items-center px-6">
    		  		<div class="flex items-center leading-none">
    		  			<h4 class="text-xl font-semibold mr-4">Positions</h4>
    		  			<a href="#" class="text-sm add-new-position" v-b-modal.addEditPositions><strong>&plus;</strong> Add New</a>
    		  		</div>
    		  		<ul class="flex grp-select-unselect">
    		  			<li class="pr-2 mr-2" @click="checkAllPositions(true)">
    		  				<label class="flex items-center" for="checkboxSelectAll">
                    <!-- <font-awesome-icon :icon="['far', 'check-square']" class="mr-1" style="color: #3B86FF" /> -->
                    <input type="checkbox" class="mr-1" v-model="modal.checkBoxOption.selectAll">
    		            <span class="text-sm" id="checkboxSelectAll">Select all</span>
    		          </label>
    		  			</li>
    		  			<li @click="checkAllPositions(false)">
    		  				<label class="flex items-center" for="checkboxUnSelectAll">
                    <!-- <font-awesome-icon :icon="['far', 'square']" class="mr-1" /> -->
                    <input type="checkbox" class="mr-1" v-model="modal.checkBoxOption.unSelectAll">
    		            <span class="text-sm" id="checkboxUnSelectAll">Clear all</span>
    		          </label>
    		  			</li>
    		  		</ul>
    		  	</div>

            <ul class="flex flex-col flex-wrap ml-10 mr-6" v-if="modal.positions.length===0">
              <li>No record found!</li>
            </ul>
    				<ul class="flex flex-col flex-wrap ml-10 mr-6" v-else>
    					<li v-for="data in modal.positions" :key="data.id">
    						<label class="inline-flex items-center">
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


            <ValidationProvider rules="email" v-slot="v">
      				<div class="md:flex md:items-center">
      					<div class="md:w-1/4">
      						<label class="block md:text-right mb-1 md:mb-0 pr-4">Email</label>
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


            <ValidationProvider rules="required" v-slot="v">
      				<div class="md:flex md:items-center">
      					<div class="md:w-1/4">
      						<label class="block md:text-right mb-1 md:mb-0 pr-4">Phone</label>
      					</div>
      					<div class="md:w-3/4">
      						<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model.number="modal.addEmployee.phone" @keypress="isNumberOnly($event)" maxlength="15">
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-1/4"></div>
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>


            <ValidationProvider rules="required" v-slot="v">
      				<div class="md:flex md:items-center">
      					<div class="md:w-1/4">
      						<label class="block md:text-right mb-1 md:mb-0 pr-4">2nd Phone</label>
      					</div>
      					<div class="md:w-3/4">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model.number="modal.addEmployee.phone2" @keypress="isNumberOnly($event)" maxlength="15">
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-1/4"></div>
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>


            <ValidationProvider rules="required" v-slot="v">
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
            </ValidationProvider>


    				<div class="md:flex md:items-center mb-1">
    					<div class="md:w-1/4"></div>
    					<div class="md:w-3/4">
    						<p class="text-xs note">(for reference only - entering cell here does NOT enable any automated text notifications which are set up in the notifications section)</p>
    					</div>
    				</div>

            <ValidationProvider rules="required" v-slot="v">
      				<div class="md:flex md:items-center">
      					<div class="md:w-1/4">
      						<label class="block md:text-right mb-1 md:mb-0 pr-4">Employee #</label>
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
            </ValidationProvider>


            <ValidationProvider rules="required" v-slot="v">
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
            </ValidationProvider>


    				<div class="md:flex md:items-center mb-1">
    					<div class="md:w-1/4">
    						<label class="block md:text-right mb-1 md:mb-0 pr-4">Address 2</label>
    					</div>
    					<div class="md:w-3/4">
    						<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.addEmployee.address2">
    					</div>
    				</div>


    				<div class="md:flex">
    					<div class="md:w-3/12">
    						<label class="block md:text-right mb-1 mt-1 md:mb-0 pr-4">City, State, Zip</label>
    					</div>
    					<div class="md:w-5/12">
                <ValidationProvider rules="required" v-slot="v">
    						  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.addEmployee.city">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
    					</div>
    					<div class="md:w-2/12 mx-1">
                <ValidationProvider rules="required" v-slot="v">
    						  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.addEmployee.state">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
    					</div>
    					<div class="md:w-2/12">
                <ValidationProvider rules="required" v-slot="v">
    						  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.addEmployee.zip">
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
    					<div class="c-bg-card flex px-5 py-3 rounded-lg w-6/6 mx-auto">
    						<h6 class="w-1/4 font-bold">Maximums</h6>
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
    					</div>
    					<!-- ====== ./Maximums ====== -->

    					<!-- ====== Seniority ====== -->
    					<div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-6/6 mx-auto my-2">

    						<h6 class="w-1/4 font-bold">By Seniority</h6>
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
    					</div>
    					<!-- ====== ./Seniority ====== -->

    					<!-- ====== Priority Group ====== -->
    					<div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-6/6 mx-auto ">
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
    					</div>
    					<!-- ====== ./Priority Group ====== -->

    				</div>
    			</div>
    			<!-- ================================= ./Auto Fill Options ================================= -->


    			<!-- ================================= Pay Rate ================================= -->
    			<div class="pay-rate px-6 pb-4 mb-4 flex">
    				<h4 class="text-xl font-semibold w-3/12">Pay Rate**</h4>
    				<div class="w-4/12 mr-2">
              <ValidationProvider rules="required" v-slot="v">
                <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-right" type="text" maxlength="10" v-model="modal.addEmployee.pay_rate" @keypress="isNumberOnlyAndDecimalPoint($event)">
                <small class="text-red-600 block">{{ v.errors[0] }}</small>
              </ValidationProvider>
    				</div>
    				<span class="mt-1">per hour default</span>
    			</div>
    			<!-- ================================= ./Pay Rate ================================= -->


    			<!-- ================================= Custom Fields ================================= -->
    			<div class="custom-fields px-6 pb-4 mb-4 flex">
    				<h4 class="text-xl font-semibold w-3/12">Custom Fields*</h4>
    				<div class="w-9/12">
    					<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none mb-1" type="text">
    					<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text">
    				</div>
    			</div>
    			<!-- ================================= ./Custom Fields ================================= -->


    			<!-- ================================= Comments ================================= -->
    			<div class="comments px-6 pb-4 mb-4 flex">
    				<h4 class="text-xl font-semibold w-3/12">Comments</h4>
    				<div class="w-9/12">
    					<label class="text-sm">Your Settings currently DO NOT ALLOW Employee to see this comment</label>
    					<textarea rows="5" class="w-full rounded p-2 leading-tight focus:outline-none border border-custom-border" v-model="modal.addEmployee.comments"></textarea>
    				</div>
    			</div>
    			<!-- ================================= ./Comments ================================= -->


    			<!-- ================================= Options ================================= -->
    			<div class="options px-6 pb-4 flex items-center">
    				<h4 class="text-xl font-semibold w-3/12">Options</h4>
    				<div class="w-9/12">
    			  	<label class="flex items-center">
    		        <input class="mr-1 leading-tight font-semibold" type="checkbox" v-model="modal.addEmployee.enable_screen_reader">
    		        <span class="text-sm">This employee uses a screen reader and prefers high contrast display</span>
    		      </label>
    				</div>
    			</div>
    			<!-- ================================= ./Options ================================= -->
    			
    			<div class="text-center mt-10 mb-8">
    				<button class="text-white py-3 px-12 rounded-full mb-5 bg-custom-primary" type="submit">Add Employee</button>
    		  	<label class="flex items-center justify-center">
    	        <input class="mr-1 leading-tight font-semibold" type="checkbox" checked>
    	        <span class="font-semibold">Email sign in instructions now</span>
    	      </label>
          </div>
        </form>
      </ValidationObserver>

			<div class="information px-6">
				<h4 class="text-xl mb-2">Information</h4>
				<ul class="list-inside">
					<li class="text-sm">* The account main manager can define two custom fields labels.</li>
					<li class="text-sm">** Optional default pay rate can be set here. Pay rates per position can be set later.</li>
				</ul>
			</div>
		</b-modal>


    <b-modal id="editEmployee" centered hide-footer class="modal-edit-employee" size="md:w-7/12" title="EDIT Employee">
      <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(updateEmployee)" novalidate>
          <div class="w-3/5 mx-auto mt-8 mb-4">
            <div class="flex justify-around items-center">
              <div>
                <a href="#" class="text-2xl text-custom-primary" @click.prevent="getPrevNextEmployeeDetail('prev')" v-if="modal.getEmployeeRecord.prev.show">
                  &#9664;
                </a>
              </div>
              <div>
                <span class="text-xl font-semibold">EDIT {{ modal.fullname }}</span>
              </div>
              <div>
                <a href="#" class="text-2xl text-custom-primary" @click.prevent="getPrevNextEmployeeDetail('next')" v-if="modal.getEmployeeRecord.next.show">
                  &#9654;
                </a>
              </div>
            </div>
          </div>

          <!-- ================================= Full Name ================================= -->
          <div class="fullname flex py-5 justify-center px-6">
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
          <!-- ================================= ./Full Name ================================= -->

          <!-- ================================= Sign in ================================= -->
          <div class="sign-in py-5 px-6 mb-4">
            <div class="flex justify-between items-center mb-4">
              <h4 class="text-xl font-semibold mr-4">Sign In</h4>
            </div>

            <div class="flex justify-between">
              <div class="w-1/2">
                <div class="md:flex md:items-center mb-1">
                  <div class="w-2/6">
                    <label class="block mb-1 md:mb-0 pr-4">Username:</label>
                  </div>
                  <div class="w-3/6 text-left">
                    WWJ854221058
                    <!--  -->
                  </div>
                </div>
                <div class="md:flex md:items-center mb-1">
                  <div class="md:w-2/6">
                    <label class="block mb-1 md:mb-0 pr-4">Last Sign In:</label>
                  </div>
                  <div class="md:w-3/6">
                    <!--  -->
                  </div>
                </div>
              </div>
              <div class="w-1/2">
                <ul class="flex justify-around options">
                  <li>
                    <a href="#" class="text-custom-primary" @click.prevent="testMsg('This function is still on progress')">
                      <font-awesome-icon icon="pencil-alt" class="mr-1" />
                      Change
                    </a>
                  </li>
                  <li>
                    <a href="#" class="text-custom-primary flex items-center" @click.prevent="testMsg('This function is still on progress')">
                      <img src="/images/icon-email.svg" class="h-4 mr-1" alt="email">
                      Email
                    </a>
                  </li>
                  <li>
                    <a href="#" class="text-custom-primary" @click.prevent="testMsg('This function is still on progress')">
                      <font-awesome-icon icon="print" class="mr-1" />
                      Reset Pass &amp; Print
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <!-- ================================= ./Sign in ================================= -->

          <!-- ================================= Positions ================================= -->
          <div class="positions py-5 mb-4">
            <div class="flex justify-between items-center mb-4 px-6">
              <h4 class="text-xl font-semibold mr-4">Positions</h4>
              <a href="#" class="text-sm add-new-position" @click.prevent="openModal('AddNewPosition')"><strong>&plus;</strong> Add New</a>
            </div>

            <ul class="flex flex-col flex-wrap ml-10 mr-6" v-if="modal.positions.length===0">
              <li>No record found!</li>
            </ul>
            <ul class="flex flex-col flex-wrap ml-10 mr-6" v-else>
              <li v-for="data in modal.positions" :key="data.id">
                <label class="inline-flex items-center">
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
                  <a href="#" class="text-sm text-custom-primary"><strong>&plus;</strong> Add Email</a>
                </div>
              </div>
            </div>
            <div class="md:flex md:items-center mb-1">
              <div class="md:w-1/4">
                <label class="block md:text-right mb-1 md:mb-0 pr-4 flex items-center justify-end">
                  <font-awesome-icon icon="lock" class="mr-1" size="xs" />Phone
                </label>
              </div>
              <div class="md:w-3/4">
                <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.reqEditEmployee.phone" readonly>
              </div>
            </div>


            <ValidationProvider rules="required" v-slot="v">
              <div class="md:flex md:items-center">
                <div class="md:w-1/4">
                  <label class="block md:text-right mb-1 md:mb-0 pr-4">2nd Phone</label>
                </div>
                <div class="md:w-3/4">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model.number="modal.reqEditEmployee.phone2" @keypress="isNumberOnly($event)" maxlength="15">
                </div>
              </div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-1/4"></div>
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>


            <ValidationProvider rules="required" v-slot="v">
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
            </ValidationProvider>


            <ValidationProvider rules="required" v-slot="v">
              <div class="md:flex md:items-center">
                <div class="md:w-1/4">
                  <label class="block md:text-right mb-1 md:mb-0 pr-4">Employee #</label>
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
            </ValidationProvider>


            <ValidationProvider rules="required" v-slot="v">
              <div class="md:flex md:items-center">
                <div class="md:w-1/4">
                  <label class="block md:text-right mb-1 md:mb-0 pr-4">Address</label>
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


            <div class="md:flex md:items-center mb-1">
              <div class="md:w-1/4">
                <label class="block md:text-right mb-1 md:mb-0 pr-4">Address 2</label>
              </div>
              <div class="md:w-3/4">
                <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.reqEditEmployee.address2">
              </div>
            </div>


            <div class="flex">
              <div class="md:w-3/12">
                <label class="block md:text-right mb-1 mt-1 md:mb-0 pr-4">City, State, Zip</label>
              </div>
              <div class="md:w-5/12">
                <ValidationProvider rules="required" v-slot="v">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.reqEditEmployee.city">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
              </div>
              <div class="md:w-2/12 mx-1">
                <ValidationProvider rules="required" v-slot="v">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.reqEditEmployee.state">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
              </div>
              <div class="md:w-2/12">
                <ValidationProvider rules="required" v-slot="v">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.reqEditEmployee.zip">
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
          <div class="pay-rate px-6 pb-4 mb-4 flex mt-1">
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
          <div class="custom-fields px-6 pb-4 mb-4 flex">
            <h4 class="text-xl font-semibold w-3/12">Custom Fields*</h4>
            <div class="w-9/12">
              <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none mb-1" type="text">
              <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text">
            </div>
          </div>
          <!-- ================================= ./Custom Fields ================================= -->


          <!-- ================================= Comments ================================= -->
          <div class="comments px-6 pb-4 mb-4 flex">
            <h4 class="text-xl font-semibold w-3/12">Comments</h4>
            <div class="w-9/12">
              <label class="text-sm">Your Settings currently DO NOT ALLOW Employee to see this comment</label>
              <textarea rows="5" class="w-full rounded p-2 leading-tight focus:outline-none"></textarea>
            </div>
          </div>
          <!-- ================================= ./Comments ================================= -->


          <!-- ================================= Preferences ================================= -->
          <div class="px-6 pb-4 flex items-center">
            <h4 class="text-xl font-semibold w-3/12">Preferences</h4>
            <div class="w-9/12">
              <ul class="flex items-center">
                <li>
                  <a href="#" class="text-custom-primary" @click.prevent="testMsg('This function is still on progress')">
                    <font-awesome-icon icon="pencil-alt" class="mr-1" />
                    Work Time Prefs
                  </a>
                </li>
                <li class="ml-5">
                  <a href="#" class="text-custom-primary" @click.prevent="testMsg('This function is still on progress')">
                    <font-awesome-icon icon="pencil-alt" class="mr-1" />
                    Position Prefs
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <!-- ================================= ./Preferences ================================= -->


          <!-- ================================= Time Off ================================= -->
          <div class="px-6 pb-4 flex items-center">
            <h4 class="text-xl font-semibold w-3/12">Time Off</h4>
            <div class="w-9/12">
              <ul>
                <li>
                  <a href="#" class="text-custom-primary" @click.prevent="testMsg('This function is still on progress')">
                    <font-awesome-icon icon="pencil-alt" class="mr-1" />
                    View / Edit
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <!-- ================================= ./Time Off ================================= -->

          
          <div class="flex justify-between mt-10 mb-8">
            <button class="text-white py-3 px-12 rounded-full bg-gray-400 text-red-600" type="button" @click.prevent="removeEmployee">DELETE Employee</button>
            <div>
              <button class="text-white py-3 px-12 rounded-full bg-custom-primary" ref="editEmployeeSave" type="submit">Save</button>
              <button class="text-white py-3 px-12 rounded-full ml-2 bg-custom-primary" ref="editEmployeeSaveNext" type="button" @click.prevent="updateAndProceedNext">Save &amp; Next</button>
            </div>
          </div>
        </form>
      </ValidationObserver>

      <div class="information">
        <h4 class="text-xl mb-2">Information</h4>
        <p class="text-sm mb-1">* The account main manager can add/edit custom field labels.</p>
        <ul class="ml-2">
          <li class="text-sm mb-1">
            Emails marked "None" mean that employee has not set up any notifications. Information will not be shown to other employees. For privacy reasons, employee must sign in and allow this on their INFO page.
          </li>
          <li class="text-sm">
            To make employee also a manager go to Settings>Add/Edit Managers page. The employee will then have two usernames, one to access as employee and one as manager.
          </li>
        </ul>
      </div>
    </b-modal>

		<b-modal id="addEditPositions" centered hide-footer class="modal-add-edit-positions" size="md:w-7/12" title="Add/Edit Positions">
			<div class="new-position px-6 py-6">
        <ValidationObserver v-slot="{ handleSubmit }">
          <form @submit.prevent="handleSubmit(addPosition)">
    				<div class="flex items-center justify-between mb-5">
    					<h4 class="text-xl font-semibold">New Position</h4>
    					<button class="text-white py-2 px-16 rounded-full text-sm bg-custom-primary" type="submit">Add</button>
    				</div>
            <ValidationProvider rules="required" v-slot="v">
      				<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none mb-1" type="text" v-model="modal.addPosition.position">
              <small class="text-red-600 block">{{ v.errors[0] }}</small>
      				<p class="text-sm">After adding, set which employees can work each position on the <strong>Employees>Positions Grid</strong></p>
            </ValidationProvider>
          </form>
        </ValidationObserver>
			</div>

		  <div class="list-of-positions py-5 mb-4">
		  	<div class="flex justify-between items-center mb-5 px-6">
		  		<div class="flex items-center leading-none">
		  			<h4 class="text-xl font-semibold">Positions</h4>
		  		</div>
		  		<button class="text-white py-2 px-16 rounded-full text-sm bg-custom-primary" type="button" @click.prevent="openModal('EditDeletePositions')">Edit/Delete</button>
		  	</div>

        <ul class="flex flex-col flex-wrap ml-10 mr-6 mb-4" v-if="modal.positions.length===0">
          <li class="text-sm">No record found!</li>
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
          <li v-if="modal.trashedPositions.length===0" class="text-sm">No record found!</li>
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
		</b-modal>

    <b-modal id="editDeletePositions" centered  hide-footer class="modal-edit-delete-positions" size="md:w-6/12" :title="`Edit/Delete Positions`">
      <nav class="my-4 edit-delete-positions">
        <ul class="flex justify-center w-5/12 mx-auto">
          <li class="w-2/4 mr-1 rounded-t-lg" :class="{ active: isActive('edit') }">
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
          <button class="text-white py-2 px-12 rounded-full text-sm bg-custom-primary" type="button" @click.prevent="saveSortedPosition">Save</button>
        </div>
<!-- 
        <draggable v-model="modal.positions" tag="ul" class="w-9/12 mx-auto my-10" handle=".handle"
          @start="isDragging=true" @end="isDragging=false">
          <transition-group type="transition" name="flip-list">
            <li v-for="(data) in modal.positions"  :key="data.id" class="flex items-center justify-between border border-custom-primary mb-2 px-3 py-1 rounded-md">
              <a href="#" @click.prevent="openModal('EditPosition', data)">{{ data.position }}</a>
              <a href="#"><font-awesome-icon icon="bars" class="text-custom-primary handle" /></a>
            </li>
          </transition-group>
        </draggable> -->

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
    </b-modal>

    <b-modal id="showEditPosition" centered hide-footer class="modal-edit-delete-positions text-center" size="md:w-4/12" title="Edit this position">
      <p class="w-2/3 mx-auto my-3">This edited position name will display for all existing {{ modal.reqEditPositionDisplay }} shifts in all schedules including PAST schedules.</p>
      <form @submit.prevent="editPosition">
        <input class="appearance-none block w-full rounded py-1 px-4 mb-4 leading-tight focus:outline-none" type="text" v-model="modal.reqEditPosition.position" required>
        <div>
          <button class="text-white text-center py-2 rounded-full bg-custom-primary w-32" type="submit">Ok</button>
          <button class="text-white text-center py-2 rounded-full bg-red-700 w-32 ml-2" type="button" @click.prevent="modal.showEditPosition = !modal.showEditPosition">Cancel</button>
        </div>
      </form>
    </b-modal>

  </div>
</template>

<script>

import Modal from '../shared/Modal'
import Loader from '../shared/Loader'
import axios from 'axios'
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import draggable from 'vuedraggable'

export default {
	components: {
		Modal,
    Loader,
    DatePicker,
    draggable,
	},
	data() {
		return {
			requestedHeaders: {
				headers: {}
			},
      isLoader: false,

      searchKeyword: '',
      searchTimer: null,

      // tabs
      activeItem: 'edit',

			modal: {
				addNewEmployee: false,
        editEmployee: false,
        addEditPositions: false,
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

        addEmployee: {},
        reqEditEmployee: {},

        positions: {},
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

      index: {
        employees: {},
        positions: {},
        selectPosition: '',
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
  },
  async created() {
    let vm = this
    await vm.indexEmployee()
    await vm.indexPosition('index-position')
  },
	methods: {
    DoRemind(){
       let vm = this
      vm.modal.SendReminders = false;
    },
      showMsgBoxOne() {
        
          let vm = this;
          vm.$confirm(
        {
          message: `Send individual sign in instructions to all employees who have email addresses entered but who have not already signed in.`,
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
               this.$bvModal.hide('SignIn')
               this.$bvModal.show('SignInSent')
              
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
          .get(`/api/employee-search?kw=${vm.searchKeyword}&position_id=${vm.index.selectPosition}&has_email=both`)
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
		async openModal(modal, data, index) {
			let vm = this
			switch(modal) {
				case 'AddNewEmployee':
					vm.modal.addNewEmployee = true
          vm.modal.addEmployee = {}
          vm.$refs.frmAddEmployee.reset()
          vm.modal.addEmployee.priority_group = 1
          vm.indexPosition('modal-position')
					break
        case 'EditEmployee':
          vm.modal.editEmployee = true

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
            phone: data.employee.phone,
            phone2: data.employee.phone2,
            mobile: data.employee.mobile,
            employee_no: data.employee.employee_no,
            address: data.employee.address,
            address2: data.employee.address2,
            city: data.employee.city,
            state: data.employee.state,
            zip: data.employee.zip,

            max_weekly_hours: data.employee.max_weekly_hours,
            max_weekly_days: data.employee.max_weekly_days,
            max_day_hours: data.employee.max_day_hours,
            max_day_shifts: data.employee.max_day_shifts,

            pay_rate: data.employee.pay_rate,
            hired_date: data.employee.hired_date,
            priority_group: data.employee.priority_group,
            // position: fetchEmployeePositions.data,
          }



          vm.indexPosition('modal-position')
          vm.isLoader = false
          break
        // case 'AddNewPosition':
        //   // vm.modal.addNewEmployee = false
        //   vm.modal.addEditPositions = true
        //   break
				// case 'AddEditPositions':
				// 	vm.modal.addEditPositions = true
        //   vm.indexPosition('modal-position')
        //   vm.trashedPosition()
				// 	break
			
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

      vm.isLoader = true
      axios
        .get(`/api/employees?position_id=${position}&kw=${vm.searchKeyword}&has_email=both`)
        .then(res => {
          vm.index.employees = res.data
          vm.isLoader = false
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
          vm.index.positions = position.data
          break
        case 'modal-position':
          vm.modal.positions = position.data
          break
      }
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

      axios
        .post('/api/employees', Object.assign(vm.modal.addEmployee, { positions: vm.modal.selectedPositions }))
        .then(res => {
          vm.indexEmployee()
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
        // console.log('resEmployee', resEmployee.data)

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
        console.log('error', e)
        vm.isLoader = false
      }
    },

    /**
     * Update employee
     */
    async updateEmployee() {
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
        await axios.patch(`/api/employees/${vm.modal.reqEditEmployee.id}`, Object.assign(vm.modal.reqEditEmployee, { positions: vm.modal.selectedPositions }))
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
        console.log('Error', e)
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
        await axios.patch(`/api/employees/${vm.modal.reqEditEmployee.id}`, Object.assign(vm.modal.reqEditEmployee, { positions: vm.modal.selectedPositions }))
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
        console.log('Error', e)
        vm.isLoader = false
      }
      
    },

    /**
     * Remove employee
     */
    async removeEmployee() {
      let vm = this
      try {
        if (confirm('Are you sure you want to remove this user?')) {
          vm.isLoader = true
          await axios.delete(`/api/employees/${vm.modal.reqEditEmployee.id}`)
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
          vm.$swal({
            icon: 'success',
            title: 'Successfully added!',
            showConfirmButton: false,
            timer: 2000
          })
          vm.modal.addPosition.position = ''
          vm.modal.positions.splice(res.data.id, 0, res.data)
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
        .patch(`/api/positions/${vm.modal.reqEditPosition.id}`, {position: vm.modal.reqEditPosition.position})
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
          console.log(err.response.data)
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
          .delete(`/api/positions/${data.id}`)
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
        console.log(e)
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
</style>