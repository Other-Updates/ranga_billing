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
<div class="container-fluid">        
    <div class="page-title">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h3>Warehouse Stock Report</h3>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Report</li>
                <li class="breadcrumb-item active">Reference Report</li>
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
                            <div class="col-md-3">
                                <label class="form-label" for="validationCustom04">Category</label>
                                <select class="form-select category_select" name ="category" id="validationCustom04" >
                                    <option selected="" value="">Choose...</option>
                                    <?php foreach($categories as $category){ ?>
                                    <option value="<?php echo $category['iCategoryId'] ?>"><?php echo $category['vCategoryName'] ?></option>
                                    <?php } ?>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="validationCustom04">Subcategory</label>
                                <select class="form-select subcategory_select" name ="category" id="validationCustom04" >
                                    <option selected="" value="">Choose...</option>
                                    <?php foreach($subcategories as $subcategory){ ?>
                                    <option value="<?php echo $subcategory['iSubcategoryId'] ?>"><?php echo $subcategory['vSubcategoryName'] ?></option>
                                    <?php } ?>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <!-- <div class="col-md-2">
                                <label class="form-label" for="validationCustom04">From</label>
                                <input class="form-control from_date datepicker-here" id="validationCustom03" readonly type="text" name="name" placeholder="" >
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="validationCustom04">To</label>
                                <input class="form-control to_date datepicker-here" id="validationCustom03" readonly type="text" name="name" placeholder="" >
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div> -->
                            <div class="col-md-3">
                                <label class="form-label col-md-12 mnone"><br></label>
                                <button class="btn btn-primary" id="submit" type="submit"><i class="icofont icofont-ui-search"></i></button>
                                <button class="btn btn-danger reset" type="reset"><i class="icofont icofont-refresh"></i></button>
                                <button class="btn btn-succuss"><i class="fas fa-file-export"></i></button>
                            </div>
                        </div>
                    <!-- </form> -->
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive reference-report">
                        <table class="list-table display basictable" id="basic">
                            <thead>
                                <tr>
                                    <th>Sno</th>
                                    <th>Category</th>
                                    <th>Subcategory</th>
                                    <th>Product</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
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
                                    <td></td>
                                    <td class=""></td>
                                    <td class="bg-success hide_class text-right"></td>
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
    $(document).ready(function(){
        datatable();
    })
    $('#submit').on('click',function(){
        var branch = $('.branch_select').val();
        var category = $('.category_select').val();
        var subcategory = $('.subcategory_select').val();
        datatable(branch,category,subcategory);
    })
    $(document).on('click','.reset',function(){
        $('.branch_select').val('');
        $('.category_select').val('');
        $('.subcategory_select').val('');
    })
    function datatable(branch="",category="",subcategory=""){
        var table = $('#basic').DataTable({
            "processing":true,
            "serverSide":true,
            "destroy": true,
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ],
            "pageLength": 50,
            // "retrieve":true,
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ],
            "pageLength": 50,
            "order":[], 
            "ajax": {
                url : "<?php echo base_url('report/stock_report/get_warehouse_stock'); ?>",
                data : {branch:branch,category:category,subcategory:subcategory},
                type: "POST",
            },
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'SO#');
                $(row).find('td:eq(1)').attr('data-th', 'Category');
                $(row).find('td:eq(2)').attr('data-th', 'Subcategory');
                $(row).find('td:eq(3)').attr('data-th', 'Product');
                $(row).find('td:eq(4)').attr('data-th', 'Unit');
                $(row).find('td:eq(5)').attr('data-th', 'Quantity');
                $(row).find('td:eq(6)').attr('data-th', 'Price');
                $(row).find('td:eq(6)').attr('class', 'text-right');
            },
            "columnDefs":[{"targets":[0],"orderable":false,}, {"targets":[0, 5],"className":"text-center"}, {"targets":[6],"className":"text-center"} ], 
            "dom": 'lBfrtip',
            "buttons": [
                'excel', 'csv', 'pdf', 'copy',
            ],
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
                    var cols = [6];
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
        });
    }
</script>