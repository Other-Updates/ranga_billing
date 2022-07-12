<?php
    $theme_path = $this->config->item('theme_locations');
?>
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
            <div class="col-6 mnone">
                <h3>Products</h3>
                <!-- <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add Product</button> -->
                <a href="<?php echo base_url('master/product/insert_product') ?>"><button type="button" class="btn btn-sm btn-primary">Add Product</button></a>
                &nbsp;&nbsp;<button type="button" class="btn btn-sm btn-info add_bluk_import"> Import Products</button>

            </div>
            <div class="sm-col-6 wnone">
                <h3 class="mb-1">Products</h3>
                <a href="<?php echo base_url('master/product/insert_product') ?>"><button type="button" class="btn btn-sm btn-primary">Add Product</button></a>
                &nbsp;&nbsp;<button type="button" class="btn btn-sm btn-info add_bluk_import"> Import Products</button>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Products</li>
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
                    <div class="table-responsive">
                        <table class="list-table display basictable" id="basic">
                        <thead>
                            <tr>
                            <th>Sno</th>                            
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category Name</th>
                            <th>HSN No</th>
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
<div id="myModal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Import Products</h6>
            </div>
            <form class="needs-validation" action="<?php echo $this->config->item('base_url'); ?>master/product/import_products" enctype="multipart/form-data" name="import_products" method="post" id="import_products">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Attachment</label>
                                    <input type="file" name="product_data" id="validationCustom03" class="form-control" accept=".csv,.xls,.xlsx" required>
                                    <span class="error_msg"></span>
                                    <a href="<?php echo $this->config->item('base_url') . 'attachments/csv/sample_product.csv'; ?>" download><i class="fa fa-download"></i>&nbsp; Sample File</a>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Skip Rows</label>
                                    <input type="text" name="skip_rows" id="skip_rows" class="form-control" value="0">
                                    <span class="error_msg"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" name="cancel" id="cancel" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="submit" id="import" class="btn btn-success">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- <div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>    
            <form class="needs-validation" novalidate="" method="post" enctype="multipart/form-data" action="<?php echo base_url('master/product/add_product'); ?>">        
            <div class="modal-body">
                <div class="row g-3">               
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom01">Product Name</label>
                        <input class="form-control" id="validationCustom01" type="text" name="product_name" value="" required="">
                        <div class="valid-feedback">Looks good!</div>
                    </div>
            
                    <div class="col-md-6">
                            <label class="form-label" for="validationCustom02">Product Image</label>
                            <input class="form-control" id="validationCustom02" name="product_image[]" multiple="multiple" type="file" required="">
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustomUsername">Unit</label>                            
                            <input class="form-control" id="validationCustomUsername" type="text" name="unit" placeholder="" aria-describedby="inputGroupPrepend" required="">
                            <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Price</label>
                        <input class="form-control" id="validationCustom03" type="text" name="price" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="validationCustom03">Description</label>
                        <input class="form-control" id="validationCustom03" type="text" name="description" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>                    
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" data-bs-original-title="" title="">Close</button>
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>  
<div class="modal fade" id="kt_modal_edit_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div> 
            <form class="needs-validation" novalidate="" method="post" action = "<?php echo base_url('master/product/update_product'); ?>" enctype="multipart/form-data" >
                <div class="modal-body scroll-y">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom01">Product Name</label>
                            <input type="hidden" name="product_id" class="product_id">
                            <input class="form-control product_name" id="validationCustom01" type="text" name="product_name" required="">
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom02">Product Image</label>
                            <input type="hidden" name="old_image" id="old_image">
                            <input class="form-control" id="validationCustom02" name="product_image[]" type="file" multiple="multiple" required="">
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustomUsername">Unit</label>                            
                            <input class="form-control unit" id="validationCustomUsername" type="text" name="unit" placeholder="" aria-describedby="inputGroupPrepend" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>                        
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom03">Price</label>
                            <input class="form-control price" id="validationCustom03" type="text" name="price" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="validationCustom03">Description</label>
                            <input class="form-control description" id="validationCustom03" type="text" name="description" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" data-bs-original-title="" title="">Close</button>
                <button class="btn btn-primary update" type="submit">Submit</button>
            </div>
            </form>
     </div>
</div>  -->

<script>
    $(document).ready(function(){

        $('.add_bluk_import').click(function () {
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#myModal').modal('show');
        });

        var table = $('#basic').DataTable({
			"processing":true,
			"serverSide":true,
			"order":[], 
			"ajax": {
				url : '<?php echo base_url("master/product/get_products"); ?>',
				type: "POST"  
			},
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'S.No');
                $(row).find('td:eq(1)').attr('data-th', 'Image');
                $(row).find('td:eq(2)').attr('data-th', 'Product Name');
                $(row).find('td:eq(3)').attr('data-th', 'Category Name');
                $(row).find('td:eq(4)').attr('data-th', 'HSN No Name');
                $(row).find('td:eq(5)').attr('data-th', 'Status');
                $(row).find('td:eq(6)').attr('data-th', 'Action');
            },
			"columnDefs":[  
				{  
					"targets":[0,1,6],  
					"orderable":false,
				},
                {  
					"targets":[0,1,4,5,6],  
					"className":"text-center"
				},
                // {  
				// 	"targets":[5],  
				// 	"className":"text-right"
				// }
                
			], 
		});

        // $("#close_modal").on('click',function () {
        //     $('#kt_modal_add_user').modal('hide');
        // });
        // $("#close_edit_modal").on('click',function () {
        //     $('#kt_modal_edit_user').modal('hide');
        // });
    });
    // $(document)	.on('click','.addAttr',function(){
	// 	var id = $(this).attr('data-id');
	// 	var baseurl = "<?php echo base_url(); ?>";
	// 	$.ajax({
	// 		type : "POST",
	// 		url  : "<?php echo base_url('master/product/edit_product');?>",
	// 		dataType : "JSON",
	// 		data : {id:id},
	// 		success: function(data){
	// 			$(".product_name").val(data.vProductName);
	// 			$(".unit").val(data.iUnit);
	// 			$('.description').val(data.vDescription);
	// 			$('.price').val(data.iPrice);
    //             $('.old_image').val(data.vImage);
    //             $('.product_id').val(data.iProductId);
	// 		}
	// 	});
	// 	return false;
	// });
    $(document).on('click','.removeAttr',function(event){
      event.preventDefault();
        var id = $(this).attr('data-id');
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
                    url: "<?php echo base_url() . '/master/product/delete_product';?>",
                    type: 'POST',
                    data:{id:id},
                    success: function(data) {
                            Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                        );      
                        $('#basic').DataTable().ajax.reload()
                    }
                });
            }
        });
    });
    // $(document).on('click','.update',function(){
    //     var id = $('.product_id').val();
    //     var product_name = $('.product_name').val();
    //     var description = $('.description').val();
    //     var price = $('.price').val();
    //     $.ajax({
    //         type: "POST",
    //         url: "<?php echo base_url('master/product/update_product'); ?>",
    //         data: { id:id,product_name:product_name,description:description,price:price }, // pass it as POST parameter
    //         success: function(data){
    //             alert(1);
    //             console.log(data);
    //             if(data.statusCode == 200){
    //                 table.ajax.reload();
    //             }
    //         }
    //     });
    // });
</script>