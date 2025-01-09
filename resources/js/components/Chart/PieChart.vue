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
                    <font-awesome-icon :icon="['fas', 'chevron-right']" class="mx-2"/>

                    <!-- Second Item -->
                    <span class="truncate cursor-pointer hover:text-blue-500 font-bold text-lg">
                        Daily attendance status
                    </span>

                    <font-awesome-icon :icon="['fas', 'chevron-down']" class="mx-2" />
                </div>
                <div class="flex flex-row items-center justify-center gap-5 ml-auto">
                  <font-awesome-icon icon="caret-left" class="text-primary" style="font-size: 24px;" />
                  <input
                    type="date"
                    id="fromDate"
                    v-model="fromDate"
                    class="border border-gray-300 rounded-md px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                  />
                  <font-awesome-icon icon="caret-right" class="text-primary" style="font-size: 24px;" />
                </div>
                

                <div class="flex flex-row items-center justify-center gap-5 ml-auto">
                    <font-awesome-icon :icon="['fas', 'ellipsis-v']" />
                </div>
                
                <div class="h-8 w-10 flex items-center justify-center bg-gray-200 rounded-lg mr-2">
                    <font-awesome-icon icon="sliders-h" />
                </div>               

                <div class="h-8 w-10 flex items-center justify-center bg-gray-200 rounded-lg">
                    <font-awesome-icon icon="ellipsis-h" :transform="'rotate-90'" />
                </div>
            </div>

            <div class="main-container flex flex-wrap gap-5 p-5 bg-gray-50">
                <!-- Attendance Chart -->
                <div class="flex-1 bg-white p-5 rounded-lg shadow-md w-full">
                    <h3 class="text-xl font-semibold mb-4">
                        Users - Attendance Status
                    </h3>

                    <!-- Flex container to arrange chart and stats side by side with space between -->
                    <div class="flex justify-between items-start gap-8 ">
                        <!-- Left Section: Pie Chart -->
                        <div
                            style="height: 400px; width: 400px; flex-shrink: 0;"
                        >
                            <Pie
                                :chart-options="attendanceChartOptions"
                                :chart-data="attendanceChartData"
                                :width="200"
                                :height="200"
                            />
                        </div>

                        <!-- Right Section: Stats List -->
                        <div class="flex flex-col gap-4 w-full">
                            <div class="flex items-center">
                                <font-awesome-icon :icon="['fas', 'bars']" />
                            </div>
                            <ul class="mt-5">
                                <li
                                    v-for="item in stats"
                                    :key="item.label"
                                    class="flex justify-between items-center py-2 px-4 my-2"
                                >
                                    <div class="flex items-center">
                                        <span
                                            class="w-3 h-3 rounded-full mr-2"
                                            :style="{
                                                backgroundColor: item.color
                                            }"
                                        ></span>
                                        <span class="text-gray-600">{{
                                            item.label
                                        }}</span>
                                    </div>
                                    <span
                                        class="text-gray-800 font-medium ml-4"
                                        >{{ item.value }}</span
                                    >
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Current Day Status -->
                <div class="flex-1 max-w-md bg-white p-5 rounded-lg shadow-md ">
                    <!-- Header Section -->
                    <div class="flex w-full items-center gap-4 mb-5">
                        <div>
                            <font-awesome-icon :icon="['fas', 'bars']" />
                        </div>
                        <h3 class="text-xl font-semibold">
                            Current Day Status
                        </h3>
                    </div>

                    <!-- Main Content: Chart and List -->
                    <div
                        class="flex flex-col md:flex-row justify-between  gap-5"
                    >
                        <!-- Pie Chart Section -->
                        <div style="height: 250px; width: 250px">
                            <Pie
                                :chart-options="dayStatusChartOptions"
                                :chart-data="dayStatusChartData"
                                :width="250"
                                :height="250"
                            />
                        </div>

                        <!-- Stats List Section -->
                        <div class="w-full md:w-auto">
                            <ul class="text-left">
                                <li class="flex justify-between items-center">
                                    <span class="flex items-center">
                                        <span
                                            class="w-3 h-3 bg-green-500 rounded-full mr-2"
                                        ></span>
                                        In
                                    </span>
                                    <span>15</span>
                                </li>
                                <li class="flex justify-between items-center">
                                    <span class="flex items-center">
                                        <span
                                            class="w-3 h-3 bg-red-500 rounded-full mr-2"
                                        ></span>
                                        Out
                                    </span>
                                    <span>15</span>
                                </li>
                                <li class="flex justify-between items-center">
                                    <span class="flex items-center">
                                        <span
                                            class="w-3 h-3 bg-yellow-500 rounded-full mr-2"
                                        ></span>
                                        check in
                                    </span>
                                    <span>15</span>
                                </li>
                                <li class="flex justify-between items-center">
                                    <span class="flex items-center">
                                        <span
                                            class="w-3 h-3 bg-blue-500 rounded-full mr-2"
                                        ></span
                                        >on Break
                                    </span>
                                    <span>15</span>
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
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    CategoryScale
} from "chart.js";

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale);

export default {
    name: "AttendanceCharts",
    components: {
        Pie
    },
    data() {
        return {
          fromDate: "", 

            stats: [
                { label: "Total Users", value: 55, color: "#D3D3D3" },
                { label: "Present", value: 42, color: "#A0D995" },
                { label: "On Duty", value: 2, color: "#A084E8" },
                { label: "Paid Leave", value: 5, color: "#FBC490" },
                { label: "Absent", value: 0, color: "#F67E7D" },
                { label: "Unpaid Leave", value: 1, color: "#F2A0A0" },
                { label: "Holiday Leave", value: 0, color: "#FCE883" },
                { label: "Weekend", value: 0, color: "#FFCF70" },
                { label: "Statutory", value: 6, color: "#FFD580" },
                { label: "Non-Statutory", value: 0, color: "#F4F4F4" }
            ],
            labels: ["In", "Out", "Yet to check-in", "On Break"],
            datasets: [
                {
                    data: [15, 39, 20, 1],
                    backgroundColor: [
                        "#10B981",
                        "#EF4444",
                        "#9CA3AF",
                        "#F59E0B"
                    ],
                    borderWidth: 1
                }
            ],

            // Attendance Chart Data
            attendanceChartData: {
                labels: [
                    "Present",
                    "On Duty",
                    "Paid Leave",
                    "Unpaid Leave",
                    "Statutory"
                ],
                datasets: [
                    {
                        backgroundColor: [
                            "#A3E635", // Present
                            "#60A5FA", // On Duty
                            "#F59E0B", // Paid Leave
                            "#EF4444", // Unpaid Leave
                            "#10B981" // Statutory
                        ],
                        data: [42, 2, 5, 1, 6]
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

            // Day Status Chart Data
            dayStatusChartData: {
                labels: ["In", "Out", "Yet to check-in", "On Break"],
                datasets: [
                    {
                        backgroundColor: [
                            "#34D399",
                            "#F87171",
                            "#FACC15",
                            "#3B82F6"
                        ],
                        data: [15, 39, 0, 1]
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
                },
                cutout: "50%" // Donut chart effect
            }
        };
        const statusList = document.getElementById('status-list');
        dayStatusChartData.labels.forEach((label, index) => {
    const listItem = `
      <li class="flex justify-between items-center">
        <span class="flex items-center">
          <span class="w-3 h-3" style="background-color: ${dayStatusChartData.datasets[0].backgroundColor[index]};" class="rounded-full mr-2"></span>
          ${label}
        </span>
        <span>${dayStatusChartData.datasets[0].data[index]}</span>
      </li>
    `;
    statusList.innerHTML += listItem;  // Append the new list item to the ul
  });
    }
};
</script>

