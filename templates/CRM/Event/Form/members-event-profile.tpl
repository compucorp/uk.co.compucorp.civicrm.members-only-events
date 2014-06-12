
<div id="members-only-event-profile" name="members-only-event-profile">
  <div class="crm-section editrow_a-section form-item" id="editrow-mem_ID">
    <div class="label">
      Membership ID:
      <span class="crm-marker" title="This field is required.">*</span>
    </div>
    <div class="content">
      <input maxlength="64" size="30" name="member_ID" type="text" value="" id="member_ID" class="form-text big required">
      <input type='button' id='check_membership' name='check_membership' value='Check Membership'>
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

        //result texts
        var checking_html = 'Checking...';  
  
        //when button is clicked  
        cj('#check_membership').click(function(){  
          //else show the cheking_text and run the function to check
          if(cj(this).attr("value") == "Check Membership"){
            cj('#membership_result').show(); 
            cj('#membership_result').html(checking_html);  
            search_membership();
          }else{
            cj('#editrow-first_name').hide();
            cj('#editrow-last_name').hide();
            cj('#editrow-email-Primary').hide();

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
                
                cj('#editrow-mem_name').show();

                cj('#editrow-first_name').show();
                cj('#editrow-last_name').show();
                cj('#editrow-email-Primary').show();

                cj("[name='member_ID']").attr('disabled','disabled');
                cj("[name='member_ID']").attr('style', 'background:#C0C0C0');
                cj("[name='member_name']").attr('disabled','disabled');
                cj("[name='member_name']").attr('style', 'background:#C0C0C0');

                var fields = new Array(
                  cj("[name='first_name']"),
                  cj("[name='last_name']"),
                  cj("[name='email-Primary']")
                );

                cj.each(fields, function(key, value){
                  if(cj.trim(value.val())){
                    value.attr('disabled','disabled');
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

//TODO: Fix required field for Membership ID (need to check membership before submit);
//TODO: Fix duplicated ticket purchase checking;

</script>
{/literal}