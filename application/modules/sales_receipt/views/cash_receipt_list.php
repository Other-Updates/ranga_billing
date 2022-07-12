<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?>
<script src="<?= $theme_path; ?>/js/jquery-1.8.2.js"></script>
<script src="<?= $theme_path; ?>/js/jquery-ui-my-1.10.3.min.js"></script>
<script type='text/javascript' src='<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.css" />
<div class="mainpanel">
    <div class="media mt--20">
        <h4>Sales Receipt List </h4>
    </div>
    <div class="contentpanel mb-45">

        <table class="table table-striped table-bordered responsive no-footer dtr-inline search_table_hide" style="display:none;">
            <tr>


                <td>Supplier
                    <input type="hidden" name="po_no" id="po_no" autocomplete="off" style="width:150px"/>
                    <select id='state' style="width: 170px;">
                        <option>Select</option>
                        <?php
                        if (isset($all_state) && !empty($all_state)) {
                            foreach ($all_state as $val) {
                                ?>
                                <option value='<?= $val['id'] ?>'><?= $val['state'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select id='supplier'  class="form-control" style="width: 170px;">
                        <option>Select</option>
                        <?php
                        if (isset($all_supplier) && !empty($all_supplier)) {
                            foreach ($all_supplier as $val) {
                                ?>
                                <option value='<?= $val['id'] ?>'><?= $val['store_name'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </td>
                <td>From Date</td>
                <td>
                    <div class="input-group" style="width:70%;">
                        <input type="text" id='from_date'  class="form-control datepicker" name="inv_date" placeholder="dd-mm-yyyy" >

                    </div>
                </td>
                <td>To Date</td>
                <td>
                    <div class="input-group" style="width:70%;">
                        <input type="text"  id='to_date' class="form-control datepicker" name="inv_date" placeholder="dd-mm-yyyy" >

                    </div>
                </td>
                <td><a style="float:right;" id='search' class="btn btn-default">Search</a></td>
            </tr>
        </table>
        <div id='result_div' class="panel-body">
            <table id="basicTable" class="table table-striped table-bordered responsive dataTable no-footer dtr-inline">
                <thead>
                    <tr>
                        <td class="action-btn-align">S.No</td>
                        <td class='action-btn-align'>Invoice #</td>
                        <td class="action-btn-align">Company Name</td>
                        <td class="action-btn-align">Invoice Amount</td>
                        <td class="action-btn-align">Advance Amount</td>
                        <td class="action-btn-align">Paid Amount</td>
                        <td class="action-btn-align">Discount Amount</td>
                        <td class="action-btn-align">Balance</td>
                        <td class="hide_class action-btn-align">Due Date</td>
                        <td class="hide_class action-btn-align">Payment Status</td>
                        <td class="hide_class action-btn-align">Action</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $paid = $bal = $inv = $advance = 0;
                    if (isset($all_receipt) && !empty($all_receipt)) {
                        $i = 1;
                        foreach ($all_receipt as $val) {
                            $advance = $advance + $val['advance'];
                            $inv = $inv + $val['net_total'];
                            $paid = $paid + $val['receipt_bill'][0]['receipt_paid'];
                            $bal = $bal + ($val['net_total'] - ($val['receipt_bill'][0]['receipt_paid'] + $val['receipt_bill'][0]['receipt_discount'] + $val['advance']));
                            ?>
                            <tr>
                                <td class='first_td action-btn-align'><?php echo $i ?></td>
                                <td class='action-btn-align'><?php echo $val['inv_id'] ?></td>
                                <td><?php echo $val['store_name'] ?></td>
                                <td  class="text_right"><?php echo number_format($val['net_total'], 2, '.', ',') ?></td>
                                <td  class="text_right"><?php echo number_format($val['advance'], 2, '.', ',') ?></td>
                                <td  class="text_right"><?php echo number_format($val['receipt_bill'][0]['receipt_paid'], 2, '.', ',') ?></td>
                                <td  class="text_right"><?php echo number_format($val['receipt_bill'][0]['receipt_discount'], 2, '.', ',') ?></td>
                                <td  class="text_right">
                                    <?php echo ($val['net_total'] - ($val['receipt_bill'][0]['receipt_paid'] + $val['receipt_bill'][0]['receipt_discount']) > 0) ? number_format($val['net_total'] - ($val['receipt_bill'][0]['receipt_paid'] + $val['receipt_bill'][0]['receipt_discount'] + $val['advance']), 2, '.', ',') : '0.00'; ?>
                                </td>
                                <td class="hide_class action-btn-align"><?php echo ($val['receipt_bill'][0]['next_date'] != '') ? date('d-M-Y', strtotime($val['receipt_bill'][0]['next_date'])) : ''; ?></td>
                                <td class="hide_class action-btn-align">
                                    <?php
                                    if ($val['payment_status'] == 'Pending') {
                                        echo '<a href="#" data-toggle="modal" class="tooltips ahref border0" title="In-Complete"><span class="fa fa-thumbs-down blue">&nbsp;</span></a>';
                                    } else {
                                        echo '<a href="#" data-toggle="modal" class="tooltips ahref border0" title="Complete"><span class="fa fa-thumbs-up green">&nbsp;</span></a>';
                                    }
                                    ?>
                                </td>
                                <td  class="hide_class action-btn-align">

                                    <?php
                                    if ($val['payment_status'] == 'Pending') {
                                        ?>
                                        <a href="<?php if ($this->user_auth->is_action_allowed('sales_receipt', 'sales_receipt', 'edit')): ?><?php echo $this->config->item('base_url') . 'sales_receipt/manage_receipt/' . $val['id'] ?><?php endif ?>"data-toggle="tooltip" class="tooltips btn btn-info btn-xs <?php if (!$this->user_auth->is_action_allowed('sales_receipt', 'sales_receipt', 'edit')): ?>alerts<?php endif ?>" title="" data-original-title="Edit"><span class="fa fa-edit "></span></a>
                                        <a href="<?php if ($this->user_auth->is_action_allowed('sales_receipt', 'sales_receipt', 'view')): ?><?php echo $this->config->item('base_url') . 'sales_receipt/view_receipt/' . $val['id'] ?><?php endif ?>" data-toggle="tooltip" class="tooltips btn btn-default btn-xs <?php if (!$this->user_auth->is_action_allowed('sales_receipt', 'sales_receipt', 'view')): ?>alerts<?php endif ?>" title="" data-original-title="View" ><span class="fa fa-eye"></span></a>

                                        <?php
                                    } else {
                                        ?>
                                        <a href="<?php if ($this->user_auth->is_action_allowed('sales_receipt', 'sales_receipt', 'view')): ?><?php echo $this->config->item('base_url') . 'sales_receipt/view_receipt/' . $val['id'] ?><?php endif ?>" data-toggle="tooltip" class="tooltips btn btn-default btn-xs <?php if (!$this->user_auth->is_action_allowed('sales_receipt', 'sales_receipt', 'view')): ?>alerts<?php endif ?>" title="" data-original-title="View" ><span class="fa fa-eye"></span></a>
                                    <?php }
                                    ?>
                                </td>
                            </tr>
                            <?php
                            $i++;
                        }
                        ?>

                        <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text_right total-bg"><?= number_format($inv, 2, '.', ',') ?></td>
                        <td class="text_right total-bg"><?= number_format($advance, 2, '.', ',') ?></td>
                        <td class="text_right total-bg"><?= number_format($paid, 2, '.', ',') ?></td>
                        <td></td>
                        <td class="text_right total-bg"><?= number_format($bal, 2, '.', ',') ?></td>
                        <td class="hide_class"></td>
                        <td class="hide_class"></td>
                        <td class="hide_class"></td>
                    </tr>
                </tfoot>
            </table>
            <div class="action-btn-align mt-top15">
                <button class="btn btn-defaultprint6 print_btn"><span class="glyphicon glyphicon-print"></span> Print</button>
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
<script type="text/javascript">
    $(document).ready(function () {
        jQuery('.datepicker').datepicker();
    });
    $().ready(function () {
        $("#po_no").autocomplete(BASE_URL + "gen/get_po_list", {
            width: 260,
            autoFocus: true,
            matchContains: true,
            selectFirst: false
        });
    });
    $('#search').live('click', function () {
        for_loading();
        $.ajax({
            url: BASE_URL + "po/search_result",
            type: 'GET',
            data: {
                po: $('#po_no').val(),
                state: $('#state').val(),
                supplier: $('#supplier').val(),
                supplier_name: $('#supplier').find('option:selected').text(),
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val()
            },
            success: function (result) {
                for_response();
                $('#result_div').html(result);
            }
        });
    });
</script>