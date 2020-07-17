<?php

use CRM_MembersOnlyEvent_BAO_EventMembershipType as  EventMembershipType;
use CRM_MembersOnlyEvent_BAO_MembersOnlyEvent as MemberOnlyEvent;

/**
 * Class for for Copy Hook for Event object
 */
class CRM_MembersOnlyEvent_Hook_Copy_Event {

  /**
   * Handle Hook Pre Event
   *
   * @param  string $objectName
   * @param  $object
   * @throws CRM_Core_Exception
   */
  public function handle($objectName, &$object) {
    if (!$this->shouldHandle($objectName)) {
      return;
    }
    $this->createMemberOnlyEventFromEventTemplate($object->id);
  }

  protected function createMemberOnlyEventFromEventTemplate($id) {

    $templateId = CRM_Utils_Request::retrieve('template_id', 'Int');

    if (empty($templateId)) {
      return;
    }

    $memberOnlyEventTemplate = MemberOnlyEvent::getMembersOnlyEvent($templateId);

    if (empty($memberOnlyEventTemplate)) {
      return;
    }

    $params = [
      'event_id' => $id,
      'notice_for_access_denied' => strip_tags($memberOnlyEventTemplate->notice_for_access_denied),
      'contribution_page_id' => $memberOnlyEventTemplate->contribution_page_id,
      'purchase_membership_url' => $memberOnlyEventTemplate->purchase_membership_url,
      'purchase_membership_button' => $memberOnlyEventTemplate->purchase_membership_button,
      'purchase_membership_button_label' => $memberOnlyEventTemplate->purchase_membership_button_label,
      'purchase_membership_link_type' => $memberOnlyEventTemplate->purchase_membership_link_type,
    ];

    $membersOnlyEvent = MemberOnlyEvent::create($params);

    // Set allowed membership type IDs if applicable
    $allowedMembershipTypes = EventMembershipType::getAllowedMembershipTypesIDs($memberOnlyEventTemplate->id);

    if (empty($allowedMembershipTypes)) {
      return;
    }

    EventMembershipType::updateAllowedMembershipTypes($membersOnlyEvent->id, $allowedMembershipTypes);

  }

  /**
   * Checks whether the hook should be handled or not.
   *
   * @param string $objectName
   *
   * @return bool
   */
  private function shouldHandle($objectName) {
    return $objectName == 'Event';
  }

}
