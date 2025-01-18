<template>
  <div>
    <Loader msg="Loading Leave Request Listings..." v-model="isLoader" />
    <header-component />
    <div style="margin-left: 242px;">
      <div class="max-w-7xl mx-auto p-4 h-screen flex flex-col">
        <div class="flex items-center justify-between bg-white p-4 shadow-md rounded-lg mb-5">
          <div class="flex items-center">
            <span class="font-semibold text-lg text-gray-800">Payment Details</span>
          </div>
          <div class="flex items-center">
            <label for="month" class="text-black-600 mr-2">Month</label>
            <select
              id="month"
              v-model="selectedMonth"
              class="border border-gray-300 rounded-md px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
              @change="onMonthChange"
            >
              <option value="1">January</option>
              <option value="2">February</option>
              <option value="3">March</option>
              <option value="4">April</option>
              <option value="5">May</option>
              <option value="6">June</option>
              <option value="7">July</option>
              <option value="8">August</option>
              <option value="9">September</option>
              <option value="10">October</option>
              <option value="11">November</option>
              <option value="12">December</option>
            </select>
          </div>
        </div>

        <div class="bg-white shadow-lg rounded-lg flex-grow flex flex-col">
          <div class="flex-grow overflow-auto">
            <table class="w-full table-auto">
              <thead>
                <tr class="text-center border-b">
                  <th class="py-3 px-4 text-blue-600">Name </th>
                  <th
                    class="py-3 px-4 text-blue-600 flex items-center gap-1 justify-center cursor-pointer"
                    @click="sortBy('month')"
                  >
                    Salary Period <b-icon-arrow-down-up />
                  </th>
                  <th class="py-3 px-4 text-blue-600">Designation</th>
                  <th class="py-3 px-4 text-blue-600">Gross Salary</th>
                  <th class="py-3 px-4 text-blue-600">Net Salary</th>
                  <th class="py-3 px-4 text-blue-600">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="isLoader">
                  <td colspan="6" class="text-center loader py-4">Loading...</td>
                </tr>
                <tr v-else-if="employees.length === 0">
                  <td colspan="6" class="text-center py-4">No payment details found for the selected month range.</td>
                </tr>
                <tr v-else v-for="(employee, index) in employees" :key="index" class="border-b text-center">
                  <td class="py-3 px-4">{{ employee.employee.firstname }} {{ employee.employee.lastname }}</td>
                  <td class="py-2 px-4">{{ employee.month }}/{{ employee.year }}</td>
                  <td class="py-2 px-4">{{ employee.employee.position || 'N/A' }}</td>
                  <td class="py-2 px-4">{{ employee.salary.gross_salary }}</td>
                  <td class="py-2 px-4">{{ employee.salary.net_salary }}</td>
                  <td class="py-2 px-4">
                    <div class="gap-2 flex justify-center items-center">
                      <button class="text-blue-500 border border-blue-400 px-2 py-1 rounded-full hover:bg-blue-100 text-sm ">
                        {{ employee.status }}
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="flex items-center justify-between p-4 border-t">
            <div class="flex items-center gap-2">
              <label for="show" class="text-gray-600">Show</label>
              <select
                id="show"
                v-model="itemsPerPage"
                class="border border-gray-300 rounded px-2 py-1 focus:outline-none"
                @change="fetchData"
              >
                <option value="6">6</option>
                <option value="12">12</option>
                <option value="24">24</option>
              </select>
            </div>
            <div class="flex items-center gap-1">
              <button 
                @click="prevPage" 
                :disabled="offset === 0" 
                class="text-blue-500 px-2 py-1 hover:bg-blue-100 disabled:text-gray-400">&laquo;
              </button>
              <span class="px-3 py-1">Page {{ currentPage }}</span>
              <button 
                @click="nextPage" 
                :disabled="offset + itemsPerPage >= totalItems" 
                class="text-blue-500 px-2 py-1 hover:bg-blue-100 disabled:text-gray-400">&raquo;
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
  name: "paymentdetails",
  data() {
    return {
      employees: [],
      isLoader: false,
      itemsPerPage: 6,
      offset: 0,
      totalItems: 0,
      sortByField: "month",
      sortOrder: "desc",
      currentPage: 1,
      selectedMonth: new Date().getMonth() + 1, // Default to the current month
    };
  },
  methods: {
    async fetchData() {
      const url = `/api/salaries?limit=${this.itemsPerPage}&offset=${this.offset}&sortBy=${this.sortByField}&sortOrder=${this.sortOrder}&month=${this.selectedMonth}`;
      try {
        this.isLoader = true;
        const response = await axios.get(url);
        this.employees = response.data;
        this.totalItems = response.data.total;
        console.log(response,"jgajdsfgfsdgaj");
      } catch (error) {
        console.error("Error fetching data:", error);
      } finally {
        this.isLoader = false; 
      }
    },
    prevPage() {
      if (this.offset > 0) {
        this.offset -= this.itemsPerPage;
        this.currentPage--;
        this.fetchData();
      }
    },
    nextPage() {
      if (this.offset + this.itemsPerPage < this.totalItems) {
        this.offset += this.itemsPerPage;
        this.currentPage++;
        this.fetchData();
      }
    },
    sortBy(field) {
      this.sortByField = field;
      this.sortOrder = this.sortOrder === "asc" ? "desc" : "asc";
      this.fetchData();
    },
    onMonthChange() {
      this.offset = 0; // Reset pagination when changing the month
      this.currentPage = 1;
      this.fetchData();
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>

<style scoped>
.table-container {
  overflow-x: auto;
}
</style>
