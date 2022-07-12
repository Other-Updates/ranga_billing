<?php
    $theme_path = $this->config->item('theme_locations');
?>
<script src="<?php echo $theme_path ?>/assets/js/jquery.basictable.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.basictable').basictable({
        breakpoint: 768
        });
    });
</script>
<div class="mainpanel">
    <div class="container-fluid">        
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Payment</h3>
                </div>
                <div class="col-6">                
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                    <li class="breadcrumb-item">Sales Order</li>
                    <li class="breadcrumb-item active">Payment</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">        
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive reference-report">
                            <div id='result_div' class="panel-body">
                                <table class="list-table display basictable table" id="basic">
                                    <thead>
                                        <tr>
                                            <th class="action-btn-align">S.No</th>
                                            <th class='action-btn-align'>Inv Id #</th>
                                            <th class="action-btn-align">Supplier</th>
                                            <th class="action-btn-align">Inv Amt</th>
                                            <!--<th class="action-btn-align">Return Amount</th>-->
                                            <th class="action-btn-align">Paid Amt</th>
                                            <th class="action-btn-align">Dis Amt</th>
                                            <th class="action-btn-align">Balance</th>
                                            <th class="action-btn-align">Inv Date</th>
                                            <th class="action-btn-align">Paid Date</th>
                                            <th class="action-btn-align">Due Date</th>
                                            <th class="action-btn-align">Payment Status</th>
                                            <th class="action-btn-align hide_class">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text_right total-bg"><?= number_format($inv, 2, '.', ',') ?></td>
                                            <!--<td class="text_right total-bg"></td>-->
                                            <!--<td class="text_right total-bg"><?= number_format($advance, 2, '.', ',') ?></td>-->
                                            <td class="text_right total-bg"><?= number_format(($paid + $advance), 2, '.', ',') ?></td>
                                            <td  class="text_right total-bg"></td>
                                            <td class="text_right total-bg"><?= number_format($bal, 2, '.', ',') ?></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
        </div>

        <script>
            $(document).on('click', '.alerts', function () {
                sweetAlert("Oops...", "This Access is blocked!", "error");
                return false;
            });
            $('.print_btn').click(function () {
                window.print();
            });
        </script>
    </div><!-- contentpanel -->

</div><!-- mainpanel -->

<?php
if (isset($all_gen) && !empty($all_gen)) {
    foreach ($all_gen as $val) {
        ?>
        <form method="post" action="<?= $this->config->item('base_url') . 'po/force_to_complete/1' ?>">
            <div id="com_<?= $val['id']; ?>" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" align="center">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header"><a class="close" data-dismiss="modal">×</a>
                            <h4 style="color:#06F">Force to Complete</h4>
                            <h3 id="myModalLabel">
                        </div>
                        <div class="modal-body">

                            <strong>
                                Are You Sure You Want to Complete This PO ?
                            </strong>
                            <table class="table table-striped table-bordered responsive dataTable no-footer dtr-inline">
                                <tr>
                                    <td width="40%" style="text-align:right;" class="first_td1">Remarks&nbsp;</td>
                                    <td>
                                        <input type="text" style="width:220px;" class="form-control" name='complete_remarks' />
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" name="update_id" value="<?= $val['id'] ?>"  />

                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary delete_yes yesin" id="yesin">Yes</button>
                            <button type="button" class="btn btn-danger delete_all"  data-dismiss="modal" id="no"> No</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php
    }
}
?>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/dataTables.buttons.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/jszip.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/pdfmake.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/vfs_fonts.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/buttons.html5.min.js"></script>
<script>
    $(document).ready(function(){
        var table = $('#basic').DataTable({
			"processing":true,
			"serverSide":true,
            "pageLength": 50,
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ],
			"order":[], 
			"ajax": {
				url : "<?php echo base_url('purchase_receipt/ajaxList/'); ?>",
				type: "POST"  
			},
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'S.No');
                $(row).find('td:eq(1)').attr('data-th', 'Inv Id');
                $(row).find('td:eq(2)').attr('data-th', 'Supplier');
                $(row).find('td:eq(3)').attr('data-th', 'Inv Amt');
                $(row).find('td:eq(4)').attr('data-th', 'Paid Amt');
                $(row).find('td:eq(5)').attr('data-th', 'Dis Amt');
                $(row).find('td:eq(6)').attr('data-th', 'Balance');
                $(row).find('td:eq(7)').attr('data-th', 'Inv Date');
                $(row).find('td:eq(8)').attr('data-th', 'paid Date');
                $(row).find('td:eq(9)').attr('data-th', 'Due Date');
                $(row).find('td:eq(10)').attr('data-th', 'Payment Status');
                $(row).find('td:eq(11)').attr('data-th', 'Action');
            },
            "oLanguage": {
            "sInfoFiltered": ""
             },
			 "columnDefs":[  
			// 	{  
			// 		"targets":[0],  
			// 		"orderable":false,
			// 	}, 
                {  
					"targets":[0,10,11,7,8,9],  
					"className":"text-center"
				},
                 {  
			 		"targets":[3,4,5,6],
			 		"className":"text-right"
			 	}, 
                 {  
			 		"targets":[2],  
			 		"className":"text-left"
			 	}
                 
			 ], 
             "dom": 'lBfrtip',
            "buttons": [
                { extend: 'excelHtml5', footer: true },
            ],
             "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
                var cols = [3, 4, 5, 6];
                var numFormat = $.fn.dataTable.render.number('\,', '.', 2).display;
                for (x in cols) {
                    total = api.column(cols[x]).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    console.log("success : " + total);

                    // Total over this page
                    pageTotal = api.column(cols[x], {page: 'current'}).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    // Update footer
//                    if (Math.floor(pageTotal) == pageTotal && $.isNumeric(pageTotal)) {
//                        pageTotal = pageTotal;
//
//                    } else {
//                        pageTotal = pageTotal.toFixed(2);/* float */
//
//                    }
                    $(api.column(cols[x]).footer()).html(numFormat(pageTotal));
                }


            },
            
		});
    });
    $(document).on('click','.removeAttr',function(event){
      event.preventDefault();
        var id = $(this).attr('data-id');
        Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?php echo base_url() . 'sales_receipt/ajaxList/';?>",
                    type: 'POST',
                    data:{id:id},
                    success: function(data) {
                        console.log(id);
                            Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                        );      
                        $('#basic').DataTable().ajax.reload()
                    }
                });
            }
        });
    });
    
</script>
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-time-picker/moment.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-time-picker/tempusdominus-bootstrap-4.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-time-picker/datetimepicker.custom.js"></script>