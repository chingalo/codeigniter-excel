<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	
	function index()	{

		$this->load->library('excel');
		$this->load->view('welcome_message');
	}


	function import_data(){

		$config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'xls|xlsx';

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload()){
               
        //retrun errors
        $error = array('error' => $this->upload->display_errors());

       // $this->load->view('welcome_message',array('error' => $error));
        print_r($error);

        }
        else{

        	$uploaded_data = $this->upload->data(); 
            $file_name = $uploaded_data['file_name'];

            $file_path = $uploaded_data['full_path'];
        	$data = $this->uploader($file_name);

        	$this->user_model->upload_users($data);
        	echo "uploaded successful";
        }       
	}


	function uploader($file){

		$file = './uploads/'.$file;
		date_default_timezone_set('Africa/Dar_es_Salaam');
 
		//load the excel library
		$this->load->library('excel');
		 
		//read file from path
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		 
		//get only the Cell Collection
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

		$members_list = array();
		$counter = 0;
		 
		//extract to a PHP readable array format
		foreach ($cell_collection as $cell) {
		    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
		    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
		    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

		    
		 
		    //header will/should be in row 1 only. of course this can be modified to suit your need.
		    if ($row > 1) {
		        $arr_data[$row][$column] = $data_value;	       

		    } 


		}
		$member = array();

		foreach ($arr_data as $data) {
			$member['name'] = $data['A'];
			$member['phone_number'] = $data['B'];
			$member['email'] = $data['C'];

			$members_list[$counter] = $member;
			$counter ++;
		}
		
		//$data['values'] = $arr_data;

		return $members_list;
	}
}

