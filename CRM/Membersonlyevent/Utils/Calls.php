<?php

class CRM_Membersonlyevent_Utils_Calls{
		
	public $_firstname;
	
	public $_lastname;
	
	public $_displayname;
	
	public $_email;
  
  public $_organizationname;
	
	/*public function getMember($Id){
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
	}*/
	
	static function getMember() {
    $cId = CRM_Utils_Type::escape($_POST['member_Id'], 'Integer');
   
    $query = "
    	SELECT cc.id as id, 
    	  cc.first_name as first_name, 
    	  cc.last_name as last_name, 
    	  cc.display_name as display_name,
    	  cc.organization_name as organization_name,
    	  ce.email as email
    	FROM civicrm_contact cc
    	left join civicrm_email ce on
                ce.contact_id = cc.id
    	WHERE cc.id = {$cId}
    	";
    $dao = CRM_Core_DAO::executeQuery($query);

    while ($dao->fetch()) {
      // make sure we return tag name entered by user only if it does not exists in db
      if ($cId == $dao->id) {

      // escape double quotes, which break results js
      	$contact = array(
        	'id' => $dao->id,
        	'firstname' => $dao->first_name,
        	'lastname' => $dao->last_name,
        	'displayname' => $dao->display_name,
        	'email' => $dao->email,
        	'organizationname' => $dao->organization_name,
        	'error' => 0
      	);
		break;
	  }else{
	  	$contact = array(
        	'id' => null,
        	'firstname' => null,
        	'lastname' => null,
        	'displayname' => null,
        	'email' => null,
        	'organizationname' => null,
        	'error' => 1
        );
	  }
    }

    echo json_encode($contact);
    CRM_Utils_System::civiExit();
  }
}
