{* Check if the online registration for this event is allowed, show notification message otherwise *}
<div class="crm-block crm-form-block crm-membersevent-event-config-form-block">
{if $isOnlineRegistration == 1}
  {* HEADER *}

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="top"}
  </div>
    
  {* FIELDS: (AUTOMATIC LAYOUT) *}

  {foreach from=$elementNames item=elementName}
    <div class="crm-section" id="{$form.$elementName.id}">
      <div class="label">{$form.$elementName.label}</div>
      <div class="content">{$form.$elementName.html}</div>
      <div class="clear"></div>
    </div>
  {/foreach}
  
  {* FOOTER *}

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
  
  <script type="text/javascript">
  {literal}
    jQuery(document).ready(function(){
      jQuery("#contribution_page_id").show();
      jQuery("#membersPrice").hide();
      
      if (jQuery("input[name='members_event_type']:checked").val()==1){
        jQuery("#contribution_page_id").hide();
      }else if(jQuery("input[name='members_event_type']:checked").val()==3){
        jQuery("#membersPrice").show();
      }
      
      
      jQuery("input[name='members_event_type']").change(function(){
        
        if (jQuery(this).val()==2){
          jQuery("#contribution_page_id").show();
          jQuery("#membersPrice").hide();
        }else if(jQuery(this).val()==3){
          jQuery("#contribution_page_id").show();
          jQuery("#membersPrice").show();
        }else {
          jQuery("#contribution_page_id").hide();
          jQuery("#membersPrice").hide();
        }
      
      });
      
    });
  {/literal}
  </script>
    
{else}
    <div id="help">{ts}Online registration tab needs to be enabled for this event to set the members only event settings.{/ts}</div>
{/if}
</div>