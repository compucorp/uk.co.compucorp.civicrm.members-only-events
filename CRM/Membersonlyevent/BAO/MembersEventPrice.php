<?php

class CRM_Membersonlyevent_BAO_MembersEventPrice extends CRM_Membersonlyevent_DAO_MembersEventPrice {

  /**
   * Create a new MembersEventPrice based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Membersonlyevent_DAO_MembersEventPrice|NULL
   *
   */
  public static function create($params) {
    $className = 'CRM_Membersonlyevent_DAO_MembersEventPrice';
    $entityName = 'MembersEventPrice';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }
  
  public static function getPriceValue($eventId){
  	
    $priceSetId = CRM_Price_BAO_PriceSet::getFor('civicrm_event', $eventId, NULL);
    if ($priceSetId) {
      if ($isQuick = CRM_Core_DAO::getFieldValue('CRM_Price_DAO_PriceSet', $priceSetId, 'is_quick_config')) {
        $priceField = CRM_Core_DAO::getFieldValue('CRM_Price_DAO_PriceField', $priceSetId, 'id', 'price_set_id');
        $results = array();
        $priceFieldOptions = CRM_Price_BAO_PriceFieldValue::getValues($priceField, $results, 'weight', true);
      }
    }
    
    return $results;
  }
  
  public static function getMemberPrice($params){
  	
    $dao = new CRM_Membersonlyevent_DAO_MembersEventPrice();
	$dao->copyValues($params);
    $dao->find();
    $results = array();
 	
    while ($dao->fetch()) {
      CRM_Core_DAO::storeValues($dao, $results[(int) $dao->id]);
    }
    return $results;
  }
}
