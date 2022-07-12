<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?>
<script src="<?= $theme_path; ?>/js/jquery-1.8.2.js"></script>
<script src="<?= $theme_path; ?>/js/jquery-ui-my-1.10.3.min.js"></script>
<script type='text/javascript' src='<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path; ?>/js/sweetalert.css">
<script src="<?php echo $theme_path; ?>/js/sweetalert.min.js" type="text/javascript"></script>
<script type='text/javascript' src='<?php echo $theme_path; ?>/js/jquery.table2excel.min.js'></script>
<style>
    .text_right1 {
        text-align: right !important;
    }

    .dataTable tbody tr td:nth-child(6) {
        text-align: right !important;
    }

    .dataTable tbody tr td:nth-child(7) {
        text-align: right !important;
    }

    .dataTable tbody tr td:nth-child(8) {
        text-align: right !important;
    }
</style>
<?php
$this->config->item('firm_id');
$this->load->model('admin/admin_model');
$data['company_details'] = $this->admin_model->get_company_details();
?>
<div class="print_header">
    <table width="100%">
        <tr>
            <td width="15%" style="vertical-align:middle;">
                <div class="print_header_logo"><img src="<?= $theme_path; ?>/images/logo-login2.png" /></div>
            </td>
            <td width="85%">
                <div class="print_header_tit">
                    <h3><?= $data['company_details'][0]['company_name'] ?></h3>
                    <p>
                        <?= $data['company_details'][0]['address1'] ?>
                        <?= $data['company_details'][0]['address2'] ?>
                    </p>
                    <p></p>
                    <p><?= $data['company_details'][0]['city'] ?>-
                        <?= $data['company_details'][0]['pin'] ?>,
                        <?= $data['company_details'][0]['state'] ?></p>
                    <p></p>
                    <p>Ph:
                        <?= $data['company_details'][0]['phone_no'] ?>, Email:
                        <?= $data['company_details'][0]['email'] ?>
                    </p>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="row">
    <div class="col-lg-12 ">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Balance List
                    <!-- <a href="javascript:void(0);" id="advancesearchshow" class="btn btn-info clor" style="float:right;margin-top:-4px;" title="Advance Search">
                        Advance Search</a> -->
                </h4>
            </div>
        </div>
    </div>
    <div class="col-md-12 grid-margin stretch-card" id="loader_area" style="display:none;">
        <div class="circle-loader"></div>
    </div>
    <div class="col-md-12 grid-margin stretch-card hide_class" id="myDIVSHOW">
        <div class="card">
            <div class="card-body">
                <form id="form-filter" class="form-horizontal">
                    <div class="form-group row">

                        <div class="col col-md-3">
                            <label>Opening Balance</label>
                            <input type="text" id='firm_amt' class="form-control" name="company_amount">
                        </div>
                        <div class="col col-md-2">
                            <label>From Date</label>
                            <input type="text" id='from_date' class="form-control datepicker" name="from_date" value="<?php echo date('01-01-Y') ?>" placeholder="dd-mm-yyyy" style="background-color:white;">
                        </div>
                        <div class="col col-md-2">
                            <label>To Date</label>
                            <input type="text" id='to_date' class="form-control datepicker" name="to_date" value="<?php echo date('31-03-Y', strtotime('+1 year')) ?>" placeholder="dd-mm-yyyy" style="background-color:white;">
                            <span class="date_err" style="color:#F00;font-size: 12px "></span>
                        </div>
                        <div class="col col-md-2">
                            <label class="control-label col-md-12 mnone"></label>
                            <a id='search' class="btn btn-success  mtop4" title="Search">SUBMIT<span class=" icon-magnifier"></span></a>&nbsp;
                            <a class="btn btn-danger mtop4" id='clear' title="Clear">CLEAR<span></span></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12 grid-margin stretch-card" id="expense_list_table" style="display:none">
        <div class="card">
            <div class="card-body">
                <div class="tab-content tab-content-solid">
                    <div class="tabpad">
                        <table id="basicTable_call_back" class="table table-striped responsive no-footer dtr-inline">
                            <thead>
                                <tr>
                                    <td class="action-btn-align">S.No</td>
                                    <!-- <td class="action-btn-align">Company</td> -->
                                    <!--<td class="action-btn-align">Opening Balance</td>-->
                                    <td class="action-btn-align">Type</td>
                                    <td class="action-btn-align">Details</td>
                                    <td class="action-btn-align">Created Date</td>
                                    <td class="action-btn-align">Debit Amt</td>
                                    <td class="action-btn-align">Credit Amt</td>
                                    <td class="action-btn-align">Balance</td>
                                </tr>
                            </thead>
                            <tbody id="result_data">
                                <!-- <tr>
                                    <td colspan="8" class="action-btn-align">No Data Found</td>
                                </tr> -->
                            </tbody>
                            <tfoot id="footer_id" style="display:none">
                                <tr>
                                    <td></td>
                                    <!-- <td></td> -->
                                    <!--<td></td>-->
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="total-bg text_right1"></td>
                                    <td class="total-bg text_right1"></td>
                                    <td class=" text_right1"></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="action-btn-align mb-10 excel_show" style="display:none">
                            <button class="btn btn-primary print_btn"><span class="icon-printer"></span> Print</button>
                            <div class="btn-group">
                                <button type="button" class=" btn btn-success" data-toggle="dropdown">
                                    Excel
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#" class="excel_btn1">Current Entries</a></li>
                                    <li><a href="#" id="excel-prt">Entire Entries</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="export_excel"></div>
<script>
    // $('.datepicker').datepicker({
    //     format: 'dd-mm-yyyy',
    // });
    $('.print_btn').click(function() {
        window.print();
    });
    $('#search').click(function() { //button filter event click
        var table;
        var firm_id = $('#firm').val();
        if (firm_id != '') {
            $("#footer_id").show();
            $(".excel_show").show();
            $('#expense_list_table').show();
            var table;
            table = $('#basicTable_call_back').DataTable({
                "lengthMenu": [
                    [50, 100, 150, -1],
                    [50, 100, 150, "All"]
                ],
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "retrieve": true,
                "order": [], //Initial no order.
                //dom: 'Bfrtip',
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('expenses/balancesheet_ajaxList/'); ?>",
                    "type": "POST",
                    "data": function(data) {
                        data.firm_id = $('#firm').val();
                        data.from_date = $('#from_date').val();
                        data.to_date = $('#to_date').val();
                    }
                },
                //Set column definition initialisation properties.
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;
                    // Remove the formatting to get integer data for summation
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };
                    // Total over all pages
                    var cols = [4, 5];
                    var currency_symbol = '<span class="hide_rupee">&#8377;</span>'
                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2, currency_symbol).display;
                    for (x in cols) {
                        total = api.column(cols[x]).data().reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        // Total over this page
                        pageTotal = api.column(cols[x], {
                            page: 'current'
                        }).data().reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        // Update footer
                        if (Math.floor(pageTotal) == pageTotal && $.isNumeric(pageTotal)) {
                            pageTotal = pageTotal;
                        } else {
                            pageTotal = pageTotal.toFixed(2); /* float */
                        }
                        $(api.column(cols[x]).footer()).html(numFormat(pageTotal));
                    }
                },
                responsive: true,
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: -2
                    },
                    {
                        "targets": [0], //first column / numbering column
                        "orderable": false, //set not orderable
                    },
                ]
            });
            // new $.fn.dataTable.FixedHeader(table);
        } else {
            $('#expense_list_table').hide();
            swal("Please select Company");
        }
        // new $.fn.dataTable.FixedHeader(table);
        // table.ajax.reload();  //just reload table
    });
    $(document).on('click', '#clear', function() {
        $('#form-filter')[0].reset();
    });
    $('#firm').change(function() {
        var firm_id = $(this).val();
        $.ajax({
            url: BASE_URL + "expenses/get_company_amount",
            type: "post",
            data: {
                firm_id: firm_id
            },
            dataType: 'json',
            success: function(result) {
                if (result[0].opening_balance != null && result[0].opening_balance > 0) {
                    opening_amt = (result[0].opening_balance);
                    $("#firm_amt").val(opening_amt);
                } else {
                    $("#firm_amt").val('0');
                }
            }
        });
    });
    $(document).on('click', '.excel_btn1', function() {
        fnExcelReport2();
    });

    function fnExcelReport2() {
        var tab_text = "<table id='custom_export' border='5px'><tr width='100px' bgcolor='#87AFC6'>";
        var textRange;
        var j = 0;
        tab = document.getElementById('basicTable_call_back'); // id of table
        for (j = 0; j < tab.rows.length; j++) {
            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
        }
        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
        $('#export_excel').show();
        $('#export_excel').html('').html(tab_text);
        $('#export_excel').hide();
        $("#custom_export").table2excel({
            exclude: ".noExl",
            name: "Balance Report",
            filename: "Balance Report",
            fileext: ".xls",
            exclude_img: false,
            exclude_links: false,
            exclude_inputs: false
        });
    }
    $('#excel-prt').on('click', function() {
        var firm_id = $('#firm').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        window.location = (BASE_URL + 'expenses/getall_balance_entries?firm_id=' + firm_id + '&from_date=' + from_date + '&to_date=' + to_date);
    });
    $("#from_date").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    }).on('changeDate', function(selected) {
        var startDate = new Date(selected.date.valueOf());
        $('#to_date').datepicker('setStartDate', startDate);
    }).on('clearDate', function(selected) {
        $('#to_date').datepicker('setStartDate', null);
    });
    $("#to_date").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    }).on('changeDate', function(selected) {
        var endDate = new Date(selected.date.valueOf());
        $('#from_date').datepicker('setEndDate', endDate);
    }).on('clearDate', function(selected) {
        $('#from_date').datepicker('setEndDate', null);
    });
</script>