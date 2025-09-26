<template>
  <div>
    <ol class="breadcrumb 2">
			<li><router-link to="/home"><i class="entypo-home"></i>Home</router-link></li>
			<li class="active"><strong>Contratos generados</strong></li>
		</ol>
		<h2 class="margin-bottom">Contratos generados</h2>

		<div class="row">
			<div class="col-md-12">
				<div id="toolbar">
			        <!-- <router-link to="/orders/edit">
			        	<button class="btn btn-success btn-sm">
				            <i class="fa fa-plus"></i> Nuevo
				        </button>
			        </router-link>
			        <button class="btn btn-danger btn-sm" @click="deleteRows()">
			            <i class="fa fa-trash"></i> Borrar
			        </button> -->
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
      orders:[],
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
            title: '#',
            sortable:true,
            switchable:true,
          },
          {
            field: 'contractbtn',
            title: 'Contrato',
            sortable:true,
            switchable:true,
          },
          {
            field: 'user_name',
            title: 'Contratante',
            sortable:true,
            switchable:true,
          },
          {
            field: 'user_contra_name',
            title: 'Contraparte',
            sortable:true,
            switchable:true,
          },
          {
            field: 'created',
            title: 'Fecha de Emision',
            sortable:true,
            switchable:true,
          },
          /*{
            field: 'date_vi',
            title: 'Fecha de vigencia',
            sortable:true,
            switchable:true,
          },*/
          {
            field: 'certificate',
            title: 'Certificado digital',
            sortable:true,
            switchable:true,
          },
          {
            field: 'stamp',
            title: 'Sello de tiempo',
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
        //this.$router.push('/orders/edit/'+data.id);
      });
      /*var orders = [];
      var order = {
          id:1,
          type:'Tipo 1',
          name:'Cliente sustam',
          company:'Empresa',
          date:'05-06-20222',
          date_vi:'05-12-2022',
          certificate:'sa2e33ddas234232ed',
          sello:'234df2w2weerfr34',
          
      };
      orders.push(order);
      jQuery('#table').bootstrapTable('removeAll');
          jQuery('#table').bootstrapTable('append',orders);*/
     this.getContent();

    },

    getContent(){
      this.$parent.inPetition=true;
      axios.get(tools.url("/api/admin/contracts_generated")).then((response)=>{
          this.orders = response.data;
          jQuery('#table').bootstrapTable('removeAll');
          jQuery('#table').bootstrapTable('append',this.orders);
          this.$parent.inPetition=false;
        }).catch((error)=>{
            this.$parent.handleErrors(error);
            this.$parent.inPetition=false;
        });
    },

    deleteRows:function(){
      var rows=jQuery('#table').bootstrapTable('getSelections');
      if(rows.length==0){
        return false;
      }
      alertify.confirm("Alerta!","¿Seguro que deseas borrar "+rows.length+" registros?",()=>{
        this.$parent.inPetition=true;
        var params={};
        params.ids=jQuery.map(rows,(row)=>{
          return row.id;
        });

        axios.delete(tools.url('/api/admin/orders'),{data:params})
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
