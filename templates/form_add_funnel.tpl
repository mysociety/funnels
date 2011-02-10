<span id='FunnelForm' style="display:none;">
<form>
<table class="dataTable tableFormFunnels">
	<tr class="first">
		<th colspan="3">{'Funnels_Funnel'|translate} </th>
	<tr>
	<tbody>
		<tr>
			<td></td>
      <td><span class="cellHeader">{'Funnels_FunnelGoal'|translate}</span>
          <div class="funnelInlineHelp">{'Funnels_FunnelGoalHelp'|translate}</div></td>
			<td>
				<select name="goal_id" class="inp">
					{foreach from=$goalsWithoutFunnels item=goal}
					<option value="{$goal.idgoal}">{$goal.name}</option>
					{/foreach}
				</select>
				<span id="goal_name">	
				</span>
			</td>
		</tr>
		<tr>
		  <td></td>
		  <td colspan="2">
		    {'Funnels_StepHelp'|translate}
		  </td>
		</tr>
		<tr>
			<td></td>
			<td>
			  <span class="cellHeader">{'Funnels_StepUrl'|translate}</span>
			  <div class="funnelInlineHelp">{'Funnels_StepUrlHelp'|translate}</div>
			</td>
			<td>
			  <span class="cellHeader">{'Funnels_StepName'|translate}</span>
				<div class="funnelInlineHelp">{'Funnels_StepNameHelp'|translate}</div>
			</td>
		</tr>
		{section name=funnel_step start=1 loop=11 step=1}
			<tr>
	            <td>{'Funnels_Step'|translate} {$smarty.section.funnel_step.index}</td>
				<td>
					<input type="text" class="inp" name="step_url" size="40" id="step_url_{$smarty.section.funnel_step.index}" value="" />
				</td>
				<td>
					<input type="text" class="inp" name="step_name" size="40" id="step_name_{$smarty.section.funnel_step.index}" value="" />
				</td>
			</tr>
		{/section}

	</tbody>
</table>
	<input type="hidden" name="methodFunnelAPI" value="" />	
	<input type="hidden" name="funnelIdUpdate" value="" />
	<input type="hidden" name="goalId" value="" />
    <input type="submit" value="" name="submit" id="funnel_submit" class="but_submit" />
</form>
</span>