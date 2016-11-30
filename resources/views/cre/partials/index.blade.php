@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('sales_tl') || Auth::user()->hasRole('marketing'))
	@include('../partials/daterange_users')
@else
	@include('../partials/daterange')
@endif