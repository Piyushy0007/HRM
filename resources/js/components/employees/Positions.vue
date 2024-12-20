<template>
  <div class="c-employee-positions pb-4">
    <Loader msg="Processing ..." v-model="isLoader" />
    <div style="margin-left: 240px;">		
      <div class="px-4">
        <div class="flex items-center justify-between my-5">
          <button class="text-white py-2 px-16 rounded-lg text-sm btn-add-edit-position" type="button" @click.prevent="openModal('AddEditPositions')">Add/Delete Position</button>
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
                <select class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none" v-model="selectedPosition">
                  <option value="">All Positions</option>
                  <option v-for="data in positions" :key="data.id" :value="data.id">{{ data.position }}</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
              </div>
            </div>
          </div>
        </div>

        <table class="w-full table-fixed">
          <thead>
            <tr>
              <th class="w-6">Select</th>
              <th class="w-20 text-sm leading-none px-2 py-2">Edit Pos Prefs</th>
              <th class="w-32 text-sm text-left">First</th>
              <th class="w-32 text-sm text-left">Last</th>
              <th v-for="data in positions" :key="data.id" class="w-1/12 py-2 text-sm leading-none truncate cursor-pointer" v-if="selectedPosition == '' || selectedPosition == data.id"
                v-tooltip="{
                  content: data.position,
                  classes: ['rounded','bg-black','text-white','py-1','px-3'],
                }">
                {{ data.position }}
                <a href="#" @click.prevent="openModal('EditPosition', data)">
                  <font-awesome-icon icon="pencil-alt" class="block mx-auto mt-1" />
                </a>
              </th>
            </tr>
          </thead>
          <tbody>
            
            <tr v-if="employees.length === 0">
              <td :colspan="(4 + positions.length)">No Records Found</td>
            </tr>
            <tr v-for="(data, index) in employees" :key="data.id">
              <td class="text-center">
                <input type="checkbox" v-model="checkEmpPositions[index]" @click="checkEmpPosition(index)" class="form-checkbox h-3 w-3 text-blue-600" >
              </td>
              <td class="text-center">
                <a href="#" @click.prevent="editEmpPosition(data, index)"> <font-awesome-icon icon="pencil-alt"  /></a>
              </td>
              <td>{{ data.firstname }}</td>
              <td>{{ data.lastname }}</td>
              <!-- display all positions -->
              <td v-for="data2 in positions" class="text-center class-color-here" :key="data2.id" v-if="selectedPosition == '' || selectedPosition == data2.id">
                <input type="checkbox" class="form-checkbox h-3 w-3 text-blue-600" v-model="mapEmployeePosition[index]" :value="data2.id">
                <!-- <span v-for="data1 in mapEmployeePosition1[index]" v-bind:key="data1.id">
                  <template  v-if="data1.id == data2.id">
                    <input type="checkbox" :class="data1.preference" class="form-checkbox h-3 w-3 text-blue-600" v-model="mapEmployeePosition[index]" :value="data2.id">
                  </template>
                </span> -->
              </td>
            </tr>
          </tbody>
        </table>

        <div class="text-center my-12">
          <button class="text-white py-2 px-16 rounded-lg text-sm btn-add-edit-position" type="button" @click.prevent="updateEmployeePositions">Save Changes</button>
        </div>

        <div class="information mt-5 p-4 rounded-lg">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2">
            <h4 class="text-lg font-semibold mb-2 mb-md-0">Information</h4>
            <a href="#" class="text-sm inline-flex items-center">
              Help on this topic&nbsp;<font-awesome-icon icon="arrow-circle-right" />&nbsp;<strong>More</strong>
            </a>
          </div>

          <ul class="ml-2 mb-1 instructions">
            <li class="mb-1">Check the boxes for the positions each users can work and click the "Save" button.</li>
            <li class="mb-1">Check the title row box to check/uncheck all users for that position.</li>
            <li class="mb-1">Check the box to the left of an users to check/uncheck all positions for that users.</li>
            <li>Optional: Click pencil icon to set that users position preferences.</li>
          </ul>

          <div class="flex justify-between flex-md-row flex-column items-end">
            <ul>
              <li class="flex items-center">
                <span class="inline-block w-20 h-8 mr-1" style="background-color: #FFA700">&nbsp;</span>
                Cell changes not saved.
              </li>
              <li class="flex items-center">
                <span class="inline-block w-20 h-8 mr-1" style="background-color: #67FF91">&nbsp;</span>
                Position preference set to PREFER (can be used by AutoFill)
              </li>
              <li class="flex items-center">
                <span class="inline-block w-20 h-8 mr-1" style="background-color: #FF8F92">&nbsp;</span>
                Position preference set to DISLIKE (can be used by AutoFill)
              </li>
            </ul>
            <div class="text-sm float-right ">
              Trouble with grid displaying?&nbsp;<font-awesome-icon icon="arrow-circle-right" />&nbsp;<strong>More</strong>
            </div>
          </div>
        </div>
      </div>
      <!-- ============================================ Modal ============================================ -->
      <!-- Bulk edit start -->
      <modal v-model="modal.editEmpPositionModal" class="modal-add-edit-positions BulkEditEmp" size="md:w-7/12" :title="editEmpPositiondata.firstname">
          <ValidationObserver v-slot="{ handleSubmit }">
          <form @submit.prevent="handleSubmit(editEmpPositionForm)" novalidate ref="editEmpPosition">
            <div class="center">
              <table class="module">  
                <tbody><tr><td class="wgt"><b class="titleBox">Optional Position Settings</b></td></tr>
                <tr><td style="padding:0 5px 10px 5px;">
                <table cellspacing="1" cellpadding="1" width="100%" style="border-collapse:separate;">
                <tbody>
                    <tr><td align="center" width="33.33%" rowspan="2" valign="center"><b class="title">Position Preferences</b><br><span class="smaller">click position name below to set</span></td>
                  <td align="center" width="33.33%"><span class="small">Default</span><br>
                  
                  <ValidationProvider rules="required" v-slot="v">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-right" type="text" v-model="editEmpPositiondata.pay_rate" maxlength="10" @keypress="isNumberOnlyAndDecimalPoint($event)">
                  <small class="text-red-600 block">{{ v.errors[0] }}</small>
                </ValidationProvider>
                  </td>
                  <!--
                  <!CHECK  !HELP>
                  -->
                  <td align="center" width="33.33%"><span class="small">&nbsp;&nbsp;&nbsp;&nbsp; Default</span><br><nobr>
                  </nobr>
                  <ValidationProvider rules="required" v-slot="v">
                        <date-picker valueType="format"  v-model="editEmpPositiondata.alert_date"
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
                  </td>
                  <!--
                  <!/CHECK>
                  -->
                  </tr>
                  <tr>
                  <td align="center" valign="bottom"><b class="title">Pay Rate</b></td>
                  <!--
                  <!CHECK  !HELP>
                  -->
                  <td align="center" class="title" valign="bottom" style="padding-left:20px; background-color: #E3E5F1;"><b class="title">Alert Date</b></td>
                  <!--
                  <!/CHECK>
                  -->
                  </tr>
                  <tr align="center" v-for="position in  editEmpPositiondata.position" :key="position.id" >
                  <td class="small" :class="position.preference" v-on:click="setPreference(position)"  :id="position.position.id"  >{{position.position.position}}</td>
                  <!-- <td class="small"   :id="position.position.id"  >{{position.position.position}}</td>   -->
                  <td style="height: 25px margin: 7px 7px;
      padding: 7px 7px;">
                    
                    <ValidationProvider rules="required" v-slot="v">
                  <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none text-right" type="text" v-model="position.pay_rate"  maxlength="10" @keypress="isNumberOnlyAndDecimalPoint($event)">
                  <small class="text-red-600 block">{{ v.errors[0] }}</small>
                </ValidationProvider>
                    </td>
                  <!--
                  <!CHECK  !HELP>
                  -->
                  <td style="height: 25px margin: 7px 7px;
      padding: 7px 7px;"><nobr>
                    <!-- <ValidationProvider rules="required" v-slot="v"> -->
                        <date-picker valueType="format"  v-model="position.alter_date"
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
                        <!-- <small class="text-red-600 block">{{ v.errors[0] }}</small> -->
                      <!-- </ValidationProvider> -->
                    </nobr>
                    </td>
                  <!--
                  <!/CHECK>
                  -->
                  </tr>
                <tr><td class="small">Use the <b>users&gt;Positions Grid </b>page to select more positions
                for this users</td></tr>
                </tbody></table>
                </td></tr>
                </tbody></table>
            </div>
            <!-- ================================= Comments ================================= -->
            <div class="comments px-6 pb-4 mb-4 flex">
              <h4 class="text-xl font-semibold w-3/12">Comments</h4>
              <div class="w-9/12">
                <textarea rows="5" class="w-full rounded p-2 leading-tight focus:outline-none" style="border: 1px solid #707070;"  v-model="editEmpPositiondata.comment"></textarea>
              </div>
            </div>
            <!-- ================================= ./Comments ================================= -->
          

            <div class="flex justify-between mt-10 mb-8 text-center">
              <div style="width:100%; text-align:center;">
                <button class="text-white py-3 px-12 rounded-full bg-custom-primary focus:outline-none" type="submit">Save</button>
                
              </div>
            </div>
          </form>
        </ValidationObserver>
        <table class="module">
  <tbody>
    <tr><td class="wgt"><b class="titleBox">Information</b></td></tr>
  <tr><td class="bwgt">  
  - Choose your preferences for this users positions: click position label above to change to <b><span style="background-color: #90F68E">prefer</span>, <span style="background-color: #FFAEAE">dislike</span></b> and <span style="background:white;"> <b>no preference</b></span> <br>
  Can be used by Autofill and when viewing available users for a shift.
  - To allow this users to work more positions, use the <b>users&gt; Positions Grid</b> page.
  <br><br>
  - Pay Rates - enter default pay rate at the top or edit pay rates per position.<br><span class="small">Can be used to view approximate payroll.</span>
  <br><br>
  - Alert dates - set default date for all positions or edit individual position dates.
  <br><span class="small">Can be used, for example, for certification expirations.</span>
  <br><br>
  - Click the "Save" button when finished.
  <br><br>- Changes will be reflected on the "Positions Grid" the next time that page is reloaded.</td></tr>
  </tbody></table>

          
      </modal>
      <!-- bulk edit end -->
      <modal v-model="modal.addEditPositions" class="modal-add-edit-positions" size="md:w-7/12" title="Add/Delete Positions">
        <div class="new-position px-6 py-6">
          <ValidationObserver v-slot="{ handleSubmit }">
            <form @submit.prevent="handleSubmit(addPosition)">
              <div class="flex items-center justify-between mb-5">
                <h4 class="text-xl font-semibold">New Position</h4>
                <button class="text-white py-2 px-16 rounded-full text-sm bg-custom-primary" type="submit">Add</button>
              </div>
              <ValidationProvider rules="required" v-slot="v">
                <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none mb-1" type="text" v-model="modal.addPosition.position">
                <small class="text-red-600 block">{{ v.errors[0] }}</small>
                <p class="text-sm">After adding, set which users can work each position on the <strong>users>Positions Grid</strong></p>
              </ValidationProvider>
            </form>
          </ValidationObserver>
        </div>

        <div class="list-of-positions py-5 mb-4">
          <div class="flex justify-between items-center mb-5 px-6">
            <div class="flex items-center leading-none">
              <h4 class="text-xl font-semibold">Positions</h4>
            </div>
            <button class="text-white py-2 px-16 rounded-full text-sm bg-custom-primary" type="button" @click.prevent="openModal('EditDeletePositions')">Delete</button>
          </div>

          <ul class="flex flex-col flex-wrap ml-10 mr-6 mb-4" v-if="modal.positions.length===0">
            <li class="text-sm">No Records Found</li>
          </ul>  
          <ul class="flex flex-col flex-wrap ml-10 mr-6 mb-4" v-else>
            <li class="text-sm" v-for="data in modal.positions" :key="data.id">{{ data.position }}</li>
          </ul>
        </div>

        <div class="deleted-positions py-5 mb-4">
          <div class="flex justify-between items-center mb-5 px-6">
            <div class="flex items-center leading-none">
              <h4 class="text-xl font-semibold">
                Recently Deleted
                <span class="text-sm font-normal">Click Position to restore it.</span>
              </h4>
            </div>
          </div>

          <ul class="flex flex-col flex-wrap ml-10 mr-6 mb-4">
            <li v-if="modal.trashedPositions.length===0" class="text-sm">No Records Found</li>
            <li v-else class="text-sm" v-for="(data, index) in modal.trashedPositions">
              <a href="#" @click.prevent="restorePosition(data.id, index)">{{ data.position }}</a>
            </li>
          </ul>
        </div>

        <div class="information">
          <h4 class="text-xl mb-2">Information</h4>
          <ul class="list-inside">
            <li class="text-sm">Sort order of positions is alphabetical. You can edit positions to have a leading space or hyphen to list them first</li>
            <li class="text-sm">Only the main manager on the account can delete positions.</li>
          </ul>
        </div>
      </modal>
      <modal v-model="modal.editDeletePositions" class="modal-edit-delete-positions" size="md:w-6/12" :title="`Delete Positions`">
        <nav class="my-4 edit-delete-positions">
          <ul class="flex justify-center w-5/12 mx-auto">
            <li class="w-2/4 mr-1 rounded-t-lg" :class="{ active: isActive('edit') }" style="display:none;">
              <a href="#" class="block text-center font-semibold text-lg pt-1" @click.prevent="setActive('edit')">Edit</a>
            </li>
            <li class="w-2/4 mr-1 rounded-t-lg" :class="{ active: isActive('delete') }">
              <a href="#" class="block text-center font-semibold text-lg pt-1" @click.prevent="setActive('delete')">Delete</a>
            </li>
          </ul>
        </nav>
        
        <!-- ==================================== Edit position ==================================== -->
        <div v-if="activeItem == 'edit'">
          <div class="flex items-center justify-between">
            <div>
              <h4 class="text-xl font-semibold">Current positions</h4>
              <a href="#" class="text-custom-primary" @click.prevent="sortAlphabetically">Sort alphabetically</a>
            </div>
            <!-- <button class="text-white py-2 px-12 rounded-full text-sm bg-custom-primary" type="button" @click.prevent="saveSortedPosition">Save</button> -->
          </div>
          <draggable v-model="modal.positions" tag="ul" class="w-9/12 mx-auto my-10" handle=".handle"
            @start="isDragging=true" @end="isDragging=false">
            <transition-group type="transition" name="flip-list">
              <li v-for="(data) in modal.positions" :key="data.id" class="flex items-center justify-between border border-custom-primary mb-2 px-3 py-1 rounded-md">
                <a href="#" @click.prevent="openModal('EditPosition', data)">{{ data.position }}</a>
                <a href="#"><font-awesome-icon icon="bars" class="text-custom-primary handle" /></a>
              </li>
            </transition-group>
          </draggable>

          <div class="text-center">
            <button class="text-white py-2 px-12 rounded-full text-sm bg-custom-primary" type="button" @click.prevent="saveSortedPosition">Save</button>
          </div>
          
          <div class="information mt-5 pt-3">
            <h4 class="text-xl mb-2">Information</h4>
            <ul class="ml-2">
              <li class="text-sm">Click position name to edit and change the name on all schedules (past and future).</li>
              <li class="text-sm">Default sort order of positions is alphabetical. You can change sort order by dragging positions using the [<font-awesome-icon icon="bars" size="xs" />] icon</li>
            </ul>
            <p class="text-red-700 text-sm">* Only the main manager on the account can delete positions.</p>
          </div>
        </div>
        <!-- ==================================== ./Edit position ==================================== -->

        <!-- ==================================== Delete position ==================================== -->
        <div v-if="activeItem == 'delete'">
          <div class="flex items-center justify-between">
            <div>
              <h4 class="text-xl font-semibold">Current positions</h4>
              <a href="#" class="text-custom-primary" @click.prevent="sortAlphabetically">Sort alphabetically</a>
            </div>
            <button class="text-white py-2 px-12 rounded-full text-sm" type="button">Save</button>
          </div>

          <ul class="w-9/12 mx-auto my-10">
            <li v-for="(data, i) in modal.positions" :key="data.id" class="flex items-center justify-between border border-custom-primary mb-2 px-3 py-1 rounded-md">
              <a href="#">{{ data.position }}</a>
              <a href="#" @click.prevent="removePosition(data, i)"><font-awesome-icon :icon="['far', 'trash-alt']" class="text-red-700" /></a>
            </li>
          </ul>

          <div class="text-center">
            <button class="text-white py-2 px-12 rounded-full text-sm" type="button">Save</button>
          </div>
          
          <div class="information mt-5 pt-3">
            <h4 class="text-xl mb-2">Information</h4>
            <ul class="ml-2">
              <li class="text-sm">Default sort order of positions is alphabetical. You can change sort order by dragging positions using the [<font-awesome-icon :icon="['far', 'trash-alt']" size="xs" class="text-red-700" />] icon</li>
            </ul>
            <p class="text-red-700 text-sm">* Only the main manager on the account can delete positions.</p>
          </div>
        </div>
        <!-- ==================================== ./Delete position ==================================== -->
      </modal>
      <modal v-model="modal.showEditPosition" class="modal-edit-delete-positions text-center" size="md:w-4/12" title="Edit this position">
        <p class="w-2/3 mx-auto my-3">This edited position name will display for all existing {{ modal.reqEditPositionDisplay }} shifts in all schedules including PAST schedules.</p>
        <form @submit.prevent="editPosition">
          <input class="appearance-none block w-full rounded py-1 px-4 mb-4 leading-tight focus:outline-none" type="text" v-model="modal.reqEditPosition.position" required>
          <div>
            <button class="text-white text-center py-2 rounded-full bg-custom-primary w-32" type="submit">Ok</button>
            <button class="text-white text-center py-2 rounded-full bg-red-700 w-32 ml-2" type="button" @click.prevent="modal.showEditPosition = !modal.showEditPosition">Cancel</button>
          </div>
        </form>
      </modal>
    </div>
  </div>
</template>

<script>

import Modal from '../shared/Modal'
import Loader from '../shared/Loader'
import axios from 'axios'
import draggable from 'vuedraggable'
import moment from 'moment'
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'

export default {
	components: {
		Modal,
    Loader,
    draggable,
    DatePicker
	},
	data() {
		return {
      // tabs
      activeItem: 'delete',
      isLoader: false,

      searchKeyword: '',
      searchTimer: null,

			positions: {},
			employees: {},
			selectedPosition: '',
			selectedPositions: [], // selected thru checkboxes

			isColumnTickAll: false,		// when ticked all checkbox under that column will be un/checked
      isColumnTickAll: [],		// when ticked all checkbox under that column will be un/checked
      checkEmpPositions:[],
      editEmpPositiondata: {
        pay_rate : '15',
        alert_date : '20-20-2020',
        comment: '',
        position:{}
      },
			modal: {
        editEmpPositionModal: false,
				addEditPositions: false,
				editDeletePositions: false,
        showEditPosition: false,
        // The reason I put two for this to avoid reactive behaviour, look for alternative for this
        reqEditPositionDisplay: '',
        reqEditPosition: '',

        showIndexEditPosition: false,
        // The reason I put two for this to avoid reactive behaviour, look for alternative for this
        reqEditIndexPositionDisplay: '',
        reqEditIndexPosition: '',

				positions: [],
				addPosition: {},
				trashedPositions: {},
			},

      mapEmployeePosition: [],
      mapEmployeePosition1: [],
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
  watch:{
    checkEmpPositions:{
      handler(val){
        this.defaultCheckPositions();
      }
    }
  },


	created() {
		this.indexEmployee()
    this.indexPosition('index-position')
    let vm = this;
    setTimeout(() => {
        vm.defaultCheckPositions();
    }, 3000);
  },

  mounted(){
    let vm = this;
  },
	methods: {
    defaultCheckPositions(){
      let vm = this;
      console.log (vm.positions.length, 'watch');
      let pos_length =  vm.positions.length;
      vm.mapEmployeePosition.map((data, index) =>{
      if(data.length == pos_length){
        vm.checkEmpPositions[index] = true
      }
      else{
       vm.checkEmpPositions[index] = false
      }
    })
    },
    setPreference(value){
           if(value.preference == null ){
        value.preference = 'position-prefer';
      }
           if(value.preference =='position-no-preference' ){
        value.preference = 'position-prefer';
      }
      else if(value.preference == 'position-prefer'){
        value.preference = 'position-dislike';
      }
      else {
       value.preference = 'position-no-preference';
      }
    },
    TogglePosition(value)
    {
    if (myElement.bgColor == 'red')
      { myElement.bgColor = NormalColor;
      FormEl.value = "N";
      }
    else if (myElement.bgColor == PreferColor)
      { myElement.bgColor = DislikeColor;
      FormEl.value = "D";
      }
    else
      { myElement.bgColor = PreferColor;
      FormEl.value = "P";
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
    // edit employee position
    editEmpPosition(data, index){
      let vm = this;
      vm.isLoader = true
      axios.get(`/api/employee/${data.id}/positiondata`)
          .then((res) => {
           vm.editEmpPositiondata = res.data.data
            vm.modal.editEmpPositionModal = true;
            vm.isLoader = false
          })
     
    },
    // Save edit employee position
    editEmpPositionForm(){
      let vm = this;
      vm.isLoader = true
      axios.post(`/api/employee/${vm.editEmpPositiondata.id}/positiondata` ,  vm.editEmpPositiondata  )
          .then(() => {
          vm.editEmpPositiondata = {};
          vm.modal.editEmpPositionModal = false;
          vm.isLoader = false

          })
    },
    /**
     * Search for 
     * @return {[type]} [description]
     */
    async search() {
      let vm = this
      if (vm.searchKeyword == " ") return false
      if ( vm.searchTimer ) {
        clearTimeout(vm.searchTimer)
        vm.searchTimer = null
      }
      try {
        vm.searchTimer = setTimeout(async() => {
          vm.isLoader = true
          let employee = await axios.get(`/api/employeess-search?kw=${vm.searchKeyword}`)
          vm.employees = employee.data
          vm.mapEmployeePosition = []
          for (let i=0; i<vm.employees.length; i++) {
            let positions = await axios.get(`/api/employee/${vm.employees[i].id}/positions`)
            let arr = []
            positions.data.forEach(val => arr.push(val.position_id))
            vm.mapEmployeePosition.push(arr)
          }
          vm.isLoader = false
        }, 500)
      } catch (e) {
        vm.$swal({
          icon: 'error',
          title: 'Error!',
          showConfirmButton: false,
          timer: 1500
        })
        vm.isLoader = false
      }
    },

    /*
      | =======================================
      |  [ Tabs ]
      |  @Note - create component for this
      | =======================================
      */
    isActive (menuItem) {
      let vm = this
      return vm.activeItem === menuItem
    },
    setActive (menuItem) {
      this.activeItem = menuItem
      this.indexPosition('modal-position')
    },

    /**
     * Remove Position
     * 
     * @param  Ojb data
     * @param  Int index    
     */
    // delete position
    removePosition(data, index) {
      let vm = this
      if (confirm(`Are you sure you want to remove ${data.position}?`)) {
        axios
          .get(`/api/positions/${data.id}`)
          .then(() => {
            vm.modal.positions.splice(index, 1)
            vm.indexPosition('index-position')
            vm.indexPosition('modal-position')
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
		 * Open modal
		 * 
		 * @param  String modal
		 */
		openModal(modal, data) {
			let vm = this
			switch(modal) {
				case 'AddEditPositions':
					vm.modal.addEditPositions = true
          vm.indexPosition('modal-position')
          vm.trashedPosition()
					break
        case 'EditDeletePositions':
          vm.modal.addEditPositions = false   // close the parent modal
          vm.modal.editDeletePositions = true
          break
        case 'EditPosition':
          vm.modal.showEditPosition = true
          vm.modal.reqEditPositionDisplay = data.position
          vm.modal.reqEditPosition = {
            id: data.id,
            position: data.position
          }
          break
			}
		},
    checkEmpPosition(index){
      let vm = this;
      let pos ;
      if(vm.checkEmpPositions[index] == true){
        vm.checkEmpPositions[index] = false
      }
      else if(vm.checkEmpPositions[index] == false){
         vm.checkEmpPositions[index] = true
      }
      else{
        vm.checkEmpPositions[index] = true
      }

      if(vm.checkEmpPositions[index] == true){
         vm.mapEmployeePosition[index] = [];
        var officersIds = [];
        vm.positions.forEach(function (officer) {
          officersIds.push(officer.id);
        });
        vm.mapEmployeePosition[index] = officersIds;
      }
      else{
         vm.mapEmployeePosition[index] = [];
      }
    },
		checkPosition(index, data) {
      let vm = this
      let pos ;
      if(vm.isColumnTickAll[index]==false){
        vm.isColumnTickAll[index]=true
      }
      else if(vm.isColumnTickAll[index]==true){
        vm.isColumnTickAll[index]=false
      }
      else{
         vm.isColumnTickAll[index]=true
      }

			if ( vm.isColumnTickAll[index]==true) {
				// avoid duplication
        if (vm.mapEmployeePosition.includes(index) === false) 
        vm.mapEmployeePosition.map(data =>{
          if(data.includes(index + 1 )){
          }
          else{
             data.push(index + 1)
          }
        })
          vm.isColumnTickAll[index] = true
			} else {
        if (vm.mapEmployeePosition.includes(index) === true) 
        vm.mapEmployeePosition.map(data =>{
        if(data.includes(index + 1)){
        pos = data.indexOf(index + 1)
            data.splice(pos ,1)
          }
          else{
           console.log('pop')
          }
        
        })
         vm.isColumnTickAll[index] = false
        // vm.mapEmployeePosition.map(data =>{
        //   data[0].pop(index)
        // })
      }
		},

    /**
     * Load employees
     */
    async indexEmployee() { 
      let vm = this

      vm.isLoader = true
 
      try {
        let employee = await axios.get('/api/positionemployeess')
       
        console.log('position data',employee.data);
          
              vm.employees = employee.data
              for (let i=0; i<vm.employees.length; i++) {
                if(vm.employees[i].enable_security_officer==1){
                  let positions = await axios.get(`/api/employee/${vm.employees[i].id}/positions`)
                  let arr = []
                  let arr1 = []
                  positions.data.forEach(val => arr.push(val.position_id))
                  positions.data.forEach(val => arr1.push({id: val.position_id , preference: val.preference}))



                  vm.mapEmployeePosition.push(arr)
                  vm.mapEmployeePosition1.push(arr1)
                }
              }

        vm.isLoader = false
      } catch (e) {
        console.log('Dev-e',e)
        
        vm.isLoader = false
      }
    },

    updateEmployeePositions() {
      let vm = this
      vm.isLoader = true
      vm.employees.forEach((val, index) => {
        axios
          .patch(`/api/employee/${val.id}/positions`, { positions: vm.mapEmployeePosition[index] })
          .then(res => {
            vm.isLoader = false
          })
          .catch(err => {
            vm.isLoader = false
          })
      })
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
          vm.positions = position.data.data
          break
        case 'modal-position':
          vm.modal.positions = position.data.data
          break
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
          vm.indexPosition('index-position')
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
        .post(`/api/positions/${vm.modal.reqEditPosition.id}`, {position: vm.modal.reqEditPosition.position})
        .then(res => {
          vm.$swal({
            icon: 'success',
            title: 'Successfully updated!',
            showConfirmButton: false,
            timer: 1500
          })
          vm.modal.reqEditPosition = {}
          vm.modal.showEditPosition = false
          vm.indexPosition('index-position')
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
      if (confirm('Are you sure you want to restore it?')) {
        axios
          .delete(`/api/positions-restore/${positionId}`)
          .then(res => {
            vm.modal.trashedPositions.splice(index, 1)
            vm.indexPosition('index-position')
            vm.indexPosition('modal-position')
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
        // awts
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

	}
}

</script>

<style lang="scss" scoped>
	@import '../../../sass/employees';

	.btn-add-edit-position {
		background: #2D477F;
	}
  .position-no-preference{
    background-color: #ffffff !important;
  }
  .position-prefer{
    background-color: #90F68E !important;
  }
  .position-dislike{
    background-color:#FFAEAE !important;
  }
</style>