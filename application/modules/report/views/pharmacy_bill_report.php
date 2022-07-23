<?php
    $theme_path = $this->config->item('theme_locations');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/date-picker.css">
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-picker/datepicker.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/jquery.basictable.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.basictable').basictable({
        breakpoint: 768
        });
    });
</script>
<style>
.p-none {
    background: #e7e7e7;
    pointer-events: none;
}
</style>
<div class="container-fluid">        
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Pharmacy Bill Report</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')  ?>"><i class="fa fa-home"></i></a></li>
                <li class="breadcrumb-item">Report</li>
                <li class="breadcrumb-item active">Pharmacy Bill Report</li>
                </ol>
                <?php if ($this->session->flashdata('error')) { ?>
                <div class="alert alert-danger"> <?= $this->session->flashdata('error') ?> </div>
            <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">        
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <!-- <form id="reference_form"> -->
                        <div class="row row-8 g-3">
                            <div class="col-md-3">
                                <label class="form-label" for="validationCustom04">Salesman</label>
                                <select class="form-select salesman" name ="salesman" id="validationCustom04" >
                                    <option selected="" value="">Choose...</option>
                                    <?php foreach($salesman as $sale){ ?>
                                    <option value="<?php echo $sale['iUserId'] ?>" <?php echo ($this->session->userdata('LoggedId') == $sale['iUserId'] && $this->session->userdata('UserRole') != 1) ? 'selected' : '';?>><?php echo $sale['vName'] ?></option>
                                    <?php } ?>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="validationCustom04">Customer</label>
                                <select class="form-select distributor" name ="customer" id="validationCustom04" >
                                    <option selected="" value="">Choose...</option>
                                    <?php foreach($distributor as $distribute){ ?>
                                    <option value="<?php echo $distribute['iCustomerId'] ?>"><?php echo $distribute['vCustomerName'] ?></option>
                                    <?php } ?>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="validationCustom04">From</label>
                                <input class="form-control from_date datepicker-here" id="validationCustom03" type="text" name="name" autocomplete="off" placeholder="" >
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="validationCustom04">To</label>
                                <input class="form-control to_date datepicker-here" id="validationCustom03" type="text" name="name" autocomplete="off" placeholder="" >
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="validationCustom04">Status</label>
                                <select class="form-select delivery_status" name ="status" id="validationCustom04" >
                                    <option selected="" value="">Choose</option>
                                    <option  value="Delivered">Delivered</option>
                                    <option value="Not shipped">Not Shipped</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="validationCustom04">Payment status</label>
                                <select class="form-select payment_status" name ="payment_status" id="validationCustom04" >
                                    <option selected="" value="">Select</option>
                                    <option value="paid">Paid</option>
                                    <option value="unpaid">Unpaid</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label col-md-12 mnone"><br></label>
                                <button class="btn btn-primary" id="submit" type="submit"><i class="icofont icofont-ui-search"></i></button>
                                <button class="btn btn-danger reset" type="submit"><i class="icofont icofont-refresh"></i></button>
                                <!-- <button class="btn btn-succuss"><i class="fas fa-file-export"></i></button> -->
                            </div>
                            <div class="col-md-2">
                            <label class="form-label col-md-12 mnone"><br></label>
                            <ul>
                            <li class="profile-nav onhover-dropdown p-0 me-0 pull-sm-right">
                                <button type="button" class=" btn btn-success" data-toggle="dropdown">
                                    Gst Report&nbsp;&nbsp;<i class="middle fa fa-angle-down"></i>
                                </button>
                                <ul class="profile-dropdown onhover-show-div drop-shadow-1" style="top:35px; width: 160px;">
                                <li class="p-2"><a href="#" class="text-drop excel_summary" data-value="summary"><i class="fa fa-download"></i> Summary Report</a></li>
                                <li class="border-top-dd p-2"><a href="#" class="text-drop excel_summary" data-value="details"><i class="fa fa-download"></i> Details Report</a></li>
                                </ul>
                            </li>
                            <ul>
                            </div>
                        </div>
                    <!-- </form> -->
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive reference-report">
                        <table class="list-table display td-nowrap basictable" id="basic">
                            <thead>
                                <tr>
                                    <!-- <th>Sno</th> -->
                                    <th>SO#</th>
                                    <th>Customer</th>
                                    <th>Head office</th>
                                    <th>Branch</th>
                                    <th class="sum_qty">QTY</th>
                                    <!-- <th>IGST</th> -->
                                    <th>CGST</th>
                                    <th>SGST</th> 
                                    <th>SubTotal</th> 
                                    <th class="sum_amt">Inv Amt</th>
                                    <th class="paid_amt">Paid</th>
                                    <th class="disc_amt">Discount</th>
                                    <th class="bal_amt">Balance</th>
                                    <th>Payment</th>
                                    <th>Inv Date</th>
                                    <th>Created Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <!-- <th></th> -->
                                    <th></th>
                                    <th class="bg-success total_qty text-right"></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="bg-success"></th>
                                    <th class="bg-success"></th>
                                    <th class="bg-success"></th>
                                    <th class="bg-success"></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/dataTables.buttons.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/jszip.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/pdfmake.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/vfs_fonts.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datatable/datatable-extension/buttons.html5.min.js"></script>
<script>
$(".alert-danger").delay(3200).fadeOut(300);
    $(document).ready(function(){
        datatable();
    })
    $('#submit').on('click',function(){
        var salesman = $('.salesman').val();
        var distributor = $('.distributor').val();
        var from_date = $('.from_date').val();
        var to_date = $('.to_date').val();
        var status = $('.delivery_status').val();
        var payment_status = $('.payment_status').val();
        datatable(salesman,distributor,from_date,to_date,status,payment_status);
    })
    function datatable(salesman="",distributor="",from_date="",to_date="",status="",payment_status=""){
        var table = $('#basic').DataTable({
            "processing":true,
            "serverSide":true,
            "destroy": true,
            // "retrieve":true,
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ],
            "pageLength": 50,
            "order":[], 
            "ajax": {
                url : "<?php echo base_url('report/pharmacy_report/get_reference'); ?>",
                data : {salesman:salesman,distributor:distributor,from_date:from_date,to_date:to_date,status:status,payment_status:payment_status},
                type: "POST",
            },
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'SO#');
                $(row).find('td:eq(1)').attr('data-th', 'Customer');
                $(row).find('td:eq(2)').attr('data-th', 'Head office');
                $(row).find('td:eq(3)').attr('data-th', 'Branch');
                $(row).find('td:eq(4)').attr('data-th', 'Qty');
                $(row).find('td:eq(5)').attr('data-th', 'CGST');
                $(row).find('td:eq(6)').attr('data-th', 'SGSTs');
                $(row).find('td:eq(7)').attr('data-th', 'SubTotal');
                $(row).find('td:eq(8)').attr('data-th', 'Inv Amt');
                $(row).find('td:eq(9)').attr('data-th', 'Paid');
                $(row).find('td:eq(10)').attr('data-th', 'Discout');
                $(row).find('td:eq(11)').attr('data-th', 'Balance');
                $(row).find('td:eq(12)').attr('data-th', 'Payment');
                $(row).find('td:eq(13)').attr('data-th', 'Inv Date');
                $(row).find('td:eq(14)').attr('data-th', 'Created Date');
                $(row).find('td:eq(15)').attr('data-th', 'Status');
                $(row).find('td:eq(16)').attr('data-th', 'Action');
            },
            // "footerCallback": function (settings, json) {
            //     var data_length = this.api().data().length;
            //     this.api().columns('.sum_amt').every(function () {
            //         var column = this;
            //         sum = 0;
            //         if(data_length > 0){
            //             var sum = column
            //             .data()
            //             // [].reduce( (a, b) => a + b, 0);
            //             .reduce(function (a, b) { 
            //                 a = parseInt(a, 10);
            //                 if(isNaN(a)){ a = 0; }

            //                 b = parseInt(b, 10);
            //                 if(isNaN(b)){ b = 0; }

            //                 return a + b;
            //             });
            //         }

            //         $(column.footer()).html(sum +'.00');
            //     });
            //     this.api().columns('.sum_qty').every(function () {
            //         var column = this;
            //         sum = 0;
            //         if(data_length > 0){
            //             var sum = column
            //             .data()
            //             .reduce(function (a, b) { 
            //                 a = parseInt(a, 10);
            //                 if(isNaN(a)){ a = 0; }

            //                 b = parseInt(b, 10);
            //                 if(isNaN(b)){ b = 0; }

            //                 return a + b;
            //             });
            //         }
            //         // $(column.footer()).html('Total: ' + sum+'.00');
            //         $(column.column(4).footer()).html(sum+'.00');
            //     });
            //     this.api().columns('.paid_amt').every(function () {
            //         var column = this;
            //         sum = 0;
            //         if(data_length > 0){
            //             var sum = column
            //             .data()
            //             .reduce(function (a, b) { 
            //                 a = parseInt(a, 10);
            //                 if(isNaN(a)){ a = 0; }

            //                 b = parseInt(b, 10);
            //                 if(isNaN(b)){ b = 0; }

            //                 return a + b;
            //             });
            //         }
            //         // $(column.footer()).html('Total: ' + sum+'.00');
            //         $(column.column(9).footer()).html(sum+'.00');
            //     });
            //     this.api().columns('.disc_amt').every(function () {
            //         var column = this;
            //         sum = 0;
            //         if(data_length > 0){
            //             var sum = column
            //             .data()
            //             .reduce(function (a, b) { 
            //                 a = parseInt(a, 10);
            //                 if(isNaN(a)){ a = 0; }

            //                 b = parseInt(b, 10);
            //                 if(isNaN(b)){ b = 0; }

            //                 return a + b;
            //             });
            //         }
            //         // $(column.footer()).html('Total: ' + sum+'.00');
            //         $(column.column(10).footer()).html(sum+'.00');
            //     });
            //     this.api().columns('.bal_amt').every(function () {
            //         var column = this;
            //         sum = 0;
            //         if(data_length > 0){
            //             var sum = column
            //             .data()
            //             .reduce(function (a, b) { 
            //                 a = parseInt(a, 10);
            //                 if(isNaN(a)){ a = 0; }

            //                 b = parseInt(b, 10);
            //                 if(isNaN(b)){ b = 0; }

            //                 return a + b;
            //             });
            //         }
            //         // $(column.footer()).html('Total: ' + sum+'.00');
            //         $(column.column(11).footer()).html(sum+'.00');
            //     });
            // },
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
                    var cols = [4, 8, 9, 10, 11];
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
            "columnDefs":[
                {"targets":[16],"orderable":false,}, 
                {"targets":[0,4,12,16],"className":"text-center"}, 
                {"targets":[1],"className":"text-left"}, 
                {"targets":[5,6,7,8,10,9,11],"className":"text-right"} ], 
            "dom": 'lBfrtip',
            "buttons": [
                { extend: 'excelHtml5', footer: true },
            ],
        });
    }
    $(".reset").click(function() {
        $('.salesman').val("");
        $('.distributor').val("");
        $('.from_date').val("");
        $('.payment_status').val("");
        $('.delivery_status').val("");
        $('.to_date').val("");
        datatable();
    });
    // Gst Excel Export
    $(".excel_summary").click(function() {
        var type = $(this).attr("data-value");
        var salesman = $('.salesman').val();
        var distributor = $('.distributor').val();
        var from_date = $('.from_date').val();
        var to_date = $('.to_date').val();
        var status = $('.delivery_status').val();
        var payment_status = $('.payment_status').val();
        window.location.href = "<?php echo base_url('report/reference_report/export_sales_details'); ?>?type=" + type + "&from_date=" + from_date +
               "&to_date=" + to_date + "&salesman=" + salesman + "&distributor=" + distributor + "&status=" + status + "&payment_status="+ payment_status;
    });
</script>