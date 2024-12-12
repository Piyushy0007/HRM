<template>
  <div class="c-employee-deleted px-4 pb-4 w-80" style="margin-right: 1vw; margin-left: 240px;">

    <Loader msg="Processing ..." v-model="isLoader" />

    <div class="selection-function px-4 pt-2 pb-3 flex justify-between">
      <div class="text-right">
        <span class="block mb-1" style="float:left;">Total SOS Report</span>
      </div>
    </div>

    <div class="px-4">
      <table class="w-full border-red-300">
        <thead>
          <tr class="bg-red-300">
            <th class="text-center">Users Name</th>
            <th class="text-center">Alert Report Images</th>
            <th class="text-center">Category</th>
            <th class="text-center" style="width:350px;">Description</th>
            <th class="text-center">Date</th>
            <th v-if="this.userRole == 'admin' ">Client Tag</th>
          </tr>
        </thead>
        <tbody v-if="index.employees.length===0">
          <tr><td colspan="15" class="px-2">No Records Found</td></tr>
        </tbody>
        <tbody v-else>
          <tr v-for="(data, index) in index.employees" :key="data.id">
            <td class="text-center">{{ data.empname }}</td>
            <td class="text-center">
              <div v-for="(imagedata, index1) in data.dataImageval" :key="index1" style="float:left;"> 
                <a :href="imagedata" target="_blank"><img class="b-avatar-img-class" style="height: 150px;margin-right:5px;margin-bottom:10px;" :src="imagedata" /></a>
              </div>
            </td>
            <td class="text-center">{{ data.category }}</td>
            <td class="text-center" style="width:350px;">{{ data.description }}</td>
            <td class="text-center">{{ data.created_at | moment("MM/DD/YYYY h:mm A") }}</td>
            <td v-if="usertype == 'admin' ">
              <button class="add-blue-button"  @click="create('create',data.id)" >Tag to Community</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <div class="information mx-4 mt-5 p-4 rounded-lg">
      <div class="flex justify-between items-center mb-2">
        <h4 class="text-2xl font-semibold">Information</h4>
        <a href="#" class="text-sm">Help on this topic <strong>More</strong></a>
      </div>

	    <ul class="list-inside">
        <li>SOS report added by users</li>
        
      </ul>
    </div>

    <modal v-model="modal.geoLoc1" size="md:w-7/12" title="Select Community">
      <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(propertyModel1())" ref="frmpropertymodel1" novalidate>
          <div class=" px-4 mb-4 mt-4">
            
            <template>
               <div class="flex" style="display:flex;">
                  <template>
                    <ValidationProvider rules="required" v-slot="v" class=" md:w-6/12 m-3" style="display:block;">
                      <div class=" md:items-center">
                        <div class="md:w-4/4">
                          <label class="block mb-1 md:mb-0 pr-4">Community<span class="req_form_fields">*</span></label>
                        </div>
                        <div class="md:w-4/4">
                        <select style="appearance:menulist;" class="block appearance-none w-full py-1 px-4 pr-8 rounded-10 height-41 leading-tight focus:outline-none" v-model="modal.geoloc.clientid" @change="clientselected">
                          <option value="">Select Community</option>  
                          <option :value="data.id" v-for="data in clientlist" :key="data.id">{{ data.clientname }}</option>
                        </select>
                          
                        </div>
                      </div>
                    </ValidationProvider>
                  </template>
              </div>
          </template>
         </div>
          <div class="text-center mt-2 mb-2">
              
            <button class="text-white py-3 px-12 rounded-full  bg-custom-primary" type="submit">Assign</button>
          </div>
        </form>
      </ValidationObserver>
    </modal>
</div>
</template>

<script>

import Modal from '../shared/Modal'
import Loader from '../shared/Loader'
import axios from 'axios'

export default {
	components: {
		Modal,
    Loader
	},
	data() {
		return {
      // select employee
      checkedNames: [],
      bulkids: [],
			requestedHeaders: {
				headers: {}
			},
      clientlist:[],
      isLoader: false,
      usertype:'',
      searchKeyword: '',
      searchTimer: null,

			modal: {
        BulkEditEmp: false,
        geoLoc1: false,
        geoloc:{
          clientid: '',
          reportid: '',
        },
			},
      
      index: {
        employees: {},
      },
      allSelected: false,
		}
	},
  computed: {
    
    userid() {
      let user = localStorage.getItem('user');
      console.log('user get',user);
        if(user){
          return JSON.parse(user).id;
        }else {
         return 0;
        }
    },
    userRole() {
      let user = localStorage.getItem("admin");
        if(user){
          return JSON.parse(user).role;
        }else {
         return 0;
        }
      },
  },

  async created() {
    let vm = this
    await vm.indexEmployee()
  },
	methods: {
    indexEmployee() {
      let vm = this
      vm.isLoader = false
      axios
        .get(`/api/employees-alert-all`)
        .then(res => {
          console.log('resDev',res);
          vm.index.employees = res.data
          vm.isLoader = false
          vm.usertype = vm.userRole
        })
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
    create(value, reportid){
      let vm = this;
      if(value == 'create'){
         vm.modal.geoloc.clientid = '';
         vm.modal.geoloc.reportid = reportid;
         vm.fetchclientlist();
         vm.modal.geoLoc1 = true;
      }
    },
     propertyModel1() {
        let vm = this;
        vm.isLoader = true
        if((vm.modal.geoloc.client_id != '' || vm.modal.geoloc.client_id != undefined )){
            axios.post('/api/adminaddclientreport',{
              client_id : vm.modal.geoloc.clientid,
              report_id : vm.modal.geoloc.reportid,
            })
            .then(res => {
              if(res.data.status == true){
                  vm.$swal({ icon: 'success', title: 'Successfully Updated!', showConfirmButton: false, timer: 1500})
                  vm.modal.geoLoc1 = false;
                  vm.isLoader = false
              }else{
                vm.$swal({ icon: 'error', title: res.data.message, showConfirmButton: false, timer: 1500 })
                vm.isLoader = false
              }
            })
            .catch(err => {
              vm.$swal({ icon: 'error', title: err.response.data, showConfirmButton: false, timer: 1500})
              vm.isLoader = false
            })
      }else{
          vm.$swal({ icon: 'error', title: 'Fill Required fields', showConfirmButton: false, timer: 1500 })
          vm.isLoader = false
      }
    },

  }
}
</script>

<style lang="scss" scoped>
	@import '../../../sass/employees';
</style>