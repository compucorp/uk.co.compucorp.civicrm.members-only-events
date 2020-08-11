<?php

use CRM_MembersOnlyEvent_Test_Fabricator_Event as EventFabricator;
use CRM_MembersOnlyEvent_Test_Fabricator_MembersOnlyEvent as MembersOnlyEventFabricator;
use CRM_MembersOnlyEvent_BAO_MembersOnlyEvent as MembersOnlyEvent;

require_once __DIR__ . '/../../../../BaseHeadlessTest.php';

/**
 * Class CRM_MembersOnlyEvent_BAO_MembersOnlyEventTest
 *
 * @group headless
 */
class CRM_MembersOnlyEvent_Hook_Copy_EventFromTemplateCreatorTest extends BaseHeadlessTest {

  /**
   * Tests Create MemberOnlyEvent when new Event was created from template.
   */
  public function testEventFromTemplate() {
    $eventTemplate = EventFabricator::fabricate(['is_template' => TRUE]);
    $membersOnlyEventTemplate = MembersOnlyEventFabricator::fabricate(['event_id' => $eventTemplate->id]);
    $event = EventFabricator::fabricate();

    $eventFromTemplateCreator = new CRM_MembersOnlyEvent_Hook_Copy_EventFromTemplateCreator($event->id, $eventTemplate->id);
    $eventFromTemplateCreator->create();

    $membersOnlyEvent = MembersOnlyEvent::getMembersOnlyEvent($event->id);

    $this->assertEquals($membersOnlyEventTemplate->contribution_page_id, $membersOnlyEvent->contribution_page_id);
    $this->assertEquals($membersOnlyEventTemplate->notice_for_access_denied, $membersOnlyEvent->notice_for_access_denied);
    $this->assertEquals($membersOnlyEventTemplate->purchase_membership_button, $membersOnlyEvent->purchase_membership_button);
    $this->assertEquals($membersOnlyEventTemplate->purchase_membership_button_label, $membersOnlyEvent->purchase_membership_button_label);
    $this->assertEquals($membersOnlyEventTemplate->purchase_membership_link_type, $membersOnlyEvent->purchase_membership_link_type);
    $this->assertEquals($membersOnlyEventTemplate->purchase_membership_url, $membersOnlyEvent->purchase_membership_url);
  }

}
