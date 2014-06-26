<?php

class CRM_Membersonlyevent_Utils_Calls{
		
	public $_firstname;
	
	public $_lastname;
	
	public $_displayname;
	
	public $_email;
	
	public function getContact($Id){
		$currentContact = civicrm_api3('contact', 'get', array('id' => $Id));
		if($currentContact['count']!==0){dpm($Id);
			$this->_firstname = $currentContact["values"][$Id]["first_name"];
			$this->_lastname = $currentContact["values"][$Id]["last_name"];
			$this->_displayname = $currentContact["values"][$Id]["display_name"];
			$this->_email = $currentContact["values"][$Id]["email"];
			return 1;
		}else{dpm($Id);
			$this->_firstname = null;
			$this->_lastname = null;
			$this->_displayname = null;
			$this->_email = null;
			return 0;
		}
	}
}
