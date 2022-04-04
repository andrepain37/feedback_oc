<?php
class ModelExtensionModuleFeedback extends Model {
	public function setFeedback($data) {
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "feedback SET 
		name = '".$this->db->escape($data['name'])."',  
		phone = '".$this->db->escape($data['phone'])."',
		email = '".$this->db->escape($data['email'])."'");
		
	}
}