cj(function ($) {
  'use strict';
  if(!$('form').hasClass('CRM_Event_Form_Registration_AdditionalParticipant')) {
  	$('input[value="' + CRM.membership_type.type + '"]').prop('checked', true);
  }
});