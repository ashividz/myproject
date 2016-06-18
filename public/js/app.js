$( document ).ready(function() {
    toastr.options = {
      "closeButton": true,
      "debug": true,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-center",
      "preventDuplicates": false,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": 5000,
      "extendedTimeOut": 1000,
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
      "tapToDismiss": true
    }
    Vue.http.headers.common['X-CSRF-TOKEN'] = $('[name="csrf_token"]').attr('content');
    
    Vue.filter('format_date', function (value) {
        if (value == null) {
            return null;
        }
        return moment(value).format('D MMM hh:mm A');
    })
    Vue.filter('format_date1', function (value) {
        if (value == null) {
            return null;
        }
        return moment(value).format('D MMM, YYYY');
    })
    Vue.filter('format_date2', function (value) {
        if (value == null) {
            return null;
        }
        return moment(value).format('D MMM');
    })
});
