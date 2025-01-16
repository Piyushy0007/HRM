<template>
  <div class="c-employee-index client-table  client-add ">
    <client-header-component />
    <Loader msg="Processing ..." v-model="isLoader" />
    <p style="margin-bottom: 0;padding-bottom: 75px;border-left: 2px solid #f9f9f9;margin-top: -52px;padding-left: 30px;font-family: Poppins;font-weight: bold;font-size: 22px; color: #2C1977; "> Location</p>
    <div class="px-4 pb-4  flex mr-1 tabletopbackground">
      <table class="w-full tabletopposition">
        <thead class="thed">
          <tr >
           <th class="text1one" style="width: 25%;">Watchers Assigned   </th>
           <th class="text1one" style="width: 30%;">Location </th>
           <th style="width: 30%;"> -  </th>
           <th class="text1one" style="width: 15%;padding-left: 0;"> Action  </th>          
          </tr>
        </thead>
        <tbody style= "height: 498px;overflow: scroll;" >
          <template v-if="propertiesdata == '' ">
             <tr class="w-12/12" style="width: 100%;">
             <td class="w-12/12 text-center text-transform-capitalise" style="width: 100%;">No Records Found</td>
             </tr>
          </template>
          <template  v-else >
            <tr class="w-12/12"  v-for="data in  propertiesdata" :key="data.id" style="width: 100%;">
              <td class="w-3/12 text-left text-transform-capitalise">{{ data.properties_name || '-'}} </td>
              <td class=" w-3/12 text-left address">{{data.properties_address ? data.properties_address : '-'}}</td>
              <td style="width: 30%;"class="w-3/12 text-center empl-names" v-if="data.employees.length != 0" >
                <span v-for="(empname , index) in data.employees" :key='index'>
                  <template v-if="data.employees.length == 1">
                    <span class="text-transform-capitalize"> {{empname.emp_firstname}} {{empname.emp_lastname}}</span>
                  </template>
                  <template v-else>
                    <span class="text-transform-capitalize" v-if="index == 0"> {{empname.emp_firstname}} {{empname.emp_lastname}} - </span>
                    <span class="text-transform-capitalize" v-if="index == data.employees.length - 1 ">{{empname.emp_firstname}} {{empname.emp_lastname}}</span>
                  </template>
                </span>
              </td>
              <td style="width: 30%;" class="w-3/12 text-center empl-names" v-else > - </td>
              <td class="w-3/12 text-center" style="width: 15%; text-align: right !important;">
                <button :class="data.employees.length == 0 ? 'add-purple-button ' : 'add-purple-button'" 
                @click="sendMessage1(data)">Message</button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <!-- <modal v-model="modal.createmessage" class="modal-add-new-employee" size="md:w-5/12" title="Message">
      <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(sendEmail())" ref="frmcreateclient" novalidate>
    	
    		  <div class=" px-4 mb-4 mt-4">
            <ValidationProvider rules="required" v-slot="v">
      				<div class=" md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Message <span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-4/4">
        					<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.sendMessage.message">
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1 scale-0.1">
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>
    		  </div>
    	
          <div class="text-center mt-2 mb-2">
    				<button class="text-white py-3 px-12 rounded-full  bg-custom-primary" type="submit">Send</button>
          </div>
        </form>
      </ValidationObserver>
		</modal> -->
      <b-modal centered  class="modal-add-new-employee" id="datepicker" title="Send Message" style="margin:0 auto;">
        <div class=" px-4 mb-1 mt-1">
          <div class=" md:items-center">
            <div class="md:w-4/4">
              <label class="block mb-1 md:mb-0 pr-4">Subject <span class="req_form_fields">*</span></label>
            </div>
            <div class="md:w-4/4">
              <input type="text"  class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-500"  v-model="modal.sendMessage.subject" style="border-color: #a0aec0 !important;" />
            </div>
          </div>
          <div class=" md:items-center">
            <div class="md:w-4/4">
              <label class="block mb-1 md:mb-0 pr-4">Message <span class="req_form_fields">*</span></label>
      			</div>
            <div class="md:w-4/4">
              <textarea class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-500"  v-model="modal.sendMessage.message"></textarea>
      			</div>
      		</div>
    		</div>
        <template #modal-footer="{ }">
          <div class="text-center mt-2 mb-2" style="margin:0 auto;">
            <button @click="sendEmail()" style="border-radius: 5px;" class="text-white py-2 px-10 bg-custom-primary" type="submit">Send</button>
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
import { BButton } from 'bootstrap-vue'

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
      nameSort: false,
      propertiesdata:[],
      user_id: '',
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
        createmessage: false,
        sendMessage:{
          idemail:[],
          message:'',
          subject:'',
          propertyname:''
        },
        SendMessageToAll:{
        id: [],
        subject: '',
        message: ''
        },
        SignInSuccess : [],
				addNewEmployee: false,
        editEmployee: false,
        addEditPositions: false,
        //sign in ins, bulk edit modals
        SignIn: false,
        BulkEditEmp: false,
        SignInSent: false,
        // send reminder
        SendReminders: false,
        SendReminderSuccess: false,
        // SendMessage: false,

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
  async created() {
    let vm = this
   this.user_id = this.userid
    await vm.indexEmployee()
  },
  watch :{

  },
	methods: {
    sendEmail(){   
      let vm = this;
       if(this.modal.sendMessage.message && this.modal.sendMessage.subject){  
        let vm = this;
          axios.post(`/api/clientmessagetoemployee`, { data : this.modal.sendMessage.idemail, message: this.modal.sendMessage.message, subject: this.modal.sendMessage.subject, propertyname: this.modal.sendMessage.propertyname}) 
          .then(res => {
            if(res.data.status != true){
              vm.$swal({
                  icon: 'error',
                  title: res.data.message,
                  showConfirmButton: false,
                  timer: 1500
                  })
            }
            else{
            vm.$swal({
                  icon: 'success',
                  title: 'Mail Sent Successfully',
                  showConfirmButton: false,
                  timer: 1500
                  })
                    this.$bvModal.hide('datepicker');
                }
          });
              }
              else{
                    vm.$swal({
                          icon: 'error',
                          title:'Fill required field',
                          showConfirmButton: false,
                          timer: 1500
                          })
              }
    },
    	async openModal(modal, data ,data2) {
			let vm = this
			switch(modal) {
        case 'sendMessage':
            vm.modal.sendMessage.message = '';
            vm.modal.sendMessage.subject = '';
            vm.modal.sendMessage.idemail = data;
            vm.modal.sendMessage.propertyname = data2;
            vm.modal.createmessage = true;
            this.$bvModal.show('datepicker');
          break
			}
		},
    sendMessage1(data){
      let vm =this;
      if(data.employees){
        let datas = data.employees
       let senddata = []; 
       let properties_name = data.properties_name
       senddata = datas.map(x =>{
          // return {id: x.emp_id , email: x.emp_email}
          return { email: x.emp_email}
        })
         this.openModal('sendMessage', senddata , properties_name)
      }
    },
    addclient(){
      this.$router.push('/client/clientadd')
    },
    openView(data){
      // this.router.push({ name: 'client-view', params: { id: id } })
      this.$router.push(`/client/properties/${data.id}`) 

    },
    deleteView(idprp){
      let vm = this;
     axios.get(`/api/deleteproperty/${idprp}`).then(res => {
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
      

    },
    /**
     * List all property
     */
    indexEmployee(position='') {
      let vm = this

      vm.isLoader = false
      axios
        .get(`/api/getproperty/${this.userid}`)
        .then(res => {
          if(res.data.status != false){

           vm.isLoader = false
          vm.index.client = res.data.data
          vm.propertiesdata = vm.index.client.slice().sort(function(a, b){
          return (a.properties_name > b.properties_name) ? 1 : -1;
          
        });
        vm.propertiesdata = vm.propertiesdata.filter(x=>{
         return x.employees = vm.uniquebykeeplast(x.employees, it=> it.emp_id)
        })
        
          }
          else{
             vm.isLoader = false
             vm.propertiesdata = [];
          }
        //   let data = vm.index.client.data
        // data.filter(elem =>{
        //     return elem.employees != null; 
        //   })
          vm.isLoader = false
        })
    },
    uniquebykeeplast(data, key){
      return [
        ...new Map(
          data.map(x=>[key(x),x])
        ).values()
      ]
    },
        namesort(){
      let vm = this;
      if(!this.nameSort){
        vm.propertiesdata =  vm.propertiesdata.slice().sort(function(a, b){
          return (a.properties_name < b.properties_name) ? 1 : -1;
        });
         this.nameSort = !this.nameSort
      }
      else{
         vm.propertiesdata =  vm.propertiesdata.slice().sort(function(a, b){
          return (a.properties_name > b.properties_name) ? 1 : -1;
        });
         this.nameSort = !this.nameSort
      }
    },

   


	}
}

</script>

<style lang="scss" scoped>
  @import '../../../sass/client/employees';
  .text1one{
    font-family: Poppins;
    font-size: 20px;
    padding-left:15px;
  }
  .text{
    display:flex;
    flex-direction:row;
  }
  .backnew{
    background-color:#E5E5E5;
  }
  th {
      font-family: Poppins;
      font-size: 20px;
      color: #FFFFFF;
      background: #AD9E58;
      }
    tr,
  td {
        background: #FFFFFF;
          box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
      }
          td,th {
              text-align: left;
              padding: 8px;
              border-bottom: 1px solid #ddd;
            }
          tr:nth-child(even) {
            border: 1px solid rgba(24, 109, 65, 0.082);
            
          }
          
          .border {
              width: 1057px;
              height: 71px;
              background: #FFFFFF;
              box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
              border-left: #2630c9;
          }
        
  .client-properties{
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
        padding: 1px 0px 1px 25px;
          
      }
        .address{
          white-space: nowrap;
          max-width: 200px;
          padding: 1px 0px 1px 25px;
          
        }
        .add-client{
          overflow:auto;
          }
      }
    }
  }
  .add-blue-button.disable{
    pointer-events: none;
    background-color: grey !important;
  }
  table td:first-child {
      background: white !important;
      box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
      background-color:none;
      border-left:3px solid rgb(16, 43, 100)
    
  }
  table tbody td {
    border-bottom: 13px solid #ffffff;
    text-align: center !important;
  }

  .tooltip1 {
    position: relative;
  
  }

  /* Tooltip text */
  .tooltip1 .tooltiptext1 {
    visibility: hidden;
    width: 120px;
    background-color: black;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    border-radius: 1px;
    position: fixed;
    z-index: 1;
  }

  .tooltip1:hover .tooltiptext1 {
    visibility: visible;
  }

</style>