require('./bootstrap');

import Vue from 'vue';
//Rutas del website
import Router from './router.js';

//Librerias globales
import Library from './libs.js';
Vue.use(Library);

//Componentes del website
import components from './components/components.js';
Vue.use(components);

// == Lenguajes del sitio web ==
import VueI18n from 'vue-i18n';
import messages from './langs.js';
Vue.use(VueI18n);

const i18n = new VueI18n({
  locale: 'es', // set locale
  fallbackLocale: 'en',
  messages: messages // set locale messages
})
// == ==

window.Vue=Vue;

//Instancia principal
const app = new Vue({
    el: '#app',
    router:Router,

    data:{
      cartCount: 0,
      logged: false,
      user:{
        imageUrl:'https://notarionet.com/public/images/logo.svg'
      },
      title_url:null,
      contracts_id:null,
    },
    methods:{
        auth:function(){
          axios.get(tools.url("/api/userfront")).then((response)=>{
            this.user = response.data;
            this.logged = true; // PASAR A FALSE DESPUES, AHORA ES TEMPORAL
          }).catch(()=>{
              //no login
          });
        },
        // == functions para la modificación de urls amigables ==
        _clearString(str){
          if(str != null){
          var newStr =  str.trim()            // Quitar espacios al inicio y final
                          .toLowerCase()          // Convertir a minusculas
                          .replace(/\s/g, '-')    // Convertir espacios a "-"
                          .normalize('NFD').replace(/[\u0300-\u036f]/g, "") // Vocales sin acento
                                            .replace(/[^a-z0-9-]+/gi, '')   // Quitar todo lo que no es del a-z, A-Z o 0-9 (excepto el simbolo "-")
                                            .replace(/--+/g, '-')   // Convertir multiples "-" en uno solo
                                            .replace(/^-/, '')      // Quita el simbolo "-" al inicio
                                            .replace(/-$/, '');     // Quita el simbolo "-" al final
              // console.log(newStr);
          return newStr;
          }else{
          return 'error';
          }
        },

        _converToURL(name, id){
          var url = '';
          var newName = this._clearString(name);

          if( /^\d+$/.test(id) ){ // Si es un numero
            url = newName + '-' + id;
          }else{
            url = '/error';
          }

          return url;
        },

        _getURLID(url){
          var num = url.lastIndexOf('-');
          var idx = url.substring(num + 1);
          var idx = ( /^\d+$/.test(idx) ) ? parseInt(idx) : 'error';

          return idx;
        },

        _getURLName(url)
        {
          var explode = url.split('-');
          return explode[0];
        },

        _getURLNameTitle(url)
        {
            var num = url.lastIndexOf('-');

            var urll = url.substr(0, num);

            const regex = /-/gi;
            urll = urll.replace(regex, " ");
            return urll;
        },
        selectLang(){
          var cookieLang = sessionStorage.getItem('language');
          // console.log(cookieLang);
  
          if(cookieLang){
            if(cookieLang == 'en'){
              this.$i18n.locale = 'en';
            }else{
              this.$i18n.locale = 'es';
            }
          }
          else{
            this.$i18n.locale = 'es';
            sessionStorage.setItem('language', 'es');
          }
        },
  
        changeLocale(lang = 'es'){
          if(lang == 'en'){
            this.$i18n.locale = 'en';
            sessionStorage.setItem('language', 'en');
          }else{
            this.$i18n.locale = 'es';
            sessionStorage.setItem('language', 'es');
          }
        },
    },
    beforeMount(){
      this.selectLang();
    },
    mounted:function(){
    this.auth();
  },
  mode: 'history',
  i18n,
});
