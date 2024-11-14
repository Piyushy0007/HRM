<template>
  <div class="c-employee-index client-add">
    <Loader msg="Processing ..." v-model="isLoader" />

    <div class="w-5/6 mx-auto mt-5">
     <form @submit.prevent="checkForm"  ref="frmAddEmployee" novalidate>
      <div class="col-md-12 flex mr-3">
      <div class="col-md-4 mr-3">

        <div class="rounded-lg add-client">
          <h1 class="">Add Community</h1>
          <h4 class="mb-4">Please complete to create account for client.</h4>
          <div class="section1 flex">
            <div class="info m-3">
            <b-avatar
              badge
              badge-variant="dark"
              :src="this.image"
              size="6rem"
              v-model="properties.image"
            >
              <template #badge>     
                <image-uploader
                :debug="1"
                :maxWidth="512"
                :quality="0.7"
                :autoRotate=true
                outputFormat="verbose"
                :preview=false
                :className="['fileinput', { 'fileinput--loaded' : hasImage }]"
                :capture="false"
                accept="video/*,image/*"
                doNotResize="['gif', 'svg']"
                @input="setImage"
                @onUpload="startImageResize"
                @onComplete="endImageResize"
                v-model="properties.image"
                
              >
                <label class="m-0" for="fileInput" slot="upload-label">
                  <b-icon icon="camera"></b-icon> 
                </label>
              </image-uploader> 
              </template>
            </b-avatar>
       
            </div>
            
          </div>
        </div>
      </div>
      <div class="col-md-8 mr-3">
        <div class="rounded-lg p-4">
          <div class="container">
            <div id="map"></div>
            <form action="" method="POST" id="map-form">
              <div class="col-sm-6">
                <input type="hidden" name="coords" id="map-coords" value="" />
                     <!-- <div class="information">
                        <b-button
                          class="m-3 text-center"
                          style="border: 1px solid black"
                          @click="saveCoordinates()"
                          >Save</b-button
                        >
                        <b-button
                          class="m-3 text-center"
                          style="border: 1px solid black"
                          @click="resetCoordinates()"
                          >Reset</b-button
                        >
                      </div> -->
              </div>
            </form>
          </div>
        </div>
      </div>
      </div>
       <div class="col-md-12 mr-3 flex">
         <div class="col-md-6">
            <div class="flex col-md-12 mt-3 mb-3 p-0">
           <div class="col-md-6 section_firstname p-0 mr-3">
              <h6>First Name<span class="req_form_fields">*</span></h6>
               <div class="col-md-12 pr-0 pl-0 mt-3 mb-3">
                <b-input-group style="border: 1px solid #DEDEDE; border-radius: 20px">
                  <b-form-input
                    style="border: none;background: no-repeat;margin: 10px 10px;"
                    type="text"
                    v-model="properties.firstname"
                    name='firstname'
                  >
                  </b-form-input>
                </b-input-group>
              </div>
            </div>
            <div class="col-md-6 section_lastname p-0">
              <h6>Last Name<span class="req_form_fields">*</span></h6>
                            <div class="col-md-12 pr-0 pl-0 mt-3 mb-3">
                <b-input-group style="border: 1px solid #DEDEDE; border-radius: 20px">
                  <b-form-input
                    style="border: none;background: no-repeat;margin: 10px 10px;"
                    type="text"
                    v-model="properties.lastname"
                     name='lastname'
                  >
                  </b-form-input>
                </b-input-group>
              </div>
            </div>
            </div>
            <div class="section2 mt-3 mb-3">
              <h6>Email<span class="req_form_fields">*</span></h6>
                             <div class="col-md-12 pr-0 pl-0 mt-3 mb-3">
                <b-input-group style="border: 1px solid #DEDEDE; border-radius: 20px">
                  <b-form-input
                    style="border: none;background: no-repeat;margin: 10px 10px;"
                    type="text"
                    v-model="properties.email"
                     name='email'
                  >
                  </b-form-input>
                </b-input-group>
              </div>
            </div>
            <div class="section2 mt-3 mb-3">
              <h6>Name of Company/Property<span class="req_form_fields">*</span></h6>
              <div class="col-md-12 pr-0 pl-0 mt-3 mb-3">
                <b-input-group style="border: 1px solid #DEDEDE; border-radius: 20px">
                  <b-form-input
                    style="border: none;background: no-repeat;margin: 10px 10px;"
                    type="text"
                    v-model="properties.company"
                     name='company'
                  >
                  </b-form-input>
                </b-input-group>
              </div>
            </div>
            <div class="section3 mt-3 mb-3 flex">
              <b-button class="col-md-4 generate-password" @click="generate_temporary_password()"  pill variant="outline-secondary"
                >Generate Temporary Password</b-button
              >
              <div class="col-md-8 pr-0">
                <b-input-group style="border: 1px solid #DEDEDE; border-radius: 20px">
                  <b-form-input
                    style="
                      border: none;
                      border-radius: 20px;
                      background: no-repeat;
                      margin: 10px 10px;
                    "
                    type="text"
                    v-model="properties.password"
                     name='password'
                  >
                  </b-form-input>
                  <b-input-group-append
                    style="border: none; border-radius: 20px"
                  >
                    <b-button
                    v-clipboard:copy="properties.password"
                    v-clipboard:success="onCopy"
                      style="
                        border: 1px solid #302369;
                        margin: 8px 8px;
                        border-radius: 20px;
                        background: #302369;
                        color: #ffffff;"
                      variant="outline-secondary"
                      >Copy to clipboard</b-button
                    >
                  </b-input-group-append><span class="req_form_fields">*</span>
                </b-input-group>
              </div>
            </div>
             <div class="section2 mt-3 mb-3">
              <h6>Username/email<span class="req_form_fields">*</span></h6>
                <div class="col-md-12 pr-0 pl-0 mt-3 mb-3">
                <b-input-group style="border: 1px solid #DEDEDE; border-radius: 20px">
                  <b-form-input
                    style="border: none;background: no-repeat;margin: 10px 10px;"
                    type="text"
                    v-model="properties.username"
                      name='username'
                  >
                  </b-form-input>
                </b-input-group>
              </div>
            </div>

           
         </div>
         <div class="col-md-6">
          <div class="section4 mt-3 mb-3">
              <h6>Office/Business Address<span class="req_form_fields">*</span></h6>
              <div class="col-md-12 pr-0 pl-0 mt-3 mb-3">
                <b-input-group style="border: 1px solid #DEDEDE; border-radius: 20px">
                  <b-form-input
                  id="address"
                    style="
                      border: none;
                      border-right: 1px solid;
                      background: no-repeat;
                      margin: 10px 10px;
                    "
                    type="text"
                    v-model="properties.property.address"
                     name='address'
                  >
                  </b-form-input>
                  <b-input-group-append
                    style="border: none; border-radius: 20px"
                  >
                    <b-button id="submit1"
                      style="border: none; border-radius: 20px"
                      variant="outline-secondary"
                      >GPS Coordinates: 41°24'12.2″N</b-button
                    >
                     
                  </b-input-group-append>
                </b-input-group>
              </div>

              <h6 class="mt-3">Latitude boundary<span class="req_form_fields">*</span></h6>
              <div class="col-md-12 pr-0 pl-0 mt-3 mb-3">
                <b-input-group style="border: 1px solid #DEDEDE; border-radius: 20px">
                  <b-form-input
                    style="
                      border: none;

                      background: no-repeat;
                      margin: 10px 10px;
                    "
                    type="text"
                    v-model="properties.property.lat"
                    name='lat'
                  >
                  </b-form-input>
                  <b-input-group-append
                    style="border: none; border-radius: 20px; width: 10%"
                  >
                    <b-icon
                      font-scale="1.5"
                      icon="geo-alt-fill"
                      style="
                        margin: auto;
                        text-align: center;
                        vertical-align: middle;
                        color: rgb(48, 35, 105);
                        font-size: 20px;
                      "
                    />
                  </b-input-group-append>
                </b-input-group>
              </div>

              <h6 class="mt-3">Longitude boundary<span class="req_form_fields">*</span></h6>
              <div class="col-md-12 pr-0 pl-0 mt-3 mb-3">
                <b-input-group style="border: 1px solid #DEDEDE; border-radius: 20px">
                  <b-form-input
                    style="
                      border: none;

                      background: no-repeat;
                      margin: 10px 10px;
                    "
                    type="text"
                  v-model="properties.property.long"
                  name='long'
                  >
                  </b-form-input>
                  <b-input-group-append
                    style="border: none; border-radius: 20px; width: 10%"
                  >
                   
                    <b-icon
                      font-scale="1.5"
                      icon="geo-alt-fill"
                      style="
                        margin: auto;
                        text-align: center;
                        vertical-align: middle;
                        color: rgb(48, 35, 105);
                        font-size: 20px;
                      "
                    />
                  </b-input-group-append>
                </b-input-group>
              </div>
              <h6>
                Trigger push notification to officer mobile app when officer on
                shift and perimeter is violated<span class="req_form_fields">*</span>
              </h6>
              <b-form-checkbox
                class="switch-check"
                size="lg"
                v-model="properties.switch"
                switch
                button-variant="info"
                name='switch'
              >
              </b-form-checkbox>
            </div>
         </div>
           
         
        </div>
        <div class="col-md-12 text-center">
           <p v-if="errors.length">
          <small class="text-red-600">Please fill the required fields</small>
           </p>
           <b-button type="submit" style="
                        border: 1px solid #302369;
                        margin: 8px 8px;
                        border-radius: 20px;
                        background: #302369;
                        color: #ffffff;"
                      variant="outline-secondary"
                      >  <b-icon-plus font-scale="1.5" style="
                        margin: auto;
                        text-align: center;
                        vertical-align: middle;
                        color: #ffffff;
                        font-size: 20px;
                      "/> Add Client </b-button>
        </div>
        <p v-if="errors.length">
          <!-- <small class="text-red-600">Please fill the required fields</small> -->
    <!-- <b>Please correct the following error(s):</b> -->
    <!-- <ul>
      <li v-for="error in errors" v-bind:key="error">{{ error }}</li>
    </ul> -->
  </p>
     </form>
      </div>

  </div>
</template>
<style lang="scss" scoped>
#map {
  width: 100%;
  height: 300px;
}
</style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.rawgit.com/bjornharrtell/jsts/gh-pages/1.4.0/jsts.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfX422PdA9Yy6GOf4HeRBRTtfoz-AGQdU&callback=initMap&libraries=&v=weekly"></script>

<script src="https://cdn.rawgit.com/bjornharrtell/jsts/gh-pages/1.4.0/jsts.min.js"></script>
<script>


import Vue from "vue";
import { BAvatar } from "bootstrap-vue";
Vue.component("b-avatar", BAvatar);
import Modal from "./shared/Modal";
import Loader from "./shared/Loader";
import axios from "axios";
import moment from "moment";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import draggable from "vuedraggable";
import Client from "./Client.vue";



export default {
  components: {
    Modal,
    Loader,
    DatePicker,
    draggable,
  },
  data() {
    return {
      errors: [],
      hasImage: false,
      image: '',
      bermudaTriangle: [],
      triangleCoords: [],
      lat: "",
      long: "",
      address1: "",
      coordinated_data: [],
      prop_id: this.$route.params.id,
      properties: {
        firstname:'',
        lastname:'',
        company:'',
        username:'',
        switch: true,
        image:this.image,
        password: '',
        property:{
          address:'',
          coordinates:'',
          lat:30.709579,
          long:76.689343,
        }
      },
      isLoader: false,
    };
  },
  computed: {
        profileimg() {
      return this.image;
    },

  },
  created() {
    this.inject_material_fonts_and_icons_into_header();
  },
  async mounted(){
    let vm = this;
     await vm.geoLocMethod();
  },

  async beforeDestroy() {
    $(
      'link[href="https://unpkg.com/bootstrap/dist/css/bootstrap.min.css"]'
    ).remove();
    $(
      'link[href="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css"]'
    ).remove();
  },
  watch: {
    profileimg: {
      handler() {
        console.log(this.image , 'profileimg')
      },
      deep: true,
      immediate: true,
    },
  },
  methods: {
    checkForm(e){
      let vm = this;
       if (this.properties.firstname && this.properties.lastname && this.properties.password && this.properties.property.address) {
         this.errors = [];
          vm.isLoader = true
         axios.post('/api/addclient', this.properties )
          .then(res => {
            if(res.data.status == true){
                  vm.$swal({
                  icon: 'success',
                  title: res.data.message,
                  showConfirmButton: false,
                  timer: 2000
              })
               vm.isLoader = false
              vm.$router.push('/client/') 
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
            title: err.response.data.message,
            showConfirmButton: false,
            timer: 1500
          })
            vm.isLoader = false
        })
        // return true;
      }
        if (!this.properties.password) {
        this.errors.push('firstname required.');
      }
        e.preventDefault();
    },
    storeClient() {
      let vm = this


      vm.isLoader = true
      vm.isLoader = false

        
    },




        onCopy: function (e) {
    //  this.copytoclipboard = true;
              this.$swal({
            icon: "success",
            title: "Password copied successfully",
            showConfirmButton: false,
            timer: 2000,
          });
    },
   
    generate_temporary_password(){
    let vm = this;
    var generator = require('generate-password');
    var password = generator.generate({
      length: 10,
      numbers: true
    });
    vm.properties.password = password;
    },
    startImageResize(){
      console.log(this.image)
    },
    endImageResize(){
       console.log(this.image)
    },
     setImage: function (file) {
      this.hasImage = true
      this.image = file.dataUrl
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
    resetCoordinates() {
      let vm = this;
      vm.bermudaTriangle.setMap(null);

      vm.lat = vm.properties.property.lat;
      vm.long = vm.properties.property.long;
      vm.address1 = vm.properties.address;

      axios
        .post(`/api/updateproperties/1`, {
          lat: vm.lat,
          long: vm.long,
          address: vm.address1,
          coordinates: "",
        })
        .then((res) => {
          vm.$swal({
            icon: "success",
            title: "Successfully added!",
            showConfirmButton: false,
            timer: 2000,
          });
          this.modal.geoLoc = false;
        })
        .catch((err) => {
          vm.$swal({
            icon: "error",
            title: err.response.data,
            showConfirmButton: false,
            timer: 1500,
          });
        });
    },
    saveCoordinates() {
      let vm = this;
      vm.lat = vm.properties.property.lat;
      vm.long = vm.properties.property.long;
      vm.address1 = vm.properties.address;

      axios
        .post(`/api/updateproperties/1`, {
          lat: vm.lat,
          long: vm.long,
          address: vm.address1,
          coordinates: this.coordinated_data,
        })
        .then((res) => {
          vm.$swal({
            icon: "success",
            title: "Successfully added!",
            showConfirmButton: false,
            timer: 2000,
          });
          this.modal.geoLoc = false;
        })
        .catch((err) => {
          vm.$swal({
            icon: "error",
            title: err.response.data,
            showConfirmButton: false,
            timer: 1500,
          });
        });
    },

    openGeo(id) {
      this.openModal("geoLoc");
    },
  
    // select all checkbox
   
    // close modal
   

    // Select  check message
  
    // Export data

    // Send Message
  
    //Send Reminder
  
 
    // Sign In instructions
    geoLocMethod() {
      let vm = this;
      var mapOptions = {
        zoom: 16,
        center: new google.maps.LatLng(vm.properties.property.lat, vm.properties.property.long ),
      };
       const geocoder = new google.maps.Geocoder();
        document.getElementById("submit1").addEventListener("click", () => {
          geocodeAddress(geocoder, map);
        });
      function geocodeAddress(geocoder, resultsMap) {
        const address = document.getElementById("address").value;
        geocoder.geocode({ address: address }, (results, status) => {
          if (status === "OK") {
            resultsMap.setCenter(results[0].geometry.location);
            new google.maps.Marker({
              map: resultsMap,
              position: results[0].geometry.location,
            });
          } else {
            alert("Geocode was not successful for the following reason: " + status);
          }
        });
      }

     
      if (
        vm.properties.property.coordinates != "" ||
        vm.properties.property.coordinates == null
      ) {
        JSON.parse(vm.properties.property.coordinates).map((item) => {
          item.map((elem) => {
            vm.triangleCoords.push({ lat: elem.x, lng: elem.y });
          });
        });
      } else {
        var drawingManager = new google.maps.drawing.DrawingManager({
          drawingControl: false,
          polygonOptions: {
            editable: true,
          },
        });

        var googleMaps2JTS = function (boundaries) {
          var coordinates = [];
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
          vm.properties.property.coordinates = coordinates;
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
      var map = new google.maps.Map( document.getElementById("map"), mapOptions);
       
      if (
        vm.properties.property.coordinates != "" ||
        vm.properties.property.coordinates == null
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
      } else {
        drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
        drawingManager.setMap(map);
        google.maps.event.addListener(
          drawingManager,
          "polygoncomplete",
          function (polygon) {
            //var polyPath = event.overlay.getPath();
            var intersects = findSelfIntersects(polygon.getPath());
            if (intersects && intersects.length) {
              alert("Done");
            } else {
              alert("Done");
            }
          }
        );
      }
    },
    // Properties(id) {
    //   let vm = this;
    //   vm.isLoader = true;
    //   axios.get(`/api/singleView/${id}`).then((res) => {
    //     vm.properties = res.data;
    //     console.log(vm.properties);
    //     vm.isLoader = false;
    //     vm.geoLocMethod();
    //   });
    // },
  },
};
</script>

<style lang="scss" scoped>
.generate-password{
  color: #302369;
    border: 2px solid;
    font-weight: bold;
    &:hover{
      background-color: #302369;
      color: #ffffff;
    }
}
@import "../../sass/employees";
</style>