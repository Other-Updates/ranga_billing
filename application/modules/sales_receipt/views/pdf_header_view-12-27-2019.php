
<?php
$theme_path = $this->config->item('theme_locations') . $this->config->item('active_template');
$data['company_details'] = $this->admin_model->get_company_details();
?>

<table width="100%">
    <tr>
        <td rowspan="" align="left" width="25%">
            <img src="<?= $theme_path; ?>/images/logo-login.png"  style="vertical-align:middle !important;" />
        </td>
		
        <td width="75%" align="left" style="font-size:8px;">     
			
			<tr><td><b style="font-size:10px;">AMMAN BLUE METALS</b></td></tr>
            <?= $data['company_details'][0]['address1'] ?>,
			<?= $data['company_details'][0]['address2'] ?>,
			<?= $data['company_details'][0]['city'] ?>,
			<?= $data['company_details'][0]['state'] ?> -
		    <?= $data['company_details'][0]['pin'] ?><br/>
			Ph:<?= $data['company_details'][0]['phone_no'] ?>,
            Email:<?= $data['company_details'][0]['email'] ?>                  
            
        </td>
    </tr>	


