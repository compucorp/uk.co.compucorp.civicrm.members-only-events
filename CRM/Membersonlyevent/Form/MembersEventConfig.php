<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Membersonlyevent_Form_MembersEventConfig extends CRM_Core_Form {
  protected $_config = NULL;

  function preProcess() {
    parent::preProcess();
    CRM_Utils_System::setTitle(ts('Settings - Members Only Event Configuration'));

	$configValue = CRM_Membersonlyevent_BAO_MembersEventConfig::getConfig();
	$this->_config = $configValue;

  }
  
  function buildQuickForm() {

    // add form elements
    $this->add(
      'checkbox', // field type
      'check_duration', // field name
      'Check Membership Duration'// field label
    );
	$this->add(
      'checkbox', // field type
      'registration_restriction', // field name
      'First Ticket Purchasing Restricts to Current User?'// field label
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

  function setDefaultValues() {
  	$defaults = array();
  	$defaults['check_duration'] = $this->_config['duration_check'];
	$defaults['registration_restriction'] = $this->_config['registration_restriction'];
	
	return $defaults;
  }

  function postProcess() {
  	CRM_Utils_System::flushCache();
    $values = $this->exportValues();
	$params['id'] = $this->_config['id'];
	
	if(isset($values['check_duration'])){
	  $params['duration_check'] = $values['check_duration'];
	}else{
	  $params['duration_check'] = 0;
	}
	
	if(isset($values['registration_restriction'])){
	  $params['registration_restriction'] = $values['registration_restriction'];
	}else{
	  $params['registration_restriction'] = 0;
	}

	// submit to BAO for updating
	  $set = CRM_Membersonlyevent_BAO_MembersEventConfig::create($params);

	  //$url = CRM_Utils_System::url('civicrm/admin/setting/preferences/members_event_config', 'reset=1');
	  // show message
	  CRM_Core_Session::setStatus(ts('The member event configuration has been saved.'), ts('Saved'), 'success');
    //parent::postProcess();
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
