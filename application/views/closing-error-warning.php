<div class="row">
<!-- Earnings (Monthly) Card Example -->
<?php 
	if(isset($vw_error_warning[0]->type))
		{
	foreach($vw_error_warning as $error_each) 
			{ 	    
?>
<div class="col-lg-6 col-md-6 mb-4">
    <div class="card <?= ($error_each->type == 'Error')?'border-left-danger':'border-left-warning' ?> shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold <?= ($error_each->type == 'Error')?'text-danger':'text-warning' ?> text-uppercase mb-1">
                        <?= $error_each->heading ?></div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $error_each->descrip ?></div>
                </div>
                <div class="col-auto">
				<div class="icon-circle <?= ($error_each->type == 'Error')?'bg-danger':'bg-warning' ?> ">
                   <i class="fas fa-exclamation-triangle text-white"></i>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } 
	} 
?>
</div>
<div class="row d-flex justify-content-center">
<div class="col-lg-6 col-md-offset-3 well">
		
		<?php 
		if($this->session->flashdata('errorClosingPeriod'))
		{
			
				$this->load->view('validation_error'); 
		}
		
		?>

		

	<div class="row">
				<?php echo form_open_multipart('process-close-period', array('role' => 'form')); ?>
					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-12 form-group <?php echo (form_error('confirm_det')?"form-group has-warning":""); ?>">
                                            <label>Confirm Closure Of Period</label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="confirm_det" value="s1" id="<?php echo (form_error('confirm_det')?"inputError":""); ?>">
                                            </label>
                            </div>

						</div>												
						                    
					<button type="submit" class="btn btn-lg btn-info" <?= ($countErrors>0)?'disabled':'' ?>>
						Close Period
					</button>					
					</div>
				<?php echo form_close(); ?> 
				</div>
	</div>
</div>