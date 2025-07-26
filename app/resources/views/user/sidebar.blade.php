<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/user/dashboard" class="brand-link mt-2 mb-3">
        <!-- <img src="dist/img/PEF_LOGO_WBG.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
        <img src="{{ Route::assets('PEF_LOGO_WBG.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3 mb-1" style="opacity: .8">
        <span class="brand-text font-weight-light mb-1"><b><?php echo $org; ?> </b>Carbon Forest</span>
    </a>
    <div class="user-panel mt-3 pb-3 mb-2 d-flex"></div>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
            <img src="{{ Route::assets('PEF_LOGO_WBG.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
            <a href="user/profile" class="d-block"><?php echo $username; ?></a>
            </div>
        </div> -->

        <!-- SidebarSearch Form -->
        <div class="form-inline mb-3">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
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
                            <a href="/user/dashboard"
                                class="nav-link {{ $_SERVER['REQUEST_URI'] == '/user/dashboard' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tree Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/user/data"
                                class="nav-link {{ $_SERVER['REQUEST_URI'] == '/user/data' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Planting Schedule</p>
                            </a>
                        </li>
                    </ul>
                    <!-- <div class="user-panel mt-1 pb-1 mb-2 d-flex"></div>
                <li class="nav-item">
                <a href="/logout" class="nav-link">
                    <i class="nav-icon fas fa-arrow-circle-right"></i>
                    <p>
                    Logout
                    </p>
                </a>
                </li> -->
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>