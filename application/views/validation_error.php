<?php
            if (validation_errors() || $this->session->flashdata('userError')) {
                ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <h3><?= $this->session->flashdata('userError') ?></h3>
                <?php if(validation_errors()) echo validation_errors(); ?>
                <?php if($this->session->flashdata('errorDesc')) echo $this->session->flashdata('errorDesc'); ?>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    			<span aria-hidden="true">&times;</span>
				</div>
                <?php
            }
            ?>