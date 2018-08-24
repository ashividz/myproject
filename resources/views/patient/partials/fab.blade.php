@extends('patient.index')

@section('top')
<div class="container-fluid" id="fabController">
    <div class="panel panel-default">
        
        <div class="panel-body">
        <div class='panel panel-default fabheader'>
          <div class='container'>
              
             <div class ='panel-body'>
                <p>Dear @{{patient.lead.name}} </p>
                <p>Thank you for choosing “Dr. Shikha’s Nutrihealth” </p>
                <p>We hope that you thoroughly enjoyed the experience and we are able to enlighten/ educate you with the concept of Ayurveda & Body type based Nutrition advisory and plans. We hope that we are able to establish trust with you and add you in our satisfied clientele.
                <p>We are constantly trying to improve the service we offer and we would be grateful if you could take a couple of minutes to send a feedback with your thoughts. </p>
                <p>We work really hard to provide the best experience to our customers and are always looking for ways to improve. If you have any feedback please reply to this email directly. We read every email we get and appreciate your help in improving our customer experience.</p>
                <p>As your program is completed, we would like to share the final analysis brochure with you mentioned below.</p>
                <p>I look forward to hearing from you, and hopefully welcoming you back to Dr. Shikha’s NutriHealth family.</p><br></div>
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
             <div class='col-md-6'>
                 <label>HEIGHT: @{{patient.lead.height}}</label><br>
                 <label>INITIAL WEIGHT: <input class="editfield" v-model="patient.initialWeight.weight" @keyup.13="weightSave" /> </label><br>
                 <label>FINAL WEIGHT: <input class="editfield" v-model="patient.lastWeight.weight" @keyup.13="weightSave"  /> </label><br>
                 <label>@{{patient.weightDiff}} </label>
             </div>
          </div>
        </div>
        <div class='row'>
          <div class='col-md-6'>
            <h4>MEASUREMENTS:</h4>
            
              <table class='table-bordered edittable' width='100%'>
                <thead><tr><th>Parameters </th><th>Initial </th><th>Final </th></tr></thead>
                  <tr><td>Height (cm)</td><td>@{{patient.lead.height}}</td><td>@{{patient.lead.height}}</td></tr>
                  <tr><td>Weight (kg)</td><td>@{{patient.initialWeight.weight}}</td><td>@{{patient.lastWeight.weight}}</td></tr>
                  <tr><td>Arm (cm)</td><td>@{{patient.initialMeasurement.arms}}</td><td>@{{patient.lastMeasurement.arms}}</td></tr>
                  <tr><td>Chest (cm)</td><td>@{{patient.initialMeasurement.chest}}</td><td>@{{patient.lastMeasurement.chest}}</td></tr>
                  <tr><td>Waist (cm)</td><td>@{{patient.initialMeasurement.waist}}</td><td>@{{patient.lastMeasurement.waist}}</td></tr>
                  <tr><td>Abdomen (cm)</td><td>@{{patient.initialMeasurement.abdomen}}</td><td>@{{patient.lastMeasurement.abdomen}}</td></tr>
                  <tr><td>Hips (cm)</td><td>@{{patient.initialMeasurement.hips}}</td><td>@{{patient.lastMeasurement.hips}}</td></tr>
                  <tr><td>Thighs (cm)</td><td>@{{patient.initialMeasurement.thighs}}</td><td>@{{patient.lastMeasurement.thighs}}</td></tr>
              </table>
              <br>
              <h4>MEDICAL IMPROVEMENTS</h4>
              <table class='table-bordered' width='100%'>
                <thead><tr><th>Parameters </th><th>Initial </th><th>Status </th><th>Final </th><th>Status </th></tr></thead>
                 <tr><td colspan="5" align="left" style="padding-left: 100px;font-weight: bold;background-color:#DDDDDD;">BLOOD HAEMATOLOGY</td></tr>
                  <tr><td>Hemoglobin </td><td>@{{patient.initialMedical.hemoglobin}}</td><td>@{{patient.initialMedical.hemoglobin_staus}}</td<td>@{{patient.lastMedical.hemoglobin}}</td><td>@{{patient.lastMedical.hemoglobin_staus}}</td</tr>

                  <tr><td>MCV </td><td>@{{patient.initialMedical.mcv}}</td><td>@{{patient.initialMedical.mcv_status}}</td><td>@{{patient.lastMedical.mcv}}</td><td>@{{patient.lastMedical.mcv_status}}</td></tr>
                  
                  <tr><td>MCH </td><td>@{{patient.initialMedical.mch}}</td><td>@{{patient.initialMedical.mch_status}}</td><td>@{{patient.lastMedical.mch}}</td><td>@{{patient.lastMedical.mch_status}}</td></tr>
                  
                  <tr><td>MCHC </td><td>@{{patient.initialMedical.mchc}}</td><td>@{{patient.initialMedical.mchc_stauts}}</td><td>@{{patient.lastMedical.mchc}}</td><td>@{{patient.lastMedical.mchc_stauts}}</td></tr>

                  <tr><td>ESR </td><td>@{{patient.initialMedical.esr}}</td><td>@{{patient.initialMedical.esr_status}}</td><td>@{{patient.lastMedical.esr}}</td><td>@{{patient.lastMedical.esr_status}}</td></tr>
                 
                  <tr><td colspan="5" align="left" style="padding-left: 100px;font-weight: bold;background-color:#DDDDDD;">BLOOD GLUCOSE</td></tr>
                  <tr><td>FASTING </td><td>@{{patient.initialMedical.fasting}}</td><td>@{{patient.initialMedical.fasting_status}}</td><td>@{{patient.lastMedical.fasting}}</td><td>@{{patient.lastMedical.fasting_status}}</td></tr>
               
                  <tr><td>PP </td><td>@{{patient.initialMedical.pp}}</td><td>@{{patient.initialMedical.pp_status}}</td><td>@{{patient.lastMedical.pp}}</td><td>@{{patient.lastMedical.pp_status}}</td></tr>
                  
                  <tr><td colspan="5" align="left" style="padding-left: 100px;font-weight: bold;background-color:#DDDDDD;">LIVER FUNCTION TESTS</td></tr>
                  <tr><td>S.G.O.T </td><td>@{{patient.initialMedical.sgot}}</td><td>@{{patient.initialMedical.sgot_status}}</td><td>@{{patient.lastMedical.sgot}}</td><td>@{{patient.lastMedical.sgot_status}}</td></tr>
                  
                  <tr><td>S.G.P.T </td><td>@{{patient.initialMedical.sgpt}}</td><td>@{{patient.initialMedical.sgpt_status}}</td><td>@{{patient.lastMedical.sgpt}}</td><td>@{{patient.lastMedical.sgpt_status}}</td></tr>

                  <tr><td>ALKALINE PHOSPHATASE </td><td>@{{patient.initialMedical.alkaline}}</td><td>@{{patient.initialMedical.alkaline_status}}</td><td>@{{patient.lastMedical.alkaline}}</td><td>@{{patient.lastMedical.alkaline_status}}</td></tr>

                  <tr><td>G.G.T.P </td><td>@{{patient.initialMedical.ggtp}}</td><td>@{{patient.initialMedical.ggtp_status}}</td><td>@{{patient.lastMedical.ggtp}}</td><td>@{{patient.lastMedical.ggtp_status}}</td></tr>
                  
                  <tr><td colspan="5" align="left" style="padding-left: 100px;font-weight: bold;background-color:#DDDDDD;">THYROID PROFILE</td></tr>
                  <tr><td>T3 </td><td>@{{patient.initialMedical.t3}}</td><td>@{{patient.initialMedical.t3_status}}</td><td>@{{patient.lastMedical.t3}}</td><td>@{{patient.lastMedical.t3_status}}</td></tr>

                  <tr><td>T4 </td><td>@{{patient.initialMedical.t4}}</td><td>@{{patient.initialMedical.t4_status}}</td><td>@{{patient.lastMedical.t4}}</td><td>@{{patient.lastMedical.t4_status}}</td></tr>
                  
                  <tr><td>TSH </td><td>@{{patient.initialMedical.tsh}}</td><td>@{{patient.initialMedical.tsh_status}}</td><td>@{{patient.lastMedical.tsh}}</td><td>@{{patient.lastMedical.tsh_status}}</td></tr>

                  <tr><td colspan="5" align="left" style="padding-left: 100px;font-weight: bold;background-color:#DDDDDD;">LIPID PROFILE</td></tr>
                  <tr><td>Total cholesterol </td><td>@{{patient.initialMedical.total}}</td><td>@{{patient.initialMedical.total_status}}</td><td>@{{patient.lastMedical.total}}</td><td>@{{patient.lastMedical.total_status}}</td></tr>
                  
                  <tr><td>HDL-Chol </td><td>@{{patient.initialMedical.hdl}}</td><td>@{{patient.initialMedical.hdl_status}}</td><td>@{{patient.lastMedical.hdl}}</td><td>@{{patient.lastMedical.hdl_status}}</td></tr>

                  <tr><td>LDL-Chol </td><td>@{{patient.initialMedical.ldl}}</td><td>@{{patient.initialMedical.ldl_status}}</td><td>@{{patient.lastMedical.ldl}}</td><td>@{{patient.lastMedical.ldl_status}}</td></tr>

                  <tr><td>VLDL-Chol </td><td>@{{patient.initialMedical.vldl}}</td><td>@{{patient.initialMedical.vldl_staus}}</td><td>@{{patient.lastMedical.vldl}}</td><td>@{{patient.lastMedical.vldl_staus}}</td></tr>
                  
                  <tr><td>TRIGLYCERIDES </td><td>@{{patient.initialMedical.tri}}</td><td>@{{patient.initialMedical.tri_status}}</td><td>@{{patient.lastMedical.tri}}</td><td>@{{patient.lastMedical.tri_status}}</td></tr>
                
                  <tr><td colspan="5" align="left" style="padding-left: 100px;font-weight: bold;background-color:#DDDDDD;">KIDNEY FUNCTION TESTS</td></tr>
                  <tr><td>UREA </td><td>@{{patient.initialMedical.urea}}</td><td>@{{patient.initialMedical.urea_status}}</td><td>@{{patient.lastMedical.urea}}</td><td>@{{patient.lastMedical.urea_status}}</td></tr>
               
                  <tr><td>SERUM,CREATININE </td><td>@{{patient.initialMedical.serum}}</td><td>@{{patient.initialMedical.serum_status}}</td><td>@{{patient.lastMedical.serum}}</td><td>@{{patient.lastMedical.serum_status}}</td></tr>
                  
                  <tr><td>URIC ACID </td><td>@{{patient.initialMedical.uric}}</td><td>@{{patient.initialMedical.uric_status}}</td><td>@{{patient.lastMedical.uric}}</td><td>@{{patient.lastMedical.uric_status}}</td></tr>
                  
                  <tr><td>TOTAL CALCIUM </td><td>@{{patient.initialMedical.totall}}</td><td>@{{patient.initialMedical.totall_status}}</td><td>@{{patient.lastMedical.totall}}</td><td>@{{patient.lastMedical.totall_status}}</td></tr>
               
                  <tr><td>TOTAL PROTEINS </td><td>@{{patient.initialMedical.proteins}}</td><td>@{{patient.initialMedical.proteins_status}}</td><td>@{{patient.lastMedical.proteins}}</td><td>@{{patient.lastMedical.proteins_status}}</td></tr>

                  <tr><td>SERUM ALBUMN/GLOBULIN </td><td>@{{patient.initialMedical.seruma}}</td><td>@{{patient.initialMedical.seruma_status}}</td><td>@{{patient.lastMedical.seruma}}</td><td>@{{patient.lastMedical.seruma_status}}</td></tr>
            
                  <tr><td colspan="5" align="left" style="padding-left: 100px;font-weight: bold;background-color:#DDDDDD;">IMMUNO ASSAYS</td></tr>
                  <tr><td>PROLACTIN (Fasting) </td><td>@{{patient.initialMedical.prolactin_f}}</td><td>@{{patient.initialMedical.prolactin_f_status}}</td><td>@{{patient.lastMedical.prolactin_f}}</td><td>@{{patient.lastMedical.prolactin_f_status}}</td></tr>
               
                  <tr><td>INSULIN (Fasting) </td><td>@{{patient.initialMedical.insulin_f}}</td><td>@{{patient.initialMedical.insulin_f_status}}</td><td>@{{patient.lastMedical.insulin_f}}</td><td>@{{patient.lastMedical.insulin_f_status}}</td></tr>
                
                  <tr><td>INSULIN (PP) </td><td>@{{patient.initialMedical.insulin_p}}</td><td>@{{patient.initialMedical.insulin_p_status}}</td><td>@{{patient.lastMedical.insulin_p}}</td><td>@{{patient.lastMedical.insulin_p_status}}</td></tr>

                  <tr><td colspan="5" align="left" style="padding-left: 100px;font-weight: bold;background-color:#DDDDDD;"> OTHERS</td></tr>
                  <tr><td>Vitamin D </td><td>@{{patient.initialMedical.vitamin_d}}</td><td>@{{patient.initialMedical.vitamin_d_status}}</td><td>@{{patient.lastMedical.vitamin_d}}</td><td>@{{patient.lastMedical.vitamin_d_status}}</td></tr>
               
                  <tr><td>Vitamin B12</td><td>@{{patient.initialMedical.vitamin_b12}}</td><td>@{{patient.initialMedical.vitamin_b12_status}}</td><td>@{{patient.lastMedical.vitamin_b12}}</td><td>@{{patient.lastMedical.vitamin_b12_status}}</td></tr>
                
                  <tr><td>HBA1C </td><td>@{{patient.initialMedical.hba1c}}</td><td>@{{patient.initialMedical.hba1c_status}}</td><td>@{{patient.lastMedical.hba1c}}</td><td>@{{patient.lastMedical.hba1c_status}}</td></tr>
                 
              </table>
          </div>
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

                 </table>
                <br>
                <div class=' panel panel-default'>
                  <div class='panel-body'>
                    <h4>PRAKRITI</h4>
                    <p style='font-weight: bold'>@{{patient.prakriti.first_dominant_name}} Constitution</p>
                    <p>@{{{patient.prakriti.first_dominant_text}}}</p>
  

                  </div>
                </div>

                <div class=' panel panel-default'>
                  <div class='panel-body'>
                      <h4>FOODS/PLANS WHICH SUIT YOU</h4>
                      <p>@{{patient.suit.suit}}</p>

                      <h4>FOODS/PLANS WHICH DO NOT SUIT YOU</h4>
                      <p>@{{patient.suit.not_suit}}</p>
                  </div>
                </div>

                <div class=' panel panel-default'>
                    <div class='panel-body'>
                      <h4>EATING OUT TIPS</h4>
                      <div class="col-md-12">
                          <input type="textarea" v-model="eatingTipField" class="form-control" placeholder="Eating Tips *" required @keyup.13="store" />
                          <br>
                      </div>
                      <div class="form-group" style="margin-left:175px">
                        <button class="btn btn-primary" name="eat" @click="store"> Save</button>
                      </div>
                      <div class="col-md-12 " v-for="eatingtipx in eatingTips">
                        <editable-field2 :eatingtipx.sync="eatingtipx"></editable-field2>
                      </div>
                    </div>
                </div>
            </div>
          </div>
          
          <div class="panel panel-default">
          <div class="panel-heading tags">
            <h4>7 DAYS DIETS</h4>
          </div>

          <div class="panel-body" style='padding-top: 0px'>
            <div class='row'>
              <div class="col-md-2 datebar">
                <div style='padding: 0px' class="col-md-12 " v-for="(index, diet_date) in patient.diet_dates">
                  <a class='diet_datelnk ' v-bind:class="[(diet_date==active_date)?'active_date':'']" @click="setDiet(index)">@{{diet_date}}</a>
                </div>
              </div>
              <div class="col-md-10" id="form-diet">
                <table class="table table-bordered blocked">
                  <tbody>
                    <tr>
                      <td>
                        <div><label>Breakfast:</label></div>
                        <textarea v-model="breakfast" name="breakfast" id="breakfast" class="diet-area" placeholder="Breakfast"> </textarea>
                        <div id="breakfast-list" class="diet-list"></div>
                      </td>
                      <td>
                        <div><label>Mid Morning:</label></div>
                        <textarea v-model="midMorning" name="mid_morning" id="mid_morning" class="diet-area" placeholder="Mid Morning"> </textarea>
                        <div id="mid_morning-list" class="diet-list"></div>
                      </td>
                      <td>
                        <div><label>Lunch:</label></div>
                        <textarea v-model="lunch" name="lunch" id="lunch" class="diet-area" placeholder="Lunch"> </textarea>
                        <div id="lunch-list" class="diet-list"></div>
                      </td>
                     
                    </tr>
                    <tr>
                     <td>
                        <div><label>Evening:</label></div>
                        <textarea v-model="evening" name="evening" id="evening" class="diet-area" placeholder="Evening"> </textarea>
                        <div id="evening-list" class="diet-list"></div>
                      </td>
                      <td>
                        <div><label>Dinner:</label></div>
                        <textarea v-model="dinner" name="dinner" id="dinner" class="diet-area" placeholder="Dinner"> </textarea>
                        <div id="dinner-list" class="diet-list"></div>
                      </td>
                    
                      <td>
                        <div><label>Remarks/Deviations:</label></div>
                        <textarea v-model="remarks" placeholder="Remarks/Deviations" name="rem_dev" id="remark"> </textarea>
                      </td>
                     
                    </tr>
                    <tr> 
                       <td>
                        <div style="height 10em;width:25em;overflow:scroll;">
                          @{{{patient.herb_names}}}           
                        </div>
                      </td>
                       <td>
                          <div class="form-group">
                            <button class="btn btn-primary" name="email" @click="saveDiet"> Save Diet</button>
                          </div>
                      </td>
                     </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <a class="btn btn-primary" target='_blank' href="/patient/fab/preview/{{$patient->id}}" >Preview Mail</a>
          <!--<button @click="sendMail" type="button" class="btn btn-primary">Send Mail</button>-->
           <a class="btn btn-primary" @click="sendFabMail"  >Send Mail</a>
 
        </div>
    </div>
</div>
</div>
<template id="editable-field2">
   <div class=" col-md-12 eatingtips">
        <div v-show="edit">
            <span class="col-xs-10 col-md-10" style='padding: 0px'>
                <input type="text" v-model="eatingtipx.name" style='width: 100% !important' class="editable-input" @keyup.13="update"/> 
            </span>
        </div>
        <div v-else>
            <span class="col-xs-10 col-md-10 eating_tip" @click="toggleEdit">@{{ eatingtipx.name }}</span>
        </div>
        <span class="col-xs-2 col-md-2">
            <small class="pull-right hidden-xs" v-if="eatingtipx.deleted_at">
                <em>@{{ eatingtipx.deleted_at }}</em>
            </small>
            <div class="pull-right">
                <a class='deletebtn'  @click="toggleDelete"><i class="fa fa-remove red"></i></a>
            </div>
        </span>
    </div>
</template>

@{{methods}}
@include('partials.modal')
<script>
Vue.component('editableField3', {
    mixins: [ VueFocus.mixin ],
    template: '#editable-field3',
    props: ['item'],
    data: function() {
        return {
            edit: false
        }
    },
   
    methods: {
             update(){

            this.$http.patch("/patient/eatingTip/" + this.eatingtipx.id, {
                name: this.eatingtipx.name
            }).success(function(data){
                this.$parent.eatingtipx = data;
                toastr.success('Tips updated', 'Success!')
            }).error(function(errors) {
                this.$parent.toastErrors(errors);
            }).bind(this);
            this.edit = false;

        },

         toggleEdit() {
            this.edit = true;
        },

        toggleDelete() {
            
            this.$http.post("/patient/eatingTip/" + this.eatingtipx.id + "/delete")
            .success(function(data){
                this.$parent.eatingTips = data;
                this.$parent.toastDelete(this.eatingtipx.deleted_at);
            }).bind(this);
        }
    }
})

Vue.component('editableField2', {
    mixins: [ VueFocus.mixin ],
    template: '#editable-field2',
    props: ['eatingtipx'],
    data: function() {
        return {
            edit: false
        }
    },
     ready: function() {
        console.log(this.eatingtipx);
    },
    methods: {
             update(){

            this.$http.patch("/patient/eatingTip/" + this.eatingtipx.id, {
                name: this.eatingtipx.name
            }).success(function(data){
                this.$parent.eatingtipx = data;
                toastr.success('Tips updated', 'Success!')
            }).error(function(errors) {
                this.$parent.toastErrors(errors);
            }).bind(this);
            this.edit = false;

        },

         toggleEdit() {
            this.edit = true;
        },

        toggleDelete() {
            
            this.$http.post("/patient/eatingTip/" + this.eatingtipx.id + "/delete")
            .success(function(data){
                this.$parent.eatingTips = data;
                this.$parent.toastDelete(this.eatingtipx.deleted_at);
            }).bind(this);
        }
    }
})
new Vue({
    el: '#fabController',

    data: {
        //loading: false,
        patient: '',
        eatingTips: [],
       
        diets: [],
        eatingTipField: '',
        editSymptoms: false,
        editMeasurements: false,
        diet_dates: [],
        active_date: '',

        breakfast: '',
        midMorning: '',
        lunch: '',
        evening: '',
        dinner: '',
        remarks: ''
        
    },

    ready: function(){
        this.getEatingTips(),
        this.getPatientFab(),
        this.setDietDates();
    },

    methods: {

        toggleEditSymptoms() {
            this.editSymptoms = !this.editSymptoms;
        },

        toggleEditMeasurements() {
            this.editMeasurements = !this.editMeasurements;
        },

        setDiet(i) {
          this.active_date = this.patient.diet_dates[i];
          this.$http.get("/patient/getFabDiet", {
                patient_id: {{$patient->id}},
                diet_date: this.active_date
            }).success(function(data){
                this.breakfast = data.breakfast;
                this.midMorning = data.mid_morning;
                this.lunch = data.lunch;
                this.evening = data.evening;
                this.dinner = data.dinner;
                this.remarks = data.rem_dev;

             }).bind(this);  
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

        sendFabMail(){
            this.$http.get("/patient/sendFabMails/{{$patient->id}}", {}).success(function(data){
                toastr.success('Mail Sent!', 'Success!');
                this.getPatientFab;
            }).error(function(errors) {
                this.toastErrors(errors);
            }).bind(this);
        },

          saveSymptoms() {
            this.$http.post("/patient/editSymptoms/{{$patient->id}}", {
                initial_energy_level: this.patient.initialSymptom.energy_level,
                initial_skin: this.patient.initialSymptom.skin,
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
                last_skin: this.patient.lastSymptom.skin,
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

        saveDiet() {
           this.$http.post("/patient/saveFABDiet/{{$patient->id}}", {
                diet_date: this.active_date,
                breakfast: this.breakfast,
                mid_morning: this.midMorning,
                
                lunch: this.lunch,
                evening: this.evening,
                dinner: this.dinner,
                rem_dev: this.remarks
                 }).success(function(data){
                
                toastr.success('Diet Updated', 'Success!');
                
            }).error(function(errors) {
                this.toastErrors(errors);
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

        getPatientFab() {
            this.$http.get("/patient/getFabData", {
                patient_id: {{$patient->id}}
            })
            .success(function(data){
                 this.patient = data;
                 //this.diet_dates = this.patient.diet_dates.split("$");
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
        },

        weightSave() {
           this.$http.post("/patient/weightUpdate", {
                    initial_weight: this.patient.initialWeight.weight,
                    final_weight: this.patient.lastWeight.weight,
                    patient_id: {{$patient->id}}
                })
                .success(function(data){
                    toastr.success('Weight Updated', 'Success!');
                    this.getPatientFab();
                })
                .error(function(errors) {
                    this.toastErrors(errors);
                })
                .bind(this);
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
  background: #f5f5f5;
  color: #555;
  border: 1px solid #eee;

 
  text-align: left;
  padding: 7px 10px;
  cursor: pointer;

  -webkit-box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.25);
  -moz-box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.25);
   box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.25);
   margin-bottom: 5px;
}
a.diet_datelnk:hover
{
  border: 1px solid #b3d9ff;
  background: #cce6ff;
}
a.diet_datelnk.active_date
{
  border: 1px solid #99ccff;
  background: #b3daff;
  border: 1px solid #404e6c;
  background: #4b5c81;
  background: #99ceff;
  border: 1px solid #80c1ff;
  color: #1c2330;

}
.col-md-2.datebar
{
padding-top: 10px;
padding-bottom: 10px;
padding-left: 2px !mportant;
padding-right: 2px !mportant;

-webkit-box-shadow: 8px 8px 8px -8px rgba(0,0,0,0.35);
-moz-box-shadow: 8px 8px 8px -8px rgba(0,0,0,0.35);
box-shadow: 8px 8px 8px -8px rgba(0,0,0,0.35);
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
.editfield
{
  width: 100px;
  background: #c4dbed;
  border: 1px solid #9dc4e1;
}
</style>
@endsection