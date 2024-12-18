<template>
  <div>
    <header-component />

    <nav class="employee mb-1">
      <ul class="flex justify-center w-5/12 mx-auto" style="margin-bottom:0px;">
      	<router-link tag="li" :to="{ name: 'report-index' }" class="w-1/4 mr-1 rounded-t-lg">
      		<a href="#" class="block text-center font-semibold text-lg pt-1 rounded-t-lg">Shift Details</a> 
      	</router-link>
      	<router-link tag="li" :to="{ name: 'officer-report' }" class="w-1/4 mr-1 rounded-t-lg">
      		<a href="#" class="block text-center font-semibold text-lg pt-1 rounded-t-lg">Reports</a>
      	</router-link>
      	<router-link tag="li" :to="{ name: 'parking-ticket' }" class="w-1/4 mr-1 rounded-t-lg">
      		<a href="#" class="block text-center font-semibold text-lg pt-1 rounded-t-lg" >Parking Tickets</a>
      	</router-link>
        <router-link tag="li" :to="{ name: 'report-alert' }" class="w-1/4 mr-1 rounded-t-lg">
          <a href="#" class="block text-center font-semibold text-lg pt-1 rounded-t-lg" >SOS Report</a>
        </router-link>
      </ul>
    </nav>
		<router-view></router-view>

	</div>
</template>

<style lang="scss" scoped>
a.active{
	color: #ffffff;
}
	@import '../../sass/employees';
</style>