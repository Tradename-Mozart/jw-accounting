<?php
            if ($this->session->flashdata('userSuccess')) {
                ?>
                <div class="alert alert-success alert-dismissible fade show"><?= $this->session->flashdata('userSuccess') ?>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    			<span aria-hidden="true">&times;</span>
				</div>
                <?php
            }
            ?>