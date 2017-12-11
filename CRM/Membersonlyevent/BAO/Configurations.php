<?php

class CRM_Membersonlyevent_BAO_Configurations extends CRM_Membersonlyevent_DAO_Configurations {

  /**
   * Configurations record id in the entity table
   * which will always point to the first and only record.
   */
  const CONFIGURATIONS_RECORD_ID = 1;

  /**
   * Updates the extension system-wide
   * configurations.
   *
   * @param array $params
   *
   * @return CRM_Membersonlyevent_DAO_Configurations
   */
  public static function updateConfigs(array $params) {
    // always set the id to ensure we don't create another record
    // in this table.
    $params['id'] = self::CONFIGURATIONS_RECORD_ID;

    $configurations = new self();
    $configurations->copyValues($params);
    $configurations->save();

    return $configurations;
  }

  /**
   * Gets the extension system-wide configurations
   *
   * @return CRM_Membersonlyevent_DAO_Configurations
   */
  public static function getConfigs() {
    $configurations = new self();
    $configurations->find(TRUE);

    return $configurations;
  }
}
