<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Membersonlyevent_Form_MembersOnlyEvent extends CRM_Event_Form_ManageEvent {
	
  /**
   * the event ID of the event if we are resuming a event
   *
   * @var integer
   */
  protected $_eventID;
  
  /**
   * Function to set variables up before form is built
   *
   * @return void
   * @access public
   */
  //public function preProcess() {
  	//$this->$_eventID = CRM_Utils_Request::retrieve('id', 'Integer', $this, FALSE, NULL);
  	//dpm($_eventID);
  //}
  
  /**
   * Function to actually build the form
   *
   * @return None
   * @access public
   */
  function buildQuickForm() {
     
	//TODO: change hard coded options to civicrm option group
	$members_event_type = array();
    $members_event_type[] = &HTML_QuickForm::createElement('radio', NULL, NULL, 'Public Event', '1');
    $members_event_type[] = &HTML_QuickForm::createElement('radio', NULL, NULL, 'Members Only Event', '2');
    $members_event_type[] = &HTML_QuickForm::createElement('radio', NULL, NULL, 'Members and Non-members Event', '3');
    $this->addGroup( $members_event_type, 'members_event_type', ( 'Members Event Type:' ) );
    
    // add form elements
    $this->add(
      'select', // field type
      'contribution_page_id', // field name
      ts('Contribution page used for membership signup'), // field label
      $this->getContributionPagesAsOptions(),   // list of attributes
      TRUE // is required
    );
    
	//getPriceOptions();
    /*if (count($groups) <= 10) {
      // setting minimum height to 2 since widget looks strange when size (height) is 1
      $groupSize = max(count($groups), 2);
    }
    else {
      $groupSize = 10;
    }*/
    
    //TODO:match the key
    $groups = array(
	    1 => "Platium Member",
	    2 => "Golden Member",
	    3 => "Silver Member",
	    4 => "Public",
	);
    $inG = $this->addElement('advmultiselect', 'membersPrice',
      ts('Members\' Price(s)') . ' ',
      $groups,
      array(
        'size' => 10,
        'style' => 'width:auto; min-width:240px;',
        'class' => 'advmultiselect',
      )
    );
	
	//as we are having hidden smart group so no need.
    //if (!$this->_searchBasedMailing) {
      $this->addRule('membersPrice', ts('Please select a group to be mailed.'), 'required');
    //}
	
	$inG->setButtonAttributes('add', array('value' => ts('Add >>')));
    $inG->setButtonAttributes('remove', array('value' => ts('<< Remove')));
	
	// add form rules
	$this->addFormRule(array('CRM_Membersonlyevent_Form_MembersOnlyEvent', 'rules'));
	
    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }
  
  
  protected static function rules($params, $files, $self) {
    $errors = array();
	$isPublicEvent = CRM_Utils_Array::value('members_event_type', $params);
	$contributionPageID = CRM_Utils_Array::value('contribution_page_id', $params);
	
    if($isPublicEvent!=='1'){
      if(!is_numeric($contributionPageID)){
        $errors['contribution_page_id'] = ts('Please select a contribution page.');
      }
    }
	return $errors;
  }
  
  /**
   * This function sets the default values for the form.
   *
   * @access public
   */
  function setDefaultValues() {
      
    $defaults = array();
    $defaults['members_event_type'] = 1;
	
    // Search for the Members Only Event object by the Event ID
    $members_only_event = CRM_Membersonlyevent_BAO_MembersOnlyEvent::getMembersOnlyEvent($this->_id);

    if(is_object($members_only_event)) {
    
      $defaults['members_event_type'] = $members_only_event->members_event_type;
      $defaults['contribution_page_id'] = $members_only_event->contribution_page_id;
    
    }
    
	//Member_price
	/*if ($this->_id) {
      $dao = new CRM_Mailing_DAO_MailingGroup();

      $mailingGroups = array(
        'civicrm_group' => array( ),
        'civicrm_mailing' => array( )
      );
      $dao->mailing_id = $this->_mailingID;
      $dao->find();
      while ($dao->fetch()) {
        // account for multi-lingual
        // CRM-11431
        $entityTable = 'civicrm_group';
        if (substr($dao->entity_table, 0, 15) == 'civicrm_mailing') {
          $entityTable = 'civicrm_mailing';
        }
        $mailingGroups[$entityTable][$dao->group_type][] = $dao->entity_id;
      }

      $defaults['includeGroups'] = $mailingGroups['civicrm_group']['Include'];

      if (!empty($mailingGroups['civicrm_mailing'])) {
        $defaults['includeMailings'] = CRM_Utils_Array::value('Include', $mailingGroups['civicrm_mailing']);
      }
    }*/
	
    return $defaults;
  }
  
  function getContributionPagesAsOptions() {
      
    $contribution_pages = CRM_Contribute_BAO_ContributionPage::commonRetrieveAll('CRM_Contribute_DAO_ContributionPage');
    
    $return_array = array();
    $return_array['NULL'] = ts('- Select contribution page -');
    
    foreach ($contribution_pages as $key => $contribution_object) {
      $return_array[$contribution_object['id']] = $contribution_object['title'];
    }
    
    return $return_array;
  }
  
  function getPriceOptions() {
    
    $return_array = array();
    
    foreach ($contribution_pages as $key => $contribution_object) {
      $return_array[$contribution_object['id']] = $contribution_object['title'];
    }
    
    return $return_array;
  }

  function postProcess() {
    $passed_values = $this->exportValues();
    
    // Search for the Members Only Event object by the Event ID
    $members_only_event = CRM_Membersonlyevent_BAO_MembersOnlyEvent::getMembersOnlyEvent($passed_values['id']);
    
    if(is_object($members_only_event)) {
      // If we have the ID, edit operation will fire
      $params['id'] = $members_only_event->id;
    }
    
    $params['event_id'] = $passed_values['id'];
    $params['contribution_page_id'] = $passed_values['contribution_page_id'];
    $params['members_event_type'] = $passed_values['members_event_type'];
    
    // Create or edit the values
    CRM_Membersonlyevent_BAO_MembersOnlyEvent::create($params);
	
	$priceParams['event_id'] = $passed_values['id'];
	foreach ($passed_values['membersPrice'] as $key => $value) {
		$priceParams['price_value_id'] = $value;
		$priceParams['is_member_price'] = 1;
		CRM_Membersonlyevent_BAO_MembersEventPrice::create($priceParams);
	}
    
    parent::postProcess();
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }
}
