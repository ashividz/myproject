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
                    <input type="checkbox" name="status" value="@{{ status.id }}" v-model="selected"> @{{ status.name }}
                </span>
            </div>
        </div>
        <div class="panel-body">
            <form  v-on:submit.prevent="sendSMS">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><input type="checkbox" @click="selectAll"></th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(key, lead) in leads">
                            <td>
                                @{{ key + 1 }}
                            </td>
                            <td>
                                <input type="checkbox" name="check[]" v-model="selected" value="@{{ lead.id }}">
                            </td>
                            <td>
                                <a href="/lead/@{{ lead.id }}/viewDetails" target="_blank">
                                    @{{ lead.name }}
                                </a>
                            </td>
                            <td>
                                @{{ lead.city }}
                            </td>
                            <td>
                                @{{ lead.status.name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-show='leads.length'>
                    @{{ message.length }} characters
                    <textarea v-model="message"></textarea>
                    <button type="submit" class="btn btn-primary" disabled='@{{ loading || message.length == 0 || selected.length == 0 }}'>Send SMS</button>
                </div>
            </form>
        </div>
    </div>
    <div class="alert alert-success" v-show='responses' style="width:500px">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <li v-for='response in responses'>
            @{{ response.name }} - 
            @{{ response.sms }}
        </li>
    </div>  
</div>
<script>
    Vue.http.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';

    var vm1 = new Vue({
        el: '#leads',

        data: {
            statuses: [],
            loading: false,
            leads: [],
            daterange: '{{ Carbon::now()->subDay(30)->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
            start_date: '',
            end_date: '',
            patient: 'active',
            ids: [],
            selected: [],
            message: '',
            responses: []
        },

        ready: function(){
            this.getStatuses();
            this.getLeads();
        },

        methods: {

            getStatuses() {
                this.$http.get("/api/getStatusList")
                .success(function(data){
                    this.statuses = data;
                }).bind(this);
            },

            getLeads() {
                this.loading = true;
                this.$http.post("/api/getLeads", {
                    'start_date': this.start_date, 
                    'end_date' : this.end_date, 
                    'status_id' : this.selected
                })
                .success(function(data){
                    this.leads = data;
                    this.loading = false;
                }).bind(this);
            },

            selectStatus(status) {
                console.log(status);
                this.selected.push(status.id);
            },

            selectAll() {
                if(this.selected.length > 0) {
                    this.ids = [];
                } else {                    
                    for (lead in this.leads) {
                        this.ids.push(this.leads[lead].id);
                    }
                };
                
            },

            sendSMS() {
                this.loading = true;
                this.$http.post("/api/sendSMS", {
                    'ids': this.ids,
                    'message' : this.message
                })
                .success(function(data){
                    this.responses = data;
                    console.log(data);
                    this.loading = false;
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
      return moment(value).format('D-MMM-YY');
    })
    vm1.$watch('daterange', function (newval, oldval) {
        this.getLeads();
    })
    vm1.$watch('selected', function (newval, oldval) {
        this.getLeads();
    })
</script>
<script type="text/javascript" src="/js/daterange.js"></script>
<style type="text/css">
.location {
    display: inline-block;
    margin-left: 40px;
}
.location input {
    margin-right: 20px;
}
</style>