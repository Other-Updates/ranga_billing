<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api extends REST_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('api_model');
	}
	public function salesman_login(){
        $data = $this->_get_customer_post_values();
		if(!empty($data)){
			$username = $data['username'];
			$user_check = $this->api_model->check_customer_exist($username);
			$employee_check = $this->api_model->check_employee_exist($username);
			// print_r($employee_check);exit;
			// $login = $this->api_model->check_salesman_login($username,$password);
				if(!empty($employee_check)){
					// print_r($employee_check);exit;
					$update = array();
					$otp = $this->api_model->generateNumericOTP('4');
					$update_arr = array(
						'iOtpCode' => $otp,
						'tOtpVerify' => 0,
						'tLoginStatus' => 0,
					);
					$this->api_model->update_user($employee_check['iUserId'],$update_arr);
					// Authorisation details.
					$username = "smsgateway2k22@gmail.com";
					$hash = "af6af7187849c32125a21b3c57dc5fd6cea5c96111d5b0bb5baf1e008bb5d9cd";
					// Config variables. Consult http://api.textlocal.in/docs for more info.
					$test = "0";
					// Data for text message. This is the text message data.
					$sender = "CICSTO"; // This is who the message appears to be from.
					$numbers = $employee_check['iPhoneNumber']; // A single number or a comma-seperated list of numbers
					$message = $otp." is your OTP to register with CoolinCool Store, For any help, please contact us at +91 9655007712";
					// $message = $otp." is your OTP to register with CoolinCool Store, For any help, please contact us at +91 9655007712
					// 612 chars or less
					// A single number or a comma-seperated list of numbers
					$message = urlencode($message);
					$post_data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
					$ch = curl_init('https://api.textlocal.in/send/?');
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$result = curl_exec($ch); // This is the result from the API
					curl_close($ch);
					$details = $this->api_model->get_salesman_by_id($employee_check['iUserId'],$data['language']);
					$output = array ('status' => 'Success', 'message' => 'Otp send successfully','data'=>$details);
					$this->response($output);exit;
				}
				else if(!empty($user_check)){
					$update = array();
					$otp = $this->api_model->generateNumericOTP('4');
					$update_arr = array(
						'iOtpCode' => $otp,
						'tOtpVerify' => 0,
						'tLoginStatus' => 0,
					);
					$this->api_model->update_distributor($user_check['iCustomerId'],$update_arr);
					
					// Authorisation details.
					$username = "smsgateway2k22@gmail.com";
					$hash = "af6af7187849c32125a21b3c57dc5fd6cea5c96111d5b0bb5baf1e008bb5d9cd";
					// Config variables. Consult http://api.textlocal.in/docs for more info.
					$test = "0";
					// Data for text message. This is the text message data.
					$sender = "CICSTO"; // This is who the message appears to be from.
					$numbers = $user_check['vPhoneNumber']; // A single number or a comma-seperated list of numbers
					$message = $otp." is your OTP to register with CoolinCool Store, For any help, please contact us at +91 9655007712";
					// $message = $otp." is your OTP to register with CoolinCool Store, For any help, please contact us at +91 9655007712
					// 612 chars or less
					// A single number or a comma-seperated list of numbers
					$message = urlencode($message);
					$post_data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
					$ch = curl_init('https://api.textlocal.in/send/?');
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$result = curl_exec($ch); // This is the result from the API
					curl_close($ch);
					$details = $this->api_model->distributor_by_id_login($user_check['iCustomerId'],$data['language']);
					$output = array ('status' => 'Success', 'message' => 'Otp send successfully','data'=>$details);
					$this->response($output);exit;
				}
			else{
				$output = array ('status' => 'Error', 'message' => 'Invalid Phone number Please Sign up and try again');
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function login_otp_verify(){
        $data = $this->_get_customer_post_values();
		if(!empty($data)){
			if($data['type'] == "Salesman"){
				$id = $data['user_id'];
				$otp = $data['otp'];
				$details = $this->api_model->get_salesman_by_id($id,$data['language']);
				if($details['iOtpCode'] == $otp){
					$update_arr = array(
						'tOtpVerify' => 1,
						'tLoginStatus' => 1,
					);
					$this->api_model->update_user($id,$update_arr);
					$time_update = array(
						'iUserId' => $data['user_id'],
						'dLoginTIme' => date('Y-m-d H:i:s'),
						'dLatitude' => $data['lat'], 
						'dLongitude' => $data['long'],
						'iCreatedDate' => date('Y-m-d'),
					); 
					$this->api_model->update_login_time($time_update,$data['user_id']);
					$user = $this->api_model->get_salesman_by_id($id,$data['language']);
					if($user)
					$output = array ('status' => 'Success', 'message' => 'Logged in successfully','data'=>$user);
					else
					$output = array ('status' => 'Failed', 'message' => 'No data found');
					$this->response($output);exit;
				}else{
					$output = array ('status' => 'Error', 'message' => 'Invalid Otp entered');
					$this->response($output);
				}
			}
			else{
				$id = $data['user_id'];
				$otp = $data['otp'];
				$details = $this->api_model->distributor_by_id($id,$data['language']);
				if($details['iOtpCode'] == $otp){
					$update_arr = array(
						'tOtpVerify' => 1,
						'tLoginStatus' => 1,
					);
					$this->api_model->update_distributor($id,$update_arr);
					$user = $details = $this->api_model->distributor_by_id_login($id,$data['language']);
					$output = array ('status' => 'Success', 'message' => 'Logged in successfully','data'=>$user);
					$this->response($output);exit;
				}else{
					$output = array ('status' => 'Error', 'message' => 'Invalid Otp entered');
					$this->response($output);
				}
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function products_list(){
		$products = $this->api_model->get_products();
		if($products){
			$output = array ('status' => 'Success', 'message' => 'product listed successfully','data'=>$products);
			$this->response($output);
		}else{
			$output = array ('status' => 'Error', 'message' => 'No data found');
			$this->response($output);
			
		}
	}
	public function salesman_list(){
		$salesman = $this->api_model->get_salesman();
		if($salesman){
			$output = array ('status' => 'Success', 'message' => 'product listed successfully','data'=>$salesman);
			$this->response($output);
		}else{
			$output = array ('status' => 'Error', 'message' => 'No data found');
			$this->response($output);
			
		}
	}
	public function salesman_logout(){
        $data = $this->_get_customer_post_values();
		if(!empty($data)){
			$user = $this->api_model->get_salesman_by_id($data['user_id']);
			if($user){
				$time_update = array();
				$time_update['dLogoutTime'] = date('Y-m-d H:i:s');
				$this->api_model->update_logout_time($data['user_id'],$time_update);
				$update['tLoginStatus'] = 0;
				$this->api_model->update_user($data['user_id'],$update);
				$login_result = $this->api_model->get_salesman_by_id($data['user_id']);
				$output = array ('status' => 'Success', 'message' => 'Logged out successfully','data'=>$login_result);
				$this->response($output);
			}else{
				$output = array ('status' => 'Error', 'message' => 'No user found');
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function edit_sales_order(){
        $data = $this->_get_customer_post_values();
		if(!empty($data)){
			$order_id = $data['sales_order_id'];
			$sales = array();
			$sales['iUserId'] = $data['user_id'];
			$sales['iProductId'] = $data['product_id'];
			$sales['iSalesOrderNumber'] = $data['sales_order_number'];
			$sales['iDistributorId'] = $data['distributor_id'];
			$sales['iSalesAmount'] = $data['sales_amount'];
			$sales['dSalesOrderDate'] = $data['sales_order_date'];
			$this->api_model->update_sales_order($order_id,$sales);
			$details = $this->api_model->sales_order_by_id($data['sales_order_id']);
			$output = array(
				'status' => 'success',
				'message' => 'order updated successfully',
				'data' => $etails,
			);
			$this->response($output);
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function track_salesman_location(){
        $data = $this->_get_customer_post_values();
		if(!empty($data)){
			$user = $this->api_model->get_salesman_by_id($data['user_id']);
			if($user){
				$location = array(
					'iSalesmanId' => $data['user_id'],
					'dLatitude' => $data['latitude'],
					'dLongitude' => $data['longitude'],
					'dCreatedDate' => date("Y-m-d h:i:s")
				);
				$this->api_model->get_salesman_location($location);
				$output = array ('status' => 'success', 'message' => 'location added successfully');
				$this->response($output);
			}else{
				$output = array ('status' => 'Error', 'message' => 'No user found');
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function forgot_password(){
		$json_input = $this->_get_customer_post_values();
		if (!empty($json_input)) {
			$exist = $this->api_model->get_custom_salesman($data['mobile']);
			if($exist){
				$user = $this->api_model->get_salesman_by_id($exist['iUserId']);
				$otp = $this->api_model->generateNumericOTP('4');
				$update_data['iOtpCode'] = $otp;
				$this->api_model->update_user($user['user_id'],$update_data);
				//Otp mail sending
				$msg = 'Your Otp code is '.$otp.' - Coolincool masala';
				$sub = 'Registration Otp';
				$config['protocol']    = 'smtp';
				$config['smtp_host']    = 'ssl://smtp.googlemail.com';
				$config['smtp_port']    = '465';
				$config['smtp_timeout'] = '7';
				$config['smtp_user']    = 'ftwoftesting@gmail.com';
				$config['smtp_pass']    = 'MotivationS@1';
				$config['charset']    = 'utf-8';
				$config['newline']    = "\r\n";
				$config['mailtype'] = 'text';
				$config['validation'] = TRUE; // bool whether to validate email or not      
				$this->load->library('email',$config);
				$this->email->initialize($config);
				$this->email->from('ftwoftesting@gmail.com','Tester');
				$this->email->to($user['vEmail']); 
				$this->email->subject($sub);
				$this->email->message($msg);  
				$this->email->send();
				
				$details = $this->api_model->get_salesman_by_id($user['user_id']);
				$output = array(
					'status' => 'success', 'code' =>200 ,'message' => 'OTP sent successfully','data'=> $details
				);
				$this->response($output);
			}else{
				$output = array(
					'status' => 'error', 'code'=>415 , 'message' => 'Invalid credentials'
				);
				$this->response($output);
			}
        } else {
            $output = array ('status' => 'error', 'message' => 'Please enter input data');
			$this->response($output);
        }
    }
	public function otp_verify(){
		$json_input = $this->_get_customer_post_values();
		if(!empty($json_input)){
			if($json_input['type'] == 'forget password'){
				$otp_result = $this->api_model->get_salesman_by_id($json_input['user_id']);
                if (!empty($otp_result) && $otp_result['iOtpCode'] == $json_input['otp_code']) {
                    $update_data['tOtpVerify']=1;
                    $this->api_model->update_user($otp_result['iUserId'],$update_data);
                    $output = array ('status' => 'Success','code' => 200 , 'message' => 'Otp Verified successfully');
                    $this->response($output);
                } else {
                    $output = array ('status' => 'Error', 'message' => 'Invalid OTP');
                    $this->response($output);
                }
			}
		}else {
            $output = array ('status' => 'error', 'message' => 'Please enter input data');
			$this->response($output);
        }
	}
	public function resend_otp(){
		$json_input = $this->_get_customer_post_values();
		if(!empty($json_input)){
			$otp = $this->api_model->generateNumericOTP('4');
			$update_data = array();
			if($json_input['type'] == 'forget password' || $json_input['type'] == 'login'){
				$update_data['iOtpCode'] = $otp;
				$update_data['tOtpVerify'] = 0;
				if($json_input['user_type'] == "Salesman"){
					$update = $this->api_model->update_user($json_input['user_id'],$update_data);
					$details = $this->api_model->get_salesman_by_id($json_input['user_id'],$json_input['language']);
				}else{
					$this->api_model->update_distributor($json_input['user_id'],$update_data);
					$details = $this->api_model->distributor_by_id_login($json_input['user_id'],$json_input['language']);
					
				}
				// Authorisation details.
				$username = "smsgateway2k22@gmail.com";
				$hash = "af6af7187849c32125a21b3c57dc5fd6cea5c96111d5b0bb5baf1e008bb5d9cd";
				// Config variables. Consult http://api.textlocal.in/docs for more info.
				$test = "0";
				// Data for text message. This is the text message data.
				$sender = "CICSTO"; // This is who the message appears to be from.
				if($json_input['user_type'] == "Salesman"){
				$numbers = $details['iPhoneNumber']; // A single number or a comma-seperated list of numbers
				}else{
					$numbers = $details['vPhoneNumber']; // A single number or a comma-seperated list of numbers
				}
				$message = $otp." is your OTP to register with CoolinCool Store, For any help, please contact us at +91 9655007712";
				// $message = $otp." is your OTP to register with CoolinCool Store, For any help, please contact us at +91 9655007712
				// 612 chars or less
				// A single number or a comma-seperated list of numbers
				$message = urlencode($message);
				$data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
				$ch = curl_init('https://api.textlocal.in/send/?');
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch); // This is the result from the API
				curl_close($ch);
				//otp mail sending
				$msg = 'Your Otp code is '.$otp.' - Coolincool masala';
				$sub = 'Registration Otp';
				$config['protocol']    = 'smtp';
				$config['smtp_host']    = 'ssl://smtp.googlemail.com';
				$config['smtp_port']    = '465';
				$config['smtp_timeout'] = '7';
				$config['smtp_user']    = 'ftwoftesting@gmail.com';
				$config['smtp_pass']    = 'MotivationS@1';
				$config['charset']    = 'utf-8';
				$config['newline']    = "\r\n";
				$config['mailtype'] = 'text';
				$config['validation'] = TRUE; // bool whether to validate email or not      
				$this->load->library('email',$config);
				$this->email->initialize($config);
				$this->email->from('ftwoftesting@gmail.com','Tester');
				$this->email->to($details['vEmail']); 
				$this->email->subject($sub);
				$this->email->message($msg);  
				$this->email->send();
				if(!empty($details)){
				$output = array('status' => 'Success', 'message' => 'Otp sent successfully','data'=>$details);
				}else{
				$output = array('status' => 'Error', 'message' => 'User not found');
				}
				$this->response($output);
			}
		}else
		{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
            $this->response($output);
		}
		
	}
	
	// public function resend_otp(){
	// 	$json_input = $this->_get_customer_post_values();
	// 	if(!empty($json_input)){
	// 		$otp = $this->api_model->generateNumericOTP('4');
	// 		$update_data = array();
	// 		if($json_input['type'] == 'forget password' || $json_input['type'] == 'login'){
	// 			$update_data['iOtpCode'] = $otp;
	// 			$update_data['tOtpVerify'] = 0;
	// 			$update = $this->api_model->update_user($json_input['user_id'],$update_data);
	// 			$details = $this->api_model->get_salesman_by_id($json_input['user_id']);
	// 			//otp mail sending
	// 			$msg = 'Your Otp code is '.$otp.' - Coolincool masala';
	// 			$sub = 'Registration Otp';
	// 			$config['protocol']    = 'smtp';
	// 			$config['smtp_host']    = 'ssl://smtp.googlemail.com';
	// 			$config['smtp_port']    = '465';
	// 			$config['smtp_timeout'] = '7';
	// 			$config['smtp_user']    = 'ftwoftesting@gmail.com';
	// 			$config['smtp_pass']    = 'MotivationS@1';
	// 			$config['charset']    = 'utf-8';
	// 			$config['newline']    = "\r\n";
	// 			$config['mailtype'] = 'text';
	// 			$config['validation'] = TRUE; // bool whether to validate email or not      
	// 			$this->load->library('email',$config);
	// 			$this->email->initialize($config);
	// 			$this->email->from('ftwoftesting@gmail.com','Tester');
	// 			$this->email->to($details['vEmail']); 
	// 			$this->email->subject($sub);
	// 			$this->email->message($msg);  
	// 			$this->email->send();
	// 			if($details){
	// 			$output = array('status' => 'Success', 'message' => 'Otp sent successfully','data'=>$details);
	// 			}else{
	// 			$output = array('status' => 'Error', 'message' => 'User not found');
	// 			}
	// 			$this->response($output);
	// 		}
	// 	}else
	// 	{
	// 		$output = array ('status' => 'Error', 'message' => 'Please enter input data');
    //         $this->response($output);
	// 	}
		
	// }
	public function product_category(){
		$category = $this->api_model->get_category();
		if($category){
			$output = array(
				'status'=> 'Success',
				'message'=> 'category list displayed successfully',
				'data'=>$category,
			);
			$this->response($output);
		}else{
				$output = array(
					'status'=> 'Error',
					'message'=> 'No date found',
				);
				$this->response($output);
			}
	}
	// public function add_product(){
	// 	$json_input = $this->_get_customer_post_values();
	// 	if(!empty($json_input)){
	// 		$product = array(
	// 			'iProductCategoryId'=> $json_input['category_id'],
	// 			'vProductName'=> $json_input['name'],
	// 			'iUnit'=> $json_input['unit'],
	// 			'vDescription'=> $json_input['description'],
	// 			'iPrice'=> $json_input['price'],
	// 		);
	// 		$id = $this->api_model->add_product($product);
	// 		$details = $this->api_model->product_by_id($id);
	// 		$output = array(
	// 			'status' => 'success',
	// 			'message' => 'product added successfully',
	// 			'data' => $details,
	// 		);
	// 		$this->response($output);
	// 	}else{
	// 		$output = array(
	// 			'status'=> 'error',
	// 			'message'=> 'please enter input data',
	// 		);
	// 		$this->response($output);
	// 	}
	// }
	public function add_distributor(){
		$json_input = $this->_get_customer_post_values();
		if(!empty($json_input)){
			$region_data = $this->api_model->get_headoffice_and_region($json_input['branch_id']);
			$headoffice = $this->api_model->get_branch_headoffice($json_input['branch_id']);
			$state = $this->api_model->get_headoffice_state($headoffice['iHeadOfficeId']);
			$distributor = array(
				'vCustomerName' => $json_input['name'],
				// 'vCompanyName'=> $json_input['company_name'],
				'vPhoneNumber'=> $json_input['phone'],
				'vAddress'=> $json_input['address'],
				//'iUserRoleId' => $json_input['role_id'],
				'iUserRoleId' => 5,
				'iBranchId' => $json_input['branch_id'],
				'iHeadOfficeId' => $headoffice['iHeadOfficeId'],
				'iStateId' => $state['iStateId'],
				'vEmail'=> $json_input['email'],
				// 'iSalesmanId'=> $json_input['salesman_id'],
				'iGradeId'=>$json_input['grade'],
				'dCreatedDate' => date('Y-m-d h:i:s'),
				//'iRegionId' =>$json_input['region_id'],
				'iRegionId' =>$region_data['iRegionId'],
			);
			$id = $this->api_model->add_distributor($distributor);
			// $category_id = str_replace('[','',$json_input['category_id']);
			// $category_id = str_replace(']','',$category_id);
			// $category_id = explode(",",$category_id);
			$category_all = $this->api_model->get_category();
			$insert_customer_category = array();
			foreach($category_all as $category){
				$customer_category = array();
				$customer_category['iCustomerId'] = $id;
				$customer_category['iCategoryId'] = $category['iCategoryId'];
				$insert_customer_category[] = $customer_category;
			}
			$this->api_model->insert_customer_category($insert_customer_category);
			$details = $this->api_model->distributor_by_id($id);
			$output = array(
				'status' => 'success',
				'message' => 'Customer added successfully',
				'data' => $details,
			);
			$this->response($output);
		}else{
			$output = array(
				'status'=> 'error',
				'message'=> 'please enter input data',
			);
			$this->response($output);
		}
	}
	// public function edit_product(){
	// 	$json_input = $this->_get_customer_post_values();
	// 	if(!empty($json_input)){
	// 		$product_id = $json_input['product_id'];
	// 		$product = array(
	// 			'iProductCategoryId'=> $json_input['category_id'],
	// 			'vProductName'=> $json_input['name'],
	// 			'iUnit'=> $json_input['unit'],
	// 			'vDescription'=> $json_input['description'],
	// 			'iPrice'=> $json_input['price'],
	// 		);
	// 		$this->api_model->update_product($product_id,$product);
	// 		$details = $this->api_model->product_by_id($json_input['product_id']);
	// 		$output = array(
	// 			'status'=> 'success',
	// 			'message'=> 'product updated successfully',
	// 			'data'=>$details,
	// 		);
	// 		$this->response($output);
	// 	}else{
	// 		$output = array(
	// 			'status'=> 'error',
	// 			'message'=> 'please enter input data',
	// 		);
	// 		$this->response($output);
	// 	}
	// }
	public function get_distributor(){
		$json_input = $this->_get_customer_post_values();
		if(!empty($json_input)){
			$details = array();
			$details[] = $this->api_model->distributor_by_id($json_input['customer_id']);
			if($details){
				$output = array(
					'status'=> 'success',
					'message'=> 'Customer Details',
					'data'=>$details,
				);
				$this->response($output);
			}
			else{
				$output = array(
					'status'=> 'Error',
					'message'=> 'No customer data found',
				);
				$this->response($output);
			}
		}
	}
	public function edit_distributor(){
		$json_input = $this->_get_customer_post_values();
		if(!empty($json_input)){
			$details = $this->api_model->distributor_by_id($json_input['customer_id']);
			if($details){
				$distributor_id = $json_input['customer_id'];
				$distributor = array(
					'vCustomerName' => $json_input['name'],
					// 'iUserRoleId'=> $json_input['type'],
					// 'vCompanyName'=> $json_input['company_name'],
					'vPhoneNumber'=> $json_input['phone'],
					'vAddress'=> $json_input['address'],
					'vEmail'=> $json_input['email'],
					// 'iSalesmanId'=> $json_input['salesman_id'],
					// 'iGradeId'=>$json_input['grade'],
					'dUpdatedDate' => date('Y-m-d'),
					// 'iRegionId' =>$json_input['region_id'],
				);
				$this->api_model->update_distributor($distributor_id,$distributor);
				$data = $this->api_model->distributor_by_id($json_input['customer_id']);
				$output = array(
					'status'=> 'success',
					'message'=> 'Customer updated successfully',
					'data'=>$data,
				);
				$this->response($output);
			}else{
				$output = array(
					'status'=> 'Error',
					'message'=> 'No customer data found',
				);
				$this->response($output);
			}
		}else{
			$output = array(
				'status'=> 'Error',
				'message'=> 'please enter input data',
			);
			$this->response($output);
		}
	}
	public function get_user_profile(){
		$json_input = $this->_get_customer_post_values();
		if(!empty($json_input)){
			if($json_input['type']=="Salesman")
			$profile = $this->api_model->get_profile_by_id($json_input['salesman_id'],$json_input['language']);
			else
			$profile = $this->api_model->distributor_by_id_login_profile($json_input['customer_id'],$json_input['language']);
			if($profile){
				$output = array(
					'status' => 'success',
					'message' => 'user details fetched successfully',
					'data'=>$profile
				);
				$this->response($output);
			}else{
				$output = array(
					'status'=> 'error',
					'message'=> 'no user found',
				);
				$this->response($output);
			}
		}else{
			$output = array(
				'status'=> 'error',
				'message'=> 'please enter input data',
			);
			$this->response($output);
		}
	}
	public function edit_profile(){
		$json_input = $this->_get_customer_post_values();
		if(!empty($json_input)){
			$id = $json_input['user_id'];
			$user = array(
				'vName' => $json_input['name'],
				'vUserName' => $json_input['username'],
				'iPhoneNumber' => $json_input['phone'],
				'vAddress' => $json_input['address'],
				'vEmail' => $json_input['email'],
			);
			$this->api_model->update_profile($id,$user);
			$details = $this->api_model->get_salesman_by_id($json_input['user_id']);
			$output = array(
				'status' => 'success',
				'message' => 'user updated successfully',
				'data'=>$details
			);
			$this->response($output);
		}else{
			$output = array(
				'status'=> 'error',
				'message'=> 'please enter input data',
			);
			$this->response($output);
		}
	}
	public function distributors_list(){
		$json_input = $this->_get_customer_post_values();
		if(!empty($json_input)){
			$data = $this->api_model->get_distributors($json_input['language']);
			if($data){
				$output = array(
					'status' => 'Success',
					'message' => 'Distributor list',
					'data'=>$data
				);
				$this->response($output);
			}else{
				$output = array(
					'status' => 'Error',
					'message' => 'No data found',
				);
				$this->response($output);
			}
		}else{
			$output = array(
				'status'=> 'error',
				'message'=> 'please enter input data',
			);
			$this->response($output);
		}
	}
	public function stock_list(){
		$json_input = $this->_get_customer_post_values();
		if(!empty($json_input)){
			$user = $this->api_model->get_customer_by_id($json_input['salesman_id']);
			$data = $this->api_model->get_stock($user['iBranchId'],$json_input['language'],$user['iGradeId']);
			if($data){
				$output = array(
					'status' => 'Success',
					'message' => 'Stock list',
					'data'=>$data
				);
				$this->response($output);
			}else{
				$output = array(
					'status' => 'Error',
					'message' => 'No data found',
				);
				$this->response($output);
			}
		}else{
			$output = array(
				'status'=> 'error',
				'message'=> 'please enter input data',
			);
			$this->response($output);
		}
	}
	
	public function stock_by_category(){
		$json_input = $this->_get_customer_post_values();
		if(!empty($json_input)){
			$user = $this->api_model->get_customer_by_id($json_input['salesman_id']);
			$data = $this->api_model->stock_by_categoryid($json_input['category_id'],$user['iBranchId'],$json_input['language'],$user['iGradeId']);
			if($data){
				$output = array(
					'status' => 'Success',
					'message' => 'Stock list',
					'data'=>$data
				);
				$this->response($output);
			}else{
				$output = array(
					'status' => 'Error',
					'message' => 'No data found',
				);
				$this->response($output);
			}
		}else{
			$output = array(
				'status'=> 'Error',
				'message'=> 'please enter input data',
			);
			$this->response($output);
		}
	}
	// public function stock_by_employee_category(){
	// 	$json_input = $this->_get_customer_post_values();
	// 	if(!empty($json_input)){
	// 		$user = $this->api_model->get_customer_by_id($json_input['salesman_id']);
	// 		$data = $this->api_model->stock_by_employee_categoryid($json_input['category_id'],$user['iBranchId'],$json_input['language']);
	// 		if($data){
	// 			$output = array(
	// 				'status' => 'Success',
	// 				'message' => 'Stock list',
	// 				'data'=>$data
	// 			);
	// 			$this->response($output);
	// 		}else{
	// 			$output = array(
	// 				'status' => 'Error',
	// 				'message' => 'No data found',
	// 			);
	// 			$this->response($output);
	// 		}
	// 	}else{
	// 		$output = array(
	// 			'status'=> 'Error',
	// 			'message'=> 'please enter input data',
	// 		);
	// 		$this->response($output);
	// 	}
	// }
	public function get_cities(){
		$city = $this->api_model->get_cities();
		if($city){
			$output = array(
				'status' => 'Success',
				'message' => 'City list',
				'data'=>$city
			);
			$this->response($output);
		}else{
			$output = array(
				'status' => 'Error',
				'message' => 'No data found',
			);
			$this->response($output);
		}
	}
	public function get_head_offices(){
		$head_offices = $this->api_model->get_head_offices();
		if($head_offices){
			$output = array(
				'status' => 'Success',
				'message' => 'head ofiices list',
				'data'=>$head_offices
			);
			$this->response($output);
		}else{
			$output = array(
				'status' => 'Error',
				'message' => 'No data found',
			);
			$this->response($output);
		}
	}
	
	public function get_branches(){
		$branch = $this->api_model->get_branches();
		if($branch){
			$output = array(
				'status' => 'Success',
				'message' => 'branch list',
				'data'=>$branch
			);
			$this->response($output);
		}else{
			$output = array(
				'status' => 'Error',
				'message' => 'No data found',
			);
			$this->response($output);
		}
	}
	// public function get_sales_order__(){
    //     $data = $this->_get_customer_post_values();
	// 	if(!empty($data)){
	// 		$user = $this->api_model->get_salesman_by_id($data['salesman_id']);
	// 		// print_r($user);exit;
	// 		if($user){
	// 			$product = $data['product_list'];
	// 			$sales[] = array();
	// 			for($i=0;$i<count($product);$i++) {
    // 				// $this->api_model->update_stock_quantity($user['iHeadOfficeId'],$$user['iBranchId'],$product[$i]['catagory_id'],$product[$i]['product_id'],$product[$i]['productunit_id'],$net_qty[$i]);
	// 				$sales['iHeadOfficeId'] = $user['iHeadOfficeId'];
	// 				$sales['iBranchId'] = $user['iBranchId'];
	// 				$sales['iCatagoryId'] = $product[$i]['catagory_id'];
	// 				$sales['iProductId'] = $product[$i]['product_id'];
	// 				$sales['iProductUnitId'] = $product[$i]['productunit_id'];
	// 				$sales['vSalesOrderNo'] = $data['sales_order_number'];
	// 				$sales['iSalesmanId'] = $data['salesman_id'];
	// 				$sales['iCustomerId'] = $data['customer_id'];
	// 				$sales['fNetCostwithoutGST'] = $product[$i]['product_amount'];
	// 				$sales['fNetCost'] = $data['net_cost'];
	// 				$sales['fNetQty'] = $data['net_quantity'];
	// 				$sales['dCreatedDate'] = $data['date'];
	// 			}
	// 			$id = $this->api_model->get_sales($sales);
	// 			// $details = $this->api_model->sales_order_by_id($id);
	// 			$output = array ('status' => 'success', 'message' => 'sales order added successfully');
	// 			$this->response($output);
	// 		}else{
	// 			$output = array ('status' => 'Error', 'message' => 'No user found');
	// 			$this->response($output);
	// 		}
	// 	}else{
	// 		$output = array ('status' => 'Error', 'message' => 'Please enter input data');
	// 		$this->response($output);
	// 	}
	// }
	public function get_user_roles(){
		$user_roles = $this->api_model->get_user_roles();
		if($user_roles){
			$output = array(
				'status' => 'Success',
				'message' => 'User role list',
				'data'=>$user_roles
			);
			$this->response($output);
		}else{
			$output = array(
				'status' => 'Error',
				'message' => 'No data found',
			);
			$this->response($output);
		}
	}
	public function today_sale_order(){
        $data = $this->_get_customer_post_values();
		if(!empty($data)){
			$sales = $this->api_model->get_today_sales($data['salesman_id'],$data['language']);
			if($sales){
				$output = array(
					'status' => 'Success',
					'message' => 'Today sale order list',
					'data'=>$sales
				);
				$this->response($output);
			}else{
				$output = array(
					'status' => 'Error',
					'message' => 'No data found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function sales_by_status(){
        $data = $this->_get_customer_post_values();
		if(!empty($data)){
			if($data['salesman_id']!="")
			$salesman_id = $data['salesman_id'];
			else
			$salesman_id = $data['customer_id'];
			$customer_id = $data['customer_id'];
			$type = $data['Type'];
			$status = $data['status'];
			$lang = $data['language'];
			if($type=='salesman')
			$details = $this->api_model->get_sales_by_status($salesman_id,$status,$lang,$type);
			else
			$details = $this->api_model->get_sales_by_status($customer_id,$status,$lang,$type);
			if($details){
				$output = array(
					'status' => 'Success',
					'message' => 'sales order list',
					'data'=>$details
				);
				$this->response($output);
			}else{
				$output = array(
					'status' => 'Error',
					'message' => 'No Sale orders found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function customer_by_id(){
        $data = $this->_get_customer_post_values();
		if(!empty($data)){
				$details = $this->api_model->distributor_by_id($data['customer_id']);
			if($details){
				$output = array(
					'status' => 'Success',
					'message' => 'Customer details',
					'data'=>$details
				);
				$this->response($output);
			}else{
				$output = array(
					'status' => 'Error',
					'message' => 'No data found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function branch_product(){
        $data = $this->_get_customer_post_values();
		if(!empty($data)){
			$product = $this->api_model->product_by_id($data['product_id']);
			if($product){
				$details = $this->api_model->get_product_branch_data($data['product_id']);
				$output = array(
					'status' => 'Success',
					'message'=>'Product available for the branches',
					'data' => $details,
				);
				$this->response($output);
			}else{
				$output = array(
					'status' => 'Error',
					'message' => 'No data found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function all_product_branch(){
		$data = $this->api_model->get_all_product_branch_data();
		if($data){
			$output = array(
				'status' => 'Success',
				'message'=>'Product available for the branches',
				'data' => $data,
			);
			$this->response($output);
		}else{
			$output = array(
				'status' => 'Error',
				'message' => 'No data found',
			);
			$this->response($output);
		}
	}
	// public function get_sales_order(){
    //     $data = $this->_get_customer_post_values();
	// 	// print_r($data);exit;
	// 	if(!empty($data)){
	// 		$user = $this->api_model->get_salesman_by_id($data['salesman_id']);
	// 		$date = $data['date'];
	// 		$head_office = $user['iHeadOfficeId'];
	// 		$branch = $user['iBranchId'];
	// 		$so_number = $data['sales_order_number'];
	// 		$net_cost = $data['net_cost'];
	// 		$net_quantity = $data['net_quantity'];
	// 		$salesman_id = $data['salesman_id'];
	// 		$customer_id = $data['customer_id'];
	// 		$product_list = $data['product_list'];
			
	// 		$sales_order = array(
	// 			'vSalesOrderNo' => $so_number,
	// 			'iHeadOfficeId' => $head_office,
	// 			'iBranchId' => $branch,
	// 			'iSalesmanId' => $salesman_id,
	// 			'iCustomerId' => $customer_id,
	// 			'fNetQty' => $net_quantity,
	// 			'fNetCost' => $net_cost,
	// 			'dCreatedDate' => $date,
	// 		);
	// 		$salesorderid = $this->api_model->get_sales_order_data($sales_order);
	// 		$product = $data['product_list'];
	// 		// print_r(count($product));exit;
	// 		$insert_sales = array();
	// 		for($i=0;$i<count($product);$i++) {
	// 			$product_details = $this->api_model->product_by_id($product[$i]['product_id']);
	// 			$sales = array();
	// 			$sales['iSalesOrderId'] = $salesorderid;
	// 			$cgst = $product_details['CGST'];
	// 			$sgst = $product_details['SGST'];
	// 			$igst = $product_details['IGST'];
	// 			// $total_cgst_price = ($net_cost * $cgst)/100;
	// 			// $total_sgst_price = ($net_cost * $sgst)/100;
	// 			// $total_igst_price = ($net_cost * $igst)/100;
	// 			// $gst_price = $total_cgst_price + $total_sgst_price;
	// 			// $without_gst_price = $product[$i]['product_amount'] - $gst_price;
	// 			$sales['iCatagoryId'] = $product[$i]['catagory_id'];
	// 			$sales['iProductId'] = $product[$i]['product_id'];
	// 			$sales['iProductUnitId'] = $product[$i]['productunit_id'];				
	// 			$sales['iDeliveryCostperQTY'] = $product[$i]['product_amount'];
	// 			$sales['iDeliveryQTY'] = $product[$i]['pices'];
	// 			$sales['IGST'] = $igst;
	// 			$sales['CGST'] = $cgst;
	// 			$sales['SGST'] = $sgst;
	// 			$sales['iDeliverySubTotal'] = $product[$i]['pices'] * $product[$i]['product_amount'];
	// 			$insert_sales[] = $sales;
	// 		}
	// 		$this->api_model->get_sales_order_details($insert_sales);
	// 		$curl = curl_init();
	// 	curl_setopt_array($curl, array(
	// 	CURLOPT_URL => 'https://test.cashfree.com/api/v2/cftoken/order',
	// 	CURLOPT_RETURNTRANSFER => true,
	// 	CURLOPT_ENCODING => '',
	// 	CURLOPT_MAXREDIRS => 10,
	// 	CURLOPT_TIMEOUT => 0,
	// 	CURLOPT_FOLLOWLOCATION => true,
	// 	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	// 	CURLOPT_CUSTOMREQUEST => 'POST',
	// 	CURLOPT_POSTFIELDS =>'{
	// 		"orderId": '.$salesorderid.',
	// 		"orderAmount": '.$net_cost.',
	// 		"orderCurrency": "INR"
	// 	}',
	// 	CURLOPT_HTTPHEADER => array(
	// 		'x-client-id: 13441255ac24923378af546060214431',
	// 		'x-client-secret: bfb65d854ef6576333023ee26bccdbff90ac6d0b',
	// 		'Content-Type: text/plain'
	// 	),
	// 	));
	// 	$response = curl_exec($curl);
	// 	// curl_close($curl);
	// 		$response = json_decode($response);
	// 		$details = $this->api_model->get_sales_by_id($salesorderid);
	// 		$output = array(
	// 			'status' =>'Success',
	// 			'message' => 'Sales order added successfully',
	// 			'data' => $details,
	// 			'order_token' => $response
	// 		);
	// 		$this->response($output);
	// 	}else{
	// 		$output = array ('status' => 'Error', 'message' => 'Please enter input data');
	// 		$this->response($output);
	// 	}
	// }
	public function get_sales_order(){
        $data = $this->_get_customer_post_values();
		if(!empty($data)){
			$user = $this->api_model->get_customer_by_id($data['customer_id']);
			$headoffice = $this->api_model->get_branch_headoffice($user['iBranchId']);
			$state = $this->api_model->get_headoffice_state($headoffice['iHeadOfficeId']);
			$sales_order_number = $this->api_model->get_order_number();
			$order_date = $data['date'];
			$year = date("Y");
			$so_number = "$year-".($sales_order_number['iOrderNumber'] + 1);
			$date = date("Y-m-d h:i:s");
			$od = str_replace("/","-",$order_date);
			$head_office = $headoffice['iHeadOfficeId'];
			$branch = $user['iBranchId'];
			$net_cost = $data['net_cost'];
			$net_quantity = $data['net_quantity'];
			if($data['salesman_id']!="")
			$salesman_id = $data['salesman_id'];
			else
			$salesman_id = $data['customer_id'];
			$customer_id = $data['customer_id'];
			$product_list = $data['product_list'];
			$address = $data['Address_'];
			$payment_method = $data['payment_method'];
			$delivery_date = date("Y-m-d",strtotime($od));
			$sales_order = array(
				'vSalesOrderNo' => $so_number,
				'iHeadOfficeId' => $head_office,
				'iBranchId' => $branch,
				'iSalesmanId' => $salesman_id,
				'iCustomerId' => $customer_id,
				'vAddress' => $address,
				'vShippingAddress' => $address,
				'fNetQty' => $net_quantity,
				'fNetCost' => $net_cost,
				'dCreatedDate' => $date,
				'iCreatedBy'=> $salesman_id,
				'vPayemntMethod' => $payment_method,
				'dOrderedDate' => $delivery_date
			);
			$salesorderid = $this->api_model->get_sales_order_data($sales_order);
			$product = $data['product_list'];
			$this->api_model->update_order_number();
			// Authorisation details.
			$username = "smsgateway2k22@gmail.com";
			$hash = "af6af7187849c32125a21b3c57dc5fd6cea5c96111d5b0bb5baf1e008bb5d9cd";
			// Config variables. Consult http://api.textlocal.in/docs for more info.
			$test = "0";
			// Data for text message. This is the text message data.
			$sender = "CICSTO"; // This is who the message appears to be from.
			$numbers = $user['vPhoneNumber']; // A single number or a comma-seperated list of numbers	
			$message = "Dear Customer,Your Order ".$so_number." has been placed successfully, Thank you for shopping at CoolinCool Store.";
			// 612 chars or less
			// A single number or a comma-seperated list of numbers
			$message = urlencode($message);
			$data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
			$ch = curl_init('https://api.textlocal.in/send/?');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch); // This is the result from the API
			curl_close($ch);
			
			$insert_sales = array();
			for($i=0;$i<count($product);$i++) {
				$this->api_model->update_stock($product[$i]['product_id'],$branch,$product[$i]['productunit_id'],$product[$i]['pices'],$stock="delete");
				$product_details = $this->api_model->product_by_id($product[$i]['product_id']);
				$sales = array();
				$sales_gst_amt = array();
				$sales['iSalesOrderId'] = $salesorderid;
				$cgst = $product_details['CGST'];
				$sgst = $product_details['SGST'];
				$igst = $product_details['IGST'];
				$sales['iCatagoryId'] = $product[$i]['catagory_id'];
				$sales['iProductId'] = $product[$i]['product_id'];
				$sales['iProductUnitId'] = $product[$i]['productunit_id'];				
				$sales['iDeliveryCostperQTY'] = $product[$i]['product_amount'];
				$sales['iDeliveryQTY'] = $product[$i]['pices'];
				$sales['IGST'] = $igst;
				$sales['CGST'] = $cgst;
				$sales['SGST'] = $sgst;
				$sales['iDeliverySubTotal'] = $product[$i]['pices'] * $product[$i]['product_amount'];
				$insert_sales[] = $sales;
				// Gst Amount Calculation
				if($state['iStateId']=='2'){
				$cgst_amt = $cgst / 100 * $net_cost;
				$sgst_amt = $sgst / 100 * $net_cost;
				$igst_amt = '0.00';
				$gst_amount = $cgst_amt + $sgst_amt;
				$taxable_amt = $net_cost - $gst_amount;
				}
				else{
				$cgst_amt = '0.00';
				$sgst_amt = '0.00';
				$igst_amt = $igst / 100 * $net_cost;
				$gst_amount = $igst_amt;
				$taxable_amt = $net_cost - $gst_amount;
				}
				$sales_gst_amt['fNetCostwithoutGST'] = $taxable_amt;
				$sales_gst_amt['IGST'] = $gst_amount;
				$sales_gst_amt['CGST'] = $gst_amount/2;
				$sales_gst_amt['SGST'] = $gst_amount/2;
			    $this->api_model->update_sales_order_gst_amount($sales_gst_amt,$salesorderid);
			}
			$this->api_model->get_sales_order_details($insert_sales);
			
			// Token Creation Part
			if($payment_method == "Online Payment"){
				$curl = curl_init();
				curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://test.cashfree.com/api/v2/cftoken/order',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS =>'{
					"orderId": "'.$so_number.'",
					"orderAmount":'.$net_cost.',
					"orderCurrency": "INR"
				  }',
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json',
					'x-api-version: 2022-01-01',
					'x-client-id: 13550426a70dab0593cb962933405531',
					'x-client-secret: fa4ea189de3421e78d2c69de846e2abefeb316ab'
				),
				));
				$response = curl_exec($curl);
				curl_close($curl);
				// echo $response;
				$response_token = json_decode($response,true);
			}else{
			$response_token = '';
			}
			$details = $this->api_model->get_sales_by_id($salesorderid);
			$output = array(
				'status' =>'Success',
				'message' => 'Sales order added successfully',
				'data' => $details,
				'order_token_response'=>$response_token
			);
			$this->response($output);
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function update_payment_status()
	{
		$data = $this->_get_customer_post_values();
		if(!empty($data)){
		$orderId = $data["orderId"];
		$orderAmount = $data["orderAmount"];
		$referenceId = $data["referenceId"];
		$txStatus = $data["txStatus"];
		$paymentMode = $data["paymentMode"];
		$txMsg = $data["txMsg"];
		$txTime = $data["txTime"];
		$signature = $data["signature"];
		$secretkey = 'fa4ea189de3421e78d2c69de846e2abefeb316ab';
		$response = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;
		$hash_hmac = hash_hmac('sha256', $response, $secretkey, true);
		$computedSignature = base64_encode($hash_hmac);
		$sales_id_no = $this->api_model->get_sales_order_id_by_no($orderId);
		if ($signature == $computedSignature && $txStatus=="SUCCESS") {
			$update = array(
				'vPayemntStatus' => $txStatus,
				'dTransactionTime' => $txTime,
				'vReferenceId' => $referenceId,
				'vPaymentMode' => $paymentMode,
				'vSignature' => $signature
			);
			$inc['vType'] = 'Gst Code';
            $inc['code'] = 'RECQ000' . $sales_id_no['iSalesOrderId'];
			$recipt_bill = array(
				'receipt_id' => $txStatus
			);
            $this->api_model->update_increment($inc, $inc['vType']);
			$reciept_insert = array(
				'receipt_id' => $sales_id_no['iSalesOrderId'],
				'receipt_no' => $inc['code'],
				'bill_amount' => $orderAmount,
				'total_paid_amt' => $orderAmount,
				'remarks' => $referenceId,
				'created_date' => date("Y-m-d h:i:s")
			);
			$this->api_model->insert_reciept_bill($reciept_insert);
		} else {
			$update = array(
				'vPayemntStatus' => 'FAILED',
				'vPaymentMode' => 'Online Payment',
				'eDeliveryStatus' => 'Cancelled',
				'eDeliveryStatus_Tamil' => 'ரத்து செய்யப்பட்டது'
			);
			$products = $this->api_model->get_salesorder_product_details($sales_id_no['iSalesOrderId'],$data['language']);
		for($i=0;$i<count($products);$i++) {
			$this->api_model->update_stock($products[$i]['iProductId'],$data['iBranchId'],$products[$i]['iProductUnitId'],$products[$i]['iDeliveryQTY'],$stock="add");
		}
		}
		$this->api_model->update_sales_payment_status($sales_id_no['iSalesOrderId'],$update);
		$sales = $this->api_model->get_sales_by_id($sales_id_no['iSalesOrderId']);
			if($sales){
				$output = array(
					'status' =>'Success',
					'meassage'=>'Sales payment status updated successfully',
					'data'=> $sales
				);
				$this->response($output);
			}else{
				$output = array(
					'status' =>'Error',
					'meassage'=>'No sales found',
					'data'=> $sales
				);
				$this->response($output);
			}
			
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function branch_based_category(){
        $data = $this->_get_customer_post_values();
		if(!empty($data)){
			$category = $this->api_model->get_category_by_branch($data['branchid'],$data['language'],$data['customer_id']);
			if($category){
				$output = array(
					'status' =>'Success',
					'message' => 'Branch based category listed successfully',
					'data' => $category,
				);
				$this->response($output);
			}else{
				$output = array(
					'status' =>'Failure',
					'message' => 'No data found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function dashboard_page(){
		$data = $this->api_model->get_dashboard_data();
		if(!empty($data)){
			$output = array(
				'status' =>'Success',
				'message' => 'Dashboard images listed successfully',
				'data' => $data,
			);
			$this->response($output);
		}else{
			$output = array(
				'status' =>'Failure',
				'message' => 'No data found',
			);
			$this->response($output);
		}
	}
	public function most_sold_product_category(){
        $data = $this->_get_customer_post_values();
		$sold_product_category = $this->api_model->get_most_sold_category($data);
		if($sold_product_category){
			$output = array(
				'status' => 'Success',
				'message' => 'most sold category list',
				'data'=>$sold_product_category
			);
			$this->response($output);
		}else{
			$output = array(
				'status' => 'Error',
				'message' => 'No data found',
			);
			$this->response($output);
		}
	}
	
	public function distributor_type(){
        $customer_type=array(
            array("retailer_type"=>"Customer"),
            array("retailer_type"=>"Retailer"),
            array("retailer_type"=>"Distributor"),
		);
        $output = array(
			'status' => 'Success',
			'message' => 'distributor types',
			'data'=>$customer_type
		);
		$this->response($output);
    }
	public function offer_products(){
        $data = $this->_get_customer_post_values();
		if(!empty($data)){
			$user = $this->api_model->get_customer_by_id($data['customer_id']);
			$offer_products = $this->api_model->get_offer_products($data,$user['iBranchId']);
			
			if(!empty($offer_products)){
			if($data['type'] == "Recent selling"){
				$output = array(
					'status' => 'Success',
					'message' => 'Recent selling products',
					'data' => $offer_products,
				);
			}
			elseif($data['type'] == "Most recent"){
				$output = array(
					'status' => 'Success',
					'message' => 'Recently added products',
					'data' => $offer_products,
				);
			}
			else{
				$output = array(
					'status' => 'Error',
					'message' => 'Invalid Type',
				);
			}
				$this->response($output);
			}else{
				$output = array(
					'status' => 'Error',
					'message' => 'No data found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function grade_list(){
		$grade = $this->api_model->get_grades();
		if ($grade){
			$output = array(
				'status' => 'Success',
				'message' => 'Recently added products',
				'data' => $grade,
			);
			$this->response($output);
		}else{
			$output = array(
				'status' => 'Error',
				'message' => 'No data found',
			);
			$this->response($output);
		}
	}
	// public function get_category_by_customer(){
	// 	$data = $this->_get_customer_post_values();
	// 	if(!empty()){}
	// }
	public function customer_userrole(){
		$user_roles = $this->api_model->get_customer_userroles();
		if($user_roles){
			$output = array(
				'status' => 'Success',
				'message' => 'Customer role list',
				'data' => $user_roles
			);
			$this->response($output);
		}else{
			$output = array(
				'status' => 'Error',
				'message' => 'No data found',
			);
			$this->response($output);
		}
	}
	public function get_category_by_customer(){
        $data = $this->_get_customer_post_values();
		if(!empty($data)){
			$result = $this->api_model->get_category_by_customer_id($data);
			if($result){
				$output = array(
					'status' => 'Success',
					'message' => 'Customer category list',
					'data' => $result
				);
			$this->response($output);
			}else{
				$category = $this->api_model->get_category();
				$output = array(
					'status' => 'Success',
					'message' => 'All Category list',
					'data' => $category
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function customer_login(){
        $data = $this->_get_customer_post_values();
		if(!empty($data)){
			$mobile_number = $data['mobile_number'];
			$login = $this->api_model->check_customer_login($mobile_number);
			if($login){
				$update = array();
				$otp = $this->api_model->generateNumericOTP('4');
				$update_arr = array(
					'iOtpCode' => $otp,
					'tOtpVerify' => 0,
					'tLoginStatus' => 0,
				);
				$this->api_model->update_distributor($login['iCustomerId'],$update_arr);
				
				$details = $this->api_model->distributor_by_id($login['iCustomerId']);
				$output = array ('status' => 'Success', 'message' => 'Otp send successfully','data'=>$details);
				$this->response($output);
			}else{
				$output = array ('status' => 'Error', 'message' => 'Mobile number not registered yet');
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function get_customers_by_branch(){
        $data = $this->_get_customer_post_values();
		if(!empty($data)){
			$result = $this->api_model->get_customers_by_salesman_branch($data['salesman_id'],$data['language']);
			if($result){
				$output = array(
					'status'=> 'Success',
					'message'=> 'Customers list',
					'data'=> $result
				);
				$this->response($output);
			}else{
				$output = array(
					'status'=> 'Error',
					'message'=> 'No Customers found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function get_branch_list(){
		$result = $this->api_model->get_branches();
		if($result){
			$output = array(
				'status'=> 'Success',
				'message'=> 'Branches list',
				'data'=> $result
			);
			$this->response($output);
		}else{
			$output = array(
				'status'=> 'Error',
				'message'=> 'No Branches found',
			);
			$this->response($output);
		}
	}
	public function category_based_products(){
        $data = $this->_get_customer_post_values();
		if(!empty($data)){
			$user = $this->api_model->get_salesman_by_id($data['salesman_id']);
			$result = $this->api_model->products_by_category($data['category_id'],$data['language'],$user['iBranchId']);
			if($result){
				$output = array(
					'status'=> 'Success',
					'message'=> 'Category based Products list',
					'data'=> $result
				);
				$this->response($output);
			}else{
				$output = array(
					'status'=> 'Error',
					'message'=> 'No Products found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function product_details_by_id(){
		$data = $this->_get_customer_post_values();
		if(!empty($data)){
			$user = $this->api_model->get_customer_by_id($data['salesman_id']);
			$result = $this->api_model->get_product_details($user['iGradeId'],$user['iBranchId'],$data['iProductId'],$data['iProductUnitId'],$data['language']);
			if($result){
				$output = array(
					'status'=> 'Success',
					'message'=> 'Product details',
					'data'=> $result
				);
				$this->response($output);
			}else{
				$output = array(
					'status'=> 'Error',
					'message'=> 'No Product found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function subcategory_by_category(){
		$data = $this->_get_customer_post_values();
		if(!empty($data)){
			$user = $this->api_model->get_customer_by_id($data['customer_id']);
			$result = $this->api_model->get_subcategory_by_category($data['customer_id'],$user['iBranchId'],$data['category_id'],$data['language']);
			if($result){
				$output = array(
					'status'=> 'Success',
					'message'=> 'Subcategory details',
					'data'=> $result
				);
				$this->response($output);
			}else{
				$output = array(
					'status'=> 'Error',
					'message'=> 'No Subcategory found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	// Shop From Top Category
	public function shop_from_top_category(){
		$data = $this->_get_customer_post_values();
		if(!empty($data)){
			$user = $this->api_model->get_customer_by_id($data['customer_id']);
			$result = $this->api_model->get_subcategory_by_category($data['customer_id'],$data['branchid'],$data['category_id'],$data['language'],$top='top');
			if($result){
				$output = array(
					'status'=> 'Success',
					'message'=> 'Subcategory details',
					'data'=> $result
				);
				$this->response($output);
			}else{
				$output = array(
					'status'=> 'Error',
					'message'=> 'No Subcategory found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function product_by_subcategory(){
		$data = $this->_get_customer_post_values();
		if(!empty($data)){
			if($data['type'] == "Customer"){
				$user = $this->api_model->get_customer_by_id($data['salesman_id']);
			}
			if($data['type'] == "Employee"){
				$user = $this->api_model->get_salesman_by_id($data['salesman_id']);
			}
			$result = $this->api_model->get_product_by_subcategory($data['subcategoryid'],$user['iBranchId'],$data['language']);
			if($result){
				$output = array(
					'status'=> 'Success',
					'message'=> 'Product list',
					'data'=> $result
				);
				$this->response($output);
			}else{
				$output = array(
					'status'=> 'Error',
					'message'=> 'No Product found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function get_app_version(){
		$data = $this->_get_customer_post_values();
		if(!empty($data)){
			$version = $this->api_model->get_current_version($data);
			if($version){
				$output = array(
					'status'=>'Success',
					'message'=>'APP Version details',
					'data'=>$version
				);
			$this->response($output);
			}else{
				$output = array(
					'status'=> 'Error',
					'message'=> 'Version Details not found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function get_offer_list()
	{
		$data = $this->_get_customer_post_values();
		if(!empty($data)){
			$user = $this->api_model->get_customer_by_id($data['customer_id']);
			$result = $this->api_model->get_offer_details($user['iBranchId']);
			if($result){
				$output = array(
					'status'=>'Success',
					'message'=>'Offer details',
					'data'=>$result
				);
				$this->response($output);
			}else{
				$output = array(
					'status'=>'Error',
					'message'=>'No offers found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function subcategory_by_category_v1(){
		$data = $this->_get_customer_post_values();
		if(!empty($data)){
			$user = $this->api_model->get_customer_by_id($data['customer_id']);
			$result = $this->api_model->get_subcategory_by_category_v1($user['iBranchId'],$data['customer_id'],$data['language']);
			if($result){
				$output = array(
					'status'=> 'Success',
					'message'=> 'Product details',
					'data'=> $result
				);
				$this->response($output);
			}else{
				$output = array(
					'status'=> 'Error',
					'message'=> 'No Category found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	public function stock_by_category_v1(){
		$json_input = $this->_get_customer_post_values();
		if(!empty($json_input)){
			if($json_input['type']=="category"){
				// $result = $this->api_model->get_user_categories($json_input['customer_id']);
				// $category_arr = array();
				// foreach($result as $uscat){
				// 	$category_arr[] = $uscat['iCategoryId'];
				// }
				// $categoryid = $category_arr;//implode(",",$category_arr);
				$categoryid = $json_input['category_id'];
			}
			
			elseif($json_input['type']=="subcategory"){
				$categoryid = $json_input['subcategory_id'];
			}
			else{
				$output = array(
					'status' => 'Error',
					'message' => 'Invalid Type',
				);
			}
			$user = $this->api_model->get_customer_by_id($json_input['customer_id']);
			$data = $this->api_model->stock_by_categoryid_v1($categoryid,$user['iBranchId'],$json_input['language'],$user['iGradeId'],$json_input['type'],$json_input['sorting']);
			if($data){
				$output = array(
					'status' => 'Success',
					'message' => 'Stock list',
					'data'=>$data
				);
				$this->response($output);
			}else{
				$output = array(
					'status' => 'Error',
					'message' => 'No data found',
				);
				$this->response($output);
			}
		}else{
			$output = array(
				'status'=> 'Error',
				'message'=> 'please enter input data',
			);
			$this->response($output);
		}
	}
	function cashfree_api(){
		$curl = curl_init();
		curl_setopt_array($curl, [
		CURLOPT_URL => "https://api.cashfree.com/pg/orders",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "{\"order_id\":\"string\",\"order_amount\":10.15,\"order_currency\":\"INR\",\"customer_details\":{\"customer_id\":\"7112AAA812234\",\"customer_email\":\"john@cashfree.com\",\"customer_phone\":\"9908734801\",\"customer_bank_account_number\":\"1518121112\",\"customer_bank_ifsc\":\"CITI0000001\",\"customer_bank_code\":3333},\"order_meta\":{\"return_url\":\"https://b8af79f41056.eu.ngrok.io?order_id={order_id}&order_token={order_token}\",\"notify_url\":\"https://b8af79f41056.eu.ngrok.io/webhook.php\"},\"order_expiry_time\":\"2021-07-02T10:20:12+05:30\",\"order_note\":\"Test order\",\"order_tags\":{\"additionalProp\":\"string\"},\"order_splits\":[{\"vendor_id\":\"Vendor01\",\"amount\":\"100.1\"}]}",
		CURLOPT_HTTPHEADER => [
			"Accept: application/json",
			"Content-Type: application/json",
			"x-api-version: 2022-01-01",
			"x-client-id: 1845849604dc4459356f614090485481",
			"x-client-secret: e3f72441a8bddaf8fc3024efde0d842b8d8558f4"
		],
		]);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
		echo "cURL Error #:" . $err;
		} else {
		echo $response;
		}
	}
	// Get UserDocument
	public function get_user_document(){
		$json_input = $this->_get_customer_post_values();
		if(!empty($json_input)){
			$details = array();
			$details[] = $this->api_model->distributor_by_id($json_input['user_id']);
			if($details){
				$output = array(
					'status'=> 'success',
					'message'=> 'Customer Details',
					'data'=>$details,
				);
				$this->response($output);
			}
			else{
				$output = array(
					'status'=> 'Error',
					'message'=> 'No customer data found',
				);
				$this->response($output);
			}
		}
	}
	// Add Document info
	public function add_document_company_details(){
		$json_input = $this->input->post();
		if(!empty($json_input)){
		$randomText = rand(10000,10000000);
		$uploadPath = "./uploads/";
		$allowedTypes = "jpg|gif|png|jpeg";
		$maxSize = '10000000000';
		$overwrite = FALSE;
		$extension = pathinfo($_FILES['id_proof']['name'], PATHINFO_EXTENSION);
		$extensioncomp = pathinfo($_FILES['company_id_proof']['name'], PATHINFO_EXTENSION);
		$fileName =  'UID_'.$randomText.'.'.$extension;
		$fileName_company =  'CID_'.$randomText.'.'.$extensioncomp;
        
		$this->load->library('upload');
		$files = $_FILES;

		$config = array();
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = '0';
        $config['overwrite']     = FALSE;
        $config['file_name'] = $fileName;
        
        $_FILES['id_proof']['name']= $files['id_proof']['name'];
        $_FILES['id_proof']['type']= $files['id_proof']['type'];
        $_FILES['id_proof']['tmp_name']= $files['id_proof']['tmp_name'];
        $_FILES['id_proof']['error']= $files['id_proof']['error'];
        $_FILES['id_proof']['size']= $files['id_proof']['size'];    
        
        $this->upload->initialize($config);
        $this->upload->do_upload('id_proof');
        $upload_data = $this->upload->data();

		$config['file_name'] = $fileName_company;
		$_FILES['company_id_proof']['name']= $files['company_id_proof']['name'];
        $_FILES['company_id_proof']['type']= $files['company_id_proof']['type'];
        $_FILES['company_id_proof']['tmp_name']= $files['company_id_proof']['tmp_name'];
        $_FILES['company_id_proof']['error']= $files['company_id_proof']['error'];
        $_FILES['company_id_proof']['size']= $files['company_id_proof']['size'];    
        
        $this->upload->initialize($config);
        $this->upload->do_upload('company_id_proof');
        $upload_data = $this->upload->data();


			$userdocument = array(
				'iCustomerId' => $json_input['customer_id'],
				'iBranchId'=> $json_input['branchid'],
				'vName'=> $json_input['name'],
				'vPanNumber'=> $json_input['pan_number'],
				'vIdProof' => $fileName,
				'vAddress' => $json_input['address'],
				'vEmail' => $json_input['email'],
				'vPhoneNumber'=> $json_input['phone_number'],
				'vCompanyName'=> $json_input['company_name'],
				'vGstNumber'=> $json_input['gst_number'],
				'vCompanyIdProof'=> $fileName_company,
				'vCompanyAddress'=> $json_input['company_address'],
				'vCompanyEmail'=> $json_input['company_email'],
				'vCompanyPhoneNumber'=> $json_input['company_phone_number'],
				'eStatus' => '1',
				'dCreatedDate' => date('Y-m-d h:i:s'),
			);
			$documentid = $this->api_model->add_user_document($userdocument);
			$details = $this->api_model->userdocument_by_id($documentid);
			$output = array(
				'status' => 'success',
				'message' => 'Document details added successfully',
				'data' => $details,
			);
			$this->response($output);
		}else{
			$output = array(
				'status'=> 'error',
				'message'=> 'please enter input data',
			);
			$this->response($output);
		}
	}
	// Update Document Info
	public function edit_document_company_details(){
		$json_input = $this->input->post();
		if(!empty($json_input)){
			$randomText = rand(10000,10000000);
		$uploadPath = "./uploads/";
		$allowedTypes = "jpg|gif|png|jpeg";
		$maxSize = '10000000000';
		$overwrite = FALSE;
		$extension = pathinfo($_FILES['id_proof']['name'], PATHINFO_EXTENSION);
		$extensioncomp = pathinfo($_FILES['company_id_proof']['name'], PATHINFO_EXTENSION);
		$fileName =  'UID_'.$randomText.'.'.$extension;
		$fileName_company =  'CID_'.$randomText.'.'.$extensioncomp;
        
		$this->load->library('upload');
		$files = $_FILES;

		$config = array();
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = '0';
        $config['overwrite']     = FALSE;
        $config['file_name'] = $fileName;
        
        $_FILES['id_proof']['name']= $files['id_proof']['name'];
        $_FILES['id_proof']['type']= $files['id_proof']['type'];
        $_FILES['id_proof']['tmp_name']= $files['id_proof']['tmp_name'];
        $_FILES['id_proof']['error']= $files['id_proof']['error'];
        $_FILES['id_proof']['size']= $files['id_proof']['size'];    
        
        $this->upload->initialize($config);
        $this->upload->do_upload('id_proof');
        $upload_data = $this->upload->data();
		
		$config['file_name'] = $fileName_company;
		$_FILES['company_id_proof']['name']= $files['company_id_proof']['name'];
        $_FILES['company_id_proof']['type']= $files['company_id_proof']['type'];
        $_FILES['company_id_proof']['tmp_name']= $files['company_id_proof']['tmp_name'];
        $_FILES['company_id_proof']['error']= $files['company_id_proof']['error'];
        $_FILES['company_id_proof']['size']= $files['company_id_proof']['size'];    
        
        $this->upload->initialize($config);
        $this->upload->do_upload('company_id_proof');
        $upload_data = $this->upload->data();
			$details = $this->api_model->userdocument_by_id($json_input['customer_id']);
			if($details){
				$useracc_id = $details['iCustomerId'];
				$useraccount = array(
					'vName'=> $json_input['name'],
					'vPanNumber'=> $json_input['pan_number'],
					'vIdProof' => $fileName,
					'vAddress' => $json_input['id_proof'],
					'vEmail' => $json_input['email'],
					'vPhoneNumber'=> $json_input['phone_number'],
					'vCompanyName'=> $json_input['company_name'],
					'vGstNumber'=> $json_input['gst_number'],
					'vCompanyIdProof'=> $fileName_company,
					'vCompanyAddress'=> $json_input['company_address'],
					'vCompanyEmail'=> $json_input['company_email'],
					'vCompanyPhoneNumber'=> $json_input['company_phone_number'],
					'eStatus' => '1',
					'dUpdatedDate' => date('Y-m-d h:i:s'),
				);
				$this->api_model->update_document_account($useracc_id,$useraccount);
				$data = $this->api_model->userdocument_by_id($json_input['customer_id']);
				$output = array(
					'status'=> 'success',
					'message'=> 'Document details updated successfully',
					'data'=>$data,
				);
				$this->response($output);
			}else{
				$output = array(
					'status'=> 'Error',
					'message'=> 'No documnet data found',
				);
				$this->response($output);
			}
		}else{
			$output = array(
				'status'=> 'Error',
				'message'=> 'please enter input data',
			);
			$this->response($output);
		}
	}
	// Search products list
	public function search_list_products()
	{
		$data = $this->_get_customer_post_values();
		if(!empty($data)){
			$user = $this->api_model->get_customer_by_id($data['customer_id']);
			$result = $this->api_model->get_search_products($data['customer_id'],$user['iBranchId'],$user['iGradeId'],$data['language']);
			if($result){
				$output = array(
					'status'=>'Success',
					'message'=>'Product details',
					'data'=>$result
				);
				$this->response($output);
			}else{
				$output = array(
					'status'=>'Error',
					'message'=>'No Products found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	// Get Salesorder details
	public function get_sales_order_details_by_id()
	{
		$data = $this->_get_customer_post_values();
		if(empty($data['action'])){
		if(!empty($data)){
			$result = $this->api_model->get_salesorder_details($data['sales_order_id'],$data['language']);
			$products = $this->api_model->get_salesorder_product_details($data['sales_order_id'],$data['language']);
			if($result || $products){
				$output = array(
					'status'=>'Success',
					'message'=>'Product details',
					'data'=>$result,
					'product_data'=>$products
				);
				$this->response($output);
			}else{
				$output = array(
					'status'=>'Error',
					'message'=>'No Products found',
				);
				$this->response($output);
			}
		}else{
			$output = array ('status' => 'Error', 'message' => 'Please enter input data');
			$this->response($output);
		}
	}
	else{
		$delete = $this->api_model->cancel_salesorder($data['sales_order_id'],$data['action']);
		$user = $this->api_model->get_customer_by_id($data['customer_id']);
		$branch = $user['iBranchId'];
		$result = $this->api_model->get_salesorder_details($data['sales_order_id'],$data['language']);
		$products = $this->api_model->get_salesorder_product_details($data['sales_order_id'],$data['language']);
		for($i=0;$i<count($products);$i++) {
			$this->api_model->update_stock($products[$i]['iProductId'],$branch,$products[$i]['iProductUnitId'],$products[$i]['iDeliveryQTY'],$stock="add");
		}
		if($delete){
			$output = array(
				'status'=>'Success',
				'message'=>'Order Cancelled',
				'data'=>$result,
				'product_data'=>$products
			);
			$this->response($output);
		}else{
			$output = array(
				'status'=>'Error',
				'message'=>'Order could not be cancelled'
			);
			$this->response($output);
		}
	}
}
}
