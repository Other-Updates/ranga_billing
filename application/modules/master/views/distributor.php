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
                <h3>Customers</h3>
                <button type="button" class="btn btn-sm btn-primary mnone" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add Customers</button>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Customers</li>
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
                            <th>Customer Name</th>
                            <th>Type</th>
                            <th>Grade</th>
                            <th>Company Name</th>
                            <th>PhoneNumber</th>
                            <!-- <th>Address</th> -->
                            <!-- <th>vEmail</th> -->
                            <!-- <th>Reference Distributor</th> -->
                            <!-- <th>Salesman Name</th> -->
                            <!-- <th>Commission</th> -->
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
                <h5 class="modal-title">Add Customer</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" id="distributor_form" novalidate="" method="post" enctype="multipart/form-data" >
            <div class="modal-body scroll-y">
                <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('LoggedId'); ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Customer Name</label>
                        <input class="form-control add_name" id="validationCustom03" type="text" name="name" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tamil-lang" for="validationCustom03">விநியோகஸ்தர் பெயர்</label>
                        <input class="form-control add_name_tamil tamil-lang" id="validationCustom03" type="text" name="name" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">GSTIN Number</label>
                        <input class="form-control add_gstin" id="validationCustom03" type="text" name="gstin" placeholder="" >
                        <div class="invalid-feedback">Field is required.</div>
                        </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">State</label>
                        <select class="form-select add_state" name ="type" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($state as $states){ ?>
                            <option value="<?php echo $states['iStateId'] ?>"><?php echo $states['vStateName'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>    
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Role</label>
                        <select class="form-select add_type" name ="type" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($roles as $role){ ?>
                            <option value="<?php echo $role['iUserRoleId'] ?>"><?php echo $role['vUserRole'] ?></option>
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
                        <label class="form-label" for="validationCustom03">Company Name</label>
                        <input class="form-control add_company_name" id="validationCustom03" type="text" name="company_name" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tamil-lang" for="validationCustom03">நிறுவனத்தின் பெயர்</label>
                        <input class="form-control add_company_name_tamil tamil-lang" id="validationCustom03" type="text" name="company_name_tamil" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Grade</label>
                        <select class="form-select add_grade" name ="grade" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($grade as $grades){ ?>
                            <option value="<?php echo $grades['iGradeId'] ?>"><?php echo $grades['vGradeName'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Branch</label>
                        <?php if($this->session->userdata('UserRole') == 2 || $this->session->userdata('UserRole') == 3){ ?>
                                <label class="form-control" for="validationCustom04" readonly><?php echo $branch[0]['vBranchName'];?></label>  
                                <input class="form-control add_branch" id="validationCustom01" type="hidden" name="branch" value="<?php echo $branch[0]['iBranchId'];?>">
                                <?php } else {?>
                        <select class="form-select add_branch" name ="branch" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($branch as $branches){ ?>
                                <option value="<?php echo $branches['iBranchId'] ?>"><?php echo $branches['vBranchName'] ?></option>
                            <?php } } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Address</label>
                        <input class="form-control add_address" id="validationCustom03" type="text" name="address" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tamil-lang" for="validationCustom03">முகவரி</label>
                        <input class="form-control add_address_tamil tamil-lang" id="validationCustom03" type="text" name="address_tamil" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Salesman</label>
                        <?php if($this->session->userdata('UserRole') == 3){ ?>
                                <label class="form-control" for="validationCustom04" readonly><?php echo $user[0]['vName'];?></label>  
                                <input class="form-control add_salesman_id" id="validationCustom01" type="hidden" name="salesman_id" value="<?php echo $user[0]['iUserId'];?>">
                                <?php } else {?>
                        <select class="form-select add_salesman_id" name ="salesman_id" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($user as $users){ ?>
                            <option value="<?php echo $users['iUserId'] ?>" <?php if($this->session->userdata('LoggedId')==$users['iUserId']){ echo 'selected';} ?>><?php echo $users['vName'] ?></option>
                            <?php }  }?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <!-- <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Reference Distributer</label>
                        <input class="form-control" id="validationCustom03" type="text" name="reference_distributor" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div> -->
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Email</label>
                        <input class="form-control add_email" id="validationCustom03" type="text" name="email" placeholder="" >
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <!-- <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Commission</label>
                        <input class="form-control" id="validationCustom03" type="text" name="commission" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div> -->
                    <div class="card">
                        <div class="card-body">
                            <div class="branch-box">
                                <div class="section-title">Category</div>
                                <?php foreach($categories as $ct){ ?>
                                    <div class="branch-list"><input type="checkbox" class="category_check" name="category_check[]" value="<?php echo $ct['iCategoryId'] ?>" id="">
                                    <label><?php echo $ct['vCategoryName'] ?></label></div>
                                <?php } ?>
                            </div>
                            <span class="category_err"></span>
                        </div>
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
                <h5 class="modal-title">Edit Customer</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" novalidate="" method="post" enctype="multipart/form-data" >
                <div class="modal-body scroll-y">                
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="hidden" class="customer_id" name="distributorid" value="">
                            <label class="form-label name" for="validationCustom03">Customer Name</label>
                            <input class="form-control edit_name" id="validationCustom03" type="text" name="name" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-6">
                            <input type="hidden" class="customer_id" name="distributorid" value="">
                            <label class="form-label name tamil-lang" for="validationCustom03">விநியோகஸ்தர் பெயர்</label>
                            <input class="form-control edit_name_tamil tamil-lang" id="validationCustom03" type="text" name="name_tamil" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom03">GSTIN Number</label>
                            <input class="form-control edit_gstin" id="validationCustom03" type="text" name="gstin" placeholder="" >
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">State</label>
                            <select class="form-select edit_state" name ="type" id="validationCustom04" required="">
                                <option selected="" disabled="" value="">Choose...</option>
                                <?php foreach($state as $states){ ?>
                                <option value="<?php echo $states['iStateId'] ?>"><?php echo $states['vStateName'] ?></option>
                                <?php } ?>
                            </select>
                            <div class="invalid-feedback">Please select a valid state.</div>
                        </div> 
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">Role</label>
                            <select class="form-select edit_type" name ="type" id="validationCustom04" required="">
                                <option selected="" disabled="" value="">Choose...</option>
                                <?php foreach($roles as $role){ ?>
                                <option value="<?php echo $role['iUserRoleId'] ?>"><?php echo $role['vUserRole'] ?></option>
                                <?php } ?>
                                <?php //foreach($role as $roles){ ?>
                                <!-- <option value="<?php echo $roles['iUserRoleId'] ?>"><?php echo $roles['vUserRole'] ?></option> -->
                                <?php //} ?>
                            </select>
                            <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustomUsername">Phone</label>
                            
                            <input class="form-control edit_phone" id="validationCustomUsername" name="phone" type="text" name="unit" placeholder="" aria-describedby="inputGroupPrepend" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom03">Company Name</label>
                            <input class="form-control edit_company_name" id="validationCustom03" type="text" name="company_name" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label tamil-lang" for="validationCustom03">நிறுவனத்தின் பெயர்</label>
                            <input class="form-control edit_company_name_tamil tamil-lang" id="validationCustom03" type="text" name="company_name_tamil" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Grade</label>
                        <select class="form-select edit_grade" name ="grade" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($grade as $grades){ ?>
                            <option value="<?php echo $grades['iGradeId'] ?>"><?php echo $grades['vGradeName'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Branch</label>
                        <?php if($this->session->userdata('UserRole') == 2 || $this->session->userdata('UserRole') == 3){ ?>
                                <label class="form-control" for="validationCustom04" readonly><?php echo $branch[0]['vBranchName'];?></label>  
                                <input class="form-control edit_branch" id="validationCustom01" type="hidden" name="branch" value="<?php echo $branch[0]['iBranchId'];?>">
                                <?php } else {?>
                        <select class="form-select edit_branch" name ="branch" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($branch as $branches){ ?>
                            <option value="<?php echo $branches['iBranchId'] ?>"><?php echo $branches['vBranchName'] ?></option>
                            <?php } } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom03">Address</label>
                            <input class="form-control edit_address" id="validationCustom03" type="text" name="address" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label tamil-lang" for="validationCustom03">முகவரி</label>
                            <input class="form-control edit_address_tamil tamil-lang" id="validationCustom03" type="text" name="address_tamil" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">Salesman</label>
                            <?php if($this->session->userdata('UserRole') == 3){ ?>
                                <label class="form-control" for="validationCustom04" readonly><?php echo $user[0]['vName'];?></label>  
                                <input class="form-control edit_salesman_id" id="validationCustom01" type="hidden" name="salesman_id" value="<?php echo $user[0]['iUserId'];?>">
                                <?php } else {?>
                            <select class="form-select edit_salesman_id" name ="salesman_id" id="validationCustom04" required="">
                                <option selected="" disabled="" value="">Choose...</option>
                                <?php foreach($user as $users){ ?>
                                    <option value="<?php echo $users['iUserId'] ?>" <?php if($this->session->userdata('LoggedId')==$users['iUserId']){ echo 'selected';} ?>><?php echo $users['vName'] ?></option>
                                <?php } } ?>
                            </select>
                            <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <!-- <div class="col-md-6">
                            <label class="form-label" for="validationCustom03">Reference Distributer</label>
                            <input class="form-control referencedistributor" id="validationCustom03" type="text" name="reference_distributor" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div> -->
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom03">Email</label>
                            <input class="form-control edit_email" id="validationCustom03" type="text" name="email" placeholder="" >
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <!-- <div class="col-md-6">
                            <label class="form-label" for="validationCustom03">Commission</label>
                            <input class="form-control commission" id="validationCustom03" type="text" name="commission" placeholder="" required="">
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
                        <div class="card">
                            <div class="card-body">
                                <div class="branch-box">
                                    <div class="section-title">Category</div>
                                    <?php foreach($categories as $ct){ ?>
                                        <div class="branch-list"><input type="checkbox" class="category_check_edit" name="category_check[]" id="category_value_<?php echo $ct['iCategoryId'] ?>" value="<?php echo $ct['iCategoryId'] ?>" id="">
                                        <label><?php echo $ct['vCategoryName'] ?></label></div>
                                    <?php } ?>
                                </div>
                                <span class="category_err"></span>
                            </div>
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
            var name_tamil = $('.add_name_tamil').val();
            var type = $('.add_type').val();
            var phone = $('.add_phone').val();
            var company_name = $('.add_company_name').val();
            var branch = $('.add_branch').val();
            var company_name_tamil = $('.add_company_name_tamil').val();
            var grade = $('.add_grade').val();
            var address = $('.add_address').val();
            var address_tamil = $('.add_address_tamil').val();
            var salesman_id = $('.add_salesman_id').val();
            var email = $('.add_email').val();
            var gstin = $('.add_gstin').val();
            var state = $('.add_state').val();
            var category = [];
            $('.category_check:checked').each(function(i){
                category[i] = $(this).val();
            });
            // var status = $('.radio:checked').val();
            
            if($('input[name="category_check[]"]:checked').length <= 0){
                // alert($('input[name="category_check[]"]:checked').length);
                $('.category_err').css('color','red').text('Please select atleast one category');
                return false;
            } else 
                $('.category_err').text('');
            if(name!="" && type!="" && phone!="" && company_name!="" && grade!="" && address!=""&& salesman_id!="" && salesman_id!=null){
                $("#butsave").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/distributor/add_distributor'); ?>",
                    type: "POST",
                    data: {
                        name: name,
                        type: type,
                        phone: phone,
                        company_name: company_name,
                        grade: grade,
                        address: address,
                        salesman_id: salesman_id,
                        email: email,
                        name_tamil:name_tamil,
                        company_name_tamil:company_name_tamil,
                        address_tamil:address_tamil,
                        category:category,
                        branch:branch,
                        gstin:gstin,
                        state:state
                    },
                    cache: false,
                    success: function(dataResult){
                        // alert(dataResult);
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            $("#butsave").removeAttr("disabled");
                            $('#distributor_form').find('input:text').val('');

                            $('#kt_modal_add_user').modal('hide');
                            $('#basic').DataTable().ajax.reload();					
                        }
                    }
                });
            }
        });

        $('#btn_update').on('click', function() {
            var name = $('.edit_name').val();
            var name_tamil = $('.edit_name_tamil').val();
            var type = $('.edit_type').val();
            var phone = $('.edit_phone').val();
            var company_name = $('.edit_company_name').val();
            var branch = $('.edit_branch').val();
            var company_name_tamil = $('.edit_company_name_tamil').val();
            var grade = $('.edit_grade').val();
            var address = $('.edit_address').val();
            var address_tamil = $('.edit_address_tamil').val();
            var salesman_id = $('.edit_salesman_id').val();
            var email = $('.edit_email').val();
            var status = $('.radio:checked').val();
            var distributorid = $('.customer_id').val();
            var gstin = $('.edit_gstin').val();
            var state = $('.edit_state').val();
            var category_check_edit = [];
            // alert(salesman_id);return false;
            if(name!="" && type!="" && phone!="" && company_name!="" && grade!="" && address!=""&& salesman_id!="" && status!="" && distributorid!="" && salesman_id!=null){
                if($('input[name="category_check[]"]:checked').length <= 0){
                        // alert($('input[name="category_check[]"]:checked').length);
                        $('.category_err').css('color','red').text('Please select atleast one category');
                        return false;
                    } else 
                        $('.category_err').text('');
                $('.category_check_edit:checked').each(function(i){
                    category_check_edit[i] = $(this).val();
                });
                $("#btn_update").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/distributor/update_distributor'); ?>",
                    type: "POST",
                    data: {
                        name: name,
                        name_tamil:name_tamil,
                        type: type,
                        phone: phone,
                        company_name: company_name,
                        company_name_tamil:company_name_tamil,
                        grade: grade,
                        address: address,
                        address_tamil:address_tamil,
                        salesman_id: salesman_id,
                        email: email,
                        status: status,
                        distributorid:distributorid,
                        category_check_edit:category_check_edit,
                        branch:branch,
                        gstin:gstin,
                        state:state
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
				url : "<?php echo base_url('master/distributor/get_distributor'); ?>",
				type: "POST"  
			},
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'S.No');
                $(row).find('td:eq(1)').attr('data-th', 'Customer Name');
                $(row).find('td:eq(2)').attr('data-th', 'Type');
                $(row).find('td:eq(3)').attr('data-th', 'Grade');
                $(row).find('td:eq(4)').attr('data-th', 'Company Name');
                $(row).find('td:eq(5)').attr('data-th', 'Phone Number');
                $(row).find('td:eq(6)').attr('data-th', 'Status');
                $(row).find('td:eq(7)').attr('data-th', 'Action');
            },
			"columnDefs":[  
				{  
					"targets":[0,7],  
					"orderable":false,
				},
                {  
					"targets":[0,5,6,7],
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
            $('.category_err').text('');
            $('input[type=checkbox]').prop('checked',false);

            var id = $(this).attr('data-id');
            var baseurl = "<?php echo base_url(); ?>";
            $.ajax({
                type : "POST",
                url  : "<?php echo base_url('master/distributor/edit_distributor');?>",
                dataType : "JSON",
                data : {id:id},
                success: function(data){
                    $('.edit_name').val(data.vCustomerName);
                    $('.edit_name_tamil').val(data.vCustomerName_Tamil);
                    $('.edit_phone').val(data.vPhoneNumber);
                    $('.edit_address').val(data.vAddress);
                    $('.edit_address_tamil').val(data.vAddress_Tamil);
                    $('.edit_type').val(data.iUserRoleId);
                    $('.edit_grade').val(data.iGradeId);
                    $('.edit_company_name').val(data.vCompanyName);
                    $('.edit_company_name_tamil').val(data.vCompanyName_Tamil);
                    $('.edit_email').val(data.vEmail);
                    $('.edit_branch').val(data.iBranchId);
                    $('.customer_id').val(data.iCustomerId);
                    $('.edit_salesman_id').val(data.iSalesmanId);
                    $('.edit_gstin').val(data.vGSTINNo);
                    $('.edit_state').val(data.iStateId);
                    if(data.eStatus == 'Active'){
                        $(".radio_active").prop("checked", true);
                    }
                    if(data.eStatus == 'Inactive'){
                        $(".radio_inactive").prop("checked", true);
                    }
                    $(data.category_arr).each(function( key, value ) {
                        console.log(value.iCategoryId);
                        $("#category_value_"+value.iCategoryId).prop("checked", true);
                    })
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
                    url: "<?php echo base_url() . '/master/distributor/delete_distributor';?>",
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