{literal}
<style>
.goalInlineHelp{
	color:#9B9B9B;
}
.tableForm { 
	width:700px;
}
</style>
{/literal}
<span id='FunnelForm' style="display:none;">
<form>
<table class="tableForm">
	<tbody>
		<tr>
			<td></td>
            <td>{'Funnels_FunnelGoal'|translate} </td>
			<td>
				<select name="goal_id">
					{foreach from=$goalsWithoutFunnels item=goal}
					<option value="{$goal.idgoal}">{$goal.name}</option>
					{/foreach}
				</select>
				<span id="goal_name">	
				</span>
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td></td>
			<td style="font-weight:bold">{'Funnels_StepUrl'|translate} </td>
			<td style="font-weight:bold">{'Funnels_StepName'|translate}</td>
		</tr>
		{section name=funnel_step start=1 loop=4 step=1}
			<tr>
	            <td>{'Funnels_Step'|translate} {$smarty.section.funnel_step.index}</td>
				<td>
					<input type="text" name="step_url" id="step_url_{$smarty.section.funnel_step.index}" value="" />
				</td>
				<td>
					<input type="text" name="step_name" id="step_name_{$smarty.section.funnel_step.index}" value="" />
				</td>
			</tr>
		{/section}
		<tr>
			<td colspan="2" style="border:0">
				<input type="hidden" name="methodFunnelAPI" value="" />	
				<input type="hidden" name="funnelIdUpdate" value="" />
				<input type="hidden" name="goalId" value="" />
				<center>
	            <input type="submit" value="" name="submit" id="funnel_submit" class="submit" />
	            </center>
			</td>
		</tr>
	</tbody>
</table>
</form>
</span>