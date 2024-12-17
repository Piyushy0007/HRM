<template>
  <div>
    <nav class="navigation text-white p-3 flex flex-col">
        <!-- A-HR Logo at the Top -->
      <div class="logo mb-3 text-center">
        <h1 class="text-white font-bold text-5xl">A-HR</h1>
      </div>
      <div class="text-center mb-2">
        <h6 class="text-white p-0 m-0">Piyush Yadav</h6>
        <p class="text-white text-sm">admin@example.com</p>
      </div>
      <ul class="list-unstyled">
        <li v-for="(group, groupIndex) in menuGroups" :key="groupIndex" class="mb-3">
          <div :class="['nav-link text-start py-2 px-2 rounded flex items-center justify-between cursor-pointer', group.expanded ? 'bg-primary' : '']"
          @click="toggleGroup(groupIndex)">
            <div class="flex items-center justify-between gap-2">
                <div class="me-3" style="width: 24px; text-align: center;">
                  <font-awesome-icon :icon="group.icon" style="font-size: 16px;"/>
                </div>
                <div class="flex-grow-1">{{ group.title }}</div>
              </div>
              <span>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor"
                  class="bi"
                  :class="group.expanded ? 'bi-chevron-right' : 'bi-chevron-down'"
                  width="14"
                  height="14"
                  viewBox="0 0 16 16"
                >
                  <path
                    fill-rule="evenodd"
                    d="M1.5 1.5l7 7-7 7L0 14l6-6L0 2l1.5-1.5z"
                    :transform="group.expanded ? 'rotate(90 8 8)' : 'rotate(360 8 8)'"
                  />
                </svg>
              </span>
          </div>
            <!-- Group Links -->
            <ul v-if="group.expanded" class="list-unstyled ps-3 mt-2">
              <li v-for="item in group.items" :key="item.name" class="nav-item ml-2">
                <div class="flex items-center pl-3">
                  <span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="currentColor"
                      class="bi"
                      :class="group.expanded ? 'bi-chevron-right' : 'bi-chevron-down'"
                      width="12"
                      height="12"
                      viewBox="0 0 16 16"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M1.5 1.5l7 7-7 7L0 14l6-6L0 2l1.5-1.5z"
                        :transform="group.expanded ? '' : 'rotate(360 6 6)'"
                      />
                    </svg>
                  </span>
                  <router-link
                    tag="a"
                    class="sub-nav-link text-white text-start py-1 px-3 rounded"
                    :to="{ name: item.name }"
                  >
                    {{ item.label }}
                  </router-link>
                </div>
              </li>
            </ul>
          </li>
          <li class="nav-item mt-auto">
            <a href="#" @click="logout()" class="nav-link text-start py-2 px-3 rounded">
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
      selectedGroupIndex: null,
      selectedItemName: '',
      menuGroups: [
        {
          title: 'Management',
          icon: ['fas', 'users'], // Icon representing multiple users/employees
          expanded: false,
          items: [
            { name: 'employees', label: 'Employees' },
            { name: 'attendance', label: 'Attendance' },
            { name: 'leave', label: 'Leave' },
            { name: 'onoffboarding', label: 'On/Off Boarding' },
            // { name: 'onoffboarding', label: 'Onboarding/Offboarding' },
          ],
        },
        {
          title: 'Payroll',
          icon: ['fas', 'money-check-alt'], // Icon for payroll/financial management
          expanded: false,
          items: [
            { name: 'Salary', label: 'Salary' },
            { name: 'Tax', label: 'Tax' },
            { name: 'Claims', label: 'Claims' },
          ],
        },
        {
          title: 'Branches',
          icon: ['fas', 'building'], // Icon representing office branches or locations
          expanded: false,
          items: [{ name: 'clindex', label: 'Branches' }],
        },
        {
          title: 'Admin Users',
          icon: ['fas', 'user-shield'], // Icon for administrators or secure users
          expanded: false,
          items: [{ name: 'adminindex', label: 'Admin Users' }],
        },
        {
          title: 'Shift & Schedules',
          icon: ['fas', 'calendar-alt'], // Icon representing schedules or calendars
          expanded: false,
          items: [
            { name: 'schedules', label: 'Schedule' },
            { name: 'on-now', label: 'On Now' },
          ],
        },
        {
          title: 'Messaging',
          icon: ['far', 'envelope'], // Icon for messaging or email
          expanded: false,
          items: [
            {
              name: 'messaging',
              label: 'Messaging',
            },
          ],
        },
        {
          title: 'Reports & Analytics',
          icon: ['fas', 'chart-line'], // Icon representing reports or analytics
          expanded: false,
          items: [
            { name: 'reports', label: 'Reports' },
            { name: 'performance', label: 'Performance' },
            { name: 'attendance', label: 'Attendance' },
            { name: 'payroll', label: 'Payroll' },
          ],
        },
        {
          title: 'Jobs',
          icon: ['fas', 'briefcase'], // Icon representing jobs or employment
          expanded: false,
          items: [
            { name: 'Create', label: 'Create' },
            { name: 'Listing', label: 'Listing' },
          ],
        },

      ],
    };
  },
  created() {
    this.indexPositions();
    this.message_count();
  },
  methods: {
    toggleGroup(index) {
      this.menuGroups.forEach((group, i) => {
      if (i !== index) group.expanded = false;
      });
      this.menuGroups[index].expanded = !this.menuGroups[index].expanded;
      this.selectedGroupIndex = index;
    },
    selectItem(groupIndex, itemName) {
    this.selectedGroupIndex = groupIndex;
    this.selectedItemName = itemName;
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
  background-color: #4E1BD7;
  overflow-y: auto;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
  z-index: 10;
  position: fixed; 
  height: 100vh;
  min-width: 240px;
}

.navigation ul {
  padding: 0;
  margin: 0;
}

.text-truncate {
  max-width: 106px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.navigation .nav-link {
  color: white;
  text-decoration: none;
  border-radius: 8px;
}
.nav-link {
  transition: background-color 0.3s ease, color 0.3s ease;  
}

.navigation .nav-link:hover {
  background-color: #007bff;
  transform: translateY(-2px);
}
.sub-nav-link {
  transition: transform 0.2s ease;
}

.sub-nav-link:hover {
  transform: scale(1.03);
  font-weight: 600;
}

/* .navigation a:focus {
  outline: none;
  background-color: #0056b3;
} */

.navigation .text-start {
  text-align: left;
}

.navigation .ms-auto {
  margin-left: auto;
}

.navigation::-webkit-scrollbar {
  width: 5px;
}

.navigation::-webkit-scrollbar-thumb {
  background: white;
  border-radius: 10px;
}
</style>
