<div class="container" id="status">
  <div id="loader" v-show="loading" style="text-align:center" >
        <img src="/images/loading.gif">
    </div>
    <div class="panel">
        <div class="panel-heading">
        @if(Auth::user()->hasRole('marketing') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('admin'))
            <span>
                <select v-model="user">
                    <option value="0">Select All</option>
                    <option v-for="user in users"  v-bind:value="user.id">@{{ user.name }}</option>
                </select>
                <a id="downloadCSV" class="btn btn-primary pull-right" style="margin-bottom:2em;">download</a>
                 
                
            </span>

              <input type="text" id="daterange" v-model="daterange" size="25" readonly/>
        @endif
        </div>
        <div class="panel-body" v-if="cres">
            
             <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"></a></li>
            </ul>
              <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="first">

                    <table class="table table-bordered lead_status">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Leads</th> 
                                <th>Converted before <input type="text" id="conversionDate" v-model="conversionDate" size="15" readonly/></th>
                                <th>Conversion %</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cre in cres">
                                <td>
                                    @{{ cre.name }}
                                </td>
                                <td>
                                 <a href="/cre/@{{ cre.id }}/leads/?start_date=@{{ encodeURIComponent(start_date) }}&end_date=@{{ encodeURIComponent(end_date) }}" class="dropdown-toggle" data-toggle="modal" data-target="#modal">
                                   <b>@{{ cre.leads }}</b>
                                   </a>
                                </td>
                                <td>
                                <a href="/cre/@{{ cre.id }}/leads/converted/?start_date=@{{ encodeURIComponent(start_date) }}&end_date=@{{ encodeURIComponent(end_date) }}" class="dropdown-toggle" data-toggle="modal" data-target="#modal">                                        
                                   <b>@{{ cre.converted }}</b>
                                   </a>
                                </td>
                                <td>
                                    <em>(@{{ cre.leads > 0 ? (cre.converted/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                   
                                </td>
                        </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total</td>
                                <td>@{{ cres | total 'leads' }}</td>
                                <td>@{{ cres | total 'converted' }}</td>
                                <td>(@{{ cres | percentage 'leads' }}%)</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
              
              
            </div>
        </div>
    </div>
</div>
@include('partials/modal')
<script>
    //var tab = require('vue-strap').tab;
    var vm = new Vue({
        el: '#status',

        data: {
            user: {{ Auth::user()->hasRole('sales_tl') ? Auth::id() : '0' }},
            statuses: [],
            users: [],
            cres: [],
            loading: false,
            daterange: '{{ Carbon::now()->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
            start_date: '',
            end_date: '',
            conversion_last_date: '',
            conversionDate: '{{ Carbon::now()->format('Y-m-d') }}'
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
                this.loading = true;
                $.getJSON("/api/creConversionReport", {
                    'user_id' : this.user,
                    start_date: this.start_date, 
                    end_date: this.end_date,
                    conversion_last_date: this.conversion_last_date
                     }, function(cres){
                    this.cres = cres;
                    this.loading = false;
                }.bind(this));

              

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
            conversion_last_date() {
                var range = this.conversionDate;
                return moment(range).format('YYYY-MM-DD') + ' 23:59:59';
            }
        }
    })

    vm.$watch('user', function (newval, oldval) {
        this.getReport();
    })

    vm.$watch('daterange', function (newval, oldval) {
        this.getReport();
    })

     vm.$watch('conversionDate', function (newval, oldval) {
        this.getReport();
    })

    Vue.filter('total', function (list, key1) {
        return list.reduce(function(total, item) {
            return total + item[key1]
        }, 0)
    })

      Vue.filter('percentage', function (list, key1, key2) {
       var leads =  list.reduce(function(total, item) {
            return total + item[key1]
        }, 0);
        var converted =  list.reduce(function(total, item) {
            return total + item['converted']
        }, 0);
       var perc = (converted/leads*100).toFixed(2);
        return perc;
    })
</script> 
<script type="text/javascript">
$(document).ready(function() 
{
    $( "#downloadCSV" ).bind( "click", function() 
    {
        var csv_value = $('.tab-pane.active').find('small').remove();
        
        $( "td" ).each(function() {
            t = $(this).text();
            t = t.replace(/[\r\n]+/g, '');
            $(this).text(t);
            //console.log(t);
        });      
        

        $('.tab-pane.active').find('small').remove();        
        var csv_value = $('.tab-pane.active').find('table').table2CSV({
                delivery: 'value'
            });
        console.log(csv_value);
        downloadFile('creconversion.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
        $("#csv_text").val(csv_value);
        location.reload();
    });

    function downloadFile(fileName, urlData){
        var aLink = document.createElement('a');
        var evt = document.createEvent("HTMLEvents");
        evt.initEvent("click");
        aLink.download = fileName;
        aLink.href = urlData ;
        aLink.dispatchEvent(evt);
    }

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

     $('#conversionDate').daterangepicker(
    {   
        singleDatePicker: true,
        showDropdowns: true,
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
    $('#conversionDate').on('apply.daterangepicker', function(ev, picker) 
    {   
        $('#conversionDate').trigger('change'); 
    });
});
</script>                   
</div>