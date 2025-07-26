<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="@csrf">
    <link rel="icon" href="{{ Route::assets('PEF_LOGO.png') }}" type="png">
    <title>PEF-CSD | AYALA</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
     @css("ayala/plugins/fontawesome-free/css/all.min.css")
    <!-- overlayScrollbars -->
     @css("ayala/plugins/overlayScrollbars/css/OverlayScrollbars.min.css")
    <!-- Theme style -->
     @css("ayala/dist/css/adminlte.min.css")

    <!-- DataTables -->
     @css("ayala/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css")
     @css("ayala/plugins/datatables-responsive/css/responsive.bootstrap4.min.css")
     @css("ayala/plugins/datatables-buttons/css/buttons.bootstrap4.min.css")
    
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

    @include('ayala.preloader')

    @include('ayala.header')

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index.php" class="brand-link">
        <!-- <img src="dist/img/PEF_LOGO_WBG.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
        <img src="{{ Route::assets('PEF_LOGO_WBG.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><b>AYALA</b>Carbon Forest</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
            <img src="{{ Route::assets('PEF_LOGO_WBG.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
            <a href="profile.php" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
            <li class="nav-item menu-open">
                <a href="#" class="nav-link active bg-gradient-primary">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Reforestation
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="ayala" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Tree Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="data.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Planting Schedule</p>
                    </a>
                </li>
                </ul>
                <div class="user-panel mt-1 pb-1 mb-2 d-flex"></div>
                <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-arrow-circle-right"></i>
                    <p>
                    Logout
                    </p>
                </a>
                </li>
            </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    @yield('content')

    <footer class="main-footer">
        <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.2.0
        </div>
    </footer>

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
</div>
</body>

</html>