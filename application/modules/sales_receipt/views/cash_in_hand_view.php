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
                    <h3>Cash In Hand List</h3>
                </div>
                <div class="col-6">                
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                    <li class="breadcrumb-item">Sales Order</li>
                    <li class="breadcrumb-item active">Cash In Hand List</li>
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
                            <table class="table list-table display basictable" id="basic_receipt">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <!-- <th class='action-btn-align'>Inv Id #</th> -->
                                            <th>Branch</th>
                                            <th>Salesman</th>
                                            <!--<th>Return Amount</th>-->
                                            <!-- <th>Customer</th> -->
                                            <th>Total Amt</th>
                                            <th>Expense Chrg</th>
                                            <th>Paid Amt</th>
                                            <th>Remarks</th>
                                            <!-- <th>Paid Date</th>
                                            <th>Due Date</th> -->
                                            <!-- <th>Paid Date</th> -->
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <!-- <td></td> -->
                                            <td></td>
                                            <td></td>
                                            <!-- <td></td> -->
                                            <td class="text_right total-bg"></td>
                                            <!--<td class="text_right total-bg"></td>-->
                                            <!--<td class="text_right total-bg"></td>-->
                                            <td class="text_right total-bg"></td>
                                            <td  class="text_right total-bg" data-th="Dis Amt"></td>
                                            <td class=""><div class="mw-200"> &nbsp; </div></td>
                                            <td class=""></td>
                                            <!-- <td class=""></td> -->
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
        </div>

        <div class="modal fade" id="kt_modal_edit_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cash Details</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <div class="modal-body scroll-y">
                <form class="needs-validation" novalidate="" method="post" enctype="multipart/form-data" >                
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="pp-sales-detail">
                                <table class="table delivery-order popup-table basictable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.NO</th>
                                            <th>Inv Id</th>
                                            <th>Receipt Id</th>
                                            <th>Salesman</th>
                                            <th>Customer</th>
                                            <th class="text-right">Paid Amt</th>
                                            <th class="text-right">Expense</th>
                                            <th class="text-right">Total Amt</th>
                                            <th>Remark</th>
                                            <th class="text-center">Paid Date</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cash_info">
                                        <!-- <tr>
                                            <td class="count_id text-center">1</td>
                                            <td class="salesman"></td>
                                            <td class="Customer"></td>
                                            <td class="paid_amt text-right"></td>
                                            <td class="total_amt text-right"></td>
                                            <td class="expense_amt text-right"></td>
                                            <td class="remark"></td>
                                            <td class="paid_amt text-center"></td>
                                            <td class="action"></td>
                                        </tr> -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text_right total-bg total_amt_text" colspan="2"></td>
                                            <td class="text_right total-bg total_amt_cash"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" data-bs-original-title="" title="">Close</button>
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
        $('#kt_modal_edit_user').modal('hide');
        // datatable_reciept();
    })
    $('#submit').on('click',function(){
        $("#basic_receipt").dataTable().fnDestroy();
        var from_date = $('.from_date').val();
        var to_date = $('.to_date').val();
        var salesman = $('.salesman_select').val();
        var branch = $('.branch_select').val();
        datatable_reciept(from_date,to_date,salesman,branch);
        $('#display_card').removeClass('d-none');
    })
    function datatable_reciept(from_date="",to_date="",salesman="",branch=""){
        var table = $('#basic_receipt').DataTable({
            "processing":true,
			"serverSide":true,
            "pageLength": 10,
			"order":[],
            "ajax": {
                url : "<?php echo base_url('sales_receipt/cash_hand_over_ajaxList_view/'); ?>",
                data : {from_date:from_date,to_date:to_date,salesman:salesman,branch:branch},
                type: "POST",
            },
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', '#');
                // $(row).find('td:eq(1)').attr('data-th', 'Inv Id #');
                $(row).find('td:eq(1)').attr('data-th', 'Branch');
                $(row).find('td:eq(2)').attr('data-th', 'Salesman');
                // $(row).find('td:eq(4)').attr('data-th', 'Customer');
                $(row).find('td:eq(3)').attr('data-th', 'Total Amt');
                $(row).find('td:eq(4)').attr('data-th', 'Expense');
                $(row).find('td:eq(5)').attr('data-th', 'Paid Amt');
                $(row).find('td:eq(6)').attr('data-th', 'Remarks');
                // $(row).find('td:eq(7)').attr('data-th', 'Paid Date');
                $(row).find('td:eq(7)').attr('data-th', 'Action');
            },
            "oLanguage": {
            "sInfoFiltered": ""
             },
			 "columnDefs":[  
                {  
					"targets":[0,7],  
					"className":"text-center"
				},
                {
			 		"targets":[1],
			 		"className":"net_cost"
			 	}, 
                 {
			 		"targets":[2],
			 		"className":"paid_amt"
			 	},
                 {
			 		"targets":[3],
			 		"className":"text-right"
			 	},
                 {
			 		"targets":[4],
			 		"className":"additional_charge text-right"
			 	},
                 {
			 		"targets":[5],
			 		"className":"balance_amt text-right"
			 	},
                {  
			 		"targets":[6],  
			 		"className":"customer_name"
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
                var cols = [3, 4, 5];
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
        $('.from_date,.to_date,.salesman_select,.branch_select').val("");
        datatable_reciept();
    });
    $(document).on('click','.view_cashinfo',function(){
      var cash_id = $(this).attr('data-id');
      var from_date = $('.from_date').val();
      var to_date = $('.to_date').val();
      $('#cash_info').empty();
      $('#sales_info').empty();
      $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>sales_receipt/get_cash_hand_info",
            data:{cash_id:cash_id,from_date:from_date,to_date:to_date},
            beforeSend: function(){
			$("#popup-loader").addClass('show').addClass('d-block');
		    },
            success: function(data){
                data = JSON.parse(data);
                var html='';
                var i = 1;
                var total=0;
                var total_cash=0;
                $(data.cash_details).each(function( key, value ) {
                    console.log(value.iReceiptId);
                    // var value = $(this).val().replace(" ", "");
                    words = value.iReceiptId.split(",");
                    var rcount = words.length;
                    total = (value.bill_amount) - (value.expense);
                    html += '<tr><td class="count_id text-center">'+i+'</td>';
                    html += '<td class="inv_no">'+value.vSalesOrderNo+'</td>';
                    html += '<td class="receipt_no">'+value.receipt_no+'</td>';
                    html += '<td class="salesman">'+value.vName+'</td>';
                    html += '<td class="customer">'+value.vCustomerName+'</td>';
                    html += '<td class="paid_amt text-right">'+value.bill_amount+'</td>';
                    html += '<td class="expense_amt text-right">'+value.expense+'</td>';
                    html += '<td class="total_amt text-right">'+total+'</td>';
                    html += '<td class="remark">'+value.tRemarks+'</td>';
                    html += '<td class="paid_date text-center">'+moment(value.dPaidDate).format('DD-MM-YYYY')+'</td>';
                    html += '<td class="view text-center"><a href="<?php echo base_url() ?>sales_receipt/view_receipt/'+value.receipt_id+'" class="action-icon" title="View" data-original-title="View" ><span class="fa fa-eye fs-5"></span></a></td></tr>';
                    total_cash += total;
                    i++;
                })
                $('#cash_info').append(html);
                $('.total_amt_text').html('<span>Total Amount</span> &nbsp;');
                $('.total_amt_cash').html(total_cash);
                $("#popup-loader").removeClass('show').removeClass('d-block');
                $('#kt_modal_edit_user').modal('show');
            }
        });
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