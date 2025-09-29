<template lang="html">
  <header id="header" class="sticky-top">
    <div class="header-content" style="background-color: #00bd00;height: 80px;text-align: center;" v-if="$route.meta.title == 'Registrarse' && $route.params.id">
    <p style="font-size: 25px; color: white; padding: 16px;">Has sido invitado a firmar un documento de; {{$root.title_url}}, antes de proceder a la firma necesitamos tu datos.</p>
    </div>

    <div class="header-content" hidden>
      <b-navbar toggleable="lg" type="light" variant="">
        <div class="container oversized-container">
          <b-navbar-brand to="/">
            <img src="/images/logo.svg">
          </b-navbar-brand>

          <b-navbar-toggle target="nav-collapse"><i class="far fa-bars icon"></i></b-navbar-toggle>

          <b-collapse id="nav-collapse" is-nav>
            <b-navbar-nav>
              <b-nav-item-dropdown class="simple-item" left>
                <template #button-content>
                  <span>{{ $t("header.documents") }}</span>
                </template>

                <b-dropdown-item v-for="(cat,indx) in categories" :key="indx" v-bind:class="{ l_category: cat.category }" :to="'/contratos/'+cat.id" > {{ $i18n.locale == 'en' ? cat.name_en  : cat.name }}</b-dropdown-item>

                <!-- <b-dropdown-item to="/contratos/1">Compraventa</b-dropdown-item>
                <b-dropdown-item to="/contratos/1">Prestación de servicios</b-dropdown-item>
                <b-dropdown-item to="/contratos/1">Arrendamiento</b-dropdown-item>
                <b-dropdown-item to="/contratos/1">Obra por encargo</b-dropdown-item>
                <b-dropdown-item to="/contratos/1">Confidencialidad</b-dropdown-item>
                <b-dropdown-item class="l-category">Área civíl</b-dropdown-item>
                <b-dropdown-item to="/contratos/1">Opción civíl</b-dropdown-item>
                <b-dropdown-item class="l-category">Área inmobiliaria</b-dropdown-item>
                <b-dropdown-item to="/contratos/1">Opción inmobiliaria</b-dropdown-item> -->
              </b-nav-item-dropdown>

              <b-nav-item-dropdown class="simple-item" left>
                <template #button-content>
                  <span>{{ $t("header.company") }}</span>
                </template>
                <b-dropdown-item to="/validez-legal-de-documento-electronico">{{ $t("header.company_1") }}<br>{{ $t("header.company_1_2") }}</b-dropdown-item>
                <b-dropdown-item to="/acreditaciones">{{ $t("header.company_2") }}</b-dropdown-item>
                <b-dropdown-item to="/solucion-de-conflictos-de-documentos-electronicos">{{ $t("header.company_3") }}<br>{{ $t("header.company_3_2") }}</b-dropdown-item>
                <b-dropdown-item to="/preguntas-frecuentes">{{ $t("header.company_4") }}</b-dropdown-item>
                <b-dropdown-item to="/nuestra-empresa">{{ $t("header.company_5") }}</b-dropdown-item>
              </b-nav-item-dropdown>
              <!-- <b-nav-item class="simple-item d-lg-none" to="/contacto"><strong class="f-w-600">{{ $t("header.documents") }}</strong></b-nav-item> -->

              <li class="nav-item d-lg-none">
                <div class="nav-link">
                  <hr class="mt-0 mb-1" />
                </div>
              </li>

              <b-nav-item-dropdown class="simple-item" right v-if="$root.logged">
                <template #button-content>
                  <span><i class="fas fa-user-cog"></i> {{ $t("header.myaccount") }}</span>
                </template>
                <b-dropdown-item to="/usuario">{{ $t("header.myinfo") }}</b-dropdown-item>
                <!-- <b-dropdown-item to="/usuario/documentacion">Mi Documentación</b-dropdown-item> -->
                <b-dropdown-item to="/usuario/fotografia">{{ $t("header.mylog") }}</b-dropdown-item>
                <b-dropdown-item to="/usuario/firma">{{ $t("header.mysing") }}</b-dropdown-item>
                <b-dropdown-item to="/usuario/contrasena">{{ $t("header.mypass") }}</b-dropdown-item>
                <b-dropdown-item to="/usuario/contratos">{{ $t("header.docs_sing") }}</b-dropdown-item>
                <b-dropdown-item to="/usuario/contactos">{{ $t("header.contacts") }}</b-dropdown-item>

                <b-dropdown-item to="/usuario/comprar-creditos">{{ $t("header.buy_credits") }}</b-dropdown-item>
                <b-dropdown-item to="/usuario/historico-pagos">{{ $t("header.history_pay") }}</b-dropdown-item>
                <li class="nav-item" style="text-align:center;cursor: pointer;" @click="logout()">
                  <a class="nav-link">{{ $t("header.logout") }}</a>
                </li>
              </b-nav-item-dropdown>


              <b-nav-item-dropdown class="simple-item" right v-else>
                <template #button-content>
                  <span><i class="fas fa-user"></i> {{ $t("header.myaccount") }}</span>
                </template>
                <b-dropdown-item to="/login">{{ $t("header.login") }}</b-dropdown-item>
                <b-dropdown-item to="/registrarse">{{ $t("header.join") }}</b-dropdown-item>
              </b-nav-item-dropdown>

              <b-nav-item class="mb-1 mb-lg-0 special-item-1 green" to="/usuario/contratos" >{{ $t("header.sing_documents") }}</b-nav-item>
              <li class="nav-item special-item-1 blue video-promo">
                <a class="nav-link" @click="$refs['modal-video'].show();">{{ $t("header.video_prmo") }}</a>
              </li>
            </b-navbar-nav>

            <b-navbar-nav class="ml-auto">
              <li class="nav-item mr-lg-0 nav-langs">
                <div class="nav-link">
                  <a class="btn-lang" v-bind:class="{ 'active' : $i18n.locale == 'es' }" @click="$root.changeLocale('es')">ESP</a>
                  <a class="btn-lang" v-bind:class="{ 'active' : $i18n.locale == 'en' }" @click="$root.changeLocale('en')">ENG</a>
                </div>
              </li>

              <li class="nav-item nav-networks">
                <a class="nav-link" target="_blank" href="https://apps.apple.com/mx/app/notarynet/id6444889355">
                  <img src="/images/shared/app-store.svg" alt="App Store">
                </a>
                <a class="nav-link" target="_blank" href="https://play.google.com/store/apps/details?id=com.notario.net&hl=es_MX">
                  <img src="/images/shared/google-play.svg" alt="Google Play">
                </a>
              </li>
            </b-navbar-nav>
          </b-collapse>
        </div>
      </b-navbar>
    </div>
    <div id="headenew">
        <div class="bg-white py-3">
            <div class="container">
                <nav class="navbar navbar-expand-lg bg-white">
                    <div class="container-fluid">
                        <a class="navbar-brand" @click="goHome" style="cursor: pointer;">
                            <img src="/images/redesign/logo-notarynet.webp" alt="NotaryNet" class="img-fluid" style="width: 88px"/>
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" >
                            <span class="navbar-toggler-icon"><img width="48" height="48" src="https://img.icons8.com/fluency/48/menu--v2.png" alt="menu--v2"/></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <router-link class="nav-link mr-1 link-header" :class="{ active: $route.path === '/documentos' }" to="/documentos">Documentos</router-link>
                                </li>
                                <li class="nav-item">
                                    <b-nav-item-dropdown text="Empresa" right>
                                        <b-dropdown-item to="/validez-legal-de-documento-electronico">Validez legal</b-dropdown-item>
                                        <b-dropdown-item to="/acreditaciones">Acreditaciones</b-dropdown-item>
                                        <b-dropdown-item to="/solucion-de-conflictos-de-documentos-electronicos">Solución de conflictos</b-dropdown-item>
                                        <b-dropdown-item to="/preguntas-frecuentes">Preguntas frecuentes</b-dropdown-item>
                                        <b-dropdown-item to="/nuestra-empresa">Nuestra empresa</b-dropdown-item>
                                    </b-nav-item-dropdown>
                                </li>
                                <li class="nav-item">
                                    <b-nav-item-dropdown text="Mi cuenta" right>
                                        <b-dropdown-item to="/login">Iniciar sesión</b-dropdown-item>
                                        <b-dropdown-item to="/registrarse">Registrarse</b-dropdown-item>
                                    </b-nav-item-dropdown>
                                    <!-- <router-link class="nav-link mr-1 link-header" :class="{ active: $route.path === '/' }" to="/">Como funciona</router-link> -->
                                </li>
                                <li class="nav-item">
                                    <router-link class="nav-link mr-1 link-header" :class="{ active: $route.path === '/contacto' }" to="/contacto">Asesoría</router-link>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mr-1 link-header" data-toggle="modal" data-target="#staticBackdrop" style="cursor: pointer;">
                                        Busca el contrato
                                    </a>
                                </li>
                            </ul>
                            <div class="d-flex">
                            <a class="btn rounded-circle me-2" @click="goToAccount" style="cursor: pointer;">

                            </a>
                            <a class="btn rounded-circle me-2" href="javascript:void(0)">

                            </a>
                            <a class="btn btn-primary btn-header" @click="goToDocuments" style="cursor: pointer;">Ver documentos</a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>



    <!-- Modal para el video promocional -->
    <b-modal modal-class="modal-video-s1" ref="modal-video" size="xl" no-close-on-backdrop centered hide-header hide-footer>
      <div class="box-video">
        <iframe class="video-iframe" src="https://www.youtube.com/embed/VIDEO_ID?autoplay=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      </div>
    </b-modal>
  </header>
</template>

<script>
    export default {
        data(){
            return{
            categories:[]
            }
        },

        methods: {
             goHome() {
                 this.$router.push('/');
             },
             goToAccount() {
                 this.$router.push('/cuenta');
             },
             goToDocuments() {
                 this.$router.push('/documentos');
             },
             getCategories(){
                 axios.get('https://notarionet.com/api/categoriesContracts').then((response)=>{
                     this.categories = response.data;
                 }).catch(()=>{});
             },
             logout(){
                 axios.post(tools.url("/api/logout")).then((response)=>{
                     this.$root.user = {};
                     this.$root.logged = false;
                     this.$router.push('/login');
                 }).catch(()=>{});
             },
         },

        mounted(){
            this.getCategories();
            // Asegurar que el modal tenga el z-index correcto cuando se abra
            this.$nextTick(() => {
                if (typeof window.$ !== 'undefined') {
                    window.$('#staticBackdrop').on('show.bs.modal', () => {
                        // Aplicar z-index cuando el modal se muestre
                        window.$('#staticBackdrop').css('z-index', '1055');
                        window.$('.modal-backdrop').css('z-index', '1040');
                    });
                }
            });
        }
    }
</script>

<style>
    .nav-link.dropdown-toggle{
        color: #000000 !important;
    }
    #headenew {
        box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.08);
    }
    .btn-header {
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
        border-radius: 60px 60px 60px 60px;
        padding: 15px 20px 015px 20px;
    }
    .link-header {
        font-size: 14px;
        font-weight: 400;
        text-decoration: none;
        color: #000000;
        border-radius: 0;
    }
         .link-header:hover {
         border-bottom: 2px solid #000000;
         transition: all 0.3s ease;
         border-radius: 0;
     }

     /* Estilos para el modal de búsqueda - Solución definitiva */
     /* .modal-backdrop {
         z-index: 1 !important;
     }

     #staticBackdrop {
         z-index: 9999 !important;
     }

     #staticBackdrop .modal-dialog {
         z-index: 10000 !important;
     }

     #staticBackdrop .modal-content {
         z-index: 10001 !important;
     } */
</style>
