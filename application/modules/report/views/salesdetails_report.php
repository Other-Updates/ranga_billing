<?php $theme_path = $this->config->item('theme_locations');?>
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
<div class="container-fluid">        
    <div class="page-title">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h3>Sales Detail Report</h3>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')  ?>"><i class="fa fa-home"></i></a></li>
                <li class="breadcrumb-item">Report</li>
                <li class="breadcrumb-item active">Sales Detail Report</li>
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
                    <!-- <form id="reference_form"> -->
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label" for="validationCustom04">Salesman</label>
                                <select class="form-select salesman" name ="salesman" id="validationCustom04" >
                                    <option selected="" value="">Choose...</option>
                                    <?php foreach($salesman as $sale){ ?>
                                    <option value="<?php echo $sale['iUserId'] ?>"><?php echo $sale['vName'] ?></option>
                                    <?php } ?>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="validationCustom04">Customer</label>
                                <select class="form-select distributor" name ="customer" id="validationCustom04" >
                                    <option selected="" value="">Choose...</option>
                                    <?php foreach($distributor as $distribute){ ?>
                                    <option value="<?php echo $distribute['iCustomerId'] ?>"><?php echo $distribute['vCustomerName'] ?></option>
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
                                <label class="form-label" for="validationCustom04">Status</label>
                                <select class="form-select delivery_status" name ="status" id="validationCustom04" >
                                    <option value="">Choose...</option>
                                    <option value="Delivered">Delivered</option>
                                    <option value="Not Shipped">Not Shipped</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid status.</div>
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
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive reference-report">
                        <table class="list-table display td-nowrap basictable" id="basic">
                            <thead>
                                <tr>
                                    <th>SO#</th>
                                    <th>Customer</th>
                                    <th>Branch</th>
                                    <th>Product</th>
                                    <th>QTY</th>
                                    <th>Unit</th>
                                    <th>Inv Date</th>
                                    <th>Created Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
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
    $(document).ready(function(){
        datatable();
    })
    $('#submit').on('click',function(){
        var salesman = $('.salesman').val();
        var distributor = $('.distributor').val();
        var from_date = $('.from_date').val();
        var to_date = $('.to_date').val();
        var status = $('.delivery_status').val();
        datatable(salesman,distributor,from_date,to_date,status);
    })
    function datatable(salesman="",distributor="",from_date="",to_date="",status=""){
        var table = $('#basic').DataTable({
            "processing":true,
            "serverSide":true,
            "destroy": true,
            // "retrieve":true,
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ],
            "pageLength": 50,
            "order":[], 
            "ajax": {
                url : "<?php echo base_url('report/salesdetails/salesdetail_data'); ?>",
                data : {salesman:salesman,distributor:distributor,from_date:from_date,to_date:to_date,status:status},
                type: "POST",
            },
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'SO#');
                $(row).find('td:eq(1)').attr('data-th', 'Customer');
                $(row).find('td:eq(2)').attr('data-th', 'Branch');
                $(row).find('td:eq(3)').attr('data-th', 'Product');
                $(row).find('td:eq(4)').attr('data-th', 'QTY');
                $(row).find('td:eq(5)').attr('data-th', 'Unit');
                $(row).find('td:eq(6)').attr('data-th', 'Inv Date');
                $(row).find('td:eq(7)').attr('data-th', 'Created Date');
                $(row).find('td:eq(8)').attr('data-th', 'Status');
                $(row).find('td:eq(9)').attr('data-th', 'Action');
            },
            "dom": 'lBfrtip',
             "columnDefs":[
                {"targets":[9],"orderable":false,}, 
                {"targets":[0,4,5,6,7,8,9],"className":"text-center"}], 
            "buttons": [
                { extend: 'excelHtml5', footer: true },
            ],
        });
    }
    $(".reset").click(function() {
        $('.salesman,.distributor,.from_date,.to_date,.delivery_status').val("");
        datatable();
    });
</script>