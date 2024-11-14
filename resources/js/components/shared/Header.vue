<template>


  <div>

    <nav class="navigation d-flex flex-column bg-primary position-fixed vh-100 p-3 shadow" style="width: 12rem;">
  <ul class="nav flex-column">
    <li class="nav-item mb-2">
      <router-link tag="a" class="nav-link text-white py-2 px-3 rounded" :to="{ name: 'schedules' }">
        SCHEDULES
      </router-link>
    </li>
    <li class="nav-item mb-2">
      <router-link tag="a" class="nav-link text-white py-2 px-3 rounded" :to="{ name: 'employees' }">
        USERS
      </router-link>
    </li>
    <li class="nav-item mb-2">
      <router-link tag="a" class="nav-link text-white py-2 px-3 rounded" :to="{ name: 'clindex' }">
        COMMUNITY
      </router-link>
    </li>
    <li class="nav-item mb-2">
      <router-link tag="a" class="nav-link text-white py-2 px-3 rounded" :to="{ name: 'adminindex' }">
        ADMIN
      </router-link>
    </li>
    <li class="nav-item mb-2">
      <router-link tag="a" class="nav-link text-white py-2 px-3 rounded d-flex align-items-center" :to="{ name: 'messaging' }">
        MESSAGING 
        <span v-if="count > 0" class="badge bg-danger ms-2">{{ count }}</span>
      </router-link>
    </li>
    <li class="nav-item mb-2">
      <router-link tag="a" class="nav-link text-white py-2 px-3 rounded" :to="{ name: 'reports' }">
        REPORTS
      </router-link>
    </li>
    <li class="nav-item mb-2">
      <router-link tag="a" class="nav-link text-white py-2 px-3 rounded" :to="{ name: 'on-now' }">
        ON NOW
      </router-link>
    </li>
    <li class="nav-item mt-auto">
      <a href="#" @click="logout()" class="nav-link text-white py-2 px-3 rounded">
        SIGNOUT
      </a>
    </li>
  </ul>
</nav>

<div class="c-header flex justify-between items-center px-5 py-3" style="margin-left: 200px">
  <div class="w-1/3">
    <span v-if="$route.path != '/schedules'">{{ currentDate | moment('MMMM D, YYYY | HH:mm') }}</span>
    <span v-if="$route.path == '/schedules'">
      <router-link to="/schedules/timerequestoff">
        <button class="add" style="background-color: #007bff; color: white; border: 1px solid black; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#0056b3'" onmouseout="this.style.backgroundColor='#007bff'">
          Time Off Requests
        </button>
      </router-link>
    </span>
  </div>
  <div class="w-1/3 text-right">
    <div class="relative w-7/12 float-right" v-if="$route.path == '/schedules'">
      <select class="block appearance-none w-full py-1 px-4 pr-8 rounded leading-tight focus:outline-none" v-model="selectedPosition" @change="changePosition">
        <option value="">All Positions</option>
        <option :value="data.id" v-for="data in positions" :key="data.id">{{ data.position }}</option>
      </select>
      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
      </div>
    </div>
  </div>
</div>


  </div>
</template>

<style lang="scss" scoped>
.navigation {
    background-color: #007bff; // Navigation background color
    height: 100vh; // Full height
    position: fixed; // Fixed positioning
    top: 0; 
    left: 0; 
    width: 12rem; 
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); // Subtle shadow
    padding: 1rem; // Padding for inner spacing
}

.navigation ul {
    padding: 0; // Reset padding
    margin: 0; // Reset margin
    list-style: none; // Remove bullet points
}

.navigation li {
    margin-bottom: 1rem; // Space between items
}

.navigation a {
    display: block; // Block display for full clickable area
    padding: 1rem; // Inner padding
    color: white; // Text color
    text-decoration: none; // No underline
    border-radius: 5px; // Rounded corners
    transition: background-color 0.3s, transform 0.3s; // Smooth transitions
}

// Change the background color on hover
.navigation a:hover {
    background-color: #0056b3; // Darker blue on hover
    transform: translateY(-2px); // Slight lift effect on hover
}

// Remove focus style to avoid flickering
.navigation a:focus {
    outline: none; // Remove default focus outline
    background-color: #0056b3; // Maintain hover color on click
}

// Style for notification badge
.notification-badge {
    background-color: red; // Badge background color
    border-radius: 50%; // Circular badge
    padding: 0.25rem; // Inner padding
    color: white; // Text color
    font-size: 0.75rem; // Smaller text
    margin-left: 0.5rem; // Space to the left
    display: inline-block; // Keep it inline
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
			return JSON.parse(user).id;
    	},
  },
  created() {
    this.indexPositions();
    this.message_count();
  }, 
  methods: {
    // admin message count
		message_count(){
       axios.post("/api/adminmessagecount", {admin_id: this.userid , userable_type : this.apparray.admin}).then((res) => {
         if(res.data.status){
           this.count = res.data.data
         }
       });
    },
    logout(){
      localStorage.removeItem('admin')
      localStorage.removeItem('accesstoken')
      this.$router.push('/login')
    },
    indexPositions() {
      axios
        .get('/api/positions')
        .then(res => this.positions = res.data.data)
    },
    changePosition() {
      this.$emit('position', this.selectedPosition)
      this.$emit('positionName', this.selectedPosition)
    },
  }
}

</script>