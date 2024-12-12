<template>
  <div>
    

    <div style="align-items: center; margin-left: 255px;">
      <table class="modwideclear">
        <tbody>
          <tr>
            <td class="small hover">
              <router-link
                tag="li"
                :to="{ name: 'schedules' }"
                class="w-1/4 mr-1 rounded-t-lg list-style-none"
                exact
              >
                <a href="#">
                  <font-awesome-icon icon="calendar-alt" class="mr-1" />
                  Show all of today's shifts</a
                >
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>
      <table class="modwide">
        <tbody>
          <tr>
            <td class="wgt"><b class="titlebox">Scheduled On Now</b></td>
          </tr>
          <tr>
            <td class="bWGT">
              <table
                id="data"
                cellpadding="5"
                cellspacing="0"
                width="100%"
                style="font-size: 14px"
              >
                <thead>
                  <tr class="small" style="font-weight: bold">
                    <th style="align: left; width: 20px">
                      All<br />
					   <input v-model="allSelected" type="checkbox" @click="selectAll">
                    </th>
                    <th>Employee</th>
                    <th>Position</th>
                    <th>Property Name</th>
                    <th>Clock in True/False</th>
                    <!-- <th>Start - End</th> -->
                    
                  </tr>
                </thead>
                 <tbody v-if="index.employees.length===0">
                      <tr><td colspan="13" class="px-2">No Records Found</td></tr>
                  </tbody>
                  <tbody v-else>
                  <tr v-for="(data, index) in index.employees" :key="data.id">
                    <td style="align: left; width: 20px" class="text-center"><input :id="data.employee.id" :index="index" :value="data" v-model="checkedNames"   type="checkbox"></td>
                    <td class="text-center">{{ data.employee.firstname }} {{ data.employee.lastname }}</td>
                    <td class="text-center">{{data.position.position}}</td>
                    <td class="text-center">{{data.property.name}}</td>
                    <td class="text-center">{{data.clockin.shiftaction}}</td>
                    <!-- <td class="text-center">{{data.start}} - {{data.end}}</td> -->
                   
                  </tr>
                </tbody>
              </table>
              <br />
              <a
                href="#"
                @click.prevent="SendMessageCheck('SendMessage')"
                class="small"
                title="Send a W2W message to Watchers assigned to the selected shifts"
              >
                <font-awesome-icon
                  :icon="['far', 'envelope']"
                  class="mr-1"
                />Send message to selected</a
              >&nbsp;&nbsp;
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Send Message start -->

    <modal
      v-model="modal.SendMessage"
      class="modal-add-new-employee"
      size="md:w-4/12"
      title="Write a Message"
    >
      <ValidationObserver v-slot="{ handleSubmit }">
        <form
          @submit.prevent="handleSubmit(SendMessageToSelected)"
          ref="frmAddEmployee"
          novalidate
        >
          <!-- ================================= To ================================= -->
          <div class="px-6 pb-4 pt-4 mb-4 flex">
            <h4 class="text-xl font-semibold w-3/12">To</h4>
            <div class="w-9/12">
              <!-- <label class="text-sm" >{{ checkedNames.length }} Employee</label> -->
              <b class="titleBox text-xl font-semibold comma_after_content"><span class="CheckedDescription" v-for="names in checkedNames" :key="names.id"> {{names.employee.email}}</span></b>
            </div>
          </div>
          <!-- ================================= ./To ================================= -->
          <!-- ================================= Subject ================================= -->
          <div class="px-6 pb-4 mb-4 flex">
            <h4 class="text-xl font-semibold w-3/12">Subject</h4>
            <div class="w-9/12">
              <input
                type="text"
                class="w-full rounded p-2 leading-tight focus:outline-none border border-custom-border"
                v-model="modal.Message.subject"
              />
            </div>
          </div>
          <!-- ================================= ./Subject ================================= -->
          <!-- ================================= Message ================================= -->
          <div class="px-6 pb-4 mb-4 flex">
            <h4 class="text-xl font-semibold w-3/12">Message</h4>
            <div class="w-9/12">
              <textarea
                rows="5"
                class="w-full rounded p-2 leading-tight focus:outline-none border border-custom-border"
                v-model="modal.Message.message"
              ></textarea>
            </div>
          </div>
          <!-- ================================= ./Message ================================= -->
          <div class="text-center">
            <input
              type="submit"
              value="Send"
              class="send_ins"
            />
          </div>
        </form>
      </ValidationObserver>
    </modal>
    <!-- Send Message end -->
    <div></div>
  </div>
</template>

<script>
import Modal from "../shared/Modal";
import axios from "axios";
import Loader from "../shared/Loader";

export default {
  components: {
    Modal,
    Loader,
  },
  data() {
    return {
      loader: {
        is: false,
        msg: "Loading ...",
      },
      checkedNames: [],
      bulkids: [],
      index: {
        employees: {},
      },
      modal: {
        addEmployee: {},
        SendMessage: false,
        Message: {},
	  },
	selected : [],
    allSelected: false,
    };
  },
  created() {
    let vm = this;
    vm.Loading();

    vm.indexEmployee()

    setTimeout(function () {
      vm.UnLoading();
    }, 1000);
  },
  computed: {
    countTotalEmployees() {
      return this.index.employees.length;
    },

    // ids of selected employees

    BulkIDS() {
	  let ids = [];
	  this.bulkids = [];
      for (let i = 0; i <= this.checkedNames.length - 1; i++) {
        {
          this.bulkids[i] = this.checkedNames[i].employee.id;
        }
      }
      ids = this.bulkids;
      return ids;
    },
  },
  watch: {
    BulkIDS:{
      handler() {
        console.log(this.BulkIDS, 'checked ids')
      },
       deep: true,
      immediate: true
    }
  },
  methods: {
    /**
     * List all employee
     */
    indexEmployee() {
      let vm = this;
      axios.get(`/api/employees/on_now`).then((res) => {
        vm.index.employees = res.data.data;
      });
    },
    /**
     * Loader
     */
    async Loading() {
      let vm = this;
      vm.loader.is = false;
    },
    async UnLoading() {
      let vm = this;
      vm.loader.is = false;
    },
        selectAll: function() {
            this.checkedNames = [];
            this.allSelected = !this.allSelected
           if(this.allSelected){
                for (let user in this.index.employees) {
                    this.checkedNames.push(this.index.employees[user]);
                    this.allSelected = true
                }
           }
           else{
             this.checkedNames = [];
           }
        },
    // Select  check message
    SendMessageCheck(func) {
      if (this.checkedNames.length != 0 && func == "SendMessage") {
		this.modal.SendMessage = true;
      } else {
        this.$alert(
          "First click the checkboxes to the left of each name to select Watchers."
        );
      }
    },
    DoMessage() {},
    SendMessageToSelected() {
	  let vm = this;

	  vm.modal.Message.id = vm.BulkIDS ;
    //   vm.Loading();
      axios
        .post("/api/employees/send-message", Object.assign(vm.modal.Message))
        .then((res) => {
          vm.$swal({
            icon: "success",
            title: "Successfully sent!",
            showConfirmButton: false,
            timer: 1500,
          });
          vm.UnLoading();
          vm.modal.SendMessage = false;
        });
    },
  },
};
</script>
<style lang="scss" scoped>
@import "../../../sass/onnow";
</style>