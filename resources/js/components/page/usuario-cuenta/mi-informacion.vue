<template lang="html">
<div>
  <b-form class="c-f-g-2-wrp sm" @submit.prevent="onSubmit()">
    <h5 class="main-page-title">{{ $t("account.basic_info.my_basic_info") }}</h5>
    <p>{{ $t("account.basic_info.subtitle") }}</p>
    <hr class="c-hr" />

    <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.email')+' *'">
      <b-form-input type="email" v-model="$root.user.email" size="sm" required placeholder="" />
    </b-form-group>

    <hr class="c-hr" />

    <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.fullname')+' *'">
      <b-form-input type="text" v-model="$root.user.name" size="sm" required placeholder="" />
    </b-form-group>

    <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.phone')+' *'">
      <b-form-input type="text" v-model="$root.user.phone" size="sm" required placeholder="" maxlength="10" minlength="10" />
    </b-form-group>

    <div v-if="$root.user.business_name == null || $root.user.business_name == 'null' || $root.user.business_name == ''">
      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.street')+' *'">
        <b-form-input type="text" v-model="$root.user.address.street" size="sm" required placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.num_ext')+' *'">
        <b-form-input type="text" v-model="$root.user.address.num_ext" size="sm" required placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.num_int')">
        <b-form-input type="text" v-model="$root.user.address.num_int" size="sm" placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.neighborhood')+' *'">
        <b-form-input type="text" v-model="$root.user.address.neighborhood" size="sm" required placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.zip_code')+' *'">
        <b-form-input type="text" v-model="$root.user.address.zipcode" size="sm" required placeholder="" />
      </b-form-group>

     

      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.foreing')+' *'">
        <b-form-checkbox class="mb-3" v-model="$root.user.address.foreing" name="foreing" value="1" unchecked-value="0"></b-form-checkbox>
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.country')+' *'" v-if="$root.user.address.foreing == 1">
        <b-form-input type="text" v-model="$root.user.address.country" size="sm" required placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.state')+' *'" v-if="$root.user.address.foreing == 0">
        <b-form-select required v-model="$root.user.address.state_id" size="sm">
          <b-form-select-option :value="null">{{ $t("account.basic_info.select_option") }}</b-form-select-option>
          <b-form-select-option :value="s.id" v-for="(s, srInx) in states" :key="'srInx-'+srInx">{{ s.name }}</b-form-select-option>
        </b-form-select>
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.town')+' *'" v-if="$root.user.address.foreing == 0">
        <b-form-select required v-model="$root.user.address.town_id" size="sm">
          <b-form-select-option :value="null">{{ $t("account.basic_info.select_option") }}</b-form-select-option>
        <b-form-select-option :value="t.id" v-for="(t, srInx) in towns" :key="'srInx-'+srInx" >{{ t.name }}</b-form-select-option>
        </b-form-select>
      </b-form-group>


    </div>
    <div v-else>
      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.street')+' *'">
        <b-form-input type="text" v-model="$root.user.address.street_company" size="sm" required placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.num_ext')+' *'">
        <b-form-input type="text" v-model="$root.user.address.num_ext_company" size="sm" required placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.num_int')">
        <b-form-input type="text" v-model="$root.user.address.num_int_company" size="sm" placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.neighborhood')+' *'">
        <b-form-input type="text" v-model="$root.user.address.neighborhood_company" size="sm" required placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.zip_code')+' *'">
        <b-form-input type="text" v-model="$root.user.address.zipcode_company" size="sm" required placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.foreing')+' *'">
        <b-form-checkbox class="mb-3" v-model="$root.user.address.foreing" name="foreing" value="1" unchecked-value="0"></b-form-checkbox>
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.country')+' *'" v-if="$root.user.address.foreing == 1">
        <b-form-input type="text" v-model="$root.user.address.country" size="sm" required placeholder="" />
      </b-form-group>
      
      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.state')+' *'" v-if="$root.user.address.foreing == 0">
        <b-form-select required v-model="$root.user.address.state_id_company" size="sm">
          <b-form-select-option :value="null">{{ $t("account.basic_info.select_option") }}</b-form-select-option>
          <b-form-select-option :value="s.id" v-for="(s, srInx) in states" :key="'srInx-'+srInx">{{ s.name }}</b-form-select-option>
        </b-form-select>
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.basic_info.town')+' *'" v-if="$root.user.address.foreing == 0">
        <b-form-select required v-model="$root.user.address.town_id_company" size="sm">
          <b-form-select-option :value="null">{{ $t("account.basic_info.select_option") }}</b-form-select-option>
        <b-form-select-option :value="t.id" v-for="(t, srInx) in towns" :key="'srInx-'+srInx" >{{ t.name }}</b-form-select-option>
        </b-form-select>
      </b-form-group>
    </div>

    <!-- <b-form-group class="cus-f-group-2" label="Mi foto * Tu foto es mandatoria, sino capturas una selfie no podrás emitir documentos">
      <img width="150px" :src="imageBase64" alt="..." v-if="imageBase64 != null">
			<img width="150px" src="//placehold.it/200x150?text=Imagen" alt="..." v-else>
      <br><br>
      <a class="btn" style="background-color: #efefef;border: 1px solid;"  @click="openModalSig()">
					<span class="">Tomar foto</span>
			</a>
    </b-form-group> -->


    <b-button type="submit" class="btn-s1 bg-blue" :disabled="disabled">{{ $t("account.basic_info.save_changes") }}</b-button>

  </b-form>

    <sweet-modal :icon="modal.icon" :blocking="modal.block" :hide-close-button="modal.block" ref="modal">
        <div class="fa-3x" v-if="modal.icon== ''"><i class="fas fa-spinner fa-pulse"></i></div><br/>
        {{modal.msg}}
        <div class="col-12 text-center" style="padding-top: 20px;" v-if="modal.icon == 'success'">
        <b-button class="btn btn-primary" slot="button" v-on:click.prevent="$refs.modal.close();">OK</b-button>
        </div>
    </sweet-modal>

    <sweet-modal ref="modalCapture" title="Tomar foto">
      <div style="text-align: center; width: 550px; height: 550px;" id="imagecapture_rfc">
          <!-- <PhotoCapture v-model="imageBase64"  :data.sync="imageBase64" captureBtnContent="Capturar"/>		        -->
      </div>
      

    </sweet-modal>
</div>
</template>

<script>
import {PhotoCapture, VideoCapture} from 'vue-media-recorder'
export default {
  components:{
	    PhotoCapture,
	    VideoCapture
	},
  data(){
    return{
        states: [],
        towns: [],
        disabled: false,

        modal:{
          msg:'',
          icon:'',
          block:false,
        },
        imageBase64:null
    }
  },

  watch:{
      'imageBase64':function (val) {
        var canv = $('.preview');
      canv.prop('width', 500);
      canv.prop('height', 500);
				if (val != null) {
					this.$refs.modalCapture.close();
				}
			},
    '$root.user.address.state_id':function(val){
      if(val && !isNaN(val)){
        this.getTowns(val);
      }
    },
    '$root.user.address.state_id_company':function(val){
      if(val && !isNaN(val)){
        this.getTowns(val);
      }
    },

    
  },

  methods: {
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

    onSubmit(){
      this.modal.icon = "";
        this.modal.msg = 'Cargando...';
        this.modal.block = true;
        this.$refs.modal.open();

        var data = {
            id: this.$root.user.id,
            name: this.$root.user.name,
            lastname: this.$root.user.lastname,
            email: this.$root.user.email,
            phone: this.$root.user.phone,
            foreing: this.$root.user.foreing,
            country: this.$root.user.country,
            //customer
            street: this.$root.user.address.street,
            num_int: this.$root.user.address.num_int,
            num_ext: this.$root.user.address.num_ext,
            neighborhood: this.$root.user.address.neighborhood,
            zipcode: this.$root.user.address.zipcode,
            state_id: this.$root.user.address.state_id,
            town_id: this.$root.user.address.town_id,
        };

        this.disabled = true;

        var formData = new FormData();
        formData.append("id", this.$root.user.id);
        formData.append("name", this.$root.user.name);
        formData.append("lastname", this.$root.user.lastname);
        formData.append("email", this.$root.user.email);
        formData.append("phone", this.$root.user.phone);
        formData.append("street", this.$root.user.address.street);
        formData.append("num_int", this.$root.user.address.num_int);
        formData.append("num_ext", this.$root.user.address.num_ext);
        formData.append("neighborhood", this.$root.user.address.neighborhood);
        formData.append("zipcode", this.$root.user.address.zipcode);
        formData.append("state_id", this.$root.user.address.state_id);
        formData.append("town_id", this.$root.user.address.town_id);
        formData.append("foreing", this.$root.user.address.foreing);
        formData.append("country", this.$root.user.address.country);

        formData.append("street_company", this.$root.user.address.street_company);
        formData.append("num_int_company", this.$root.user.address.num_int_company);
        formData.append("num_ext_company", this.$root.user.address.num_ext_company);
        formData.append("neighborhood_company", this.$root.user.address.neighborhood_company);
        formData.append("zipcode_company", this.$root.user.address.zipcode_company);
        formData.append("state_id_company", this.$root.user.address.state_id_company);
        formData.append("town_id_company", this.$root.user.address.town_id_company);
        
        //formData.append("image", jQuery('input[name="image"]')[0].files[0]);
        formData.append("image_base64", this.imageBase64);

        axios.post(tools.url('/api/user/profile'), formData).then((response)=>{
            if(response.data.status == 'success'){
                this.disabled = false;
                this.modal.icon = "success";
                this.modal.msg = response.data.msg;
                this.modal.block = false;
                
                this.$root.auth();
            }else{
                this.disabled = false;
                this.modal.icon = "error";
                this.modal.msg = response.data.msg;
                this.modal.block = false;
                
            }
        }).catch((error)=>{
            this.disabled = false;
            this.handleErrors(error);
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
    openModalSig(){
      this.$refs.modalCapture.open();
      $('.photo-capture h1').text('');
      $('.photo-capture .photo-capture-actions .flex-center').text('Capturar');
    }
  },

  beforeMount(){
    this.getStates();
  },

  mounted(){
    this.imageBase64 = this.$root.user.imageUrl;
    // $('.photo-capture h1').text('');
      //$('.photo-capture .photo-capture-actions .flex-center').text('Capturar');
    if(this.$root.user.address && this.$root.user.address.state_id){
      this.getTowns(this.$root.user.address.state_id);
    }

    if(this.$root.user.address && this.$root.user.address.state_id_company){
      this.getTowns(this.$root.user.address.state_id_company);
    }

    /*var canv = $('.preview');
    canv.prop('width', 500);
    canv.prop('height', 500);

    $('.btn .flex-center ').click(function(event) {
      console.log('cllick');
      var canv = $('.preview');
      canv.prop('width', 500);
      canv.prop('height', 500);
    });*/
  }
}
</script>
<style>
  .photo-capture .camera{
    width: 450px;
    height: 450px;
  }
  .photo-capture .preview{
    width: 450px;
    margin-top: 55px;
  }
  
  .center .btn{
    background-color: black;
    color: white;
    width: 130px;
    margin: 10px;
  } 
</style>