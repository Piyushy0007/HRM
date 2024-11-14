<template>
  <div class="c-employee-notifications">

  	<Loader msg="Processing ..." v-model="isLoader" />
		
		<div class="px-4">
			<div class="flex items-center justify-between my-5">
				<button class="text-white py-2 px-16 rounded-lg text-sm btn-add-edit-position" type="button" @click.prevent="showEmployeeEmailsToggle(hasEmail)">Show users with {{ hasEmailLabel }}</button>
	      <div class="flex flex-wrap -mx-3">
	        <div class="w-full md:w-1/2 px-3">
            <div class="relative">
              <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none" type="text" placeholder="Find" v-model="searchKeyword" @keyup="search">
              <div class="absolute inset-y-0 right-0 flex items-center px-2 text-custom-border rounded-r border border-custom-border border-l-0">
                <font-awesome-icon icon="search" class="fill-current" />
              </div>
            </div>
	        </div>
	        <div class="w-full md:w-1/2 pr-3">
	          <div class="relative">
	            <select class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none" v-model="selectedPosition"  @change="filterResultViaPosition(selectedPosition)">
	              <option value="">All Positions</option>
	              <option v-for="data in positions" :key="data.id">{{ data.position }}</option>
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
            <th class="w-6"></th>
            <th class="w-20 text-sm leading-none px-1 py-2">Add/Edit</th>
            <th class="text-sm text-left">First</th>
            <th class="text-sm text-left">Last</th>
            <th class="text-sm text-left">E-mail</th>
            <th class="text-sm text-center">New schedule published</th>
            <th class="text-sm text-center">Published shift is changed</th>
	        </tr>
	      </thead>
	      <tbody>
	      	<tr class="sub-header">
	      		<td colspan="5"></td>
	      		<td class="text-center"><input type="checkbox"></td>
	      		<td class="text-center"><input type="checkbox"></td>
	      	</tr>
	      	<tr v-if="employees.length === 0" class="text-center">
	      		<td colspan="7">No result found!</td>
	      	</tr>
	      	<tr else v-for="(data, index) in employees" :key="data.id">
	      		<td class="text-center"><input type="checkbox"></td>
	      		<td class="text-center"><font-awesome-icon icon="pencil-alt" /></td>
	      		<td>{{ data.employee.firstname }}</td>
	      		<td>{{ data.employee.lastname }}</td>
	      		<td>{{ data.employee.email || '-----' }}</td>
	      		<td class="text-center">
	      			<input type="checkbox" v-model="mapEmployeePublishedNewSchedule[index]">
	      		</td>
	      		<td class="text-center">
	      			<input type="checkbox" v-model="mapEmployeePublishedScheduleShiftChanged[index]">
	      		</td>
	      	</tr>
	      </tbody>
	    </table>

      <div class="text-center my-12">
        <button class="text-white py-2 px-16 rounded-lg text-sm btn-add-edit-position" type="button" @click.prevent="updatePublish">Save Changes</button>
      </div>

      <div class="information mt-5 p-4 rounded-lg">
        <div class="flex justify-between items-center mb-2">
          <h4 class="text-2xl font-semibold">Information</h4>
          <a href="#" class="text-sm inline-flex items-center">
            Help on this topic&nbsp;<font-awesome-icon icon="arrow-circle-right" />&nbsp;<strong>More</strong>
          </a>
        </div>
        
        <ul class="ml-2 instructions">
          <li class="mb-1">Check box for each notification for each users and click the "Save" button.</li>
          <li class="mb-1">Check title row box to check/uncheck all users for that notification.</li>
          <li class="mb-1">Check box to the left of users to check/uncheck all notifications.</li>
          <li class="mb-1">Click pencil icon to edit the notifications with more details.</li>
          <li class="mb-1">Grid only shows users with emails added. Missing users? Edit users notifications and be sure their email was not set up as a text address.</li>
        </ul>

        <p class="mb-5">
        	<strong>* Text message addresses</strong> are not shown here and you should only be set up by the recipient
        </p>

        <ul>
          <li class="mb-1">
            <span class="inline-block w-20" style="background-color: #FFA700">&nbsp;</span>
            Changes not saved.
          </li>
        </ul>

      </div>
    </div>


		<modal v-model="modal.addEditPositions" class="modal-add-edit-positions" size="md:w-7/12" title="Add/Edit Positions">
			<div class="new-position px-6 py-6">
        <form @submit.prevent="addPosition">
  				<div class="flex items-center justify-between mb-5">
  					<h4 class="text-xl font-semibold">New Position</h4>
  					<button class="text-white py-2 px-16 rounded-full text-sm" type="submit">Add</button>
  				</div>
  				<input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none mb-1" type="text" v-model="modal.addPosition.position">
  				<p class="text-sm">After adding, set which users can work each position on the <strong>Users>Positions Grid</strong></p>
        </form>
			</div>

		  <div class="list-of-positions py-5 mb-4">
		  	<div class="flex justify-between items-center mb-5 px-6">
		  		<div class="flex items-center leading-none">
		  			<h4 class="text-xl font-semibold">Positions</h4>
		  		</div>
		  		<button class="text-white py-2 px-16 rounded-full text-sm" type="button" @click.prevent="openModal('EditDeletePositions')">Edit/Delete</button>
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
					<li v-else class="text-sm" v-for="(data, index) in modal.trashedPositions" :key="data.id">
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
			isLoader: false,
			searchKeyword: '',
      searchTimer: null,

      // This will display all employees that has email (default)
      hasEmail: 1,
      hasEmailLabel: 'NO EMAIL', // default

			positions: {},
			employees: {},
			selectedPosition: '',
			selectedPositions: [], // selected thru checkboxes

			isColumnTickAll: false,		// when ticked all checkbox under that column will be un/checked

			modal: {
				addEditPositions: false,

				positions: {},
				addPosition: {},
				trashedPositions: {},
			},

			mapEmployeePublishedNewSchedule: [],
			mapEmployeePublishedScheduleShiftChanged: []
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
	created() {
		this.indexEmployee()
		this.indexPosition('index-position')
	},
	methods: {
    /**
     * Search for 
     * @return {[type]} [description]
     */
    async search() {
      // awts
      let vm = this
      if (vm.searchKeyword == " ") return false
      if ( vm.searchTimer ) {
        clearTimeout(vm.searchTimer)
        vm.searchTimer = null
      }
      vm.searchTimer = setTimeout(async() => {
        vm.isLoader = true

        vm.mapEmployeePublishedNewSchedule = []
        vm.mapEmployeePublishedScheduleShiftChanged = []

        let employee = await axios.get(`/api/employee-search?kw=${vm.searchKeyword}&position_id=${vm.selectedPosition}&has_email=${vm.hasEmail}`)
        for (let i=0; i<employee.data.length; i++) {
          vm.mapEmployeePublishedNewSchedule.push(employee.data[i].employee.new_schedule_published)
          vm.mapEmployeePublishedScheduleShiftChanged.push(employee.data[i].employee.published_shift_is_changed)
        }
        vm.employees = employee.data
        vm.isLoader = false
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
				case 'AddEditPositions':
					vm.modal.addEditPositions = true
          vm.indexPosition('modal-positions')
          vm.trashedPosition()
					break
			}
		},

		checkPosition(index, data) {
			let vm = this		
			if ( vm.isColumnTickAll ) {
				// alert('true')
				// avoid duplication
      	if (vm.selectedPositions.includes(index) === false) vm.selectedPositions.push(index)
			} else {
				// alert('false')
				vm.isColumnTickAll[index] = true
			}
			
		},

    /**
     * Load employees
     */
    async indexEmployee(position='') { 
    	let vm = this
    	vm.isLoader = true
      try {
        let employees = await axios.get(`/api/employees?position_id=${position}&kw=${vm.searchKeyword}&has_email=${vm.hasEmail}`)
        console.log('notificaiton data',employees.data);
        
              vm.employees = employees.data
               for (let i=0; i<employees.data.length; i++) {
                  vm.mapEmployeePublishedNewSchedule.push(employees.data[i].employee.new_schedule_published)
                  vm.mapEmployeePublishedScheduleShiftChanged.push(employees.data[i].employee.published_shift_is_changed)
                }

       
        vm.isLoader = false
      } catch (e) {
        console.log(e)
      }
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
          vm.positions = position.data.data
          break
        case 'modal-positions':
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
            title: err.response.data.message,
            showConfirmButton: false,
            timer: 1500
          })
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

    /**
     * Update publish
     */
    updatePublish() {
    	let vm = this
    	vm.isLoader = true
    	vm.employees.forEach((val, index) => {
	    	let requestPayload = {
	    		arr_new_schedule_published: vm.mapEmployeePublishedNewSchedule[index],
	    		arr_published_shift_is_changed: vm.mapEmployeePublishedScheduleShiftChanged[index]
	    	}
    		axios
    			.patch(`/api/employee/${val.employee_id}/publish`, requestPayload)
    			.then(res => {
    				vm.isLoader = false
    			})
    			.catch(err => console.log(err.response))
    	})
    },

    /**
     * This will toggle users who has/hasn't emails
     * 
     * @param  bool hasEmail
     */
    async showEmployeeEmailsToggle(hasEmail) {
      let vm = this
      if (hasEmail) {
        vm.hasEmail = 0
        vm.hasEmailLabel = 'EMAIL'
      } else {
        vm.hasEmail = 1
        vm.hasEmailLabel = 'NO EMAIL'
      }
      vm.mapEmployeePublishedNewSchedule = []
      vm.mapEmployeePublishedScheduleShiftChanged = []
      await vm.indexEmployee()
    }

	}
}

</script>

<style lang="scss" scoped>
	@import '../../../sass/employees';

	.btn-add-edit-position {
		background: #2D477F;
	}

</style>