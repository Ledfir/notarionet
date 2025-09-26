<template lang="html">
  <div id="info-page-s1">
  
    <section class="container text-center main-section">
      	<div>
      		<!-- <img src="public/images/logo.png" width="200px"><br><br>
      		<h3>DOCUMENTO CERTIFICADO POR NOTARIONET</h3> -->

      	
      		<img src="public/images/circlecheck.png" width="40%"><br><br>

      		
      		<b>TIPO DE DOCUMENTO {{row.title}}</b>
      		<br><br>
			  <div class="col-12 box">
				<a :href="row.documentUrl" target="_blank" class="btn btn-sm btn-s1 bg-black" style="width: 100%;"><i class="fas fa-file-pdf"></i> Descargar pdf</a>
			</div>
			<br><br>
			<div class="col-12 box">
				<a :href="row.documentUrlNom" target="_blank" class="btn btn-sm btn-s1 bg-black" style="width: 100%;"><i class="fas fa-asterisk"></i> Descargar NOM</a>
			</div>
			<br><br>
      		<b>FECHA DE CREACION:</b>
      		{{row.created}}<br>

      		<!--<br><br>
      		<table>
      			<tr>
      				<td>Fecha y hora de firma. Parte 1</td>
      				<td>{{row.date_signature_user}}</td>
      			</tr>
      			<tr>
      				<td>Fecha y hora de firma. Parte 2</td>
      				<td>{{row.date_signature_user_contra}}</td>
      			</tr>
      			<tr>
      				<td>Fecha y hora de creación</td>
      				<td>{{row.created}}</td>
      			</tr>
      			<tr>
      				<td>Sello de tiempo</td>
      				<td>{{row.stamp}}</td>
      			</tr>
      			<tr>
      				<td>Certificado digital</td>
      				<td>{{row.certificate}}</td>
      			</tr>
      			
      		</table> -->
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
      		axios.get(tools.url('/api/contractsvalidate/' + this.id)).then((response)=>{
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