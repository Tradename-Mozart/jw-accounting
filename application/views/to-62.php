<div class="row d-flex justify-content-center">
<div class="col-lg-6 col-md-offset-3 well">
		
		<?php 
		if($this->session->flashdata('errorTO-62-Currency'))
		{
			if($this->session->flashdata('errorTO-62-Currency') == $to_62_currency)
			{
				$this->load->view('validation_error'); 
			}
		}
		
		?>
        <h1><?= $to_62_currency ?></h1>
	<div class="row">
				<?php echo form_open_multipart('post-to62', array('role' => 'form')); ?>
					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-6 form-group  <?php echo (form_error('transdate'.$to_62_currency)?"form-group has-warning":""); ?>">
								<label>Date Of Transanction <span class="fas fa-calendar"></span></label>
								<input placeholder="dd/mm/yyyy" class="form-control" type="date" name="transdate<?= $to_62_currency ?>"
                                value="<?php echo set_value('transdate'.$to_62_currency); ?>" 
                                id="<?php echo (form_error('transdate'.$to_62_currency)?"inputError":""); ?>" required>
							</div>
							<div class="col-sm-6 form-group <?php echo (form_error('transMethod'.$to_62_currency)?"form-group has-warning":""); ?>">
								<label>Transfer Method</label>
                                <select class="form-control" name='transMethod<?= $to_62_currency ?>' id="<?php echo (form_error('transMethod'.$to_62_currency)?"inputError":""); ?>">
                                
                                <?php echo (set_value('transMethod'.$to_62_currency) == 'select...') ? '<option value="0" selected>' : '<option value="0">';?>
                                select...</option>
								<?php echo (set_value('transMethod'.$to_62_currency) == 'AT') ? '<option value="AT" selected>' : '<option value="AT">';?>
                                Automatic Transfer</option>
								<?php echo (set_value('transMethod'.$to_62_currency) == 'ET_DDBA') ? '<option value="ET_DDBA" selected>' : '<option value="ET_DDBA">';?>
                                Electronic Transfer or Deposit to a Branch BankAccount</option>
								<?php echo (set_value('transMethod'.$to_62_currency) == 'C_MO') ? '<option value="C_MO" selected>' : '<option value="C_MO">';?>
                                Check orMoney Order</option>                                                             
                                </select>
							</div>
                            <div class="col-sm-12 form-group <?php echo (form_error('refno'.$to_62_currency)?"form-group has-warning":""); ?>">
								<label>Transanction Reference Number</label>
								<input placeholder="Enter Reference Number Here..." class="form-control" type="text" name="refno<?= $to_62_currency ?>"
                                value="<?php echo set_value('refno'.$to_62_currency); ?>"
                                id="<?php echo (form_error('refno'.$to_62_currency)?"inputError":""); ?>" >
							</div>

                            <?php foreach($to_62Det as $to_62Det_each) { ?>
							<div class="col-sm-12 form-group <?php echo (form_error('input'.$to_62Det_each->tbl_to_62_trans_type_id.$to_62_currency)?"form-group has-warning":""); ?>">
								<label><?= $to_62Det_each->description ?></label>
								<input placeholder="0.00" class="form-control" type="text" name="input<?= $to_62Det_each->tbl_to_62_trans_type_id ?><?= $to_62_currency ?>"
                                value="<?= (set_value('input'.$to_62Det_each->tbl_to_62_trans_type_id.$to_62_currency)?set_value('input'.$to_62Det_each->tbl_to_62_trans_type_id.$to_62_currency):$to_62Det_each->amount); ?>"
                                id="<?php echo (form_error('input'.$to_62Det_each->tbl_to_62_trans_type_id.$to_62_currency)?"inputError":""); ?>" required>
							</div>
                            <?php } ?>

							
								<input name="currID" value="<?= $to_62_currency_id ?>"  hidden>
								<input name="currName" value="<?= $to_62_currency ?>"  hidden>

						</div>												
						                    
					<button type="submit" class="btn btn-lg btn-info">Post</button>					
					</div>
				<?php echo form_close(); ?> 
				</div>
	</div>
</div>