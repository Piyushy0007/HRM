<template>
  <div class="c-employee-index ">
    <Loader msg="Processing ..." v-model="isLoader" />
    <div class="px-4 pb-4 w-80" style="margin-right: 1vw; margin-left: 240px;">
      <div class="flex" style="justify-content: space-between">
        <h1 class="mb-4 mt-4">Reports</h1>
      </div>
      <div class="flex mb-4">
        <!-- <button class="add-blue-button" @click="openDateModal()">Choose Date</button> -->
        <b-button class="add-blue-button" @click="opendatepicker()">Choose Date</b-button>
        <div class="px-3">
          <div class="relative">
            <input
              class="officer-report-search appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none"
              type="text"
              placeholder="Search"
              v-model="searchKeyword"
              @keyup="search()"
            />
            <div
              class="border-none absolute inset-y-0 right-0 flex items-center px-2 text-custom-border rounded-r border-custom-border border-l-0">
              <font-awesome-icon icon="search" class="fill-current" />
            </div>
          </div>
        </div>
      </div>
      <table class="table-auto w-full border border-custom-border">
        <thead class="thead-light bg-custom-bg_table_head_primary border-custom-border">
          <tr class="text-gray-600 border border-custom-border rounded-lg">
            <th style="width: 20%" class="text-left p-2 border border-custom-border heading-sort">
              <div class="flex items-center gap-1">
                Date/Time Submitted <b-icon-arrow-down-up @click="namesort()" />
              </div>
            </th>
            <th style="width: 10%" class="text-left p-2 border border-custom-border">Slot</th>
            <th style="width: 10%" class="text-left p-2 border border-custom-border">Officer</th>
            <th style="width: 10%" class="text-left p-2 border border-custom-border">ID #</th>
            <th style="width: 23%" class="text-left p-2 border border-custom-border heading-sort">Location</th>
            <th style="width: 20%" class="text-left p-2 border border-custom-border heading-sort">Report Type</th>
            <th style="width: 10%" class="text-left p-2 border border-custom-border">Status</th>
            <th style="width: 7%" class="text-center p-2 border border-custom-border">Actions</th>
          </tr>
        </thead>

        <tbody>
          <template v-if="reportsdata && reportsdata.length != 0">
            <tr v-for="data in reportsdata" :key="data.id">
              <!-- <tr> -->
              <td class="text-left p-2 border border-custom-border">
                {{ data.date | moment("MM/DD/YYYY") }}
                 {{ data.time }}
              </td>
              <td class="text-left p-2 border border-custom-border">{{ data.slot }}</td>
              <td class="text-left p-2 border border-custom-border">
                <span v-for="empname in data.employee" :key="empname.id" class="capitalize">
                  {{ empname.firstname || "-" }} {{ empname.lastname }}
                </span>
              </td>
              <td class="text-left p-2 border border-custom-border">{{ data.id }}</td>
              <td class="text-left p-2 border border-custom-border location">
                <span v-for="location in data.property" :key="location.id">
                  {{ location.address || "-" }}
                </span>
              </td>
              <td class="text-left p-2 border border-custom-border">
                <span v-for="report in data.report" :key="report.id">
                  {{ report.report_name || "-" }}
                </span>
              </td>
              <td
                :class="
                  data.status == 'approved'
                    ? 'active text-left capitalize p-2 border border-custom-border'
                    : 'inactive text-left capitalize p-2 border border-custom-border'
                "
              >
                {{ data.status }}
              </td>
              <td class="text-left p-2 border border-custom-border">
                <div class="flex" style="justify-content: flex-start">
                  <b-button
                    class="m-2 action-button view"
                    @click="viewReport(data)"
                  >
                    <b-icon-list-task />View
                  </b-button>
                  <!-- <b-button class="m-2 action-button edit"  @click="editReport(data)"> <b-icon-pencil-square   />Edit</b-button> -->
                  <b-button
                    class="m-2 action-button approve"
                    @click="
                      approveReportfromOutside(data.id)
                    "
                    v-if="data.status == 'pending'"
                  >
                    <b-icon-check-circle />Approve</b-button
                  > 
                  <a  v-if="data.status == 'approved'" :href="'/api/adminhourlyPdfReportdata/' + data.employee[0].id +'/'+ data.report[0].id +'/'+ data.date" target="_blank" style="text-decoration:none;">
                  <b-button
                    class="m-2 action-button download"
                    :download="true"
                  >
                    <b-icon-cloud-arrow-down />Download(pdf)</b-button
                  >
                  </a>
                </div>
              </td>
            </tr>
          </template>
          <template v-else> <td class="text-center" colspan="8">No Records Found</td>  </template>
        </tbody>
      </table>
    </div>
    <!-- Date Model -->
    <!-- <modal v-model="modal.datemodal" class="modal-add-new-employee" size="md:w-5/12" title="Date Picker">
      <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(storeDate())" ref="frmdatemodal" novalidate>
    		  <div class=" px-4 mb-4 mt-4">
				   <b-calendar
				selected-variant="primary"
				today-variant="info"
				nav-button-variant="primary"
				v-model="modal.date"
			>
			
			</b-calendar>
    		  </div>
    		
          <div class="text-center mt-2 mb-2">
    				<button class="text-white py-3 px-12 rounded-full  bg-custom-primary" type="submit">Set</button>
          </div>

    		
        </form>
      </ValidationObserver>
		</modal> -->

    <b-modal
      centered
      class="modal-add-new-employee"
      id="datepicker"
      title="Date picker"
      style="margin: 0 auto"
    >
      <!-- <template #modal-header="{ close }"> -->
      <!-- Emulate built in modal header close button action -->
      <!-- <b-button size="sm" variant="outline-danger" @click="close()">
				Close Modal
			</b-button> -->
      <!-- <h2 class="text-center py-2 text-2xl font-semibold">
						   Date Picker
			</h2> -->
      <!-- </template> -->

      <b-calendar
        selected-variant="success"
        today-variant="info"
        nav-button-variant="primary"
        v-model="modal.date"
      >
      </b-calendar>

      <template #modal-footer="{}">
        <div class="text-center mt-2 mb-2" style="margin: 0 auto">
          <button
            @click="sendDate()"
            class="text-white py-3 px-12 rounded-full bg-custom-primary"
            type="submit"
          >
            Select
          </button>
        </div>
      </template>
    </b-modal>
    <!-- Date Model -->
    <!-- View Report -->
    <modal
      v-model="modal.viewReportModal"
      class="modal-add-new-employee modal-view-report"
      size="md:w-6/12"
      :title="modal.reportTypeName"
      id="printThis"
    >
    <div style="min-height:480px;">
      <div class="px-4 mb-8 mt-8">

        <div class="upper_section px-3" style="float:left;width:60%">
          <div class="section1 flex mb-3">
            <span class="reporttype" style="width:130px">Report type</span>
            <span class="reportypevalue ml-2">
              <span
                v-for="report in modal.viewreportmodal.report"
                :key="report.id"
              >
                {{ report.report_name || "-" }}
              </span>
            </span>
          </div>
          <div class="section1 mb-3">
            <span class="flex">
              <span class="reporttype" style="width:130px">Officer Name</span>
              <span class="reportypevalue ml-2">
                <span
                  v-for="empname in modal.viewreportmodal.employee"
                  :key="empname.id"
                >
                  {{ empname.firstname || "-" }} {{ empname.lastname }}
                </span>
              </span>
            </span>

            <span class="flex">
              <span class="reporttype" style="width:135px;margin-top:10px;">ID No. </span>
              <span class="reportypevalue ml-2" style="margin-top:10px;">
                <span> {{ modal.viewreportmodal.id }} </span>
              </span>
            </span>

            <span class="flex" v-if="modal.viewreportmodal.report_type_id == 2">
              <span class="reporttype" style="width:145px;margin-top:10px;">Location </span>
              <span class="reportypevalue ml-2" style="margin-top:10px;">
                <span> {{ modal.viewreportmodal.location }} </span>
              </span>
            </span>

            <span class="flex" v-if="modal.viewreportmodal.report_type_id == 2">
              <span class="reporttype" v-for="type in modal.allsubtypeincident" :key="type.id"  style="margin-top:10px;">
                <template
                  v-if="modal.viewreportmodal.report_subtype_id == type.id"
                  >{{ type.report_type_name }}: </template
                >
              </span>

              <span class="reportypevalue ml-2" style="margin-top:10px;">
                <span> {{ modal.viewreportmodal.description }} </span>
              </span>
            </span>

          </div>
        </div>
        <div class="upper_section px-3" v-if="modal.viewreportmodal.report_type_id == 2" style="float:left;width:40%">
            <div class="right_section mt-1" v-if="modal.viewreportmodal.report_image !='' ">
              <img class="b-avatar-img-class" style="max-height: 270px;" :src="this.currentpath+''+modal.viewreportmodal.report_image"/>
            </div>
        </div>

        <div
          class="lower_section mt-10"
          style="max-height:260px;overflow-y:scroll;width:100%;"
          v-if="modal.viewreportmodal.report_type_id == 1"
        >
          <table class="w-full">
            <thead>
              <tr style="height: 45px;background-color:#302369;-webkit-print-color-adjust: exact;color: #FFFFFF;opacity: 1;font-family:'Open Sans';font-size: 15px;">
                <th style="width: 10%;border-top-left-radius:10px" class="text-center">Date</th>
                <th style="width: 15%" class="text-center">Time</th>
                <th style="width: 35%" class="text-center">Location</th>
                <th style="width: 40%; border-top-right-radius10px;" class="text-center">Description</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="reportdata in modal.viewreportmodalreport"
                :key="reportdata.id"
              >
                <td class="text-center pl-5 pr-5">
                  {{ reportdata.date | moment("MM/DD/YYYY") }}
                </td>
                <td class="text-center">{{ reportdata.time }}</td>
                <td class="text-center">{{ reportdata.location }}</td>
                <td class="text-center pr-5 pt-2 pb-2">
                  {{ reportdata.description }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        
      </div>
      <div class="text-center mt-2 mb-2 flex hidebtnwhenprint" style="justify-content: center;width: 100%;float: left;margin-top: 0px;">
        <b-button
          class="m-2 action-button edit"
          @click="editReport(modal.viewreportmodal)"
        >
          <b-icon-pencil-square />Edit</b-button
        >

        <!-- <b-button class="m-2 action-button edit" @click="PrintReport()" style="    background: #302369;"> <b-icon-list-task   />Print </b-button> -->

        <!-- <b-button
          class="m-2 action-button approve"
          v-if="modal.viewreportmodal.status != 'approved'"
          @click="approveReport(modal.viewreportmodalreport)"
        >
          <b-icon-check-circle />Approve</b-button
        >
        <b-button class="m-2 action-button reject" v-else @click="rejectReport()"> <b-icon-list-task   />Reject </b-button> -->
      </div>
      </div>
    </modal>
    <!-- View Report -->
    <!-- Edit Report -->
    <modal
      v-model="modal.editReportModal"
      class="modal-add-new-employee modal-edit-report"
      size="md:w-6/12"
      title="Edit Report"
    >
      <template v-if="modal.editreportmodal.report_type_id == 1">
        <div class="px-4 mb-4 mt-10">
          <table class="w-full">
            <thead>
              <tr>
                <th style="width: 10%" class="text-center">Time</th>
                <th style="width: 15%" class="text-center"></th>
                <th style="width: 80%" class="text-left">Report</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="reportdata in modal.editreportmodalreport"
                :key="reportdata.id"
              >
                <td class="text-center">
                  <!-- <datetime :clear-button="true" :format="customFormatter" width="300px" v-model="reportdata.created_at"></datetime>  -->
                  <datepicker
                    :format="customFormatter"
                    :value="reportdata.date"
                    v-model="reportdata.date"
                  ></datepicker>
                </td>
                <td class="text-left">
                  <vue-timepicker
                    format="h:mm A"
                    :placeholder="reportdata.time"
                    close-on-complete
                    hide-clear-button
                    :value="reportdata.time"
                    v-model="reportdata.time"
                  ></vue-timepicker>
                </td>
                <td class="text-left">
                  <textarea class="text" v-model="reportdata.description" />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </template>
      <template v-else-if="modal.editreportmodal.report_type_id == 2">
        <div class="px-4 mb-4 mt-10 ml-10 mr-10">
          <div class="upper_section mb-3">
            <div class="heading">Report type</div>

            <select
              id="status"
              v-model="modal.editreportmodal.report_subtype_id"
              class="
                mt-1
                block
                w-full
                py-1
                px-4
                pr-8
                rounded-10
                height-41
                leading-tight
                focus:outline-none
              "
              style="
                font: normal normal normal 18px Source Sans Pro;
                height: 50px;
              "
            >
              <option
                v-for="type in modal.allsubtypeincident"
                :key="type.id"
                :value="type.id"
              >
                {{ type.report_type_name }}
              </option>
            </select>
          </div>
          <div class="lower_section mt-10">
            <div class="heading">Description</div>
            <div class="card">
              <textarea
                v-model="modal.editreportmodal.description"
                class="text"
                style="font: normal normal normal 18px Source Sans Pro"
              />
            </div>
          </div>
        </div>
      </template>

      <div
        class="text-center mt-2 mb-2 pb-6 flex"
        style="margin: 0 auto; justify-content: flex-end; width: 90%"
      >
        <button
          @click="savereport()"
          class="mr-2 text-white py-3 px-12 rounded-full bg-custom-primary"
          type="submit"
        >
          Save
        </button>
        <button
          @click="cancelreport()"
          class="mr-2 text-white py-3 px-12 rounded-full bg-custom-purple-trans"
          type="submit"
        >
          Cancel
        </button>
      </div>
    </modal>
    <!-- Edit Report -->
    <b-modal
      size="lg"
      no-close-on-esc
      hide-header-close
      class="modal-add-new-employee modal-edit-report"
      id="editreport"
      title="Edit Report"
      style="margin: 0 auto"
    >
      <div class="px-4 mb-4 mt-4">
        <template v-if="modal.editreportmodal.report_type_id == 1">
          <table class="w-full">
            <thead>
              <tr>
                <th style="width: 5%" class="text-center">Time</th>
                <th style="width: 5%" class="text-center"></th>
                <th style="width: 90%" class="text-left">Report</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="reportdata in modal.editreportmodalreport"
                :key="reportdata.id"
              >
                <td class="text-center" style="width: 15%">
                  <!-- <datetime format="YYYY-MM-DD" width="300px" v-model="reportdata.date"></datetime>  -->
                  {{ reportdata.date }}
                  <b-form-datepicker
                    :date-format-options="{
                      year: 'numeric',
                      month: 'numeric',
                      day: 'numeric',
                    }"
                    autocomplete="off"
                    id="example-datepicker"
                    v-model="reportdata.date"
                    class="mb-2"
                  ></b-form-datepicker>
                </td>
                <td class="text-left">{{ reportdata.time }}</td>
                <td class="text-left">
                  <textarea class="text" v-model="reportdata.description" />
                </td>
              </tr>
            </tbody>
          </table>
        </template>
        <template v-else-if="modal.editreportmodal.report_type_id == 2">
          <div class="upper_section">
            <div class="heading">Report type</div>
            <h3>Incident</h3>
          </div>
          <div class="lower_section">
            <div class="heading">Description</div>
            <div class="card">
              <textarea
                v-model="modal.editreportmodal.description"
                class="text"
              />
            </div>
          </div>
        </template>
      </div>

      <template #modal-footer="{}">
        <div
          class="text-center mt-2 mb-2 flex"
          style="margin: 0 auto; justify-content: flex-end; width: 90%"
        >
          <button
            @click="savereport()"
            class="mr-2 text-white py-3 px-12 rounded-full bg-custom-primary"
            type="submit"
          >
            Save
          </button>
          <button
            @click="cancelreport(modal.viewreportmodal)"
            class="mr-2 text-white py-3 px-12 rounded-full bg-custom-primary"
            type="submit"
          >
            Cancel
          </button>
        </div>
      </template>
    </b-modal>
    <!-- Download Report PDF design start -->
    <div
      id="printThisHourly"
      class="px-4 mb-8 mt-8"
      style="position: absolute; top: -1000000px"
    >
      
      <div class="text-center" style="display: flex">
        <div class="image left-section">
          <img
            src="/images/logo.png"
            alt="Eyewitness"
            style="height: 70px; margin: 20px 20px 10px 20px"
          />
        </div>
        
        <div class="image right-section">
          <div
            class="heading"
            style="
              text-align: left;
              font-size: 26px;
              font-weight: bold;
              letter-spacing: 0px;
              margin-top:10px;
              color: #302369;
              text-transform: uppercase;
              opacity: 1;
              font-family: sans-serif;
            "
          >
            Eyewitness
          </div>

          
          <div
            class="website"
            style="
              text-align: left;
              font-size: 20px;
              letter-spacing: 0px;
              color: #515151;
              opacity: 1;
            "
          >
            8012 South Ashland Ave
          </div>
          <div
            class="website"
            style="
              text-align: left;
              font-size: 20px;
              letter-spacing: 0px;
              color: #515151;
              opacity: 1;
            "
          >
            Chicago, IL 60620
          </div>
          <div
            class="website"
            style="
              text-align: left;
              font-size: 20px;
              letter-spacing: 0px;
              color: #515151;
              opacity: 1;
            "
          >
           (888)-575-5598
          </div>
          <div
            class="email"
            style="
              text-align: left;
              font-size: 20px;
              letter-spacing: 0px;
              color: #515151;
              opacity: 1;
            "
          >
            info@swssgroup.com
          </div>
          
        </div>
      </div>

      <div
            style="
              text-align: right;
              float:right;
              width:100%;
              margin-top:-100px;
              font-size: 22px;
              letter-spacing: 0px;
              color: #302369;
              opacity: 1;
            "
          >
            License #: 122-001312
          </div>

      <div
        class="text-center"
        style="
          text-align: center;
          margin-top:50px;
          font: normal normal 600 26px Source Sans Pro;
          letter-spacing: 0px;
          color: #302369;
          opacity: 1;
        "
      >
        <span v-for="report in downloadupperdata.report" :key="report.id">
          {{ report.report_name || "-" }}
        </span>
        Report
      </div>
      <div
        style="
          position: relative;
          padding-bottom: 1.5rem;
          padding-left: 1.5rem;
          padding-right: 1.5rem;
        "
      >
        <div
          style="
            padding-left: 1rem;
            padding-right: 1rem;
            margin-bottom: 2rem;
            margin-top: 2rem;
          "
        >
          <div
            class="upper_section px-3"
            style="ppadding-left: 0.75rem; padding-right: 0.75rem"
          >
            <div class="section1 flex mb-3" style="display: flex">
              <span
                class="reporttype"
                style="
                  op: 0px;
                  text-align: left;
                  font: normal normal normal 19px Montserrat;
                  letter-spacing: 0px;
                  color: #000000;
                  font-weight: 700;
                "
                >Report type:</span
              >
              <span class="reportypevalue ml-2" style="margin-left: 0.5rem">
                <span
                  v-for="report in downloadupperdata.report"
                  :key="report.id"
                  style="
                    text-align: left;
                    font: normal normal normal 19px Montserrat;
                    letter-spacing: 0px;
                    color: #302369;
                    font-weight: 500;
                  "
                >
                  {{ report.report_name || "-" }}
                </span>
              </span>
            </div>
            <div class="section1 mb-3">
              <span class="flex" style="display: flex">
                <span
                  class="reporttype"
                  style="
                    top: 0px;
                    text-align: left;
                    font: normal normal normal 19px Montserrat;
                    letter-spacing: 0px;
                    color: #000000;
                    font-weight: 700;
                  "
                  >Officer Name:</span
                >
                <span class="reportypevalue ml-2" style="margin-left: 0.5rem">
                  <span
                    v-for="empname in downloadupperdata.employee"
                    :key="empname.id"
                    style="
                      text-align: left;
                      text-transform: capitalize;
                      font: normal normal normal 19px Montserrat;
                      letter-spacing: 0px;
                      color: #302369;
                      font-weight: 500;
                    "
                  >
                    {{ empname.firstname || "-" }} {{ empname.lastname }}
                  </span>
                </span>
              </span>
              <span class="flex" style="display: flex">
                <span
                  class="reporttype"
                  style="
                    top: 0px;
                    text-align: left;
                    font: normal normal normal 19px Montserrat;
                    letter-spacing: 0px;
                    color: #000000;
                    font-weight: 700;
                  "
                  >ID No. :</span
                >
                <span class="reportypevalue ml-2" style="margin-left: 0.5rem">
                  <span
                    style="
                      text-align: left;
                      font: normal normal normal 19px Montserrat;
                      letter-spacing: 0px;
                      color: #302369;
                      font-weight: 500;
                      text-transform: capitalize;
                    "
                  >
                    {{ downloadupperdata.report_type_id }}
                  </span>
                </span>
              </span>

              <span
                class="flex"
                style="display: flex"
                v-if="downloadupperdata.report_type_id == 2"
              >
                <span
                  class="reporttype"
                  style="
                    top: 0px;
                    text-align: left;
                    font: normal normal normal 19px Montserrat;
                    letter-spacing: 0px;
                    color: #000000;
                    font-weight: 700;
                  "
                  >Report Sub Type:</span
                >
                <span class="reportypevalue ml-2" style="margin-left: 0.5rem">
                  <span
                    v-for="type in modal.allsubtypeincident"
                    :key="type.id"
                    style="
                      text-align: left;
                      font: normal normal normal 19px Montserrat;
                      letter-spacing: 0px;
                      color: #302369;
                      font-weight: 500;
                      text-transform: capitalize;
                    "
                  >
                    <template
                      v-if="downloadpdfdata.report_subtype_id == type.id"
                      >{{ type.report_type_name }}</template
                    >
                  </span>
                </span>
              </span>
              <span
                class="flex"
                style="display: flex"
                v-if="downloadupperdata.report_type_id == 2"
              >
                <span
                  class="reporttype"
                  style="
                    top: 0px;
                    text-align: left;
                    font: normal normal normal 19px Montserrat;
                    letter-spacing: 0px;
                    color: #000000;
                    font-weight: 700;
                  "
                  >Location: </span
                >
                <span class="reportypevalue ml-2" style="margin-left: 0.5rem">
                  <span
                    style="
                      text-align: left;
                      font: normal normal normal 19px Montserrat;
                      letter-spacing: 0px;
                      color: #302369;
                      font-weight: 500;
                      text-transform: capitalize;
                    "
                  >
                    {{ downloadpdfdata.location }}
                  </span>
                </span>
              </span>
              <span
                class="flex"
                style="display: flex"
                v-if="downloadupperdata.report_type_id == 2"
              >
                <span
                  class="reporttype"
                  style="
                    top: 0px;
                    text-align: left;
                    font: normal normal normal 19px Montserrat;
                    letter-spacing: 0px;
                    color: #000000;
                    font-weight: 700;
                  "
                  >Description: </span
                >
                <span class="reportypevalue ml-2" style="margin-left: 0.5rem">
                  <span
                    style="
                      text-align: left;
                      font: normal normal normal 19px Montserrat;
                      letter-spacing: 0px;
                      color: #302369;
                      font-weight: 500;
                      text-transform: capitalize;
                    "
                  >
                    {{ downloadpdfdata.description }}
                  </span>
                </span>
              </span>
            </div>
          </div>
          <div
            class="lower_section mt-10"
            style="margin-top: 2.5rem"
            v-if="downloadupperdata.report_type_id == 1"
          >
            <table class="w-full" style="border: none; width: 100%">
              <thead>
                <tr style="height: 45px;background: 0% 0% no-repeat padding-box padding-box rgb(48, 35, 105);color: rgb(255, 255, 255);opacity: 1;font-size: 15px;">
                  <th style="width: 10%; text-align: center;border-top-left-radius: 10px">Date</th>
                  <th style="width: 15%; text-align:center;">Time</th>
                  <th style="width: 35%; text-align:center;">Location</th>
                  <th style="width: 40%; text-align: center;border-top-right-radius: 20px;">Description</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="reportdata in downloadpdfdata" :key="reportdata.id">
                  <td class="text-center pl-5 pr-5" style="width: 10%; border-left:1px solid #000000;border-bottom:1px solid #000000;">
                    {{ reportdata.date | moment("MM/DD/YYYY") }}
                  </td>
                  <td class="text-center" style=" width: 15% ;text-align: center;border-left:1px solid #000000;border-bottom:1px solid #000000;">{{ reportdata.time | moment("h:mm A") }}</td>
                  <td class="text-center" style=" width: 35%;text-align: center;border-left:1px solid #000000;border-bottom:1px solid #000000;">
                    {{ reportdata.location }}
                  </td>
                  <td class="text-center" style="width: 40%; text-align: center;border-left:1px solid #000000;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                    {{ reportdata.description }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- Download Report PDF design end-->
  </div>
</template>

<script>
import Vue from "vue";
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
// import 'bootstrap/dist/css/bootstrap.css'
// import 'bootstrap-vue/dist/bootstrap-vue.css'
import "bootstrap-vue/src/components/form-datepicker";
import "bootstrap-vue/src/components/calendar";
import moment from "moment";
import axios from "axios";
import Modal1 from "./shared/Modal1";
import Modal from "./shared/Modal";
import Loader from "./shared/Loader";
import { BCalendar } from "bootstrap-vue";
import { BFormDatepicker } from "bootstrap-vue";
import datetime from "vuejs-datetimepicker";
import Datepicker from "vuejs-datepicker";
import VueTimepicker from "vue2-timepicker";
import "vue2-timepicker/dist/VueTimepicker.css";
import Header from "./shared/Header.vue";
Vue.component("b-form-datepicker", BFormDatepicker);

export default {
  components: {
    BootstrapVue,
    IconsPlugin,
    Modal1,
    Modal,
    Loader,
    BCalendar,
    datetime,
    Datepicker,
    VueTimepicker,
  },
  data() {
    return {
      currentpath : window.location.origin,
      nameSort: false,
      isLoader: false,
      searchKeyword: "",
      searchTimer: null,
      reportsdata: [],
      mode: "view",
      report_id: "",
      report_type_id: "",
      employee_id: "",
      datee: "",
      timee: "",
      senddata: "",
      downloadpdfdata: "",
      downloadupperdata: "",
      downloadpdfbool: false,
      modal: {
        datemodal: false,
        date: "",
        viewreportmodal: [],
        viewreportmodalreport: [],
        viewReportModal: false,
        allsubtypeincident: [],
        editreportmodal: [],
        editreportmodalreport: [],
        editReportModal: false,
        reportTypeName:'Hourly Report',
      },
    };
  },
  computed: {},
  //   async beforeCreate(){
  // 	       [
  //         "https://unpkg.com/bootstrap/dist/css/bootstrap.min.css",
  //         "https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css",
  //       ].forEach((route) => {
  //         const link_el = document.createElement("link");
  // 		link_el.setAttribute("href", route);
  //         link_el.setAttribute("type", "stylesheet");
  //         // link_el.setAttribute("id", "bootstrapcss");

  //         document.head.appendChild(link_el);
  //       });
  //   },
  async created() {
    let vm = this;
    // vm.inject_material_fonts_and_icons_into_header();
    await vm.indexEmployee();
  },
  async beforeDestroy() {
    let vm = this;
    vm.destroystyle();
  },
  async destroyed() {
    let vm = this;
  },
  watch: {},
  methods: {
    customFormatter(date) {
      return moment(date).format("MM/DD/YYYY");
    },
    customFormatter1(time) {
      return moment(time).format("h:mm A");
    },
    savereport() {
      if (this.modal.editreportmodalreport.report_type_id == 2) {
        let y = this.modal.editreportmodalreport.date;
        this.modal.editreportmodalreport.date = moment(y).format("MM/DD/YYYY");
        this.senddata = this.modal.editreportmodalreport;
        let vm = this;
        axios
          .post(`/api/updatereport`, {
            report_type_id: this.report_type_id,
            subtype: this.senddata.report_subtype_id,
            description: this.senddata.description,
            id: this.senddata.id,
          })
          .then((res) => {
            if (res.data.status != true) {
              vm.$swal({
                icon: "error",
                title: "in progress",
                showConfirmButton: false,
                timer: 1500,
              });
              //  vm.reportsdata = [];
            } else {
              vm.$swal({
                icon: "success",
                title: "Data Saved Successfully!",
                showConfirmButton: false,
                timer: 1500,
              });
              vm.modal.editReportModal = false;
              vm.indexEmployee();
            }
          });
      } else {
        this.senddata = this.modal.editreportmodalreport;
        this.senddata.map((x) => {
          return (x.date = moment(x.date).format("MM/DD/YYYY"));
        });
        let vm = this;
        axios
          .post(`/api/updatereport`, {
            data: this.senddata,
            report_type_id: this.senddata[0].report_type_id,
          })
          .then((res) => {
            if (res.data.status != true) {
              vm.$swal({
                icon: "error",
                title: "in progress",
                showConfirmButton: false,
                timer: 1500,
              });
              //  vm.reportsdata = [];
            } else {
              vm.$swal({
                icon: "success",
                title: "Data Saved Successfully!",
                showConfirmButton: false,
                timer: 1500,
              });
              vm.modal.editReportModal = false;
              vm.indexEmployee();
            }
          });
      }
    },
    cancelreport(data) {
      let vm = this;
      this.modal.editreportmodal = [];
      // vm.$bvModal.hide('editreport');
      // vm.destroystyle();
      this.modal.editReportModal = false;
      // viewReport(data);
      this.modal.viewReportModal = true;
    },
    opendatepicker() {
      this.inject_material_fonts_and_icons_into_header();
      setTimeout(() => {
        this.$bvModal.show("datepicker");
      }, 1000);
    },
    sendDate() {
      let vm = this;
      axios
        .post(`/api/filterreports`, {
          range_date: vm.modal.date,
          search: vm.searchKeyword,
        })
        .then((res) => {
          if (res.data.status != true) {
            vm.$swal({
              icon: "error",
              title: res.data.message,
              showConfirmButton: false,
              timer: 1500,
            });
            //  vm.reportsdata = [];
          } else {
            vm.$swal({
              icon: "success",
              title: "Data Fetched Successfully!",
              showConfirmButton: false,
              timer: 1500,
            });
            this.$bvModal.hide("datepicker");
            vm.reportsdata = res.data.data;
          }
        });
    },
    viewReport(data) {
      let vm = this;

      this.datee = data.date;
      this.date = moment(this.datee).format("YYYY-MM-D");
      this.report_type_id = data.report_type_id;
      this.report_id = data.id;
      this.employee_id = data.employee[0].id;
      this.mode = "view";
      let datatosent = data;
      if (vm.downloadpdfbool == true) {
        axios
          .post(`/api/viewreport`, {
            id: this.report_id,
            report_type_id: this.report_type_id,
            employee_id: this.employee_id,
            date: this.date,
          })
          .then((res) => {
            if (res.data.status != false) {
              vm.downloadpdfdata = res.data.data;
              if (this.report_type_id == 1) {
                vm.downloadupperdata = vm.downloadpdfdata[0];
              } else {
                vm.downloadupperdata = vm.downloadpdfdata;
              }
              vm.allsubtypereportincident();
            } else {
              vm.isLoader = false;
              vm.$swal({
                icon: "error",
                title: res.data.message,
                showConfirmButton: false,
                timer: 1500,
              });
            }
            vm.downloadpdfbool = false;
          });
      } else {
        axios
          .post(`/api/viewreport`, {
            id: this.report_id,
            report_type_id: this.report_type_id,
            employee_id: this.employee_id,
            date: this.date,
          })
          .then((res) => {
            if (res.data.status != false) {
              datatosent = res.data.data;
              vm.allsubtypereportincident();
              this.destroystyle();
              this.openModal("viewreport", datatosent);
            } else {
              vm.isLoader = false;
              vm.$swal({
                icon: "error",
                title: res.data.message,
                showConfirmButton: false,
                timer: 1500,
              });
            }
          });
      }
    },
    allsubtypereportincident() {
      let vm = this;
      axios.get(`/api/allsubtypereport`).then((res) => {
        if (res.data.status != false) {
          vm.modal.allsubtypeincident = res.data.data;
        } else {
          vm.isLoader = false;
          vm.$swal({
            icon: "error",
            title: res.data.message,
            showConfirmButton: false,
            timer: 1500,
          });
        }
      });
    },
    editReport(data) {
      let vm = this;
      axios
        .post(`/api/viewreport`, {
          id: this.report_id,
          report_type_id: data.report_type_id,
          employee_id: data.employee_id,
          date: data.date,
        })
        .then((res) => {
          if (res.data.status != false) {
            this.destroystyle();
            this.openModal("editreport", res.data.data);
          } else {
            vm.isLoader = false;
            vm.$swal({
              icon: "error",
              title: res.data.message,
              showConfirmButton: false,
              timer: 1500,
            });
          }
        });
    },
    approveReportfromOutside(employeeid) {
      let vm = this;
      axios
        .post(`/api/approvereport`, { id: employeeid })
        .then((res) => {
          if (res.data.status != true) {
            vm.$swal({
              icon: "error",
              title: "in progress",
              showConfirmButton: false,
              timer: 1500,
            });
          } else {
            vm.$swal({
              icon: "success",
              title: res.data.message,
              showConfirmButton: false,
              timer: 1500,
            });
            vm.indexEmployee();
          }
        })
        .catch((err) => {
          vm.$swal({
            icon: "error",
            title: "in progress",
            showConfirmButton: false,
            timer: 1500,
          });
        });
    },
    approveReport(data) {
      let vm = this;
      let dataofreport = data;
      let idofreport = [];
      if (dataofreport.report_type_id == 2) {
        this.idofreport = dataofreport.id;
      } else {
        this.idofreport = dataofreport.map((x) => {
          return x.id;
        });
      }
      axios
        .post(`/api/approvereport`, { id: this.idofreport })
        .then((res) => {
          if (res.data.status != true) {
            vm.$swal({
              icon: "error",
              title: "in progress",
              showConfirmButton: false,
              timer: 1500,
            });
          } else {
            vm.modal.viewReportModal = false;
            vm.indexEmployee();
          }
        })
        .catch((err) => {
          vm.$swal({
            icon: "error",
            title: "in progress",
            showConfirmButton: false,
            timer: 1500,
          });
        });
    },
    downloadReports(data) {
      this.printDiv(data);
    },
    printDiv(data) {
      let vm = this;
      let typeArray = data.report;
      vm.downloadpdfdata = typeArray;
      let type;
      console.log(typeArray);
      if (typeArray[0].report_name == "Hourly") {
        this.type = "Hourly";
      } else {
        this.type = "Incident";
      }
      vm.downloadpdfbool = true;
      vm.viewReport(data);
      setTimeout(function () {
        vm.printDivreport();
      }, 1000);
      return false;
    },
    printDivreport() {
      let contents = document.getElementById("printThisHourly").innerHTML;
      console.log(contents);
      //document.body.removeChild(frame1);
      let frame1 = document.createElement("iframe");
      frame1.name = "frame1";
      frame1.style.position = "absolute";
      frame1.style.top = "-100000000px";
      document.body.appendChild(frame1);
      let frameDoc = frame1.contentWindow
        ? frame1.contentWindow
        : frame1.contentDocument.document
        ? frame1.contentDocument.document
        : frame1.contentDocument;
      frameDoc.document.open();
      frameDoc.document.write(
        '<html lang="en"><head><title>Daily Activity Report</title>'
      );
      frameDoc.document.write(
        '<link rel="stylesheet" type="text /css" href="/css/app.css"/>'
      );
      frameDoc.document.write(
        '<link rel="stylesheet" type="text /css" href="/css/dashboard.css"/>'
      );
      frameDoc.document.write("</head><body>");
      frameDoc.document.write(contents);
      frameDoc.document.write("</body></html>");
      frameDoc.document.close();
      setTimeout(function () {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        document.body.removeChild(frame1);
      }, 500);
      //   vm.downloadpdfdata = '';
      //   vm.downloadupperdata = '';
    },
    downloadReport(data) {
      let vm = this;

      this.datee = data.date;
      this.date = moment(this.datee).format("YYYY-MM-D");
      this.report_type_id = data.report_type_id;
      this.report_id = data.id;
      this.employee_id = data.employee[0].id;

      alert(this.report_type_id);
      //   vm.isLoader = true
      if (this.report_type_id == 1) {
        axios
          .post(`/api/hourlyreportdownload`, {
            id: this.report_id,
            report_type_id: this.report_type_id,
            employee_id: this.employee_id,
            date: this.date,
          })
          .then((res) => {
            if (res.data.status != false) {
              vm.isLoader = false;
            } else {
              vm.isLoader = false;
            }
          });
      } else {
        axios
          .post(`/api/incidentdownload`, {
            id: this.report_id,
            report_type_id: this.report_type_id,
            employee_id: this.employee_id,
            date: this.date,
          })
          .then((res) => {
            if (res.data.status != false) {
              vm.isLoader = false;
            } else {
              vm.isLoader = false;
            }
          });
      }
    },
    rejectReport() {
      vm.$swal({
        icon: "error",
        title: "in progress",
        showConfirmButton: false,
        timer: 1500,
      });
    },
    PrintReport(){
        this.modal.viewReportModal = false;
        document.getElementsByClassName('hidebtnwhenprint')[0].style.visibility = 'hidden';
        document.getElementsByClassName('btn-close')[0].style.visibility = 'hidden';

        var elem = document.getElementById("printThis");
        var domClone = elem.cloneNode(true);

        var $printSection = document.getElementById("printSection");

        if (!$printSection) {
            var $printSection = document.createElement("div");
            $printSection.id = "printSection";
            document.body.appendChild($printSection);
        }

        $printSection.innerHTML = "";
        $printSection.appendChild(domClone);
       

        setTimeout(() => {
          document.getElementsByClassName('hidebtnwhenprint')[0].style.visibility = 'visible';
          document.getElementsByClassName('btn-close')[0].style.visibility = 'visible';
        }, 1500);
        window.print();
        var element = document.getElementById("printSection");
        element.parentNode.removeChild(element);
        
    },
    openDateModal() {
      this.openModal("datemodal");
    },
    async openModal(modal, data) {
      let vm = this;
      switch (modal) {
        case "viewreport":
          if (data.report_type_id == 2) {
            vm.modal.viewreportmodal = data;
            //  data.time = moment(data.time).format('h:mm A')
            vm.modal.viewreportmodalreport = data;
            vm.modal.reportTypeName = 'Incident Report';
          } else {
            vm.modal.viewreportmodal = data[0];
            vm.modal.reportTypeName = 'Hourly Report';
            vm.modal.viewreportmodalreport = data;
            vm.modal.viewreportmodalreport =
              vm.modal.viewreportmodalreport.filter((x) => {
                return (x.time = x.date + " " + x.time);
              });
            vm.modal.viewreportmodalreport =
              vm.modal.viewreportmodalreport.filter((x) => {
                return (x.time = moment(x.time).format("h:mm A"));
              });
          }

          vm.modal.viewReportModal = true;
          break;
        case "editreport":
          vm.modal.viewReportModal = false;
          // vm.inject_material_fonts_and_icons_into_header();
          if (data.report_type_id == 2) {
            vm.modal.editreportmodal = data;
            vm.modal.editreportmodalreport = data;
          } else {
            vm.modal.editreportmodal = data[0];
            vm.modal.editreportmodalreport = data;
            vm.modal.editreportmodalreport =
              vm.modal.editreportmodalreport.filter((x) => {
                return (x.time = x.date + " " + x.time);
              });
            vm.modal.editreportmodalreport =
              vm.modal.editreportmodalreport.filter((x) => {
                return (x.time = moment(x.time).format("h:mm A"));
              });
          }
          // vm.$bvModal.show('editreport');
          vm.modal.editReportModal = true;
          break;
        case "datemodal":
          vm.modal.datemodal = true;
          vm.focusMyElement();
          vm.inject_material_fonts_and_icons_into_header();
          break;
      }
    },
    indexEmployee() {
      let vm = this;

      //   vm.isLoader = true
      axios.get(`/api/allreport`).then((res) => {
        if (res.data.status != false) {
          vm.reportsdata = res.data.data;
          //   vm.isLoader = false
        } else {
          //   vm.isLoader = false
          vm.$swal({
            icon: "error",
            title: res.data.message,
            showConfirmButton: false,
            timer: 1500,
          });
        }
      });
    },
    namesort() {
      let vm = this;
      if (!this.nameSort) {
        vm.reportsdata = vm.reportsdata.slice().sort(function (a, b) {
          return a.date < b.date ? 1 : -1;
        });
        this.nameSort = !this.nameSort;
      } else {
        vm.reportsdata = vm.reportsdata.slice().sort(function (a, b) {
          return a.date > b.date ? 1 : -1;
        });
        this.nameSort = !this.nameSort;
      }
    },
    close() {
      let vm = this;
      vm.inject_material_fonts_and_icons_into_header();
      vm.modal.datemodal = false;
    },
    search() {
      let vm = this;
      if (vm.searchTimer) {
        clearTimeout(vm.searchTimer);
        vm.searchTimer = null;
      }
      vm.searchTimer = setTimeout(() => {
        axios
          .post(`/api/filterreports`, {
            range_date: vm.modal.date,
            search: vm.searchKeyword,
          })
          .then((res) => {
            if (res.data.status != true) {
              vm.$swal({
                icon: "error",
                title: res.data.message,
                showConfirmButton: false,
                timer: 1500,
              });
              vm.reportsdata = [];
            } else {
              vm.reportsdata = res.data.data;
            }
          })
          .catch((err) => {
            vm.$swal({
              icon: "error",
              title: err.response.data,
              showConfirmButton: false,
              timer: 1500,
            });
          });
      }, 500);
    },

    destroystyle() {
      $(
        'link[href="https://unpkg.com/bootstrap/dist/css/bootstrap.min.css"]'
      ).remove();
      $(
        'link[href="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css"]'
      ).remove();
    },
    inject_material_fonts_and_icons_into_header() {
      [
        "https://unpkg.com/bootstrap/dist/css/bootstrap.min.css",
        "https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css",
      ].forEach((route) => {
        const link_el = document.createElement("link");

        link_el.setAttribute("rel", "stylesheet");
        link_el.setAttribute("id", "bootstrapcss");
        link_el.setAttribute("href", route);

        document.head.appendChild(link_el);
      });
    },
    removeStyles(el) {
      el.removeAttribute("style");
      el.childNodes.forEach((x) => {
        if (x.nodeType == 1) this.removeStyles(x);
      });
    },
  },
  // beforeRouteLeave (to, from, next) {
  //    this.removeStyles(document.head);
  //    next();
  //   }
};
</script>

<style lang="scss" scoped>

table td:first-child {
  background: none !important;
}
.bg-custom-primary {
  &:focus {
    outline: none;
  }
}
.bg-custom-purple-trans {
  &:focus {
    outline: none;
  }
  background: transparent;
  color: #302369;
  border: 1px solid #302369;
  font-family: "Source Sans Pro", sans-serif;
  font-size: 18px;
  padding: 10px 39px;
  font-weight: 700;
}
.perimeter-time .data:last-child span {
  display: none;
}
.officer-report-search {
  top: 302px;
  left: 269px;
  width: 171px;
  height: 41px;
  background: #ffffff 0% 0% no-repeat padding-box;
  border: 1px solid #959595;
  border-radius: 10px;
  opacity: 1;
  padding-right: 35px;
}
.officer-logs-report-table {
  font: normal normal normal 19px Source Sans Pro;
  .active.text-center {
    color: #3b86ff;
  }
  .inactive.text-center {
    color: grey;
    /* // color:#3B86FF; */
  }

  .client-table table .heading-sort svg {
    display: inline-block;
    font-size: 10px !important;
  }
}
.officer-logs-report-table {
  thead {
    tr {
      th {
        padding: 1px 0px 1px 25px;
      }
    }
  }
  tbody {
    tr {
      td.location {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
        padding: 1px 0px 1px 25px;
      }
      td {
        padding: 1px 0px 1px 25px;
        .action-button {
          margin-left: 0;
        }
      }
    }
  }
}

/* // action buttons */
.action-button {
  &:focus {
    outline: none;
  }
  top: 420px;
  left: 1490px;
  border-radius: 10px;
  opacity: 1;
  display: flex;
  color: #ffffff;
  justify-content: space-evenly;
  font: normal normal normal 19px Source Sans Pro;
  padding: 3px 5px;
  svg {
    font-size: 16px !important;
    margin: 5px 0px 5px 0px !important;
  }
}
.action-button.view {
  background: #3b86ff 0% 0% no-repeat padding-box;
  width: 80px;
  height: 33px;
}
.action-button.reject {
  background: #ffffff 0% 0% no-repeat padding-box;
  width: 80px;
  height: 33px;
  border: 1px solid #606060;
  color: #606060;
}
.action-button.edit {
  background: #c4aa33 0% 0% no-repeat padding-box;
  width: 80px;
  height: 33px;
}
.modal-view-report .action-button.edit {
  background: #c4aa33 0% 0% no-repeat padding-box;
  width: 100px;
  height: 45px;
  font-family: "Source Sans Pro", sans-serif;
  padding: 10px 20px;
  font-weight: 600;
}
.action-button.approve {
  background: #302369 0% 0% no-repeat padding-box;
  top: 732px;
  left: 1674px;
  width: 105px;
  height: 33px;
}
.modal-view-report .action-button.approve {
  background: #302369 0% 0% no-repeat padding-box;
  width: 125px;
  height: 45px;
  left: 1674px;
  font-family: "Source Sans Pro", sans-serif;
  padding: 10px 15px;
}
.action-button.download {
  background: #ffffff;
  border: none;
  color: #302369;
  font: normal normal normal 19px Source Sans Pro;
  svg {
    color: #302369;
    font-size: 25px !important;
    margin: 0px 5px 5px 0px !important;
  }
}
/* @import "../../sass/employees"; */
/* // @import '../../sass/officerreport'; */
.modal-view-report {
  table {
    tbody {
      tr {
        font: normal normal normal 16px Open Sans;
      }
    }
  }
  .upper_section {
    .section1 {
      .reporttype {
        top: 0px;
        /* // left: 0px;
        // width: 125px;
        // height: 19px; */
        text-align: left;
        font: normal normal normal 19px Montserrat;
        letter-spacing: 0px;
        color: #000000;
        font-weight: 700;
      }
      .reportypevalue {
        span {
          /* // top: 0px;
          // left: 106px;
          // width: 53px;
          // height: 19px; */
          text-align: left;
          font: normal normal normal 19px Montserrat;
          letter-spacing: 0px;
          color: #302369;
          font-weight: 500;
        }
      }
    }
  }
  .lower_section {
    .heading {
      text-align: left;
      font: normal normal normal 22px Source Sans Pro;
      letter-spacing: 0px;
      color: #2a2a2a;
      opacity: 1;
      font-weight: 500;
    }
    .card {
      min-height: 100px;
      background: #ffffff 0% 0% no-repeat padding-box;
      box-shadow: 2px 4px 20px #00000026;
      border-radius: 10px;
      opacity: 1;
      padding: 15px 15px;
      margin-bottom: 15px;
      .text {
        top: 378px;
        left: 592px;
        width: 701px;
        height: 87px;
        text-align: left;
        font: normal normal normal 20px Open Sans;
        letter-spacing: 0px;
        color: #2a2a2a;
        opacity: 1;
      }
    }
  }
}
.modal-edit-report {
  table {
    tbody {
      tr {
        font: normal normal normal 16px Open Sans;
        height: auto;
      }
    }
    .text {
      background: #ffffff 0% 0% no-repeat padding-box;
      box-shadow: 2px 4px 20px #00000026;
      border-radius: 10px;
      opacity: 1;
      /* // padding: 15px 8px;
      // margin-bottom: 15px; */
      margin: 15px 15px 15px 0px;
      width: 90%;
      min-height: 90px;
      border: none;
      overflow: hidden;
      padding-left: 5px;
      &:focus {
        outline: none;
      }
    }
  }
  .upper_section {
    .heading {
      text-align: left;
      font: normal normal normal 22px Source Sans Pro;
      letter-spacing: 0px;
      color: #2a2a2a;
      opacity: 1;
      font-weight: 500;
    }
  }
  .lower_section {
    .heading {
      text-align: left;
      font: normal normal normal 22px Source Sans Pro;
      letter-spacing: 0px;
      color: #2a2a2a;
      opacity: 1;
      font-weight: 500;
    }
    .card {
      .text {
        background: #ffffff 0% 0% no-repeat padding-box;
        box-shadow: 2px 4px 20px #00000026;
        border-radius: 10px;
        opacity: 1;
        padding: 15px 15px;
        margin-bottom: 15px;
        margin: 15px 0px;
        width: 100%;
        min-height: 115px;
      }
    }
  }
}
@media screen {
    #printSection {
        display: none;
    }
}
@media print {
    body * {
        visibility:hidden;

    }
    table {
        border-collapse: inherit;
    }
    .hidebtnwhenprint{
    display:none;
    opacity:0;
    visibility:hidden;
    }
    #printSection, #printSection * {
        visibility:visible;
    }
    #printSection {
        left:0;
        top:0;
    }
}

</style>
