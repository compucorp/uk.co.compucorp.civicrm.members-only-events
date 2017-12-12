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

  <div class="crm-section" id="{$form.membership_purchase_url.id}">
    <div class="label">{$form.membership_purchase_url.label}</div>
    <div class="content">{$BASE_URL}{$form.membership_purchase_url.html}</div>
    <div class="clear"></div>
  </div>

  <div class="crm-section" id="{$form.contribution_page_id.id}">
    <div class="label">{$form.contribution_page_id.label}</div>
    <div class="content">{$form.contribution_page_id.html}</div>
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
          jQuery("#membership_purchase_url").show();
          jQuery("#contribution_page_id").show();
        }
        else {
          jQuery("#allowed_membership_types").hide();
          jQuery("#membership_purchase_url").hide();
          jQuery("#contribution_page_id").hide();
        }
      
      });
      
      if (jQuery("#is_members_only_event input[type=checkbox]").attr("checked") == false){
        jQuery("#allowed_membership_types").hide();
        jQuery("#membership_purchase_url").hide();
        jQuery("#contribution_page_id").hide();
      }
      
    });
  {/literal}
  </script>
    
{else}
    <div id="help">{ts}Online registration tab needs to be enabled for this event to set the members only event settings.{/ts}</div>
{/if}