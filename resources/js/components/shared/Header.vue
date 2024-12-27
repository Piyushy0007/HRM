<template>
    <div>
        <nav
            class="navigation text-white p-3 flex flex-col"
            style="overflow: scroll; height: 100vh;"
        >
            <!-- A-HR Logo -->
            <div class="logo mb-3 text-center">
                <h1 class="text-white font-bold text-5xl">A-HR</h1>
            </div>
            <div class="text-center mb-2">
                <h6 class="text-white p-0 m-0">Piyush Yadav</h6>
                <p class="text-white text-sm">admin@example.com</p>
            </div>
            <ul class="list-unstyled">
                <!-- Dynamically Render Menu Groups -->
                <li
                    v-for="(group, groupIndex) in activeMenuGroups"
                    :key="groupIndex"
                    class="mb-3"
                >
                    <!-- Parent Group -->
                    <div
                        class="nav-link text-start py-2 px-2 rounded flex items-center justify-between cursor-pointer"
                        :class="{ 'bg-primary': group.expanded }"
                        @click="toggleGroup(groupIndex)"
                    >
                        <div class="flex items-center gap-2">
                            <div
                                class="me-3"
                                style="width: 24px; text-align: center;"
                            >
                                <font-awesome-icon
                                    :icon="group.icon"
                                    style="font-size: 16px;"
                                />
                            </div>
                            <span>{{ group.title }}</span>
                        </div>
                        <span>
                            <font-awesome-icon
                                :icon="
                                    group.expanded
                                        ? 'chevron-down'
                                        : 'chevron-right'
                                "
                            />
                        </span>
                    </div>
                    <!-- Child Items -->
                    <ul v-if="group.expanded" class="list-unstyled ps-3 mt-2">
                        <li
                            v-for="(item, itemIndex) in group.items"
                            :key="itemIndex"
                            class="mb-2 flex flex-col"
                        >
                            <div
                                v-if="item.children"
                                class="sub-nav-link text-white py-1 px-2 cursor-pointer flex items-center justify-between gap-2"
                                @click="toggleChild(groupIndex, itemIndex)"
                                style="margin-left: 16px;"
                            >
                                <span>{{ item.label }}</span>
                                <font-awesome-icon
                                    :icon="
                                        item.expanded
                                            ? 'chevron-down'
                                            : 'chevron-right'
                                    "
                                />
                            </div>
                            <router-link
                                v-else
                                tag="a"
                                class="sub-nav-link text-white py-1 rounded"
                                style="margin-right: auto; margin-left: 24px;"
                                :to="{ name: item.name }"
                            >
                                {{ item.label }}
                            </router-link>
                            <!-- Nested Children -->
                            <ul v-if="item.expanded" class="list-unstyled ps-3">
                                <li
                                    v-for="child in item.children"
                                    :key="child.name"
                                    class="py-1 flex flex-col"
                                    style="margin-left: 22px;"
                                >
                                    <router-link
                                        tag="a"
                                        class="sub-nav-link text-white py-1 px-3 rounded"
                                        :to="{ name: child.name }"
                                    >
                                        {{ child.label }}
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- Logout -->
                <li class="nav-item mt-auto">
                    <a
                        href="#"
                        @click="logout()"
                        class="nav-link text-start py-2 px-3 rounded"
                    >
                        SIGNOUT
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            role: JSON.parse(localStorage.getItem("role")) || "default",
            currentDate: new Date(),
            positions: {},
            selectedPosition: "",
            count: 0,
            selectedGroupIndex: null,
            selectedItemName: "",
            menuGroups: [
                {
                    title: "Management",
                    icon: ["fas", "users"],
                    expanded: false,
                    items: [
                        { name: "employees", label: "Employees" },
                        { name: "attendance", label: "Attendance" },
                        {
                            label: "Leave",
                            expanded: false,
                            children: [
                                { name: "leave", label: "Apply Leave" },
                                { name: "requests", label: "Leave Requests" }
                            ]
                        },
                        { name: "onoffboarding", label: "On/Off Boarding" }
                    ]
                },
                {
                    title: "Payroll",
                    icon: ["fas", "money-check-alt"],
                    items: [
                        { name: "Salary", label: "Salary" },
                        { name: "Tax", label: "Tax" },
                        { name: "Claims", label: "Claims" }
                    ]
                },
                {
                    title: "Branches",
                    icon: ["fas", "building"],
                    expanded: false,
                    items: [{ name: "clindex", label: "Branches" }]
                },
                {
                    title: "Admin Users",
                    icon: ["fas", "user-shield"],
                    expanded: false,
                    items: [{ name: "adminindex", label: "Admin Users" }]
                },
                {
                    title: "Shift & Schedules",
                    icon: ["fas", "calendar-alt"],
                    expanded: false,
                    items: [
                        { name: "schedules", label: "Schedule" },
                        { name: "on-now", label: "On Now" }
                    ]
                },
                {
                    title: "Messaging",
                    icon: ["far", "envelope"],
                    expanded: false,
                    items: [
                        {
                            name: "messaging",
                            label: "Messaging"
                        }
                    ]
                },
                {
                    title: "Reports & Analytics",
                    icon: ["fas", "chart-line"],
                    expanded: false,
                    items: [
                        { name: "reports", label: "Reports" },
                        { name: "performance", label: "Performance" },
                        { name: "attendance", label: "Attendance" },
                        { name: "payroll", label: "Payroll" }
                    ]
                },
                {
                    title: "Jobs",
                    icon: ["fas", "briefcase"],
                    expanded: false,
                    items: [
                        { name: "Create", label: "Create" },
                        { name: "Listing", label: "Listing" }
                    ]
                }
            ],

            HRmenuGroups: [
                {
                    title: "Management",
                    icon: ["fas", "users"],
                    expanded: false,
                    items: [
                        { name: "employees", label: "Employees" },
                        { name: "attendance", label: "Attendance" },
                        {
                            label: "Leave",
                            expanded: false,
                            children: [
                                { name: "leave", label: "Apply Leave" },
                                { name: "requests", label: "Leave Requests" }
                            ]
                        },
                        { name: "onoffboarding", label: "On/Off Boarding" }
                    ]
                },

                {
                    title: "Admin Users",
                    icon: ["fas", "user-shield"],
                    expanded: false,
                    items: [{ name: "adminindex", label: "Admin Users" }]
                },

                {
                    title: "Reports & Analytics",
                    icon: ["fas", "chart-line"],
                    expanded: false,
                    items: [
                        { name: "reports", label: "Reports" },
                        { name: "performance", label: "Performance" }
                    ]
                },
                {
                    title: "Jobs",
                    icon: ["fas", "briefcase"],
                    expanded: false,
                    items: [
                        { name: "Create", label: "Create" },
                        { name: "Listing", label: "Listing" }
                    ]
                }
            ],
            employeeMenuGroups: [
                {
                    title: "Management",
                    icon: ["fas", "users"],
                    expanded: false,
                    items: [
                        { name: "employees", label: "Employees" },
                        { name: "attendance", label: "Attendance" },
                        {
                            label: "Leave",
                            expanded: false,
                            children: [
                                { name: "leave", label: "Apply Leave" },
                                { name: "requests", label: "Leave Requests" }
                            ]
                        },
                        { name: "onoffboarding", label: "On/Off Boarding" }
                    ]
                },

                {
                    title: "Admin Users",
                    icon: ["fas", "user-shield"],
                    expanded: false,
                    items: [{ name: "adminindex", label: "Admin Users" }]
                },

                {
                    title: "Reports & Analytics",
                    icon: ["fas", "chart-line"],
                    expanded: false,
                    items: [
                        { name: "reports", label: "Reports" },
                        { name: "performance", label: "Performance" }
                    ]
                },
                {
                    title: "Jobs",
                    icon: ["fas", "briefcase"],
                    expanded: false,
                    items: [
                        { name: "Create", label: "Create" },
                        { name: "Listing", label: "Listing" }
                    ]
                }
            ]

        };
    },

    created() {
        this.indexPositions();
        this.message_count();
        this.updateRole();
    },

    computed: {
      activeMenuGroups() {
    if (this.role === "admin") {
        return this.menuGroups;
    } else if (this.role === "employee") {
        return this.employeeMenuGroups; 
    } else if (this.role === "HR") {
        return this.HRmenuGroups; 
    } else {
        return []; 
    }
}

    },
    methods: {
        updateRole() {
            this.role = JSON.parse(localStorage.getItem("role")) || "default";
        },
        toggleGroup(index) {
            this.$set(this.activeMenuGroups, index, {
                ...this.activeMenuGroups[index],
                expanded: !this.activeMenuGroups[index].expanded
            });
        },
        toggleChild(groupIndex, itemIndex) {
            const item = this.activeMenuGroups[groupIndex].items[itemIndex];
            if (item.children) {
                item.expanded = !item.expanded;
            }
        },

        message_count() {
            axios
                .post("/api/adminmessagecount", {
                    admin_id: this.userid,
                    userable_type: this.apparray.admin
                })
                .then(res => {
                    if (res.data.status) {
                        this.count = res.data.data;
                    }
                });
        },
        logout() {
            localStorage.removeItem("admin");
            localStorage.removeItem("accesstoken");
            localStorage.removeItem("role");
            this.$router.push("/login");
        },
        indexPositions() {
            axios
                .get("/api/positions")
                .then(res => (this.positions = res.data.data));
        },

        changePosition() {
            this.$emit("position", this.selectedPosition);
            this.$emit("positionName", this.selectedPosition);
        }
    }
};
</script>

<style lang="scss" scoped>
.navigation {
    min-height: 100vh;
    min-width: 240px;
    overflow: scroll;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    background-color: #4e1bd7;
    z-index: 10;
    position: fixed;
}

.navigation ul {
    padding: 0;
    margin: 0;
}

.text-truncate {
    max-width: 106px;
    /* overflow: hidden; */
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
    display: none;
}

.navigation::-webkit-scrollbar-thumb {
    background: white;
    border-radius: 10px;
}
</style>
