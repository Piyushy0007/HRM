<template>
    <div>
        <Loader msg="Loading Attendance Listings..." v-model="isLoader" />
        <header-component />
        <div style="margin-left: 242px;">
            <div class="w-full mx-auto p-4 h-screen flex flex-col">
                <div
                    class="flex w-full items-center justify-between bg-white p-4 rounded-lg mb-2 bg-gray-100"
                >
                    <!-- Left Section -->
                    <div class="flex items-center">
                        <span class="font-semibold text-lg text-gray-800"
                            >Attendance</span
                        >
                        <!-- <span class="text-gray-600 ml-1">Month- {{ selectedMonth }}</span> -->
                    </div>
                    <!-- Center Section - Search Bar with Font Awesome Icon -->
                    <div class="flex items-center flex-grow mx-12 relative">
                        <input
                            type="text"
                            placeholder="Search..."
                            class="w-full border border-gray-300 rounded-md pl-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            style="height: 36px;"
                            v-model="searchQuery"
                            @input="handleSearch"
                        />
                        <div>
                            <font-awesome-icon
                                icon="search"
                                class="absolute right-0 mr-2 transform -translate-y-1/2 top-1/2 cursor-pointer text-custom-text_grey"
                                style="font-size: 20px"
                            />
                        </div>
                    </div>
                    <!-- Right Section -->
                    <div class="flex items-center space-x-4">
                        <!-- From Date Selector -->
                        <div class="flex items-center">
                            <label for="fromDate" class="text-gray-600 mr-2"
                                >From</label
                            >
                            <input
                                type="date"
                                id="fromDate"
                                v-model="fromDate"
                                class="border border-gray-300 rounded-md px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            />
                        </div>
                        <!-- To Date Selector -->
                        <div class="flex items-center">
                            <label for="toDate" class="text-gray-600 mr-2"
                                >To</label
                            >
                            <input
                                type="date"
                                id="toDate"
                                v-model="toDate"
                                @change="fetchAttendanceData"
                                class="border border-gray-300 rounded-md px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            />
                        </div>
                        <div class="flex items-center">
                            <button
                                class="bg-custom-button text-white px-3 py-1 text-sm rounded-md hover:bg-blue-600"
                            >
                                Add Attendance
                            </button>
                        </div>
                    </div>
                </div>
                <div class="flex gap-4 pb-4">
                  <div class="bg-custom-light_grey shadow-md rounded-lg p-6 w-full">
                    <h3 class="font-semibold text-lg flex items-center">
                      Present Summary
                    </h3>
                    <div class="flex justify-between mt-4">
                      <div class="">
                        <p class="text-custom-text_grey font-semibold">On time</p>
                        <p class="text-2xl font-bold">265</p>
                        <p class="text-custom-text_blue text-sm">+12 vs yesterday</p>
                      </div>
                      <div class="">
                        <p class="text-custom-text_grey font-semibold">Late clock-in</p>
                        <p class="text-2xl font-bold">62</p>
                        <p class="text-custom-text_red text-sm">-6 vs yesterday</p>
                      </div>
                      <div class="">
                        <p class="text-custom-text_grey font-semibold">Early clock-in</p>
                        <p class="text-2xl font-bold">224</p>
                        <p class="text-custom-text_red text-sm">-6 vs yesterday</p>
                      </div>
                    </div>
                  </div>
                  <div class="bg-custom-light_grey shadow-md rounded-lg p-6 w-full">
                    <h3 class="font-semibold text-lg flex items-center">
                            Not Present Summary
                        </h3>
                        <div class="flex justify-between mt-4">
                            <div class="">
                              <p class="text-custom-text_grey font-semibold">Absent</p>
                              <p class="text-2xl font-bold">25</p>
                              <p class="text-blue-500 text-sm">+12 vs yesterday</p>
                            </div>

                            <div class="">
                              <p class="text-custom-text_grey font-semibold"> No clock-in</p>
                              <p class="text-2xl font-bold">29</p>
                              <p class="text-custom-text_red text-sm">-6 vs yesterday</p>
                            </div>
                            <div class="">
                              <p class="text-custom-text_grey font-semibold"> No clock-out</p>
                              <p class="text-2xl font-bold">0</p>
                              <p class="text-custom-text_red text-sm">0 vs yesterday</p>
                            </div>
                            <div class="">
                              <p class="text-custom-text_grey font-semibold">Invalid</p>
                              <p class="text-2xl font-bold">0</p>
                              <p class="text-custom-text_red text-sm">0 vs yesterday</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-custom-light_grey shadow-md rounded-lg p-4 w-1/3">
                        <h3 class="font-semibold text-lg">No Summary</h3>
                        <div class="flex justify-between mt-4">
                          <div class="">
                            <p class="text-custom-text_grey font-semibold"> Day off</p>
                            <p class="text-xl font-bold">0</p>
                            </div>
                            <div class="">
                              <p class="text-custom-text_grey font-semibold"> Time off</p>
                              <p class="text-xl font-bold">0</p>
                             
                              </div>                           
                        </div>
                    </div>
                </div>
                <!-- Table Container -->
                <div
                    class="bg-white shadow-lg rounded-lg flex-grow flex flex-col"
                >
                    <!-- Table Header -->
                    <div class="flex-grow overflow-auto">
                        <table
                            class="min-w-full border border-custom-border rounded-lg border-collapse"
                        >
                            <thead
                                class="bg-custom-bg_table_head_primary border-custom-border"
                            >
                                <tr
                                    class="text-gray-600 border border-custom-border rounded-lg"
                                >
                                    <th class="p-3 border border-custom-border">
                                        Employee
                                    </th>
                                    <th class="p-3 border border-custom-border">
                                        Designation
                                    </th>
                                    <th class="p-3 border border-custom-border">
                                        Date
                                    </th>
                                    <th class="p-3 border border-custom-border">
                                        Check-in Time
                                    </th>
                                    <th class="p-3 border border-custom-border">
                                        Checkout Time
                                    </th>
                                    <th class="p-3 border border-custom-border">
                                        Status
                                    </th>
                                    <th class="p-3 border border-custom-border">
                                        Details
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Row 1 -->
                                <tr v-if="isLoader">
                                    <td
                                        colspan="7"
                                        class="text-center loader py-4"
                                    >
                                        Loading...
                                    </td>
                                </tr>
                                <tr v-else-if="employees.length === 0">
                                    <td colspan="7" class="text-center py-4">
                                        No attendance data found for the
                                        selected date range.
                                    </td>
                                </tr>
                                <tr
                                    v-else
                                    v-for="(employee, index) in employees"
                                    :key="index"
                                    class="p-3 border border-custom-border"
                                >
                                    <td class="p-3 border border-custom-border">
                                        {{
                                            employee.employee.firstname +
                                                " " +
                                                employee.employee.lastname
                                        }}
                                    </td>
                                    <td class="p-3 border border-custom-border">
                                        {{ employee.employee.role }}
                                    </td>
                                    <td class="p-3 border border-custom-border">
                                        {{ employee.attendance_date }}
                                    </td>
                                    <td class="p-3 border border-custom-border">
                                        {{ employee.attendance_date }}
                                    </td>
                                    <td class="p-3 border border-custom-border">
                                        {{ employee.attendance_date }}
                                    </td>
                                    <td class="p-3 border border-custom-border">
                                        {{
                                            employee.status === 1
                                                ? "Present"
                                                : "Absent"
                                        }}
                                    </td>
                                    <td class="p-3 border border-custom-border">
                                        <button
                                            class="text-blue-500 border border-blue-400 px-3 text-sm py-1 rounded-full hover:bg-blue-100 flex justify-center items-center gap-1"
                                        >
                                            <b-icon-list-task />View
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Section -->
                    <div class="flex items-center justify-between p-4 border-t">
                        <div class="flex items-center gap-2">
                            <label for="show" class="text-gray-600">Show</label>
                            <select
                                id="show"
                                v-model="itemsPerPage"
                                @change="fetchAttendanceData"
                                class="border border-gray-300 rounded px-2 py-1 focus:outline-none"
                            >
                                <option value="6">6</option>
                                <option value="12">12</option>
                                <option value="24">24</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-1">
                            <button
                                @click="prevPage"
                                :disabled="currentPage === 1"
                                class="text-blue-500 px-2 py-1 hover:bg-blue-100 disabled:text-gray-400"
                            >
                                &laquo;
                            </button>
                            <button
                                v-for="page in totalPages"
                                :key="page"
                                @click="goToPage(page)"
                                :class="[
                                    'px-3 py-1 rounded',
                                    page === currentPage
                                        ? 'bg-blue-500 text-white hover:bg-blue-600'
                                        : 'text-blue-500 border border-blue-400 hover:bg-blue-100'
                                ]"
                            >
                                {{ page }}
                            </button>
                            <button
                                @click="nextPage"
                                :disabled="currentPage === totalPages"
                                class="text-blue-500 px-2 py-1 hover:bg-blue-100 disabled:text-gray-400"
                            >
                                &raquo;
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    name: "attendance",
    data() {
        return {
            isLoader: false,
            currentPage: 1,
            itemsPerPage: 6,
            totalItems: 0,
            fromDate: "",
            toDate: "",
            searchQuery: "",
            employees: []
        };
    },
    computed: {
        // paginatedData() {
        //   if (!Array.isArray(this.employees)) {
        //       return []; // Ensure it always returns an array
        //   }
        //   const start = (this.currentPage - 1) * this.itemsPerPage;
        //   return this.employees.slice(start, start + this.itemsPerPage);
        // },
        // totalPages() {
        //     return Math.ceil((this.employees || []).length / this.itemsPerPage);
        // },
        totalPages() {
            return Math.ceil(this.totalItems / this.itemsPerPage);
        }
    },
    methods: {
        handleSearch() {
            // You can directly call fetchAttendanceData here to trigger the API call when the user types
            this.fetchAttendanceData();
        },
        async fetchAttendanceData() {
            if (this.fromDate && this.toDate) {
                this.isLoader = true;
                this.error = null;
                try {
                    const params = {
                        start_date: this.fromDate,
                        end_date: this.toDate,
                        page: this.currentPage,
                        per_page: this.itemsPerPage
                    };
                    if (this.searchQuery) {
                        params.search = this.searchQuery;
                    }
                    console.log("Request Params:", params);
                    const response = await axios.get(`/api/attendances`, {
                        params
                    });
                    console.log("API Response:", response.data);
                    if (response.status === 200 && response.data) {
                        this.employees = response.data;
                        this.totalItems = response.data.total; // Update this based on the actual API response format
                        console.log(response, "jbks");
                        console.log(response.employee_id, "sdjhajgdaj");
                    } else {
                        console.error("Unexpected API response:", response);
                        // this.employees = [];
                        this.totalItems = 0;
                    }
                } catch (error) {
                    console.error("Error fetching leave requests:", error);
                    // this.employees = []; // Reset employees on error
                    this.totalItems = 0;
                } finally {
                    this.isLoader = false;
                }
            }
        },
        prevPage() {
            // if (this.currentPage > 1) {
            //   this.currentPage--;
            // }
            if (this.currentPage > 1) {
                this.currentPage--;
                this.fetchAttendanceData();
            }
        },
        nextPage() {
            // if (this.currentPage < this.totalPages) {
            //   this.currentPage++;
            // }
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.fetchAttendanceData();
            }
        },
        goToPage(page) {
            // this.currentPage = page;
            this.currentPage = page;
            this.fetchAttendanceData();
        }
    },
    formatDate(date) {
        if (!date) return "";
        const [year, month, day] = date.split("-");
        return `${day}-${month}-${year}`;
    },

    mounted() {
        const today = new Date();
        const yesterday = new Date();
        yesterday.setDate(today.getDate() - 1);

        const formatDate = date => {
            const yyyy = date.getFullYear();
            const mm = String(date.getMonth() + 1).padStart(2, "0");
            const dd = String(date.getDate()).padStart(2, "0");
            return `${yyyy}-${mm}-${dd}`;
        };
        this.fromDate = formatDate(yesterday);
        this.toDate = formatDate(today);

        this.fetchAttendanceData();
    }
};
</script>

<style lang="scss" scoped>
.main-of-attandance {
    margin-left: 220px;
}
.content {
    overflow-y: auto;
}
.loader {
    text-align: center;
    font-size: 20px;
    color: #007bff;
}
.c-attendance-page {
    .selection-function {
        background-color: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    table {
        th {
            background-color: #e5e7eb;
            padding: 10px;
        }
        td {
            padding: 10px;
            border: 1px solid #e5e7eb;
        }
    }

    .information {
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
    }
    .sidebar {
        height: 100vh;
    }
}
</style>
