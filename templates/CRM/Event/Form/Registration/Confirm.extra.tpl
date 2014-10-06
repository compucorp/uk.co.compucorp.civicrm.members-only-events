{literal}
<script type="text/javascript">
  cj(document).ready(function() {
    {/literal}{if $membersEventType !== 1 && $purchaseForOther}{literal}
      cj('div[class="crm-section Email Address-section"]').hide();
    {/literal}{elseif $membersEventType !== 1 && !$purchaseForOther}{literal}
      cj('div[class="crm-section Email Address-section"]:gt(0)').hide();
    {/literal}{/if}{literal}
    cj("[name='_qf_Confirm_next']").val("PAY NOW");
  });
</script>
{/literal}