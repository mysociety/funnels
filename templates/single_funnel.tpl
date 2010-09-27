<h2>{$name}</h2>
<table class="funnel">
	<tbody>
		{foreach from=$steps item=step}
		<tr>
			<td class="entry">
				<h4>{$step.nb_entry}</h4>
				{if $step.referring_actions|@count gt 0}
				<table>
					{foreach from=$step.referring_actions item=referrer}
					<tr>
						<td class="label">{$referrer.label}</td>
						<td class="value">{$referrer.value}</td>
					</tr>
					{/foreach}
				</table>
				{/if}
			</td>
			<td class="step">
				<div>
					<div>
						<div>
							<h3>
								<span class="label">{$step.name} {$step.url}</span> {$step.nb_actions}
							</h3>
							<b class="funnel_bar_graph">
								<b style="width:{$step.percent_next_step_actions}%"></b>
							</b>
						</div>
					</div>
				</div>
				<h4>
					{$step.nb_next_step_actions} ({$step.percent_next_step_actions}%) <span class="label">{'Funnels_ContinuedToNextStep'|translate}</span>
				</h4>
			</td>
			<td class="exit">
					<h4>{$step.nb_exit}</h4>
					{if $step.next_actions|@count gt 0}
					<table>
						{foreach from=$step.next_actions item=next}
						<tr>
							<td class="label">{$next.label}</td>
							<td class="value">{$next.value}</td>
						</tr>
						{/foreach}
					</table>
					{/if}
			</td>
		</tr>
		{/foreach}

		<tr>
			<td class="entry">
			</td>
			<td class="step">
				<div>
					<div>
						<div>
							<h3>
								<span class="label">{$name}</span> {$goal_conversions}
							</h3>
							<p>
								{$conversion_rate}% {'Funnels_FunnelConversionRate'|translate}
							</p>
						</div>
					</div>
				</div>
			</td>
			<td class="exit">
			
			</td>
		</tr>
	</tbody>
</table>