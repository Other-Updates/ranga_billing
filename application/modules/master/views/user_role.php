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
                <h3>User Roles</h3>
                <button type="button" class="btn btn-sm btn-primary mnone" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add User Role</button>
            </div>
            <div class="col-6">
                <button type="button" class="btn btn-sm btn-primary wnone pull-right" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add User Role</button>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">User Roles</li>
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
                        <?php $sno = 1; ?>
                        <table class="list-table display text-center basic-table basictable" id="basic">
                        <thead>
                            <tr>
                                <th width="10%">S.No</th>                            
                                <th width="60%" class="text-left">User roles</th>                            
                                <th width="20%">Status</th>                 
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($user_roles as $roles){ ?>
                                <tr>
                                    <td><?php echo $sno; ?></td>
                                    <td class="text-left"><?php echo $roles['vUserRole']; ?></td>
                                    <td><?php echo $roles['eStatus']; ?></td>
                                    <td>
                                        <a href="<?php echo base_url('master/user_role/user_permission/'.$roles['iUserRoleId']); ?>" class="action-icon fa fa-cog td-icon"></a>
                                        <a href="" data-id= "<?php echo $roles['iUserRoleId'] ?>" class="action-icon addAttr" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="fa fa-pencil td-icon tm2"></i></a>
                                        <a href="" data-id= "<?php echo $roles['iUserRoleId'] ?>" class="action-icon removeAttr " ><i class="fa fa-trash td-icon"></i></a>
                                    </td>
                                </tr>
                            <?php $sno++;
                            } ?>
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
                <h5 class="modal-title">Add User Role</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" novalidate="" id="user_role_form" method="post" enctype="multipart/form-data" >
            <div class="modal-body scroll-y">
                <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('LoggedId'); ?>">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label" for="validationCustom03">User Role</label>
                        <input class="form-control add_user_role" id="validationCustom03" type="text" name="user_role" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-12 d-none">
                        <label class="form-label" for="validationCustom03">User Role (Tamil)</label>
                        <input class="form-control add_user_role_tamil" id="validationCustom03" type="text" name="user_role_tamil" placeholder="" required="">
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User Role</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" novalidate="" method="post" enctype="multipart/form-data" >
                <input type="hidden" class="userrole_id" name="userrole_id">  
                <div class="modal-body scroll-y">                
                    <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('LoggedId'); ?>">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label" for="validationCustom03">User Role</label>
                            <input class="form-control user_role" id="validationCustom03" type="text" name="user_role" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-6 d-none">
                            <label class="form-label" for="validationCustom03">User Role (Tamil)</label>
                            <input class="form-control user_role_tamil" id="validationCustom03" type="text" name="user_role_tamil" placeholder="" required="">
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
    $(document).ready( function () {
        $('#basic').DataTable(
            {
                "createdRow": function(row, data, dataIndex) {
                    $(row).find('td:eq(0)').attr('data-th', 'S.No');
                    $(row).find('td:eq(1)').attr('data-th', 'User roles');
                    $(row).find('td:eq(2)').attr('data-th', 'Status');
                    $(row).find('td:eq(3)').attr('data-th', 'Action');
                },
            }
        );
        $('#butsave').on('click', function() {
            var user_role = $('.add_user_role').val();
            var user_role_tamil = $('.add_user_role_tamil').val();
            if(user_role!="" ){
                $("#butsave").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/user_role/add_roles'); ?>",
                    type: "POST",
                    data: {
                        user_role: user_role,user_role_tamil:user_role_tamil,
                    },
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            $("#butsave").removeAttr("disabled");
                            $('#user_role_form').find('input:text').val('');

                            $('#kt_modal_add_user').modal('hide');
                            $('#basic').ajax.reload();					
                        }
                    }
                });
            }
        });
        $('#btn_update').on('click', function() {
            var user_role = $('.user_role').val();
            var user_role_tamil = $('.user_role_tamil').val();
            var status = $('.radio:checked').val();
            var userrole_id = $('.userrole_id').val();
            if(user_role!="" && status!="" && userrole_id!=""){
                $("#btn_update").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/user_role/update_roles'); ?>",
                    type: "POST",
                    data: {
                        user_role: user_role,
                        user_role_tamil:user_role_tamil,
                        status: status,
                        userrole_id: userrole_id,
                    },
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            $('#btn_update').removeAttr('disabled');

                            $('#kt_modal_edit_user').modal('hide');
                            $('#basic').DataTable().draw();					
                        }
                    }
                });
            }
        });
    });
    $(document).on('click','.addAttr',function(){
        var id = $(this).attr('data-id');
        var baseurl = "<?php echo base_url(); ?>";
        $.ajax({
            type : "POST",
            url  : "<?php echo base_url('master/user_role/edit_roles');?>",
            dataType : "JSON",
            data : {id:id},
            success: function(data){
                $('.user_role').val(data.vUserRole);
                $('.user_role_tamil').val(data.vUserRole_Tamil);
                $('.userrole_id').val(data.iUserRoleId);
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
                    url: "<?php echo base_url() . 'master/user_role/delete_roles';?>",
                    type: 'POST',
                    data:{id:id},
                    success: function() {
                            Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                        );      
                        $('#basic').DataTable().draw();
                    }
                });
            }
        });
    });
</script>