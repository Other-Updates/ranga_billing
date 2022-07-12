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
                <h3>Purchase Detail Report</h3>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Report</li>
                <li class="breadcrumb-item active">Purchase Detail Report</li>
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
                    <div class="row row-7 g-3">
                        <div class="col-md-3">
                            <label class="form-label" for="validationCustom04">Product</label>
                            <select class="form-select product">
                                <option selected="" value="">Choose...</option>
                                <?php 
                                if(isset($products) && !empty($products)){
                                    foreach($products as $product){ ?>
                                <option value="<?php echo $product['iProductId'] ?>"><?php echo $product['vProductName'] ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="validationCustom04">Product Unit</label>
                            <select class="form-select productunit">
                                <option selected="" value="">Choose...</option>
                                <?php 
                                if(isset($units) && !empty($units)){
                                foreach($units as $unit){ ?>
                                <option value="<?php echo $unit['iProductUnitId'] ?>"><?php echo $unit['vProductUnitName'] ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="validationCustom04">From</label>
                            <input class="form-control from_date datepicker-here" id="validationCustom03" type="text" name="name" autocomplete="off" placeholder="" >
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="validationCustom04">To</label>
                            <input class="form-control to_date datepicker-here" id="validationCustom03" type="text" name="name" autocomplete="off" placeholder="" >
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="validationCustom04">Status</label>
                            <select class="form-select delivery_status" name ="status" id="validationCustom04" >
                                <option value="">Choose</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Not Shipped">Not Shipped</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label mnone col-md-12"><br></label>
                            <button class="btn btn-primary" id="submit" type="submit"><i class="icofont icofont-ui-search"></i></button>
                            <button class="btn btn-danger reset" type="submit"><i class="icofont icofont-refresh"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive reference-report">
                        <table class="list-table display td-nowrap basictable" id="basic">
                            <thead>
                                <tr>
                                    <th>PO#</th>
                                    <th>Supplier</th>
                                    <th>Product</th>
                                    <th>QTY</th>
                                    <th>Price</th>
                                    <th>Unit</th>
                                    <th>Delivery Date</th>
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
    //Datatable - Initialilization 
    $(document).ready(function(){
        datatable();
    });
    //Search - Click Event 
    $(document).on('click','#submit',function(){
        var product = $('.product').val();
        var productunit = $('.productunit').val();
        var from_date = $('.from_date').val();
        var to_date = $('.to_date').val();
        var status = $('.delivery_status').val();
        datatable(product,productunit,from_date,to_date,status);
    });
    //Search - Reset Event
    $(".reset").click(function() {
        $('.product,.productunit,.from_date,.to_date,.delivery_status').val("");
        datatable();
    });
    //Datatable - Serverside Function 
    function datatable(product="",productunit="",from_date="",to_date="",status=""){
        var table = $('#basic').DataTable({
            "processing":true,
            "serverSide":true,
            "destroy": true,
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ],
            "pageLength": 50,
            "order":[], 
            "ajax": {
                url : "<?php echo base_url('report/purchasedetails/purchasedetail_data'); ?>",
                data : {product:product,productunit:productunit,from_date:from_date,to_date:to_date,status:status},
                type: "POST",
            },
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'SO#');
                $(row).find('td:eq(1)').attr('data-th', 'Supplier');
                $(row).find('td:eq(2)').attr('data-th', 'Product');
                $(row).find('td:eq(3)').attr('data-th', 'QTY');
                $(row).find('td:eq(4)').attr('data-th', 'Price');
                $(row).find('td:eq(5)').attr('data-th', 'Unit');
                $(row).find('td:eq(6)').attr('data-th', 'Inv Date');
                $(row).find('td:eq(7)').attr('data-th', 'Created Date');
                $(row).find('td:eq(8)').attr('data-th', 'Status');
                $(row).find('td:eq(9)').attr('data-th', 'Action');
            },
            "dom": 'lBfrtip',
             "columnDefs":[
                {"targets":[9],"orderable":false,}, 
                {"targets":[0,3,5,6,7,8,9],"className":"text-center"}, 
                {"targets":[4],"className":"text-right"} ], 
            "buttons": [
                { extend: 'excelHtml5', footer: true },
            ],
        });
    }
</script>