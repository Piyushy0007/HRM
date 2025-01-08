<template>
  <div class="c-employee-index client-table">

    <Loader msg="Processing ..." v-model="isLoader" />
    <header-component />
    <div class="px-4 pb-4 w-80" style="margin-right: 1vw;">
      <div class="flex " style="justify-content: space-between;">
        <h1 class="mb-4">Time Off Requests </h1>
    
      </div>
        <div class="flex">
          <div class="w-full flex md:w-1/10">
         
          <div class="w-full md:w-1/4 pr-3 mt-4 mb-4">
            <div class="relative">
              <select id="requested" @change="selectRequested()"  class="height-41 block appearance-none w-full py-1 px-4 pr-8 rounded-10 leading-tight focus:outline-none">
                <option value="">Most recent requested</option>
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
                
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
              </div>
            </div>
          </div>
          <div class="w-full md:w-1/4 pr-3 mt-4 mb-4">
            <div class="relative">
              <select  id="status" @change="selectStatus()" class="height-41 block appearance-none w-full py-1 px-4 pr-8 rounded-10 leading-tight focus:outline-none">
                <option value="">All Status</option>
                <option value="approved">Approved</option>  
                <option value="reject">Reject</option>
                <option value="pending">Pending</option>
                
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
              </div>
            </div>
          </div>
          <!-- <b-button variant="outline-primary">Filter</b-button> -->
         </div>
          <div class="w-full md:w-1/2">
          <div class="w-50 px-3  md:w-1/2 mt-4 mb-4" style="float:right;">
            <div class="relative">
              <!-- <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" placeholder="Search" v-model="searchKeyword" @keyup="search">
              <div class="absolute inset-y-0 right-0 flex items-center px-2 text-custom-border rounded-r border border-custom-border border-l-0">
                <font-awesome-icon icon="search" class="fill-current" />
              </div> -->
            </div>
          </div>
          </div>
         
           </div>
      <table class="w-full">
        <thead>
          <tr>
           <th class="text-center">Officer Name</th>
           <th class="text-center">Date Submitted</th>
           <th class="text-center">Dates Requested</th>
           <th class="text-center">Status</th>
           <th class="text-center"></th>
          </tr>
        </thead>
        
        <tbody >
          <template v-if="timeoffdata.length != 0">
          <tr v-for="(data) in timeoffdata" :key="data.id" class="col-md-12">
            <td class="col-md-3 text-center text-transform-capitalise">{{ data.firstname || '-'}} {{ data.lastname || '-'}}  </td>
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
            <td class="col-md-2 text-center text-transform-capitalize text-center" ><input class="text-transform-capitalize text-center border-0" style="border:none; background:transparent;" v-model="data.status" readonly/></td>
            <!-- <td class="text-center"  @click='openView(data)' ><b-button class="m-3" variant="success"> <font-awesome-icon icon="pencil-alt"  class="text-gray-500"  /></b-button></td> -->
            <!-- <td class="text-center" @click='deleteView(data.id)' ><b-button class="m-3" variant="success"> <font-awesome-icon :icon="['far', 'trash-alt']" class="text-gray-500" /></b-button></td> -->
           <td class="col-md-3 text-center"  v-if="data.status == 'reject'">
             <span class="display-inline-flex mx-auto greyish">
               <b-button class="add-blue-button blue ml-1">Approve</b-button>
               <button class="add-blue-button grey ml-1">Reject</button> 
               <b-icon @click="update('pending', data.timeoff_id,data.email)" class=" ml-1" icon="arrow-counterclockwise" style="color: #3B86FF;" font-scale="1.25" />
             </span>
           </td>
             <td class="col-md-3 text-center"  v-else-if="data.status == 'pending'">
             <span class="mx-auto display-inline-flex">
               <button  @click="update('approved', data.timeoff_id,data.email)" class="add-blue-button blue ml-1">Approve</button>
               <button @click="update('reject', data.timeoff_id,data.email)" class="add-blue-button grey ml-1">Reject</button> 
                <b-icon class=" ml-1 " icon="arrow-counterclockwise" style="opacity:0; pointer-events:none;" font-scale="1.25" />
              
             </span>
          </td>
            <td class="col-md-3 text-center" v-else>
             <span class="mx-auto greyish display-inline-flex">
               <button class="add-blue-button blue ml-1">Approve</button>
               <button class="add-blue-button grey ml-1">Reject</button> 
               <b-icon @click="update('pending', data.timeoff_id, data.email)" class=" ml-1" icon="arrow-counterclockwise" style="color: #3B86FF;" font-scale="1.25" />
             </span>
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
     <modal v-model="modal.SignIn" class="modal-add-edit-positions" size="md:w-7/12" :title="`Time Off Request ${modal.SignInSuccessMessage} `">
		    
        <tbody class="w-100" style="display: block;">
          <tr class="flex items-center justify-between mb-5 mt-5" style="display: block;">
            <td class="wgt text-center" colspan="2" style="display: block;">
              <b class="titleBox text-xl font-semibold">Request update notification has been sent to officer in mobile app.</b>
            </td>
          </tr>
        </tbody>
		   
        <div class="text-center">
        <!-- <vue-confirm-dialog ref="sendsign"></vue-confirm-dialog> -->
        <input class="timereqoff" type="button" value="Ok" @click="showMsgBoxOne" title="ok">
         </div>
        </modal>

  </div>
</template>
<style lang="scss" scoped>
.tooltip {
  position: relative;
  // display: inline-block;
  // border-bottom: 1px dotted black; 
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
table td:first-child {
    background: none !important;
}
body{
  font-family: 'Open Sans'
}
</style>
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
      timeoffdata: [],
      requested: '',
      time_off_date:[],
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
        SendMessageToAll:{
        id: [],
        subject: '',
        message: ''
        },
        SignInSuccess : [],
        SignInSuccessMessage : '',
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
        SendMessage: false,

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
    countTotalEmployees() {
      return this.index.employees.length
    },
    modalEmployeeName() {
      return this.modal.reqEditEmployee.firstname + ' ' + this.modal.reqEditEmployee.lastname
    },
    datastatus(){
      return this.index.client
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
  },
  watch :{
    datastatus:{
      handler() {
        console.log(this.datastatus, 'cdatastatus')
      },
       deep: true,
      immediate: true
    }
  },
	methods: {
    selectRequested() {
    var x = document.getElementById("requested").value;
    var y = document.getElementById("status").value;
    let vm = this;  
    axios.post(`/api/filtertimeoff`, {status:y, requested: x})  
    .then(res => {
          vm.$swal({
            closeOnClickOutside: false,
            icon: 'success',
            title: res.data.message,
            showConfirmButton: false,
            timer: 5000,
          })
          vm.isLoader = false
        vm.timeoffdata= res.data.data
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
    selectStatus() {
    var x = document.getElementById("status").value;
    var y = document.getElementById("requested").value;
    let vm = this;      
     axios.post(`/api/filtertimeoff`, {status:x, requested: y}).then(res => { 
       if(res.data.status == true){
          vm.$swal({
            closeOnClickOutside: false,
            icon: 'success',
            title: res.data.message,
            showConfirmButton: false,
            timer: 5000,
          })
          vm.isLoader = false
          vm.timeoffdata = res.data.data
       }
       else{
          vm.$swal({
            icon: 'error',
            title:res.data.message,
            showConfirmButton: false,
            timer: 3000
          })
            vm.isLoader = false
           vm.timeoffdata =[];
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
    addclient(){
      this.$router.push('/client/clientadd')
    },
    openView(data){
      // this.router.push({ name: 'client-view', params: { id: id } })
      this.$router.push(`/client/properties/${data.id}`) 

    },
    deleteView(id){
      let vm = this;
     axios.get(`/api/Delete/${id}`).then(res => {
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
      this.modal.SignIn = false;
      vm.indexEmployee();
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
        this.$alert('First click the checkboxes to the left of each name to select Watchers.' );
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
         this.$alert('Selected Watchers copied to the clipboard so you can paste in another software system.');
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
          message: `Are you sure you want to send sign in instructions to ${namelength} Watchers `,
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
        vm.modal.SignIn = false
        // vm.indexEmployee();
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
		async openModal(modal, data) {
			let vm = this
			switch(modal) {
				case 'SignIn':
          vm.modal.SignIn = true
          if(data == 'reject'){
             vm.modal.SignInSuccessMessage = 'Rejected!'
          }
          else if(data == 'approve' || data == 'approved'){
               vm.modal.SignInSuccessMessage = 'Approved!'
          }
          else if(data == 'pending'){
             vm.modal.SignInSuccessMessage = 'Pending!'
          }
         
					break
			}
		},

    /**
     * List all Client
     */
    indexEmployee(position='') {
      let vm = this

      vm.isLoader = true
      axios
        .get(`/api/showtimeoff`)
        .then(res => {
          vm.index.client = res.data
          vm.requested =  moment(vm.index.client.updated_at).format('D/MM/YYYY')
          //  vm.time_off_date = vm.index.client.time_off
           vm.index.client.map(item =>{
             item.time_off.map(elem=>{
               return elem = moment(elem).format('MM/DD/YYYY')
             })
           })
           vm.timeoffdata =  vm.index.client;
          // vm.time_off_date.filter((item)=>{
          //    item = moment(item).format('D/mm/YYYY')
          // })
          vm.isLoader = false
        })
    },
    update(status, time_off, email){
      let vm = this
       vm.isLoader = true
     
      axios
        .post(`/api/updatetimeoff`, {status:status, id: time_off, email:email} )
        .then(res => {
           vm.indexEmployee();
           this.openModal('SignIn', res.data.data.status);
          // if(status!='pending'){
          //     this.openModal('SignIn', res.data.data.status);
          // }
          
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
                      vm.$alert('Sign in Instructions have been sent to selected Watchers!');
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
	@import '../../sass/employees';
</style>