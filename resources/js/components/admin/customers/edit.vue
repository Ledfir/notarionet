<template>
	<div class="row">
		<div class="col-md-offset-1 col-md-10">
			
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						<i class="fa fa-user"></i> Cliente <span v-if="id">{{ customer.name }}</span>
					</div>
					<div class="panel-options">
						<a @click="$router.push('/customers/')"><i class="fas fa-times"></i></a>
					</div>
				</div>
				
				<div class="panel-body">
					<div id="tabs">
						<ul class="nav nav-tabs">
							<li :class="active1"><a @click="active = 1" href="#1" data-toggle="tab">Información</a></li>
							<li :class="active2" v-if="id"><a @click="active = 2" href="#2" data-toggle="tab">Documentacion</a></li>
							<li :class="active3" v-if="id"><a @click="active = 3" href="#3" data-toggle="tab">Fotografia</a></li>
							<li :class="active4" v-if="id"><a @click="active = 4" href="#4" data-toggle="tab">Firma</a></li>
							<li :class="active5" v-if="id"><a @click="active = 5" href="#5" data-toggle="tab">Contratos</a></li>
							<li :class="active6" v-if="id"><a @click="active = 6" href="#6" data-toggle="tab">Creditos</a></li>
						</ul>
						<div class="tab-content">

							<div :class=" 'tab-pane ' + active1" id="1">
								<form role="form" class="form-horizontal" @submit.prevent="newCustomer($event.target)">

									<input-form name="nombre" text="Nombre" :data.sync="customer.name"></input-form>
									<input-form name="nombre" text="Apellidos" :data.sync="customer.lastname"></input-form>
									<div class="form-group">
										<label class="col-sm-3 control-label">Email:</label>
										<div class="col-sm-7">
											<input type="email" :data.sync="customer.email"  v-model="customer.email" class="form-control" id="emailcustomer":disabled="disabledinput">
										</div>
									</div>
									
									<!-- <input-form name="email" text="Email" :data.sync="customer.email" validate="email|required"></input-form> -->
									<!-- <input-form type="password" name="password" text="Password" :data.sync="customer.password" :validate="rule_password" place="Solo si desea cambiarla"></input-form> -->
									<div class="form-group">
										<label class="col-sm-3 control-label">Password:</label>
										<div class="col-sm-7">
											<input class="form-control" v-model="customer.password" name="row_lastname" type="password"  :validate="rule_password" placeholder="Solo si desea cambiarla" autocomplete="off" :disabled="disabledinput">
										</div>
									</div>
									<input-form type="tel" name="telefono" text="Telefono" :data.sync="customer.phone" validate="digits:10"></input-form>

									<input-form name="street" text="Calle" :data.sync="customer.street"></input-form>
									<input-form name="num_ext" text="Numero exterior" :data.sync="customer.num_ext"></input-form>
									<input-form name="num_int" text="Numero Interior" :data.sync="customer.num_init"></input-form>
									<input-form name="neighborhood" text="Colonia" :data.sync="customer.neighborhood"></input-form>
									<input-form name="cp" text="Código postal" :data.sync="customer.zipcode"></input-form>

									<div class="form-group">
										<label class="col-sm-3 control-label">Estado:</label>
										<div class="col-sm-7">
											<v-select v-model="customer.state_id" :options="estados" label="name" index="id" @change="getTowns" required/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label">Ciudad:</label>
										<div class="col-sm-7">
											<v-select v-model="customer.town_id" :options="ciudades" label="name" index="id" required/>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-sm-12">
											<button type="button" class="btn btn-danger" @click="deleteRow" v-show="$route.params.id"><i class="fa fa-trash"></i> Borrar</button>
											<button type="submit" class="btn btn-success pull-right"><i class="far fa-save"></i> Guardar</button> 
											<button type="button" class="btn btn-default pull-right" @click="$router.push('/customers/')">Cancelar</button>			
										</div>
									</div>
								</form>	
							</div>
							<div :class=" 'tab-pane ' + active2" id="2">
								<form role="form" class="form-horizontal" @submit.prevent="newCustomer($event.target)">

									<div class="form-group">
										<label class="col-sm-3 control-label">RFC:</label>
										<div class="col-sm-6">
											<div class="fileinput fileinput-new" data-provides="fileinput">
												<div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 150px" data-trigger="fileinput">
													<span v-if="customer.rfc_documents_id!=''">{{ customer.rfcDoc }}</span>
													<span v-else>Documento</span>
												</div>
												<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
												<div>
													<span class="btn btn-white btn-file">
														<span class="fileinput-new">Selecccionar Documento</span>
														<span class="fileinput-exists">Cambiar</span>
														<input type="file" accept=".pdf" name="rfc">
													</span>
													<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Quitar</a>
												</div>
											</div>
										</div>
										<div class="col-sm-1" v-if="customer.rfc_documents_id">
											<a :href=" customer.rfcDoc" target="_blank"><span class="btn btn-info" ><i class="fas fa-cloud-download-alt"></i></span></a>
															
											<label>DESCARGAR</label>
										</div>
									</div>
									<input-form name="rfc" text="RFC" :data.sync="customer.rfc"></input-form>


									<div class="form-group">
										<label class="col-sm-3 control-label">CURP:</label>
										<div class="col-sm-6">
											<div class="fileinput fileinput-new" data-provides="fileinput">
												<div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 150px" data-trigger="fileinput">
													<span v-if="customer.curp_documents_id!=''">{{ customer.curpDoc }}</span>
													<span v-else>Documento</span>
												</div>
												<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
												<div>
													<span class="btn btn-white btn-file">
														<span class="fileinput-new">Selecccionar Documento</span>
														<span class="fileinput-exists">Cambiar</span>
														<input type="file" accept=".pdf" name="curp">
													</span>
													<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Quitar</a>
												</div>
											</div>
										</div>
										<div class="col-sm-1" v-if="customer.curp_documents_id">
											<a :href=" customer.curpDoc" target="_blank"><span class="btn btn-info" ><i class="fas fa-cloud-download-alt"></i></span></a>
															
											<label>DESCARGAR</label>
										</div>
									</div>
									<input-form name="curp" text="CURP" :data.sync="customer.curp"></input-form>
									
									<div class="form-group">
										<label class="col-sm-3 control-label">Foto INE Frente:</label>
										<div class="col-sm-6">
											<div class="fileinput fileinput-new" data-provides="fileinput">
												<div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 150px" data-trigger="fileinput">
													<span v-if="customer.inefront_documents_id!=''">{{ customer.inefrontDoc }}</span>
													<span v-else>Documento</span>
												</div>
												<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
												<div>
													<span class="btn btn-white btn-file">
														<span class="fileinput-new">Selecccionar Documento</span>
														<span class="fileinput-exists">Cambiar</span>
														<input type="file" accept=".pdf" name="inefront">
													</span>
													<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Quitar</a>
												</div>
											</div>
										</div>
										<div class="col-sm-1" v-if="customer.inefront_documents_id">
											<a :href=" customer.inefrontDoc" target="_blank"><span class="btn btn-info" ><i class="fas fa-cloud-download-alt"></i></span></a>
															
											<label>DESCARGAR</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label">Foto INE Reverso:</label>
										<div class="col-sm-6">
											<div class="fileinput fileinput-new" data-provides="fileinput">
												<div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 150px" data-trigger="fileinput">
													<span v-if="customer.ineback_documents_id!=''">{{ customer.inebackDoc }}</span>
													<span v-else>Documento</span>
												</div>
												<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
												<div>
													<span class="btn btn-white btn-file">
														<span class="fileinput-new">Selecccionar Documento</span>
														<span class="fileinput-exists">Cambiar</span>
														<input type="file" accept=".pdf" name="ineback">
													</span>
													<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Quitar</a>
												</div>
											</div>
										</div>
										<div class="col-sm-1" v-if="customer.ineback_documents_id">
											<a :href=" customer.inebackDoc" target="_blank"><span class="btn btn-info" ><i class="fas fa-cloud-download-alt"></i></span></a>
															
											<label>DESCARGAR</label>
										</div>
									</div>

									<input-form name="clave_ine" text="Clave de lector del INE" :data.sync="customer.clave_ine"></input-form>


									<div class="form-group">
										<div class="col-sm-12">
											<button type="submit" class="btn btn-success pull-right"><i class="far fa-save"></i> Guardar</button> 		
										</div>
									</div>
								</form>	
							</div>
							<div :class=" 'tab-pane ' + active3" id="3">
								<form role="form" class="form-horizontal" @submit.prevent="newCustomer($event.target)">

									<div class="form-group">
										<div class="col-sm-3">
											<label class="col-sm-12 control-label">Foto</label>
										</div>
										<div class="col-sm-9">
											<div class="fileinput fileinput-new" data-provides="fileinput">
												<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
													<img :src="customer.imageUrl" alt="..." v-if="id!=''">
													<img src="//placehold.it/200x150?text=Imagen" alt="..." v-else>
												</div>
												<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 400px; max-height: 300px"></div>
												<div>
													<span class="btn btn-white btn-file">
														<span class="fileinput-new">Select image</span>
														<span class="fileinput-exists">Change</span>
														<input type="file" accept="image/*" name="image">
													</span>
													<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
												</div>
											</div>

										</div>
									</div>
									
									<div class="form-group">
										<div class="col-sm-12">
											<button type="submit" class="btn btn-success pull-right"><i class="far fa-save"></i> Guardar</button> 		
										</div>
									</div>
								</form>
							</div>
							<div :class=" 'tab-pane ' + active4" id="4">
								<form role="form" class="form-horizontal" @submit.prevent="newCustomer($event.target)">

									<div class="form-group">
										<div class="col-sm-3">
											<label class="col-sm-12 control-label">Firma</label>
										</div>
										<div class="col-sm-9">
											<div class="fileinput fileinput-new" data-provides="fileinput">
												<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
													<img :src="customer.signatureUrl" alt="..." v-if="id!=''">
													<img src="//placehold.it/200x150?text=Imagen" alt="..." v-else>
												</div>
												<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 400px; max-height: 300px"></div>
												<!-- <div>
													<span class="btn btn-white btn-file">
														<span class="fileinput-new">Select image</span>
														<span class="fileinput-exists">Change</span>
														<input type="file" accept="image/*" name="image">
													</span>
													<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
												</div> -->
											</div>

										</div>
									</div>

									<!-- <a @click="openSignatureModal" class="btn btn-info"><i class="far fa-plus"></i> Agregar firma</a> -->

									
									
									<div class="form-group">
										<div class="col-sm-12">
											<button type="submit" class="btn btn-success pull-right"><i class="far fa-save"></i> Guardar</button> 		
										</div>
									</div>
								</form>
							</div>
							<div :class=" 'tab-pane ' + active5" id="5">
								 <table id="contracts"></table>
							</div>
							<div :class=" 'tab-pane ' + active6" id="6">

								<button class="btn btn-info btn-sm" @click="addCredit">
									<i class="fa fa-plus"></i> Agregar credito
								</button>

								 <table id="credits"></table>
							</div>



						</div>
					</div>
									
				</div>			
			</div>		
		</div>
		<sweet-modal ref="modalfrm">
			<div class="box-signature" v-if="showSignaturePad">
		        <VueSignaturePad class="signature-pad" ref="signaturePad" />

		        <div class="box-buttons">
		          <button class="btn btn-s1 bg-black" @click="saveSignature">Guardar</button>
		          <button class="btn btn-s1 bg-black" @click="resetSignature">Limpiar</button>
		        </div>
		      </div>
		</sweet-modal>

		<sweet-modal ref="addCreditModal">
			<form role="form" class="form-horizontal" @submit.prevent="saveCredit($event.target)">
				<div class="form-group">
					<label class="col-sm-3 control-label">Paquete a agregar:</label>
					<div class="col-sm-7">
						<v-select v-model="formcredit.package_id" :options="packages" label="name" index="id" />
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">
						<button type="submit" class="btn btn-success pull-right"><i class="far fa-save"></i> Guardar</button> 		
					</div>
				</div>
			</form>
		</sweet-modal>

	</div>
</template>
<script type="text/javascript">
	export default {
		data(){
			return {
				customer:{
					name:'',
					lastname: '',
					email: '',
					password: '',
					phone: '',

					street: '',
					num_ext: '',
					num_int: '',
					neighborhood: '',
					state_id: null,
					town_id: null,
					zipcode: '',

					rfc_documents_id:null,
					curp_documents_id:null,
					inefront_documents_id:null,
					ineback_documents_id:null,
					clave_ine:null,
					imageUrl:null,
				},

				estados: [],
				ciudades: [],
				id: null,
				active:1,

				imageSignature:null,
				showSignaturePad:false,
				formcredit:{
					user_id:null,
					package_id:null
				},
				packages:[],
				disabledinput:true,
			}
		},
		computed:{
			rule_password:function(){
				if(this.customer.password==undefined || this.customer.password.length==0){
					return '';
				}
				else{
					return 'min:6|required';
				}
			},
			active1: function()
			{
				return (this.active == 1) ? 'active' : '';
			},

			active2: function()
			{
				return (this.active == 2) ? 'active' : '';
			},
			active3: function()
			{
				return (this.active == 3) ? 'active' : '';
			},
			active4: function()
			{
				return (this.active == 4) ? 'active' : '';
			},
			active5: function()
			{
				return (this.active == 5) ? 'active' : '';
			},
			active6: function()
			{
				return (this.active == 6) ? 'active' : '';
			},
		},

		methods:{

			getCustomer(){
				axios.get(tools.url("/api/admin/customers/"+this.id)).then((response)=>{
			    	this.customer = response.data;
					jQuery('#contracts').bootstrapTable('removeAll');
			    	jQuery('#contracts').bootstrapTable('append',this.customer.contracts);

					jQuery('#credits').bootstrapTable('removeAll');
			    	jQuery('#credits').bootstrapTable('append',this.customer.credits);
			    }).catch((error)=>{
			    	this.$parent.handleErrors(error);
			    });
			},

			newCustomer(form){
				this.$parent.validateAll(()=>{

					var data = tools.params(form, this.customer);
					if(this.$route.params.id){
						axios.post(tools.url("/api/admin/customers/"+this.id),data).then((response)=>{
					    	this.getCustomer();
					    	this.$parent.showMessage("Cliente "+this.customer.name+" modificado correctamente!","success");
					    }).catch((error)=>{
					    	this.$parent.handleErrors(error);
					    });
					}
					else{
						axios.post(tools.url("/api/admin/customers"),data)
						.then((response)=>{

							var customer = response.data;
							this.id = customer.id;
					    	this.$parent.showMessage("Cliente "+customer.name+" agregado correctamente!","success");
					    	this.$router.push('/customers/edit/'+customer.id);
					    }).catch((error)=>{
					    	this.$parent.handleErrors(error);
					    });
					}
				},(e)=>{
					console.log(e);
				});				
			},

			deleteRow:function(){
				alertify.confirm("Alerta!","¿Seguro que deseas borrar este cliente?",()=>{
					axios.delete(tools.url("/api/admin/customers/"+this.id))
					.then((response)=>{
						this.$parent.showMessage(response.data.msg,"success");
						this.$router.push("/customers/");
					})
					.catch((error)=>{
						this.$parent.handleErrors(error);
					});
				},
				()=>{
					
				});
			},

			getEstados(){
				axios.get(tools.url('/api/admin/states')).then((response)=>{
					this.estados = response.data;
				}).catch((error)=>{
					this.$parent.handleErrors(error);
				});
			},

			getTowns(state_id){
				axios.get(tools.url('/api/admin/towns/' + state_id)).then((response)=>{
					this.ciudades = response.data;
				}).catch((error)=>{
					this.$parent.handleErrors(error);
				});
			},

			 resetSignature() {
		      this.$refs.signaturePad.clearSignature();
		    },
		    saveSignature() {
		    	 this.$refs.modalfrm.close();
		      const { isEmpty, data } = this.$refs.signaturePad.saveSignature();
		      // console.log(isEmpty);
		      // console.log(data);
		      this.imageSignature = data;
		      
		    },
		    openSignatureModal(){
		      this.$refs.modalfrm.open();

		      setTimeout(()=>{ this.showSignaturePad = true; }, 1000);
		    },

		    mounthTableContracts(){
				jQuery('#contracts').bootstrapTable({
					columns: [
						{
							field:"check",
							checkbox:true,
							align: 'center',
						},
				
						
						{
					        field: 'contrac_name',
					        title: 'Contrato',
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
					        field: 'btnpdf',
					        title: 'Documento',
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
				    // data: this.users,
					//Boton de refrescar
					showRefresh:true,
		            //Define si tienen detalles cada fila       
		            // detailView:true,
		            // detailFormatter:"detailFormatter",
            		
				});

				jQuery('#contracts').on('refresh.bs.table',()=>{
					this.getCustomer();
				});

				jQuery('#contracts').on('click-row.bs.table',(row,data)=>{
					
					//this.$router.push('/customers/edit/'+data.id);
					// console.log(data);
				});

			

			},
			mounthTableCredits(){
				jQuery('#credits').bootstrapTable({
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
				    // data: this.users,
					//Boton de refrescar
					showRefresh:true,
		            //Define si tienen detalles cada fila       
		            // detailView:true,
		            // detailFormatter:"detailFormatter",
            		
				});

				jQuery('#credits').on('refresh.bs.table',()=>{
					this.getCustomer();
				});

				jQuery('#credits').on('click-row.bs.table',(row,data)=>{
					
					//this.$router.push('/customers/edit/'+data.id);
					// console.log(data);
				});

			

			},
			addCredit(){
				
				this.formcredit.user_id = this.id;
				this.formcredit.package_id = null;
				

				this.$refs.addCreditModal.open();

			},
			saveCredit(){
				this.$parent.validateAll(()=>{
				this.formcredit.user_id = this.id;
				
					axios.post(tools.url("/api/admin/customerscredits"),this.formcredit).then((response)=>{
						this.getCustomer();
						this.$parent.showMessage("Creditos agregados correctamente!","success");
						this.$refs.addCreditModal.close();
					}).catch((error)=>{
						this.$parent.handleErrors(error);
					});
				
				
				},(e)=>{
				console.log(e);
				});				
			},
			getPackages(){
				axios.get(tools.url("/api/admin/packages")).then((response)=>{
					this.packages = response.data;
				}).catch((error)=>{
					
				});
			},

		},
		
		mounted(){
			var self = this;
			setTimeout(function(){
				self.disabledinput = false;
			}, 700)
			this.mounthTableContracts();
			this.mounthTableCredits();
			this.getEstados();
			this.getPackages();

			if(this.$route.params.id){
				this.id = this.$route.params.id;
				this.getCustomer();
			}

			if(this.customer.state_id){
				this.getTowns(this.customer.state_id);
			}


			
		}
	}
</script>
