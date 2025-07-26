<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="@csrf">
  <link rel="icon" href="{{ Route::assets('PEF_LOGO.png') }}" type="png">
  <title>PEF-CSD | {{{ $org }}}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  @css("ayala/plugins/fontawesome-free/css/all.min.css")
  <!-- overlayScrollbars -->
  @css("ayala/plugins/overlayScrollbars/css/OverlayScrollbars.min.css")
  <!-- Theme style -->
  @css("ayala/dist/css/adminlte.min.css")

  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  @css("ayala/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css")
  <!-- iCheck -->
  @css("ayala/plugins/icheck-bootstrap/icheck-bootstrap.min.css")
  <!-- JQVMap -->
  @css("ayala/plugins/jqvmap/jqvmap.min.css")
  <!-- Daterange picker -->
  @css("ayala/plugins/daterangepicker/daterangepicker.css")
  <!-- summernote -->
  @css("ayala/plugins/summernote/summernote-bs4.min.css")

  <!-- DataTables -->
  @css("ayala/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css")
  @css("ayala/plugins/datatables-responsive/css/responsive.bootstrap4.min.css")
  @css("ayala/plugins/datatables-buttons/css/buttons.bootstrap4.min.css")


  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
    crossorigin="anonymous" /><!--NEED NI-->
  <!--end::Third Party Plugin(Bootstrap Icons)-->
  <!--begin::Required Plugin(AdminLTE)-->
  <!-- <link rel="stylesheet" href="../../dist/css/adminlte.css" /> -->
  <!-- @css("ayala/dist/css/css4/adminlte.css") -->
  <!--end::Required Plugin(AdminLTE)-->



</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">

    @include('user.preloader')

    @include('user.header')

    @include('user.sidebar')

    @yield('content')

    @include('user.footer')



    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    @js("ayala/plugins/jquery/jquery.min.js")
    <!-- Bootstrap -->
    @js("ayala/plugins/bootstrap/js/bootstrap.bundle.min.js")
    <!-- overlayScrollbars -->
    @js("ayala/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js")
    <!-- AdminLTE App -->
    @js("ayala/dist/js/adminlte.js")

    <!-- PAGE PLUGINS -->
    <!-- jQuery Mapael -->
    @js("ayala/plugins/jquery-mousewheel/jquery.mousewheel.js")
    @js("ayala/plugins/raphael/raphael.min.js")
    @js("ayala/plugins/jquery-mapael/jquery.mapael.min.js")
    @js("ayala/plugins/jquery-mapael/maps/usa_states.min.js")
    <!-- ChartJS -->
    @js("ayala/plugins/chart.js/Chart.min.js")

    <!-- AdminLTE for demo purposes -->
    @js("ayala/dist/js/demo.js")
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    @js("ayala/dist/js/pages/dashboard2.js")


    <!-- jQuery UI 1.11.4 -->
    @js("ayala/plugins/jquery-ui/jquery-ui.min.js")
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button)
    </script>
    @js("ayala/plugins/sparklines/sparkline.js")
    <!-- JQVMap -->
    @js("ayala/plugins/jqvmap/jquery.vmap.min.js")
    @js("ayala/plugins/jqvmap/maps/jquery.vmap.usa.js")
    <!-- jQuery Knob Chart -->
    @js("ayala/plugins/jquery-knob/jquery.knob.min.js")
    <!-- daterangepicker -->
    @js("ayala/plugins/moment/moment.min.js")
    @js("ayala/plugins/daterangepicker/daterangepicker.js")
    <!-- Tempusdominus Bootstrap 4 -->
    @js("ayala/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js")
    <!-- Summernote -->
    @js("ayala/plugins/summernote/summernote-bs4.min.js")
    @js("ayala/dist/js/pages/dashboard.js")



    <!-- DataTables  & Plugins -->
    @js("ayala/plugins/datatables/jquery.dataTables.min.js")
    @js("ayala/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js")
    @js("ayala/plugins/datatables-responsive/js/dataTables.responsive.min.js")
    @js("ayala/plugins/datatables-responsive/js/responsive.bootstrap4.min.js")
    @js("ayala/plugins/datatables-buttons/js/dataTables.buttons.min.js")
    @js("ayala/plugins/datatables-buttons/js/buttons.bootstrap4.min.js")
    @js("ayala/plugins/jszip/jszip.min.js")
    @js("ayala/plugins/pdfmake/pdfmake.min.js")
    @js("ayala/plugins/pdfmake/vfs_fonts.js")
    @js("ayala/plugins/datatables-buttons/js/buttons.html5.min.js")
    @js("ayala/plugins/datatables-buttons/js/buttons.print.min.js")
    @js("ayala/plugins/datatables-buttons/js/buttons.colVis.min.js")

    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)-->
  </div>
</body>

</html>