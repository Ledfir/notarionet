<template>
	<div class="row">
		<div class="col-md-offset-1 col-md-10">
			
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						<i class="fa fa-user"></i> Usuario
					</div>
					<div class="panel-options">
						<a @click="$router.push('/users/')"><i class="fas fa-times"></i></a>
					</div>
				</div>
				
				<div class="panel-body">
					
					<form role="form" class="form-horizontal" @submit.prevent="newUser($event.target)">
						<input-form name="nombre" text="Nombre" :data.sync="user.name" validate="alpha_spaces|required|min:4"></input-form>
						<input-form name="lastname" text="Apellidos" :data.sync="user.lastname" validate="alpha_spaces|required|min:4"></input-form>
						<input-form name="email" text="Email" :data.sync="user.email" validate="email|required"></input-form>
						<input-form type="password" name="password" text="Password" :data.sync="user.password" :validate="rule_password" place="Solo si desea cambiarla"></input-form>
						<input-form type="tel" name="telefono" text="Telefono" :data.sync="user.phone" validate="digits:10"></input-form>
						<select-form text="Roles" name="roles" :options="roles" :data.sync="user.roles" :multiple="true"></select-form>
						<checkbox-form text="Acceso al panel de administración" :data.sync="user.access" value="1" name="access" key="a"></checkbox-form>
						
						<div class="form-group">
							<div class="col-sm-12">
								<button type="button" class="btn btn-danger" @click="deleteUser" v-show="$route.params.id"><i class="fa fa-trash"></i> Borrar</button>
								<button type="submit" class="btn btn-success pull-right"><i class="far fa-save"></i> Guardar</button> 
								<button type="button" class="btn btn-default pull-right" @click="$router.push('/users/')">Cancelar</button>			
							</div>
						</div>
					</form>	

				</div>			
			</div>		
		</div>

	</div>
</template>
<script type="text/javascript">
	export default {
		data(){
			return {
				user:{
					name:"",
					lastname: '',
					email:"",
					password:"",
					phone:"",
					access:0,
					roles:[],
				},
				
				roles:{},
				id:"",
				date:"",
				check:false,
			}
		},
		computed:{
			rule_password:function(){
				if(this.user.password==undefined || this.user.password.length==0){
					return '';
				}
				else{
					return 'min:5|required';
				}
			},
		},
		methods:{
			getUser(){
				axios.get(tools.url("/api/admin/user/"+this.id)).then((response)=>{
			    	this.user = response.data;
			    	this.user.roles=jQuery.map(this.user.roles,(row)=>{
						return row.name;
					});
			    }).catch((error)=>{
			    	this.$parent.handleErrors(error);  
			    });
			},

			getRoles:function(){
				axios.get(tools.url("/api/admin/roles")).then((response)=>{
					this.roles = response.data;
					this.roles = jQuery.map(this.roles,(row)=>{
						return row.name;
					});
					
				}).catch((error)=>{
					this.$parent.handleErrors(error);
					
				});
			},
			newUser(form){				
				this.$parent.validateAll(()=>{
					var data=tools.params(form,this.user);
					if(this.$route.params.id){
						axios.post(tools.url("/api/admin/user/"+this.id),data)
						.then((response)=>{
					    	this.getUser();
					    	this.$parent.showMessage("Usuario "+this.user.name+" modificado correctamente!","success");
					    	
					    }).catch((error)=>{
					    	this.$parent.handleErrors(error);
					    });

					}else{
						axios.post(tools.url("/api/admin/user"),data).then((response)=>{
							var user=response.data;
					    	this.$parent.showMessage("Usuario "+user.name+" agregado correctamente!","success");
					    	this.$router.push('/users/edit/'+user.id);
					    }).catch((error)=>{
					    	this.$parent.handleErrors(error);
					    });
					}
				},(e)=>{
					console.log(e);
				});				
			},

			deleteUser:function(){
				alertify.confirm("Alerta!","¿Seguro que deseas borrar este usuario?",()=>{
					axios.delete(tools.url("/api/admin/user/"+this.id)).then((response)=>{
						this.$parent.showMessage(response.data.msg,"success");
						this.$router.push("/users/");
					})
					.catch((error)=>{
						this.$parent.handleErrors(error);
					});
				},
				()=>{
					
				});
			},

		},
		mounted(){
			if(this.$route.params.id){
				this.id = this.$route.params.id;
				this.getUser();
			}
			this.getRoles();
		}
	}
</script>