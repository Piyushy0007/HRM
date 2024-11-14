<template>
  <div class="c-employee-index client-table client-add">
   
    <Loader msg="Processing ..." v-model="isLoader" />
    <div class="px-4 pb-4 add-client">
      <div class="flex " style="justify-content: space-between;">
        <!-- <h1 class="mb-4">Report</h1> -->
        <!-- <button class="add-blue-button" @click="addclient()">Add Property</button> -->
      </div>

    </div>

  </div>
</template>
<style lang="scss" scoped>

</style>
<script>

import Modal from '../shared/Modal'
import Loader from '../shared/Loader'  
import axios from 'axios'
import moment from 'moment'
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import draggable from 'vuedraggable'
// import { BModal, VBModal } from 'bootstrap-vue'
import { BButton } from 'bootstrap-vue'

export default {
	components: {
		Modal,
    Loader,
    DatePicker,
    draggable,
    BButton
	},
	data() {
		return {
      user_id:'',
      index:{
        report:{}
      },
      isLoader: false,
		}
	},
  computed: {
   user_data(){
     return JSON.parse(localStorage.getItem('user'))
   },
   userid(){
    let user = localStorage.getItem('user')
    return JSON.parse(user).id
  }
  },
  async created() {
    let vm = this
     this.user_id = this.userid
    await vm.indexReport()
   
      // vm.isLoader = true
  },
  watch :{

  },
	methods: {
    /**
     * List all report
     */
    indexReport() {
      let vm = this

      vm.isLoader = true
      // axios
      //   .get(`/api/clienthourlyreport/${this.user_id}`)
      //   .then(res => {
      //     vm.index.report = res.data
      //     vm.isLoader = false
      //   })
    },

   


	}
}

</script>

<style lang="scss" scoped>
	@import '../../../sass/client/employees';
</style>