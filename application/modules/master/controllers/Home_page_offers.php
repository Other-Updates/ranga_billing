<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_page_offers extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('home_page_offers_model'); 
    }

	public function index()
	{
        $data['title'] = 'Home page app';
        $offers = $this->home_page_offers_model->get_offer_list();
        
        if(!empty($offers)){
        $data['offer_details'] = $this->home_page_offers_model->get_offer_list();
        $data['product'] = $this->home_page_offers_model->all_products();
        $data['category'] = $this->home_page_offers_model->all_categories();
        $data['subcategory'] = $this->home_page_offers_model->all_sub_categories();
        }
        $data['categories'] = $this->home_page_offers_model->get_category();
        $data['branch'] = $this->home_page_offers_model->get_branch();
        $this->template->write_view('content', 'home_page_offers', $data);
        $this->template->render();
	}

    // public function get_offer_images(){
    //     $input = $this->input->post();
    //     // echo "<pre>"; print_r($input);exit;
    //     $config = array();
    //     $config['upload_path'] = './uploads/';
    //     $config['allowed_types'] = 'gif|jpg|png';
    //     $config['max_size']      = '0';
    //     $config['overwrite']     = FALSE;
    //     $config['file_name'] = 'Main offer -'.rand(10000,10000000);

    //     $this->load->library('upload');
    //     $files = $_FILES;
    //     $count = count($_FILES['main_banner']['name']);
    //     $file_name = array();
    //     for($i=0; $i<$count; $i++){           
    //         $_FILES['main_banner']['name']= $files['main_banner']['name'][$i];
    //         $_FILES['main_banner']['type']= $files['main_banner']['type'][$i];
    //         $_FILES['main_banner']['tmp_name']= $files['main_banner']['tmp_name'][$i];
    //         $_FILES['main_banner']['error']= $files['main_banner']['error'][$i];
    //         $_FILES['main_banner']['size']= $files['main_banner']['size'][$i];    
    
    //         $this->upload->initialize($config);
    //         $this->upload->do_upload('main_banner');
    //         $upload_data = $this->upload->data();
    //         $file_name[] = $upload_data['file_name'];
    //     }
    //     $image = implode(",",$file_name);

    //     $main_banner = array(
    //         'iCategoryId'=> $input['main_banner_category'],
    //         'vType' => $input['type1'],
    //         'vImages' => $image,

    //     );
    //     $this->home_page_offers_model->insert_dashboard_data($main_banner);
    // }

    public function get_selected_type_records(){
        $type = $this->input->post('type');
        $data = $this->home_page_offers_model->get_type_based_data($type);
        echo json_encode($data);
        exit;
    }

    public function add_offer_list()
    {   
        $input = $this->input->post();
        // echo "<pre>";print_r($input);exit;
        
        $offer_details_delete_id = $input['offer_details_deleted_id'];
        $offer_delete_id = explode(",",$offer_details_delete_id);
        foreach($offer_delete_id as $od){
            $this->home_page_offers_model->remove_offer_details($od);
        }
      
        $files = $_FILES;

        for($i=0;$i<count($input['offer_name']);$i++){
            
            $config = array();
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size']      = '0';
            $config['overwrite']     = FALSE;
            $config['file_name'] = 'offer_img-'.rand(10000,10000000);
            
            $this->load->library('upload');
           
            if(!empty($files['offer_image']['name'][$i])){
            
            $_FILES['offer_image']['name']= $files['offer_image']['name'][$i];
            $_FILES['offer_image']['type']= $files['offer_image']['type'][$i];
            $_FILES['offer_image']['tmp_name']= $files['offer_image']['tmp_name'][$i];
            $_FILES['offer_image']['error']= $files['offer_image']['error'][$i];
            $_FILES['offer_image']['size']= $files['offer_image']['size'][$i];
            
            $this->upload->initialize($config);
            $this->upload->do_upload('offer_image');
            $upload_data = $this->upload->data();
                $file_name = $upload_data['file_name'];
            }else{
                $file_name = $input['old_offer_img'][$i];
            }
            $offer_type = 'offer_value_'.$input['offer_value_name_id'][$i];
            
            $branch_id = 'branch_id_'.$input['offer_value_name_id'][$i];
            $branch_id = implode(',',$input[$branch_id]);
            // $input['branch_id'];exit;
            $exp_offer = explode(',',$input['offer_type_id'][$i]);
            $offer_typr_id = $exp_offer[0];
            $offer_item_name = $exp_offer[1];

            if($input[$offer_type] == "Flat"){
                $offer_value = $input['offer_price'][$i];
            }if($input[$offer_type] == "Percent"){
                $offer_value = $input['offer_percent'][$i];
            }
            if(!empty($input['offer_id'][$i])){
                $offers_update_arr[] = array(
                    'vOfferName'=> $input['offer_name'][$i],
                    'vOfferBadge'=>$input['offer_badge'][$i],
                    'dFromDate'=> date("Y-m-d",strtotime($input['from_date'][$i])),
                    'dToDate'=> date("Y-m-d",strtotime($input['to_date'][$i])),
                    'vImage'=> $file_name,
                    'eType'=> $input['offer_type'][$i],
                    'iOfferTypeId'=> $offer_typr_id,
                    'vOfferTypeName'=>$offer_item_name,
                    'iBranchId'=> $branch_id,
                    'vOfferType'=> $input[$offer_type],
                    'vOfferValue'=> $offer_value,
                    'iOfferId' => $input['offer_id'][$i]
                );
            }else{
                $offers_insert_arr[] = array(
                    'vOfferName'=> $input['offer_name'][$i],
                    'vOfferBadge'=>$input['offer_badge'][$i],
                    'dFromDate'=> date("Y-m-d",strtotime($input['from_date'][$i])),
                    'dToDate'=> date("Y-m-d",strtotime($input['to_date'][$i])),
                    'vImage'=> $file_name,
                    'eType'=> $input['offer_type'][$i],
                    'iOfferTypeId'=> $offer_typr_id,
                    'vOfferTypeName'=>$offer_item_name,
                    'iBranchId'=> $branch_id,
                    'vOfferType'=> $input[$offer_type],
                    'vOfferValue'=> $offer_value,
                );
            }
        }
     
        if(!empty($offers_insert_arr)){
            $this->home_page_offers_model->insert_offers($offers_insert_arr);
        }
        if(!empty($offers_update_arr)){
            $this->home_page_offers_model->update_offers($offers_update_arr);
        }
        redirect(base_url('master/home_page_offers'));
    }
}