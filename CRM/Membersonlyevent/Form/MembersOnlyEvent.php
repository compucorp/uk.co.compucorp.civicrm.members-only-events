<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Membersonlyevent_Form_MembersOnlyEvent extends CRM_Event_Form_ManageEvent {
  function buildQuickForm() {
     
    // add form elements
    $this->add(
      'checkbox', // field type
      'is_members_only_event', // field name
      ts('Is members only event?'), // field label
      '',   // list of attributes
      false // is required
    );
    
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
    
    // Search for the Members Only Event object by the Event ID
    $members_only_event = CRM_Membersonlyevent_BAO_MembersOnlyEvent::getMembersOnlyEvent($this->_id);
    
    if(is_object($members_only_event)) {
    
      $defaults['is_members_only_event'] = $members_only_event->is_members_only_event;
      $defaults['contribution_page_id'] = $members_only_event->contribution_page_id;
    
    }
    
    return $defaults;
  }
  
  function getContributionPagesAsOptions() {
      
    $results = civicrm_api3('ContributionPage', 'get', array('sequential' => 1));
    $contribution_pages = $results['values'];
    
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
    $members_only_event = CRM_Membersonlyevent_BAO_MembersOnlyEvent::getMembersOnlyEvent($this->_id);
    
    if(is_object($members_only_event)) {
      // If we have the ID, edit operation will fire
      $params['id'] = $members_only_event->id;
    }
    
    $params['event_id'] = $this->_id;
    $params['contribution_page_id'] = $passed_values['contribution_page_id'];
    $params['is_members_only_event'] = isset($passed_values['is_members_only_event']) ? $passed_values['is_members_only_event'] : 0;
    
    // Create or edit the values
    CRM_Membersonlyevent_BAO_MembersOnlyEvent::create($params);
    
    //need recheck
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
