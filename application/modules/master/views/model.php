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
                <h3>Model</h3>
                <button type="button" class="btn btn-sm btn-primary mnone" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add Model</button>
            </div>
            <div class="col-6">
                <button type="button" class="btn btn-sm btn-primary wnone pull-right" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add Model</button>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')  ?>"><i class="fa fa-home"></i></a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Model</li>
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
                            <th>Model Name</th>
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
                <h5 class="modal-title">Add Model</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" novalidate="" id="model_form" method="post" enctype="multipart/form-data" >
            <div class="modal-body scroll-y">
                <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('LoggedId'); ?>">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label" for="validationCustom03">Model Name</label>
                        <input class="form-control add_model_name" id="validationCustom03" type="text" name="model_name" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label tamil-lang" for="validationCustom03">மாதிரி பெயர்</label>
                        <input class="form-control add_model_name_tamil tamil-lang" id="validationCustom03" type="text" name="model_name_tamil" placeholder="">
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
                <h5 class="modal-title">Edit Model</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" novalidate="" method="post" enctype="multipart/form-data" >
                <input type="hidden" class="region_id" name="region_id">  
                <div class="modal-body scroll-y">                
                    <input type="hidden" name="model_id" class="model_id">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label tamil-lang" for="validationCustom03">மாதிரி பெயர்</label>
                            <input class="form-control model_name_tamil tamil-lang" id="validationCustom03" type="text" name="model_name_tamil" placeholder="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom03">Model Name</label>
                            <input class="form-control model_name" id="validationCustom03" type="text" name="model_name" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
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
            var model_name = $('.add_model_name').val();
            var model_name_tamil = $('.add_model_name_tamil').val();
            if(model_name!=""){
                $("#butsave").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/model/add_model'); ?>",
                    type: "POST",
                    data: {
                        model_name: model_name,model_name_tamil:model_name_tamil,
                    },
                    cache: false,
                    success: function(dataResult){
                        // alert(dataResult);
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            $("#butsave").removeAttr("disabled");
                            $('#model_form').find('input:text').val('');

                            $('#kt_modal_add_user').modal('hide');
                            $('#basic').DataTable().ajax.reload();					
                        }
                    }
                });
            }
        });

        $('#btn_update').on('click', function() {
                var model_name = $('.model_name').val();
                var model_name_tamil = $('.model_name_tamil').val();
                var model_id = $('.model_id').val();
                var status = $('.radio:checked').val();
            if(model_name!="" && model_id!="" && status!=""){
                $("#btn_update").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/model/update_model'); ?>",
                    type: "POST",
                    data: {
                        model_name: model_name,
                        model_name_tamil:model_name_tamil,
                        model_id: model_id,
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
				url : "<?php echo base_url('master/model/get_models'); ?>",
				type: "POST"  
			},
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'S.No');
                $(row).find('td:eq(1)').attr('data-th', 'Model Name');
                $(row).find('td:eq(2)').attr('data-th', 'Status');
                $(row).find('td:eq(3)').attr('data-th', 'Action');
            },
			"columnDefs":[  
				{  
					"targets":[3],  
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
        $(document).on('click','.addAttr',function(){
            var id = $(this).attr('data-id');
            var baseurl = "<?php echo base_url(); ?>";
            $.ajax({
                type : "POST",
                url  : "<?php echo base_url('master/model/edit_model');?>",
                dataType : "JSON",
                data : {id:id},
                success: function(data){
                    console.log(data);
                    $('.model_name').val(data.vModelName);
                    $('.model_name_tamil').val(data.vModelName_Tamil);
                    $('.model_id').val(data.iModelId);
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
                    url: "<?php echo base_url() . 'master/model/delete_model'; ?>",
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