    <div class="container" id="shipping">
        <div class="panel panel-default">
            <div class="panel-heading">
                <input type="text" id="daterange" v-model="daterange" size="25" readonly/>
            </div>
            <div class="panel-body">
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Lead</th>
                            <th>Invoice</th>
                            <th>Carrier</th>
                            <th>Tracking Id</th>
                            <th>Status</th>
                            <th>Estimated Delivery</th>
                            <th>Actual Delivery</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="shipping in shippings">
                            <td>
                                @{{ shipping.created_at | format_date }}
                            </td>
                            <td>
                                <a href="/lead/@{{ shipping.cart.lead.id }}/cart" target="_blank">
                                    @{{ shipping.cart.lead.name }}
                                </a>
                            </td>
                            <td>
                                <a href="/cart/@{{ shipping.cart_id }}/invoice" v-bind:class="{ 'red': !shipping.invoice }" data-toggle="modal" data-target="#modal" >
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>
                            </td>
                            <td>
                                @{{ shipping.carrier.name }}
                            </td>
                            <td>
                                @{{ shipping.tracking_id }}
                            </td>
                            <td>
                                <span class="statusbar @{{ shipping.status | lowercase }}" title="@{{ shipping.status_detail.Description }}"></span>
                            </td>
                            <td>
                                @{{ shipping.estimated_delivery_timestamp | format_date }}
                            </td>
                            <td>
                                @{{ shipping.actual_delivery_timestamp | format_date }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@include('partials.modal')
<style type="text/css">
    
    table.table {
        font-size: 12px;
    }
    
</style>
<script>
    var vm1 = new Vue({
        el: '#shipping',

        data: {
            loading: false,
            shippings: [],
            daterange: '{{ Carbon::now()->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
            start_date: '',
            end_date: '',
        },

        ready: function(){
            this.getshippings();
        },

        methods: {

            getshippings() {
                this.$http.get("/api/getShippings", {'start_date': this.start_date, 'end_date' : this.end_date}).success(function(data){
                    this.shippings = data;
                }).bind(this);
            },

            sync() {
                this.loading = true;
                this.$http.get("/api/sync").success(function(data){
                    this.shippings = data;
                    this.loading = false;
                }).bind(this);
            }
        },
        computed: {
            /*daterange() {
                return moment(this.start_date).format('YYYY-MM-DD') + ' - ' + moment(this.end_date).format('YYYY-MM-DD');
            },*/

            start_date() {
                var range = this.daterange.split(" - ");
                return moment(range[0]).format('YYYY-MM-DD') + ' 0:0:0';
            },

            end_date() {
                var range = this.daterange.split(" - ");
                return moment(range[1]).format('YYYY-MM-DD') + ' 23:59:59';
            }
        }
    })
    Vue.filter('format_date', function (value) {
        if (value == null) {
            return null;
        }
      return moment(value).format('D MMM hh:mm A');
    })
    Vue.filter('format_date2', function (value) {
        if (value == null) {
            return null;
        }
      return moment(value).format('D MMM');
    })
    vm1.$watch('daterange', function (newval, oldval) {
        this.getshippings();
    })
</script>
<script type="text/javascript" src="/js/daterange.js"></script>