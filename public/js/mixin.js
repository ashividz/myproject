var mixin = {
    methods : {
        toastErrors(errors) {
            var msg = '';
            $.each(errors, function( index, value ) {
                msg += "<li>" + value +"</li>";
            });    
            
            toastr.error(msg, 'Error!');             
        },

        toastDelete(value) {
            if (value) {
                toastr.warning('Deleted', 'Success!!!');
            } else {
                toastr.success('Restored', 'Success!!!');
            };
            
        },

        slug(value) {
            var $slug = '';
            var trimmed = $.trim(value);
            $slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
            replace(/-+/g, '-').
            replace(/^-|-$/g, '');
            return $slug.toLowerCase();
        }
    }
}