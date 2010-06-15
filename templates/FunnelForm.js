// init the funnel form with existing funnel values, if any
function initFunnelForm(funnelMethodAPI, submitText, goalName, goalId, funnelId)
{
  if (goalId != undefined){
    $('input[name=goalIdUpdate]').val(goalId);
    $('[name=goal_id]').hide();
    $('#goal_name').text(goalName);
    $('#goal_name').show();
  }else{
    $('input[name=goalIdUpdate]').val('');
    $('[name=goal_id]').show();
    $('#goal_name').hide();
  }
	if(funnelId != undefined) {
		$('input[name=funnelIdUpdate]').val(funnelId);
	}
	$('input[name=methodFunnelAPI]').val(funnelMethodAPI);
	$('#funnel_submit').val(submitText);
}

function showAddNewFunnel()
{
	$("#FunnelForm").show();
	$("#EditFunnels").hide();
	$.scrollTo("#AddEditFunnels", 400);
	return false;
}

function showEditFunnels()
{
	$("#EditFunnels").show();
	$("#FunnelForm").hide();
	$.scrollTo("#AddEditFunnels", 400);
	return false;
}

function bindFunnelForm()
{
	
	$('#funnel_submit').click( function() {
		// prepare ajax query to API to add funnel
		ajaxRequestAddEditFunnel = getAjaxAddEditFunnel();
		$.ajax( ajaxRequestAddEditFunnel );
		return false;
	});
	
	$('a[name=linkAddNewFunnel]').click( function(){ 
		initAndShowAddFunnelForm();
	} );
}

function bindListFunnelEdit()
{
	$('a[name=linkEditFunnel]').click( function() {
		var funnelId = $(this).attr('id');
		var funnel = piwik.funnels[funnelId];
		var goalId = funnel.idgoal;
		var goalName = funnel.goal_name;
		initFunnelForm("Funnels.updateFunnel", _pk_translate('Funnels_UpdateFunnel_js'), goalName, goalId, funnel.id);
		showAddNewFunnel();
		return false;
	});
	
	$('a[name=linkDeleteFunnel]').click( function() {
		var funnelId = $(this).attr('id');
		var funnel = piwik.funnels[funnelId];
    if(confirm(sprintf(_pk_translate('Funnels_DeleteFunnelConfirm_js'), '"'+funnel.id+'"')))
		{
			$.ajax( getAjaxDeleteFunnel( funnelId, funnel.idgoal ) );
		}
		return false;
	});

	$('a[name=linkEditFunnels]').click( function(){ 
		return showEditFunnels(); 
	} );
}

function getAjaxDeleteFunnel(idFunnel, idGoal)
{
	var ajaxRequest = piwikHelper.getStandardAjaxConf('funnelAjaxLoading');
	$.scrollTo("#AddEditFunnels", 400);
	
	var parameters = {};
	parameters.idSite = piwik.idSite;
	parameters.idFunnel =  idFunnel;
	parameters.idGoal =  idGoal;
	parameters.method =  'Funnels.deleteFunnel';
	parameters.module = 'API';
	parameters.format = 'json';
	parameters.token_auth = piwik.token_auth;
	ajaxRequest.data = parameters;
	return ajaxRequest;
}

function getAjaxAddEditFunnel()
{
	var ajaxRequest = piwikHelper.getStandardAjaxConf('funnelAjaxLoading');
	$.scrollTo("#AddEditFunnels", 400);
	var parameters = {};
	
	parameters.idSite = piwik.idSite;
  // Updating an existing funnel for a goal
  parameters.idGoal = $('input[name=goalId]').val();
  // New funnel 
  if (parameters.idGoal == ''){
	  parameters.idGoal = $('[name=goal_id]').val();
  }
	parameters.method =  $('input[name=methodFunnelAPI]').val();
	parameters.module = 'API';
	parameters.format = 'json';
	parameters.token_auth = piwik.token_auth;
	
	ajaxRequest.data = parameters;
	return ajaxRequest;
}

function initAndShowAddFunnelForm()
{
	initFunnelForm('Funnels.addFunnel', _pk_translate('Funnels_AddFunnel_js'));
	return showAddNewFunnel(); 
}