<?php
class User_model extends CI_Model{

	function upload_users($data){

		$this->db->insert_batch('user', $data); 
	}

}
?>