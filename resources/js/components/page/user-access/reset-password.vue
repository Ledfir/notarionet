<template lang="html">
  <div id="user-access-page" class="placed-backg">

    <div class="container">
      <div class="row mx-0 flex-center-xy page-size">

        <div class="form-container">
          <div class="box-color"><i><u class="fas fa-user"></u></i></div>

          <b-form class="form" @submit.prevent="recoverPassword()">

            <h1 class="mb-3">{{ $t('resetpassword.recover') }}</h1>

            <p class="mb-3">
              {{ $t('resetpassword.write') }}
            </p>

            <b-form-group>
              <b-form-input type="email"
                            v-model="form.email"
                            required
                            :placeholder="$t('resetpassword.email')">
              </b-form-input>
            </b-form-group>

            <p class="mb-3 text-center">
              <router-link to="/login">{{ $t('resetpassword.remember') }}</router-link>
            </p>

            <b-form-group class="text-center">
              <b-button type="submit" class="btn-s1 bg-blue" variant="primary">{{ $t('resetpassword.recover') }}</b-button>
            </b-form-group>

          </b-form>
        </div>

      </div>
    </div>

    <sweet-modal :icon="modal.icon" :blocking="modal.block" :hide-close-button="modal.block" ref="modal">
        <div class="fa-3x" v-if="modal.icon== ''"><i class="fas fa-spinner fa-pulse"></i></div><br/>
        {{modal.msg}}
        <div class="col-12 text-center" style="padding-top: 20px;" v-if="modal.icon == 'success'">
        <b-button class="btn btn-success" slot="button" v-on:click.prevent="$refs.modal.close();">{{ $t('resetpassword.accept') }}</b-button>
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
    }
  },

  methods: {
    recoverPassword(){
      this.modal.icon = "";
      this.modal.msg = 'Loading...';
      this.modal.block = true;
      this.$refs.modal.open();

      axios.post(tools.url("/api/resetpassword"), this.form).then((response)=>{
        this.modal.block = false;
            this.modal.icon = "success";
            if(this.$i18n.locale == 'en'){
              this.modal.msg = 'An email with your temporary password has been sent';
            }
            else{
              this.modal.msg = 'Se ha enviado un correo con tu contraseña temporal';
            }

      }).catch((error)=>{
              this.modal.icon = "error";
              this.modal.msg = error.response.data.msg;
              this.modal.block = false;
              
      });
    }
  }
}
</script>
