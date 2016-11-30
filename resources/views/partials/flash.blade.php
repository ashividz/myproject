<script type="text/javascript" src="/js/sweetalert-dev.js"></script>
<style type="text/css" href="/css/sweetalert.css"></style>

@if (session()->has('flash_message')) 

<script type="text/javascript">
	swal({   
		title: "{{ session('flash_message.title') }}",   
		text: "{{ session('flash_message.message') }}",   
		type: "{{ session('flash_message.type') }}",
		timer :  2000,
		showConfirmationButton : false
	});
</script>

@endif

@if (session()->has('flash_message_overlay')) 

<script type="text/javascript">
	swal({   
		title: "{{ session('flash_message_overlay.title') }}",   
		text: "{{ session('flash_message_overlay.message') }}",   
		type: "{{ session('flash_message_overlay.type') }}", 		 
		confirmButtonColor: "#DD6B55",   
		confirmButtonText: "Okay",   
		closeOnConfirm: false
	});
</script>

@endif