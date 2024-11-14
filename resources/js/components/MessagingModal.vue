<template>
  <div>
		<Loader :msg="loader.msg" v-model="loader.is" />

		<header-component />

		<div class="px-4 mt-4">
			<div class="bg-custom-cardBg rounded-lg px-4 pb-6 pt-10 mb-16">

				<ValidationObserver v-slot="{ handleSubmit }">

					<form novalidate @submit.prevent="handleSubmit(sendMessage)" ref="MessageForm">

					  <div class="flex mb-2">
					    <div class="md:w-1/6">
					      <label class="block md:text-right mb-1 pr-2" for="form-add-shift-description">To <span class="req_form_fields">*</span></label>
					    </div>
					    <div class="md:w-5/6">
		            <ValidationProvider rules="required" v-slot="v">
					        <select class="appearance-none block w-full rounded p-1 h-40 leading-tight focus:outline-none border border-custom-border" v-model="req.body.recipients" multiple>
					        	<option value="everyone">EVERYONE</option>
					        	<option disabled>----------------</option>
					        	<option value="all-managers">All Managers</option>
					        	<option :value="`pos-${data1.id}`" v-for="data1 in options.positions" :key="data1.id">{{ `Every ${data1.position}` }}</option>
					        	<option disabled>----------------</option>
					        	<option :value="`emp-${data2.id}`" v-for="data2 in options.managers" :key="data2.id">{{ `Manager: ${data2.firstname} ${data2.lastname}` }}</option>
					        	<option :value="`emp-${data3.id}`" v-for="data3 in options.guards" :key="data3.id">{{ `${data3.firstname} ${data3.lastname}` }}</option>
					        </select>
		              <small class="text-red-600">{{ v.errors[0] }}</small>
		            </ValidationProvider>
					    </div>
					  </div>

					  <div class="flex mb-2">
					    <div class="md:w-1/6">
					      <label class="block md:text-right mb-1 pr-2" for="form-email-subject">Subject <span class="req_form_fields">*</span></label>
					    </div>
					    <div class="md:w-5/6">
		            <ValidationProvider rules="required" v-slot="v">
					        <input type="text" class="appearance-none block w-full rounded py-1 px-2 leading-tight focus:outline-none border border-gray-400" id="form-email-subject" v-model="req.body.subject">
					        <small class="text-red-600">{{ v.errors[0] }}</small>
		            </ValidationProvider>
					    </div>
					  </div>

					  <div class="flex mb-6">
					    <div class="md:w-1/6">
					      <label class="block md:text-right mb-1 pr-2">Message <span class="req_form_fields">*</span></label>
					    </div>
					    <div class="md:w-5/6">
		            <ValidationProvider rules="required" v-slot="v">
					        <textarea class="appearance-none block w-full rounded py-1 px-2 leading-tight focus:outline-none border border-gray-400" rows="10" v-model="req.body.message"></textarea>
					        <small class="text-red-600">{{ v.errors[0] }}</small>
		            </ValidationProvider>
					    </div>
					  </div>

			      <div class="flex">
			      	<div class="w-1/6"></div>
			      	<div class="w-5/6 text-center">
			        	<button class="text-white py-2 px-24 rounded-lg text-sm bg-custom-button" type="submit">Send</button>
			      	</div>
			      </div>

		      </form>

		    </ValidationObserver>

			</div>

			<div class="bg-custom-cardBg rounded-lg p-4">
	      <div class="flex justify-between items-center mb-2">
	        <h4 class="text-2xl font-semibold">Information</h4>
	        <a href="#" class="text-sm inline-flex items-center">
	          Help on this topic&nbsp;<font-awesome-icon icon="arrow-circle-right" />&nbsp;<strong>More</strong>
	        </a>
	      </div>

	      <ul class="list-inside">
	        <li>* Ctrl-click multiple names to send to more than one person</li>
	        <li class="my-2">- To send a message to employees working on a certain date use the <strong>Employee>List</strong> "Schedule Reminder" feature. <font-awesome-icon icon="arrow-circle-right" size="xs" />&nbsp;<strong class="text-sm">More</strong></li>
	        <li><span class="text-red-600">** <strong>No <u>"Reply To" address</u> <span class="text-black">has been set up.</span></strong></span> Setting up a reply-to email allows message recipients who receive that notice by email to reply directly back to your email.</li>
	      </ul>
			</div>
		</div>

  </div>
</template>

<script>
	
import axios from 'axios'
import Loader from './shared/Loader'

export default {
	components: {
		Loader
	},
	data() {
		return {
			loader: {
				is: false,
				msg: 'Processing ...'
			},

			options: {
				positions: {},
				managers: {},
				guards: {},
			},
			req: {
				body: {
					recipients: [],
					subject: '',
					message: ''
				}
			},
		}
	},
	created() {
		let vm = this
		vm.showEmployeesAndPositions()
	},
	methods: {
		/**
		 * This will show all/specified roles and positions
		 */
		async showEmployeesAndPositions() {
			let vm = this
			vm.loader.is = true

			try {
				let position = await axios.get('/api/positions')
				vm.options.positions = position.data

				let employee = await axios.get('/api/employeess')
				vm.options.managers = employee.data.filter(filt => filt.role === 'manager')
				vm.options.guards = employee.data.filter(filt => filt.role === 'guard')

				vm.loader.is = false
			} catch (err) {
				console.log('error', err)
			}
		},

		sendMessage() {
			let vm = this
			let reqBody = vm.req.body

			vm.loader = {
				is: true,
				msg: 'Sending ...'
			}

			axios
				.post('/api/messages', reqBody)
				.then(res => {
          vm.$swal({
            icon: 'success',
            title: 'Successfully sent!',
            showConfirmButton: false,
            timer: 1500
          })
					vm.req.body = {
					recipients: [],
					subject: '',
					message: '',
				}	
					vm.loader.is = false
					vm.$destroy();
					setTimeout(() => {
						vm.$router.go()
					}, 1600);
				})
		},
	}
}

</script>