<template>
  <div>
		<Loader :msg="loader.msg" v-model="loader.is" />

		<header-component />

		<div class="px-16 mt-4">
			<div class="headingoflist">
				 <div class="selectallbox" >
					 <input v-if="index.messages.length!= 0" v-model="allSelected" type="checkbox" @click="selectAll">
					 <span v-if="index.messages.length!= 0" class="select-label"> Select All </span>
					  	<b-button  v-if="index.messages.length!= 0 && allSelected" class="mx-3 trash-button" variant="success" @click="deleteAll()"> 
							<font-awesome-icon :icon="['far', 'trash-alt']" class="text-gray-500 font-size-24" />
				 		</b-button>
					 </div>
				
				<button @click="openThread()" class="add-blue-button">Create new thread</button>
			</div>
			

		</div>

		<div class="px-16 mt-4">
			<div class="pb-6">
				<table class="w-full all-messages">
					<template v-if="index.messages.length!= 0"  >
					<tr v-for="data in index.messages" :key="data.id" class="mt-5 mb-2 border-bottom-grey w-12/12">
					
					<div class="upper flex pt-3">
					
						<td  :class="data.seen == 0 ? 'bold-message name px-4 w-2/12' : ' name px-4   w-2/12'">
							<router-link style="width: 100%; display: block; height: auto;" :to="{ name: 'chat', params: { id: data.user.id , type: data.userable_type }}">
							<div class="name-container">
								<span class="logo">
									<span class="image"></span>
								</span>
								<span class=" pl-1 fullname" v-if="data.userable_type=='employee'">{{data.user.firstname}} {{data.user.lastname}}</span>
								<span class=" pl-1 fullname" v-else-if="data.userable_type=='client'">{{data.user.clientname ? data.user.clientname : 'No Name'}} </span>
							</div>
							</router-link>
							
						</td>
						<td :class="data.seen == 0 ? 'bold-message message px-4  w-9/12' : 'message px-4  w-9/12'">
							<router-link style="width: 100%; display: block; height: auto;" :to="{ name: 'chat', params: { id: data.user.id , type: data.userable_type }}">
							<span class="text" v-if="data.message_type == 'text' ">{{data.message}}</span>
							<span class="text" v-else-if="data.message_type == 'image' ">Image</span>
							<span class="text" v-else>Null</span>
							</router-link>
						</td>
						
						<td class=" delete px-4 text-right  w-1/12" @click='deleteView(data)' >
							<b-button class="m-1 trash-button" variant="success"> 
								<font-awesome-icon :icon="['far', 'trash-alt']" class="text-gray-500 font-size-24" />
							</b-button>
						</td>
					</div>
					<div class="lower px-4 pb-2">
						<span class="time">{{data.send_time}}</span>  
					</div>
					
					</tr>


					</template>
					<template v-else>
						<tr>
							<td>No Messages Found</td>
						</tr>
					</template>
				</table>
				
			</div>
			

		</div>
	
        <!-- Send Message -->
        <modal v-model="modal.newthread" class="modal-add-edit-positions" size="md:w-7/12" title="Create new thread">
			<div class="pb-6 pt-10">
			
				<ValidationObserver v-slot="{ handleSubmit }">

					<form novalidate @submit.prevent="handleSubmit(sendMessage)" ref="MessageForm">

					  <div class="flex mb-6 md:w-12/12">
					    <div class="md:w-1/12">
					      <label class="block md:text-right mb-1 pr-2" for="form-add-shift-description">To 
							  <!-- <span class="req_form_fields">*</span> -->
							  </label>
					    </div>
					    <div class="md:w-11/12">
		            <ValidationProvider rules="required" v-slot="v">
					        <select class="appearance-none block w-full rounded p-1 h-40 leading-tight focus:outline-none border border-custom-border" v-model="req.body.recipients" multiple>
					        	<option value="everyone">EVERYONE</option>
					        	<option disabled>----------------</option>
					        	<option value="all-managers">All Managers</option>
					        	<option :value="`pos-${data1.id}`" v-for="data1 in options.positions" :key="data1.id">{{ `Every ${data1.position}` }}</option>
					        	<option disabled>----------------</option>
								<span  v-if="options.managers.length !== 0">
					        	<option :value="`emp-${data2.id}`" v-for="data2 in options.managers" :key="data2.id">{{ `Manager: ${data2.firstname} ${data2.lastname}` }}</option>
								</span>
					        	<option :value="`emp-${data3.id}`" v-for="(data3,i) in options.guards" :key="'A'+i">{{ `${data3.firstname} ${data3.lastname}` }}</option>
					        	<option disabled>----------------</option>
					        	<option :value="`client-${data4.id}`" v-for="data4 in options.clients" :key="'Client'+data4.id">{{ `${data4.clientname}` }}</option>

							</select>
		              <small class="text-red-600">{{ v.errors[0] }}</small>
		            </ValidationProvider>
					    </div>
					  </div>

					  <div class="flex mb-6  md:w-12/12">
					    <div class="md:w-1/12">
					      <label class="block md:text-right mb-1 pr-2" for="form-email-subject">Subject 
							  <!-- <span class="req_form_fields">*</span> -->
							  </label>
					    </div>
					    <div class="md:w-11/12">
		            <ValidationProvider rules="required" v-slot="v">
					        <input type="text" class="appearance-none block w-full rounded py-1 px-2 leading-tight focus:outline-none border border-gray-400" id="form-email-subject" v-model="req.body.subject">
					        <small class="text-red-600">{{ v.errors[0] }}</small>
		            </ValidationProvider>
					    </div>
					  </div>

					  <div class="flex mb-6 md:w-12/12">
					    <div class="md:w-1/12">
					      <!-- <label class="block md:text-right mb-1 pr-2">Message 
							  <span class="req_form_fields">*</span>
							  </label> -->
					    </div>
					    <div class="md:w-11/12">
		            <ValidationProvider rules="required" v-slot="v">
					        <textarea class="appearance-none block w-full rounded py-1 px-2 leading-tight focus:outline-none border border-gray-400" rows="10" v-model="req.body.message"></textarea>
					        <small class="text-red-600">{{ v.errors[0] }}</small>
		            </ValidationProvider>
					    </div>
					  </div>

			      <div class="flex md:w-12/12">
			      	<div class="md:w-1/12"></div>
			      	<div class="md:w-11/12">
			        	<button class="add-purple-button text-white py-2 rounded-lg text-sm bg-custom-button" type="submit">
							Send
						</button>
			      	</div>
			      </div>

		      </form>

		    </ValidationObserver>

			</div>
        </modal>

  </div>
</template>

<script>
	
import axios from 'axios'
import Loader from './shared/Loader'
import Modal from './shared/Modal'
import moment from 'moment'

export default {
	components: {
		Loader,
		Modal
	},
	data() {
		return {
			checkedNames:[],
			allSelected :false,

			index:{
				messages:[]
			},
			modal:{
				newthread: false
			},
			loader: {
				is: false,
				msg: 'Processing ...'
			},

			options: {
				positions: {},
				managers: {},
				guards: {},
				clients:{},
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
		vm.getMessages()
	},
	methods: { 
		//delete all
		deleteAll(){
			console.log(this.checkedNames , 'deleteall')
			let vm = this;
			axios
				.post(`/api/messagedeleteall`, {ids: this.checkedNames})
				.then(res => {
					
					if(res.data.status){
					vm.$swal({
					icon: "success",
					title: "All Message Deleted Successfully",
					showConfirmButton: false,
					timer: 2000,
				});
				vm.index.messages = [];
					}
					else{
						vm.$swal({
						icon: "error",
						title: "Message Not Deleted ",
						showConfirmButton: false,
						timer: 2000,
					});
					}

				})
		},
		// delete message
		deleteView(message){
			console.log(message.employee_id , 'message id')
			let vm = this;
			axios
				.post(`/api/messagedelete/${message.userable_id}/${message.userable_type}`)
				.then(res => {
					
					if(res.data.status){
					vm.$swal({
					icon: "success",
					title: "Message Deleted Successfully",
					showConfirmButton: false,
					timer: 2000,
				});
				vm.getMessages();
					}
					else{
						vm.$swal({
						icon: "error",
						title: "Message Not Deleted ",
						showConfirmButton: false,
						timer: 2000,
					});
					}

				})
		},
		    // select all checkbox
        selectAll: function() {
            this.checkedNames = [];
            this.allSelected = !this.allSelected
           if(this.allSelected){
                for (let user in this.index.messages) {
                    this.checkedNames.push(this.index.messages[user].userable_id);
                    this.allSelected = true
                }
				console.log(this.checkedNames, 'selectall')
           }
           else{
             this.checkedNames = [];
           }
        },
		// get messages
		getMessages(){
			let vm = this;
			axios
				.get('/api/messages')
				.then(res => {
					if(res.data.status){
						let now = new Date();
						// now = moment(now).format('HH:mm:ss');
						
						vm.index.messages = res.data.data.filter(x=>{
							console.log((moment(x.send_time).isBefore(now, 'hour') ))
							// updated_at is used instead of sent_time
							if((moment(x.updated_at).isBefore(now , 'day') )){
								if((moment(x.updated_at).isBefore(now , 'year') )){
									return x.send_time = moment(x.updated_at).format('MMM DD, YYYY');
									}else{
										return x.send_time = moment(x.updated_at).format('MMM DD');
									}
								}
							else{
								return x.send_time =  moment(x.updated_at).fromNow();
							}
						});
					}
					else{
						console.log(res.data)
						vm.index.messages = [];
					}
				})

		},
		// open thread modal
		openThread(){
			let vm = this;
			vm.modal.newthread = true;
		},
		/**
		 * This will show all/specified roles and positions
		 */
		async showEmployeesAndPositions() {
			let vm = this
			// vm.loader.is = true

			try {
				let position = await axios.get('/api/positions')
				vm.options.positions = position.data.data

				let employee = await axios.get('/api/employeess')
				let client = await axios.get('/api/client')
				vm.options.managers = employee.data.filter(filt => filt.role === 'manager')
				vm.options.guards = employee.data.filter(filt => filt.role === 'guard')
				vm.options.clients = client.data

				// vm.loader.is = false
			} catch (err) {
				console.log('error', err)
			}
		},

		sendMessage() {
			let vm = this
			let reqBody = vm.req.body

			// vm.loader = {
			// 	is: true,
			// 	msg: 'Sending ...'
			// }

			axios
				.post('/api/messages', reqBody)
				.then(res => {
					vm.$swal({
						icon: 'success',
						title: 'Successfully sent!',
						showConfirmButton: false,
						timer: 1500
					})
					// vm.loader.is = false
					vm.modal.newthread = false;
					vm.req.body = {
					recipients: [],
					subject: '',
					message: '',
				}	
					// vm.loader.is = false
					vm.modal.newthread = false;
					// setTimeout(() => {
					// 	vm.$router.go()
					// }, 1600);
					vm.getMessages()
				})
		},
	}
}

</script>
<style lang="scss" scoped>
.trash-button{
	&:focus{
		outline: none;
	}
}
.headingoflist{
	display:flex; justify-content: space-between;
	.selectallbox{
		display: flex;
		.select-label{
			top: 217px;
			left: 199px;
			// width: 61px;
			// height: 16px;
			text-align: left;
			letter-spacing: 0px;
			color: #373535;
			opacity: 0.7;
			font-size: 16px;
		}
		input{
			top: 214px;
			left: 173px;
			width: 22px;
			height: 22px;
			background: #FFFFFF 0% 0% no-repeat padding-box;
			border: 1px solid #959595;
			border-radius: 4px;
			opacity: 1;
			margin-right: 6px;
		}
	}
}
.all-messages{
	border-top: 1px solid #DEDEDE !important;
	.border-bottom-grey{
		border-bottom: 1px solid #DEDEDE !important;
		.upper{
			.bold-message{
				span.fullname{
					font-weight: 700 !important;
				}
				span.text{
					font-weight: 700 !important;
				}
			}
			.name{
				.name-container{
					display: flex;
				
				.logo{
					border-radius: 50%;
					display: block;
					width: 20px;
					height: 20px;
					background: #E6E6E6 0% 0% no-repeat padding-box;
					.image{
						// top: 271px;
						// left: 184px;
						width: 20px;
						height: 20px;
						background: transparent url(/images/prof.svg) 0% 0% no-repeat padding-box;
						opacity: 1;
						background-position: center;
						display: block;
					}
					
				}
				.fullname{
					// top: 271px;
					// left: 207px;
					// width: 69px;
					height: 14px;
					text-align: left;
					font: normal normal 14px Open Sans;
					letter-spacing: 0px;
					color: #000000;
					opacity: 1;
					text-transform: capitalize;
				}
				}


			}
			.message{
				.text{
					// top: 269px;
					// left: 303px;
					// width: 1205px;
					height: 36px;
					text-align: left;
					font: normal normal 16px Open Sans;
					letter-spacing: 0px;
					color: #131313;
					opacity: 1;
				}
			}
			.delete{
				.trash-button{
					&:focus{
						outline: none;
					}
				}
			}
		}
			.lower{
				.time{
					top: 304px;
					left: 181px;
					width: 61px;
					height: 19px;
					text-align: left;
					font: normal normal normal 16px Open Sans;
					letter-spacing: 0px;
					color: #373737;
					opacity: 1;
				}
		}

	}
}
</style>