<?php

/**
 * Class for for Pre Hook
 */
class CRM_MembersOnlyEvent_Hook_Pre_Event {

  /**
   * Handle Hook Pre Event
   *
   * @param $op
   * @param string $objectName
   * @param int $id
   * @param array $params
   */
  public function handle($op, $objectName, $id, &$params) {
    if (!$this->shouldHandle($op, $objectName)) {
      return;
    }
    $this->setSession($params);
  }


  /**
   * @param $params
   */
  private function setSession(&$params) {
    if (!isset($params['template_id'])) {
      return;
    }

    CRM_Core_Session::singleton()->set('event_template_' . $params['created_date'], $params['template_id']);
  }

  /**
   * Checks whether the hook should be handled or not.
   *
   * @param string $op
   * @param string $objectName
   *
   * @return bool
   */
  private function shouldHandle($op, $objectName) {
    return $objectName == 'Event';
  }

}
