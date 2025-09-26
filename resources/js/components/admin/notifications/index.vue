<template>
	<div class="row">
		<div class="col-md-offset-1 col-md-10">

			<div class="panel panel-primary" data-collapsed="0">

				<div class="panel-heading">
					<div class="panel-title">
						<i class="fa fa-bell"></i> Notificaciones
					</div>
                
				</div>

				<div class="panel-body">
					<form role="form" class="form-horizontal" @submit.prevent="newRow($event.target)">

					

						<input-form name="title" text="Titulo" :data.sync="row.title"></input-form>

                        <text-form name="body" text="Cuerpo" :data.sync="row.body"></text-form>

                        <table id="table"></table>

                   
						<div class="form-group">
							<div class="col-sm-12">
							
								<button type="submit" class="btn btn-success pull-right"><i class="far fa-save"></i> Enviar</button>
							
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
				row:{
					title: '',
					body:'',
					ids:[]
				},
				
				id: '',
				check:false,
                players:[]
			}
		},
		methods:{



			newRow(form){
				var rows=jQuery('#table').bootstrapTable('getSelections');
				if(rows.length==0){
				return false;
				}
				this.$parent.inPetition=true;
				this.$parent.validateAll(()=>{
					var params={};
					params.ids=jQuery.map(rows,(row)=>{
						return row.id;
					});
					this.row.ids  = params.ids;
					var data=tools.params(form, this.row);

						axios.post(tools.url("/api/admin/notifications"),data).then((response)=>{
							var temp = response.data;
					    	this.$parent.showMessage("Notificacion enviada correctamente!","success");
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
							field: 'nombre',
							title: 'Nombre',
							sortable:true,
							switchable:true,
						},
						{
							field: 'email',
							title: 'Email',
							sortable:true,
							switchable:true,
						},
                ],
                showRefresh:true,
                });
        
                jQuery('#table').on('refresh.bs.table',()=>{
                    this.getContent();
                });
        
                jQuery('#table').on('click-row.bs.table',(e,row,data,$element)=>{
      
                
                });
        
                //this.getContent();
        
            },
            getContent(){
                this.$parent.inPetition=true;
                axios.get(tools.url("/api/admin/playersUser")).then((response)=>{
                    this.players = response.data;
                    jQuery('#table').bootstrapTable('removeAll');
                    jQuery('#table').bootstrapTable('append',this.players);

                    this.$parent.inPetition=false;
                }).catch((error)=>{
                    this.$parent.handleErrors(error);
                    this.$parent.inPetition=false;
                });
            },
		},
		mounted(){
            this.mounthTable();
			this.getContent();
		}
	}
</script>
