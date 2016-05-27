    <div class="container" id="employees">

        <div class="panel panel-default">
            <div class="panel-heading">
            </div>
            <div class="panel-body">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Contact No</th>
                            <th>Roles</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="employee in employees">
                            <td>
                                <a href="/admin/employee/@{{ employee.id }}">
                                    @{{ employee.name }}
                                    (@{{ employee.emp_no }})
                                </a>
                            </td>
                            <td>
                                <div v-if="employee.user">
                                    @{{ employee.user.username }}
                                   (@{{ employee.user.id }})
                                </div>                                   
                            </td>
                            <td>
                                <div v-if="employee.user.email">
                                    <b>Personal : </b> @{{ employee.user.email }} 
                                </div>
                                <div v-if="employee.email">
                                    <b>Official : </b> @{{ employee.email }} 
                                </div>
                            </td>
                            <td>
                                <div v-if="employee.user.mobile">
                                    <b>Personal : </b> @{{ employee.user.mobile }} 
                                </div>
                                <div v-if="employee.mobile">
                                    <b>Official : </b> @{{ employee.mobile }} 
                                </div>
                            </td>
                            <td>
                                <li v-for="role in employee.user.roles">
                                    @{{ role.display_name }}
                                </li>
                                <span class="pull-right" v-if="employee.user">
                                    <a href="/admin/user/@{{ employee.user.id }}/role" target="_blank">
                                        <i class="fa fa-edit"></i>
                                    </a>                                    
                                </span>
                            </td>
                            <td style="text-align:center">
                                <div v-if="employee.user">
                                    <input type="checkbox" name="" v-bind:checked="!employee.user.deleted_at" v-on:click="deleteUser(employee)">
                                    <div v-if="employee.user.deleted_at">
                                        @{{ employee.user.deleted_at | format_date }}
                                    </div>
                                </div>                                    
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@include('partials.modal')
<style type="text/css">
    
    table.table {
        font-size: 12px;
    }
    
</style>
<script>
    Vue.http.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';

    var vm1 = new Vue({
        el: '#employees',

        data: {
            loading: false,
            employees: []
        },

        ready: function(){
            this.getEmployees();
        },

        methods: {

            getEmployees() {
                this.loading = true;
                this.$http.get("/api/getEmployees").success(function(data){
                    this.employees = data;
                    this.loading = false;
                }).bind(this);
            },

            deleteUser(employee) {
                this.$http.post("/api/toggleDeleteUser", { 'id' : employee.user.id })
                .success(function(data){
                    employee.user.deleted_at = data;
                }).bind(this);
            }
        }
    })

    Vue.filter('format_date', function (value) {
        if (value == null) {
            return null;
        }
      return moment(value).format('D MMM hh:mm A');
    })
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