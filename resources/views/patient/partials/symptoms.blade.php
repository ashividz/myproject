@extends('patient.index')

@section('top')
<div class="container-fluid" id="fabController">
    <div class="panel panel-default">
        
        <div class="panel-body">
       
        <div class='row'>
         
          <div class='col-md-6'>
              <h4>SYMPTOMATIC IMPROVEMENTS:</h4>
              <table class='table-bordered edittable' width='100%'>
                    <thead><tr><th>Parameters </th><th>Initial </th><th>Final </th></tr></thead>
                    <tr><td>Energy Level</td><td>
                      <select class="editfield" v-model="patient.initialSymptom.energy_level">
                        <option>Low</option>
                        <option>Average</option>
                        <option>Good</option>
                        <option>High</option>
                      </select>
                    </td><td>
                    <select class="editfield" v-model="patient.lastSymptom.energy_level">
                        <option>Low</option>
                        <option>Average</option>
                        <option>Good</option>
                        <option>High</option>
                      </select>
                    </td></tr>
                    <tr><td>Constipation (cm)</td><td>
                       <select class="editfield" v-model="patient.initialSymptom.constipation">
                        <option>Yes</option>
                        <option>No</option>
                        <option>Sometimes</option>
                       </select>
                    </td><td>
                       <select class="editfield" v-model="patient.lastSymptom.constipation">
                        <option>Yes</option>
                        <option>No</option>
                        <option>Sometimes</option>
                      </select>
                    </td></tr>
                    <tr><td>Gas</td><td>
                    <select class="editfield" v-model="patient.initialSymptom.gas">
                        <option>Yes</option>
                        <option>No</option>
                        <option>Sometimes</option>
                       </select>
                    </td><td>
                      <select class="editfield" v-model="patient.lastSymptom.gas">
                        <option>Yes</option>
                        <option>No</option>
                        <option>Sometimes</option>
                      </select>
                    </td></tr>
                    <tr><td>Acidity</td><td>
                    <select class="editfield" v-model="patient.initialSymptom.acidity">
                        <option>Yes</option>
                        <option>No</option>
                        <option>Sometimes</option>
                       </select>
                    </td><td>
                      <select class="editfield" v-model="patient.lastSymptom.acidity">
                        <option>Yes</option>
                        <option>No</option>
                        <option>Sometimes</option>
                      </select>
                    </td></tr>
                    <tr><td>Water Retention (cm)</td><td>
                      <select class="editfield" v-model="patient.initialSymptom.water_retention">
                        <option>Yes</option>
                        <option>No</option>
                        <option>Sometimes</option>
                       </select>
                    </td><td>
                      <select class="editfield" v-model="patient.lastSymptom.water_retention">
                        <option>Yes</option>
                        <option>No</option>
                        <option>Sometimes</option>
                      </select>
                    </td></tr>
                    <tr><td>Joint Pains</td><td>
                       <select class="editfield" v-model="patient.initialSymptom.joint_pain">
                          <option selected>Mild</option>
                          <option>Sharp</option>
                          <option>Severe</option>
                          <option>Dull</option>
                          <option>No</option>
                       </select>
                    </td><td>
                      <select class="editfield" v-model="patient.lastSymptom.joint_pain">
                          <option selected>Mild</option>
                          <option>Sharp</option>
                          <option>Severe</option>
                          <option>Dull</option>
                          <option>Sometimes</option>
                          <option>No</option>
                       </select>
                    </td></tr>
                    <tr><td>Emotional Eating</td><td>
                   
                      <select class="editfield" v-model="patient.initialSymptom.emotional_eating">
                        <option>Yes</option>
                        <option>No</option>
                        <option>Sometimes</option>
                      </select>
                    </td><td>
                     <select class="editfield" v-model="patient.lastSymptom.emotional_eating">
                        <option>Yes</option>
                        <option>No</option>
                        <option>Sometimes</option>
                      </select></td></tr>
                    <tr><td>Sugar/Food Craving</td><td>
                    
                     <select class="editfield" v-model="patient.initialSymptom.sugar_food_craving">
                        <option>Yes</option>
                        <option>No</option>
                        <option>Sometimes</option>
                      </select>
                    </td><td>
                    
                     <select class="editfield" v-model="patient.lastSymptom.sugar_food_craving">
                        <option>Yes</option>
                        <option>No</option>
                        <option>Sometimes</option>
                      </select></td></tr>
                    <tr><td>Headache</td><td>
                   
                    <select class="editfield" v-model="patient.initialSymptom.headache">
                          <option selected>Mild</option>
                          <option>Sharp</option>
                          <option>Severe</option>
                          <option>Dull</option>
                          <option>Sometimes</option>
                          <option>No</option>
                       </select>
                    </td><td>
                    
                     <select class="editfield" v-model="patient.lastSymptom.headache">
                          <option selected>Mild</option>
                          <option>Sharp</option>
                          <option>Severe</option>
                          <option>Dull</option>
                          <option>Sometimes</option>
                          <option>No</option>
                       </select>
                    </td></tr>
                    <tr><td>Backache</td><td>
                  
                    <select class="editfield" v-model="patient.initialSymptom.backache">
                          <option selected>Mild</option>
                          <option>Sharp</option>
                          <option>Severe</option>
                          <option>Dull</option>
                          <option>Sometimes</option>
                          <option>No</option>
                       </select>
                    </td><td>
                    
                     <select class="editfield" v-model="patient.lastSymptom.backache">
                          <option selected>Mild</option>
                          <option>Sharp</option>
                          <option>Severe</option>
                          <option>Dull</option>
                          <option>Sometimes</option>
                          <option>No</option>
                       </select>
                    </td></tr>
                    <tr><td>General Feeling</td><td>
                    
                    <select class="editfield" v-model="patient.initialSymptom.general_feeling">
                          <option selected>Good</option>
                          <option>Sad</option>
                          <option>Happy</option>
                          <option>Tense</option>
                         
                       </select>
                    </td><td>
                    
                    <select class="editfield" v-model="patient.lastSymptom.general_feeling">
                          <option selected>Good</option>
                          <option>Sad</option>
                          <option>Happy</option>
                          <option>Tense</option>
                         
                       </select>
                    </td></tr>
                </table>
              
              <p class='editsave_bar'>
              <a class='savelink' @click="saveSymptoms">Save Symptoms</a></p>
            </div>
          </div>
      


     
    </div>
</div>
</div>


@{{methods}}
@include('partials.modal')
<script>

new Vue({

    el: '#fabController',
     mixins: [ VueFocus.mixin ],
    data: {
        //loading: false,
        patient: '',
        editSymptoms: false,
        editMeasurements: false,
     },

    ready: function(){
        $.isLoading( "hide" );
        this.getPatientFab();
    },

    methods: {

        toggleEditSymptoms() {
            this.editSymptoms = !this.editSymptoms;
        },

        toggleEditMeasurements() {
            this.editMeasurements = !this.editMeasurements;
        },

        

        saveMeasurements() {

             this.$http.post("/patient/editMeasurements/{{$patient->id}}", {
                initialHeight: this.patient.initialMeasurement.height,
                initialWeight: this.patient.initialWeight.weight,
                initialArm: this.patient.initialMeasurement.arms,
                initialChest: this.patient.initialMeasurement.chest,
                initialWaist: this.patient.initialMeasurement.waist,
                initialAbdomen: this.patient.initialMeasurement.abdomen,
                initialHips: this.patient.initialMeasurement.hips,
                initialThighs: this.patient.initialMeasurement.thighs,

                lastHeight: this.patient.lastMeasurement.height,
                lastWeight: this.patient.lastWeight.weight,
                lastArm: this.patient.lastMeasurement.arms,
                lastChest: this.patient.lastMeasurement.chest,
                lastWaist: this.patient.lastMeasurement.waist,
                lastAbdomen: this.patient.lastMeasurement.abdomen,
                lastHips: this.patient.lastMeasurement.hips,
                lastThighs: this.patient.lastMeasurement.thighs,

            }).success(function(data){
                
                toastr.success('Measurements Updated', 'Success!');
                this.getPatientFab;
            }).error(function(errors) {
                this.toastErrors(errors);
            }).bind(this);
            this.editMeasurements = false;
        },

          saveSymptoms() {
            this.$http.post("/patient/editSymptoms/{{$patient->id}}", {
                initial_energy_level: this.patient.initialSymptom.energy_level,
                //initial_skin: this.patient.initialSymptom.skin,
                initial_constipation: this.patient.initialSymptom.constipation,
                initial_gas: this.patient.initialSymptom.gas,
                initial_acidity: this.patient.initialSymptom.acidity,
                initial_water_retention: this.patient.initialSymptom.water_retention,
                initial_joint_pain: this.patient.initialSymptom.joint_pain,
                initial_emotional_eating: this.patient.initialSymptom.emotional_eating,
                initial_sugar_food_craving: this.patient.initialSymptom.sugar_food_craving,
                initial_headache: this.patient.initialSymptom.headache,
                initial_backache: this.patient.initialSymptom.backache,
                initial_general_feeling: this.patient.initialSymptom.general_feeling,
                

                last_energy_level: this.patient.lastSymptom.energy_level,
                //last_skin: this.patient.lastSymptom.skin,
                last_constipation: this.patient.lastSymptom.constipation,
                last_gas: this.patient.lastSymptom.gas,
                last_acidity: this.patient.lastSymptom.acidity,
                last_water_retention: this.patient.lastSymptom.water_retention,
                last_joint_pain: this.patient.lastSymptom.joint_pain,
                last_emotional_eating: this.patient.lastSymptom.emotional_eating,
                last_sugar_food_craving: this.patient.lastSymptom.sugar_food_craving,
                last_headache: this.patient.lastSymptom.headache,
                last_backache: this.patient.lastSymptom.backache,
                last_general_feeling: this.patient.lastSymptom.general_feeling,
                

            }).success(function(data){
                
                toastr.success('Symptoms Updated', 'Success!');
                this.getPatientFab;
            }).error(function(errors) {
                this.toastErrors(errors);
            }).bind(this);
            this.editSymptoms = false;
        },


       

        getPatientFab() {
            this.$http.get("/patient/getFabData", {
                patient_id: {{$patient->id}}
            })
            .success(function(data){
                 this.patient = data;
                 //this.diet_dates = this.patient.diet_dates.split("$");
                $.isLoading( "hide" );
            }).bind(this);
        }
    }
})

Vue.filter('max', function (list, key) {
    if (list.length == 0) {
        return 0;
    }
    return Math.max.apply(Math,list.map(function(o){return o.pivot.discount;}))
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
<style type="text/css">
.panel-info {
    border-color: #ddd;
}
.panel-info>.panel-heading {
    background-color: #fff; 
    border-color: #ddd;
    color: #111;
}
.panel-info>.panel-heading.benefitCart, .panel-info>.panel-body.benefitCart, .panel-info>.panel-body.benefitCart .table-striped > tbody > tr:nth-child(2n+1)
{
    background: #e0ebeb;
   background: #ffe6cc;
}
.table-bordered td, .table-bordered th
{
    padding: 7px;
}
a.deletebtn
{
    cursor: pointer;
}
a.savelink
{

  border: 1px solid #b3d9ff;
  padding: 3px 8px;
  display: inline-block;
  color: #00264d;
  background: #e6f2ff;
  border-radius: 2px;
  -webkit-box-shadow: 0px 1px 5px 0px rgba(0,0,0,0.25);
  -moz-box-shadow: 0px 1px 5px 0px rgba(0,0,0,0.25);
  box-shadow: 0px 1px 5px 0px rgba(0,0,0,0.25);
  margin-left: 10px;
  cursor: pointer;
}
.editsave_bar
{
  margin-top: 5px;
  margin-bottom: 20px;
}
.editfield
{
width: 100%;
}
.editfield
{
  border: 1px solid #ddd;
  padding: 5px;
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
.eatingtips
{
  margin: 5px 0px;
  border-bottom: 1px solid #ddd;
  padding-bottom: 3px;
}
p.herbslist
{
  padding: 0px 30px;
}
p.herbslist li
{
  padding: 5px 0px;
}
a.diet_datelnk
{
  display: block;
  background: #e6f2ff;
  color: #00264d;
  border: 1px solid #b3d9ff;

 
  text-align: left;
  padding: 7px 10px;
  cursor: pointer;

  -webkit-box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.25);
  -moz-box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.25);
   box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.25);
   margin-bottom: 5px;
}
a.diet_datelnk:hover, a.diet_datelnk.active_date
{
  border: 1px solid #80bfff;
  background: #cce6ff;
}
a.diet_datelnk.active_date
{
  border: 1px solid #80bfff;
  background: #b3daff;
  border: 1px solid #404e6c;
  background: #4b5c81;
  background: #80c1ff;
  border: 1px solid #4da6ff;
  color: #1c2330;

}
.col-md-2.datebar
{
padding-top: 10px;
padding-bottom: 10px;
padding-left: 2px !mportant;
padding-right: 2px !mportant;

-webkit-box-shadow: 8px 8px 15px -8px rgba(0,0,0,0.35);
-moz-box-shadow: 8px 8px 15px -8px rgba(0,0,0,0.35);
box-shadow: 8px 8px 15px -8px rgba(0,0,0,0.35);
}
#form-diet textarea
{
  width: 17em;
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
</style>
@endsection