<template>
  <div>

    <div class="c-header flex justify-between items-center px-2 py-5">
      <div class="w-2/3">
        <img src="/images/logo.png" alt="Eyewitness">

        <div class="mt-4">
					<h4 class="text-xl font-semibold leading-tight">Week of {{ selectedDate | moment('MMMM D, YYYY') }}</h4>
					
        </div>
      </div>
      <div class="w-1/3 text-right">
        <span>Printed on {{ currentDate | moment('MMMM D, YYYY') }}</span>
      </div>
    </div>
		<div class="mx-2  c-schedules">
	    <table class="table-fixed w-full" ref="scheduletable">
        <thead>
          <tr>
            <th v-for="data in weekDays"  :key="data.id">
              {{ data | moment('dddd') }}
              <span class="block text-sm">{{ data | moment('MMM D') }}</span>
            </th>
          </tr>
        </thead>
        <tbody v-if="schedules.length !== 0">
          <tr v-for="(data) in schedules"  :key="data.id">
            <td class="text-center" v-for="(data1, index1) in 7"  :key="data1.id">
              <div v-if="extractAddedTo(data).added_to[index1] != 0">
                <strong class="block leading-tight" v-if="modal.layout.printview.position">{{ data.position.position }}</strong>
                <div class="text-sm" v-for="dtls in data.shiftDetails.data"  :key="dtls.id"  >
                  <font :color="dtls.shift.color">
                     {{ `${dtls.shift.start} - ${dtls.shift.end}` }}
                  </font>
                  <span class="block text-base text-transform-capitalize">
                    <font :color="dtls.shift.color">
                      <template v-if="modal.layout.printview.name == 'firstlast'">
                       
                          {{ `${dtls.employee.firstname} ${dtls.employee.lastname}` }}
                      </template>
                      <template v-else-if="modal.layout.printview.name == 'lastfirst'">
                          {{ `${dtls.employee.lastname}, ${dtls.employee.firstname}` }}
                      </template>
                      <template v-else-if="modal.layout.printview.name == 'firstl'">
                          {{ `${dtls.employee.firstname} ${dtls.employee.lastname.charAt(0)}.` }}
                      </template>
                      <template v-else-if="modal.layout.printview.name == 'flast'">
                          {{ `${dtls.employee.firstname.charAt(0)}.${dtls.employee.lastname} ` }}
                      </template>
                      <template v-else-if="modal.layout.printview.name == 'lastf'">
                          {{ `${dtls.employee.lastname},${dtls.employee.firstname.charAt(0)}. ` }}
                      </template>
                     
                  </font>
                  </span>
                  <span v-if="modal.layout.printview.number"  class="block text-base">
                  {{ `${dtls.employee.mobile}` }}
                  </span>
                  <span v-if="modal.layout.printview.description" class="block text-sm">
                 Description: {{data.description}}
                </span>
                <span v-if="modal.layout.printview.totalhours" class="block text-sm">
                 {{data.paid_hours}} hours
                </span>
                </div>
                
              </div>
              <div v-else-if="extractAddedTo(data).added_to[index1] == 0 && modal.layout.printview.off == true">
                OFF
              </div>
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="7" class="text-center">No Records Found</td>
          </tr>
        </tbody>
        <tfoot v-if="!modal.layout.printview.dateheader">
          <tr>
            <th v-for="data in weekDays"  :key="data.id">
              {{ data | moment('dddd') }}
              <span class="block text-sm">{{ data | moment('MMM D') }}</span>
            </th>
          </tr>
        </tfoot>
      </table>

    </div>
  </div>
</template>

<script>

import Loader from '../shared/Loader'
import moment from 'moment'
import axios from 'axios'

import VueTimepicker from 'vue2-timepicker'
// import 'vue2-timepicker/dist/VueTimepicker.css'

export default {
	components: {
    Loader,
    VueTimepicker,
	},
	data() {
		return {
	  currentDate: new Date(),
      isLoader: false,
      selectedDate: moment().startOf('isoWeek'),
      // chosenWeek: null,
      // This is gonna be used on generating week range
      dateRange: {
        prev: [],
        next: []
      },

      // index
      weekDays: [],
      schedules: {},

      modal: {
        changelayout: false,
        //export
        export : false,
        exportData : {
          CheckedNames:[],
          position: '',
          name : true ,
          firstname: false,
          lastname: false,
          number: true,
          payrate: true,
          filename:'EXPORT'
        },
        // generic
        positions: {},
        employees: {},
        layout: {
          screenview:{
          },
          printview:{
          }
        },

        // specific
        shifts: {
          open: false,
          requestBody: {
            color : 0
          },
          weekDays: [],
          selectedDays: [],
          activeIndexEmployee: [],
          // This is un/ticking checkbox
          checkBoxOption: {
            selectAll: false,
            unSelectAll: false
          },

          startTime: {},
          endTime: {},
        }
      },

      // validate time interval
      isValidTimeInterval: null,

		}
	},
  created() {
	this.indexSchedule();
  },
  mounted() {
    let vm = this
    vm.displayWeek()
    vm.displayPrevNextWeek()
  },
	methods: {
    printSchedule(){
      setTimeout(() => {
         window.print();
      }, 2000);
     
    },



    /**
     * Generate prev/next week
     */
    displayPrevNextWeek() {
      let vm = this
      let prevWeek = vm.selectedDate.clone().startOf('isoWeek'),
          nextWeek = vm.selectedDate.clone().startOf('isoWeek')

      // based on weekstart start of week, then just add +7 days to determine the next week, etc 
      let prev = [],
          next = []
      for (let i=0; i<6; i++) {
        prev.push( prevWeek.subtract(7, 'days').format('YYYY-MM-DD') )
        next.push( nextWeek.add(7, 'days').format('YYYY-MM-DD') )
      }
      vm.dateRange = {
        prev: prev.reverse(),
        next: next
      }
    },

    /**
     * Set Prev/Next Week
     * 
     * @param String action [i.e. prev | next]
     */


    displayWeek() {
      let vm = this

      let weekStart = vm.selectedDate.clone().startOf('isoWeek'),
          weekEnd = vm.selectedDate.clone().endOf('isoWeek'),
          days = []

      for (let i=0; i<=6; i++) {
        days.push( moment( weekStart).add(i, 'days').format('YYYY-MM-DD') )
      }
      vm.weekDays = days
    },

    extractAddedTo(data) {
      let weekDays = data.added_to.split('~')
      return {
        added_to: weekDays,
      }
    },



    // fetch Layout
    fetchlayout(){
      let vm = this
      
      axios
        .post('/api/schedules/getlayout' , {user_id:'1'})
        .then(res => {
         
    vm.modal.layout.printview = JSON.parse(res.data.data.printview.meta_value);
     vm.$refs.scheduletable.style.fontSize =  vm.modal.layout.printview.font;
    vm.modal.layout.screenview = JSON.parse(res.data.data.screenview.meta_value);
    vm.printSchedule();
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
     * Show schedules
     */
    async indexSchedule(positionId='') {
      let vm = this
      let selectedDate = vm.selectedDate.format('YYYY-MM-DD')
      vm.currentPosition = positionId ; 
      if(vm.currentPosition == ''){
        vm.currentPosition = 0;
      }
      vm.isLoader = true
      let schedules = await axios.get(`/api/shifts?selectedDate=${selectedDate}&position_id=${positionId}`)
      await schedules.data.forEach(el => el.shiftDetails = null)

      for (let i=0; i<schedules.data.length; i++) {
        let employeeShift = await axios.get(`/api/shift/${schedules.data[i].id}/employee`)
        schedules.data[i].shiftDetails = employeeShift.data
      }
      vm.schedules = schedules.data
	  vm.isLoader = false;
	  vm.fetchlayout()
    },

    // ====================================================================
    //  [Modal] - Add shift
    // ====================================================================
    //
    /**
     * Validate time interval
     * 
     * @param  time start
     * @param  time end
     */
    validateTimeInterval(start, end) {
      let vm = this
      let startTime = moment(`${start.hh}:${start.mm} ${start.A}`, ['h:mm A']),
          endTime = moment(`${end.hh}:${end.mm} ${end.A}`, ['h:mm A'])

      let duration = moment.duration(endTime.diff(startTime)),
          hours = duration.asHours()

      // if total hours is equal to 12, make it positive regardless who's first, am or pm
      // This is for visual only
      if ( hours == -12 ) {
        vm.test = Math.abs(hours).toFixed(2)
        vm.isValidTimeInterval = true
      } else 


      /*if ( startTime == undefined || endTime == undefined || !startTime.isValid() ||  !endTime.isValid() || !startTime.isBefore(endTime) || endTime.isBefore(startTime) ) {*/
      if ( startTime == undefined || endTime == undefined || !startTime.isValid() || !endTime.isValid() || startTime.isSame(endTime) || !startTime.isBefore(endTime) ) {
      // if ( startTime == undefined || endTime == undefined || !startTime.isValid() || !endTime.isValid() || startTime.isSame(endTime) ) {
        vm.isValidTimeInterval = false
      } else {
        vm.isValidTimeInterval = true
        vm.test = hours.toFixed(2)
      }
    },
    captureErrorTimeInterval(eventData) {
      console.log(eventData)
    },
    errorHandler(eventData) {
      console.log('eventData', eventData)
    },
	}
}

</script>

<style lang="scss" scoped>
	@import '../../../sass/schedules';
  .c-send-notifications {
    input:checked + svg {
      display: block;
    }    
  }
</style>
