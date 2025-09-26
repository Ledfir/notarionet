<template>
  <div>
    <ol class="breadcrumb 2">
			<li><router-link to="/home"><i class="entypo-home"></i>Home</router-link></li>
			<li class="active"><strong>Blog</strong></li>
		</ol>
		<h2 class="margin-bottom">Blog</h2>

		<div class="row">
			<div class="col-md-12">
				<div id="toolbar">
            <router-link to="/blogs/edit"><button class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Nuevo</button></router-link>
			      <button class="btn btn-danger btn-sm" @click="deleteNews()"><i class="fa fa-trash"></i> Borrar</button>
			    </div>
				<table id="news"></table>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
  export default {
  data(){
    return {
      news:[],
    }
  },
  methods:{
    mounthTable(){
      jQuery('#news').bootstrapTable({
        columns: [
          {
            field:"check",
            checkbox:true,
            align: 'center',
          },
          {
            field: 'id',
            title: '#',
            sortable:false,
            switchable:true,
          }, 
          {
            field: 'imagen',
            title: ' ',
            sortable:false,
            width:"52px",
          },         
          {
            field: 'title',
            title: 'Titulo',
            sortable:true,
            switchable:true,
          },
          {
            field: 'created',
            title: 'Fecha de registro',
            sortable:true,
            switchable:true,
          },
          {
            field: 'updated',
            title: 'Fecha de actualización',
            sortable:true,
            switchable:true,
          }

          
        ],
        showRefresh:true,
      });

      jQuery('#news').on('refresh.bs.table',()=>{
        this.getNews();
      });

      jQuery('#news').on('click-row.bs.table',(row,data)=>{
        this.$router.push('/blogs/edit/'+data.id);
      });

      this.getNews();

    },
    getNews(){
      this.$parent.inPetition=true;
      axios.get(tools.url("/api/admin/blogs")).then((response)=>{
          this.news = response.data;
          jQuery('#news').bootstrapTable('removeAll');
          jQuery('#news').bootstrapTable('append',this.news);
          this.$parent.inPetition=false;
        }).catch((error)=>{
            this.$parent.handleErrors(error);
            this.$parent.inPetition=false;
        });
    },

    deleteNews:function(){
      var rows=jQuery('#news').bootstrapTable('getSelections');
      if(rows.length==0){
        return false;
      }
      alertify.confirm("Alerta!","¿Seguro que deseas borrar "+rows.length+" registros?",()=>{
        this.$parent.inPetition=true;
        var params={};
        params.ids=jQuery.map(rows,(row)=>{
          return row.id;
        });

        axios.delete(tools.url('/api/admin/blogs'),{data:params})
        .then((response)=>{
          this.$parent.showMessage(response.data.msg,"success");
          this.getNews();
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
