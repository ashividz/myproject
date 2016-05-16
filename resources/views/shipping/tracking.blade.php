<!DOCTYPE html>
<html>
<head>
    <title></title>

    <!-- jQuery -->
    <script src="/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="/plugins/bootstrap/bootstrap.min.css">
    <script src="/plugins/bootstrap/bootstrap.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="/plugins/font-awesome/font-awesome.min.css">

    <!-- VueJS -->
    <script src="/plugins/vue/vue.min.js"></script>
    <script src="/plugins/vue/vue-resource.min.js"></script>

    <!-- Moment JS -->
    <script src="/plugins/moment/moment.min.js"></script>

    <!-- Main css -->
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    
</head>
<body>
    <div class="container1">

        <div id="alert" v-show="loading" style="text-align:center" class="alert alert-warning">
            <img src="/images/loading.gif">
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <button class="btn btn-success" v-on:click="sync()" disabled="@{{ loading }}">Sync with FedEx</button>
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
                                <a href="/lead/@{{ tracking.cart.lead.id }}/cart" target="_blank">
                                    @{{ tracking.cart.lead.name }}
                                </a>
                            </td>
                            <td>
                                <a href="" class="1btn btn1-primary">
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>
                            </td>
                            <td>
                                <a href="/track/@{{ tracking.id }}/" data-toggle="modal" data-target="#modal" >
                                    @{{ tracking.id }}
                                </a>
                                <div v-if="tracking.returned" title="Return Tracking Id">
                                    <a href="/track/@{{ tracking.returned.id }}/" data-toggle="modal" data-target="#modal" >
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
    #alert {
        position: fixed;
        margin-top: 150px;
        width: 200px;
        left: 40%;
        z-index: 9999;
        text-align: center;        
        background-color: #333333;
        border-color: #CAC5BC;
    }
    table.table {
        font-size: 12px;
    }
    
</style>
<script>
    var vm = new Vue({
        el: 'body',

        data: {
            loading: false,
            trackings: []
        },

        ready: function(){
            this.getTrackings();
        },

        methods: {

            getTrackings() {
                this.$http.get("/api/getTrackings").success(function(data){
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
</script>
</body>
</html>