<template lang="html">
    <div id="info-page-s1">
        <section class="container oversized-container text-justify main-section" v-if="$i18n.locale == 'en'">
            <h1 class="title-s-1">Frequently Asked Questions</h1>
            <div class="accordion" id="accordioneEn">
                <div class="card">
                    <div class="card-header" id="headingOneEn">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseOneEn" aria-expanded="true" aria-controls="collapseOneEn">
                                What is an electronic document?
                            </button>
                        </h2>
                    </div>
                    <div id="collapseOneEn" class="collapse" aria-labelledby="headingOneEn" data-parent="#accordioneEn">
                        <div class="card-body">
                            <p>
                                It can be said that “the concept of a document includes all the objects that can be brought before a judge and that serve as evidence in a trial, since they represent a thought or an intention; This is why not only manuscripts are considered documents.”<br />
                                <br />
                                The electronic document. It is one whose material support is given through the intervention of electronic systems or devices, which can be understood by man through digital signals.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwoEn">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwoEn" aria-expanded="false" aria-controls="collapseTwoEn">
                                What is the value of an electronic document?
                            </button>
                        </h2>
                    </div>
                    <div id="collapseTwoEn" class="collapse" aria-labelledby="headingTwoEn" data-parent="#accordioneEn">
                        <div class="card-body">
                            <p>
                                An electronic document has the same value as a private document and, consequently, its probative value consists in the fact that it has a content that appears on a support or container, and that the requirements for notarization and signature concur, with certain particularities: the support it is computational, uses technological writing means or binary machine languages ​​and is attributable to whoever imitates it and types the keys, keys, or magnetic codes (which are more secure than handwritten signatures).
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingThreeEn">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThreeEn" aria-expanded="false" aria-controls="collapseThreeEn">
                                What is a timestamp?
                            </button>
                        </h2>
                    </div>
                    <div id="collapseThreeEn" class="collapse" aria-labelledby="headingThreeEn" data-parent="#accordioneEn">
                        <div class="card-body">
                            <p>
                                With Time Stamping it is possible to demonstrate that a series of data have existed and have not been altered from a specific instant in time.<br />
                                <br />
                                A Certification Service Provider is approved by the Mexican Ministry of Economy as a Time Stamping Authority and acts as a trusted third party testifying to the existence of said electronic data on a specific date and time.<br />
                                <br />
                                The applications of the electronic time stamp are multiple, being used in processes of certified digitalization of invoices, storage of all types of electronic documents and any process in which it is key to certify that certain electronic data existed at a given time in the form in which they were presented to the Time Stamping Authority.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingFourEn">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFourEn" aria-expanded="false" aria-controls="collapseFourEn">
                                What is a digital certificate?
                            </button>
                        </h2>
                    </div>
                    <div id="collapseFourEn" class="collapse" aria-labelledby="headingFourEn" data-parent="#accordioneEn">
                        <div class="card-body">
                            <p>
                                A digital certificate is a document that identifies people or companies in the digital environment. Due to its nature, it is non-transferable and verifies/authenticates the identity of the signatory in multiple transactions.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingFiveEn">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFiveEn" aria-expanded="false" aria-controls="collapseFiveEn">
                                What is an advanced electronic signature?
                            </button>
                        </h2>
                    </div>
                    <div id="collapseFiveEn" class="collapse" aria-labelledby="headingFiveEn" data-parent="#accordioneEn">
                        <div class="card-body">
                            <p>
                                An advanced electronic signature is an electronic signature that has fulfilled the requirements established in EU Regulation No. 910/2014 on electronic identification and trust services for electronic transactions in the single European market.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="container oversized-container text-justify main-section" v-else>
            <h1 class="title-s-1 mb-4">Preguntas frecuentes</h1>
            <div class="accordion" id="accordioneEs">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                ¿Qué es un documento electrónico?
                            </button>
                        </h2>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordioneEs">
                        <div class="card-body">
                            <p>
                                Se puede decir que “el concepto de documento comprende todos los objetos que pueden ser llevados ante un juez y que sirven como prueba en un juicio, pues representan un pensamiento o una intención; en ello estriba que no sólo los manuscritos se consideran documentos”.<br />
                                <br />
                                El documento electrónico. Es aquel cuyo soporte material se da mediante la intervención de sistemas o dispositivos electrónicos, que pueden ser entendidos por el hombre a través de las señales digitales.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                ¿Qué valor tiene un documento electrónico?
                            </button>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordioneEs">
                        <div class="card-body">
                            <p>
                                Un documento electrónico tiene el mismo valor de un documento privado y, en consecuencia, su valor probatorio consiste en que tiene un contenido que consta en un soporte o continente, y concurren en él los requisitos de escrituración y firma, con ciertas particularidades: el soporte es computacional, usa medios de escrituración tecnológica o lenguajes de máquina binarios y es atribuible a quien lo imita y digite las claves, llaves, o códigos magnéticos (que son más seguros que las firmas manuscritas).
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                ¿Qué es el sello de tiempo?
                            </button>
                        </h2>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordioneEs">
                        <div class="card-body">
                            <p>
                                Con el Sellado de Tiempo es posible demostrar que una serie de datos han existido y no han sido alterados desde un instante específico en el tiempo.<br />
                                <br />
                                Un Prestador de Servicios de Certificación está homologado por la Secretaría de Economía de México como Autoridad de Sellado de Tiempo y actúa como tercero de confianza testificando la existencia de dichos datos electrónicos en una fecha y hora concretos.<br />
                                <br />
                                Las aplicaciones del sello electrónico de tiempo son múltiples, siendo empleado en procesos de digitalización certificada de facturas, almacenamiento de todo tipo de documentos electrónicos y cualquier proceso en el que sea clave certificar que determinados datos electrónicos existían en un momento dado en la forma en que fueron presentados a la Autoridad de Sellado de Tiempo.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingFour">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                ¿Qué es un certificado digital?
                            </button>
                        </h2>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordioneEs">
                        <div class="card-body">
                            <p>
                                Un certificado digital es un documento que identifica a las personas o empresas en el entorno digital. Por su carácter, es intransferible y verifica/autentica la identidad del firmante en múltiples transacciones.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingFive">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                ¿Qué es la firma electrónica avanzada?
                            </button>
                        </h2>
                    </div>
                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordioneEs">
                        <div class="card-body">
                            <p>
                                Una firma electrónica avanzada es una firma electrónica que ha cumplido los requisitos establecidos en el Reglamento de la UE nº 910/2014 sobre la identificación electrónica y los servicios de confianza para las transacciones electrónicas en el mercado único europeo.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    mounted() {
        // Inicializar Bootstrap collapse cuando el componente se monta
        this.$nextTick(() => {
            // Inicializar todos los elementos collapse
            const collapseElements = this.$el.querySelectorAll('.collapse');
            collapseElements.forEach(element => {
                if (typeof window.$ !== 'undefined' && window.$.fn.collapse) {
                    window.$(element).collapse({
                        toggle: false
                    });
                }
            });
        });
    }
}
</script>

<style lang="css">
    .btn-link:hover {
        color: #0056b3;
        text-decoration: none !important;
    }
</style>
