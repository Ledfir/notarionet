<template>
	<div class="row">
		<div class="col-md-offset-1 col-md-10">

			<div class="panel panel-primary" data-collapsed="0">

				<div class="panel-heading">
					<div class="panel-title">
						<i class="fas fa-box"></i> Productos
					</div>
					<div class="panel-options">
						<a @click="$router.push('/products/')"><i class="fas fa-times"></i></a>
					</div>
				</div>

				<div class="panel-body">
					

					<div id="tabs">
						<ul class="nav nav-tabs">
							<li :class="activeInfo"><a @click="active = 1" href="#1" data-toggle="tab">Información</a></li>
						</ul>


						<div class="tab-content">

							<div :class=" 'tab-pane ' + activeInfo" id="1">
								<form role="form" class="form-horizontal" @submit.prevent="newProducto($event.target)">

									<input-form name="sku" text="SKU" :data.sync="producto.sku" validate="required"></input-form>
									<input-form name="nombre" text="Nombre" :data.sync="producto.name" validate="required"></input-form>

									<div class="form-group">
										<label for="editor2" class="col-sm-3 control-label">Descripción</label>
										<div class="col-sm-7">
											<vue-editor id="editor2" v-model="producto.description"
												:editor-toolbar="[['bold', 'italic', 'underline'],[{ list: 'ordered' }, { list: 'bullet' }]]">
											</vue-editor>
										</div>
									</div>

									<input-form type="decimal" name="precio" text="Precio" :data.sync="producto.price"></input-form>
									<input-form type="number" name="stock" text="cantidad (stock)" :data.sync="producto.stock"></input-form>

									<div class="form-group">
										<label class="col-sm-3 control-label">Categoria:</label>
										<div class="col-sm-7">
											<v-select v-model="producto.category_id" :options="categories" label="name" index="id" @change="getSubcategories(producto.category_id);"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label">Subcategoria:</label>
										<div class="col-sm-7">
											<v-select v-model="producto.subcategory_id" :options="subcategories" label="name" index="id"/>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-12">
											<button type="submit" class="btn btn-success pull-right"><i class="far fa-save"></i>
												Guardar</button>
											<button type="button" class="btn btn-default pull-right"
												@click="$router.push('/products/')">Cancelar</button>
										</div>
									</div>

								</form>
							</div>

						</div>
					</div>	




				</div>
			</div>
		</div>

	</div>
</template>
<script type="text/javascript">
	export default {
		data(){
			return {
				producto:{
					name: '',
					sku: '',
					description: '',
					price: 0.00,
					stock: 0,
					category_id: null,
					subcategory_id: null,
				},

				categories: [],
				subcategories: [],
				
				id: '',
				check:false,
				active:1,
			}
		},

		computed:{
			activeInfo: function()
			{
				return (this.active == 1) ? 'active' : '';
			},

			activeVariantes: function()
			{
				return (this.active == 2) ? 'active' : '';
			}
		},

		methods:{

			getProducto(){
				axios.get(tools.url("/api/admin/products/"+this.id)).then((response)=>{
			    	this.producto = response.data;
			    }).catch((error)=>{
			    	this.$parent.handleErrors(error);
			    });
			},

			newProducto(form){
				this.$parent.validateAll(()=>{
					var data=tools.params(form, this.producto);
					if(this.$route.params.id){
						axios.post(tools.url("/api/admin/products/"+this.id),data).then((response)=>{
					    	this.getProducto();
					    	this.$parent.showMessage("Producto "+ this.producto.name +" modificado correctamente!","success");
					    }).catch((error)=>{
					    	this.$parent.handleErrors(error);
					    });
					}
					else{
						axios.post(tools.url("/api/admin/products"),data).then((response)=>{
							var temp = response.data;
					    	this.$parent.showMessage("Producto "+ temp.name +" agregado correctamente!","success");
					    	this.$router.push('/products/edit/'+temp.id);
					    }).catch((error)=>{
					    	this.$parent.handleErrors(error);
					    });
					}
				},(e)=>{
					console.log(e);
				});
			},

			getCategories(){
				axios.get(tools.url("/api/admin/categories")).then((response)=>{
			    	this.categories = response.data;
			    }).catch((error)=>{
			    	this.$parent.handleErrors(error);
			    });
			},

			getSubcategories(id){
				if(id){
					axios.get(tools.url("/api/admin/getSubcategories/"+id)).then((response)=>{
						this.subcategories = response.data.subcategories;
					}).catch((error)=>{
			    		this.$parent.handleErrors(error);
			    	});
				}
			},
		},

		mounted(){
			this.getCategories();
			if(this.$route.params.id){
				this.id = this.$route.params.id;
				this.getProducto();
				this.getSubcategories(this.producto.category_id);
			}
		}
	}
</script>
