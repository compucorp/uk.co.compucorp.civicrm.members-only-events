
<div id="members-only-event-profile" name="members-only-event-profile">
  <div class="crm-section editrow_a-section form-item" id="editrow-mem_ID">
    <div class="label">
      {$form.member_ID.label}
      <span class="crm-marker" title="This field is required.">*</span>
    </div>
    <div class="content">
      {$form.member_ID.html}
      <input type='button' id='check_membership' name='check_membership' value='Search Member'>
      <input maxlength="64" size="30" name="exist_ID" type="text" value="" id="exist_ID" style="display:none" >
      <div id='membership_result'></div>  
    </div>
    <div class="clear"></div>
  </div>
  <div class="crm-section editrow_b-section form-item" id="editrow-mem_name">
    <div class="label">
      Member name:
    </div>
    <div class="content">
      <input maxlength="64" size="30" name="member_name" type="text" value="" id="mem_name" class="form-text big">
    </div>
    <div class="clear"></div>
  </div>
</div>

{literal}
<script type="text/javascript">
    cj(document).ready(function() {
        cj('#members-only-event-profile').hide();
        fieldsAction(1);
        cj("[id='exist_ID']").attr('disabled','disabled');

        //result texts
        var checking_html = 'Checking...';  
  
        //when button is clicked  
        cj('#check_membership').click(function(){  
          //else show the cheking_text and run the function to check
          if(cj(this).attr("value") == "Search Member"){
            cj('#membership_result').show(); 
            cj('#membership_result').html(checking_html);  
            search_membership();
          }else{
            cj('#editrow-first_name').hide();
            cj('#editrow-last_name').hide();
            cj('#editrow-email-Primary').hide();
            cj("[id='exist_ID']").attr('disabled','disabled');
            cj("[id='exist_ID']").val("");

            fieldsAction(2);
          }
        });  
    });
  
//function to check the existence of the membership  
function search_membership(){
  
        //get the membership ID 
        var memberID = cj("[name='member_ID']").val();

        if(!cj.isNumeric(memberID)){
          memberID = 0;
        }

        if({/literal}{$calls->getContact(2)}{literal}==1){
          cj("[id='mem_name']").val('{/literal}{$calls->_displayname}{literal}');
          cj('#membership_result').html("Successful.");

          cj("[id='first_name']").val('{/literal}{$calls->_firstname}{literal}');
          cj("[id='last_name']").val('{/literal}{$calls->_lastname}{literal}');
          cj("[id='email-Primary']").val('{/literal}{$calls->_email}{literal}');
          cj("[id='exist_ID']").val(2);
          cj("[id='exist_ID']").removeAttr('disabled');
                
          cj('#editrow-mem_name').show();

          cj('#editrow-first_name').show();
          cj('#editrow-last_name').show();
          cj('#editrow-email-Primary').show();

          cj("[id='member_ID']").attr('readonly','readonly');
          cj("[id='member_ID']").attr('style', 'background:#C0C0C0');
          cj("[id='mem_name']").attr('readonly','readonly');
          cj("[id='mem_name']").attr('style', 'background:#C0C0C0');

          var fields = new Array(
            cj("[id='first_name']"),
            cj("[id='last_name']"),
            cj("[id='email-Primary']")
          );

          cj.each(fields, function(key, value){
            if(cj.trim(value.val())){
              value.attr('readonly','readonly');
              value.attr('style', 'background:#C0C0C0');
            }
          });

          cj("#check_membership").attr('value', 'Reset');
          
        }else{
          cj('#membership_result').html("Not a valid membership ID.");
          cj("[id='exist_ID']").attr('disabled','disabled');
        }

}
  
  //TODO:maybe add a configuration in admin to enable the switch of letting member email be used for additional participants as well

  function checkMemberPrice() {

    var event_id = {/literal}{$event.id}{literal};
    var pfv_id = cj('#priceset input:checked').attr('value');
    var result = false;
    var pfv_type = 0;
    {/literal}{foreach from=$membersPriceOptions key=priceId item=priceType}{literal}
      if(pfv_id=={/literal}{$priceId}{literal}){
        pfv_type = {/literal}{$priceType}{literal};
      }
    {/literal}{/foreach}{literal}

    if(pfv_type==1){
      fieldsAction(1);
      cj('#members-only-event-profile').show();
      cj('#user_profile').show();
      result = true;
    }else{
      fieldsAction(2);
      fieldsAction(3);
      cj('#members-only-event-profile').hide();
      cj('#user_profile').show();
      result = false;
    }

    return result;
  }

  function fieldsAction(test){
    cj("[id='first_name']").val("");
    cj("[id='last_name']").val("");
    cj("[id='email-Primary']").val("");
    cj("[id='member_ID']").val("");
    cj("[id='mem_name']").val("");
    cj('#membership_result').hide(); 
    cj('#editrow-mem_name').hide();
    cj('#check_membership').attr('value', 'Search Member');
    cj("[id='member_ID']").removeAttr('readonly');
    cj("[id='mem_name']").removeAttr('readonly');
    cj("[id='member_ID']").attr('style', 'background:white');
    cj("[id='mem_name']").attr('style', 'background:white');
    if(test==1){
      cj('#editrow-first_name').hide();
      cj('#editrow-last_name').hide();
      cj('#editrow-email-Primary').hide();
    }else if(test==2){
      cj("[id='first_name']").removeAttr('readonly');
      cj("[id='last_name']").removeAttr('readonly');
      cj("[id='email-Primary']").removeAttr('readonly');
      cj("[id='first_name']").attr('style', 'background:white');
      cj("[id='last_name']").attr('style', 'background:white');
      cj("[id='email-Primary']").attr('style', 'background:white');
    }else if(test==3){
      cj('#editrow-first_name').show();
      cj('#editrow-last_name').show();
      cj('#editrow-email-Primary').show();
    }
  }

</script>
{/literal}