<template>
  <div class="c-employee-index client-table">

    <Loader msg="Processing ..." v-model="isLoader" />
    <div class="px-4 pb-4 w-80" style="margin-right: 1vw; margin-left: 240px;">
		<div class="flex " style="justify-content: space-between;">
			<h1 class="mb-4 mt-4">Parking Tickets</h1>
		</div>

		<table class="w-full officer-logs-report-table">
			<thead>
			<tr>
			<th style="width:10%;" class="text-left">Client </th>
			<th style="width:10%;" class="text-left">Date/Time</th>
			<th style="width:34%;" class="text-left">Location </th>
			<th style="width:12%;" class="text-left">Vehicle Type</th>
			<th style="width:12%;" class="text-left">Make</th>
			<th style="width:12%;" class="text-left">Model</th>
			<th style="width:10%;" class="text-left">License Plate</th>
			<th style="width:10%;" class="text-left">Actions</th>
			
			</tr>
			</thead>
			
			<tbody >
			<template v-if="parkingdata.length!= 0">
			<tr v-for="(data) in parkingdata" :key="data.id">
			<!-- <tr> -->
		
				<td class="text-left"><span v-for="client in data.client" :key="client.id">{{client.clientname}} </span></td> 
				<td class="text-left">
					{{ data.created_at | moment("MM/DD/YYYY h:mm A") }}
				</td>
				<td class="text-left text-transform-capitalise location">
					{{data.parking_location}}
				</td>
				<td class="text-left">{{data.vechicletype ? data.vechicletype[0].vehicle_type_name : '-'}}</td>
				<td class="text-left">
					{{data.vechiclemake? data.vechiclemake[0].vehicle_make_name : '-'}}
				</td>
				<td class="text-left">
					{{data.vechiclemodel ? data.vechiclemodel[0].vehicle_model_name : '-'}}
				</td>
				<td class="text-left">{{data.licence_plate ? data.licence_plate : '-'}} </td> 
				<td class="text-left">
					<div class="flex" style="justify-content:flex-start;">
					<b-button class="m-2 action-button view mx-auto" @click="viewParking(data)"> <b-icon-list-task   />View </b-button>
					
					</div>
				</td>

			</tr>
			</template>
			<template v-else>
				No Records Found
			</template>
			</tbody>
		</table>

    </div>
	
   <b-modal centered  no-close-on-esc hide-header-close class="modal-add-new-employee" id="datepicker" title="Date picker" style="margin:0 auto;">
	   		
	   	
			<b-calendar
				selected-variant="success"
				today-variant="info"
				nav-button-variant="primary"
				v-model="modal.date"
			>
			</b-calendar>
		
			<template #modal-footer="{ }">
				<div class="text-center mt-2 mb-2" style="margin:0 auto;">
    				<button @click="sendDate()" class="text-white py-3 px-12 rounded-full  bg-custom-primary" type="submit">Set</button>
          		</div>
    		</template>
  </b-modal>
  <!-- Date Model -->
  <!-- View Parking -->
  	 <modal  v-model="modal.viewParkingModal" class="modal-add-new-employee modal-view-parking" size="md:w-6/12" title='View Parking Ticket'>
    	<div class=" px-4 mb-8 mt-8 w-12/12 flex">
			
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
					<div class="w-3/12 content-css border-right-0"> Date/Time:</div>
					<div class="w-9/12 content-css"> 
						{{ modal.viewparkingmodal.created_at | moment("MM/DD/YYYY h:mm A") }}
					</div>
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
				<img class="b-avatar-img-class" :src="this.currentpath+''+modal.viewparkingmodal.vehicle_image"/>
			</div>

    	</div>
        <div class="text-center mt-2 mb-2 flex" style="justify-content:center;">
		<b-button class="m-2 action-button edit"  @click="editReport(modal.viewparkingmodal)"> <b-icon-pencil-square   />Edit</b-button>
		<b-button  :class="modal.viewparkingmodal.status == 'approved' ? 'm-2 action-button approve-disable' : 'm-2 action-button approve'"   @click="approveParking(modal.viewparkingmodaldata)"> <b-icon-check-circle   />Approve</b-button>
		
        </div>
		</modal>
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
import Modal1 from './shared/Modal1'
import Modal from './shared/Modal'
import Loader from './shared/Loader'
import { BCalendar } from 'bootstrap-vue'
import { BFormDatepicker } from 'bootstrap-vue'
import datetime from 'vuejs-datetimepicker';
import Datepicker from 'vuejs-datepicker';
import VueTimepicker from 'vue2-timepicker'
import 'vue2-timepicker/dist/VueTimepicker.css'
import Header from './shared/Header.vue';
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
		vm.destroystyle();
  },
  async destroyed() {
	   let vm = this

  },
  watch :{
  },
	methods: {
	vehiclemake(){
		let vm = this;
			axios.get(`/api/vehicleMake`).then(res => {
          if(res.data.status != false){
			  vm.modal.vehicleMake = res.data.data;
          }
          else{
          vm.isLoader = false
          vm.$swal({
            icon: 'error',
            title: res.data.message,
            showConfirmButton: false,
            timer: 1500
          })
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
          vm.$swal({
            icon: 'error',
            title: res.data.message,
            showConfirmButton: false,
            timer: 1500
          })
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
          vm.$swal({
            icon: 'error',
            title: res.data.message,
            showConfirmButton: false,
            timer: 1500
          })
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
          vm.$swal({
            icon: 'error',
            title: res.data.message,
            showConfirmButton: false,
            timer: 1500
          })
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
							vm.$swal({
							icon: 'error',
							title:'in progress',
							showConfirmButton: false,
							timer: 1500
							})
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

	sendDate(){
		let vm = this;
      axios.post(`/api/filterreports`, {range_date : vm.modal.date, search: vm.searchKeyword}) 
          .then(res => {
            if(res.data.status != true){
               vm.$swal({
              icon: 'error',
              title: res.data.message,
              showConfirmButton: false,
              timer: 1500
            })
            //  vm.parkingdata = [];
      
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
          vm.$swal({
            icon: 'error',
            title: res.data.message,
            showConfirmButton: false,
            timer: 1500
          })
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
          vm.$swal({
            icon: 'error',
            title: res.data.message,
            showConfirmButton: false,
            timer: 1500
          })
          }
      
        })
		
	},

	approveParking(data){
		let vm = this;
		axios.post(`/api/approveparking/${data[0].id}`) 
          .then(res => {
            if(res.data.status != true){
               vm.$swal({
              icon: 'error',
              title: 'in progress',
              showConfirmButton: false,
              timer: 1500
            })
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
            vm.$swal({
              icon: 'error',
              title: 'in progress',
              showConfirmButton: false,
              timer: 1500
            })
         
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
			vm.modal.viewparkingmodaldata = data
            vm.modal.viewParkingModal = true;
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
	indexEmployee() {
      let vm = this

    //   vm.isLoader = true
      axios
        .get(`/api/allparking`)
        .then(res => {
          if(res.data.status != false){
              vm.parkingdata = res.data.data;
            //   vm.isLoader = false
          }
          else{
        //   vm.isLoader = false
          vm.$swal({
            icon: 'error',
            title: res.data.message,
            showConfirmButton: false,
            timer: 1500
          })
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
    border-radius: 10px;
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


.officer-logs-report-table{
  thead{
    tr{
      th{
         padding: 1px 0px 1px 25px;
      }
    }
  }
  tbody{
    tr{
      td.location{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
        padding: 1px 0px 1px 25px;
      }
      td{
        padding: 1px 0px 1px 25px;
		.action-button{
			margin-left: 0;
		}
      }
    }
  }
}

// action buttons
.action-button{
	&:focus{
		outline: none;
	}
	top: 420px;
	left: 1490px;
	border-radius: 10px;
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
	@import '../../sass/employees';
	// @import '../../sass/officerreport';
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
			border-radius: 10px;
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
			border-radius: 10px;
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
				border-radius: 10px;
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

</style>
