<template>
    <div class="c-payroll-page">
        <header-component />

        <div style="margin-left: 242px;">
            <!-- Breadcrumb Section -->
            <div
            class="flex items-center bg-white p-3 rounded-lg shadow-sm max-w-full whitespace-nowrap"
            >
                <font-awesome-icon icon="home" style="font-size: 20;" />
                <div class="flex items-center text-sm text-gray-600 ml-3">
                    <span
                        class="truncate cursor-pointer hover:text-blue-500 font-bold text-lg"
                    >
                        Payroll Process
                    </span>
                    <font-awesome-icon
                        :icon="['fas', 'chevron-right']"
                        class="mx-2"
                    />
                    <span
                        class="truncate cursor-pointer hover:text-blue-500 font-bold text-lg"
                    >
                        Monthly & Yearly Payroll Report
                    </span>
                </div>
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

            <!-- Chart Section -->
            <div class="flex flex-col w-full">

                <!-- First Chart -->
                  <div class="flex flex-row gap-4"> 

                    <div class="flex flex-col bg-white p-4 rounded-lg shadow-md w-full md:w-1/2">
                    <h3 class="text-xl font-semibold mb-4">
                        Monthly Payroll and TDS Report
                    </h3>
                    <Bar
                        :chart-options="payrollBarChartOptions"
                        :chart-data="payrollBarChartData"
                        :width="400"
                        :height="300"
                    />
                </div>

                <!-- Second Chart -->
                <div class="flex flex-col bg-white p-4 rounded-lg shadow-md w-full md:w-1/2">
                    <h3 class="text-xl font-semibold mb-4">
                        Yearly Payroll Trends
                    </h3>
                    <Bar
                        :chart-options="yearlyBarChartOptions"
                        :chart-data="yearlyBarChartData"
                        :width="400"
                        :height="300"
                    />
                   
                


                </div>
            </div>

                
                                  <div class="flex flex-row gap-4 m-2"> 

                <!-- Third Chart -->

                    <div class="flex flex-col bg-white p-4 rounded-lg shadow-md w-full md:w-1/2">
                    <h3 class="text-xl font-semibold mb-4">
                        Monthly Data Distribution
                    </h3>
                    <Bar
                        :chart-options="verticalBarChartOptions"
                        :chart-data="verticalBarChartData"
                        :width="400"
                        :height="300"
                    />
                </div>

                <!-- Fourth Chart -->
               
                <div class="flex flex-col bg-white p-4 rounded-lg shadow-md w-full md:w-1/2">
                    <h3 class="text-xl font-semibold mb-4">
                        Stacked Horizontal Bar Chart
                    </h3>
                    <Bar
                        :chart-options="stackedBarChartOptions"
                        :chart-data="stackedBarChartData"
                        :width="400"
                        :height="300"
                    />
                </div>
                </div>

                <!-- fifth -->
                                   <div class="flex flex-row gap-4 mt2 mb-2"> 


                    <div class="flex flex-col bg-white p-4 rounded-lg shadow-md w-full md:w-1/2">
                    <h3 class="text-xl font-semibold mb-4">
                        Employee Overtime Analysis
                    </h3>
                    <!-- <Bar
                        :chart-options="overtimeBarChartOptions"
                        :chart-data="overtimeBarChartData"
                        :width="400"
                        :height="300"
                    /> -->
                    <Bar
      :chart-options="stackedBarChartOptionsVerticaly"
      :chart-data="stackedBarChartDataVerticaly"
      :width="400"
      :height="300"
    />

                </div>
                <!-- sixth -->
                <div class="flex flex-col bg-white p-4 rounded-lg shadow-md w-full md:w-1/2">
                    <h3 class="text-xl font-semibold mb-4">
                        Department Payroll Distribution
                    </h3>
                    <Bar
                        :chart-options="departmentBarChartOptions"
                        :chart-data="departmentBarChartData"
                        :width="400"
                        :height="300"
                    />
                </div>
                </div>

                <div class="flex flex-row gap-4 mt2 mb-2"> 


                    <div class="flex flex-col bg-white p-4 rounded-lg shadow-md w-full md:w-1/2">
                    <h3 class="text-xl font-semibold mb-4">
                        Employee Overtime Analysis
                    </h3>
                <Bar
                :chart-options="groupedStackedBarChartOptions"
                :chart-data="groupedStackedBarChartData"
                :width="400"
                :height="300"
            />
            </div>
            </div>

                <!-- sixth -->
                <!-- Stacked Horizontal Bar Chart -->
               

            </div>
        </div>
    </div>
</template>

<script>
import { Bar, Line } from "vue-chartjs/legacy";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    LineElement,
    PointElement,
    filter
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
);

export default {
    name: "PayrollReport",
    components: {
        Bar,
        Line
    },
    data() {
        return {
            // Data for the first chart
            fromDate: '',
            toDate: '',
            payrollBarChartData: {
                labels: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec"
                ],
                datasets: [
                    {
                        label: "Payroll Expense ($)",
                        backgroundColor: "#34D399",
                        data: [
                            1000,
                            11000,
                            10980,
                            10910,
                            10950,
                            101340,
                            151430,
                            141430,
                            15140,
                            151340,
                            161340,
                            17013
                        ]
                    }
                ]
            },
            payrollBarChartOptions: {
                responsive: true,
                plugins: {
                    legend: { position: "top" },
                    title: {
                        display: true,
                        text: "Monthly Payroll and TDS Report"
                    }
                },
                scales: { x: { beginAtZero: true }, y: { beginAtZero: true } }
            },

            // Data for the second chart
            yearlyBarChartData: {
                labels: ["2018","2019","2020", "2022", "2023", "2024"],
                datasets: [
                    {
                        label: "Yearly Payroll ($)",
                        backgroundColor: "#60A5FA",
                        data: [100000,  130000, 150000, 160000,180000,190000]
                    }
                ]
            },
            yearlyBarChartOptions: {
                responsive: true,
                plugins: {
                    legend: { position: "top" },
                    title: { display: true, text: "Yearly Payroll Trends" }
                },
                scales: { x: { beginAtZero: true }, y: { beginAtZero: true } }
            },

            // Data for the third chart
            departmentBarChartData: {
                labels: ["HR", "IT", "Finance", "Operations", "Marketing"],
                datasets: [
                    {
                        label: "Department Payroll ($)",
                        backgroundColor: "#34D399",
                        data: [50000, 70000, 60000, 80000, 45000]
                    }
                ]
            },
            departmentBarChartOptions: {
                responsive: true,
                plugins: {
                    legend: { position: "top" },
                    title: {
                        display: true,
                        text: "Department Payroll Distribution"
                    }
                },
                scales: { x: { beginAtZero: true }, y: { beginAtZero: true } }
            },

            // Data for the fourth chart
            // overtimeBarChartData: {
            //     labels: [
            //         "Jan",
            //         "Feb",
            //         "Mar",
            //         "Apr",
            //         "May",
            //         "Jun",
            //         "Jul",
            //         "Aug",
            //         "Sep",
            //         "Oct",
            //         "Nov",
            //         "Dec"
            //     ],
            //     datasets: [
            //         {
            //             label: "Overtime Hours",
            //             backgroundColor: "#FBBF24",
            //             data: [50, 60, 40, 30, 70, 80, 90, 100, 110, 50, 30, 40]
            //         }
            //     ]
            // },
            // overtimeBarChartOptions: {
            //     responsive: true,
            //     plugins: {
            //         legend: { position: "top" },
            //         title: { display: true, text: "Employee Overtime Analysis" }
            //     },
            //     scales: { x: { beginAtZero: true }, y: { beginAtZero: true } }
            // },
            horizontalBarChartData: {
                labels: ["January", "February", "March"], // Replace Categories with Months
                datasets: [
                    {
                        label: "Payroll",
                        backgroundColor: "#34D399", // Green
                        data: [120, 150, 100] // Payroll data for Jan, Feb, Mar
                    },
                    {
                        label: "Overtime",
                        backgroundColor: "#60A5FA", // Blue
                        data: [80, 100, 90] // Overtime data for Jan, Feb, Mar
                    },
                    {
                        label: "Bonuses",
                        backgroundColor: "#F87171", // Red
                        data: [50, 70, 60] // Bonuses data for Jan, Feb, Mar
                    }
                ]
            },
            horizontalBarChartOptions: {
                responsive: true,
                indexAxis: "y", // Horizontal Bar Chart
                plugins: {
                    legend: { position: "top" },
                    title: { display: true, text: "Monthly Data Distribution" }
                },
                scales: {
                    x: { beginAtZero: true }, // Ensure bars start from 0
                    y: { beginAtZero: true }
                }
            },
            stackedBarChartData: {
                labels: [
                    "Employee A",
                    "Employee B",
                    "Employee C",
                    "Employee D"
                ], // Names or categories
                datasets: [
                    {
                        label: "Approved Overtime",
                        backgroundColor: "#34D399", // Green
                        data: [200, 300, 250, 400] // Values for each employee
                    },
                    {
                        label: "Unapproved Overtime",
                        backgroundColor: "#F87171", // Red
                        data: [150, 100, 200, 150] // Values for each employee
                    },
                    {
                        label: "Planned Overtime",
                        backgroundColor: "#60A5FA", // Blue
                        data: [100, 200, 150, 300] // Values for each employee
                    }
                ]
            },
            stackedBarChartOptions: {
                responsive: true,
                indexAxis: "y", // Horizontal Bar Chart
                plugins: {
                    legend: { position: "top" },
                    title: {
                        display: true,
                        text: "Overtime Analysis by Employee"
                    }
                },
                scales: {
                    x: {
                        stacked: true, // Stack bars along the x-axis
                        beginAtZero: true
                    },
                    y: {
                        stacked: true // Stack bars along the y-axis
                    }
                }
            },
            verticalBarChartData: {
                labels: ["January", "February", "March"], // Months as labels
                datasets: [
                    {
                        label: "Payroll",
                        backgroundColor: "#34D399", // Green
                        data: [120, 150, 100] // Payroll data for Jan, Feb, Mar
                    },
                    {
                        label: "Overtime",
                        backgroundColor: "#60A5FA", // Blue
                        data: [80, 100, 90] // Overtime data for Jan, Feb, Mar
                    },
                    {
                        label: "Bonuses",
                        backgroundColor: "#F87171", // Red
                        data: [50, 70, 60] // Bonuses data for Jan, Feb, Mar
                    }
                ]
            },
            verticalBarChartOptions: {
                responsive: true,
                plugins: {
                    legend: { position: "top" },
                    title: { display: true, text: "Monthly Data Distribution" }
                },
                scales: {
                    x: { beginAtZero: true }, // X-axis starts at 0
                    y: { beginAtZero: true } // Y-axis starts at 0
                }
            },
            areaChartData: {
                labels: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec"
                ], // Months
                datasets: [
                    {
                        label: "Approved",
                        data: [5, 7, 6, 8, 9, 10, 12, 14, 13, 15, 14, 16], // Example data
                        borderColor: "#FB923C", // Line color (orange)
                        backgroundColor: "rgba(251, 146, 60, 0.5)", // Fill color with transparency
                        fill: true, // Enables area filling
                        tension: 0.4 // Smooth curve
                    }
                ]
            },
            areaChartOptions: {
                responsive: true,
                plugins: {
                    legend: { position: "bottom" },
                    title: {
                        display: true,
                        text: "Overtime Year-To-Date Trends"
                    }
                },
                scales: {
                    x: { beginAtZero: true }, // X-axis (months)
                    y: { beginAtZero: true } // Y-axis (values)
                }
            },
            stackedBarChartDataVerticaly: {
        labels: ["January", "February", "March", "April", "May", "June"],
        datasets: [
          {
            label: "Category A",
            data: [30, 40, 25, 50, 60, 35],
            backgroundColor: "#34D399"
          },
          {
            label: "Category B",
            data: [20, 30, 35, 25, 45, 50],
            backgroundColor: "#60A5FA"
          },
          {
            label: "Category C",
            data: [50, 20, 40, 35, 30, 40],
            backgroundColor: "#F87171"
          }
        ]
      },
      stackedBarChartOptionsVerticaly: {
        responsive: true,
        plugins: {
          legend: { position: "top" },
          title: { display: true, text: "Stacked Vertical Bar Chart" }
        },
        scales: {
          x: {
            stacked: true,
            beginAtZero: true
          },
          y: {
            stacked: true,
            beginAtZero: true
          }
        }
      },
      groupedStackedBarChartData: {
                labels: ["1", "2", "3", "4",], 
                datasets: [
                    // Bar 1: Data per group
                    {
                        label: "This Year (Segment A)",
                        backgroundColor: "#F87171", // Red
                        stack: "Stack 1",
                        data: [30, 40, 50, 60] // Values for bar segment A
                    },
                    {
                        label: "This Year (Segment B)",
                        backgroundColor: "#60A5FA", // Blue
                        stack: "Stack 1",
                        data: [20, 30, 40, 5] // Values for bar segment B
                    },
                    {
                        label: "This Year (Segment C)",
                        backgroundColor: "#34D399", // Green
                        stack: "Stack 1",
                        data: [10, 20, 30, 40,] // Values for bar segment C
                    },

                    // Bar 2: Data per group
                    {
                        label: "Last Year (Segment A)",
                        backgroundColor: "#FBBF24", // Yellow
                        stack: "Stack 2",
                        data: [25, 35, 45, 55,] // Values for bar segment A
                    },
                    {
                        label: "Last Year (Segment B)",
                        backgroundColor: "#A78BFA", // Purple
                        stack: "Stack 2",
                        data: [15, 25, 35, 45, ] // Values for bar segment B
                    },
                    {
                        label: "Last Year (Segment C)",
                        backgroundColor: "#D1D5DB", // Gray
                        stack: "Stack 2",
                        data: [5, 15, 25, 35,] // Values for bar segment C
                    }
                ]
            },
            groupedStackedBarChartOptions: {
                responsive: true,
                plugins: {
                    legend: { position: "top" },
                    title: {
                        display: true,
                        text: "Grouped Stacked Bar Chart Example"
                    }
                },
                scales: {
                    x: {
                        stacked: false // Group bars for each category
                    },
                    y: {
                        stacked: true // Stack segments within each bar
                    }
                }
            }




        };
    },
    methods: {
        fetchAttendanceData() {
        console.log("Fetching attendance data for:", this.fromDate);
        // Add your logic for fetching attendance data here
        },
    },
};
</script>

<style scoped>
.grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}
</style>
