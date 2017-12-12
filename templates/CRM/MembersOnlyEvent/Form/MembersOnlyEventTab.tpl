{* Check if the online registration for this event is allowed, show notification message otherwise *}
<div class="crm-block crm-form-block crm-event-manage-membersonlyevent-form-block">

{if $isOnlineRegistration == 1}
  {* HEADER *}

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="top"}
  </div>

  <div class="crm-section" id="{$form.is_members_only_event.id}">
    <div class="label">{$form.is_members_only_event.label}</div>
    <div class="content">{$form.is_members_only_event.html}</div>
    <div class="clear"></div>
  </div>

  <div id="members-only-event-fields">
    <div class="crm-section" id="{$form.allowed_membership_types.id}">
      <div class="label">{$form.allowed_membership_types.label} {help id="allowed-membership-types" file="CRM/MembersOnlyEvent/Form/MembersOnlyEventTab"}</div>
      <div class="content">{$form.allowed_membership_types.html}</div>
      <div class="clear"></div>
    </div>

    <div class="crm-section" id="{$form.purchase_membership_button.id}">
      <div class="label">{$form.purchase_membership_button.label}</div>
      <div class="content">{$form.purchase_membership_button.html}</div>
      <div class="clear"></div>
    </div>

    <div id="purchase-button-disabled-section">
      <div class="crm-section" id="{$form.notice_for_access_denied.id}">
        <div class="label">{$form.notice_for_access_denied.label}</div>
        <div class="content">{$form.notice_for_access_denied.html}</div>
        <div class="clear"></div>
      </div>
    </div>

  </div>

</div>

  {* FOOTER *}

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
  
  <script type="text/javascript">
  {literal}
    jQuery(document).ready(function(){
      if (jQuery("#is_members_only_event input[type=checkbox]").attr("checked") == false){
        jQuery("#members-only-event-fields").hide();
      }

      jQuery("#is_members_only_event input[type=checkbox]").click(function(){
        if (jQuery(this).attr("checked") == true){
          jQuery("#members-only-event-fields").show();
        }
        else {
          jQuery("#members-only-event-fields").hide();
        }
      });

      if (jQuery("input[name='purchase_membership_button']:checked").val() == '1'){
        jQuery("#purchase-button-disabled-section").hide();
      }

      jQuery("input[name='purchase_membership_button']").click(function(){
        if (jQuery(this).val() === '0') {
          jQuery("#purchase-button-disabled-section").show();
        } else if (jQuery(this).val() === '1') {
          jQuery("#purchase-button-disabled-section").hide();
        }
      });
      
    });
  {/literal}
  </script>
    
{else}
    <div id="help">{ts}Online registration tab needs to be enabled for this event to set the members only event settings.{/ts}</div>
{/if}
