<?php
class User_model extends CI_Model{

	function upload_user($data){

		$this->db->insert('user', $data); 
	}

	function check_user($email){

		$this->db->where('email',$email);

		$query = $this->db->get('user');

		$output = $query->row();

		return $output;
	}

}
?>