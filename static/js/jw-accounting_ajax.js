$(document).ready(function () {
	$('#all_plays_port_zimnat').dataTable( {
		"searching":false,
		"lengthChange": true,
		"sPaginationType": "full_numbers",
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": datatable_url+"/ajax/all_plays_port_zimnat.php",
        "fnServerParams": function ( aoData ) {
		aoData.push( { "name": "hascontact", "value": function() { return hascontact } } );
		},
		/*dom: 'Bfrtip',
		buttons: [
			{
				extend: 'excelHtml5',
				title: function() { return  'All Plays AS AT '+getCurrentDate() },
				text: '<i class="fas fa-file-excel fa-sm text-white-50"> Download Excel</i>',
				className: 'btn btn-sm btn-primary shadow-sm',
				exportOptions: {
					modifier: {
					  page: 'all',
					  search: 'none'   
					}
				 }
			},
			,
			{
				extend: 'pdfHtml5',
				title: function() { return  'All Plays AS AT '+getCurrentDate() },
				text: '<i class="fas fa-file-pdf fa-sm text-white-50"> Download pdf</i>',
				className: 'btn btn-sm btn-primary shadow-sm'
			}
		]*/
        } );
        
	$('#campaign_list').dataTable( {
		"searching":false,
		"lengthChange": false,
		"sPaginationType": "full_numbers",
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": datatable_url+"/ajax/campaign_list.php",
		"columns":  [
			{ "width": "100%" }
		  ],
		"fnServerParams": function ( aoData ) {
			aoData.push( { "name": "campaignListID", "value": function() { return campaignListID } } );
			},
		"drawCallback": function( settings ) {
				$("#campaign_list thead").remove();
			},
		"createdRow": function( row, data, dataIndex ) {
			//console.log(data[1]);			
			$(row).addClass( 'row_id_'+data[1] );
			if ( data[1] == campaignListID) {
				$(row).addClass( 'isselected' );
			}
			}  
		} );

	$('#leaderboad_per_Campaign').dataTable( {
		"searching":false,
		"lengthChange": false,
		"sPaginationType": "full_numbers",
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": datatable_url+"/ajax/leaderboad_per_Campaign.php",
		"fnServerParams": function ( aoData ) {
			aoData.push( { "name": "campaignListID", "value": function() { return  0 }} );
			},
		dom: 'Bfrtip',
		buttons: [
            {
				extend: 'excelHtml5',
				title: function() { return  'LeaderBoard For Campaign Ending '+latestEndDte },
                text: '<i class="fas fa-file-excel fa-sm text-white-50"> Download Excel</i>',
				className: 'btn btn-sm btn-primary shadow-sm'
            },
			,
            {
                extend: 'pdfHtml5',
                title: function() { return  'LeaderBoard For Campaign Ending '+latestEndDte },
                text: '<i class="fas fa-file-pdf fa-sm text-white-50"> Download pdf</i>',
				className: 'btn btn-sm btn-primary shadow-sm'
            }
        ]
		} );

});