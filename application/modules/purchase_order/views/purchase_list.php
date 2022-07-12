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
                <h3>Purchase Order</h3>
                <a href="<?php echo base_url('purchase_order/add_purchase'); ?>"><button type="button" class="btn btn-sm btn-primary mnone">Add Purchase</button></a>
            </div>
            <div class="col-6">
            <a href="<?php echo base_url('purchase_order/add_purchase'); ?>"><button type="button" class="btn btn-sm btn-primary wnone pull-right">Add Purchase</button></a>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Sales Order</li>
                <!-- <li class="breadcrumb-item active">Default</li> -->
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
                        <table class="list-table display basictable table" id="basic">
                        <thead>
                            <tr>
                                <th width="5%">Sno</th>
                                <th width="10%">PO Number</th>
                                <th width="20%">Supplier</th> 
                                <th width="10%" class="p-r-30">Net Amount</th>
                                <th width="15%">Delivery Date</th>
                                <th width="15%">Delivery Status</th>
                                <th width="10%">Created Date</th>
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
<input type="hidden" name="otp" class="verificaion_otp">
<input type="hidden" class="edit_purchase_url">
<input type="hidden" class="userrole_id" value="<?php echo $this->session->userdata('UserRole'); ?>">
<div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h5 class="modal-title">Add Unit</h5> -->
                <input type="hidden" class="action_type" >
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" id="unit_form" novalidate="" method="post" enctype="multipart/form-data">
            <div class="modal-body scroll-y">
                <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label" for="validationCustom04">Otp</label>
                    <input class="form-control user_otp" id="validationCustom03" type="number" required name="otp" placeholder="" >
                    <span class="otp_err"></span>
                    <div class="invalid-feedback" style="color:red">Field is required.</div>
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" data-bs-original-title="" title="">Close</button>
                <button class="btn btn-primary butsave" type="button">Submit</button>
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
				url : "<?php echo base_url('purchase_order/get_purchase_order'); ?>",
				type: "POST"  
			},
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'S.No');
                $(row).find('td:eq(1)').attr('data-th', 'PO.No');
                $(row).find('td:eq(2)').attr('data-th', 'Supplier');
                $(row).find('td:eq(3)').attr('data-th', 'Net Amount');
                $(row).find('td:eq(4)').attr('data-th', 'Delivery Date');
                $(row).find('td:eq(5)').attr('data-th', 'Delivery Status');
                $(row).find('td:eq(6)').attr('data-th', 'Created Date');
                $(row).find('td:eq(7)').attr('data-th', 'Action');
            },
			 "columnDefs":[  
				{  
					"targets":[0,7],
					"orderable":false,
				}, 
                {  
					"targets":[0,4,5,6,7],  
					"className":"text-center"
				},
                 {  
			 		"targets":[3],  
			 		"className":"text-right"
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
        var type = "delete";
        var id = $(this).attr('data-id');
        if($(this).attr('data-check') == 1){
        delete_purchase(id);
        }if($(this).attr('data-check') == 0){
            $('.user_otp').val('');
            $('.otp_err').text('');
            $('.action_type').attr('data-delete-id',id);
            $('.action_type').val('delete');
            $('#kt_modal_add_user').modal('show');
            generate_otp(type);
        }
    });
    function delete_purchase(id){
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
                    url: "<?php echo base_url() . 'purchase_order/delete_purchase_order';?>",
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
    }
    $(document).on('click','.edit_purchase_order_modal',function(){
        $('.user_otp').val('');
        $('.otp_err').text('');
        var order_number = $(this).attr('data-order-no');
        var type = "edit";
        $('.action_type').val('edit');
        $('#kt_modal_add_user').modal('show');
        var edit_url = $(this).attr('data_url');
        $('.edit_purchase_url').val(edit_url);
        generate_otp(type,order_number);
    });
    $(document).on('click','.butsave',function(e){
        // e.preventDefault();
        if($('.user_otp').val() != ''){
                var user_otp = $('.user_otp').val();
                var verify_otp = $('.verificaion_otp').val();
            if($('.action_type').val() == "edit"){
                if(user_otp == verify_otp){
                    window.location.href = $('.edit_purchase_url').val();
                }else{
                    $('.otp_err').css('color','red').text('Invalid Otp');
                }
            }
            if($('.action_type').val() == "delete"){
                if(user_otp == verify_otp){
                    $('#kt_modal_add_user').modal('hide');
                    delete_purchase($('.action_type').attr('data-delete-id'));
                }else{
                    $('.otp_err').css('color','red').text('Invalid Otp');
                }
            }
        }
    });
    function generate_otp(type,order_number=null){
        $.ajax({
            type: "POST",
            data:{type:type,order_number:order_number},
            url: "<?php echo base_url() ?>purchase_order/generate_otp",
                success: function(data){
                    data = JSON.parse(data);
                    console.log(data);
                    $('.verificaion_otp').val(data.iOtpCode);
                    $('.user_otp').val(data.iOtpCode);
                }
        });
    }
    
</script>
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-time-picker/moment.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-time-picker/tempusdominus-bootstrap-4.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-time-picker/datetimepicker.custom.js"></script>