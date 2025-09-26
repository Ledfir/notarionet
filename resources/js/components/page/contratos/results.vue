<template lang="html">
  <div class="container" id="contracts-results-page">

    <section class="container oversized-container main-section">
      <h1 class="title-s-1 page-title">CONTRATOS</h1>

      <div class="row box-results">
        <div class="col-md-6 col-lg-4" v-for="(a, ahInx) in contracts" :key="'ahInx-'+ahInx">
            <div
                class="card contract-card h-100"
                @click="navigateToContract(a.id)"
                style="cursor: pointer;"
            >
                <div class="card-body d-flex flex-column">
                    <img :src="a.imageUrl" alt="img" class="img-fluid">
                    <h6 class="card-title contract-title my-3 text-center">
                        <b>{{ a.title }}</b>
                    </h6>
                    <div class="txt-description" v-html="a.description"></div>
                </div>
            </div>
        </div>
      </div>
    </section>

  </div>
</template>

<script>
    // import vZoom from 'vue-zoom';
    export default {
        // components: {vZoom},

        data() {
            return {
                id: null,
                contracts:[]
            }
        },
        watch: {
            '$route.query.keywords':function(){
                this.getSearch();
            },

        },
        methods:{
            getSearch() {
                axios.post(tools.url('/api/contracts_search'),{keywords:this.$route.query.keywords}).then((response)=>{
                    this.contracts = response.data;
                }).catch((error)=>{
                    console.log(error);
                });
            },
            navigateToContract(contractId) {
                this.$router.push(`/contratos/${contractId}`);
            }
        },
        mounted(){
            if(this.$route.query.keywords){
                this.getSearch();
            }

        }
    }
</script>

<style scoped>
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
