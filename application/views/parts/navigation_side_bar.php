<!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="login">
                <div class="sidebar-brand-icon rotate-n-15">
                   <?php echo img(array(
                                                        'src'   => 'static/img/green-chart-arrow.png',
                                                        'alt'   => 'rewards icon',
                                                        'title' => 'rewards icon',
                                                        'width' => '60',
                                                        'height'=> '60',
														'class' => 'img-profile rounded-circle'
                                                )); ?>
                </div>
                <div class="sidebar-brand-text mx-3">JW Accounting System</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?= uri_string()=='login'?'active':'' ?>">
                <a class="nav-link" href="login">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider"> 
            
            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?= uri_string()=='standing-approvals'?'active':'' ?>">
                <a class="nav-link" href="standing-approvals">
                    <i class="fas fa-file-archive"></i>
                    <span>Standing Approvals</span></a>
            </li>

        </ul>
        <!-- End of Sidebar -->