<template>
  <div class="c-employee-index client-table client-add">
   
    <Loader msg="Processing ..." v-model="isLoader" />
    <div class="px-4 pb-4 add-client hourly-report">
      <div class="flex header" style="justify-content: space-between;">
        <!-- <h1 class="mb-4">Report</h1> -->
        <span class="date">{{ currentDate | moment('MMMM D, YYYY | HH:mm') }}</span>
        <!-- <button class="add-blue-button" @click="addclient()">Add Property</button> -->
          <div class="w-full md:w-4/12 pr-3 mt-2 mb-2 flex right-section">
            <div class="relative" style="width:50%;">
              <select id="location" @change="selectLocation()"  class="height-41 block appearance-none w-full py-1 px-4 pr-8 rounded-10 leading-tight focus:outline-none">
               <option value="">All Locations</option><option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AS">American Samoa</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District of Columbia</option><option value="FM">Micronesia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="GU">Guam</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MH">Marshall Islands</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="MP">Northern Mariana Islands</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PW">Palau</option><option value="PA">Pennsylvania</option><option value="PR">Puerto Rico</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VI">Virgin Islands</option><option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option><option value="AA">Armed Forces Americas</option><option value="AE">Armed Forces Europe, Canada, Africa and Middle East</option><option value="AP">Armed Forces Pacific</option>
                
              </select>
              <!-- <region-select id="location" @change="selectLocation()"  class="height-41 block appearance-none w-full py-1 px-4 pr-8 rounded-10 leading-tight focus:outline-none" v-model="region" placeholder='All Locations' :region="region" /> -->
            
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
              </div>
             
            </div>
           <a class="m-2 action-button download"  @click="downloadReport()" > <b-icon-download   />Download as pdf</a>
            
          </div>
      </div>

    </div>
    <div class=" report-container">
      <div class="reports" >
        <template v-if="index.report != 0">
        <div class=" flex" style="">  
       
            <div class="flex"  v-for="data  in index.report" :key="data.id" style="margin-right:20px;">
              <div class="card" >
                <div class="card-body">
                  <div class="flex date-section">
                    <h5 class="card-title"> 
                      <span v-for="type in modal.allsubtypeincident" :key="type.id">
                        <template v-if="data.report_subtype_id == type.id ">{{type.report_type_name}}</template>
                      </span>
                     </h5>
                    <input type="checkbox" :id="`checkbox-`+data.id" :name="`checkbox-`+data.id" :value="data.id">
                  </div>
                  <div class="upper-section flex">
                    <img class="card-img-top" src="https://placekitten.com/50/50" alt="Card image cap" />
                    <div class="info-section">
                      <p class="card-text name"> Name : {{data.employee[0].firstname}} {{data.employee[0].lastname}}</p>
                      <p class="card-text id-no"> ID NO : {{data.employee[0].id}}</p>
                      <p></p>
                    </div>
                  </div>
                  <div class="lower-section">
                    <textarea class="desc" readonly :value="data.description" >
                    
                    </textarea>              
                  </div>
                </div>
              </div>
            </div>
         
      </div>
       </template>
        <template v-else>
          No Records Found
        </template>
      </div>
    </div>
     <b-modal centered  no-close-on-esc hide-header-close class="modal-add-new-employee" id="datepicker" title="Date picker" style="margin:0 auto;">
			<b-calendar
				selected-variant="success"
				today-variant="info"
				nav-button-variant="primary"
				v-model="modal.date"
			>
			</b-calendar>
		
			<template #modal-footer="{ }">
				<div class="text-center mt-2 mb-2" style="margin:0 auto;">
    				<button @click="sendDate()" class="text-white py-3 px-12 rounded-full  bg-custom-primary" type="submit">Set</button>
          		</div>
    		</template>
  </b-modal>



  </div>
</template>
<style lang="scss" scoped>

</style>
<script>

import Vue from 'vue';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
// import 'bootstrap/dist/css/bootstrap.css'
// import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'bootstrap-vue/src/components/form-datepicker'
import 'bootstrap-vue/src/components/calendar'
import moment from 'moment'
import axios from 'axios'

import Modal from '../shared/Modal'
import Loader from '../shared/Loader'
import { BCalendar } from 'bootstrap-vue'
import { BFormDatepicker } from 'bootstrap-vue'
import datetime from 'vuejs-datetimepicker';
import Datepicker from 'vuejs-datepicker';
import VueTimepicker from 'vue2-timepicker'
import 'vue2-timepicker/dist/VueTimepicker.css'

Vue.component('b-form-datepicker', BFormDatepicker)

export default {
	components: {
		BootstrapVue,
		IconsPlugin,
		
		Modal,
		Loader,
		BCalendar,
		datetime ,
		Datepicker,
		VueTimepicker 
	},
	data() {
		return {
      showdatepicker: false,
      showmodal: false,
      currentDate: new Date(),
      hourlydate : new Date(),
      user_id:'',
      index:{
        report:{}
      },
      modal:{
        date:'',
        allsubtypeincident: []
      },
      isLoader: false,
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
    this.modal.date = moment(new Date()).format('YYYY-MM-DD');
    await vm.allsubtypereportincident()
    await vm.indexReport()
   
      // vm.isLoader = true
  },
  mounted(){
    this.$root.$on('message1' ,() => {
      this.showdatepicker = true
      this.opendatepicker();
    })
  },
	methods: {
    downloadReport(){
      window.print();
    },
    selectLocation() {
    var x = document.getElementById("location").value;
    let vm = this;
        axios
          .post(`/api/incidentfilter`, {search: x, report_type_id: 2,client_id: this.user_id , current_date:  vm.modal.date}) 
          .then(res => {
            if(res.data.status != true){
              
             vm.index.report= [];
            }
            else{
             vm.index.report = res.data.data
            }
           
          })
          .catch(err => {
            
          })
      

  },
    sendDate(){
       var y = document.getElementById("location").value;
      		let vm = this;
          axios.post(`/api/incidentfilter`, {search: y,current_date : vm.modal.date , client_id : this.user_id, report_type_id: 2}) 
          .then(res => {
            if(res.data.status != true){
              
            }
            else{

            
            vm.$swal({
                  icon: 'success',
                  title: 'Data Fetched Successfully!',
                  showConfirmButton: false,
                  timer: 1500
                  })
                this.$bvModal.hide('datepicker');
                // this.destroystyle();
                vm.index.report = res.data.data
            }
		  });
     
    },
    opendatepicker(){
        this.inject_material_fonts_and_icons_into_header();
        setTimeout(() => {
              this.$bvModal.show('datepicker')
          }, 1000);
	  },
    destroystyle(){
          $(
      'link[href="https://unpkg.com/bootstrap/dist/css/bootstrap.min.css"]'
    ).remove();
    $(
      'link[href="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css"]'
    ).remove();
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
    /**
     * List all report
     */
    indexReport() {
      let vm = this

      vm.isLoader = true
      this.hourlydate = moment(this.hourlydate).format('YYYY-MM-DD');
      axios
        .post(`/api/clientincidentreport`,{current_date : this.hourlydate, client_id: this.user_id  })  
        .then(res => {
          if(res.data.status != true){
               vm.isLoader = false
               vm.index.report = [];
          }
          else{
            vm.index.report = res.data.data
            vm.isLoader = false
          }
         
        })
    },
    allsubtypereportincident(){
		let vm = this;
			axios
        .get(`/api/subtypes`)
        .then(res => {
          if(res.data.status != false){
			  vm.modal.allsubtypeincident = res.data.data;
          }
          else{
           vm.isLoader = false
          }
      
        })
	},

   


	}
}

</script>

<style lang="scss" scoped>
.hourly-report{
  .header{
    .date{
      margin: auto 0;
      font-size: 18px;
      font: normal normal normal 16px Montserrat;
      color: #302369;
      font-weight: 600;
    }
    .right-section{
      .download{
      color: #717171;
      svg{
        font-size: 22px;
          margin: 0 5px;
      
      }
      }
    }
  }
}
  .report-container{
    .reports{
      justify-content: flex-start;
      .card{
        width:100%;
        background: #FFFFFF 0% 0% no-repeat padding-box;
        box-shadow: 2px 4px 20px #00000026;
        border-radius: 10px;
        opacity: 1;
        margin: 15px 15px 15px 0px;
        min-height: 90px;
        border: none;
        overflow: hidden;
        padding-left: 5px;
        .card-body{
          .card-title{
            width: auto;
            border: 1px solid purple;
            border-radius: 10px;
            padding: 5px 5px;
            font-size: 16px;

          }
          .date-section{
            .card-title{
              // height: 25px;
              border: 1px solid #302369;
              border-radius: 7px;
              opacity: 1;
              /* top: 279px; */
              /* left: 344px; */
              width: auto;
              /* height: 18px; */
              text-align: center;
              font: normal normal normal 14px/18px Montserrat;
              /* letter-spacing: 0px; */
              color: #302369;
              opacity: 1;
            }
            justify-content: space-between;
            input{
              margin-top: 10px;
            }
          }
          .upper-section{
            img{
              width: 50px;
              height: 50px;
              border: 3px solid #FFFFFF;
              border-radius: 7px;
              opacity: 1;
            }
            .info-section{
              margin-left: 5px;
              p{
                margin-bottom: 0px;
                font-weight: 500;
              }
              .name{
                text-transform: capitalize;
              }
              .id-no{
                text-transform: uppercase;
              }
            }
          }
          .lower-section{
            .desc{
              background: #FFFFFF 0% 0% no-repeat padding-box;
              box-shadow: 2px 4px 20px #00000026;
              border-radius: 10px;
              opacity: 1;
              margin: 15px 15px 15px 0px;
              width: 100%;
              min-height: 47px;
              border: none;
              overflow: hidden;
              padding-left: 5px;
              display: block;
              &:focus{
                outline: none;
              }
            }
          }
        }
      }
    }
  }
	@import '../../../sass/client/employees';
</style>