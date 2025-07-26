<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $dp; ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= $username; ?></p>
                <a href="#">
                    <i class="fa fa-circle text-success"></i>
                    Online
                </a>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">
                PEFCARBONSINK MONITORING SYSTEM
            </li>
            <li class="refo treeview">
                <a href="#">
                    <i class="fa fa-dashboard"></i>
                    <span>Reforestation</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="./">
                            <i class="fa  fa-pie-chart"></i>
                            Tree Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="plantingsched">
                            <i class="fa fa-calendar"></i>
                            Planting Schedules
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nursery treeview">
                <a href="#">
                    <i class="fa fa-leaf"></i>
                    <span>Nursery Production</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="b">
                        <a href="nursery">
                            <i class="fa  fa-pie-chart"></i>
                            Nursery Dashboard
                        </a>
                    </li>
                    <li class="nursery-updates">
                        <a href="#" onclick="sendPost('nurs_logs')">
                            <i class="fa fa-list-alt"></i>
                            Activity Logs
                        </a>
                    </li>
                </ul>
            </li>
            <li class="webapp treeview">
                <a href="#">
                    <i class="fa fa-chrome"></i>
                    <span>Web Apps</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="c">
                        <a href="mapviewer">
                            <i class="fa fa-map"></i>
                            Map viewer
                        </a>
                    </li>
                </ul>
            </li>

            @if($role === 'superadmin' || $role === 'admin')
                <li class="dataManagement treeview">
                    <a id="dataManangement" href="#">
                        <i class="fa fa-cogs"></i>
                        <span>Data management</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="data-kobo">
                            <a href="#" onclick="sendPost('kobo')"><i class="fa fa-circle-o"></i>Kobotoolbox </a>
                        </li>
                        <li class="data-validation">
                            <a href="#" onclick="sendPost('admin_validate_data')">
                                <i class="fa fa-circle-o"></i>Validate reforestation data </a>
                        </li>
                        <li class="manage-nursery" id="manage-nursery">
                            <a href="https://www.nursery.pefcarbonsink.info/?redirect=<?= $ident; ?>" target="_blank">
                                <i class="fa fa-circle-o"></i>Manage nursery data </a>
                        </li>
                        <li class="manage-qrcode">
                            <a href="https://www.qrscanner.pefcarbonsink.info/?redirect=<?= $ident; ?>" target="_blank">
                                <i class="fa fa-circle-o"></i>Manage QR Codes
                            </a>
                        </li>
                        <li class="manage-user">
                            <a href="#" onclick="sendPost('manage_user')">
                                <i class="fa fa-circle-o"></i>Manage users </a>
                        </li>
                        <li class="add-spatial-data">
                            <a href="#" onclick="sendPost('add_polygon')"><i class="fa fa-circle-o">
                                </i>Add spatial data </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if($role === 'superadmin' || $role === 'admin')
                <li class="treeview">
                    <a href="#">
                        <span>Online users</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul id="online-users" class="treeview-menu"></ul>
                </li>
            @endif
        </ul>
    </section>
</aside>