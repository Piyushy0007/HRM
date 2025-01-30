<template>
  <div class="c-employee-index">
    <Loader msg="Processing ..." v-model="isLoader" />
    <div class="p-2 max-w-7xl mx-auto h-screen flex flex-col " style=" margin-left: 240px;">
      <div class="flex justify-between items-center p-2 mb-5 ">
        <p class="text-3xl">Properties</p>
        <button class="add-blue-button"  @click="create('create')" >Create new property</button>
      </div>
      <div class="flex flex-col p-2 mb-4">
        <div class="flex flex-col md:flex-row w-full gap-2 items-stretch ">
          <div class="w-full md:w-1/2 flex flex-col md:flex-row mb-4">
            <div class="w-full md:w-1/2 pr-3 mb-4">
              <div class="relative">
                <select  id="location" @change="selectLocation()" class="w-full border border-gray-300 rounded px-2 py-2 focus:outline-none">
                  <option value="">All Locations</option>
                  <option v-for="loc,index in alllocation" :key="index+loc" >{{loc}}</option>
                </select>
              </div>
            </div>
            <div class="w-full md:w-1/2 pr-3 mb-4">
              <div class="relative">
                <select id="status" @change="selectStatus()"  class="w-full border border-gray-300 rounded px-2 py-2 focus:outline-none">
                  <option value="">All Status</option>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
            </div>
          </div>
          <div class="w-full md:w-1/2">
            <div class="flex items-center flex-grow relative" >
              <input class="w-full rounded pl-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" 
                style="height: 38px; border: 1px solid #d1d5db;"
                type="text" 
                placeholder="Search" 
                v-model="searchKeyword" @keyup="search"
              >
              <div>
                <font-awesome-icon
                  icon="search"
                  class="absolute right-0 mr-2 transform -translate-y-1/2 top-1/2 cursor-pointer text-gray-500"
                  style="font-size: 20px"
                />
              </div>
            </div>
          </div>
        </div>
        <table class="table-auto w-full border-collapse border border-custom-border">
          <thead class="thead-light bg-custom-bg_table_head_primary border-custom-border">
            <tr class="text-left border-b thead-light bg-custom-bg_table_head_primary border-custom-border">
              <th width="15%" class="text-left p-2 border border-custom-border heading-sort">
                <div class="flex items-center gap-1">
                  Property Name <b-icon-arrow-down-up @click="namesort()" />
                </div>
              </th>
              <th class="text-left p-2 border border-custom-border">Location</th>
              <th class="text-left p-2 border border-custom-border">Community Name</th>
              <th class="text-left p-2 border border-custom-border heading-sort">Email </th>
              <th class="text-left p-2 border border-custom-border">Contact Number</th>
              <!-- <th class="text-center">Username</th> -->
              <th class="text-left p-2 border border-custom-border">Date Registered</th>
              <th class="text-left p-2 border border-custom-border">Status</th>
              <th class="text-center p-2 border border-custom-border" colspan="2">Action</th>
              <!-- <th width="5%" class="text-left"></th> -->
            </tr>
          </thead>
        <tbody >
          <template v-if="propertiesdata.length != 0">
            <tr v-for="(data) in propertiesdata" :key="data.id">
              <td class="text-left p-2 border border-custom-border text-transform-capitalise">{{ data.name || '-'}}  </td>
              <td class="text-left p-2 border border-custom-border location">{{data.address ? data.address : '-'}}</td>
              <td class="text-left p-2 border border-custom-border text-transform-capitalise">{{ data.client ? data.client.clientname : '-'}} </td>
              <td class="text-left p-2 border border-custom-border">{{ data.client ? data.client.email : '' }}</td>
              <td class="text-left p-2 border border-custom-border">{{ data.client ? data.client.phone : '-' }}</td>
              <!-- <td class="text-center">{{ data.name }}</td> -->
              <td class="text-left p-2 border border-custom-border" v-if="data.created_at">{{data.created_at  | moment('MM/DD/YYYY') }}</td>
              <td class="text-left p-2 border border-custom-border" v-else>-</td>
              <td :class="data.status == 'active' ? 'active text-center p-2 border border-custom-border' : 'inactive text-center p-2 border border-custom-border'" >{{ data.status ? data.status : '-' }}</td>
              <td class="text-left p-2 border-l border-b border-custom-border"   @click="create('edit', data)" ><b-button variant="success"> <font-awesome-icon icon="pencil-alt"  class="text-gray-500 font-size-22"  /></b-button></td>
              <td class="text-left p-2 border-r border-b border-custom-border" @click='deleteView(data)' ><b-button variant="success"> <font-awesome-icon :icon="['far', 'trash-alt']" class="text-gray-500 font-size-22" /></b-button></td>
            </tr>
          </template>
          <template v-else>
            <tr> 
              <td colspan="9" class="text-center p-4">No Record Found</td> 
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>
  <modal v-model="modal.geoLoc1" size="md:w-7/12" :title="updateprop ? 'Update Property' : 'Create Property'">
    <ValidationObserver v-slot="{ handleSubmit }">
      <form @submit.prevent="handleSubmit(propertyModel1())" ref="frmpropertymodel1" novalidate>
    		<div class=" px-4 mb-4 mt-4">
          <!-- <template v-if="updateprop">
               <div class="flex" style="display:flex;">
            <ValidationProvider rules="required" v-slot="v" class=" md:w-6/12 m-3" style="display:block;">
      				<div class=" md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Property Name<span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-4/4">
        					<input class="appearance-none block w-full rounded-10 height-41 py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.geoloc.propertyname">
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>
             <ValidationProvider rules="required" v-slot="v"  class=" m-3" style="display:block; width:47%;">
      				<div class=" md:items-center">
      					<div class="md:w-4/4">
      						<label class="block mb-1 md:mb-0 pr-4">Property Address<span class="req_form_fields">*</span></label>
      					</div>
      					<div class="md:w-4/4">
        					<input  id="pac-input" class="appearance-none block w-full rounded-10 height-41 py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" >
      					</div>
      				</div>
              <div class="md:flex md:items-center mb-1">
                <div class="md:w-3/4">
                  <small class="text-red-600">{{ v.errors[0] }}</small>
                </div>
              </div>
            </ValidationProvider>
             
            </div>
            </template> -->
          <template>
            <div class="flex" style="display:flex;">
              <template v-if="!updateprop">
                <ValidationProvider rules="required" v-slot="v" class=" md:w-6/12 m-3" style="display:block;">
                  <div class=" md:items-center">
                    <div class="md:w-4/4">
                      <label class="block mb-1 md:mb-0 pr-4">Community<span class="req_form_fields">*</span></label>
                    </div>
                    <div class="md:w-4/4">
                      <select style="appearance:menulist;" class="block appearance-none w-full py-1 px-4 pr-8 rounded-10 height-41 leading-tight focus:outline-none" v-model="modal.geoloc.clientid" @change="clientselected">
                        <option value="">Select Community</option>  
                        <option :value="data.id" v-for="data in clientlist" :key="data.id">{{ data.clientname }}</option>
                      </select>        					
                    </div>
                  </div>
                  <div class="md:flex md:items-center mb-1">
                    <div class="md:w-3/4">
                      <small class="text-red-600">{{ v.errors[0] }}</small>
                    </div>
                  </div>
                </ValidationProvider>
              </template>
              <ValidationProvider rules="required" v-slot="v" class=" md:w-6/12 m-3" style="display:block;">
                <div class=" md:items-center">
                  <div class="md:w-4/4">
                    <label class="block mb-1 md:mb-0 pr-4">Property Name<span class="req_form_fields">*</span></label>
                  </div>
                  <div class="md:w-4/4">
                    <input class="appearance-none block w-full rounded-10 height-41 py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.geoloc.propertyname">
                  </div>
                </div>
                <div class="md:flex md:items-center mb-1">
                  <div class="md:w-3/4">
                    <small class="text-red-600">{{ v.errors[0] }}</small>
                  </div>
                </div>
              </ValidationProvider>
              <ValidationProvider rules="required" v-slot="v"  class=" m-3" style="display:block; width:47%;">
                <div class=" md:items-center">
                  <div class="md:w-4/4">
                    <label class="block mb-1 md:mb-0 pr-4">Property Address<span class="req_form_fields">*</span></label>
                  </div>
                  <div class="md:w-4/4">
                    <input id="pac-input" class="appearance-none block w-full rounded-10 height-41 py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" >
                  </div>
                </div>
                <div class="md:flex md:items-center mb-1">
                  <div class="md:w-3/4">
                    <small class="text-red-600">{{ v.errors[0] }}</small>
                  </div>
                </div>
              </ValidationProvider>
            </div>
          </template>
          <div class="d-flex">
            <div id="map"></div>
            <b-button v-if="resettrue"
              class="m-3 text-center"
              style="border: 1px solid black"
              @click="resetCoordinates()">
              Reset
            </b-button>
          </div>
    		</div>
    		<!-- ================================= ./Contact ================================= -->
        <div class="text-center mt-2 mb-2">
          <span  v-if="resettrue" class="text-white py-3 px-12 rounded-full  bg-custom-primary" @click="close()">
            <span>Cancel</span>
          </span>
          <button class="text-white py-3 px-12 rounded-full  bg-custom-primary" type="submit">Save</button>
        </div>
      </form>
    </ValidationObserver>
  </modal>
  </div>
</template>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.rawgit.com/bjornharrtell/jsts/gh-pages/1.4.0/jsts.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfX422PdA9Yy6GOf4HeRBRTtfoz-AGQdU&callback=initMap&libraries=drawing,places&v=weekly"></script>
<script src="https://maps.google.com/maps/api/js?libraries=places,drawing&key=AIzaSyDfX422PdA9Yy6GOf4HeRBRTtfoz-AGQdU"></script>
<script src="https://cdn.rawgit.com/bjornharrtell/jsts/gh-pages/1.4.0/jsts.min.js"></script>
<script>
import Modal from './shared/Modal'
import Loader from './shared/Loader'
import axios from 'axios'
import moment from 'moment'
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import draggable from 'vuedraggable'
// import { BModal, VBModal } from 'bootstrap-vue'
import { BButton } from 'bootstrap-vue'
  const removeAllSelections = () => {
    window.getSelection().removeAllRanges()
  }
  const selectContent = element => {
    let range = document.createRange()
    range.selectNode(element)
    window.getSelection().addRange(range)
    var txt = window.getSelection().getRangeAt(0).toString();
    document.execCommand('copy')
    removeAllSelections();
  }
  const copySelection = () => {
    setTimeout(() => {
      document.execCommand('copy')
      removeAllSelections()
    })
  }
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
       nameSort: false,
      emailSort: false,
      triangleCoords:[],
       resettrue: false,
      updateprop: false,
      clientlist:[],
      geoLoc: false,
      propertiesdata:[],
			requestedHeaders: {
				headers: {}
			},
      isLoader: false,

      searchKeyword: '',
      searchTimer: null,
      // select employee
       checkedNames: [],
       bulkids: [],
      // tabs
      activeItem: 'edit',
      coordinated_data: [],
			modal: {
      geoLoc1: false,
      geoloc:{
          clientid: '',
          propertyname: '',
          propertyaddress: '',
          coordinates: '',
          lat: 30.709579 ,
          long:76.689343 ,
        },
        SendMessageToAll:{
        id: [],
        subject: '',
        message: ''
        },
        SignInSuccess : [],
				addNewEmployee: false,
        editEmployee: false,
        addEditPositions: false,
        //sign in ins, bulk edit modals
        SignIn: false,
        BulkEditEmp: false,
        SignInSent: false,
        // send reminder
        SendReminders: false,
        SendReminderSuccess: false,
        SendMessage: false,

        editDeletePositions: false,

        showEditPosition: false,
        reqEditPositionDisplay: '',
        reqEditPosition: '',

        // This is intended for prev/next fn inside EDIT employee
        getEmployeeRecord: {
          prev: {
            show: false,
            data: {},
          },
          next: {
            show: false,
            data: {},
          },
        },

        fullname: '',

        addEmployee: {
          SendEmail : true
        },
        reqEditEmployee: {},
        reqEditEmployeeBulk: {},
        reqEditEmployeeBulk: {
          max_weekly_hours: 40,
          max_weekly_days: 7,
          max_day_hours: 14,
          max_day_shifts: 1
        },


        positions: [],
        trashedPositions: {},
        addPosition: {},

        // Add employee
        selectedPositions: [],
        // This is un/ticking checkbox
        checkBoxOption: {
          selectAll: false,
          unSelectAll: false
        },
      },
       selected : [],
       allSelected: false,
      options: {
				chooseDateRange: '',
				beginDate: '',
        endDate: '',
        id: [],
        subject: '',
        comment: ''
      },
      SendReminderSuccessData:[],

      index: {
        employees: {},
        positions: {},
        selectPosition: '',
      },
        alllocation:[],
		}
	},
  computed: {
    TotalProperties() {
      return this.propertiesdata
    },
    modalEmployeeName() {
      return this.modal.reqEditEmployee.firstname + ' ' + this.modal.reqEditEmployee.lastname
    },
    // ids of selected employees
    BulkIDS(){
      let ids= [];
      this.bulkids = [];
       for (let i = 0; i <= this.checkedNames.length - 1; i++) {
            {
              this.bulkids[i] = this.checkedNames[i].employee_id
            }
        }
        ids = this.bulkids; 
        return Object.values(ids)
    }
  }, 
  async created() {
    let vm = this
    await vm.indexEmployee()
    await vm.indexPosition('index-position')
     await vm.allLocationFetch()
  },
  watch :{
    TotalProperties:{
      handler() {
        console.log(this.propertiesdata, 'propertiesdatawatch')
      },
       deep: true,
      immediate: true
    },
    close: {
       handler(){
      let vm = this;
      this.resettrue = false;
      this.updateprop = false;
      // this.$refs.frmpropertymodel1.reset();
      vm.modal.geoloc.propertyname = '' ,
          vm.modal.geoloc.propertyaddress = ''
          vm.modal.geoloc.coordinates = ''
        vm.modal.geoLoc1 = false;
       },
       deep: true,
      immediate: true,
    }
  },
	methods: {
    allLocationFetch(){
      let vm = this
        axios
        .get('/api/propertielocation')
        .then(res => {
          if(res.data.status){
            vm.alllocation = res.data.data
          }
          else{
             vm.alllocation =[]
          }
        })
        .catch(err => {
         console.log(err.response.data)
        })
    },
    clientselected(){
     console.log('id')
    },
    fetchclientlist(){
      let vm = this;
      axios
        .get(`/api/client`)
        .then(res => {
          vm.clientlist = res.data
        })      
    },
    create(value, data){
    let vm = this;
    if(value == 'create'){
       this.resettrue = false;
       this.updateprop = false;
      vm.modal.geoloc.propertyname = '' 
      vm.modal.geoloc.propertyaddress = ''
      vm.modal.geoloc.coordinates = ''
      vm.modal.geoloc.clientid = ''
      vm.fetchclientlist();
      vm.modal.geoLoc1 = true;
      vm.isLoader = false;
      vm.geoLocAdd();
      vm.isLoader = false;
    }
    else{
      vm.modal.geoloc.coordinates = ''
      this.resettrue = true;
      vm.modal.geoloc.propertyname = '' 
      vm.modal.geoloc.propertyaddress = ''
       this.updateprop = true;
       vm.coordinated_data = [];
        if(data.id){
           vm.modal.geoloc.clientid = data.client_id;
              vm.modal.geoloc.propertyid = data.id;
          }
          if(data.address){
            vm.modal.geoloc.propertyaddress = data.address;
            vm.modal.geoloc.propertyname = data.name;
            vm.modal.geoloc.coordinates = data.coordinates;
          }
          if(data.lat ){
            vm.modal.geoloc.lat = data.lat;
          }
          if(data.long ){
            vm.modal.geoloc.long = data.long;
          }
          vm.modal.geoLoc1 = true;
          vm.geoLocAdd();
    }
   
    },
     geoLocAdd() {
       
      let vm = this;

      var mapOptions = {
        zoom: 16,
        center: new google.maps.LatLng(vm.modal.geoloc.lat, vm.modal.geoloc.long),
      };
      if (
        vm.modal.geoloc.coordinates != "" ||
        vm.modal.geoloc.coordinates == null
      ) {
        vm.resettrue = true;
         vm.triangleCoords = [];
        JSON.parse( vm.modal.geoloc.coordinates).map((item) => {
          item.map((elem) => {
            vm.triangleCoords.push({ lat: elem.x, lng: elem.y });
          });
        });
      }else{
          vm.resettrue = false;
        var drawingManager = new google.maps.drawing.DrawingManager({
          drawingControl: false,
          polygonOptions: {
            editable: true,
          },
        });

        var googleMaps2JTS = function (boundaries) {
          var coordinates = [];
           vm.coordinated_data = [];
          for (var i = 0; i < boundaries.getLength(); i++) {
            coordinates.push(
              new jsts.geom.Coordinate(
                boundaries.getAt(i).lat(),
                boundaries.getAt(i).lng()
              )
            );
          }
          
          coordinates.push(coordinates[0]);
          vm.coordinated_data.push(coordinates);
          // vm.properties.property.coordinates = coordinates;
          vm.modal.geoloc.coordinates = vm.coordinated_data;
          return coordinates;
        };
      }
      /**
       * findSelfIntersects
       *
       * Detect self-intersections in a polygon.
       *
       * @param {object} google.maps.Polygon path co-ordinates.
       * @return {array} array of points of intersections.
       */
      var findSelfIntersects = function (googlePolygonPath) {
        var coordinates = googleMaps2JTS(googlePolygonPath);
        var geometryFactory = new jsts.geom.GeometryFactory();
        var shell = geometryFactory.createLinearRing(coordinates);
        var jstsPolygon = geometryFactory.createPolygon(shell);

        // if the geometry is aleady a simple linear ring, do not
        // try to find self intersection points.
        var validator = new jsts.operation.IsSimpleOp(jstsPolygon);
        if (validator.isSimpleLinearGeometry(jstsPolygon)) {
          return;
        }

        var res = [];
        var graph = new jsts.geomgraph.GeometryGraph(0, jstsPolygon);
        var cat = new jsts.operation.valid.ConsistentAreaTester(graph);
        var r = cat.isNodeConsistentArea();
        if (!r) {
          var pt = cat.getInvalidPoint();
          res.push([pt.x, pt.y]);
        }
        return res;
      };
      var divmap = document.getElementById("map");
      var map = new google.maps.Map( divmap , mapOptions);


      vm.input = document.getElementById("pac-input");
      document.getElementById("pac-input").value = vm.modal.geoloc.propertyaddress;
      document.getElementById("pac-input").placeholder = "";
      vm.searchBox = new google.maps.places.SearchBox(vm.input);
        // Create the search box and link it to the UI element.

  // map.controls[google.maps.ControlPosition.TOP_LEFT].push(vm.input);
  // Bias the SearchBox results towards current map's viewport.
  map.addListener("bounds_changed", () => {
    vm.searchBox.setBounds(map.getBounds());
  });
  let markers = [];
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  vm.searchBox.addListener("places_changed", () => {
    const places = vm.searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }
    // Clear out the old markers.
    markers.forEach((marker) => {
      marker.setMap(null);
    });
    markers = [];
    // For each place, get the icon, name and location.
    const bounds = new google.maps.LatLngBounds();
    places.forEach((place) => {
      if (!place.geometry || !place.geometry.location) {
        console.log("Returned place contains no geometry");
        return;
      }
      const icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25),
      };
      // Create a marker for each place.
      markers.push(
        new google.maps.Marker({
          map,
          icon,
          title: place.name,
          position: place.geometry.location,
        })
      );
 
      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
          //  vm.modal.geoloc.long = place.geometry.viewport.La.i;
          //  vm.modal.geoloc.lat = place.geometry.viewport.Ta.i;
          let viewport1 = place.geometry.viewport;
           Object.entries(viewport1).forEach(([key, value], index) => {
            console.log('Dev start')
            console.log( 'place.geometry.value' ,  value )
            console.log( 'place.geometry.index' ,  index )
            console.log('Dev end')
           if(index == 0){
             vm.modal.geoloc.lat = value.hi;
           }
           if(index == 1){
             vm.modal.geoloc.long = value.hi;
           }
          });
            vm.modal.geoloc.coordinates = '';
            vm.coordinated_data = []; 

            vm.modal.geoloc.propertyaddress = document.getElementById('pac-input').value; 
           vm.geoLocAdd();
      } else {
        bounds.extend(place.geometry.location);
      }
      if(place.geometry.location){
        console.log( 'place.geometry.location' ,  vm.modal.geoloc.long )
          console.log( vm.modal.geoloc.lat ,  vm.modal.geoloc.long )
      }
    });
    map.fitBounds(bounds);
  });

  // --------------- end search places ---------------------------------//
          if (
        vm.modal.geoloc.coordinates != "" ||
        vm.modal.geoloc.coordinates == null
      ) {
        vm.bermudaTriangle = new google.maps.Polygon({
          paths: vm.triangleCoords,
          strokeColor: "#020202cf",
          strokeOpacity: 0.5,
          strokeWeight: 3,
          fillColor: "#020202cf",
          fillOpacity: 0.75,
        });
        vm.bermudaTriangle.setMap(map);
      }
      else{

      
        drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
        drawingManager.setMap(map);
        google.maps.event.addListener(
          drawingManager,
          "polygoncomplete",
          function (polygon) {
            //var polyPath = event.overlay.getPath();
            var intersects = findSelfIntersects(polygon.getPath());
            if (intersects && intersects.length) {
              console.log("Done");
            } else {
               console.log("Done");
            }
          }
        );
      }
    },
        resetCoordinates() {
      let vm = this;
      vm.bermudaTriangle.setMap(null);

      vm.lat = vm.modal.geoloc.lat;
      vm.long = vm.modal.geoloc.long;
      vm.address1 = vm.modal.geoloc.address;
      vm.modal.geoloc.coordinates = '';
      vm.geoLocAdd();
    },
      propertyModel1() {
      let vm = this;
      vm.isLoader = false
          var nameArr , country, city , combine;
          if(/[,\-]/.test(vm.modal.geoloc.propertyaddress)){
            nameArr = vm.modal.geoloc.propertyaddress.split(',');
            country = nameArr[nameArr.length - 1]
            city = nameArr[nameArr.length - 2]
            combine = city +',' +country
          }
          else{
            combine = vm.modal.geoloc.propertyaddress
          }
         vm.modal.geoloc.state = combine;


       if(this.updateprop){
          axios
        .post(`/api/updateproperties/${vm.modal.geoloc.propertyid}`, {
          lat:vm.modal.geoloc.lat,
          long:vm.modal.geoloc.long,
          address: vm.modal.geoloc.propertyaddress,
          state: vm.modal.geoloc.state,
          coordinates:vm.modal.geoloc.coordinates,
          name:vm.modal.geoloc.propertyname,
        })
        .then((res) => {
          vm.$swal({
            icon: "success",
            title: "Successfully added!",
            showConfirmButton: false,
            timer: 2000,
          });
          this.modal.geoLoc1 = false;
           vm.isLoader = false
           this.updateprop = false;
            // vm.$refs.frmpropertymodel1.reset();
             vm.modal.geoloc.propertyname = '' ,
          vm.modal.geoloc.propertyaddress = ''
          vm.modal.geoloc.coordinates = ''
          vm.modal.geoloc.state = ''
           vm.indexEmployee()

       
        })
        .catch((err) => {
          vm.$swal({
            icon: "error",
            title: err.response.data,
            showConfirmButton: false,
            timer: 1500,
          });
          this.updateprop = false
        });
         vm.isLoader = false
         vm.indexEmployee();
       }
       else{
          if((vm.modal.geoloc.client_id != '' || vm.modal.geoloc.client_id != undefined ) || vm.modal.geoloc.propertyname != '' || vm.modal.geoloc.propertyaddress != '' ||  vm.modal.geoloc.lat != '' ||  vm.modal.geoloc.long != ''  || vm.modal.geoloc.coordinates != '' ){
        axios
        .post('/api/adminaddproperty',{
          client_id : vm.modal.geoloc.clientid,
          address : vm.modal.geoloc.propertyaddress,
          state : vm.modal.geoloc.state,
          name : vm.modal.geoloc.propertyname,
          lat : (vm.modal.geoloc.lat).toString(),
          long : (vm.modal.geoloc.long).toString(),
          coordinates : vm.modal.geoloc.coordinates,
        })
        .then(res => {
          if(res.data.status == true){
               vm.$swal({
            icon: 'success',
            title: 'Successfully Updated!',
            showConfirmButton: false,
            timer: 1500
          })
          vm.modal.geoLoc1 = false;
          // vm.$refs.frmpropertymodel1.reset();
          vm.modal.geoloc.propertyname = '' ,
          vm.modal.geoloc.propertyaddress = ''
          vm.modal.geoloc.state = ''
          vm.modal.geoloc.coordinates = ''
           vm.isLoader = false
             vm.indexEmployee()
          }
          else{
            vm.$swal({
            icon: 'error',
            title: res.data.message,
            showConfirmButton: false,
            timer: 1500
          })
          vm.isLoader = false
          }
         
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 1500
          })
          vm.isLoader = false
        })
      }else{
        vm.$swal({
            icon: 'error',
            title: 'Fill Required fields',
            showConfirmButton: false,
            timer: 1500
          })
           vm.isLoader = false
      }
       }

     

    },
        selectLocation() {
    var x = document.getElementById("location").value;
    var y = document.getElementById("status").value;
    let vm = this;
        // axios.post(`/api/propertieslocation`, {search: x}) 
          axios.post(`/api/filterproperty`, {location: x, status: y ,search: vm.searchKeyword}) 
          .then(res => {
            if(res.data.status != true){
               vm.$swal({
              icon: 'error',
              title: res.data.message,
              showConfirmButton: false,
              timer: 1500
            })
             vm.propertiesdata = [];
         
            }
            else{
            vm.propertiesdata  = res.data.data
          
            }
           
          })
          .catch(err => {
            vm.$swal({
              icon: 'error',
              title: err.response.data,
              showConfirmButton: false,
              timer: 1500
            })
          
          })
      

  },
    selectStatus() {
     var x = document.getElementById("location").value;
    var y = document.getElementById("status").value;
    let vm = this;  
  
    // axios.post(`/api/propertiesstatus`, {status:x}).then(res => {
      axios.post(`/api/filterproperty`, {location: x, status: y ,search: vm.searchKeyword}) 
      .then(res => {
       if(res.data.status!= true){
          vm.propertiesdata = [];
       }
       else{
          vm.$swal({
            closeOnClickOutside: false,
            icon: 'success',
            title: res.data.message,
            showConfirmButton: false,
            timer: 5000,
          })
       
          vm.propertiesdata = res.data.data
       }

        
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 3000
          })
       
        })
    

    

  },
      /**
     * Search for 
     * @return {[type]} [description]
     */
    search() {
      let vm = this
      var x = document.getElementById("location").value;
      var y = document.getElementById("status").value;
      // if (vm.searchKeyword == " ") {
      //  vm.indexEmployee();
      // }
      if (vm.searchTimer ) {
        clearTimeout(vm.searchTimer)
        vm.searchTimer = null
      }
      vm.searchTimer = setTimeout(() => {
      
        // axios.post(`/api/propertysearch`, {search: vm.searchKeyword}) 
         axios.post(`/api/filterproperty`, {location: x, status: y ,search: vm.searchKeyword}) 
          .then(res => {
            if(res.data.status != true){
               vm.$swal({
              icon: 'error',
              title: res.data.message,
              showConfirmButton: false,
              timer: 1500
            })
             vm.propertiesdata = [];
           
            }
            else{
            vm.$swal({
            closeOnClickOutside: false,
            icon: 'success',
            title: res.data.message,
            showConfirmButton: false,
            timer: 5000,
          })
            vm.propertiesdata = res.data.data
         
            }
           
          })
          .catch(err => {
            vm.$swal({
              icon: 'error',
              title: err.response.data,
              showConfirmButton: false,
              timer: 1500
            })
       
          })
      }, 500)
    },
    addclient(){
      this.$router.push('/client/clientadd')
    },
    async deleteView(data){
      let vm = this;
       try {
    if (confirm(`Are you sure you want to cancel this property :  ${data.name} - ${data.address}?  `)) {
     axios.get(`/api/propertydelete/${data.id}`).then(res => {
          vm.$swal({
            closeOnClickOutside: false,
            icon: 'success',
            title: res.data.message,
            showConfirmButton: false,
            timer: 5000,
          })
          vm.isLoader = false
          vm.indexEmployee();
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
        })
      }
      } catch (e) {
        console.log('error', e)
      }

    },
    chooseDateRange() {
			let vm = this
			switch( vm.options.chooseDateRange ) {
				case 'today':
					vm.options.beginDate = moment().format('MM/DD/YYYY')
					vm.options.endDate = moment().format('MM/DD/YYYY')
					break
				case 'tomorrow':
					vm.options.beginDate = moment().add(1, 'day').format('MM/DD/YYYY')
					vm.options.endDate = moment().add(1, 'day').format('MM/DD/YYYY')
					break
				case 'this-week':
					vm.options.beginDate = moment().startOf('isoWeek').format('MM/DD/YYYY')
					vm.options.endDate = moment().endOf('isoWeek').format('MM/DD/YYYY')
					break
				case 'last-week':
					vm.options.beginDate = moment().subtract(1, 'week').startOf('isoWeek').format('MM/DD/YYYY')
					vm.options.endDate = moment().subtract(1, 'week').endOf('isoWeek').format('MM/DD/YYYY')
					break
				case 'next-week':
					vm.options.beginDate = moment().add(1, 'week').startOf('isoWeek').format('MM/DD/YYYY')
					vm.options.endDate = moment().add(1, 'week').endOf('isoWeek').format('MM/DD/YYYY')
					break
				case 'this-month':
					vm.options.beginDate = moment().startOf('month').format('MM/DD/YYYY')
					vm.options.endDate = moment().endOf('month').format('MM/DD/YYYY')
					break
				case 'last-month':
					vm.options.beginDate = moment().subtract(1, 'month').startOf('month').format('MM/DD/YYYY')
					vm.options.endDate = moment().subtract(1, 'month').endOf('month').format('MM/DD/YYYY')
					break
				case 'next-month':
					vm.options.beginDate = moment().add(1, 'month').startOf('month').format('MM/DD/YYYY')
					vm.options.endDate = moment().add(1, 'month').endOf('month').format('MM/DD/YYYY')
					break
				case 'this-quarter':
				case 'last-quarter':
				case 'this-year':
				case 'year-to-date':
				case 'after-today':
				case 'before-today':
				case 'all-dates':
					// vm.options.beginDate = moment().quarter().format('MM/DD/YYYY')
					// vm.options.endDate = moment().quarter().format('MM/DD/YYYY')
					alert('still in progress')
				default:
					vm.options.beginDate = ''
					vm.options.endDate = ''
			}
		},
    // select all checkbox
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
    // close modal
    close() {
     let vm = this;
      this.resettrue = false;
       this.updateprop = false;
      vm.modal.geoloc.propertyname = '' 
      vm.modal.geoloc.propertyaddress = ''
      vm.modal.geoloc.coordinates = ''
      this.modal.geoLoc1 = false;
    },
    
    // Select  check message
    SendMessageCheck(func){
      let arr = Object.keys(this.checkedNames).map((k) => this.checkedNames[k])
      if(this.checkedNames.length != 0 && func == 'SendMessage'){
        this.modal.SendMessage = true;
      }
      else if(this.checkedNames.length != 0 && func == 'SendReminders'){
        this.modal.SendReminders = true;
      }
      else if(this.checkedNames.length != 0 && func == 'SendSignIn'){
        this.SendSignIn(this.checkedNames);
      }
      else if(this.checkedNames.length != 0 && func == 'ExportData'){
        this.ExportData(this.checkedNames);
      }
      else if(this.checkedNames.length != 0 && func == 'BulkEdit'){
       if(this.checkedNames.length == 1){
          this.openModal('EditEmployee', this.checkedNames[0]);
       }
       else{
         this.openModal('BulkEditEmp');
       }
      }
      else if(this.checkedNames.length == 0 && (func == 'ExportData' || func == 'BulkEdit' )){
        this.$alert('Please Select at least one row' );
      }
      else{
        this.$alert('First click the checkboxes to the left of each name to select Watchers.' );
      }
    },
    // Export data
    ExportData(names){
      let vm = this;
      vm.copyGroupsToClipboard()
    },
    copyGroupsToClipboard() {
        removeAllSelections()
        selectContent(this.$refs.content)
        // copySelection()
         this.$alert('Selected Watchers copied to the clipboard so you can paste in another software system.');
      },
      // Send Message 
    SendMessageToAll(){
        let vm = this
        
        vm.isLoader = false
        vm.modal.SendMessageToAll.id = vm.bulkids
        axios
        .post('/api/employees/send-message', Object.assign(vm.modal.SendMessageToAll))
        .then(res => {
          vm.option = {}
          vm.$swal({
            closeOnClickOutside: false,
            icon: 'success',
            title: `Message Sent `,
            showConfirmButton: false,
            timer: 5000,
          })
          vm.modal.SendMessage = false
          vm.isLoader = false
          vm.$refs.sendmessage.reset();
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 3000
          })
          vm.isLoader = false
        })
    },
    //Send Reminder
    SendReminderToAll(){
        let vm = this
        
        vm.isLoader = false
        vm.options.id = vm.bulkids
        axios
        .post('/api/employees/send-reminder', Object.assign(vm.options))
        .then(res => {
          vm.option = {}
          vm.SendReminderSuccessData = res.data
          this.modal.SendReminderSuccess = true;
          vm.modal.SendReminders = false
          vm.isLoader = false
          vm.$refs.frmSendReminder.reset();
        })
    },
    DoMessage(){
       let vm = this
      vm.modal.SendMessage = false;
    },
    // Sign In instructions
    sentIns(){
      let vm = this
      vm.modal.SignIn = false;
      vm.modal.SignInSent = true;
      vm.$confirm.hide();
      },
    SendSignIn(names){
        let vm = this;
        let namelength = names.length

        let ids = [];
         for (let i = 0; i <= names.length - 1; i++) {
            {
              ids[i] = names[i].employee_id
            }
        }
        vm.$confirm(
        {
          message: `Are you sure you want to send sign in instructions to ${namelength} Watchers `,
          button: {
            no: 'No',
            yes: 'Yes'
          },
          /**
          * Callback Function
          * @param {Boolean} confirm
          */
          callback: confirm => {
            if (confirm) {
                   vm.sendEmailToSelectedEmp(ids);
            }
          }
        }
      )
      },
    showMsgBoxThree(){
       let vm = this;
        vm.modal.SignIn = false;
        vm.modal.SignInSent = false;
      },
    showMsgBoxOne() {
       
          let vm = this;
          vm.$confirm(
        {
          message: `Send individual sign in instructions to all Watchers who have email addresses entered but who have not already signed in.`,
          button: {
            no: 'No',
            yes: 'Yes'
          },
          /**
          * Callback Function
          * @param {Boolean} confirm 
          */
          callback: confirm => {
            if (confirm) {
               vm.modal.SignInSent = false;
                vm.modal.SignIn = false;
                vm.sendEmailToAll();
            }
          }
        }
      )
           
    },
    isNumberOnly(evt) {
      evt = (evt) ? evt : window.event
      let charCode = (evt.which) ? evt.which : evt.keyCode
      // if decimal point (.) is allowed, set this to charCode !== 46
      // otherwise, set this to charCode !== 46
      // if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode == 46) {
      if ((charCode > 31 && (charCode < 48 || charCode > 57)) || charCode == 46 || charCode == 9 || charCode == 16) {
        evt.preventDefault()
      } else {
        return true
      }
    },
    isNumberOnlyAndDecimalPoint(evt) {
      evt = (evt) ? evt : window.event
      let charCode = (evt.which) ? evt.which : evt.keyCode
      // if decimal point (.) is allowed, set this to charCode !== 46
      // otherwise, set this to charCode !== 46
      if ((charCode > 31 && (charCode < 48 || charCode > 57)) && (charCode !== 46 || this.modal.addEmployee.pay_rate.split('.').length === 2 || this.modal.reqEditEmployee.pay_rate.split('.').length === 2)) {
        evt.preventDefault()
      } else {
        return true
      }
    },


		/**
		 * Open modal
		 * 
		 * @param  String modal
		 */
		async openModal(modal, data) {
			let vm = this
			switch(modal) {
				case 'AddNewEmployee':
					vm.modal.addNewEmployee = true
          vm.modal.addEmployee = {}
          vm.$refs.frmAddEmployee.reset()
          vm.modal.addEmployee.priority_group = 1
          vm.indexPosition('modal-position')
					break
        case 'EditEmployee':
          vm.modal.editEmployee = true

          // Show the prev/next button
          vm.modal.getEmployeeRecord.prev.show = true
          vm.modal.getEmployeeRecord.next.show = true

          vm.isLoader = false

          // This will filter the arrow if it already meet it's first/last record
          let filterPrevNextBtn = await axios.get(`/api/employee/${data.employee.id}/prevnextrecord`)
          vm.modal.getEmployeeRecord.prev.show = (filterPrevNextBtn.data.prev == null)?false:true
          vm.modal.getEmployeeRecord.next.show = (filterPrevNextBtn.data.next == null)?false:true

          // Get the employee's position(s)
          let fetchEmployeePositions = await axios.get(`/api/employee/${data.employee.id}/positions`)
          // Map employee's position to checkbox
          let arrPositionIds = []
          fetchEmployeePositions.data.forEach(val => arrPositionIds.push(val.position_id))
          vm.modal.selectedPositions = arrPositionIds


          vm.modal.fullname = `${data.employee.firstname} ${data.employee.lastname}`

          vm.modal.reqEditEmployee = {
            id: data.employee.id,
            firstname: data.employee.firstname,
            lastname: data.employee.lastname,
            email: data.employee.email,
            phone: data.employee.phone,
            phone2: data.employee.phone2,
            mobile: data.employee.mobile,
            employee_no: data.employee.employee_no,
            address: data.employee.address,
            address2: data.employee.address2,
            city: data.employee.city,
            state: data.employee.state,
            zip: data.employee.zip,

            max_weekly_hours: data.employee.max_weekly_hours,
            max_weekly_days: data.employee.max_weekly_days,
            max_day_hours: data.employee.max_day_hours,
            max_day_shifts: data.employee.max_day_shifts,

            pay_rate: data.employee.pay_rate,
            hired_date: data.employee.hired_date,
            priority_group: data.employee.priority_group,
            // position: fetchEmployeePositions.data,
          }



          vm.indexPosition('modal-position')
          vm.isLoader = false
          break
        case 'AddNewPosition':
          // vm.modal.addNewEmployee = false
          vm.modal.addEditPositions = true
          break
				case 'AddEditPositions':
					vm.modal.addEditPositions = true
          vm.indexPosition('modal-position')
          vm.trashedPosition()
					break
				case 'SignIn':
          vm.modal.SignIn = true
					break
				case 'BulkEditEmp':
          vm.modal.BulkEditEmp = true
					break
				case 'SignInSent':
					vm.modal.SignIn = false
					vm.modal.SignInSent = true
					break
        case 'EditDeletePositions':
          vm.modal.addEditPositions = false   // close the parent modal
          vm.modal.editDeletePositions = true
          break
        case 'EditPosition':
          vm.modal.showEditPosition = true
          vm.modal.reqEditPositionDisplay = data.position
          vm.modal.reqEditPosition = {
            id: data.id,
            position: data.position
          }
          break
			}
		},

    /**
     * List all Client
     */
    indexEmployee(position='') {
      let vm = this

      vm.isLoader = false
      axios
        .get(`/api/allproperties`)
        .then(res => {
         vm.propertiesdata = res.data.data.slice().sort(function(a, b){
          return (a.name > b.name) ? 1 : -1;
        });
      
          vm.isLoader = false
        })
    },
    namesort(){
      let vm = this;
      if(!this.nameSort){
        vm.propertiesdata = vm.propertiesdata.slice().sort(function(a, b){
          return (a.name < b.name) ? 1 : -1;
        });
         this.nameSort = !this.nameSort
      }
      else{
         vm.propertiesdata = vm.propertiesdata.slice().sort(function(a, b){
          return (a.name > b.name) ? 1 : -1;
        });
         this.nameSort = !this.nameSort
      }
    },
    // Send Mail To New Employee Added
    sendMailToNewEmp(){
      let vm = this;
      let ids = [];
      ids[0] = vm.index.employees[0].employee_id ;
                  axios.post('/api/employee/send-signin-instruction-to-employee' , {ids} )
                    .then(res => {
                      vm.$alert('Sign in Instructions have been sent to new employee!');
                    })
                    .catch(err => {
                      console.log('send email to selected error')
                    })

    },

    /**
     * Filter result via position id
     * 
     * @param  int position
     */
    filterResultViaPosition(position) {
      this.indexEmployee(position)
    },

    /**
     * Load positions
     * 
     * @param  String requestedBy [part the API is being requested]
     */
    async indexPosition(requestedBy) {
      let vm = this
      let position = await axios.get('/api/positions')

      switch (requestedBy) {
        case 'index-position':
          vm.index.positions = position.data
          break
        case 'modal-position':
          vm.modal.positions = position.data
          break
      }
    },

    /**
     * Send Email to all
     * 
     * @param  String requestedBy [part the API is being requested]
     */
    async sendEmailToAll() {
      let vm = this
      await axios.post('/api/employee/send-signin-instruction-to-all').then(res => {
                      vm.modal.SignInSuccess = res.data;
                      vm.sentIns();
                    })
                    .catch(err => {
                      console.log('email error')
                    });
    },
    /**
     * Send Email to selected employee
     * 
     * @param  String requestedBy [part the API is being requested]
     */
    async sendEmailToSelectedEmp(ids) {
      let vm = this
      await  axios.post('/api/employee/send-signin-instruction-to-employee' , {ids} )
                    .then(res => {
                      vm.$alert('Sign in Instructions have been sent to selected Watchers!');
                    })
                    .catch(err => {
                      console.log('send email to selected error')
                    })
    },

    /**
     * Load position(s) of an employee
     * 
     * @param  int empId
     */
    async showEmployeePosition(empId) {
      let vm = this
      // let employeePositions = await axios.get()
    },

    /*
      | =======================================
      |  [ Modal ] - Add Employee
      | =======================================
      */
    checkAllPositions(bool) {
      let vm = this

      let selected = []

      if (bool) {
        vm.modal.positions.forEach(pos => selected.push(pos.id))
        vm.modal.selectedPositions = selected
        vm.modal.checkBoxOption.selectAll = true
        vm.modal.checkBoxOption.unSelectAll = false
      } else {
        vm.modal.selectedPositions = []
        vm.modal.checkBoxOption.selectAll = false
        vm.modal.checkBoxOption.unSelectAll = true
      }
    },
    storeEmployee() {
      let vm = this

      if ( Object.keys(vm.modal.selectedPositions).length === 0 ) {
        vm.$swal({
          icon: 'error',
          title: 'At least select a position',
          showConfirmButton: false,
          timer: 1500
        })
        return false
      }

      vm.isLoader = false

      axios
        .post('/api/employees', Object.assign(vm.modal.addEmployee, { positions: vm.modal.selectedPositions }))
        .then(res => {
          vm.indexEmployee()
          // if(vm.modal.addEmployee.SendEmail){
          //    vm.sendMailToNewEmp()
          // }
          vm.modal.addEmployee = {}
          vm.$swal({
            icon: 'success',
            title: 'Successfully Added!',
            showConfirmButton: false,
            timer: 1500
          })
          vm.modal.addNewEmployee = false
          vm.isLoader = false
          // vm.$refs.frmAddEmployee.reset();
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 1500
          })
          vm.isLoader = false
        })
    },

    /*
      | =======================================
      |  [ Modal ] - Edit Employee
      | =======================================
      */
    /**
     * Get Employee Detail
     * 
     * @param string value [prev || next]
     */
    async getPrevNextEmployeeDetail(value) {
      let vm = this
      vm.isLoader = false
      try {
        let resEmployee = await axios.get(`/api/employee/${vm.modal.reqEditEmployee.id}/prevnextrecord`)
        // console.log('resEmployee', resEmployee.data)

        // Map employee's position to checkbox
        let arrPositionIds = []

        if ('prev' === value) {
          vm.modal.reqEditEmployee = resEmployee.data.prev
          vm.modal.getEmployeeRecord.prev.show = (resEmployee.data.prev == null || resEmployee.data.prev.id == resEmployee.data.first.id)?false:true
          vm.modal.getEmployeeRecord.next.show = true
          vm.modal.fullname = `${resEmployee.data.prev.firstname} ${resEmployee.data.prev.lastname}`

          resEmployee.data.prev.position.forEach(val => arrPositionIds.push(val.position_id))
          vm.modal.selectedPositions = arrPositionIds
        } else {
          vm.modal.reqEditEmployee = resEmployee.data.next
          vm.modal.getEmployeeRecord.prev.show = true
          vm.modal.getEmployeeRecord.next.show = (resEmployee.data.next == null || resEmployee.data.next.id == resEmployee.data.last.id)?false:true
          vm.modal.fullname = `${resEmployee.data.next.firstname} ${resEmployee.data.next.lastname}`

          resEmployee.data.next.position.forEach(val => arrPositionIds.push(val.position_id))
          vm.modal.selectedPositions = arrPositionIds
        }
        vm.isLoader = false
      } catch (e) {
        console.log('error', e)
        vm.isLoader = false
      }
    },

    /**
     * Update employee
     */
    async updateEmployee() {
      let vm = this
      if ( Object.keys(vm.modal.selectedPositions).length === 0 ) {
        vm.$swal({
          icon: 'error',
          title: 'At least select a position',
          showConfirmButton: false,
          timer: 1500
        })
        return false
      }

      vm.isLoader = false
      try {
        await axios.patch(`/api/employees/${vm.modal.reqEditEmployee.id}`, Object.assign(vm.modal.reqEditEmployee, { positions: vm.modal.selectedPositions }))
        vm.indexEmployee()
        vm.$swal({
          icon: 'success',
          title: 'Successfully updated!',
          showConfirmButton: false,
          timer: 1500
        })
        vm.isLoader = false
        vm.modal.editEmployee = false
      } catch (e) {
        console.log('Error', e)
        vm.isLoader = false
      }
    },

    /**
    /**
     * Bulk Update employee
     */
    async updateEmployeeBulk() {
      let vm = this
      vm.isLoader = false
      vm.modal.reqEditEmployeeBulk.ids = vm.BulkIDS
      try {
        await axios.post(`/api/employees/employee-bulk-edit`, Object.assign(vm.modal.reqEditEmployeeBulk))
        // vm.indexEmployee()
        vm.$swal({
          icon: 'success',
          title: 'Successfully updated!',
          showConfirmButton: false,
          timer: 1500
        })
        vm.isLoader = false
        vm.indexEmployee()
        vm.modal.BulkEditEmp = false
        vm.allSelected = false
      } catch (e) {
        console.log('Error', e)
        vm.isLoader = false
      }
    },

    /**
     * Update employee then proceed to next
     */
    async updateAndProceedNext() {
      let vm = this
      if ( Object.keys(vm.modal.selectedPositions).length === 0 ) {
        vm.$swal({
          icon: 'error',
          title: 'At least select a position',
          showConfirmButton: false,
          timer: 1500
        })
        return false
      }

      vm.isLoader = false
      try {
        await axios.patch(`/api/employees/${vm.modal.reqEditEmployee.id}`, Object.assign(vm.modal.reqEditEmployee, { positions: vm.modal.selectedPositions }))
        vm.indexEmployee()
        vm.$swal({
          icon: 'success',
          title: 'Successfully updated!',
          showConfirmButton: false,
          timer: 1500
        })
        vm.getPrevNextEmployeeDetail('next')
        // vm.isLoader = false
      } catch (e) {
        console.log('Error', e)
        vm.isLoader = false
      }
      
    },

    /**
     * Remove employee
     */
    async removeEmployee() {
      let vm = this
      try {
        if (confirm('Are you sure you want to remove this user?')) {
          vm.isLoader = false
          await axios.delete(`/api/employees/${vm.modal.reqEditEmployee.id}`)
          vm.indexEmployee()
          vm.$swal({
            icon: 'success',
            title: 'Successfully removed!',
            showConfirmButton: false,
            timer: 1500
          })
          vm.isLoader = false
          vm.modal.editEmployee = false
        }
      } catch (e) {
        console.log('error', e)
      }
    },



    /*
      | =======================================
      |  [ Modal ] - Add/Edit Positions
      | =======================================
      */
    trashedPosition() {
      axios
        .get('/api/positions-trashed')
        .then(res => this.modal.trashedPositions = res.data)
    },
    addPosition() {
      let vm = this
      let inputData = {
        position: vm.modal.addPosition.position
      }

      axios
        .post('/api/positions', inputData)
        .then(res => {
          vm.$swal({
            icon: 'success',
            title: 'Successfully added!',
            showConfirmButton: false,
            timer: 2000
          })
          vm.modal.addPosition.position = ''
          vm.modal.positions.splice(res.data.id, 0, res.data)
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 1500
          })
        })
    },
    /**
     * Edit specific position
     */
    editPosition() {
      let vm = this
      vm.isLoader = false
      axios
        .patch(`/api/positions/${vm.modal.reqEditPosition.id}`, {position: vm.modal.reqEditPosition.position})
        .then(res => {
          vm.$swal({
            icon: 'success',
            title: 'Successfully updated!',
            showConfirmButton: false,
            timer: 1500
          })
          vm.modal.reqEditPosition = {}
          vm.modal.showEditPosition = false
          vm.indexPosition('modal-position')
          vm.isLoader = false
        })
        .catch(err => {
          vm.$swal({
            icon: 'error',
            title: err.response.data,
            showConfirmButton: false,
            timer: 1500
          })
          console.log(err.response.data)
          vm.isLoader = false
        })
    },

    /**
     * Restore position which has been removed
     * 
     * @param  Int positionId
     * @param  Int index
     */
    restorePosition(positionId, index) {
      let vm = this
      axios
        .delete(`/api/positions-restore/${positionId}`)
        .then(res => {
          vm.modal.trashedPositions.splice(index, 1)
          vm.indexPosition()
          vm.$swal({
            icon: 'success',
            title: 'Successfully restored!',
            showConfirmButton: false,
            timer: 1500
          })
        })
        .catch(err => {
          console.log('error', err.response)
        })
    },

    /*
      | =======================================
      |  [ Tabs ]
      |  @Note - create component for this
      | =======================================
      */
    isActive(menuItem) {
      return this.activeItem === menuItem
    },
    setActive(menuItem) {
      this.indexPosition('modal-position')
      this.activeItem = menuItem
    },

    /**
     * Remove Position
     * 
     * @param  Ojb data
     * @param  Int index    
     */
    removePosition(data, index) {
      let vm = this
      if (confirm(`Are you sure you want to remove ${data.position}?`)) {
        axios
          .delete(`/api/positions/${data.id}`)
          .then(() => {
            vm.modal.positions.splice(index, 1)
            vm.$swal({
              icon: 'success',
              title: 'Successfully removed!',
              showConfirmButton: false,
              timer: 1500
            })
          })
          .catch(err => {
            vm.$swal({
              icon: 'error',
              title: err.response.data.message,
              showConfirmButton: false,
              timer: 1500
            })
          })
      }
    },

    /**
     * Save sorted position [draggable]
     */
    async saveSortedPosition() {
      let vm = this
      let arr = []

      vm.isLoader = false

      vm.modal.positions.forEach((val, index) => {
        arr.push({
          position_id: val.id,
          index: index
        })
      })

      try {
        await axios.patch('/api/position-sort', {data: arr})
        vm.$swal({
          icon: 'success',
          title: 'Sorted!',
          showConfirmButton: false,
          timer: 2000
        })
        vm.indexPosition('modal-position')
        vm.indexPosition('index-position')
        vm.isLoader = false
      } catch(e) {
        console.log(e)
        vm.isLoader = false
      }
    },

    /**
     * Sort position alphabetically
     */
    sortAlphabetically() {
      let vm = this
      let sortPosition = this.modal.positions.map(val => {
        return { id: val.id, index: val.index, position: val.position }
      })
      // Conditions
      //  - If return -1, it will place the first item before the second. 
      //  - If return 1, it will place the second item before the first.
      //  - If return 0, it will leave them unchanged.
      sortPosition.sort(function(a,b) {
        if ( a.position > b.position ) return 1
        if ( a.position < b.position ) return -1
      })
      vm.modal.positions = sortPosition
    },

    /**
     * Remove this
     */
    testMsg(msg) {
      this.$swal({
        icon: 'error',
        title: msg,
        showConfirmButton: false,
        timer: 1500
      })
    },
	}
}

</script>

<style lang="scss" scoped>
	@import '../../sass/employees';
  .property-table-list {
    thead{
      tr{
        th{
           padding: 1px 0px 1px 25px;
        }
      }
    }
    tbody{
      tr{
        td.location{
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
          max-width: 200px;
          padding: 1px 0px 1px 25px;
        }
        td{
          padding: 1px 0px 1px 25px;
        }
      }
    }
  }
  #map {
    width: 100%;
    height: 300px;
  }
  table td:first-child {
      background: none !important;
  }
</style>
