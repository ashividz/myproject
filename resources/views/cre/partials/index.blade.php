@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('sales_tl'))
	@include('../partials/daterange_users')
@else
	@include('../partials/daterange')
@endif