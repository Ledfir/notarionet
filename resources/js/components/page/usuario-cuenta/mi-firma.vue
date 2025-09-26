<template lang="html">
<div>
  <b-form class="c-f-g-2-wrp sm" @submit.prevent="onSubmit()">
    <h5 class="main-page-title">{{ $t("account.mysignature.title") }}</h5>
    <hr class="c-hr" />

    <b-form-group class="cus-f-group-2 mb-1 text-center" :label="$t('account.mysignature.subtitle')"></b-form-group>

    <div class="row justify-content-center">
      <div class="col-lg-9 mb-4">
        <div class="box-image-signature">
          <img class="empty" src="public/images/shared/empty.png">

          <div class="box-btn-signature" @click="openSignatureModalSD" v-if="!$root.user.signatureUrl">
            <div class="inside">
              <h5> {{ $t('account.mysignature.new_sing') }}</h5>
            </div>
          </div>

          <div class="box-fake-signature" v-else>
            <div class="fake-image" v-bind:style="{ backgroundImage: 'url('+$root.user.signatureUrl+')' }"></div>
            <!-- <div class="box-button">
              <button class="btn btn-s1 bg-blue" @click="removeImage('imageSignature')">Eliminar firma y subir una nueva</button>
            </div> -->
          </div>
        </div>
      </div>
        
      <div class="col-12 text-center" v-if="!$root.user.signature_image_id">
          <b-button type="submit" class="btn-s1 bg-blue" >{{ $t('account.mysignature.save_sing') }}</b-button>
        </div>
    </div>

  </b-form>

  <!-- Modal firma -->
  <b-modal modal-class="modal-signature" ref="modal-signature" centered hide-footer title="" @hidden="showSignaturePad = false">
    <div class="box-content">
      <p style="text-align: center;">{{ $t('account.mysignature.sing_equal') }}</p>
      
      
      <div class="box-signature" v-if="showSignaturePad">
        <VueSignaturePad class="signature-pad" ref="signaturePad" />

        <div class="box-buttons">
          <button class="btn btn-s1 bg-blue" @click="saveSignature">{{ $t('account.mysignature.save') }}</button>
          <button class="btn btn-s1 bg-blue" @click="resetSignature">{{ $t('account.mysignature.clean') }}</button>
        </div>
      </div>
    </div>
  </b-modal>

  <sweet-modal ref="modal_signature_seguridata">
    <p v-if="$i18n.locale == 'en'">Please kindly append your signature below. Our intelligent system will securely store it and generate an electronic certificate accordingly</p>
      
      <div id="content-wrapper" role="main">
            <div id="content" class="canvasDivContainer">
                <div class="signCaptureCanvas"></div>
            </div>
      </div>
  </sweet-modal>
  <!--  -->

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
      imageSignature: '',
      showSignaturePad: false,

      modal:{
        msg:'',
        icon:'',
        block:false,
      },
      formdata:{
        points:[],
        base64:null
      }
    }
  },

  methods: {
    resetSignature() {
      this.$refs.signaturePad.clearSignature();
    },
    saveSignature() {
      const { isEmpty, data } = this.$refs.signaturePad.saveSignature();
      // console.log(isEmpty);
      // console.log(data);
      this.$root.user.signatureUrl = data;
      this.$refs['modal-signature'].hide();
    },

    openSignatureModal(){
      this.$refs['modal-signature'].show();

      setTimeout(()=>{ this.showSignaturePad = true; }, 1000);
    },
    openSignatureModalSD(){
        this.$refs.modal_signature_seguridata.open();
        $(".canvasDivContainer").empty();
        $(".canvasDivContainer").html('<div class="signCaptureCanvas"></div>');
        var self = this;
        var canvasWidth = 450;
        var canvasHeight = 250;
        if(this.$i18n.locale == 'en'){
          var firmaText = "Sign here";
          var limpiarText = "Clean";
          var getText = "Confirm signature";
        }
        else{
          var firmaText = "Firme Aquí";
          var limpiarText = "Limpiar";
          var getText = "Confirmar firma";
        }
        var clickX_simple = [];
        var clickY_simple = [];
        var time_simple = [];
        var clickDrag_simple = [];
        var canvas_simple;
        var sigPad = [];


        var canvasDiv = null;
        var button_clear = null;
        var button_getdata = null;
        /*  Función que busca los elementos div que cuenten con la class signCaptureCanvas,
            para cada div encontrado creará un canvas, un botón para borrar y otro para obtener los datos
        */

        var canvasDivList = $(".signCaptureCanvas");
        $.each(canvasDivList, function (index, value) {

            canvasDiv = value;
            clickX_simple[index] = new Array();
            clickY_simple[index] = new Array();
            time_simple[index] = new Array();
            clickDrag_simple[index] = new Array();
            time_simple[index] = new Array();
            sigPad[index] = new Array();

            // Creación de canvas

            var idSignCaptureCanvas = 'idSignCaptureCanvas' + index;
            canvas_simple = document.createElement('canvas');
            canvas_simple.setAttribute('width', canvasWidth);
            canvas_simple.setAttribute('height', canvasHeight);
            canvas_simple.setAttribute('id', idSignCaptureCanvas);
            canvas_simple.setAttribute('class', 'drawnCanvas');
            canvas_simple.setAttribute('index', index);
            canvasDiv.appendChild(canvas_simple);

              // Creación de botón para mostrar datos capturados en consola
              button_getdata = document.createElement('button');
            button_getdata.setAttribute('class', 'btn-s1 bg-blue');
            button_getdata.setAttribute('type', 'button');
            button_getdata.setAttribute('index', index);
            button_getdata.setAttribute('id', 'idGetButton' + index);
            $(button_getdata).html(getText);
            $(button_getdata).insertAfter(canvasDiv);


            // Creación de botón para limpiar texto

            button_clear = document.createElement('button');
            button_clear.setAttribute('class', 'btn-s1 btn-secondary');
            button_clear.setAttribute('type', 'button');
            button_clear.setAttribute('index', index);
            button_clear.setAttribute('id', 'idCleanButton' + index);
            $(button_clear).html(limpiarText);
            $(button_clear).insertAfter(canvasDiv);


            if (typeof G_vmlCanvasManager != 'undefined') {
                canvas_simple = G_vmlCanvasManager.initElement(canvas_simple);
            }

            /*  Instancia de la librería SignaturePad, en él se define el elemento canvas
                y otras propiedades como texto de marca de agua, fuente, grosor del trazo etc.
            */

            sigPad[index] = new SignaturePad( {
                canvas: document.getElementById(idSignCaptureCanvas),   // Elemento canvas
                textFont: 'normal 15px monospace',                      // Fuente
                textStrokeColor: 'transparent',                         // Color de contorno del texto
                textFillColor: '#000',                                  // Color del texto de marca de agua
                brushSize: 2,                                           // Grosor del trazo
                splashText: firmaText,                                  // Texto de la marca de agua
                pointBlackPercent: 0.005,                               // Porcentaje de trazo minímo para aprovar la firma
                canvasWhitePercent: 0.80,                               // Procentaje del lienzo en blanco que debe haber para aprovar la firma
                dialog: {
                    displayDialog: 'Y',// Desplegar dialog en caso de que se detecte que el trazo no concuerda con la configuración
                    //Mensaje de cuerpo del dialogo
                    bodyText: '¡Ups! se ha detectado que la firma puede no ser correcta, por favor a continuación confirma si la firma mostrada en la parte superior parece ser correcta:',
                    btnDecline: 'Declinar y repetir firma',//Texto de boton para declinar
                    btnConfirm: 'Confirmar y enviar firma',//Texto de boton para confirmar
                    btnConfirmFunction: function () { //function para actuar dependiendo si el usuario presiona el boton confirmar
                        //console.log("btnConfirm pressed");
                        //console.log(sigPad[index].getDataInJSON());
                    },
                    btnDeclineFunction: function () {//function para actuar dependiendo si el usuario presiona el boton declinar
                        var signBackground = document.getElementsByClassName('signature_background')[0];
                        signBackground.firstChild.remove();
                        signBackground.remove();

                        document.querySelector('#idCleanButton0').click();
                    }
                }
            });

            sigPad[index].setSize( canvasWidth, canvasHeight );

            /*  Asignación de funciones a los botones creados previamente.
            */

            document.querySelector( '#idCleanButton' + index ).onclick = function () {
                // Función para borrar el trazo del canvas
                sigPad[index].clear();
            }

            document.querySelector( '#idGetButton' + index ).onclick = function () {
                var statuss = sigPad[index].verify();
                if (statuss['status'] == 'Rejected') {


                  $(".signature_background").css("display", "none");
                  alert('La firma no es valida');
                }
                else{
                    // Función para obtener los datos X, Y y Dt
                    self.$refs.modal_signature_seguridata.close();

                    self.formdata.points = sigPad[index].getDataInJSON();
                    self.$root.user.signatureUrl = sigPad[index].toDataURL();
                }

            }

        });
    },


    removeImage: function (target) {
      if(target == 'imagePhoto'){
        this.imagePhoto = false;
      }
      if(target == 'imageSignature'){
        this.$root.user.signatureUrl = false;
      }
    },

    onSubmit(){
        var formData = new FormData();
        formData.append("signature", this.$root.user.signatureUrl);
        formData.append("points", this.formdata.points);

        axios.post(tools.url('/api/user/signature'), formData).then((response)=>{
            if(response.data.status == 'success'){
                this.modal.block = false;
                this.modal.icon = "success";
                this.modal.msg = response.data.msg;
                this.$root.auth();
                if(this.$root.contracts_id != null){
                    this.$router.push('/usuario/contratos');
                }
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
    }
  },
  mounted(){
      if(this.$root.contracts_id != null){
        this.openSignatureModalSD();
      }
  }
}
</script>
