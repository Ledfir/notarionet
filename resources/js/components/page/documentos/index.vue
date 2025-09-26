<template lang="html">
    <div>
        <div id="header-documents">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h1>Documentos</h1>
                    </div>
                    <div class="col-12 col-md-6" style="justify-items: right;">
                        <p>
                            100% válidos en México y demás países de la OCDE….
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container py-5">
            <div class="row">
                <!-- Cards de categorías y contratos -->
                <div
                    v-for="(item, index) in categories.slice(1)"
                    :key="index + 1"
                    class="col-12 col-md-6 col-lg-4 mb-4"
                >
                    <!-- Card para categorías (encabezados) -->
                    <div
                        v-if="item.category"
                        class="card category-header-card"
                    >
                        <div class="card-body text-center">
                            <h5 class="card-title category-title">
                                {{ $i18n.locale == 'en' ? item.name_en : item.name }}
                            </h5>
                        </div>
                    </div>

                    <!-- Card para contratos -->
                    <div
                        v-else
                        class="card contract-card h-100"
                        @click="navigateToContract(item.id)"
                        style="cursor: pointer;"
                    >
                        <div class="card-body d-flex flex-column">
                            <img :src="item.images" alt="img" class="img-fluid">
                            <h6 class="card-title contract-title my-3 text-center">
                                <b>{{ $i18n.locale == 'en' ? item.name_en : item.name }}</b>
                            </h6>
                            <div class="txt-description" v-html="item.description"></div>
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
            getCategoriesCard(){
                axios.get(tools.url("/api/categoriesContracts")).then((response)=>{
                    this.categories = response.data;
                }).catch(()=>{});
            },
            navigateToContract(contractId) {
                this.$router.push(`/contratos/${contractId}`);
            }
        },
        mounted(){
            this.getCategoriesCard();
        }
    }
</script>

<style scoped>
    .contract-card img{
        border-radius: 20px;
    }
    .txt-description p{
        text-align: justify;
    }
    #header-documents{
        min-height: 300px;
        position: relative;
        align-content: center;
    }
    #header-documents::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        min-height: 300px;
        height: 100%;
        background-color: transparent;
        background-image: linear-gradient(288deg, var(--primary-color) 0%, #ffffff 73%);
        opacity: 0.25;
        z-index: -1;
    }
    #header-documents h1 {
        font-size: 60px;
        font-weight: 300;
        line-height: 53px;
    }

    /* Estilos para las cards de categorías */
    .category-header-card {
        background: linear-gradient(135deg,rgb(0, 113, 189) 0%, #004BAF 100%);
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 189, 0, 0.2);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .category-header-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 189, 0, 0.3);
    }

    .category-title {
        color: white;
        font-weight: 600;
        font-size: 1.2rem;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Estilos para las cards de contratos */
    .contract-card {
        border: 2px solid #e9ecef;
        border-radius: 15px;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .contract-card:hover {
        border-color: #004BAF;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 113, 189, 0.15);
    }

    .contract-title {
        color: #333;
        font-weight: 500;
        font-size: 1rem;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .contract-card:hover .contract-title {
        color: #004BAF;
    }

    .contract-card:hover .text-muted {
        color: #004BAF !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .category-title {
            font-size: 1.1rem;
        }

        .contract-title {
            font-size: 0.95rem;
        }
    }
</style>
