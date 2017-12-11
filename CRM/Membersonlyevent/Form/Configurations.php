<?php

use CRM_Membersonlyevent_BAO_Configurations as Configurations;

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Membersonlyevent_Form_Configurations extends CRM_Core_Form {

  public function preProcess() {
    CRM_Utils_System::setTitle(ts('Members-Only Event Extension Configurations'));
  }

  public function buildQuickForm() {
    $this->add(
      'checkbox',
      'membership_duration_check',
      ts('Membership duration check')
    );

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));
  }

  public function setDefaultValues() {
    $defaultValues = array();

    $configs = Configurations::getConfigs();
    $defaultValues['membership_duration_check'] = $configs->membership_duration_check;

    return $defaultValues;
  }

  public function postProcess() {
    $params = $this->exportValues();
    $params['membership_duration_check'] = !empty($params['membership_duration_check']) ? TRUE : FALSE;
    Configurations::updateConfigs($params);

    CRM_Core_Session::setStatus(ts('The configurations have been saved.'), ts('Saved'), 'success');
  }
}
