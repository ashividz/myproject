<div class="container" id="app">
	<div id="loading" v-show="loading">
		<img src="/images/loading.gif">
	</div>
	<div class="panel">
		<div class="panel-heading">
			<h4>No product purchase</h4>
		</div>
		<div class="panel-body">			
			<div>
		    	<label for="limit">show upto</label>	
				<select v-model="limit" name="limit" @change="getPatients">
					<option v-for="r in ranges" :value="r">@{{r}}</option>
				</select>
			</div>
			<div style="margin-top:20px;">
				<button class="btn btn-primary" @click="decreaseOffset">Previous</button>
				<span>Displaying @{{offset}} to @{{offset + limit}} </span>
				<button class="btn btn-primary pull-right" @click="increaseOffset">Next</button>
			</div>


			<div>
				<table class="table table-bordered" id="table">
					<thead>
						<tr>
							<th>#</th>
							<th>Lead id</th>
							<th>Name</th>
							<th>Doctor</th>
							<th>Nutritionist</th>
							<th>start Date</th>
							<th>end Date</th>				
						</tr>
					</thead>
					<tbody>
					
						<tr v-for="patient in patients">
							<td>@{{$index+1}}</td>
							<td>
							<a href='/lead/@{{patient.lead_id}}/cart' target='_blank'> @{{patient.lead.id}}</a></td>
							<td>
							<a href='/lead/@{{patient.lead_id}}/viewDetails' target='_blank'> @{{patient.lead.name}}</a></td>
							<td> @{{patient.doctor}} </td>
							<td> @{{patient.nutritionist}} </td>
							<td> @{{patient.start_date}} </td>
							<td> @{{patient.end_date}} </td>
						</tr>
					
					</tbody>
				</table>
			</div>
			<div style="margin-top:20px;">
				<button class="btn btn-primary" @click="decreaseOffset">Previous</button>
				<span>Displaying @{{offset}} to @{{offset + limit}} </span>
				<button class="btn btn-primary pull-right" @click="increaseOffset">Next</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var data =  {
		patients : [],	
		loading  : false,
		offset   : 0,
		limit    : 100,
		ranges   : [20,40,80,100,500,1000],
	};

new Vue({
	el : '#app',
	data : data,
	methods :{
		getPatients : function(){
			this.loading =  true;
			this.$http.get('/api/getNoPurchases',{limit:this.limit,offset:this.offset}).then((response) => {
	      		this.patients      = response.data;
	      		this.loading = false;
	      	});	      	
		},
		decreaseOffset : function() {
			this.offset = this.offset - this.limit;
			if ( this.offset < 0 )
				this.offset = 0;
			this.getPatients();
		},
		increaseOffset : function() {
			this.offset = this.offset + this.limit;			
			if ( this.offset < 0 )
				this.offset = 0;
			this.getPatients();
		},		
		
	},
	ready() {
			this.getPatients();
	},	
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