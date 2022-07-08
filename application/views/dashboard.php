<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <a href="#" class=" d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    <a href="#" class=" d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
    class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
</div>


 <!-- Card Row -->
 <div class="row">

<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Congregation Name</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $congregation_name ?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-building fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        City, Province Or State</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $city ?>, <?= $province_state ?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-map-marker fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Pending Requests Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Current Period</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $sequenceno ?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Requests Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    PlaceHolder</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"> PlaceHolder</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<!-- Card Row -->

<!-- Content Row -->

<div class="row">


      <!-- Area Chart -->
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <!-- Nav Tabls-->
                <ul class="nav nav-pills " role="tablist">
                    <li class="nav-item">
                    <a class="nav-link <?php echo ($navPillSelect == 'ledgers26'?"active":""); ?>" data-toggle="tab" href="#ledgers26">
                    Ledger S-26 Current Period
                    </a>
                    </li>
                    <li class="nav-item ">
                    <a class="nav-link <?php echo ($navPillSelect == 'capture-trans'?"active":""); ?>" data-toggle="tab" href="#capture-trans">
                    Capture Transanction
                    </a>
                    </li>
                    <li class="nav-item ">
                    <a class="nav-link <?php echo ($navPillSelect == 'process-TO62'?"active":""); ?>" data-toggle="tab" href="#process-TO62">
                    Process TO-62
                    </a>
                    </li>
                </ul>                
                <!-- Nav Tabls-->
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
            <div class="tab-content">
            <div id="ledgers26" class="container tab-pane <?php echo ($navPillSelect == 'ledgers26'?"active":"fade"); ?>"><br>
            <?php $this->load->view('ledger-s26'); ?>
            </div>
            <div id="capture-trans" class="container tab-pane <?php echo ($navPillSelect == 'capture-trans'?"active":"fade"); ?>"><br>
            <?php $this->load->view('capture-trans'); ?>
            </div>
            <div id="process-TO62" class="container tab-pane <?php echo ($navPillSelect == 'process-TO62'?"active":"fade"); ?>"><br>
            <?php $dataForTO62['to_62Det'] = $vw_to_62;
                  $this->load->view('to-62', $dataForTO62); 
            ?>
            </div>
            </div>
            </div>
        </div>
    </div>

    
</div>

<!-- Content Row -->
</div>
<!-- /.container-fluid -->

				