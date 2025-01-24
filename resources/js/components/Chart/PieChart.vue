<template>
    <div class="c-leave-page">
        <header-component />

        <div style="margin-left: 242px;">
            <div
                class="flex items-center bg-white p-3 rounded-lg shadow-sm max-w-full whitespace-nowrap"
            >
                <!-- Home Icon -->
                <font-awesome-icon icon="home" style="font-size: 20;" />

                <!-- Breadcrumb Links -->
                <div class="flex items-center text-sm text-gray-600 ml-3">
                    <!-- First Item -->
                    <span class="truncate cursor-pointer hover:text-blue-500 font-bold text-lg">
                        Attendance process
                    </span>

                    <!-- Divider -->
                    <font-awesome-icon :icon="['fas', 'chevron-right']" class="mx-2" />

                    <!-- Second Item -->
                    <span class="truncate cursor-pointer hover:text-blue-500 font-bold text-lg">
                        Daily attendance status
                    </span>
                    <font-awesome-icon :icon="['fas', 'chevron-down']" class="mx-2" />
                </div>

                <!-- Date Range Inputs -->
                <div class="flex items-center gap-3 ml-auto">
                    <input
                        type="date"
                        id="fromDate"
                        v-model="fromDate"
                        @change="fetchAttendanceData"
                        class="border border-gray-300 rounded-md px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    />
                    <span>to</span>
                    <input
                        type="date"
                        id="toDate"
                        v-model="toDate"
                        @change="fetchAttendanceData"
                        class="border border-gray-300 rounded-md px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    />
                </div>
            </div>

            <div class="main-container flex flex-wrap gap-5 p-5 bg-gray-50">

                <!-- Attendance Chart -->
                <div class="flex-1 bg-white p-5 rounded-lg shadow-md w-full">
                    <h3 class="text-xl font-semibold mb-4">
                        Users - Attendance Status
                    </h3>

                    <div class="flex justify-between items-start gap-8">
                        <!-- Pie Chart -->
                        <div style="height: 400px; width: 400px; flex-shrink: 0;">
                            <Pie
                                :chart-options="attendanceChartOptions"
                                :chart-data="attendanceChartData"
                                :width="200"
                                :height="200"
                            />
                        </div>

                        <!-- Stats List -->
                        <div class="flex flex-col gap-4 w-full">
                            <ul class="mt-5">
                                <li
                                    v-for="item in stats"
                                    :key="item.label"
                                    class="flex justify-between items-center py-2 px-4 my-2"
                                >
                                    <div class="flex items-center">
                                        <span
                                            class="w-3 h-3 rounded-full mr-2"
                                            :style="{ backgroundColor: item.color }"
                                        ></span>
                                        <span class="text-gray-600">{{ item.label }}</span>
                                    </div>
                                    <span class="text-gray-800 font-medium ml-4">{{ item.value }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Current Day Status -->
                <div class="flex-1 max-w-md bg-white p-5 rounded-lg shadow-md">
                    <div class="flex w-full items-center gap-4 mb-5">
                        <font-awesome-icon :icon="['fas', 'bars']" />
                        <h3 class="text-xl font-semibold">Current Day Status</h3>
                    </div>

                    <div class="flex flex-col md:flex-row justify-between gap-5">
                        <div style="height: 250px; width: 250px">
                            <Pie
                                :chart-options="dayStatusChartOptions"
                                :chart-data="dayStatusChartData"
                                :width="250"
                                :height="250"
                            />
                        </div>

                        <div class="flex flex-col gap-4 w-full">
                            <ul class="mt-5">
                                <li
                                    v-for="item in stats"
                                    :key="item.label"
                                    class="flex justify-between items-center py-2 px-4 my-2"
                                >
                                    <div class="flex items-center">
                                        <span
                                            class="w-3 h-3 rounded-full mr-2"
                                            :style="{ backgroundColor: item.color }"
                                        ></span>
                                        <span class="text-gray-600">{{ item.label }}</span>
                                    </div>
                                    <span class="text-gray-800 font-medium ml-4">{{ item.value }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Pie } from "vue-chartjs/legacy";
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale } from "chart.js";
import axios from "axios";

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale);

export default {
    name: "AttendanceCharts",
    components: {
        Pie
    },
    data() {
          const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1);

    const formatDate = (date) => {
        const yyyy = date.getFullYear();
        const mm = String(date.getMonth() + 1).padStart(2, "0");
        const dd = String(date.getDate()).padStart(2, "0");
        return `${yyyy}-${mm}-${dd}`;
    };
        return {
            fromDate: formatDate(today), 
        toDate: formatDate(tomorrow),
            stats: [],
            dayStatus:[],
            attendanceChartData: {
                labels: ["Total Users", "Present", "Absent", "On Leave"],
                datasets: [
                    {
                        backgroundColor: ["#D3D3D3", "#A0D995", "#F67E7D", "#FBC490"],
                        data: [0,0,0,0]
                    }
                ]
            },
            attendanceChartOptions: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "bottom"
                    },
                    title: {
                        display: true,
                        text: "Users - Attendance Status"
                    }
                }
            },
            dayStatusChartData: {
                // labels: ["In", "Out", "Yet to check-in", "On Break"],
                labels: ["Total Users", "Present", "Absent", "On Leave"],

                datasets: [
                    {
                        backgroundColor: ["#34D399", "#F87171", "#FACC15", "#3B82F6"],
                        data: [0, 0, 0, 0]
                    }
                ]
            },
            dayStatusChartOptions: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "bottom"
                    },
                    title: {
                        display: true,
                        text: "Current Day Status"
                    }
                }
            }
        };
    },
    methods: {
        async fetchAttendanceData() {
    try {
        if (!this.fromDate || !this.toDate) return;

        const response = await axios.get("api/attendanceSummary", {
            params: {
                start_date: this.fromDate,
                end_date: this.toDate
            }
        });

        const data = response.data || {};

        const totalEmployees = data.total_employees ?? 0;
        const present = data.present ?? 0;
        const absent = data.absent ?? 0;
        const onLeave = data.on_leave ?? 0;

        const isAllNull = totalEmployees === 0 && present === 0 && absent === 0 && onLeave === 0;

        // If all values are null, evenly divide the chart
        if (isAllNull) {
            this.attendanceChartData.datasets[0].data = [1, 1, 1, 1];
            this.stats = [
                { label: "Total Employees", value: "null", color: "#D3D3D3" },
                { label: "Present", value: "null", color: "#A0D995" },
                { label: "Absent", value: "null", color: "#F67E7D" },
                { label: "On Leave", value: "null", color: "#FBC490" }
            ];

            this.dayStatusChartData.datasets[0].data = [1, 1, 1, 1];
            this.dayStatus = [
                { label: "Total Employees", value: "null", color: "#D3D3D3" },
                { label: "Present", value: "null", color: "#A0D995" },
                { label: "Absent", value: "null", color: "#F67E7D" },
                { label: "On Leave", value: "null", color: "#FBC490" }
            ];
        } else {
            this.attendanceChartData.datasets[0].data = [
                totalEmployees,
                present,
                absent,
                onLeave
            ];

            this.stats = [
                { label: "Total Employees", value: totalEmployees, color: "#D3D3D3" },
                { label: "Present", value: present, color: "#A0D995" },
                { label: "Absent", value: absent, color: "#F67E7D" },
                { label: "On Leave", value: onLeave, color: "#FBC490" }
            ];

            this.dayStatusChartData.datasets[0].data = [
                totalEmployees,
                present,
                absent,
                onLeave
            ];

            this.dayStatus = [
                { label: "Total Employees", value: totalEmployees, color: "#D3D3D3" },
                { label: "Present", value: present, color: "#A0D995" },
                { label: "Absent", value: absent, color: "#F67E7D" },
                { label: "On Leave", value: onLeave, color: "#FBC490" }
            ];
        }

    } catch (error) {
        console.error("Error fetching attendance data:", error);
    }
}
    },
    mounted() {
        this.fetchAttendanceData();
    }
};
</script>
