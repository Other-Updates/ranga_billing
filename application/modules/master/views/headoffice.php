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
                <h3>Head offices</h3>
                <button type="button" class="btn btn-sm btn-primary mnone" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add Headoffice</button>
            </div>
            <div class="col-6">
                <button type="button" class="btn btn-sm btn-primary wnone pull-right" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add Headoffice</button>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Head office</li>
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
                        <table class="list-table table display basictable" id="basic">
                        <thead>
                            <tr>
                            <th>S.No</th>
                            <th>Head office Name</th>
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
                <h5 class="modal-title">Add Head office</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" id="ho_form" novalidate="" method="post" enctype="multipart/form-data" >
            <div class="modal-body scroll-y">
                <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('LoggedId'); ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Head office Name</label>
                        <input class="form-control add_headoffice_name" id="validationCustom03" type="text" name="headoffice_name" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tamil-lang" for="validationCustom03">தலைமை அலுவலகத்தின் பெயர்</label>
                        <input class="form-control add_headoffice_name_tamil tamil-lang" id="validationCustom03" type="text" name="headoffice_name_tamil" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">State</label>
                        <select class="form-select add_state" name="state" id="validationCustom04" required="">
                        <option selected="" disabled="" value="">Choose...</option>
                        <?php foreach ($state as $states){ ?>
                        <option value="<?php echo $states['iStateId'] ?>"><?php echo $states['vStateName'] ?></option>
                        <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Region</label>
                        <select class="form-select add_region" name="region" id="validationCustom04" required="">
                        <option selected="" disabled="" value="">Choose...</option>
                        <?php foreach ($region as $regions){ ?>
                        <option value="<?php echo $regions['iRegionId'] ?>"><?php echo $regions['vRegionName'] ?></option>
                        <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
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
                <h5 class="modal-title">Edit Head office</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" novalidate="" method="post" enctype="multipart/form-data" >
                <input type="hidden" class="headoffice_id" name="headoffice_id">  
                <div class="modal-body scroll-y">                
                    <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('LoggedId'); ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="hidden" class="distributorid" name="distributorid" value="">
                            <label class="form-label" for="validationCustom03">Head office Name</label>
                            <input class="form-control headoffice_name" id="validationCustom03" type="text" name="headoffice_name" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-6">
                            <input type="hidden" class="distributorid" name="distributorid" value="">
                            <label class="form-label tamil-lang" for="validationCustom03">தலைமை அலுவலகத்தின் பெயர்</label>
                            <input class="form-control headoffice_name_tamil tamil-lang" id="validationCustom03" type="text" name="headoffice_name_tamil" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">State</label>
                            <select class="form-select state" name="state" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach ($state as $states){ ?>
                            <option value="<?php echo $states['iStateId'] ?>"><?php echo $states['vStateName'] ?></option>
                            <?php } ?>
                            </select>
                            <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">Region</label>
                            <select class="form-select region" name="region" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach ($region as $regions){ ?>
                            <option value="<?php echo $regions['iRegionId'] ?>"><?php echo $regions['vRegionName'] ?></option>
                            <?php } ?>
                            </select>
                            <div class="invalid-feedback">Please select a valid state.</div>
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

        $('#butsave').on('click', function() {
            var headoffice_name = $('.add_headoffice_name').val();
            var headoffice_name_tamil = $('.add_headoffice_name_tamil').val();
            var state = $('.add_state').val();
            var region = $('.add_region').val();
            if(headoffice_name!="" && state!="" && region!=""){
                $("#butsave").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/headoffice/add_headoffice'); ?>",
                    type: "POST",
                    data: {
                        headoffice_name: headoffice_name,
                        headoffice_name_tamil:headoffice_name_tamil,
                        state: state,
                        region: region,
                    },
                    cache: false,
                    success: function(dataResult){
                        // alert(dataResult);
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            $("#butsave").removeAttr("disabled");
                            $('#ho_form').find('input:text').val('');

                            $('#kt_modal_add_user').modal('hide');
                            $('#basic').DataTable().ajax.reload();					
                        }
                    }
                });
            }
        });

        $('#btn_update').on('click', function() {
                var headoffice_name = $('.headoffice_name').val();
                var headoffice_name_tamil = $('.headoffice_name_tamil').val();
                var state = $('.state').val();
                var region = $('.region').val();
                var headoffice_id = $('.headoffice_id').val();
                var status = $('.radio:checked').val();
            if(headoffice_name!="" && state!="" && region!="" && headoffice_id!=""){
                $("#btn_update").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/headoffice/update_headoffice'); ?>",
                    type: "POST",
                    data: {
                        headoffice_name: headoffice_name,
                        headoffice_name_tamil:headoffice_name_tamil,
                        state: state,
                        region: region,
                        headoffice_id: headoffice_id,
                        status: status,
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

        var table = $('#basic').DataTable({
			"processing":true,
			"serverSide":true,
			"order":[], 
			"ajax": {
				url : "<?php echo base_url('master/headoffice/get_headoffices'); ?>",
				type: "POST"  
			},
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'S.No');
                $(row).find('td:eq(1)').attr('data-th', 'Head office Name');
                $(row).find('td:eq(2)').attr('data-th', 'Status');
                $(row).find('td:eq(3)').attr('data-th', 'Action');
            },
			"columnDefs":[  
				{  
					"targets":[0,3],  
					"orderable":false,
				},
                {  
					"targets":[0, 2, 3],  
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
                url  : "<?php echo base_url('master/headoffice/edit_headoffice');?>",
                dataType : "JSON",
                data : {id:id},
                success: function(data){
                    $('.headoffice_name').val(data.vHeadOfficeName);
                    $('.headoffice_name_tamil').val(data.vHeadOfficeName_Tamil);
                    $('.state').val(data.iStateId);
                    $('.region').val(data.iRegionId);
                    $('.headoffice_id').val(data.iHeadOfficeId);
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
                    url: "<?php echo base_url() . 'master/headoffice/delete_headoffice';?>",
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