<template>
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb 2">
				<li><router-link to="/home"><i class="entypo-home"></i>Home</router-link></li>
				<li class="active"><strong>Roles</strong></li>
			</ol>
		</div>

		<div class="col-md-8 col-md-offset-2">
			<div id="toolbar">
	           	<button class="btn btn-success btn-sm" @click="addRole()">
		            <i class="fa fa-plus"></i> Nuevo
		        </button>
		        
		    </div>
			<table id='roles'></table>
		</div>
	</div>
</template>
<script type="text/javascript">
	export default {
		data(){
			return {
				roles:{},

			}
		},
		methods:{
			getRoles:function(){
				axios.get(tools.url("/api/admin/roles")).then((response)=>{
					this.roles=response.data;
					this.roles.forEach((v,k)=>{
			    		this.roles[k].users=v.users.length;
			    	});
					jQuery('#roles').bootstrapTable('removeAll');
			    	jQuery('#roles').bootstrapTable('append',this.roles);
				})
				.catch((error)=>{
					this.$parent.handleErrors(error);
				});
			},

			addRole:function(){
				alertify.prompt('Nombre del rol:', '', (evt, value)=>{
					var params={
						name:value
					};
					
					axios.post(tools.url("/api/admin/role"),params).then((response)=>{
						this.$parent.showMessage(response.data.msg,"success");
						this.getRoles();
					})
					.catch((error)=>{
						this.$parent.handleErrors(error);
					});
				});
			},
			mountTable:function(){
				jQuery('#roles').bootstrapTable({
					columns: [
						{
							field:"check",
							checkbox:true,
							align: 'center',
						},
						{
					        field: 'name',
					        title: 'Nombre',
					        sortable:true,
							switchable:true,
							
					    }, 
					    {
					        field: 'users',
					        title: 'Usuarios totales',
					        sortable:true,
							switchable:true,
							
					    }
					],
				    // data: this.users,
					//Boton de refrescar
					// showRefresh:true,
		            //Define si tienen detalles cada fila       
		            // detailView:true,
		            // detailFormatter:"detailFormatter",
            		
				});

				jQuery('#roles').on('refresh.bs.table',()=>{
					this.getRoles();
				});

				jQuery('#roles').on('click-row.bs.table',(row,data)=>{
					this.$router.push('/roles/edit/'+data.id);
					// console.log(data);
				});

				this.getRoles();
			}
		},
		mounted(){
			this.mountTable();
		}
	}
</script>