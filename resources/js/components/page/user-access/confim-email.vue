<template lang="html">
    <div id="info-page-s1">
    
      <section class="container text-center main-section">
            <div>
                <!-- <img src="public/images/logo.png" width="200px"><br><br>
                <h3>DOCUMENTO CERTIFICADO POR NOTARIONET</h3> -->
  
            
                <img src="public/images/circlecheck.png" width="30%"><br><br>
  
                
                <b>Gracias</b><br>
                Tu correo electrónico ha sido confirmado
                
                <br><br>
                Cualquier duda o comentario contáctanos a ayuda@notarionet.com
            </div>
            <br><br>
      </section>
  
    </div>
  </template>
  
  <script>
  export default {
        data() {
          return {
                id: null,
                row:{}
           }
        },
        methods:{
          getRow(){
                axios.get(tools.url('/api/confirmemail/' + this.id)).then((response)=>{
              this.row = response.data;
             
            }).catch((error)=>{
              console.log(error);
            });
            }
        },
  
         mounted(){
          this.id = this.$route.params.id;
           this.getRow();
        }
  }
  </script>
  <style>
  table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
  }
  
  td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
  }
  </style>