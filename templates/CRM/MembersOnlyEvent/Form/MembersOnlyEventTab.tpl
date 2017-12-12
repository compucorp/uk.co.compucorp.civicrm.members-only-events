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

  <div class="crm-section" id="{$form.allowed_membership_types.id}">
    <div class="label">{$form.allowed_membership_types.label} {help id="allowed-membership-types" file="CRM/MembersOnlyEvent/Form/MembersOnlyEventTab"}</div>
    <div class="content">{$form.allowed_membership_types.html}</div>
    <div class="clear"></div>
  </div>

</div>

  {* FOOTER *}

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
  
  <script type="text/javascript">
  {literal}
    jQuery(document).ready(function(){
      jQuery("#is_members_only_event input[type=checkbox]").click(function(){
        
        if (jQuery(this).attr("checked") == true){
          jQuery("#allowed_membership_types").show();
        }
        else {
          jQuery("#allowed_membership_types").hide();
        }
      
      });
      
      if (jQuery("#is_members_only_event input[type=checkbox]").attr("checked") == false){
        jQuery("#allowed_membership_types").hide();
      }
      
    });
  {/literal}
  </script>
    
{else}
    <div id="help">{ts}Online registration tab needs to be enabled for this event to set the members only event settings.{/ts}</div>
{/if}