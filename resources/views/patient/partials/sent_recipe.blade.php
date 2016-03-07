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
        <script src="/js/jquery.media.js"></script> 
        <script src="/js/jquery.metadata.js"></script> 
        <script type="text/javascript">
           $(document).ready(function(){
   $(".media").media({'width': '100%', 'height': '470px'});
    });
        </script>
</head>
<body>
<?php echo $recipe_body; ?>
</body>
</html>
