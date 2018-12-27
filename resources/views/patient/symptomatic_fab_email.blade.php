
<div style='padding: 10px'>
  <div>
      
      <div>
      <div>
        <div >
            <table width='100%'>
                <tr><td colspan='2' style='text-align: center'>
                      <img width='300' src='http://drshikhasnutriwelhealth.com/logo.jpg' /></td>
                    </tr>
            </table>

            <label>Dear {{$patient->lead->name}}</label><br><br>
                <label>Thank you for choosing “Dr. Shikha’s Nutrihealth”</label><br><br>
                <label>We hope that you thoroughly enjoyed the experience and we are able to enlighten/ educate you with the concept of Ayurveda & Body type based Nutrition advisory and plans. We hope that we are able to establish trust with you and add you in our satisfied clientele.</label><br><br>
                <label>We are constantly trying to improve the service we offer and we would be grateful if you could take a couple of minutes to send a feedback with your thoughts.</label><br><br>
                <label>We work really hard to provide the best experience to our customers and are always looking for ways to improve. If you have any feedback please reply to this email directly. We read every email we get and appreciate your help in improving our customer experience.</label><br><br>
                <label>As your program is completed, we would like to share the final analysis brochure with you mentioned below.</label><br><br>
                <label>I look forward to hearing from you, and hopefully welcoming you back to Dr. Shikha’s NutriHealth family.</label><br><br>
        <table width='100%'>
        <tr>
            <td colspan='2' style='text-align: center'>
             <h4>FINAL ANALYSIS BROCHURE</h4></td>
           </tr>
             <tr><td width='50%'>
               <br/><br/>
               <label>NAME: {{$patient->lead->name}}</label> <br>
               <label>PRAKRITI: {{$patient->prakriti->first_dominant_name}}</label> <br>
               <label>BLOOD GROUP: {{$patient->blood_type->name}} {{$patient->rh_factor->code or ''}}</label>  <br>
             </td>
             </tr>
           </table>
        </div>
      </div>
      
      <br /><br />
      <table width='100%' border='1' bordercolor='grey' cellspacing='0'>
       <tr><td colspan='3' style='padding: 10px;text-align: left;background: #ccc'>
            <h4 style='margin: 0px' >SYMPTOMATIC IMPROVEMENTS:</h4>
          </td></tr>
              <tr><th style='padding: 10px;text-align: left'>Parameters </th><th style='padding: 10px;text-align: left'>Initial </th><th style='padding: 10px;text-align: left'>Final </th></tr>
              <tr><td style='padding: 10px'>Energy Level</td><td style='padding: 10px'>{{$patient->initialSymptom->energy_level}}</td><td style='padding: 10px'>{{$patient->lastSymptom->energy_level}}</td></tr>
              <tr><td style='padding: 10px'>Constipation </td><td style='padding: 10px'>{{$patient->initialSymptom->constipation}}</td><td style='padding: 10px'>{{$patient->lastSymptom->constipation}}</td></tr>
              <tr><td style='padding: 10px'>Gas</td><td style='padding: 10px'>{{$patient->initialSymptom->gas}}</td><td style='padding: 10px'>{{$patient->lastSymptom->gas}}</td></tr>
              <tr><td style='padding: 10px'>Acidity</td><td style='padding: 10px'>{{$patient->initialSymptom->acidity}}</td><td style='padding: 10px'>{{$patient->lastSymptom->acidity}}</td></tr>
              <tr><td style='padding: 10px'>Water Retention</td><td style='padding: 10px'>{{$patient->initialSymptom->water_retention}}</td><td style='padding: 10px'>{{$patient->lastSymptom->water_retention}}</td></tr>
              <tr><td style='padding: 10px'>Joint Pains</td><td style='padding: 10px'>{{$patient->initialSymptom->joint_pain}}</td><td style='padding: 10px'>{{$patient->lastSymptom->joint_pain}}</td></tr>
              <tr><td style='padding: 10px'>Emotional Eating</td><td style='padding: 10px'>{{$patient->initialSymptom->emotional_eating}}</td><td style='padding: 10px'>{{$patient->lastSymptom->emotional_eating}}</td></tr>
              <tr><td style='padding: 10px'>Sugar/Food Craving</td><td style='padding: 10px'>{{$patient->initialSymptom->sugar_food_craving}}</td><td style='padding: 10px'>{{$patient->lastSymptom->sugar_food_craving}}</td></tr>
              <tr><td style='padding: 10px'>Headache</td><td style='padding: 10px'>{{$patient->initialSymptom->headache}}</td><td style='padding: 10px'>{{$patient->lastSymptom->headache}}</td></tr>
              <tr><td style='padding: 10px'>Backache</td><td style='padding: 10px'>{{$patient->initialSymptom->backache}}</td><td style='padding: 10px'>{{$patient->lastSymptom->backache}}</td></tr>
              <tr><td style='padding: 10px'>General Feeling</td><td style='padding: 10px'>{{$patient->initialSymptom->general_feeling}}</td><td style='padding: 10px'>{{$patient->lastSymptom->general_feeling}}</td></tr>
              <tr><td style='padding: 10px'>Sleep Pattern</td><td style='padding: 10px'>{{$patient->initialSymptom->sleep_pattern}}</td><td style='padding: 10px'>{{$patient->lastSymptom->sleep_pattern}}</td></tr>
      </table>
      
      <div style='margin-top: 15px;border: 1px solid #bbb;border-radius: 4px; padding: 15px'>
          <div class=''>
            <h4>PRAKRITI</h4>
            <p style='font-weight: bold'>{{$patient->prakriti->first_dominant_name}} Constitution</p>
                 @if( $patient->prakriti->first_dominant_name == "Kapha")
                      <p>The Energy of Lubrication and Retention .<br>
                          ‘Kapha’ ( Water + Earth) controls the physical structure and fluid balance of the body<br>
                          As your Prakriti is Kapha dominating it means that you are  blessed with strength, endurance and stamina. You are calm, tolerant and forgiving. You have strong  and you have good immunity power . You  tend to gain weight because of a slow metabolic system.<br>
                          You  are prone to diseases connected to the water principle such as flu, sinus congestion, and other diseases involving mucous. Sluggishness, excess weight, diabetes, water retention, and headaches are also common.</p>

                 @elseif($patient->prakriti->first_dominant_name == "Pitta")
                      <p>
                        The Energy of Digestion, Metabolism and Transformation.<br><br>
                        ‘Pitta’ manages digestion and metabolism.<br>
                        Your predominant dosha is Pitta (fire element), you are intense ("hot" - literally), digesting and processing on all levels - from ideas, emotions, and perceptions, to sensations and food. When balanced, you are self-confident, goal-oriented, efficient, focused, with good problem-solving skills and a sharp wit.<br>
                        A Pitta imbalance can lead you to heartburn, ulcers, insomnia, vision problems, anger, stubbornness, aggressiveness, absolutism and being judgmental.
                      </p>
                @elseif( $patient->prakriti->first_dominant_name == "Vata")
                      <p>Energy of movement and governs all biological activity.<br><br>

                      It controls blood flow, elimination of wastes, breathing and the movement of thoughts across the mind.<br>

                      As your Prakriti is Vata (ether, or space and air elements), you are in perpetual movement, like change and manifest lightness, quickness, and flexibility in every aspect of your life. When balanced, you have a fun, vibrant, lively, and expressive personality.<br>
                      A Vata imbalance leads you to increased worrying, impulsiveness, inability to make decisions, depression, insomnia, headaches and fatigue.</p>
                @endif
          </div>
      </div>

      <div style='margin-top: 15px;border: 1px solid #bbb;border-radius: 4px; padding: 15px'>
          <div>
            <h4>Remarks</h4>
            <ol>
              @foreach($eatingTips as $eatingTip)
              <li class="col-md-12" style='float: none'>
                  {{$eatingTip->name}}
              </li>
             @endforeach
           </ul>
          </div>
      </div>

      <div style='margin-top: 15px;border: 1px solid #bbb;border-radius: 4px; padding: 15px'>
          <div>
            <h4>GENERAL GUIDELINES</h4>
            <ol>
              @foreach($patient->guidelines as $guideline)
              <li class="col-md-12" style='float: none'>
                  {{$guideline->description}}
              </li>
             @endforeach
           </ul>
          </div>
      </div>

      <br /><br />
      <p style='font-weight: bold'> Living Light….!!!!</p>
      <p style='font-weight: bold'>Warm Regards<br>
      Nutritionist - {{$patient->nutritionist}}</p>
            </div>
            </div>
            </div>

<style type="text/css">

</style>
