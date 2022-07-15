<div class="row d-flex justify-content-center">
<div class="col-lg-6 col-md-offset-3 well">
		
		<?php 
		if($this->session->flashdata('errorResolvMonContri'))
		{
			
				$this->load->view('validation_error'); 
		}
		
		?>
        <h1><?= $_SESSION['default_currency']->currency ?></h1>
	<div class="row">
				<?php echo form_open_multipart('place-holder', array('role' => 'form')); ?>
					<div class="col-sm-12">
						<div class="row">
                            <div class="col-sm-12 form-group <?php echo (form_error('resolvMon')?"form-group has-warning":""); ?>">
								<label>Resolved Monthly Contributions</label>
								<input placeholder="Enter Reference Number Here..." class="form-control" type="text" name="resolvMon"
                                value="<?php echo (set_value('resolvMon'))?set_value('resolvMon'):(isset($resolvMonContrib->amount)?$resolvMonContrib->amount:0.00); ?>"
                                id="<?php echo (form_error('resolvMon')?"inputError":""); ?>" >
							</div>

						</div>												
						                    
					<button type="submit" class="btn btn-lg btn-info">Post</button>					
					</div>
				<?php echo form_close(); ?> 
				</div>
	</div>
</div>