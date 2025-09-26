<template lang="html">
  <div class="contract-detail-wrapper">
    <div id="description-page" hidden>
      <section class="breadcrumb-section">
        <div class="container oversized-container">
          <!-- <span>Contratos</span>
          <span class="line">/</span>
          <span>{{ row.title }}</span> -->
        </div>
      </section>

      <section class="container oversized-container description-section" id="product-description">
        <div class="row">

          <div class="col-lg-6 col-xl-5 mb-3 mb-lg-0 col-gallery">
            <swiper :options="galleryOptions" v-if="showGallery">
              <swiper-slide v-for="(row, galIndex) in gallery" :key="galIndex">
                <div class="image-container">
                  <div class="img">
                    <!-- <v-zoom :img="row" :width="'100%'"></v-zoom> -->
                    <img :src="row">
                  </div>
                </div>
              </swiper-slide>

              <div class="swiper-pagination" slot="pagination"></div>
            </swiper>
          </div>

          <b-form class="col-lg-6 col-description" @submit="addCart">
            <h1 class="name">{{ $i18n.locale == 'en' ? row.title_en  : row.title }}</h1>
            <!-- <h6 class="sku">SKU: {{ product.sku }}</h6> -->

            <!-- <h6 class="mt-3 subtitle">Costo en créditos</h6>
            <h4 class="price">2 Créditos</h4> -->

            <h6 class="mt-3 subtitle">{{ $t("detail.cost") }}</h6>
            <h4 class="price">{{ row.price }} créditos</h4>

            <h6 class="mt-3 subtitle">{{ $t("detail.description") }}</h6>
            <div class="txt-description" v-html="$i18n.locale == 'en' ? row.description_en  : row.description"></div>


            <!-- <div class="d-block mt-4 mb-2" v-if="product.pdf">
              <a class="btn btn-submit btn-contact" v-if="product.pdf" :href="product.pdf" target="_blank" rel="noopener noreferrer">PDF <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
            </div> -->

            <!-- <h6 class="mt-2 mb-1 subtitle" v-if="product.tablaPrecios">Lista de precios</h6>
            <img class="img-fluid" :src="product.tablaPrecios"> -->

            <!-- <h6 class="mt-4 subtitle" v-if="modelos.length">Presentaciones</h6>
            <b-form-group class="prese-options" v-slot="{ ariaPr }">
              <b-form-radio v-model="modelo" v-for="(row, index) in modelos" :key="index" :value="row.id" :aria-describedby="ariaPr" name="pre">
                <div class="radio-prese">{{ row.name }}</div>
              </b-form-radio>
            </b-form-group> -->

            <!-- <h6 class="mb-1 subtitle">Cantidad</h6> -->
            <!-- <b-form-spinbutton id="sb-inline" v-model="form.quantity" inline></b-form-spinbutton> -->

            <!-- <div class="col-quantity2">
              <a class="form-control btn-q" @click="changeCantidad('-')">
                <i class="fas fa-minus"></i>
              </a>
              <b-form-input class="input-q" type="number" min="1" v-model="form.quantity" @keypress="isNumber($event)" @paste="noPaste" />
              <a class="form-control btn-q" @click="changeCantidad('+')">
                <i class="fas fa-plus"></i>
              </a>
            </div> -->

            <div class="d-block mt-3 mb-2 box-main-buttons">
             <!--  <router-link class="btn btn-s1 bg-black mr-sm-2" to="/creditos"><i class="fad fa-credit-card mr-1"></i> Comprar créditos</router-link> -->
              <router-link class="btn btn-s1 bg-black" to="/usuario/contratos"><i class="fad fa-cabinet-filing mr-1"></i> {{ $t("detail.use_doc") }}</router-link>


              <a class="btn btn-s1" style="margin-left: 20px;background-color: silver;color:black !important" @click="$refs.modalView.open()"><i class="fas fa-eye mr-1"></i> {{ $t("detail.display_format") }}</a>
            </div>
          </b-form>
        </div>
      </section>

      <sweet-modal :icon="modal.icon" :blocking="modal.block" :hide-close-button="modal.block"  ref="modal">
          <div v-html="modal.msg"></div>
          <div class="col-12 text-center" style="padding-top: 20px;" v-if="modal.icon == 'success'">
              <b-button class="btn btn-primary" slot="button" v-on:click.prevent="$refs.modal.close()">Agregar mas productos</b-button>
              <b-button class="btn btn-primary" slot="button" v-on:click.prevent="$refs.modal.close(); $router.push('/cart')">Ir al carrito <i class="color-white fas fa-shopping-cart"></i></b-button>
          </div>
      </sweet-modal>




    </div>

    <div id="description-page-new">
        <div class="container">
            <div class="row py-5">
                <div class="col-12 col-md-6">
                    <h1 class="name">{{ row.title }}</h1>
                    <div class="txt-description py-5" v-html="row.description"></div>
                    <div class="price-container mb-5">
                        <h6 class="mt-3 subtitle">{{ $t("detail.cost") }}</h6>
                        <h4 class="price">{{ row.price }} créditos</h4>
                    </div>
                    <div class="d-block mt-3 mb-2 box-main-buttons">
                        <router-link class="btn btn-primary rounded" to="/usuario/contratos"><i class="fad fa-cabinet-filing mr-1"></i> {{ $t("detail.use_doc") }}</router-link>
                        <a class="btn btn-secondary rounded" style="margin-left: 20px; " @click="$refs.modalView.open()"><i class="fas fa-eye mr-1"></i> {{ $t("detail.display_format") }}</a>
                    </div>
                    <div class="d-block mt-3 mb-2 box-main-buttons">
                        <router-link class="btn btn-secondary rounded" to="/usuario/comprar-creditos">
                            <i class="fas fa-credit-card mr-1"></i> Comprar créditos
                        </router-link>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <img :src="row.img" alt="img" class="img-fluid rounded-lg">
                </div>
            </div>
        </div>
        <sweet-modal ref="modalView">
            <div v-html="row.body_view"></div>
        </sweet-modal>
    </div>
  </div>
</template>

<script>
    // import vZoom from 'vue-zoom';
    export default {
    // components: {vZoom},

    data() {
        return {
        id: null,
        showGallery: true,

        form: {
            modelo: null,
            quantity: 1,
            color: null
        },

        product: {
            name: 'Contrato de Confidencialidad',
            description: `
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><br />
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            `
        },
        qtyPrices: [],
        category: [],
        products: [],
        modelos: [],
        modelo:null,
        stock: 0,
        price: 0,
        offer_price: 0,
        avg: 0,
        description: null,

        colors: [],
        gallery: [
            'public/images/pages/contracts/contract-lg.jpg',
            'public/images/pages/contracts/contract-lg.jpg',
        ],
        galleryOld: [],
        relatedProducts: [],

        comments: [],

        modal:{
            msg:'',
            icon:'',
            block:false
        },

        comment:{
            stars: null,
            body: ''
        },

        // == Carrusel options ==
        galleryOptions: {
            loop: false,
            speed: 600,

            pagination: {
            el: '.swiper-pagination',
            clickable: true
            },

            // autoplay: {
            //   delay: 3000,
            //   disableOnInteraction: false
            // }
        },

        productsOption: {
            loop: false,
            slidesPerView: 5,
            spaceBetween: 30,

            navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
            },

            breakpoints: {
            1399: {
                slidesPerView: 5
            },
            1199: {
                slidesPerView: 4
            },
            991: {
                spaceBetween: 30,
                slidesPerView: 3
            },
            575: {
                spaceBetween: 20,
                slidesPerView: 2
            },
            1: {
                slidesPerView: 1
            },
            }
        },
        row:{}
        // == ==
        }
    },

    watch: {
            "$route.params.id" : function(v){
                //this.id = this.$route.params.id;
                this.id = this.$root._getURLID(this.$route.params.id);
                this.getRow();
            },

            /*"modelo" : function(v){
                let model = this.modelos.find( ( model ) => model.id === this.modelo );
                this.price = model.price;
                this.offer_price = model.offer_price ? model.offer_price : 0;
                this.stock = model.stock;
                this.description = model.description;
                this.form.modelo = model.id;

                if(model.images.length > 0){
                    this.gallery = model.images;
                }else{
                    this.gallery = this.galleryOld;
                }

                this.qtyPrices = model.qtyPrice;
            },

            'form.quantity' : function(value, oldValue){
            if( value < 1 || value > 100 ){
                setTimeout( ()=>{ this.form.quantity = 1; }, 100);
            }
            },*/
        },

    methods:{
        toast() {
        this.$bvToast.toast(`El producto fue agregado a su carrito`, {
            title: `Producto agregado al carrito`,
            toaster: 'b-toaster-bottom-right b-toaster-custom-1',
            variant: 'success',
            solid: true,
            appendToast: true
        })
        },

        noPaste(evt){
        event.preventDefault();
        },

        isNumber: function(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode < 48 || charCode > 57) {
            evt.preventDefault();
        } else {
            return true;
        }
        },

        changeCantidad(type){
        this.form.quantity = parseInt(this.form.quantity);
        if (type == '-') {
            this.form.quantity = (this.form.quantity > 1) ? --this.form.quantity : 1;
        }
        else{
            this.form.quantity = (this.form.quantity < 100) ? ++this.form.quantity : 100;
        }
        },

        getRow(){
        this.showGallery = false;
        axios.get(tools.url('/api/contracts/' + this.id)).then((response)=>{
            this.row = [];
            this.gallery = [];
            this.modelo = null;
            this.modelos = [];
            this.row = response.data;
            this.gallery = response.data.gallery

            /*this.galleryOld = response.data.gallery;
            this.category = response.data.category;*/

        /* if(this.product.modelos.length){
                this.modelos = this.product.modelos;
                this.modelo = this.modelos[0].id;
                this.price = this.modelos[0].price;
                this.stock = this.modelos[0].stock;
                this.form.modelo = this.modelos[0].id;

                if(this.modelos[0].images.length > 0){
                    this.gallery = this.modelos[0].images;
                }
            }else{
                this.price = this.product.price;
                this.stock = this.product.stock;
                this.form.modelo = null;
                this.gallery = response.data.gallery;
            }*/

            this.showGallery = true;
            //this.getRelated(this.id);
            //this.getComments(this.id);
        }).catch((error)=>{
            console.log(error);
        });
        },

        addCart(evt){
        evt.preventDefault();
        var cart = [];
        var encontrado = false;

        if(localStorage.cart){
            cart = JSON.parse(localStorage.getItem('cart'));

            for(var i = 0; i < cart.length ; i++){
            if(cart[i].id == this.id && cart[i].modelo == this.form.modelo && cart[i].color == this.form.color){
                var suma = cart[i].quantity + this.form.quantity;
                cart[i].quantity = suma <= this.stock ? suma : this.stock;
                encontrado = true;
                break;
            }
            }

            if(!encontrado){
            cart.push({ id:this.id, quantity:this.form.quantity, modelo: this.form.modelo, color: this.form.color});
            }
        } else{
            cart.push({ id:this.id, quantity:this.form.quantity, modelo: this.form.modelo, color: this.form.color });
        }
        localStorage.setItem("cart",JSON.stringify(cart));
        this.$root.cartCount = cart.length;
        //this.toast();
        this.modal.msg = 'El producto <b>'+this.product.name+'</b> se agregado al carrito';
        this.modal.icon = 'success';
        this.$refs.modal.open();
        },
    },

    mounted(){
        this.id = this.$route.params.id;
        // this.id = this.$root._getURLID(this.$route.params.id);
        this.getRow();
    }
    }
</script>

<style lang="scss">
.contract-detail-wrapper {
    .name {
        font-size: 50px;
        font-weight: 400;
        color: #000000;
        margin-bottom: 20px;
        letter-spacing: 1px;
        line-height: 50px;
    }
    .btn{
        font-size: 16px;
        font-weight: 500;
        text-transform: capitalize;
        font-style: normal;
        text-decoration: none;
        line-height: 1em;
        letter-spacing: -0.2px;
        word-spacing: 0px;
        fill: #ffffff;
        color: #ffffff;
        border-style: none;
        border-radius: 60px 60px 60px 60px !important;
        padding: 15px 20px 015px 20px;
    }
}
</style>
