<template>
  <div>
    <Loader msg="Processing ..." v-model="isLoader" />
    <div  style="margin-left: 242px;">
      <div class="p-4 max-w-7xl mx-auto h-screen">
      <b-alert  variant="success">Password copied successfully </b-alert>
      <div class="flex md:flex-row flex-col justify-center w-full">
        <div class=" w-full md:w-1/2 flex justify-center bg-white p-4 shadow-md rounded-lg mb-5">
          <div class="rounded-lg " style="width: 80%;" v-show="!readonlyprofile">
            <div class="section1 flex" style="justify-content: space-evenly;">
              <div class="info my-3" style="display:grid;">
              <label>Cover Image</label>
             <!-- <img class="b-avatar-img-class" :src="this.image ? properties.image : 'https://placekitten.com/300/300'"/> -->
              <b-avatar
                badge
                badge-variant="dark"
                :src="this.logo"
                size="6rem"
                v-show="show_default_logo"
              >
                <template #badge>
                  <input type='file' accept="image/*" id='fileInput21' name='fileInput21'  @change="Logo_onFileChanged">
                    <label class="m-0" for="fileInput21" slot="upload-label">
                    <b-icon class="blue-bicon" icon="camera"></b-icon> 
                  </label>
                </template>
              </b-avatar>
              <b-avatar
                v-show="hide_default_logo"
                badge
                badge-variant="light"
                :src="this.logo"
                size="6rem"
                
              >
                <template #badge>     
                  <input type='file' accept="image/*" id='fileInput31' name='fileInput31'  @change="Logo_onFileChanged">
                    <label class="m-0" for="fileInput31" slot="upload-label">
                    <b-icon class="blue-bicon" icon="camera"></b-icon> 
                  </label>
                </template>
              </b-avatar>
              </div>
              <div class="info my-3" style="display:grid;">
                <label>Profile Image</label>
             <!-- <img class="b-avatar-img-class" :src="this.image ? properties.image : 'https://placekitten.com/300/300'"/> -->
              <b-avatar
                badge
                badge-variant="dark"
                :src="this.image"
                size="6rem"
                v-show="show_default_img"
              >
                <template #badge>
                  <input type='file' accept="image/*" id='fileInput2' name='fileInput2'  @change="Profile_onFileChanged">
                    <label class="m-0" for="fileInput2" slot="upload-label">
                    <b-icon class="blue-bicon" icon="camera"></b-icon> 
                  </label>
                </template>
              </b-avatar>
              <b-avatar
                v-show="hide_default_img"
                badge
                badge-variant="light"
                :src="this.image"
                size="6rem"
                
              >
                <template #badge>     
                  <input type='file' accept="image/*" id='fileInput3' name='fileInput3'  @change="Profile_onFileChanged">
                    <label class="m-0" for="fileInput3" slot="upload-label">
                    <b-icon class="blue-bicon" icon="camera"></b-icon> 
                  </label>
                </template>
              </b-avatar>
              </div>
               </div>
              <div class="section2 mt-3 flex flex-col">
                <h6 class="text-lg text-gray-700 ">Name of Client:</h6>
               <input class="flex-grow border border-gray-300 rounded-md p-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" v-model="modal.editClient.clientname">
               <!-- <input class="appearance-none block w-full rounded py-1 mt-2 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.company"> -->
              <input type="hidden" v-model="modal.editClient.id">
              </div>
              <div class="section2 mt-3 flex flex-col">
                <h6 class="text-lg text-gray-700 ">Name of Contact:</h6>
                <input class="flex-grow border border-gray-300 rounded-md p-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" v-model="modal.editClient.contactname">
              </div>
              <div class="section2 mt-3 flex flex-col">
                <h6 class="text-lg text-gray-700" style="min-width: 120px;">Phone Number: </h6>
                 <input 
                  id="textbox" 
                  class="flex-grow border border-gray-300 rounded-md p-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                  type="text" 
                  style="max-width: 400px;"
                  v-model="modal.editClient.phone" @input="acceptNumber">   
              </div>
              <div class="section2 mt-3 flex flex-col">
                <h6 class="text-lg text-gray-700 ">Email Address:</h6>
                <input 
                  type="text" 
                  v-model="modal.editClient.email" 
                  placeholder="Enter your email"
                  class="flex-grow border border-gray-300 rounded-md p-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
              </div>
              <div class="section2 mt-3 flex flex-col">
                <h6 class="text-lg text-gray-700 ">Zipcode: </h6>
                <!-- <div class="info m-3"> -->
               <input class="flex-grow border border-gray-300 rounded-md p-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" v-model="modal.editClient.zip">
              </div>
              <div class="section2 mt-3 flex flex-col">
                <h6 class="text-lg text-gray-700">Police Email Address: </h6>
                <!-- <div class="info m-3"> -->
               <input class="flex-grow border border-gray-300 rounded-md p-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" v-model="modal.editClient.police_email">
              </div>
              <div class="section2 mt-3 flex flex-col">
                <h6 class="text-lg text-gray-700 ">Location: </h6>
                <!-- <div class="info m-3"> -->
               <input id="pac-input1" class="flex-grow border border-gray-300 rounded-md p-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text">
              </div>
              <div class="section2 mt-3 flex flex-col">
                <h6 class="text-lg text-gray-700 ">Password: </h6>
                <!-- <div class="info m-3"> -->
               <input class="flex-grow border border-gray-300 rounded-md p-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="password" v-model="modal.editClient.password">
              </div>
  
              <div class="section2 mt-3 flex flex-col">
                <h6 class="text-lg text-gray-700">Confirm Password:</h6>
                <!-- <div class="info m-3"> -->
               <input class="flex-grow border border-gray-300 rounded-md p-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="password" v-model="modal.editClient.confirmpassword">
              </div>
  
  
              <div class="section2 mt-3 flex flex-col">
                <h6 class="text-lg text-gray-700">Website: </h6>
                <!-- <div class="info m-3"> -->
               <input class="flex-grow border border-gray-300 rounded-md p-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" v-model="modal.editClient.website">
               <input  type="hidden" v-model="modal.editClient.state">
              </div>
              <div class="section2 mt-3 flex flex-col">
                <h6 class="text-lg text-gray-700">Job Title: </h6>
                <!-- <div class="info m-3"> -->
               <input class="flex-grow border border-gray-300 rounded-md p-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" v-model="modal.editClient.job_title">
              </div>
              <div class="section2 mt-5 mb-3 flex justify-center">
                  <button style="display: flex; padding: 10px 10px;width: 150px;" class="add-purple-button" @click="saveProfile(properties)"><b-icon-pencil-square style="margin: 3px 9px;" /> Save Profile</button>
              </div>
          </div>
          <div class=" rounded-md" style="width: 80%;" v-show="readonlyprofile">
            <div class="section1 ">
              <div class="info my-3">
               <img v-if="logo" class="b-avatar-logo-class" :src="logo" />
               <img v-else-if="properties.client_background_logo" class="b-avatar-logo-class" :src="currentpath+'/storage/'+properties.client_background_logo" />
              </div>
              <div class="info my-3">
               <img v-if="image" class="b-avatar-img-class" :src="image" />
               <img v-else-if="properties.client_image" class="b-avatar-img-class" :src="currentpath+'/storage/'+properties.client_image" />
              </div>
            </div>
              <div class="section2 mt-3 mb-3 flex items-center gap-2">
                <h6 class="h6-heading">Name of Client:</h6>
                <h6 class="h6-label textcap">
                  {{ properties.clientname || "-" }}             
                </h6>
                  <!-- <h6 class="h6-label textcap">
                    {{ properties.company ?  properties.company :'-' }}
                  </h6> -->          
                 <!-- <b-input-group style="border: 1px solid #DEDEDE; border-radius: 20px">
                    <b-form-input
                      style="border: none;background: no-repeat;margin: 10px 10px;"
                      type="text"
                     :value=" properties.email"
                    >
                    </b-form-input>
                  </b-input-group> -->
              </div>
              <div class="section2 mt-3 mb-3 flex items-center gap-2">
                <h6 class="h6-heading">Name of Contact:</h6>
                <h6 class="h6-label textcap">
                 {{ properties.contactname || "---" }}
                </h6>
              </div>
              <div class="section2 mt-3 mb-3 flex items-center gap-2">
                <h6 class="h6-heading">Phone Number:</h6>
                <!-- <div class="info m-3"> -->
                <h6 class="h6-label">
                  {{ properties.phone || "---" }}
                </h6>
              </div>
              <div class="section2 mt-3 mb-3 flex items-center gap-2">
                <h6 class="h6-heading">Email Address: </h6>
                <!-- <div class="info m-3"> -->
                <h6 class="h6-label">
                  {{ properties.email || "---" }}
                </h6>
              </div>
              <div class="section2 mt-3 mb-3 flex items-center gap-2">
                <h6 class="h6-heading">Zipcode:</h6>
                <!-- <div class="info m-3"> -->
                <h6 class="h6-label">
                  {{ properties.zip || "---" }}
                </h6>
              </div>
              <div class="section2 mt-3 mb-3 flex items-center gap-2">
                <h6 class="h6-heading">Police Email Address: </h6>
                <!-- <div class="info m-3"> -->
                <h6 class="h6-label">
                  {{ properties.police_email || "---" }}
                </h6>
              </div>
              <div class="section2 mt-3 mb-3 flex items-center gap-2">
                <h6 class="h6-heading">Location:</h6>
                <!-- <div class="info m-3"> -->
                <h6 class="h6-label">
                  {{ properties.address || "---" }}
                </h6>
              </div>
              <div class="section2 mt-3 mb-3 flex items-center gap-2">
                <h6 class="h6-heading">Website:</h6>
                <!-- <div class="info m-3"> -->
                <h6 class="h6-label">
                  {{ properties.website  || "---" }}
                </h6>
              </div>
              <div class="section2 mt-3 mb-3 flex items-center gap-2">
                <h6 class="h6-heading">Job Title:</h6>
                <!-- <div class="info m-3"> -->
                <h6 class="h6-label">
                  {{ properties.job_title || "---" }}
                </h6>
              </div>
              <div class="section2 mt-5 mb-3 flex justify-center">
                <button style="display: flex; padding: 10px 30px; width:190px;" class="add-purple-button" @click="editProfile(properties)"><b-icon-pencil-square style="margin: 3px 5px;" /> Edit Profile</button>
              </div>
              <!-- <div class="section3 mt-3 mb-3 flex">
                <b-button class="col-md-4 generate-password" @click="generate_temporary_password()" pill variant="outline-secondary"
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
                      readonly
                      v-model="properties.password"
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
                          color: #ffffff;
                        "
                        variant="outline-secondary"
                        >Copy to clipboard</b-button
                      >
                    </b-input-group-append>
                  </b-input-group>
                </div>
              </div>
              <div class="section4 mt-3 mb-3">
                <h6>Office/Business Address</h6>
                <div class="col-md-12 pr-0 pl-0 mt-3 mb-3">
                  <b-input-group style="border: 1px solid #DEDEDE; border-radius: 20px">
                    <b-form-input
                      style="
                        border: none;
  
                        background: no-repeat;
                        margin: 10px 10px;
                      "
                      type="text"
                      readonly
                      :value=" properties.property
                      ? properties.property.address
                      : 'abc address'"
                    >
                    </b-form-input>
                    <b-input-group-append
                      style="border: none; border-radius: 20px"
                    >
                      <b-button
                        style="border: none; border-radius: 20px"
                        variant="outline-secondary"
                        >GPS Coordinates: 41°24'12.2″N</b-button
                      >
                    </b-input-group-append>
                  </b-input-group>
                </div>
  
                <h6>Latitude boundary</h6>
                <div class="col-md-12 pr-0 pl-0 mt-3 mb-3">
                  <b-input-group style="border: 1px solid #DEDEDE; border-radius: 20px">
                    <b-form-input
                      style="
                        border: none;
  
                        background: no-repeat;
                        margin: 10px 10px;
                      "
                      type="text"
                      readonly
                      :value=" properties.property
                      ? properties.property.lat
                      : '-'"
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
  
                <h6>Longitude boundary</h6>
                <div class="col-md-12 pr-0 pl-0 mt-3 mb-3">
                  <b-input-group style="border: 1px solid #DEDEDE; border-radius: 20px">
                    <b-form-input
                      style="
                        border: none;
  
                        background: no-repeat;
                        margin: 10px 10px;
                      "
                      type="text"
                      readonly
                     :value=" properties.property
                      ? properties.property.long
                      : '-'"
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
                  shift and perimeter is violated
                </h6>
                <b-form-checkbox
                  class="switch-check"
                  size="lg"
                  v-model="properties.switch"
                  name="check-button"
                  switch
                  button-variant="info"
                >
                </b-form-checkbox>
              </div> -->         
          </div>
        </div>
        <div class="w-full md:w-4/6 mr-3">
          <!-- <div class="rounded-lg p-4">
            <div class="container">
              <div id="map"></div>
              <form action="" method="POST" id="map-form">
                <div class="col-sm-6">
                  <input type="hidden" name="coords" id="map-coords" value="" />
  
                  <input @click="saveCoordinates()" type="submit" value="Save"/>
                  <input type="button" value="Reset" id="reset"/>
                </div>
              </form>
            </div>
          </div> -->
          <div class="px-4 pb-4 table-width-full">
          <div>
            <h2 class="font-semibold text-lg text-gray-800 bg-white p-4 shadow-md rounded-lg mb-5">Properties</h2>
          </div>
          <template>
            <div class="bg-white shadow-lg rounded-lg">
              <table class="w-full table-auto">
                <thead>
                  <tr>
                  <th class="text-center py-3 px-4 text-blue-600">Property Name</th>
                  <th class="text-center py-3 px-4 text-blue-600">Location</th>
                  <th class="text-center py-3 px-4 text-blue-600">Status</th>
                  <!-- <th class="text-center"></th> -->
                  <th class="text-center py-3 px-4 text-blue-600">Edit</th>
                  <th class="text-center py-3 px-4 text-blue-600">Delete</th>
                  </tr>
                </thead>        
                <tbody >
                  <template v-if="properties.property.length != 0">
                  <tr v-for="(data) in properties.property" :key="data.id">
                    <td class="text-center text-transform-capitalise">{{ data.name || '-'}}</td>
                    <td class="text-center">{{data.address ? data.address : '-'}}</td>
                    <td :class="data.status == 'active' ? 'active text-center' : 'inactive text-center'" >{{ data.status ? data.status : '-' }}</td>
                    <td class="text-center"  @click='openView(data)' > <font-awesome-icon icon="pencil-alt"  class="text-gray-500 m-3 font-size-24"  /></td>
                    <td class="text-center" @click='deleteView(data)' ><font-awesome-icon :icon="['far', 'trash-alt']" class="text-gray-500 m-3 font-size-24" /></td>
                  </tr>
                    </template>
                  <template  v-else>
                    <tr> 
                      <td colspan="5" class="text-center p-4">No Record Found</td> 
                    </tr>                
                  </template>
                </tbody>
              </table>
            </div>
          </template>
          </div>
        </div>
  
      </div>
        <modal v-model="modal.geoLoc" size="md:w-7/12" :title="updateprop ? 'Update Property' : 'Create Property'">
          <ValidationObserver v-slot="{ handleSubmit }">
            <form @submit.prevent="handleSubmit(propertyModel())" ref="frmpropertymodel" novalidate>
              <div class=" px-4 mb-4 mt-4">
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
                
                <ValidationProvider rules="required" v-slot="v"  class=" md:w-6/12 m-3" style="display:block;">
                  <div class=" md:items-center">
                    <div class="md:w-4/4">
                      <label class="block mb-1 md:mb-0 pr-4">Property Address<span class="req_form_fields">*</span></label>
                    </div>
                    <div class="md:w-4/4">
                      <input id="pac-input" class="appearance-none block w-full rounded-10 height-41 py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text">
                    </div>
                  </div>
                  <div class="md:flex md:items-center mb-1">
                    <div class="md:w-3/4">
                      <small class="text-red-600">{{ v.errors[0] }}</small>
                    </div>
                  </div>
                </ValidationProvider>
                 
                </div>
                <div class="d-flex">
                  <div id="map"></div>
                   <b-button v-if="resettrue"
                    class="m-3 text-center"
                    style="border: 1px solid black"
                    @click="resetCoordinates()"
                    >Reset</b-button
                  >
                </div>
              </div>
              <!-- ================================= ./Contact ================================= -->
              <div class="text-center mt-2 mb-2">
                <span  v-if="resettrue" class="text-white py-3 px-12 rounded-full  bg-custom-primary" @click="close()">
                   <span>Cancel</span>
                 
                </span>
                <button class="text-white py-3 px-12 rounded-full  bg-custom-primary" type="submit">
                   <span>Save</span>
                 
                </button>
              </div>
            </form>
          </ValidationObserver>
        </modal>
        <modal v-model="modal.editclient" class="modal-add-new-employee" size="md:w-5/12" title="Edit Client">
          <ValidationObserver v-slot="{ handleSubmit }">
            <form @submit.prevent="handleSubmit(storeEmployee('edit'))" ref="frmeditclient" novalidate>
    
    
              <!-- ================================= Contact ================================= -->
              <div class=" px-4 mb-4 mt-4">
                <ValidationProvider rules="required" v-slot="v">
                  <div class=" md:items-center">
                    <div class="md:w-4/4">
                      <label class="block mb-1 md:mb-0 pr-4">Name of Client<span class="req_form_fields">*</span></label>
                    </div>
                    <div class="md:w-4/4">
                      <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.clientname">
                        <input type="hidden" v-model="modal.editClient.id">
                    </div>
                  </div>
                  <div class="md:flex md:items-center mb-1">
                    <div class="md:w-3/4">
                      <small class="text-red-600">{{ v.errors[0] }}</small>
                    </div>
                  </div>
                </ValidationProvider>
                <ValidationProvider rules="required" v-slot="v">
                  <div class=" md:items-center">
                    <div class="md:w-4/4">
                      <label class="block mb-1 md:mb-0 pr-4">Company Name<span class="req_form_fields">*</span></label>
                    </div>
                    <div class="md:w-4/4">
                      <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.company">
                    </div>
                  </div>
                  <div class="md:flex md:items-center mb-1">
                    <div class="md:w-3/4">
                      <small class="text-red-600">{{ v.errors[0] }}</small>
                    </div>
                  </div>
                </ValidationProvider>
                <ValidationProvider rules="required" v-slot="v">
                  <div class=" md:items-center">
                    <div class="md:w-4/4">
                      <label class="block mb-1 md:mb-0 pr-4">Address<span class="req_form_fields">*</span></label>
                    </div>
                    <div class="md:w-4/4">
                      <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.address">
                    </div>
                  </div>
                  <div class="md:flex md:items-center mb-1">
                    <div class="md:w-3/4">
                      <small class="text-red-600">{{ v.errors[0] }}</small>
                    </div>
                  </div>
                </ValidationProvider>
    
                <ValidationProvider rules="required" v-slot="v">
                  <div class=" md:items-center">
                    <div class="md:w-4/4">
                      <label class="block mb-1 md:mb-0 pr-4">Name of contact<span class="req_form_fields">*</span></label>
                    </div>
                    <div class="md:w-4/4">
                      <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model="modal.editClient.contactname">
                    </div>
                  </div>
                  <div class="md:flex md:items-center mb-1">
                 
                    <div class="md:w-3/4">
                      <small class="text-red-600">{{ v.errors[0] }}</small>
                    </div>
                  </div>
                </ValidationProvider>
    
                <ValidationProvider rules="required" v-slot="v">
                  <div class="md:items-center">
                    <div class="md:w-4/4">
                      <label class="block mb-1 md:mb-0 pr-4">Phone Number<span class="req_form_fields">*</span></label>
                    </div>
                    <div class="md:w-4/4">
                      <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="text" v-model.number="modal.editClient.phone" @keypress="isNumberOnly($event)" maxlength="15">
                    </div>
                  </div>
                  <div class="md:flex md:items-center mb-1">
                  
                    <div class="md:w-3/4">
                      <small class="text-red-600">{{ v.errors[0] }}</small>
                    </div>
                  </div>
                </ValidationProvider>
    
                 <ValidationProvider rules="required" v-slot="v">
                  <div class=" md:items-center">
                    <div class="md:w-4/4">
                      <label class="block mb-1 md:mb-0 pr-4">Email<span class="req_form_fields">*</span></label>
                    </div>
                    <div class="md:w-4/4">
                      <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="email" v-model="modal.editClient.email">
                    </div>
                  </div>
    
                  <div class="md:flex md:items-center mb-1">
                  
                    <div class="md:w-3/4">
                      <small class="text-red-600">{{ v.errors[0] }}</small>
                    </div>
                  </div>
                </ValidationProvider>
                 <ValidationProvider rules="required" v-slot="v">
                  <div class=" md:items-center">
                    <div class="md:w-4/4">
                      <label class="block mb-1 md:mb-0 pr-4">Status(Active/Inactive)<span class="req_form_fields">*</span></label>
                    </div>
                    <div class="md:w-4/4">
                     <b-form-checkbox
                      class="switch-check"
                      size="lg"
                      v-model="modal.editClient.switch"
                      switch
                      button-variant="info"
                      name='switch'
                      checked
                    >
                  </b-form-checkbox>
                      <!-- <input class="appearance-none block w-full rounded py-1 px-4 leading-tight focus:outline-none border-2 border-gray-200" type="email" v-model="modal.editClient.email"> -->
                    </div>
                  </div>
    
                  <div class="md:flex md:items-center mb-1">
                  
                    <div class="md:w-3/4">
                      <small class="text-red-600">{{ v.errors[0] }}</small>
                    </div>
                  </div>
                </ValidationProvider>
              </div>
              <!-- ================================= ./Contact ================================= -->
              <div class="text-center mt-2 mb-2">
                <button class="text-white py-3 px-12 rounded-full  bg-custom-primary" type="submit">Save</button>
              </div>
    
            
            </form>
          </ValidationObserver>
        </modal>
      </div>
    </div>

  </div>
</template>

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  
<script src="https://cdn.rawgit.com/bjornharrtell/jsts/gh-pages/1.4.0/jsts.min.js"></script> 
<script src="https://maps.google.com/maps/api/js?key=AIzaSyDfX422PdA9Yy6GOf4HeRBRTtfoz-AGQdU&callback=initAutocomplete&libraries=places,drawing&v=weekly" async></script>
<script src="https://cdn.rawgit.com/bjornharrtell/jsts/gh-pages/1.4.0/jsts.min.js"></script>
<script>
// import 'bootstrap/dist/css/bootstrap.css'
// import 'bootstrap-vue/dist/bootstrap-vue.css'

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
// import { BModal, VBModal } from 'bootstrap-vue'

const removeAllSelections = () => {
  window.getSelection().removeAllRanges();
};

const selectContent = (element) => {
  let range = document.createRange();
  range.selectNode(element);
  window.getSelection().addRange(range);
  var txt = window.getSelection().getRangeAt(0).toString();
  document.execCommand("copy");
  removeAllSelections();
};

const copySelection = () => {
  setTimeout(() => {
    document.execCommand("copy");
    removeAllSelections();
  });
};

export default {
  components: {
    Modal,
    Loader,
    DatePicker,
    draggable,
  },
  data() {
    return {
       currentpath : window.location.origin,
      resettrue: false,
      updateprop: false,
     name: '',
      nameState: null,
     address: '',
      addressState: null,
        submittedNames: [],
      hasImage: false,
      image: '',
      logo: '',
      readonlyprofile: true,
      bermudaTriangle: [],
      triangleCoords: [],
      lat: "",
      long: "",
      address1: "",
      coordinated_data: [],
      prop_id: this.$route.params.id,
      properties: {
          property:{
          address:'',
          coordinates:'',
          lat:30.709579,
          long:76.689343,
        },
        password:'',
         switch: false,
      },
      requestedHeaders: {
        headers: {},
      },
      isLoader: false,

      searchKeyword: "",
      searchTimer: null,
      // select employee
      checkedNames: [],
      bulkids: [],
      // tabs
      activeItem: "edit",

      modal: {
        editclient: false,
        editClient:{
          phone: ''
        },
        SendMessageToAll: {
          id: [],
          subject: "",
          message: "",
        },
        SignInSuccess: [],
        addNewEmployee: false,
        editEmployee: false,
        geoLoc: false,
        geoloc:{
          clientid: '',
          propertyid: '',
          propertyname: '',
          propertyaddress: '',
          coordinates: '',
          lat: 30.709579 ,
          long:76.689343 ,
        },
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
        reqEditPositionDisplay: "",
        reqEditPosition: "",

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

        fullname: "",

        addEmployee: {
          SendEmail: true,
        },
        reqEditEmployee: {},
        reqEditEmployeeBulk: {},
        reqEditEmployeeBulk: {
          max_weekly_hours: 40,
          max_weekly_days: 7,
          max_day_hours: 14,
          max_day_shifts: 1,
        },

        positions: [],
        trashedPositions: {},
        addPosition: {},

        // Add employee
        selectedPositions: [],
        // This is un/ticking checkbox
        checkBoxOption: {
          selectAll: false,
          unSelectAll: false,
        },
      },
      selected: [],
      allSelected: false,
      options: {
        chooseDateRange: "",
        beginDate: "",
        endDate: "",
        id: [],
        subject: "",
        comment: "",
      },
      SendReminderSuccessData: [],

      index: {
        employees: {},
        positions: {},
        selectPosition: "",
      },
      input: '',
      input1: '',
      searchBox:'',
      searchBox1:'',
      Images:{
        client_image:null,
        client_background_logo:null,
      },
      show_default_img: true,
      hide_default_img: false,
      show_default_logo: true,
      hide_default_logo: false,
    };
  },
  computed: {
    profileimg() {
      return this.image;
    },
    user_data(){
     return JSON.parse(localStorage.getItem('user'))
    },
    userid(){
      let user = localStorage.getItem('user')
      return JSON.parse(user).id
    }

  },
  async created() {
    this.inject_material_fonts_and_icons_into_header();
    let vm = this;
    await vm.indexEmployee();
    await vm.Properties(vm.prop_id);
    await vm.indexPosition("index-position");
  },

  async beforeDestroy() {
    this.destroystyle();
  },

    watch: {
    profileimg: {
      handler() {
        // console.log(this.image , 'profileimg')
      },
      deep: true,
      immediate: true,
    },
     close: {
       handler(){
      let vm = this;
      this.resettrue = false;
      this.updateprop = false;
      // this.$refs.frmpropertymodel.reset();
      vm.modal.geoloc.propertyname = '' ,
          vm.modal.geoloc.propertyaddress = ''
          vm.modal.geoloc.coordinates = ''
        this.modal.geoLoc = false;
       },
       deep: true,
      immediate: true,
    }
  },

  methods: {
  //image upload
	Profile_onFileChanged(event)
	  {
				this.Images.client_image = event.target.files;
        this.createProfileImage( this.Images.client_image[0]);
        document.getElementById("fileInput3").style.display = "none";
        this.modal.editClient.client_image = this.Images.client_image
         this.hide_default_img = true;
         this.show_default_img = false;
		},
	Logo_onFileChanged(event)
	  {
				this.Images.client_background_logo = event.target.files;
        this.createLogoImage( this.Images.client_background_logo[0]);
        document.getElementById("fileInput31").style.display = "none";
        this.modal.editClient.client_background_logo = this.Images.client_background_logo
         this.hide_default_logo = true;
         this.show_default_logo = false;
		},
    createLogoImage(file) {
      var logo1 = new Image();
      var reader = new FileReader();
      var vm = this;
      reader.onload = (e) => {
        this.logo1 = e.target.result;
        this.logo =  this.logo1;
      };
      reader.readAsDataURL(file);
    },
    createProfileImage(file) {
      var image1 = new Image();
      var reader = new FileReader();
      var vm = this;
      reader.onload = (e) => {
        this.image1 = e.target.result;
        this.image =  this.image1;
      };
      reader.readAsDataURL(file);
    },
    destroystyle(){
          $(
      'link[href="https://unpkg.com/bootstrap/dist/css/bootstrap.min.css"]'
    ).remove();
    $(
      'link[href="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css"]'
    ).remove();
    },
    create(id){
      let vm = this;
      this.updateprop = false;
      vm.modal.geoloc.clientid = id;
      vm.modal.geoloc.propertyid = '';
          vm.modal.geoloc.propertyaddress = '';
            vm.modal.geoloc.propertyname = '';
             vm.modal.geoloc.coordinates = '';
          this.openModal("geoLoc");
    },
    isnumber(evt){
    evt = evt ? evt : window.event;
      let charCode = evt.which ? evt.which : evt.keyCode;
      // if decimal point (.) is allowed, set this to charCode !== 46
      // otherwise, set this to charCode !== 46
      // if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode == 46) {
      if (
        (charCode > 31 && (charCode < 48 || charCode > 57)) ||
        charCode == 46 ||
        charCode == 9 ||
        charCode == 16
      ) {
        evt.preventDefault();
      } else {
        // this.modal.editClient.phone = evt;
        return true;
      }
    },
    acceptNumber() {
      var y = this.modal.editClient.phone;  
      if(this.isnumber(y)){
        	var x = y.toString().replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
      this.modal.editClient.phone = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
      }
      else{
        alert('invalid')
      }
    
    },
    openView(data){
      let vm = this;
        vm.modal.geoloc.clientid = data.client_id;
        vm.modal.geoloc.propertyid = data.id;
          vm.modal.geoloc.propertyaddress = data.address;
            vm.modal.geoloc.propertyname = data.name;
             vm.modal.geoloc.coordinates = data.coordinates;
                vm.modal.geoloc.lat = data.lat;
                 vm.modal.geoloc.long = data.long;
                  this.updateprop = true;
                  this.openModal("geoLoc");
                   
    },
   async  deleteView(data){  
      let vm = this;
      let id = data.id;
      let propid = data.id;
      let clientid = data.client_id;
     var index = vm.properties.property.map(x => {
        return x.id;
      }).indexOf(id);
      
           

          try {
        if (confirm(`Are you sure you want to cancel this property : ${data.name} - ${data.address}?  `)) {
          vm.isLoader = true
           vm.properties.property.splice(index, 1);
          await axios.get(`/api/propertydelete/${propid}`).then(res => {
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
      
      // axios
      //   .post('/api/updateclient', Object.assign(vm.properties))
      //   .then(res => {
      //     // vm.indexEmployee()
      //     vm.$swal({
      //       icon: 'success',
      //       title: 'Successfully Updated!',
      //       showConfirmButton: false,
      //       timer: 1500
      //     })
      //     vm.isLoader = false
      //   })
      //   .catch(err => {
      //     vm.$swal({
      //       icon: 'error',
      //       title: err.response.data,
      //       showConfirmButton: false,
      //       timer: 1500
      //     })
      //     vm.isLoader = false
      //   })

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
     editProfile(data){

      let vm = this;
      this.readonlyprofile = false
      vm.modal.editClient = data;
    // document.getElementById("fileInput").style.display = "none";
    document.getElementById("fileInput2").style.display = "none";
    document.getElementById("fileInput21").style.display = "none";
      //  this.openModal('editClientModal', data);
      vm.initAutocomplete();
    },

     saveProfile(data){
       this.readonlyprofile = false
       this.storeEmployee1();
      //  this.openModal('editClientModal', data);
    },

    initAutocomplete() {
      let vm = this;
    const map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: -33.8688, lng: 151.2195 },
    zoom: 13,
    mapTypeId: "roadmap",
  });
    // Create the search box and link it to the UI element.
    vm.input = document.getElementById("pac-input1");
    document.getElementById("pac-input1").value =  vm.modal.editClient.address ;
    document.getElementById("pac-input1").placeholder = "";
    vm.searchBox = new google.maps.places.SearchBox(vm.input);
    // Bias the SearchBox results towards current map's viewport.
    map.addListener("bounds_changed", () => {
      vm.searchBox.setBounds(map.getBounds());
    });

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

      vm.lat = vm.modal.geoloc.lat;
      vm.long = vm.modal.geoloc.long;
      vm.address1 = vm.modal.geoloc.address;
      vm.modal.geoloc.coordinates = '';
      vm.coordinated_data = [];
      vm.geoLocAdd();

      // axios
      //   .post(`/api/updateproperties/1`, {
      //     lat: vm.lat,
      //     long: vm.long,
      //     address: vm.address1,
      //     coordinates: "",
      //   })
      //   .then((res) => {
      //     vm.$swal({
      //       icon: "success",
      //       title: "Successfully added!",
      //       showConfirmButton: false,
      //       timer: 2000,
      //     });
      //     this.modal.geoLoc = false;
      //   })
      //   .catch((err) => {
      //     vm.$swal({
      //       icon: "error",
      //       title: err.response.data,
      //       showConfirmButton: false,
      //       timer: 1500,
      //     });
      //   });
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
    chooseDateRange() {
      let vm = this;
      switch (vm.options.chooseDateRange) {
        case "today":
          vm.options.beginDate = moment().format("MM/DD/YYYY");
          vm.options.endDate = moment().format("MM/DD/YYYY");
          break;
        case "tomorrow":
          vm.options.beginDate = moment().add(1, "day").format("MM/DD/YYYY");
          vm.options.endDate = moment().add(1, "day").format("MM/DD/YYYY");
          break;
        case "this-week":
          vm.options.beginDate = moment()
            .startOf("isoWeek")
            .format("MM/DD/YYYY");
          vm.options.endDate = moment().endOf("isoWeek").format("MM/DD/YYYY");
          break;
        case "last-week":
          vm.options.beginDate = moment()
            .subtract(1, "week")
            .startOf("isoWeek")
            .format("MM/DD/YYYY");
          vm.options.endDate = moment()
            .subtract(1, "week")
            .endOf("isoWeek")
            .format("MM/DD/YYYY");
          break;
        case "next-week":
          vm.options.beginDate = moment()
            .add(1, "week")
            .startOf("isoWeek")
            .format("MM/DD/YYYY");
          vm.options.endDate = moment()
            .add(1, "week")
            .endOf("isoWeek")
            .format("MM/DD/YYYY");
          break;
        case "this-month":
          vm.options.beginDate = moment().startOf("month").format("MM/DD/YYYY");
          vm.options.endDate = moment().endOf("month").format("MM/DD/YYYY");
          break;
        case "last-month":
          vm.options.beginDate = moment()
            .subtract(1, "month")
            .startOf("month")
            .format("MM/DD/YYYY");
          vm.options.endDate = moment()
            .subtract(1, "month")
            .endOf("month")
            .format("MM/DD/YYYY");
          break;
        case "next-month":
          vm.options.beginDate = moment()
            .add(1, "month")
            .startOf("month")
            .format("MM/DD/YYYY");
          vm.options.endDate = moment()
            .add(1, "month")
            .endOf("month")
            .format("MM/DD/YYYY");
          break;
        case "this-quarter":
        case "last-quarter":
        case "this-year":
        case "year-to-date":
        case "after-today":
        case "before-today":
        case "all-dates":
          // vm.options.beginDate = moment().quarter().format('MM/DD/YYYY')
          // vm.options.endDate = moment().quarter().format('MM/DD/YYYY')
          alert("still in progress");
        default:
          vm.options.beginDate = "";
          vm.options.endDate = "";
      }
    },
    // select all checkbox
    selectAll: function () {
      this.checkedNames = [];
      this.allSelected = !this.allSelected;
      if (this.allSelected) {
        for (let user in this.index.employees) {
          this.checkedNames.push(this.index.employees[user]);
          this.allSelected = true;
        }
      } else {
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
      this.modal.geoLoc = false;
    },

    // Select  check message
    SendMessageCheck(func) {
      let arr = Object.keys(this.checkedNames).map((k) => this.checkedNames[k]);
      if (this.checkedNames.length != 0 && func == "SendMessage") {
        this.modal.SendMessage = true;
      } else if (this.checkedNames.length != 0 && func == "SendReminders") {
        this.modal.SendReminders = true;
      } else if (this.checkedNames.length != 0 && func == "SendSignIn") {
        this.SendSignIn(this.checkedNames);
      } else if (this.checkedNames.length != 0 && func == "ExportData") {
        this.ExportData(this.checkedNames);
      } else if (this.checkedNames.length != 0 && func == "BulkEdit") {
        if (this.checkedNames.length == 1) {
          this.openModal("EditEmployee", this.checkedNames[0]);
        } else {
          this.openModal("BulkEditEmp");
        }
      } else if (
        this.checkedNames.length == 0 &&
        (func == "ExportData" || func == "BulkEdit")
      ) {
        this.$alert("Please Select at least one row");
      } else {
        this.$alert(
          "First click the checkboxes to the left of each name to select Watchers."
        );
      }
    },
    // Export data
    ExportData(names) {
      let vm = this;
      vm.copyGroupsToClipboard();
    },
    copyGroupsToClipboard() {
      removeAllSelections();
      selectContent(this.$refs.content);
      // copySelection()
      this.$alert(
        "Selected Watchers copied to the clipboard so you can paste in another software system."
      );
    },
    // Send Message
    SendMessageToAll() {
      let vm = this;

      vm.isLoader = true;
      vm.modal.SendMessageToAll.id = vm.bulkids;
      axios
        .post(
          "/api/employees/send-message",
          Object.assign(vm.modal.SendMessageToAll)
        )
        .then((res) => {
          vm.option = {};
          vm.$swal({
            closeOnClickOutside: false,
            icon: "success",
            title: `Message Sent `,
            showConfirmButton: false,
            timer: 5000,
          });
          vm.modal.SendMessage = false;
          vm.isLoader = false;
          vm.$refs.sendmessage.reset();
        })
        .catch((err) => {
          vm.$swal({
            icon: "error",
            title: err.response.data,
            showConfirmButton: false,
            timer: 3000,
          });
          vm.isLoader = false;
        });
    },
    //Send Reminder
    SendReminderToAll() {
      let vm = this;

      vm.isLoader = true;
      vm.options.id = vm.bulkids;
      axios
        .post("/api/employees/send-reminder", Object.assign(vm.options))
        .then((res) => {
          vm.option = {};
          vm.SendReminderSuccessData = res.data;
          this.modal.SendReminderSuccess = true;
          vm.modal.SendReminders = false;
          vm.isLoader = false;
          vm.$refs.frmSendReminder.reset();
        });
    },
    DoMessage() {
      let vm = this;
      vm.modal.SendMessage = false;
    },
    // Sign In instructions
    sentIns() {
      let vm = this;
      vm.modal.SignIn = false;
      vm.modal.SignInSent = true;
      vm.$confirm.hide();
    },
    SendSignIn(names) {
      let vm = this;
      let namelength = names.length;

      let ids = [];
      for (let i = 0; i <= names.length - 1; i++) {
        {
          ids[i] = names[i].employee_id;
        }
      }
      vm.$confirm({
        message: `Are you sure you want to send sign in instructions to ${namelength} Watchers `,
        button: {
          no: "No",
          yes: "Yes",
        },
        /**
         * Callback Function
         * @param {Boolean} confirm
         */
        callback: (confirm) => {
          if (confirm) {
            vm.sendEmailToSelectedEmp(ids);
          }
        },
      });
    },
    showMsgBoxThree() {
      let vm = this;
      vm.modal.SignIn = false;
      vm.modal.SignInSent = false;
    },
    showMsgBoxOne() {
      let vm = this;
      vm.$confirm({
        message: `Send individual sign in instructions to all Watchers who have email addresses entered but who have not already signed in.`,
        button: {
          no: "No",
          yes: "Yes",
        },
        /**
         * Callback Function
         * @param {Boolean} confirm
         */
        callback: (confirm) => {
          if (confirm) {
            vm.modal.SignInSent = false;
            vm.modal.SignIn = false;
            vm.sendEmailToAll();
          }
        },
      });
    },
    isNumberOnly(evt) {
      evt = evt ? evt : window.event;
      let charCode = evt.which ? evt.which : evt.keyCode;
      // if decimal point (.) is allowed, set this to charCode !== 46
      // otherwise, set this to charCode !== 46
      // if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode == 46) {
      if (
        (charCode > 31 && (charCode < 48 || charCode > 57)) ||
        charCode == 46 ||
        charCode == 9 ||
        charCode == 16
      ) {
        evt.preventDefault();
      } else {
        return true;
      }
    },
    isNumberOnlyAndDecimalPoint(evt) {
      evt = evt ? evt : window.event;
      let charCode = evt.which ? evt.which : evt.keyCode;
      // if decimal point (.) is allowed, set this to charCode !== 46
      // otherwise, set this to charCode !== 46
      if (
        charCode > 31 &&
        (charCode < 48 || charCode > 57) &&
        (charCode !== 46 ||
          this.modal.addEmployee.pay_rate.split(".").length === 2 ||
          this.modal.reqEditEmployee.pay_rate.split(".").length === 2)
      ) {
        evt.preventDefault();
      } else {
        return true;
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
      // var divmap =   this.$refs.createprop;
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
          //   vm.modal.geoloc.long = place.geometry.viewport.La.i;
          //  vm.modal.geoloc.lat = place.geometry.viewport.Ra.i;
          let viewport1 = place.geometry.viewport;
           console.log( 'place.geometry.viewport1' ,  viewport1 )
           Object.entries(viewport1).forEach(([key, value], index) => {
              console.log( 'place.geometry.value' ,  value )
             
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
            console.log( 'place.geometry.propertyaddress' ,   vm.modal.geoloc.propertyaddress )
           vm.geoLocAdd();
      } else {
        console.log( 'place.geometry.location23' ,  place.geometry.location )
        bounds.extend(place.geometry.location);
      }
      if(place.geometry.location){
         console.log( 'place.geometry.location' ,  place.geometry.location )
          console.log( vm.modal.geoloc.lat ,  vm.modal.geoloc.long )
      }
    });
    map.fitBounds(bounds);
  });


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
            console.log(intersects);
            if (intersects && intersects.length) {
              console.log("Done");
            } else {
               console.log("Done");
            }
          }
        );
      }
    },
    geoLocMethod() {
      let vm = this;
      var mapOptions = {
        zoom: 16,
        center: new google.maps.LatLng(vm.properties.property.lat, vm.properties.property.long),
      };
      if (
        vm.properties.property.coordinates != "" ||
        vm.properties.property.coordinates == null
      ) {
        console.log(vm.properties.property.coordinates, 'coordinates');
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

      var map = new google.maps.Map(document.getElementById("map"), mapOptions);
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
            // if (intersects && intersects.length) {
            //   alert("Done");
            // } else {
            //   alert("Done");
            // }
          }
        );
      }
    },

    /**
     * Search for
     * @return {[type]} [description]
     */
    search() {
      let vm = this;

      if (vm.searchKeyword == " ") return false;
      if (vm.searchTimer) {
        clearTimeout(vm.searchTimer);
        vm.searchTimer = null;
      }
      vm.searchTimer = setTimeout(() => {
        vm.isLoader = true;
        axios
          .get(
            `/api/employee-search?kw=${vm.searchKeyword}&position_id=${vm.index.selectPosition}&has_email=both`
          )
          .then((res) => {
            vm.index.employees = res.data;
            vm.isLoader = false;
          })
          .catch((err) => {
            vm.$swal({
              icon: "error",
              title: err.response.data,
              showConfirmButton: false,
              timer: 1500,
            });
            vm.isLoader = false;
          });
      }, 500);
    },

    /**
     * Open modal
     *
     * @param  String modal
     */
    async openModal(modal, data) {
      let vm = this;
      switch (modal) {
         case 'editClientModal':
          // this.inject_material_fonts_and_icons_into_header();
          vm.modal.editClient = data
          vm.modal.editclient = true;
         
         
          break
        case "AddNewEmployee":
          vm.modal.addNewEmployee = true;
          vm.modal.addEmployee = {};
          vm.$refs.frmAddEmployee.reset();
          vm.modal.addEmployee.priority_group = 1;
          vm.indexPosition("modal-position");
          break;
        case "geoLocCreate":
          vm.createpropertymodal = true;
          vm.isLoader = true;
          vm.geoLocAdd();
          vm.isLoader = false;
          break;
        case "geoLoc":
          vm.destroystyle();
          vm.modal.geoLoc = true;

       
          // vm.geoLocMethod();
           vm.geoLocAdd();

        
          break;
        case "AddNewPosition":
          // vm.modal.addNewEmployee = false
          vm.modal.addEditPositions = true;
          break;
        case "AddEditPositions":
          vm.modal.addEditPositions = true;
          vm.indexPosition("modal-position");
          vm.trashedPosition();
          break;
        case "SignIn":
          vm.modal.SignIn = true;
          break;
        case "BulkEditEmp":
          vm.modal.BulkEditEmp = true;
          break;
        case "SignInSent":
          vm.modal.SignIn = false;
          vm.modal.SignInSent = true;
          break;
        case "EditDeletePositions":
          vm.modal.addEditPositions = false; // close the parent modal
          vm.modal.editDeletePositions = true;
          break;
        case "EditPosition":
          vm.modal.showEditPosition = true;
          vm.modal.reqEditPositionDisplay = data.position;
          vm.modal.reqEditPosition = {
            id: data.id,
            position: data.position,
          };
          break;
      }
    },
    /**
     * List all Properties
     */
    Properties(id) {
      let vm = this;
      vm.isLoader = true;
      axios.get(`/api/singleView/${id}`).then((res) => {
        vm.properties = res.data;
        vm.isLoader = false;
        // vm.geoLocMethod();
      });
    },

    /**
     * List all Client
     */
    indexEmployee(position = "") {
      let vm = this;

      vm.isLoader = true;
      axios.get(`/api/client`).then((res) => {
        vm.index.client = res.data;
        vm.isLoader = false;
      });
    },
    // Send Mail To New Employee Added
    sendMailToNewEmp() {
      let vm = this;
      let ids = [];
      ids[0] = vm.index.employees[0].employee_id;
      axios
        .post("/api/employee/send-signin-instruction-to-employee", { ids })
        .then((res) => {
          vm.$alert("Sign in Instructions have been sent to new Watchers!");
        })
        .catch((err) => {
          console.log("send email to selected error");
        });
    },

    /**
     * Filter result via position id
     *
     * @param  int position
     */
    filterResultViaPosition(position) {
      this.indexEmployee(position);
    },

    /**
     * Load positions
     *
     * @param  String requestedBy [part the API is being requested]
     */
    async indexPosition(requestedBy) {
      let vm = this;
      let position = await axios.get("/api/positions");

      switch (requestedBy) {
        case "index-position":
          vm.index.positions = position.data;
          break;
        case "modal-position":
          vm.modal.positions = position.data;
          break;
      }
    },

    /**
     * Send Email to all
     *
     * @param  String requestedBy [part the API is being requested]
     */
    async sendEmailToAll() {
      let vm = this;
      await axios
        .post("/api/employee/send-signin-instruction-to-all")
        .then((res) => {
          vm.modal.SignInSuccess = res.data;
          vm.sentIns();
        })
        .catch((err) => {
          console.log("email error");
        });
    },
    /**
     * Send Email to selected employee
     *
     * @param  String requestedBy [part the API is being requested]
     */
    async sendEmailToSelectedEmp(ids) {
      let vm = this;
      await axios
        .post("/api/employee/send-signin-instruction-to-employee", { ids })
        .then((res) => {
          vm.$alert(
            "Sign in Instructions have been sent to selected Watchers!"
          );
        })
        .catch((err) => {
          console.log("send email to selected error");
        });
    },

    /**
     * Load position(s) of an employee
     *
     * @param  int empId
     */
    async showEmployeePosition(empId) {
      let vm = this;
      // let employeePositions = await axios.get()
    },

    /*
      | =======================================
      |  [ Modal ] - Add Employee
      | =======================================
      */
    checkAllPositions(bool) {
      let vm = this;

      let selected = [];

      if (bool) {
        vm.modal.positions.forEach((pos) => selected.push(pos.id));
        vm.modal.selectedPositions = selected;
        vm.modal.checkBoxOption.selectAll = true;
        vm.modal.checkBoxOption.unSelectAll = false;
      } else {
        vm.modal.selectedPositions = [];
        vm.modal.checkBoxOption.selectAll = false;
        vm.modal.checkBoxOption.unSelectAll = true;
      }
    },
    storeEmployee() {
      let vm = this;

      if (Object.keys(vm.modal.selectedPositions).length === 0) {
        vm.$swal({
          icon: "error",
          title: "At least select a position",
          showConfirmButton: false,
          timer: 1500,
        });
        return false;
      }

      vm.isLoader = true;

      axios
        .post(
          "/api/employees",
          Object.assign(vm.modal.addEmployee, {
            positions: vm.modal.selectedPositions,
          })
        )
        .then((res) => {
          vm.indexEmployee();
          // if(vm.modal.addEmployee.SendEmail){
          //    vm.sendMailToNewEmp()
          // }
          vm.modal.addEmployee = {};
          vm.$swal({
            icon: "success",
            title: "Successfully Added!",
            showConfirmButton: false,
            timer: 1500,
          });
          vm.modal.addNewEmployee = false;
          vm.isLoader = false;
          // vm.$refs.frmAddEmployee.reset();
        })
        .catch((err) => {
          vm.$swal({
            icon: "error",
            title: err.response.data,
            showConfirmButton: false,
            timer: 1500,
          });
          vm.isLoader = false;
        });
    },
    storeEmployee1() {
      let vm = this;
      vm.isLoader = true
      vm.modal.editClient.address = document.getElementById('pac-input1').value
      var nameArr , country, city , combine;
      if(/[,\-]/.test(vm.modal.editClient.address)){
         nameArr = vm.modal.editClient.address.split(',');
         country = nameArr[nameArr.length - 1]
         city = nameArr[nameArr.length - 2]
         combine = city +',' +country
      }
      else{
        combine = vm.modal.editClient.address
      }
      if(vm.modal.editClient.password!=""){
       
          if(vm.modal.editClient.password.length<7){
            vm.isLoader = false
            vm.$swal({
              icon: "error",
              title: "Password must be greater than 6 characters",
              showConfirmButton: false,
              timer: 1500,
            });
            return false;

          }else if(vm.modal.editClient.password!=vm.modal.editClient.confirmpassword){
             vm.isLoader = false
             vm.$swal({
              icon: "error",
              title: "Confirm password must be same as password",
              showConfirmButton: false,
              timer: 1500,
            });
            return false;
          }
      }
      vm.modal.editClient.state = combine;
      const mypostparameters= new FormData();
      mypostparameters.append('client_image', vm.modal.editClient.client_image);
      mypostparameters.append('client_background_logo', vm.modal.editClient.client_background_logo);
     	mypostparameters.append('address', vm.modal.editClient.address);
     	mypostparameters.append('clientname', vm.modal.editClient.clientname);
     	mypostparameters.append('company', vm.modal.editClient.company);
     	mypostparameters.append('contactname', vm.modal.editClient.contactname);
     	mypostparameters.append('email', vm.modal.editClient.email);
      mypostparameters.append('police_email', vm.modal.editClient.police_email);
      mypostparameters.append('zip', vm.modal.editClient.zip);
     	mypostparameters.append('phone', vm.modal.editClient.phone);
     	mypostparameters.append('id', vm.modal.editClient.id);
     	mypostparameters.append('website', vm.modal.editClient.website);
      mypostparameters.append('password', vm.modal.editClient.password);
      mypostparameters.append('job_title', vm.modal.editClient.job_title);
     	mypostparameters.append('state', vm.modal.editClient.state);
			if(this.Images.client_background_logo){
				for( var i = 0; i <  this.Images.client_background_logo.length; i++ ){
				let file =  this.Images.client_background_logo[i];
				mypostparameters.set('client_background_logo', file);
				}
			}
			if(this.Images.client_image){
				for( var i = 0; i <  this.Images.client_image.length; i++ ){
				let file =  this.Images.client_image[i];
				mypostparameters.set('client_image', file);
				}
			}
      axios
        .post('/api/updateclient',mypostparameters )
        .then(res => {
          if(res.data.status){
          vm.indexEmployee()
          vm.$swal({
            icon: 'success',
            title: 'Successfully Updated!',
            showConfirmButton: false,
            timer: 1500
          })
          vm.readonlyprofile = true
          vm.modal.createclient = false
          vm.isLoader = false
          vm.$refs.frmcreateclient.reset();
          vm.Properties(this.userid)
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
    },
    propertyModel() {
      let vm = this;
      vm.isLoader = true

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

      if(!this.updateprop){
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
          vm.$swal({
            icon: 'success',
            title: 'Successfully Updated!',
            showConfirmButton: false,
            timer: 1500
          })
          vm.modal.geoLoc = false;
          // vm.$refs.frmpropertymodel.reset();
           vm.modal.geoloc.coordinates = '';
           vm.modal.geoloc.propertyaddress = '';
           vm.modal.geoloc.state = '';
           vm.modal.geoloc.propertyname = '';
           vm.modal.geoloc.lat = 30.709579;
           vm.modal.geoloc.long = 76.689343;
           vm.input = '';
           vm.searchBox = '';
           vm.isLoader = false;
           setTimeout(() => {
            //  location.reload();
              vm.Properties(vm.prop_id);
           }, 2000);
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
      }
      else{
        
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
          this.modal.geoLoc = false;
           vm.isLoader = false
           this.updateprop = false;
            // vm.$refs.frmpropertymodel.reset();
             vm.modal.geoloc.propertyname = '' ,
          vm.modal.geoloc.propertyaddress = ''
          vm.modal.geoloc.state = ''
          vm.modal.geoloc.coordinates = ''
             vm.Properties(vm.prop_id);
       
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
      }

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
      let vm = this;
      vm.isLoader = true;
      try {
        let resEmployee = await axios.get(
          `/api/employee/${vm.modal.reqEditEmployee.id}/prevnextrecord`
        );
        // console.log('resEmployee', resEmployee.data)

        // Map employee's position to checkbox
        let arrPositionIds = [];

        if ("prev" === value) {
          vm.modal.reqEditEmployee = resEmployee.data.prev;
          vm.modal.getEmployeeRecord.prev.show =
            resEmployee.data.prev == null ||
            resEmployee.data.prev.id == resEmployee.data.first.id
              ? false
              : true;
          vm.modal.getEmployeeRecord.next.show = true;
          vm.modal.fullname = `${resEmployee.data.prev.firstname} ${resEmployee.data.prev.lastname}`;

          resEmployee.data.prev.position.forEach((val) =>
            arrPositionIds.push(val.position_id)
          );
          vm.modal.selectedPositions = arrPositionIds;
        } else {
          vm.modal.reqEditEmployee = resEmployee.data.next;
          vm.modal.getEmployeeRecord.prev.show = true;
          vm.modal.getEmployeeRecord.next.show =
            resEmployee.data.next == null ||
            resEmployee.data.next.id == resEmployee.data.last.id
              ? false
              : true;
          vm.modal.fullname = `${resEmployee.data.next.firstname} ${resEmployee.data.next.lastname}`;

          resEmployee.data.next.position.forEach((val) =>
            arrPositionIds.push(val.position_id)
          );
          vm.modal.selectedPositions = arrPositionIds;
        }
        vm.isLoader = false;
      } catch (e) {
        console.log("error", e);
        vm.isLoader = false;
      }
    },

    /**
     * Update employee
     */
    async updateEmployee() {
      let vm = this;
      if (Object.keys(vm.modal.selectedPositions).length === 0) {
        vm.$swal({
          icon: "error",
          title: "At least select a position",
          showConfirmButton: false,
          timer: 1500,
        });
        return false;
      }

      vm.isLoader = true;
      try {
        await axios.patch(
          `/api/employees/${vm.modal.reqEditEmployee.id}`,
          Object.assign(vm.modal.reqEditEmployee, {
            positions: vm.modal.selectedPositions,
          })
        );
        vm.indexEmployee();
        vm.$swal({
          icon: "success",
          title: "Successfully updated!",
          showConfirmButton: false,
          timer: 1500,
        });
        vm.isLoader = false;
        vm.modal.editEmployee = false;
      } catch (e) {
        console.log("Error", e);
        vm.isLoader = false;
      }
    },

    /**
    /**
     * Bulk Update employee
     */
    async updateEmployeeBulk() {
      let vm = this;
      vm.isLoader = true;
      vm.modal.reqEditEmployeeBulk.ids = vm.BulkIDS;
      try {
        await axios.post(
          `/api/employees/employee-bulk-edit`,
          Object.assign(vm.modal.reqEditEmployeeBulk)
        );
        // vm.indexEmployee()
        vm.$swal({
          icon: "success",
          title: "Successfully updated!",
          showConfirmButton: false,
          timer: 1500,
        });
        vm.isLoader = false;
        vm.indexEmployee();
        vm.modal.BulkEditEmp = false;
        vm.allSelected = false;
      } catch (e) {
        console.log("Error", e);
        vm.isLoader = false;
      }
    },

    /**
     * Update employee then proceed to next
     */
    async updateAndProceedNext() {
      let vm = this;
      if (Object.keys(vm.modal.selectedPositions).length === 0) {
        vm.$swal({
          icon: "error",
          title: "At least select a position",
          showConfirmButton: false,
          timer: 1500,
        });
        return false;
      }

      vm.isLoader = true;
      try {
        await axios.patch(
          `/api/employees/${vm.modal.reqEditEmployee.id}`,
          Object.assign(vm.modal.reqEditEmployee, {
            positions: vm.modal.selectedPositions,
          })
        );
        vm.indexEmployee();
        vm.$swal({
          icon: "success",
          title: "Successfully updated!",
          showConfirmButton: false,
          timer: 1500,
        });
        vm.getPrevNextEmployeeDetail("next");
        // vm.isLoader = false
      } catch (e) {
        console.log("Error", e);
        vm.isLoader = false;
      }
    },

    /**
     * Remove employee
     */
    async removeEmployee() {
      let vm = this;
      try {
        if (confirm("Are you sure you want to remove this user?")) {
          vm.isLoader = true;
          await axios.delete(`/api/employees/${vm.modal.reqEditEmployee.id}`);
          vm.indexEmployee();
          vm.$swal({
            icon: "success",
            title: "Successfully removed!",
            showConfirmButton: false,
            timer: 1500,
          });
          vm.isLoader = false;
          vm.modal.editEmployee = false;
        }
      } catch (e) {
        console.log("error", e);
      }
    },

    /*
      | =======================================
      |  [ Modal ] - Add/Edit Positions
      | =======================================
      */
    trashedPosition() {
      axios
        .get("/api/positions-trashed")
        .then((res) => (this.modal.trashedPositions = res.data));
    },
    addPosition() {
      let vm = this;
      let inputData = {
        position: vm.modal.addPosition.position,
      };

      axios
        .post("/api/positions", inputData)
        .then((res) => {
          vm.$swal({
            icon: "success",
            title: "Successfully added!",
            showConfirmButton: false,
            timer: 2000,
          });
          vm.modal.addPosition.position = "";
          vm.modal.positions.splice(res.data.id, 0, res.data);
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
    /**
     * Edit specific position
     */
    editPosition() {
      let vm = this;
      vm.isLoader = true;
      axios
        .patch(`/api/positions/${vm.modal.reqEditPosition.id}`, {
          position: vm.modal.reqEditPosition.position,
        })
        .then((res) => {
          vm.$swal({
            icon: "success",
            title: "Successfully updated!",
            showConfirmButton: false,
            timer: 1500,
          });
          vm.modal.reqEditPosition = {};
          vm.modal.showEditPosition = false;
          vm.indexPosition("modal-position");
          vm.isLoader = false;
        })
        .catch((err) => {
          vm.$swal({
            icon: "error",
            title: err.response.data,
            showConfirmButton: false,
            timer: 1500,
          });
          console.log(err.response.data);
          vm.isLoader = false;
        });
    },

    /**
     * Restore position which has been removed
     *
     * @param  Int positionId
     * @param  Int index
     */
    restorePosition(positionId, index) {
      let vm = this;
      axios
        .delete(`/api/positions-restore/${positionId}`)
        .then((res) => {
          vm.modal.trashedPositions.splice(index, 1);
          vm.indexPosition();
          vm.$swal({
            icon: "success",
            title: "Successfully restored!",
            showConfirmButton: false,
            timer: 1500,
          });
        })
        .catch((err) => {
          console.log("error", err.response);
        });
    },

    /*
      | =======================================
      |  [ Tabs ]
      |  @Note - create component for this
      | =======================================
      */
    isActive(menuItem) {
      return this.activeItem === menuItem;
    },
    setActive(menuItem) {
      this.indexPosition("modal-position");
      this.activeItem = menuItem;
    },

    /**
     * Remove Position
     *
     * @param  Ojb data
     * @param  Int index
     */
    removePosition(data, index) {
      let vm = this;
      if (confirm(`Are you sure you want to remove ${data.position}?`)) {
        axios
          .delete(`/api/positions/${data.id}`)
          .then(() => {
            vm.modal.positions.splice(index, 1);
            vm.$swal({
              icon: "success",
              title: "Successfully removed!",
              showConfirmButton: false,
              timer: 1500,
            });
          })
          .catch((err) => {
            vm.$swal({
              icon: "error",
              title: err.response.data.message,
              showConfirmButton: false,
              timer: 1500,
            });
          });
      }
    },

    /**
     * Save sorted position [draggable]
     */
    async saveSortedPosition() {
      let vm = this;
      let arr = [];

      vm.isLoader = true;

      vm.modal.positions.forEach((val, index) => {
        arr.push({
          position_id: val.id,
          index: index,
        });
      });

      try {
        await axios.patch("/api/position-sort", { data: arr });
        vm.$swal({
          icon: "success",
          title: "Sorted!",
          showConfirmButton: false,
          timer: 2000,
        });
        vm.indexPosition("modal-position");
        vm.indexPosition("index-position");
        vm.isLoader = false;
      } catch (e) {
        console.log(e);
        vm.isLoader = false;
      }
    },

    /**
     * Sort position alphabetically
     */
    sortAlphabetically() {
      let vm = this;
      let sortPosition = this.modal.positions.map((val) => {
        return { id: val.id, index: val.index, position: val.position };
      });
      // Conditions
      //  - If return -1, it will place the first item before the second.
      //  - If return 1, it will place the second item before the first.
      //  - If return 0, it will leave them unchanged.
      sortPosition.sort(function (a, b) {
        if (a.position > b.position) return 1;
        if (a.position < b.position) return -1;
      });
      vm.modal.positions = sortPosition;
    },

    /**
     * Remove this
     */
    testMsg(msg) {
      this.$swal({
        icon: "error",
        title: msg,
        showConfirmButton: false,
        timer: 1500,
      });
    },
  },
};
</script>

<style lang="scss" scoped>

.info{
  img{
    width: 6rem;
    height: 6rem;
    border: 1px solid;
  }
}
table td:first-child {
    background: none !important;
}
#map {
  width: 100%;
  height: 300px;
}
.b-avatar-img-class{
 
    width: 6rem;
    height: 6rem;
    border-radius: 50%;
 
}
.b-avatar-logo-class{
 
    width: 100% !important;
    height: 6rem;
 
}
.b-avatar-img {
  img{
 
    width: 6rem;
    height: 6rem;
    border-radius: 50%;
 
}
}
.b-avatar{
     border: 1px solid;
    border-radius: 50%;
  .bi-person-fill{
    width: 6rem;
    height: 6rem;
    border: 1px solid;
    border-radius: 50%;
  }
}
.generate-password{
  color: #302369;
    border: 2px solid;
    font-weight: bold;
    &:hover{
      background-color: #302369;
      color: #ffffff;
    }
}
.h6-heading{
  text-align: left;
    font: normal normal normal 20px Source Sans Pro;
    letter-spacing: 0px;
    color: #302369;
    opacity: 1;
}
.h6-label{
 
    text-align: left;
    font: normal normal normal 20px Source Sans Pro;
    letter-spacing: 0px;
    color: #363636;
    opacity: 1;
    &.textcap{
       text-transform: capitalize;
    }
}
.clientview-table{
  .table-width-full{
    width: 100%;
    h1{
      font: normal normal normal 28px/40px Montserrat;
      font-family: 'Source Sans Pro';
      color: #302369;
      font-weight: 600;
    }
    .btn-outline-primary{
      margin-top: 1rem;
      margin-bottom: 1rem;
      color: #007bff;
      /* display: inline-block; */
      /* font-weight: 400; */
      height: 30px;
      /* color: #212529; */
      text-align: center;
      vertical-align: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
      background-color: transparent;
      border: 1px solid #007bff;
      padding: 0px 30px;
      font-size: 16px;
      line-height: 0;
      border-radius: 0.25rem;
    }
    .btn-outline-primary:hover {
      color: #fff;
      background-color: #007bff;
      border-color: #007bff;
  }
  }
  table{
   
    thead{
      tr{
        height: 59px;
        background: #302369 0% 0% no-repeat padding-box;
        color: #FFFFFF;
        opacity: 1;
        font-family: 'Open Sans';
        font-size: 15px;
        th{
          &:first-child{
            border-top-left-radius: 20px;
          }
          &:last-child{
            border-top-right-radius: 20px;
          }
          font: normal normal 400 18px/41px Open Sans;
        }
      }
    }
    tbody{
      border: 1px solid #DEDEDE !important;
      tr{
        background: none !important;
        height: 50px;
          .active{
            text-transform: capitalize;
            color: #3B86FF;
          }
          .inactive{
            text-transform: capitalize;
            color: #707070;
          }
          td{
           
          }
      }
    }
  }
}
@import "../../sass/employees";
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
      td{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
        padding: 1px 0px 1px 25px;
      }
    }
  }
}
</style>