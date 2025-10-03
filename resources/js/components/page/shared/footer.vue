<template lang="html">
  <div>
    <footer id="footernew">
      <div class="container-fluid py-5">
        <div class="row m-0 p-0">
          <div class="col-12">
            <div class="row m-0 p-0">
              <div class="col-12 col-md-4">
                <img src="/images/redesign/logo-white.webp" class="img-fluid w-100 mb-3" style="max-width: 296px;" alt="Logo NotaryNet">
              </div>
              <div class="col-12 col-md-4">
                  <h5 class="footer-title mb-3">MAPA DEL SITIO</h5>
                  <ul>
                    <li>
                        <router-link to="/usuario/comprar-creditos" class="footer-link">Créditos</router-link>
                    </li>
                    <li>
                      <router-link to="/terminos-y-condiciones" class="footer-link">Términos y condiciones</router-link>
                    </li>
                    <li>
                      <router-link to="/solicita-tu-factura" class="footer-link">Solicita tu factura</router-link>
                    </li>
                    <li>
                      <router-link to="/aviso-de-privacidad" class="footer-link">Aviso de Privacidad</router-link>
                    </li>
                  </ul>
              </div>
              <div class="col-12 col-md-4">
                <h5 class="footer-title mb-3">CONTACTO RÁPIDO</h5>
                <ul>
                  <li hidden>
                    <a href="javascript:void(0)" class="footer-link">Av. Juárez 624, Adolfo L. Mateos. C.P. 77667 Cozumel, Quintana Roo</a>
                  </li>
                  <li>
                    <a href="tel:+5219878688658" class="footer-link">Teléfono. +52 1 987 868 8658</a>
                  </li>
                  <li>
                    <a href="mailto:hola@notarionet.com" class="footer-link">Correo. hola@notarionet.com</a>
                  </li>
                  <li>
                      <img src="/images/svgs/facebook.svg" style="margin-right: 10px; width: 20px; height: 20px;" alt="Facebook">
                      <img src="/images/svgs/Twitter.svg" style="margin-right: 10px; width: 20px; height: 20px;" alt="Twitter">
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-12">
              <hr style="margin: 20px 0; border: 1px solid #6D6D6D;">
          </div>
          <div class="col-12">
              <p style="color: #ffffff; font-size: 14px;">
                Todos los documentos expedidos cuentan con la cláusula modelo de arbitraje expedito y de baja cuantía del Centro de Arbitraje Empresarial, S.C.P. (“CAE”). <br>
                  Para mas información accede a: <a href="https://cae.com.mx/">https://cae.com.mx/</a><br>
                  Confirma con nuestro prestador de servicios de certificación PSC, Seguridata Privada, S.A. de C.V., la validez de los documentos firmados por NOTARIONET en la siguiente liga:<br>
                  <a href="https://validador.seguridata.com:8443/ValidacionNOM/sgdata/home#hash" target="_blank" class="w-break-word">https://validador.seguridata.com:8443/ValidacionNOM/sgdata/home#hash</a>
              </p>
          </div>
        </div>
      </div>
    </footer>
    <!-- Modal de búsqueda -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="100" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white" id="staticBackdropLabel">Busca el contrato que necesitas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body border-0">
                    <form class="d-flex" @submit="onSubmit">
                        <input
                            class="form-control"
                            type="search"
                            :placeholder="$t('header.search_placeholder') || 'Buscar contrato...'"
                            v-model="formSearch.keywords"
                            style="min-width: 100%;"
                        >
                    </form>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <button type="button" class="btn btn-secondary btn-header" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary btn-header" @click="onSubmit">Buscar</button>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
export default {
    data(){
        return{
            formSearch: {
                keywords: null
            }
        }
    },
    methods: {
        onSubmit(event) {
            event.preventDefault();
            this.$router.push({path: '/contratos', query: {keywords:this.formSearch.keywords}});
            // Cerrar el modal después de la búsqueda
            this.closeModal();
        },
        closeModal() {
            // Cerrar el modal usando jQuery/Bootstrap
            if (typeof window.$ !== 'undefined') {
                window.$('#staticBackdrop').modal('hide');
            }
        }
    }
}
</script>

<style>

    #footernew {
    background-color: #000000;
    color: #ffffff;
    padding: 20px;
    text-align: center;
    }
    #footernew ul {
    list-style: none;
    padding: 0;
    margin: 0;
    margin-bottom: 10px;
    }
    #footernew ul li {
        text-align: start;
        padding-top: 20px;
    }

    .footer-title {
    font-size: 16px;
    font-weight: 400;
    text-align: start;
    }
    .footer-link{
        font-size: 16px;
        font-weight: 300;
        color: #ffffff !important;
        text-decoration: none;
    }
    p.footer-link {
        text-align: start;
        font-size: 16px;
    }
    .footer-link:hover {
    color:rgb(119, 119, 119) !important;
    text-decoration: none;
    transition: all 0.3s ease;
    }
    .text-newsletter {
        font-size: 16px;
        font-weight: 400;
        text-align: start;
    }
    #newsletter-email {
        background-color: transparent;
        border: 1px solid #ffffff;
        color: #ffffff;
        border-radius: 0;
        padding: 10px;
        font-size: 16px;
        font-weight: 400;
        text-align: start;
        height: 100%;
    }
    button[type="submit"] {
        background-color: transparent;
        color: #ffffff;
        border: 1px solid #ffffff;
        border-radius: 0;
        padding: 10px;
        font-size: 16px;
        font-weight: 400;
    }
</style>
