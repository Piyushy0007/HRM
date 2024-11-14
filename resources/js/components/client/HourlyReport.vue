<template>
  <div id="nonPrintable" class="c-employee-index client-table client-add" style="border-left: 2px solid #f9f9f9;margin-top: -5px;">
    
    <Loader msg="Processing ..." v-model="isLoader" />
    <div class="px-4 pb-4 add-client hourly-report">
      <div class="flex header" style="justify-content: space-between;">
        <!-- <h1 class="mb-4" style="font-family: Poppins;font-size: 22px;">Report</h1> -->
        <div class="flex">
        <span class="date">{{ currentDate | moment('dddd, MMMM D, YYYY') }}</span>
         <b-button v-if="modal.date !== '' || dateRange.startDate !== null " class="aj ml-3 mt-2" @click="datetoreset()"> <b-icon-cloud-arrow-down   /> Reset To Default</b-button>
         <b-button class="aj ml-3 mt-2" @click="datetolisten1()">Choose Date</b-button>
         <div class=" px-3 mt-2">
          <div class="relative">
          <input class="officer-report-search-report appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" placeholder="Search" v-model="searchKeyword"  @keyup="search()">
          <div class="border-none absolute inset-y-0 right-0 flex items-center px-2 text-custom-border rounded-r border-custom-border border-l-0">
            <font-awesome-icon icon="search" class="fill-current" />
          </div>
          </div>
			  </div>
        </div>
        <!-- <button class="add-blue-button" @click="addclient()" style="font-family: Poppins;font-size: 22px;">Add Property</button> -->
          <div class="w-full md:w-3/12 mt-2 mb-2 flex right-section">
            <div class="relative"  style=" width:50%; display: none;">
              <select id="location" @change="selectLocation()"  class="height-41 block appearance-none w-full py-1 px-4 pr-8 rounded-10 leading-tight focus:outline-none">
               <option value="">All Locations</option><option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AS">American Samoa</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District of Columbia</option><option value="FM">Micronesia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="GU">Guam</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MH">Marshall Islands</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="MP">Northern Mariana Islands</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PW">Palau</option><option value="PA">Pennsylvania</option><option value="PR">Puerto Rico</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VI">Virgin Islands</option><option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option><option value="AA">Armed Forces Americas</option><option value="AE">Armed Forces Europe, Canada, Africa and Middle East</option><option value="AP">Armed Forces Pacific</option>
                
              </select>
             
            
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700" style="display: none;">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
              </div>
             
            </div>
           <!-- <a class=" aj action-button download" style="margin: auto 0 auto auto;"  @click="downloadReport()" > <b-icon-cloud-arrow-down   />Download Full Report(pdf)</a> -->
            
          </div>
      </div>
  
    </div>
    <div class=" report-container">
      <div class="reports" >
<template v-if="modal.properties != 0">
        <!-- <div v-for="report in index.report" :key="report.id" class=" flex" style="">
          <div v-for="reports in report" :key="reports.id" style="margin-right:20px;" >
            <div class=""  v-for="data , number in reports" :key="data.id">
              <div class="card" v-if="number == 0">
                <div class="card-body">
                  <div class="flex date-section">  
                    <h5 class="card-title" v-if="data.slot"> {{data.slot.split("-")[0] | moment('HH:mm A')}}</h5>
                    <h5 class="card-title" v-else> - </h5>
                    <input type="checkbox" :id="`checkbox-`+data.id" :name="`checkbox-`+data.id" :value="data.id">
                  </div>
                  <div class="upper-section flex">
                    <img class="card-img-top" src="https://placekitten.com/50/50" alt="Card image cap" />
                    <div class="info-section">
                      <p class="card-text name"> Name : {{data.emp[0].firstname}} {{data.emp[0].lastname}}</p>
                      <p class="card-text id-no"> ID NO : {{data.emp[0].emp_id}}</p>
                      <p></p>
                    </div>
                  </div>
                  <div class="lower-section">
                    <textarea class="desc" readonly :value="data.description"  v-for="data in reports" :key="data.id">
                    
                    </textarea>              
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div> -->
      <div class=" px-4 mb-8 mt-8" v-for="properties in modal.properties" :key="properties.id">
			<div v-if="properties.hourly.length!== 0 || properties.incident.length!== 0  ">
			<div class="upper_section px-3">
				<div class="section1 flex mb-1" style="justify-content:flex-start;">
					<span class="propertyname_head"  style="font-family: Poppins;font-style: normal;font-weight: bold;font-size: 22px;">{{properties.name}} #{{properties.property_id}}</span>
          <a  :href="'/api/hourlyreportdownloaddata/' + properties.client_id +'/'+ modal.date +'/1'" target="_blank" style="text-decoration:none;right:35px;position:absolute; "><b-button class="m-2 action-button view download-report"><b-icon-cloud-arrow-down   />Download Report(pdf)</b-button></a>
          <!-- <button class="add-blue-button download-blue-button"  @click="downloadReport()" > <b-icon-cloud-arrow-down   />Download Report(pdf)</button> -->
          <!-- <button class="action-button view "  @click="alert('in progress')" > <b-icon-cloud-arrow-down   />View</button> -->
        </div>

			</div>
      <div class="mt-3 mb-3">
      <!-- <div class="mt-3 mb-3" v-for="employee in properties.employee" :key="employee.employee_id"> -->
			<div class="upper_section px-3" >
          <!-- <d 
          iv class="mb-3 heading-log">{{employee.firstname}} {{employee.lastname}} #{{employee.employee_id}}</div>  -->
        <div class="heading-log-container flex mb-2" v-if="properties.hourly.length !== 0" style="justify-content: space-between;">
          <div class="mb-3 heading-log" style="font-weight:bold;">Daily Activity Logs</div> 
          <!-- <button class="add-blue-button download-blue-button"  @click="downloadReport()" > <b-icon-cloud-arrow-down   />Download Report(pdf)</button> -->
          <button class=" aj action-button view "  @click="ViewDailyReport()" > <b-icon-zoom-in   />View Report</button>
        </div>
			</div>
			<div class="lower_section mt-1 tabletopbackground" style="height:300px"  v-if="properties.hourly.length !== 0">

        <table class=" tabletopposition w-full client-hourly-report">
          <thead>
            <tr>
            <th style="width: 30%;" class=" nh5 text-left pl-3">Date/Time </th>

            <th style="width:30%;" class="text-left">Officer </th>
            <th style="width:36%;" class="text-left">Location  </th>
          

            <!-- <th style="width:25%;" class="text-left">Daily Activity Logs</th> -->
            <!-- <th style="width:75%;" class="text-left">Report <b-button style="float-right" class="m-2 action-button download download-single-property text-right" :download='true'  @click="downloadReport(data)"> <b-icon-cloud-arrow-down   />Download(pdf)</b-button></th> -->
            <!-- <th style="width:20%;" class="text-right"><b-button class="m-2 action-button download" :download='true'  @click="downloadReport(data)"> <b-icon-cloud-arrow-down   />Download(pdf)</b-button></th> -->
            </tr>
          </thead>
          <tbody >
            <tr v-for="reportdata in properties.hourly" :key="reportdata.id">
        
              <td class="text-left pl-3" style="width:30%;">{{reportdata.date}} {{reportdata.slot}}</td>

              <td class="text-left text-transform-capitalise" style="width:30%;">{{reportdata.employee.firstname}} {{reportdata.employee.lastname}} </td>
              <td class="text-left address" style="width:36%;">{{reportdata.location}}</td>
              
              <!-- <td class="text-left pr-5 pt-2 pb-2" >{{reportdata.description}}</td> -->
              <!-- <td class="text-right" style="width:1%;" ></td> -->
            </tr>
          </tbody>
        </table>
			</div>
      <div class="upper_section mt-3 px-3">
        <div class="heading-log-container flex mb-2" v-if="properties.incident.length !== 0" style="justify-content: space-between;">
          <div class="mb-3 heading-log" style="font-weight:bold;">Incident Reports</div> 
          <!-- <button class="add-blue-button download-blue-button"  @click="downloadReport()" > <b-icon-cloud-arrow-down   />Download Report(pdf)</button> -->
           <!-- <button class="action-button view "  @click="ViewIncidentReport()" > <b-icon-cloud-arrow-down   />View</button> -->
        </div>
        

			</div>
			<div class="lower_section mt-1 tabletopbackground" style="height:300px;" v-if="properties.incident.length!== 0">
        <table class="w-full client-hourly-report tabletopposition">
          <thead>
            <tr>
            <th style="width:18%;" class="text-left">Incident Type <img style="display:inline;height: 17px;margin: 12px;margin-top: 10px;justify-self: center; justify-content: center;margin-right:10px;" src="/images/arrow.png" alt="arrow"></th>
            <th style="width:15%;" class="text-left  pl-3">Date/Time <img style="display:inline;height: 17px;margin: 12px;margin-top: 10px;justify-self: center; justify-content: center;margin-right:10px;" src="/images/arrow.png" alt="arrow"></th>
            <th style="width:26%;" class="text-left">Officer <img style="display:inline;height: 17px;margin: 12px;margin-top: 10px;justify-self: center; justify-content: center;margin-right:10px;" src="/images/arrow.png" alt="arrow"></th>
            <th style="width:27%;" class="text-left">Location <img style="display:inline;height: 17px;margin: 12px;margin-top: 10px;justify-self: center; justify-content: center;margin-right:10px;" src="/images/arrow.png" alt="arrow"></th>
            <!-- <th style="width:50%;" class="text-left ">Incident Report</th> -->
            <!-- <th style="width:75%;" class="text-left">Report <b-button style="float-right" class="m-2 action-button download download-single-property text-right" :download='true'  @click="downloadReport(data)"> <b-icon-cloud-arrow-down   />Download(pdf)</b-button></th> -->
            <!-- <th style="width:20%;" class="text-right"><b-button class="m-2 action-button download" :download='true'  @click="downloadReport(data)"> <b-icon-cloud-arrow-down   />Download(pdf)</b-button></th> -->
            <th style="width:14%" class="text-center"></th>
            </tr>
          </thead>
          <tbody >
            <tr v-for="incidentdata in properties.incident" :key="incidentdata.id">
              <!-- <td class="text-center pl-5 pr-5">{{reportdata.date | moment('MM/DD/YYYY')}}</td> -->
              <td class="text-left" style="width:15%;">{{incidentdata.sub_report.report_type_name}}</td>
              <td class="text-left  pl-3" style="width:15%;">{{incidentdata.date}} {{incidentdata.time }}</td>
              <td class="text-left text-transform-capitalise" style="width:26%;">{{incidentdata.employee.firstname}} {{incidentdata.employee.lastname}} {{incidentdata.employee.phone}}</td>
              <td class="text-left address" style="width:27%;">{{incidentdata.location}}</td>
              <!-- <td class="text-left  pr-5 pt-2 pb-2 " style="border-bottom:13px solid rgb(250 250 250);border-top: 1px solid #828282;padding-left: 22px;background:#ffffff !important;">{{incidentdata.description}}</td> -->
              <td class="text-center" style="width:14%;">
                 <b-button class="aj m-2 action-button view" @click="ViewIncidentSingleReport(incidentdata)"> <b-icon-zoom-in  />View Report</b-button>
              </td>
            </tr>
          </tbody>
        </table>
			</div>
      </div>
      </div>
      <div v-else></div>
    	</div>
       </template>
        <template v-else>
          <div style="padding-left:30px;">No Records Found</div>
        </template>
      </div>
    </div>
     <b-modal centered  no-close-on-esc hide-header-close  style="background-color: #AD9E58; margin:0 auto;" class="" id="datepicker1" title="Date picker">
			  <div  class="date-radio-labels my-3">
       
            <b-form-radio class="mx-3" v-model="selected"  name="some-radios" value="A">Single Date</b-form-radio>
            <!-- <b-form-radio class="mx-3" v-model="selected"  name="some-radios" value="B">Date Range</b-form-radio>-->
        
        </div>
      <b-calendar v-if="selected == 'A'"
				selected-variant="success"
				today-variant="info"
				nav-button-variant="primary"
				v-model="modal.date"
			>
			</b-calendar>
      <div class="daterangediv" style="text-align:center;" v-else>
        <div id="myList1"></div>
        <date-range-picker
            ref="picker"
            opens="inline"
            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
            :minDate="minDate" 
            :maxDate="maxDate"
            :autoApply="true"
            v-model="dateRange"
            :alwaysShowCalendars="true"
         
            :linkedCalendars="linkedCalendars"
            :dateFormat="dateFormat"
            :ranges="false"
            control-container-class="htmldatepicker"
            id="daterangedivpicker" >
        <template v-slot:input="picker" style="display: none;">
            {{ picker.startDate | moment('MM/DD/YYYY') }} - {{ picker.endDate | moment('MM/DD/YYYY') }}
        </template>
    </date-range-picker>
      </div>
		
			<template #modal-footer="{ }">
				<div class="text-center mt-2 mb-2" style="margin:0 auto;">
    				<button @click="sendDate()" style="border-radius: 5px;padding-top:6px; padding-bottom:6px;" class="text-white px-12 bg-custom-primary" type="submit">Set</button>
          		</div>
    		</template>
  </b-modal>
    <!-- View Report -->
  	 <b-modal size="md:w-12/12" centered  no-close-on-esc hide-header-close class="modal-add-new-employee modal-view-report hourlyincidentmodal" id="ViewDailyReport" title="" style="margin:0 auto;" >
    	<div id="printThis" class=" px-12 mb-8 mt-8">
			<div class="text-center" style="display:inline-block;">
        <div class="image left-section" style="float:left">
          <img src="/images/logo.png" alt="Eyewitness" style="height: 70px;margin: 10px 20px 10px 0px;">
        </div>
        <div class="image right-section"  style="float:left">
          <div class="heading" style=" text-align: left; font-size: 26px; font-weight: bold;letter-spacing: 0px; margin-top:10px; color: #302369; text-transform: uppercase;opacity: 1;font-family: sans-serif;">
            Eyewitness
          </div>
          <div class="website" style="text-align: left;font-size: 20px;  letter-spacing: 0px; color: #515151;opacity: 1;">
            8012 South Ashland Ave
          </div>
          <div class="website"  style=" text-align: left;font-size: 20px; letter-spacing: 0px; color: #515151; opacity: 1; ">
            Chicago, IL 60620
          </div>
          <div class="website" style=" text-align: left; font-size: 20px; letter-spacing: 0px; color: #515151;opacity: 1;">
           (888)-575-5598
          </div>
          <div class="email" style=" text-align: left; font-size: 20px;letter-spacing: 0px;color: #515151;opacity: 1;">
            info@swssgroup.com
          </div>
        </div>
      </div>
      <div style=" text-align: right;float:right; width:100%; margin-top:-100px; font-size: 22px; letter-spacing: 0px;color: #302369;opacity: 1;">
            License #: 122-001312
      </div>


		<div class="text-center" style="text-align: center;font-family: Poppins;letter-spacing: 0px;color: #3B86FF;opacity: 1;color: #2C1977;"> Daily Activity Logs </div>
			<div class="lower_section tabletopbackground" style="height:325px;margin-top: 2.5rem;" v-for="properties in modal.properties" :key="properties.id" >
      <table class="w-full client-hourly-report tabletopposition">
          <thead>
            <tr>
            <th style="width:13%">Date/Time</th> 
            <th style="width:20%">Officer  </th>
            <th style="width:16%">Location  </th>
            <th style="width: 20%;padding-left: 80px !important;">Description </th>
           
            </tr>
          </thead>
          <tbody style="width:91%">
            <tr v-for="reportdata in properties.hourly" :key="reportdata.id"  style="background:#ffffff !important;border-bottom:13px solid rgb(250 250 250);">
              <td style="width:20%">{{reportdata.date}} {{reportdata.slot}}</td> 
              <td style="width:30%">{{reportdata.employee.firstname}} {{reportdata.employee.lastname}} {{reportdata.employee.phone}}</td>
              <td style="width:27%">{{reportdata.location}}</td>
              <td style="margin-left:16px;" >{{reportdata.description}}</td>
            </tr>
          </tbody>
        </table>
			</div>
    	</div>
       
        		<template #modal-footer="{ }">
				<div class="text-center mt-2 mb-2 flex" style="margin:0 auto;">
		          <!-- <a  :href="'/api/hourlyreportdownloaddata/' + modal.apiUserId +'/'+ modal.apiStartDate +'/'+modal.apiEndDate" target="_blank" style="text-decoration:none;"><b-button class="m-2 action-button view download-report"><b-icon-cloud-arrow-down   />Download Report(pdf)</b-button></a> -->
    					<b-button class="m-2 action-button reject close-report" @click="hideViewReport()" > Close</b-button>
        </div>
    		</template>
		</b-modal>
  <!-- View Report -->
      <!-- Incident Report -->
  	 <b-modal size="md:w-12/12" centered  no-close-on-esc hide-header-close class="modal-add-new-employee modal-view-report hourlyincidentmodal" id="ViewIncidentReport" title="View Incident Report" style="margin:0 auto;" >
    	<div id="printThisIncident" class=" px-4 mb-8 mt-8">
			
		
			<div class="lower_section mt-10 tabletopbackground" style="height:300px;" v-for="properties in modal.properties" :key="properties.id" >
      <table class="w-full client-hourly-report tabletopposition">
          <thead>
            <tr>
            <th style="width:20%;" class="text-left  pl-3">Date/Time
             <img style="float:right;height: 17px;margin: 12px;margin-top: 16px;justify-self: center; justify-content: center;margin-right:10px;" src="/images/arrow.png" alt="arrow">
             </th>
            <th style="width:20%;" class="text-left">Officer 
              </th>
            <th style="width:20%;" class="text-left">Location 
            </th>
            <th style="width:20%;" class="text-left">Incident Report Type 
             </th>
            <th style="width:50%;" class="text-left ">Incident Report
             
            </th>
         
         
            </tr>
          </thead>
          <tbody >
            <tr  style="border-top: 10px solid #fff8f8;"v-for="incidentdata in properties.incident" :key="incidentdata.id">
              <td class="text-left  pl-3" style="border-bottom:13px solid rgb(250 250 250);border-top: 1px solid #828282;padding-left: 22px;background:#ffffff !important;"> {{incidentdata.time }}</td>
              <td class="text-left text-transform-capitalise">{{incidentdata.employee.firstname}} {{incidentdata.employee.lastname}}</td>
              <td class="text-left address">{{incidentdata.location}}</td>
              <td class="text-left">{{incidentdata.sub_report.report_type_name}}</td>
              <td class="text-left  pr-5 pt-2 pb-2 ">{{incidentdata.description}}</td>
            
            </tr>
          </tbody>
        </table>
			</div>
    	</div>
       
        		<template #modal-footer="{ }">
				<div class="text-center mt-2 mb-2 flex" style="margin:0 auto;">
    					<b-button class="m-2 action-button download" @click="hideIncidentReport()" > <b-icon-x-circle />Close</b-button>
		         
              
          		</div>
    		</template>
		</b-modal>
  <!-- View Incident Report -->
  <!-- Incident Single Report -->
  	 <b-modal size="md:w-12/12" centered  no-close-on-esc hide-header-close class="modal-add-new-employee modal-view-report hourlyincidentmodal" id="ViewIncidentSingleReport" title="" style="margin:0 auto;" >
    	<div id="printThisIncidentSingle" class=" px-4 mb-8 mt-8">
									<div class="text-center" style="display:flex;">

        <div class="image left-section">
          <img src="/images/logo.png" alt="Eyewitness" style="height: 70px;margin: 10px 20px 10px 0px;">
        </div>

        <div class="image right-section">
         <div class="heading" style=" text-align: left; font-size: 26px; font-weight: bold;letter-spacing: 0px; margin-top:10px; color: #302369; text-transform: uppercase;opacity: 1;font-family: sans-serif;">
            Eyewitness
          </div>

          <div class="website" style="text-align: left;font-size: 20px;  letter-spacing: 0px; color: #515151;opacity: 1;">
            8012 South Ashland Ave
          </div>

          <div class="website"  style=" text-align: left;font-size: 20px; letter-spacing: 0px; color: #515151; opacity: 1; ">
            Chicago, IL 60620
          </div>

          <div class="website" style=" text-align: left; font-size: 20px; letter-spacing: 0px; color: #515151;opacity: 1;">
           (888)-575-5598
          </div>

          <div class="email" style=" text-align: left; font-size: 20px;letter-spacing: 0px;color: #515151;opacity: 1;">
            info@swssgroup.com
          </div>

        </div>


      </div>
      <div style=" text-align: right;float:right; width:100%; margin-top:-100px; font-size: 22px; letter-spacing: 0px;color: #302369;opacity: 1;">
            License #: 122-001312
      </div>
		<div class="text-center" style="text-align: center;font: normal normal 600 26px Source Sans Pro; letter-spacing: 0px; color:#302369; opacity: 1;">Incident Report </div>
		
			 <div class="lower_section mt-10" v-for="properties in modal.properties" :key="properties.id" style="padding: 0px 50px 0px 50px;" > 
			<div class=" tabletopbackground lower_section mt-10"  style="padding: 0px 50px 0px 50px;" >
      <table class="w-full client-hourly-report tabletopposition" style="display:none;">
          <thead>
            <tr>

             

            <th style="width:20%;">Date/Time</th>

            <th style="width:20%;">Officer</th>

            <th style="width:20%;">Location</th>

            <th style="width:20%;">Incident Type</th>

            <th style="width:20%;">Description</th>
         
         
            </tr>
          </thead>
          <tbody v-if="singleincident.length != 0">

            <tr :key="singleincident.id" style="background:#ffffff !important;border-bottom:13px solid rgb(250 250 250);">
              <td style="width:20%;">{{singleincident.time }}</td>

              <td style="width:20%;">{{singleincident.employee.firstname}} {{singleincident.employee.lastname}}</td>

              <td style="width:20%;">{{singleincident.location}}</td>

              <td style="width:18%;">{{singleincident.sub_report.report_type_name}}</td>
              
              <td style="width:20%;margin-left:5px;">{{singleincident.description}}</td>
            
            </tr>
          </tbody>
        </table> 

        <div v-if="singleincident.length != 0" class="left_section w-12/12" style="margin:0 auto;" >
				<div class="content flex" style="display:flex;margin-bottom:10px;">
					<div class="w-3/12 content-css border-right-0" style=" color:#302369; border-right: none  !important; font:normal normal 600 23px Source Sans Pro;"> Incident Type: </div>
					<div class="w-9/12 content-css" style="font:normal normal 600 23px Source Sans Pro;">{{singleincident.sub_report.report_type_name}} </div>
				</div>
				<div class="content flex" style="display:flex; margin-bottom:10px;">
					<div class="w-3/12 content-css border-right-0" style="color:#302369; border-right: none  !important;font:normal normal 600 23px Source Sans Pro;">Date/Time: </div>
					<div class="w-9/12 content-css"  style="font:normal normal 600 23px Source Sans Pro;">  {{singleincident.date }} {{singleincident.time }} </div>
				</div>
				<div class="content flex" style="display:flex; margin-bottom:10px;">
					<div class="w-3/12 content-css border-right-0" style="color:#302369;border-right: none  !important;font:normal normal 600 23px Source Sans Pro;">Officer: </div>
					<div class="w-9/12 content-css"  style="font:normal normal 600 23px Source Sans Pro;">{{singleincident.employee.firstname}} {{singleincident.employee.lastname}} {{singleincident.employee.phone}} </div>
				</div>
				<div class="content flex" style="display:flex; margin-bottom:10px;">
					<div class="w-3/12 content-css border-right-0" style="color:#302369;border-right: none  !important;font:normal normal 600 23px Source Sans Pro;">Location: </div>
					<div class="w-9/12 content-css"  style="font:normal normal 600 23px Source Sans Pro;">{{singleincident.location}} </div>
				</div>
				<div class="content flex" style="display:flex;margin-bottom:10px;">
					<div class="w-3/12 content-css border-right-0" style="color:#302369; border-right: none  !important;font:normal normal 600 23px Source Sans Pro;">Description: </div>
					<div class="w-9/12 content-css" style="font:normal normal 600 23px Source Sans Pro;">  {{singleincident.description}} </div>
				</div>
		</div>
			</div>
			</div>
    	</div>
       
        		<template #modal-footer="{ }">
				<div class="text-center mt-2 mb-2 flex" style="margin:auto;">
          
    					<b-button class="m-2 action-button reject close-report" @click="hideIncidentSingleReport()" >Close</b-button>
		         
          		</div>
    		</template>
		</b-modal>
  <!-- View Incident Report -->



  </div>
</template>

<script>

import Vue from 'vue';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
// import 'bootstrap/dist/css/bootstrap.css'
// import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'bootstrap-vue/src/components/form-datepicker'
import 'bootstrap-vue/src/components/calendar'
import moment from 'moment'
import axios from 'axios'

import Modal from '../shared/Modal'
import Loader from '../shared/Loader'
import { BCalendar } from 'bootstrap-vue'
import { BFormDatepicker } from 'bootstrap-vue'
import datetime from 'vuejs-datetimepicker';
import Datepicker from 'vuejs-datepicker';
import VueTimepicker from 'vue2-timepicker'
import DateRangePicker from 'vue2-daterange-picker'
import 'vue2-timepicker/dist/VueTimepicker.css'
import 'vue2-daterange-picker/dist/vue2-daterange-picker.css'

Vue.component('b-form-datepicker', BFormDatepicker)

export default {
	components: {
		BootstrapVue,
		IconsPlugin,
		
		Modal,
		Loader,
		BCalendar,
		datetime ,
		Datepicker,
		VueTimepicker ,
    DateRangePicker 
	},
	data() {
		return {
      // multiple date range
      direction: 'ltr',
      format: 'mm/dd/yyyy',
      separator: ' - ',
      applyLabel: 'Apply',
      cancelLabel: 'Cancel',
      weekLabel: 'W',
      customRangeLabel: 'Custom Range',
      daysOfWeek: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
      monthNames: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      firstDay: 0,
      dateRange:{
      startDate: null ,
      endDate: null
      },
      startDate: null,
      endDate: null,
      opens: '',
      minDate: null,
      maxDate: null,
      singleDatePicker: false,
      timePicker: false,
      timePicker24Hour: false,
      showWeekNumbers: false,
      showDropdowns: false,
      autoApply: false,
      linkedCalendars: false,
      dateFormat:null,
      checkOpen:true,
      ranges: { //default value for ranges object (if you set this to false ranges will no be rendered)
                'Today': false,
                'Yesterday': false,
                'This month':false,
                'This year': false,
                'Last week':false,
                'Last month':false,
            },


      selected: 'A',
      searchKeyword: '',
      searchTimer: null,
      showdatepicker: false,
      showmodal: false,
      currentDate: new Date(),
      hourlydate : new Date(),
      user_id:'',
      index:{
        report:{}
      },
      modal:{
        date:'1',
        properties:[],
        viewreportmodal: [],
        viewReportModal: false,
				viewreportmodalreport: [],
      },
      isLoader: false,
      singleincident:[],
      apiUserId:'',
      apiStartDate:'',
      apiEndDate:'',
		}
	},
  computed: {
   user_data(){
     return JSON.parse(localStorage.getItem('user'))
   },
   userid(){
    let user = localStorage.getItem('user')
    return JSON.parse(user).id
  }
  },
  async created() {
    let vm = this
     this.user_id = this.userid
    //  this.modal.date = moment(new Date()).format('YYYY-MM-DD');
    // await vm.indexReport()
    await vm.hourlyReport()
    axios.get('/api/export').then(res => {
          if(res.data.status != true){
            // vm.$swal({
            //   icon: 'error',
            //   title: res.data.message,
            //   showConfirmButton: false,
            //   timer: 1500
            // 	})
               vm.isLoader = false
             
          }
          else{
            vm.isLoader = false
          }
        })
   
      // vm.isLoader = true
  },
  mounted(){
    this.$root.$on('message1' ,() => {
      this.showdatepicker = true
      this.opendatepicker();
    })
  },
	methods: {
    alert(data){
      alert(data);
    },
    // Single Incident Report
    hideIncidentSingleReport(){
       this.$bvModal.hide('ViewIncidentSingleReport');
    },
    ViewIncidentSingleReport(data){
      let vm =  this;
      vm.singleincident = data
      // vm.modal.viewReportModal = true; 
       this.inject_material_fonts_and_icons_into_header();
        setTimeout(() => {
              this.$bvModal.show('ViewIncidentSingleReport')
          }, 1000);
    },
    // Incident Report
    hideIncidentReport(){
       this.$bvModal.hide('ViewIncidentReport');
    },
    ViewIncidentReport(){
      let vm =  this;
      // vm.modal.viewReportModal = true; 
       this.inject_material_fonts_and_icons_into_header();
        setTimeout(() => {
              this.$bvModal.show('ViewIncidentReport')
          }, 1000);
    },
    // Daily Report
    hideViewReport(){
       this.$bvModal.hide('ViewDailyReport');
    },
    ViewDailyReport(){
      let vm =  this;
      //vm.modal.apiUserId = this.user_id;
      //vm.modal.apiStartDate = this.modal.date;
      //vm.modal.apiEndDate = '1';
       this.inject_material_fonts_and_icons_into_header();
        setTimeout(() => {
              this.$bvModal.show('ViewDailyReport')
          }, 1000);
    },
    datetoreset(){
      let vm = this;
      vm.modal.date = ''
      vm.searchKeyword = ''
      vm.dateRange.startDate = null
      vm.dateRange.endDate = null
      vm.currentDate = new Date();
      // this.sendDatedefault();
      vm.hourlyReport();
    },
    datetolisten1(){
      this.showdatepicker = true
      this.opendatepicker();
      // this.modal.date = '';
      // this.dateRange.startDate = null;
      // this.dateRange.endDate = null;
    },
    openCalender(){
      
     
    },
    updateValues(){
      
    },
    downloadReportModal(id){
       this.printDiv(id);
      // document.getElementById("nonPrintable").className += " noPrint";
    },
    downloadReport(){
      window.print();
    },
   printDiv(modalid) {
            

                let contents = document.getElementById(modalid).innerHTML;
                let frame1 = document.createElement('iframe');
                frame1.name = "frame1";
                frame1.id = "frame";
                frame1.style.position = "absolute";
                frame1.style.top = "-1000000px";
                document.body.appendChild(frame1);
                let frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
                frameDoc.document.open();
                frameDoc.document.write('<html lang="en"><head><title>Daily Activity Report</title>');
                frameDoc.document.write('<link rel="stylesheet" type="text /css" href="/css/app.css"/>');
                frameDoc.document.write('<link rel="stylesheet" type="text /css" href="/css/dashboard.css"/>');
                frameDoc.document.write('</head><body>');
                frameDoc.document.write(contents);
                frameDoc.document.write('</body></html>');
                frameDoc.document.close();
                

                let newcontent = '<html lang="en"><head><title>Daily Activity Report</title><link rel="stylesheet" type="text /css" href="/css/app.css"/><link rel="stylesheet" type="text /css" href="/css/dashboard.css"/></head><body><div style="margin-left:50px">'+contents+'</div></body></html>';
               // console.log('contents',newcontent)
                axios
                .post(`/api/hourlyreportdownloaddata`, {content: newcontent, client_id: ''  }) 
                .then(res => { console.log('success')  })
                .catch(err => { console.log('failure')  })

                setTimeout(function () {
                   
                   // return false;

                  //   var pdf = new jsPDF('p', 'pt', 'letter');
                  //   var margin = 10;
                  //   pdf.fromHTML(newcontent, 0, 0, {},
                  // function(bla){ pdf.save('hourlyreport.pdf'); },
                  // margin);
                  window.frames["frame1"].focus();
                 // window.frames["frame1"].print();
                  document.body.removeChild(frame1);
                }, 500);

                //return false;
},
    selectLocation() {
    var x = document.getElementById("location").value;
    let vm = this;
        axios
          .post(`/api/reportfilter`, {search: x,client_id: this.user_id, current_date:  vm.modal.date  }) 
          .then(res => {
            if(res.data.status != true){
               vm.$swal({
              icon: 'error',
              title: res.data.message,
              showConfirmButton: false,
              timer: 1500
            })
             vm.index.report= [];
            }
            else{
             vm.index.report = res.data.data
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
    sendDatedefault(){
        var y = document.getElementById("location").value;
      		let vm = this;
      axios.post(`/api/reportfilter`, {search: vm.searchKeyword, current_date : vm.currentDate , client_id : this.user_id }) 
          .then(res => {
        if(res.data.status != true){
           vm.$swal({
              icon: 'error',
              title: res.data.message,
              showConfirmButton: false,
              timer: 1500
            	})
              vm.index.report = [];
        }
        else{
         vm.currentDate = vm.modal.date;
			   vm.$swal({
              icon: 'success',
              title: 'Data Fetched Successfully!',
              showConfirmButton: false,
              timer: 1500
            	})
            this.$bvModal.hide('datepicker1');
            // this.destroystyle();
            vm.index.report = res.data.data
           
        }
		  });
     
    },
    sendDate(){
      let vm = this;
      let datetosent = moment(new Date()).format('YYYY-MM-DD');
      let start_date = ''; 
      let end_date = '';
          if(this.selected == 'A'){
            start_date = '';
            end_date = '';
             if(vm.modal.date!=''){
                datetosent = vm.modal.date
              }
          }
          else{
          datetosent = '';
            if(vm.dateRange.startDate != null || vm.dateRange.endDate != null ){
            start_date = moment(vm.dateRange.startDate).format('YYYY-MM-DD');
            end_date = moment(vm.dateRange.endDate).format('YYYY-MM-DD');
            }
          }
      axios.post(`/api/reportfilter`, {start_date: start_date  , end_date: end_date, search: vm.searchKeyword, current_date : datetosent , client_id : this.user_id}) 
          .then(res => {
        if(res.data.status != true){
           vm.$swal({
              icon: 'error',
              title: res.data.message,
              showConfirmButton: false,
              timer: 1500
            	})
              vm.index.report = [];
        }
        else{
         vm.currentDate = vm.modal.date;
			   vm.$swal({
              icon: 'success',
              title: 'Data Fetched Successfully!',
              showConfirmButton: false,
              timer: 1500
            	})
            this.$bvModal.hide('datepicker1');
            // this.destroystyle();
            // vm.index.report = res.data.data
            let data = res.data.data;
            vm.modal.properties = data;
            vm.modal.properties.map(x=>{
              return x.incident.filter(y =>{
              return y.time = moment( y.date + ' ' + y.time).format('h:mm A')
              })
            })
           
        }
		  });
     
    },
    search() {
      let vm = this
      if (vm.searchTimer ) {
        clearTimeout(vm.searchTimer)
        vm.searchTimer = null
      }
      vm.searchTimer = setTimeout(() => {
        vm.sendDate();
      }, 500)
    },
    opendatepicker(){
        this.inject_material_fonts_and_icons_into_header();
        setTimeout(() => {
              this.$bvModal.show('datepicker1')
          }, 1000);
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
    /**
     * List all report
     */
    indexReport() {
      let vm = this

      vm.isLoader = true
      this.hourlydate = moment(this.hourlydate).format('YYYY-MM-DD');
      axios
        .post(`/api/clienthourlyreport`,{current_date : this.hourlydate, client_id: this.user_id  })  
        .then(res => {
          if(res.data.status != true){
            vm.$swal({
              icon: 'error',
              title: res.data.message,
              showConfirmButton: false,
              timer: 1500
            	})
               vm.isLoader = false
               vm.index.report = [];
          }
          else{
            vm.index.report = res.data.data
             //   vm.index.report.filter(x=>{
            // 	 return x.time = x.date + ' ' + x.time;
            // });
            //   vm.index.report.filter(x=>{
            // 	 return x.time = moment(x.time).format('h:mm A')
            // });
            vm.isLoader = false
          }
        })
    },
    hourlyReport(){
     // alert( this.currentDate )
      let vm = this;
      this.currentDate = moment(this.currentDate).format('YYYY-MM-DD');
      console.log('clientreports-client_id', this.userid)
      console.log('clientreports-date', this.currentDate)
      axios
            .post(`/api/clientreports`, { client_id: this.userid, date: this.currentDate })
            .then(res => {
              console.log('clientre',res);
              if(res.data.status != false){ 
          
                let data = res.data.data;
                vm.modal.properties = data;
                vm.modal.viewreportmodal = data[0];
                vm.modal.viewreportmodalreport = data
                vm.modal.viewreportmodalreport = vm.modal.viewreportmodalreport.filter(x=>{
                  return x.time = x.date + ' ' + x.time;
                });
                vm.modal.viewreportmodalreport = vm.modal.viewreportmodalreport.filter(x=>{
                  return x.time = moment(x.time).format('h:mm A')
                });
                 vm.modal.properties.map(x=>{
                  return x.incident.filter(y =>{
                    return y.time = moment( y.date + ' ' + y.time).format('h:mm A')
                   })
                 })
              }
              else{
              vm.isLoader = false
              /*vm.$swal({
                icon: 'error',
                title: res.data.message,
                showConfirmButton: false,
                timer: 1500
              })*/
              vm.modal.properties =[];
              }
          
            })
        
    }

   


	}
}

</script>

<style lang="scss" scoped>


@media screen {
  #printSection {
      display: none;
  }
}

@media print {
  body * {
    visibility:hidden;
  }
  #printSection, #printSection * {
    visibility:visible;
  }
  #printSection {
    position:absolute;
    left:0;
    top:0;
  }
}
// Incident report
.lower_section{
  	.left_section{
		.content{
			margin-right: 1.25rem;
			.content-css{
				border: 1px solid #bcbfc3;
				padding: 15px 8px;
				// font-family: "Source Sans Pro", sans-serif;
				font: normal normal normal 16px Montserrat;
				margin: 5px 0 0 0;
			}
		}
	}
}
.border-right-0{
	border-right: none  !important;
}
.aj{
  background-color:#2C1977;
border-radius: 5px;
color:#ffffff;
font-family: Poppins;
font-weight: bold;
font-size: 16px;
}
.nh5{
background-color:#AD9E58 !important;
font-family: Poppins;
font-size: 20px;
color:#ffffff;


}
// action buttons
.action-button{
	&:focus{
		outline: none;
	}
	top: 420px;
	left: 1490px;
	// border-radius: 10px;
	opacity: 1;
	display: flex;
	color:#ffffff;
	justify-content: space-evenly;
  font-family: Poppins;
	// font: normal normal normal 15px Source Sans Pro;
    padding: 5px 5px;
	svg{
		font-size: 16px !important;
    	margin:3px 5px 5px 5px !important;
	}
}
.action-button.view{
  background-color:#2C1977;
	// background: #3B86FF 0% 0% no-repeat padding-box;
	width: auto;
	height: 36px;
  font-family: Poppins;
   border-radius: 5px;
       padding-right: 15px;
  svg{
    transform: scaleX(-1);
  }
}
.action-button.reject{
	background: #2C1977 0% 0% no-repeat padding-box;
	width: 80px;
	height: 33px;
	border: 1px solid #606060;
	color: #ffffff;
  align-items: center;
}
.action-button.edit{
	background: #C4AA33 0% 0% no-repeat padding-box;
	width: 80px;
	height: 33px;
}
.modal-view-report .action-button.edit{
	background: #C4AA33 0% 0% no-repeat padding-box;
	width: 100px;
    height: 45px;
    font-family: "Source Sans Pro", sans-serif;
    padding: 10px 20px;
    font-weight: 600;
}
.action-button.approve{
	background: #302369 0% 0% no-repeat padding-box;
	top: 732px;
	left: 1674px;
	width: 105px;
	height: 33px;
}
.modal-view-report .action-button.approve{
	background: #302369 0% 0% no-repeat padding-box;
    width: 125px;
    height: 45px;
    left: 1674px;
    font-family: "Source Sans Pro", sans-serif;
    padding: 10px 15px;
}
.action-button.download{
	background: #ffffff;
	border: none;
	color: #302369;
	font: normal normal normal 19px Source Sans Pro;
	svg{
		color: #302369;
		font-size: 25px !important;
    	margin: 0px 5px 5px 0px !important;
	}
} 
.w-10{
  width: 10% !important;
}
.client-hourly-report {
      thead{
    tr{
      text-align: center;
      height: 59px;
       background-color: #aea05c;
      color: #FFFFFF;
      opacity: 1;
      font-family: Poppins;
      font-size: 20px;
      
      th{
         padding: 1px 0px 1px 25px;
         &:first-child {
            // border-top-left-radius: 20px;
            
        }
         &:last-child {
            // border-top-right-radius: 20px;
        }
      }
    }
  }
  
}
.heading-log{
  top: 262px;
left: 334px;
width: 80%;
height: 23px;
text-align: left;
font: normal normal 600 23px ;
font-family: Poppins;
letter-spacing: 0px;
color: #000000;
opacity: 1;
text-transform: capitalize;
}
.download-blue-button{
  width: 20%;
  svg{
    font-size: 24px;
    margin-right: 5px;
  }
}
.propertyname_head{
  top: 272px;
left: 328px;
// width: 131px;
width: 80%;
width: auto;
margin-right: 10px;
height: 33px;
text-align: left;
font: normal normal 600 26px/33px ;
font-family: Poppins;
letter-spacing: 0px;
color: #302369;
opacity: 1;
}
.date-radio-labels{
  
    display: flex;
    justify-content: center;
 
}
@media screen and (min-width: 1440px){
  .right-section{
      .download{
        width: 75% !important;
      }
  }
  .propertyname_head{
    // width: 85%;
    width: auto;
  }
  .heading-log{
    text-transform: capitalize;
    width: 85%;
  }
  .download-blue-button{
  width: 20%;
}
}
.download-single-property{
  float: right;
    cursor: pointer;
    text-decoration: none;
    background: #302369 0% 0% no-repeat padding-box;
    opacity: 1;
    color: #ffffff;
    border-radius: 10px;
    padding: 5px;
    border: none;
}

table td:first-child {
    background: none !important;
    border-radius: 5px 5px 0px 0px;
}
.reset-to-default{
    top: 202px;
    left: 466px;
    width: 174px;
    height: fit-content;
    border: 1px solid #3B86FF;
    border-radius: 10px;
    opacity: 1;
    background-color: #ffffff;
    color: #3B86FF;
    &:hover{
      border: 1px solid #3B86FF;
      border-radius: 10px;
      opacity: 1;
      background-color: #ffffff;
      color: #3B86FF;
    }
}
.hourly-report{
  .header{
    .date{
      margin: auto 0;
      font-size: 18px;
      font: normal normal normal 16px Montserrat;
      color: #302369;
      font-weight: 600;
    }
    .officer-report-search-report{
        top: 302px;
        left: 269px;
        width: 171px;
        height: 41px;
        background: #FFFFFF 0% 0% no-repeat padding-box;
        border: 1px solid #959595;
        border-radius: 10px;
        opacity: 1;
        padding-right: 35px;
      }

    .right-section{
      .download{
      // color: #717171;
      cursor: pointer;
      text-decoration: none;
      top: 202px;
      left: 1633px;
      // width: 201px;
      width: 100%;
      text-align: center;
      height: 36px;
      background: #302369 0% 0% no-repeat padding-box;
      opacity: 1;
      color: #ffffff;
      border-radius: 10px;
      padding: 5px;
      svg{
        font-size: 22px;
          margin: 0 5px;
      
      }
      }
    }
  }
}
  .report-container{
    .reports{
      justify-content: flex-start;
      .card{
        width:100%;
        background: #FFFFFF 0% 0% no-repeat padding-box;
        box-shadow: 2px 4px 20px #00000026;
        border-radius: 10px;
        opacity: 1;
        margin: 15px 15px 15px 0px;
        min-height: 90px;
        border: none;
        overflow: hidden;
        padding-left: 5px;
        .card-body{
          .card-title{
            width: auto;
            border: 1px solid purple;
            border-radius: 10px;
            padding: 5px 5px;
            font-size: 16px;

          }
          .date-section{
            .card-title{
              // height: 25px;
              border: 1px solid #302369;
              border-radius: 7px;
              opacity: 1;
              /* top: 279px; */
              /* left: 344px; */
              width: auto;
              /* height: 18px; */
              text-align: center;
              font: normal normal normal 14px/18px Montserrat;
              /* letter-spacing: 0px; */
              color: #302369;
              opacity: 1;
            }
            justify-content: space-between;
            input{
              margin-top: 10px;
            }
          }
          .upper-section{
            img{
              width: 50px;
              height: 50px;
              border: 3px solid #FFFFFF;
              border-radius: 7px;
              opacity: 1;
            }
            .info-section{
              margin-left: 5px;
              p{
                margin-bottom: 0px;
                font-weight: 500;
              }
              .name{
                text-transform: capitalize;
              }
              .id-no{
                text-transform: uppercase;
              }
            }
          }
          .lower-section{
            .desc{
              background: #FFFFFF 0% 0% no-repeat padding-box;
              box-shadow: 2px 4px 20px #00000026;
              border-radius: 10px;
              opacity: 1;
              margin: 15px 15px 15px 0px;
              width: 100%;
              min-height: 47px;
              border: none;
              overflow: hidden;
              padding-left: 5px;
              display: block;
              &:focus{
                outline: none;
              }
            }
          }
        }
      }
    }
  }
	@import '../../../sass/client/employees';
</style>