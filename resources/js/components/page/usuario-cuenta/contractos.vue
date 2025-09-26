<template lang="html">
  <div class="contracts-page">
    <div class="row align-items-center">
      <div class="col-7">
        <h5 class="d-inline-block main-page-title">{{ $t("account.documents.title") }}</h5>
      </div>

      <div class="col-5 text-center" style="text-align: right !important;" v-if="$root.user.access == 1">
        <router-link class="btn btn-sm btn-s1 bg-blue" to="/usuario/nuevo-contrato" v-if="$root.user.credits > 0 && $root.user.alldocument == true"><i class="fal fa-cabinet-filing"></i> {{ $t("account.documents.new_document") }}</router-link>
        
        <p v-if="$root.user.credits > 0">{{ $t("account.documents.aviable_credits") }}: {{$root.user.credits}}</p>
        <router-link class="btn btn-sm btn-s1 bg-blue" to="/creditos" v-if="$root.user.credits == 0"><i class="fal fa-credit-card"></i> {{ $t("account.documents.buy_credits") }}</router-link>
        
        <p v-if="$root.user.alldocument == false">{{ $t("account.documents.complete_info") }} <span @click="showDocumentsLees" style="cursor:pointer;border-radius: 50%; border:solid black 1px;padding-left: 5px;padding-right: 5px;padding-top: 1px;padding-bottom: 1px;">{{$root.user.documents_less_size}}</span></p>
      </div>
      <div v-else>
        <p >{{ $t("account.documents.account_auth") }} </p>
      </div>
      <div class="col-5 text-center" style="text-align: right !important;">
      </div>
    </div>

    <hr class="c-hr" />

    <div class="box-contracts">

      <div class="tab">
        <button class="tablinks" style="width:25%;padding: 14px 10px !important;" :class="active1" @click="active = 1">{{ $t("account.documents.waiting_signature") }}<br><span style="border-radius: 50%; border:solid black 1px;padding-left: 5px;padding-right: 5px;padding-top: 1px;padding-bottom: 1px;">{{contracts.waiting_signature.length}}</span></button>
        <button class="tablinks" style="width:25%;padding: 14px 10px !important;" :class="active2" @click="active = 2">{{ $t("account.documents.pending_sign") }}<br><span style="border-radius: 50%; border:solid black 1px;padding-left: 5px;padding-right: 5px;padding-top: 1px;padding-bottom: 1px;">{{contracts.pending_signature.length}}</span></button>
        <button class="tablinks" style="width:25%;padding: 14px 10px !important;" :class="active3" @click="active = 3">{{ $t("account.documents.signed") }}<br><span style="border-radius: 50%; border:solid black 1px;padding-left: 5px;padding-right: 5px;padding-top: 1px;padding-bottom: 1px;">{{contracts.signatured.length}}</span></button>
        <button class="tablinks" style="width:25%;padding: 14px 10px !important;" :class="active4" @click="active = 4">{{ $t("account.documents.canceled") }}<br><span style="border-radius: 50%; border:solid black 1px;padding-left: 5px;padding-right: 5px;padding-top: 1px;padding-bottom: 1px;">{{contracts.canceled.length}}</span></button>

      </div>
      <div class="box-contract" v-for="(c, cInx) in contracts.waiting_signature" :key="'cInx-'+cInx" v-if="active == 1">

        <div class="col-lg-12 box">
          <label class="label">ID:</label>
          <h6>NN000{{ c.id }}</h6>
        </div>

        <div class="col-lg-6 box">
          <label class="label">{{ $t("account.documents.document_type") }}:</label>
          <h6>{{ c.contrac_name }}</h6>
        </div>

        <div class="col-lg-6 box">
          <label class="label">{{ $t("account.documents.date_realization") }}:</label>
          <h6>{{ c.created }}</h6>
        </div>

        <div class="col-6 box">
          <label class="label">{{ $t("account.documents.contractor") }}:</label>
          <h6 v-if="c.user_name != 'Amha'">{{ c.user_name }}</h6>
          <h6 v-else>{{ c.company_amha }}</h6>
        </div>
        <div class="col-6 box">
          <label class="label">{{ $t("account.documents.counterpart") }}:</label>
          <h6 v-for="(contra,indxx) in c.users_contra">{{ contra.name }} <span style="color: #adadad;">({{ contra.email }})</span></h6>
        </div>

        <!-- <div class="col-6 box">
          <label class="label">Sello de tempo:</label>
          <h6>{{ c.stamp }}</h6>
        </div>

        <div class="col-6 box">
          <label class="label">Certificado digital:</label>
          <h6>{{ c.certificate }}</h6>
        </div> -->

        <div class="col-6 box">
          <label class="label">{{ $t("account.documents.signature_date") }} {{$root.user.name}}:</label>
          <h6>{{ c.date_signature_user }}</h6>
        </div>

        <div class="col-6 box"  v-for="(contra,indxx) in c.users_contra">
          <label class="label">{{ $t("account.documents.signature_date") }} {{ contra.name }}:</label>
          <h6 v-if="contra.date_signature_user != null">{{ contra.date_signature_user }}</h6>
          <h6 v-else style="color:red">{{ $t("account.documents.pending") }} <button @click="sendEmail(c.id,contra.id)" class="btn btn-sm btn-s1 bg-blue">{{ $t("account.documents.resend") }}</button></h6>
        </div>


        
         <div class="col-12 box">
            <a :href="c.documentUrl" target="_blank" class="btn btn-sm btn-s1 bg-blue" style="width: 100%;background-color: #000000;border-color: #000000"><i class="fas fa-file-pdf"></i> Descargar pdf</a>
        </div> 

        


        <div class="col-12 box" v-if="$root.user.id == c.user_contra_id && c.status == 'Pendiente de firma'">
          <button class="btn btn-s1 bg-blue" @click="openSignatureModalSD(c.id,c.documentUrl)">{{ $t("account.documents.sign") }}</button>
        </div>
      </div>
      <div class="box-contract" v-for="(c, cInx) in contracts.pending_signature" :key="'cInx-'+cInx" v-if="active == 2">

        <div class="col-lg-12 box">
          <label class="label">ID:</label>
          <h6>NN000{{ c.id }}</h6>
        </div>

        <div class="col-lg-6 box">
          <label class="label">{{ $t("account.documents.document_type") }}:</label>
          <h6>{{ c.contrac_name }}</h6>
        </div>

        <div class="col-lg-6 box">
          <label class="label">{{ $t("account.documents.date_realization") }}:</label>
          <h6>{{ c.created }}</h6>
        </div>

        <div class="col-6 box">
          <label class="label">{{ $t("account.documents.contractor") }}:</label>
          <h6 v-if="c.user_name != 'Amha'">{{ c.user_name }}</h6>
          <h6 v-else>{{ c.company_amha }}</h6>
        </div>
        <div class="col-6 box">
          <label class="label">{{ $t("account.documents.counterpart") }}:</label>
          <h6 v-for="(contra,indxx) in c.users_contra">{{ contra.name }} <span style="color: #adadad;">({{ contra.email }})</span></h6>
        </div>

        <!-- <div class="col-6 box">
          <label class="label">Sello de tempo:</label>
          <h6>{{ c.stamp }}</h6>
        </div>

        <div class="col-6 box">
          <label class="label">Certificado digital:</label>
          <h6>{{ c.certificate }}</h6>
        </div> -->

        <div class="col-6 box">
          <label class="label">{{ $t("account.documents.signature_date") }} {{$root.user.name}}:</label>
          <h6>{{ c.date_signature_user }}</h6>
        </div>

        <div class="col-6 box"  v-for="(contra,indxx) in c.users_contra">
          <label class="label">{{ $t("account.documents.signature_date") }} {{ contra.name }}:</label>
          <h6 v-if="contra.date_signature_user != null">{{ contra.date_signature_user }}</h6>
          <h6 v-else style="color:red">{{ $t("account.documents.pending") }}</h6>
        </div>


        <div class="col-12 box">
            <a :href="c.documentUrl" target="_blank" class="btn btn-sm btn-s1 bg-blue" style="width: 100%;background-color: #000000;border-color: #000000"><i class="fas fa-file-pdf"></i> Descargar pdf</a>
        </div> 


            <div v-if="$root.user.signature_base64 != null && $root.user.points_signature">
              <div class="col-12 box" v-if="c.status == 'Pendiente de firma'">
                <button class="btn btn-s1 bg-blue" @click="openSignatureModalSD(c.id,c.documentUrl)">{{ $t("account.documents.sign") }}</button>
              </div>
            </div>
            <div v-else class="col-12 box" >
                <router-link class="btn btn-s1 bg-blue" to="/usuario/firma" style="background-color: #cccccc; border-color: #cccccc;">{{ $t("account.documents.sign_less") }}</router-link>
            </div>
        <div>

        </div>
      </div>
      <div style="display: flex;max-width: 100%;padding: 14px 2px 8px;"  v-if="active == 3">
        
        
        <div class="col-3 col-menu">
          <div class="white-box">
              <button @click="addFolder" class="btn btn-sm btn-s1 bg-blue" style="font-size: 11px;"> <i class="fas fa-plus"></i>  {{ $t("account.documents.newfolder") }}</button>
              <div class="box-menu">
                <p class="item">
                  <a class="btn-menu btn-menu router-link-exact-active router-link-active" v-if="activeFold == 99999999" > {{ $t("account.documents.all") }}</a>
                  <a class="btn-menu" v-else  @click="activeFold = 99999999"> ...</a>
                </p>
                <p class="item"  v-for="(fold, cInx) in folders" :key="'fold-'+cInx">
                  <a class="btn-menu btn-menu router-link-exact-active router-link-active" v-if="activeFold == fold.id" @click="activeFold = fold.id"> {{ fold.name }}</a>
                  <a class="btn-menu" v-else @click="activeFold = fold.id"> {{ fold.name }}</a>
                </p>
              </div>
            </div>
        </div>
        <div class="col-9">
            <button @click="moveFolder" class="btn btn-success" style="font-size: 12px;width: 100%;"> <i class="fas fa-exchange-alt"></i> {{ $t("account.documents.move_to_folder") }}</button>
            <button v-if="activeFold != 99999999" @click="deleteFolder" class="btn btn-danger" style="font-size: 12px;width: 100%;"> <i class="fas fa-trash"></i> {{ $t("account.documents.delete_folder") }}</button>
            
            <div class="box-contract" v-for="(c, cInx) in contracts_signatured" :key="'cInx-'+cInx">
              <input type="checkbox" style="text-align: left;transform: scale(1.5);margin-left: 16px;" v-model="form_change.check_signatured" :value="c.id" :id="'check-'+c.id">
              <br><br>
              <div class="col-lg-12 box">
                  <label class="label">{{ $t("account.documents.folder") }}:</label>
                  <h6>{{ c.folder_name }}</h6>
                </div>
                <div class="col-lg-12 box">
                  <label class="label">ID:</label>
                  <h6>NN000{{ c.id }}</h6>
                </div>

                <div class="col-lg-6 box">
                  <label class="label">{{ $t("account.documents.document_type") }}:</label>
                  <h6>{{ c.contrac_name }}</h6>
                </div>

                <div class="col-lg-6 box">
                  <label class="label">{{ $t("account.documents.date_realization") }}:</label>
                  <h6>{{ c.created }}</h6>
                </div>

                <div class="col-6 box">
                  <label class="label">{{ $t("account.documents.contractor") }}:</label>
                  <h6>{{ c.user_name }}</h6>
                </div>
                <div class="col-6 box" v-if="c.images_id == null">
                  <label class="label">{{ $t("account.documents.counterpart") }}:</label>
                  <h6 v-for="(contra,indxx) in c.users_contra">{{ contra.name }} <span style="color: #adadad;">({{ contra.email }})</span></h6>
                </div>

                <!-- <div class="col-6 box">
                  <label class="label">Sello de tempo:</label>
                  <h6>{{ c.stamp }}</h6>
                </div>

                <div class="col-6 box">
                  <label class="label">Certificado digital:</label>
                  <h6>{{ c.certificate }}</h6>
                </div> -->

                <div class="col-6 box"  v-if="c.images_id != null">
                  <label class="label">{{ $t("account.documents.signature_date") }} {{$root.user.name}}:</label>
                  <h6>{{ c.date_signature_user }}</h6>
                </div>

                <div class="col-6 box"  v-for="(contra,indxx) in c.users_contra">
                  <label class="label">{{ $t("account.documents.signature_date") }} {{ contra.name }}:</label>
                  <h6 v-if="contra.date_signature_user != null">{{ contra.date_signature_user }}</h6>
                  <h6 v-else style="color:red">{{ $t("account.documents.pending") }}</h6>
                </div>

                
                <div class="col-12 box">
                    <a :href="c.documentUrl" target="_blank" class="btn btn-sm btn-s1 bg-blue" style="width: 100%;"><i class="fas fa-file-pdf"></i> {{ $t("account.documents.download") }} pdf</a>
                </div>

                <div class="col-12 box" v-if="c.documentUrlNom != null">
                    <a :href="c.documentUrlNom" target="_blank" class="btn btn-sm btn-s1 bg-blue" style="width: 100%;"><i class="fas fa-asterisk"></i> {{ $t("account.documents.download") }} NOM</a>
                </div>
                <div class="col-12 box" v-if="c.documentUrlNomData != null">
                    <a :href="c.documentUrlNomData" target="_blank" class="btn btn-sm btn-s1 bg-blue" style="width: 100%;"><i class="fas fa-file-alt"></i> {{ $t("account.documents.download") }} NOM PDF</a>
                </div>

                
                <div class="col-6 box" v-if="c.user_id == $root.user.id"></div>
                <div class="col-6 box" v-if="c.user_id == $root.user.id">
                    <a @click="cancelContract(c.id)" class="btn btn-sm btn-s1" style="width: 100%;background-color: red;"><i class="fas fa-times-circle"></i> {{ $t("account.documents.cancel") }}</a>
                </div>
            </div>
        </div>
      </div>
      <div class="box-contract" v-for="(c, cInx) in contracts.canceled" :key="'cInx-'+cInx" v-if="active == 4">
             
              <div class="col-lg-12 box">
                <label class="label">ID:</label>
                <h6>NN000{{ c.id }}</h6>
              </div>

              <div class="col-lg-6 box">
                <label class="label">{{ $t("account.documents.document_type") }}:</label>
                <h6>{{ c.contrac_name }}</h6>
              </div>

              <div class="col-lg-6 box">
                <label class="label">{{ $t("account.documents.date_realization") }}:</label>
                <h6>{{ c.created }}</h6>
              </div>

              <div class="col-6 box">
                <label class="label">{{ $t("account.documents.contractor") }}:</label>
                <h6 v-if="c.user_name != 'Amha'">{{ c.user_name }}</h6>
                <h6 v-else>{{ c.company_amha }}</h6>
              </div>
              <div class="col-6 box" v-if="c.images_id == null">
                <label class="label">{{ $t("account.documents.counterpart") }}:</label>
                <h6 v-for="(contra,indxx) in c.users_contra">{{ contra.name }} <span style="color: #adadad;">({{ contra.email }})</span></h6>
              </div>

              <!-- <div class="col-6 box">
                <label class="label">Sello de tempo:</label>
                <h6>{{ c.stamp }}</h6>
              </div>

              <div class="col-6 box">
                <label class="label">Certificado digital:</label>
                <h6>{{ c.certificate }}</h6>
              </div> -->

              <div class="col-6 box"  v-if="c.images_id != null">
                <label class="label">{{ $t("account.documents.signature_date") }} {{$root.user.name}}:</label>
                <h6>{{ c.date_signature_user }}</h6>
              </div>

              <div class="col-6 box"  v-for="(contra,indxx) in c.users_contra">
                <label class="label">{{ $t("account.documents.signature_date") }} {{ contra.name }}:</label>
                <h6 v-if="contra.date_signature_user != null">{{ contra.date_signature_user }}</h6>
                <h6 v-else style="color:red">{{ $t("account.documents.pending") }}</h6>
              </div>


              <div class="col-lg-6 box">
                <label class="label">{{ $t("account.documents.cancellation_folio") }}:</label>
                <h6>{{ c.id_cancel }}</h6>
              </div>

              <div class="col-lg-6 box">
                <label class="label">{{ $t("account.documents.date_cacelation") }}:</label>
                <h6>{{ c.date_cancel }}</h6>
              </div>
              
              <!-- <div class="col-12 box">
                  <a :href="c.documentUrl" target="_blank" class="btn btn-sm btn-s1 bg-blue" style="width: 100%;"><i class="fas fa-file-pdf"></i> Descargar pdf</a>
              </div> -->
          
      </div>

    </div>

    <sweet-modal :icon="modal.icon" :blocking="modal.block" :hide-close-button="modal.block" ref="modal">
      
      <div class="fa-3x" v-if="modal.icon== ''"><i class="fas fa-spinner fa-pulse"></i></div><br/>
      <div v-html="modal.msg"></div>
      <div class="col-12 text-center" style="padding-top: 20px;" v-if="modal.icon == 'success'">
      <b-button class="btn btn-primary" slot="button" v-on:click.prevent="$refs.modal.close();">{{ $t("account.documents.accept") }}</b-button>
      </div>
  </sweet-modal>

  <sweet-modal ref="modal_signature_seguridata" >
    <!-- <p>A continuación firma dentro del recuadro similar a la firma de referencia inicial que capturaste en el registro. Si la firma no es similar no podrás certificar documentos.</p>
     -->
     <p>{{ $t("account.documents.modal_sing_title") }}</p>
     <p style="text-align: center;">{{ $t("account.documents.modal_sing_subtitle") }}</p>
      <br>
     <!-- <div class="fa-3x" v-if="showPreloader"><i class="fas fa-spinner fa-pulse"></i></div><br/> -->
      <!-- <embed :src="documentUrl_save+'#toolbar=0'" width="500" height="450" @load="hidePreloader" @error="showPreloader"> -->

      <!-- <div id="content-wrapper" role="main">
            <div id="content" class="canvasDivContainer">
                <div class="signCaptureCanvas"></div>
            </div>
      </div> -->
      <a :href="documentUrl_save" target="_blank" style="color: black;"><img src="https://notarionet.com/public/images/contrato.png" width="100px"><br>
        <span style="color: black;">{{ $t("account.documents.view_document") }}</span>
        </a>
      <br><br>

      <button class="btn btn-s1 bg-blue"  @click="saveSignature(id_save);">{{ $t("account.documents.sign") }}</button>
      
      <!-- <div>
        <p>Firma de referencia</p>
        <img :src="$root.user.signatureUrl" style="300px">
      </div> -->
  </sweet-modal>

    <sweet-modal ref="foldermodal">
        <b-form class="c-f-g-2-wrp sm" @submit="onSubmit">
          <b-form-group class="cus-f-group-2" label="Nombre de la carpeta">
            <b-form-input type="text" v-model="formfolder.name"  size="sm" placeholder="" />
          </b-form-group>

          <b-button type="submit" class="btn-s1 bg-blue">{{ $t("account.documents.save") }}</b-button>

        </b-form>
        
    </sweet-modal>
    <sweet-modal ref="foldermodalmove">
      <b-form class="c-f-g-2-wrp sm" @submit="onSubmitChange">
        <b-form-group class="cus-f-group-2" label="Carpeta de destino">
          <b-form-select required v-model="form_change.folders_id" size="sm">
            <b-form-select-option :value="null">Selecciona una opcion</b-form-select-option>
            <b-form-select-option :value="s.id" v-for="(s, srInx) in folders" :key="'fold-'+srInx">{{ s.name }}</b-form-select-option>
          </b-form-select>
        </b-form-group>


        <b-button type="submit" class="btn-s1 bg-blue">Mover</b-button>

      </b-form>
    </sweet-modal>

  </div>
</template>

<script>
export default {
  data(){
    return{
      activeFold:99999999,
      contracts: {
        waiting_signature:[],
        pending_signature:[],
        signatured:[],
        canceled:[],
      },
      contracts_signatured:[],
      modal:{
          msg:'',
          icon:'',
          block:false,
        },
        active:2,
        formsignature:{
            points:[],
            signatureUrl:null
        },
        id_save:null,
        documentUrl_save:null,
        formfolder:{
          name:null
        },
        folders:[],
        form_change:{
          check_signatured:[],
          folders_id:null,
        },
        showPreloader: true
        
    }
  },
  computed:{
      active1: function()
      {
        return (this.active == 1) ? 'active' : '';
      },

      active2: function()
      {
        return (this.active == 2) ? 'active' : '';
      },
      active3: function()
      {
        return (this.active == 3) ? 'active' : '';
      },
      active4: function()
      {
        return (this.active == 4) ? 'active' : '';
      }
    },
    watch:{
        'activeFold':function(val){
          var data = [];
          if(val == 99999999){
              data = this.contracts.signatured;
          }
          else{
            for (let z = 0; z < this.contracts.signatured.length; z++) {
              if(this.contracts.signatured[z]['folders_id'] == val){
                data.push(this.contracts.signatured[z]);
              }
              
            }
          }
            
          this.contracts_signatured = data;
        }
    },
  methods: {
    getContracts(){
        this.modal.icon = "";
        if(this.$i18n.locale == 'en'){
          this.modal.msg = 'Loading...';
        }
        else{
          this.modal.msg = 'Cargando...';
        }
        
        this.modal.block = true;
        this.$refs.modal.open();

        axios.get(tools.url('/api/mycontracts')).then((response)=>{
          this.contracts = response.data;

          this.contracts_signatured = this.contracts.signatured;

          this.modal.icon = "";
          this.modal.msg = '';
          this.modal.block = false;
          this.$refs.modal.close();

          if(this.$root.contracts_id != null){
            this.active = 2;
            var id = null;
            var documentUrl = null;
            for (let x = 0; x < this.contracts.pending_signature.length; x++) {
                if(this.contracts.pending_signature[x]['id'] == this.$root.contracts_id){
                    id = this.contracts.pending_signature[x]['id'];
                    documentUrl = this.contracts.pending_signature[x]['documentUrl'];
                } 
              
            }
            this.openSignatureModalSD(id,documentUrl);
            this.id_save = id;
          }

        }).catch((error)=>{
           console.log(error);
           this.modal.icon = "";
            this.modal.msg = 'Error al consultar los contratos';
            this.modal.block = false;
            this.$refs.modal.close();
        });
    },
    getContractsTwo(){
        axios.get(tools.url('/api/mycontracts')).then((response)=>{
          this.contracts = response.data;

          if(this.active == 3){
            var data = [];
            if(this.activeFold == 99999999){
                data = this.contracts.signatured;
            }
            else{
              for (let z = 0; z < this.contracts.signatured.length; z++) {
                if(this.contracts.signatured[z]['folders_id'] == this.activeFold){
                  data.push(this.contracts.signatured[z]);
                }
                
              }
            }
              
            this.contracts_signatured = data;
          }
        }).catch((error)=>{
        });
    },
    saveSignature(id){
      this.$refs.modal_signature_seguridata.close();
        this.modal.icon = "";
        if(this.$i18n.locale == 'en'){
          this.modal.msg = 'Loading...';
        }
        else{
          this.modal.msg = 'Cargando...';
        }
        this.modal.block = true;
        this.$refs.modal.open();
        axios.get(tools.url('/api/user/saveSignatureContracts/'+id)).then((response)=>{
            this.modal.block = false;
            this.modal.icon = "success";
            if(this.$i18n.locale == 'en'){
              this.modal.msg = 'Successfully signed contract<br>';
            }
            else{
              this.modal.msg = 'Contrato firmado correctamente <br>';
            }

            
            if(response.data.stripe_link != null){
                window.open(response.data.stripe_link,'_blank');
            }
          this.getContractsTwo();
        }).catch((error)=>{
           this.modal.block = false;
            this.modal.icon = "error";
            this.modal.msg = 'Error al guardar la información';
        });
    },
    cancelContract(id){
      let text = "¿Seguro que deseas cancelar este contrato?";

      if (confirm(text) == true) {
          this.modal.icon = "";
          this.modal.msg = 'Cargando...';
          this.modal.block = true;
          this.$refs.modal.open();
          axios.get(tools.url('/api/user/cancelContracts/'+id)).then((response)=>{
              this.modal.block = false;
              this.modal.icon = "success";
              this.modal.msg = 'Contrato cancelado correctamente <br><a href="'+ response.data.documentUrl +'" target="_blank" class="btn btn-sm btn-s1 bg-blue" style="width: 40%;"><i class="fas fa-file-pdf"></i> Descargar pdf</a>';

            this.getContractsTwo();

            

          }).catch((error)=>{
            this.modal.block = false;
              this.modal.icon = "error";
              this.modal.msg = 'Error al guardar la información';
          });
      } else {
       
      }
    },
    sendEmail(orders_id,user_id){
        this.modal.icon = "";
        this.modal.msg = 'Cargando...';
        this.modal.block = true;
        this.$refs.modal.open();
        var formdata = {
            'orders_id':orders_id,
            'order_contacts_id':user_id
        };
        axios.post(tools.url('/api/user/sedEmail'),formdata).then((response)=>{
            this.modal.block = false;
            this.modal.icon = "success";
            this.modal.msg = 'Correo enviado correctamente';

          this.getContractsTwo();
        }).catch((error)=>{
           this.modal.block = false;
            this.modal.icon = "error";
            this.modal.msg = 'Error al guardar la información';
        });
    },
    openSignatureModalSD(id,documentUrl){
      this.id_save = id;
      this.documentUrl_save = documentUrl;

      
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
                    self.$refs.modal_signature_seguridata.close();
                   
                    self.formsignature.points = sigPad[index].getDataInJSON();
                    self.compareSiganature();
                    //self.formsignature.signatureUrl = sigPad[index].toDataURL();
                //}
                
            }
            
        });
    },
    compareSiganature(){
        this.modal.icon = "";
        if(this.$i18n.locale == 'en'){
            this.modal.msg = 'Validating signature';
        }
         else{
          this.modal.msg = 'Validando firma...';
        }

       
        
        this.modal.block = true;
        this.$refs.modal.open();
        axios.post(tools.url('/api/comparesignature'),this.formsignature).then((response)=>{
            if (response.data.status == true) {
              if (response.data.prediction > 50) {
                  //this.modal.block = false;
                  //this.$refs.modal.close();
                  this.saveSignature(this.id_save);
              }
              else{
                  this.modal.block = false;
                  this.$refs.modal.close();
                  alert('La firma no coincide con la firma registrada anteriormente, intenta nuevamente');
                  this.openSignatureModalSD(this.id_save,this.documentUrl_save);
              }
              
            }
            else{
                this.modal.block = false;
                this.$refs.modal.close();
                alert('La firma no coincide con la firma registrada anteriormente, intenta nuevamente');
                this.openSignatureModalSD(this.id_save,this.documentUrl_save);
            } 
            
        }).catch((error)=>{
              this.modal.block = false;
              this.$refs.modal.close();
              alert('Error al comparar la firma, no es similar a la firma inicial que realizaste y que nuestro algoritmo toma de referencia.');
              this.openSignatureModalSD(this.id_save,this.documentUrl_save);
        });
    },
    showDocumentsLees(){
        this.modal.msg = this.$root.user.documents_less;
        this.modal.icon = 'warning';
        this.modal.block = null;
        this.$refs.modal.open();
    },
    addFolder(){
        this.formfolder.name = null;
        this.formfolder.users_id = this.$root.user.id;
        this.$refs.foldermodal.open();
    },
    onSubmit(event){
      event.preventDefault()

        axios.post(tools.url('/api/user/folders'), this.formfolder).then((response)=>{
          this.$refs.foldermodal.close();
          this.getFolders();
        }).catch((error)=>{
          this.$refs.foldermodal.close();
            this.modal.block = false;
            this.modal.icon = "error";
            this.modal.msg = response.data.msg;
            
            this.handleErrors(error);
        });
    },
    getFolders(){
      
        axios.get(tools.url('/api/myfolders')).then((response)=>{
          this.folders = response.data;
        }).catch((error)=>{
        });
    },
    moveFolder(){
        if(this.form_change.check_signatured.length > 0){
            this.$refs.foldermodalmove.open();
        } 
        else{
          if(this.$i18n.locale == 'en'){
            alert('Select at least one document');
          }
          else{
            alert('Seleciona al menos un documento');
          }
            
        }

        
    },

    onSubmitChange(event){
      event.preventDefault()

        axios.post(tools.url('/api/user/foldersmove'), this.form_change).then((response)=>{
          this.form_change.check_signatured = [];
          this.form_change.folders_id = null;
          this.getContractsTwo();
          this.$refs.foldermodalmove.close();

          this.modal.block = false;
            this.modal.icon = "success";
            
            if(this.$i18n.locale == 'en'){
              this.modal.msg = 'Documents added to the folder';
            }
            else{
              this.modal.msg = 'Documentos agregados a la carpeta';
            }

            this.$refs.modal.open();
        }).catch((error)=>{
          this.$refs.foldermodalmove.close();
            this.modal.block = false;
            this.modal.icon = "error";
            this.modal.msg = response.data.msg;
            
            this.handleErrors(error);
        });
    },
    deleteFolder(){
      if(this.$i18n.locale == 'en'){
        if (confirm('Are you sure you want to delete this folder? Your documents will go to the "All" section') == true) {
            axios.delete(tools.url('/api/user/folders/'+this.activeFold), this.form_change).then((response)=>{
              this.modal.block = false;
                this.modal.icon = "success";
                this.modal.msg = 'Delete folder<br>your documents are located in the "All" section';
              this.$refs.modal.open();
              this.getContractsTwo();
              this.getFolders();
              this.activeFold = 99999999;
            }).catch((error)=>{
              
                this.modal.block = false;
                this.modal.icon = "error";
                this.modal.msg = response.data.msg;
                this.$refs.modal.open();
                
            });
          }
      }
      else{
          if (confirm('¿Seguro que deseas borrar esta carpeta?, tus documentos se iran al apartado "Todos"') == true) {
            axios.delete(tools.url('/api/user/folders/'+this.activeFold), this.form_change).then((response)=>{
              this.modal.block = false;
                this.modal.icon = "success";
                this.modal.msg = 'Carpeta elimina<br>tus documentos se ecuentran en el apartado "Todos"';
              this.$refs.modal.open();
              this.getContractsTwo();
              this.getFolders();
              this.activeFold = 99999999;
            }).catch((error)=>{
              
                this.modal.block = false;
                this.modal.icon = "error";
                this.modal.msg = response.data.msg;
                this.$refs.modal.open();
                
            });
          }
      }


      

    },
    reloadDocs(){
      var data = [];
          if(this.activeFold == 99999999){
              data = this.contracts.signatured;
          }
          else{
            for (let z = 0; z < this.contracts.signatured.length; z++) {
              if(this.contracts.signatured[z]['folders_id'] == this.activeFold){
                data.push(this.contracts.signatured[z]);
              }
              
            }
          }
            
          this.contracts_signatured = data;
    },
    hidePreloader() {
      this.showPreloader = false;
    },
    showPreloader() {
      this.showPreloader = true;
    }

  },
  mounted(){
    if(this.$root.logged == false){
         this.$router.push("/login");
     }
     this.getFolders();
    this.getContracts();

    
  }
}
</script>
<style scoped="">
body {font-family: Arial;}

/* Style the tab */
.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 13px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}
</style>
