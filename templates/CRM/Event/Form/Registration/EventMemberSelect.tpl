
<div class="crm-container crm-public">
<fieldset class="crm-profile crm-profile-name-event_registration">
  <legend>{ts}Please select the membership:{/ts}</legend>
  <div class="crm-section">
    <div id="help">Our training and events are available to members of the PSHE Association only. 
    If you are an existing member please log in and book your place, otherwise please complete this short form to sign up for the event and one year’s membership. 
    You can read more about the benefits of Association membership <a href="/membership" target="_blank">here</a>.</div>
    <div class="label">{$form.membership_types.label}</div>
    <div class="content">{$form.membership_types.html}</div>
  </div>

</fieldset>
  <div id="crm-submit-buttons" class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
</div>
