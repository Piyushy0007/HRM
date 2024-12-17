<template>
  <div class="c-employee-deleted px-4 pb-4 w-80" style="margin-right: 1vw;">

    <Loader msg="Processing ..." v-model="isLoader" />

    <div class="selection-function px-4 pt-2 pb-3 flex justify-between">
      <div class="flex items-start">
        <div class="options py-4 px-6 rounded-md flex items-center">
          <label class="font-semibold text-lg mr-4 text-custom-primary">Selected users:</label>
          <ul class="flex items-center">
            <li class="mr-4">
              <a href="#" class="flex items-center text-sm"  @click.prevent="SendMessageCheck('RestoreMultiple')">
                <font-awesome-icon icon="plus" class="mr-1 text-custom-primary" />
                Restore
              </a>
            </li>
            <li class="mr-4">
              <a href="#" class="flex items-center text-sm"  @click.prevent="SendMessageCheck('ExportData')">
                <font-awesome-icon icon="external-link-square-alt" class="mr-1 text-custom-primary" />
                Export to Clipboard
              </a>
            </li>
            <li class="mr-4">
              <a href="#" class="flex items-center text-sm"  @click.prevent="SendMessageCheck('BulkEdit')" >
                <font-awesome-icon icon="pencil-alt" class="mr-1 text-custom-primary" />
                <!-- <font-awesome-icon icon="pencil-alt" class="mr-1 text-custom-primary" /> -->
                Bulk Edit
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div class="text-right">
        <span class="block mb-1">Total Deleted users: {{ countTotalEmployees }}*</span>
        <div class="flex flex-wrap -mx-3">
          <div class="w-full md:w-1/2 px-3">
            <div class="relative">
              <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" placeholder="Find" v-model="searchKeyword" @keyup="search">
              <div class="absolute inset-y-0 right-0 flex items-center px-2 text-custom-border rounded-r">
                <font-awesome-icon icon="search" class="fill-current" />
              </div>
            </div>
          </div>
          <div class="w-full md:w-1/2 pr-3">
            <div class="relative">
              <select class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none" v-model="index.selectPosition" @change="filterResultViaPosition(index.selectPosition)">
                <option value="">All Positions</option>
                <option v-for="data in index.positions" :key="data.id" :value="data.id">{{ data.position }}</option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="px-4">
      <table class="w-full border-red-300">
        <thead>
          <tr class="bg-red-300">
            <th class="text-center">
               <input v-model="allSelected" type="checkbox" @click="selectAll">
              </th>
            <!-- <th>View</th> -->
            <th>Restore</th>
            <!-- <th class="leading-tight">View<span class="block">Sched</span></th> -->
            <th>First</th>
            <th>Last</th>
            <th class="leading-tight">Last<span class="block">Edited</span></th>
            <th>Phone</th>
            <th>Email*</th>
            
            <th>Hire Date</th>
            <th class="leading-tight">Priority<span class="block">Group</span></th>
          </tr>
        </thead>
        <tbody v-if="index.employees.length===0">
          <tr><td colspan="15" class="px-2">No Records Found</td></tr>
        </tbody>
        <tbody v-else>
          <tr v-for="(data, index) in index.employees" :key="data.id">
            <td class="text-center"><input type="checkbox" :value="data" v-model="checkedNames" ></td>
            <!-- <td class="text-center">
              <a href="#"><font-awesome-icon icon="search" /></a>
            </td> -->
            <td class="text-center">
              <a href="#" @click.prevent="restoreEmployee(data, index)" class="text-gray-600"><font-awesome-icon icon="user-plus" /></a>
            </td>
            <!-- <td class="text-center">
              <a href="#"><font-awesome-icon icon="calendar-alt" /></a>
            </td> -->
            <td class="text-center">{{ data.employee.firstname }}</td>
            <td class="text-center">{{ data.employee.lastname }}</td>
            <td class="text-center">{{ data.employee.updated_at | moment('YYYY-MM-D') }}</td>
            <td class="text-center">{{ data.employee.mobile }}</td>
            <td class="text-center">{{ data.employee.email }}</td>
            <td class="text-center">{{ data.employee.hired_date }}</td>
            <td class="text-center">{{ data.employee.priority_group }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div style="opacity: 0; position:absolute;" ref="content" class="p-4">
        <table class="w-full" ref="content">
        <thead>
          <tr>
           <th>First</th>
            <th>Last</th>
            <th class="leading-tight">Last Edited </th>
            <th>Phone</th>
            <th>Email*</th>
            <th class="leading-tight py-2">Max Wkly Hours</th>
            <th class="leading-tight">Max Wkly Days </th>
            <th class="leading-tight">Max Day Hours </th>
            <th class="leading-tight">Max Day Shifts </th>
            <th>Hire Date</th>
            <th class="leading-tight">Priority Group </th>
          </tr>
        </thead>
        
        <tbody>
          <tr v-for="data in checkedNames" :key="data.id">
           <td class="text-center">{{ data.employee.firstname }}</td>
            <td class="text-center">{{ data.employee.lastname }}</td>
            <td class="text-center">{{ data.employee.updated_at | moment('YYYY-MM-D') }}</td>
            <td class="text-center">{{ data.employee.mobile }}</td>
            <td class="text-center">{{ data.employee.email }}</td>
            <td class="text-center">{{ data.employee.max_weekly_hours }}</td>
            <td class="text-center">{{ data.employee.max_weekly_days }}</td>
            <td class="text-center">{{ data.employee.max_day_hours }}</td>
            <td class="text-center">{{ data.employee.max_day_shifts }}</td>
            <td class="text-center">{{ data.employee.hired_date }}</td>
            <td class="text-center">{{ data.employee.priority_group }}</td>
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
        <li>Deleted users are not counted toward your subscription 'users count'</li>
        <li>Restoring an user will reset their username and password</li>
        <li>Click column title to sort by that field</li>
      </ul>
    </div>

<!-- modals -->
     <!-- Bulk edit start -->
     <modal v-model="modal.BulkEditEmp" class="modal-add-edit-positions BulkEditEmp" size="md:w-5/12" title="User bulk EDIT">
		    <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(updateEmployeeBulk)" novalidate ref="bulkEditEmpl">

          <!-- ================================= Contact ================================= -->
          <div class="contact px-6 pb-6 mb-4">
            <h4 class="text-xl font-semibold mb-4">Contact</h4>
            <div class="flex">
              <div class="md:w-3/12">
                <label class="block md:text-right mb-1 mt-1 md:mb-0 pr-4">City, State, Zip</label>
              </div>
              <div class="md:w-5/12">
                <ValidationProvider rules="required" v-slot="v">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.reqEditEmployeeBulk.city">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
              </div>
              <div class="md:w-2/12 mx-1">
                <ValidationProvider rules="required" v-slot="v">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.reqEditEmployeeBulk.state">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
              </div>
              <div class="md:w-2/12">
                <ValidationProvider rules="required" v-slot="v">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" v-model="modal.reqEditEmployeeBulk.zip">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </ValidationProvider>
              </div>
            </div>
          </div>
          <!-- ================================= ./Contact ================================= -->


          <!-- ================================= Auto Fill Options ================================= -->
          <div class="auto-fill-options px-6 pb-6 mb-4">
            <h4 class="text-xl font-semibold mb-2">AutoFill options</h4>
            <div>
              
              <!-- ====== Maximums ====== -->
              <div class="c-bg-card flex px-5 py-3 rounded-lg w-5/6 mx-auto">
                <h6 class="w-1/4 font-bold">Maximums</h6>
                <div class="w-3/4">

                  <div class="flex items-center mb-1">
                    <span class="block w-2/12 text-right">per week</span>
                    <div class="w-3/12 mx-2">
                      <ValidationProvider rules="required|min_value:1" v-slot="v">
                        <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-center" type="text" v-model="modal.reqEditEmployeeBulk.max_weekly_hours" maxlength="4" @keypress="isNumberOnly($event)">
                        <small class="text-red-600">{{ v.errors[0] }}</small>
                      </ValidationProvider>
                    </div>
                    <span class="block w-1/12">hrs</span>

                    <div class="w-3/12 ml-6 mr-2">
                      <ValidationProvider rules="required|min_value:1" v-slot="v">
                        <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-center" type="text" v-model="modal.reqEditEmployeeBulk.max_weekly_days" maxlength="4" @keypress="isNumberOnly($event)">
                        <small class="text-red-600">{{ v.errors[0] }}</small>
                      </ValidationProvider>
                    </div>
                    <span class="block w-1/12">days</span>
                  </div>

                  <div class="flex items-center">
                    <span class="block w-2/12 text-right">per day</span>
                    <div class="w-3/12 mx-2">
                      <ValidationProvider rules="required|min_value:1" v-slot="v">
                        <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-center" type="text" v-model="modal.reqEditEmployeeBulk.max_day_hours" maxlength="4" @keypress="isNumberOnly($event)">
                        <small class="text-red-600">{{ v.errors[0] }}</small>
                      </ValidationProvider>
                    </div>
                    <span class="block w-1/12">hrs</span>

                    <div class="w-3/12 ml-6 mr-2">
                      <ValidationProvider rules="required|min_value:1" v-slot="v">
                        <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-center" type="text" v-model="modal.reqEditEmployeeBulk.max_day_shifts" maxlength="4" @keypress="isNumberOnly($event)">
                        <small class="text-red-600">{{ v.errors[0] }}</small>
                      </ValidationProvider>
                    </div>
                    <span class="block w-1/12">shifts</span>
                  </div>

                </div>
              </div>
              <!-- ====== ./Maximums ====== -->


              <!-- ====== Seniority ====== -->
              <div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-5/6 mx-auto my-2">
                <h6 class="w-1/4 font-bold">By Seniority</h6>
                <div class="w-3/4 flex items-center">
                  <span class="block w-2/12 text-right">Hire Date</span>
                  <div class="w-8/12 ml-2">
                    <ValidationProvider rules="required" v-slot="v">
                      <date-picker valueType="format" v-model="modal.reqEditEmployeeBulk.hired_date"
                        input-class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border border-custom-border"
                        :clearable="false"
                        :shortcuts="[
                          { text: 'Yesterday', onClick: () => {
                            let date = new Date()
                            date.setTime( date.getTime() - 3600 * 1000 * 24 )
                            return date
                          } },
                          { text: 'Today', onClick: () => new Date() },
                        ]"></date-picker>
                      <small class="text-red-600 block">{{ v.errors[0] }}</small>
                    </ValidationProvider>
                  </div>
                </div>
              </div>
              <!-- ====== ./Seniority ====== -->

              <!-- ====== Priority Group ====== -->
              <div class="c-bg-card flex items-center px-5 py-3 rounded-lg w-5/6 mx-auto">
                <h6 class="w-1/4 font-bold">By Priority Group</h6>
                <div class="w-3/4 flex">
                  <span class="block w-2/12 text-right"></span>
                  <div class="w-8/12 ml-2">
                    <div class="relative">
                      <select class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none" v-model="modal.reqEditEmployeeBulk.priority_group">
                        <option value="0">Leave unchanged</option>
                        <option value="1">First</option>
                        <option value="2">Second</option>
                        <option value="3">Third</option>
                        <option value="4">Fourth</option>
                        <option value="5">Fifth</option>
                        <option value="6">Sixth</option>
                        <option value="7">Seventh</option>
                        <option value="8">Eight</option>
                        <option value="9">Ninth</option>
                        <option value="10">Tenth</option>
                      </select>
                      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ====== ./Priority Group ====== -->

            </div>
          </div>
          <!-- ================================= ./Auto Fill Options ================================= -->

          
          <!-- ================================= Pay Rate ================================= -->
          <div class="pay-rate px-6 pb-4 mb-4 flex mt-1">
            <h4 class="text-xl font-semibold w-3/12">Pay Rate</h4>
            <div class="w-4/12 mr-2">
              <ValidationProvider rules="required" v-slot="v">
                <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-right" type="text" v-model="modal.reqEditEmployeeBulk.pay_rate" maxlength="10" @keypress="isNumberOnlyAndDecimalPoint($event)">
                <small class="text-red-600 block">{{ v.errors[0] }}</small>
              </ValidationProvider>
            </div>
            <span class="mt-1">per hour default</span>
          </div>
          <!-- ================================= ./Pay Rate ================================= -->
          <!-- ================================= Alert Date ================================= -->
          <div class="pay-rate px-6 pb-4 mb-4 flex mt-1">
            <h4 class="text-xl font-semibold w-3/12">Alert Date </h4>
            <div class="w-4/12 mr-2">
                    <ValidationProvider rules="required" v-slot="v">
                      <date-picker valueType="format" v-model="modal.reqEditEmployeeBulk.alert_date "
                        input-class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border border-custom-border"
                        :clearable="false"
                        :shortcuts="[
                          { text: 'Yesterday', onClick: () => {
                            let date = new Date()
                            date.setTime( date.getTime() - 3600 * 1000 * 24 )
                            return date
                          } },
                          { text: 'Today', onClick: () => new Date() },
                        ]"></date-picker>
                      <small class="text-red-600 block">{{ v.errors[0] }}</small>
                    </ValidationProvider>
            </div>
            <span class="mt-1"> default</span>
          </div>
          <!-- ================================= ./Alert Date================================= -->


          <!-- ================================= Custom Fields ================================= -->
          <div class="custom-fields px-6 pb-4 mb-4 flex">
            <h4 class="text-xl font-semibold w-3/12">Custom Fields</h4>
            <div class="w-9/12">
              <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none mb-1" v-model="modal.reqEditEmployeeBulk.cf_1" type="text">
              <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none"  v-model="modal.reqEditEmployeeBulk.cf_2" type="text">
            </div>
          </div>
          <!-- ================================= ./Custom Fields ================================= -->


          <!-- ================================= Comments ================================= -->
          <div class="comments px-6 pb-4 mb-4 flex">
            <h4 class="text-xl font-semibold w-3/12">Comments</h4>
            <div class="w-9/12">
              <textarea rows="5" class="w-full rounded p-2 leading-tight focus:outline-none" style="border: 1px solid #707070;"  v-model="modal.reqEditEmployeeBulk.comments"></textarea>
            </div>
          </div>
          <!-- ================================= ./Comments ================================= -->

          <div class="flex justify-between mt-10 mb-8 text-center">
            <div style="width:100%; text-align:center;">
              <button class="text-white py-3 px-12 rounded-full bg-custom-primary focus:outline-none" ref="editEmployeeSave" type="submit">Save</button>
               <button class="text-white py-3 px-12 rounded-full bg-custom-primary focus:outline-none" ref="editEmployeeClose" @click.prevent="close" type="button">Cancel</button>
            </div>
          </div>
        </form>
      </ValidationObserver>

        
        </modal>
    
    <!-- bulk edit end -->
<!-- modals -->


  </div>
</template>

<script>

import Modal from '../shared/Modal'
import Loader from '../shared/Loader'
import axios from 'axios'
import DatePicker from 'vue2-datepicker'

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
    DatePicker
	},
	data() {
		return {
      // select employee
      checkedNames: [],
      bulkids: [],
			requestedHeaders: {
				headers: {}
			},

      isLoader: false,

      searchKeyword: '',
      searchTimer: null,

			modal: {
        BulkEditEmp: false,
        positions: [],
        reqEditEmployeeBulk: {},
        reqEditEmployeeBulk: {
          max_weekly_hours: 40,
          max_weekly_days: 7,
          max_day_hours: 14,
          max_day_shifts: 1
        },
			},

      index: {
        employees: {},
        positions: {},
        selectPosition: '',
      },
      allSelected: false,
		}
	},
  computed: {
    countTotalEmployees() {
      return this.index.employees.length
    },
        // ids of selected employees
    BulkIDS(){
      let ids= [];
       for (let i = 0; i <= this.checkedNames.length - 1; i++) {
            {
              this.bulkids[i] = this.checkedNames[i].employee_id
            }
        }
        ids = this.bulkids; 
        return Object.values(ids)
    },
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
    }
  }, 
    watch :{
    BulkIDS:{
      handler() {
        console.log(this.BulkIDS)
      },
       deep: true,
       immediate: true
    }
  },
  async created() {
    let vm = this
    await vm.indexEmployee()
    await vm.indexPosition('index-position')
  },
	methods: {
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

      if(this.checkedNames.length != 0 && func == 'RestoreMultiple'){
       this.restoreEmployeeSelected(Object.assign(this.BulkIDS));
      }
      else if(this.checkedNames.length != 0 && func == 'ExportData'){
        this.ExportData(this.checkedNames);
      }
      else if(this.checkedNames.length != 0 && func == 'BulkEdit'){
       if(this.checkedNames.length == 1){
          this.restoreEmployee(this.checkedNames[0]);
       }
       else{
        this.openModal('BulkEditEmp');
       }
      }
      else if(this.checkedNames.length == 0 && (func == 'ExportData' || func == 'BulkEdit' )){
        this.$alert('Please Select at least one row' );
      }
      else{
        this.$alert('First click the checkboxes to the left of each name to select users.' );
      }
    },
    ExportData(names){
      let vm = this;
      vm.copyGroupsToClipboard()
    },
    // copy to clipboard
    copyGroupsToClipboard() {
        removeAllSelections()
        selectContent(this.$refs.content)
        // copySelection()
        this.$alert('Selected users copied to the clipboard so you can paste in another software system.');
      },
    /**
     * Search
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
          .get(`/api/employee-trashed-search?kw=${vm.searchKeyword}&position_id=${vm.index.selectPosition}`)
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
		openModal(modal) {
			let vm = this
			switch(modal) {
				case 'AddNewEmployee':
					vm.modal.addNewEmployee = true
          vm.indexPosition('modal-positions')
					break
				case 'AddEditPositions':
					vm.modal.addEditPositions = true
          vm.indexPosition('modal-positions')
          vm.trashedPosition()
					break
        case 'EditDeletePositions':
          vm.modal.addEditPositions = false   // close the parent modal
          vm.modal.editDeletePositions = true
          break
        case 'BulkEditEmp':
          vm.modal.BulkEditEmp = true
					break
			}
		},

    /**
     * List all employee
     */
    indexEmployee(position='') {
      let vm = this
      vm.isLoader = true
      axios
        .get(`/api/employees-trashed?position_id=${position}&kw=${vm.searchKeyword}`)
        .then(res => {
          console.log('res.data.data',res.data)
          if(vm.userRole!='admin'){
              vm.index.employees = res.data.filter(x=>{
                return x.employee.added_by == vm.userid
              })
          }else{
            vm.index.employees = res.data
          }
          //vm.index.employees = res.data
          vm.isLoader = false
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
          vm.index.positions = position.data.data
          break
        case 'modal-positions':
          vm.modal.positions = position.data.data
          break
      }
    },

    /**
     * Restore removed employee
     * 
     * @param  Obj data
     * @param  Int index
     */
    restoreEmployee(data, index) {
      let vm = this

      if (confirm(`Are you sure you want to restore ${data.employee.firstname} ${data.employee.lastname} (deleted)?`)) {
        axios
          .delete(`/api/employees-restore/${data.employee.id}`)
          .then(res => {
            vm.index.employees.splice(index, 1)
            vm.indexEmployee()
            vm.$swal({
              icon: 'success',
              title: 'Successfully restored!',
              showConfirmButton: false,
              timer: 1500
            })
          })
      }
    },
    /**
     * Restore removed employee selected
     * 
     * @param  Obj data
     * @param  Int index
     */
    restoreEmployeeSelected(data) {
      let vm = this
      if (confirm(`Are you sure you want to restore the selected users?`)) {
        axios
          .delete(`/api/employees-restore-selected?id=${data}`)
          .then(res => {
            // vm.index.employees.splice(index, 1)
            vm.indexEmployee()
            vm.$swal({
              icon: 'success',
              title: 'Successfully restored!',
              showConfirmButton: false,
              timer: 1500
            })
             this.allSelected = false;
          })
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
        await axios.post(`/api/employees/delete-bulk-edit`, Object.assign(vm.modal.reqEditEmployeeBulk))
        // vm.indexEmployee()
        vm.$swal({
          icon: 'success',
          title: 'Successfully updated!',
          showConfirmButton: false,
          timer: 1500
        })
        vm.isLoader = false
        vm.indexEmployee()
        vm.modal.editEmployeeBulk = false
        this.modal.BulkEditEmp = false;
        this.$refs.bulkEditEmpl.reset()
        this.allSelected = false;
      } catch (e) {
        vm.isLoader = false
      }
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
    
	}
}

</script>

<style lang="scss" scoped>
	@import '../../../sass/employees';
</style>