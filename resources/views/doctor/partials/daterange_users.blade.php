@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('service')|| Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('senior_doctor'))
	@include('../partials/daterange_users')
@else
	@include('../partials/daterange')
@endif