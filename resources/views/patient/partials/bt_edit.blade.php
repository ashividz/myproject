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
        <link rel="stylesheet" type="text/css" href="/css/icons/icol16.css" media="screen">
        <link rel="stylesheet" type="text/css" href="/css/icons/icol32.css" media="screen">


        <link rel="stylesheet" href="/css/theme/mws-theme.css">
        <link rel="stylesheet" type="text/css" href="/css/fonts/icomoon/style.css" media="screen">


        <script type="text/javascript" src="/js/jquery.min.js"></script>
        <script type="text/javascript" src="/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/js/jquery/jquery-ui.js"></script>

        <!-- JS --
        <script src="/js/angular/angular.min.js"></script>
        <script src="/js/angular/angular-sanitize.js"></script>
        <script src="/js/angular/angular-route.js"></script>
        <script src="/js/angular/angular-resource.js"></script> -->

        
        <script src="/js/core/mws.js"></script>

        <!-- -->

        <script src="/js/moment.min.js"></script> 
        
        <script src="/js/daterangepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/daterangepicker.css">

        <!-- dataTable -->
        <script src="/js/jquery/jquery.dataTables.js"></script> 
        <link rel="stylesheet" type="text/css" href="/css/jquery/jquery.dataTables.css">

        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/menu.css">

        <!-- jQuery Raty A Star Rating Plugin-->
        <script src="/js/jquery/jquery.raty.js"></script> 
        <link rel="stylesheet" href="/css/jquery/jquery.raty.css">

        <!-- jQuery DateTime Picker Plugin-->
        <script src="/js/jquery/jquery.datetimepicker.js"></script> 
        <link rel="stylesheet" href="/css/jquery/jquery.datetimepicker.css">

        <!-- jQuery JEditable Plugin-->
        <script src="/js/jquery/jquery.jeditable.js"></script>
        <script src="/js/jquery/jquery.jeditable.datepicker.js"></script> 

        <!-- jQuery SimpleModal Plugin-->
        <script type="text/javascript" src="/js/jquery/jquery.simplemodal.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/modal.css">

        <!-- jQuery WebUI Popover Plugin-->
        <link rel="stylesheet" type="text/css" href="/css/jquery.webui-popover.css">
        <script type="text/javascript" src="/js/jquery.webui-popover.js"></script>

        <!-- HTML Table to CSV Plugin-->
        <script src="/js/table2CSV.js"></script> 

        <!-- HighCharts Plugin-->
        <script type="text/javascript" src="/js/highcharts/highcharts.js"></script>
        <script type="text/javascript" src="/js/highcharts/highcharts-3d.js"></script>
        <script type="text/javascript" src="/js/highcharts/modules/data.js"></script>
        <script type="text/javascript" src="/js/highcharts/highcharts-more.js"></script>
        <script type="text/javascript" src="/js/highcharts/modules/funnel.js"></script>


        <!-- Jquery Player 

        <script type="text/javascript" src="/js/jquery.jplayer.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/jplayer.blue.monday.min.css">-->
        <!-- Bootstrap Switch -->
        <script type="text/javascript" src="/js/bootstrap-switch.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/bootstrap-switch.min.css">

        <!-- Select 2 -->
        <script type="text/javascript" src="/js/select2.full.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/select2.min.css">

        <script src="https://code.highcharts.com"></script>

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
            
            <div class="panel-body">
                <form id="form-upload" enctype="multipart/form-data" method="post" action="">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>
                                    <label> Upload Report : </label> 
                                    <input type='file' id='bt_report' name="bt_report" />
                                </td></tr><tr>
                                <td>
                                    <label> Report Date : </label> 
                                    <input type='text' name="report_date" value='{{date('Y-m-d', strtotime($edit_bt->report_date)) }}' />
                                </td>
                                </tr>
                            <tr>
                            <td colspan='2'><label> Remark : </label> <br>
                                    <textarea name="remark" cols="40">{{$edit_bt->remark}}</textarea>
                                </td></tr><tr>
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

                $('#bt_report').bind('change', function() {
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