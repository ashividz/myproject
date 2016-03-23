@extends('patient.index')

@section('top')
<?php
	$latestMedical = $patient->medical;	
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4>Medical Test</h4>
	</div>
	<div class="panel-body">	

<form role="form"  method="POST" name="medical" action="{{url('patient/'.$patient->id.'/medicalTest')}}">
{{csrf_field()}}
<table class="table table-striped table-bordered table-condensed">
	
		<tr>
	    <td colspan="5" align="center" style="background-color:#DDDDDD;">BLOOD HAEMATOLOGY</td>
        </tr>
	<tr>
		<th> </th>
	    	<th>Female</th>
		<th>Male</th>
		<th colspan="2"> </th>
        </tr>
	<tr>
		<td><b>Hemoglobin</b></td>
		<td>12-15g/dL</td>
		<td>13-17g/dL</td>
	    <td><input name="hemoglobin" type="text" tabindex="1" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->hemoglobin : ''}}" ></td>
		<td><input  name="hemoglobin_status" type="text" readonly="" value="{{$latestMedical ?$latestMedical->hemoglobin_staus : ''}}"></td>
	</tr>
	<tr>
		<td><b>MCV</b></td>
		<td>83-101FL</td>
		<td>83-101FL</td>
		<td><input name="mcv" type="text" tabindex="2" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->mcv : ''}}" ></td>
		<td><input  name="mcv_status" type="text" readonly value="{{$latestMedical ?$latestMedical->mcv_status: ''}}"></td>
	</tr>
	<tr>
		<td><b>MCH</b></td>
		<td>26-32pg</td>
		<td>26-32pg</td>
		<td><input name="mch" type="text" tabindex="3" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->mch : ''}}"></td>
		<td><input name="mch_status" type="text" readonly value="{{$latestMedical ?$latestMedical->mch_status : ''}}"></td>
	</tr>
	<tr>
		<td><b>MCHC</b></td>
		<td>31.5-34.5g/dL</td>
		<td>31.5-34.5g/dL</td>
		<td><input name="mchc" type="text" tabindex="4" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->mchc : ''}}"></td>
		<td><input name="mchc_status" type="text" readonly value="{{$latestMedical ?$latestMedical->mchc_stauts : ''}}"></td>
	</tr>
	<tr>
		<td><b>ESR</b></td>
		<td>0-20mm/hr</td>
		<td>0-15mm/hr</td>
		<td><input name="esr" type="text" tabindex="5" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->esr : ''}}"></td>
		<td><input name="esr_status" type="text" readonly value="{{$latestMedical ?$latestMedical->esr_status : ''}}"></td>
	</tr>
	
	<tr>
	
	<!----------------------- BLOOD GLUCOSE table -->
	
	<tr>
	    <td colspan="5" align="center" style="background-color:#DDDDDD;">BLOOD GLUCOSE</td>
	</tr>
	
	<tr>
		<th> </th>
	    	<th>Female</th>
		<th>Male</th>
		<th colspan="2"> </th>
        </tr>
	<tr>
		<td><b>FASTING</b></td>
		<td>74-99mg/dL</td>		
		<td>74-99mg/dL</td>
	     	<td><input name="fasting" type="text" tabindex="6" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->fasting : ''}}"></td>
		<td><input  name="fasting_status" type="text" readonly value="{{$latestMedical ?$latestMedical->fasting_status : ''}}"></td>
	</tr>
	
	<tr>
		<td>PP</td>
		<td>70-120mg/dL</td>		
		<td>70-120mg/dL</td>
		<td><input name="pp" type="text" tabindex="7" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->pp : ''}}"></td>
		<td><input name="pp_status" type="text" raedonly value="{{$latestMedical ?$latestMedical->pp_status : ''}}"></td>
	</tr>

  
  <!----------------------- LIVER FUNCTION TESTS table -->
	
	
	
	<tr>
	    <td colspan="5" align="center" style="background-color:#DDDDDD;">LIVER FUNCTION TESTS</td>
	</tr>
	
	<tr>
		<th> </th>
	    	<th>Female</th>
		<th>Male</th>
		<th colspan="2"> </th>
        </tr>
	<tr>
		<td><b>S.G.O.T</b></td>
		<td>15-37U/L</td>		
		<td>15-37U/L</td>
	     	<td><input name="sgot" type="text" tabindex="8" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->sgot : ''}}"></td>
		<td><input  name="sgot_status" type="text" readonly value="{{$latestMedical ?$latestMedical->sgot_status : ''}}"></td>
	</tr>
	
	<tr>
		<td><b>S.G.P.T</b></td>
		<td>30-65U/L</td>		
		<td>30-65U/L</td>
		<td><input name="sgpt" type="text" tabindex="9" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->sgpt : ''}}"></td>
		<td><input name="sgpt_status" type="text" readonly value="{{$latestMedical ?$latestMedical->sgpt_status : ''}}"></td>
	</tr>
	
	<tr>
		<td><b>ALKALINE PHOSPHATASE</b></td>
		<td>50-136U/L</td>
		<td>50-136U/L</td>
		<td><input name="alkaline" type="text" tabindex="10" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->alkaline : ''}}"></td>
		<td><input name="alkaline_status" type="text" readonly value="{{$latestMedical ?$latestMedical->alkaline_status : ''}}"></td>
	</tr>
	
	<tr>
		<td><b>G.G.T.P</b></td>
		<td>10-50mg/dL</td>
		<td>10-50mg/dL</td>
		<td><input name="ggtp" type="text" tabindex="11" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->ggtp : ''}}"></td>
		<td><input name="ggtp_status" type="text" readonly value="{{$latestMedical ?$latestMedical->ggtp : ''}}"></td>
	</tr>
	  
	  
	  <!---------------------------- THYROID PROFILE -->
  	<tr>
		<td colspan="5" align="center" style="background-color:#DDDDDD;">THYROID PROFILE</td>
	</tr>
	
	<tr>
		<th> </th>
	    	<th>Female</th>
		<th>Male</th>
		<th colspan="2"> </th>
        </tr>
	<tr>
		<td><b>T3</b></td>
		<td>60-181ng/dL</td>
		<td>60-181ng/dL</td>
	     	<td><input name="t3" type="text" tabindex="12" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->t3 : ''}}"></td>
		<td><input  name="t3_status" type="text" readonly value="{{$latestMedical ?$latestMedical->t3_status : ''}}"></td>
	</tr>
	
	<tr>
		<td><b>T4</b></td>
		<td>4.50-12.60Î¼g/dl</td>
		<td>4.50-12.60Î¼g/dl</td>
		<td><input name="t4" type="text" tabindex="13" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->t4 : ''}}"></td>
		<td><input name="t4_status" type="text" readonly value="{{$latestMedical ?$latestMedical->t4_status : ''}}"></td>
	</tr>
	<tr>
		<td><b>TSH</b></td>
		<td>0.35-5.50Î¼IU/mL</td>
		<td>0.35-5.50Î¼IU/mL</td>
		<td><input name="tsh" type="text" tabindex="14" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->tsh : ''}}"></td>
		<td><input name="tsh_status" type="text" readonly value="{{$latestMedical ?$latestMedical->tsh_status : ''}}"></td>
	</tr> <!--
</table>
		</td>
		<td valign="top">
<!-- Second Table -->
	<!----------------------- LIPID PROFILE table ------------------------------->
	<!--<div style="position:absolute; top:185px; left:750px;"> --
<table width="100%" cellspacing="0" border="0" align="center" class="table table-striped table-bordered">-->
	<tr>
	    <td colspan="5" align="center" style="background-color:#DDDDDD;">LIPID PROFILE</td>
	</tr>
	
	<tr>
		<th> </th>
	    	<th>Female</th>
		<th>Male</th>
		<th colspan="2"> </th>
        </tr>
	<tr>
		<td><b>Total cholesterol</b></td>
		<td>&#60; 200 Desirable</td>
		<td>&#60; 200 Desirable</td>
	     	<td><input name="total" type="text" tabindex="15" onBlur="check_status()"  value="{{$latestMedical ?$latestMedical->total:''}}"></td>
		<td><input  name="total_status" type="text" readonly value="{{$latestMedical ?$latestMedical->total_status:''}}"></td>
	</tr>
	<tr>
		<td><b>HDL-Chol</b></td>
		<td>40-60mg/dL</td>
		<td>40-60mg/dL</td>
		<td><input name="hdl" type="text" tabindex="16" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->hdl:''}}"></td>
		<td><input name="hdl_status" type="text" readonly value="{{$latestMedical ?$latestMedical->hdl_status:''}}"></td>
	</tr>
		<tr>
		<td><b>LDL-Chol</b></td>
		<td>&#60; 100 Desirable</td>
		<td>&#60; 100 Desirable</td>
		<td><input name="ldl" type="text" tabindex="17" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->ldl:''}}"></td>
		<td><input name="ldl_status" type="text" readonly value="{{$latestMedical ?$latestMedical->ldl_status:''}}"></td>
	</tr>
	<tr>
		<td><b>VLDL-Chol</b></td>
		<td>&#60;/=30mg/dL</td>
		<td>&#60;/=30mg/dL</td>
		<td><input name="vldl" type="text" tabindex="18" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->vldl:''}}"></td>
		<td><input name="vldl_status" type="text" readonly value="{{$latestMedical ?$latestMedical->vldl_staus:''}}"></td>
	</tr>
	<tr>
		<td><b>TRIGLYCERIDES</b></td>
		<td>&#60; 150 Normal</td>
		<td>&#60; 150 Normal</td>
		<td><input name="tri" type="text" tabindex="19" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->tri:''}}"></td>
		<td><input name="tri_status" type="text" readonly value="{{$latestMedical ?$latestMedical->tri_status:''}}"></td>
	</tr>
		<!----------------------- KIDNEY FUNCTION TESTS table ------------------------------>

	<tr>
	    <td colspan="5" align="center" style="background-color:#DDDDDD;">KIDNEY FUNCTION TESTS</td>
	</tr>
	
	<tr>
		<th> </th>
	    	<th>Female</th>
		<th>Male</th>
		<th colspan="2"> </th>
        </tr>
	<tr>
		<td><b>UREA</b></td>
		<td>6-20mg/dL</td>
		<td>6-20mg/dL</td>
	     	<td><input name="urea" type="text" tabindex="20" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->urea:''}}">	    </td>
		<td><input  name="urea_status" type="text" readonly value="{{$latestMedical ?$latestMedical->urea_status:''}}"></td>
	</tr>
	<tr>
		<td><b>SERUM,CREATININE</b></td>
		<td>0.6-1.1mg/dL</td>
		<td>0.9-1.3mg/dL</td>
		<td><input name="serum" type="text" tabindex="21" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->serum:''}}"></td>
		<td><input name="serum_status" type="text" readonly value="{{$latestMedical ?$latestMedical->serum_status:''}}"></td>
	</tr>
	<tr>
		<td><b>URIC ACID</b></td>
		<td>2.6-6.0mg/dL</td>
		<td>3.5-7.2mg/dL</td>
		<td><input name="uric" type="text" tabindex="22" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->uric:''}}"></td>
		<td><input name="uric_status" type="text" readonly="" value="{{$latestMedical ?$latestMedical->uric_status:''}}"></td>
	</tr>
	<tr>
		<td><b>TOTAL CALCIUM</b></td>
		<td>8.5-10.1mg/dL</td>
		<td>8.5-10.1mg/dL</td>
		<td><input name="totall" type="text" tabindex="23" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->totall:''}}"></td>
		<td><input name="totall_status" type="text" readonly="" value="{{$latestMedical ?$latestMedical->totall_status:''}}"></td>
	</tr>
	<tr>
		<td><b>TOTAL PROTEINS</b></td>
		<td>6.4-8.2g/dL</td>
		<td>6.4-8.2g/dL</td>
		<td><input name="proteins" type="text" tabindex="24" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->proteins:''}}"></td>
		<td><input name="proteins_status" type="text" readonly="" value="{{$latestMedical ?$latestMedical->proteins_status:''}}"></td>
	</tr>
	<tr>
		<td><b>SERUM ALBUMN/GLOBULIN</b></td>
		<td>3.4-5.0g/dL</td>
		<td>3.4-5.0g/dL</td>
		<td><input name="seruma" type="text" tabindex="25" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->seruma:''}}"></td>
		<td><input name="seruma_status" type="text" readonly="" value="{{$latestMedical ?$latestMedical->seruma_status:''}}"></td>
	</tr>

	<!-- IMMUNO ASSAYS table -->
	
	<tr>
	    <td colspan="5" align="center" style="background-color:#DDDDDD;">IMMUNO ASSAYS<center></td>
	</tr>
	
	<tr>
		<th> </th>
	    	<th>Female</th>
		<th>Male</th>
		<th colspan="2"> </th>
        </tr>
	<tr>
		<td><b>PROLACTIN (Fasting)</b></td>
		<td>2.9-29.2ng/ml</td>
		<td>NA</td>
	      	<td><input name="prolactin_f" type="text" tabindex="26" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->prolactin_f:''}}"></td>
		<td><input  name="prolactin_f_status" type="text"  value="{{$latestMedical ?$latestMedical->prolactin_f_status : ''}}"></td>
	</tr>
	<tr>
		<td><b>INSULIN (Fasting)</b></td>
		<td>3.00-25.00uU/ml</td>
		<td>3.00-25.00uU/ml</td>
		<td><input name="insulin_f" type="text" tabindex="28" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->insulin_f : ''}}"></td>
		<td><input name="insulin_f_status" type="text"  value="{{$latestMedical ?$latestMedical->insulin_f_status : ''}}"></td>
	</tr>
	<tr>
		<td><b>INSULIN (PP)</b></td>
		<td>-</td>
		<td>-</td>
		<td><input name="insulin_p" type="text" tabindex="29" onBlur="check_status()" value="{{$latestMedical ?$latestMedical->insulin_p : ''}}"></td>
		<td><input name="insulin_p_status" type="text"  value="{{$latestMedical ?$latestMedical->insulin_p_status : ''}}"></td>
	</tr>
	<tr>
		<td colspan="5"><center><input name="submit" class="btn btn-primary" type="submit" value="Submit"></center></td>
	</tr> 
</table>	
<script>
function check_status()
{
	var a1=document.medical.hemoglobin.value;
	if(a1!="")
	{
	if(a1<11)
	{
		document.medical.hemoglobin_status.value="Low";
	}
	
	 if(a1>15)
	{
	    document.medical.hemoglobin_status.value="High";
	}
	
	 if(a1>=11 & a1<=15)
	{
	    document.medical.hemoglobin_status.value="Normal";
	}		
	}
	
	var a2=document.medical.mcv.value;
	if(a2!="")
	{
	if(a2 <79)
	{
		document.medical.mcv_status.value="Low";
	}
    if(a2 >98)
	{
		document.medical.mcv_status.value="high";
	}
	if(a2>=79 & a2<=89)
	{
		document.medical.mcv_status.value="normal";
	}
	}
	
	var a3=document.medical.mch.value;
    if(a3!="")
	{
	if(a3 <26)
	{
		document.medical.mch_status.value="Low";
	}
    if(a3 >32)
	{
		document.medical.mch_status.value="High";
	}
	 if(a3>=26 & a3<=32)
	{
		document.medical.mch_status.value="Normal";
	}
	}
	
	var a4=document.medical.mchc.value;
    if(a4!="")
	{
	if(a4 <30)
	{
		document.medical.mchc_status.value="Low";
	}
     if(a4 >36)
	{
		document.medical.mchc_status.value="High";
	}
	 if(a4>=30 & a4<=36 )
	{
		document.medical.mchc_status.value="Normal";
	}
	}
	
	var a5=document.medical.esr.value;
    if(a5!="")
	{
	if(a5 <0)
	{
		document.medical.esr_status.value="Low";
	}
    if(a5 >20)
	{
		document.medical.esr_status.value="High";
	}
	if(a5>=0 & a5<=20)
	{
		document.medical.esr_status.value="Normal";
	}
	}	
	
	var b1=document.medical.fasting.value;
    if(b1!="")
	{
	if(b1 <60)
	{
		document.medical.fasting_status.value="Low";
	}
    if(b1 >100)
	{
		document.medical.fasting_status.value="High";
	}
	if(b1>=60 & b1<=100)
	{
		document.medical.fasting_status.value="Normal";
	}
	}
	
	var b2=document.medical.pp.value;
	if(b2!="")
	{
    if(b2 <120)
	{
		document.medical.pp_status.value="Low";
	}
    if(b2 >140)
	{
		document.medical.pp_status.value="High";
	}
	if(b2>=120 & b2<=140)
	{
		document.medical.pp_status.value="Normal";
	}
	}
	
	var b3=document.medical.sgot.value;
	if(b3!="")
	{
    if(b3 <10)
	{
		document.medical.sgot_status.value="Low";
	}
    if(b3 >50)
	{
		document.medical.sgot_status.value="High";
	}
	if(b3>=10 & b3<=50)
	{
		document.medical.sgot_status.value="Normal";
	}
	}
	
	var b4=document.medical.sgpt.value;
	if(b4!="")
	{
	if(b4 <5)
	{
	document.medical.sgpt_status.value="Low";
	}	
    if(b4 >40)
	{
		document.medical.sgpt_status.value="High";
	}
	if(b4>=5 & b4<=40)
	{
		document.medical.sgpt_status.value="Normal";
	}
	}
	
	var b5=document.medical.alkaline.value;
    if(b5!="")
	{
	if(b5 <40)
	{
		document.medical.alkaline_status.value="Low";
	}
    if(b5 >120)
	{
		document.medical.alkaline_status.value="High";
	}
	if(b5>=40 & b5<=120)
	{
		document.medical.alkaline_status.value="Normal";
	}
	}
	
	var c1=document.medical.ggtp.value;
	if(c1!="")
	{
    if(c1 <10)
	{
		document.medical.ggtp_status.value="Low";
	}
    if(c1 >50)
	{
		document.medical.ggtp_status.value="High";
	}
	if(c1>=10 & c1<=50)
	{
		document.medical.ggtp_status.value="Normal";
	}
	}
	
	var c2=document.medical.t3.value;
    if(c2!="")
	{
	if(c2 <1.64)
	{
		document.medical.t3_status.value="Low";
	}
    if(c2 >3.45)
	{
		document.medical.t3_status.value="High";
	}
	if(c2>=1.64 & c2<=3.45)
	{
		document.medical.t3_status.value="Normal";
	}
	}
	
	var c3=document.medical.t4.value;
    if(c3!="")
	{
	if(c3 <0.71)
	{
		document.medical.t4_status.value="Low";
	}
    if(c3 >1.85)
	{
		document.medical.t4_status.value="High";
	}
	if(c3>=0.71 & c3<=1.85)
	{
		document.medical.t4_status.value="Normal";
	}
	}
	
	var c4=document.medical.tsh.value;
    if(c4!="")
	{
	if(c4 < 0.49)
	{
		document.medical.tsh_status.value="Low";
	}
    if(c4 >4.67)
	{
		document.medical.tsh_status.value="High";
	}
	if(c4 >=0.49 & c4 <=4.67)
	{
		document.medical.tsh_status.value="Normal";
	}
	}
	
	var c5=document.medical.total.value;
	if(c5!="")
	{
    if(c5 <130)
	{
		document.medical.total_status.value="Low";

	}
    if(c5 >220)
	{
		document.medical.total_status.value="High";
	}
	if(c5 >=130 & c5 <=220)
	{
		document.medical.total_status.value="Normal";
	}
	}
	
	var d1=document.medical.hdl.value;
    if(d1!="")
	{
	if(d1 <30)
	{
		document.medical.hdl_status.value="Low";
	}
    if(d1 >75)
	{
		document.medical.hdl_status.value="High";
	}
	if(d1 >=30 & d1 <=75)
	{
		document.medical.hdl_status.value="Normal";
	}
	}
	
	var d2=document.medical.ldl.value;
    if(d2!="")
	{
	if(d2 <30)
	{
		document.medical.ldl_status.value="Low";
	}
    if(d2 >100)
	{
		document.medical.ldl_status.value="High";
	}
	if(d2 >=30 & d2 <=100)
	{
		document.medical.ldl_status.value="Normal";
	}
	}
	
	var d3=document.medical.vldl.value;
	if(d3!="")
	{
    if(d3 <10)
	{
		document.medical.vldl_status.value="Low";
	}
    if(d3 >30)
	{
		document.medical.vldl_status.value="High";
	}
	if(d3 >=10 & d3 <=30)
	{
		document.medical.vldl_status.value="Normal";
	}
	}
	
	var d4=document.medical.tri.value;
    if(d4!="")
	{
	if(d4 <50)
	{
		document.medical.tri_status.value="Low";
	}
    if(d4 >150)
	{
		document.medical.tri_status.value="High";
	}
	if(d4 >=50 & d4 <=150)
	{
		document.medical.tri_status.value="Normal";
	}
	}
	
	var d5=document.medical.urea.value;
    if(d5!="")
	{
	if(d5 <15)
	{
		document.medical.urea_status.value="Low";
	}
    if(d5 >45)
	{
		document.medical.urea_status.value="High";
	}
	if(d5>=15 & d5<=45)
	{
		document.medical.urea_status.value="Normal";
	}
	}
	
	var e1=document.medical.serum.value;
    if(e1!="")
	{
	if(e1 <0.5)
	{
		document.medical.serum_status.value="Low";
	}
    if(e1 >1.4)
	{
		document.medical.serum_status.value="High";
	}
	if(e1 >=0.5 & e1 <=1.4)
	{
		document.medical.serum_status.value="Normal";
	}
	}
	
	var e2=document.medical.uric.value;
    if(e2!="")
	{
	if(e2 <2)
	{
		document.medical.uric_status.value="Low";
	}
    if(e2 >7)
	{
		document.medical.uric_status.value="High";
	}
	if(e2 >=2 & e2 <=7)
	{
		document.medical.uric_status.value="Normal";
	}
	}
	
	var e3=document.medical.totall.value;
    if(e3!="")
	{
	if(e3 <8)
	{
		document.medical.totall_status.value="Low";
	}
    if(e3 >11)
	{
		document.medical.totall_status.value="High";
	}
	if(e3 >=8 & e3 <=11)
	{
		document.medical.totall_status.value="Normal";
	}
	}
	
	var e4=document.medical.proteins.value;
    if(e4!="")
	{
	if(e4 <6.00)
	{
		document.medical.proteins_status.value="Low";
	}
    if(e4 >8.50)
	{
		document.medical.proteins_status.value="High";
	}
	if(e4 >=6.00 & e4 <=8.50)
	{
		document.medical.proteins_status.value="Normal";
	}
	}
	
	var e5=document.medical.seruma.value;
    if(e5!="")
	{
	if(e5 <1.1)
	{
		document.medical.seruma_status.value="Low";
	}
    if(e5 >2.2)
	{
		document.medical.seruma_status.value="High";
	}
	if(e5 >=1.1 & e5 <=2.2)
	{
		document.medical.seruma_status.value="Normal";
	}
	}
	
	var f1=document.medical.prolactin_f.value;
    if(f1!="")
	{
	if(f1 <2.9)
	{
		document.medical.prolactin_f_status.value="Low";
	}
    if(f1 >29.2)
	{
		document.medical.prolactin_f_status.value="High";
	}
	if(f1 >=2.9 & f1 <=29.2)
	{
		document.medical.prolactin_f_status.value="Normal";
	}
	}
	
	var f2=document.medical.insulin_f.value;
    if(f2!="")
	{
	if(f2 <3)
	{
		document.medical.insulin_f_status.value="Low";
	}
    if(f2 >22)
	{
		document.medical.insulin_f_status.value="High";
	}
	if(f2 >=3 & f2 <=22)
	{
		document.medical.insulin_f_status.value="Normal";
	}
	}  
	var f3=document.medical.insulin_p.value;
    if(f3!="")
	{
	if(f3 <30)
	{
		document.medical.insulin_p_status.value="Low";
	}
    if(f3 >80)
	{
		document.medical.insulin_p_status.value="High";
	}
	if(f3 >=30 & f3 <=80)
	{
		document.medical.insulin_p_status.value="Normal";
	}
	}  
}
</script>
</div>
</div>	
@endsection
	
@section('main')
<div class="container1">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Test Details</h4>
		</div>
		<div class="panel-body">

<table align="center" border="0" class="table table-striped table-bordered">
<thead>
<tr>
	<th><font face="Verdana" size="1">Date</font></th>
	<th><font face="Verdana" size="1">Hemoglobin</font></th>
	<th><font face="Verdana" size="1">MCV</font></th>
	<th><font face="Verdana" size="1">MCH</font></th>
	<th><font face="Verdana" size="1">MCHC</font></th>
	<th><font face="Verdana" size="1">ESR</font></th>
	<th><font face="Verdana" size="1">FASTING</font></th>
	<th><font face="Verdana" size="1">PP</font></th>
	<th><font face="Verdana" size="1">sgot</font></th>
	<th><font face="Verdana" size="1">sgpt</font></th>
	<th><font face="Verdana" size="1">ALKALINE</font></th>
	<th><font face="Verdana" size="1">ggtp</font></th>
	<th><font face="Verdana" size="1">T3</font></th>
	<th><font face="Verdana" size="1">T4</font></th>
	<th><font face="Verdana" size="1">TSH</font></th>
	<th><font face="Verdana" size="1">Total cholesterol</font></th>
	<th><font face="Verdana" size="1">HDL</font></th>
	<th><font face="Verdana" size="1">LDL</font></th>
	<th><font face="Verdana" size="1">VLDL</font></th>
	<th><font face="Verdana" size="1">TRI</font></th>
	<th><font face="Verdana" size="1">UREA</font></th>
	<th><font face="Verdana" size="1">SERUM</font></th>
	<th><font face="Verdana" size="1">URIC</font></th>
	<th><font face="Verdana" size="1">TOTAL CALCIUM</font></th>
	<th><font face="Verdana" size="1">PROTEINS</font></th>
	<th><font face="Verdana" size="1">SERUMA</font></th>
	<th><font face="Verdana" size="1">PROLACTIN (Fastning)</font></th>
	<th><font face="Verdana" size="1">INSULIN (Fastning)</font></th>
	<th><font face="Verdana" size="1">INSULIN (PP)</font></th>
</tr>
</thead>
@if(!$patient->medicals->isEmpty())
<tbody>
	@foreach($patient->medicals as $medical)
	<tr>	
	<td><font face="Verdana" size="1">{{ $medical->date or ""  or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->hemoglobin or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->mcv or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->mch or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->mchc or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->esr or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->fasting or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->pp or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->sgot or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->sgpt or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->alkaline or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->ggtp or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->t3 or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->t4 or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->tsh or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->total or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->hdl or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->ldl or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->vldl or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->tri or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->urea or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->serum or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->uric or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->totall or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->proteins or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->seruma or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->prolactin_f or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->insulin_f or "" }}</font></td>
	<td><font face="Verdana" size="1">{{$medical->insulin_p or "" }}</font></td>
	</tr>
	@endforeach
</tbody>
@endif
@endsection