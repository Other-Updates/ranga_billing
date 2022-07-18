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
                <h3>Branch</h3>
                <button type="button" class="btn btn-sm btn-primary mnone" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add Branch</button>
            </div>
            <div class="col-6">
                <button type="button" class="btn btn-sm btn-primary wnone pull-right" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add Branch</button>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Branch</li>
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
                        <table class="display list-table basictable" id="basic">
                        <thead>
                            <tr>
                            <th>S.No</th>
                            <th>Head office</th>
                            <th>Branch Name</th>
                            <th>Branch Manager</th>
                            <th>Aadhaar / Gst no</th>
                            <th>Mobile</th>
                            <th>Address</th>
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
                <h5 class="modal-title">Add Branch</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" id="branch_form" novalidate="" method="post" enctype="multipart/form-data" >
                <input type="hidden" id="unique-branch-err" value="0">
            <div class="modal-body scroll-y">                
                <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('LoggedId'); ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Head Office</label>
                        <select class="form-select add_headoffice" name ="headoffice" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($headoffice as $ho){ ?>
                            <option value="<?php echo $ho['iHeadOfficeId'] ?>"><?php echo $ho['vHeadOfficeName'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Branch Name</label>
                        <input class="form-control add_branch" id="validationCustom03" type="text" name="branch_name" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                        <span class="ajax_response_result"></span>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tamil-lang" for="validationCustom03">கிளை பெயர்</label>
                        <input class="form-control add_branch_tamil tamil-lang" id="validationCustom03" type="text" name="branch_name_tamil" placeholder="" >
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Manager Name</label>
                        <select class="form-select add_manager" name ="manager_name" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($manager as $managers){ ?>
                            <option value="<?php echo $managers['iUserId'] ?>"><?php echo $managers['vName'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Aadhaar / GSTN NO</label>
                        <input class="form-control add_aadhaar_name" id="validationCustom03" type="text" name="aadhaar_name" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Mobile number</label>
                        <input class="form-control add_mobile_number" id="validationCustom03" type="text" name="mobile_number" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Address</label>
                        <textarea class="form-control add_address" id="validationCustom03" type="text" name="address" placeholder="" required=""></textarea>
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">முகவரி</label>
                        <textarea class="form-control add_address_tamil" id="validationCustom03" type="text" name="address_tamil" placeholder="" required=""></textarea>
                        <div class="invalid-feedback">Field is required.</div>
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
            <form class="needs-validation" id="" novalidate="" method="post" enctype="multipart/form-data" >
                <input type="hidden" class="branch_id" name="branch_id">
                <div class="modal-body scroll-y">                
                <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('LoggedId'); ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Head Office</label>
                        <select class="form-select headoffice" name ="headoffice" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($headoffice as $ho){ ?>
                            <option value="<?php echo $ho['iHeadOfficeId'] ?>"><?php echo $ho['vHeadOfficeName'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" class="distributorid" name="distributorid" value="">
                        <label class="form-label" for="validationCustom03">Branch Name</label>
                        <input class="form-control branch_name" id="validationCustom03" type="text" name="branch_name" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" class="distributorid" name="distributorid" value="">
                        <label class="form-label tamil-lang" for="validationCustom03">கிளை பெயர்</label>
                        <input class="form-control branch_name_tamil tamil-lang" id="validationCustom03" type="text" name="branch_name_tamil" placeholder="" >
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Manager Name</label>
                        <select class="form-select manager_name" name ="manager_name" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($manager as $managers){ ?>
                            <option value="<?php echo $managers['iUserId'] ?>"><?php echo $managers['vName'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Aadhaar / GSTN NO</label>
                        <input class="form-control edit_aadhaar_name" id="validationCustom03" type="text" name="aadhaar_name" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Mobile number</label>
                        <input class="form-control edit_mobile_number" id="validationCustom03" type="text" name="mobile_number" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Address</label>
                        <textarea class="form-control edit_address" id="validationCustom03" type="text" name="address" placeholder="" required=""></textarea>
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">முகவரி</label>
                        <textarea class="form-control edit_address_tamil" id="validationCustom03" type="text" name="address_tamil" placeholder="" required=""></textarea>
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col">
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
        $('.add_branch').on('keyup',function(){
            var branch = $(this).val();
            duplicate_branch(branch);
        });
        $('#butsave').on('click', function() {
            var headoffice = $('.add_headoffice').val();
            var branch_name = $('.add_branch').val();
            var branch_name_tamil = $('.add_branch_tamil').val();
            var manager_name = $('.add_manager').val();
            var aadhaar = $('.add_aadhaar_name').val();
            var mobile = $('.add_mobile_number').val();
            var address = $('.add_address').val();
            var address_tamil = $('.add_address_tamil').val();
            // var status = $('.radio:checked').val();
            if(headoffice!="" && branch_name!="" && manager_name!="" && headoffice!=null && branch_name!=null && manager_name!=null){
                if($("#unique-branch-err").val() > 0) {
                    return false;
                }
                $("#butsave").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/branch/add_branch'); ?>",
                    type: "POST",
                    data: {
                        headoffice: headoffice,
                        branch_name: branch_name,
                        branch_name_tamil:branch_name_tamil,
                        manager_name: manager_name,
                        aadhaar:aadhaar,
                        mobile:mobile,
                        address:address,
                        address_tamil:address_tamil
                    },
                    cache: false,
                    success: function(dataResult){
                        // alert(dataResult);
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

        $('#btn_update').on('click', function() {
            var headoffice = $('.headoffice').val();
            var branch_name = $('.branch_name').val();
            var branch_name_tamil = $('.branch_name_tamil').val();
            var manager_name = $('.manager_name').val();
            var aadhaar = $('.edit_aadhaar_name').val();
            var mobile = $('.edit_mobile_number').val();
            var address = $('.edit_address').val();
            var address_tamil = $('.edit_address_tamil').val();
            var status = $('.radio:checked').val();
            var branch_id = $('.branch_id').val();
            if(headoffice!="" && branch_name!="" && manager_name!="" && branch_id!=""){
                $("#btn_update").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/branch/update_branch'); ?>",
                    type: "POST",
                    data: {
                        headoffice: headoffice,
                        branch_name: branch_name,
                        branch_name_tamil:branch_name_tamil,
                        manager_name: manager_name,
                        branch_id: branch_id,
                        status: status,
                        aadhaar:aadhaar,
                        mobile:mobile,
                        address:address,
                        address_tamil:address_tamil
                    },
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            $('#btn_update').removeAttr('disabled');

                            $('#branch_form').find('input:text').val('');
                            $('#kt_modal_edit_user').modal('hide');
                            $('#basic').DataTable().ajax.reload();					
                        }
                    }
                });
            }
        });

        function duplicate_branch(branch) {
            // branch.preventDefault();
            jQuery.ajax({
            type: "POST",
            url: "<?php echo site_url('master/branch/check_duplicate_branch') ?>",    
            data: {branch:branch},
            success: function(data) {
                // console.log(data);
                data = JSON.parse(data);
                if(data.status == 'success'){
                    $("#unique-branch-err").val(1);
                    $(".ajax_response_result").html('').html(data.message).css('color','red');
                    
                } else {
                    $("#unique-branch-err").val(0);
                    $(".ajax_response_result").html('');
                }
            }
            });

        }

        var table = $('#basic').DataTable({
			"processing":true,
			"serverSide":true,
			"order":[], 
			"ajax": {
				url : "<?php echo base_url('master/branch/get_branches'); ?>",
				type: "POST"  
			},
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'S.No');
                $(row).find('td:eq(1)').attr('data-th', 'Head Office');
                $(row).find('td:eq(2)').attr('data-th', 'Branch Name');
                $(row).find('td:eq(3)').attr('data-th', 'Branch Manager');
                $(row).find('td:eq(4)').attr('data-th', 'Aadhaar / Gst no');
                $(row).find('td:eq(5)').attr('data-th', 'Mobile');
                $(row).find('td:eq(6)').attr('data-th', 'Address');
                $(row).find('td:eq(7)').attr('data-th', 'Status');
                $(row).find('td:eq(8)').attr('data-th', 'Action');
            },
			"columnDefs":[  
				{  
					"targets":[0,8],  
					"orderable":false,
				},
                {  
					"targets":[0,4,7,8],  
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
                url  : "<?php echo base_url('master/branch/edit_branch');?>",
                dataType : "JSON",
                data : {id:id},
                success: function(data){
                    console.log(data);
                    $('.branch_name').val(data.vBranchName);
                    $('.branch_name_tamil').val(data.vBranchName_Tamil);
                    $('.headoffice').val(data.iHeadOfficeId);
                    $('.manager_name').val(data.iBranchManagerId);
                    $('.edit_aadhaar_name').val(data.vAdhaarGst);
                    $('.edit_mobile_number').val(data.vMobileNumber);
                    $('.edit_address').html(data.vAddress);
                    $('.edit_address_tamil').html(data.vAddress_Tamil);
                    $('.branch_id').val(data.iBranchId);
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
                    url: "<?php echo base_url() . 'master/branch/delete_branch';?>",
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