<template lang="html">
    <div class="d-flex account-page">
        <div class="container">
            <div class="main-box">
                <div class="row">
                    <!-- <div class="col-12 px-2">
                    <h1 class="mb-3 page-title">{{ title }}</h1>
                    </div> -->
                    <div class="col-lg-4 px-2 col-menu">
                    <div class="white-box">
                        <div class="box-profile"  style="min-height: 0px;">
                        <div class="d-block mt-4 pb-3">
                            <!-- <span class="fa-stack fa-4x user-icon">
                            <div class="img-photo" v-bind:style="{ backgroundImage: 'url('+$root.user.imageUrl+')' }"></div>

                            </span> -->

                            <h5 class="txt-username">{{ $t("account.index.welcome") }} {{ $root.user.name }}</h5>
                        </div>
                        </div>

                        <hr />

                        <div class="box-menu">
                        <p class="item">
                            <router-link class="btn-menu special" to="/usuario/contratos"><i class="fas fa-cabinet-filing"></i> {{ $t("account.index.documents") }} <span class="num">{{$root.user.total_contracts}}</span></router-link>
                        </p>
                        <p class="item">
                            <router-link class="btn-menu" to="/usuario"><i class="far fa-edit"></i> {{ $t("account.index.myinfo") }}</router-link>
                        </p>
                        <!-- <p class="item">
                            <router-link class="btn-menu" to="/usuario/documentacion"><i class="far fa-folder"></i> Mi documentación</router-link>
                        </p> -->
                        <p class="item">
                            <router-link class="btn-menu" to="/usuario/fotografia"><i class="far fa-camera"></i> {{ $t("account.index.mylog") }}</router-link>
                        </p>
                        <p class="item">
                            <router-link class="btn-menu" to="/usuario/firma"><i class="far fa-signature"></i> {{ $t("account.index.mysing") }}</router-link>
                        </p>
                        <p class="item">
                            <router-link class="btn-menu" to="/usuario/contrasena"><i class="far fa-lock-alt"></i> {{ $t("account.index.mypass") }}</router-link>
                        </p>

                        <p class="item">
                            <router-link class="btn-menu" to="/usuario/contactos"><i class="far fa-address-book"></i> {{ $t("account.index.contacts") }}</router-link>
                        </p>
                        <p class="item">
                            <router-link class="btn-menu" to="/usuario/comprar-creditos"><i class="fal fa-credit-card"></i> {{ $t("account.index.buy_credits") }}</router-link>
                        </p>
                        <p class="item">
                            <router-link class="btn-menu" to="/usuario/historico-pagos"><i class="fas fa-clipboard-list"></i> {{ $t("account.index.history_pay") }}</router-link>
                        </p>
                        <p class="item">
                            <a class="btn-menu" v-on:click="logout()" style="cursor: pointer;"><i class="far fa-sign-out-alt"></i> {{ $t("account.index.logout") }}</a>
                        </p>
                        </div>

                    </div>
                    </div>

                    <div class="col-lg px-2 col-page-content">
                    <div class="white-box">

                        <router-view></router-view>

                    </div>
                    </div>

                    <button @click="bottomFunction()" id="btn-go-bottom" class="t-150" title="Go to bottom"><i class="fal fa-arrow-to-bottom"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  data(){
    return{
      title: 'Mi cuenta'
    }
  },

  methods:{
    getUrlName(){
      var urlName = this.$route.path;
      if(this.$i18n.locale == 'en'){
        if(urlName == '/usuario'){ this.title = 'My Basic Information' }
        if(urlName == '/usuario/documentacion'){ this.title = 'My Documentation' }
        if(urlName == '/usuario/fotografia'){ this.title = 'My logo' }
        if(urlName == '/usuario/firma'){ this.title = 'My signature' }
        if(urlName == '/usuario/firma'){ this.title = 'My signature' }
        if(urlName == '/usuario/contrasena'){ this.title = 'My password' }
        if(urlName == '/usuario/contratos'){ this.title = 'Documents' }
        if(urlName == '/usuario/nuevo-contrato'){ this.title = 'New document' }
        if(urlName == '/usuario/contactos'){ this.title = 'Contacts' }
        if(urlName == '/usuario/comprar-creditos'){ this.title = 'Buy credits' }
        if(urlName == '/usuario/historico-pagos'){ this.title = 'Payment history' }
      }
      else{
        if(urlName == '/usuario'){ this.title = 'Mi información básica' }
        if(urlName == '/usuario/documentacion'){ this.title = 'Mi documentación' }
        if(urlName == '/usuario/fotografia'){ this.title = 'Mi fotografía' }
        if(urlName == '/usuario/firma'){ this.title = 'Mi firma' }
        if(urlName == '/usuario/firma'){ this.title = 'Mi firma' }
        if(urlName == '/usuario/contrasena'){ this.title = 'Mi Contraseña' }
        if(urlName == '/usuario/contratos'){ this.title = 'Documentos' }
        if(urlName == '/usuario/nuevo-contrato'){ this.title = 'Nuevo documento' }
        if(urlName == '/usuario/contactos'){ this.title = 'Contactos' }
        if(urlName == '/usuario/comprar-creditos'){ this.title = 'Comprar créditos' }
        if(urlName == '/usuario/historico-pagos'){ this.title = 'Historico de pagos' }
      }

    },

    logout(){
        axios.post(tools.url("/api/logout")).then((response)=>{
            this.$parent.user = {};
            this.$parent.logged = false;
            this.$router.push('/login');
        }).catch(()=>{});
    },
    bottomFunction() {
        window.scrollTo(0,document.body.scrollHeight);
    }
  },

  beforeMount(){
    this.getUrlName();
  },

  watch: {
    $route(to, from) {
      this.getUrlName();
    },
  },

  mounted(){
    if(this.$root.logged == false){
         this.$router.push("/login");
     }
     this.$root.auth();
  }
}
</script>
