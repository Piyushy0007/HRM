<template>
  
	<div class="bv-example-row mx-auto">
	<b-row>
		<b-col cols="2" >
			<ul class="justify-center mx-auto mt-3">
				<router-link  :to="{ name: 'cd' }" class="md:w-40 xl:w-64 mr-1 mb-3 mt-3 ">				
						<img style="height:50px; margin-left:65px; margin-top:30px" src="/images/logo.png" alt="Eyewitness" class="">
          		</router-link>
          		<router-link tag="li" :to="{ name: 'schedules' }"  class="flex mr-1"> 
				 	<div class="leftindex">
		                <div class="leftimage">
		                    <img src="/images/build.png" alt="" v-if="$route.path == '/schedules/' || $route.path == '/schedules'">
		                    <img src="/images/propertty_unsel.png" alt="" v-if="$route.path !== '/schedules/' && $route.path !== '/schedules'">
		                </div>
		                <div class="lefttext ">
		                	<a href="#target1"  id='target1' class='target' v-if="$route.path !== '/schedules/' && $route.path !== '/schedules'"> Schedules</a>
		                    <a href="#target1"  id='target1' class='target' v-if="$route.path == '/schedules/' || $route.path == '/schedules'" style="color:#AD9E58 !important"> Schedules</a>
		                </div>
		            </div>
				</router-link>
				<router-link tag="li" :to="{ name: 'employees' }"  class="flex mr-1"> 
				 	<div class="leftindex">
		                <div class="leftimage">
		                    <img src="/images/build.png" alt="" v-if="$route.path == '/employees/' || $route.path == '/employees'">
		                    <img src="/images/propertty_unsel.png" alt="" v-if="$route.path !== '/employees/' && $route.path !== '/employees'">
		                </div>
		                <div class="lefttext ">
		                	<a href="#target1"  id='target1' class='target' v-if="$route.path !== '/employees/' && $route.path !== '/employees'"> Users</a>
		                    <a href="#target1"  id='target1' class='target' v-if="$route.path == '/employees/' || $route.path == '/employees'" style="color:#AD9E58 !important"> Users</a>
		                </div>
		            </div>
				</router-link>		
				<router-link tag="li" :to="{ name: 'creport' }" class="flex mr-1 ">
			    	<div class="leftindex">
		                <div class="leftimage">
		                    <img src="/images/file_sel.png" alt="" v-if="$route.path == '/cd/creport/' || $route.path == '/cd/creport/ticket'">
		                    <img src="/images/file.png" alt="" v-if="$route.path !== '/cd/creport/' && $route.path !== '/cd/creport/ticket'">
		                </div>
		                <div class="lefttext">
		                    <a href="#target2"  id='target2' class='target' v-if="$route.path == '/cd/creport/' || $route.path == '/cd/creport/ticket'" style="color:#AD9E58 !important"> Reports</a>
		                    
		                     <a href="#target2"  id='target2' class='target' v-if="$route.path !== '/cd/creport/' && $route.path !== '/cd/creport/ticket'"> Reports</a>
		                </div>
		            </div>
				</router-link>
				<router-link tag="li" :to="{ name: 'cmsg' }" class=" flex mr-1">
					<div class="leftindex">
		                <div class="leftimage1">
		                    <img  style="height: 25px; width: 23px; margin-top: 4px;" src="/images/msg_sel.png" alt="" v-if="$route.path == '/cd/msg'">
		                    <img  style="height: 25px; width: 23px; margin-top: 4px;" src="/images/message.png" alt="" v-if="$route.path !== '/cd/msg'">
		                </div>
		                <div class="lefttext">
		                    <a href="#target4"  id='target4' class='target' v-if="$route.path == '/cd/msg'" style="color:#AD9E58 !important"> Messages</a>
		                     <a href="#target4"  id='target4' class='target' v-if="$route.path !== '/cd/msg'"> Messages</a>
		                </div>   
		            </div>
				</router-link>
				</router-link>	
      </ul>
		</b-col>
		<b-col cols="10">
			<router-view></router-view>
		</b-col>
	</b-row>
	</div>
</template>

<style lang="scss" scoped>
	@import '../../../sass/client/dashboard';
	.leftindex {
            flex-direction: row;
            display: flex;
            margin-top: 40px;
            text-align: center;
            margin-left: 30px;
     } 
      a:active {
        color: #AD9E58  !important;
       }
        a:focus {
        	 color: #AD9E58  !important;
        } 
        :target {
          color: #AD9E58;
           }
    .lefttext {
            width: 69px;
            height: 27px;
            font-family: Poppins;
            /* color: #242424; */
            font-size: 18px;
            line-height: 27px;
            margin-left: 14px;
        }
        .leftimagemsg {
            margin-left: 30px;
            width: 26px;
            height: 33px;
        }
        .leftimageone{
        	height:100px;
        	width:158px;
        	margin-top:6px;
        }
        
        .lefttext a {
            color: #242424;
            text-decoration: none;
        }
        .mx-auto{
        	background-color:#FFFFFF;
        }
	.bv-example-row{
		ul{
			li{
				top: 116px;
				left: 0px;
				width: 20%;
                height: auto;
                background: #FFFFFF;   
				opacity: 1;
				width: 100%;
				
				a{
					margin: auto auto;
					
					vertical-align: middle;
					padding-top: 20px;
					
					letter-spacing: 0px;
					 color:black;
					  font-size:18px;
                     line-height: 27px;
					font-family: Poppins;
					
				flex-direction:row
					&:hover{
					
						text-decoration: none;
					}
				}
			}
		}
	}
	@media only screen and (max-width: 500px) {
	}
</style>
<script>
export default {
	mounted(){
		this.inject_material_fonts_and_icons_into_header();
	},
	created() {
    this.inject_material_fonts_and_icons_into_header();
	  },
	  methods: {
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
	  },
	    async beforeDestroy() {
			$(
			'link[href="https://unpkg.com/bootstrap/dist/css/bootstrap.min.css"]'
			).remove();
			$(
			'link[href="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css"]'
			).remove();
		},
}
</script>