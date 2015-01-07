cj(function ($) {
  'use strict';
    if(CRM.membership_type.type == CRM.membership_type.value) {
      $('#CIVICRM_QFID_41_12').prop('checked', true);
    } else {
      $('#CIVICRM_QFID_40_10').prop('checked', true);
    }
})