<template>
  <div>
    <div style="display flex;justify-content: flex-end;padding-top: 25px;border-left: 2px solid #f9f9f9;margin-top: 0px;">
    <nav class="navigation client-header">
      <ul class="flex" style="justify-content: flex-end;margin-top:0px;">
          <li class="md:w-40 xl:w-64 user-section flex">
             <b-dropdown no-caret class="w-full side-drawer" size="lg"  variant="link" toggle-class="text-decoration-none" >
              <template #button-content >
               <h3  class="client-name" style="margin-top: -5px;"> Hi {{ user_data.clientname ? user_data.clientname : "User" }} </h3>  
                
                
                

                <img style="margin-left:10px; margin-top:-17px;width: 50px;height:50px; border-radius:30px;" v-if="user_data.client_image != undefined || user_data.client_image != null" :src="currentpath+'/storage/'+user_data.client_image" />
                <img v-else style="margin-left:10px; margin-top:-17px;width: 50px; height:50px; border-radius:30px;" src="/images/topimage.png" alt="user">

                <b-icon-chevron-down style="color:black; margin-top:-5px;" class="dropdown-caret-arrow"  />
              </template>
              
              <b-dropdown-item @click="redirect('profile')" class="dropdown-item">Profile</b-dropdown-item>
              <b-dropdown-item class="dropdown-item" @click="logout()">Logout</b-dropdown-item>

            </b-dropdown>
            
        </li>
      </ul>
    </nav>
    </div>
  </div>
</template>
<style lang="scss" scoped>
  .dropdown-caret-arrow{
    color:red;
  }
  .side-drawer ul.dropdown-menu{
      position: absolute;
  }
  .clientname{
      margin-right:120px;
  }

.client-header {
  ul {
    margin-bottom: 5px;
    margin-top: 20px;
    .chat-icon{
      background-image: url('/images/messageicon.svg');
      background-position: center;
      background-size: contain;
      height: 40px;
      width: 40px;
      margin: auto 30px;
      background-repeat: no-repeat;
      position: relative;
      .notification_count{
       width: auto;
      height: auto;
      background: #FF2626 0% 0% no-repeat padding-box;
      opacity: 1;
      z-index: 100;
      position: absolute;
      border-radius: 10px;
      right: 0;
      top: 0;
      font-size: inherit;
      color: #ffffff;
      padding: 0px 5px 0px 5px;
      }
    }
    .user-section {
      h3 {
        height: 18px;
        text-align: center;
        font: normal normal normal 15px Montserrat;
        letter-spacing: 0.42px;
        color: #302369;
        opacity: 1;
        margin-top: .5rem;
        width: auto;
      }
    }
  }
}
</style>
<script>
import axios from "axios";

export default {
  data() {
    return {
      currentpath : window.location.origin,
      currentDate: new Date(),
      positions: {},
      selectedPosition: "",
      count: 0
    };
  },
  computed: {
    user_data() {
      let userdata = JSON.parse(localStorage.getItem("user"));
      if(userdata == undefined || userdata == '' || userdata == null){ 
        return this.$router.push('/client_login')
      }
      else {
        return userdata
        }
     
    },
    userid() {
      let user = localStorage.getItem("user");
      return JSON.parse(user).id;
    },
    userimg() {
      let user = localStorage.getItem("user");
      let img =  JSON.parse(user).client_image;
      if (img != null){
        return this.currentpath+'/storage/'+img
      }
      else{
        return 'https://placekitten.com/300/300'
      }
    },
  },
  created() {
    // this.indexPositions();
    this.message_count();
  },
  methods: {
    // redirect to
    redirect(name){
      this.$router.push(`/${name}`)
    },
    logout(){
       axios.post("/api/clientlogout").then((res) => {
         localStorage.removeItem('user');
         this.$router.push('/client_login')

       });
    },
    message_count(){
       axios.post("/api/cmessagecount", {user_id: this.userid , user_type: 'App\\Client'}).then((res) => {
         if(res.data.status){
           this.count = res.data.data
         }
       });
    }
    // indexPositions() {
    //   axios.get("/api/positions").then((res) => (this.positions = res.data));
    // },
    // changePosition() {
    //   this.$emit("position", this.selectedPosition);
    //   this.$emit("positionName", this.selectedPosition);
    // },
  },
};
</script>