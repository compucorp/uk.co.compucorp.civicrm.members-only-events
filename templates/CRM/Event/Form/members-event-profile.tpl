
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
        cj("[name='exist_ID']").attr('disabled','disabled');

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
            cj("[name='exist_ID']").attr('disabled','disabled');
            cj("[name='exist_ID']").val("");

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
  
        CRM.api('Contact','get',{'version' :'3', 'id' : memberID}
          ,{ success:function (data){
            if(data["values"] == undefined || data["values"] == null || data["values"].length == 0){
              cj('#membership_result').html("Not a valid membership ID.");
            }else{
              cj.each(data['values'], function(key, value) {
                //result texts
                cj("[name='member_name']").val(value.display_name);
                cj('#membership_result').html("Successful.");

                cj("[name='first_name']").val(value.first_name);
                cj("[name='last_name']").val(value.last_name);
                cj("[name='email-Primary']").val(value.email);
                cj("[name='exist_ID']").val(value.id);
                cj("[name='exist_ID']").removeAttr('disabled');
                
                cj('#editrow-mem_name').show();

                cj('#editrow-first_name').show();
                cj('#editrow-last_name').show();
                cj('#editrow-email-Primary').show();

                cj("[name='member_ID']").attr('readonly','readonly');
                cj("[name='member_ID']").attr('style', 'background:#C0C0C0');
                cj("[name='member_name']").attr('readonly','readonly');
                cj("[name='member_name']").attr('style', 'background:#C0C0C0');

                var fields = new Array(
                  cj("[name='first_name']"),
                  cj("[name='last_name']"),
                  cj("[name='email-Primary']")
                );

                cj.each(fields, function(key, value){
                  if(cj.trim(value.val())){
                    value.attr('readonly','readonly');
                    value.attr('style', 'background:#C0C0C0');
                  }
                });

                cj("#check_membership").attr('value', 'Reset');
              });
            }
          },
          error: function(){
            //result texts
            cj('#membership_result').html("Fail.");
          }

        });
}

/*TODO:assign this argument from PHP
  function checkMemberEvent() {

    var event_id = '{/literal}{$event.id}{literal}';
    var result = false;

    CRM.api('MembersOnlyEvent', 'get', {'version' : '3', 'event_id' : event_id}
      ,{
        success:function(data){
        if(data["values"] == undefined || data["values"] == null || data["values"].length == 0){
          result = false;
          fieldsAction(3);
          cj('#user_profile').show();
        }else{
          cj.each(data['values'], function(key, value) {
            if(value.members_event_type==1){
              result = false;
              fieldsAction(3);
              cj('#user_profile').show();
            }else{
              result = true;
              checkMemberPrice();
            }
          });
        }
      },

      error: function(){
        result = false;
        fieldsAction(3);
        cj('#user_profile').show();
      }
    });
  }*/
  
  //TODO:maybe add a configuration in admin to enable the switch of letting member email be used for additional participants as well

  function checkMemberPrice() {

    var event_id = '{/literal}{$event.id}{literal}';
    var pfv_id = cj('#priceset input:checked').attr('value');
    var result = false;

    CRM.api('MembersEventPrice', 'get', {'version' : '3', 'event_id' : event_id, 'price_value_id' : pfv_id}
      ,{success:function(data){
        if(data["values"] == undefined || data["values"] == null || data["values"].length == 0){
          fieldsAction(2);
          fieldsAction(3);
          cj('#members-only-event-profile').hide();
          cj('#user_profile').show();
          result = false;
        }else{
          cj.each(data['values'], function(key, value) {
            if(value.is_member_price==1){
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
          });
        }
      },

      error: function(){
        fieldsAction(2);
        fieldsAction(3);
        cj('#members-only-event-profile').hide();
        cj('#user_profile').show();
        result = false;
      }
    });

    return result;
  }

  function fieldsAction(test){
    cj("[name='first_name']").val("");
    cj("[name='last_name']").val("");
    cj("[name='email-Primary']").val("");
    cj("[name='member_ID']").val("");
    cj("[name='member_name']").val("");
    cj('#membership_result').hide(); 
    cj('#editrow-mem_name').hide();
    cj('#check_membership').attr('value', 'Search Member');
    cj("[name='member_ID']").removeAttr('readonly');
    cj("[name='member_name']").removeAttr('readonly');
    cj("[name='member_ID']").attr('style', 'background:white');
    cj("[name='member_name']").attr('style', 'background:white');
    if(test==1){
      cj('#editrow-first_name').hide();
      cj('#editrow-last_name').hide();
      cj('#editrow-email-Primary').hide();
    }else if(test==2){
      cj("[name='first_name']").removeAttr('readonly');
      cj("[name='last_name']").removeAttr('readonly');
      cj("[name='email-Primary']").removeAttr('readonly');
      cj("[name='first_name']").attr('style', 'background:white');
      cj("[name='last_name']").attr('style', 'background:white');
      cj("[name='email-Primary']").attr('style', 'background:white');
    }else if(test==3){
      cj('#editrow-first_name').show();
      cj('#editrow-last_name').show();
      cj('#editrow-email-Primary').show();
    }
  }
//TODO: Fix required field for Membership ID (need to check membership before submit);
//TODO: Fix duplicated ticket purchase checking;

</script>
{/literal}