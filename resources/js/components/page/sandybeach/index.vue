<template lang="html">
    <div id="text-page">
      
      <div class="container" >
        <h1 class="mb-2 page-title">{{content.contract.title}}</h1>
        <embed :src="content.documentUrl+'#toolbar=0'" width="500" height="450" >

        <div id="content-wrapper" role="main">
            <div id="content" class="canvasDivContainer">
                <div class="signCaptureCanvas"></div>
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
        content: '',
        formsignature:{
            points:[],
            users_id:null,
        },
        modal:{
            msg:'',
            icon:'',
            block:false,
        },
      }
    },
  
    methods: {
        getContent: function(){
            axios.get(tools.url('/api/ordersandybeach/'+this.$route.params.id)).then((response)=>{
            this.content = response.data;;
            }).catch((error)=>{
            console.log(error);
            })
        },
        openSignatureModalSD(){
       
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
                        displayDialog: 'N',// Desplegar dialog en caso de que se detecte que el trazo no concuerda con la configuración
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
                    /*var statuss = sigPad[index].verify();
                    console.log(statuss);
                    if (statuss['status'] == 'Rejected') {
                    
                    
                    $(".signature_background").css("display", "none");
                    alert('La firma no es valida');
                    }
                    else{*/
                    
                        // Función para obtener los datos X, Y y Dt
                
                        
                        self.formsignature.points = sigPad[index].getDataInJSON();
                        self.formsignaturesignatureUrl = sigPad[index].toDataURL();
                        self.compareSiganature();
                    //}
                    
                }
                
            });
        },
        compareSiganature(){
            this.modal.icon = "";
            this.modal.msg = 'Cargando...';
            this.modal.block = true;
            this.$refs.modal.open();

            this.formsignature.users_id = this.content.contact.user_id;
           
            axios.post(tools.url('/api/signature_sandybeach'),this.formsignature).then((response)=>{
        
                this.saveSignature();
                
            }).catch((error)=>{
                this.modal.block = false;
                this.modal.icon = "error";
                this.modal.msg = 'Error al guardar la información';
            });
        },
        saveSignature(){
            
            
            axios.get(tools.url('/api/user/saveSignatureContracts_sandybeach/'+id)).then((response)=>{
                this.modal.block = false;
                this.$refs.modal.close();

                this.modal.block = false;
                this.modal.icon = "success";
                this.modal.msg = 'Contrato firmado correctamente <br>';
                
                window.open('https://www.sandybeachcoastal.com','_blank');
                
            this.getContractsTwo();
            }).catch((error)=>{
                this.modal.block = false;
                this.modal.icon = "error";
                this.modal.msg = 'Error al guardar la información!';
            });
        },

    },
  
    beforeMount(){
       this.getContent();
       this.openSignatureModalSD();
    }
  }
  </script>
  