<template lang="html">
    <div class="contacts-page">
      <div class="row align-items-center">
        <div class="col-6">
          <h5 class="d-inline-block main-page-title">{{ $t("account.history_payment.title") }}</h5>
          <br>
        </div>
    
      
      </div>
    
      <hr class="c-hr" />
    
   
      <div class="box-contacts">
        <div class="col-12 col-contact" v-for="(c, cInx) in rows" :key="'cInx-'+cInx">
          <!-- <div class="wrp" @click="openContact"> -->
          <div class="wrp">
            
    
            <div class="col col-info">
               <div class="row">

                <div class="col-lg-6 box">
                  <label class="label">{{ $t("account.history_payment.id_compra") }}:</label>
                  <h6>{{ c.id }}</h6>
                </div>
                <div class="col-lg-6 box">
                  <label class="label">{{ $t("account.history_payment.package") }}:</label>
                  <h6>{{ c.package }}</h6>
                </div>
                
             
                <div class="col-lg-6 box">
                  <label class="label">{{ $t("account.history_payment.total_credits") }}:</label>
                  <h6>{{ c.quantity }}</h6>
                </div>
    
                <div class="col-lg-6 box">
                  <label class="label">Total:</label>
                  <h6>{{ c.total }}</h6>
                </div>
    
                <div class="col-lg-6 box">
                  <label class="label">{{ $t("account.history_payment.date") }}:</label>
                  <h6 >{{c.created}}</h6>
                </div>

                <div class="col-lg-6 box">
                  <label class="label" style="color: #034195;">{{ $t("account.history_payment.validity") }}:</label>
                  <h6 style="color: #034195;" >{{c.expires_on}}</h6>
                </div>
                
                <div class="col-lg-6 box" v-if="c.payment_method == 'deposito'">
                  <label class="label" >{{ $t("account.history_payment.status") }}:</label>
                  <h6 v-if="c.status == 'pagado'">{{ $t("account.history_payment.payed") }}</h6>
                  <h6 v-else-if="c.status == 'pediente_pago'">{{ $t("account.history_payment.outstanding") }}</h6>
                </div>

    
               
              </div>
            </div>
          </div>
        </div>
      </div>
    
 

    
      <sweet-modal :icon="modal.icon" :blocking="modal.block" :hide-close-button="modal.block" ref="modal">
        <div class="fa-3x" v-if="modal.icon== ''"><i class="fas fa-spinner fa-pulse"></i></div><br/>
        {{modal.msg}}
        <div class="col-12 text-center" style="padding-top: 20px;" v-if="modal.icon == 'success'">
        <b-button class="btn btn-primary" slot="button" v-on:click.prevent="$refs.modal.close();">{{ $t("account.history_payment.accept") }}</b-button>
        </div>
      </sweet-modal>
    </div>
    </template>
    
    <script>
    export default {
      data(){
        return{
          rows: [
            /*{ id: 1, status: 1, imgURL: 'public/images/pages/user/photo.jpg', nombre: 'Oscar Alejandro Lopez Lopez', tel: '33 14005000', email: 'email@gmail.com' },
            { id: 1, status: 2, imgURL: 'public/images/pages/user/photo.jpg', nombre: 'Alejandro Oscar Lopez Lopez', tel: '33 55000000', email: 'email2@gmail.com' },
            { id: 1, status: 1, imgURL: 'public/images/pages/user/photo.jpg', nombre: 'Alejandro Oscar Lopez Lopez', tel: '33 55000000', email: 'email2@gmail.com' },*/
          ],

    
          modal:{
            msg:'',
            icon:'',
            block:false,
          },
    
        
        }
      },

    
      methods: {

        getData(){
          this.modal.icon = "";
            this.modal.msg = 'Cargando...';
            this.modal.block = true;
            this.$refs.modal.open();
            axios.get(tools.url('/api/user/paymenthistory')).then((response)=>{
              this.rows = response.data;
              this.modal.icon = "";
              this.modal.msg = '';
              this.modal.block = false;
              this.$refs.modal.close();
            }).catch((error)=>{
               console.log(error);
    
              this.modal.icon = "";
              this.modal.msg = 'Error al consultar los contactos';
              this.modal.block = false;
              this.$refs.modal.close();
            });
        },
        
    
    
      },
      beforeMount(){
        if(this.$root.logged == false){
             this.$router.push("/login");
         }
    
      },
      mounted(){
        this.getData();
    
      }
    }
    </script>
    