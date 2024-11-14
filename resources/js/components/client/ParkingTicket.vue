<template>
  <div class="c-employee-index client-table">
     
    <Loader msg="Processing ..." v-model="isLoader" />
    <div class="pr-4 pb-4 pl-0 w-full" >
      <div class="flex header my-3" style="justify-content: space-between;">
        <!-- <h1 class="mb-4">Report</h1> -->
        <div class="flex">
        <!-- <span class="date">{{ this.modal.date | moment('dddd, MMMM D, YYYY') }}</span> -->
         <!-- <b-button v-if="modal.date !== '' || dateRange.startDate !== null " class="reset-to-default ml-3 mt-2" @click="datetoreset()"> <b-icon-cloud-arrow-down   /> Reset To Default</b-button> -->
         <b-button style="background-color:#2C1977;border-radius: 5px;font-family: Poppins;font-weight: bold;" class="ml-0 mt-2" @click="datetolisten1()">Choose Date</b-button>
         <div class=" px-3 mt-2">
          <div class="relative">
          <input class="officer-report-search-report appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" placeholder="Search" v-model="searchKeyword"  @keyup="search()">
          <div class="border-none absolute inset-y-0 right-0 flex items-center px-2 text-custom-border rounded-r border-custom-border border-l-0">
            <font-awesome-icon icon="search" class="fill-current" />
          </div>
          </div>
			  </div>
        </div>
        <!-- <button class="add-blue-button" @click="addclient()">Add Property</button> -->
          <div class="w-full md:w-3/12 mt-2 mb-2 flex right-section">
            <div class="relative"  style=" width:50%; display: none;">
              <select id="location" @change="selectLocation()"  class="height-41 block appearance-none w-full py-1 px-4 pr-8 rounded-10 leading-tight focus:outline-none">
               <option value="">All Locations</option><option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AS">American Samoa</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District of Columbia</option><option value="FM">Micronesia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="GU">Guam</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MH">Marshall Islands</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="MP">Northern Mariana Islands</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PW">Palau</option><option value="PA">Pennsylvania</option><option value="PR">Puerto Rico</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VI">Virgin Islands</option><option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option><option value="AA">Armed Forces Americas</option><option value="AE">Armed Forces Europe, Canada, Africa and Middle East</option><option value="AP">Armed Forces Pacific</option>
                
              </select>
             
            
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700" style="display: none;">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
              </div>
             
            </div>
           <!-- <a class="action-button download" style="margin: auto 0 auto auto;"  @click="downloadReport()" > <b-icon-cloud-arrow-down   />Download Full Report(pdf)</a> -->
            
          </div>
      </div>
    <template v-if="parkingdata.length!= 0 && !allempty">
  <div  v-for="(ticket, index) in parkingdata" :key="'A'+ticket.id+ index">
     <!-- <template v-if="ticket.parking.length!= 0"> -->
			<div class="upper_section px-3 my-3 mt-5">
				<div class="section1 flex mb-1">
					<span class="propertyname_head">{{ticket.property.name}} #{{ticket.property.id}}</span>
				
        </div>

			</div>
		<div class="tabletopbackground">	
		<table class="w-full officer-logs-report-table tabletopposition">
			<thead>
			<tr >
			
			<th style="width: 10%;">Date </th>
			
			<th style="width: 10%;">Time </th>
			
			<th style="width: 12%;">Vehicle Type</th>
			
			<th style="width: 12%;">Make</th>
			
			<th style="width: 16%;">Model</th>
			
			<th style="width: 13%;">License Plate</th>
			
			<th>Color</th>
			
			<th >Actions</th>
			
			</tr>
			</thead>
			
			<tbody  style="width:81%;">
		
			<tr v-for="(data) in ticket.parking" :key="data.id">
			<!-- <tr> -->
		
				<!-- <td class="text-center"><span v-for="client in data.client" :key="client.id">{{client.clientname}} </span></td> -->
				<td  style="width:12%;">
					{{data.created_at | moment('MMM D, YYYY')}}
				</td>
				
				<td style="width:12%;">
					{{data.created_at | moment('hh:mm A')}}
				</td>
				
				<td style="width:12%;">{{data.vechicletype ? data.vechicletype[0].vehicle_type_name : '-'}}</td>
				
				<td style="width:12%;">
					{{data.vechiclemake? data.vechiclemake[0].vehicle_make_name : '-'}}
				</td>
				<td style="width:18%;">
					{{data.vechiclemodel ? data.vechiclemodel[0].vehicle_model_name : '-'}}
				</td>
				<td style="width:12%;">{{data.licence_plate ? data.licence_plate : '-'}} </td> 
				
				<td style="width:12%;">{{data.color ? data.color : '-'}} </td> 
				
				<td>
					<div class="flex" style="justify-content:flex-start;">
					<b-button style="background-color:#2C1977;border-radius: 5px; font-weight: bold;" class="m-2 action-button view mx-auto" @click="viewParking(data)"> <b-icon-list-task />View </b-button>
					
					</div>
				</td>
			</tr>
		
			</tbody>
		</table>
		</div>
     <!-- </template> -->
     
  </div>
     </template>
			<template v-else>
				No Records Found
			</template>

    </div>
	
   <b-modal centered  no-close-on-esc hide-header-close style="margin:0 auto; background-color:#AD9E58;" class="modal-add-new-employee" id="datepicker" title="Date picker">
	   		
	   	
			<b-calendar
				selected-variant="success"
				today-variant="info"
				nav-button-variant="primary"
				v-model="modal.date"
			>
			</b-calendar>
		
			<template #modal-footer="{ }">
				<div class="text-center mt-2 mb-2" style="margin:0 auto;">
    				<button @click="sendDate()"  style="border-radius: 5px;font-family: Poppins;
    				padding-top: 8px !important;padding-bottom: 8px !important;"class="text-white py-3 px-12 bg-custom-primary" type="submit">Set</button>
          		</div>
    		</template>
  </b-modal>
  <!-- Date Model -->
  <!-- View Parking -->
     <b-modal centered  size="lg"  no-close-on-esc  style="background-color:#AD9E58;margin:0 auto" class="modal-add-new-employee modal-view-parking"  id="viewParkingModal" title="Parking Violation Description" >
	   		<div class=" px-4 mb-8 mt-8 w-12/12 flex modal-view-parking">
			<div :class="modal.viewparkingmodal.vehicle_image != null ? 'left_section w-8/12' : 'left_section w-full'">
				<div class="content flex">
					<div class="w-3/12 content-css border-right-0"> Client:</div>
					<div class="w-9/12 content-css"> {{modal.viewparkingmodal.property ? modal.viewparkingmodal.client[0].clientname : '-'}} </div>
				</div>
				<div class="content flex">
					<div class="w-3/12 content-css border-right-0"> Location:</div>
					<div class="w-9/12 content-css"> {{modal.viewparkingmodal.parking_location ? modal.viewparkingmodal.parking_location : '-'}} </div>
				</div>
				<div class="content flex">
					<div class="w-3/12 content-css border-right-0"> Vehicle Type:</div>
					<div class="w-9/12 content-css"> {{modal.viewparkingmodal.vechicletype ? modal.viewparkingmodal.vechicletype[0].vehicle_type_name : '-'}} </div>
				</div>
				<div class="content flex">
					<div class="w-3/12 content-css border-right-0"> Make:</div>
					<div class="w-9/12 content-css"> {{modal.viewparkingmodal.vechiclemake ? modal.viewparkingmodal.vechiclemake[0].vehicle_make_name : '-'}} </div>
				</div>
				<div class="content flex">
					<div class="w-3/12 content-css border-right-0">Model:</div>
					<div class="w-9/12 content-css"> {{modal.viewparkingmodal.vechiclemodel ? modal.viewparkingmodal.vechiclemodel[0].vehicle_model_name : '-'}} </div>
				</div>
				<div class="content flex">
					<div class="w-3/12 content-css border-right-0">License Plate:</div>
					<div class="w-9/12 content-css"> {{modal.viewparkingmodal.licence_plate ? modal.viewparkingmodal.licence_plate : '-'}} </div>
				</div>
				<div class="content flex">
					<div class="w-3/12 content-css border-right-0">Color:</div>
					<div class="w-9/12 content-css"> {{modal.viewparkingmodal.color ? modal.viewparkingmodal.color : '-'}} </div>
				</div>
				<div class="content flex">
					<div class="w-3/12 content-css border-right-0">Parking violation description:</div>  
					<div class="w-9/12 content-css"> {{modal.viewparkingmodal.description ? modal.viewparkingmodal.description : '-'}} </div>
				</div>
			</div>
			<div class="right_section w-4/12 mt-1" v-if="modal.viewparkingmodal.vehicle_image != null">
				<img style="width: 230px;" class="b-avatar-img-class" :src="this.currentpath+''+modal.viewparkingmodal.vehicle_image"/>
			</div>

    	</div>
		
			<template #modal-footer="{ }">
				<div class="footer-section text-center mt-2 mb-2" style="margin:0 auto;width: 15%; justify-content:center;">
					<b-button class="add-blue-button ml-0 mt-2" @click="downloadTicket(modal.viewparkingmodal)" >Close</b-button>
    				<!-- <b-button class="m-2 action-button edit"  @click="editReport(modal.viewparkingmodal)"> <b-icon-pencil-square   />Edit</b-button>
					<b-button  :class="modal.viewparkingmodal.status == 'approved' ? 'm-2 action-button approve-disable' : 'm-2 action-button approve'"   @click="approveParking(modal.viewparkingmodaldata)"> <b-icon-check-circle   />Approve</b-button> -->
          		</div>
    		</template>
  </b-modal>
  
  <!-- View Report -->
  <!-- Edit Report -->
  	 <modal  v-model="modal.editParkingModal" class="modal-add-new-employee modal-edit-report" size="md:w-6/12" title='Edit Parking Ticket'>
    	
				
				
				
					<div class=" px-4 mb-4 mt-10 ml-10 mr-10">
					<div class="upper_section mb-3">
						<div class="heading">Client</div>
					
							
								<select id="status"  v-model="modal.editparkingmodal.client_id" class="mt-1 block w-full py-1 px-4 pr-8 rounded-10 height-41 leading-tight focus:outline-none" style="font: normal normal normal 18px Source Sans Pro; height: 50px;">
									<option v-for="client in modal.allclient" :key="client.id" :value="client.id" >{{client.clientname}}</option>
									
								</select>
						
					
						
					</div>
					<div class="lower_section">
						<div class="heading">Address</div>
						<div class="card"> 
							<input type="text" v-model="modal.editparkingmodal.parking_location" class="textinput" style="font: normal normal normal 18px Source Sans Pro;" />
						</div>
					</div>
					<div class="upper_section mb-3">
						<div class="heading">Vehicle Type</div>
						
						<select id="status"  v-model="modal.editparkingmodal.vehicle_type" class="mt-1 block w-full py-1 px-4 pr-8 rounded-10 height-41 leading-tight focus:outline-none" style="font: normal normal normal 18px Source Sans Pro; height: 50px;">
							<option v-for="type in modal.vehicleType" :key="type.id" :value="type.id" >{{type.vehicle_type_name}}</option>
							
						</select>
					</div>
					<div class="upper_section mb-3">
						<div class="heading">Make</div>
						
						<select id="status"  v-model="modal.editparkingmodal.vehicle_make" class="mt-1 block w-full py-1 px-4 pr-8 rounded-10 height-41 leading-tight focus:outline-none" style="font: normal normal normal 18px Source Sans Pro; height: 50px;">
							<option v-for="type in modal.vehicleMake" :key="type.id" :value="type.id" >{{type.vehicle_make_name}}</option>
							
						</select>
					</div>
					<div class="upper_section mb-3">
						<div class="heading">Model</div>
						
						<select id="status"  v-model="modal.editparkingmodal.vehicle_model" class="mt-1 block w-full py-1 px-4 pr-8 rounded-10 height-41 leading-tight focus:outline-none" style="font: normal normal normal 18px Source Sans Pro; height: 50px;">
							<option v-for="type in modal.vehicleModel" :key="type.id" :value="type.id" >{{type.vehicle_model_name}}</option>
							
						</select>
					</div>
					<div class="lower_section">
						<div class="heading">License Plate</div>
						<div class="card"> 
							<input type="text" v-model="modal.editparkingmodal.licence_plate" class="textinput" style="font: normal normal normal 18px Source Sans Pro;" />
						</div>
					</div>
					<div class="lower_section">
						<div class="heading">Color</div>
						<div class="card"> 
							<input type="text" v-model="modal.editparkingmodal.color" class="textinput" style="font: normal normal normal 18px Source Sans Pro;" />
						</div>
					</div>
					<div class="lower_section">
						<div class="heading">Description</div>
						<div class="card"> 
							<textarea v-model="modal.editparkingmodal.description" class="text" style="font: normal normal normal 18px Source Sans Pro;" />
						</div>
					</div>
					
					</div>
			
        
		<div class="text-center mt-2 mb-2 pb-6 flex" style="margin:0 auto; justify-content: center; width: 90%;">
    				<button @click="savereport()" class="mr-2 text-white py-3 px-12 rounded-full  bg-custom-primary" type="submit">Save</button>
    				<button @click="cancelreport()" class="mr-2 text-white py-3 px-12 rounded-full  bg-custom-purple-trans" type="submit">Cancel</button>
        </div>
		</modal>  
  <!-- Edit Report -->
     <b-modal size="lg"  no-close-on-esc hide-header-close class="modal-add-new-employee modal-edit-report" id="editparking" title="Edit Report" style="margin:0 auto;">
	  <div class=" px-4 mb-4 mt-4">
				<template v-if="modal.editparkingmodal.report_type_id == 1">
							<table class="w-full">
							<thead>
							<tr>
							<th style="width:5%;" class="text-center">Time</th>
							<th style="width:5%;" class="text-center"></th>
							<th style="width:90%;" class="text-left">Report</th>
							</tr>
							</thead>
							<tbody >
							<tr  v-for="reportdata in modal.editreportmodalreport" :key="reportdata.id">
								<td class="text-center" style="width:15%;">
									<!-- <datetime format="YYYY-MM-DD" width="300px" v-model="reportdata.date"></datetime>  -->
									{{reportdata.date}}
									 <b-form-datepicker  :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }" autocomplete="off" id="example-datepicker" v-model="reportdata.date" class="mb-2"></b-form-datepicker>
									</td>
								<td class="text-left">{{reportdata.time}}</td>
								<td class="text-left"><textarea class="text" v-model="reportdata.description" /></td>
							</tr>
							</tbody>
						</table>
				</template>
				<template v-else-if="modal.editparkingmodal.report_type_id == 2">
					<div class="upper_section">
						<div class="heading">Report type</div>
						<h3>Incident</h3>
					</div>
					<div class="lower_section">
						<div class="heading">Description</div>
						<div class="card"> 
							<textarea v-model="modal.editparkingmodal.description" class="text" />
						</div>
					</div>
				</template>
    	</div>
        
		
		<template #modal-footer="{ }">
			<div class="text-center mt-2 mb-2 flex" style="margin:0 auto; justify-content: flex-end; width: 90%;">
    				<button @click="savereport()" class="mr-2 text-white py-3 px-12 rounded-full  bg-custom-primary" type="submit">Save</button>
    				<button @click="cancelreport(modal.viewparkingmodal)" class="mr-2 text-white py-3 px-12 rounded-full  bg-custom-primary" type="submit">Cancel</button>
        	</div>
    	</template>
  </b-modal>

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
import Modal1 from '../shared/Modal1'
import Modal from '../shared/Modal'
import Loader from '../shared/Loader'
import { BCalendar } from 'bootstrap-vue'
import { BFormDatepicker } from 'bootstrap-vue'
import datetime from 'vuejs-datetimepicker';
import Datepicker from 'vuejs-datepicker';
import VueTimepicker from 'vue2-timepicker'
import 'vue2-timepicker/dist/VueTimepicker.css'
import Header from '../shared/Header.vue';
Vue.component('b-form-datepicker', BFormDatepicker)

export default {
	components: {
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
			allempty: false,
			currentpath : window.location.origin,
			isLoader: false,
			searchKeyword: '',
			searchTimer: null,
			parkingdata:[],
			mode: 'view',
			report_id: '',
			report_type_id: '',
			employee_id:'',
			timee:'',
			senddata:'',
			modal:{
				datemodal : false,
				date : '',
				viewparkingmodal: [],
				viewparkingmodaldata: [],
				viewParkingModal: false,
				allclient :[],
				vehicleMake:[],
				vehicleModel:[],
				vehicleType:[],
				editparkingmodal: [],
				editreportmodalreport: [],
				editParkingModal: false,
			}
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
//   async beforeCreate(){
// 	       [
//         "https://unpkg.com/bootstrap/dist/css/bootstrap.min.css",
//         "https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css",
//       ].forEach((route) => {
//         const link_el = document.createElement("link");
// 		link_el.setAttribute("href", route);
//         link_el.setAttribute("type", "stylesheet");
//         // link_el.setAttribute("id", "bootstrapcss");

//         document.head.appendChild(link_el);
//       });
//   },
  async created() {
    this.user_id = this.userid
    this.modal.date = moment(new Date()).format('YYYY-MM-DD');
	//   console.log(window.location, 'dzsgdfsjy');
	//   console.log(window.location.origin , 'hihi')
	//   console.log(this.currentpath , 'hihi111')
    let vm = this
	// vm.inject_material_fonts_and_icons_into_header();
    await vm.indexEmployee()
	vm.allclientdata();

	vm.vehiclemake();
	vm.vehiclemodel();
	vm.vehicletype();
	
  },
  async beforeDestroy(){
	  let vm = this
		// vm.destroystyle();
  },
  async destroyed() {
	   let vm = this

  },
  watch :{
  },
	methods: {
    //open calendar
    datetolisten1(){
      this.$bvModal.show('datepicker');
    },
	vehiclemake(){
		let vm = this;
			axios.get(`/api/vehicleMake`).then(res => {
          if(res.data.status != false){
			  vm.modal.vehicleMake = res.data.data;
          }
          else{
          vm.isLoader = false
          
          }
      
        })
	},
	vehiclemodel(){
		let vm = this;
			axios.get(`/api/vehicleModel`).then(res => {
          if(res.data.status != false){
			  vm.modal.vehicleModel = res.data.data;
          }
          else{
          vm.isLoader = false
         
          }
      
        })
	},
	vehicletype(){
		let vm = this;
			axios.get(`/api/vehicleType`).then(res => {
          if(res.data.status != false){
			  vm.modal.vehicleType = res.data.data;
          }
          else{
          vm.isLoader = false
         
          }
      
        })
	},
	allclientdata(){
		let vm = this;
			axios.get(`/api/client`).then(res => {
          if(res.data.status != false){
			  vm.modal.allclient = res.data;
          }
          else{
          vm.isLoader = false
          
          }
      
        })
	},
	customFormatter(date) {
      return moment(date).format('MM/DD/YYYY');
    },
	customFormatter1(time) {
      return moment(time).format('h:mm A');
    },
	savereport(){
			this.senddata = this.modal.editparkingmodal;
				let vm = this;
					axios.post(`/api/updateparking`, {
						parking_id: this.senddata.id,
						client_id: this.senddata.client_id,
						parking_location: this.senddata.parking_location,
						property_id: this.senddata.property_id,
						vehicle_make: this.senddata.vehicle_make,
						vehicle_model: this.senddata.vehicle_model,
						vehicle_type: this.senddata.vehicle_type,
						licence_plate : this.senddata.licence_plate,  
						color : this.senddata.color,
						description : this.senddata.description,
						vehicle_image : this.senddata.vehicle_image,




						}) 
						.then(res => {
							if(res.data.status != true){
							
							//  vm.parkingdata = [];
					
							}
							else{
							vm.$swal({
							icon: 'success',
							title: 'Data Saved Successfully!',
							showConfirmButton: false,
							timer: 1500
								})
							vm.modal.editParkingModal = false;
						vm.indexEmployee();
						
							}
						});
		

		
		
	},
	cancelreport(data){
		let vm = this;
		this.modal.editparkingmodal = [];
		// vm.$bvModal.hide('editparking');
		// vm.destroystyle();
		this.modal.editParkingModal = false;
		// viewParking(data);
		this.modal.viewParkingModal = true;
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

	sendDate(){
		let vm = this;
      axios.post(`/api/filtertickets`, {current_date : vm.modal.date, search: vm.searchKeyword, client_id: this.user_id}) 
          .then(res => {
            if(res.data.status != true){
               
			this.$bvModal.hide('datepicker');
            vm.parkingdata = [];
      
            }
            else{
			   vm.$swal({
              icon: 'success',
              title: 'Data Fetched Successfully!',
              showConfirmButton: false,
              timer: 1500
            	})
			this.$bvModal.hide('datepicker');
            vm.parkingdata = res.data.data
			// vm.parkingdata.map(x =>{
			// 	console.log('empty')
			// 	if(x.parking.length == 0){
			// 		return vm.allempty = true;
			// 	}
			// 	else{
			// 		return vm.allempty = false;
			// 	}
			// })
        
            }
		  });
           
	},
	viewParking(data){
	let vm = this;
	this.report_type_id =  data.id;
	// this.report_id =  data.report_id;
	// this.employee_id = data.employee[0].id;
	this.mode = 'view';
	let datatosent = data;
	axios	
        .get(`/api/viewparking/${this.report_type_id}`)
        .then(res => {
          if(res.data.status != false){
              datatosent = res.data.data;

			  this.openModal('viewparking', datatosent)
          }
          else{
          vm.isLoader = false
          
          }
      
        })
		
	},

	editReport(data){
	let vm = this;
	axios
        .post(`/api/editparking/${data.id}`)
        .then(res => {
          if(res.data.status != false){
			  this.openModal('editparking', res.data.data)
          }
          else{
          vm.isLoader = false
          
          }
      
        })
		
	},

	approveParking(data){
		let vm = this;
		axios.post(`/api/approveparking/${data[0].id}`) 
          .then(res => {
            if(res.data.status != true){
              
            }
            else{
			vm.modal.viewParkingModal = false;
			 vm.$swal({
              icon: 'success',
              title: 'Approved',
              showConfirmButton: false,
              timer: 1500
            })
			vm.indexEmployee();
            }
           
          })
          .catch(err => {
            
         
          })	

	},

	
	openDateModal(){
		this.openModal('datemodal');
	},
	async openModal(modal, data) {
		let vm = this
		switch(modal) {
        case 'viewparking':
			vm.modal.viewparkingmodal = data[0];
			vm.modal.viewparkingmodaldata = data;
			// vm.destroystyle();
			 this.$bvModal.show('viewParkingModal'); 
            // vm.modal.viewParkingModal = true;
          break
        case 'editparking':
			vm.modal.viewParkingModal = false;
			vm.modal.editparkingmodal = data[0];
			vm.modal.editreportmodalreport = data;
            vm.modal.editParkingModal = true;
          break
        case 'datemodal':
			vm.modal.datemodal = true;
			vm.focusMyElement();
			vm.inject_material_fonts_and_icons_into_header();
          break
		}
	},
	downloadTicket(data){
		 this.modal.viewParkingModal = false;
	},
	indexEmployee() {
      let vm = this

    //   vm.isLoader = true
      axios
        .post(`/api/parkingreport`, {client_id: this.user_id , current_date: this.modal.date})
        .then(res => {
          if(res.data.status != false){
              vm.parkingdata = res.data.data;
			  console.log( vm.parkingdata, ' vm.parkingdata')
			
            //   vm.isLoader = false
          }
          else{
        //   vm.isLoader = false
         
          }
      
        })

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
	 removeStyles(el) {
		el.removeAttribute('style')
		el.childNodes.forEach(x => {
			if(x.nodeType == 1) this.removeStyles(x)
		})
	},
},

}


</script>

<style lang="scss" scoped>
.propertyname_head{
  top: 272px;
left: 328px;
// width: 131px;
width: 80%;
width: auto;
margin-right: 10px;
height: 33px;
text-align: left;
font: normal normal 600 26px/33px Source Sans Pro;
letter-spacing: 0px;
color: #302369;
opacity: 1;
}
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
        // border-radius: 10px;
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
      // border-radius: 10px;
      padding: 5px;
      svg{
        font-size: 22px;
          margin: 0 5px;
      
      }
      }
    }
  }
table td:first-child {
    background: none !important;
}
.bg-custom-primary{
  &:focus{
    outline: none;
  }
}
.bg-custom-purple-trans{
	&:focus{
    outline: none;
  }
	background: transparent;
    color: #302369;
    border: 1px solid #302369;
    font-family: "Source Sans Pro", sans-serif;
    font-size: 18px;
    padding: 10px 39px;
    font-weight: 700;
}
.perimeter-time .data:last-child span {
  display: none;
}
.officer-report-search{
	top: 302px;
    left: 269px;
    width: 171px;
    height: 41px;
    background: #FFFFFF 0% 0% no-repeat padding-box;
    border: 1px solid #959595;
    // border-radius: 10px;
    opacity: 1;
    padding-right: 35px;
}
.officer-logs-report-table{
	font: normal normal normal 19px Source Sans Pro;
.active.text-center{
	color:#3B86FF;
}
.inactive.text-center{
	color: grey;
	// color:#3B86FF;
}

.client-table table .heading-sort svg {
    display: inline-block;
    font-size: 10px !important;
}
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
	font: normal normal normal 19px Source Sans Pro;
    padding: 3px 5px;
	svg{
		font-size: 16px !important;
    	margin: 5px 0px 5px 0px !important;
	}
}
.action-button.view{
	background: #3B86FF 0% 0% no-repeat padding-box;
	width: 80px;
	height: 33px;
}
.action-button.reject{
	background: #ffffff 0% 0% no-repeat padding-box;
	width: 80px;
	height: 33px;
	border: 1px solid #606060;
	color: #606060;
}
.action-button.edit{
	background: #C4AA33 0% 0% no-repeat padding-box;
	width: 80px;
	height: 33px;
}
	.footer-section{ 
		width: 30%;
	.add-blue-button{
		width: 100%;

		}
	}
#viewParkingModal{
	header{
		background: #302369;
    color: #ffffff;
	h5{
		text-align: center;
    width: 100%;
	}
	}
}
.modal-view-parking {
	.right_section{
		img{
			border: 1px solid black;
			height: 300px;
			width: 300px;
		}
	}
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
.modal-view-parking .action-button.edit{
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
.modal-view-parking .action-button.approve{
	background: #302369 0% 0% no-repeat padding-box;
    width: 125px;
    height: 45px;
    left: 1674px;
    font-family: "Source Sans Pro", sans-serif;
    padding: 10px 15px;
}
.modal-view-parking .action-button.approve-disable{
	background: #7e789a 0% 0% no-repeat padding-box;
	pointer-events: none;
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
	.modal-view-parking{
		table{
			tbody{
				tr{
					font: normal normal normal 16px Open Sans;
				}
			}
		}
    .upper_section{
        .section1{
            .reporttype{
                top: 0px;
                // left: 0px;
                // width: 125px;
                // height: 19px;
                text-align: left;
                font: normal normal normal 19px Montserrat;
                letter-spacing: 0px;
                color: #000000;
				font-weight: 700;
            }
            .reportypevalue{
                span{
                    // top: 0px;
                    // left: 106px;
                    // width: 53px;
                    // height: 19px;
                    text-align: left;
                    font: normal normal normal 19px Montserrat;
                    letter-spacing: 0px;
                    color: #302369;
					font-weight: 500;
                }
            }
        }
    }
		.lower_section{
		.heading{
			text-align: left;
			font: normal normal normal 22px Source Sans Pro;
			letter-spacing: 0px;
			color: #2A2A2A;
			opacity: 1;
			font-weight: 500;
		}
		.card{
			min-height: 100px;
			background: #FFFFFF 0% 0% no-repeat padding-box;
			box-shadow: 2px 4px 20px #00000026;
			// border-radius: 10px;
			opacity: 1;
			padding: 15px 15px;
			margin-bottom: 15px;
			.text{
				top: 378px;
				left: 592px;
				width: 701px;
				height: 87px;
				text-align: left;
				font: normal normal normal 20px Open Sans;
				letter-spacing: 0px;
				color: #2A2A2A;
				opacity: 1;
			}
		}
	}
}
.modal-edit-report{
	table{
		tbody{
			tr{
				font: normal normal normal 16px Open Sans;
				height: auto;
			}
		}
		.text{
			background: #FFFFFF 0% 0% no-repeat padding-box;
			box-shadow: 2px 4px 20px #00000026;
			// border-radius: 10px;
			opacity: 1;
			// padding: 15px 8px;
			// margin-bottom: 15px;
			margin: 15px 15px 15px 0px;
			width: 90%;
			min-height: 90px;
			border: none;
    		overflow: hidden;
			&:focus{
				outline: none;
			}
		}
	}
	.upper_section{
		.heading{
			text-align: left;
			font: normal normal normal 22px Source Sans Pro;
			letter-spacing: 0px;
			color: #2A2A2A;
			opacity: 1;
			font-weight: 500;
		}

	}
	.lower_section{
		.heading{
			text-align: left;
			font: normal normal normal 22px Source Sans Pro;
			letter-spacing: 0px;
			color: #2A2A2A;
			opacity: 1;
			font-weight: 500;
		}
		.card{
			.text{
				background: #FFFFFF 0% 0% no-repeat padding-box;
				box-shadow: 2px 4px 20px #00000026;
				// border-radius: 10px;
				opacity: 1;
				padding: 15px 15px;
				margin-bottom: 15px;
				margin: 15px 0px;
				width:100%;
				min-height: 115px;
			}
			.textinput{
				background: #FFFFFF 0% 0% no-repeat padding-box;
				box-shadow: 2px 4px 20px #00000026;
				border-radius: 10px;
				opacity: 1;
				padding: 15px 15px;
				margin-bottom: 15px;
				margin: 15px 0px;
				width:100%;
				
			}
		}
	}
}

.modal-view-parking {
	.right_section{
		img{
			border: 1px solid black;
			height: 300px;
			width: 300px;
		}
	}
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
.modal-view-parking .action-button.edit{
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
.modal-view-parking .action-button.approve{
	background: #302369 0% 0% no-repeat padding-box;
    width: 125px;
    height: 45px;
    left: 1674px;
    font-family: "Source Sans Pro", sans-serif;
    padding: 10px 15px;
}
.modal-view-parking .action-button.approve-disable{
	background: #7e789a 0% 0% no-repeat padding-box;
	pointer-events: none;
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
	@import '../../../sass/employees';

</style>
