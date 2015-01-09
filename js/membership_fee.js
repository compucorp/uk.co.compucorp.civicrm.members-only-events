cj(function ($) {
  'use strict';
  $('input[value="' + CRM.membership_type.type + '"]').prop('checked', true);
  if($('form').hasClass('CRM_Event_Form_Registration_AdditionalParticipant')) {
    $('.Membership_fee-section').hide();
  }
});