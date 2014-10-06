<fieldset class="mem-fieldset" id="mem-fieldset">
<legend>Member Info</legend>
  <div class="crm-section editrow_a-section form-item" id="editrow-link">
  	Find your colleague's Member ID <a href="/members/member-directory" target="_blank">here</a>
  	<div class="clear"></div>
  </div>
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
</fieldset>

{literal}
<script type="text/javascript">
    cj(document).ready(function() {
      
        cj("[id='exist_ID']").attr('disabled','disabled');
        cj('fieldset.crm-profile:first').hide();
        cj('#editrow-mem_name').hide();

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
            cj('fieldset.crm-profile:first').hide();
            cj('#editrow-first_name').hide();
            cj('#editrow-last_name').hide();
            cj('#editrow-email-Primary').hide();
            cj('#editrow-current_employer').hide();
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

        var postUrl = {/literal}"{crmURL p=$ajaxUrl}"{literal};
        cj.post( postUrl, { member_Id: memberID }, function( contact ) {console.log(contact);
           if( memberID == 0 || !contact || contact.validmember == 0 || contact.validmember == null){

              cj('#membership_result').html("Not a valid membership ID.");
              cj("[id='exist_ID']").attr('disabled','disabled');
          
            }else if(contact.error!==0){

              cj('#membership_result').html("Not a valid membership ID.");
              cj("[id='exist_ID']").attr('disabled','disabled');

            }else{

              cj("[id='mem_name']").val(contact.displayname);
              cj('#membership_result').html("Successful.");
              cj('fieldset.crm-profile:first').show();

              cj("[id='first_name']").val(contact.firstname);
              cj("[id='last_name']").val(contact.lastname);
              cj("[id='email-Primary']").val(contact.email);
              cj("[id='current_employer']").val(contact.organizationname);
              cj("[id='exist_ID']").val(contact.id);
              cj("[id='exist_ID']").removeAttr('disabled');
                
              cj('#editrow-mem_name').show();

              cj('#editrow-first_name').show();
              cj('#editrow-last_name').show();
              cj('#editrow-current_employer').show();

              cj("[id='member_ID']").attr('readonly','readonly');
              cj("[id='member_ID']").attr('style', 'background:#C0C0C0');
              cj("[id='mem_name']").attr('readonly','readonly');
              cj("[id='mem_name']").attr('style', 'background:#C0C0C0');

              var fields = new Array(
                cj("[id='first_name']"),
                cj("[id='last_name']"),
                cj("[id='email-Primary']"),
                cj("[id='current_employer']")
              );

              cj.each(fields, function(key, value){
                if(cj.trim(value.val())){
                  value.attr('readonly','readonly');
                  value.attr('style', 'background:#C0C0C0');
                }
              });

              cj("#check_membership").attr('value', 'Reset');

            }
        }, "json" );
        

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
      var profileName = cj('fieldset.crm-profile').children('legend:first').text();
      cj('fieldset.crm-profile:first').children('legend:first').text("");
      cj('fieldset.mem-fieldset').children('legend:first').text(profileName);
      cj('#mem-fieldset').show();
      cj('fieldset.crm-profile:first').hide();
      result = true;
    }else{
      if(cj('fieldset.crm-profile:first').children('legend:first').text()==""){
        var profileName = cj('fieldset.mem-fieldset').children('legend:first').text();
        cj('fieldset.mem-fieldset').children('legend:first').text("");
        cj('fieldset.crm-profile').children('legend:first').text(profileName);
      }
      fieldsAction(2);
      fieldsAction(3);
      cj('#mem-fieldset').hide();
      cj('fieldset.crm-profile:first').show();
      result = false;
    }

    return result;
  }

  function fieldsAction(action){
    cj("[id='first_name']").val("");
    cj("[id='last_name']").val("");
    cj("[id='email-Primary']").val("");
    cj("[id='member_ID']").val("");
    cj("[id='mem_name']").val("");
    cj("[id='current_employer']").val("");
    cj('#membership_result').hide(); 
    cj('#editrow-mem_name').hide();
    cj('#check_membership').attr('value', 'Search Member');
    cj("[id='member_ID']").removeAttr('readonly');
    cj("[id='mem_name']").removeAttr('readonly');
    cj("[id='member_ID']").attr('style', 'background:white');
    cj("[id='mem_name']").attr('style', 'background:white');
    if(action==1){
      cj('#editrow-first_name').hide();
      cj('#editrow-last_name').hide();
      cj('#editrow-email-Primary').hide();
      cj('#editrow-current_employer').hide();
    }else if(action==2){
      cj("[id='first_name']").removeAttr('readonly');
      cj("[id='last_name']").removeAttr('readonly');
      cj("[id='email-Primary']").removeAttr('readonly');
      cj("[id='current_employer']").removeAttr('readonly');
      cj("[id='first_name']").attr('style', 'background:white');
      cj("[id='last_name']").attr('style', 'background:white');
      cj("[id='email-Primary']").attr('style', 'background:white');
      cj("[id='current_employer']").attr('style', 'background:white');
    }else if(action==3){
      cj('#editrow-first_name').show();
      cj('#editrow-last_name').show();
      cj('#editrow-email-Primary').show();
      cj('#editrow-current_employer').show();
    }
  }

</script>
{/literal}