<div class="container" id="products">     
    <div id="loading" v-show="loading">
        <img src="/images/loading.gif">
    </div>
    <div class="panel panel-default">       
        <div class="panel-heading">         
            <div class="pull-left">
                <h4>Products</h4>
            </div>
        </div>
                
        <div class="panel-body">
            <div class="col-lg-12">
                <!--
                <span class="pull-left">
                    <select v-model="tl">
                        <option value="0">All</option>
                        <option v-for="user in tls" :value="user.id">@{{ user.name }}</option>
                    </select>
                </span>
                <span class="pull-left">
                    <select v-model="cre">
                        <option value="0">All</option>
                        <option v-for="user in cres" :value="user.id">@{{ user.name }}</option>
                    </select>
                </span>
                -->
                <span class="pull-right">
                    <input type="text" id="daterange" v-model="daterange" size="25" readonly/>
                </span>
            </div>  
            <div class="col-lg-12" style="height:4em;">
                <!--
                <div style="display:inline;padding:10px;border:1px solid #000;margin-left:30px;">
                    <span>
                        Diets : <input type="radio" v-model="categories_radio" value="1" >
                    </span> 
                    <span>
                        Goods : <input type="radio" v-model="categories_radio" value="2" >
                    </span>
                    <span>
                        BT : <input type="radio" v-model="categories_radio" value="3" >
                    </span> 
                    <span>
                        Books : <input type="radio" v-model="categories_radio" value="4" >
                    </span>  
                </div>  -->             
                <div style="display:inline;padding:10px;border:1px solid #000;margin-left:30px">
                    <span style="margin-right:10px">
                        Diets : <input type="checkbox" v-model="categories_check" value="1">
                    </span> 
                    <span style="margin-right:10px">
                        Goods : <input type="checkbox" v-model="categories_check" value="2">
                    </span>
                    <span style="margin-right:10px">
                        BT : <input type="checkbox" v-model="categories_check" value="3">
                    </span> 
                    <span>
                        Books : <input type="checkbox" v-model="categories_check" value="4">
                    </span>  
                </div>                 
                &nbsp;&nbsp;&nbsp;<button @click="getReport" class="btn btn-primary btn-sm">Fetch</button>
            </div>          
                
            <div>
                <table id="table_products" class="table table-striped table-bordered">
                        
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lead id</th>
                                <th>Name</th>
                                <th>CRE</th>
                                <th>Products</th>
                                <th>Days</th>
                            </tr>
                        </thead>
                        <tbody>                
                            <tr v-for="lead in leads">
                                <td>@{{$index+1}}</td>
                                <td><a href="/lead/@{{lead.id}}/cart" target="_blank">@{{lead.id}}</a></td>
                                <td><a href="/lead/@{{lead.id}}/cart" target="_blank">@{{lead.name}}</a></td>
                                <td>@{{lead.cre_name}}</td>
                                <td>
                                    <table class="table table-bordered">   
                                        <tr>
                                            <th>SN</th>
                                            <th>Cart id</th>
                                            <th>Products</th>
                                            <th>Payments</th>
                                        </tr>
                                        <tr v-for="cart in lead.carts">
                                            <td>@{{$index+1}}</td>
                                            <td><a href="/cart/@{{ cart.id }}/" target="_blank">@{{ cart.id }}</a></td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr>             
                                                        <th>Product Name</th>
                                                        <th>Product Price</th>
                                                        <th>Product Discount</th>
                                                        <th>Product Amount</th>
                                                        <th>Product Date</th>
                                                    </tr>
                                                    <tr v-for="product in cart.products">
                                                        <td>@{{product.name}}</td>
                                                        <td>@{{product.pivot.price}}</td>
                                                        <td>
                                                            @{{product.pivot.discount}}
                                                            @{{product.product_category_id }}
                                                        </td>
                                                        <td>@{{product.pivot.amount}}</td>
                                                        <td>@{{product.pivot.updated_at}}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr>             
                                                        <th>Payment Date</th>
                                                        <th>Payment Method</th>
                                                        <th>Payment Amount</th>
                                                    </tr>
                                                    <tr v-for="payment in cart.payments">
                                                        <td>@{{ payment.date }}</td>
                                                        <td>@{{ payment.method.name }}</td>
                                                        <td>@{{ payment.amount }}</td>
                                                    </tr>
                                                </table>
                                            </td>         
                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    @{{ lead.carts[0].payments[0].date | days }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>                        
                        </tfoot>
                </table>                
                @{{leads.length}} entries                
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    new Vue({
        el: '#products',

        data: {
            user : {{ Auth::user()->hasRole('sales_tl') ? Auth::id() : '0' }},
            tls  : [],
            cres : [],
            tl   : '',
            cre  : '',
            daterange: '{{ Carbon::now()->subDays(30)->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
            leads : [],
            categories_check : [],
            categories_radio :'',
            loading : false,

        },

        ready: function(){
            this.getTLs();
            this.$watch('tl', function (newval, oldval) {
                this.getCREs();
            });             
            this.$watch('categories_check', function (newval, oldval) {
                if (newval.length > 0){
                    this.categories_radio = '';
                }
            });             
            this.$watch('categories_radio', function (newval, oldval) {
                if (newval) {
                    this.categories_check = [];
                }
            });             
        },

        methods: {            
            getTLs() {
                this.$http.get('/api/getUsersByRole',{role: 'sales_tl'}).then((response) => {
                    this.tls = response.data;
                });
            },
            getCREs() {
                this.$http.get('/api/getUsersByRole',{role: 'cre',supervisor:this.tl}).then((response) => {
                    this.cres = response.data;
                });
            },            
            
            getReport() {
                if (this.categories.length == 0 ) {
                    document.write('');
                    return false;
                }

                this.loading         = true;
                params               = {};
                params['categories'] = this.categories;
                params['daterange']  = this.daterange;             
                this.$http.get('/api/getProductPurchases',params).then((response) => {
                    this.leads    = response.data;
                    this.loading  = false;
                });
            }
        },
        computed: {
            start_date() {
                var range = this.daterange.split(" - ");
                return moment(range[0]).format('YYYY-MM-DD') + ' 0:0:0';
            },

            end_date() {
                var range = this.daterange.split(" - ");
                return moment(range[1]).format('YYYY-MM-DD') + ' 23:59:59';
            },
            categories() {
                if (this.categories_check.length > 0){
                    return this.categories_check;
                } else {
                    categories  = [this.categories_radio];
                    return categories;
                }
            },
        },
    })    

Vue.filter('days', function (value) {
    if (!value) {return }
    date = value.split('-');
    return moment().diff(moment([date[0], date[1]-1 , date[2]]), 'd');
    return moment([date[0], date[1]-1 , date[2]]).fromNow(true);  
})
</script>

<script type="text/javascript">
$(document).ready(function() 
{
    $('#daterange').daterangepicker(
    { 
        ranges: 
        {
            'Today': [new Date(), new Date()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), new Date()],
            'Last 30 Days': [moment().subtract(29, 'days'), new Date()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }, 
        format: 'YYYY-MM-DD' 
        }
    );   
    $('#daterange').on('apply.daterangepicker', function(ev, picker) 
    {   
        $('#daterange').trigger('change'); 
    });

});
</script>
<style type="text/css">
#loading
{
    position: fixed;
    margin-top: 100px;
    margin-left: 45%;
    z-index:2892;
    opacity:1;
}
</style>

