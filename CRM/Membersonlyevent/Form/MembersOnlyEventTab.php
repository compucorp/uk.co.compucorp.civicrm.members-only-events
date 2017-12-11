<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Membersonlyevent_Form_MembersOnlyEventTab extends CRM_Event_Form_ManageEvent {

  /**
   * @inheritdoc
   */
  public function buildQuickForm() {
    $this->addFields();
    $this->addFormRule(array($this, 'formRules'));

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


    $this->add('text', 'membership_purchase_url', ts('Membership purchasing page URL'));
    $this->assign('BASE_URL', CRM_Utils_System::baseURL());

    $this->addEntityRef(
      'contribution_page_id',
      ts('Contribution page used for membership signup'),
      array(
        'entity' => 'ContributionPage',
        'multiple' => FALSE,
        'placeholder' => ts('- No selection -'),
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
   * Sets the form validation rules.
   */
  public function formRules($params, $files, $self) {
    $errors = array();

    $isMembersOnlyEvent = CRM_Utils_Array::value('is_members_only_event', $params, FALSE);
    $membershipPurchaseURL = CRM_Utils_Array::value('membership_purchase_url', $params);

    if ($isMembersOnlyEvent && empty($membershipPurchaseURL)) {
      $errors['membership_purchase_url'] = ts('Please set Membership purchasing page URL.');
    }

    return $errors;
  }

  /**
   * @inheritdoc
   */
  public function setDefaultValues() {
    $defaultValues= array();

    $membersOnlyEvent = CRM_Membersonlyevent_BAO_MembersOnlyEvent::getMembersOnlyEvent($this->_id);
    if($membersOnlyEvent) {
      $defaultValues['is_members_only_event'] = TRUE;
      $defaultValues['membership_purchase_url'] = $membersOnlyEvent->membership_purchase_url;
      $defaultValues['contribution_page_id'] = $membersOnlyEvent->contribution_page_id;
      $defaultValues['allowed_membership_types'] = CRM_Membersonlyevent_BAO_EventMembershipType::getAllowedMembershipTypesIDs($membersOnlyEvent->id);
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
    $membersOnlyEvent = CRM_Membersonlyevent_BAO_MembersOnlyEvent::getMembersOnlyEvent($params['event_id']);
    $operation = $this->getSubmitOperation($eventSetToMembersOnly, $membersOnlyEvent);

    switch ($operation){
      case 'create':
        $this->saveFormData($params);
        break;
      case 'update':
        $params['id'] = $membersOnlyEvent->id;
        $this->saveFormData($params);
        break;
      case 'downgrade_to_normal_event':
        $this->downgradeToNormalEvent($membersOnlyEvent->id);
        break;
    }
  }

  /**
   * Returns the type of submit operation based
   * on the submitted data, there are 4 cases which are :
   * 1- do_nothing : the event is not already a members-only
   *   event & 'Is members-only event ?' field is not checked.
   * 2- downgrade_to_normal_event : if the event is currently
   *   a members-only event but we unchecked 'Is members-only event ?' field.
   * 3- update : if the event is currently a members-only event
   *   and we kept 'Is members-only event ?' field checked.
   * 4- create : if the event is not a members-only event but
   *   we checked 'Is members-only event ?' field.
   *
   * @param boolean $eventSetToMembersOnly
   *   True if Is members-only event ?' field is checked
   *   or False if it's not.
   * @param CRM_Membersonlyevent_BAO_MembersOnlyEvent $membersOnlyEvent
   *   Contains the members-only event configurations if the event is
   *   members-only event.
   *
   * @return string
   *   It may contain `do_nothing`, `downgrade_to_normal_event`,
   *     `update` or `create`.
   */
  private function getSubmitOperation($eventSetToMembersOnly, $membersOnlyEvent = NULL) {
    if(!$membersOnlyEvent && !$eventSetToMembersOnly) {
      return 'do_nothing';
    }

    if($membersOnlyEvent && !$eventSetToMembersOnly) {
      return 'downgrade_to_normal_event';
    }

    if($membersOnlyEvent && $eventSetToMembersOnly) {
      return 'update';
    }

    return 'create';
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
    $membersOnlyEvent = CRM_Membersonlyevent_BAO_MembersOnlyEvent::create($params);
    if (!empty($membersOnlyEvent->id)) {
      $allowedMembershipTypesIDs = array();
      if (!empty($params['allowed_membership_types'])) {
        $allowedMembershipTypesIDs = explode(',', $params['allowed_membership_types']);
      }

      CRM_Membersonlyevent_BAO_EventMembershipType::updateAllowedMembershipTypes($membersOnlyEvent->id, $allowedMembershipTypesIDs);
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
    $membersOnlyEvent = new CRM_Membersonlyevent_BAO_MembersOnlyEvent();
    $membersOnlyEvent->id = $membersOnlyEventID;
    $membersOnlyEvent->delete();
  }
}
