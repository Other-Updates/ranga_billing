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
                <h3>Delivery Order</h3>
                <?php if($this->session->userdata('UserRole') == 1){?>
                <a href="<?php echo base_url('stock/add_stock'); ?>" class="mnone"><button type="button" class="btn btn-sm btn-primary">Add Delivery Order</button></a>
                <?php }?>
            </div>
            <div class="col-6">
            <?php if($this->session->userdata('UserRole') == 1){?>
                <a href="<?php echo base_url('stock/add_stock'); ?>" class="wnone pull-right"><button type="button" class="btn btn-sm btn-primary">Add Delivery Order</button></a>
                <?php }?>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Delivery Order</li>
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
                            <th>S.No</th>
                            <th>Delivery Order No</th>
                            <th>Branch Name</th>
                            <th>Quantity</th>
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
<script>
    $(document).ready(function(){
        var table = $('#basic').DataTable({
			"processing":true,
			"serverSide":true,
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ],
			"order":[], 
			"ajax": {
				url : "<?php echo base_url('stock/get_branch_stock'); ?>",
				type: "POST"
			},
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'S.No');
                $(row).find('td:eq(1)').attr('data-th', 'Delivery Order No');
                $(row).find('td:eq(2)').attr('data-th', 'Branch Name');
                $(row).find('td:eq(3)').attr('data-th', 'Quantity');
                $(row).find('td:eq(4)').attr('data-th', 'Status');
                $(row).find('td:eq(5)').attr('data-th', 'Action');
            },
			"columnDefs":[  
				{  
					"targets":[0,5],
					"orderable":false,
				},
                {  
					"targets":[0,1,3,4,5],  
					"className":"text-center"
				},  
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
                    url: "<?php echo base_url() . 'stock/delete_delivery_order';?>",
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