<!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- currency options dropdown -->

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['default_currency']->currency ?></span>
                            </a>
                            <!-- currency options for selecting -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <?php foreach($_SESSION['currencies'] as $each_currency) 
                                      { if($each_currency->currency_id != $_SESSION['default_currency']->currency_id)
                                        {                                
                                ?>
                                <a class="dropdown-item" href="switch-currency/<?= $each_currency->currency_id ?>" >
                                    <i class="fas fa-money-bill fa-sm fa-fw mr-2 text-gray-400"></i>
                                    <?= $each_currency->currency ?>
                                </a>
                                <?php } }?>
                            </div>
                        </li>

                        <!-- EOF currency options dropdown -->

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1 show">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <i class="fas fa-piggy-bank fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <!--span class="badge badge-danger badge-counter">3+</span-->
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Cash Box
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-success">Total Cash In Box</div>
                                        <span class="font-weight-bold"><?= $vw_cash_box_snap->amount_in_cash_box?></span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-globe-americas text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-success">Cash For World Wide Conributions</div>
                                        <?= $vw_cash_box_snap->ww_from_contri_box?>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-hand-holding-usd text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-success">Cash For Congregation Usage</div>
                                        <?= $vw_cash_box_snap->amount_in_cash_box_less_ww?>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-hand-holding-usd text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-success">Cash For Congregation Usage Less Mon Resol</div>
                                        <?= $vw_cash_box_snap->amount_for_congr_use_less_reslov?>
                                    </div>
                                </a>
                                <!--a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a-->
                            </div>
                        </li>


                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter"></span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <!--<a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg"
                                            alt="">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>-->
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['user']->username ?></span>
								<?php echo img(array(
                                                        'src'   => 'static/img/undraw_profile.svg',
                                                        'alt'   => 'avatar',
                                                        'title' => 'avatar',
														'class' => 'img-profile rounded-circle'
                                                )); ?>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->