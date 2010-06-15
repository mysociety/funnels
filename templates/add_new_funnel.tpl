{if $userCanEditFunnels}
	{if $goalsPluginDeactived}
		{'Funnels_GoalsPluginDeactivated'|translate}
	{else}
		{include file=Funnels/templates/add_edit_funnel.tpl}
	{/if}
{else}
	{'Funnels_NoFunnelsNeedAccess'|translate}
{/if}
