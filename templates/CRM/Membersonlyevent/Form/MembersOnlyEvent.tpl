{* Check if the online registration for this event is allowed, show notification message otherwise *}
<div class="crm-block crm-form-block crm-membersevent-event-config-form-block">
{if $isOnlineRegistration == 1}
  {* HEADER *}

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="top"}
  </div>
    
  {* FIELDS: (AUTOMATIC LAYOUT) *}

    <div class="crm-section" id="event_config_event_type">
      <div class="label">{$form.members_event_type.label}</div>
      <div class="content">{$form.members_event_type.html}</div>
      <div class="clear"></div>
    </div>
  	<div class="crm-section" id="event_config_member_url">
      <div class="label">{$form.membership_url.label}</div>      
      <div class="content">{$BASE_URL}{$form.membership_url.html}</div>
      <div class="clear"></div>
    </div>
    <div class="crm-section" id="event_config_member_price">
      <div class="label">{$form.membersPrice.label}</div>
      <div class="content">{$form.membersPrice.html}</div>
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
      jQuery("#event_config_member_url").show();
      jQuery("#event_config_member_price").hide();
      
      if (jQuery("input[name='members_event_type']:checked").val()==1){
        jQuery("#event_config_member_url").hide();
      }else if(jQuery("input[name='members_event_type']:checked").val()==3){
        jQuery("#event_config_member_price").show();
      }
      
      
      jQuery("input[name='members_event_type']").change(function(){
        
        if (jQuery(this).val()==2){
          jQuery("#event_config_member_url").show();
          jQuery("#event_config_member_price").hide();
        }else if(jQuery(this).val()==3){
          jQuery("#event_config_member_url").show();
          jQuery("#event_config_member_price").show();
        }else {
          jQuery("#event_config_member_url").hide();
          jQuery("#event_config_member_price").hide();
        }
      
      });
      
    });
  {/literal}
  </script>
    
{else}
    <div id="help">{ts}Online registration tab needs to be enabled for this event to set the members only event settings.{/ts}</div>
{/if}