<template lang="html">
  <div id="user-access-page" class="placed-backg">

    <div class="container">
      <div class="row mx-0 flex-center-xy page-size">

        <div class="form-container" style="border-radius: 20px;">
          <div class="box-color"><i><img src="/images/redesign/logo-notarynet.webp" alt="NotaryNet" class="img-fluid" style="width: 88px;"></i></div>

          <b-form class="form" @submit.prevent="login()">

            <h1 class="mb-3">{{ $t('login.login') }}</h1>

            <b-form-group>
              <b-form-input type="email" v-model="form.email" required :placeholder="$t('login.email')"></b-form-input>
            </b-form-group>

            <!-- <b-form-group>
              <b-form-input type="password" v-model="form.password" required placeholder="Contraseña"></b-form-input>
            </b-form-group> -->

            <b-form-group>
              <div class="eye-box" style="position:relative;">
                  <b-form-input
                            id="il-1"
                            size="sm"
                            v-model="form.password"
                            type="password"
                            required
                            :placeholder="$t('login.password')"

                  ></b-form-input>
                  <i style="background-color: #fff;cursor: pointer; padding: 6px 10px; position: absolute;right: 2px;top: 2px; z-index: 4" class="fas ic-eye" v-bind:class="{ 'fa-eye' : showpass, 'fa-eye-slash' : !showpass }"  @click="showpass = !showpass"></i>
              </div>
            </b-form-group>


            <p class="mb-3 text-center">
              <router-link to="/registrarse">{{ $t('login.sign_up') }}</router-link><br><br>
              <router-link to="/recuperar-contrasena">{{ $t('login.forgot_pass') }}</router-link>
            </p>

            <b-form-group class="text-center">
              <b-button type="submit" class="btn-s1 bg-blue" style="border-radius: 20px;">{{ $t('login.access') }}</b-button>
            </b-form-group>

          </b-form>
        </div>

      </div>
    </div>

    <sweet-modal :icon="modal.icon" :blocking="modal.block" :hide-close-button="modal.block" ref="modal">
        <div class="fa-3x" v-if="modal.icon== ''"><i class="fas fa-spinner fa-pulse"></i></div><br/>
        {{modal.msg}}
        <div class="col-12 text-center" style="padding-top: 20px;" v-if="modal.icon == 'success'">
        <b-button class="btn btn-primary" slot="button" v-on:click.prevent="$refs.modal.close();">OK</b-button>
        </div>
    </sweet-modal>

  </div>
</template>

<script>
    export default {
    data(){
        return{
        form: {
            email: '',
            password: ''
        },

        modal:{
            msg:'',
            icon:'',
            block:false,
        },
        showpass:false,

        }
    },
    watch:{
        'showpass':function (val) {
            if (val == true) {
                $("#il-1").prop('type','text');
            }
            else if (val == false) {
                $("#il-1").prop('type','password');
            }
        },
    },
    methods: {
        login(){
        this.modal.icon = "";
        if(this.$i18n.locale == 'en'){
            this.modal.msg = 'Loading  your data...';
            }
            else{
            this.modal.msg = 'Cargando...';
            }
        this.modal.block = true;
        this.$refs.modal.open();

        axios.get(tools.url('/sanctum/csrf-cookie')).then(() => {
            axios.post(tools.url("/api/login"), this.form).then((response)=>{
            this.$root.auth();
            var self = this;
                setTimeout(() => {
                if(self.$root.user.email_verified_at != null){
                    self.modal.icon = "";
                    self.modal.msg = '';
                    self.modal.block = false;
                    self.$refs.modal.close();

                    if(self.$route.query.token){
                        if(self.$root.user.signature_base64 != null){
                        self.$router.push('/usuario/contratos');
                        }
                        else{
                        self.$router.push('/usuario/firma');
                        }

                    }
                    else{
                    self.$router.push("/usuario/contratos");
                    }
                }
                else{
                    self.logout();
                    self.modal.icon = "error";
                    self.modal.msg = 'Correo electronico no confirmado';
                    self.modal.block = false;
                }
                }, 2000);


            }).catch((error)=>{
                console.log(error.response.data.error);
                this.modal.icon = "error";
                this.modal.msg = 'Credenciales incorrectas';
                this.modal.block = false;

            });
        });
        },

        handleErrors:function(errors){
            var err="";

            if (errors.response) {
                if(errors.response.data.errors){
                    jQuery.each(errors.response.data.errors,(k,v)=>{
                        err+="*"+v[0]+"\n";
                    });
                }
            }
            else{
                console.log(errors);
                err="Error desconocido.";
            }

            this.modal.icon = "error";
            this.modal.msg = err;
            this.modal.block = false;
            this.$refs.modal.open();

        },
        logout(){
            axios.post(tools.url("/api/logout")).then((response)=>{
                this.$parent.user = {};
                this.$parent.logged = false;

            }).catch(()=>{});
        },

    },

    mounted(){
        if(this.$root.logged){
            this.$router.push("/usuario/");
        }
        else{
            if(this.$route.query.token){
                this.form.email = this.$route.query.email;
                this.form.password = this.$route.query.token;
                this.$root.contracts_id = this.$route.query.id;
                this.$root.changeLocale('en');
                this.login();
            }
        }
    }
    }
</script>

<style lang="css">
    .box-color img{
        align-content: center;
        text-align: center;
        background: #fff !important;
    }
    #user-access-page > .container > .row .form-container .box-color i{
        align-content: center;
        text-align-last: center;
        border: 1px solid #004aad;
        background: #ffffff;
    }
</style>
