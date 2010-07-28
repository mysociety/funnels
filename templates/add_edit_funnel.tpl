<div id="AddEditFunnels">
{if isset($onlyShowAddNewFunnel)}
    <h2>{'Funnels_AddNewFunnel'|translate}</h2>
{else}
<h2>{'Funnels_FunnelsManagement'|translate}</h2>
		<ul class='listCircle'>
			{if count($goalsWithoutFunnels) > 0}
			<li><a onclick='' name='linkAddNewFunnel'><u>{'Funnels_AddNewFunnelLink'|translate}</u></a></li>
			{/if} 
			{if count($funnels) > 0}
			<li><a onclick='' name='linkEditFunnels'><u>{'Funnels_EditExistingFunnel'|translate}</u></a></li>
			{/if}
		</ul>
		<br>
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