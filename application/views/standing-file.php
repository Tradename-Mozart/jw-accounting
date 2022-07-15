<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
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
                    <a class="nav-link <?php echo ($navPillSelect == 'resolvedMonContr'?"active":""); ?>" data-toggle="tab" href="#resolvedMonContr">
                    Resolved Monthly Contributions
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
            <div id="ledgers26" class="container tab-pane <?php echo ($navPillSelect == 'resolvedMonContr'?"active":"fade"); ?>"><br>
            <?php $data['resolvMonContrib'] = $resolvMonContrib;
            $this->load->view('resolvedMonContrib', $data); 
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

				