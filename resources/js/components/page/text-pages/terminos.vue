<template lang="html">
  <div id="text-page">
    <div class="container" v-if="$i18n.locale == 'en'">
      <h1 class="mb-2 page-title">TERMS AND CONDITIONS</h1>
      <p v-html="content.body_en"></p>
    </div>
    <div class="container" v-else>
      <h1 class="mb-2 page-title">TÉRMINOS Y CONDICIONES</h1>
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
      axios.get(tools.url('/api/terminos')).then((response)=>{
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
