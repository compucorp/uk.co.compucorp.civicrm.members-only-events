<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Membersonlyevent_Form_MembersOnlyEvent extends CRM_Event_Form_ManageEvent {
  function buildQuickForm() {
     
	//TODO: change hard coded options to civicrm option group
	$members_event_type = array();
    $members_event_type[] = &HTML_QuickForm::createElement('radio', NULL, NULL, 'Public Event', 1);
    $members_event_type[] = &HTML_QuickForm::createElement('radio', NULL, NULL, 'Members Only Event', 2);
    $members_event_type[] = &HTML_QuickForm::createElement('radio', NULL, NULL, 'Members and Non-members Event', 3);
    $this->addGroup( $members_event_type, 'members_event_type', ( 'Members Event Type:' ) );
    
    // add form elements
    $this->add(
      'select', // field type
      'contribution_page_id', // field name
      ts('Contribution page used for membership signup'), // field label
      $this->getContributionPagesAsOptions(),   // list of attributes
      false // is required
    );
    
    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
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
