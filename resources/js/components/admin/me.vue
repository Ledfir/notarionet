<template>
	<div class="row">
		<div class="col-md-offset-1 col-md-10">
			
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						<i class="fa fa-user"></i> Perfil
					</div>
					<div class="panel-options">
						<a @click="$router.push('/home/')"><i class="fas fa-times"></i></a>
					</div>
				</div>
				
				<div class="panel-body">
					<form role="form" class="form-horizontal" @submit.prevent="newUser($event.target)">

						<input-form name="nombre" text="Nombre" :data.sync="user.name" validate="alpha_spaces|required"></input-form>
						<input-form name="lastname" text="Apellidos" :data.sync="user.lastname" validate="alpha_spaces|required"></input-form>
						<input-form name="email" text="Email" :data.sync="user.email" validate="email|required"></input-form>
						<input-form type="password" name="password" text="Password" :data.sync="user.password" :validate="rule_password" place="Solo si desea cambiarla"></input-form>
						<input-form type="tel" name="telefono" text="Telefono" :data.sync="user.phone" validate="digits:10"></input-form>
																
						<div class="form-group">
							<div class="col-sm-12">
								
								<button type="submit" class="btn btn-success pull-right"><i class="far fa-save"></i> Guardar</button> 
								<button type="button" class="btn btn-default pull-right" @click="$router.push('/home/')">Cancelar</button>
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
					lastname:"",
					email:"",
					password:"",
					phone:""
				},				
				
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
			newUser(form){
				this.$parent.validateAll(()=>{
					var data = tools.params(form, this.user);
					axios.post(tools.url("/api/admin/profile"), data).then((response)=>{
				    	this.$parent.auth();
				    	this.$parent.showMessage("Tu perfil a sido modificado correctamente!","success");
				    }).catch((error)=>{
				    	this.$parent.handleErrors(error);
				    });
					
				},(e)=>{
					console.log(e);
				});				
			},

		},
		mounted(){
			this.user = this.$parent.user;
		}
	}
</script>