<template>
	<div class="row">
		<div class="col-md-offset-1 col-md-10">

			<div class="panel panel-primary" data-collapsed="0">

				<div class="panel-heading">
					<div class="panel-title">
						<i class="fas fa-bars"></i> Contratos <span v-if="id">- {{row.title}}</span>
					</div>
					<div class="panel-options">
						<a @click="$router.push('/contracts/')"><i class="fas fa-times"></i></a>
					</div>
				</div>

				<div class="panel-body">
					<div id="tabs">
						<ul class="nav nav-tabs">
							<li :class="activeInfo"><a @click="active = 1" href="#1" data-toggle="tab">Información basica</a></li>
							<li :class="activeClause" v-if="id"><a @click="active = 2" href="#2" data-toggle="tab">Catalogo de parametros</a></li>
							<li :class="activeFormat" v-if="id"><a @click="active = 3" href="#3" data-toggle="tab">Formato</a></li>
							<!-- <li :class="activeImages" v-if="id"><a @click="active = 4" href="#4" data-toggle="tab">Imagenes de referencia</a></li> -->
						</ul>

						<div class="tab-content">

							<div :class=" 'tab-pane ' + activeInfo" id="1">
								<form role="form" class="form-horizontal" @submit.prevent="newRow($event.target)">

									<input-form type="number" name="position" text="Posicion de aparicion" :data.sync="row.position"></input-form>

									<div class="form-group">
										<label class="col-sm-3 control-label">Categoria:</label>
										<div class="col-sm-7">
											<v-select v-model="row.categories_id" :options="categories" label="name" index="id" />
										</div>
									</div>

									<input-form name="title" text="Titulo" :data.sync="row.title"></input-form>

									<div class="form-group">
										<label for="editor2" class="col-sm-3 control-label">Descripcion</label>
										<div class="col-sm-7">
											<vue-editor id="editor2"  v-model="row.description" :editorToolbar="customToolbar"></vue-editor>
										</div>
									</div>

									
									<input-form name="keywords" text="Palabras clave" :data.sync="row.keywords"></input-form>
									<input-form name="price" text="Precio en creditos" :data.sync="row.price"></input-form>

									<div class="form-group">
										<div class="col-sm-3">
											<label class="col-sm-12 control-label">Imagen</label>
										</div>
										<div class="col-sm-9">
											<div class="fileinput fileinput-new" data-provides="fileinput">
												<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
													<img :src="row.imageUrl" alt="..." v-if="id!=''">
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
										<checkbox-form name="active" text="Puede realizarse sin necesidad de contraparte" :data.sync="row.plain_receipt"></checkbox-form>
									</div>

									<div class="form-group">
										<div class="col-sm-12">
											<button type="button" class="btn btn-danger" @click="deleteRow" v-show="$route.params.id"><i class="fa fa-trash"></i> Borrar</button>
											<button type="submit" class="btn btn-success pull-right"><i class="far fa-save"></i> Guardar</button>
											<button type="button" class="btn btn-default pull-right" @click="$router.push('/contracts/')">Cancelar</button>
										</div>
									</div>

								</form>
							</div>
							<div :class=" 'tab-pane ' + activeClause" id="2">

								
						       	<button class="btn btn-success btn-sm" @click="addClause">
							            <i class="fa fa-plus"></i> Agregar nuevo parametro
							    </button>
						       
						        <button class="btn btn-danger btn-sm" @click="deleteRowsClauses()">
						            <i class="fa fa-trash"></i> Borrar
						        </button>

								<table id="clauses"></table>
							</div>
							<div :class=" 'tab-pane ' + activeFormat" id="3">
								
								
								<div class="col-sm-11">
									<table id="tableproducts">
										<tr>
											<th><b>Etiqueta</b></th>
											<td><b>Valor</b></td>
											<th><b>Etiqueta</b></th>
											<td><b>Valor</b></td>
											
										</tr>
										<tr v-if="showtable == true">
											<th>Nombre parte 1</th>
											<td>[parte1_nombre]</td>
											<th>Nombre Contraparte</th>
											<td>[contraparte_nombre]</td>
											
										</tr>
										<tr v-if="showtable == true">
											<th>Email parte 1</th>
											<td>[parte1_email]</td>

											<th>Email Contraparte</th>
											<td>[contraparte_email]</td>
										</tr>
										<tr v-if="showtable == true">
											<th>Telefono parte 1</th>
											<td>[parte1_telefono]</td>

											<th>Telefono Contraparte</th>
											<td>[contraparte_telefono]</td>
										</tr>
										<tr v-if="showtable == true">
											<th>RFC parte 1</th>
											<td>[parte1_rfc]</td>
											<th>RFC Contraparte</th>
											<td>[contraparte_rfc]</td>
										</tr>
										<tr v-if="showtable == true">
											<th>CURP parte 1</th>
											<td>[parte1_curp]</td>

											<th>CURP Contraparte</th>
											<td>[contraparte_curp]</td>
										</tr>
										<tr v-if="showtable == true">
											<th>Calle parte 1</th>
											<td>[parte1_calle]</td>
											<th>Calle Contraparte</th>
											<td>[contraparte_calle]</td>
										</tr>
										<tr v-if="showtable == true">
											<th>Numero exterior parte 1</th>
											<td>[parte1_numext]</td>
											<th>Numero exterior Contraparte</th>
											<td>[contraparte_numext]</td>
										</tr>
										<tr v-if="showtable == true">
											<th>Numero interior parte 1</th>
											<td>[parte1_numint]</td>
											<th>Numero interior Contraparte</th>
											<td>[contraparte_numint]</td>
										</tr>
										<tr v-if="showtable == true">
											<th>Colonia parte 1</th>
											<td>[parte1_colonia]</td>
											<th>Colonia Contraparte</th>
											<td>[contraparte_colonia]</td>
										</tr>
										<tr v-if="showtable == true">
											<th>Codigo postal parte 1</th>
											<td>[parte1_cp]</td>
											<th>Codigo postal Contraparte</th>
											<td>[contraparte_cp]</td>
										</tr>
										<tr v-if="showtable == true">
											<th>Ciudad parte 1</th>
											<td>[parte1_ciudad]</td>
											<th>Ciudad Contraparte</th>
											<td>[contraparte_ciudad]</td>
										</tr>
										<tr v-if="showtable == true">
											<th>Estado parte 1</th>
											<td>[parte1_estado]</td>
											<th>Estado Contraparte</th>
											<td>[contraparte_estado]</td>
										</tr>

										<tr v-if="showtable == true">
											<th>Docmiclio completo parte 1(Calle ,Numero exterior,Colonia...)</th>
											<td>[parte1_domicilio]</td>
											<th>Docmiclio completo Contraparte(Calle ,Numero exterior,Colonia...) </th>
											<td>[contraparte_domicilio]</td>
										</tr>

										<tr v-for="(page,indx) in row.clausespaginate" :key="indx" v-if="showtable == true">
											<th>{{ row.clausespaginate[indx][0]['title'] }}</th>
											<td>{{ row.clausespaginate[indx][0]['description'] }}</td>

											<th v-if="row.clausespaginate[indx][1]">{{ row.clausespaginate[indx][1]['title'] }}</th>
											<td v-if="row.clausespaginate[indx][1]">{{ row.clausespaginate[indx][1]['description'] }}</td>
											
										</tr>
									</table>
								</div>
								<div class="col-sm-1">
									<p style="text-align: right;font-size: 40px;cursor: pointer;"> 
										<span @click="showtable = true" v-if="showtable == false"><i class="fas fa-arrow-circle-down"></i></span>
										<span @click="showtable = false" v-if="showtable == true"><i class="fas fa-arrow-circle-up"></i></span>
									</p>
								</div>
								
								<form role="form" class="form-horizontal" @submit.prevent="newRow($event.target)">

									<div class="form-group">
										<label for="editor2" class="col-sm-3 control-label">Encabezado</label>
										<div class="col-sm-7">
											<vue-editor id="editor2"  v-model="row.header_format" :editorToolbar="customToolbar"></vue-editor>
										</div>
									</div>

									<div class="form-group">
										<label for="editor2" class="col-sm-3 control-label">Medio</label>
										<div class="col-sm-7">
											<vue-editor id="editor2"  v-model="row.medio_format" :editorToolbar="customToolbar"></vue-editor>
										</div>
									</div>

									<!-- <div class="form-group">
										<label for="editor2" class="col-sm-3 control-label">Inferior</label>
										<div class="col-sm-7">
											<vue-editor id="editor2"  v-model="row.inferior_format" :editorToolbar="customToolbar"></vue-editor>
										</div>
									</div> -->

									<div class="form-group">
										<label for="editor2" class="col-sm-3 control-label">Cuerpo</label>
										<div class="col-sm-7">
											<vue-editor id="editor2"  v-model="row.body_format" :editorToolbar="customToolbar"></vue-editor>
										</div>
									</div>

									<div class="form-group">
											<div class="col-sm-12">
												<button type="button" class="btn btn-danger" @click="deleteRow" v-show="$route.params.id"><i class="fa fa-trash"></i> Borrar</button>
												<button type="submit" class="btn btn-success pull-right"><i class="far fa-save"></i> Guardar</button>
												<button type="button" class="btn btn-default pull-right" @click="$router.push('/contracts/')">Cancelar</button>
											</div>
									</div>
								</form>

							</div>
							<div :class=" 'tab-pane ' + activeImages" id="4">
								<button class="btn btn-success btn-sm" @click="addImage">
							            <i class="fa fa-plus"></i> Agregar nueva imagen
							    </button>
						       
						        <button class="btn btn-danger btn-sm" @click="deleteRowsImages()">
						            <i class="fa fa-trash"></i> Borrar
						        </button>

								<table id="images"></table>
							</div>

						</div>

					</div>
			
				</div>
			</div>
		</div>
		<sweet-modal ref="modalClause" width="80%">
			<form role="form" class="form-horizontal" @submit.prevent="newRowclause($event.target)">

				<div class="form-group" >
					<label class="col-sm-3 control-label"></label>
					<div class="col-sm-3" >
						<input type="radio" name="campo" value="campo" v-model="rowclause.type" id="producto">
						<label for="campo">Campo </label>
					</div>
					<div class="col-sm-2">
						<input type="radio" name="text" value="text" v-model="rowclause.type" id="servicio">
						<label for="text">Text</label>
					</div>
										
				</div>

				<input-form name="title" text="Titulo" :data.sync="rowclause.title"></input-form>

				<p>Nota: para hacer una frase dinamica, escribe el nombre del campo entre <b>[ ]</b><br>Ejemplo:<br>El prestador pagara la cantidad de [Cantidad] por el valor total de los servicios contratados </p>
				
				<input-form name="description" text="Descripcion" :data.sync="rowclause.description"></input-form>

				<!-- <div class="form-group">
					<label for="editor2" class="col-sm-3 control-label">Descripcion</label>
					<div class="col-sm-7">
						<vue-editor id="editor2"  v-model="rowclause.description" :editorToolbar="customToolbar"></vue-editor>
					</div>
				</div> -->
				<div class="form-group">
					<div class="col-sm-12">
		
						<button type="submit" class="btn btn-success pull-right"><i class="far fa-save"></i> Guardar</button>
						<button type="button" class="btn btn-default pull-right" @click="$refs.modalClause.close()">Cancelar</button>
					</div>
				</div>
			</form>
		</sweet-modal>

		<sweet-modal ref="modalImage" >
			<form role="form" class="form-horizontal" @submit.prevent="newRowimage($event.target)">

				<div class="form-group">
					<div class="col-sm-3">
								<label class="col-sm-12 control-label">Imagen</label>
					</div>
					<div class="col-sm-9">
								
								<div class="fileinput fileinput-new" data-provides="fileinput">
									<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
										<img :src="rowimage.imageUrl" alt="..." v-if="id!=''">
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
				<text-form name="description" text="Descripcion" :data.sync="rowimage.description"></text-form>
				
				<div class="form-group">
					<div class="col-sm-12">
		
						<button type="submit" class="btn btn-success pull-right"><i class="far fa-save"></i> Guardar</button>
						<button type="button" class="btn btn-default pull-right" @click="$refs.modalImage.close()">Cancelar</button>
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
				row:{
					name: '',
					clauses:[],
					categories_id:null
				},
				
				id: '',
				check:false,
				subcontracts: [],
				active:1,

				customToolbar: [
					[{ 'font': [] }],
					[{ 'header': [false, 1, 2, 3, 4, 5, 6, ] }],
					['bold', 'italic', 'underline'],
					[{'align': ''}, {'align': 'center'}, {'align': 'right'}, {'align': 'justify'}],
					[{ 'list': 'ordered'}, { 'list': 'bullet' }]//,
					//['image', 'code-block']
				],
				rowclause:{
					title:null,
					description:null,
					contracts_id:null,
					new:1,
					type:null
				},
				rowimage:{
					description:null,
					contracts_id:null,
					new:1
				},
				categories:[],
				showtable:true

			}
		},
		computed:{
			activeInfo: function()
			{
				return (this.active == 1) ? 'active' : '';
			},

			activeClause: function()
			{
				return (this.active == 2) ? 'active' : '';
			},
			activeFormat: function()
			{
				return (this.active == 3) ? 'active' : '';
			},
			activeImages: function()
			{
				return (this.active == 4) ? 'active' : '';
			},
		},

		methods:{

			getRow(){
				this.$parent.inPetition=true;
				axios.get(tools.url("/api/admin/contracts/"+this.id)).then((response)=>{

			    	this.row = response.data;
			    	jQuery('#clauses').bootstrapTable('removeAll');
          			jQuery('#clauses').bootstrapTable('append',this.row.clauses);

					  jQuery('#images').bootstrapTable('removeAll');
          			jQuery('#images').bootstrapTable('append',this.row.images);

					this.row.plain_receipt = (this.row.plain_receipt)?(true):(false);
					this.$parent.inPetition=false;
					
			    }).catch((error)=>{
			    	this.$parent.handleErrors(error);
			       this.$parent.inPetition=false;
			    });
			},

			newRow(form){
				this.$parent.inPetition=true;
				this.$parent.validateAll(()=>{
					var data=tools.params(form, this.row);
					if(this.$route.params.id){
						axios.post(tools.url("/api/admin/contracts/"+this.id),data)
						.then((response)=>{
					    	this.getRow();
					    	this.$parent.showMessage("Registro modificado correctamente!","success");
					    	this.$parent.inPetition=false;
					    }).catch((error)=>{
					    	this.$parent.handleErrors(error);
					        this.$parent.inPetition=false;
					    });
					}
					else{
						axios.post(tools.url("/api/admin/contracts"),data).then((response)=>{

							var temp = response.data;
							this.id = temp.id;
					    	this.$parent.showMessage("Registro agregado correctamente!","success");
					    	this.$router.push('/contracts/edit/'+temp.id);
					    	this.$parent.inPetition=false;
					    }).catch((error)=>{
					    	this.$parent.handleErrors(error);
					        this.$parent.inPetition=false;
					    });
					}
				},(e)=>{
					console.log(e);
					this.$parent.inPetition=false;
				});
			},

			deleteRow:function(){
				alertify.confirm("Alerta!","¿Seguro que deseas borrar?",()=>{
					this.$parent.inPetition=true;
					axios.delete(tools.url("/api/admin/contracts/"+this.id))
					.then((response)=>{
						this.$parent.showMessage(response.data.msg,"success");
						this.$router.push("/contracts/");
						this.$parent.inPetition=false;
					})
					.catch((error)=>{
						this.$parent.handleErrors(error);
				        this.$parent.inPetition=false;
					});
				},
				()=>{
				});
			},
			mounthTable(){
		      jQuery('#clauses').bootstrapTable({
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
		            field: 'title',
		            title: 'Titulo',
		            sortable:true,
		            switchable:true,
		          },
		          /*{
		            field: 'created',
		            title: 'Fecha de creación',
		            sortable:true,
		            switchable:true,
		          },
		          {
		            field: 'updated',
		            title: 'Fecha de <br>ultima modificacion',
		            sortable:true,
		            switchable:true,
		          },*/
		          {
		            field: 'fields',
		            title: 'Parametro',
		            sortable:true,
		            switchable:true,
		          },
		          {
		            field: 'detailbtn',
		            title: 'Ver detalles',
		            sortable:true,
		            switchable:true,
		          },
		        ],
		        showRefresh:true,
		      });

		      jQuery('#clauses').on('refresh.bs.table',()=>{
		        this.getRow();
		      });

		      jQuery('#clauses').on('click-row.bs.table',(row,data)=>{
		        	this.rowclause = data;
		        	this.rowclause.new = 0;
		        	this.$refs.modalClause.open();
		      });

		      

		    },
		    newRowclause(form){
		    	this.$parent.inPetition=true;
				this.$parent.validateAll(()=>{
					var data=tools.params(form, this.rowclause);
					
					axios.post(tools.url("/api/admin/contractsclause/"+this.id),data)
						.then((response)=>{
							this.$refs.modalClause.close();
					    	this.getRow();
					    	this.$parent.showMessage("Registro modificado correctamente!","success");
					    	this.$parent.inPetition=false;
					}).catch((error)=>{
					    	this.$parent.handleErrors(error);
					        this.$parent.inPetition=false;
					});
					
					
				},(e)=>{
					console.log(e);
					this.$parent.inPetition=false;
				});
		    },
		    addClause(){
		    	this.rowclause.id = null;
		    	this.rowclause.title = null;
		    	this.rowclause.description = null;
		    	this.rowclause.contracts_id = null;
				this.rowclause.new = 1;

				this.$refs.modalClause.open();
		    },
		    deleteRowsClauses:function(){
		      var rows=jQuery('#clauses').bootstrapTable('getSelections');
		      if(rows.length==0){
		        return false;
		      }
		      alertify.confirm("Alerta!","¿Seguro que deseas borrar "+rows.length+" registros?",()=>{
		        this.$parent.inPetition=true;
		        var params={};
		        params.ids=jQuery.map(rows,(row)=>{
		          return row.id;
		        });

		        axios.delete(tools.url('/api/admin/contractsclause'),{data:params})
		        .then((response)=>{
		          this.$parent.showMessage(response.data.msg,"success");
		          this.getRow();
		          this.$parent.inPetition=false;
		        })
		        .catch((error)=>{
		          this.$parent.handleErrors(error);
		              this.$parent.inPetition=false;
		        });
		      },
		      ()=>{
		      });
		    },
		     getCategories(){
		      axios.get(tools.url("/api/admin/categories")).then((response)=>{
		          this.categories = response.data;
		          
		        }).catch((error)=>{
		            this.$parent.handleErrors(error);
		        });
		    },


			//imagenes
			addImage(){
				this.rowimage.id = null;
		    	this.rowimage.description = null;
		    	this.rowimage.contracts_id = null;
				this.rowimage.new = 1;

				this.$refs.modalImage.open();
			},
			deleteRowsImages:function(){
		      var rows=jQuery('#images').bootstrapTable('getSelections');
		      if(rows.length==0){
		        return false;
		      }
		      alertify.confirm("Alerta!","¿Seguro que deseas borrar "+rows.length+" registros?",()=>{
		        this.$parent.inPetition=true;
		        var params={};
		        params.ids=jQuery.map(rows,(row)=>{
		          return row.id;
		        });

		        axios.delete(tools.url('/api/admin/contractsimage'),{data:params})
		        .then((response)=>{
		          this.$parent.showMessage(response.data.msg,"success");
		          this.getRow();
		          this.$parent.inPetition=false;
		        })
		        .catch((error)=>{
		          this.$parent.handleErrors(error);
		              this.$parent.inPetition=false;
		        });
		      },
		      ()=>{
		      });
		    },
			mounthTableImages(){
		      jQuery('#images').bootstrapTable({
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
					field: 'imagen',
					title: ' ',
					sortable:false,
					width:"52px",
				},
		          {
		            field: 'description',
		            title: 'Descripcion',
		            sortable:true,
		            switchable:true,
		          },
		          {
		            field: 'created',
		            title: 'Fecha de creación',
		            sortable:true,
		            switchable:true,
		          },
		          
		        ],
		        showRefresh:true,
		      });

		      jQuery('#images').on('refresh.bs.table',()=>{
		        this.getRow();
		      });

		      jQuery('#images').on('click-row.bs.table',(row,data)=>{
		        	this.rowimage = data;
		        	this.rowimage.new = 0;
		        	this.$refs.modalImage.open();
		      });

		      

		    },
		    newRowimage(form){
		    	this.$parent.inPetition=true;
				this.$parent.validateAll(()=>{
					var data=tools.params(form, this.rowimage);
					
					axios.post(tools.url("/api/admin/contractsimage/"+this.id),data)
						.then((response)=>{
							this.$refs.modalImage.close();
					    	this.getRow();
					    	this.$parent.showMessage("Registro modificado correctamente!","success");
					    	this.$parent.inPetition=false;
					}).catch((error)=>{
					    	this.$parent.handleErrors(error);
					        this.$parent.inPetition=false;
					});
					
					
				},(e)=>{
					console.log(e);
					this.$parent.inPetition=false;
				});
		    },

		    
		},
		mounted(){
			this.getCategories();
			if(this.$route.params.id){
				this.id=this.$route.params.id;
				this.getRow();
			}
			this.mounthTable();
			this.mounthTableImages();
		}
	}
</script>
<style>
table, #tableproducts {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

table, #tableproducts td, th {
  border: 1px solid #9b9b9b;
  text-align: left;
  padding: 3px;
}
table #tableproducts, th {
  background-color: #dddddd;
}
</style>