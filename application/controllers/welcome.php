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

        	

        	$uploaded_data = 0;
        	$not_uploaded_data = 0;

        	foreach ($data as $user_data) {
        		
        		$email = $user_data['C'];
        		$name = $user_data['C'];
        		$phone_number = "";

        		if (isset($user_data['B'])) {
        			
        			$phone_number = $user_data['B'];
        		}

        		$user_checker = $this->user_model->check_user($email);
        		if (! $user_checker) {
        			
        			$insert_data = array(
        				'email' => $email,
        				'name' => $name,
        				'phone_number' => $phone_number,
        				);

        			$this->user_model->upload_user($insert_data);
        			$uploaded_data ++;
        		}
        		else{

        			$not_uploaded_data ++;
        		}
        	}

        	echo "uploaded_data ::: ".$uploaded_data;
        	echo "<br>";
        	echo "not_uploaded_data ::: ".$not_uploaded_data;


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
		
		

		return $arr_data;
	}
}

