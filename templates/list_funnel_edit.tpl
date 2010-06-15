<span id='EditFunnels' style="display:none;">
	<table class="tableForm">
	<thead style="font-weight:bold">
		<td>Id</td>
		<td>Goal</td>
        <td>{'General_Edit'|translate}</td>
        <td>{'General_Delete'|translate}</td>
	</thead>
	{foreach from=$funnels item=funnel}
	<tr>
		<td>{$funnel.idfunnel}</td>
		<td>{$funnel.goal_name}</td>
		<td><a href='#' name="linkEditFunnel" id="{$funnel.idfunnel}"><img src='plugins/UsersManager/images/edit.png' border="0" /> {'General_Edit'|translate}</a></td>
		<td><a href='#' name="linkDeleteFunnel" id="{$funnel.idfunnel}"><img src='plugins/UsersManager/images/remove.png' border="0" /> {'General_Delete'|translate}</a></td>
	</tr>
	{/foreach}
	</table>
</span>