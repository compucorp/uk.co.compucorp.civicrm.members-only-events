<?php

class CRM_MembersOnlyEvent_Configurations {

  /**
   * @const String
   * The settings group name in which all extension settings are stored.
   */
  const SETTINGS_GROUP_NAME = 'members_only_event_extension_settings';

  /**
   * Gets the extension system-wide configurations
   *
   * @return array
   */
  public static function get() {
    $settingFields = civicrm_api3('Setting', 'getfields',
      array(
        'filters' => array('group' => self::SETTINGS_GROUP_NAME),
      )
    )['values'];

    $configurations = civicrm_api3('Setting', 'get',
      array('return' => array_keys($settingFields['values']), 'sequential' => 1));

    return $configurations['values'];
  }
}
