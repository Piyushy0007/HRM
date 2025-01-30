<template>
  <div class="c-employee-index pb-4">
    <Loader msg="Processing ..." v-model="isLoader" />
    <div style="margin-left: 240px;">
      <div class="px-4 pb-4 ">
        <div class="flex items-center justify-between mx-2 my-3">
          <h2 class="text-xl font-semibold">Watcher Logs</h2>
          <div class="w-50 px-3  md:w-2/1">
              <div class="relative">
                <input class="appearance-none border block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" placeholder="Search" v-model="searchKeyword" @keyup="search()">
                <div class="absolute inset-y-0 right-0 flex items-center px-2 text-custom-border rounded-r ">
                  <font-awesome-icon icon="search" class="fill-current" />
                </div>
              </div>
            </div>
          <!-- <button class="add-blue-button" @click="openClientModal()">Create Community</button> -->
        </div>
          <div class="flex">
            <div class="w-full flex md:w-1/10">              
          </div>
            <div class="w-full md:w-1/2">
            <!-- <div class="w-50 px-3  md:w-1/2 mt-4 mb-4" style="float:right;">
              <div class="relative">
                <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" placeholder="Search" v-model="searchKeyword" @keyup="search()">
                <div class="absolute inset-y-0 right-0 flex items-center px-2 text-custom-border rounded-r border border-custom-border border-l-0">
                  <font-awesome-icon icon="search" class="fill-current" />
                </div>
              </div>
            </div> -->
            </div>
          
            </div>
        <table class="table-auto w-full border-collapse border border-custom-border">
          <thead class="thead-light bg-custom-bg_table_head_primary border-custom-border">
            <tr class="text-gray-600 border border-custom-border rounded-lg">
            <th style="width:15%;" class="text-center p-2 border border-custom-border heading-sort">
              <div class="flex items-center gap-1">
                Officer Name <b-icon-arrow-down-up @click="namesort()" /> 
              </div>
            </th>
            <th style="width:15%;" class="text-center p-2 border border-custom-border">Clocked in </th>
            <th style="width:15%;" class="text-center p-2 border border-custom-border">Clocked out</th>
            <th style="width:25%;" class="text-center p-2 border border-custom-border">Perimeter violation</th>
            <th style="width:15%;" class="text-center p-2 border border-custom-border">Lunch</th>
            <th style="width:15%;" class="text-center p-2 border border-custom-border">Break</th>
            </tr>
          </thead>
          
          <tbody >
            <template v-if="officerdata.length!= 0">
            <tr v-for="(data) in officerdata" :key="data.id">
            <!-- <tr> -->
              <td class="text-center p-2 border border-custom-border text-transform-capitalise">{{ data.employee.firstname || '-'}} {{ data.employee.lastname}} </td>
              <td class="text-center p-2 border border-custom-border">{{ data.start_time }} </td>
              <td class="text-center p-2 border border-custom-border">{{ data.end_time || '-'}}</td>
              <td class="text-center perimeter-time tooltip1 p-2 border border-custom-border">
                <span class="data" v-for="(peri , index) in data.detect_shift" :key="peri.id">
                  <span class="text-transform-capitalize" v-if="index == 0"> {{peri.clock_in_time}} -  {{peri.clock_out_time}} to </span>
                  <span class="text-transform-capitalize" v-if="index == data.detect_shift.length - 1 "> {{peri.clock_in_time}} -  {{peri.clock_out_time}} </span>
                </span>
              
                <span class="tooltiptext1">
                    <span class="text-transform-capitalize" v-for="(peri) in data.detect_shift" :key="peri.id">
                    {{peri.clock_in_time}} - {{peri.clock_out_time}}<br>
                    </span>
                </span>
              </td>
              <td class="text-center p-2 border border-custom-border" v-if="data.lunch_start">{{ data.lunch_start}} - {{ data.lunch_end}}</td>
              <td class="text-center p-2 border border-custom-border" v-else>-</td>
              <td class="text-center p-2 border border-custom-border" v-if="data.break_start">{{ data.break_start}} - {{ data.break_end}}</td>
              <td class="text-center p-2 border border-custom-border" v-else>-</td>
            </tr>
            </template>
            <template v-else>
              <td class="text-center" colspan="8">
                No Records Found
              </td>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>

import Modal from '../shared/Modal'
import Loader from '../shared/Loader'
import axios from 'axios'
import moment from 'moment'
export default {
	components: {
		Modal,
		Loader
	},
	data() {
		return {
			isLoader: false,
			searchKeyword: '',
      searchTimer: null,
      officerdata:[],
      start:'',
		}
	}, 
	computed: {
    userRole() {
        let user = localStorage.getItem("user");
        console.log('user get',user);
        if(user){
          return JSON.parse(user).role;
        }else {
         return 0;
        }
    },
    userid() {
      let user = localStorage.getItem('user');
      console.log('user get',user);
        if(user){
          return JSON.parse(user).id;
        }else {
         return 0;
        }
    },
  },
  async created() {
    let vm = this
    await vm.indexEmployee()
  },
  watch :{
    
  },
	methods: {

    search() {
      let vm = this
      // if (vm.searchKeyword == " ") {
      //  vm.indexEmployee();
      // }
      if (vm.searchTimer ) {
        clearTimeout(vm.searchTimer)
        vm.searchTimer = null
      }
      vm.searchTimer = setTimeout(() => {
        // axios.post(`/api/clientsearch`, {search: vm.searchKeyword}) 
      axios.post(`/api/filterofficerlogs`, {search: vm.searchKeyword}) 
          .then(res => {
            if(res.data.status != true){
               vm.$swal({
              icon: 'error',
              title: res.data.message,
              showConfirmButton: false,
              timer: 1500
            })
             vm.officerdata = [];
      
            }
            else{
            vm.officerdata = res.data.data
            vm.officerdata.map(x =>{
                 x.start_time = moment().format("MMM D, YYYY ") + moment( x.start_time,'h:mmA').format('h:mm A');
                 x.end_time = moment().format("MMM D, YYYY ") + moment( x.end_time,'h:mmA').format('h:mm A');
                 if(x.lunch_start){
                     x.lunch_start =  moment(x.lunch_start,'h:mm A').format('h:mm A');
                 }
                 if(x.break_start){
                     x.break_start =  moment(x.break_start,'h:mm A').format('h:mm A');
                 }
                
                 if(x.lunch_end){
                     x.lunch_end =  moment(x.lunch_end,'h:mm A').format('h:mm A');
                 }
                 if(x.break_end){
                     x.break_end =  moment(x.break_end,'h:mm A').format('h:mm A');
                 }
                 if(x.detect_shift){
                    x.detect_shift.map(y=>{
                   y.clock_in_time =  moment( y.clock_in_time,'h:mm A').format('h:mm A');
                   y.clock_out_time =  moment(   y.clock_out_time,'h:mm A').format('h:mm A');
                 })
                 }
                
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
         
          })
      }, 500)
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

    indexEmployee() {
      let vm = this

      vm.isLoader = true
      axios
        .get(`/api/officerlogs`)
        .then(res => {
          if(res.data.status != false){

              if(vm.userRole!='admin'){
                  vm.officerdata = res.data.data.filter(x=>{
                    return x.employee.added_by == vm.userid
                  })
                  vm.officerdata.map(x =>{
                       x.start_time = moment().format("MMM D, YYYY ") + moment( x.start_time,'h:mmA').format('h:mm A');
                       x.end_time = moment().format("MMM D, YYYY ") + moment( x.end_time,'h:mmA').format('h:mm A');
                       if(x.lunch_start){
                           x.lunch_start =  moment(x.lunch_start,'h:mm A').format('h:mm A');
                       }
                       if(x.break_start){
                           x.break_start =  moment(x.break_start,'h:mm A').format('h:mm A');
                       }
                      
                       if(x.lunch_end){
                           x.lunch_end =  moment(x.lunch_end,'h:mm A').format('h:mm A');
                       }
                       if(x.break_end){
                           x.break_end =  moment(x.break_end,'h:mm A').format('h:mm A');
                       }
                       if(x.detect_shift){
                          x.detect_shift.map(y=>{
                         y.clock_in_time =  moment( y.clock_in_time,'h:mm A').format('h:mm A');
                         y.clock_out_time =  moment(   y.clock_out_time,'h:mm A').format('h:mm A');
                       })
                       }
                  })
              }else{
                    vm.officerdata = res.data.data;
                    console.log('res.data.data',res.data)
                    vm.officerdata.map(x =>{
                       x.start_time = moment().format("MMM D, YYYY ") + moment( x.start_time,'h:mmA').format('h:mm A');
                       x.end_time = moment().format("MMM D, YYYY ") + moment( x.end_time,'h:mmA').format('h:mm A');
                       if(x.lunch_start){
                           x.lunch_start =  moment(x.lunch_start,'h:mm A').format('h:mm A');
                       }
                       if(x.break_start){
                           x.break_start =  moment(x.break_start,'h:mm A').format('h:mm A');
                       }
                      
                       if(x.lunch_end){
                           x.lunch_end =  moment(x.lunch_end,'h:mm A').format('h:mm A');
                       }
                       if(x.break_end){
                           x.break_end =  moment(x.break_end,'h:mm A').format('h:mm A');
                       }
                       if(x.detect_shift){
                          x.detect_shift.map(y=>{
                         y.clock_in_time =  moment( y.clock_in_time,'h:mm A').format('h:mm A');
                         y.clock_out_time =  moment(   y.clock_out_time,'h:mm A').format('h:mm A');
                       })
                       }
                  })
              }

              //vm.officerdata = res.data.data;
              
              vm.isLoader = false
          }
          else{
          vm.isLoader = false
          
          }
      
        })

    },
    namesort(){
      let vm = this;
      if(!this.nameSort){
        vm.officerdata = vm.officerdata .slice().sort(function(a, b){
          return (a.employee.firstname < b.employee.firstname) ? 1 : -1;
        });
         this.nameSort = !this.nameSort
      }
      else{
         vm.officerdata = vm.officerdata .slice().sort(function(a, b){
          return (a.employee.firstname > b.employee.firstname) ? 1 : -1;
        });
         this.nameSort = !this.nameSort
      }
    },

	}
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

/* Tooltip text */
.tooltip1 .tooltiptext1 {
  visibility: hidden;
  width: 170px;
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
.tooltip1:hover .tooltiptext1 {
  visibility: visible;
}
	/* @import '../../../sass/employees'; */
</style>