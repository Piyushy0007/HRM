<template>
  <div class="client-add" style="padding-left: 15px;">
    <client-header-component />
   
    <nav class="employee mb-1 mt-10">
       <ul class="flex justify-center w-8/12 mx-auto" style="margin-bottom:0px;">
      	
      	<router-link tag="li" :to="{ name: 'HourlyReport' }" class="w-1/4 mr-1 rounded-t-lg">
      		<a href="#" class="block text-center font-semibold text-lg pt-1">Hourly Report</a> 
      	</router-link>
      	<router-link tag="li" :to="{ name: 'ticket' }" class="w-1/4 mr-1 rounded-t-lg">
      		<a href="#" class="block text-center font-semibold text-lg pt-1">Parking Tickets</a>
      	</router-link>
			  <router-link tag="li" :to="{ name: 'incident' }" class="w-1/4 mr-1 rounded-t-lg">
      		<a href="#" class="block text-center font-semibold text-lg pt-1">Incident Report</a>
      	</router-link>
      	<router-link tag="li" :to="{ name: 'sosreport' }" class="w-1/4 mr-1 rounded-t-lg">
      		<a href="#" class="block text-center font-semibold text-lg pt-1">SOS Report</a>  
      	</router-link>
      </ul>
    </nav>
	
		<router-view></router-view>

	</div>
</template>

<style lang="scss" scoped>
.bold{
	display: flex;
    justify-content: flex-start;
	span{
		margin: 5px 5px;
	}
}
.header-mainreport{
	border-bottom: 0;
    opacity: 1;
    padding-bottom: 0;
}
.client-add .add-client h1{
	font: normal normal normal 33px/40px Montserrat;
    font-family: "Source Sans Pro";
    color: #302369;
    font-weight: 600;
}
a.active{
	color: #ffffff !important;
}
.add-blue-button{
	width: 110px;
    height: 41px;
    background-color: #3B86FF !important;
    border-radius: 10px !important;
    opacity: 1;
    color: #ffffff !important;
    border-color: #3B86FF !important;
    font-size: 14px;
}
	@import '../../../sass/employees';
</style>
<script>
import HourlyReport from './HourlyReport.vue';
export default {

	components: {
	HourlyReport
	},
	data() {
		return {
		isChecked: false,
		selected: 'first',
        options: [
          { text: ' Reports', value: 'first' , class:'first'},
          { text: ' Parking Tickets', value: 'second' , class:'second' }
        ]
		}
	},
	computed: {

  },

  async created() {
	  this.checkrouter();
  },

 
	methods: {
				checkrouter(){
			if(this.$route.name == 'ticket'){
				 this.selected = 'second'
			}
			else{
					 this.selected = 'first'
			}
		},
		onChange(){
			if(this.selected == 'first'){
				this.$router.push('/cd/creport/ticket')
				}
			else{
				this.$router.push('/cd/creport/')
			}
		},
		datetolisten() {
			this.$root.$emit('message1')
		},
		
	
	},

}
</script>