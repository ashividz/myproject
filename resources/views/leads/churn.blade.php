@extends('master')

@section('content')
<div class="container" id="leads">
    <!-- Loader -->
    <div id="loader" v-show="loading" style="text-align:center" >
        <img src="/images/loading.gif">
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <input type="text" id="daterange" v-model="daterange" size="25" readonly/>
            <div style="display:inline; margin:5px 10px;">
                <span v-for="status in statuses" style="padding:10px">
                    <input type="checkbox" name="status" value="@{{ status.id }}" v-model="selected" :checked="status.id==1 || status.id==2 || status.id==3"> @{{ status.name }}
                </span>
                <input type="text" v-model="limit" debounce="800" style="width: 50px;"> Limit
            </div>
        </div>
        <div class="panel-body">
            <form v-on:submit.prevent="churn">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="pull-right"><input type="checkbox" @click="selectAll"></th>
                            <th>Name</th>
                            <th>CRE</th>
                            <th width="40%">Last Disposition</th>
                            <th>Source</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(key, lead) in leads">
                            <td>
                                @{{ key + 1 }}
                                <div class="pull-right">
                                    <input type="checkbox" name="check[]" v-model="ids" value="@{{ lead.id }}">
                                </div>
                            </td>
                            <td>
                                <a href="/lead/@{{ lead.id }}/viewDetails" target="_blank">
                                    @{{ lead.name }}
                                </a>
                            </td>
                            <td>
                                @{{ lead.cre_name }}
                                <small><span class="pull-right">
                                    @{{ lead.cre_assigned_at | format_date }}
                                </span></small>
                            </td>
                            <td>
                                <strong>[@{{ lead.disposition.master.disposition_code }}]</strong>
                                @{{ lead.disposition.remarks }}

                                - <em><small>
                                    @{{ lead.disposition.name }}
                                </small></em>
                                <span class="pull-right">
                                    <small>@{{ lead.disposition.created_at | format_date }}</small>
                                </span>
                            </td>
                            <td>
                                @{{ lead.lsource.source_name }}
                            </td>
                            <td>
                                @{{ lead.status.name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <div class="col-md-3 panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <select class="form-control" v-model="user">
                    <option value="" selected>--Select CRE-- </option>
                    <option v-for="user in users" :value="user.id">@{{ user.name }}</option>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" v-if="user && ids.length > 0" @click="churn">Churn Lead</button>
            </div>
        </div>
    </div>
</div>
<script>
    new Vue({
        el: '#leads',

        data: {
            statuses: [],
            loading: false,
            leads: [],
            daterange: '{{ Carbon::now()->subDay(15)->format('Y-m-d') }} - {{ Carbon::now()->subDay(15)->format('Y-m-d') }}',
            start_date: '',
            end_date: '',
            users: [],
            ids: [],
            selected: [],
            limit: 100,
        },

        ready: function(){
            this.getStatuses();
            this.getLeads();
            this.getUsers();
            this.$watch('daterange', function (newval, oldval) {
                this.getLeads();
            })
            this.$watch('selected', function (newval, oldval) {
                this.getLeads();
            })
            this.$watch('limit', function (newval, oldval) {
                this.getLeads();
            })
        },

        methods: {

            getStatuses() {
                this.$http.get("/api/getStatusList")
                .success(function(data){
                    this.statuses = data;
                }).bind(this);
            },

            getLeads() {
                if(this.selected.length > 0) {
                    $.isLoading({ text: "Loading" });
                    this.$http.get("/getLeadsByAssignedDate", {
                        'start_date': this.start_date, 
                        'end_date' : this.end_date, 
                        'status_id' : this.selected,
                        'limit': this.limit
                    })
                    .success(function(data){
                        this.leads = data;
                        $.isLoading( "hide" );
                    }).bind(this);
                }                    
            },
            getUsers() {
                $.isLoading({ text: "Loading" });
                this.$http.get("/api/getUsersByRole", {
                    'role': 'cre', 
                })
                .success(function(data){
                    this.users = data;
                    $.isLoading( "hide" );
                }).bind(this);
            },

            selectStatus(status) {
                console.log(status);
                this.selected.push(status.id);
            },

            selectAll() {
                if(this.ids.length > 0) {
                    this.ids = [];
                } else {                    
                    for (lead in this.leads) {
                        this.ids.push(this.leads[lead].id);
                    }
                };
                
            },

            churn() {
                $.isLoading({ text: "Churning Leads" });
                this.$http.post("/leads/churn", {
                    'ids': this.ids,
                    'cre_id' : this.user
                })
                .success(function(data){
                    this.getLeads();
                    this.responses = data;
                    this.ids = [];
                    $.isLoading( "hide" );
                    toastr.success("Leads Churned", "Success");
                }).bind(this);
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
            }
        }
    })
</script>
<script type="text/javascript" src="/js/daterange.js"></script>
@endsection