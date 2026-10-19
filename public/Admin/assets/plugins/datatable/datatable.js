$(function(e) {

	//Data-table1
	$('#data-table1').DataTable();

	//Data-table2
	if ($('#data-table2').length) {
		var table = $('#data-table2').DataTable();
		$('#data-table2 button').click( function() {
			var data = table.$('input, select').serialize();
			return false;
		});
	}

	//Data-table3
	$('#data-table3').DataTable( {
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        var data = row.data();
                        return 'Details for '+data[0]+' '+data[1];
                    }
                } ),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                    tableClass: 'table'
                } )
            }
        }
    } );

	// Export Data-table
	// var table = $('#exportexample').DataTable( {
	// 	lengthChange: false,
	// 	buttons: [ 'copy', 'excel', 'pdf', 'colvis' ]
	// } );
	// table.buttons().container()
	// .appendTo( '#exportexample_wrapper .col-md-6:eq(0)' );
});
