<template class="reportss" style="margin-left: 20%;">
  <div>

		<div class="w-5/6 flex mx-auto mt-5 ">
			<div class="w-2/6 mr-3">
				<div class="bg-custom-cardBg rounded-lg p-4">
					<h4 class="leading-none font-semibold text-xl mb-2">Options</h4>
		      <div class="relative bg-custom-cardBg mb-2">
		        <select class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none"
		        	@change="chooseDateRange"
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
		          <!-- <option value="this-quarter">This Quarter</option>
		          <option value="last-quarter">Last Quarter</option>
		          <option value="this-year">This Year</option>
		          <option value="year-to-date">Year To Date</option>
		          <option value="after-today">After Today</option>
		          <option value="before-today">Before Today</option>
		          <option value="all-dates">All Dates</option> -->
		        </select>
		        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
		          <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
		        </div>
		      </div>					
					<div class="flex items-center justify-end mb-2">
          	<label>Begin Date</label>
          	<div class="ml-4">
	          	<font-awesome-icon icon="calendar-alt" class="mr-1" />
		          <date-picker valueType="format" v-model="options.beginDate"
		            input-class="appearance-none block w-full rounded py-1 pl-2 pr-4 leading-tight focus:outline-none border border-custom-border placeholder-black"
		            placeholder="mm/dd/yyyy"
		            format="MM/DD/YYYY"
		            :clearable="false">
		          	<i slot="icon-calendar" style="color: #000">
		          		<font-awesome-icon :icon="['far', 'calendar']" />
		          	</i>
		          </date-picker>
	          </div>
          </div>

					<div class="flex items-center justify-end mb-2">
          	<label>End Date</label>
          	<div class="ml-4">
	          	<font-awesome-icon icon="calendar-alt" class="mr-1" />
		          <date-picker valueType="format" v-model="options.endDate"
		            input-class="appearance-none block w-full rounded py-1 pl-2 pr-4 leading-tight focus:outline-none border border-custom-border placeholder-black"
		            placeholder="mm/dd/yyyy"
		            format="MM/DD/YYYY"
		            :clearable="false">
		          	<i slot="icon-calendar" style="color: #000">
		          		<font-awesome-icon :icon="['far', 'calendar']" />
		          	</i> 
		          </date-picker>
	          </div>
          </div>

		      <div class="relative bg-custom-cardBg">
		        <select class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none"
		        	v-model="options.position">
		          <option value="">All Positions</option>
		          <option :key="data.id" :value="data.id" v-for="data in positions.data">{{ data.position }}</option>
		        </select>
		        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
		          <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
		        </div>
		      </div>
				</div>

				<!-- <div class="bg-custom-cardBg my-1 rounded-lg p-4">
					<h4 class="leading-none font-semibold text-xl mb-1">Search for Shifts</h4>
					<p>
						<a href="#" @click.prevent="maintenanceMode">
							Click to search shifts by individual employee or by a shift description etc.
						</a>
					</p>
				</div> -->

				<!-- <div class="bg-custom-cardBg rounded-lg p-4">
					<h4 class="leading-none font-semibold text-xl mb-3">Information</h4>
					<ul>
						<li class="flex my-2">
							<font-awesome-layers class="mr-1 mt-1">
							  <font-awesome-icon icon="star" class="text-custom-border" />
							  <font-awesome-icon icon="star" transform="shrink-3" :style="{ color: '#83B7FF' }" />
							</font-awesome-layers>
							Blue starred favorites are at top of the list
						</li>
						<li class="flex my-2">
							<font-awesome-layers class="mr-1 mt-1">
							  <font-awesome-icon icon="star" class="text-custom-border" />
							  <font-awesome-icon icon="star" transform="shrink-3" :style="{ color: '#8ECD68' }" />
							</font-awesome-layers>
							Green starred indicates a non-favorite run recently
						</li>
						<li class="flex">
							<font-awesome-layers class="mr-1 mt-1">
							  <font-awesome-icon icon="star" class="text-custom-border" />
							  <font-awesome-icon icon="star" transform="shrink-3" :style="{ color: '#E3E6F0' }" />
							</font-awesome-layers>
							Click star to favorite a report and add to top of list
						</li>
						<li class="mt-2">
							- Use Find to quickly locate a report
						</li>
					</ul>
				</div> -->
			</div>

			<div class="w-4/6 bg-custom-cardBg rounded-lg py-4 px-6">
				<div class="mb-8">
					<h2 class="leading-loose font-semibold text-custom-primary text-3xl px-2">Shift Details Reports</h2>

					<div class="flex items-center justify-between my-3 pl-2">
					  <label class="flex items-center select-none">
					    <input type="checkbox" class="form-checkbox border border-custom-border" v-model="options.isIncludePayRateAndCosts">
					    <span class="ml-2">Include Pay Rate & Costs</span>
					  </label>
	          <div class="relative">
	            <input class="appearance-none block w-full rounded py-2 pl-4 pr-8 leading-tight focus:outline-none border border-custom-border placeholder-custom-placeholder" type="text" placeholder="Find" v-model="search" @keyup="getSearchedData">
	            <div class="absolute inset-y-0 right-0 flex items-center px-2 text-custom-border rounded-r border border-custom-border border-l-0">
	              <font-awesome-icon icon="search" class="fill-current" />
	            </div>
	          </div>
					</div>
				</div>

				<table class="table-fixed w-full">
					<thead>
						<tr>
							<td class="w-6/12 pl-2 font-semibold text-lg py-2">Description</td>
							<!-- <td class="w-2/12 font-semibold text-lg text-center">Date Range</td> -->
							<td class="w-1/12 font-semibold text-lg text-center">Print</td>
							<td class="w-1/12 font-semibold text-lg text-center">Export</td>
							<!-- <td class="w-2/12 font-semibold text-lg text-center">Last Run</td> -->
						</tr>
					</thead>
					<tbody class="border border-custom-border" v-if="reports.length > 0">
						<tr :key="data.id" v-for="data in reports" class="bg-white even:bg-custom-custom2">
							<td class="pl-2 py-6 text-lg">
								<font-awesome-layers class="mr-3">
								  <font-awesome-icon icon="star" class="text-custom-border" size="lg" />
								  <font-awesome-icon icon="star" size="lg" transform="shrink-3" :style="{ color: '#E3E6F0' }" />
								</font-awesome-layers>
								{{ data.description }}
							</td>
							<!-- <td class="text-center">&nbsp;</td> -->
							<td class="text-center">
								<a href="#" class="text-custom-primary"
									@click.prevent="openModal('Print', data)">
									<font-awesome-icon icon="print" size="lg" />
								</a>
							</td>
							<td class="text-center">
								<a href="#" class="text-custom-primary"
									@click.prevent="openModal('Export', data)">
									<font-awesome-icon icon="external-link-square-alt" size="lg" />
								</a>
							</td>
							<!-- <td class="text-center">&nbsp;</td> -->
						</tr>
					</tbody>
					<tbody class="border border-custom-border" v-else>
						<tr class="bg-white">
							<td class="pl-2 py-4" colspan="5">No record(s) found.</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="bg-custom-cardBg mx-4 rounded-lg p-4 mt-20">
			<div class="flex justify-between items-center">
				<h2 class="leading-none font-semibold text-xl mb-1">Information</h2>
	      <a href="#" class="text-sm inline-flex items-center">
	        Help on this topic&nbsp;<font-awesome-icon icon="arrow-circle-right" />&nbsp;<strong>More</strong>
	      </a>
	    </div>
		</div>
		<!-- <download-csv  style="opacity: 0; position:absolute; cursor: default;"  ref='myBtn' :data = exportData name = 'Export' ></download-csv> -->
<!-- MODAL for Export -->
<modal v-model="modal.export" class="modal-add-new-employee" size="md:w-4/12" title="Export Data">
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
                      <template v-if="options.position == ''">
                      <h5 class="mt-2 mr-4" > All Positions </h5>
                      </template>
                      <template v-else>
                        <h5 class="mt-2 mr-4" v-for="pos in positions" :key="pos.id" > {{ options.position == pos.id ? pos.position : '' }} </h5>
                      </template>
                      
                    </div>
    		  		</div>
    		  		<div class="items-center leading-none mt-4">
    		  			<h3 class="text-xl font-semibold mr-4">File Info</h3>
    		  			<h5 class="mt-2 mr-4">Include Columns</h5>
    		  		</div>
    		  	</div>
            <!-- <ul class="flex flex-col flex-wrap ml-10 mr-6" v-if="weekDays.length===0">
              <li>No Records Found</li>
            </ul> -->
            
    		<ul class="flex flex-col flex-wrap ml-10 mr-6 export-ul">
              <!-- <li class="export-li" v-if="modal.exportvisible.number"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.exportData.number">
    	            <span class="text-sm">Employee Number</span>
    	          </label>
              </li> -->
              <li class="export-li"  v-if="modal.exportvisible.name"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.exportData.name">
    	            <span class="text-sm">Employee Name</span>
    	          </label>
              </li>
              <li class="export-li"  v-if="modal.exportvisible.position"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.exportData.position">
    	            <span class="text-sm">Position</span>
    	          </label>
              </li>
              <li class="export-li"  v-if="modal.exportvisible.shifts"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.exportData.shifts">
    	            <span class="text-sm">Number of Shifts </span>
    	          </label>
              </li>
              <li class="export-li"  v-if="modal.exportvisible.hours"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.exportData.hours">
    	            <span class="text-sm">Number of Hours</span>
    	          </label>
              </li>
              <li class="export-li"  v-if="modal.exportvisible.hire_date"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.exportData.hire_date">
    	            <span class="text-sm">Hire Date</span>
    	          </label>
              </li>
              <li class="export-li"  v-if="modal.exportvisible.payrate"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.exportData.payrate">
    	            <span class="text-sm">Pay Rate </span>
    	          </label>
              </li>
              <li class="export-li"  v-if="modal.exportvisible.totalpay"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.exportData.totalpay">
    	            <span class="text-sm">Total Pay </span>
    	          </label>
              </li>
			  <li class="export-li"  v-if="modal.exportvisible.firstname"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.exportData.firstname">
    	            <span class="text-sm">First Name</span>
    	          </label>
              </li>
			  <li class="export-li"  v-if="modal.exportvisible.lastname"> 
                <label class="inline-flex items-center">
    	            <input class="mr-1 leading-tight"  type="checkbox" v-model="modal.exportData.lastname">
    	            <span class="text-sm">Last Name</span>
    	          </label>
              </li>
             
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
<!-- MODAL for Export end -->
  </div>
</template>

<script>
	
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'

import moment from 'moment'
import axios from 'axios'
import Modal from './shared/Modal'

export default {
	components: {
		DatePicker,
		Modal
	},
	data() {
		return {
			data: [],
			request: {
				body: {}
			},
			options: {
				chooseDateRange: '',
				beginDate: '',
				endDate: '',
				position: '',
				isIncludePayRateAndCosts: false
			},
			positions: {},
			reports: {},

			search: '',
			exportData:[],
			modal:{
				export: false,
				position: '',
				exportData:{
					filename: 'EXPORT',
					// number: false,
					name: false,
					shifts: false,
					hours: false,
					firstname: false,
					hire_date: false,
					lastname: false,
					payrate: false,
					totalpay: false
				},
				exportvisible:{
					// number: false,
					name: false,
					shifts: false,
					hours: false,
					firstname: false,
					hire_date: false,
					lastname: false,
					payrate: false,
					totalpay: false
				}
			},
			    exportfd : [],
		}
	},
	created() {
		let vm = this
		vm.getPositions()
		vm.getReports()
	},
	methods: {
		maintenanceMode() {
			alert('still in progress')
		},

		getReports() {
			this.reports = [
				{
					description: 'By employee (summary)',
					type: 'summary-employee',
				},
				{
					description: 'By employee (detailed)',
					type: 'detailed-employee',
				},
				{
					description: 'By position (summary)',
					type: 'summary-position',
				},
				{
					description: 'By position (detailed)',
					type: 'detailed-position',
				},
			]
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
		getPositions() {
			axios.get('/api/positions').then(res => this.positions = res.data)
		},
		async export(){
			
			try {
			let exportdata = await axios.get(`/api/report/shifts?start=${moment(this.options.beginDate).format('YYYY-MM-DD')}&end=${moment(this.options.endDate).format('YYYY-MM-DD')}&position=${this.options.position}`)
					this.exportData = JSON.stringify(exportdata.data.data)
			}
			catch (e) {
				console.log('Error', e)
			}
			},
		async exportdata(data){
			let vm = this;
			try {
			let exportdata = await axios.get(`/export-report?s=${moment(vm.options.beginDate).format('YYYY-MM-DD')}&e=${moment(vm.options.endDate).format('YYYY-MM-DD')}&p=${vm.options.position}&include-pr-and-cost=${vm.options.isIncludePayRateAndCosts}&type=${data.type}`)
					this.exportData = exportdata
			}
			catch (e) {
				console.log('Error', e)
			}
			}, 
		ExportFile(){  
		let vm = this;
		vm.modal.exportData.beginDate = this.options.beginDate;
		vm.modal.exportData.endDate = this.options.endDate;
		vm.modal.exportData.isIncludePayRateAndCosts = this.options.isIncludePayRateAndCosts;
		vm.modal.exportData.position = this.options.position;
		if(vm.modal.exportData.position == ''){
			vm.modal.exportData.position = "All Positions";
		}
		vm.modal.exportData.filename =  (vm.modal.exportData.filename).concat('.CSV')
		if(vm.modal.exportData.data_type == "summary-employee"){
			vm.exportfd = [];
				axios
			.post('/api/report/exportfile/byemployee', Object.assign(vm.modal.exportData))
			.then(res => {
			vm.modal.export = false
		
			vm.$refs.exportFile.reset();
			vm.data = res.data.data
			vm.exportfd =vm.data;
			// open csv download
			setTimeout(() => {
				vm.$refs.myBtn.$el.click();
			}, 1500);
			})
			.catch(err => {
			 vm.$swal({
              icon: 'error',
              title: err
            })
			
			})
		}
		else if(vm.modal.exportData.data_type == "detailed-employee"){
			vm.exportfd = [];
				axios
			.post('/api/report/exportfile/byemployee', Object.assign(vm.modal.exportData))
			.then(res => {
			vm.modal.export = false
		
			vm.$refs.exportFile.reset();
			vm.data = res.data.data
			vm.exportfd = vm.data;
			// open csv download
			setTimeout(() => {
				vm.$refs.myBtn.$el.click();
			}, 1500);
			})
			.catch(err => {
			 vm.$swal({
              icon: 'error',
              title: err
            })
			
			})
		}
		else if(vm.modal.exportData.data_type == "summary-position"){
			vm.exportfd = [];
				axios.post('/api/report/exportfile/byposition', Object.assign(vm.modal.exportData))
			.then(res => {
			vm.modal.export = false
			vm.$refs.exportFile.reset();
			vm.exportfd = res.data.data;
			// open csv download
			setTimeout(() => {
				vm.$refs.myBtn.$el.click();
			}, 1500);
			})
			.catch(err => {
			 vm.$swal({
              icon: 'error',
              title: err
            })
			
			})
		}
		else if(vm.modal.exportData.data_type == "detailed-position"){
			vm.exportfd = [];
				axios.post('/api/report/exportfile/byposition', Object.assign(vm.modal.exportData))
			.then(res => {
			vm.modal.export = false
			vm.$refs.exportFile.reset();
		vm.exportfd = res.data.data;
			// open csv download
			setTimeout(() => {
				vm.$refs.myBtn.$el.click();
			}, 1500);
			})
			.catch(err => {
			 vm.$swal({
              icon: 'error',
              title: err
            })
			
			})
		}

		},
		openModal(modal, data) {
			let vm = this
			switch(modal) {
				case 'Print':
					if ( vm.options.beginDate == '' || vm.options.endDate == '' ) {
            vm.$swal({
              icon: 'error',
              title: 'Begin/End Date has been left empty, please fill-in to generate data.',
              // showConfirmButton: false,
              // timer: 1500
            })
			return false   
			}
					window.open(`/print-report?s=${moment(vm.options.beginDate).format('YYYY-MM-DD')}&e=${moment(vm.options.endDate).format('YYYY-MM-DD')}&p=${vm.options.position}&include-pr-and-cost=${vm.options.isIncludePayRateAndCosts}&type=${data.type}`, '', 'width=700,height=500')
					
					// save
					// axios.

					break
			case 'Export':
			if ( vm.options.beginDate == '' || vm.options.endDate == '' ) {
            vm.$swal({
              icon: 'error',
              title: 'Begin/End Date has been left empty, please fill-in to generate data.',
              // showConfirmButton: false,
              // timer: 1500
            })
			return false
			}
			vm.modal.exportData.data_type = data.type;
			vm.modal.export = true;
			vm.modal.exportData.filename = 'EXPORT';
			// vm.modal.exportData.number = false;
			vm.modal.exportData.name = false;
			vm.modal.exportData.position = false;
			vm.modal.exportData.shifts = false;
			vm.modal.exportData.hours = false;
			vm.modal.exportData.firstname = false;
			vm.modal.exportData.hire_date = false;
			vm.modal.exportData.lastname = false;
			vm.modal.exportData.payrate = false;
			vm.modal.exportData.totalpay = false;

			if(vm.modal.exportData.data_type == "summary-employee"){
			// vm.modal.exportvisible.number = true;
			vm.modal.exportvisible.name = true;
			vm.modal.exportvisible.position = false;
			vm.modal.exportvisible.shifts = true;
			vm.modal.exportvisible.hours = true;
			vm.modal.exportvisible.firstname = true;
			vm.modal.exportvisible.hire_date = false;
			vm.modal.exportvisible.lastname = true;
			vm.modal.exportvisible.payrate = true;
			vm.modal.exportvisible.totalpay = true;
			
				}
			else if(vm.modal.exportData.data_type == "detailed-employee"){
			// vm.modal.exportvisible.number = true;
			vm.modal.exportvisible.name = true;
			vm.modal.exportvisible.position = true;
			vm.modal.exportvisible.shifts = true;
			vm.modal.exportvisible.hours = true;
			vm.modal.exportvisible.firstname = true;
			vm.modal.exportvisible.hire_date = true;
			vm.modal.exportvisible.lastname = true;
			vm.modal.exportvisible.payrate = true;
			vm.modal.exportvisible.totalpay = true;
				}
				else if(vm.modal.exportData.data_type == "summary-position"){
			// vm.modal.exportvisible.number = false;
			vm.modal.exportvisible.name = false;
			vm.modal.exportvisible.position = true;
			vm.modal.exportvisible.shifts = true;
			vm.modal.exportvisible.hours = true;
			vm.modal.exportvisible.firstname = false;
			vm.modal.exportvisible.hire_date = false;
			vm.modal.exportvisible.lastname = false;
			vm.modal.exportvisible.payrate = false;
			vm.modal.exportvisible.totalpay = true;
				}
				else if(vm.modal.exportData.data_type == "detailed-position"){
			// vm.modal.exportvisible.number = true;
			vm.modal.exportvisible.name = true;
			vm.modal.exportvisible.position = true;
			vm.modal.exportvisible.shifts = true;
			vm.modal.exportvisible.hours = true;
			vm.modal.exportvisible.firstname = false;
			vm.modal.exportvisible.hire_date = false;
			vm.modal.exportvisible.lastname = false;
			vm.modal.exportvisible.payrate = true;
			vm.modal.exportvisible.totalpay = true;
				}

			break
				default: //
			}
		},
		getSearchedData() {
			let vm = this

 			// this will reset everything so the full record will be re-scanned again, LOL right?
 			// but later huehue, just use AJAX for uniformity
			vm.getReports()

			if (vm.search !== '') {
				let filteredData = vm.reports.filter( filt => filt.description.toLowerCase().includes( vm.search.toLowerCase() ))
				vm.reports = filteredData
			} else {
				vm.getReports()
			}
		},
	}
}

</script>
<style lang="scss" scoped>
	@import '../../sass/report';
</style>