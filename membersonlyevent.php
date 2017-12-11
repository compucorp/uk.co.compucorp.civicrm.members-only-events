<?php

//----------------------------------------------------------------------------//
//                             File Organization                              //
//                                                                            //
// To keep this file organized, it is split into 2 sections: CiviCRM Hooks    //
// and Helper Functions. The former has all the civicrm hooks implementations //
// used by this extension, whereas the latter, has all the helper functions   //
// used by those hooks.                                                       //
//                                                                            //
// If you're adding new things here, please keep this organization in mind.   //
//                                                                            //
//----------------------------------------------------------------------------//

use CRM_MembersOnlyEvent_BAO_MembersOnlyEvent as MembersOnlyEvent;

require_once 'membersonlyevent.civix.php';


//----------------------------------------------------------------------------//
//                           CiviCRM Hooks                                    //
//----------------------------------------------------------------------------//

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function membersonlyevent_civicrm_config(&$config) {
  _membersonlyevent_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function membersonlyevent_civicrm_xmlMenu(&$files) {
  _membersonlyevent_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function membersonlyevent_civicrm_install() {
  return _membersonlyevent_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function membersonlyevent_civicrm_uninstall() {
  return _membersonlyevent_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function membersonlyevent_civicrm_enable() {
  return _membersonlyevent_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function membersonlyevent_civicrm_disable() {
  return _membersonlyevent_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function membersonlyevent_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _membersonlyevent_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function membersonlyevent_civicrm_managed(&$entities) {
  return _membersonlyevent_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function membersonlyevent_civicrm_caseTypes(&$caseTypes) {
  _membersonlyevent_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function membersonlyevent_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _membersonlyevent_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_permission().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_permission
 */
function membersonlyevent_civicrm_permission(&$permissions) {
  $prefix = ts('Members-Only Event') . ': ';
  $permissions['members only event registration'] = $prefix . ts('Can register for members-only events irrespective of membership status');
}

/**
 * Implements hook_civicrm_tabset().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_tabset/
 */
function membersonlyevent_civicrm_tabset($tabsetName, &$tabs, $context) {
  // check if the tabset is 'Manage Event' page
  if ($tabsetName == 'civicrm/event/manage') {
    if (empty($context['event_id'])) {
      return;
    }

    $eventID = $context['event_id'];
    $url = CRM_Utils_System::url(
      'civicrm/event/manage/membersonlyevent',
      'reset=1&id=' . $eventID . '&action=update&component=event');

    $tab['membersonlyevent'] = array(
      'title' => ts('Members only event settings'),
      'link' => $url,
      'valid' => _membersonlyevent_is_tab_valid($eventID),
      'active' => TRUE,
    );

    //Insert this tab into position 4 (after `Online Registration` tab)
    $tabs = array_merge(
      array_slice($tabs, 0, 4),
      $tab,
      array_slice($tabs, 4)
    );
  }
}

/**
 * Implementation of hook_civicrm_pageRun
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_pageRun/
 *
 * Handler for pageRun hook.
 */
function membersonlyevent_civicrm_pageRun(&$page) {
  $f = '_' . __FUNCTION__ . '_' . get_class($page);
  if (function_exists($f)) {
    $f($page);
  }
}

/**
 * Alter the event registration and check for the correct permissions.
 */
function membersonlyevent_civicrm_alterContent(&$content, $context, $tplName, &$object) {
}

function membersonlyevent_civicrm_navigationMenu(&$params) {
  _membersonlyevent_add_configurations_menu($params);
}

//----------------------------------------------------------------------------//
//                               Helper Functions                             //
//----------------------------------------------------------------------------//

/**
 * Checks if the members-only settings tab
 * should be valid or not. Currently it is valid
 * only if the event is members-only event and
 * online registration is enabled.
 *
 * @param int $eventID
 *
 * @return bool
 *
 */
function _membersonlyevent_is_tab_valid($eventID) {
  $isOnlineRegistrationEnabled = FALSE;
  $event = civicrm_api3('Event', 'get', array(
    'sequential' => 1,
    'return' => array('is_online_registration'),
    'id' => $eventID,
  ));
  if (!empty($event['values'][0]['is_online_registration'])) {
    $isOnlineRegistrationEnabled = TRUE;
  }

  $membersOnlyEvent = MembersOnlyEvent::getMembersOnlyEvent($eventID);

  if ($isOnlineRegistrationEnabled && $membersOnlyEvent) {
    return TRUE;
  }

  return FALSE;
}

/**
 * Adds `Members-Only Event Extension Configurations` menu
 * item under `Administer` top-level menu item.
 *
 * @param $params
 */
function _membersonlyevent_add_configurations_menu(&$params) {
  $administerMenuId = CRM_Core_DAO::getFieldValue('CRM_Core_BAO_Navigation', 'Administer', 'id', 'name');
  if ($administerMenuId) {
    // get the maximum key under administer menu
    $maxAdminMenuKey = max(array_keys($params[$administerMenuId]['child']));
    $nextAdminMenuKey = $maxAdminMenuKey+1;
    $params[$administerMenuId]['child'][$nextAdminMenuKey] =  array(
      'attributes' => array(
        'label' => ts('Members-Only Event Extension Configurations'),
        'name' => 'membersonlyevent_configurations',
        'url' => 'civicrm/admin/membersonlyevent',
        'permission' => 'administer CiviCRM,access CiviEvent',
        'operator' => null,
        'separator' => 1,
        'parentID' => $administerMenuId,
        'navID' => $nextAdminMenuKey,
        'active' => 1
      ),
      'child' => null
    );
  }
}
