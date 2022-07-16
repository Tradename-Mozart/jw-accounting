<div class="row d-flex justify-content-center">
<div class="col-lg-6 col-md-offset-3 well">
		
		<?php 
        
            if($this->session->flashdata('captureTransError'))
            {
                
                    $this->load->view('validation_error'); 
            }

        ?>
        
    <h1><?= $_SESSION['default_currency']->currency ?></h1>

	<div class="row">
				<?php echo form_open_multipart('post-transaction', array('role' => 'form')); ?>
					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-6 form-group  <?php echo (form_error('transdate')?"form-group has-warning":""); ?>">
								<label>Date Of Transanction <span class="fas fa-calendar"></span></label>
								<input placeholder="dd/mm/yyyy" class="form-control" type="date" name="transdate"
                                value="<?php echo set_value('transdate'); ?>" 
                                id="<?php echo (form_error('transdate')?"inputError":""); ?>" required>
							</div>
							<div class="col-sm-6 form-group <?php echo (form_error('amount')?"form-group has-warning":""); ?>">
								<label>Amount</label>
								<input placeholder="0.00" class="form-control" type="text" name="amount"
                                value="<?php echo set_value('amount'); ?>"
                                id="<?php echo (form_error('amount')?"inputError":""); ?>" required>
							</div>

                            <div class="col-sm-6 form-group <?php echo (form_error('tc')?"form-group has-warning":""); ?>">
								<label>Transanction Type</label>
                                <select class="form-control" name='tc' id="<?php echo (form_error('tc')?"inputError":""); ?>">
                                
                                <?php echo (set_value('tc') == 'select...') ? '<option value="0" selected>' : '<option onClick="populateDescription(\'\')" value="0">';?>
                                select...</option>
                                <?php foreach($tc_details AS $tc_detail_each) { ?>
                                <?php echo (set_value('tc') == $tc_detail_each->tbl_transaction_code_id) 
                                                               ?'<option value="'.$tc_detail_each->tbl_transaction_code_id.'" onClick="populateDescription(\''.$tc_detail_each->category.'\')" selected>' 
                                                               :'<option value="'.$tc_detail_each->tbl_transaction_code_id.'" onClick="populateDescription(\''.$tc_detail_each->category.'\')">';?>
                                <?= $tc_detail_each->transaction_code ?></option>
                                <?php } ?>

                                </select>
							</div>

							<div class="col-sm-6 form-group <?php echo (form_error('account')?"form-group has-warning":""); ?>">
								<label>Account</label>
                                <select class="form-control" name='account' id="<?php echo (form_error('account')?"inputError":""); ?>">
                                
                                <?php echo (set_value('account') == 'select...') ? '<option value="0" selected>' : '<option value="0">';?>
                                select...</option>
                                <?php foreach($account_details AS $account_detail_each) { ?>
                                <?php echo (set_value('account') == $account_detail_each->tbl_account_id) 
                                                               ?'<option value="'.$account_detail_each->tbl_account_id.'" selected>' 
                                                               :'<option value="'.$account_detail_each->tbl_account_id.'" >';?>
                                <?= $account_detail_each->account ?></option>
                                <?php } ?>
                                
                                </select>
							</div>

                            <div class="col-sm-12 form-group <?php echo (form_error('descrip')?"form-group has-warning":""); ?>">
                                            <label>Description</label>
                                            <textarea class="form-control desTextArea" name="descrip" rows="3" value="<?php echo set_value('descrip'); ?>"
                                            id="<?php echo (form_error('descrip')?"inputError":"desTextArea"); ?>"><?php echo set_value('descrip'); ?></textarea>
                                        </div>

                            
                            

                            <div class="col-sm-12 form-group <?php echo (form_error('confirm_det')?"form-group has-warning":""); ?>">
                                            <label>Confirm Details Are Correct</label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="confirm_det" value="s1" id="<?php echo (form_error('confirm_det')?"inputError":""); ?>">
                                            </label>
                            </div>

						</div>												
						                    
					<button type="submit" class="btn btn-lg btn-info">Post</button>					
					</div>
				<?php echo form_close(); ?> 
				</div>
	</div>
</div>