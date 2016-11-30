<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>@yield('title')Nutrihealth</title>
        <meta name="description" content="@yield('description')">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- CSS -->
        <link rel="stylesheet" href="/css/font-awesome.css">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/theme/mws-style.css">
     
        <script type="text/javascript" src="/js/jquery.min.js"></script>
        <script type="text/javascript" src="/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/js/jquery/jquery-ui.js"></script>


        <script src="/js/moment.min.js"></script> 
        
        <script src="/js/daterangepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/daterangepicker.css">

        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/menu.css">

        <!-- jQuery Raty A Star Rating Plugin-->
        <script src="/js/jquery/jquery.raty.js"></script> 
        <link rel="stylesheet" href="/css/jquery/jquery.raty.css">

        

    </head>
    <body>
        <div id="alert" class="alert alert-danger center">
            @if (count($errors) > 0)        
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            
            @endif
        </div>
        @if(Session::has('status'))            
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            <h5 style='padding-left: 10px;color: #336600'>{!! Session::get('status') !!}</h5>
        @endif
        <div class="panel panel-default">
            <h4 style='18px !important;padding-left: 20px'>{{$attachment_name}}</h4>
            <div class="panel-body">
                <form id="form-upload" enctype="multipart/form-data" method="post" action="">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>
                                    <label> Upload Attachment : </label> 
                                    <input type='file' id='email_attachment' name="email_attachment" />
                                </td></tr>
                          <tr>
                                <td colspan="2">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() 
            {

                $('#email_attachment').bind('change', function() {
                        var fileExtension = ['pdf'];
                    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                            alert("Only PDF or Image Files Allowed");
                            $('#form-upload .btn').prop('disabled', true);
                            return false;
                      }

                    if(this.files[0].size > 1024 * 1024 * 2) {
                            alert('Report size is: ' + (this.files[0].size/1024/1024).toFixed(2) + "MB, shouldn't be more that 2 MB.");
                            $('#form-upload .btn').prop('disabled', true);
                      }
                    else
                            $('#form-upload .btn').prop('disabled', false);
                });

                $('input[name="report_date"]').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    locale: {
                        format: 'YYYY-MM-DD'
                        },
                    endDate: '2016-12-31'
                });

                $('input[name="report_date"]').on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('YYYY-MM-DD'));
                    });
                    });
        </script>
    </body>
    </html>
