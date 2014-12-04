cj(function ($) {
  'use strict';
    if(CRM.membership_type.type == 41) {
      $('#CIVICRM_QFID_41_12').prop('checked', true);
    } else {
      $('#CIVICRM_QFID_40_10').prop('checked', true);
    }
})