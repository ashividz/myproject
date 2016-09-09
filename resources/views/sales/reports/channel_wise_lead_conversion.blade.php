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
                <a href="/channelWiseLeadConversionDownload"  class="btn btn-primary pull-right" style="margin-bottom:2em;">download</a>
                 
                
            </span>

              <input type="text" id="daterange" v-model="daterange" size="25" readonly/>
             
              <span style='margin-left: 10px'>
             Converted on <input type="text" id="conversionDate" v-model="conversionDate" size="20" readonly/>
             </span>
              <span style='margin-left: 10px'>
              Created <input type='radio' v-model="createAssign" value='created' /> Assigned <input type='radio' v-model="createAssign" value='assigned' />
              </span>
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
                                <th>Converted</th>
                                <th>Conversion %</th>
                                <th>New </th>
                                <th>Reference</th>
                                <th>Rejoin </th>
                                <th>Upgrade </th>
                                <th>Corporate </th>
                                <th>Events </th>
                                <th>Unknown </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cre in cres">
                                <td>
                                    @{{ cre.name }}
                                </td>
                                <td>
                                    <a href="/cre/@{{ cre.id }}/leads/includeChurned?start_date=@{{ encodeURIComponent(start_date) }}&end_date=@{{ encodeURIComponent(end_date) }}" class="dropdown-toggle" data-toggle="modal" data-target="#modal">
                                        <b>@{{ cre.leads }}</b>
                                    </a>
                                </td>
                                <td>
                                    <a href="/cre/@{{ cre.id }}/leads/converted/?start_date=@{{ encodeURIComponent(start_date) }}&end_date=@{{ encodeURIComponent(end_date) }}&conversion_start_date=@{{ encodeURIComponent(conversion_start_date) }}&conversion_end_date=@{{ encodeURIComponent(conversion_end_date) }}&create_assign=@{{ encodeURIComponent(createAssign) }}" class="dropdown-toggle" data-toggle="modal" data-target="#modal">
                                        <b>@{{ cre.converted }}</b>
                                    </a>
                                </td>
                                <td>
                                    <em>(@{{ cre.leads > 0 ? (cre.converted/cre.leads*100).toFixed(2) : 0 }}%)</em>
                                   
                                </td>
                                <td>
                                    <span class='chnl_data'>@{{ cre.channels[0] }} </span>
                                    <span class='chnl_data'>@{{ cre.channel_conversions[0] }}</span>
                                    <em>(@{{ cre.channels[0] > 0 ?(cre.channel_conversions[0]/cre.channels[0]*100).toFixed(2): 0 }}%)</em>
                                                                        
                                </td>
                                  <td>
                                    <span class='chnl_data'>@{{ cre.channels[1] }}</span>
                                    <span class='chnl_data'>@{{ cre.channel_conversions[1] }}</span>
                                    <em>(@{{ cre.channels[1] > 0 ?(cre.channel_conversions[1]/cre.channels[1]*100).toFixed(2): 0 }}%)</em>

                                </td>
                                  <td>
                                    <span class='chnl_data'>@{{ cre.channels[2] }} </span>
                                    <span class='chnl_data'>@{{ cre.channel_conversions[2] }}</span>
                                    <em>(@{{ cre.channels[2] > 0 ?(cre.channel_conversions[2]/cre.channels[2]*100).toFixed(2): 0 }}%)</em>
                                </td>
                                  <td>
                                    <span class='chnl_data'>@{{ cre.channels[3] }} </span>
                                    <span class='chnl_data'>@{{ cre.channel_conversions[3] }}</span>
                                    <em>(@{{ cre.channels[3] > 0 ?(cre.channel_conversions[3]/cre.channels[3]*100).toFixed(2): 0 }}%)</em>
                                </td>
                                 <td>
                                    <span class='chnl_data'>@{{ cre.channels[4] }} </span>
                                    <span class='chnl_data'>@{{ cre.channel_conversions[4] }}</span>
                                    <em>(@{{ cre.channels[4] > 0 ?(cre.channel_conversions[4]/cre.channels[4]*100).toFixed(2): 0 }}%)</em>
                                </td>
                                <td>
                                    <span class='chnl_data'>@{{ cre.channels[5] }} </span>
                                    <span class='chnl_data'>@{{ cre.channel_conversions[5] }}</span>
                                    <em>(@{{ cre.channels[5] > 0 ?(cre.channel_conversions[5]/cre.channels[5]*100).toFixed(2): 0 }}%)</em>
                                </td>
                                 <td>
                                    <span class='chnl_data'>@{{ cre.channels[6] }} </span>
                                    <span class='chnl_data'>@{{ cre.channel_conversions[6] }}</span>
                                    <em>(@{{ cre.channels[6] > 0 ?(cre.channel_conversions[6]/cre.channels[6]*100).toFixed(2): 0 }}%)</em>
                                </td>
                        </tr>
                        
                            <tr>
                                <td>Total</td>
                                <td>@{{ cres | total 'leads' }}</td>
                                <td>@{{ cres | total 'converted' }}</td>
                                <td>(@{{ cres | percentage 'leads' }}%)</td>

                                <td>
                                <table class='chnl_ttl'><tr>
                                    <td>@{{ channelLeads[0] }}</td>
                                    <td>@{{ channelConverted[0] }}</td>
                                    <td>(@{{ channelPerc[0] }}%)</td>
                                    </tr></table>
                                </td>

                                <td>
                                <table class='chnl_ttl'><tr>
                                    <td>@{{ channelLeads[1] }}</td>
                                    <td>@{{ channelConverted[1] }}</td>
                                    <td>(@{{ channelPerc[1] }}%)</td>
                                    </tr></table>
                                </td>

                                <td>
                                <table class='chnl_ttl'><tr>
                                    <td>@{{ channelLeads[2] }}</td>
                                    <td>@{{ channelConverted[2] }}</td>
                                    <td>(@{{ channelPerc[2] }}%)</td>
                                    </tr></table>
                                </td>

                                <td>
                                <table class='chnl_ttl'><tr>
                                    <td>@{{ channelLeads[3] }}</td>
                                    <td>@{{ channelConverted[3] }}</td>
                                    <td>(@{{ channelPerc[3] }}%)</td>
                                    </tr></table>
                                </td>

                                <td>
                                <table class='chnl_ttl'><tr>
                                    <td>@{{ channelLeads[4] }}</td>
                                    <td>@{{ channelConverted[4] }}</td>
                                    <td>(@{{ channelPerc[4] }}%)</td>
                                    </tr></table>
                                </td>

                                <td>
                                <table class='chnl_ttl'><tr>
                                    <td>@{{ channelLeads[5] }}</td>
                                    <td>@{{ channelConverted[5] }}</td>
                                    <td>(@{{ channelPerc[5] }}%)</td>
                                    </tr></table>
                                </td>

                                <td>
                                <table class='chnl_ttl'><tr>
                                    <td>@{{ channelLeads[6] }}</td>
                                    <td>@{{ channelConverted[6] }}</td>
                                    <td>(@{{ channelPerc[6] }}%)</td>
                                    </tr></table>
                                </td>


                            </tr>
                        </tbody>
                       
                         <thead>
                            <tr>
                                <th></th>
                                <th>Leads</th> 
                                <th>Converted</th>
                                <th>Conversion %</th>
                                <th>New </th>
                                <th>Reference</th>
                                <th>Rejoin </th>
                                <th>Upgrade </th>
                                <th>Corporate </th>
                                <th>Events </th>
                                <th>Unknown </th>
                            </tr>
                        </thead>
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
            conversion_start_date: '',
            conversion_end_date: '',
            conversionDate: '{{ Carbon::now()->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
            createAssign: 'created',
            channelLeads: [],
            channelConverted: [],
            channelPerc: [],

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
                $.getJSON("/api/channelWiseLeadConversion", {
                    'user_id' : this.user,
                    start_date: this.start_date, 
                    end_date: this.end_date,
                    conversion_start_date: this.conversion_start_date,
                    conversion_end_date: this.conversion_end_date,
                    create_assign: this.createAssign
                     }, function(cres){
                    this.cres = cres;
                    this.calcTotal();
                    this.loading = false;
                }.bind(this));

              

            },

             calcTotal() {
                this.channelLeads = [];
                this.channelConverted = [];
                this.channelPerc = [];

              for (var i = 0; i < 7; i++) 
                {
                    this.channelLeads.push(0);
                    this.channelConverted.push(0);
                    this.channelPerc.push(0);
                }

             
              for (var j = 0; j < this.cres.length; j++) 
              {
                for (var i = 0; i < 7; i++) 
                    {   
                        
                        this.channelLeads[i] = this.channelLeads[i] + this.cres[j].channels[i];
                        this.channelConverted[i] = this.channelConverted[i] + this.cres[j].channel_conversions[i];
                        this.channelPerc[i] = (this.channelConverted[i]/this.channelLeads[i]*100).toFixed(2);
                        
                    }
                   
                    
            }

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

            conversion_start_date() {
                var range = this.conversionDate.split(" - ");
                return moment(range[0]).format('YYYY-MM-DD') + ' 0:0:0';
            },

            conversion_end_date() {
                var range = this.conversionDate.split(" - ");
                return moment(range[1]).format('YYYY-MM-DD') + ' 23:59:59';
            }
        }
    })

    vm.$watch('user', function (newval, oldval) {
        this.getReport();
    })

    vm.$watch('daterange', function (newval, oldval) {
        this.conversionDate = this.daterange;
        this.getReport();
    })

    vm.$watch('conversionDate', function (newval, oldval) {
        this.getReport();
    })

    vm.$watch('createAssign', function (newval, oldval) {
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
    $( "#downloadCSV" ).bind( "click", function(e) 
    {
        e.preventDefault();
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

   $('#modal').on('hidden.bs.modal', function () {
       $('#modal tbody').html("");//location.reload();
    })

});
</script>

<style>
.chnl_data
{

    padding-right: 10px;
}
.chnl_ttl td
{
    border-right: 1px solid #999;
    padding: 0px 3px;
}

.chnl_ttl td:last-child
{
   font-size: 11px;
}
</style>                   
</div>