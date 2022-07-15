<div class="row d-flex justify-content-center">
<div class="col-lg-6 col-md-offset-3 well">
		
		<?php 
        
            if($this->session->flashdata('error-cash-box-standing'))
            {
                
                    $this->load->view('validation_error'); 
            }

        ?>
        
    <h1><?= $_SESSION['default_currency']->currency ?></h1>

	<div class="row">
				<?php echo form_open_multipart('post-cash-box-standing', array('role' => 'form')); ?>
					<div class="col-sm-12">
						
                    <div class="row">
                        <div class="col-sm-12 form-group <?php echo (form_error('cash_in_box')?"form-group has-warning":""); ?>">
								<label>Cash In Box</label>
								<input placeholder="0.00" class="form-control" type="text" name="cash_in_box"
                                value="<?= (set_value('cash_in_box')?set_value('cash_in_box'):$cashStand->cash_in_box); ?>"
                                id="<?php echo (form_error('cash_in_box')?"inputError":""); ?>" required>
						</div>
					</div>
                    
                    <div class="row">
                        <div class="col-sm-12 form-group <?php echo (form_error('comp_pay_no_record')?"form-group has-warning":""); ?>">
								<label>Completed payments not yet recorded</label>
								<input placeholder="0.00" class="form-control" type="text" name="comp_pay_no_record"
                                value="<?= (set_value('comp_pay_no_record')?set_value('comp_pay_no_record'):$cashStand->complete_pay_not_recorde); ?>"
                                id="<?php echo (form_error('comp_pay_no_record')?"inputError":""); ?>" >
						</div>
					</div>

                    <div class="row">
                        <div class="col-sm-12 form-group <?php echo (form_error('cash_adv_no_clr')?"form-group has-warning":""); ?>">
								<label>Cash advances not yet cleared</label>
								<input placeholder="0.00" class="form-control" type="text" name="cash_adv_no_clr"
                                value="<?= (set_value('cash_adv_no_clr')?set_value('cash_adv_no_clr'):$cashStand->cash_adv_not_clr); ?>"
                                id="<?php echo (form_error('cash_adv_no_clr')?"inputError":""); ?>" >
						</div>
					</div>
						                    
					<button type="submit" class="btn btn-lg btn-info">Post</button>					
					</div>
				<?php echo form_close(); ?> 
				</div>
	</div>
</div>