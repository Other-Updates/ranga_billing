<?php
    $theme_path = $this->config->item('theme_locations');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/date-picker.css">
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-picker/datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/select2.css">
<script src="<?php echo $theme_path ?>/assets/js/select2/select2.full.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/select2/select2-custom.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/jquery.basictable.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.basictable').basictable({
        breakpoint: 768
        });
    });
</script>
<div class="container-fluid"> 
    <div class="page-title">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h3>Expenses List
                <?php
                if($this->session->userdata('UserRole') == 1){?>
                    <a href="<?php echo $this->config->item('base_url') . 'expenses/' ?>" class="btn btn-sm btn-primary topgen "> Add Expense</a>
                <?php } ?></h3>
            </div>
            <div class="col-md-6  text-right">  
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Expenses</li>
                <li class="breadcrumb-item active">Expenses List</li>
                </ol>
            </div>
        </div>
    </div>    
</div>
<div class="container-fluid"> 
<div class="row">
    <div class="col-md-12 grid-margin stretch-card" id="myDIVSHOW">
        <div class="card">
            <div class="card-body">
                <form id="form-filter" class="form-horizontal">
                    <div class="row g-3 row-7">
                        <div class="col-md-2">
                            <div class="form-group ">
                                    <label>Branch</label>
                                    <div class="col-sm-12">
                                    <?php if($this->session->userdata('UserRole') == 2 || $this->session->userdata('UserRole') == 3){ ?>
                                    <label class="form-control" for="validationCustom04" readonly><?php echo $branches[0]['vBranchName'];?></label>  
                                <input class="form-control branch" id="validationCustom01" type="hidden" name="branch_id" value="<?php echo $branches[0]['iBranchId'];?>">
                                    <?php } else{?>
                                <select class="form-select branch disabled branch-multiple" name="branch_id" id="validationCustom04" required="required">
                                <option value="" >Select</option>
                                <?php foreach ($branches as $list){ ?>   
                                    <option value="<?php echo $list['iBranchId'] ?>" <?php echo ($this->session->userdata('BranchId') == $list['iBranchId']) ? 'selected' : '';?> ><?php echo $list['vBranchName'] ?></option>
                                <?php }  ?>
                                </select>
                                <span id="branch_err" class="val" style="color:#F00; "></span>
                                <?php }?>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-2">
                            <label>Exp Category</label>
                            <select class="form-select category-multiple" id="ex_category">
                                <option value="">Select</option>
                                <?php
                                if (isset($category_list) && !empty($category_list)) {
                                    foreach ($category_list as $cat_list) {
                                ?>
                                        <option value="<?php echo $cat_list['id']; ?>"> <?php echo $cat_list['category']; ?> </option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Exp Subcategory</label>
                            <select class="form-select subcategory-multiple" id="ex_subcat">
                                <option value="">Select</option>
                                <?php
                                if (isset($sub_category_list) && !empty($sub_category_list)) {
                                    foreach ($sub_category_list as $sub_cat_list) {
                                ?>
                                        <option value="<?php echo $sub_cat_list['id']; ?>" <?php echo ($sub_cat_list['id'] == $get_category[0]['category_id']) ? 'selected' : ''; ?>> <?php echo $sub_cat_list['sub_category']; ?> </option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Mode</label>
                            <select class="form-select" id="paymode">
                                <option value="">Select</option>
                                <option value="debit">Debit</option>
                                <option value="credit">Credit</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>From Date</label>
                            <!-- <input type="text" id='from_date' value="<?php echo date('01-m-Y') ?>" class="form-control datepicker" name="from_date" placeholder="dd-mm-yyyy" style="background-color:white;"> -->
                            <input class="form-control from_date datepicker-here" id="from_date" type="text" value="<?php echo date('01/m/Y') ?>" name="from_date" placeholder="" required>
                            <!-- <input type="text" name="reports[date_range][from_date]"  value="10/08/2021" readonly="readonly"> -->
                        </div>
                        <div class="col-md-2">
                            <label>To Date</label>
                            <input class="form-control to_date datepicker-here" id="to_date" type="text" value="<?php echo date("d/m/Y") ?>" name="to_date" placeholder="" required>
                            <span class="date_err" style="color:#F00;font-size: 12px "></span>
                        </div>
                        <div class="col-md-2">
                            <label class="col-md-12 mnone"><br></label>
                            <a id='search' class="btn btn-t-success" title="Search"><i class="icofont icofont-ui-search"></i></a>
                            <a class="btn btn-danger" id='clear' title="Clear"><i class="icofont icofont-refresh"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12 grid-margin stretch-card" id="expense_list_table">
        <div class="card">
            <div class="card-body">
                <div class="reference-report">
                    <table id="basicTable_call_back" class="table list-table display td-nowrap basictable">
                        <thead>
                            <tr>
                                <th class="action-btn-align">Branch</th>
                                <th class="action-btn-align">Created Date</th>
                                <th class="action-btn-align">Expense Type</th>
                                <th class="action-btn-align">Exp Category</th>
                                <th class="action-btn-align">Exp Subcategory</th>
                                <th class="action-btn-align">Mode</th>
                                <th class="action-btn-align">Debit Amount</th>
                                <th class="action-btn-align">Credit Amount</th>
                                <!-- <th class="action-btn-align">Created Date</th> -->
                                <th class="hide_class action-btn-align">Action</th>
                            </tr>
                        </thead>
                        <tbody id="result_data">
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="total-bg"> </td>
                                <td class="total-bg"> </td>
                                <!-- <td></td> -->
                                <td class="hide_class"></td>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- <div class="action-btn-align mb-10">
                        <button class="btn btn-primary print_btn"><span class="icon-printer"></span> Print</button>
                    </div> -->
                </div>
            </div>
        </div>
     </div>
  </div>
</div>
<div id="export_excel"></div>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/dataTables.buttons.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/jszip.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/pdfmake.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/vfs_fonts.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/buttons.html5.min.js"></script>
<script>
    // $('.datepicker').datepicker({
    //     format: 'dd-mm-yyyy',
    // });
    // if($('#search').click(function)){
    //     $('#basicTable_call_back').find().show();
    // } else {
    //     $('#basicTable_call_back').find().hide();
    // }
    $('.print_btn').click(function() {
        window.print();
    });
    $(document).ready(function() {
        // category change
        $('#ex_category').change(function() {
            var category_id = $(this).val();
            $('#ex_subcat').empty();
            $('#ex_subcat').val('');
            $.ajax({
                url: "<?php echo base_url() ?>expenses/get_subcategory",
                method: 'post',
                data: {
                    category_id: category_id
                },
                dataType: 'json',
                success: function(response) {
                var html = '';
                html += '<option value="">Choose Exp Subcategory</option>';
                $.each(response, function(key,val) {
                    html += '<option value='+val['id']+'>'+val['sub_category']+'</option>';
                });
                $('#ex_subcat').html(html);
                }
            });
        });
    });
    var table;
    $(document).ready(function() {
        var firm_id = $('#firm').val();
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
                // "retrieve": true,
                responsive: true,
                "order": [], //Initial no order.
                //dom: 'Bfrtip',
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('expenses/expenses_ajaxList/'); ?>",
                    "type": "POST",
                    "data": function(data) {
                        data.branch_id = $('.branch').val();
                        data.cat_id = $('#ex_category').val();
                        data.sub_cat_id = $('#ex_subcat').val();
                        data.mode = $('#paymode').val();
                        data.from_date = $('#from_date').val();
                        data.to_date = $('#to_date').val();
                    }
                },
                    "createdRow": function(row, data, dataIndex) {
                    $(row).find('td:eq(0)').attr('data-th', 'Branch');
                    $(row).find('td:eq(1)').attr('data-th', 'Created Date');
                    $(row).find('td:eq(2)').attr('data-th', 'Expense Type');
                    $(row).find('td:eq(3)').attr('data-th', 'Exp Category');
                    $(row).find('td:eq(4)').attr('data-th', 'Exp Subcategory');
                    $(row).find('td:eq(5)').attr('data-th', 'Mode');
                    $(row).find('td:eq(6)').attr('data-th', 'Debit amount');
                    $(row).find('td:eq(7)').attr('data-th', 'Credit amount');
                    $(row).find('td:eq(8)').attr('data-th', 'Action');
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
                    var cols = [6,7];
                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2, '&#8377;').display;
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
					"targets":[1,5,8],  
					"className":"text-center"
				    },
                    {  
					"targets":[6,7],  
					"className":"text-right"
				    },
                    {
                        "targets": [8], //first column / numbering column
                        "orderable": false, //set not orderable
                    },
                ],
                "dom": 'lBfrtip',
                buttons: [
    {
        extend: 'excelHtml5',
        footer: true,
        text: '<i class="fa fa-file-excel-o"></i> Excel',
        titleAttr: 'Export to Excel',
        title: 'Ranga Hospital Expense Report',
        exportOptions: {
            columns: ':not(:last-child)',
        }
    },
    {
        extend: 'pdfHtml5',
        footer: true,
        text: '<i class="fa fa-file-pdf-o"></i> PDF',
        titleAttr: 'PDF',
        title: 'Ranga Hospital Expense Report',
        exportOptions: {
            columns: ':not(:last-child)',
        },
    },
],
            });
            // new $.fn.dataTable.FixedHeader(table);
        // table.ajax.reload();  //just reload table

        $('#clear').click(function() { //button reset event click
            $(".branch-multiple").val(null).trigger("change"); 
            $(".category-multiple").val(null).trigger("change"); 
            $(".subcategory-multiple").val(null).trigger("change"); 
            $('#form-filter')[0].reset();
            $('#basicTable_call_back').DataTable().ajax.reload();
            //        window.location.reload();
        });
        $('#search').click(function() { //button filter event click
            table.ajax.reload(); //just reload table
        });
    $(document).on('click', '.deleteexp', function (event) {
                event.preventDefault();
            var delete_id = $(this).attr('delete_id');
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
                url: "<?php echo base_url() ?>expenses/delete",
                type: 'POST',
                data: {
                    delete_id: delete_id
                },
                success: function(result) {
                    table.ajax.reload();
                }
            });
        }
    });
    });
});

    function check(val) {
        $('#test' + val).modal('show');
    }
    $('#search').click(function() { //button filter event click
        // console.log(1);
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
                    "url": "<?php echo site_url('expenses/expenses_ajaxList/'); ?>",
                    "type": "POST",
                    "data": function(data) {
                        data.branch = $('.branch').val();
                        data.cat_id = $('#ex_category').val();
                        data.sub_cat_id = $('#ex_subcat').val();
                        data.mode = $('#paymode').val();
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
                    var cols = [6,7];
                    var numFormat = $.fn.dataTable.render.number('\,', '.', 2, '&#8377;').display;
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
                        "targets": [0, 8], //first column / numbering column
                        "orderable": false, //set not orderable
                    },
                ]
            });
            // new $.fn.dataTable.FixedHeader(table);
        } else {
            swal("Please select Company");
            $('#expense_list_table').hide();
        }
        // table.ajax.reload();  //just reload table
    });
    $('#clear').click(function() { //button reset event click
        $(".branch-multiple").val(null).trigger("change"); 
        $(".category-multiple").val(null).trigger("change"); 
        $(".subcategory-multiple").val(null).trigger("change"); 
        $('#form-filter')[0].reset();
        // table.ajax.reload(); //just reload table
        $('#basicTable_call_back').DataTable().ajax.reload();
        //        window.location.reload();
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
                if (result != '') {
                    if (result[0].opening_balance != null && result[0].opening_balance > 0) {
                        opening_amt = (result[0].opening_balance);
                        $("#firm_amt").val(opening_amt);
                    } else {
                        $("#firm_amt").val('0');
                    }
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
            name: "Expenses Report",
            filename: "Expenses Report",
            fileext: ".xls",
            exclude_img: false,
            exclude_links: false,
            exclude_inputs: false
        });
    }
    $('#excel-prt').on('click', function() {
        // var firm_id = $('#firm').val();
        // var cat_id = $('#ex_category').val();
        // var sub_cat_id = $('#ex_subcat').val();
        // var from_date = $('#from_date').val();
        // var to_date = $('#to_date').val();
        var arr = [];
        arr.push({
            'firm_id': $('#firm_id').val()
        });
        arr.push({
            'cat_id': $('#ex_category').val()
        });
        arr.push({
            'sub_cat_id': $('#ex_subcat').val()
        });
        arr.push({
            'from_date': $('#from_date').val()
        });
        arr.push({
            'to_date': $('#to_date').val()
        });

        var arrStr = JSON.stringify(arr);
        window.location = (BASE_URL + 'expenses/getall_expenses_entries?' + arrStr);
        // window.location.replace('<?php echo $this->config->item('base_url') . 'report/getall_expenses_entries?search=' ?>' + arrStr);
    });
    $("#from_date").datepicker({
        autoclose: true,
    }).on('changeDate', function(selected) {
        var startDate = new Date(selected.date.valueOf());
        $('#to_date').datepicker('setStartDate', startDate);
    }).on('clearDate', function(selected) {
        $('#to_date').datepicker('setStartDate', null);
    });
    $("#to_date").datepicker({
        autoclose: true,
    }).on('changeDate', function(selected) {
        var endDate = new Date(selected.date.valueOf());
        $('#from_date').datepicker('setEndDate', endDate);
    }).on('clearDate', function(selected) {
        $('#from_date').datepicker('setEndDate', null);
    });
</script>
<!-- <script src="<?= $theme_path; ?>/js/fixedheader/dataTables.fixedHeader.min.js"></script> -->