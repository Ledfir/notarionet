<template lang="html">
    <div id="info-page-s1">
        <section class="placed-backg banner-section" v-bind:style="{ backgroundImage: 'url(https://notarionet.com/public/images/pages/documents/banner-1.jpg)' }"></section>
        <section class="container oversized-container main-section m-h-inherit">
            <h1 class="title-s-1">{{ $t("home.accreditations") }}</h1>
            <div class="row">
                <div class="col-sm-6 col-lg-3" @click="openModalCer('Certificados Digitales', 'https://notarionet.com/public/docs/pdf-1.pdf')">
                    <div class="card-beneficio text-center h-100 box-accreditation-s1 m-3">
                        <img src="https://notarionet.com/public/images/pages/contracts/icon-certificate.svg">
                        <h3>{{ $t("home.accreditations_one") }}</h3>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3" @click="openModalCer('Constancias NOM-151', 'https://notarionet.com/public/docs/pdf-2.pdf')">
                    <div class="card-beneficio text-center h-100 box-accreditation-s1 m-3">
                        <img src="https://notarionet.com/public/images/pages/contracts/icon-certificate.svg">
                        <h3>{{ $t("home.accreditations_two") }}</h3>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3" @click="openModalCer('Sellos de Tiempo', 'https://notarionet.com/public/docs/pdf-3.pdf')">
                    <div class="card-beneficio text-center h-100 box-accreditation-s1 m-3">
                        <img src="https://notarionet.com/public/images/pages/contracts/icon-certificate.svg">
                        <h3>{{ $t("home.accreditations_three") }}</h3>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3" @click="openModalCer('Digitalización de Documentos en Soporte Físico', 'https://notarionet.com/public/docs/pdf-4.pdf')">
                    <div class="card-beneficio text-center h-100 box-accreditation-s1 m-3">
                        <img src="https://notarionet.com/public/images/pages/contracts/icon-certificate.svg">
                        <h3>{{ $t("home.accreditations_four") }}</h3>
                    </div>
                </div>
            </div>
            <!-- Modal para ver los pdfs -->
            <b-modal modal-class="modal-content-s1 modal-documents" ref="modal-show-file" :title="modalObj.title" size="xl" no-close-on-backdrop no-close-on-esc centered hide-footer>
                <iframe class="doc-iframe" :src="modalObj.pdfLink"></iframe>
            </b-modal>
            <!--  -->
        </section>
    </div>
</template>

<script>
export default {
  data() {
    return {
      modalObj: {
        title: null,
        pdfLink: null,
      },
    }
  },

  methods: {
    openModalCer(title, pdfLink) {
      this.modalObj = {
        title: title,
        pdfLink: pdfLink,
      },

      this.$refs['modal-show-file'].show();
    },
  }
}
</script>

<style>
    .card-beneficio {
        border: 1px solid #CACACA;
        border-radius: 20px;
        padding: 40px;
    }
    .card-beneficio img {
        width: 84px;
        height: 84px;
        margin-bottom: 10px;
    }
    .card-beneficio h3 {
        font-size: 28px;
        font-weight: 400;
        color: #000000;
        margin-bottom: 10px;
    }
</style>
