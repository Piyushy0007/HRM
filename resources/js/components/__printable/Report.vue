<template>
  <div>

    <div class="c-header flex justify-between items-center px-2 py-5">
      <div class="w-2/3">
        <img src="/images/logo.png" alt="Eyewitness">

        <div class="mt-4">
					<h4 class="text-xl font-semibold leading-tight">Shifts & Hours Worked - By Employee</h4>
					{{ dateRange.start | moment('MMM. D, YYYY') }} - {{ dateRange.end | moment('MMM. D, YYYY') }}
        </div>
      </div>
      <div class="w-1/3 text-right">
        <span>Printed on {{ currentDate | moment('MMMM D, YYYY') }}</span>
      </div>
    </div>

		<div class="mx-2">
	    <table class="table-auto w-full" v-if="selectedReportType == 'summary-employee' || selectedReportType == 'detailed-employee'">
	    	<thead>
	    		<tr>
	    			<th class="border border-gray-500">Employee</th>
	    			<th class="border border-gray-500" v-if="selectedReportEmployee.detailed">Position</th>
	    			<th class="border border-gray-500">Shifts</th>
	    			<th class="border border-gray-500">Hours</th>
	    			<th class="border border-gray-500" v-if="showPayRateAndCost == 'true'">Payrate</th>
	    			<th class="border border-gray-500" v-if="showPayRateAndCost == 'true'">Cost</th>
	    		</tr>
	    	</thead>
	    	<tbody v-if="reportDetails.length === 0">
	    		<tr>
	    			<td :colspan="showPayRateAndCost == 'true' ? 5 : 3" class="border border-gray-500">No record!</td>
	    		</tr>
	    	</tbody>
	    	<tbody else>
	    		<tr v-for="data in reportDetails" :key="data.id">
	    			<td class="border border-gray-500 px-2">{{ data.name }}</td>
	    			<td class="border border-gray-500 text-center px-2" v-if="selectedReportEmployee.detailed">{{ data.position }}</td>
	    			<td class="border border-gray-500 text-center">{{ data.total_shifts }}</td>
	    			<td class="border border-gray-500 px-2 text-right">{{ data.total_hours }}</td>
	    			<td class="border border-gray-500 px-2 text-right" v-if="showPayRateAndCost == 'true'">{{ data.pay_rate }}</td>
	    			<td class="border border-gray-500 px-2 text-right" v-if="showPayRateAndCost == 'true'">{{ data.cost }}</td>
	    		</tr>
	    	</tbody>
	    	<tfoot>
	    		<tr>
	    			<th class="border border-gray-500 px-2 text-right" :colspan="selectedReportEmployee.detailed ? 2 : 1">Total</th>
	    			<th class="border border-gray-500">{{ metaData.total_shifts }}</th>
	    			<th class="border border-gray-500 px-2 text-right">{{ metaData.total_hours }}</th>
	    			<th class="border border-gray-500 px-2 text-right" v-if="showPayRateAndCost == 'true'">{{ metaData.totalPayRate }}</th>
	    			<th class="border border-gray-500 px-2 text-right" v-if="showPayRateAndCost == 'true'">{{ metaData.totalCost }}</th>
	    		</tr>
	    	</tfoot>
	    </table>
		<table class="table-auto w-full" v-else-if="selectedReportType == 'summary-position' || selectedReportType == 'detailed-position'">
	    	<thead>
	    		<tr>
	    			<th class="border border-gray-500">Position</th>
	    			<th class="border border-gray-500" v-if="selectedReportPosition.detailed">Employee</th>
	    			<th class="border border-gray-500">Shifts</th>
	    			<th class="border border-gray-500">Hours</th>
	    			<th class="border border-gray-500" v-if="showPayRateAndCost == 'true'">Payrate</th>
	    			<th class="border border-gray-500" v-if="showPayRateAndCost == 'true'">Cost</th>
	    		</tr>
	    	</thead>
	    	<tbody v-if="reportDetails.length === 0">
	    		<tr>
	    			<td :colspan="showPayRateAndCost == 'true' ? 5 : 3" class="border border-gray-500">No record!</td>
	    		</tr>
	    	</tbody>
	    	<tbody else>
	    		<tr v-for="data in reportDetails" :key="data.id">
	    			<td class="border border-gray-500 px-2">{{ data.position }}</td>
	    			<td class="border border-gray-500 text-center px-2" v-if="selectedReportPosition.detailed">{{ data.empolyee }}</td>
	    			<td class="border border-gray-500 text-center">{{ data.shifts }}</td>
	    			<td class="border border-gray-500 px-2 text-right">{{ data.hours }}</td>
	    			<td class="border border-gray-500 px-2 text-right" v-if="showPayRateAndCost == 'true'">{{ data.payrate }}</td>
	    			<td class="border border-gray-500 px-2 text-right" v-if="showPayRateAndCost == 'true'">{{ data.cost }}</td>
	    		</tr>
	    	</tbody>
	    	<tfoot>
	    		<tr>
	    			<th class="border border-gray-500 px-2 text-right" :colspan="selectedReportPosition.detailed ? 2 : 1">Total</th>
	    			<th class="border border-gray-500">{{ metaData.total_shifts }}</th>
	    			<th class="border border-gray-500 px-2 text-right">{{ metaData.total_hours }}</th>
	    			<th class="border border-gray-500 px-2 text-right" v-if="showPayRateAndCost == 'true'">{{ metaData.totalPayRate }}</th>
	    			<th class="border border-gray-500 px-2 text-right" v-if="showPayRateAndCost == 'true'">{{ metaData.total_cost }}</th>
	    		</tr>
	    	</tfoot>
	    </table>

    </div>

  </div>
</template>

<script>

import axios from 'axios'

export default {
	mounted() {
		this.generateReport()
	},
	data() {
		return {
			currentDate: new Date(),
			dateRange: {},
			reportDetails: {},
			metaData: {},
			showPayRateAndCost: '',
			selectedReportType: '',
			selectedReportEmployee: {
				summary: false,
				detailed: false
			},
			selectedReportPosition: {
				summary: false,
				detailed: false
			},
		}
	},
	methods: {
		async generateReport() {
			let vm = this,
					urlParams = new URLSearchParams(window.location.search)

			vm.showPayRateAndCost = urlParams.get('include-pr-and-cost') // show payrate and cost column if true
			vm.selectedReportType = urlParams.get('type')

			vm.dateRange = {
				start: urlParams.get('s'),
				end: urlParams.get('e')
			}

			try {

				let reportDetails

				// Trigger API for employee related
				if ( vm.selectedReportType == 'summary-employee' || vm.selectedReportType == 'detailed-employee' ) {

					reportDetails = await axios.get(`/api/report/shifts?start=${vm.dateRange.start}&end=${vm.dateRange.end}&position=${urlParams.get('p')}`)
					switch ( vm.selectedReportType ) {
						case 'summary-employee':
							vm.selectedReportEmployee = {
								summary: true,
								detailed: false
							}
							break
						case 'detailed-employee':
							vm.selectedReportEmployee = {
								summary: false,
								detailed: true
							}
							break
						default: //  
					} 

				} else if ( vm.selectedReportType == 'summary-position' || vm.selectedReportType == 'detailed-position' ) {
					reportDetails = await axios.get(`/api/report/shifts?start=${vm.dateRange.start}&end=${vm.dateRange.end}&position=${urlParams.get('p')}&reportType=position`)
					switch ( vm.selectedReportType ) {
						case 'summary-position':
							vm.selectedReportPosition = {
								summary: true,
								detailed: false
							}
							break
						case 'detailed-position':
							vm.selectedReportPosition = {
								summary: false,
								detailed: true
							}
							break
						default: //
					}
				}

				vm.reportDetails = reportDetails.data.data
				vm.metaData = reportDetails.data.meta		

				await Object.assign(vm.metaData, {
					totalCost: vm.reportDetails.data.reduce((acc, val) => acc + val.cost, 0),
					totalPayRate: vm.reportDetails.data.reduce((acc, val) => acc + val.pay_rate, 0)
				})

			} catch (e) {
				console.log('Error', e)
			}
			window.print();
		}
	}
}
</script>


<style lang="scss" scoped>
	// @import '../../../sass/print';
</style>
