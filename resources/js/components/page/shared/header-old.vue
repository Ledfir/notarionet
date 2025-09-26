<template lang="html">
  <header id="header">
    <div class="header-content" style="background-color: #00bd00;height: 80px;text-align: center;" v-if="$route.meta.title == 'Registrarse' && $route.params.id">
    <p style="font-size: 25px; color: white; padding: 16px;">Has sido invitado a firmar un documento de; {{$root.title_url}}, antes de proceder a la firma necesitamos tu datos.</p>
    </div>

    <div class="header-content">
      <b-navbar toggleable="lg" type="light" variant="">
        <div class="container oversized-container">
          <b-navbar-brand href="/">
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

              <!-- <li class="nav-item nav-icon-circle">
                <div class="nav-link">
                  <router-link class="btn-network t-250 btn-con" to="/contacto"><i class="fal fa-envelope"></i></router-link>
                  <a class="t-250 btn-network btn-fac"><i class="fab fa-facebook-f"></i></a>
                  <a class="t-250 btn-network btn-twi"><i class="fab fa-twitter"></i></a>
                </div>
              </li> -->
            </b-navbar-nav>
          </b-collapse>
        </div>
      </b-navbar>
    </div>

    <div class="header-search">
      <div class="container oversized-container">
        <div class="row">
          <div class="col-lg-4 align-self-center col-left">
            <h6 v-if="$root.logged"><i class="fas fa-user-circle"></i> {{ $t("header.hi") }} <strong>{{$root.user.name}}</strong></h6>
            <span v-if="$root.logged" class="sep">|</span>
            <h6 v-if="$root.logged">{{ $t("header.credits") }}: <strong>{{$root.user.credits}}</strong></h6>
          </div>

          <div class="col-lg-8 col-right">
            <div class="content">
              <b-form inline @submit="onSubmit">
                <b-form-input
                  v-model="formSearch.keywords"
                  type="text"
                  :placeholder="$t('header.placesearch')"
                  required
                ></b-form-input>
                <b-button type="submit" class="t-250 btn-search">{{ $t("header.search") }}</b-button>

                <router-link class="t-250 btn-asesoria" to="/contacto">{{ $t("header.advisory") }}</router-link>
              </b-form>

            </div>
          </div>
        </div>
      </div>
    </div>

    <b-modal modal-class="modal-content-s1 modal-video-promo" ref="modal-video" title="VIDEO PROMO" size="lg" hide-footer centered>
      <iframe src="https://www.youtube.com/embed/1SKb--UE4jU?rel=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </b-modal>

  </header>
</template>

<script>
export default {
  data(){
    return{
      formSearch: {
        keywords: null
      },
      categories:[]
    }
  },

  methods: {
    onSubmit(event) {
        event.preventDefault();
        this.$router.push({path: '/contratos', query: {keywords:this.formSearch.keywords}});
    },
    getCategories(){
        axios.get(tools.url("/api/categoriesContracts")).then((response)=>{
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
  }
}
</script>
