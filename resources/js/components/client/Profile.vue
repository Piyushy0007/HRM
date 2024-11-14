<template>
  <div class="c-employee-index client-table client-add">
    <Loader msg="Processing ..." v-model="isLoader" />
    <div class="px-32 pb-4 add-client my-16 mx-64">
      <div class="flex " style="justify-content: space-between;">
        <h1 class="mb-4">Profile</h1>
       <router-link v-if="readonlyprofile" :to="{ name: 'employees'}">
					 <button class="add-light-button">Back</button>
	    </router-link>
       <div v-else @click="readonlyprofile=true;" >
					 <button class="add-light-button">Cancel</button>
	    </div>
      </div>
     <div class="profile_section">
      <div class="">
        <div class="rounded-lg" v-show="!readonlyprofile">
         
          <div class="section1 flex">
           <div class="info m-3"> 
            <b-avatar
              badge
               v-show="show_default_img"
              badge-variant="light"
              :src="this.currentpath+'/storage/'+modal.editClient.client_image"
              size="6rem"
             
            >
              <template #badge>
                <input type='file' accept="image/*" id='fileInput2' name='fileInput2'  @change="Profile_onFileChanged">
                  <label class="m-0" for="fileInput2" slot="upload-label">
                  <b-icon class="blue-bicon" icon="camera"></b-icon> 
                </label>
              </template>
            </b-avatar>
            <b-avatar
              v-show="hide_default_img"
              badge
              badge-variant="light"
              :src="modal.editClient.client_image"
              size="6rem"
              
            >
              <template #badge>     
                <input type='file' accept="image/*" id='fileInput3' name='fileInput2'  @change="Profile_onFileChanged">
                  <label class="m-0" for="fileInput3" slot="upload-label">
                  <b-icon class="blue-bicon" icon="camera"></b-icon> 
                </label>
              </template>
            </b-avatar>
            </div>
             </div>
             
            <!-- <div class="section2 mt-3 mb-3 w-12/12 flex">
             <div class="w-6/12 mr-3">
               <h6 class="h6-heading">First Name</h6>
             <input class="appearance-none block w-full rounded py-1 mt-2 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.clientname">
             </div>
             <div class="w-6/12">
               <h6 class="h6-heading">Last Name</h6>
             <input class="appearance-none block w-full rounded py-1 mt-2 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.clientname">
             </div>
            
      			<input type="hidden" v-model="modal.editClient.id">
            </div> -->

            
            <div class="section2 mt-3 mb-3">
              <h6 class="h6-heading">Name of Company</h6>
              <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.clientname" >
              
            </div>
            
            <!-- <div class="section2 mt-3 mb-3">
              <h6 class="h6-heading">Company Name</h6>
              <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.company">
              
            </div> -->
            <div class="section2 mt-3 mb-3">
              <h6 class="h6-heading">Location </h6>
              <!-- <div class="info m-3"> -->
             <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.address">
            </div>
            <div class="section2 mt-3 mb-3">
              <h6 class="h6-heading">Name of contact</h6>
              <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.contactname">
              
            </div>
            <div class="section2 mt-3 mb-3 contact-info">
              Contact Info
            </div>
            <div class="section2 mt-3 mb-3">
              <h6 class="h6-heading">Phone</h6>
              <!-- <div class="info m-3"> -->
             <!-- <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.phone"> -->
             	<input id="textbox" class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.phone" @input="acceptNumber">   
            </div>
            <div class="section2 mt-3 mb-3">
              <h6 class="h6-heading">Email </h6>
              <!-- <div class="info m-3"> -->
             <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="email" v-model="modal.editClient.email">
            </div>

            <div class="section2 mt-3 mb-3">
              <h6 class="h6-heading">Password</h6>
              <!-- <div class="info m-3"> -->
             <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="password" v-model="modal.editClient.password">
            </div>

            <div class="section2 mt-3 mb-3">
              <h6 class="h6-heading">Confirm Password</h6>
              <!-- <div class="info m-3"> -->
             <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="password" v-model="modal.editClient.confirmpassword">
            </div>

             <ValidationProvider rules="email" v-slot="v">
            <div class="section2 mt-3 mb-3">
              <h6 class="h6-heading">Website </h6>
              <!-- <div class="info m-3"> -->
             <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="email" v-model="modal.editClient.website">
               <div class="md:flex md:items-center mb-1">
                <div class="md:w-1/4"></div>
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </div>
             </ValidationProvider>
           
            <div class="section2 mt-3 mb-3">
              <h6 class="h6-heading">Job Title </h6>
              <!-- <div class="info m-3"> -->
             <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.job_title">
               <div class="md:flex md:items-center mb-1">
                <div class="md:w-1/4"></div>
               
              </div>
            </div>

            <div class="section2 mt-5 mb-3  mx-auto text-center">
                <button  class="add-purple-button" @click="saveProfile()"> Save </button>
            </div>
        </div>
        <div class="rounded-lg readonlytrue" v-show="readonlyprofile">
          <div class="section1 flex">
            <div class="info" style="margin: auto; min-width:150px;">
            
            </div>
             </div>
          <div class="section1 flex" style="justify-content:space-between;">
            <div class="flex">
            <div class="info m-3">
            <b-avatar :src="this.currentpath+'/storage/'+profile.client_image" size="6rem"></b-avatar>
  
             <!-- <img class="b-avatar-img-class" :src="this.currentpath+'/storage/'+profile.client_image" /> -->
            </div>
            <div class="info-section">
               <h6 class="h6-label textcap">
                {{ profile.clientname || "-" }}
              </h6>
              <!-- <h6 class="h6-label textcap">
                {{ profile.company ?  profile.company :'-' }}
                Position
              </h6> -->
            </div>
            </div>
            <div class="" style="margin: auto 0;">
              <!-- <b-icon-pencil-square  @click="editProfile(profile)" style="margin: 3px 5px;" /> -->
              <div @click="editProfile(profile)" style="margin: 3px 5px;" class="pencil-icon"></div>
            </div>
             </div>
            <div class="section2 mt-3 mb-3 contact-info">
             Information
            </div>
            <!-- <div class="section2 my-3 px-3 flex">
              <h6 class="h6-heading1 mx-2">Company Name: </h6>
             
              <h6 class="h6-label1 textcap">
              {{ profile.company ?  profile.company :'-' }}
              </h6>
            </div> -->
            <div class="section2 my-3 px-3 flex">
              <h6 class="h6-heading1 mx-2">Name of contact: </h6>
              <!-- <div class="info m-3"> -->
              <h6 class="h6-label1 textcap">
              {{ profile.contactname || "-" }}
              </h6>
            </div>
            <div class="section2 my-3 px-3 flex">
              <h6 class="h6-heading1 mx-2">Phone: </h6>
              <!-- <div class="info m-3"> -->
              <h6 class="h6-label1 textcap">
           {{ profile.phone || "-" }}
              </h6>
            </div>
            <div class="section2 my-3 px-3 flex">
              <h6 class="h6-heading1 mx-2">Email: </h6>
              <!-- <div class="info m-3"> -->
              <h6 class="h6-label1 textcap">
            {{ profile.email || "-" }}
              </h6>
            </div>
            <div class="section2 my-3 px-3 flex">
              <h6 class="h6-heading1 mx-2">Website: </h6>
              <!-- <div class="info m-3"> -->
              <h6 class="h6-label1 textcap">
            {{ profile.website || "-" }}
              </h6>
            </div>
            <div class="section2 my-3 px-3 flex">
              <h6 class="h6-heading1 mx-2">Location: </h6>
              <!-- <div class="info m-3"> -->
              <h6 class="h6-label1 textcap">
           {{ profile.address || "-" }}
              </h6>
            </div>
            <div class="section2 my-3 px-3 flex">
              <h6 class="h6-heading1 mx-2">Job Title: </h6>
              <!-- <div class="info m-3"> -->
              <h6 class="h6-label1 textcap">
           {{ profile.job_title || "-" }}
              </h6>
            </div>
        </div>
      </div>
     </div>
    </div>



  </div>
</template>
<style lang="scss" scoped>

  .no-img{
    // width: 1150px;
    height: 150px;
    background-color: #777;
  }
.blue-bicon{
  color: #007bff;
}
.cover-picture{
  position: relative;
  .update-logo{
margin-top: 1rem;
    margin-bottom: 1rem;
    color: #007bff;
    /* display: inline-block; */
    /* font-weight: 400; */
    /* height: 30px; */
    /* color: #212529; */
    /* text-align: center; */
    /* vertical-align: middle; */
    /* -webkit-user-select: none; */
    -moz-user-select: none;
    -ms-user-select: none;
    /* user-select: none; */
    /* background-color: transparent; */
    border: 1px solid #007bff;
    padding: 10px 30px;
    font-size: 16px;
    line-height: 0;
    border-radius: 0.25rem;
    /* top: 271px; */
    /* left: 487px; */
    /* width: 94px; */
    /* height: 41px; */
    border: 1px solid #3B86FF;
    border-radius: 10px;
    opacity: 1;
    position: absolute;
    margin: auto;
    left: 40%;
    top: 33%;
    background-color: #ffffff;
    height: 40px;
    &:hover{
      color: #007bff !important;
    }
    
  }

  img{
    width: 100%;
    height: 115px;
    max-width: 100%;
    max-height: 150px;
    object-fit: cover;
  }
  .no-img{
    // width: 1150px;
    height: 150px;
    background-color: #777;
  }
}
    .b-avatar-img{
      border-radius: inherit;
    width: 100%;
    height: 100%;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    -webkit-mask-image: radial-gradient(white,#000);
     border-radius: 50%;
      img{
        width: 100%;
        max-width: 1125px;
        height: 100%;
        border-radius: inherit;
        object-fit: cover;
        max-height: 125px;
      }
    }
.pencil-icon{
    background-image: url('/images/profileedit.svg');
    width: 24px;
    height: 24px;
    opacity: 1;
    background-position: center;
    background-size: cover;
}
.readonlytrue{
  .section2{
    border-bottom: 1px solid #DEDEDE;
  }
}
.h6-label1{
  height: 22px;
  text-align: left;
  font: normal normal normal 16px/22px Open Sans;
  letter-spacing: 0px;
  color: #3B86FF;
  opacity: 1;
}
.contact-info{
    text-align: left;
    font: normal normal 600 16px/22px Open Sans;
    letter-spacing: 0px;
    color: #000000;
    opacity: 1;
    padding: 15px;
    height: 47px;
    background: #E6E6E6 0% 0% no-repeat padding-box;
    opacity: 1;
}
.h6-heading{
    height: 24px;
    text-align: left;
    font: normal normal 600 18px/16px Open Sans;
    letter-spacing: 0px;
    color: #000000;
    opacity: 1;
}
.h6-heading1{
    height: 22px;
  text-align: left;
  font: normal normal normal 16px/22px Open Sans;
  letter-spacing: 0px;
  color: #000000;
  opacity: 1;
}
.border-gray-200{
    width: 100%;
    height: 42px;
    background: #FFFFFF 0% 0% no-repeat padding-box;
    border: 1px solid #BBBBBB;
    border-radius: 10px !important;
    opacity: 1;
}
.b-avatar-badge{
    font-size: calc(1.68rem);
    bottom: unset;
    right: unset;
    border-radius: 10px;
    margin: auto;
    padding: 0px 5px;
    background: #ffffff;
    color: #3B86FF;
    border-color: #3B86FF;
}
.info-section{
  margin: auto 10px;
}
.b-avatar{
  width: 6rem;
    height: 6rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    vertical-align: middle;
    flex-shrink: 0;
    /* width: 2.5rem; */
    /* height: 2.5rem; */
    font-size: inherit;
    font-weight: 400;
    line-height: 1;
    max-width: 100%;
    max-height: auto;
    text-align: center;
    overflow: visible;
    position: relative;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,box-shadow .15s ease-in-out;
    border-radius: 50%;
    border: 1px solid;
    .b-avatar-img{
      border-radius: inherit;
    width: 100%;
    height: 100%;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    -webkit-mask-image: radial-gradient(white,#000);
     border-radius: 50%;
      img{
        width: 100%;
        max-width: 1125px;
        height: 100%;
        border-radius: inherit;
        object-fit: cover;
        max-height: 125px;
      }
    }
}
.b-avatar-img-class{
 
    width: 6rem;
    height: 6rem;
    border-radius: 50%;
 
}
input#fileInput2 {
    display: none;
}
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
</style>

<script>
import Vue from "vue";
import { BAvatar } from "bootstrap-vue";
Vue.component("b-avatar", BAvatar);
import Modal from '../shared/Modal'
import Loader from '../shared/Loader'  
import axios from 'axios'
import moment from 'moment'
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import draggable from 'vuedraggable'
// import { BModal, VBModal } from 'bootstrap-vue'
import { BButton } from 'bootstrap-vue'


export default {
	components: {
		Modal,
    Loader,
    DatePicker,
    draggable,
    BButton
	},
	data() {
		return {
      show_default: true,
      hide_default: false,
      show_default_img: true,
      hide_default_img: false,
      Images:{
         client_image:null  ,
         client_background_logo:null  
      },
      currentpath : window.location.origin,
      file:'',
      user_id : '',
      readonlyprofile: true,
      hasImage0: false,
      hasImage: false,
      isLoader: false,
      image: '',
      coverimage: '',
      profile: [],
      modal:{
        editClient:[],

      }
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
   created() {
    let vm = this
    this.inject_material_fonts_and_icons_into_header();
    this.user_id = this.userid;
    this.Profile(this.user_id);
  
  },
  mounted(){
    let vm = this
    this.inject_material_fonts_and_icons_into_header();
  },
   beforeDestroy() {
    this.destroystyle();
  },
  watch :{

  },
	methods: {
		//COVER upload
		Images_onFileChanged(event)
			 {
				 this.Images.client_background_logo = event.target.files;
         this.createImage( this.Images.client_background_logo[0]);
         this.hide_default = true;
         this.show_default = false;
        //  this.modal.editClient.client_background_logo = this.Images.client_background_logo[0].name
			},
		//image upload
		Profile_onFileChanged(event)
			 {
				this.Images.client_image = event.target.files;
        this.createProfileImage( this.Images.client_image[0]);
        document.getElementById("fileInput3").style.display = "none";
         this.hide_default_img = true;
         this.show_default_img = false;
        this.modal.editClient.client_image = this.Images.client_image[0].name
			},
    onFileChange(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length)
        return;
      this.createImage(files[0]);
    },
    createImage(file) {
      var image = new Image();
      var reader = new FileReader();
      var vm = this;
      reader.onload = (e) => {
        vm.image = e.target.result;
      //   const mypostparameters= new FormData()
      //   if(vm.image){
			// 	for( var i = 0; i <  vm.image.length; i++ ){
			// 	let file =   vm.image[i];
			// 	mypostparameters.append('files[' + i + ']', file);
			// 	}
			// }
      //    this.modal.editClient.client_background_logo =  mypostparameters;
         this.modal.editClient.client_background_logo =  this.image;
      };
      reader.readAsDataURL(file);
    },
    createProfileImage(file) {
      var image = new Image();
      var reader = new FileReader();
      var vm = this;
      reader.onload = (e) => {
        vm.image = e.target.result;
      //   const mypostparameters= new FormData()
      //   if(vm.image){
			// 	for( var i = 0; i <  vm.image.length; i++ ){
			// 	let file =   vm.image[i];
			// 	mypostparameters.append('files[' + i + ']', file);
			// 	}
			// }
      //    this.modal.editClient.client_background_logo =  mypostparameters;
         this.modal.editClient.client_image =  this.image;
      };
      reader.readAsDataURL(file);
    },
    handleFileUpload(){
    this.file = this.$refs.file.files[0];
     this.modal.editClient.client_background_logo =  this.file
  },
    // startImageResize0(){
    //   console.log(this.coverimage)
    // },
    // endImageResize0(){
    //    console.log(this.coverimage)
    // },
     setCoverImage: function (file) {
      this.hasImage0 = true
      this.modal.editClient.client_background_logo = file.dataUrl
     
    },
    // startImageResize(){
    //   console.log(this.image)
    // },
    // endImageResize(){
    //    console.log(this.image)
    // },
     setProfileImage: function (file) {
      this.hasImage = true 
      this.modal.editClient.client_image = file.dataUrl
    },
    Profile(id) {
      let vm = this;
      vm.isLoader = true;
      axios.get(`/api/viewprofile/${id}`).then((res) => {
        vm.profile = res.data.data;
        vm.isLoader = false;
      });
    },
     editProfile(data){

      let vm = this;
      this.readonlyprofile = false
      vm.modal.editClient = data;
      // document.getElementById("fileInput").style.display = "none";
      document.getElementById("fileInput1").style.display = "none";
      document.getElementById("fileInput2").style.display = "none";
      document.getElementById("fileInput3").style.display = "none";
      //  this.openModal('editClientModal', data);
    },
     saveProfile(){
       this.readonlyprofile = false
       this.storeClient();
      //  this.openModal('editClientModal', data);
    },
    storeClient() {
			let vm = this;
			const mypostparameters= new FormData()
      vm.isLoader = true
      // const createFile = (bits, name, options) => {
      //   try {
       
      //     console.log('here')
      //     return new File(bits, name, options);
      //   } catch (e) {
        
      //     var myBlob = new Blob(bits, options || {});
      //     myBlob.lastModified = new Date();
      //     myBlob.name = name;
      //     return myBlob;
      //   }
      // };
      // const client_background_logo = createFile([vm.modal.editClient.client_background_logo], "background",{type:'image'});
      // const client_image = createFile([vm.modal.editClient.client_image], "profile",{type:'image'});
      if( vm.modal.editClient.website == null){
         vm.modal.editClient.website = ''
      }
      if(vm.modal.editClient.job_title == null){
        vm.modal.editClient.job_title = ''
      }

      if(vm.modal.editClient.password!="" && vm.modal.editClient.password!=undefined){
          if(vm.modal.editClient.password.length<7){
            vm.isLoader = false
            vm.$swal({
              icon: "error",
              title: "Password must be greater than 6 characters",
              showConfirmButton: false,
              timer: 1500,
            });
            return false;

          }else if(vm.modal.editClient.password!=vm.modal.editClient.confirmpassword){
             vm.isLoader = false
             vm.$swal({
              icon: "error",
              title: "Confirm password must be same as password",
              showConfirmButton: false,
              timer: 1500,
            });
            return false;
          }

          mypostparameters.append('password', vm.modal.editClient.password );
      }
      if(vm.modal.editClient.client_image!="" && vm.modal.editClient.client_image!=undefined){
        mypostparameters.append('client_image', vm.modal.editClient.client_image);
      }

      mypostparameters.append('client_background_logo', vm.modal.editClient.client_background_logo);
      
     	mypostparameters.append('address', vm.modal.editClient.address);
     	mypostparameters.append('clientname', vm.modal.editClient.clientname);
     	mypostparameters.append('company', vm.modal.editClient.company);
     	mypostparameters.append('contactname', vm.modal.editClient.contactname);
     	mypostparameters.append('email', vm.modal.editClient.email);
     	mypostparameters.append('phone', vm.modal.editClient.phone);
     	mypostparameters.append('id', vm.modal.editClient.id);
     	mypostparameters.append('role', vm.modal.editClient.role);
     	mypostparameters.append('status', vm.modal.editClient.status);
     	mypostparameters.append('updated_at', vm.modal.editClient.updated_at);
     	mypostparameters.append('website', vm.modal.editClient.website );
      
     	mypostparameters.append('job_title', vm.modal.editClient.job_title);
			if(this.Images.client_background_logo){
				for( var i = 0; i <  this.Images.client_background_logo.length; i++ ){
				let file =  this.Images.client_background_logo[i];
        mypostparameters.set('client_background_logo', file);
				}
			}
			if(this.Images.client_image){
				for( var i = 0; i <  this.Images.client_image.length; i++ ){
				let file =  this.Images.client_image[i];
				mypostparameters.set('client_image', file);
				}
			}
        axios
        .post('/api/updateprofile', mypostparameters )
        .then(res => {
         if(res.data.status){
            vm.$swal({
            icon: 'success',
            title: 'Successfully Updated!',
            showConfirmButton: false,
            timer: 1500
          })
          localStorage.removeItem('user');
          localStorage.setItem('user',JSON.stringify(res.data.data));
            vm.readonlyprofile = true
            vm.isLoader = false;
            vm.Profile(this.user_id);
           

         }
         else{
           vm.$swal({
            icon: 'error',
            title: 'Error',
            showConfirmButton: false,
            timer: 1500
          })
          vm.isLoader = false
         }
        
       
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: 'Error',
            showConfirmButton: false,
            timer: 1500
          })
          vm.isLoader = false
        })


    },
    acceptNumber() {
      var y = this.modal.editClient.phone;  
      if(this.isnumber(y)){
        	var x = y.toString().replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
      this.modal.editClient.phone = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
      }
      else{
        alert('invalid')
      }
    
    },
    isnumber(evt){
    evt = evt ? evt : window.event;
      let charCode = evt.which ? evt.which : evt.keyCode;
      // if decimal point (.) is allowed, set this to charCode !== 46
      // otherwise, set this to charCode !== 46
      // if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode == 46) {
      if (
        (charCode > 31 && (charCode < 48 || charCode > 57)) ||
        charCode == 46 ||
        charCode == 9 ||
        charCode == 16
      ) {
        evt.preventDefault();
      } else {
        // this.modal.editClient.phone = evt;
        return true;
      }
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
	}
}

</script>

<style lang="scss" scoped>
	@import '../../../sass/client/employees';
</style>