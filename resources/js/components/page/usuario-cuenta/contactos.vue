<template lang="html">
<div class="contacts-page">
  <div class="row align-items-center">
    <div class="col-6">
      <h5 class="d-inline-block main-page-title">{{ $t("account.contacts.title") }}</h5>
      <br>
    </div>

    <div class="col-6 text-right">
      <a class="btn btn-sm btn-s1 bg-blue" @click="$refs['modal-new-contact'].show();"><i class="fas fa-user-plus"></i> {{ $t("account.contacts.add_contact") }}</a>
    </div>

    <div class="col-12">
      <br>
      <p> {{ $t("account.contacts.subtitle") }}</p>

    </div>
  </div>

  <hr class="c-hr" />

  <b-form class="form-search-contact" @submit="onSubmitSearch">
    <b-form-input type="text" v-model="formSearch.keyword" required :placeholder="$t('account.contacts.search')" />
    <b-button type="submit"></b-button>
  </b-form>

  <div class="box-contacts"  v-if="contacts.length > 0">
    <div class="col-12 col-contact" v-for="(c, cInx) in contacts" :key="'cInx-'+cInx">
      <!-- <div class="wrp" @click="openContact"> -->
      <div class="wrp">
        <div class="col col-photo">
          <div class="photo">
            <img src="public/images/shared/empty.png" v-bind:style="{ backgroundImage: 'url('+c.imageUrl+')' }">
          </div>
        </div>

        <div class="col col-info">
           <div class="row">
            <div class="col-lg-8 box">
                <h6 class="name">{{ c.name }}</h6>
            </div>
            <div class="col-lg-4 box">
                <button class="btn btn-sm btn-s1 bg-blue" @click="editRow(c)" v-if="c.status != 'completado'"><i class="fas fa-edit"></i> Editar</button>
                <br v-if="c.status != 'completado'"><br v-if="c.status != 'completado'">
                <button class="btn btn-sm btn-s1 btn-danger" @click="deleteRow(c.user_contact_id)"><i class="fas fa-trash"></i> Eliminar</button>
            </div>
         
            <div class="col-lg-6 box">
              <label class="label">Tel:</label>
              <h6>{{ c.phone }}</h6>
            </div>

            <div class="col-lg-6 box">
              <label class="label">{{$t('account.contacts.email')}}:</label>
              <h6>{{ c.email }}</h6>
            </div>

            <div class="col-lg-12 box">
              <label class="label">{{ $t('account.contacts.street') }}:</label>
              <h6 v-if="c.address != null">{{c.address.street}}</h6>
            </div>

            <div class="col-lg-6 box">
              <label class="label">{{ $t('account.contacts.neighborhood') }}:</label>
              <h6 v-if="c.address != null">{{c.address.neighborhood}}</h6>
            </div>

            <div class="col-lg-6 box">
              <label class="label">{{ $t('account.contacts.zip_code') }}:</label>
              <h6 v-if="c.address != null"> {{c.address.zipcode}}</h6>
            </div>

            <div v-if="c.status == 'completado'" class="col-lg-12">
                <p> {{ $t('account.contacts.contact_exist') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-contact" v-else>
    <br>
        <p style="text-align: center;">{{ $t("account.contacts.no_contacts") }}</p>
    </div>

  <!-- Modal nuevo contacto -->
  <b-modal modal-class="modal-content-s1" ref="modal-new-contact" :title="$t('account.contacts.add_contact')" no-close-on-backdrop no-close-on-esc centered hide-footer>
    <b-form @submit="onSubmitNew">

      <b-form-group class="cus-f-group-2" :label="$t('account.contacts.email') +' *'">
        <b-form-input type="email" v-model="formNew.email" size="sm" required placeholder="" />
       <!--  <small class="note txt-orange"><i class="fas fa-bell icon"></i> Se enviará un mensaje al email seleccionado para que complete el proceso de registro.</small> -->
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.contacts.full_name') +' *'">
        <b-form-input type="text" v-model="formNew.name" size="sm" required placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.contacts.phone') +' *'">
        <b-form-input type="text" v-model="formNew.phone" size="sm" required placeholder="" maxlength="10" minlength="10" />
      </b-form-group>
      <p v-if="formNew.status == 'ok'"> {{ $t('account.contacts.contact_exist') }}</p>

      <!-- <b-form-group class="cus-f-group-2" label="Calle*">
        <b-form-input type="text" v-model="formNew.street" size="sm" required placeholder="" />
      </b-form-group>

       <b-form-group class="cus-f-group-2" label="Numero exterior *">
        <b-form-input type="text" v-model="formNew.num_ext" size="sm" required placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" label="Numero interior">
        <b-form-input type="text" v-model="formNew.num_int" size="sm" placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" label="Colonia *">
        <b-form-input type="text" v-model="formNew.neighborhood" size="sm" required placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" label="Código postal *">
        <b-form-input type="text" v-model="formNew.zipcode" size="sm" required placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" label="Estado *">
        <b-form-select required v-model="formNew.state_id" size="sm">
          <b-form-select-option :value="null">Selecciona una opción</b-form-select-option>
          <b-form-select-option :value="s.id" v-for="(s, srInx) in states" :key="'srInx-'+srInx">{{ s.name }}</b-form-select-option>
        </b-form-select>
      </b-form-group>

      <b-form-group class="cus-f-group-2" label="Ciudad *">
        <b-form-select required v-model="formNew.town_id" size="sm">
          <b-form-select-option :value="null">Selecciona una opción</b-form-select-option>
          <b-form-select-option :value="t.id" v-for="(t, srInx) in towns" :key="'srInx-'+srInx" >{{ t.name }}</b-form-select-option>
        </b-form-select>
      </b-form-group> -->

      <b-button type="submit" class="btn-s1 bg-blue" v-if="formNew.id != null">{{ $t('account.contacts.edit_contact') }}</b-button>
      <b-button type="submit" class="btn-s1 bg-blue" v-else>{{ $t('account.contacts.add_contact') }}</b-button>

    </b-form>
  </b-modal>
  <!--  -->

  <!-- Modal editar/ver contacto -->
  <!-- NOTA. El usuario inicial puede aun editar al contacto hasta que el usuario se registre por el mismo, despues solo podrá ver la info -->
  <b-modal modal-class="modal-content-s1" ref="modal-edit-contact" title="Información de contacto" no-close-on-backdrop no-close-on-esc centered hide-footer>
    <b-form @submit="onSubmitEdit">

      <b-form-group class="cus-f-group-2" :label="$t('account.contacts.email') +' *'">
        <b-form-input type="email" v-model="formNew.email" size="sm" disabled required placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.contacts.neighborhood') +' *'">
        <b-form-input type="text" v-model="formNew.colonia" size="sm" required placeholder="" />
      </b-form-group>

      <b-form-group class="cus-f-group-2" :label="$t('account.contacts.zip_code') +' *'">
        <b-form-input type="text" v-model="formNew.cp" size="sm" disabled required placeholder="" />
      </b-form-group>

  

      <b-button type="submit" class="btn-s1 bg-blue">{{ $t('account.contacts.edit_contact') }}</b-button>

    </b-form>
  </b-modal>
  <!--  -->

  <sweet-modal :icon="modal.icon" :blocking="modal.block" :hide-close-button="modal.block" ref="modal">
    <div class="fa-3x" v-if="modal.icon== ''"><i class="fas fa-spinner fa-pulse"></i></div><br/>
    {{modal.msg}}
    <div class="col-12 text-center" style="padding-top: 20px;" v-if="modal.icon == 'success'">
    <b-button class="btn btn-primary" slot="button" v-on:click.prevent="$refs.modal.close();">Aceptar</b-button>
    </div>
  </sweet-modal>
</div>
</template>

<script>
export default {
  data(){
    return{
      contacts: [
        /*{ id: 1, status: 1, imgURL: 'public/images/pages/user/photo.jpg', nombre: 'Oscar Alejandro Lopez Lopez', tel: '33 14005000', email: 'email@gmail.com' },
        { id: 1, status: 2, imgURL: 'public/images/pages/user/photo.jpg', nombre: 'Alejandro Oscar Lopez Lopez', tel: '33 55000000', email: 'email2@gmail.com' },
        { id: 1, status: 1, imgURL: 'public/images/pages/user/photo.jpg', nombre: 'Alejandro Oscar Lopez Lopez', tel: '33 55000000', email: 'email2@gmail.com' },*/
      ],

      selectedCredit: {},

      formSearch: {
        keyword: null,
      },

      formNew:{
        state_id: null,
        town_id: null,
        status:0
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
    'formNew.state_id':function(val){
      if(val && !isNaN(val)){
        this.getTowns(val);
      }
    },
    'formNew.email':function(){
        if (this.formNew.email.length > 4) {
              this.checkContact();
        }
    }
  },

  methods: {
    onSubmitSearch(event) {
      event.preventDefault()
      console.log('xxxx');
    },

    onSubmitNew(event) {
        event.preventDefault()
        this.modal.icon = "";
        this.modal.msg = 'Cargando...';
        this.modal.block = true;
        this.$refs.modal.open();

        axios.post(tools.url('/api/contact'),this.formNew).then((response)=>{
            
            this.getContactsTwo();
            this.modal.block = false;
            this.modal.icon = "success";
            this.modal.msg = 'Contacto guardado correctamente';
            this.formNew = {
              state_id: null,
              town_id: null,
            };
            this.$refs['modal-new-contact'].hide();

            
        }).catch((error)=>{
            this.modal.block = false;
            this.modal.icon = "error";
            this.modal.msg = error.response.data.msg;
        });
    },

    onSubmitEdit(event) {
      event.preventDefault()
        this.modal.icon = "";
        this.modal.msg = 'Cargando...';
        this.modal.block = true;
        this.$refs.modal.open();

        axios.post(tools.url('/api/contact'),this.formNew).then((response)=>{
            
            this.getContactsTwo();
            this.modal.block = false;
            this.modal.icon = "success";
            this.modal.msg = 'Contacto modificado correctamente';
            this.formNew = {
              state_id: null,
              town_id: null,
            };
            this.$refs['modal-new-contact'].hide();

            
        }).catch((error)=>{
            this.modal.block = false;
            this.modal.icon = "error";
            this.modal.msg = error.response.data.msg;
        });
    },

    openContact(){
      // Editar el usuario si aun no se da de alta ...
      this.$refs['modal-edit-contact'].show()
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
    getContacts(){
      this.modal.icon = "";
        this.modal.msg = 'Cargando...';
        this.modal.block = true;
        this.$refs.modal.open();
        axios.get(tools.url('/api/contacts')).then((response)=>{
          this.contacts = response.data;
          this.modal.icon = "";
          this.modal.msg = '';
          this.modal.block = false;
          this.$refs.modal.close();
        }).catch((error)=>{
        

          this.modal.icon = "";
          this.modal.block = false;
         // alert('Error al consultar los contactos');
          
       
        });
    },
    getContactsTwo(){
    

        axios.get(tools.url('/api/contacts')).then((response)=>{
          this.contacts = response.data;

        }).catch((error)=>{

        });
    },
    deleteRow(id){
      if (confirm('¿Seguro que desea eliminar este registro?') == true) {
        axios.delete(tools.url('/api/user/contacts/'+id)).then((response)=>{
          this.modal.block = false;
          this.modal.icon = "success";
          this.modal.msg = 'Contacto eliminado correctamente';
          this.$refs.modal.open();
          this.getContactsTwo();
        }).catch((error)=>{
           this.modal.block = false;
          this.modal.icon = "error";
          this.modal.msg = 'Ocurrio un error al eliminar';
          this.$refs.modal.open();
        });
      }
    },
    editRow(row){
      this.formNew = row;
      this.formNew.status = 0;
      this.$refs['modal-new-contact'].show();
    },
    checkContact(){
      axios.post(tools.url('/api/chceckcontacts'),this.formNew).then((response)=>{
        console.log(response.data);
        if (response.data.status == 'ok') {
            this.formNew = response.data;
        } 
        else{

        }
          

        }).catch((error)=>{

        });
    }


  },
  beforeMount(){
   
      if(this.$root.logged == false){
         this.$router.push("/login");
     }
  
    
     
    this.getStates();
  },
  mounted(){
    
    this.getContacts();

  }
}
</script>
