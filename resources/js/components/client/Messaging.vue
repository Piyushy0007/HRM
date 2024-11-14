<template>
  
  <div>
  	<client-header-component />
		<Loader :msg="loader.msg" v-model="loader.is" />
	  <div style="background: #ffffff;padding:10px 0px;margin-top: -60px;    border-left: 2px solid #f9f9f9;">
		<div class="px-8 mt-4 client-messaging" style="background:#ffffff;">
			<div  class="flex " style="justify-content: space-between;">
				<h1 class="mb-4 message-label" style="font-family: Poppins;font-weight: bold;font-size: 22px; ">Messages</h1> 
				 	
				</div>
			<div class="headingoflist">
				 <div class="selectallbox" >
					 <input v-if="index.messages.length!= 0" v-model="allSelected" type="checkbox" @click="selectAll">
					 <span v-if="index.messages.length!= 0" class="select-label"> Select All </span>
					  	<b-button  v-if="index.messages.length!= 0 && allSelected" class="mx-3 trash-button" variant="success" @click="deleteAll()"> 
							<font-awesome-icon :icon="['far', 'trash-alt']" class="text-gray-500 font-size-24" />
				 		</b-button>
					 </div>
				
				<button @click="openThread()" class="add-blue-button" style="width:180px;"> Create new thread </button>
			</div>
			

		</div>
 
		<div class="px-8 mt-4">
			<div class="pb-6 tabletopbackground">
				<table class="w-full all-messages tabletopposition">
				<thead class="thed">
          <tr >
           <th class="text1one" style="width: 25%;">Name  
            </th>

           <th class="text1one" style="width: 30%;">Subject
           </th>
            <th class="text1one" style="width: 15%;padding-left: 0;"> Time  </th>
           <th class="text1one" style="width: 15%;padding-left: 0;"> Action  </th>
          </tr>
        </thead>
        
        
        <tbody style= "width: 77%;" >
					<template v-if="index.messages.length!= 0"  >
				<tr v-for="data in index.messages" :key="data.id" class="border-bottom-grey">
					
					
					
						<td  :class="data.messages_count != 0 ? 'bold-message name' : ' name'" style="width: 33%;">
							<router-link style="width: 100%; display: block; height: auto;" :to="{ name: 'cmsgchat', params: { id: data.groups.id , user_id: data.groups.user_id  }}">
							<div class="name-container" v-if="data.groups.user!=null || data.groups.user!= undefined">
								
								<span class=" pl-1 fullname" v-if="data.groups.user_type == apparray.employee">{{data.groups.user.firstname}} {{data.groups.user.lastname}}</span>
								<span class=" pl-1 fullname" v-else-if="data.groups.user_type == apparray.client ">{{data.groups.user.clientname ? data.groups.user.clientname : 'No Name'}} </span>
								<span class=" pl-1 fullname" v-else-if="data.groups.user_type== apparray.admin || data.groups.user_type=='App\\Admin' ">{{data.groups.user.firstname ? data.groups.user.firstname : 'No Name'}} </span>
								<span class=" pl-1 fullname" v-else >{{data.groups.user.firstname ? data.groups.user.firstname : 'No Name'}} </span>
							</div>
							</router-link>
							
						</td>
						<td :class="data.messages_count != 0 ? 'bold-message message' : 'message'" style="width: 30%;">
							<router-link style="width: 100%; display: block; height: auto;" :to="{ name: 'cmsgchat', params: { id: data.groups.id , user_id: data.groups.user_id }}">
							<span class="text" v-if="data.groups.message_type == 'text' ">{{data.groups.group_name}}</span>
							<span class="text" v-else-if="data.groups.message_type == 'image' ">Image</span>
							<span class="text" v-else>{{data.groups.group_name}}</span>
							</router-link>
						</td>
						<td style="width: 24%;">
						  <span class="time">{{data.groups.send_time}}</span>  
						</td>
						<td class=" delete text-right" @click='deleteView(data.groups)' >
							<b-button class="m-1 trash-button" variant="success"> 
								<font-awesome-icon :icon="['far', 'trash-alt']" class="text-gray-500 font-size-24" />
							</b-button>
						</td>
					</tr>


					</template>
					<template v-else>
						<tr>
							<td colspan="4">No Messages Found</td>
						</tr>
					</template>
					</tbody>
				</table>
				
			</div>
			

		</div>
	
        <!-- Send Message -->
        <modal v-if="modal.newthread"  style="display:block;" class="modal-add-edit-positions" size="md:w-7/12" title="Create new thread">
			<div class="pb-6 pt-10">
				<a href="#" @click="closeThread()" style="margin-top:-65px;opacity:1" class="btn-close absolute right-0 top-0">&times;</a>
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
					        	<option :value="`emp-${data3.id}`" v-for="(data3,i) in options.guards" :key="'A'+i">{{ `${data3.firstname} ${data3.lastname}` }}</option>
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
						<input type="hidden"  v-model="req.body.client_id" />
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
  </div>
</template>

<script>
	
import axios from 'axios'
import Loader from '../shared/Loader'
import Modal from '../shared/Modal'
import moment from 'moment'

export default {
	components: {
		Loader,
		Modal
	},
	data() {
		return {
			adminlist:'',
			currentpath : window.location.origin,
			apparray:{
				client:"App\\Client",
				admin:"App\\Admin",
				employee:"App\\Employee",
			},
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
			},
			req: {
				body: {
					recipients: [],
					subject: '',
					message: '',
					user_id: JSON.parse(localStorage.getItem('user')).id,
					user_type: 'App\\Client'
				}
			},
		}
	},
	created() {
		let vm = this
		vm.showEmployeesAndPositions()
		vm.getAdminList();
		vm.getMessages();
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
	methods: { 
		//delete all
		deleteAll(){
			let vm = this;
			axios
				.post(`/api/cdeleteselectedgroup`, {group_ids: this.checkedNames})
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
			let vm = this;
			axios
				.post(`/api/cdeletsinglegroup` , {group_id: message.id})
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
                	  console.log(this.index.messages[user], 'selectall')
                	  console.log(this.index.messages[user].groups.id, 'selectall')
                    this.checkedNames.push(this.index.messages[user].groups.id);
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
			let id = this.userid
			axios
				.post(`/api/cgroups`, {user_id: id, user_type: vm.apparray.client})
				.then(res => {
					if(res.data.status){
						let now = new Date();
						// now = moment(now).format('HH:mm:ss');
						
						vm.index.messages = res.data.data.filter(x=>{
							// console.log((moment(x.groups.send_time).isBefore(now, 'hour') ))
							// updated_at is  used here instead of sent_time because aman is not fixing the timestamp for sent_time, 
							// so I forcefully need to change 
							if((moment(x.groups.updated_at).isBefore(now , 'day') )){
								if((moment(x.groups.updated_at).isBefore(now , 'year') )){
									return x.groups.send_time = moment(x.groups.updated_at).format('MMM DD, YYYY');
									}else{
										return x.send_time = moment(x.groups.updated_at).format('MMM DD');
									}
								}
							else{
								return x.groups.send_time =  moment(x.groups.updated_at).fromNow();
							}
						});
					}
					else{
						vm.index.messages = [];
					}
				})

		},
		// open thread modal
		openThread(){
			let vm = this;
			vm.modal.newthread = true;
		},
		closeThread(){
			let vm = this;
			vm.modal.newthread = false;
		},
		// get admin list
		async getAdminList(){
		let vm = this
			// vm.loader.is = true

			try {
				let admins = await axios.get('/api/adminlist')
				vm.adminlist = admins.data.admin
				// vm.loader.is = false
			} catch (err) {
				console.log('error', err)
			}
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
				vm.options.managers = employee.data.filter(filt => filt.role === 'manager')
				vm.options.guards = employee.data.filter(filt => (filt.role === 'guard' && filt.added_by === vm.userid ))

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
				.post('/api/creategroup', reqBody)
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
					client_id: this.req.body.client_id,
					user_type: 'App\\Client'
				}	
					// vm.loader.is = false
					vm.modal.newthread = false;
					setTimeout(() => {
						vm.$router.go()
					}, 1600);
				
				})
		},
	}
}

</script>
<style lang="scss" scoped>
	.add-light-button{
    top: 94px;
    left: 1424px;
    width: 98px;
    height: 36px;
    border: 1px solid #919191;
    color: #919191;
    border-radius: 100px;
    opacity: 1;
		&:focus{
			outline: none;
		}
	}
 .client-messaging{
	 .message-label{
		font: normal normal normal 28px/40px Montserrat;
		font-family: "Source Sans Pro";
		color: #302369;
		font-weight: 600;
	 }
 }
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
						margin: 0 auto;
						img{
							width: inherit;
							height: inherit;
							border-radius: 50%;
						}
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