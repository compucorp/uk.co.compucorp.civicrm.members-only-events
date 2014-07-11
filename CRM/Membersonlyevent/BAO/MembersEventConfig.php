<?php

class CRM_Membersonlyevent_BAO_MembersEventConfig extends CRM_Membersonlyevent_DAO_MembersEventConfig {

    /**
   * takes an associative array and creates a resource object
   *
   * the function extract all the params it needs to initialize the create a
   * resource object. the params array could contain additional unused name/value
   * pairs
   *
   * @param array $params (reference ) an assoc array of name/value pairs
   * @param array $ids    the array that holds all the db ids
   *
   * @return object CCRM_Membersonlyevent_BAO_MembersEventConfig object
   * @access public
   * @static
   */
  static function create(&$params) {
    if(!CRM_Utils_Array::value('id', $params)){
      //make sure we don't create new record
      return;
    }
    $dao = new CRM_Membersonlyevent_DAO_MembersEventConfig();
    $dao->copyValues($params);
    return $dao->save();
  }

  static function getConfig(){
    $dao = new CRM_Membersonlyevent_DAO_MembersEventConfig();
    $dao->find();
    $config = array();
    while ($dao->fetch()) {
      CRM_Core_DAO::storeValues($dao, $config);
      return $config;
    }
  }


}
		
	