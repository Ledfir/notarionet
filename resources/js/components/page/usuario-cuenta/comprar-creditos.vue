<template lang="html">
<div class="credits-page">
  <b-form class="c-f-g-2-wrp sm" @submit.prevent="onSubmit()">
    <h5 class="main-page-title"> {{ $t("account.buycredits.title") }}</h5>
    <hr class="c-hr" />

    <b-form-group v-slot="{ ariaDescribedby }" class="mb-0 box-packages-options">
      <swiper class="swiper swiper-s2 s-btn-black" :options="swiperOption">
        <swiper-slide v-for="(p, pInx) in packages" :key="'pInx-'+pInx">
          <b-form-radio class="col-12 px-0 box-credit-s2"
            required
            v-model="form.package_id"
            :aria-describedby="ariaDescribedby"
            name="radios-pqs"
            :value="p.id">
            <div class="box">
              <!-- <div class="col-xl-5 align-self-center col-image">
                <img :src="p.imageUrl">
              </div> -->

              <div class="col-12 col-info-1">
                <h4 class="num" v-html="p.credits"></h4>
                <h5 class="name">{{ $i18n.locale == 'en' ? p.name_en  : p.name }}</h5>
                <span class="price">$ {{ p.price }} MXN</span>
              </div>

              <div class="col-12 align-items-center col-button">
                <a class="_btn">
                  <span>{{ $t("account.buycredits.select") }}</span><span class="selected">{{ $t("account.buycredits.selected") }}</span>
                </a>
              </div>
            </div>
          </b-form-radio>
        </swiper-slide>

        <div class="swiper-button-prev" slot="button-prev"></div>
        <div class="swiper-button-next" slot="button-next"></div>
      </swiper>
    </b-form-group>

    <div class="box-credit-info" v-if="Object.keys(selectedCredit).length">
      <div class="col-md-6">
        <h6>Numero de créditos: <strong v-html="selectedCredit.n"></strong></h6>
      </div>

      <div class="col-md-6">
        <h6>Precio: <strong>{{ selectedCredit.price }}</strong></h6>
      </div>
    </div>
    <div class="box-credit-info" v-else>
      <div class="col-12 text-center">
        <h6> {{ $t("account.buycredits.select_option") }}</h6>
      </div>
    </div>

    <h6>{{ $t("account.buycredits.info_pay") }}</h6>
    <hr class="c-hr" />
    <img src="/images/pages/user/logo_banks.png" style="width: 150px;">

    <br><br>
    <b-form-group class="cus-f-group-1 col-md-12 col-lg-12" label="Método de pago">
            <b-form-select required v-model="form.payment_method">
              <b-form-select-option :value="null">{{ $t("account.buycredits.select_option") }}</b-form-select-option>
              <b-form-select-option value="deposito">{{ $t("account.buycredits.deposit") }}</b-form-select-option>
              <b-form-select-option value="tarjeta">{{ $t("account.buycredits.car") }}</b-form-select-option>
            </b-form-select>
    </b-form-group>

    <div class="col-12" v-show="form.payment_method == 'tarjeta'">
              <label class="mt-3">{{ $t("account.buycredits.card_details") }}:</label>
              <div id="cardElement"></div>
              <small class="form text text-muted" id="cardErrors" role="alert"></small>
              <br>
    </div>
    <div class="col-12" v-show="form.payment_method == 'deposito'">
      <img src="public/images/banamex.png" width="100">
                <p><strong>{{ $t("account.buycredits.bank") }}:</strong> BANAMEX</p>
                <p><strong>{{ $t("account.buycredits.headline") }}:</strong> 44 Y PUNTO S.A. DE C.V.</p>
                <p><strong>CLABE:</strong> 002692701842845439</p>

                <p>
                 {{ $t("account.buycredits.send_proof") }}: <br />
                  <strong>pago@notarionet.com</strong>
                </p>
    </div>
    <br><br>
    <!-- <b-form-group class="cus-f-group-2" label="Nombre en la tarjeta *">
      <b-form-input type="text" v-model="form.tarjetanombre" size="sm" required placeholder="" />
    </b-form-group>

    <b-form-group class="cus-f-group-2" label="Número en la tarjeta *">
      <b-form-input type="text" minlength="16" maxlength="16" v-model="form.tarjetanumero" size="sm" required placeholder="" />
    </b-form-group>

    <div class="row">
      <b-form-group class="col-sm-6 col-lg-4 cus-f-group-2 group-expiration" label="Fecha de expiración *">
        <b-form-select required class="month" v-model="form.mes" size="sm">
          <b-form-select-option :value="null">Mes</b-form-select-option>
          <b-form-select-option :value="cmIdx + 1" v-for="(x, cmIdx) in 12" :key="'cmIdx-'+cmIdx">{{ cmIdx + 1 }}</b-form-select-option>
        </b-form-select>
        <div class="sep">/</div>
        <b-form-select required class="year" v-model="form.anio" size="sm">
          <b-form-select-option :value="null">Año</b-form-select-option>
          <b-form-select-option :value="caIdx + 1" v-for="(x, caIdx) in 11" :key="'caIdx-'+caIdx">{{ currentYear + caIdx }}</b-form-select-option>
        </b-form-select>
      </b-form-group>

      <b-form-group class="col-6 col-sm-6 col-lg-4 cus-f-group-2" label="CVV *">
        <b-form-input type="text" v-model="form.tarjetacvv" size="sm" required placeholder="" minlength="3" maxlength="4"/>
      </b-form-group>
    </div> -->

    <b-button type="submit" class="btn-s1 bg-blue" v-show="form.payment_method == 'tarjeta'">{{ $t("account.buycredits.title") }}</b-button>
    <b-button type="submit" class="btn-s1 bg-blue" v-show="form.payment_method == 'deposito'">{{ $t("account.buycredits.process_order") }}</b-button>
  </b-form>

  <sweet-modal :icon="modal.icon" :blocking="modal.block" :hide-close-button="modal.block" ref="modal">
    <div class="fa-3x" v-if="modal.icon== ''"><i class="fas fa-spinner fa-pulse"></i></div><br/>
    <div v-html="modal.msg"></div>
    <div class="col-12 text-center" style="padding-top: 20px;" v-if="modal.icon == 'success'">
    <b-button class="btn btn-primary" slot="button" v-on:click.prevent="toHome();">{{ $t("account.buycredits.done") }}</b-button>
    </div>
  </sweet-modal>
</div>
</template>

<script>
export default {
  data(){
    return{
      currentYear: 0,



      credits: [
        { id: 1, n: '1', name:'Contrato, acuerdo o documento', imgURL: 'public/images/pages/packages/paper-1.svg', price: '$500.00 MXN',total:500 },
        { id: 2, n: '3', name:'Documentos', imgURL: 'public/images/pages/packages/paper-2.svg', price: '$1,000.00 MXN',total:1000 },
        { id: 3, n: '5', name:'Documentos',  imgURL: 'public/images/pages/packages/paper-3.svg', price: '$2,500.00 MXN',total:2500 },
      ],

      selectedCredit: {},


      form: {

        quantity:null,
        total:null,
        package_id:null,
        payment_method:null
      },

      modal:{
        msg:'',
        icon:'',
        block:false,
      },

      // == Carousel options ==
      swiperOption: {
        loop: false,
        slidesPerView: 2,
        spaceBetween: 30,

        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev'
        },

        breakpoints: {
          768: {
            slidesPerView: 2,
            spaceBetween: 30
          },
          1: {
            slidesPerView: 1,
          },
        }
      },
      // == ==
      packages:{}
    }
  },

  watch:{
      'form.package_id':function(val){
          var indxp = null;
          for (var i = 0; i < this.credits.length; i++) {
              if(this.credits[i]['id'] == val){
                  indxp = i;
              }
          }

          this.form.quantity = this.packages[indxp]['credits'];
          this.form.total = this.packages[indxp]['price'];
      }
  },

  methods: {
      /*onSubmit() {

      this.modal.icon = "";
      this.modal.msg = 'Cargando...';
      this.modal.block = true;
      this.$refs.modal.open();


        axios.post(tools.url('/api/credits'),this.form).then((response)=>{
          this.modal.block = false;
            this.modal.icon = "success";

            this.modal.msg = 'Gracias<br>Tu compra de '+this.form.quantity+' creditos ha sido generada<br><br>Recibiras 1 recibo en tu correo de esta compra';
            this.$root.auth();
        }).catch((error)=>{
              this.modal.block = false;
            this.modal.icon = "error";
            this.modal.msg = 'Error al generar compra';
        });

    },*/
    toHome(){
      this.modal.block = false;
      this.$refs.modal.close();

    },
    getPackages(){
        axios.get(tools.url('/api/packages')).then((response)=>{
            this.packages = response.data;
        }).catch((error)=>{

        });
    } ,
    loadStripe(){
      //sandbox key
      //this.stripe = Stripe('pk_test_51MJg6MFbOx5yWSN82dR9gGNKxV58qesGnCnO2D2IZ6mCo351qXpC3gWd5PLW1HFFb9mOfx5NGVhgqGKrnZLKXECD00ib1BjA5x');
      this.stripe = Stripe('pk_live_51MJg6MFbOx5yWSN8Rv2FnMKEzBnSjQknJ6BiYpFiZdYZ2G8LNFZ7WOMctTwrkRCUm3MrUFkgqXvZ8DVJgpDIbF6f00TreTHrGI');

      this.elements = this.stripe.elements({locale: 'es'});
      this.cardElement = this.elements.create('card', {hidePostalCode: true});

      this.cardElement.mount("#cardElement");
      this.cardElement.addEventListener('change', ({ error }) => {
          const displayError = document.getElementById('cardErrors');
          if (error) {
            displayError.textContent = error.message;
          } else {
            displayError.textContent = '';
          }
      });
    },
    onSubmit() {
      if (this.form.payment_method == 'deposito') {
          this.saveOrder();
      }
      else if(this.form.payment_method == 'tarjeta'){
          this.modal.block = true;
          this.modal.icon = '';
          if(this.$i18n.locale == 'en' ){
            this.modal.msg = 'Validating the payment method. please wait...';
          }
          else{
            this.modal.msg = 'Validando el metodo de pago. por favor espere...';
          }
          this.$refs.modal.open();

          this.stripe.createPaymentMethod({
              type: 'card',
              card: this.cardElement,
              billing_details: { name: this.$root.user.name+' '+this.$root.user.lastname, email: this.$root.user.email }
            }).then((result)=>{
              if (result.error) {
                this.desactivar = false;
                this.modal.block = false;
                this.modal.icon = "error";
                this.modal.msg = 'Ocurrió un error con su tarjeta';



              } else {
                const data  = { payment_method_id: result.paymentMethod.id, total: this.form.total, name: this.$root.user.name, email: this.$root.user.email };
                axios.post(tools.url('/api/stripe/Installments'),data).then((response)=>{
                    this.form.payment_intent_id = response.data.intent_id;
                    this.pagarStripe();
                  });
              }
          });
        }
    },

    pagarStripe: function(){
      this.modal.icon = "";
      this.modal.msg = 'Cargando...';
      this.modal.block = true;
      //this.$refs.modal.open();
        axios.post(tools.url('/api/credits'),this.form).then((response)=>{

          if (response.data.type == 'success') {

              this.modal.icon = "success";
              if(this.$i18n.locale == 'en' ){
                this.modal.msg = 'Thank you<br>Your purchase of '+this.form.quantity+' credits has been generated<br><br>You will receive 1 receipt in your email for this purchase<br>If you require an invoice, write us your information tax to facturacion@notarionet.com and we will gladly issue it to you. ';
              }
              else{
                this.modal.msg = 'Gracias<br>Tu compra de '+this.form.quantity+' creditos ha sido generada<br><br>Recibiras 1 recibo en tu correo de esta compra<br>Si requieres factura escríbenos tus datos fiscales a facturacion@notarionet.com y con gusto te la emitimos. ';

              }
               this.$root.auth();


              var self = this;

              setTimeout(function(){
                self.$refs.modal.close();
                self.$router.push("/usuario/historico-pagos");
            }, 2000);

          }
          else{
              this.modal.block = false;
              this.modal.icon = "error";
              this.modal.msg = response.data.message;
          }


        }).catch((error)=>{
            this.desactivar = false;
            this.modal.block = false;
            this.modal.icon = "error";
            this.modal.msg = 'Error al generar compra';
        });

    },
    saveOrder: function(){
      this.modal.icon = "";
      this.modal.msg = 'Cargando...';
      this.modal.block = true;
      this.$refs.modal.open();
      axios.post(tools.url('/api/creditsdeposit'),this.form).then((response)=>{

          this.modal.icon = "success";
          if(this.$i18n.locale == 'en' ){
            this.modal.msg = 'Thank you<br>Your request for '+this.form.quantity+' credits has been generated<br>Remember to send your proof of payment to pagos@norarionet.com, only then will your credits be released.< br>If you require an invoice, write us your fiscal information at facturacion@notarionet.com and we will gladly issue it to you. ';
          }
          else{
            this.modal.msg = 'Gracias<br>Tu pedido de '+this.form.quantity+' creditos ha sido generado<br>Recuerda enviar tu comprobante de pago a pagos@norarionet.com , solo entonces tus creditos seran liberados.<br>Si requieres factura escríbenos tus datos fiscales a facturacion@notarionet.com y con gusto te la emitimos. ';
          }
          this.$root.auth();

          var self = this;
          setTimeout(function(){
              self.$refs.modal.close();
              self.$router.push("/usuario/historico-pagos");
          }, 2000);

      }).catch((error)=>{
          this.desactivar = false;
          this.modal.block = false;
          this.modal.icon = "error";
          this.modal.msg = 'Error al generar orden';
      });

    },
  },
  mounted(){
      var self = this;
      setTimeout(self.loadStripe(), 1000);
  },
  beforeMount(){
    this.currentYear = new Date().getFullYear();
    this.getPackages();
  }
}
</script>
<style>
.StripeElement {
  box-sizing: border-box;

  height: 40px;

  padding: 10px 12px;

  border: 1px solid transparent;
  border-radius: 4px;
  background-color: white;

  box-shadow: 0 1px 3px 0 #e6ebf1;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
}

.StripeElement--focus {
  box-shadow: 0 1px 3px 0 #cfd7df;
}

.StripeElement--invalid {
  border-color: #fa755a;
}

.StripeElement--webkit-autofill {
  background-color: #fefde5 !important;
}
</style>
