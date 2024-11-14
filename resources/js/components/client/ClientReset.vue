<template>
  
    <div class="h-screen flex items-center client-login client-reset" id="app"> 
        <div class="mx-auto mt-5" style="width: 450px">
            <b-form @submit.prevent="submit" class="bg-white px-16 pt-32 pb-5">
            <!-- <form class="bg-white px-16 pt-20 pb-5" action="client-login" method="POST"> -->
             <input type="hidden" name="_token" v-bind:value="csrf">	

                <img src="../../../images/logo.png" class="mx-auto mb-3" alt="" style="margin-top: -165px;" >
            
                <h2 class="text-center text-3xl font-bold leading-none mb-8">RESET PASSWORD!</h2>
                
              

                <div class="mb-4">
                    <label class="block text-sm text-center" for="username">Enter your email. We’ll send you a link to reset your password.</label>
                    <!-- <input class="text-sm appearance-none w-full py-3 px-4 leading-tight focus:outline-none rounded-full" id="email" type="email" placeholder="Enter e-mail" name="email"  required autofocus> -->
                    <b-form-group id="email-group" class="">
                        <b-form-input
                        v-model="$v.email.$model"
                        :state="!$v.email.$invalid"
                        id="email"
                        placeholder="Email"
                        type="email"
                        class="text-sm text-center appearance-none w-full py-3 px-4 leading-tight focus:outline-none "
                        />
                        <b-form-invalid-feedback v-show="$v.email.$invalid">
                        <div class="error text-center" v-if="!$v.email.required"
                            >Email is required</div
                        >
                        <div class="error" v-if="!$v.email.email">Not a valid email</div>
                        </b-form-invalid-feedback>
                    </b-form-group>
                      <b-alert
                        :show="hasError"
                        variant="danger"
                        @dismissed="hasError = false" 
                        dismissible
                        class="alert alert-dismissible alert-danger"
                        >
                        {{this.errormessage}}
                    </b-alert>
                </div>
                <div class="text-center">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 rounded-full focus:outline-none focus:shadow-outline w-full mb-8" type="submit">RESET PASSWORD</button>
                  
                    <b> Remember Password? </b><a class="" style="color: #237BDF;" href="/client_login">Log In </a>
                </div>
               
            <!-- </form> -->
            </b-form>
        </div>

    </div>


</template>

<style lang="scss" scoped>
	@import '../../../sass/client/dashboard'; 
   
</style>
<script>
  import { validationMixin } from 'vuelidate'
  import { required, email, minLength } from 'vuelidate/lib/validators'
  import axios from 'axios'
export default {  
     name: 'ClientLogin',
    mixins: [validationMixin],
	  data(){
          return {
            //csrf token
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            email: '',
            // password: '', 
            hasError: false,
            errormessage: ''
       }
    },
    validations: {
      email: { required, email },
    },
	created() {
    this.inject_material_fonts_and_icons_into_header();
	  },
	  mounted(){
		  document.getElementById('email').focus()
			// document.getElementById('eyeOpen').style.display = 'none'
			
			
	  },
	  methods: {
                submit() {
                    let vm = this;
                    this.$v.$touch()
                    if (!this.$v.$invalid) {
                    axios.post('/api/client-reset', {
                            email: this.email,
                            // password: this.password
                        }).then(res => {
                            if(res.status){
                                // localStorage.setItem('user',JSON.stringify(res.data.data));
                                // vm.$router.push('/cd');
                                vm.$swal({
                                    icon: 'success',
                                    title: 'Your password has been reset, please check your email',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                vm.$router.push('/client_login');
                                vm.hasError = false
                                vm.errormessage = ''
                            }
                            else{
                                this.hasError = true
                                this.errormessage = res.message  
                            }
                            
                        })
                        .catch(err => {
                        this.hasError = true
                        })
                    }
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