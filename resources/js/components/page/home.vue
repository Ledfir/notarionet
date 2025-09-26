<template lang="html">
    <div id="home-container">
        <div id="home-page-old" hidden>
            <section class="banner-section">
                <swiper class="swiper swiper-s1 swiper-desktop" :options="bannersOptions">
                    <swiper-slide v-for="(banner, bhInx) in banners.pc" :key="'bhInx-'+bhInx">
                        <router-link class="placed-backg" v-bind:style="{ backgroundImage: 'url('+banner.imagen_en+')' }" to="" v-if="$i18n.locale == 'en'">
                            <img src="/images/pages/home/banner.png">
                        </router-link>
                        <router-link class="placed-backg" v-bind:style="{ backgroundImage: 'url('+banner.imagen+')' }" to="" v-else>
                            <img src="/images/pages/home/banner.png">
                        </router-link>
                    </swiper-slide>
                    <div class="swiper-button-prev" slot="button-prev"></div>
                    <div class="swiper-button-next" slot="button-next"></div>
                </swiper>
                <swiper class="swiper swiper-s1 swiper-mobile" :options="bannersOptions">
                    <swiper-slide v-for="(banner, bhInx) in banners.movil" :key="'bhInx-'+bhInx">
                        <router-link class="placed-backg" v-bind:style="{ backgroundImage: 'url('+banner.imagen_en+')' }" to=""  v-if="$i18n.locale == 'en'">
                            <img src="/images/pages/home/banner-m.png">
                        </router-link>
                        <router-link class="placed-backg" v-bind:style="{ backgroundImage: 'url('+banner.imagen+')' }" to=""  v-else>
                            <img src="/images/pages/home/banner-m.png">
                        </router-link>
                    </swiper-slide>
                    <div class="swiper-button-prev" slot="button-prev"></div>
                    <div class="swiper-button-next" slot="button-next"></div>
                </swiper>
            </section>
            <section class="how-works-section">
                <div class="container oversized-container">
                    <h2 class="title-s-1"> {{ $t("home.how_work") }}</h2>
                    <div class="row justify-content-center">
                        <div class="col-sm-6 col-lg-4 col-how">
                            <img src="/images/pages/home/how-1.png">
                            <h5 v-html="$t('home.step_one')"></h5>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-how">
                            <img src="/images/pages/home/how-2.png">
                            <h5>{{ $t("home.step_two") }}</h5>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-how">
                            <img src="/images/pages/home/how-3.png">
                            <h5>{{ $t("home.step_three") }}</h5>
                        </div>
                    </div>
                </div>
            </section>
            <section class="advantages-section">
                <div class="container oversized-container">
                    <h2 class="title-s-1">{{ $t("home.advantages") }}</h2>
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-image">
                            <img src="/images/pages/home/img-advantages.jpg">
                        </div>
                        <div class="col-lg-6 col-info">
                            <div class="flex-center-xy box-info">
                                <div class="inside">
                                    <p>{{ $t("home.advantages_one") }} (https://www.seguridata.com/)</p>
                                </div>
                            </div>
                            <div class="flex-center-xy box-info">
                                <div class="inside">
                                    <p>{{ $t("home.advantages_two") }} (https://www.seguridata.com/)</p>
                                </div>
                            </div>
                            <div class="flex-center-xy box-info">
                                <div class="inside">
                                    <p>{{ $t("home.advantages_three") }}</p>
                                </div>
                            </div>
                            <div class="flex-center-xy box-info">
                                <div class="inside">
                                    <p>{{ $t("home.advantages_four") }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="container oversized-container packages-section">
                <h2 class="title-s-1">{{ $t("home.credits") }}</h2>
                <div class="row">
                    <div class="col-12 col-carousel">
                        <swiper class="swiper" :options="packsOptions">
                            <swiper-slide v-for="(p, phInx) in packages" :key="'phInx-'+phInx">
                                <div class="col-12 px-0 box-credit-s1">
                                    <router-link class="box" to="/creditos">
                                        <div class="col-12 col-info-1">
                                            <h4 class="num" v-html="p.credits"></h4>
                                            <h5 class="name"> {{ $i18n.locale == 'en' ? p.name_en  : p.name }}</h5>
                                            <div class="descr">
                                            {{ $i18n.locale == 'en' ? p.description_en  : p.description }}
                                            </div>
                                            <span class="price">$ {{ Intl.NumberFormat("en-US", { minimumFractionDigits: 2 }).format(p.price) }} MXN</span>
                                        </div>
                                        <div class="col-12 align-items-center col-info-2">
                                            <div class="col col-text">{{ $t("home.pay_with") }}:</div>
                                            <div class="col col-image"><img src="/images/shared/cards-v2.svg"></div>
                                        </div>
                                    </router-link>
                                </div>
                            </swiper-slide>
                            <div class="swiper-pagination" slot="pagination"></div>
                        </swiper>
                    </div>
                </div>
            </section>
            <section class="placed-backg banner-2-section">
                <router-link class="t-150 btn-full" to="/implementa-notarynet"></router-link>
                <div class="container oversized-container">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="title"> {{ $t("home.desing") }}</h2>
                            <p class="mt-3">
                                <a class="btn btn-s1 bg-blue">CONTACTAR</a>
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="accreditations-section">
            <div class="container oversized-container">
                <h2 class="title-s-1">{{ $t("home.accreditations") }}</h2>

                <div class="row">
                <div class="col-sm-6 col-lg-3 box-accreditation-s1" @click="openModalCer('Certificados Digitales', 'public/docs/pdf-1.pdf')">
                    <div class="box-image">
                    <img src="/images/pages/contracts/icon-certificate.svg">
                    </div>

                    <div class="box-info">
                    <h6 class="title">{{ $t("home.accreditations_one") }}</h6>
                    <!-- <h5 class="date">DOF 20/06/2011</h5> -->
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3 box-accreditation-s1" @click="openModalCer('Constancias NOM-151', 'public/docs/pdf-2.pdf')">
                    <div class="box-image">
                    <img src="/images/pages/contracts/icon-certificate.svg">
                    </div>

                    <div class="box-info">
                    <h6 class="title">{{ $t("home.accreditations_two") }}</h6>
                    <!-- <h5 class="date">DOF 14/12/2011</h5> -->
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3 box-accreditation-s1" @click="openModalCer('Sellos de Tiempo', 'public/docs/pdf-3.pdf')">
                    <div class="box-image">
                    <img src="/images/pages/contracts/icon-certificate.svg">
                    </div>

                    <div class="box-info">
                    <h6 class="title">{{ $t("home.accreditations_three") }}</h6>
                    <!-- <h5 class="date">DOF 14/12/2011</h5> -->
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3 box-accreditation-s1" @click="openModalCer('Digitalización de Documentos en Soporte Físico', 'public/docs/pdf-4.pdf')">
                    <div class="box-image">
                    <img src="/images/pages/contracts/icon-certificate.svg">
                    </div>

                    <div class="box-info">
                    <h6 class="title">{{ $t("home.accreditations_four") }}</h6>
                    <!-- <h5 class="date">DOF 03/12/2019</h5> -->
                    <!-- <h5 class="date f-w-400 pt-1">ASESORÍA JURÍDICA GRATUÍTA</h5> -->
                    </div>
                </div>
                </div>
            </div>
            <!-- Modal para ver los pdfs -->
            <b-modal modal-class="modal-content-s1 modal-documents" ref="modal-show-file" :title="modalObj.title" size="xl" no-close-on-backdrop no-close-on-esc centered hide-footer>
                <iframe class="doc-iframe" :src="modalObj.pdfLink"></iframe>
            </b-modal>
            <!--  -->
            </section>
            <b-modal modal-class="modal-announcement-s1" ref="modal-home" title="" no-close-on-backdrop centered hide-header hide-footer>
                <p>
                    Emite documentos y certificados digitales de cualquier autoridad certificadora (SAT, PSC- Prestador de Servicios de Certificación u otra).<br />
                    <br />
                    Firma desde un portal web o desde dispositivos móviles Android y iOS.
                </p>
                <p class="mt-3">
                    <a class="t-250 btn btn-s1 bg-blue" @click="$refs['modal-home'].hide()">ENTENDIDO</a>
                </p>
            </b-modal>
            <b-modal modal-class="modal-validator-info" ref="modal-validator-info" size="lg" title="" no-close-on-backdrop centered hide-footer>
                <div class="box-top">
                    <div class="col-6 col-image">
                        <img src="/images/logo.svg">
                    </div>
                    <div class="col-6 col-image">
                        <img src="/images/shared/seguridata.png">
                    </div>
                </div>
                <div class="box-title">
                    <p>{{ $t("home.modal.validate") }}</p>
                </div>
                <div class="box-content">
                    <ol class="steps">
                        <li>
                            {{ $t("home.modal.one") }}:<br />
                            <a class="mt-1 t-150 _link" target="_blank" href="https://validador.seguridata.com:8443/ValidacionNOM/sgdata/home#hash"><i class="far fa-shield-check"></i> {{ $t("home.modal.validator") }}</a>
                        </li>
                        <li>
                            <p>
                                {{ $t("home.modal.two") }}
                            </p>
                            <div class="row box-images">
                                <div class="col-md-6 col-image">
                                    <div class="placed-backg img" v-bind:style="{ backgroundImage: 'url(/images/pages/home/prev-1.jpg)' }"></div>
                                    <i class="far fa-long-arrow-right arrow-right"></i>
                                </div>
                                <div class="col-md-6 col-image">
                                    <div class="placed-backg img" v-bind:style="{ backgroundImage: 'url(/images/pages/home/prev-2.jpg)' }"></div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <p>
                                {{ $t("home.modal.three") }}
                            </p>
                            <div class="row box-images">
                                <div class="col-12 col-image">
                                    <div class="placed-backg img lg" v-bind:style="{ backgroundImage: 'url(/images/pages/home/prev-3.jpg)' }"></div>
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>
            </b-modal>
        </div>
        <div id="home-page">
            <a class="t-250 btn-fl-validator" @click="$refs['modal-validator-info'].show()">
                <i class="fas fa-shield-check"></i> {{ $t("home.validator") }}
            </a>
            <div id="presentation-section">
                <div class="container row-presentation">
                    <div class="row px-0 m-0 align-items-center">
                    <div class="col-12 col-md-6">
                        <h1 class="mb-3">Firma electrónica<br />legal en minutos</h1>
                        <p class="mb-3">
                        NOTARIONET te permite firmar documentos legales de manera rápida y segura desde
                        cualquier lugar. Simplifica tus trámites legales con nuestra plataforma confiable y
                        fácil de usar.
                        </p>
                        <a href="javascript:void(0)" class="btn btn-primary mr-4 scale-down-center">Firma ahora</a>
                        <a href="javascript:void(0)" class="btn btn-outline-secondary scale-down-center">Empieza</a>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="d-block">
                        <div class="row m-0 p-0">
                            <div class="col-6 p-2">
                            <div class="card-img-presentation scale-up-center">
                                <img src="/images/redesign/notariimg1.webp" class="img-fluid" alt="Firma electrónica"/>
                            </div>
                            </div>
                            <div class="col-6 p-2">
                            <div class="card-img-presentation w-100 h-100 p-3 card-blue">
                                <div class="w-100 h-100 text-right">
                                    <h3 class="text-white text-right">890</h3>
                                    <p class="text-white text-right">Documentos<br>firmados</p>
                                </div>
                            </div>
                            </div>
                            <div class="col-6 p-2">
                            <div class="card-img-presentation w-100 h-100 p-3 card-black">
                                <div class="w-100 h-100 ">
                                    <h3 class="text-white ">+250</h3>
                                    <p class="text-white">Empresas<br>firmando</p>
                                </div>
                            </div>
                            </div>
                            <div class="col-6 p-2">
                            <div class="card-img-presentation scale-up-center">
                                <img src="/images/redesign/notariimg2.webp" class="img-fluid" alt="Firma electrónica"/>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div id="beneficios" class="pb-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-center mb-4">Beneficios</h6>
                            <h2 class="text-center mb-4">Ventajas de utilizar<br> NOTARIONET para firmar</h2>
                            <h4 class="text-center mb-4 text-beneficio">
                                Con NOTARIONET la firma de documentos es más rápida que nunca.<br>
                                Aseguramos un proceso legal y seguro para todos nuestros usuarios.
                            </h4>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card-beneficio text-center h-100">
                                <img src="/images/redesign/mexico.png" class="img-fluid" alt="Firma electrónica"/>
                                <h3>Legalidad 100% mexicana y países de la OCDE</h3>
                                <p>Todos los documentos firmados cumplen con la NOM-151 y son legalmente válidos en México y en los países miembros de la OCDE.</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card-beneficio text-center h-100">
                                <img src="/images/redesign/document.png" class="img-fluid" alt="Firma electrónica"/>
                                <h3>Contratos listos para usar</h3>
                                <p> Evita problemas legales: utiliza nuestros documentos prediseñados y adaptados por especialistas.</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card-beneficio text-center h-100">
                                <img src="/images/redesign/pen.png" class="img-fluid" alt="Firma electrónica"/>
                                <h3>Firma electrónica sin complicaciones</h3>
                                <p>No necesitas escanear, imprimir ni trasladarte. Firma desde tu computadora o celular.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="proceso" class="py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <p class="text-center text-badge-proceso mb-4 text-white">Proceso</p>
                            <h2 class="text-center mb-4 title-proceso text-white">Así funciona nuestra<br>firma electrónica legal</h2>
                            <p class="subtitle-proceso text-center text-white mb-4">
                                Firmar documentos nunca ha sido tan fácil. Sigue estos simples pasos para completar tu firma electrónica de manera rápida y segura.
                            </p>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="card-proceso text-center">
                                <h2>1</h2>
                                <h4>Compra tu<br>documento</h4>
                                <p>
                                    Selecciona el contrato que necesitas (arrendamiento, trabajo, NDA, etc.).
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="card-proceso text-center">
                                <h2>2</h2>
                                <h4>Págalo en línea</h4>
                                <p>
                                    Haz tu pago de forma segura con tarjeta, PayPal o transferencia.
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="card-proceso text-center">
                                <h2>3</h2>
                                <h4>Llénalo o súbelo</h4>
                                <p>
                                    Personaliza tu contrato con nuestro formulario o sube tu documento listo para firmar.
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="card-proceso text-center">
                                <h2>4</h2>
                                <h4>Fírmalo electrónicamente</h4>
                                <p>
                                    Recibe tu documento con firma válida, respaldo legal, sello de tiempo y certificado digital.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center py-4">
                        <button class="btn btn-proceso">
                            Comprar un documento
                        </button>
                    </div>
                </div>
            </div>
            <div id="credits" class="container oversized-container packages-section">
                <h2 class="title-s-1 text-center">{{ $t("home.credits") }}</h2>
                <div class="row">
                    <div class="col-12 col-carousel">
                        <swiper class="swiper" :options="packsOptions">
                            <swiper-slide v-for="(p, phInx) in packages" :key="'phInx-'+phInx">
                                <div class="col-12 px-0 box-credit-s1">
                                    <router-link class="box" to="/creditos">
                                        <div class="col-12 col-info-1">
                                            <h4 class="num" v-html="p.credits"></h4>
                                            <h5 class="name"> {{ $i18n.locale == 'en' ? p.name_en  : p.name }}</h5>
                                            <div class="descr">
                                            {{ $i18n.locale == 'en' ? p.description_en  : p.description }}
                                            </div>
                                            <span class="price">$ {{ Intl.NumberFormat("en-US", { minimumFractionDigits: 2 }).format(p.price) }} MXN</span>
                                        </div>
                                        <div class="col-12 align-items-center text-center">
                                            <div class="py-3">{{ $t("home.pay_with") }}:</div>
                                            <img src="/images/shared/cards-v2.svg">
                                        </div>
                                    </router-link>
                                </div>
                            </swiper-slide>
                            <div class="swiper-pagination" slot="pagination"></div>
                        </swiper>
                    </div>
                </div>
            </div>
            <div id="testimonios" class="py-5">
                <div class="container">
                    <div class="row p-0 m-0">
                        <div class="col-12 col-md-6">
                            <h2 class="title-testimonios">Testimonios<br>de clientes</h2>
                            <p class="mb-4 text-testimonios">Nuestros usuarios confían en NOTARIONET para sus documentos.</p>
                            <img src="/images/redesign/image-testimonials.webp" class="img-fluid" alt="Firma electrónica"/>
                        </div>
                        <div class="col-12 col-md-6 col-testimonios">
                            <div class="my-4 mx-5">
                                <div class="card-testimonio">
                                    <div class="card-testimonio-body">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                        <p class="text-testimonio-body mb-4">"Firmé un contrato de arrendamiento en menos de 15 minutos. Rápido y sin complicaciones. 100% confiable"<p>
                                        <p class="name-testimonio">Juan Pérez</p>
                                        <p class="ubic-testimonio">Gerente, CDMX</p>
                                    </div>
                                </div>
                                <div class="card-testimonio">
                                    <div class="card-testimonio-body">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                        <p class="text-testimonio-body mb-4">“Uso la carta poder para trámites familiares. Me ahorré ir al notario y todo fue legal”<p>
                                        <p class="name-testimonio">Andrés Molina</p>
                                        <p class="ubic-testimonio">Gerente, MTY</p>
                                    </div>
                                </div>
                                <div class="card-testimonio">
                                    <div class="card-testimonio-body">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                        <p class="text-testimonio-body mb-4">"Soy freelancer y firmo acuerdos de confidencialidad con mis clientes. Notari Net me da seguridad y seriedad."<p>
                                        <p class="name-testimonio">Gabriela Garza</p>
                                        <p class="ubic-testimonio">Gerente, GDL</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="firma-tu-documento" class="py-5">
                <div class="container">
                    <div class="container-firma">
                        <div class="row p-0 m-0">
                            <div class="col-12 col-md-7" style="place-content: center;">
                                <h2 class="title-firma mb-2">Firma tu documento<br>rápido y sin trámites</h2>
                                <p class="text-firma mb-2">
                                    Compra tu contrato, fírmalo en línea y recíbelo con validez legal en minutos.
                                </p>
                                <button class="btn btn-firma">
                                    Comprar
                                </button>
                            </div>
                            <div class="col-12 col-md-5">
                                <img src="/images/redesign/documento.png" class="img-fluid" alt="Firma electrónica"/>
                            </div>
                        </div>
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
      banners: {
        pc: [
          //{ imgURL: 'public/images/pages/home/banner-1.jpg' },
        ],
        movil: [
          //{ imgURL: 'public/images/pages/home/banner-1-m.jpg' },
        ],
      },

      packages: [
        { id: 1, n: '1', name:'Contrato, acuerdo o documento', imgURL: '/images/pages/packages/paper-1.svg', price: '500',total:500 },
        { id: 2, n: '3', name:'Documentos', imgURL: '/images/pages/packages/paper-2.svg', price: '1000',total:1000 },
        { id: 3, n: '5', name:'Documentos', imgURL: '/images/pages/packages/paper-3.svg', price: '2500',total:2500 },
      ],

      contracts: [
        { imgURL: '/images/pages/contracts/agreement-1.jpg', name: 'Confidencialidad', descr: 'Contrato para trabajo' },
        { imgURL: '/images/pages/contracts/agreement-2.jpg', name: 'Préstamos', descr: 'Económico pequeño o grande' },
        { imgURL: '/images/pages/contracts/agreement-3.jpg', name: 'Inversión', descr: 'Económico pequeño o grande' },
        { imgURL: '/images/pages/contracts/agreement-4.jpg', name: 'Contratación', descr: 'Para empleados' },
      ],

      modalObj: {
        title: null,
        pdfLink: null,
      },

      // == Carousels options ==
      bannersOptions: {
        slidesPerView: 1,
        loop: true,

        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },

        autoplay: {
          delay: 4500,
          disableOnInteraction: false
        },
      },

      packsOptions: {
        loop: false,
        slidesPerView: 3,
        spaceBetween: 30,
        loopedSlides: 3,

        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },

        breakpoints: {
          992: {
            slidesPerView: 3,
          },
          768: {
            slidesPerView: 2,
          },
          1: {
            slidesPerView: 1,
          },
        }
      },

      contractsOptions: {
        slidesPerView: 4,
        spaceBetween: 30,

        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev'
        },

        breakpoints: {
          1400: {
            slidesPerView: 4,
          },
          992: {
            slidesPerView: 3,
          },
          768: {
            slidesPerView: 2,
          },
          1: {
            slidesPerView: 1,
          },
        }
      }
      // == ==
    }
  },
  methods:{
    getBanners(){
      axios.get(tools.url("/api/banners")).then((response)=>{
          this.banners=response.data;
        }).catch((error)=>{

        });
    },
    getPackages(){
        axios.get(tools.url('/api/packages')).then((response)=>{
            this.packages = response.data;
        }).catch((error)=>{

        });
    },

    openModalCer(title, pdfLink) {
      this.modalObj = {
        title: title,
        pdfLink: pdfLink,
      },

      this.$refs['modal-show-file'].show();
    },
  },

  mounted(){
    // this.$refs['modal-home'].show();

    this.getBanners();
    this.getPackages();
  }
}
</script>

<style>
    #presentation-section {
        min-height: 800px;
        align-content: center;
    }
    #presentation-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        min-height: 800px;
        height: 100%;
        background-color: transparent;
        background-image: linear-gradient(288deg, var(--primary-color) 0%, #ffffff 73%);
        opacity: 0.25;
        z-index: -1;
    }
    #presentation-section h1 {
        font-size: 60px;
        font-weight: 300;
        line-height: 53px;
    }
    #presentation-section p {
        font-size: 16px;
        font-weight: 400;
        line-height: 26px;
    }
    #presentation-section .row-presentation {
        padding-top: 100px;
        padding-bottom: 100px;
        padding-left: 0;
        padding-right: 0;
    }
    #presentation-section .card-img-presentation {
        max-width: 265px;
        max-height: 258px;
    }
    #presentation-section .card-blue {
        background-color: #1738F4;
        border-top-left-radius: 170px;
    }
    #presentation-section .card-black {
        background-color: #252525;
        border-top-right-radius: 170px;
    }
    #presentation-section .card-blue div, #presentation-section .card-black div {
        align-content: center;
    }
    #presentation-section .card-blue h3, #presentation-section .card-black h3 {
        font-size: 60px;
        font-weight: 300;
    }
    #presentation-section .card-blue p, #presentation-section .card-black p {
        font-size: 28px;
        font-weight: 300;
    }
    @media (max-width: 768px) {
        #presentation-section .card-img-presentation {
            max-width: 110px;
            max-height: 110px;
        }
    }
    #beneficios h6{
        font-size: 16px;
        font-weight: 700;
        color: #000000;
        text-align: center;
        margin-bottom: 20px;
        margin-top: 20px;
    }
    #beneficios h2{
        font-size: 65px;
        font-weight: 300;
        color: #000000;
        text-align: center;
        margin-bottom: 20px;
        margin-top: 20px;
    }
    #beneficios .card-beneficio {
        border: 1px solid #CACACA;
        border-radius: 20px;
        padding: 40px;
    }
    #beneficios .card-beneficio img {
        width: 84px;
        height: 84px;
        margin-bottom: 10px;
    }
    #beneficios .card-beneficio h3 {
        font-size: 28px;
        font-weight: 400;
        color: #000000;
        margin-bottom: 10px;
    }
    #beneficios .card-beneficio p {
        font-size: 14px;
        font-weight: 400;
        color: #000000;
    }
    .text-beneficio {
        font-size: 14px;
        font-weight: 400;
        color: #000000;
    }
    #proceso {
        background-color: #004AAD;
    }
    .text-badge-proceso {
        font-weight: 400;
        font-style: Italic;
        font-size: 16px;
        line-height: 150%;
        letter-spacing: 0%;
        color: #FFFFFF;
    }
    .title-proceso {
        font-weight: 300;
        font-size: 60px;
        line-height: 120%;
        letter-spacing: 0%;
        text-align: center;
        color: #FFFFFF;
    }
    .subtitle-proceso {
        font-weight: 400;
        font-size: 16px;
        line-height: 150%;
        letter-spacing: 0%;
        text-align: center;
        color: #FFFFFF;
    }
    .card-proceso {
        border: 2px solid transparent;
        border-radius: 20px;
        padding: 20px;
        height: 100%;
        transition: all 0.3s ease;
    }
    .card-proceso:hover {
        background-color:rgb(0, 0, 0);
        border: 2px solid #FFFFFF;
    }
    .card-proceso h2 {
        font-size: 65px;
        font-weight: 300;
        color: #FFFFFF;
        margin-bottom: 10px;
        font-style: italic;
    }
    .card-proceso h4 {
        font-size: 28px;
        font-weight: 400;
        color: #FFFFFF;
        margin-bottom: 10px;
    }
    .card-proceso p {
        font-size: 14px;
        font-weight: 400;
        color: #FFFFFF;
    }
    .btn-proceso {
        background-color: #FFFFFF;
        color: #000000;
        border-radius: 20px;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: 400;
        border: 2px solid #FFFFFF;
        transition: all 0.3s ease;
    }
    .btn-proceso:hover {
        background-color: #000000;
        color: #FFFFFF;
        border: 2px solid #FFFFFF;
    }
    .title-testimonios {
        font-weight: 300;
        font-size: 65px;
        line-height: 120%;
        letter-spacing: 0%;
        text-align: left;
        color: #000000;
    }
    .text-testimonios {
        font-weight: 400;
        font-size: 20px;
        line-height: 150%;
        letter-spacing: 0%;
        text-align: left;
        color: #000000;
    }
    .card-testimonio {
        border: 1px solid transparent;
        border-radius: 20px;
        padding: 20px;
        height: 100%;
        transition: all 0.3s ease;
        margin-bottom: 10px;
    }
    .card-testimonio:hover {
        background-color: #cacaca;
        transform: scale(1.05);
        border: 1px solid #CACACA;
    }
    .card-testimonio-body {
        text-align: left;
    }
    .name-testimonio {
        font-weight: 400;
        font-size: 16px;
        line-height: 150%;
    }
    .ubic-testimonio {
        font-weight: 400;
        font-size: 12px;
        line-height: 150%;
    }
    .text-testimonio-body {
        font-weight: 400;
        font-size: 20px;
        line-height: 150%;
    }
    .col-testimonios{
        border-left: 1px solid #CECECE;
    }
    .container-firma {
        background:
            url("/images/redesign/logo-fondo.png"),
            linear-gradient(182.98deg, #1738F4 -25.39%, #000000 170.47%);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: bottom right;
        border-radius: 40px;
        padding: 40px;
    }
    .title-firma {
        font-weight: 300;
        font-size: 60px;
        line-height: 120%;
        letter-spacing: 0%;
        text-align: left;
        color: #FFFFFF;
    }
    .text-firma {
        font-weight: 300;
        font-size: 20px;
        line-height: 150%;
        letter-spacing: 0%;
        text-align: left;
        color: #FFFFFF;
    }
    .btn-firma {
        background-color: #FFFFFF;
        color: #000000;
        border-radius: 30px;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: 400;
        border: 2px solid #FFFFFF;
        transition: all 0.3s ease;
    }
    .btn-firma:hover {
        background-color: #000000;
        color: #FFFFFF;
        border: 2px solid #FFFFFF;
    }
    .box-credit-s1 .box{
        border-radius: 20px;
    }
    .box-credit-s1 .box .col-info-1 .price{
        border-radius: 20px;
    }
</style>
