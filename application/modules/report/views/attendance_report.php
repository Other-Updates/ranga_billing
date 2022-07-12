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
                <h3>Attendance</h3>
                <a href="<?php echo base_url('report/attendance_report/track_salesman') ?>" class="mnone"><button type="button" class="btn btn-sm btn-primary pull-right">Track</button></a>
            </div>
            <div class="col-6">     
                <a href="<?php echo base_url('report/attendance_report/track_salesman') ?>" class="wnone pull-right"><button type="button" class="btn btn-sm btn-primary pull-right">Track</button></a>           
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Report</li>
                <li class="breadcrumb-item active">Attendance Report</li>
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
                            <th>Salesman</th>
                            <th>Login</th>
                            <th>Logout</th>
                            <th>Hours</th>
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
			"order":[], 
			"ajax": {
				url : '<?php echo base_url("report/attendance_report/get_salesman_login"); ?>',
				type: "POST"  
			},
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(0)').attr('data-th', 'SO#');
                $(row).find('td:eq(1)').attr('data-th', 'Salesman');
                $(row).find('td:eq(2)').attr('data-th', 'Login');
                $(row).find('td:eq(3)').attr('data-th', 'Logout');
                $(row).find('td:eq(4)').attr('data-th', 'Hours');
            },
			"columnDefs":[  
				{  
					"targets":[0],  
					"orderable":false,
				},
                {  
					"targets":[0,1,2,3],  
					"className":"text-center"
				}
                
			], 
		});
    });
</script>