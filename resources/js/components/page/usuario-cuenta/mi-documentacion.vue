<template lang="html">
<div class="my-documents-page">

  <h5 class="main-page-title">Mi documentación</h5>
  <hr class="c-hr" />

  <!-- RFC -->
  <b-form class="c-f-g-2-wrp sm" @submit="onSubmit">
    <h6 class="mt-4 pt-2 text-center">DOCUMENTACIÓN RFC</h6>
    <p>Toda la información es mandatoria y sera verificada, de lo contrario no se podrán emitir documentos.</p>
    <hr class="c-hr" />

    <b-form-group class="cus-f-group-2" label="RFC">
      <b-form-input type="text" v-model="$root.user.rfc" value="555" size="sm" placeholder="" />
    </b-form-group>



    <b-form-group class="cus-f-group-2" label="Sube tu constancia del RFC" v-show="changeRFCFile">
      <b-form-file plain name="rfc" v-model="rfcfile"></b-form-file>
      <br>
      <a class="btn" style="background-color: #efefef;border: 1px solid;"  @click="openModalCapture('rfc')">
					<span class="">Tomar foto</span>
			</a>
      <a v-if="rfcfileimageBase64 != null" class="t-250 link-icon-s1 show-file" @click="openFileModal(rfcfileimageBase64, 'iframe')"> Previsualizar foto</a>

    </b-form-group>

    <b-form-group class="cus-f-group-2" label="Archivo de Constancia del RFC" v-show="!changeRFCFile">
      <a class="t-250 link-icon-s1 show-file" @click="openFileModal($root.user.rfcDoc, 'iframe')"> Visualizar Archivo</a>
      <span class="sep">|</span>
      <a class="t-250 link-icon-s1 delete-file" @click="changeRFCFile = true"> Cambiar</a>
    </b-form-group>

    <b-button type="submit" class="btn-s1 bg-blue">Guardar cambios en RFC</b-button>
  </b-form>
  <!--  -->

  <!-- CURP -->
  <b-form class="mt-5 c-f-g-2-wrp sm" @submit="onSubmit">
    <h6 class="mt-4 pt-2 text-center">DOCUMENTACIÓN CURP</h6>
    <hr class="c-hr" />

    <b-form-group class="cus-f-group-2" label="CURP">
      <b-form-input type="text" v-model="$root.user.curp" size="sm" placeholder="" />
    </b-form-group>

    <b-form-group class="cus-f-group-2" label="Sube un pdf de tu CURP" v-show="changeCURPFile">
      <b-form-file plain name="curp"  v-model="curpfile"></b-form-file>

      <br>
      <a class="btn" style="background-color: #efefef;border: 1px solid;"  @click="openModalCapture('curp')">
					<span class="">Tomar foto</span>
			</a>
      <a v-if="curpfileimageBase64 != null" class="t-250 link-icon-s1 show-file" @click="openFileModal(curpfileimageBase64, 'iframe')"> Previsualizar foto</a>

    </b-form-group>

    <b-form-group class="cus-f-group-2" label="Archivo de Constancia del RFC" v-show="!changeCURPFile">
      <a class="t-250 link-icon-s1 show-file" @click="openFileModal($root.user.curpDoc, 'iframe')"> Visualizar Archivo</a>
      <span class="sep">|</span>
      <a class="t-250 link-icon-s1 delete-file" @click="changeCURPFile = true"> Cambiar</a>
    </b-form-group>

    <b-button type="submit" class="btn-s1 bg-blue">Guardar cambios en CURP</b-button>
  </b-form>
  <!--  -->

  <!-- INE -->
  <b-form class="mt-5 c-f-g-2-wrp sm" @submit="onSubmit">
    <h6 class="mt-4 pt-2 text-center">DOCUMENTACIÓN DEL INE</h6>
    <hr class="c-hr" />

    <b-form-group class="cus-f-group-2" label="Frente INE: Subir PDF ó foto" v-show="changeINEFront">
      <b-form-file plain name="inefront" v-model="inefrontfile"></b-form-file>

      <br>
      <a class="btn" style="background-color: #efefef;border: 1px solid;"  @click="openModalCapture('inefront')">
					<span class="">Tomar foto</span>
			</a>
      <a v-if="inefrontfileimageBase64 != null" class="t-250 link-icon-s1 show-file" @click="openFileModal(inefrontfileimageBase64, 'iframe')"> Previsualizar foto</a>

    </b-form-group>

    <b-form-group class="cus-f-group-2" label="Foto INE Frente" v-show="!changeINEFront">
      <a class="t-250 link-icon-s1 show-file" @click="openFileModal($root.user.inefrontDoc, 'iframe')"> Visualizar Archivo</a>
      <span class="sep">|</span>
      <a class="t-250 link-icon-s1 delete-file" @click="changeINEFront = true"> Cambiar</a>
    </b-form-group>

    <b-form-group class="cus-f-group-2" label="Reverso INE: Subir PDF ó foto" v-show="changeINEBack">
      <b-form-file plain name="ineback" v-model="inebackfile"></b-form-file>

      <br>
      <a class="btn" style="background-color: #efefef;border: 1px solid;"  @click="openModalCapture('ineback')">
					<span class="">Tomar foto</span>
			</a>
      <a v-if="inebackfileimageBase64 != null" class="t-250 link-icon-s1 show-file" @click="openFileModal(inebackfileimageBase64, 'iframe')"> Previsualizar foto</a>


    </b-form-group>

    <b-form-group class="cus-f-group-2" label="Foto INE Reverso" v-show="!changeINEBack">
      <a class="t-250 link-icon-s1 show-file" @click="openFileModal($root.user.inebackDoc, 'iframe')"> Visualizar Archivo</a>
      <span class="sep">|</span>
      <a class="t-250 link-icon-s1 delete-file" @click="changeINEBack = true"> Cambiar</a>
    </b-form-group>

    <b-form-group class="cus-f-group-2" label="Clave de elector del INE">
      <b-form-input type="text" v-model="$root.user.clave_ine" size="sm" placeholder="" />
    </b-form-group>

    <b-button type="submit" class="btn-s1 bg-blue">Guardar cambios en INE</b-button>
  </b-form>
  <!--  -->

  <!-- Modal para ver los pdfs -->
  <b-modal modal-class="modal-content-s1 modal-documents" ref="modal-show-file" title="Información de contacto" size="xl" @hidden="resetModal" no-close-on-backdrop no-close-on-esc centered hide-footer>
    <iframe class="doc-iframe" v-if="modalType == 'iframe'" :src="modalURL"></iframe>

    <div class="doc-image" v-if="modalType == 'image'">
      <div class="inside">
        <img :src="modalURL">
      </div>
    </div>
  </b-modal>

  <sweet-modal ref="modalCapture" title="Tomar foto">
    <div style="text-align: center;" id="imagecapture_rfc" v-show="modalcapture == 'rfc'">
				<PhotoCapture v-model="rfcfileimageBase64"  :data.sync="rfcfileimageBase64"/>		       
		</div>
    <div style="text-align: center;" id="imagecapture_curp" v-show="modalcapture == 'curp'">
				<PhotoCapture v-model="curpfileimageBase64"  :data.sync="curpfileimageBase64"/>		       
		</div>
    <div style="text-align: center;" id="imagecapture_inefront" v-show="modalcapture == 'inefront'">
				<PhotoCapture v-model="inefrontfileimageBase64"  :data.sync="inefrontfileimageBase64"/>		       
		</div>
    <div style="text-align: center;" id="imagecapture_ineback" v-show="modalcapture == 'ineback'">
				<PhotoCapture v-model="inebackfileimageBase64"  :data.sync="inebackfileimageBase64"/>		       
		</div>

  </sweet-modal>


  
  <!--  -->

  <!--  -->
  <sweet-modal :icon="modal.icon" :blocking="modal.block" :hide-close-button="modal.block" ref="modal">
      <div class="fa-3x" v-if="modal.icon== ''"><i class="fas fa-spinner fa-pulse"></i></div><br/>
      {{modal.msg}}
      <div class="col-12 text-center" style="padding-top: 20px;" v-if="modal.icon == 'success'">
      <b-button class="btn btn-primary" slot="button" v-on:click.prevent="$refs.modal.close();">Aceptar</b-button>
      </div>
  </sweet-modal>
  <!--  -->
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
        // RFC
        changeRFCFile: false,
         changeCURPFile: false,
         changeINEFront: false,
        changeINEBack: false,
        /*changeRFCFile: false,
        formRFC:{
          rfc: 'RTAX5934510510502',
          file: 'http://es.tldp.org/COMO-INSFLUG/es/pdf/Linuxdoc-Ejemplo.pdf',
        },

        // CURP
        changeCURPFile: false,
        formCURP:{
          curp: 'RTAX510510',
          file: 'http://es.tldp.org/COMO-INSFLUG/es/pdf/Linuxdoc-Ejemplo.pdf',
        },

        // INE
        changeINEFront: false,
        changeINEBack: false,
        formINE:{
          ine_frente: 'https://kuali.com.mx/web/wp-content/uploads/2018/11/renovacion-credencial-frente-1-730x410.jpg',
          ine_reverso: 'https://assets.easybroker.com/property_files/1362115/71081/Rev%C3%A9s_IFE_Sr._Alfredo_Chalico_Nava.jpg',
          ine_clave: 'INS45252F363D108306542150SD20'
        },*/

        rfcfile:null,
        rfcfileimageBase64:null,

        curpfile:null,
        curpfileimageBase64:null,

        inefrontfile:null,
        inefrontfileimageBase64:null,

        inebackfile:null,
        inebackfileimageBase64:null,
      
        modalType: null,
        modalURL: null,

        modal:{
          msg:'',
          icon:'',
          block:false,
        },
        modalcapture:null
    }
  },

  watch:{
      'rfcfileimageBase64':function (val) {
				if (val != null) {
					this.$refs.modalCapture.close();
				}
			},
      'curpfileimageBase64':function (val) {
				if (val != null) {
					this.$refs.modalCapture.close();
				}
			},
      'inefrontfileimageBase64':function (val) {
				if (val != null) {
					this.$refs.modalCapture.close();
				}
			},
      'inebackfileimageBase64':function (val) {
				if (val != null) {
					this.$refs.modalCapture.close();
				}
			},
  },

  methods: {
    resetModal(){
      this.modalType = null;
      this.modalIframeURL = null;
    },

    openFileModal(url, type){
      console.log(url, type);
      this.modalType = type;
      this.modalURL = url;
      this.$refs['modal-show-file'].show();
    },

   

    onSubmit(event){
      event.preventDefault()
        this.modal.icon = "";
        this.modal.msg = 'Cargando...';
        this.modal.block = true;
        this.$refs.modal.open();

        var formData = new FormData();
        formData.append("rfc", this.$root.user.rfc);
        formData.append("curp", this.$root.user.curp);
        formData.append("clave_ine", this.$root.user.clave_ine);
        if (this.rfcfile != null ||  this.rfcfileimageBase64 != null ) {
            formData.append("rfc_file", jQuery('input[name="rfc"]')[0].files[0]);
            formData.append("rfc_file_base64", this.rfcfileimageBase64);
        }
        if (this.curpfile != null ||  this.curpfileimageBase64 != null) {
            formData.append("curp_file", jQuery('input[name="curp"]')[0].files[0]);
            formData.append("curp_file_base64", this.curpfileimageBase64);
        }
        if (this.inefrontfile != null ||  this.inefrontfileimageBase64 != null) {
            formData.append("inefront", jQuery('input[name="inefront"]')[0].files[0]);
            formData.append("inefront_file_base64", this.inefrontfileimageBase64);
        }
        if (this.inebackfile != null ||  this.inebackfileimageBase64 != null) {
            formData.append("ineback", jQuery('input[name="ineback"]')[0].files[0]);
            formData.append("ineback_file_base64", this.inebackfileimageBase64);
        }

        axios.post(tools.url('/api/user/documentation'), formData).then((response)=>{
            if(response.data.status == 'success'){
                this.modal.block = false;
                this.modal.icon = "success";
                this.modal.msg = response.data.msg;
                this.$root.auth();

                this.changeRFCFile = false;
                this.changeCURPFile = false;
                this.changeINEFront = false;
                this.changeINEBack = false;

                this.rfcfile = null;
                this.curpfile = null;
                this.inefrontfile = null;
                this.inebackfile = null;


            }else{
                this.modal.block = false;
                this.modal.icon = "error";
                this.modal.msg = response.data.msg;
            }
        }).catch((error)=>{
            this.modal.block = false;
            this.modal.icon = "error";
            this.modal.msg = response.data.msg;
            
            this.handleErrors(error);
        });
    },
    openModalCapture(type){
      this.$refs.modalCapture.open();

      
      this.modalcapture = type;
      $('.photo-capture h1').text('');
      $('.photo-capture .photo-capture-actions .flex-center').text('Capturar');
    }
  },

  mounted(){
    $('.photo-capture h1').text('');
      $('.photo-capture .photo-capture-actions .flex-center').text('Capturar');
  }
}
</script>
