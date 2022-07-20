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
                <h3>Category</h3>
                <button type="button" class="btn btn-sm btn-primary mnone" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add Category</button>
            </div>
            <div class="col-6">
                <button type="button" class="btn btn-sm btn-primary wnone pull-right" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add Category</button>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')  ?>"><i class="fa fa-home"></i></a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Category</li>
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
                            <th>Category Name</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th style="text-align:center">Action</th>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Category</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" id="add_category_form" novalidate="" method="post" enctype="multipart/form-data" >
            <div class="modal-body scroll-y">
                <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('LoggedId'); ?>">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label" for="validationCustom03">Category Name</label>
                        <input class="form-control add_category_name" id="validationCustom03" type="text" name="category_name" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label tamil-lang" for="validationCustom03">வகை பெயர்</label>
                        <input class="form-control add_category_name_tamil tamil-lang" id="validationCustom03" type="text" name="category_name_tamil" placeholder="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="validationCustom03">CGST</label>
                        <input class="form-control add_cgst" id="validationCustom03" type="text" name="cgst" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="validationCustom03">SGST</label>
                        <input class="form-control add_sgst" id="validationCustom03" type="text" name="sgst" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="validationCustom03">IGST</label>
                        <input class="form-control add_igst" id="validationCustom03" type="text" name="igst" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="validationCustom03">Category Image</label>
                        <input class="form-control add_category_image" id="validationCustom03" type="file" name="category_image" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" data-bs-original-title="" title="">Close</button>
                <input class="btn btn-primary" id="butsave" type="submit">
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="kt_modal_edit_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" novalidate="" id="edit_category_form" method="post" enctype="multipart/form-data">
                <input type="hidden" class="region_id" name="region_id">  
                <div class="modal-body scroll-y">                
                    <input type="hidden" name="category_id" class="category_id">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <input type="hidden" class="distributorid" name="distributorid" value="">
                            <label class="form-label" for="validationCustom03">Category Name</label>
                            <input class="form-control category_name" id="validationCustom03" type="text" name="category_name" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" class="distributorid" name="distributorid" value="">
                            <label class="form-label tamil-lang" for="validationCustom03">வகை பெயர்</label>
                            <input class="form-control category_name_tamil tamil-lang" id="validationCustom03" type="text" name="category_name_tamil" placeholder="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="validationCustom03">CGST</label>
                            <input class="form-control cgst" id="validationCustom03" type="text" name="cgst" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="validationCustom03">SGST</label>
                            <input class="form-control sgst" id="validationCustom03" type="text" name="sgst" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="validationCustom03">IGST</label>
                            <input class="form-control igst" id="validationCustom03" type="text" name="igst" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                            <div class="col-md-12">
                                <label class="form-label" for="validationCustom03">Category Image</label>
                                <input class="form-control category_image" id="validationCustom03" type="file" name="category_image" placeholder="" required="">
                                <div class="invalid-feedback">Field is required.</div>
                            </div>
                        
                        <div class="col-md-12">
                            <label class="form-label" for="validationCustom03">Status</label>
                            <label class="d-block" for="edo-ani">
                            <input class="radio_animated radio_active" id="edo-ani" type="radio" value='Active' name="status" checked="" data-original-title="" title="">Active
                            </label>
                            <label class="d-block" for="edo-ani1">
                            <input class="radio_animated radio_inactive" id="edo-ani1" type="radio" value='Inactive' name="status" data-original-title="" title="">Inactive
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" data-bs-original-title="" title="">Close</button>
                    <input class="btn btn-primary" type="submit">
                </div>
           </form>
        </div>
    </div>
</div> 
<script>
    $(document).ready(function(){
        $('#add_category_form').on('submit', function(e) {
            e.preventDefault();
            var category_name = $('.add_category_name').val();
            var category_name_tamil = $('.category_name_tamil').val();
            var category_image = $('.category_image').val();
            var cgst = $('.add_cgst').val();
            var sgst = $('.add_sgst').val();
            var igst = $('.add_igst').val();
            // if(category_name == "" || category_name_tamil == "" || category_image == "" || cgst == "" || igst == "" || sgst ==""){
            //     return false;
            // }
            var form = new FormData(this);
            console.log(form);
            // var category_name = $('.add_category_name').val();
            // var category_image = $('.add_category_image').files[0];
            // console.log(category_image);
            if(form!=""){
                $("#butsave").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/category/add_category'); ?>",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    data:form,
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            $("#butsave").removeAttr("disabled");
                            $('#branch_form').find('input:text').val('');

                            $('#kt_modal_add_user').modal('hide');
                            $('#basic').DataTable().ajax.reload();					
                        }
                    }
                });
            }
        });

        $('#edit_category_form').on('submit', function() {
            var _this = $(this);
            var form = new FormData(this);
            if(form!=""){
                $("#btn_update").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/category/update_category'); ?>",
                    type: "POST",
                    data: form,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            _this.removeAttr('disabled');

                            $('#branch_form').find('input:text').val('');
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
				url : "<?php echo base_url('master/category/get_categories'); ?>",
				type: "POST"  
			},
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'S.No');
                $(row).find('td:eq(1)').attr('data-th', 'Category Name');
                $(row).find('td:eq(2)').attr('data-th', 'Image');
                $(row).find('td:eq(3)').attr('data-th', 'Status');
                $(row).find('td:eq(4)').attr('data-th', 'Action');
            },
			"columnDefs":[  
				{  
					"targets":[2,4],  
					"orderable":false,
				},
                {  
					"targets":[0,2,3,4],  
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
        $(document).on('click','.addAttr',function(){
            var id = $(this).attr('data-id');
            var baseurl = "<?php echo base_url(); ?>";
            $.ajax({
                type : "POST",
                url  : "<?php echo base_url('master/category/edit_category');?>",
                dataType : "JSON",
                data : {id:id},
                success: function(data){
                    console.log(data);
                    $('.category_name').val(data.vCategoryName);
                    $('.category_name_tamil').val(data.vCategoryName_Tamil);
                    $('.igst').val(data.IGST);
                    $('.cgst').val(data.CGST);
                    $('.sgst').val(data.SGST);
                    $('.category_id').val(data.iCategoryId);
                    if(data.eStatus == 'Active'){
                        $(".radio_active").prop("checked", true);
                    }
                    if(data.eStatus == 'Inactive'){
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
                    url: "<?php echo base_url() . 'master/category/delete_category'; ?>",
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

    //get branch name by head office
    $(".headoffice").on('change', function(){
        var headoffice = $(this).val();  
        $('.branch').empty();
        $('.branch').val('');
        $( ".branch" ).removeClass( "disabled");
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>master/category/get_branch",
            data:{headoffice:headoffice},
            success: function(data){
                data = JSON.parse(data);
                var html = '';
                html += '<option value="">Choose...</option>';
                $.each(data, function(key,val) {
                    html += '<option value='+val['iHeadOfficeId']+'_'+val['iBranchId']+'>'+val['vBranchName']+'</option>';
                });
                    $('.branch').html(html);
            }
        });
    })
    
</script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/select2.css">
<script src="<?php echo $theme_path ?>/assets/js/select2/select2.full.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/select2/select2-custom.js"></script>