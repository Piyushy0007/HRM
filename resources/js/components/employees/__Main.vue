<template>
  <div>
    <header-component/>
    <div class="bv-example-row mx-auto min-h-screen" v-if="this.userRole != 'admin' ">
    <div class="flex-auto w-full float-left bg-gray-200">
       <nav class="removecls employee mb-1 mt-1">
        <ul class="flex w-6/12 mr-1" style="margin-left: 242px;">
          <router-link tag="li"  :to="{ name: 'employee-index' }" class="w-1/4 mr-1 ">
            <a href="#" class="block text-center font-semibold text-lg pt-1 " style="padding-top:5px;font-size: 16px;">Users</a>
          </router-link>
          <router-link v-if="this.userRole == 'admin' " tag="li" :to="{ name: 'employee-positions' }" class="w-1/4 mr-1">
            <a href="#" class="block text-center font-semibold text-lg pt-1 " style="padding-top:5px;font-size: 16px;">Location</a>
          </router-link>
          <router-link v-if="this.userRole == 'admin' " tag="li" :to="{ name: 'employee-notifications' }" class="w-1/4 mr-1">
            <a href="#" class="block text-center font-semibold text-lg pt-1 " style="padding-top:5px;font-size: 16px;">Notifications</a>
          </router-link>
          <router-link tag="li" :to="{ name: 'officer-logs' }" class="w-1/4 mr-1">
            <a href="#" class="block text-center font-semibold text-lg pt-1" style="padding-top:5px;font-size: 16px;">Status</a>
          </router-link>
         
          <router-link tag="li" :to="{ name: 'employee-deleted' }" class="w-1/4">
            <a href="#" class="block text-center font-semibold text-lg pt-1" style="padding-top:5px;font-size: 16px;">Deleted</a>
          </router-link>
        </ul>
      </nav>

      <router-view></router-view>
    </div>

    </div>

    <div v-if="this.userRole == 'admin' ">
        <nav class="employee mb-1">
        <ul class="flex mb-0 w-7/12" style="margin-left: 242px">
          <router-link tag="li" :to="{ name: 'employee-index' }" class="w-1/4 mr-1 mt-1">
            <a href="#" class="block text-center font-semibold text-lg pt-1 ">Users</a>
          </router-link>
          <router-link tag="li" :to="{ name: 'employee-positions' }" class="w-1/4 mr-1 mt-1">
            <a href="#" class="block text-center font-semibold text-lg pt-1 ">Positions</a>
          </router-link>
          <router-link tag="li" :to="{ name: 'employee-notifications' }" class="w-1/4 mr-1 mt-1">
            <a href="#" class="block text-center font-semibold text-lg pt-1 ">Notifications</a>
          </router-link>
          <router-link tag="li" :to="{ name: 'officer-logs' }" class="w-1/4 mr-1 mt-1">
            <a href="#" class="block text-center font-semibold text-lg pt-1">Watcher Logs</a>
          </router-link>
          <router-link tag="li" :to="{ name: 'employee-deleted' }" class="w-1/4 mt-1">
            <a href="#" class="block text-center font-semibold text-lg pt-1">Deleted</a>
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