$(function(e) {
	// Data-table1 (Demo)
	if ($('#data-table1').length) {
		$('#data-table1').DataTable();
	}

	// Data-table2 (Demo)
	if ($('#data-table2').length) {
		var table = $('#data-table2').DataTable();
		$('#data-table2 button').click(function() {
			var data = table.$('input, select').serialize();
			return false;
		});
	}

	// Data-table3 (Demo)
	if ($('#data-table3').length && $.fn.dataTable && $.fn.dataTable.Responsive && $.fn.dataTable.Responsive.display) {
		$('#data-table3').DataTable({
			responsive: {
				details: {
					display: $.fn.dataTable.Responsive.display.modal({
						header: function (row) {
							var data = row.data();
							return 'Details for ' + data[0] + ' ' + data[1];
						}
					}),
					renderer: $.fn.dataTable.Responsive.renderer.tableAll({
						tableClass: 'table'
					})
				}
			}
		});
	}
});
