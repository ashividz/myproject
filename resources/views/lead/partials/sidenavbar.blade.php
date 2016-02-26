<ul id="sidenav">
  	<li{!! (($section == "partials.personal") ? " class='selected'" : "") !!}><a href="/lead/{{ $lead->id or $patient->lead_id }}/viewPersonalDetails">Personal Details</a></li>
  	<li{!! (($section == "partials.contact") ? " class='selected'" : "") !!}><a href="/lead/{{ $lead->id or $patient->lead_id }}/viewContactDetails">Contact Details</a></li>
  	<li{!! (($section == "partials.details") ? " class='selected'" : "") !!}><a href="/lead/{{ $lead->id or $patient->lead_id }}/viewDetails">Lead Details</a></li>
  	<li{!! (($section == "partials.references") ? " class='selected'" : "") !!}><a href="/lead/{{ $lead->id or $patient->lead_id }}/viewReferences">References</a></li>
 	<li{!! (($section == "partials.dispositions") ? " class='selected'" : "") !!}><a href="/lead/{{ $lead->id or $patient->lead_id }}/viewDispositions">Call Dispositions</a></li> 
 	<li{!! (($section == "partials.email") ? " class='selected'" : "") !!}><a href="/lead/{{ $lead->id or $patient->lead_id }}/email">Email</a></li> 
</ul>

@if($lead->patient && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('nutritionist') || Auth::user()->hasRole('doctor')))
<ul id="sidenav">
	<li{!! (($section == "partials.medical") ? " class='selected'" : "") !!}><a href="/patient/{{ $patient->id or $lead->patient->id }}/medical">Medical</a></li>
	<li{!! (($section == "partials.herbs") ? " class='selected'" : "") !!}><a href="/patient/{{ $patient->id or $lead->patient->id  }}/herbs">Herbs</a></li>
	<li{!! (($section == "partials.diet") ? " class='selected'" : "") !!}><a href="/patient/{{ $patient->id or $lead->patient->id  }}/diet">Diets</a></li> 
	<li{!! (($section == "partials.weight") ? " class='selected'" : "") !!}><a href="/patient/{{ $patient->id or $lead->patient->id  }}/weight">Weight</a></li> 
    <li{!! (($section == "partials.measurements") ? " class='selected'" : "") !!}><a href="/patient/{{ $patient->id or $lead->patient->id  }}/measurements">Measurements</a></li> 
	<li{!! (($section == "partials.yuwow") ? " class='selected'" : "") !!}><a href="/patient/{{ $patient->id or $lead->patient->id  }}/yuwow">YuWoW</a></li> 
	<li{!! (($section == "partials.prakriti") ? " class='selected'" : "") !!}><a href="/patient/{{ $patient->id or $lead->patient->id  }}/prakriti">Prakriti</a></li> 
	
	<li{!! (($section == "partials.tag") ? " class='selected'" : "") !!}><a href="/patient/{{ $patient->id or $lead->patient->id  }}/tags">Tags</a></li> 
	<li{!! (($section == "partials.notes") ? " class='selected'" : "") !!}><a href="/patient/{{ $patient->id or $lead->patient->id  }}/notes">Notes</a></li> 
	<li{!! (($section == "partials.survey") ? " class='selected'" : "") !!}><a href="/patient/{{ $patient->id or $lead->patient->id }}/survey">Survey</a></li>  
	<li{!! (($section == "partials.medicaltests") ? " class='selected'" : "") !!}><a href="http://crm/medical.php?clinic={{$patient->clinic  or $lead->patient->clinic }}&registration_no={{$patient->registration_no  or $lead->patient->registration_no }}" target="_blank">Medical Tests</a></li> 
	<li{!! (($section == "partials.fitness") ? " class='selected'" : "") !!}><a href="http://crm/fitness.php?clinic={{$patient->clinic or $lead->patient->clinic }}&registration_no={{$patient->registration_no or $lead->patient->registration_no }}" target="_blank">Fitness</a></li> 
</ul>
@endif