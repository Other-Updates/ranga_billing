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
                <h3>User</h3>
                <button type="button" class="btn btn-sm btn-primary pull-right mnone" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add User</button>
            </div>
            <div class="col-6">
            <button type="button" class="btn btn-sm btn-primary pull-right wnone pull-right" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add User</button>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')  ?>"><i class="fa fa-home"></i></a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Users</li>
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
                            <th>Name</th>
                            <th>User Role</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Email</th>
                            <!-- <th>Commission</th> -->
                            <th>Login Status</th>
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
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" id="add_user" novalidate="" method="post" enctype="multipart/form-data" >
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Name</label>
                        <input class="form-control add_name" id="validationCustom03" type="text" name="name" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tamil-lang" for="validationCustom03">பெயர்</label>
                        <input class="form-control add_name_tamil tamil-lang" id="validationCustom03" type="text" name="name_tamil" placeholder="" >
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">User Role</label>
                        <select class="form-select add_user_role" name ="type" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($role as $roles){ ?>
                            <option value="<?php echo $roles['iUserRoleId'] ?>"><?php echo $roles['vUserRole'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid field.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Region</label>
                        <select class="form-select add_region region_drop" name ="region" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($region as $regions){ ?>
                            <option value="<?php echo $regions['iRegionId'] ?>"><?php echo $regions['vRegionName'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid field.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Head Office</label>
                        <select class="form-select add_head_office headoffice_drop" name ="headoffice" id="validationCustom04" >
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($headoffice as $headoffices){ ?>
                            <!-- <option value="<?php echo $headoffices['iHeadOfficeId'] ?>"><?php echo $headoffices['vHeadOfficeName'] ?></option> -->
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Branch</label>
                        <select class="form-select add_branch branch_drop" name ="branch" id="validationCustom04" >
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($branch as $branches){ ?>
                            <!-- <option value="<?php echo $branches['iBranchId'] ?>"><?php echo $branches['vBranchName'] ?></option> -->
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustomUsername">Phone</label>                            
                        <input class="form-control add_phone" id="validationCustomUsername" name="phone" type="text" name="unit" placeholder="" aria-describedby="inputGroupPrepend" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Username</label>
                        <input class="form-control add_username" id="validationCustom03" type="text" name="username" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Password</label>
                        <input class="form-control add_password" id="validationCustom03" type="text" name="password" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Address</label>
                        <input class="form-control add_address" id="validationCustom03" type="text" name="address" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tamil-lang" for="validationCustom03">முகவரி</label>
                        <input class="form-control add_address_tamil tamil-lang" id="validationCustom03" type="text" name="address_tamil" placeholder="" >
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Email</label>
                        <input class="form-control add_email" id="validationCustom03" type="text" name="email" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <!-- <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Commission</label>
                        <input class="form-control" id="validationCustom03" type="text" name="commission" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div> -->
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
                <h5 class="modal-title">Edit User</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" novalidate="" method="post" enctype="multipart/form-data" >
            <input type="hidden" name="user_id" class="user_id">
            <div class="modal-body scroll-y">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Name</label>
                        <input class="form-control edit_name" id="validationCustom03" type="text" name="name" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tamil-lang" for="validationCustom03">பெயர்</label>
                        <input class="form-control edit_name_tamil tamil-lang" id="validationCustom03" type="text" name="name" placeholder="" >
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Type</label>
                        <select class="form-select edit_type" name ="type" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($role as $roles){ ?>
                            <option value="<?php echo $roles['iUserRoleId'] ?>"><?php echo $roles['vUserRole'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Region</label>
                        <select class="form-select edit_region region_drop" name ="region" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($region as $regions){ ?>
                            <option value="<?php echo $regions['iRegionId'] ?>"><?php echo $regions['vRegionName'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Head Office</label>
                        <select class="form-select edit_headoffice headoffice_drop" name ="headoffice" id="validationCustom04" >
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($headoffice as $headoffices){ ?>
                            <option value="<?php echo $headoffices['iHeadOfficeId'] ?>"><?php echo $headoffices['vHeadOfficeName'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Branch</label>
                        <select class="form-select edit_branch branch_drop" name ="branch" id="validationCustom04">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($branch as $branches){ ?>
                            <option value="<?php echo $branches['iBranchId'] ?>"><?php echo $branches['vBranchName'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustomUsername">Phone</label>                        
                        <input class="form-control edit_phone" id="validationCustomUsername" name="phone" type="text" name="unit" placeholder="" aria-describedby="inputGroupPrepend" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Username</label>
                        <input class="form-control edit_username" id="validationCustom03" type="text" name="username" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Password</label>
                        <input class="form-control edit_password" id="validationCustom03" type="text" name="password" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Address</label>
                        <input class="form-control edit_address" id="validationCustom03" type="text" name="address" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tamil-lang" for="validationCustom03">முகவரி</label>
                        <input class="form-control edit_address_tamil tamil-lang" id="validationCustom03" type="text" name="address_tamil" placeholder="" >
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Email</label>
                        <input class="form-control edit_email" id="validationCustom03" type="text" name="email" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <!-- <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Commission</label>
                        <input class="form-control edit_commission" id="validationCustom03" type="text" name="commission" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div> -->
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

        $('#butsave').on('click', function() {
            var name = $('.add_name').val();
            var add_name_tamil = $('.add_name_tamil').val();
            var type = $('.add_user_role').val();
            var region = $('.add_region').val();
            var headoffice = $('.add_head_office').val();
            var branch = $('.add_branch').val();
            var phone = $('.add_phone').val();
            var username = $('.add_username').val();
            var password = $('.add_password').val();
            var address = $('.add_address').val();
            var address_tamil = $('.add_address_tamil').val();
            var email = $('.add_email').val();
            if(name!="" && type!="" && phone!="" && username!="" && password!="" && email!=""){
                $("#butsave").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/user/add_user'); ?>",
                    type: "POST",
                    data: {
                        name: name,
                        add_name_tamil:add_name_tamil,
                        type:type,
                        region:region,
                        headoffice:headoffice,
                        branch:branch,
                        phone:phone,
                        username:username,
                        password:password,
                        address:address,
                        address_tamil:address_tamil,
                        email:email,
                    },
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            $("#butsave").removeAttr("disabled");
                            $('#add_user').find('input:text').val('');

                            $('#kt_modal_add_user').modal('hide');
                            $('#basic').DataTable().ajax.reload();					
                        }
                    }
                });
            }
        });

        $('#btn_update').on('click', function() {
            var user_id = $('.user_id').val();
            var name = $('.edit_name').val();
            var name_tamil = $('.edit_name_tamil').val();
            var type = $('.edit_type').val();
            var address = $('.edit_address').val();
            var address_tamil = $('.edit_address_tamil').val();
            var phone = $('.edit_phone').val();
            var email = $('.edit_email').val();
            var username = $('.edit_username').val();
            var headoffice = $('.edit_headoffice').val();
            var branch = $('.edit_branch').val();
            var password = $('.edit_password').val();
            var region = $('.edit_region').val();
            var status = $('.radio:checked').val();
            if(user_id!="" && name!="" && username!="" ){
                $("#btn_update").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/user/update_user'); ?>",
                    type: "POST",
                    data: {
                        user_id: user_id,
                        name:name,
                        name_tamil: name_tamil,
                        type: type,
                        address:address,
                        address_tamil:address_tamil,
                        phone: phone,
                        email:email,
                        username:username,
                        headoffice:headoffice,
                        branch:branch,
                        password:password,
                        region:region,
                        status: status,
                    },
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            $('#btn_update').removeAttr('disabled');

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
				url : "<?php echo base_url('master/user/user_list'); ?>",
				type: "POST"  
			},
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'S.No');
                $(row).find('td:eq(1)').attr('data-th', 'Name');
                $(row).find('td:eq(2)').attr('data-th', 'User Role');
                $(row).find('td:eq(3)').attr('data-th', 'Phone');
                $(row).find('td:eq(4)').attr('data-th', 'Address');
                $(row).find('td:eq(5)').attr('data-th', 'Email');
                $(row).find('td:eq(6)').attr('data-th', 'Login Status');
                $(row).find('td:eq(7)').attr('data-th', 'Status');
                $(row).find('td:eq(8)').attr('data-th', 'Action');
            },
			"columnDefs":[  
				{  
					"targets":[0,6,8],  
					"orderable":false,
				},
                {  
					"targets":[0,3,6,7,8],  
					"className":"text-center",
				}  
			], 
		});

        $("#close_modal").on('click',function () {
            $('#kt_modal_add_user').modal('hide');
        });
        $("#close_edit_modal").on('click',function () {
            $('#kt_modal_edit_user').modal('hide');
        });
    });
    $(document)	.on('click','.addAttr',function(){
		var id = $(this).attr('data-id');
		var baseurl = "<?php echo base_url(); ?>";
		$.ajax({
			type : "POST",
			url  : "<?php echo base_url('master/user/edit_user');?>",
			dataType : "JSON",
			data : {id:id},
			success: function(data){
                console.log(data);
				$(".user_id").val(data.iUserId);
				$(".edit_name").val(data.vName);
				$(".edit_name_tamil").val(data.vName_Tamil);
				$(".edit_type").val(data.iUserRoleId);
				$('.edit_address').val(data.vAddress);
				$('.edit_address_tamil').val(data.vAddress_Tamil);
				$('.edit_phone').val(data.iPhoneNumber);
                $('.edit_email').val(data.vEmail);
                $('.edit_username').val(data.vUserName);
                $('.edit_headoffice').val(data.iHeadOfficeId);
                $('.edit_branch').val(data.iBranchId);
                $('.edit_password').val(atob(data.vPassword));
                $('.edit_region').val(data.iRegionId);
                if(data.tStatus == 'Active'){
                    $(".radio_active").prop("checked", true);
                }
                if(data.tStatus == 'Active'){
                    $(".radio_inactive").prop("checked", true);
                }
			}
		});
		return false;
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
                    url: "<?php echo base_url() . '/master/user/delete_user';?>",
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
    $(document).on('change','.region_drop',function(){
        var region = $(this).val();
            $.ajax({
                url: "<?php echo base_url() . '/master/user/ho_based_region';?>",
                type: 'POST',
                data:{region:region},
                success: function(data) {
                    data = JSON.parse(data);
                    var html = '';
                    html += '<option value="">Choose...</option>';
                    $.each(data, function(key,val) {
                        html += '<option value='+val['iHeadOfficeId']+'>'+val['vHeadOfficeName']+'</option>';
                    });
                    $('.headoffice_drop').html(html);
                }
            });
    })

    $(document).on('change','.headoffice_drop',function(){
        var head_office = $(this).val();
            $.ajax({
                url: "<?php echo base_url() . '/master/user/branch_based_ho';?>",
                type: 'POST',
                data:{head_office:head_office},
                success: function(data) {
                    data = JSON.parse(data);
                    var html = '';
                    html += '<option value="">Choose...</option>';
                    $.each(data, function(key,val) {
                        html += '<option value='+val['iBranchId']+'>'+val['vBranchName']+'</option>';
                    });
                    $('.branch_drop').html(html);
                }
            });
    })

    </script>