<template>
    <div>
      <ol class="breadcrumb 2">
              <li><router-link to="/home"><i class="entypo-home"></i>Home</router-link></li>
              <li class="active"><strong>Compras de creditos</strong></li>
          </ol>
          <h2 class="margin-bottom">Compras de creditos</h2>
  
          <div class="row">
              <div class="col-md-12">
                  <div id="toolbar">
                      <!-- <router-link to="/userscredits/edit">
                          <button class="btn btn-success btn-sm">
                              <i class="fa fa-plus"></i> Nuevo
                          </button>
                      </router-link>
                      <button class="btn btn-danger btn-sm" @click="deleteRows()">
                          <i class="fa fa-trash"></i> Borrar
                      </button> -->

                      <button class="btn btn-info btn-sm" @click="payRows()">
			            <i class="fa fa-check"></i> Marcar como pagado
			        </button>
                  </div>
                  <table id="table"></table>
              </div>
          </div>
      </div>
  </template>
  <script type="text/javascript">
    export default {
    data(){
      return {
        rows:[],
      }
    },
    methods:{
      mounthTable(){
        jQuery('#table').bootstrapTable({
            columns: [
						{
							field:"check",
							checkbox:true,
							align: 'center',
						},
				
						
						{
					        field: 'id',
					        title: 'ID de compra',
					        sortable:true,
							switchable:true,
							
						},
                        {
					        field: 'customer',
					        title: 'Cliente',
					        sortable:true,
							switchable:true,
							
						},
						{
					        field: 'payment_method',
					        title: 'Metodo de pago',
					        sortable:true,
							switchable:true,
							
						},
						{
					        field: 'status',
					        title: 'Estatus',
					        sortable:true,
							switchable:true,
							
						},
						{
					        field: 'quantity',
					        title: 'Creditos',
					        sortable:true,
							switchable:true,
							
						},
						{
					        field: 'total',
					        title: 'Total',
					        sortable:true,
							switchable:true,
							
						},
						
						{
					        field: 'expires_on',
					        title: 'Expira en',
					        sortable:true,
							switchable:true,
							
						},
						{
					        field: 'package',
					        title: 'Paquete',
					        sortable:true,
							switchable:true,
							
						},
						{
					        field: 'created',
					        title: 'Fecha y hora de registro',
					        sortable:true,
							switchable:true,
							
						},
						
					 
					],
          showRefresh:true,
        });
  
        jQuery('#table').on('refresh.bs.table',()=>{
          this.getContent();
        });
  
        jQuery('#table').on('click-row.bs.table',(row,data)=>{
          this.$router.push('/userscredits/edit/'+data.id);
        });
  
        this.getContent();
  
      },
  
      getContent(){
        this.$parent.inPetition=true;
        axios.get(tools.url("/api/admin/userscredits")).then((response)=>{
            this.rows = response.data;
            jQuery('#table').bootstrapTable('removeAll');
            jQuery('#table').bootstrapTable('append',this.rows);
            this.$parent.inPetition=false;
          }).catch((error)=>{
              this.$parent.handleErrors(error);
              this.$parent.inPetition=false;
          });
      },
  
      payRows:function(){
        var rows=jQuery('#table').bootstrapTable('getSelections');
        if(rows.length==0){
          return false;
        }
        alertify.confirm("Alerta!","¿Seguro que deseas modificar "+rows.length+" registros?",()=>{
          this.$parent.inPetition=true;
          var params={};
          params.ids=jQuery.map(rows,(row)=>{
            return row.id;
          });
  
          axios.post(tools.url('/api/admin/userscreditsstatus'),params)
          .then((response)=>{
            this.$parent.showMessage(response.data.msg,"success");
            this.getContent();
            this.$parent.inPetition=false;
          })
          .catch((error)=>{
            this.$parent.handleErrors(error);
                this.$parent.inPetition=false;
          });
        },
        ()=>{
        });
      }
    },
        mounted() {
            this.mounthTable();
        }
    }
  </script>
  