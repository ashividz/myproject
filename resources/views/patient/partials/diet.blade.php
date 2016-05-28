@extends('patient.index')

@section('top')
<div class="panel panel-default" id="top">
    <div class="panel-heading">
    </div>
    <div class="panel-body">
        <form id="form" v-on:submit.prevent="savePatient">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td width="50%">
                            <label class="col-md-4"> Preferred Time : </label> 
                            <input name="trial_plan" value="@{{ patient.suit.trial_plan }}">
                        </td>
                        <td>
                            <label class="col-md-4"> Special Food Remarks : </label> 
                            @{{ patient.special_food_remark }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="col-md-4"> Suit : </label>
                            <textarea name="suit" style='display:inline;'>@{{ patient.suit.suit }}</textarea>
                        </td>
                        <td>
                            <label class="col-md-4"> Not Suit : </label> 
                            <textarea name="not_suit">@{{ patient.suit.not_suit }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="col-md-4"> Deviation : </label> 
                            <textarea name="deviation">@{{ patient.suit.deviation }}</textarea>
                        </td>
                        <td>
                            <label class="col-md-4"> Remark : </label> 
                            <textarea name="remark">@{{ patient.suit.remark }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="btn btn-primary" disabled="@{{ loading }}">
                                <i class="fa fa-spinner fa-spin" v-show="loading"></i>
                                Save
                            </button>
                        </td>
                        <td>
                            <a href="/patient/@{{ patient.id }}/recipes" class="btn btn-success">Recipes</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
<script>
    //var tab = require('vue-strap').tab;
    Vue.http.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
    Vue.http.options.emulateJSON = true;
    Vue.http.options.emulateHTTP = true;

    new Vue({
        el: '#top',

        data: {
            loading: false,
            patient: []
        },

        ready: function(){
            this.getPatient({{ $patient->id }});
        },

        methods: {
            getPatient(id) {
                this.$http.get("/api/getPatient", {
                    'id': id
                })
                .success(function(data){
                    this.patient = data;
                }).bind(this);
            },

            savePatient() {
                this.loading = true;
                this.ajaxRequest = true;
                var formData= $('#form').serialize();

                this.$http.post("/patient/{{ $patient->id }}/suit", formData)
                .success(function(data){
                    this.loading = false;
                    console.log(data);
                }).bind(this);
            }
        }
    })
</script> 
@endsection

@section('main')

@endsection

