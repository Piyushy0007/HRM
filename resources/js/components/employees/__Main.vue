<template>
  <div>
    <header-component v-if="this.userRole == 'admin' "/>
    <client-header-component v-if="this.userRole != 'admin' "/> 

    <div class="bv-example-row mx-auto" v-if="this.userRole != 'admin' ">
        <b-row>
        <b-col cols="2" style="flex: 0 0 auto;width: 16.66666667%;">
        <ul class="justify-center mx-auto mt-3" style="margin-top: -50px;">
          <router-link  :to="{ name: 'cd' }" class="md:w-40 xl:w-64 mr-1 mb-3 mt-3 ">
            <img style="height:50px; margin-left:80px; margin-top:30px" src="/images/logo.png" alt="Eyewitness" class="">
          </router-link>
          <router-link tag="li" :to="{ name: 'schedules' }"  class="flex mr-1"> 
          <div class="leftindex">
                    <div class="leftimage">
                        <img src="/images/build.png" alt="" v-if="$route.path == '/schedules/' || $route.path == '/schedules'">
                        <img src="/images/propertty_unsel.png" alt="" v-if="$route.path !== '/schedules/' && $route.path !== '/schedules'">
                    </div>
                    <div class="lefttext ">
                      <a href="#target1"  id='target1' class='target' v-if="$route.path !== '/schedules/' && $route.path !== '/schedules'"> Schedules</a>
                        <a href="#target1"  id='target1' class='target' v-if="$route.path == '/schedules/' || $route.path == '/schedules'" style="color:#AD9E58 !important"> Schedules</a>
                    </div>
                </div>
        </router-link>

          <router-link tag="li" :to="{ name: 'employees' }"  class="flex mr-1"> 
                <div class="leftindex">
                    <div class="leftimage">
                        <img src="/images/build.png" alt="" v-if="$route.path == '/employees/' || $route.path == '/employees'">
                        <img src="/images/propertty_unsel.png" alt="" v-if="$route.path !== '/employees/' && $route.path !== '/employees'">
                    </div>
                    <div class="lefttext ">
                      <a href="#target1"  id='target1' class='target' v-if="$route.path !== '/employees/' && $route.path !== '/employees'">Users </a>
                        <a href="#target1"  id='target1' class='target' v-if="$route.path == '/employees/' || $route.path == '/employees'" style="color:#AD9E58 !important">Users </a>  
                    </div>
                </div>
          </router-link>

          <!-- <router-link tag="li" :to="{ name: 'cproperties' }"  class="flex mr-1"> 
                <div class="leftindex">
                    <div class="leftimage">
                        <img src="/images/build.png" alt="" v-if="$route.path == '/cd/' || $route.path == '/cd'">
                        <img src="/images/propertty_unsel.png" alt="" v-if="$route.path !== '/cd/' && $route.path !== '/cd'">
                    </div>
                    <div class="lefttext ">
                      <a href="#target1"  id='target1' class='target' v-if="$route.path !== '/cd/' && $route.path !== '/cd'"> Properties</a>
                        <a href="#target1"  id='target1' class='target' v-if="$route.path == '/cd/' || $route.path == '/cd'" style="color:#AD9E58 !important"> Properties</a>
                    </div>
                </div>
          </router-link>-->

          <router-link tag="li" :to="{ name: 'creport' }" class="flex mr-1 ">
            <div class="leftindex">
                    <div class="leftimage">
                        <img src="/images/file_sel.png" alt="" v-if="$route.path == '/cd/creport/' || $route.path == '/cd/creport/ticket'">
                        <img src="/images/file.png" alt="" v-if="$route.path !== '/cd/creport/' && $route.path !== '/cd/creport/ticket'">
                    </div>
                    <div class="lefttext">
                        <a href="#target2"  id='target2' class='target' v-if="$route.path == '/cd/creport/' || $route.path == '/cd/creport/ticket'" style="color:#AD9E58 !important"> Reports</a>
                        
                         <a href="#target2"  id='target2' class='target' v-if="$route.path !== '/cd/creport/' && $route.path !== '/cd/creport/ticket'"> Reports</a>
                    </div>
                </div>
          </router-link>
          <!-- <router-link tag="li" :to="{ name: 'cinvoice' }" class=" flex mr-1">
            <div class="leftindex">
                    <div class="leftimage">
                        <img  style="margin-top: 6px;" src="/images/invlice_sel.png" alt="" v-if="$route.path == '/cd/invoice'" >
                         <img  style="margin-top: 6px;" src="/images/doller.png" alt="" v-if="$route.path !== '/cd/invoice'" >
                    </div>
                    <div class="lefttext">
                       <a href="#target3"  id='target3' class='target' v-if="$route.path == '/cd/invoice'" style="color:#AD9E58 !important"> Invoice</a>
                         <a href="#target3"  id='target3' class='target' v-if="$route.path !== '/cd/invoice'"> Invoice</a>
                    </div>
                </div>
        </router-link> -->

        <router-link tag="li" :to="{ name: 'cmsg' }" class=" flex mr-1">
            <div class="leftindex">
                    <div class="leftimage1">
                        
                        <img  style="height: 25px; width: 23px; margin-top: 4px;" src="/images/msg_sel.png" alt="" v-if="$route.path == '/cd/msg'">
                        <img  style="height: 25px; width: 23px; margin-top: 4px;" src="/images/message.png" alt="" v-if="$route.path !== '/cd/msg'">
                    </div>
                    <div class="lefttext">
                        <a href="#target4"  id='target4' class='target' v-if="$route.path == '/cd/msg'" style="color:#AD9E58 !important"> Messages</a>
                         <a href="#target4"  id='target4' class='target' v-if="$route.path !== '/cd/msg'"> Messages</a>
                    </div>
                </div>
        </router-link>
      </ul>
    </b-col>

    <b-col cols="10" class="flex-auto w-full float-left bg-gray-200">

      <p class="removecls" style="margin-bottom: 0;padding-bottom: 75px;border-left: 2px solid #f9f9f9; margin-top: -105px; padding-left: 30px;font-family: Poppins;font-weight: bold;font-size: 22px; color: #2C1977; "> Users</p>

       <nav class="removecls employee mb-1 mt-1">
        <ul class="flex justify-center w-7/12 mb-0 mx-auto">
          <router-link tag="li"  :to="{ name: 'employee-index' }" class="w-1/4 mr-1 rounded-t-lg">
            <a href="#" class="block text-center font-semibold text-lg pt-1" style="padding-top:5px;font-size: 16px;">Users</a>
          </router-link>
          <router-link v-if="this.userRole == 'admin' " tag="li" :to="{ name: 'employee-positions' }" class="w-1/4 mr-1 rounded-t-lg">
            <a href="#" class="block text-center font-semibold text-lg pt-1" style="padding-top:5px;font-size: 16px;">Location</a>
          </router-link>
          <router-link v-if="this.userRole == 'admin' " tag="li" :to="{ name: 'employee-notifications' }" class="w-1/4 mr-1 rounded-t-lg">
            <a href="#" class="block text-center font-semibold text-lg pt-1" style="padding-top:5px;font-size: 16px;">Notifications</a>
          </router-link>
          <router-link tag="li" :to="{ name: 'officer-logs' }" class="w-1/4 mr-1 rounded-t-lg">
            <a href="#" class="block text-center font-semibold text-lg pt-1" style="padding-top:5px;font-size: 16px;">Status</a>
          </router-link>
         
          <router-link tag="li" :to="{ name: 'employee-deleted' }" class="w-1/4 rounded-t-lg">
            <a href="#" class="block text-center font-semibold text-lg pt-1" style="padding-top:5px;font-size: 16px;">Deleted</a>
          </router-link>
        </ul>
      </nav>

      <router-view></router-view>
    </b-col>

  </b-row>
    </div>

    <div v-if="this.userRole == 'admin' ">
        <nav class="employee mb-1">
        <ul class="flex justify-center mb-0  w-5/12 auto mx-auto" style="justify-content:space-between;">
          <router-link tag="li" :to="{ name: 'employee-index' }" class="w-1/4 mr-1 rounded-t-lg mt-1">
            <a href="#" class="block text-center font-semibold text-lg pt-1 rounded-t-lg">Users</a>
          </router-link>
          <router-link tag="li" :to="{ name: 'employee-positions' }" class="w-1/4 mr-1 rounded-t-lg mt-1">
            <a href="#" class="block text-center font-semibold text-lg pt-1 rounded-t-lg">Positions</a>
          </router-link>
          <router-link tag="li" :to="{ name: 'employee-notifications' }" class="w-1/4 mr-1 rounded-t-lg mt-1">
            <a href="#" class="block text-center font-semibold text-lg pt-1 rounded-t-lg">Notifications</a>
          </router-link>
          <router-link tag="li" :to="{ name: 'officer-logs' }" class="w-1/4 mr-1 rounded-t-lg mt-1">
            <a href="#" class="block text-center font-semibold text-lg pt-1 rounded-t-lg">Watcher Logs</a>
          </router-link>
          
          <router-link tag="li" :to="{ name: 'employee-deleted' }" class="w-1/4 rounded-t-lg mt-1">
            <a href="#" class="block text-center font-semibold text-lg pt-1 rounded-t-lg">Deleted</a>
          </router-link>
        </ul>
      </nav>
		    <router-view></router-view>
    </div>

	</div>
</template>

<style lang="scss" scoped>
	@import '../../../sass/employees';
  @import '../../../sass/client/dashboard';
  .leftindex {
            flex-direction: row;
            display: flex;
            margin-top: 40px;
            text-align: center;
            margin-left: 30px;
     } 

      a:active {
        color: #AD9E58  !important;
       }
        a:focus {
           color: #AD9E58  !important;
        } 
        :target {
          color: #AD9E58;
           }
    .lefttext {
            width: 69px;
            height: 27px;
            font-family: Poppins;
            /* color: #242424; */
            font-size: 18px;
            line-height: 27px;
            margin-left: 14px;
        }
        .leftimagemsg {
            margin-left: 30px;
            width: 26px;
            height: 33px;
        }
        .leftimageone{
          height:100px;
          width:158px;
          margin-top:6px;
        }
        
        .lefttext a {
            color: #242424;
            text-decoration: none;
        }
        .mx-auto{
          background-color:#FFFFFF;
        }
  .bv-example-row{
    ul{
      li{
        top: 116px;
        left: 0px;
        width: 20%;
                height: auto;
                background: #FFFFFF;   
        opacity: 1;
        width: 100%;
        
        a{
          margin: auto auto;
          
          vertical-align: middle;
          padding-top: 20px;
          
          letter-spacing: 0px;
           color:black;
            font-size:18px;
                     line-height: 27px;
          font-family: Poppins;
          
        flex-direction:row
          &:hover{
          
            text-decoration: none;
          }
        }
      }
    }
  }
</style>

<script>

import axios from 'axios'

export default {
  data() {
    return {
      currentDate: new Date(),
      positions: {},
      selectedPosition: '',
      count: 0,
      apparray:{
        admin : 'App\\Admin',
        client: 'App\\Client',
        employee: 'App\\Employee'
      },
    }
  },
  watch:{
        routerpath: {
          handler() {
            console.log(this.$router.currentRoute.path , 'this.$router.path')
          },
          deep: true,
          immediate: true,
      },
  },
  computed:{
     routerpath() {
      return this.$router.currentRoute.path;
    },
    userid() {
      let user = localStorage.getItem("admin");
          console.log('localStorageuser',user)
        if(user){
          return JSON.parse(user).id;
        }else {
         return 0;
        }
      },
    userRole() {
      let user = localStorage.getItem("admin");
        if(user){
          return JSON.parse(user).role;
        }else {
         return 0;
        }
      },
  },
  created() {

  }, 
  methods: {
  }
}
</script>