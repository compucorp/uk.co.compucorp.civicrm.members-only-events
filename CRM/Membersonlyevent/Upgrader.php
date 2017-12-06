<?php

/**
 * Collection of upgrade steps
 */
class CRM_Membersonlyevent_Upgrader extends CRM_Membersonlyevent_Upgrader_Base {


  /**
   * creates membersonlyevent_event_membership_type table
   *
   * @return TRUE on success
   */
  public function upgrade_1000() {
    $this->ctx->log->info('Applying upgrader #1000 : creating `membersonlyevent_event_membership_type` table');

    CRM_Utils_File::sourceSQLFile(CIVICRM_DSN, $this->extensionDir . '/sql/members_only_events_2_install.sql');

    return TRUE;
  }
}
