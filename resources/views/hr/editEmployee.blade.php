<div class="container-fluid">
	<div class="row" id="employee">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Edit</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" value="{{ $employee->id }}">

						<div class="form-group">
							<label class="col-md-4 control-label">Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{ $employee->name }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Employee No</label>
							<div class="col-md-6">
								<input type="test" class="form-control" name="emp_no" value="{{ $employee->emp_no }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ $employee->email }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Mobile</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="mobile" value="{{ $employee->mobile }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Date of Birth</label>
							<div class="col-md-6">
								<input type="date" class="form-control" name="dob" value="{{ $employee->dob }}">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Update
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>

            <div class="">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th></th>
                            </tr>
                        </thead>
                            <tbody>
                                <tr v-for="title in employee.titles">
                                    <td>
                                        @{{ title.name }}
                                    </td>
                                    <td>
                                        @{{ title.pivot.start_date }}
                                    </td>
                                    <td>
                                        <div v-if="!title.pivot.end_date">
                                            <input type="date" v-model="end_date" class="form-control"">
                                        </div>
                                        @{{ title.pivot.end_date }}
                                    </td>
                                    <td>
                                        <div v-if="!title.pivot.end_date">
                                            <button class="btn btn-primary" @click="updateTitle(title)"><i class="fa fa-save"></i></button>
                                            Exit from company
                                            <input type="checkbox" v-model="exit" checked>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>                                
                        </table>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="3">
                                        <h5>Add Title</h35>
                                    </th>
                                </tr>
                            </thead>
                            <tr>
                                <td>
                                    <select class="form-control" v-model="title.title_id">
                                        <option value="">--select--</option>
                                        <option v-for="title in titles" :value="title.id">@{{ title.name }}</option>
                                    </select>
                                </td>
                                <td><input type="date" v-model="title.start_date" class="form-control"></td>
                                <td>
                                    <button class="btn btn-success" @click="store   ">Save</button>
                                </td>
                            </tr>
                        </table>
                    </div>                    
                </div>
            </div>
		</div>
	</div>
</div>
<script>
    new Vue({
        el: '#employee',

        data: {
            employee: {!! $employee !!},
            titles : [],
            exit : '',
            end_date : '',
            title : {
                title_id : '',
                start_date : '',
                end_date : ''
            }
        },

        ready: function(){
            this.getTitles()
        },

        methods: {

            getTitles() {
                this.$http.get("/api/getTitles")
                .success(function(data){
                    this.titles = data;
                }).bind(this);
            },

            updateTitle(title) {
                this.$http.patch("/employee/{{ $employee->id }}/title/"+title.id, { 
                    end_date: this.end_date,
                    exit: this.exit 
                })
                .success(function(data){
                    toastr.success("Title updated", "Success!")
                }).bind(this); 
            },

            store() {
                this.$http.post("/employee/{{ $employee->id }}/title", this.title )
                .then((response) => {
                    toastr.success("Title stored", "Success!")
                    this.employee.titles = response.data
                }, (response) => {
                    // error callback
                }).bind(this);
            }
        }
    })
</script>