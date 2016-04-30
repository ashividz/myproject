<div class="container" id="pipelines">
    <div class="panel">
        <div class="panel-heading">
        @if(Auth::user()->hasRole('marketing') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('admin'))
            <span>
                <select v-model="user">
                    <option v-for="user in users"  v-bind:value="user.id">@{{ user.name }}</option>
                </select>
            </span>
        @endif
        </div>
        <div class="panel-body">
            
             <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#first" aria-controls="home" role="tab" data-toggle="tab">Today</a></li>
                <li role="presentation"><a href="#second" aria-controls="profile" role="tab" data-toggle="tab">Next 7 Days</a></li>
                <li role="presentation"><a href="#third" aria-controls="messages" role="tab" data-toggle="tab">This Month</a></li>
                <li role="presentation"><a href="#fourth" aria-controls="messages" role="tab" data-toggle="tab">Last Month</a></li>
                <li role="presentation"><a href="#fifth" aria-controls="messages" role="tab" data-toggle="tab">Next Month</a></li>
            </ul>
              <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="first">
                    <table class="table table-bordered">
                        <tbody>
                            <tr v-for="cre in cres1">
                                <th width="20%"> @{{cre.name}}</th>
                                <td>
                                    <table class="table table-bordered">
                                        <thead v-if="cre.pipelines.length">
                                            <tr>
                                                <th>Lead</th>
                                                <th>Date</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                <th>Amount</th>
                                                <th>Remark</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="pipeline in cre.pipelines"  class="@{{ pipeline.state.css_class }}">
                                                <td>
                                                    <a href="/lead/@{{ pipeline.lead.id }}/cart" target="_blank">@{{ pipeline.lead.name }}</a>
                                                </td>
                                                <td>
                                                    @{{ pipeline.date }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.currency.symbol }} @{{ pipeline.price }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.discount }} %
                                                </td>   
                                                <td>
                                                    @{{ pipeline.currency.symbol }} @{{ pipeline.amount }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.remark }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.state.name }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="second">
                    <table class="table table-bordered">
                        <tbody>
                            <tr v-for="cre in cres2">
                                <th width="20%"> @{{cre.name}}</th>
                                <td>
                                    <table class="table table-bordered">
                                        <thead v-if="cre.pipelines.length">
                                            <tr>
                                                <th>Lead</th>
                                                <th>Date</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                <th>Amount</th>
                                                <th>Remark</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="pipeline in cre.pipelines" class="@{{ pipeline.state.css_class }}">
                                                <td>
                                                    <a href="/lead/@{{ pipeline.lead.id }}/cart" target="_blank">@{{ pipeline.lead.name }}</a>
                                                </td>
                                                <td>
                                                    @{{ pipeline.date }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.currency.symbol }} @{{ pipeline.price }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.discount }}
                                                </td>   
                                                <td>
                                                   @{{ pipeline.currency.symbol }}  @{{ pipeline.amount }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.remark }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.state.name }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="third">
                    <table class="table table-bordered">
                        <tbody>
                            <tr v-for="cre in cres3">
                                <th width="20%"> @{{cre.name}}</th>
                                <td>
                                    <table class="table table-bordered">
                                        <thead v-if="cre.pipelines.length">
                                            <tr>
                                                <th>Lead</th>
                                                <th>Date</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                <th>Amount</th>
                                                <th>Remark</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="pipeline in cre.pipelines" class="@{{ pipeline.state.css_class }}">
                                                <td>
                                                    <a href="/lead/@{{ pipeline.lead.id }}/cart" target="_blank">@{{ pipeline.lead.name }}</a>
                                                </td>
                                                <td>
                                                    @{{ pipeline.date }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.currency.symbol }} @{{ pipeline.price }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.discount }}
                                                </td>   
                                                <td>
                                                    @{{ pipeline.currency.symbol }} @{{ pipeline.amount }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.remark }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.state.name }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="fourth">
                    <table class="table table-bordered">
                        <tbody>
                            <tr v-for="cre in cres4">
                                <th width="20%"> @{{cre.name}}</th>
                                <td>
                                    <table class="table table-bordered">
                                        <thead v-if="cre.pipelines.length">
                                            <tr>
                                                <th>Lead</th>
                                                <th>Date</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                <th>Amount</th>
                                                <th>Remark</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="pipeline in cre.pipelines" class="@{{ pipeline.state.css_class }}">
                                                <td>
                                                    <a href="/lead/@{{ pipeline.lead.id }}/cart" target="_blank">@{{ pipeline.lead.name }}</a>
                                                </td>
                                                <td>
                                                    @{{ pipeline.date }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.currency.symbol }} @{{ pipeline.price }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.discount }}
                                                </td>   
                                                <td>
                                                    @{{ pipeline.currency.symbol }} @{{ pipeline.amount }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.remark }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.state.name }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="fifth">
                    <table class="table table-bordered">
                        <tbody>
                            <tr v-for="cre in cres5">
                                <th width="20%"> @{{cre.name}}</th>
                                <td>
                                    <table class="table table-bordered">
                                        <thead v-if="cre.pipelines.length">
                                            <tr>
                                                <th>Lead</th>
                                                <th>Date</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                <th>Amount</th>
                                                <th>Remark</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="pipeline in cre.pipelines" class="@{{ pipeline.state.css_class }}">
                                                <td>
                                                    <a href="/lead/@{{ pipeline.lead.id }}/cart" target="_blank">@{{ pipeline.lead.name }}</a>
                                                </td>
                                                <td>
                                                    @{{ pipeline.date }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.currency.symbol }} @{{ pipeline.price }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.discount }} %
                                                </td>   
                                                <td>
                                                    @{{ pipeline.currency.symbol }} @{{ pipeline.amount }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.remark }}
                                                </td>
                                                <td>
                                                    @{{ pipeline.state.name }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    //var tab = require('vue-strap').tab;
    var vm = new Vue({
        el: '#pipelines',

        data: {
            user: {{ Auth::user()->hasRole('sales_tl') ? Auth::id() : '0' }},
            users: [],
            cres1: [],
            cres2: [],
            cres3: [],
            cres4: [],
            cres5: []
        },

        ready: function(){
            this.getUsers();
        @if(Auth::user()->hasRole('sales_tl'))
            this.getReport();
        @endif
        },

        methods: {

            getUsers() {
                $.getJSON("/api/getUsersByRole?role=sales_tl", function(users){
                    this.users = users;
                }.bind(this));
            },
            
            getReport() {

                /**Today**/
                $.getJSON("/api/getHotPipelines", {'user_id' : this.user }, function(cres){
                    this.cres1 = cres;
                }.bind(this));

                /** 1 Week**/
                $.getJSON("/api/getHotPipelines", {'user_id' : this.user, 'start_date': '{{ Carbon::now()->addDay()->format('Y-m-d') }}', 'end_date' : '{{ Carbon::now()->addDays(7)->format('Y-m-d') }}' }, function(cres){
                    this.cres2 = cres;
                }.bind(this));

                /** This Month **/
                $.getJSON("/api/getHotPipelines", {'user_id' : this.user, 'start_date': '{{ Carbon::now()->startOfMonth() }}', 'end_date' : '{{ Carbon::now()->endOfMonth() }}' }, function(cres){
                    this.cres3 = cres;
                }.bind(this));

                /** Last Month **/
                $.getJSON("/api/getHotPipelines", {'user_id' : this.user, 'start_date': '{{ Carbon::now()->subMonth()->startOfMonth() }}', 'end_date' : '{{ Carbon::now()->subMonth()->endOfMonth() }}' }, function(cres){
                    this.cres4 = cres;
                }.bind(this));

                /** Next Month **/
                $.getJSON("/api/getHotPipelines", {'user_id' : this.user, 'start_date': '{{ Carbon::now()->addMonth()->startOfMonth() }}', 'end_date' : '{{ Carbon::now()->addMonth()->endOfMonth() }}' }, function(cres){
                    this.cres5 = cres;
                }.bind(this));
            }
        }
    })

    vm.$watch('user', function (newval, oldval) {
        this.getReport();
    })

    Vue.filter('total', function (list, key1) {
        return list.reduce(function(total, item) {
            return total + item[key1]
        }, 0)
    })
</script> 
<style type="text/css">
    tr.primary {
    }
    tr.danger {
        background-color: red;
    }
    tr.success {
        background-color: green;
    }
    tr.warning {
        background-color: orange;
    }
</style>