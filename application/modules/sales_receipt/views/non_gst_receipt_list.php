<?php
    $theme_path = $this->config->item('theme_locations');
?>
<style>
.mt-top15 {
    margin-top: 15px;}
.action-btn-align {
    text-align: center;}
    .action-icon {
    color: #ffffff;
    padding: 0 5px!important;}
</style>
<div class="mainpanel">
    <div class="media mt--20">
        <h4>Receipt List </h4>
    </div>
    <div class="container-fluid">
    <div class="row">        
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
        <div id='result_div' class="panel-body">
        <table class="display table" id="basic">
                <thead>
                    <tr>
                        <th class="action-btn-align">S.No</th>
                        <th class='action-btn-align'>Invoice #</th>
                        <th class="action-btn-align">Customer Name</th>
                        <th class="action-btn-align">Invoice Amount</th>
                        <!--<th class="action-btn-align">Return Amount</th>-->
                        <th class="action-btn-align">Paid Amount</th>
                        <th class="action-btn-align">Discount Amount</th>
                        <th class="action-btn-align">Balance</th>
                        <th class="action-btn-align">Invoice Date</th>
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
                        <td class="text_right total-bg"></td>
                        <td class="text_right total-bg"><?= number_format($bal, 2, '.', ',') ?></td>
                        <td class=""></td>
                        <td class=""></td>
                        <td class=""></td>
                        <td class=""></td>
                        <td class=""></td>
                    </tr>
                </tfoot>
            </table>
            <div class="action-btn-align mt-top15">
                <button class="btn btn-defaultprint6 btn-info print_btn"><span class="glyphicon glyphicon-print"></span> Print</button>
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
                      </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
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
<script type="text/javascript">
    $(document).ready(function () {
        var table;
        $(document).ready(function(){
            var table = $('#basic').DataTable({
            "processing":true,
			"serverSide":true,
			"order":[], 
			"ajax": {
				url : "<?php echo base_url('sales_receipt/non_gst_receipt/ajaxList/'); ?>",
				type: "POST"  
			},
			 "columnDefs":[  
			// 	{  
			// 		"targets":[0],  
			// 		"orderable":false,
			// 	}, 
                {  
					"targets":[0,2,4,5,6,7,8],  
					"className":"text-center"
				},
                 {  
			 		"targets":[3],  
			 		"className":"text-right"
			 	}, 
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
            responsive: true,
            columnDefs: [
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -2}
            ]
        });
        new $.fn.dataTable.FixedHeader(table);

    });
});
</script>
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-time-picker/moment.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-time-picker/tempusdominus-bootstrap-4.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-time-picker/datetimepicker.custom.js"></script>