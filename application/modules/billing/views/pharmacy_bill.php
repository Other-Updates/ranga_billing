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
            <div class="col-6">
                <h3>Pharmacy Bill</h3>
                <a href="<?php echo base_url('billing/pharmacy_billing/add_pharmacy_fee'); ?>" class="mnone"><button type="button" class="btn btn-sm btn-primary">Add Pharmacy Bill</button></a>
            </div>
            <div class="col-6">
            <a href="<?php echo base_url('billing/pharmacy_billing/add_pharmacy_fee'); ?>" class="wnone pull-right"><button type="button" class="btn btn-sm btn-primary">Add Pharmacy Bill</button></a>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Pharmacy Bill</li>
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
                    <table class="list-table display table basictable" id="basic">
                        <thead>
                            <tr>
                                <th width="5%" data-th="Sno">S.No</th>
                                <!-- <th>Product</th>
                                <th>Salesman</th> -->
                                <th width="15%">PB Number</th>
                                <th width="20%">Customer</th> 
                                <th width="15%">Quantity</th>
                                <th width="15%" class="p-r-30">Sales Amount</th>
                                <th width="10%" class="d-none">Delivery Date</th>
                                <th width="10%" class="d-none">Delivery Status</th>
                                <th width="15%">Created Date</th>
                                <th width="15%">Action</th>
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
                <h5 class="modal-title">Add Pharmacy Bill</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" id="supplier_form" novalidate="" method="post" enctype="multipart/form-data" >
                <input type="hidden" id="unique-branch-err" value="0">
            <div class="modal-body scroll-y">                
                <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('LoggedId'); ?>">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label tamil-lang" for="validationCustom03">வழங்குபவர் பெயர்</label>
                        <input class="form-control add_supplier_tamil tamil-lang" id="validationCustom03" type="text" name="supplier_name" placeholder="" >
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Supplier Name</label>
                        <input class="form-control add_supplier" id="validationCustom03" type="text" name="supplier_name" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                        <span class="ajax_response_result"></span>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">GSTIN Number</label>
                        <input class="form-control add_gstno" id="validationCustom03" type="text" name="gst_no" placeholder="">
                        <div class="invalid-feedback">Field is required.</div>
                        <span class="ajax_response_result"></span>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Phone Number</label>
                        <input class="form-control add_phone" id="validationCustom03" type="text" name="phone" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                        <span class="ajax_response_result"></span>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Email</label>
                        <input class="form-control add_mail" id="validationCustom03" type="text" name="email" placeholder="">
                        <div class="invalid-feedback">Field is required.</div>
                        <span class="ajax_response_result"></span>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Address</label>
                        <textarea class="form-control add_address" id="validationCustom03" type="text" name="address" placeholder="" required=""></textarea>
                        <div class="invalid-feedback">Field is required.</div>
                        <span class="ajax_response_result"></span>
                    </div>  
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" data-bs-original-title="" title="">Close</button>
                <button class="btn btn-primary" id="butsave" type="submit">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="kt_modal_edit_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Staff</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" id="supplier_edit_form" novalidate="" method="post" enctype="multipart/form-data" >
                <input type="hidden" class="supplier_id" name="supplier_id">
                <div class="modal-body scroll-y">                
                <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('LoggedId'); ?>">
                <div class="row g-3">
                    <div class="col-md-12">
                        <input type="hidden" class="distributorid" name="distributorid" value="">
                        <label class="form-label tamil-lang" for="validationCustom03">வழங்குபவர் பெயர்</label>
                        <input class="form-control supplier_name_tamil tamil-lang" id="validationCustom03" type="text" name="supplier_name_tamil" placeholder="" >
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" class="distributorid" name="distributorid" value="">
                        <label class="form-label" for="validationCustom03">Supplier Name</label>
                        <input class="form-control supplier_name" id="validationCustom03" type="text" name="supplier_name" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">GSTIN Number</label>
                        <input class="form-control edit_gstno" id="validationCustom03" type="text" name="gst_no" placeholder="">
                        <div class="invalid-feedback">Field is required.</div>
                        <span class="ajax_response_result"></span>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Phone Number</label>
                        <input class="form-control edit_phone" id="validationCustom03" type="text" name="phone" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                        <span class="ajax_response_result"></span>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Email</label>
                        <input class="form-control edit_mail" id="validationCustom03" type="text" name="email" placeholder="" >
                        <div class="invalid-feedback">Field is required.</div>
                        <span class="ajax_response_result"></span>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Address</label>
                        <textarea class="form-control edit_address" id="validationCustom03" type="text" name="address" placeholder="" required=""></textarea>
                        <div class="invalid-feedback">Field is required.</div>
                        <span class="ajax_response_result"></span>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Status</label>
                        <label class="d-block" for="edo-ani">
                        <input class="radio_animated radio radio_active" id="edo-ani" type="radio" value='Active' name="status" checked="" data-original-title="" title="">Active
                        </label>
                        <label class="d-block" for="edo-ani1">
                        <input class="radio_animated radio radio_inactive" id="edo-ani1" type="radio" value='Inactive' name="status" data-original-title="" title="">Inactive
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" data-bs-original-title="" title="">Close</button>
                <button class="btn btn-primary" id="btn_update" type="submit">Submit</button>
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
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ],
			"order":[], 
			"ajax": {
				url : "<?php echo base_url('billing/pharmacy_billing/get_pharmacy_bills'); ?>",
				type: "POST"  
			},
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'S.No');
                $(row).find('td:eq(1)').attr('data-th', 'SO Number');
                $(row).find('td:eq(2)').attr('data-th', 'Customer');
                $(row).find('td:eq(3)').attr('data-th', 'Quantity');
                $(row).find('td:eq(4)').attr('data-th', 'Sales Amount');
                $(row).find('td:eq(5)').attr('data-th', 'Delivery Date');
                $(row).find('td:eq(6)').attr('data-th', 'Delivery Status');
                $(row).find('td:eq(5)').attr('class', 'd-none');
                $(row).find('td:eq(6)').attr('class', 'd-none');
                $(row).find('td:eq(7)').attr('data-th', 'Created Date');
                $(row).find('td:eq(8)').attr('data-th', 'Action');
            },
			 "columnDefs":[  
				{  
					"targets":[0,8],  
					"orderable":false,
				}, 
                {  
					"targets":[0,3,5,6,7,8],  
					"className":"text-center"
				},
                 {  
			 		"targets":[4],  
			 		"className":"text-right"
			 	}, 
                 {  
			 		"targets":[2],  
			 		"className":"text-left"
			 	}, 
			 ], 
            "dom": 'lBfrtip',
                "buttons": [
                    'excel', 'csv', 'pdf', 'copy',
                ],
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
                    url: "<?php echo base_url() . 'order/delete_sale_order';?>",
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