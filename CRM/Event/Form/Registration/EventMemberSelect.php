<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Event_Form_Registration_EventMemberSelect extends CRM_Event_Form_Registration {

  protected $_membershipList = NULL;

  function preProcess() {
    parent::preProcess();
    $session = CRM_Core_Session::singleton();
    $session -> set('paid_membership', 0);
    $this -> _eventId = $session -> get('member_event_id');
  }

  function buildQuickForm() {
    $this->preProcess();
    CRM_Utils_System::setTitle(ts('Become a Member'));

    $this -> _membershipList = $this -> getMembershipList();

    // add form elements
    $this -> add('select', // field type
    'membership_types', // field name
    ts('Membership Types'), // field label
    array('' => ts('-- Select --')) + $this -> _membershipList, // list of attributes
    TRUE // is required
    );

    $this -> addButtons(array( array('type' => 'submit', 'name' => ts('Submit'), 'isDefault' => TRUE, ), ));

    // export form elements
    $this -> assign('elementNames', $this -> getRenderableElementNames());
    parent::buildQuickForm();
  }

  function postProcess() {
    CRM_Utils_System::flushCache();

    $session = CRM_Core_Session::singleton();
    $values = $this -> exportValues();

    if (isset($values['membership_types']) && array_key_exists($values['membership_types'], $this -> _membershipList)) {
      $session -> set('membership_price_field_value_id', $values['membership_types']);
    } else {
      $session -> set('membership_type', 0);
    }

    $url = CRM_Utils_System::url('civicrm/event/register', "reset=1&id={$this->_eventId}");
    CRM_Utils_System::redirect($url);
  }

  function getMembershipList() {

    $members_only_event = CRM_Membersonlyevent_BAO_MembersOnlyEvent::getMembersOnlyEvent($this -> _eventId);

    if (is_object($members_only_event)) {

      if (!is_null($members_only_event -> price_field_id)) {
        $results = CRM_Price_BAO_PriceFieldValue::getValues($members_only_event -> price_field_id);
        $output = array();

        if (sizeof($results)) {
          foreach ($results as $key => $value) {
            $output[$key] = $value['label'];
          }
          return $output;
        } else {
          return NULL;
        }

      } else {
        return NULL;
      }

    } else {
      return NULL;
    }
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
      $label = $element -> getLabel();
      if (!empty($label)) {
        $elementNames[] = $element -> getName();
      }
    }
    return $elementNames;
  }

}
