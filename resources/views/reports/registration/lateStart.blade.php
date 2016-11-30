<div class="panel panel-default" id="app">
	<div id="loading" v-show="loading">
		<img src="/images/loading.gif">
	</div>
	<div class="panel-heading">
  		<div class="pull-right">
  			Fee adjust Date :</b> <input type="text" id="daterange" v-model="daterange" size="25" readonly/>
		</div>
		<h4>late start reports</h4>
	</div>	
	<div class="panel-body">
      	<!-- Tab panes -->
      	<div class="tab-content">
        	<div role="tabpanel" class="tab-pane active" id="home">
    	<div>
	    	<label for="limit">show upto</label>	
			<select v-model="limit" name="limit" @change="getLateStartRecords">
				<option v-for="r in ranges" :value="r">@{{r}}</option>
			</select>
		</div>
		<div style="margin-top:20px;">
			<button class="btn btn-primary" @click="decreaseOffset">Previous</button>
			<span>Displaying @{{offset}} to @{{offset + limit}} of @{{totalPatients}}</span>
			<button class="btn btn-primary pull-right" @click="increaseOffset">Next</button>
		</div>
		<div>

				<table id="table" class="table table-bordered">
					<thead>
						<tr>
							<th>SN</th>
							<th>Patient Id</th>
							<th>Name</th>
							<th>Nutritionist</th>							
							<th>fee changes</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="p in patients">
							<td>@{{$index+1}}</td>
							<td><a href="/patient/@{{p.id}}/diet" target="_blank">@{{p.id}}</a></td>
							<td><a href="/lead/@{{p.lead.id}}/viewDetails" target="_blank">@{{p.lead.name}}</td>
							<td>@{{p.nutritionist}}</td>
							<td>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>entry date</th>
											<th>total amount</th>
											<th>cre</th>
											<th>source</th>
											<th>duration</th>
											<th>first diet</th>
											<th>old start date</th>
											<th>new start date</th>
											<th>old end date</th>
											<th>new end date</th>
											<th>days adjusted</th>
											<th>adjusted on</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="f in p.fees">											
												<td>@{{properDate(f.entry_date.date)}}</td>
												<td>@{{f.currency.symbol}}@{{f.total_amount}}</td>
												<td>@{{f.cre}}</td>
												<td>@{{f.source.source_name}}</td>
												<td>@{{f.duration ? f.duration + ' days': f.valid_months+' months'}}</td>
												<td>@{{f.first_diet}}</td>
												<td class="danger">@{{f.log.old_value.start_date}}</td>
												<td class="success">@{{f.log.new_value.start_date}}</td>
												<td class="danger">@{{f.log.old_value.end_date}}</td>
												<td class="success">@{{f.log.new_value.end_date}}</td>
												<td>@{{diffInDays(f.log.old_value.start_date,f.log.new_value.start_date)}}</td>
												<td>@{{f.log.created_at}}</td>
										</tr>
									</tbody>
								</table>								
							</td>
						</tr>							
					</tbody>
				</table>				
			</div>
			<div>
				<button class="btn btn-primary" @click="decreaseOffset">Previous</button>
				<span>Displaying @{{offset}} to @{{offset + limit}} of @{{totalPatients}}</span>
				<button class="btn btn-primary pull-right" @click="increaseOffset">Next</button>
			</div>
		</div>		
					
		</div>
	</div>
</div>
<script type="text/javascript">

var data =  {
		patients : [],	
		daterange: '{{ Carbon::now()->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
		loading  : false,
		limit    : 20,
		offset   : 0, 
		ranges   : [20,40,80,100,500,1000],
		totalPatients : 0,
	};

new Vue({
	el : '#app',
	data : data,
	methods :{
		getLateStartRecords : function(){
			this.loading =  true;
			this.$http.get('/api/getLateStart',{start_date:this.start_date,end_date:this.end_date,limit:this.limit,offset:this.offset}).then((response) => {
	      		this.patients      = response.data.patients;
	      		this.totalPatients = response.data.total;
	      		this.loading = false;
	      	});	      	
		},
		decreaseOffset : function() {
			this.offset = this.offset - this.limit;
			if ( this.offset < 0 )
				this.offset = 0;
			this.getLateStartRecords();
		},
		increaseOffset : function() {
			this.offset = this.offset + this.limit;
			if ( this.offset >= this.totalPatients )
				this.offset = this.totalPatients - this.limit;
			if ( this.offset < 0 )
				this.offset = 0;
			this.getLateStartRecords();
		},		
		properDate : function($arg) {
			return moment($arg).format('YYYY-MM-DD HH:mm:ss');
		},
		diffInDays : function($start,$end){
			$start = moment($start);
			$end   = moment($end);
			return $end.diff($start,'days');
		},
	},
	ready() {
			this.getLateStartRecords();		
			this.$watch('daterange', function (newval, oldval) {
            	this.getLateStartRecords();
        	});
	},
	computed: {
        start_date() {
            var range = this.daterange.split(" - ");
            return moment(range[0]).format('YYYY-MM-DD') + ' 00:00:00';
        },

        end_date() {
            var range = this.daterange.split(" - ");
            return moment(range[1]).format('YYYY-MM-DD') + ' 23:59:59';
        }
    }
});


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
<style type="text/css">
#loading
{
    position: fixed;
    margin-top: 100px;
    margin-left: 45%;
    z-index:2892;
    opacity:1;
}
</style>