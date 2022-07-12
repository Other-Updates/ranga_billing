<div class="container-fluid">        
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Product Price</h3>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add Product Price</button>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Product Price</li>
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
                        <table class="display" id="basic">
                        <thead>
                            <tr>
                            <th>S.No</th>
                            <th>Product</th>
                            <th>Unit</th>
                            <th>Pack</th>
                            <th>Grade</th>
                            <th>Price</th>
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
<div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product Price</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" novalidate="" method="post" enctype="multipart/form-data" action="<?php echo base_url('master/product_price/add_product_price'); ?>">
                <div class="modal-body scroll-y">
                    <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('LoggedId'); ?>">
                    <div class="row g-3">               
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">Product</label>
                            <select class="form-select" name="product" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach ($product as $products){ ?>
                            <option value="<?php echo $products['iProductId'] ?>"><?php echo $products['vProductName'] ?></option>
                            <?php } ?>
                            </select>
                            <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">Unit</label>
                            <select class="form-select" name="unit" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach ($unit as $units){ ?>
                            <option value="<?php echo $units['iProductUnitId'] ?>"><?php echo $units['vProductUnitName'] ?></option>
                            <?php } ?>
                            </select>
                            <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">Pack Count</label>
                            <input class="form-control" id="validationCustom03" type="text" name="count" placeholder="" required="">
                            <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">Grade</label>
                            <select class="form-select" name="grade" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach ($grade as $grades){ ?>
                            <option value="<?php echo $grades['iGradeId'] ?>"><?php echo $grades['vGradeName'] ?></option>
                            <?php } ?>
                            </select>
                            <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom03">Price</label>
                            <input class="form-control" id="validationCustom03" type="text" name="price" placeholder="" required="">
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
                <h5 class="modal-title">Edit Product Price</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" novalidate="" method="post" enctype="multipart/form-data" action="<?php echo base_url('master/product_price/update_product_price'); ?>">
            <div class="modal-body scroll-y">
                    <input type="hidden" name="product_price_id" class="product_price_id">
                    <div class="row g-3">               
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">Product</label>
                            <select class="form-select product" name="product" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach ($product as $products){ ?>
                            <option value="<?php echo $products['iProductId'] ?>"><?php echo $products['vProductName'] ?></option>
                            <?php } ?>
                            </select>
                            <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">Unit</label>
                            <select class="form-select unit" name="unit" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach ($unit as $units){ ?>
                            <option value="<?php echo $units['iProductUnitId'] ?>"><?php echo $units['vProductUnitName'] ?></option>
                            <?php } ?>
                            </select>
                            <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">Pack Count</label>
                            <input class="form-control count" id="validationCustom03" type="text" name="count" placeholder="" required="">
                            <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">Grade</label>
                            <select class="form-select grade" name="grade" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach ($grade as $grades){ ?>
                            <option value="<?php echo $grades['iGradeId'] ?>"><?php echo $grades['vGradeName'] ?></option>
                            <?php } ?>
                            </select>
                            <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom03">Price</label>
                            <input class="form-control price" id="validationCustom03" type="text" name="price" placeholder="" required="">
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
<script>
    $(document).ready(function(){
        var table = $('#basic').DataTable({
			"processing":true,
			"serverSide":true,
			"order":[], 
			"ajax": {
				url : "<?php echo base_url('master/product_price/get_product_prices'); ?>",
				type: "POST"  
			},
			"columnDefs":[  
				{  
					"targets":[6],  
					"orderable":false,
				},
                {  
					"targets":[0,2,3,6],  
					"className":"text-center"
				},
                {  
					"targets":[5],  
					"className":"text-right"
				}  
			], 
		});

        $("#close_modal").on('click',function () {
            $('#kt_modal_add_user').modal('hide');
        });
        $("#close_edit_modal").on('click',function () {
            $('#kt_modal_edit_user').modal('hide');
        });
        $(document).on('click','.addAttr',function(){
            var id = $(this).attr('data-id');
            console.log(id);

            var baseurl = "<?php echo base_url(); ?>";
            $.ajax({
                type : "POST",
                url  : "<?php echo base_url('master/product_price/edit_product_price');?>",
                dataType : "JSON",
                data : {id:id},
                success: function(data){
                    console.log(data);
                    $('.product_price_id').val(data.iProductPriceListId);
                    $('.product').val(data.iProductId);
                    $('.unit').val(data.iProductUnitId);
                    $('.count').val(data.vPacketCount);
                    $('.grade').val(data.iGradeId);
                    $('.price').val(data.fProductPrice);
                }
            });
            return false;
        });
    });
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
                    url: "<?php echo base_url() . 'master/product_price/delete_product_price'; ?>",
                    type: 'POST',
                    data:{id:id},
                    success: function(data) {
                        console.log(id);
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
    
</script>