<?php
    $theme_path = $this->config->item('theme_locations');
?>
<style>
    .ui-datepicker td.ui-datepicker-today a {
        background:#999999;
    }
    .full {
        width:100%;
    }
    .sales-recipt-update tr td:last-child{text-align:right !important;}
    .bg-info {
        background-color: #6ea962;
        color:white !important;
    }
    .modal-dialog {
        width: 450px;
    }
    .modal-title{
        color:white !important;}
    table.dataTable thead th, table.dataTable thead td {
    padding: 10px 14px;}
    .action-icon {
    color: #ffffff;
    padding: 0 5px!important;}
    .modal-body {
    position: relative;
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 0rem;}
    </style>
<div class="mainpanel">
    <div class="media mt--20">
        <h4 class="">View Non GST Sales Receipt</h4>
    </div>
    <?php
    if (isset($receipt_details[0]['receipt_history']) && !empty($receipt_details[0]['receipt_history'])) {
        $i = 1;
        $dis = 0;
        $paid = 0;
        foreach ($receipt_details[0]['receipt_history'] as $val) {
            $paid = $paid + $val['bill_amount'];
            $dis = $dis + $val['discount'];
        }
    }
    ?>
   <div class="container-fluid">
<div class="card"> 
  <div class="modal-body">
        <form method="post">
            <input type="hidden" name="receipt_bill[receipt_id]" value="<?php echo $receipt_details[0]['id'] ?>">
            <table class="table table-striped table-bordered responsive dataTable no-footer dtr-inline">
                <thead>
                <th class="action-btn-align" colspan="4">Sales Invoice Details</th>
                </thead>
                <tr>
                    <td><span  class="tdhead">Invoice NO:</span></td>
                    <td><?php echo $receipt_details[0]['vSalesOrderNo'] ?></td>
                    <td class="action-btn-align"> <img src="<?php echo $theme_path; ?>/assets/images/logo/logo.png" alt="Chain Logo" width="125px"></td>
                    <td></td>
                </tr>
                <tr>
                    <td><span  class="tdhead">Date:</span></td>
                    <td><?php echo date('d-M-Y', strtotime($receipt_details[0]['created_date'])) ?></td>
                    <td><span  class="tdhead">Total Discount:</span></td>
                    <td><?php echo number_format($dis, 2, '.', ',') ?></td>
                </tr>
                <tr>
                    <td><span  class="tdhead">Total Amount:</span></td>
                    <td><?php echo number_format($receipt_details[0]['net_total'], 2, '.', ',') ?></td>
                    <td><span  class="tdhead">Total Received Amount:</span></td>
                    <td><?php echo number_format($paid, 2, '.', ',') ?></td>
<!--                    <td><span  class="tdhead">Return Amount:</span></td>
                    <td><?php echo number_format($receipt_details[0]['return_amount'], 2, '.', ',') ?></td>-->
                </tr>
                <tr>

                    <td><span  class="tdhead">Balance:</span></td>
                    <td><?php echo number_format($receipt_details[0]['payable_amt'] - ($dis + $paid), 2, '.', ','); ?></td>
                    <td colspan="2"></td>
                </tr>

            </table>
        </form>
        <table class="table table-striped table-bordered responsive dataTable no-footer dtr-inline">
            <thead>
            <th colspan="9">Payment History</th>

            </thead>
            <thead>
            <th width="1%" class="action-btn-align">S&nbsp;No</th>
            <th>Receipt&nbsp;NO</th>
            <!--<th>Receiver</th>-->
            <th>Received Date</th>
            <th width="5%">Payment&nbsp;Terms</th>
            <th width="5%">Bank&nbsp;Details</th>
            <th class="action-btn-align">Received&nbsp;Amount</th>
            <th class="action-btn-align">Discount&nbsp;(&nbsp;%&nbsp;)</th>
            <th>Remarks</th>
            <?php if ($receipt_details[0]['vSalesOrderNo'] != 'Wings Invoice') { ?>
                <th class="hide_class">Action</th>
            <?php } ?>
            </thead>
            <tbody id='receipt_info'>
                <?php
                if (isset($receipt_details[0]['receipt_history']) && !empty($receipt_details[0]['receipt_history'])) {
                    $i = 1;
                    $dis = 0;
                    $paid = 0;
                    foreach ($receipt_details[0]['receipt_history'] as $val) {
                        $paid = $paid + $val['bill_amount'];
                        $dis = $dis + $val['discount'];
                        ?>
                        <tr>
                            <td class="action-btn-align"t><?= $i ?></td>
                            <th><?php echo $val['receipt_no'] ?></th>
                            <!--<th><?php echo $val['recevier'] ?></th>-->
                            <td><?php echo date('d-M-Y', strtotime($val['created_date'])) ?></td>
                            <td>
                                <?php
                                if ($val['terms'] == 1)
                                    echo "CASH";
                                elseif ($val['terms'] == 2)
                                    echo "DD";
                                elseif ($val['terms'] == 3)
                                    echo "Cheque";
                                elseif ($val['terms'] == 4)
                                    echo "NEFT";
                                elseif ($val['terms'] == 5)
                                    echo "RTGS";
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($val['terms'] != 1 && $val['terms'] != 4 && $val['terms'] != 5) {
                                    echo "<b>A/C&nbsp;NO</b>    :<br>" . $val['ac_no'] . '<br>';
                                    echo "<b>Bank</b>    :<br>" . $val['branch'] . '<br>';
                                    echo "<b>DD&nbsp;/&nbsp;Cheque&nbsp;NO</b>:<br>" . $val['dd_no'] . '<br>';
                                } else
                                    echo "-";
                                ?>
                            </td>

                            <td class="text_right"><?php echo number_format($val['bill_amount'], 2, '.', ',') ?></td>
                            <td class="text_right"><?php echo number_format($val['discount'], 2, '.', ',') ?> ( <?= $val['discount_per'] ?> %)</td>
                            <td><?php echo ($val['remarks']) ? $val['remarks'] : '-'; ?></td>
                            <?php if ($receipt_details[0]['vSalesOrderNo'] != 'Wings Invoice') { ?>
                                <td class="hide_class">
                                    <button type="button" rec_id ="<?php echo $val['id'] ?>" class="btn btn-primary download action-icon"><span class="fa fa-download"></span></button>
                                    <button type="button" rec_id ="<?php echo $val['id'] ?>"class="btn btn-danger print action-icon"><span class="fa fa-print"></span></button>
                                </td>
                            <?php } ?>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                <tfoot>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text_right"><?php echo number_format($paid, 2, '.', ',') ?></td>
                <?php if ($receipt_details[0]['vSalesOrderNo'] != 'Wings Invoice') { ?>
                    <td class="text_right"><?php echo number_format($dis, 2, '.', ',') ?></td>
                    <td></td>

                <?php } else { ?>
                    <td style="text-align:center;"><?php echo number_format($dis, 2, '.', ',') ?></td>
                    <td></td>
                <?php } ?>
                </tfoot>
                <?php
            } else
                echo "<tr><td colspan='9'>No Data Found</td> </tr>";
            ?>
            </tbody>
        </table>
        <div class="hide_class action-btn-align">
            <button class="btn btn-defaultprint6 print_btn"><span class="glyphicon glyphicon-print"></span> Print</button>
            <a href="<?php echo $this->config->item('base_url') ?>sales_receipt/non_gst_receipt/receipt_list" class="btn btn-defaultback"><span class="glyphicon"></span> Back </a>

        </div>
    </div>
</div>
        </div>
        </div>
<script type="text/javascript">
    $('.print').click(function () {
        r_id = '<?php echo $receipt_details[0]['id'] ?>';
        var link = document.createElement('a');
        $rec_id = $(this).attr('rec_id');
        link.href = '<?php echo base_url(); ?>sales_receipt/non_gst_receipt/print_receipt/' + r_id + '/' + $rec_id;
        link.target = '_blank';
        link.click();
    });

    $('.download').click(function () {
        r_id = '<?php echo $receipt_details[0]['id'] ?>';
        var link = document.createElement('a');
        $rec_id = $(this).attr('rec_id');
        //link.download = file_name;
        link.href = '<?php echo base_url(); ?>sales_receipt/non_gst_receipt/download_receipt/' + r_id + '/' + $rec_id;
        link.click();
    });
    $('#terms').live('change', function () {
        if ($(this).val() == 2 || $(this).val() == 3)
            $('.show_tr').show();
        else
            $('.show_tr').hide();
    });
    $('.receiver').live('click', function () {
        if ($(this).val() == 'agent')
            $('.select_agent').css('display', 'block');
        else
            $('.select_agent').css('display', 'none');
    });
    // Date Picker
    $('#add_package').live('click', function () {
        $('.sty_class').each(function () {

            var s_html = $(this).closest('tr').find('.size_val');
            var size_name = $(this).closest('tr').find('.size_name');
            var cort_class = $(this).closest('tr').find('.cort_class').val();
            var sty_class = $(this).closest('tr').find('.sty_class').val();
            var col_class = $(this).closest('tr').find('.col_class').val();

            $(s_html).each(function () {
                $(this).attr('name', 'size[' + sty_class + col_class + cort_class + '][]');
            });
            $(size_name).each(function () {
                $(this).attr('name', 'size_name[' + sty_class + col_class + cort_class + '][]');
            });
        });
    });

    $(document).ready(function () {

        jQuery('#from_date1').datepicker();
    });
    $('#cor_no').live('keyup', function () {
        var select_op = '';
        if (Number($(this).val()))
        {
            select_op = select_op + '<select class="cort_class"  name="corton[]"><option>Select</option>';
            for (i = 1; i <= Number($(this).val()); i++)
            {
                select_op = select_op + '<option value=' + i + '>' + i + '</option>';
            }
            select_op = select_op + '</select>';
            $('.cor_class').html(select_op);
        }
    });
    $('#customer').live('change', function () {
        for_loading();
        $.ajax({
            url: BASE_URL + "sales_receipt/get_all_pending_invoice",
            type: 'GET',
            data: {
                c_id: $(this).val()
            },
            success: function (result) {
                $('#s_div').html(result);
            }
        });
        $.ajax({
            url: BASE_URL + "sales_receipt/get_invoice_view",
            type: 'GET',
            data: {
                c_id: $(this).val()
            },
            success: function (result) {
                for_response();
                $('#receipt_info').html(result);
            }
        });

    });
    $('.so_id').live('click', function () {
        var s_arr = [];
        var i = 0;
        $('.so_id').each(function () {
            if ($(this).attr('checked') == 'checked')
            {
                s_arr[i] = $(this).val();
                i++;
            }
        });
        for_loading();
        $.ajax({
            url: BASE_URL + "sales_receipt/get_inv",
            type: 'GET',
            data: {
                vSalesOrderNo: s_arr,
                c_id: $('#customer').val()
            },
            success: function (result) {
                for_response();
                $('#receipt_info').html(result);
            }
        });
    });
    $('#discount').live('keyup', function () {
        total = 0;
        total = (Number($('#inv_amount').val()) - Number($(this).val())) - Number($('#paid').val());
        $('#balance').val(total.toFixed(2));

        var tt = ($(this).val() / $('#inv_amount').val()) * 100;
        $('#discount_per').val(tt.toFixed(2));
    });
    $('#paid').live('keyup', function () {
        total = 0;
        total = (Number($('#inv_amount').val()) - Number($('#discount').val())) - Number($(this).val());
        $('#balance').val(total.toFixed(2));
    });
    $('#discount_per').live('keyup', function () {
        var tt = $('#inv_amount').val() * ($(this).val() / 100);
        $('#discount').val(tt.toFixed(2));

        total = 0;
        total = (Number($('#inv_amount').val()) - Number($('#discount').val())) - Number($('#paid').val());
        $('#balance').val(total.toFixed(2));
    });
</script>
<script type="text/javascript">

    $(".dduplication").live('blur', function ()
    {
        //alert("hi");
        var checkno = $(".dduplication").val();
        if (checkno == "")
        {
        } else
        {
            $.ajax(
                    {
                        url: BASE_URL + "sales_receipt/update_checking_payment_checkno",
                        type: 'POST',
                        data: {value1: checkno},
                        success: function (result)
                        {
                            $("#dupperror").html(result);

                        }
                    });
        }
    });
    $("#paid").live('blur', function ()
    {
        var paid = $('#paid').val();
        if (paid == "")
        {
            $("#receiptuperror3").html("Required Field");

        } else
        {
            $("#receiptuperror3").html("");
        }
    });
    $("#ac_no").live('blur', function ()
    {
        var ac_no = $("#ac_no").val();
        if (ac_no == "" || ac_no == null || ac_no.trim().length == 0)
        {
            $("#receiptuperror").html("Required Field");
        } else
        {
            $("#receiptuperror").html("");
        }
    });
    $("#branch").live('blur', function ()
    {
        var branch = $("#branch").val();
        if (branch == "" || branch == null || branch.trim().length == 0)
        {
            $("#receiptuperror1").html("Required Field");
        } else
        {
            $("#receiptuperror1").html("");
        }
    });
    $("#dd_no").live('blur', function ()
    {
        var dd_no = $("#dd_no").val();
        if (dd_no == "" || dd_no == null || dd_no.trim().length == 0)
        {
            $("#receiptuperror2").html("Required Field");
        } else
        {
            $("#receiptuperror2").html("");
        }
    });
    $('#pay').live('click', function ()
    {
        i = 0;
        var paid = $('#paid').val();
        if (paid == "")
        {
            $("#receiptuperror3").html("Required Field");
            i = 1;

        } else
        {
            $("#receiptuperror3").html("");
        }
        var terms = $("#terms").val();
        if (terms == 1 || terms == 4 || terms == 5)
        {
        } else
        {
            var ac_no = $("#ac_no").val();
            if (ac_no == "" || ac_no == null || ac_no.trim().length == 0)
            {
                $("#receiptuperror").html("Required Field");
                i = 1;
            } else
            {
                $("#receiptuperror").html("");
            }
            var branch = $("#branch").val();
            if (branch == "" || branch == null || branch.trim().length == 0)
            {
                $("#receiptuperror1").html("Required Field");
                i = 1;
            } else
            {
                $("#receiptuperror1").html("");
            }
            var dd_no = $("#dd_no").val();
            if (dd_no == "" || dd_no == null || dd_no.trim().length == 0)
            {
                $("#receiptuperror2").html("Required Field");
                i = 1;
            } else
            {
                $("#receiptuperror2").html("");
            }
            var m = $('#dupperror').html();
            if ((m.trim()).length > 0)
            {
                i = 1;
            }
        }
        if (i == 1)
        {
            return false;
        } else
        {
            return true;
        }
    });
</script>
<script>
    $('.print_btn').click(function () {
        window.print();
    });
</script>