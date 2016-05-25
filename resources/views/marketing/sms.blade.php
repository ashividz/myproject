<div class="container" id="leads">
    <div class="panel panel-default">
        <div class="panel-heading">
            <input type="text" id="daterange" v-model="daterange" size="25" readonly/>
            <div class="location">
                NCR : <input type="radio" value="ncr" name="location" checked>
                Pan India : <input type="radio" value="pan" name="location"> 
                Internationl : <input type="radio" value="int" name="location" >

                Filter Unread : <input type="checkbox" name="filter_unread" v-model='filter_unread'>
                Filter Action : <input type="checkbox" name="filter_action" v-model='filter_action'>
            </div>   
        </div>
        <div class="panel-body">
            <form>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="lead in leads">
                            <td></td>
                            <td>
                                <a href="/lead/@{{ lead.id }}/viewDetails" target="_blank">
                                    @{{ lead.name }}
                                </a>
                            </td>
                            <td>
                                @{{ lead.city }}
                                @{{ lead.state }}
                                @{{ lead.country }}
                            </td>
                            <td>
                                @{{ lead.patient.fees[0].start_date | format_date2 }}
                            </td>
                            <td>
                                @{{ lead.patient.fees[0].end_date | format_date2 }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
<script>
    var vm1 = new Vue({
        el: '#leads',

        data: {
            loading: false,
            leads: [],
            daterange: '{{ Carbon::now()->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
            start_date: '',
            end_date: '',
            location:
        },

        ready: function(){
            this.getLeads();
        },

        methods: {

            getLeads() {
                this.$http.get("/api/getLeads", {'start_date': this.start_date, 'end_date' : this.end_date}).success(function(data){
                    this.leads = data;
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
    vm1.$watch('daterange', function (newval, oldval) {
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