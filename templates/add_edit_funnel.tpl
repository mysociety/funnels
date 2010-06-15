<div id="AddEditFunnels">
{if isset($onlyShowAddNewFunnel)}
    <h2>{'Funnels_AddNewFunnel'|translate}</h2>
{else}
	<h2>
	{if count($goalsWithoutFunnels) == 0}
		{'Funnels_AddNewGoal'|translate:"<a onclick='' name='linkAddNewGoal'>+":"</a>"}
	{else}
		{'Funnels_AddNewFunnelLink'|translate:"<a onclick='' name='linkAddNewFunnel'>+":"</a>"}
	{/if} 
	{'Funnels_Or'|translate}
	{if count($funnels) > 0}
		{'Funnels_EditExistingFunnel'|translate:"<a onclick='' name='linkEditFunnels'>":"</a>"}
	{/if}
	</h2>
{/if}
{ajaxErrorDiv}
{ajaxLoadingDiv id=funnelAjaxLoading}

{if !isset($onlyShowAddNewFunnel)}
	{include file="Funnels/templates/list_funnel_edit.tpl"}
{/if}
	{include file="Funnels/templates/form_add_funnel.tpl"}

	<a id='bottom'></a>
</div>

{loadJavascriptTranslations plugins='Funnels'}
<script type="text/javascript" src="plugins/Funnels/templates/FunnelForm.js"></script>
<script type="text/javascript">

bindFunnelForm();
{if !isset($onlyShowAddNewFunnel)}
piwik.funnels = {$funnelsJSON};
bindListFunnelEdit();
{else}
initAndShowAddFunnelForm();
{/if}
</script>