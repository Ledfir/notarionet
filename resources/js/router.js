import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

//Componentes
//import Login from './components/admin/Login.vue';

const page="./components/page/";

const MyRouter = new VueRouter({
  	routes:[
	    { path: '/', component: require(page+'home.vue').default },
        { path: '/documentos', component: require(page+'documentos/index.vue').default, meta:{title:"Documentos"}},
        { path: '/validez-legal-de-documento-electronico', component: require(page+'analisis-legal/index.vue').default, meta:{title:"Validez legal de documento electrónico"}},
        { path: '/creditos', component: require(page+'creditos/index.vue').default, meta:{title:"Créditos"}},
        { path: '/contacto', component: require(page+'contacto/index.vue').default, meta:{title:"Contacto"}},
        { path: '/acreditaciones', component: require(page+'empresa/acreditaciones.vue').default, meta:{title:"Acreditaciones"}},
        { path: '/solucion-de-conflictos-de-documentos-electronicos', component: require(page+'empresa/afiliacion-cae.vue').default, meta:{title:"Solución de conflictos de documentos electrónicos"}},
        { path: '/preguntas-frecuentes', component: require(page+'empresa/faqs.vue').default, meta:{title:"Preguntas frecuentes"}},
        { path: '/nuestra-empresa', component: require(page+'empresa/acerca-de.vue').default, meta:{title:"Nuestra empresa"}},
        { path: '/solicita-tu-factura', component: require(page+'contacto/solicita-tu-factura.vue').default, meta:{title:"Solicita tu factura"}},
        { path: '/implementa-notarynet', component: require(page+'contacto/implementa-notarynet.vue').default, meta:{title:"Diseña e implementa NOTARYNET® en tu negocio"}},

        { path: '/contratos', component: require(page+'contratos/results.vue').default, meta:{title:"Contratos"}},
        { path: '/contratos/:id', component: require(page+'contratos/detail.vue').default, meta:{title:"Contrato"}},
        { path: '/contrato/:id', component: require(page+'contratos/validate.vue').default, meta:{title:"Contrato"}},

        { path: '/terminos-y-condiciones', component: require(page+'text-pages/terminos.vue').default, meta:{title:"Términos y condiciones"}},
        { path: '/aviso-de-privacidad', component: require(page+'text-pages/aviso.vue').default, meta:{title:"Aviso de privacidad"}},

        { path: '/login', component: require(page+'user-access/login.vue').default, meta:{title:"Iniciar sesión"}},
        { path: '/recuperar-contrasena', component: require(page+'user-access/reset-password.vue').default, meta:{title:"Recuperar contraseña"}},
        { path: '/registrarse', component: require(page+'user-access/register.vue').default, meta:{title:"Registrarse"}},
        { path: '/registrarse/:id', component: require(page+'user-access/register.vue').default, meta:{title:"Registrarse"}},
        { path: '/confirmar/:id', component: require(page+'user-access/confim-email.vue').default, meta:{title:"Confirmación de correo electronico"}},

        { path: '/usuario', component: require(page+'usuario-cuenta/index.vue').default, meta:{ title: 'Mi datos' },
  			children: [
    			{
    				path: '/',
    				component: require(page+'usuario-cuenta/mi-informacion.vue').default,
    				meta:{ title: 'Mi información básica' }
    			},
          {
    				path: 'documentacion',
    				component: require(page+'usuario-cuenta/mi-documentacion.vue').default,
    				meta:{ title: 'Mi documentación' }
    			},
          {
    				path: 'fotografia',
    				component: require(page+'usuario-cuenta/mi-fotografia.vue').default,
    				meta:{ title: 'Mi fotografía' }
    			},
          {
    				path: 'firma',
    				component: require(page+'usuario-cuenta/mi-firma.vue').default,
    				meta:{ title: 'Mi firma' }
    			},
    			{
    				path: 'contrasena',
    				component: require(page+'usuario-cuenta/mi-contrasena.vue').default,
    				meta:{ title: 'Mi Contraseña' }
    			},
          {
    				path: 'nuevo-contrato',
    				component: require(page+'usuario-cuenta/contrato-nuevo.vue').default,
    				meta:{ title: 'Nuevo documento a certificar' }
    			},
          {
    				path: 'contratos',
    				component: require(page+'usuario-cuenta/contractos.vue').default,
    				meta:{ title: 'Documentos a certificar' }
    			},
          {
    				path: 'contactos',
    				component: require(page+'usuario-cuenta/contactos.vue').default,
    				meta:{ title: 'Contactos' }
    			},
          {
    				path: 'comprar-creditos',
    				component: require(page+'usuario-cuenta/comprar-creditos.vue').default,
    				meta:{ title: 'Comprar créditos' }
    			},

				{
    				path: 'historico-pagos',
    				component: require(page+'usuario-cuenta/historico-pagos.vue').default,
    				meta:{ title: 'Historico de pagos' }
    			},
  	  	]
  		}
	    // { path: '/checkout', component: require(page+'checkout.vue').default, meta:{title:"Checkout"}},
	  ]
});

MyRouter.beforeEach((to, from, next) => {
	window.scrollTo(0,0);
	if(window.app.__vue__ && window.app.__vue__.$refs.loadingBar){
		window.app.__vue__.$refs.loadingBar.start();
	}
	next();
});

MyRouter.afterEach((to, from) => {

	if(window.app.__vue__ && window.app.__vue__.$refs.loadingBar){
		setTimeout(()=>{
			window.app.__vue__.$refs.loadingBar.done();
		},500);
	}


});

//Titulos del website
import VueDocumentTitlePlugin from "vue-document-title-plugin";
Vue.use(VueDocumentTitlePlugin, MyRouter,
	{ defTitle: "NOTARYNET - Firma y certifica documentos de forma digital", filter: (title)=>{ return title+" | NOTARYNET - Firma y certifica documentos de forma digital"; } }
);

// export {routes};
export default MyRouter;
