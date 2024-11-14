<template>
  <div class="c-employee-index client-table client-add">
     <client-header-component />
    <Loader msg="Processing ..." v-model="isLoader" />
    <div class="px-4 pb-4 add-client" style="border-left: 2px solid #f9f9f9;margin-top: -62px;padding-left: 0 !important;">
      <div class="flex " style="justify-content: space-between;padding-left: 15px;">
        <h1 class="mb-4" style="font-family: Poppins;font-weight: bold;font-size: 22px; ">Invoices</h1>
        
      </div>
      <div class="filter-section mb-3" style="padding-left: 15px;margin-top: -35px;">
        <div class="flex">
          <div class="w-full flex md:w-1/10">
         
          <div class="w-full md:w-1/4 pr-3 mt-4 mb-4">
	          <div class="relative">
	            <input class="height-41 appearance-none block w-full rounded py-2 pl-4 pr-8 leading-tight focus:outline-none border-custom-border placeholder-custom-placeholder" type="text" placeholder="" v-model="search" >
	            <div class="absolute inset-y-0 right-0 flex items-center px-2 text-custom-border rounded-r border-custom-border border-l-0">
	              <font-awesome-icon icon="search" class="fill-current" />
	            </div>
	          </div>
          </div>

          <div class="w-full md:w-1/4 pr-3 mt-4 mb-4">
            <div class="relative">
              <select id="data"  class="block appearance-none w-full py-1 px-4 pr-8 rounded-10 height-41 leading-tight focus:outline-none">
                <option value="today">Today</option>
                <option value="yesterday">Yesterday</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
                <option value="alldata">All Data</option>
                
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
              </div>
            </div>
          </div>

          <div class="w-full md:w-1/4 pr-3 mt-4 mb-4">
            <div class="relative">
              <select id="status" class="block appearance-none w-full py-1 px-4 pr-8 rounded-10 height-41 leading-tight focus:outline-none">
                <option value="all">All Status</option>
                <option value="paid">Paid</option>
                <option value="overdue">Overdue</option>
                <option value="on hold">On Hold</option>
                
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
              </div>
            </div>
          </div>
          <b-button @click="filterinvoice()" class="mt-4 mb-4 filter-button" variant="outline-primary">Filter</b-button>
         </div>
          <div class="w-full md:w-1/2">

          </div>
         
           </div>
      </div>
     <div class="px-4 pb-4  flex mr-1 tabletopbackground">
      <table class="w-full tabletopposition">
        <thead>
          <tr>
           <th class="txt"style="width: 18%;">Client Name</th>
           <th class="txt"style="width: 21%;">Invoice Number</th>
           <th class="txt"style="width: 21%;">Invoice Amount </th>
           <th class="txt"style="width: 18%;">Date Posted </th>
           <th class="txt"style="width: 18%;">Status </th>
           <th class="txt"style="width: 7%;"></th>
            </tr>
        </thead>
        
        <tbody style="width: 76%;">
           <template v-if="invoices.length === 0 ">
             <tr class="w-12/12" >
             <td class="w-12/12 text-center text-transform-capitalise" ></td>
             <td class="w-12/12 text-center text-transform-capitalise"></td>
             <td class="w-12/12 text-leftEmployees Assigned text-transform-capitalise">No Records Found</td>
             <td class="w-12/12 text-center text-transform-capitalise"></td>
             <td class="w-12/12 text-center text-transform-capitalise"></td>
             </tr>
          </template>
          <template v-else>

          <tr v-for="(data) in invoices" :key="'A'+ data.id">
            <td class="text-center text-transform-capitalise">{{ data.client.clientname || '-'}} </td>
            <td class="text-center">#{{data.id ? data.id : '-'}}</td>
            <td class="text-center">${{ data.invoice_amount || '-' }}</td>
            <td class="text-center">{{ data.created_at | moment(' MMM DD, YYYY')  }}</td>
            <td class="text-center text-transform-capitalise" :class="data.status=='on hold'? 'onhold': data.status=='paid'? 'paid' : 'overdue'">{{ data.status || '-' }}</td>
           
            <td class="text-center"><button class="add-blue-button" @click="invoicepdf(data.id)">View invoice PDF </button></td>

          </tr>
          </template>
        </tbody>
      </table>
      </div>
    </div>

  </div>
</template>
<style lang="scss" scoped>
.txt{
  color:white;
  font-family: Poppins;
   font-size: 20px;
   padding-left: 25px;
   }
.onhold{
    color: #3B86FF;
    font-weight: 600;
}
.paid{
    color: inherit;
    font-weight: 600;
}
.overdue{
    color: red;
    font-weight: 600;
}
table td:first-child {
    background: none !important;
}
.filter-section{
  .relative{
    // width: 171px;
    width: 100%;
    background: #FFFFFF 0% 0% no-repeat padding-box;
    border-radius: 10px;
    opacity: 1;
  }
  .filter-button{
    margin-top: 1rem;
    margin-bottom: 1rem;
    color: #ffffff;
    /* display: inline-block; */
    /* font-weight: 400; */
    width: 104px;
    height: 37px;
    background-color:#2C1977;
    text-align: center;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    // background-color: transparent;
    border: 1px solid #007bff;
    padding: 0px 30px;
    font-size: 16px;
    line-height: 0;
    border-radius: 0.25rem;
    top: 271px;
    left: 487px;
    border: 1px solid #3B86FF;
    background-color: #2C1977;
    opacity: 1;
     font-family: Poppins;
     font-weight: bold;
     font-size: 16px;
  }
}
</style>
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
      invoices :[],
      isLoader: false,
      currentDate: new Date(),
      search: '',

      
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
    await vm.indexInvoice()
  },
  watch :{

  },
	methods: {
    //pdf invoicepdf
    invoicepdf(id){
      let vm = this;
      vm.$swal({
              icon: 'error',
              title: `In Progress #${id}`,
              showConfirmButton: false,
              timer: 1500
            })
    },
    // filter method
    filterinvoice(){
    var data = document.getElementById("data").value;
    var status = document.getElementById("status").value;
    let vm = this;
        axios
          .post(`/api/invoicefilter`, {client_id : this.userid , data:data, status: status, search: vm.search  }) 
          .then(res => {
            if(res.data.status != true){
               vm.invoices = [];
            }
            else{
          vm.$swal({
            icon: 'success',
            title: 'Data Fetched Successfully!',
            showConfirmButton: false,
            timer: 3000
          })
             vm.invoices  = res.data.data
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
    //show invoice 
    indexInvoice() {
      let vm = this
      let date  = moment(new Date()).format('YYYY-MM-DD');
       vm.isLoader = true
     
      axios
        .post(`/api/invoices`,{client_id : this.userid , current_date: date})
        .then(res => {
          if(res.data.status){
            vm.invoices = res.data.data
            vm.isLoader = false
          }
          else{
            vm.invoices = [];
            vm.isLoader = false
          }
        })
          
    },
	}
}

</script>

<style lang="scss" scoped>
	@import '../../../sass/client/employees';
</style>