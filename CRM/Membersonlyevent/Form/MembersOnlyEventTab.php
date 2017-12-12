<?php

use CRM_MembersOnlyEvent_BAO_MembersOnlyEvent as MembersOnlyEvent;
use CRM_MembersOnlyEvent_BAO_EventMembershipType as EventMembershipType;

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_MembersOnlyEvent_Form_MembersOnlyEventTab extends CRM_Event_Form_ManageEvent {

  /**
   * Used to specify the type of  operation
   * to be performed on the submitted event data.
   */
  const OPERATION_DO_NOTHING = 'do_nothing';
  const OPERATION_CREATE = 'create';
  const OPERATION_UPDATE = 'update';
  const OPERATION_DOWNGRADE_TO_NORMAL_EVENT = 'downgrade_to_normal_event';

  /**
   * @inheritdoc
   */
  public function buildQuickForm() {
    $this->addFields();

    parent::buildQuickForm();
  }

  /**
   * Adds the form fields.
   */
  private function addFields() {
    $this->add(
      'checkbox',
      'is_members_only_event',
      ts('Is members-only event ?')
    );

    $this->addEntityRef(
      'allowed_membership_types',
      ts('Allowed Membership Types'),
      array(
        'entity' => 'MembershipType',
        'multiple' => TRUE,
        'placeholder' => ts('- any -'),
        'select' => array('minimumInputLength' => 0),
      )
    );

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));
  }

  /**
   * @inheritdoc
   */
  public function setDefaultValues() {
    $defaultValues= array();

    $membersOnlyEvent = MembersOnlyEvent::getMembersOnlyEvent($this->_id);
    if($membersOnlyEvent) {
      $defaultValues['is_members_only_event'] = TRUE;
      $defaultValues['allowed_membership_types'] = EventMembershipType::getAllowedMembershipTypesIDs($membersOnlyEvent->id);
    }
    
    return $defaultValues;
  }

  /**
   * @inheritdoc
   */
  public function postProcess() {
    $params = $this->exportValues();
    $params['event_id'] = $this->_id;

    $eventSetToMembersOnly = !empty($params['is_members_only_event']) ? TRUE : FALSE;
    $membersOnlyEvent = MembersOnlyEvent::getMembersOnlyEvent($params['event_id']);
    $operation = $this->getSubmitOperation($eventSetToMembersOnly, $membersOnlyEvent);

    switch ($operation){
      case self::OPERATION_CREATE:
        $this->saveFormData($params);
        break;
      case self::OPERATION_UPDATE:
        $params['id'] = $membersOnlyEvent->id;
        $this->saveFormData($params);
        break;
      case self::OPERATION_DOWNGRADE_TO_NORMAL_EVENT:
        $this->downgradeToNormalEvent($membersOnlyEvent->id);
        break;
    }
  }

  /**
   * Returns the type of submit operation based
   * on the submitted data, there are 4 cases which are :
   * 1- OPERATION_DO_NOTHING : the event is not already a members-only
   *   event & 'Is members-only event ?' field is not checked.
   * 2- OPERATION_DOWNGRADE_TO_NORMAL_EVENT : if the event is currently
   *   a members-only event but we unchecked 'Is members-only event ?' field.
   * 3- OPERATION_UPDATE : if the event is currently a members-only event
   *   and we kept 'Is members-only event ?' field checked.
   * 4- OPERATION_CREATE : if the event is not a members-only event but
   *   we checked 'Is members-only event ?' field.
   *
   * @param boolean $eventSetToMembersOnly
   *   True if Is members-only event ?' field is checked
   *   or False if it's not.
   * @param MembersOnlyEvent $membersOnlyEvent
   *   Contains the members-only event configurations if the event is
   *   members-only event.
   *
   * @return string
   *   It may contain one of the OPERATION_* constants
   *   defined at the top of this class.
   */
  private function getSubmitOperation($eventSetToMembersOnly, $membersOnlyEvent = NULL) {
    if(!$membersOnlyEvent && !$eventSetToMembersOnly) {
      return self::OPERATION_DO_NOTHING;
    }

    if($membersOnlyEvent && !$eventSetToMembersOnly) {
      return self::OPERATION_DOWNGRADE_TO_NORMAL_EVENT;
    }

    if($membersOnlyEvent && $eventSetToMembersOnly) {
      return self::OPERATION_UPDATE;
    }

    return self::OPERATION_CREATE;
  }

  /**
   * Saves the form data, which will either be
   * an update to already existing members-only event
   * configurations or converting a normal event to
   * members-only event,
   *
   * @param $params
   */
  private function saveFormData($params) {
    $membersOnlyEvent = MembersOnlyEvent::create($params);
    if (!empty($membersOnlyEvent->id)) {
      $allowedMembershipTypesIDs = array();
      if (!empty($params['allowed_membership_types'])) {
        $allowedMembershipTypesIDs = explode(',', $params['allowed_membership_types']);
      }

      EventMembershipType::updateAllowedMembershipTypes($membersOnlyEvent->id, $allowedMembershipTypesIDs);
    }
  }

  /**
   * Downgrades an existing members-only
   * event to normal event.
   *
   * @param $membersOnlyEventID
   *   The Id of the members-only event
   *   to be downgraded.
   */
  private function downgradeToNormalEvent($membersOnlyEventID) {
    $membersOnlyEvent = new MembersOnlyEvent();
    $membersOnlyEvent->id = $membersOnlyEventID;
    $membersOnlyEvent->delete();
  }
}
