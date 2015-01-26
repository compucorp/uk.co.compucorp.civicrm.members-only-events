{if $isMembersOnlyEvent}
{literal}
<script type="text/javascript">
cj(document).ready(function(){
	cj('.'+'{/literal}{$memberFieldSection}{literal}'+'-section').hide();
});
</script>
{/literal}
{/if}