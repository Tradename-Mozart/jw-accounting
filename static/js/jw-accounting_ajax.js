$(document).ready(function () {
	
	$('#ledger_s26').dataTable( {
		"searching":false,
		"lengthChange": false,
		"columns": [
			{ "width": "2%" },
			{ "width": "51%" },
			{ "width": "5%" },
			{ "width": "10%" },
			{ "width": "10%" },
			{ "width": "10%" },
			{ "width": "10%" },
			{ "width": "2%" },
		  ],
		"pageLength": 50,
		"sPaginationType": "full_numbers",
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": datatable_url+"/ajax/ledger_s26.php",
		"fnServerParams": function ( aoData ) {
			aoData.push( { "name": "period_id", "value": function() { return  period_id }},
						 { "name": "currency_id", "value": function() { return  currency_id }} );
			},
		dom: 'Bfrtip',
		buttons: [
            {
				extend: 'excelHtml5',
				title: function() { return  'Working S-26 For Period '+sequenceno },
                text: '<i class="fas fa-file-excel fa-sm text-white-50"> Download Excel</i>',
				className: 'btn btn-sm btn-primary shadow-sm'
            },
			,
            {
                extend: 'pdfHtml5',
                title: function() { return  'Working S-26 For Period '+sequenceno },
                text: '<i class="fas fa-file-pdf fa-sm text-white-50"> Download pdf</i>',
				className: 'btn btn-sm btn-primary shadow-sm'
            }
        ],
		"createdRow": function( row, data, dataIndex ) {
			//console.log(data[1]);			
			$(row).addClass( 'row_id_'+data[0] );
			if ( data[0] == '') {
				$(row).addClass( 'isselected' );
			}
			} 
		} );

		$('#period_reports').dataTable( {
			"searching":false,
			"lengthChange": false,
			"pageLength": 50,
			"sPaginationType": "full_numbers",
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": datatable_url+"/ajax/period_reports.php",
			"fnServerParams": function ( aoData ) {
				aoData.push( { "name": "period_id", "value": function() { return  period_id }},
							 { "name": "currency_id", "value": function() { return  currency_id }} );
				}
			} );

});