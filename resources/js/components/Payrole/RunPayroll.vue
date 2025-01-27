<template>
    <div>
      <Loader msg="Loading Leave Request Listings..." v-model="isLoader" />
      <header-component />
      <div style="margin-left: 242px;">
        <div class="max-w-7xl mx-auto p-4 h-screen flex flex-col">
            <div class="flex items-center justify-between bg-white shadow-md rounded-lg p-4 mb-5">
                <div class="font-semibold text-lg text-gray-800">Run Payroll</div>
                <div class="flex items-center">
                  <label for="year" class="text-black-600 mr-2">Financial Year</label>
                  <select id="year" v-model="selectedYear" class="border border-gray-300 rounded-md px-3 py-1 text-gray-700 focus:ring-2 focus:ring-blue-400" @change="onYearChange">
                    <option value="2024">FY 2024-25</option>
                    <option value="2023">FY 2023-24</option>
                  </select>
                </div>
              </div>
              <div class="bg-white shadow-lg rounded-lg flex-grow flex flex-col overflow-auto">
                <table class="w-full table-auto">
                  <tbody>
                    <tr v-for="(quarter, index) in quarters" :key="index" class="border text-center h-40">
                      <td class="py-3 px-4">{{ quarter.label }}</td>
                      <td v-for="month in quarter.months" :key="month" class="py-2 border px-4">
                        <span class="flex flex-col">
                          {{ getMonthRange(month) }}
                          <button v-if="selectedYear" class="mt-2 bg-gray-500 text-white py-1 px-4 rounded w-1/2 mx-auto" @click="runPayroll(month)">Run Payroll</button>
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  export default {
    name: "runpayroll",
    data() {
      return {
        selectedYear: '2024',  // Default selected year
        financialYearRanges: {
          2024: {
            July: { start: '01 Jul', end: '31 Jul' },
            August: { start: '01 Aug', end: '31 Aug' },
            September: { start: '01 Sep', end: '30 Sep' },
            October: { start: '01 Oct', end: '31 Oct' },
            November: { start: '01 Nov', end: '30 Nov' },
            December: { start: '01 Dec', end: '31 Dec' },
            January: { start: '01 Jan', end: '31 Jan' },
            February: { start: '01 Feb', end: '28 Feb' },
            March: { start: '01 Mar', end: '31 Mar' },
            April: { start: '01 Apr', end: '30 Apr' },
            May: { start: '01 May', end: '31 May' },
            June: { start: '01 Jun', end: '31 Jun' }
          },
          2023: {
            July: { start: '01 Jul', end: '31 Jul' },
            August: { start: '01 Aug', end: '31 Aug' },
            September: { start: '01 Sep', end: '30 Sep' },
            October: { start: '01 Oct', end: '31 Oct' },
            November: { start: '01 Nov', end: '30 Nov' },
            December: { start: '01 Dec', end: '31 Dec' },
            January: { start: '01 Jan', end: '31 Jan' },
            February: { start: '01 Feb', end: '28 Feb' },
            March: { start: '01 Mar', end: '31 Mar' },
            April: { start: '01 Apr', end: '30 Apr' },
            May: { start: '01 May', end: '31 May' },
            June: { start: '01 Jun', end: '31 Jun' }
          },
        },
        quarters: [
        { label: 'Q1', months: ['April', 'May', 'June'] },
        { label: 'Q2', months: ['July', 'August', 'September'] },
        { label: 'Q3', months: ['October', 'November', 'December'] },
        { label: 'Q4', months: ['January', 'February', 'March'] },
      ],
      };
    },
    methods: {
        getMonthRange(month) {
            const yearData = this.financialYearRanges[this.selectedYear];
            if (yearData && yearData[month]) {
            let displayYear = this.selectedYear;
            // Extend year for January, February, and March
            if (['January', 'February', 'March'].includes(month)) {
                displayYear = parseInt(this.selectedYear) + 1;
            }
            return `${yearData[month].start} - ${yearData[month].end}, ${displayYear}`;
            }
            return '';
        },
        runPayroll(month) {
            // Navigate to the RunPayrollScr screen with the required month parameter
            this.$router.push({ name: 'runpayrollscr', query: { month: month, year: this.selectedYear } });
        },
    },
  };
  </script>
  
  <style scoped>
    /* .table-container {
        overflow-x: auto;
    } */
  </style>
  