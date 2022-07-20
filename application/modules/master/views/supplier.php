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
                <h3>Supplier</h3>
                <button type="button" class="btn btn-sm btn-primary mnone" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add Supplier</button>
            </div>
            <div class="col-6">
                <button type="button" class="btn btn-sm btn-primary wnone pull-right" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add Supplier</button>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')  ?>"><i class="fa fa-home"></i></a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Supplier</li>
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
                            <th>S.No</th>
                            <th>Supplier Name</th>
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
<div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Supllier</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
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
                <h5 class="modal-title">Edit Branch</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
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
        $('#butsave').on('click', function() {
            var supplier_name = $('.add_supplier').val();
            var supplier_name_tamil = $('.add_supplier_tamil').val();
            var phone = $('.add_phone').val();
            var address = $('.add_address').val();
            var email = $('.add_mail').val();
            var gstno = $('.add_gstno').val();
            // var status = $('.radio:checked').val();
            if(supplier_name!="" && supplier_name_tamil!="" && supplier_name!=null && supplier_name_tamil!=null && phone!="" && phone!=null && address!=""){
                $("#butsave").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/supplier/add_supplier'); ?>",
                    type: "POST",
                    data: {
                        supplier_name: supplier_name,
                        supplier_name_tamil: supplier_name_tamil,
                        phone:phone,
                        email:email,
                        address:address,
                        gstno:gstno
                    },
                    cache: false,
                    success: function(dataResult){
                        // alert(dataResult);
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            $("#butsave").removeAttr("disabled");
                            $('#supplier_form').find('input:text').val('');

                            $('#kt_modal_add_user').modal('hide');
                            $('#basic').DataTable().ajax.reload();					
                        }
                    }
                });
            }
        });

        $('#btn_update').on('click', function() {
            var supplier_name = $('.supplier_name').val();
            var supplier_name_tamil = $('.supplier_name_tamil').val();
            var phone = $('.edit_phone').val();
            var email = $('.edit_mail').val();
            var address = $('.edit_address').val();
            var status = $('.radio:checked').val();
            var supplier_id = $('.supplier_id').val();
            var gstno = $('.edit_gstno').val();
            if(supplier_name!="" && supplier_name_tamil!="" && status!="" && supplier_id!="" && phone!="" && phone!=null && address!=""){
                $("#btn_update").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/supplier/update_supplier'); ?>",
                    type: "POST",
                    data: {
                        supplier_name: supplier_name,
                        supplier_name_tamil: supplier_name_tamil,
                        status:status,
                        supplier_id: supplier_id,
                        phone:phone,
                        email:email,
                        address:address,
                        gstno:gstno
                    },
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            $('#btn_update').removeAttr('disabled');

                            $('#supplier_edit_form').find('input:text').val('');
                            $('#kt_modal_edit_user').modal('hide');
                            $('#basic').DataTable().ajax.reload();					
                        }
                    }
                });
            }
        });

        var table = $('#basic').DataTable({
			"processing":true,
			"serverSide":true,
			"order":[], 
			"ajax": {
				url : "<?php echo base_url('master/supplier/get_suppliers'); ?>",
				type: "POST"  
			},
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'S.No');
                $(row).find('td:eq(1)').attr('data-th', 'Supplier Name');
                $(row).find('td:eq(2)').attr('data-th', 'Status');
                $(row).find('td:eq(3)').attr('data-th', 'Action');
            },
			"columnDefs":[  
				{  
					"targets":[0,3],  
					"orderable":false,
				},
                {  
					"targets":[0,2,3],  
					"className":"text-center"
				},  
			], 
		});

        $("#close_modal").on('click',function () {
            $('#kt_modal_add_user').modal('hide');
        });
        $("#close_edit_modal").on('click',function () {
            $('#kt_modal_edit_user').modal('hide');
        });
        $(document)	.on('click','.addAttr',function(){
            var id = $(this).attr('data-id');
            var baseurl = "<?php echo base_url(); ?>";
            $.ajax({
                type : "POST",
                url  : "<?php echo base_url('master/supplier/edit_supplier');?>",
                dataType : "JSON",
                data : {id:id},
                success: function(data){
                    console.log(data);
                    $('.supplier_name').val(data.vSupplierName);
                    $('.supplier_name_tamil').val(data.vSupplierName_Tamil);
                    $('.edit_phone').val(data.vPhoneNumber);
                    $('.edit_mail').val(data.vEmail);
                    $('.supplier_id').val(data.iSupplierId);
                    $('.edit_address').val(data.vAddress);
                    $('.edit_gstno').val(data.vGSTINNo);
                    if(data.eStatus == "Active"){
                        $(".radio_active").prop("checked", true);
                    }
                    if(data.eStatus == "Inactive"){
                        $(".radio_inactive").prop("checked", true);
                    }
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
                    url: "<?php echo base_url() . 'master/supplier/delete_supplier';?>",
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
    
</script>