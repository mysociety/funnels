<link rel="stylesheet" type="text/css" href="plugins/Funnels/templates/funnel.css" />

<h2>{$name}</h2>
<table class="funnel">
	<tbody>
		{foreach from=$steps item=step}
		<tr>
			<td class="entry">
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
					{$step.nb_next_step_actions} ({$step.percent_next_step_actions}%) <span class="label">continued to next step</span>
				</h4>
			</td>
			<td class="exit">
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
								{$conversion_rate}% funnel conversion rate
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