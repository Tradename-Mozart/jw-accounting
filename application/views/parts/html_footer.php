<!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Deletion-->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Entry</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Delete" below if you are sure you want to delete the entry.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <span id="delBtn"><a class="btn btn-primary" href="#">Delete</a></span>
                </div>
            </div>
        </div>
    </div>


	<!-- Report Extract Modal-->
    <div class="modal fade" id="reportExtractModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Extract Report</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                 <div class="row d-flex justify-content-center">   
                <div><i class="fas fa-file-pdf fa-10x"></i></div>

                <div class="spinner-border text-primary" id="spinnerLoad" role="status">
                      <span class="sr-only">Loading...</span>
                    </div>
                
                <div class="col-md-12">
                    <div id="downloadJwPDF" class="row d-flex justify-content-center"></div>   
                </div>

                </div>

                    
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url() ?>static/vendor/jquery/jquery-3.5.1.js"></script>
    <script src="<?= base_url() ?>static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url() ?>static/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url() ?>static/js/sb-admin-2.min.js"></script>

	<!-- DataTables JavaScript -->
    <script src="<?= base_url(); ?>static/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>static/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>static/vendor/datatables/dataTables.buttons.min.js"></script>
    <script src="<?= base_url(); ?>static/vendor/datatables/buttons.html5.min.js"></script>
    <script src="<?= base_url(); ?>static/vendor/datatables/jszip.min.js"></script>
    <script src="<?= base_url(); ?>static/vendor/datatables/pdfmake.min.js"></script>
    <script src="<?= base_url(); ?>static/vendor/datatables/vfs_fonts.js"></script>
    <script src="<?= base_url(); ?>static/js/jw-accounting_ajax.js?v=2"></script>
    <script src="<?= base_url(); ?>static/js/jw-accounting_js.js"></script>
	
	<script type="text/javascript">
    
	</script>

</body>

</html>