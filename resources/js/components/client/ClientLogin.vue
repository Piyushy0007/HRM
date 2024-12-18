<template>
  
    <div class="h-screen flex items-center client-login" id="app"> 
        <div class="mx-auto mt-5" style="width: 450px">
            <b-form @submit.prevent="submit" class="bg-white px-16 pt-32 pb-5">
            <!-- <form class="bg-white px-16 pt-20 pb-5" action="client-login" method="POST"> -->
             <input type="hidden" name="_token" v-bind:value="csrf">	

                <img src="../../../images/logo.png" class="mx-auto mb-3" alt="" style="margin-top: -165px;" >
            
                <h2 class="text-center text-3xl font-bold leading-none mb-8">CLIENT LOGIN</h2>
                
                <!-- @if ( $errors->any() )
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ $errors->all()[0] }}</span>
                </div>
                @endif

                @if ( session('error') )
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline"> {{ session('error') }}</span>
                </div>
                @endif -->

                <div class="mb-4">
                    <label class="block text-sm px-4" for="username">Username or E-mail</label>
                    <!-- <input class="text-sm appearance-none w-full py-3 px-4 leading-tight focus:outline-none rounded-full" id="email" type="email" placeholder="Enter e-mail" name="email"  required autofocus> -->
                    <b-form-group id="email-group" class="px-4">
                        <b-form-input
                        v-model="$v.email.$model"
                        :state="!$v.email.$invalid"
                        id="email"
                        placeholder="Enter e-mail"
                        type="email"
                        class="text-sm appearance-none w-full py-3 px-4 leading-tight focus:outline-none rounded-full"
                        />
                        <b-form-invalid-feedback v-show="$v.email.$invalid">
                        <div class="error" v-if="!$v.email.required"
                            >Email is required</div
                        >
                        <div class="error" v-if="!$v.email.email">Not a valid email</div>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </div>

                <div class="mb-4">
                    <label class="block text-sm px-4" for="password">Password</label>
                    <div class="flex items-center relative">
                        <!-- <input class="text-sm appearance-none w-full py-3 pl-4 pr-10 leading-tight focus:outline-none rounded-full" id="password" type="password" placeholder="Enter password" name="password" required> -->
                        <b-form-group id="password-group" class="px-4" style="width: 100%;">
                            <b-form-input
                            v-model="$v.password.$model"
                            :state="!$v.password.$invalid"
                            id="password"
                            placeholder="Enter password"
                            type="password"
                            class="text-sm appearance-none w-full py-3 pl-4 pr-10 leading-tight focus:outline-none rounded-full"
                            />
                            <b-form-invalid-feedback v-show="$v.password.$invalid">
                            <div class="error" v-if="!$v.password.required">
                                Password is required
                            </div>
                            <div class="error" v-if="!$v.password.minLength">
                                Password must have at least
                                {{ $v.password.$params.minLength.min }} letters
                            </div>
                            </b-form-invalid-feedback>
                        </b-form-group>
                        <a href="#" @click="togglePasswordVisibility()" class="absolute right-0 " style="right: 32px; top: 14px;">
                            <font-awesome-icon icon="eye-slash" flip="horizontal" id="eyeClose" class="text-gray-500" />
                        </a>
                        <a href="#" @click="togglePasswordVisibility()" class="absolute right-0 " style="right: 32px; top: 14px;">
                            <font-awesome-icon icon="eye" flip="horizontal" id="eyeOpen" class="text-gray-600" />
                        </a>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 px-4">
                        <input class="mr-2 leading-tight" type="checkbox" name="remember">
                        <span class="text-sm">Keep me signed in</span>
                    </label>
                </div>

                <div class="text-center">
                    <b-button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 rounded-full focus:outline-none focus:shadow-outline w-full mb-8" type="submit">Sign In</b-button>
                      <b-alert
                        :show="hasError"
                        variant="danger"
                        @dismissed="hasError = false" 
                        dismissible
                        class="alert alert-dismissible alert-danger"
                        >
                        {{this.errormessage}}
                    </b-alert>
                    <!-- <a class="text-sm forgot-password" href="{{ route('password.request') }}" onclick="alert('test')">Forgot your password?</a> -->
                    <a class="text-sm forgot-password" href="/client-reset">Forgot your password?</a>
                </div>
            <!-- </form> -->
            </b-form>
        </div>

    </div>


</template>

<style lang="scss" scoped>
	@import '../../../sass/client/dashboard'; 
    #email , #password{
        top: 537px;
    left: 725px;
    /* width: 471px; */
    height: 48px;
    background: #FFFFFF 0% 0% no-repeat padding-box;
    border: 1px solid #CBCBCB;
    border-radius: 100px;
    opacity: 1;
    }
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
            password: '', 
            hasError: false,
            errormessage: ''
       }
    },
    validations: {
      email: { required, email },
      password: { required, minLength: minLength(6) }
    },
	created() {
    this.inject_material_fonts_and_icons_into_header();
	  },
	  mounted(){
		document.getElementById('email').focus()
		document.getElementById('eyeOpen').style.display = 'none'
			
			
	  },
	  methods: {
          togglePasswordVisibility() {
				let x = document.getElementById('password')
				if (x.type === 'password') {
					x.type = 'text'
					document.getElementById('eyeClose').style.display = 'none'
					document.getElementById('eyeOpen').style.display = 'block'
				} else {
					x.type = 'password'
					document.getElementById('eyeClose').style.display = 'block'
					document.getElementById('eyeOpen').style.display = 'none'
				}
			},
                submit() {
                    let vm = this;
                    this.$v.$touch()
                    if (!this.$v.$invalid) {
                    axios.post('/api/login', {
                            _token: this.csrf,
                            email: this.email, 
                            password: this.password
                        }).then(res => {
                            if(res.data.status){
                                localStorage.removeItem('admin')
                                localStorage.removeItem('user')
                                localStorage.setItem('user',JSON.stringify(res.data.employee));
                                localStorage.setItem('accesstoken',JSON.stringify(res.data.token));
                                localStorage.setItem("role",JSON.stringify(res.data.employee.role.role_name));

                                vm.$router.push('/employees');

                            }
                            else{
                                this.hasError = true
                                this.errormessage = res.data.message
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