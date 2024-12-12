<template>
  <div>
    <!-- <nav class="navigation d-flex flex-column bg-primary position-fixed vh-100 p-3 shadow" style="width: 12rem;"> -->
      <nav class="navigation flex flex-col bg-primary fixed top-0 left-0 h-full p-3 shadow-lg col-xm-2">
    <nav class="navigation d-flex flex-column bg-primary position-fixed vh-100 p-3 shadow" style="width: 14rem; overflow-y: auto;">

      <!-- A-HR Logo at the Top -->
      <div class="logo mb-4 text-center">
        <h1 class="text-white font-bold text-2xl">A-HR</h1>
      </div>

      <ul class="nav flex-column flex-grow">
        <li
          v-for="(group, groupIndex) in menuGroups"
          :key="groupIndex"
          class="nav-item mb-3"
        >
          <!-- Group Title -->
          <div
            class="nav-link text-white py-2 px-3 rounded flex items-center cursor-pointer"
            @click="toggleGroup(groupIndex)"
            style="cursor: pointer;"
          >
            <span class="w-full text-left">{{ group.title }}</span>
            <span class="ml-auto">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="currentColor"
                class="bi"
                :class="group.expanded ? 'bi-chevron-down' : 'bi-chevron-right'"
                width="16"
                height="16"
                viewBox="0 0 16 16"
              >
                <path
                  fill-rule="evenodd"
                  d="M1.5 1.5l7 7-7 7L0 14l6-6L0 2l1.5-1.5z"
                  :transform="group.expanded ? '' : 'rotate(-90 8 8)'"
                />
              </svg>
            </span>
          </div>

          <!-- Group Links -->
          <ul v-if="group.expanded" class="ps-3">
            <li v-for="item in group.items" :key="item.name" class="nav-item mb-2 ml-2">
              <router-link
                tag="a"
                class="nav-link text-white py-2 px-3 rounded"
                :to="{ name: item.name }"
              >
                {{ item.label }}
              </router-link>
            </li>
          </ul>
        </li>
        <li class="nav-item mt-auto">
          <a href="#" @click="logout()" class="nav-link text-white py-2 px-3 rounded">
            SIGNOUT
          </a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      currentDate: new Date(),
      positions: {},
      selectedPosition: '',
      count: 0,
      menuGroups: [
        {
          title: 'Employee Management',
          expanded: false,
          items: [
            { name: 'employees', label: 'Employees' },
            { name: 'attendance', label: 'Attendance' },
            { name: 'leave', label: 'Leave' },
            { name: 'onoffboarding', label: 'Onboarding/Offboarding' }
          ],
        },
        {
          title: 'Payroll',
          expanded: false,
          items: [
            { name: 'Salary', label: 'Salary' },
            { name: 'Tax', label: 'Tax' },
            { name: 'Claims', label: 'Claims' },
          ],
        },
        {
          title: 'Branches',
          expanded: false,
          items: [{ name: 'clindex', label: 'Branches' }],
        },
        {
          title: 'Admin Users',
          expanded: false,
          items: [{ name: 'adminindex', label: 'Admin Users' }],
        },
        {
          title: 'Shift & Schedules',
          expanded: false,
          items: [
            { name: 'schedules', label: 'SCHEDULES' },
            { name: 'on-now', label: 'ON NOW' }
          ],
        },
        {
          title: 'Messaging',
          expanded: false,
          items: [
            {
              name: 'messaging',
              label: 'MESSAGING',
            },
          ],
        },
        {
          title: 'Reports & Analytics',
          expanded: false,
          items: [
            { name: 'reports', label: 'REPORTS' },
            { name: 'hranalytics', label: 'HR Analytics' },
            { name: 'performance', label: 'Performance' },
            { name: 'attendance', label: 'Attendance' },
            { name: 'payroll', label: 'Payroll' }          
          ],
        },
        {
          title: 'Jobs',
          expanded: false,
          items: [
            { name: 'JobPosts', label: 'Jobposts' },
            { name: 'hranalytics', label: 'HR Analytics' },     
            { name: 'Create', label: 'Create' },
            { name: 'Listing', label: 'Listing' }          
          ],
        }
      ],
    };
  },
  created() {
    this.indexPositions();
    this.message_count();
  },
  methods: {
    toggleGroup(index) {
      this.menuGroups[index].expanded = !this.menuGroups[index].expanded;
    },
    message_count() {
      axios.post('/api/adminmessagecount', {
        admin_id: this.userid,
        userable_type: this.apparray.admin,
      }).then((res) => {
        if (res.data.status) {
          this.count = res.data.data;
        }
      });
    },
    logout() {
      localStorage.removeItem('admin');
      localStorage.removeItem('accesstoken');
      this.$router.push('/login');
    },
    indexPositions() {
      axios.get('/api/positions').then((res) => (this.positions = res.data.data));
    },

    changePosition() {
      this.$emit('position', this.selectedPosition);
      this.$emit('positionName', this.selectedPosition);
    },
  },
};
</script>

<style lang="scss" scoped>
.navigation {
  background-color: #02067e;
  /* height: 100vh; */
  position: fixed;
  overflow-y: auto;
  /* top: 0; */
  /* left: 0; */
  /* width: 100%; */
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
  padding: 1rem;
  z-index: 10;
}

.navigation ul {
  padding: 0;
  margin: 0;
  list-style: none;
}

.navigation li {
  margin-bottom: 1rem;
}

.navigation .nav-link {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  padding: 1rem;
  color: white;
  text-decoration: none;
  border-radius: 5px;
  transition: background-color 0.3s, transform 0.3s;
}

.navigation .nav-link:hover {
  background-color: #0056b3;
  transform: translateY(-2px);
}

.navigation a:focus {
  outline: none;
  background-color: #0056b3;
}

.navigation .text-start {
  text-align: left;
}

.navigation .ms-auto {
  margin-left: auto;
}
</style>
