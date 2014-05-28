<?php

class CRM_Membersonlyevent_BAO_MembersEventPrice extends CRM_Membersonlyevent_DAO_MembersEventPrice {

  /**
   * Create a new MembersEventPrice based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Membersonlyevent_DAO_MembersEventPrice|NULL
   *
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
  } */
}
