<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="@csrf">
    <link rel="icon" href="{{ Route::assets('PEF_LOGO.png') }}" type="png">
    <title>PEF-CSD | RRG</title>
    @css("bower_components/bootstrap/dist/css/bootstrap.min.css")
    @css("bower_components/font-awesome/css/font-awesome.min.css")
    @css("bower_components/Ionicons/css/ionicons.min.css")
    @css("bower_components/jvectormap/jquery-jvectormap.css")
    @css("dist/css/AdminLTE.min.css")
    @css("dist/css/skins/_all-skins.min.css")
    @css("bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css")
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue sidebar-mini fixed">
    @include('rrg.header')

    @include('rrg.sidebar')

    @yield('content')

    @js("bower_components/jquery/dist/jquery.min.js")
    @js("dist/js/adminlte.min.js")
    @js("dist/js/demo.js")
    @js("bower_components/jquery-slimscroll/jquery.slimscroll.min.js")
    @js("bower_components/fastclick/lib/fastclick.js")
    @js("bower_components/bootstrap/dist/js/bootstrap.min.js")
    @js("bower_components/chart.js/Chart.min.js")
    @js("bower_components/datatables.net/js/jquery.dataTables.min.js")
    @js("bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js")
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    @js("js/models/modal.js")
    @js("js/models/fetch.js")
</body>

</html>