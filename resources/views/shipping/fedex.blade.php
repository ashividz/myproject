    <div class="row">

        <div class="panel panel-default">
            <div class="panel-heading">
                <button class="btn btn-success" v-on:click="sync()" disabled="@{{ loading }}">Sync with FedEx</button>
                <input type="text" id="daterange" v-model="daterange" size="25" readonly/>  
            </div>
            <div class="panel-body">
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Lead</th>
                            <th>Invoice</th>
                            <th>Tracking Id</th>
                            <th>Destination Address</th>
                            <th>Status</th>
                            <th>Estimated Delivery Date</th>
                            <th>Delivery Date</th>
                            <th>Status with Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="tracking in trackings">
                            <td>
                                @{{ tracking.created_at }}
                            </td>
                            <td>
                                <a href="/lead/@{{ tracking.shipping.cart.lead.id }}/cart" target="_blank">
                                    @{{ tracking.shipping.cart.lead.name }}
                                </a>
                            </td>
                            <td>
                                <a href="/track/@{{ tracking.id }}/invoice" v-bind:class="{ 'red': !tracking.invoice }" data-toggle="modal" data-target="#modal" >
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>
                            </td>
                            <td>
                                <a href="/track/@{{ tracking.id }}/" data-toggle="modal" data-target="#modal">
                                    @{{ tracking.id }}
                                </a>
                                <div v-if="tracking.returned" title="Return Tracking Id">
                                    <a href="/shipping/track/@{{ tracking.returned.id }}/" data-toggle="modal" data-target="#modal" >
                                        <i class="fa fa-reply"></i> @{{ tracking.returned.id }}
                                    </a>
                                </div>
                            </td>
                            <td>
                                @{{ tracking.shipper_address.City }}<br>
                                @{{ tracking.shipper_address.StateOrProvinceCode }},
                                @{{ tracking.shipper_address.CountryCode }}
                            </td>
                            <td>
                                <span class="statusbar @{{ tracking.status_class }}" title="@{{ tracking.status_detail.Description }}"></span>
                            </td>
                            <td>
                                @{{ tracking.estimated_delivery_timestamp | format_date }}
                            </td>
                            <td>
                                @{{ tracking.actual_delivery_timestamp | format_date }}
                            </td>
                            <td>
                                <span class="description @{{ tracking.status_class }}">
                                    @{{ tracking.status_detail.Description }}
                                </span> :
                                @{{ tracking.status_detail.Location.City }}
                                <div>
                                    @{{ tracking.status_detail.CreationTime | format_date2 }}
                                </div>
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
    var vm = new Vue({
        el: 'body',

        data: {
            loading: false,
            trackings: [],
            daterange: '{{ Carbon::now()->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
            start_date: '',
            end_date: '',
        },

        ready: function(){
            this.getTrackings();
        },

        methods: {

            getTrackings() {
                this.$http.get("/api/getTrackings", {'start_date': this.start_date, 'end_date' : this.end_date}).success(function(data){
                    this.trackings = data;
                }).bind(this);
            },

            sync() {
                this.loading = true;
                this.$http.get("/api/sync").success(function(data){
                    this.trackings = data;
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
    vm.$watch('daterange', function (newval, oldval) {
        this.getTrackings();
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