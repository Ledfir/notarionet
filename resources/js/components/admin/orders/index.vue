<template>
    <div>
        <ol class="breadcrumb 2">
            <li><router-link to="/home"><i class="entypo-home"></i>Home</router-link></li>
            <li class="active"><strong>Pedidos</strong></li>
        </ol>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="tile-stats tile-white tile-white-primary">
                            <div class="icon"><i class="entypo-basket"></i></div>
                            <div class="num">{{ totals.ordersToDay  }}</div>
                            <h3>Pedidos de hoy</h3>
                            <p>La suma del número de pedidos recibos Hoy.</p>
                        </div>   
                    </div>
                    <div class="col-sm-3">
                        <div class="tile-stats tile-white tile-white-primary">
                            <div class="icon"><i class="entypo-chart-area"></i></div>
                            <div class="num">$ {{ Intl.NumberFormat("en-US", { minimumFractionDigits: 2 }).format(totals.totalToDay) }}</div>
                            <h3>Total de hoy</h3>
                            <p>La suma del total de pedidos recibos Hoy.</p>
                        </div>   
                    </div>
                    <div class="col-sm-3">
                        <div class="tile-stats tile-white tile-white-primary">
                            <div class="icon"><i class="entypo-basket"></i></div>
                            <div class="num">{{ totals.ordersToMonth  }}</div>
                            <h3>Pedidos del mes</h3>
                            <p>La suma del número de pedidos recibos en el mes.</p>
                        </div>   
                    </div>
                    <div class="col-sm-3">
                        <div class="tile-stats tile-white tile-white-primary">
                            <div class="icon"><i class="entypo-chart-area"></i></div>
                            <div class="num">$ {{ Intl.NumberFormat("en-US", { minimumFractionDigits: 2 }).format(totals.totalToMonth) }}</div>
                            <h3>Total del mes</h3>
                            <p>La suma del total de pedidos recibos en el mes.</p>
                        </div>   
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div id="toolbar">
                    
                    <!-- <button class="btn btn-danger btn-sm" @click="cancelarPedidos()">
                        <i class="fa fa-trash"></i> Cancelar pedidos
                    </button>

                    <button class="btn btn-success btn-sm" @click="pagarPedidos()">
                        <i class="fa fa-check"></i> Pagar pedidos
                    </button> -->
                </div>
                <table id="pedidos"></table>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    export default {
        data(){
            return {
                orders:[],

                totals:{
                    totalToDay: 0,
                    totalToMonth: 0,
                    ordersToDay: 0,
                    ordersToMonth: 0
                }
            }
        },
        methods:{
            mounthTable(){
                jQuery('#pedidos').bootstrapTable({
                    columns: [
                        {
                            field:"check",
                            checkbox:true,
                            align: 'center'
                        },
                        {
                            field: 'id',
                            title: '#',
                            sortable:true,
                            switchable:true,
                            align: 'center'
                        },
                        {
                            field: 'created',
                            title: 'Fecha',
                            sortable:true,
                            switchable:true,
                            align: 'center'
                        },                    
                        {
                            field: 'status',
                            title: 'Estatus',
                            sortable:true,
                            switchable:true,
                            align: 'center'
                        },
                        {
                            field: 'user.name',
                            title: 'Cliente',
                            sortable:true,
                            align: 'center'
                        },

                        {
                            field: 'user.email',
                            title: 'Email del cliente',
                            sortable:true,
                            align: 'center'
                        },

                        {
                            field: 'subtotal',
                            title: 'Subtotal',
                            sortable:true,
                            switchable:true,
                            align: 'center'
                        },
                        {
                            field: 'total',
                            title: 'Total',
                            sortable:true,
                            switchable:true,
                            align: 'center'
                        },
                        
                    ],
                    //Boton de refrescar
                    showRefresh:true,

                });

                jQuery('#pedidos').on('refresh.bs.table',()=>{
                    this.getContent();
                });

                jQuery('#pedidos').on('click-row.bs.table',(row,data)=>{
                    this.$router.push('/orders/detail/'+data.id);
                });

                this.getContent();

            },

            getContent(){
                axios.get(tools.url("/api/admin/orders")).then((response)=>{
                    this.totals = response.data.totals;
                    this.orders = response.data.orders;
                    jQuery('#pedidos').bootstrapTable('removeAll');
                    jQuery('#pedidos').bootstrapTable('append',response.data.orders);
                }).catch((error)=>{
                    this.$parent.handleErrors(error);
                });
            },

        },
        mounted() {
            this.mounthTable();
        }
    }
</script>
