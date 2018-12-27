@extends('patient.index')

@section('top')
<div class="container-fluid" id="fabController">
    <div class="panel panel-default">
        
        <div class="panel-body">
        <div class='panel panel-default fabheader'>
          <div class='container'>
             <div class='col-md-6'>
                 <label>NAME: @{{patient.lead.name}}</label> <br>
                 <label>PRAKRITI: @{{patient.prakriti.first_dominant_name}}</label> <br>
                 <label>BLOOD GROUP @{{ patient.blood_type.name}} @{{patient.rh_factor.code}}</label>  <br>
                 <label>DURATION:
                     <span v-if="patient.lastFee.duration">
                          @{{ (patient.lastFee.duration) > 1 ? patient.lastFee.duration + " days" : patient.lastFee.duration +" day" }}
                     </span>
                     <span v-else>
                          @{{ (patient.lastFee.valid_months) > 1 ? patient.lastFee.valid_months + " months" : patient.lastFee.valid_months + " month" }}
                     </span>
                  </label>
                
             </div>
          </div>
        </div>
        <div class='row'>
          <div class='col-md-6'>
              <h4>SYMPTOMATIC IMPROVEMENTS:</h4>
             
                <table  class='table-bordered edittable' width='100%'>
                    <thead><tr><th>Parameters </th><th>Initial </th><th>Final </th></tr></thead>
                    <tr><td>Energy Level</td><td>@{{patient.initialSymptom.energy_level}}</td><td>@{{patient.lastSymptom.energy_level}}</td></tr>
                    <tr><td>Constipation </td><td>@{{patient.initialSymptom.constipation}}</td><td>@{{patient.lastSymptom.constipation}}</td></tr>
                    <tr><td>Gas</td><td>@{{patient.initialSymptom.gas}}</td><td>@{{patient.lastSymptom.gas}}</td></tr>
                    <tr><td>Acidity</td><td>@{{patient.initialSymptom.acidity}}</td><td>@{{patient.lastSymptom.acidity}}</td></tr>
                    <tr><td>Water Retention</td><td>@{{patient.initialSymptom.water_retention}}</td><td>@{{patient.lastSymptom.water_retention}}</td></tr>
                    <tr><td>Joint Pains</td><td>@{{patient.initialSymptom.joint_pain}}</td><td>@{{patient.lastSymptom.joint_pain}}</td></tr>
                    <tr><td>Emotional Eating</td><td>@{{patient.initialSymptom.emotional_eating}}</td><td>@{{patient.lastSymptom.emotional_eating}}</td></tr>
                    <tr><td>Sugar/Food Craving</td><td>@{{patient.initialSymptom.sugar_food_craving}}</td><td>@{{patient.lastSymptom.sugar_food_craving}}</td></tr>
                    <tr><td>Headache</td><td>@{{patient.initialSymptom.headache}}</td><td>@{{patient.lastSymptom.headache}}</td></tr>
                    <tr><td>Backache</td><td>@{{patient.initialSymptom.backache}}</td><td>@{{patient.lastSymptom.backache}}</td></tr>
                    <tr><td>General Feeling</td><td>@{{patient.initialSymptom.general_feeling}}</td><td>@{{patient.lastSymptom.general_feeling}}</td></tr>
                    <tr><td>Sleep Pattern</td><td>@{{patient.initialSymptom.sleep_pattern}}</td><td>@{{patient.lastSymptom.sleep_pattern}}</td></tr>
                 </table>
                <br>
          </div>
        </div>

        <!--<div class=' panel panel-default'>
            <div class='panel-body'>
                <h4>Remarks</h4>
                <div class="col-md-12">
                    <input type="textarea" v-model="eatingTipField" class="form-control" placeholder="Remarks *" required @keyup.13="store" />
                    <br>
                </div>
                <div class="form-group" style="margin-left:175px">
                  <button class="btn btn-primary" name="eat" @click="store"> Save</button>
                </div>
                <div class="col-md-12 " v-for="eatingtipx in eatingTips">
                  <editable-field2 :eatingtipx.sync="eatingtipx"></editable-field2>
                </div>
            </div>
        </div>  --> 
        <div class="panel panel-default">
           <a class="btn btn-primary" @click="sendFabMail">Send Mail</a>
        </div>
    </div>
</div>
</div>

<style type="text/css">
.table-bordered td, .table-bordered th
{
    padding: 7px;
}
.edittable td
{
  width: 30%;
}
.fabheader
{
  background: #c4dbed;
  color: #333;
  padding-top: 15px;
  padding-bottom: 10px;
  border: 1px solid #9dc4e1;
}

input[type="text"].editable-input
{
  border: 1px solid #9fabc6;
}
.eating_tip
{
border: 1px solid #b3d7ff;
padding: 4px 6px;
}
.editfield
{
  width: 100px;
  background: #c4dbed;
  border: 1px solid #9dc4e1;
}

</style>

<script>
new Vue({
    el: '#fabController',

    data: {
        //loading: false,
        patient: '',
        //eatingTips: [],
        eatingTipField: '',
        editSymptoms: false,
        editMeasurements: false,
        diet_dates: [],
        active_date: ''
        
    },

    ready: function(){
        this.getEatingTips(),
        this.getPatientFab();
    },

    methods: {
        sendFabMail(){
            this.$http.get("/patient/SendSymptomaticFabMail/{{$patient->id}}", {}).success(function(data){
                toastr.success('Mail Sent!', 'Success!');
                this.getPatientFab;
            }).error(function(errors) {
                this.toastErrors(errors);
            }).bind(this);
        },
        getPatientFab() {
            $.isLoading({ text: "Loading" });
            this.$http.get("/patient/getFabData", {
                patient_id: {{$patient->id}}
            })
            .success(function(data){
                 this.patient = data;
                 $.isLoading( "hide" );
            }).bind(this);
        },
        getEatingTips() {
            $.isLoading({ text: "Loading" });
            this.$http.get("/patient/eatingTips", {
                patient_id: {{$patient->id}}
            }).success(function(data){
                this.eatingTips = data;

                $.isLoading( "hide" );
            }).bind(this);
        },
        store() {
            this.$http.post("/patient/add/eatingTip", {
                    name: this.eatingTipField,
                    patient_id: {{$patient->id}}
                })
                .success(function(data){
                    this.eatingTips = data;
                    this.eatingTipField = '';
                    
                })
                .error(function(errors) {
                    this.toastErrors(errors);
                })
                .bind(this);
        }
    }
})
</script>
@endsection