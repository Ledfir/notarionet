<template lang="html">
  <div id="contact-page">

    <section class="container main-section">
      <div class="row align-items-center">
        <div class="col-12 col-title">
          <h1 class="title-s-1">{{ $t("requestinvoice.request_invoice") }}</h1>
        </div>

        <div class="col-lg-6 mx-auto col-form">
          <b-form @submit="onSubmit">
            <div class="row">
              <b-form-group class="cus-f-group-1 col-12" :label="$t('requestinvoice.date')">
                <b-form-input type="date" v-model="form.date" required placeholder=""></b-form-input>
              </b-form-group>

              <b-form-group class="cus-f-group-1 col-12" :label="$t('requestinvoice.email')">
                <b-form-input type="email" v-model="form.email" required placeholder=""></b-form-input>
              </b-form-group>

              <b-form-group class="cus-f-group-1 col-12" :label="$t('requestinvoice.business')">
                <b-form-input type="text" v-model="form.business_name" required placeholder=""></b-form-input>
              </b-form-group>

              <b-form-group class="cus-f-group-1 col-12" label="RFC">
                <b-form-input type="text" v-model="form.rfc" required placeholder=""></b-form-input>
              </b-form-group>

              <b-form-group class="cus-f-group-1 col-12" :label="$t('requestinvoice.address')">
                <b-form-input type="text" v-model="form.address" required placeholder=""></b-form-input>
              </b-form-group>

              <!-- <b-form-group class="cus-f-group-1 col-12" label="Estado">
                <b-form-input type="text" v-model="form.states_id" required placeholder=""></b-form-input>
              </b-form-group>

              <b-form-group class="cus-f-group-1 col-12" label="Ciudad">
                <b-form-input type="text" v-model="form.towns_id" required placeholder=""></b-form-input>
              </b-form-group> -->

              <b-form-group class="cus-f-group-1 col-12" :label="$t('requestinvoice.state')">
                <b-form-select required v-model="form.states_id" size="sm">
                  <b-form-select-option :value="null">{{ $t("requestinvoice.select_option") }}</b-form-select-option>
                  <b-form-select-option :value="s.id" v-for="(s, srInx) in states" :key="'srInx-'+srInx">{{ s.name }}</b-form-select-option>
                </b-form-select>
              </b-form-group>

              <b-form-group class="cus-f-group-1 col-12" :label="$t('requestinvoice.town')">
                <b-form-select required v-model="form.towns_id" size="sm">
                  <b-form-select-option :value="null">{{ $t("requestinvoice.select_option") }}</b-form-select-option>
                <b-form-select-option :value="t.id" v-for="(t, srInx) in towns" :key="'srInx-'+srInx" >{{ t.name }}</b-form-select-option>
                </b-form-select>
              </b-form-group>


              <b-form-group class="cus-f-group-1 col-12" :label="$t('requestinvoice.zipcode')">
                <b-form-input type="number" v-model="form.zip_code" required placeholder=""></b-form-input>
              </b-form-group>

              <div class="col-12">
                <b-button type="submit" class="btn-s1 bg-blue w-100">{{ $t("requestinvoice.send") }}</b-button>
              </div>
            </div>
          </b-form>
        </div>

        <sweet-modal :icon="modal.icon" :blocking="modal.block" :hide-close-button="modal.block" ref="modal">
            <div class="fa-3x" v-if="modal.icon== ''"><i class="fas fa-spinner fa-pulse"></i></div><br/>
            {{modal.msg}}
            <div class="col-12 text-center" style="padding-top: 20px;" v-if="modal.icon == 'success'">
            <b-button class="btn btn-success" slot="button" v-on:click.prevent="$refs.modal.close();">{{ $t("requestinvoice.accept") }}</b-button>
            </div>
        </sweet-modal>

      </div>
    </section>

  </div>
</template>

<script>
export default {
  data(){
    return{
      form: {
        name: null,
        states_id:null,
        tonws_id:null
      },
      modal:{
        msg:'',
        icon:'',
        block:false,
      },
      states:[],
      towns:[]
    }
  },
  watch:{
    'form.states_id':function(val){
      if(val && !isNaN(val)){
        this.getTowns(val);
      }
    }
  },
  methods: {
    onSubmit(event) {
      event.preventDefault();
      this.modal.icon = "";
      this.modal.msg = 'Loading...';
      this.modal.block = true;
      this.$refs.modal.open();

      axios.post(tools.url("/api/invoices"), this.form).then((response)=>{
        this.modal.block = false;
            this.modal.icon = "success";
            if(this.$i18n.locale == 'en'){
              this.modal.msg = 'Information saved correctly';
            }
            else{
              this.modal.msg = 'Informacion guardada correctamente';
            }
            

      }).catch((error)=>{
              this.modal.icon = "error";
              this.modal.msg = error.response.data.msg;
              this.modal.block = false;

      });
    },
    getStates(){
      axios.get(tools.url('/api/states')).then((response)=>{
        this.states = response.data;
      }).catch((error)=>{
         console.log(error);
      });
    },

    getTowns(state_id){
        if(state_id){
            axios.get(tools.url('/api/towns/' + state_id)).then((response)=>{
              this.towns = response.data;
            }).catch((error)=>{
              console.log(error);
            });
        }else{
            this.towns = [];
        }
    },
  },
  mounted(){
      this.getStates();
  }
}
</script>
