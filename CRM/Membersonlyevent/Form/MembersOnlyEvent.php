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
    $this->add('text', 'membership_url', ts('Membership purchase URL'));
    
	$groups = $this->getPriceOptions();
    $groupSize = max(count($groups), 2);
 
    $inP = $this->addElement('advmultiselect', 'membersPrice',
      ts('Members\' Price(s)') . ' ',
      $groups,
      array(
        'size' => $groupSize,
        'style' => 'width:auto; min-width:240px;',
        'class' => 'advmultiselect',
      )
    );
	
	$inP->setButtonAttributes('add', array('value' => ts('Add >>')));
    $inP->setButtonAttributes('remove', array('value' => ts('<< Remove')));
	
	// add form rules
	$this->addFormRule(array('CRM_Membersonlyevent_Form_MembersOnlyEvent', 'rules'));
	global $base_url;
	$this->assign('BASE_URL', $base_url.'/');
    // export form elements
    //$this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }
  
  
  protected static function rules($params, $files, $self) {
    $errors = array();
	$isPublicEvent = CRM_Utils_Array::value('members_event_type', $params);
	$memberURL = CRM_Utils_Array::value('membership_url', $params);
	$memberPrice = CRM_Utils_Array::value('membersPrice', $params);
	
    if($isPublicEvent=='2'){
      if(is_null($memberURL)){
        $errors['membership_url'] = ts('Please set a membership purchasing url.');
      }
    }else if($isPublicEvent=='3'){
      if(is_null($memberURL)){
        $errors['membership_url'] = ts('Please set a membership purchasing url.');
      }
      if(!$memberPrice){
      	$errors['membersPrice'] = ts('Please select at least one member ticket price.');
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
    $parentDefaults = parent::setDefaultValues();
	
    $defaults = array();
    $defaults['members_event_type'] = 1;
	
    // Search for the Members Only Event object by the Event ID
    $members_only_event = CRM_Membersonlyevent_BAO_MembersOnlyEvent::getMembersOnlyEvent($this->_id);

    if(is_object($members_only_event)) {
    
      $defaults['members_event_type'] = $members_only_event->members_event_type;
      $defaults['membership_url'] = $members_only_event->membership_url;
    
    }
    
	//set default value for members price
	if ($this->_id) {
	  $params = array(
	    'event_id' => $this->_id,
	    'is_member_price' => 1
	  );
      $members_price = CRM_Membersonlyevent_BAO_MembersEventPrice::getMemberPrice($params);
	  $selected_price = array();
	  foreach ($members_price as $key => $value) {
		  $selected_price[] = $value['price_value_id'];
	  }
      $defaults['membersPrice'] = $selected_price;
    }
    return $defaults;
  }
  
  function getPriceOptions() {

    $eventId  = $this->_id;
    $params   = array();
	$return_array = array();

    $price_set_id = CRM_Price_BAO_PriceSet::getFor('civicrm_event', $eventId, NULL, 1);

    if ($price_set_id) {
    	//TODO:apply to price sets
        //$defaults['price_set_id'] = $price_set_id;
    }
    else {
      $options = CRM_Membersonlyevent_BAO_MembersEventPrice::getPriceValue($eventId);
      foreach ($options as $optionId => $optionValue) {
        $value = CRM_Utils_Money::format($optionValue['amount'], NULL, '%a');
        $label = $optionValue['label'];
        $value_id = $optionValue['id'];
	    $return_array[$value_id] = $label.": ".$value;    
      }
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
    $params['membership_url'] = $passed_values['membership_url'];
    $params['members_event_type'] = $passed_values['members_event_type'];
    
    // Create or edit the values
    CRM_Membersonlyevent_BAO_MembersOnlyEvent::create($params);
	
    
		
	$value_list = $this->getPriceOptions();
	$priceCount = 1;
	foreach ($passed_values['membersPrice'] as $priceKey => $priceValue) {
		$priceParams[$priceCount]['event_id'] = $this->_id;
		$priceParams[$priceCount]['price_value_id'] = $priceValue;
		$exist_price = CRM_Membersonlyevent_BAO_MembersEventPrice::getMemberPrice($priceParams[$priceCount]);
		if($exist_price){
		  foreach ($exist_price as $key => $value) {
		    $priceParams[$priceCount]['id'] = $value['id'];
	      }
		}
		$priceParams[$priceCount]['is_member_price'] = 1;
		CRM_Membersonlyevent_BAO_MembersEventPrice::create($priceParams[$priceCount]);
		unset($value_list[$priceValue]);
		$priceCount++;
	}
	
	var_dump($value_list);
	
	foreach ($value_list as $priceKey => $priceValue) {
		$priceParams[$priceCount]['event_id'] = $this->_id;
        $priceParams[$priceCount]['price_value_id'] = $priceKey;
		$exist_price = CRM_Membersonlyevent_BAO_MembersEventPrice::getMemberPrice($priceParams[$priceCount]);
		if($exist_price){
		  foreach ($exist_price as $key => $value) {
		    $priceParams[$priceCount]['id'] = $value['id'];
	      }
		}
		$priceParams[$priceCount]['is_member_price'] = 0;
		CRM_Membersonlyevent_BAO_MembersEventPrice::create($priceParams[$priceCount]);
		$priceCount++;
	}
    parent::endPostProcess();
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
