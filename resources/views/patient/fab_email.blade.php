
<div>
    <div>
        
        <div>
        <div>
          <div>
          <table width='100%'>
              <tr><td colspan='2' style='text-align: center'>
                 <img width='300' src='https://nutrihealthsystems.com/wp-content/uploads/2016/03/logo.png' />
                 <h4>FINAL ANALYSIS BROCHURE</h4></td>
               </tr>
               <tr><td width='50%'>
                 <br/><br/>
                 <label>NAME: {{$patient->lead->name}}</label> <br>
                 <label>PRAKRITI: {{$patient->prakriti->first_dominant_name}}</label> <br>
                 <label>BLOOD GROUP: {{$patient->blood_type->name}} {{$patient->rh_factor->code}}</label>  <br>
                 <label>DURATION:
                     <span v-if="patient.lastFee.duration">
                          {{ ($patient->lastFee->duration) > 1 ? $patient->lastFee->duration . " days" : $patient->lastFee->duration ." day" }}
                     </span>
                     <span v-else>
                          {{ ($patient->lastFee->valid_months) >= 1 ? $patient->lastFee->valid_months . " months" : "" }}
                     </span>
                  </label>
               </td>
               <td width='50%'>
                 <label>HEIGHT: {{$patient->lead->height}}</label><br>
                 <label>INITIAL WEIGHT: {{$patient->initialWeight->weight}}</label><br>
                 <label>FINAL WEIGHT: {{$patient->lastWeight->weight}}</label><br>
                 <label>{{$patient->weightDiff}} </label>
               </td>
               </tr>

             </table>
          </div>
        </div>
       
        <br /><br />
        <table width='100%' border='1' bordercolor='grey' cellspacing='0'>
            <thead>
            <tr><td colspan='3' style='padding: 10px;text-align: left;background: #ccc'>
              <h4 style='margin: 0px' >MEASUREMENTS:</h4>
            </td></tr>
            <tr><th style='padding: 10px;text-align: left'>Parameters </th><th style='padding: 10px;text-align: left'>Initial </th><th style='padding: 10px;text-align: left'>Final </th></tr></thead>
              <tr><td style='padding: 10px'>Height (cm)</td><td style='padding: 10px'>{{$patient->lead->height}}</td><td style='padding: 10px'>{{$patient->lead->height}}</td></tr>
              <tr><td style='padding: 10px'>Weight (kg)</td><td style='padding: 10px'>{{$patient->initialWeight->weight}}</td><td style='padding: 10px'>{{$patient->lastWeight->weight}}</td></tr>
              <tr><td style='padding: 10px'>Arm (cm)</td><td style='padding: 10px'>{{$patient->initialMeasurement->arms}}</td><td style='padding: 10px'>{{$patient->lastMeasurement->arms}}</td></tr>
              <tr><td style='padding: 10px'>Chest (cm)</td><td style='padding: 10px'>{{$patient->initialMeasurement->chest}}</td><td style='padding: 10px'>{{$patient->lastMeasurement->chest}}</td></tr>
              <tr><td style='padding: 10px'>Waist (cm)</td><td style='padding: 10px'>{{$patient->initialMeasurement->waist}}</td><td style='padding: 10px'>{{$patient->lastMeasurement->waist}}</td></tr>
              <tr><td style='padding: 10px'>Abdomen (cm)</td><td style='padding: 10px'>{{$patient->initialMeasurement->abdomen}}</td><td style='padding: 10px'>{{$patient->lastMeasurement->abdomen}}</td></tr>
              <tr><td style='padding: 10px'>Hips (cm)</td><td style='padding: 10px'>{{$patient->initialMeasurement->hips}}</td><td style='padding: 10px'>{{$patient->lastMeasurement->hips}}</td></tr>
              <tr><td style='padding: 10px'>Thighs (cm)</td><td style='padding: 10px'>{{$patient->initialMeasurement->thighs}}</td><td style='padding: 10px'>{{$patient->lastMeasurement->thighs}}</td></tr>
        </table>
        <br /><br />
        <table width='100%' border='1' bordercolor='grey' cellspacing='0'>
         <tr><td colspan='3' style='padding: 10px;text-align: left;background: #ccc'>
              <h4 style='margin: 0px' >SYMPTOMATIC IMPROVEMENTS:</h4>
            </td></tr>
                <tr><th style='padding: 10px;text-align: left'>Parameters </th><th style='padding: 10px;text-align: left'>Initial </th><th style='padding: 10px;text-align: left'>Final </th></tr>
                <tr><td style='padding: 10px'>Energy Level</td><td style='padding: 10px'>{{$patient->initialSymptom->energy_level}}</td><td style='padding: 10px'>{{$patient->lastSymptom->energy_level}}</td></tr>
                <tr><td style='padding: 10px'>Skin</td><td style='padding: 10px'>{{$patient->initialSymptom->skin}}</td><td style='padding: 10px'>{{$patient->lastSymptom->skin}}</td></tr>
                <tr><td style='padding: 10px'>Constipation (cm)</td><td style='padding: 10px'>{{$patient->initialSymptom->constipation}}</td><td style='padding: 10px'>{{$patient->lastSymptom->constipation}}</td></tr>
                <tr><td style='padding: 10px'>Gas</td><td style='padding: 10px'>{{$patient->initialSymptom->gas}}</td><td style='padding: 10px'>{{$patient->lastSymptom->gas}}</td></tr>
                <tr><td style='padding: 10px'>Acidity</td><td style='padding: 10px'>{{$patient->initialSymptom->acidity}}</td><td style='padding: 10px'>{{$patient->lastSymptom->acidity}}</td></tr>
                <tr><td style='padding: 10px'>Water Retention (cm)</td><td style='padding: 10px'>{{$patient->initialSymptom->water_retention}}</td><td style='padding: 10px'>{{$patient->lastSymptom->water_retention}}</td></tr>
                <tr><td style='padding: 10px'>Joint Pains</td><td style='padding: 10px'>{{$patient->initialSymptom->joint_pain}}</td><td style='padding: 10px'>{{$patient->lastSymptom->joint_pain}}</td></tr>
                <tr><td style='padding: 10px'>Stress</td><td style='padding: 10px'>{{$patient->initialSymptom->stress}}</td><td style='padding: 10px'>{{$patient->lastSymptom->stress}}</td></tr>
                <tr><td style='padding: 10px'>Emotional Eating</td><td style='padding: 10px'>{{$patient->initialSymptom->emotional_eating}}</td><td style='padding: 10px'>{{$patient->lastSymptom->emotional_eating}}</td></tr>
                <tr><td style='padding: 10px'>Sugar/Food Craving</td><td style='padding: 10px'>{{$patient->initialSymptom->sugar_food_craving}}</td><td style='padding: 10px'>{{$patient->lastSymptom->sugar_food_craving}}</td></tr>
                <tr><td style='padding: 10px'>Headache</td><td style='padding: 10px'>{{$patient->initialSymptom->headache}}</td><td style='padding: 10px'>{{$patient->lastSymptom->headache}}</td></tr>
                <tr><td style='padding: 10px'>Backache</td><td style='padding: 10px'>{{$patient->initialSymptom->backache}}</td><td style='padding: 10px'>{{$patient->lastSymptom->backache}}</td></tr>
                <tr><td style='padding: 10px'>General Feeling</td><td style='padding: 10px'>{{$patient->initialSymptom->general_feeling}}</td><td style='padding: 10px'>{{$patient->lastSymptom->general_feeling}}</td></tr>
        </table>

        <br /><br />
        <table width='100%' border='1' bordercolor='grey' cellspacing='0'>
             <tr><td colspan='5' style='padding: 10px;text-align: left;background: #ccc'>
              <h4 style='margin: 0px' >MEDICAL IMPROVEMENTS:</h4>
            </td></tr>
             <tr><th style='padding: 10px;text-align: left'>Parameters </th><th style='padding: 10px;text-align: left'>Initial </th><th style='padding: 10px;text-align: left'>Status </th><th style='padding: 10px;text-align: left'>Final </th><th style='padding: 10px;text-align: left'>Status </th></tr>
               <tr><td colspan="5" align="left" style="padding: 10px;padding-left: 100px;font-weight: bold;background-color:#DDDDDD;">BLOOD HAEMATOLOGY</td></tr>
                <tr><td style='padding: 10px'>Hemoglobin </td><td style='padding: 10px'>{{$patient->initialMedical->hemoglobin}}</td><td style='padding: 10px'>{{$patient->initialMedical->hemoglobin_staus}}</td<td style='padding: 10px'>{{$patient->lastMedical->hemoglobin}}</td><td style='padding: 10px'>{{$patient->lastMedical->hemoglobin_staus}}</td</tr>

                <tr><td style='padding: 10px'>MCV </td><td style='padding: 10px'>{{$patient->initialMedical->mcv}}</td><td style='padding: 10px'>{{$patient->initialMedical->mcv_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->mcv}}</td><td style='padding: 10px'>{{$patient->lastMedical->mcv_status}}</td></tr>
                
                <tr><td style='padding: 10px'>MCH </td><td style='padding: 10px'>{{$patient->initialMedical->mch}}</td><td style='padding: 10px'>{{$patient->initialMedical->mch_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->mch}}</td><td style='padding: 10px'>{{$patient->lastMedical->mch_status}}</td></tr>
                
                <tr><td style='padding: 10px'>MCHC </td><td style='padding: 10px'>{{$patient->initialMedical->mchc}}</td><td style='padding: 10px'>{{$patient->initialMedical->mchc_stauts}}</td><td style='padding: 10px'>{{$patient->lastMedical->mchc}}</td><td style='padding: 10px'>{{$patient->lastMedical->mchc_stauts}}</td></tr>

                <tr><td style='padding: 10px'>ESR </td><td style='padding: 10px'>{{$patient->initialMedical->esr}}</td><td style='padding: 10px'>{{$patient->initialMedical->esr_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->esr}}</td><td style='padding: 10px'>{{$patient->lastMedical->esr_status}}</td></tr>
               
                <tr><td colspan="5" align="left" style="padding: 10px;padding-left: 100px;font-weight: bold;background-color:#DDDDDD;">BLOOD GLUCOSE</td></tr>
                <tr><td style='padding: 10px'>FASTING </td><td style='padding: 10px'>{{$patient->initialMedical->fasting}}</td><td style='padding: 10px'>{{$patient->initialMedical->fasting_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->fasting}}</td><td style='padding: 10px'>{{$patient->lastMedical->fasting_status}}</td></tr>
             
                <tr><td style='padding: 10px'>PP </td><td style='padding: 10px'>{{$patient->initialMedical->pp}}</td><td style='padding: 10px'>{{$patient->initialMedical->pp_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->pp}}</td><td style='padding: 10px'>{{$patient->lastMedical->pp_status}}</td></tr>
                
                <tr><td colspan="5" align="left" style="padding: 10px;padding-left: 100px;font-weight: bold;background-color:#DDDDDD;">LIVER FUNCTION TESTS</td></tr>
                <tr><td style='padding: 10px'>S.G.O.T </td><td style='padding: 10px'>{{$patient->initialMedical->sgot}}</td><td style='padding: 10px'>{{$patient->initialMedical->sgot_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->sgot}}</td><td style='padding: 10px'>{{$patient->lastMedical->sgot_status}}</td></tr>
                
                <tr><td style='padding: 10px'>S.G.P.T </td><td style='padding: 10px'>{{$patient->initialMedical->sgpt}}</td><td style='padding: 10px'>{{$patient->initialMedical->sgpt_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->sgpt}}</td><td style='padding: 10px'>{{$patient->lastMedical->sgpt_status}}</td></tr>

                <tr><td style='padding: 10px'>ALKALINE PHOSPHATASE </td><td style='padding: 10px'>{{$patient->initialMedical->alkaline}}</td><td style='padding: 10px'>{{$patient->initialMedical->alkaline_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->alkaline}}</td><td style='padding: 10px'>{{$patient->lastMedical->alkaline_status}}</td></tr>

                <tr><td style='padding: 10px'>G.G.T.P </td><td style='padding: 10px'>{{$patient->initialMedical->ggtp}}</td><td style='padding: 10px'>{{$patient->initialMedical->ggtp_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->ggtp}}</td><td style='padding: 10px'>{{$patient->lastMedical->ggtp_status}}</td></tr>
                
                <tr><td colspan="5" align="left" style="padding: 10px;padding-left: 100px;font-weight: bold;background-color:#DDDDDD;">THYROID PROFILE</td></tr>
                <tr><td style='padding: 10px'>T3 </td><td style='padding: 10px'>{{$patient->initialMedical->t3}}</td><td style='padding: 10px'>{{$patient->initialMedical->t3_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->t3}}</td><td style='padding: 10px'>{{$patient->lastMedical->t3_status}}</td></tr>

                <tr><td style='padding: 10px'>T4 </td><td style='padding: 10px'>{{$patient->initialMedical->t4}}</td><td style='padding: 10px'>{{$patient->initialMedical->t4_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->t4}}</td><td style='padding: 10px'>{{$patient->lastMedical->t4_status}}</td></tr>
                
                <tr><td style='padding: 10px'>TSH </td><td style='padding: 10px'>{{$patient->initialMedical->tsh}}</td><td style='padding: 10px'>{{$patient->initialMedical->tsh_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->tsh}}</td><td style='padding: 10px'>{{$patient->lastMedical->tsh_status}}</td></tr>

                <tr><td colspan="5" align="left" style="padding: 10px;padding-left: 100px;font-weight: bold;background-color:#DDDDDD;">LIPID PROFILE</td></tr>
                <tr><td style='padding: 10px'>Total cholesterol </td><td style='padding: 10px'>{{$patient->initialMedical->total}}</td><td style='padding: 10px'>{{$patient->initialMedical->total_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->total}}</td><td style='padding: 10px'>{{$patient->lastMedical->total_status}}</td></tr>
                
                <tr><td style='padding: 10px'>HDL-Chol </td><td style='padding: 10px'>{{$patient->initialMedical->hdl}}</td><td style='padding: 10px'>{{$patient->initialMedical->hdl_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->hdl}}</td><td style='padding: 10px'>{{$patient->lastMedical->hdl_status}}</td></tr>

                <tr><td style='padding: 10px'>LDL-Chol </td><td style='padding: 10px'>{{$patient->initialMedical->ldl}}</td><td style='padding: 10px'>{{$patient->initialMedical->ldl_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->ldl}}</td><td style='padding: 10px'>{{$patient->lastMedical->ldl_status}}</td></tr>

                <tr><td style='padding: 10px'>VLDL-Chol </td><td style='padding: 10px'>{{$patient->initialMedical->vldl}}</td><td style='padding: 10px'>{{$patient->initialMedical->vldl_staus}}</td><td style='padding: 10px'>{{$patient->lastMedical->vldl}}</td><td style='padding: 10px'>{{$patient->lastMedical->vldl_staus}}</td></tr>
                
                <tr><td style='padding: 10px'>TRIGLYCERIDES </td><td style='padding: 10px'>{{$patient->initialMedical->tri}}</td><td style='padding: 10px'>{{$patient->initialMedical->tri_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->tri}}</td><td style='padding: 10px'>{{$patient->lastMedical->tri_status}}</td></tr>
              
                <tr><td colspan="5" align="left" style="padding: 10px;padding-left: 100px;font-weight: bold;background-color:#DDDDDD;">KIDNEY FUNCTION TESTS</td></tr>
                <tr><td style='padding: 10px'>UREA </td><td style='padding: 10px'>{{$patient->initialMedical->urea}}</td><td style='padding: 10px'>{{$patient->initialMedical->urea_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->urea}}</td><td style='padding: 10px'>{{$patient->lastMedical->urea_status}}</td></tr>
             
                <tr><td style='padding: 10px'>SERUM,CREATININE </td><td style='padding: 10px'>{{$patient->initialMedical->serum}}</td><td style='padding: 10px'>{{$patient->initialMedical->serum_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->serum}}</td><td style='padding: 10px'>{{$patient->lastMedical->serum_status}}</td></tr>
                
                <tr><td style='padding: 10px'>URIC ACID </td><td style='padding: 10px'>{{$patient->initialMedical->uric}}</td><td style='padding: 10px'>{{$patient->initialMedical->uric_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->uric}}</td><td style='padding: 10px'>{{$patient->lastMedical->uric_status}}</td></tr>
                
                <tr><td style='padding: 10px'>TOTAL CALCIUM </td><td style='padding: 10px'>{{$patient->initialMedical->totall}}</td><td style='padding: 10px'>{{$patient->initialMedical->totall_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->totall}}</td><td style='padding: 10px'>{{$patient->lastMedical->totall_status}}</td></tr>
             
                <tr><td style='padding: 10px'>TOTAL PROTEINS </td><td style='padding: 10px'>{{$patient->initialMedical->proteins}}</td><td style='padding: 10px'>{{$patient->initialMedical->proteins_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->proteins}}</td><td style='padding: 10px'>{{$patient->lastMedical->proteins_status}}</td></tr>

                <tr><td style='padding: 10px'>SERUM ALBUMN/GLOBULIN </td><td style='padding: 10px'>{{$patient->initialMedical->seruma}}</td><td style='padding: 10px'>{{$patient->initialMedical->seruma_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->seruma}}</td><td style='padding: 10px'>{{$patient->lastMedical->seruma_status}}</td></tr>
          
                <tr><td colspan="5" align="left" style="padding: 10px;padding-left: 100px;font-weight: bold;background-color:#DDDDDD;">IMMUNO ASSAYS</td></tr>
                <tr><td style='padding: 10px'>PROLACTIN (Fasting) </td><td style='padding: 10px'>{{$patient->initialMedical->prolactin_f}}</td><td style='padding: 10px'>{{$patient->initialMedical->prolactin_f_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->prolactin_f}}</td><td style='padding: 10px'>{{$patient->lastMedical->prolactin_f_status}}</td></tr>
             
                <tr><td style='padding: 10px'>INSULIN (Fasting) </td><td style='padding: 10px'>{{$patient->initialMedical->insulin_f}}</td><td style='padding: 10px'>{{$patient->initialMedical->insulin_f_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->insulin_f}}</td><td style='padding: 10px'>{{$patient->lastMedical->insulin_f_status}}</td></tr>
              
                <tr><td style='padding: 10px'>INSULIN (PP) </td><td style='padding: 10px'>{{$patient->initialMedical->insulin_p}}</td><td style='padding: 10px'>{{$patient->initialMedical->insulin_p_status}}</td><td style='padding: 10px'>{{$patient->lastMedical->insulin_p}}</td><td style='padding: 10px'>{{$patient->lastMedical->insulin_p_status}}</td></tr>
               
        </table>

        <div style='margin-top: 15px;border: 1px solid #bbb;border-radius: 4px; padding: 15px'>
            <div class=''>
              <h4>PRAKRITI</h4>
              <p style='font-weight: bold'>{{$patient->prakriti->first_dominant_name}} Constitution</p>
              <p>{!!$patient->prakriti->first_dominant_text!!}</p>
            </div>
          </div>

          <div style='margin-top: 15px;border: 1px solid #bbb;border-radius: 4px; padding: 15px'>
            <div>
                <h4>FOODS/PLANS WHICH SUIT YOU</h4>
                <p>{{$patient->suit->suit}}</p>

                <h4>FOODS/PLANS WHICH DO NOT SUIT YOU</h4>
                <p>{{$patient->suit->not_suit}}</p>
            </div>
        </div>

        <div style='margin-top: 15px;border: 1px solid #bbb;border-radius: 4px; padding: 15px'>
            <div>
              <h4>EATING OUT TIPS</h4>
              <ol>
                @foreach($eatingTips as $eatingTip)
                <li class="col-md-12">
                    {{$eatingTip->name}}
                </li>
               @endforeach
             </ul>
            </div>
        </div>
        
<br />

  <div style='margin-top: 15px;border: 1px solid #bbb;border-radius: 4px; padding: 15px'>
            <div>
              <h4>Herbs Advised</h4>
              <div style='padding-left: 40px'>
                    {!!$patient->herb_names!!}
              </div>
            </div>
  </div>
<br />
<br />
        <div style='margin-top: 15px;border: 1px solid #bbb;border-radius: 4px; padding: 15px'>
            <div>
              <h4>GENERAL GUIDELINES</h4>
              <ol>
                @foreach($patient->guidelines as $guideline)
                <li class="col-md-12">
                    {{$guideline->description}}
                </li>
               @endforeach
             </ul>
            </div>
        </div>
        {!!$dietbody!!}
              </div>
              </div>
              </div>

<style type="text/css">

</style>
