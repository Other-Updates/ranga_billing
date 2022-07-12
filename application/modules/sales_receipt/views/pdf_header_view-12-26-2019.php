
<?php
$theme_path = $this->config->item('theme_locations') . $this->config->item('active_template');
$data['company_details'] = $this->admin_model->get_company_details();
?>
<style>
table tr td:last-child{text-align:left;}
</style>
<table width="100%">
<b style="margin-left:-60%;margin-top:-30px;">AMMAN BLUE METALS</b><br/>
    <tr>
        <td align="left" width="15%">
            <img src="<?= $theme_path; ?>/images/logo-login.png" width="	" />
        </td>
		
        <td width="75%" align="left">     		
		
            <font style="text-align:left; margin-left:30px; font-size:6px;">			
            <?= $data['company_details'][0]['address1'] ?>,
            <?= $data['company_details'][0]['address2'] ?>,<br/>
            <?= $data['company_details'][0]['city'] ?>,
            <?= $data['company_details'][0]['state'] ?> -
            <?= $data['company_details'][0]['pin'] ?><br/>
            Ph:<?= $data['company_details'][0]['phone_no'] ?>,
            Email:<?= $data['company_details'][0]['email'] ?>
            </font>
        </td>
    </tr>
</table>




