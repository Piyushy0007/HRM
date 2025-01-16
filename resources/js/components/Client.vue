<template>
  <div class="c-employee-index client-table">

    <Loader msg="Processing ..." v-model="isLoader" />
    <div style="margin-left: 240px;">
      <div class="p-4 max-w-7xl mx-auto h-screen flex flex-col ">
        
        <div class="flex justify-between items-center-">
          <p class="text-3xl">Community</p>
          <button class="add-blue-button" @click="openClientModal()">Create Community</button>
        </div>
        <div class="flex flex-col bg-white p-4 shadow-md rounded-lg mb-4">
          <div class="flex flex-col md:flex-row w-full gap-2 items-stretch ">
            <div class="w-full md:w-1/2 flex flex-col md:flex-row mb-4">
              <div class=" w-full md:w-1/2 pr-3 mb-4">
                <div class="relative">
                  <select id="location" @change="selectLocation()"  class="w-full border border-gray-300 rounded px-2 py-2 focus:outline-none">
                    <option value="">All Locations</option>
                    <option v-for="loc,index in alllocation" :key="index+loc" >{{loc}}</option>
                  </select>
                </div>
              </div>
              <div class=" w-full md:w-1/2 pr-3 mb-4">
                <div class="relative">
                  <select id="status" @change="selectStatus()" class="w-full border border-gray-300 rounded px-2 py-2 focus:outline-none">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>                    
                  </select>
                </div>
              </div>
            </div>
            <div class="w-full md:w-1/2">
              <div class="flex items-center flex-grow relative">
                <input 
                  class="w-full rounded pl-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" 
                  style="height: 38px; border: 1px solid #d1d5db;"
                  type="text" 
                  placeholder="Search" 
                  v-model="searchKeyword" @keyup="search()">
                <div>
                  <font-awesome-icon
                  icon="search"
                  class="absolute right-0 mr-2 transform -translate-y-1/2 top-1/2 cursor-pointer text-gray-500"
                  style="font-size: 20px"
                  />
                </div>
              </div>
            </div>
          </div>
            <!-- <div class="w-full md:w-1/2">
              <div class="w-50 px-3  md:w-1/2 mt-4 mb-4" style="float:right;">
                
              </div>
            </div> -->
            <div class="flex-grow overflow-auto">
              <table class="w-full table-auto">
                <thead>
                  <tr class="text-left border-b">
                   <th class="py-3 px-4 text-blue-600 heading-sort ">Community Name <b-icon-arrow-down-up @click="namesort()" /> </th>
                   <th class="py-3 px-4 text-blue-600">Location</th>
                   <th class="py-3 px-4 text-blue-600 heading-sort">Email <b-icon-arrow-down-up @click="emailsort()" /> </th>
                   <th class="py-3 px-4 text-blue-600">Contact Number</th>
                   <!-- <th class="text-center">Username</th> -->
                   <!-- <th class="text-center">Date Registered</th> -->
                   <th class="py-3 px-4 text-blue-600">Status</th>
                   <th class="text-left"></th>
                   <th class="text-left"></th>
                   <th class="text-left"></th>
                  </tr>
                </thead>                
                <tbody >
                  <template v-if="clientdata.length!= 0">
                  <tr v-for="(data, index) in clientdata" :key="'A'+index+data.id" class="border-b">                  
                    <td class="py-3 px-4 text-transform-capitalise">{{ data.clientname || '-'}} </td>
                    <td class="py-3 px-4">{{data.address ? data.address : '-'}}</td>
                    <td class="text-left py-3 px-4">{{ data.email }}</td>
                    <td class="text-left py-3 px-4">{{ data.phone || '-' }}</td>
                    <!-- <td class="text-center">{{ data.name }}</td> -->
                    <!-- <td class="text-center">{{data.created_at  | moment('MM/DD/YYYY')}}</td> -->
                    <td :class="data.status == 'active' ? 'active text-left' : 'inactive text-center py-3 px-4'" >{{ data.status }}</td>
                    <td class="text-left py-3 px-4"  @click='resetPassword(data)' ><b-button class="m-3" variant="success"> <font-awesome-icon icon="lock"  class="text-gray-500 font-size-24"  /></b-button></td>
                    <td class="text-left py-3 px-4"  @click='openView(data)' ><b-button class="m-3" variant="success"> <font-awesome-icon icon="eye"  class="text-gray-500 font-size-24"  /></b-button></td>
                    <td class="text-left" @click='deleteView(data)' ><b-button class="m-3" variant="success"> <font-awesome-icon :icon="['far', 'trash-alt']" class="text-gray-500 font-size-24" /></b-button></td>
                  </tr>
                   </template>
                  <template v-else>
                    No Records Found
                  </template>
                </tbody>
              </table>
            </div>
          </div>
      </div>
    </div>

    <modal v-model="modal.createclient" class="modal-add-new-employee" size="md:w-5/12" title="Create Community">
      <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(storeEmployee('create'))" ref="frmcreateclient" novalidate>
    			<!-- ================================= Contact ================================= -->
    		  <div class=" px-4 mb-4 mt-4">
            <ValidationProvider rules="required" v-slot="v">
      				<div class=" md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Name of Community<span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-4/4">
        					<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.createClient.clientname">
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>
            <!-- <ValidationProvider rules="required" v-slot="v">
      				<div class=" md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Company Name<span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-4/4">
        					<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.createClient.company">
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider> -->
            <ValidationProvider rules="required" v-slot="v" >
      				<div class=" md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Address<span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-4/4">
        					<input @change="addAddress()" id="pac-input" class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" >
        					<!-- <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="hidden" v-model="modal.createClient.address" > -->
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-3/4">
                  <small class="text-red-600" id="addressRemoveError">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>
            <div id="map"></div>
            <ValidationProvider rules="required" v-slot="v">
      				<div class=" md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Name of Contact<span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-4/4">
        					<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.createClient.contactname">
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>
            <ValidationProvider rules="required" v-slot="v">
      				<div class="md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Phone Number<span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-4/4">
      						<input id="textbox" class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.createClient.phone" @input="acceptNumber">
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
              
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>
             <ValidationProvider rules="required" v-slot="v">
      				<div class=" md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Email<span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-4/4">
      						<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="email" v-model="modal.createClient.email">
      					</div>
      				</div>

              <div class="md:flex md:items-center mb-1">
              
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>
            <ValidationProvider rules="required" v-slot="v">
              <div class=" md:items-center">
                <div class="md:w-4/4">
                  <label class="block mb-1 md:mb-0 pr-4">Zipcode<span class="req_form_fields">*</span></label>
                </div>
                <div class="md:w-4/4">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.createClient.zip">
                </div>
              </div>

              <div class="md:flex md:items-center mb-1">
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>
              <div class=" md:items-center">
                <div class="md:w-4/4">
                  <label class="block mb-1 md:mb-0 pr-4">Police Email</label>
                </div>
                <div class="md:w-4/4">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="email" v-model="modal.createClient.police_email">
                </div>
              </div>
      				<div class=" md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Website</label>
      					</div>
      					<div class="md:w-4/4">
      						<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="email" v-model="modal.createClient.website">
      					</div>
      				</div>
             
      				<div class=" md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Job Title</label>
      					</div>
      					<div class="md:w-4/4">
        					<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.createClient.job_title">
      					</div>
      				</div>
            </div>
    		  <!-- ================================= ./Contact ================================= -->
          <div class="text-center mt-2 mb-2">
    				<button class="text-white py-3 px-12 rounded-full  bg-custom-primary" type="submit">Save</button>
          </div>
        </form>
      </ValidationObserver>
		</modal>
      <modal v-model="modal.editclient" class="modal-add-new-employee" size="md:w-5/12" title="Edit Community">
      <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(storeEmployee('edit'))" ref="frmeditclient" novalidate>


    			<!-- ================================= Contact ================================= -->
    		  <div class=" px-4 mb-4 mt-4">
            <ValidationProvider rules="required" v-slot="v">
      				<div class=" md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Name of Community<span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-4/4">
        					<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.clientname">
      					  	<input type="hidden" v-model="modal.editClient.id">
                </div>
      				</div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>
            <ValidationProvider rules="required" v-slot="v">
      				<div class=" md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Company Name<span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-4/4">
        					<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.company">
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>
            <ValidationProvider rules="required" v-slot="v">
      				<div class=" md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Address<span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-4/4">
        					<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.address">
        				<label>{{modal.editClient.state}}</label>
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>

            <ValidationProvider rules="required" v-slot="v">
      				<div class=" md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Name of contact<span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-4/4">
        					<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.contactname">
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
             
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>

            <ValidationProvider rules="required" v-slot="v">
      				<div class="md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Phone Number<span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-4/4">
      						<input id="textbox" class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.phone" @input="acceptNumber">
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
              
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>

             <ValidationProvider rules="required" v-slot="v">
      				<div class=" md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Email<span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-4/4">
      						<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="email" v-model="modal.editClient.email">
      					</div>
      				</div>

              <div class="md:flex md:items-center mb-1">
              
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>


            
             <ValidationProvider rules="required" v-slot="v">
      				<div class=" md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Status(Active/Inactive)<span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-4/4">
                 <b-form-checkbox
                  class="switch-check"
                  size="lg"
                  v-model="modal.editClient.switch"
                  switch
                  button-variant="info"
                  name='switch'
                  checked
                >
              </b-form-checkbox>
      						<!-- <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="email" v-model="modal.editClient.email"> -->
      					</div>
      				</div>

              <div class="md:flex md:items-center mb-1">
              
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>
           
           







    		  </div>
    		  <!-- ================================= ./Contact ================================= -->
          <div class="text-center mt-2 mb-2">
    				<button class="text-white py-3 px-12 rounded-full  bg-custom-primary" type="submit">Save</button>
          </div>

    		
        </form>
      </ValidationObserver>
		</modal>

  </div>
</template>
<style lang="scss" scoped>
.client-table-list {
  thead{
    tr{
      th{
         padding: 1px 0px 1px 25px;
      }
    }
  }
  tbody{
    tr{
      td{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
        padding: 1px 0px 1px 25px;
      }
    }
  }
}
table td[data-v-87947714]:first-child {
    background: none;
}
</style>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.rawgit.com/bjornharrtell/jsts/gh-pages/1.4.0/jsts.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfX422PdA9Yy6GOf4HeRBRTtfoz-AGQdU&callback=initMap&libraries=drawing,places&v=weekly"></script>
<script src="https://maps.google.com/maps/api/js?libraries=places,drawing&key=AIzaSyDfX422PdA9Yy6GOf4HeRBRTtfoz-AGQdU"></script>
<script src="https://cdn.rawgit.com/bjornharrtell/jsts/gh-pages/1.4.0/jsts.min.js"></script>
<script>

import Modal from './shared/Modal'
import Loader from './shared/Loader'
import axios from 'axios'
import moment from 'moment'
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import draggable from 'vuedraggable'
// import { BModal, VBModal } from 'bootstrap-vue'
import { BButton } from 'bootstrap-vue'

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
    BButton
	},
	data() {
		return {
      headers : {
        'Accept': `application/json`,
        'Authorization': `Bearer `+ JSON.parse(localStorage.getItem('accesstoken'))
      },
      nameSort: false,
      emailSort: false,
      country: '',
      region: '',
      clientdata: [],
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
      activeItem: 'edit',

			modal: {
        createclient: false,
        createClient:{
          client_image: 'abc',
          phone:'',
        },
        editclient: false,
        editClient:{},
        SendMessageToAll:{
        id: [],
        subject: '',
        message: ''
        },
        SignInSuccess : [],
				addNewEmployee: false,
        editEmployee: false,
        addEditPositions: false,
       
        SignIn: false,
        BulkEditEmp: false,
        SignInSent: false,
      
        SendReminders: false,
        SendReminderSuccess: false,
        SendMessage: false,

        editDeletePositions: false,

        showEditPosition: false,
        reqEditPositionDisplay: '',
        reqEditPosition: '',

     
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
      },
      input:'',
      searchBox:'',
      alllocation:[]
		}
	},

  computed: {
    TotalClient() {
      return this.clientdata
    },
    modalEmployeeName() {
      return this.modal.reqEditEmployee.firstname + ' ' + this.modal.reqEditEmployee.lastname
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
    await vm.allLocationFetch()
  },
  watch :{
    TotalClient:{
      handler() {
        console.log(this.clientdata, 'watch data')
      },
       deep: true,
      immediate: true
    }
  },
	methods: {
    allLocationFetch(){
      let vm = this
        axios
        .get('/api/aclientLocation')
        .then(res => {
          if(res.data.status){
            vm.alllocation = res.data.data
          }
          else{
             vm.alllocation =[]
          }
        })
        .catch(err => {
         console.log(err.response.data)
        })
    },
    selectLocation() {
    var x = document.getElementById("location").value;
     var y = document.getElementById("status").value;
    let vm = this;
        axios
          .post(`/api/filterclient`, {location: x, status: y ,search: vm.searchKeyword}, { headers: this.headers}) 
          .then(res => {
            if(res.data.status != true){
               vm.$swal({
              icon: 'error',
              title: res.data.message,
              showConfirmButton: false,
              timer: 1500
            })
             vm.clientdata = [];
            }
            else{
            vm.clientdata = res.data.data
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
    selectStatus() {
    var x = document.getElementById("location").value;
    var y = document.getElementById("status").value;
    let vm = this;  
    axios.post(`/api/filterclient`, {location: x, status: y ,search: vm.searchKeyword}, { headers: this.headers}) 
     .then(res => {
       if(res.data.status){
          // vm.$swal({
          //   closeOnClickOutside: false,
          //   icon: 'success',
          //   title: res.data.message,
          //   showConfirmButton: false,
          //   timer: 5000,
          // })
      
          vm.clientdata = res.data.data
       }
       else{
         vm.clientdata = [];
       }
        
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 3000
          })
        
        })
        },
      /**
     * Search for 
     * @return {[type]} [description]
     */
    search() {
      let vm = this
        var x = document.getElementById("location").value;
        var y = document.getElementById("status").value;
      // if (vm.searchKeyword == " ") {
      //  vm.indexEmployee();
      // }
      if (vm.searchTimer ) {
        clearTimeout(vm.searchTimer)
        vm.searchTimer = null
      }
      vm.searchTimer = setTimeout(() => {
        // axios.post(`/api/clientsearch`, {search: vm.searchKeyword}) 
      axios.post(`/api/filterclient`, {location: x, status: y ,search: vm.searchKeyword}, { headers: this.headers}) 
          .then(res => {
            if(res.data.status != true){
            //    vm.$swal({
            //   icon: 'error',
            //   title: res.data.message,
            //   showConfirmButton: false,
            //   timer: 1500
            // })
             vm.clientdata = [];
      
            }
            else{
              //removed as said by vlient
          //   vm.$swal({
          //   closeOnClickOutside: false,
          //   icon: 'success',
          //   title: res.data.message,
          //   showConfirmButton: false,
          //   timer: 5000,
          // })
      
            vm.clientdata = res.data.data
        
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
      }, 500)
    },
    openClientModal(){
       this.openModal('createClientModal');
    },
    addclient(){
      this.$router.push('/client/clientadd')
    },
    openView(data){
      // this.router.push({ name: 'client-view', params: { id: id } })
      this.$router.push(`/client/properties/${data.id}`) 
      //  this.openModal('editClientModal', data);

    },
    
   async resetPassword(data){
      let vm = this;
    try {
    if (confirm(`Are you sure you want to reset password of this client : ${data.clientname} ?  `)) {
     await axios.post(`/api/clientreset`, { headers: this.header , email: data.email}).then(res => {
          vm.$swal({
            closeOnClickOutside: false,
            icon: 'success',
            title: 'Password Reset Successful',
            showConfirmButton: false,
            timer: 5000,
          })
          vm.isLoader = false
          vm.indexEmployee();
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
      }
      } catch (e) {
        console.log('error', e)
      }
    },
    
    async deleteView(data){
    let vm = this;
    try {
    if (confirm(`Are you sure you want to cancel this client : ${data.clientname} - ${data.address}?  `)) {
     await axios.get(`/api/Delete/${data.id}`, { headers: this.headers}).then(res => {
          vm.$swal({
            closeOnClickOutside: false,
            icon: 'success',
            title: res.data.message,
            showConfirmButton: false,
            timer: 5000,
          })
          vm.isLoader = false
          vm.indexEmployee();
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
      }
      } catch (e) {
        console.log('error', e)
      }

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
        this.$alert('First click the checkboxes to the left of each name to select employees.' );
      }
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
         this.$alert('Selected employees copied to the clipboard so you can paste in another software system.');
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
          message: `Are you sure you want to send sign in instructions to ${namelength} Employees `,
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
    acceptNumber() {
      let y = this.modal.createClient.phone;
     if(this.isnumber(y)){
        	var x = y.toString().replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
      this.modal.createClient.phone = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
      }
      else{
        alert('invalid')
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
    initAutocomplete() {
      let vm = this;
    const map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: -33.8688, lng: 151.2195 },
    zoom: 13,
    mapTypeId: "roadmap",
  });
    // Create the search box and link it to the UI element.
    vm.input = document.getElementById("pac-input");
    document.getElementById("pac-input").value = '' ;
    document.getElementById("pac-input").placeholder = "";
    vm.searchBox = new google.maps.places.SearchBox(vm.input);
    // Bias the SearchBox results towards current map's viewport.
    map.addListener("bounds_changed", () => {
      vm.searchBox.setBounds(map.getBounds());
    });

  },



		/**
		 * Open modal
		 * 
		 * @param  String modal
		 */
		async openModal(modal, data) {
			let vm = this
			switch(modal) {
        case 'createClientModal':
            // this.inject_material_fonts_and_icons_into_header();
         
        
            vm.modal.createClient = {}
        
            vm.$refs.frmcreateclient.reset();
          
            vm.modal.createclient = true;
            vm.initAutocomplete();
          break
        case 'editClientModal':
          // this.inject_material_fonts_and_icons_into_header();
          vm.modal.editClient = data
          vm.modal.editclient = true;
         
         
          break
				// case 'AddNewEmployee':
				// 	vm.modal.addNewEmployee = true
        //   vm.modal.addEmployee = {}
        //   vm.$refs.frmAddEmployee.reset()
        //   vm.modal.addEmployee.priority_group = 1
        //   vm.indexPosition('modal-position')
				// 	break
        // case 'EditEmployee':
        //   vm.modal.editEmployee = true

        //   // Show the prev/next button
        //   vm.modal.getEmployeeRecord.prev.show = true
        //   vm.modal.getEmployeeRecord.next.show = true

        //   vm.isLoader = true

        //   // This will filter the arrow if it already meet it's first/last record
        //   let filterPrevNextBtn = await axios.get(`/api/employee/${data.employee.id}/prevnextrecord`)
        //   vm.modal.getEmployeeRecord.prev.show = (filterPrevNextBtn.data.prev == null)?false:true
        //   vm.modal.getEmployeeRecord.next.show = (filterPrevNextBtn.data.next == null)?false:true

        //   // Get the employee's position(s)
        //   let fetchEmployeePositions = await axios.get(`/api/employee/${data.employee.id}/positions`)
        //   // Map employee's position to checkbox
        //   let arrPositionIds = []
        //   fetchEmployeePositions.data.forEach(val => arrPositionIds.push(val.position_id))
        //   vm.modal.selectedPositions = arrPositionIds


        //   vm.modal.fullname = `${data.employee.firstname} ${data.employee.lastname}`

        //   vm.modal.reqEditEmployee = {
        //     id: data.employee.id,
        //     firstname: data.employee.firstname,
        //     lastname: data.employee.lastname,
        //     email: data.employee.email,
        //     phone: data.employee.phone,
        //     phone2: data.employee.phone2,
        //     mobile: data.employee.mobile,
        //     employee_no: data.employee.employee_no,
        //     address: data.employee.address,
        //     address2: data.employee.address2,
        //     city: data.employee.city,
        //     state: data.employee.state,
        //     zip: data.employee.zip,

        //     max_weekly_hours: data.employee.max_weekly_hours,
        //     max_weekly_days: data.employee.max_weekly_days,
        //     max_day_hours: data.employee.max_day_hours,
        //     max_day_shifts: data.employee.max_day_shifts,

        //     pay_rate: data.employee.pay_rate,
        //     hired_date: data.employee.hired_date,
        //     priority_group: data.employee.priority_group,
        //     // position: fetchEmployeePositions.data,
        //   }



        //   vm.indexPosition('modal-position')
        //   vm.isLoader = false
        //   break
        // case 'AddNewPosition':
        //   // vm.modal.addNewEmployee = false
        //   vm.modal.addEditPositions = true
        //   break
				// case 'AddEditPositions':
				// 	vm.modal.addEditPositions = true
        //   vm.indexPosition('modal-position')
        //   vm.trashedPosition()
				// 	break
				// case 'SignIn':
        //   vm.modal.SignIn = true
				// 	break
				// case 'BulkEditEmp':
        //   vm.modal.BulkEditEmp = true
				// 	break
				// case 'SignInSent':
				// 	vm.modal.SignIn = false
				// 	vm.modal.SignInSent = true
				// 	break
        // case 'EditDeletePositions':
        //   vm.modal.addEditPositions = false 
        //   vm.modal.editDeletePositions = true
        //   break
        // case 'EditPosition':
        //   vm.modal.showEditPosition = true
        //   vm.modal.reqEditPositionDisplay = data.position
        //   vm.modal.reqEditPosition = {
        //     id: data.id,
        //     position: data.position
        //   }
        //   break
			}
		},

    /**
     * List all Client
     */
    indexEmployee(position='') {
      let vm = this

      vm.isLoader = true
      axios
        .get(`/api/client`)
        .then(res => {
        vm.clientdata = res.data;
        // vm.clientdata = res.data.slice().sort(function(a, b){
        //   return (a.clientname > b.clientname) ? 1 : -1;
        // });
          vm.isLoader = false
        })
    },
    namesort(){
      let vm = this;
      if(!this.nameSort){
        vm.clientdata = vm.clientdata .slice().sort(function(a, b){
          return (a.clientname < b.clientname) ? 1 : -1;
        });
         this.nameSort = !this.nameSort
      }
      else{
         vm.clientdata = vm.clientdata .slice().sort(function(a, b){
          return (a.clientname > b.clientname) ? 1 : -1;
        });
         this.nameSort = !this.nameSort
      }
    },
    emailsort(){
            let vm = this;
      if(!this.emailSort){
        vm.clientdata = vm.clientdata .slice().sort(function(a, b){
          return (a.email < b.email) ? 1 : -1;
        });
         this.emailSort = !this.emailSort
      }
      else{
         vm.clientdata = vm.clientdata .slice().sort(function(a, b){
          return (a.email > b.email) ? 1 : -1;
        });
         this.emailSort = !this.emailSort
      }
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
          vm.index.positions = position.data
          break
        case 'modal-position':
          vm.modal.positions = position.data
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
      await axios.post('/api/employee/send-signin-instruction-to-all').then(res => {
                      vm.modal.SignInSuccess = res.data;
                      vm.sentIns();
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
                      vm.$alert('Sign in Instructions have been sent to selected employees!');
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
    addAddress(){
      let vm = this;
       var nameArr , country, city , combine;
         vm.modal.createClient.address = document.getElementById('pac-input').value
         if(vm.modal.createClient.address){
             if(/[,\-]/.test(vm.modal.createClient.address)){
                nameArr = vm.modal.createClient.address.split(',');
                country = nameArr[nameArr.length - 1]
                city = nameArr[nameArr.length - 2]
                combine = city +',' +country
              }
              else{
                combine = vm.modal.createClient.address
                }
                  document.getElementById('addressRemoveError').style.display = 'none';
         }else{
             document.getElementById('addressRemoveError').style.display = 'block';
         }
      
       vm.modal.createClient.state = combine;
      
       
    },

    storeEmployee(value) {
      let vm = this

      vm.isLoader = true
      if(value == 'edit'){

      
      axios
        .post('/api/updateclient', Object.assign(vm.modal.editClient))
        .then(res => {
          vm.indexEmployee()
          // vm.modal.createClient = {}
          vm.$swal({
            icon: 'success',
            title: 'Successfully Added!',
            showConfirmButton: false,
            timer: 1500
          })
          vm.modal.createclient = false
          vm.isLoader = false
          vm.$refs.frmcreateclient.reset();
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
      }
      else if(value == 'create'){
        var nameArr , country, city , combine;
      vm.modal.createClient.address = document.getElementById('pac-input').value
      if(/[,\-]/.test(vm.modal.createClient.address)){
         nameArr = vm.modal.createClient.address.split(',');
         country = nameArr[nameArr.length - 1]
         city = nameArr[nameArr.length - 2]
         combine = city +',' +country
      }
      else{
        combine = vm.modal.createClient.address
      }
       vm.modal.createClient.state = combine;
      if ( vm.modal.createClient.clientname == ''  || vm.modal.createClient.address == '' || vm.modal.createClient.contactname == ''  || vm.modal.createClient.phone == ''  || vm.modal.createClient.email == '' ) {
        vm.isLoader = false
        vm.$swal({
          icon: 'error',
          title: 'Fill Required Fields',
          showConfirmButton: false,
          timer: 1500
        })
        return false
      }
      else {
        axios
        .post('/api/addclient', Object.assign(vm.modal.createClient))
        .then(res => {
         if(res.data.status == true){
          vm.modal.createClient = {}
          vm.$swal({
            icon: 'success',
            title: 'Successfully Added!',
            showConfirmButton: false,
            timer: 1500
          })
          vm.modal.createclient = false
          vm.isLoader = false
          vm.$refs.frmcreateclient.reset();
           vm.indexEmployee()
         }
         else{
            vm.isLoader = false
            vm.$swal({
            icon: 'error',
            title: res.data.error.email[0],
            showConfirmButton: false,
            timer: 1500
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
          vm.isLoader = false
        })
      }
      }
      
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
.bg-custom-primary{
  &:focus{
    outline: none;
  }
}
	@import '../../sass/employees';
</style>