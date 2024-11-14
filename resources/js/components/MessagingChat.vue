<template>
  <div>
		<Loader :msg="loader.msg" v-model="loader.is" />

		<header-component />

		<div class="px-16 mt-4">
			<div class="headingoflist">
				 <div class="selectallbox">
					<router-link :to="{ name: 'mlist'}">
					 <button class="add-light-button">Back</button>
					</router-link>
				</div>
				
				<button class="add-light-button" @click='deleteView(emp_id)'>
					<font-awesome-icon :icon="['far', 'trash-alt']" class="text-gray-500 font-size-24" />
					Delete Thread</button>
			</div>

		</div>
		<div class="px-16 mt-8 main_message_section" >
			<!-- <div class="chat_section" v-for="(data, index) in messages" :key="'A'+index"> -->
			<div class="chat_section">
				<div class="subject">Subject: {{messages.group_name}}</div>
				<div class="to flex"><div>To: </div> 
					<div class="tooltip1" v-if="messages.group_user.length > 2">
						<span v-for="(user , index) in  messages.group_user" :key="user.id" >
							<span class="text-transform-capitalize" v-if="index == 0"> {{user.groupable.firstname ?user.groupable.firstname : user.groupable.clientname}} - </span>
							<span class="text-transform-capitalize" v-if="index == messages.group_user.length - 1 ">{{user.groupable.firstname ?user.groupable.firstname : user.groupable.clientname}}</span>
										
						</span>
						<span class="tooltiptext1">
							<span class="text-transform-capitalize" v-for="user in messages.group_user" :key="user.id">
								 {{user.groupable.firstname ?user.groupable.firstname : user.groupable.clientname}}<br>
							</span>
							</span>
					</div>
					<div class="" v-else>
						<span v-for="user in messages.group_user" :key="user.id" class="span-margin">{{user.groupable.firstname ?user.groupable.firstname : user.groupable.clientname}}<span>, </span>
					</span>
					</div>
				
					</div>
			</div>
			<div class="messages-container mt-5 content scrollable" ref="messageDisplay" id="container" >
			<!-- <div v-if="messages.length != 0" > -->
			<!-- <div v-for="data in messages" :key="data.id" > -->
				<div class="messages flex m-5 py-1 px-5"  v-for="msg in messages.message" :key="msg.id" :class="msg.from=='admin'?'reverse-row':'no-row-reverse'">
				<span class="logo">
					<span class="image" >
							<img v-if="msg.messageable.client_image != undefined || msg.messageable.client_image != null" :src="currentpath+'/storage/'+ msg.messageable.client_image" />
					</span>
				</span>
				<div class="message-detail ml-2 p-2 mr-2" :class="msg.from=='admin'?'reverse-color':'no-color-reverse'" >
				<p v-if="msg.message_type =='text'" :class="msg.from=='admin'?'reverse-text':'no-text-reverse'">{{msg.message}}</p>
				<span  v-else-if="msg.message_type =='image'">
					<!-- <img v-for="(img, index) in msg.message" :key="index+'A'" :src="currentpath+'/storage/'+ img"  style="width: 100px;
    				height: 100px; margin: 10px;" /> -->
					<img :src="currentpath+'/storage/'+ msg.message"  style="width: 100px;
    				height: 100px; margin: 10px;" />
				</span>
				<!-- <img v-else-if="data.message_type=='image'" :src="data.message" /> -->
				<p v-else :class="msg.from=='admin'?'reverse-text':'no-text-reverse'">{{msg.message}}</p>
				</div>
				</div>
			<!-- </div> -->
			<!-- </div> -->
			<!-- <div v-else>
				No Data Found!
			</div> -->
			</div>
			<div class="reply-box flex mt-5">
				<div class="left-section">
					<span class="input-box">
						<textarea v-model="reply"  />
					</span>
					<span class="image-name-box">
						<label v-if="this.Images.selectedFile && this.Images.selectedFile.length == 1">{{this.Images.selectedFile[0].name}}</label>
						<label v-if="this.Images.selectedFile && this.Images.selectedFile.length > 1">{{this.Images.selectedFile.length}} Files Selected</label>
					</span>
				</div>
				<div class="right-section flex">
				<span class="logo" >
					<input type="file" multiple  @change="Images_onFileChanged">
					<button><b-icon-paperclip />
					</button>
				</span>
				<span class="send-button">

				<button @click="Images_onUpload()" class="add-purple-button text-white py-2 rounded-lg text-sm bg-custom-button" >
							Send
				</button>
				</span>
				</div>
				
			</div>
		</div>
	
  </div>
</template>
<script src="{{ asset('js/socket.io.js') }}"></script>
<script>
	import axios from 'axios'
import Loader from './shared/Loader'
import Modal from './shared/Modal'






export default {
	components: {
		Loader,
		Modal
	},
	data() {
		return {
			apparray:{
				admin :'App\\Admin'
			},
			user_id:'',
			currentpath : window.location.origin,
			emp_id:this.$route.params.id,
			route_user_id: this.$route.params.user_id,
			route_user_type: this.$route.params.user_type,
			userable_type:'',
			Images:{
				selectedFile : null
			},
			messages:[],
			reply:'',
			subject:'',
			userable_id:'',
			group_id:'',
			

			loader: {
				is: false,
				msg: 'Processing ...'
			},
		}
	},
	created() {
		let vm = this;
		this.user_id = this.userid
		vm.getMessages(this.emp_id, this.route_user_id, this.route_user_type);
		
	},
	mounted(){
		setTimeout(() => {
			
			this.scrollToEnd();
		}, 1000);
	},
	  computed: {
		messageid() {
		return this.$route.params.id;
		},
		userid(){
			 let user = localStorage.getItem('admin')
    		return JSON.parse(user).id
		}
	  } ,
	methods: { 
		// scroll to end of div 
		scrollToEnd(){
			
			// var messageDisplay = this.$refs.messageDisplay;
			// messageDisplay.scrollTop = messageDisplay.scrollHeight;

			var container = this.$el.querySelector("#container");
      		container.scrollTop = container.scrollHeight;
		},
		//send image 
		Images_onUpload() { 
			let vm = this;
			const mypostparameters= new FormData()
			mypostparameters.append('message_type', 'text' );
			// mypostparameters.append('image', this.Images.selectedFile, this.Images.selectedFile);
			if(this.Images.selectedFile){
				for( var i = 0; i <  this.Images.selectedFile.length; i++ ){
				let file =  this.Images.selectedFile[i];
				mypostparameters.append('files[' + i + ']', file);
					if(this.reply == '' ){
						mypostparameters.set('message_type', 'image' );
					}else{
						mypostparameters.delete('message_type');
					}
				}
			}
			mypostparameters.append('group_id', this.group_id );
			mypostparameters.append('user_id', this.user_id );
			mypostparameters.append('user_type', this.apparray.admin );
			mypostparameters.append('message', this.reply );
			mypostparameters.append('from', 'admin' );
			// mypostparameters.append('subject', this.subject );
			if(this.Images.selectedFile || this.reply){

			var socket = io.connect('http://3.142.74.2:8679/', { transports: ['websocket', 'polling', 'flashsocket'] });

			socket.emit('chat message', 'admin', this.emp_id, this.reply);  
			socket.on( 'receive', function( data ) {
			 //if(data.senderId!='admin'){
				  console.log('message received',data);
				  var geturl = window.location.href;
				  var getdata = geturl.split('/');
			    var getId = getdata[getdata.length-1];
			    var getUserId = getdata[getdata.length-2];

					vm.getMessages(getUserId, getId, 'recivecallDev');
					
					//}
			});

			axios.post('/api/sgroupmsg', mypostparameters).then(res => {
					if(res.data.status){
					vm.$swal({
					icon: "success",
					title: "Message Sent Successfully",
					showConfirmButton: false,
					timer: 2000,
				});
				this.Images.selectedFile = null;
				this.reply = '';
				//vm.getMessages(this.emp_id, this.route_user_id, this.route_user_type);
				

					}
					else{
						vm.$swal({
						icon: "error",
						title: "Message Not Sent ",
						showConfirmButton: false,
						timer: 2000,
					});
					}
				})
			}
			else{
						vm.$swal({
						icon: "error",
						title: "Message is empty",
						showConfirmButton: false,
						timer: 2000,
					});
			}

			},
		//image upload
		Images_onFileChanged(event){
			 {
				 this.Images.selectedFile = event.target.files;
			}
		},
		// delete message
		deleteView(id, type){
			
			let vm = this;
			axios
				.post(`/api/messagedelete` , {group_id:id})
				.then(res => {
					if(res.data.status){
					vm.$swal({
					icon: "success",
					title: "Message Deleted Successfully",
					showConfirmButton: false,
					timer: 2000,
				});
				vm.$router.push('/messaging/')
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

		// get messages
		getMessages(id, user_id, user_type){
			console.log('getmessage',id);
			console.log('getmessageuser',user_id);
			console.log('user_type',user_type);
			let vm = this;
			axios
				.get(`/api/groupuser/${id}/${user_id}`)
				.then(res => {
				console.log('res.data',res.data);
					if(res.data.status){
						vm.messages = res.data.data
						vm.subject = vm.messages.group_name
						vm.userable_id = vm.messages.user_id
						vm.userable_type = vm.messages.user_type
						vm.group_id = vm.messages.id
					}
					else{
						vm.messages = [];
					}

					setTimeout(() => {
							vm.scrollToEnd();
						}, 1000);

					
				})

		},
	}
}

</script>
<style lang="scss" scoped>
.tooltip1{
	position: relative;
	.tooltiptext1{
		  	visibility: hidden;
			width: auto;
			background-color: black;
			color: #fff;
			text-align: center;
			padding: 5px 0;
			border-radius: 6px;
			
			/* Position the tooltip text - see examples below! */
			position: absolute;
			z-index: 1;
			span{
				padding: 10px 10px;
				display: block;
				line-height: normal;
			}
	}
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip1:hover .tooltiptext1 {
  visibility: visible;
}
.span-margin{
	margin: 0px 5px 5px 5px;
    margin-bottom: 9px;
    padding: 0px 5px;
    display: inline-block;
}
.reverse-color{
	background: #E2F0FB 0% 0% no-repeat padding-box !important;
}
.reverse-text{
	text-align: right;
}
/* width */
::-webkit-scrollbar {
  width: 5px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey; 
  border-radius: 10px;
  margin-top:25px;
  
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background:#7D7D7D; 
  border-radius: 10px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #7D7D7D; 
}


.main_message_section{
	.chat_section{
		.subject{
			top: 263px;
			left: 171px;
			width: 1205px;
			height: 36px;
			text-align: left;
			font: normal normal normal 24px/16px Open Sans;
			letter-spacing: 0px;
			color: #131313;
			opacity: 1;
		}
		.to{
			top: 306px;
			left: 171px;
			width: auto;
			text-align: left;
			font: normal normal normal 24px/16px Open Sans;
			letter-spacing: 0px;
			color: #131313;
			opacity: 1;
			max-height: 300px;
			display: flex;
			justify-content: flex-start;
			.span-margin:last-child{
				span{
					display: none
				};
			}
		}
	}
	.messages-container{
		// height: 250px;
		border-top: 1px solid #DEDEDE !important;
		overflow-x: hidden;
		overflow-y: scroll;
		.reverse-row{
			flex-direction: row-reverse;
		}
		.messages{
				
				.logo{
					border-radius: 50%;
					display: block;
					width: 30px;
    				height: 25px;
					background: #E6E6E6 0% 0% no-repeat padding-box;
					.image{
						width: 25px;
    					height: 25px;
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
				.message-detail{
					top: 395px;
					left: 223px;
					width: auto;
					height: auto;
					min-height: 50px;
					background: #F1F1F1 0% 0% no-repeat padding-box;
					border-radius: 0px 10px 10px 10px;
					opacity: 1;
					min-width: 60px;
					p{
					top: 409px;
					left: 246px;
					width: 100%;
					// height: 64px;
				
					font: normal normal normal 15px/20px Open Sans;
					letter-spacing: 0px;
					color: #000000;
					opacity: 1;
					}
				}

		}
	}
	.reply-box{
		top: 830px;
		left: 172px;
		width: 100%;
		height: 69px;
		background: #FFFFFF 0% 0% no-repeat padding-box;
		border: 1px solid #BBBBBB;
		border-radius: 10px;
		opacity: 1;
		margin-bottom: 15px;
		.left-section{
			width: 80%;
			justify-content: space-between;
			display: flex;
			.input-box{
				width: 80%;
    			display: block;
				height: 100%;
				textarea{
					width: 100%;
					height: 100%;
					resize: none; 
					margin-left: 10px;
					&:focus{
						outline: none;
					}
				}
			}
			.image-name-box{
				width: 20%;
    			text-align: right;
				// overflow: scroll;
				padding: 7px 7px;
				margin: 0 auto;
			}
		}
		.right-section{
			width: 20%;
			.logo{
				width: 40%;
				position: relative;
				input{
					width: 90px;
					height: auto;
					margin-top: 20px;
					position: absolute;
					opacity: 0;
				}
				svg{
					width: 31px;
					height: 31px;
					opacity: 1;
					margin: 20px;
				}
			}
			.send-button{
				width: 60%;
				.add-purple-button{
					width: 90%;
					height: 40px;
					background: #302369 0% 0% no-repeat padding-box;
					border-radius: 30px;
					opacity: 1;
					color: #ffffff;
					margin: auto;
					margin-top: 13px;
					margin-right: 10px;
				}
			}
		}

	}
}
.headingoflist{
	display:flex; 
	justify-content: space-between;
	.add-light-button{
		width: 160px;
		height: 40px;
		border: 1px solid #919191;
		border-radius: 100px;
		text-align: center;
		font: normal normal normal 17px Open Sans;
		letter-spacing: 0px;
		color: #919191;
		opacity: 1;
		padding: 5px 5px;
		&:focus{
			outline: none;
		}
	}
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
					font-weight: 700;
				}
				span.text{
					font-weight: 700;
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
					font: normal normal 600 14px Open Sans;
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
					font: normal normal bold 16px Open Sans;
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
@media screen and (max-width: 1024px){
.messages-container{
	max-height: 240px;
}
}
@media screen and (min-width: 1024px){
.messages-container{
	max-height: 280px;
    height: 280px;
}
}


@media screen and (min-width: 1400px){
.messages-container{
	max-height: 400px;
}
}
@media screen and (min-width: 1440px){
.messages-container{
	max-height: 450;
}
}
</style>