<template lang="html">
  <div id="text-page">
    <div class="container" v-if="$i18n.locale == 'en'">
      <h1 class="mb-2 page-title">NOTICE OF PRIVACY</h1>
      <p v-html="content.body_en"></p>
    </div>
    <div class="container" v-else>
      <h1 class="mb-2 page-title">AVISO DE PRIVACIDAD</h1>
      <p v-html="content.body"></p>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return{
      content: '',
    }
  },

  methods: {
    getContent: function(){
      axios.get(tools.url('/api/aviso')).then((response)=>{
        this.content = response.data;;
      }).catch((error)=>{
        console.log(error);
      })
    }
  },

  beforeMount(){
     this.getContent();
  }
}
</script>
