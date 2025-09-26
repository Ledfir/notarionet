<template>
  <div>
    <ol class="breadcrumb 2">
			<li><router-link to="/home"><i class="entypo-home"></i>Home</router-link></li>
			<li class="active"><strong>Productos</strong></li>
		</ol>
		<h2 class="margin-bottom">Productos</h2>

		<div class="row">
			<div class="col-md-12">
				<div id="toolbar">
			        <router-link to="/products/edit">
			        	<button class="btn btn-success btn-sm">
				            <i class="fa fa-plus"></i> Nuevo
				        </button>
			        </router-link>
			        <button class="btn btn-danger btn-sm" @click="deleteRows()">
			            <i class="fa fa-trash"></i> Borrar
			        </button>
              <!-- <router-link to="/products/import">
              <button class="btn btn-success btn-sm">
                  <i class="fa fa-file"></i> Importar
              </button>
            </router-link> -->
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
      productos:[],
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
            field: 'sku',
            title: 'SKU',
            sortable:true,
            switchable:true,
          },
          {
            field: 'name',
            title: 'Nombre',
            sortable:true,
            switchable:true,
          },
          {
            field: 'stock',
            title: 'cantidad',
            sortable:true,
            switchable:true,
          },
          {
            field: 'category.name',
            title: 'Categoria',
            sortable:true,
            switchable:true,
          },
          {
            field: 'subcategory.name',
            title: 'Subcategoria',
            sortable:true,
            switchable:true,
          },
          {
            field: 'created_at',
            title: 'Fecha de creación',
            sortable:true,
            switchable:true,
          }
        ],
        showRefresh:true,
        
      });

      jQuery('#table').on('refresh.bs.table',()=>{
        this.getContent();
      });

      jQuery('#table').on('click-row.bs.table',(row,data)=>{
        this.$router.push('/products/edit/'+data.id);
      });

      this.getContent();

    },

    getContent(){
      axios.get(tools.url("/api/admin/products")).then((response)=>{
          this.productos = response.data;
          jQuery('#table').bootstrapTable('removeAll');
          jQuery('#table').bootstrapTable('append',this.productos);
        }).catch((error)=>{
            this.$parent.handleErrors(error);
        });
    },

    deleteRows:function(){
      var rows=jQuery('#table').bootstrapTable('getSelections');
      if(rows.length==0){
        return false;
      }
      alertify.confirm("Alerta!","¿Seguro que deseas borrar "+rows.length+" registros?",()=>{
        var params={};
        params.ids=jQuery.map(rows,(row)=>{
          return row.id;
        });

        axios.delete(tools.url('/api/admin/products'),{data:params}).then((response)=>{
          this.$parent.showMessage(response.data.msg,"success");
          this.getContent();
        })
        .catch((error)=>{
          this.$parent.handleErrors(error);
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
