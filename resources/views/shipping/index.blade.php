        <div class="container" id="shipping">
        <div class="panel panel-default">
            <div class="panel-heading">
                <input type="text" id="daterange" v-model="daterange" size="25" readonly/>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="col-md-1"><h5>Date</h5></div>
                    <div class="col-md-2"><h5>Name</h5></div>
                    <div class="col-md-1"><h5>Cart Created At</h5></div>
                    <div class="col-md-1"><h5>Carrier</h5></div>
                    <div class="col-md-1"><h5>Tracking Id</h5></div>
                    <div class="col-md-2"><h5>Status</h5></div>
                    <div class="col-md-2"><h5>Estimated Delivery</h5></div>
                    <div class="col-md-2"><h5>Actual Delivery</h5></div>
                </div>
            </div>
        </div>
        <div v-for="(index, shipping) in shippings">
            <div class="panel panel-default">
                <div class="panel-body">
                    <shipping-field :shipping.sync="shipping" :index="index"></shipping-field> 
                </div>
            </div>
        </div>
    </div>
@include('partials.modal')
<style type="text/css">
    
    table.table {
        font-size: 12px;
    }
    
</style>
<template id="shipping-field">    
    <div class="col-md-1"> 
        @{{ shipping.created_at | format_date }}
    </div>
    <div class="col-md-2">
        <a href="/cart/@{{ shipping.cart.id }}" target="_blank">
            @{{ shipping.cart.lead.name }}
        </a>
    </div>
    <div class="col-md-1">
        @{{ shipping.cart.created_at | format_date2 }}
    </div>
    <div class="col-md-1">
        @{{ shipping.carrier ? shipping.carrier.name : 'Manual'}}
    </div>
    <div class="col-md-1">
        @{{ shipping.tracking_id }}
    </div>
    <div class="col-md-2" >
        <div class="statusbar @{{ shipping.status | lowercase }}" title="@{{ shipping.status_detail.Description }}" v-if="!edit"></div>
        <div v-else>
            <select v-model="shipping.status" @change="update">
                <option value="">-- Select Status --</option>
                <option value="dl">Delivered</option>
                <option value="it">In Transit</option>
                <option value="de">Deliverey Exception</option>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        @{{ shipping.estimated_delivery_timestamp | format_date }}
    </div>
    <div class="col-md-2">
        <div v-show="!edit">
            @{{ shipping.actual_delivery_timestamp | format_date }}
        </div>
        <div v-else>
            <input type="text" v-model="shipping.actual_delivery_timestamp" class="form-control" placeholder="Delivery Date (dd-mm-yyyy)" @keyup.enter="update">
        </div>
        <span v-if="shipping.carrier_id > 1 || !shipping.carrier_id" class="pull-right">
            <i class="fa fa-edit" @click="toggleEdit"></i>
        </span>
    </div>
</template>
<script>
Vue.component('shippingField', {
    mixins: [ VueFocus.mixin ],
    template: '#shipping-field',
    props: ['shipping', 'index'],
    data: function() {
        return {
            edit: false,
            created_by: {{ Auth::id() }},
            carriers: []
        }
    },

    ready: function() {
        this.carriers = this.$parent.carriers;
    },

    methods: {
        toggleEdit() {
        this.shipping.actual_delivery_timestamp = this.shipping.actual_delivery_timestamp ? this.shipping.actual_delivery_timestamp : '{{ Carbon::now() }}';
            this.edit = !this.edit;
        },

        update() {
            this.$http.patch("/shipping/"+ this.shipping.id, {
                status: this.shipping.status,
                actual_delivery_timestamp: this.shipping.actual_delivery_timestamp
            })
            .success(function(data){
                toastr.success("Shipping details updated", "Success!");
                //this.$parent.getShippings();
                this.toggleEdit();
            }).bind(this);
        }
    }
})
</script>
<script>
    new Vue({
        el: '#shipping',

        data: {
            loading: false,
            shippings: [],
            daterange: '{{ Carbon::now()->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
            start_date: '',
            end_date: '',
            carriers: []
        },

        ready: function(){
            this.getShippings();
            this.$watch('daterange', function (newval, oldval) {
                this.getShippings();
            })
            this.getCarriers();
        },

        methods: {

            getShippings() {
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
            },

            getCarriers() {
                this.$http.get("/getCarriers")
                .success(function(data){
                    this.carriers = data;
                }).bind(this);
            },
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
</script>
<script type="text/javascript" src="/js/daterange.js"></script>