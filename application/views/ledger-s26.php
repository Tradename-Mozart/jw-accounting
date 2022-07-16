<?php $this->load->view('validation_success'); 
      if($this->session->flashdata('error-ledger-s26'))
        {
            
                $this->load->view('validation_error'); 
        }
?>


<h1><?= $_SESSION['default_currency']->currency ?></h1>
            <div class="table-responsive">
                <table width="100%" class="table table-striped table-bordered table-hover table-products" id="ledger_s26">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>TC</th>
                            <th>Receipts In</th>
                            <th>Receipts Out</th>
                            <th>Primary In</th>
                            <th>Primary Out</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

				