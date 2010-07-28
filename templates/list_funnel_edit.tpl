<span id='EditFunnels' style="display:none;">
	<table class="dataTable tableFormFunnels">
	<thead style="font-weight:bold">
		<th>Id</th>
		<th>Goal</th>
        <th>{'General_Edit'|translate}</th>
        <th>{'General_Delete'|translate}</th>
	</thead>
	{foreach from=$funnels item=funnel}
	<tr>
		<td>{$funnel.idfunnel}</td>
		<td>{$funnel.goal_name}</td>
		
		<td><a href='#' name="linkEditFunnel" id="{$funnel.idfunnel}" class="link_but"><img src='themes/default/images/ico_edit.png' border="0" /> {'General_Edit'|translate}</a></td>
		<td><a href='#' name="linkDeleteFunnel" id="{$funnel.idfunnel}" class="link_but"><img src='themes/default/images/ico_delete.png' border="0" /> {'General_Delete'|translate}</a></td>
	</tr>
	{/foreach}
	</table>
</span>