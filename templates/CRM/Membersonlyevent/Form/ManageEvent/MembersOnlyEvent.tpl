{* Check if the online registration for this event is allowed, show notification message otherwise *}
<div class="crm-block crm-form-block crm-event-manage-membersonlyevent-form-block">

{if $isOnlineRegistration == 1}
  {* HEADER *}

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="top"}
  </div>

  {* FIELDS: (AUTOMATIC LAYOUT) *}

  <div class="crm-section" id="{$form.is_members_only_event.id}_div">
    <div class="label">{$form.is_members_only_event.label}</div>
    <div class="content">{$form.is_members_only_event.html}</div>
    <div class="clear"></div>
  </div>
  <div class="crm-section" id="{$form.price_field_id.id}_div">
    <div class="label">{$form.price_field_id.label}</div>
    <div class="content">{$form.price_field_id.html}</div>
    <div class="clear"></div>

    <fieldset name="price_member_match" id="price_member_match" >
    <h3>Choose the membership type for each price:</h3>
    {foreach from=$elementNames item=elementName}
    {if is_numeric(substr($form.$elementName.id, -1))}
      {if is_int(substr($form.$elementName.id, -1)/2) }
        <div class="crm-section" id="{$form.$elementName.id}_div">
          <div class="label">{$form.$elementName.label}</div>
          <div class="content">{$form.$elementName.html}

      {else}
          {$form.$elementName.html}</div>
          <div class="clear"></div>
        </div>
      {/if}
    {/if}
    {/foreach}
    </fieldset>

  </div>

</div>

  {* FOOTER *}

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>

  <script type="text/javascript">
  {literal}
  	cj("input[id^='price_value_selectitem_']").bind('input', function(e) {
  		var inputId = parseInt(cj(this).attr("id").substr(-1))+1;
  		if(cj(this).val()){
  			cj(this).prop('disabled', false);
  			cj("select[id^='membership_type_selectitem_"+inputId+"']").prop('disabled', false);;
  			cj("#"+cj(this).attr("id")+"_div").show();
  		}else{
  			cj(this).prop('disabled', true);
  			cj("select[id^='membership_type_selectitem_"+inputId+"']").prop('disabled', true);;
  			cj("#"+cj(this).attr("id")+"_div").hide();
  		}
	});

    cj(document).ready(function(){
      cj("input[id^='price_value_selectitem_']").hide();

      var priceFieldId = cj("select[id='price_field_id']").val();
      priceMemberMatch(priceFieldId);
      cj("#is_members_only_event_div input[type=checkbox]").click(function(){

        cj("#price_field_id").toggleClass( "required");

        if (cj(this).is(':checked')){
          cj("#price_field_id_div").show();
        }
        else {
          cj("#price_field_id_div").hide();
        }

      });

      if (!cj("#is_members_only_event_div input[type=checkbox]").is(':checked')){
      	cj("#price_field_id").toggleClass( "required");
        cj("#price_field_id_div").hide();
      }

    });

    cj("select[id='price_field_id']").on('change', function(e) {
    	var priceFieldId = e.target.options[e.target.selectedIndex].value;
    	cj("select[id^='membership_type_selectitem_']").val("");
    	priceMemberMatch(priceFieldId);
    });

    function priceMemberMatch(priceFieldId){
        cj("input[id^='price_value_selectitem_']").val("").trigger('input');

        var count = 0;
        var priceValueSet=0;

        {/literal}{foreach from=$priceValueList key=k item=i}{literal}
          if({/literal}{$k}{literal}==priceFieldId){
            priceValueSet = {/literal}{$i|@json_encode}{literal};
          }
        {/literal}{/foreach}{literal}

        cj.each(priceValueSet,function(index, value){
          cj("label[for='price_value_selectitem_"+count+"']").text(value["label"]);
          cj("input[id^='price_value_selectitem_"+count+"']").val(value["id"]).trigger('input');
          count+=2;
        });
    }

  {/literal}
  </script>

{else}
    <div id="help">{ts}Online registration tab needs to be enabled for this event to set the members only event settings.{/ts}</div>
{/if}
