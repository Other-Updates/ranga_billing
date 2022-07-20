<?php
    $theme_path = $this->config->item('theme_locations');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/date-picker.css">
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-picker/datepicker.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/jquery.basictable.js" type="text/javascript"></script>
<style>
.p-none {
    background: #e7e7e7;
    pointer-events: none;
}
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $('.basictable').basictable({breakpoint: 768});
    });
</script>
<div class="mainpanel">
    <div class="container-fluid">        
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Cash In Hand</h3>
                </div>
                <div class="col-6">                
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')  ?>"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item">Sales Order</li>
                    <li class="breadcrumb-item active">Cash In Hand</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid cash-handover">
        <div class="row">        
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <!-- <form id="Receipt_form"> -->
                            <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label" for="validationCustom04">Branch</label>
                                <select class="form-select branch_select <?php echo ($this->session->userdata('BranchId') == $branch[0]['iBranchId']) ? 'p-none' : '';?>" name ="branch" id="validationCustom04" >
                                    <option selected="" value="">Choose...</option>
                                    <?php foreach($branch as $br){ ?>
                                    <option value="<?php echo $br['iBranchId'] ?>" <?php echo ($this->session->userdata('BranchId') == $br['iBranchId']) ? 'selected' : '';?>><?php echo $br['vBranchName'] ?></option>
                                    <?php } ?>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="validationCustom04">Salesman</label>
                                <select class="form-select salesman_select <?php echo ($this->session->userdata('iUserId') == $branch[0]['LoggedId'] && $this->session->userdata('UserRole') != 1) ? 'p-none' : '';?>" name ="salesman" id="validationCustom04" >
                                    <option selected="" value="">Choose...</option>
                                    <?php foreach($salesman as $sale){ ?>
                                    <option value="<?php echo $sale['iUserId'] ?>" <?php echo ($this->session->userdata('LoggedId') == $sale['iUserId'] && $this->session->userdata('UserRole') != 1) ? 'selected' : '';?>><?php echo $sale['vName'] ?></option>
                                    <?php } ?>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="validationCustom04">From</label>
                                <input class="form-control from_date datepicker-here" id="validationCustom03" type="text" name="name" autocomplete="off" placeholder="" >
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="validationCustom04">To</label>
                                <input class="form-control to_date datepicker-here" id="validationCustom03" type="text" name="name" autocomplete="off" placeholder="" >
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                                <div class="col-md-2">
                                <label class="form-label mnone col-md-12"><br></label>
                                    <button class="btn btn-primary" id="submit" type="submit"><i class="icofont icofont-ui-search"></i></button>
                                    <button class="btn btn-danger reset" type="submit"><i class="icofont icofont-refresh"></i></button>
                                    <!-- <button class="btn btn-succuss"><i class="fas fa-file-export"></i></button> -->
                                </div>
                            </div>
                        <!-- </form> -->
                    </div>
                </div>
                <div id="display_card" class="card d-none">
                    <div class="card-body">
                        <div class="table-responsive reference-report">
                            <div id='result_div' class="panel-body">
                                <table class="table list-table display td-nowrap basictable" id="basic_receipt">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="check_all_box"></th>
                                            <th>Inv Id #</th>
                                            <th>Receipt Id #</th>
                                            <th>Customer Name</th>
                                            <th>Inv Amt</th>
                                            <!--<th>Return Amount</th>-->
                                            <th>Paid Amt</th>
                                            <!-- <th>Dis Amt</th> -->
                                            <!-- <th>Additional Chrg</th> -->
                                            <th>Expense Chrg</th>
                                            <!-- <th>Balance</th> -->
                                            <th>Receipt Date</th>
                                            <!-- <th>Paid Date</th>
                                            <th>Due Date</th> -->
                                            <th>Payment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <!--<td class="text_right total-bg"></td>-->
                                            <!--<td class="text_right total-bg"><?= number_format($advance, 2, '.', ',') ?></td>-->
                                            <td class="text_right total-bg"><?= number_format(($paid + $advance), 2, '.', ',') ?></td>
                                            <td  class="text_right total-bg" data-th="Dis Amt"></td>
                                            <td class="text_right total-bg"><?= number_format($bal, 2, '.', ',') ?></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <!-- <td class=""></td> -->
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="display_card_calc" class="card d-none">
                    <div class="card-body">
                        <form autocomplete="off" class="needs-validation" novalidate="" method="post" id="cash_form" action="<?= $this->config->item('base_url') . 'sales_receipt/cash_handover/' ?>">
                            <div class="list-table display td-nowrap">
                                <div class="row" id="cash_info">
                                <input type="hidden" class="user_role" value="<?php echo $this->session->userdata('UserRole');?>">
                                    <input type="hidden" class="branch_id" name="branch_id" value="">
                                    <input type="hidden" class="salesman_id" name="salesman_id" value="">
                                    <input type="hidden" class="sales_order_id" name="sales_order_id[]" value="">
                                    <div class="col-md-2">
                                        <label class="form-label">Paid Amount</label><br>
                                        <input type="text" class="form-control net_cost_total" readonly="" name="net_cost" value="0.00">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Salesman Expenses</label><br>
                                        <input type="text" class="form-control add_charge" readonly="" name="add_charge" value="0.00">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label" for="validationCustom03">Paid Date </label><br>
                                        <input type="text" id="validationCustom03" class="form-control paid_date datepicker-here text-left" required="" name="paid_date">
                                        <div class="invalid-feedback">Field is required.</div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label" for="validationCustom04">Remarks</label><br>
                                        <input type="text" class="form-control remarks text-left" id="validationCustom04" required="" name="remarks">
                                        <div class="invalid-feedback">Field is required.</div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Balance </label><br>
                                        <input type="text" class="form-control balance" readonly="" name="balance" value="0.00">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label col-md-12 mnone"><br></label>
                                        <input type="submit" class="btn btn-success submit">
                                        <input class="btn btn-danger" type="reset" value="Cancel">
                                    </div>
                                </div>
                            </div>
                        </form>
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
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/dataTables.buttons.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/jszip.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/pdfmake.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/vfs_fonts.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/buttons.html5.min.js"></script>
<script>
    $(document).ready(function(){
        // datatable_reciept();
    })
    $('#submit').on('click',function(){
        $("#basic_receipt").dataTable().fnDestroy();
        var from_date = $('.from_date').val();
        var to_date = $('.to_date').val();
        var salesman = $('.salesman_select').val();
        var branch = $('.branch_select').val();
        if(salesman!='' && branch!=''){
        $('.salesman_id').val(salesman);
        $('.branch_id').val(branch);
        $('#display_card').removeClass('d-none');
        $('#display_card_calc').removeClass('d-none');
        datatable_reciept(from_date,to_date,salesman,branch);
        }
        else{
        Swal.fire({
        title: 'Warning',
        text: "Please Select Branch & Salesman",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ok'
        })
        $('#display_card').addClass('d-none'); 
        $('#display_card_calc').addClass('d-none'); 
        }
    })
    function datatable_reciept(from_date="",to_date="",salesman="",branch=""){
        var table = $('#basic_receipt').DataTable({
            "processing":true,
			"serverSide":true,
            "pageLength": 10,
			"order":[],
            "ajax": {
                url : "<?php echo base_url('sales_receipt/cash_hand_over_ajaxList/'); ?>",
                data : {from_date:from_date,to_date:to_date,salesman:salesman,branch:branch},
                type: "POST",
            },
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', '#');
                $(row).find('td:eq(1)').attr('data-th', 'Inv Id #');
                $(row).find('td:eq(2)').attr('data-th', 'Receipt Id #');
                $(row).find('td:eq(3)').attr('data-th', 'Customer Name');
                $(row).find('td:eq(4)').attr('data-th', 'Inv Amt');
                $(row).find('td:eq(5)').attr('data-th', 'Paid Amt');
                // $(row).find('td:eq(5)').attr('data-th', 'Dis Amt');
                // $(row).find('td:eq(6)').attr('data-th', 'Additional Chrg');
                $(row).find('td:eq(6)').attr('data-th', 'Expense Chrg');
                // $(row).find('td:eq(7)').attr('data-th', 'Balance');
                $(row).find('td:eq(7)').attr('data-th', 'Inv Date');
                $(row).find('td:eq(8)').attr('data-th', 'Payment Status');
            },
            "oLanguage": {
            "sInfoFiltered": ""
             },
			 "columnDefs":[  
                {
                    "targets": 0,
                    "orderable": false
                },
                {  
					"targets":[0,1,2,7,8],  
					"className":"text-center"
				},
                {
			 		"targets":[4],
			 		"className":"net_cost text-right"
			 	}, 
                 {
			 		"targets":[5],
			 		"className":"paid_amt text-right"
			 	},
                 {
			 		"targets":[6],
			 		"className":"expense_amt text-right"
			 	},
                {  
			 		"targets":[2],  
			 		"className":"customer_name"
			 	}
			 ], 
             "dom": 'lBfrtip',
            "buttons": [
                { extend: 'excelHtml5', footer: true },
            ],
            "drawCallback": function( settings ) {
                var prev_check_id = $(".sales_order_id").val().split(",");
                $(prev_check_id).each(function( key, value ) {
                    $(".cash_hand_prev_"+value).prop("checked", true);
                })
            },
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;
                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                    i : 0;
                };
                var cols = [4, 5, 6];
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
                    $(api.column(cols[x]).footer()).html(numFormat(pageTotal));
                }
            },
        });
    }
    $(".reset").click(function() {
        $("#basic_receipt").dataTable().fnDestroy();
        var user_role = $('.user_role').val();
        if(user_role==1)
        $('.from_date,.to_date,.salesman_select,.branch_select').val("");
        else
        $('.from_date,.to_date').val("");
        $('#submit').trigger('click')
        // datatable_reciept();
    });
    $(document).on('keyup','.add_charge',function(){
        calculate();
    });
    // Check calculation
    $(document).on('change','.cash_hand_id',function(){
        $(".sales_order_id").val($(".cash_hand_id:checked").map(function(){return $(this).val();}).get());
        calculate();
    });
    function calculate(){
        // var quantity = $(this);
        $(".cash_hand_id:checked").each(function(){return $(this).val();}).get();
        var net_cost = 0;
        var paid_amt = 0;
        var discount_amt = 0;
        var add_charge = 0;
        var balance = 0;
        var expense = 0;
        $(".cash_hand_id:checked").closest('tr').find(".net_cost").each(function(){
            net_cost += +parseFloat($(this).text().replace(/,/g, ''));
        });
        $(".cash_hand_id:checked").closest('tr').find(".paid_amt").each(function(){
            paid_amt += +parseFloat($(this).text().replace(/,/g, ''));
        });
        $(".cash_hand_id:checked").closest('tr').find(".disc_amt").each(function(){
            discount_amt += +parseFloat($(this).val().replace(/,/g, ''));
        });
        $(".cash_hand_id:checked").closest('tr').find(".additional_charge").each(function(){
            add_charge += +parseFloat($(this).text().replace(/,/g, ''));
        });
        $(".cash_hand_id:checked").closest('tr').find(".expense_amt").each(function(){
            expense += +parseFloat($(this).text().replace(/,/g, ''));
        });
        $('.add_charge').val(parseFloat(expense));
        var total_net = parseFloat(net_cost);
        var total_paid = parseFloat(paid_amt) + parseFloat(discount_amt);
        $('.net_cost_total').val(total_paid.toFixed(2));
        var total_balance = parseFloat(total_paid);
        if(expense!='')
        total_balance = total_balance - expense;
        $('.balance').val(total_balance.toFixed(2));
    }
    $('.submit').on('click',function(){
        // e.preventDefault();
        let isChecked = $('.cash_hand_id').is(':checked');
        if(!isChecked){
        Swal.fire({
        title: 'Warning',
        text: "Please Select Invoice",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ok'
        })
        return false;
        }
    });
    $('.check_all_box').on('change',function(){
        if($(this).is(':checked')){
        $(".cash_hand_id").trigger("click");
        $(".cash_hand_id").prop("checked", true);
        }
        else{
        $('.net_cost_total,.balance,.add_charge').val("");
        $(".cash_hand_id").trigger("click");
        $(".cash_hand_id").prop("checked", false);
        }
    });

    //get Salesman name by Branch
    $(".branch_select").on('change', function(){
        var branch = $(this).val();  
        $('.salesman_select').empty();
            $('.salesman_select').val('');
            $( ".salesman_select" ).removeClass( "disabled" )
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>order/get_salesman",
            data:{branch:branch},
            success: function(data){
                data = JSON.parse(data);
                var html = '';
                html += '<option value="">Choose Salesman</option>';
                $.each(data, function(key,val) {
                    html += '<option value='+val['iUserId']+'>'+val['vName']+'</option>';
                });
                    $('.salesman_select').html(html);
            }
        });
    });
</script>
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-time-picker/moment.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-time-picker/tempusdominus-bootstrap-4.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-time-picker/datetimepicker.custom.js"></script>
<script>
    $('.paid_date').datepicker({
       position: 'top left',
       maxDate: new Date()
    });
</script>