<div class="container" id="status">
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
        <div class="panel-body" v-if="cres1">
            
             <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#first" aria-controls="home" role="tab" data-toggle="tab">0 - 30 days</a></li>
                <li role="presentation"><a href="#second" aria-controls="profile" role="tab" data-toggle="tab">31 - 60 days</a></li>
                <li role="presentation"><a href="#third" aria-controls="messages" role="tab" data-toggle="tab">61 - 365 days</a></li>
                <li role="presentation"><a href="#fourth" aria-controls="messages" role="tab" data-toggle="tab">0 - 365 days</a></li>
            </ul>
              <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="first">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Leads</th> 
                                <th v-for="status in statuses">
                                    @{{ status.name }}
                                </th>
                                <th>Not Attempted</th>                        
                                <th>Not called since last 4 days</th>
                                <th>Less than 4 attempts</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cre in cres1">
                                <td>
                                    <a href="/cre/@{{ cre.id }}/leads" target="_blank">@{{ cre.name }}</a>
                                </td>
                                <td>
                                    <b>@{{ cre.leads }}</b>
                                </td>
                                <td v-for="count in cre.counts">
                                    @{{ count.cnt }}
                                    <small class="pull-right">
                                        <em>(@{{ cre.leads > 0 ? (count.cnt/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                    </small>
                                </td>
                                <td>
                                    @{{ cre.never }}
                                    <small class="pull-right">
                                        <em>(@{{ cre.leads > 0 ? (cre.never/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                    </small>
                                </td>
                                <td
                                    >@{{ cre.last }}
                                    <small class="pull-right">
                                        <em>(@{{ cre.leads > 0 ? (cre.last/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                    </small>
                                </td>
                                <td>
                                    @{{ cre.calls }}
                                    <small class="pull-right">
                                        <em>(@{{ cre.leads > 0 ? (cre.calls/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                    </small>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th>@{{ cres1 | total 'leads' }}</th>
                                <th colspan="6"></th>
                                <th>@{{ cres1 | total 'never' }}</th>
                                <th>@{{ cres1 | total 'last' }}</th>
                                <th>@{{ cres1 | total 'calls' }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="second">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Leads</th> 
                                <th v-for="status in statuses">
                                    @{{ status.name }}
                                </th>
                                <th>Not Attempted</th>                        
                                <th>Leads not called for last 4 days</th>
                                <th>Less than 4 dispositions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cre in cres2">
                                <td>
                                    <a href="/cre/@{{ cre.id }}/leads" target="_blank">@{{ cre.name }}</a>
                                </td>
                                <td>
                                    <b>@{{ cre.leads }}</b>
                                </td>
                                <td v-for="count in cre.counts">
                                    @{{ count.cnt }}
                                    <small class="pull-right">
                                        <em>(@{{ cre.leads > 0 ? (count.cnt/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                    </small>
                                </td>
                                <td>
                                    @{{ cre.never }}
                                    <small class="pull-right">
                                        <em>(@{{ cre.leads > 0 ? (cre.never/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                    </small>
                                </td>
                                <td
                                    >@{{ cre.last }}
                                    <small class="pull-right">
                                        <em>(@{{ cre.leads > 0 ? (cre.last/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                    </small>
                                </td>
                                <td>
                                    @{{ cre.calls }}
                                    <small class="pull-right">
                                        <em>(@{{ cre.leads > 0 ? (cre.calls/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                    </small>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th>@{{ cres2 | total 'leads' }}</th>
                                <th colspan="6"></th>
                                <th>@{{ cres2 | total 'never' }}</th>
                                <th>@{{ cres2 | total 'last' }}</th>
                                <th>@{{ cres2 | total 'calls' }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="third">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Leads</th> 
                                <th v-for="status in statuses">
                                    @{{ status.name }}
                                </th>
                                <th>Not Attempted</th>                        
                                <th>Leads not called for last 4 days</th>
                                <th>Less than 4 dispositions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cre in cres3">
                                <td>
                                    <a href="/cre/@{{ cre.id }}/leads" target="_blank">@{{ cre.name }}</a>
                                </td>
                                <td>
                                    <b>@{{ cre.leads }}</b>
                                </td>
                                <td v-for="count in cre.counts">
                                    @{{ count.cnt }}
                                    <small class="pull-right">
                                        <em>(@{{ cre.leads > 0 ? (count.cnt/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                    </small>
                                </td>
                                <td>
                                    @{{ cre.never }}
                                    <small class="pull-right">
                                        <em>(@{{ cre.leads > 0 ? (cre.never/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                    </small>
                                </td>
                                <td
                                    >@{{ cre.last }}
                                    <small class="pull-right">
                                        <em>(@{{ cre.leads > 0 ? (cre.last/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                    </small>
                                </td>
                                <td>
                                    @{{ cre.calls }}
                                    <small class="pull-right">
                                        <em>(@{{ cre.leads > 0 ? (cre.calls/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                    </small>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th>@{{ cres3 | total 'leads' }}</th>
                                <th colspan="6"></th>
                                <th>@{{ cres3 | total 'never' }}</th>
                                <th>@{{ cres3 | total 'last' }}</th>
                                <th>@{{ cres3 | total 'calls' }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="fourth">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Leads</th> 
                                <th v-for="status in statuses">
                                    @{{ status.name }}
                                </th>
                                <th>Not Attempted</th>                        
                                <th>Leads not called for last 4 days</th>
                                <th>Less than 4 dispositions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cre in cres4">
                                <td>
                                    <a href="/cre/@{{ cre.id }}/leads" target="_blank">@{{ cre.name }}</a>
                                </td>
                                <td>
                                    <b>@{{ cre.leads }}</b>
                                </td>
                                <td v-for="count in cre.counts">
                                    @{{ count.cnt }}
                                    <small class="pull-right">
                                        <em>(@{{ cre.leads > 0 ? (count.cnt/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                    </small>
                                </td>
                                <td>
                                    @{{ cre.never }}
                                    <small class="pull-right">
                                        <em>(@{{ cre.leads > 0 ? (cre.never/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                    </small>
                                </td>
                                <td
                                    >@{{ cre.last }}
                                    <small class="pull-right">
                                        <em>(@{{ cre.leads > 0 ? (cre.last/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                    </small>
                                </td>
                                <td>
                                    @{{ cre.calls }}
                                    <small class="pull-right">
                                        <em>(@{{ cre.leads > 0 ? (cre.calls/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                    </small>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th>@{{ cres4 | total 'leads' }}</th>
                                <th colspan="6"></th>
                                <th>@{{ cres4 | total 'never' }}</th>
                                <th>@{{ cres4 | total 'last' }}</th>
                                <th>@{{ cres4 | total 'calls' }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    //var tab = require('vue-strap').tab;
    var vm = new Vue({
        el: '#status',

        data: {
            user: {{ Auth::user()->hasRole('sales_tl') ? Auth::id() : '0' }},
            statuses: [],
            users: [],
            cres1: [],
            cres2: [],
            cres3: [],
            cres4: []
        },

        ready: function(){
            this.getUsers();
            this.getStatusList();
        @if(Auth::user()->hasRole('sales_tl'))
            this.getReport();
        @endif
        },

        methods: {

            getUsers() {
                $.getJSON("/api/getUsersByRole?role=sales_tl", function(users){
                    this.users = users;
                    console.log(users);
                }.bind(this));
            },

            getStatusList() {
                $.getJSON("/api/getStatusList", function(statuses){
                    this.statuses = statuses;
                    console.log(statuses);
                }.bind(this));
            },
            
            getReport() {
                $.getJSON("/api/leadStatusReport", {'user_id' : this.user }, function(cres){
                    this.cres1 = cres;
                }.bind(this));

                $.getJSON("/api/leadStatusReport", {'start_date': '{{ Carbon::now()->subdays(60)->format('Y-m-d') }}', 'end_date' : '{{ Carbon::now()->subdays(31)->format('Y-m-d 23:59:59') }}', 'user_id' : this.user }, function(cres){
                    this.cres2 = cres;
                }.bind(this));

                $.getJSON("/api/leadStatusReport", {'start_date': '{{ Carbon::now()->subdays(365)->format('Y-m-d') }}', 'end_date' : '{{ Carbon::now()->subdays(61)->format('Y-m-d 23:59:59') }}', 'user_id' : this.user }, function(cres){
                    this.cres3 = cres;
                }.bind(this));

                $.getJSON("/api/leadStatusReport", {'start_date': '{{ Carbon::now()->subdays(365)->format('Y-m-d') }}', 'end_date' : '{{ Carbon::now() }}', 'user_id' : this.user }, function(cres){
                    this.cres4 = cres;
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