<?php

require_once 'membersonlyevent.civix.php';

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
 * hook_civicrm_permission(&$permissions)
 * This hook is called to allow custom permissions to be defined. Available in 4.3 or 4.4.
 */
function membersonlyevent_civicrm_permission(&$permissions) {
    
  $prefix = ts('CiviEvent') . ': '; // name of extension or module
  $permissions = array(
    'members only event registration' => $prefix . ts('Can register for Members events'),
  );
  
}

function membersonlyevent_civicrm_tabset($tabsetName, &$tabs, $context) {
    
  // Check if the tab set is Event manage
  if ($tabsetName == 'civicrm/event/manage') {
    if (!empty($context)) {
        
      $eventID = $context['event_id'];
      $url = CRM_Utils_System::url( 'civicrm/event/manage/membersonlyevent',
        "reset=1&snippet=5&force=1&id=$eventID&action=update&component=event" );
      
     // Add a new Members only event tab along with url
     $tab['membersonlyevent'] = array(
        'title' => ts('Members only event settings'),
        'link' => $url,
        'valid' => 1,
        'active' => 1,
        'current' => false,
      );
    }
    else {
      $tab['membersonlyevent'] = array(
      'title' => ts('Members only event settings'),
        'url' => 'civicrm/event/manage/membersonlyevent',
      );
    }
 
    //Insert this tab into position 4
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
 * Handler for pageRun hook.
 */
function membersonlyevent_civicrm_pageRun(&$page) {
  $f = '_' . __FUNCTION__ . '_' . get_class($page);
  if (function_exists($f)) {
    $f($page);
  }
}

/**
 * Callback for event info page
 *
 * Inserts "Login Now and the Membership Signup buttons to the event page"
 * 
 */
function _membersonlyevent_civicrm_pageRun_CRM_Event_Page_EventInfo(&$page) {
  
  // Search for the Members Only Event object by the Event ID
  $members_only_event = CRM_Membersonlyevent_BAO_MembersOnlyEvent::getMembersOnlyEvent($page->_id);
  
  // Get the current user ID and current event ID
  $session = CRM_Core_Session::singleton();
  $currentEventID = $members_only_event->event_id;
  $userID = $session->get('userID');
  $durationCheck = true;
  $config = CRM_Membersonlyevent_BAO_MembersEventConfig::getConfig();
  
  $isParticipant = FALSE;
  $purchaseRestriction = TRUE;
  $purchaseRecords = civicrm_api3('Participant', 'get', array('event_id' => $currentEventID, 'contact_id' => $userID, 'sequential' => 1));
  $isParticipant = $purchaseRecords['values'][0]['participant_status_id'] == 1;
  $purchaseRestriction = $config['registration_restriction'];
  
  $purchaseForOther = !($purchaseRestriction&& !$isParticipant);
  
  if($config['duration_check'] == 1&&$userID){
  	$durationCheck = false;
    if(is_object($members_only_event)){
  	  $currentEventID = $page->_id;
	  $currentEvent = civicrm_api3('event', 'get', array('id' => $currentEventID));
	  $memberships = civicrm_api3('membership', 'get', array('contact_id' => $userID));
      foreach($memberships['values'] as $key => $membership){
  	    if($membership['end_date'] >= $currentEvent['values'][$currentEventID]['event_start_date']){
  	  	  $durationCheck = true;
  	    }
      }
    }
  }
  
  // Hide register now button, if the event is members only event and user has no permissions to register for the event
  if (is_object($members_only_event) && $members_only_event->members_event_type !== 1) {
  	
    if (!CRM_Core_Permission::check('members only event registration')||
    (CRM_Core_Permission::check('members only event registration')&&!$durationCheck)){
        
      CRM_Core_Region::instance('event-page-eventinfo-actionlinks-top')->update('default', array(
        'disabled' => TRUE,
      ));
      
      CRM_Core_Region::instance('event-page-eventinfo-actionlinks-bottom')->update('default', array(
        'disabled' => TRUE,
      ));
      
      if (!$userID) {
        $url = CRM_Utils_System::url('user/login', '',
          //array('reset' => 1, 'id' => $members_only_event->contribution_page_id),
          FALSE, // absolute?
          NULL, // fragment
          TRUE, // htmlize?
          TRUE // is frontend?
        );
        
        $button_text = ts('Log in to register');

        $snippet = array(
          'template' => 'CRM/Event/Page/members-event-button.tpl',
          'button_text' => $button_text,
          'position' => 'top',
          'url' => $url,
          'weight' => -10,
        );
       
        CRM_Core_Region::instance('event-page-eventinfo-actionlinks-top')->add($snippet);

        $snippet['position'] = 'bottom';
        $snippet['weight'] = -10;
        
        CRM_Core_Region::instance('event-page-eventinfo-actionlinks-bottom')->add($snippet);
          
      }else{
	  
	  if(!CRM_Core_Permission::check('members only event registration')){
		$button_text = ts('Become a member to register for this event');
	  }else if((CRM_Core_Permission::check('members only event registration')&&!$durationCheck)){
		$button_text = ts('Extend your membership to register for this event');
	  }
    	
      $url = CRM_Utils_System::url('civicrm/contribute/transact',
        array('reset' => 1, 'id' => $members_only_event->contribution_page_id),
        FALSE, // absolute?
        NULL, // fragment
        TRUE, // htmlize?
        TRUE // is frontend?
      );

      $snippet = array(
        'template' => 'CRM/Event/Page/members-event-button.tpl',
        'button_text' => $button_text,
        'position' => 'top',
        'url' => $url,
        'weight' => -10,
      );
      
      CRM_Core_Region::instance('event-page-eventinfo-actionlinks-top')->add($snippet);

      $snippet['position'] = 'bottom';
      $snippet['weight'] = -10;
           
      CRM_Core_Region::instance('event-page-eventinfo-actionlinks-bottom')->add($snippet);
      }
      
    }else{
    	if($members_only_event->members_event_type == 3){
  			CRM_Core_Region::instance('event-page-eventinfo-actionlinks-top')->update('default', array(
        		'disabled' => TRUE,
      		));
      
        	CRM_Core_Region::instance('event-page-eventinfo-actionlinks-bottom')->update('default', array(
        		'disabled' => TRUE,
      		));
		
			if($purchaseForOther){
		  		$url = CRM_Utils_System::url('civicrm/event/register',
        			array('reset' => 1, 'id' => $currentEventID, 'cid' => 0),
        			FALSE, // absolute?
        			NULL, // fragment
        			TRUE, // htmlize?
        			TRUE // is frontend?	
        		);
			}else{
		  		$url = CRM_Utils_System::url('civicrm/event/register',
        			array('reset' => 1, 'id' => $currentEventID),
        			FALSE, // absolute?
        			NULL, // fragment
        			TRUE, // htmlize?
        			TRUE // is frontend?	
        		);
			}
			$snippet = array(
        			'template' => 'CRM/Event/Page/members-event-button.tpl',
        			'button_text' => "Register Now",
        			'position' => 'top',
        			'url' => $url,
        			'weight' => -10,
      			);
      
     		CRM_Core_Region::instance('event-page-eventinfo-actionlinks-top')->add($snippet);

      		$snippet['position'] = 'bottom';
      		$snippet['weight'] = -10;
           
      		CRM_Core_Region::instance('event-page-eventinfo-actionlinks-bottom')->add($snippet);
  	    }
    }
  }
}

/**
 * Alter the event registration and check for the correct permissions.
 */
function membersonlyevent_civicrm_alterContent(&$content, $context, $tplName, &$object) {
  
  // If we are on windows enviroment the tplName is generated by backslashes so we need to convert it to slashes  
  $tplName = preg_replace('/\\\\/', '/', $tplName);
  
  $members_only_event = CRM_Membersonlyevent_BAO_MembersOnlyEvent::getMembersOnlyEvent($page->_id);
  $session = CRM_Core_Session::singleton();
  $currentEventID = $members_only_event->event_id;
  $userID = $session->get('userID');
  $durationCheck = true;
  $config = CRM_Membersonlyevent_BAO_MembersEventConfig::getConfig();
  if($config['duration_check'] == 1&&$userID){
  	$durationCheck = false;
    if(is_object($members_only_event)){
  	  $currentEventID = $page->_id;
	  $currentEvent = civicrm_api3('event', 'get', array('id' => $currentEventID));
	  $memberships = civicrm_api3('membership', 'get', array('contact_id' => $userID));
      foreach($memberships['values'] as $key => $membership){
  	    if($membership['end_date'] >= $currentEvent['values'][$currentEventID]['event_start_date']){
  	  	  $durationCheck = true;
  	    }
      }
    }
  }

  if($tplName == "CRM/Event/Form/Registration/Register.tpl" && $context == "form") {
      
    // Search for the Members Only Event object by the Event ID
    $members_only_event = CRM_Membersonlyevent_BAO_MembersOnlyEvent::getMembersOnlyEvent($_GET['id']);
          
    if (is_object($members_only_event) && $members_only_event->members_event_type !== 1) {
       
      if (!CRM_Core_Permission::check('members only event registration')||
    (CRM_Core_Permission::check('members only event registration')&&!$durationCheck)){
        $content = ts('<p>You are not allowed to register for this event!</p>');      
      }
      
    }
    
  }
}

function membersonlyevent_civicrm_navigationMenu( &$params ) {

  // get the id of Administer Menu
  $administerMenuId = CRM_Core_DAO::getFieldValue('CRM_Core_BAO_Navigation', 'Administer', 'id', 'name');
  // skip adding menu if there is no administer menu
  if ($administerMenuId) {
    // get the maximum key under administer menu
    $maxAdminMenuKey = max( array_keys($params[$administerMenuId]['child']));
    $nextAdminMenuKey = $maxAdminMenuKey+1;
    $params[$administerMenuId]['child'][$nextAdminMenuKey] =  array(
        'attributes' => array(
          'label' => ts('Members Event Config'),
          'name' => 'members_event_config',
          'url' => 'civicrm/admin/setting/preferences/members_event_config&reset=1',
          'permission' => null,
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
  
 /**
 * Implementation of hook_civicrm_entityTypes
 */
function membersonlyevent_civicrm_entityTypes(&$entityTypes) {
  $entityTypes[] = array(
    'name' => 'MembersOnlyEvent',
    'class' => 'CRM_Membersonlyevent_DAO_MembersOnlyEvent',
    'table' => 'civicrm_membersonlyevent',
  );
  $entityTypes[] = array(
    'name' => 'MembersEventConfig',
    'class' => 'CRM_Membersonlyevent_DAO_MembersEventConfig',
    'table' => 'civicrm_membersonlyevent_config',
  );
  $entityTypes[] = array(
    'name' => 'MembersEventPrice',
    'class' => 'CRM_Membersonlyevent_DAO_MembersEventPrice',
    'table' => 'civicrm_membersonlyevent_price',
  );
}

