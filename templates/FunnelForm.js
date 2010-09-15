// init the funnel form with existing funnel values, if any
function initFunnelForm(funnelMethodAPI, submitText, goalName, goalId, steps, funnelId)
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
	if (steps != undefined) {
  	$.each(steps, function(index, value) { 
  	  $('#step_name_' + value.idstep).val(value.name);
  	  $('#step_url_' + value.idstep).val(value.url);
  	});
  }
	$('input[name=methodFunnelAPI]').val(funnelMethodAPI);
	$('#funnel_submit').val(submitText);
}

function showAddNewFunnel()
{
	$("#FunnelForm").show();
	$("#EditFunnels").hide();
	piwikHelper.lazyScrollTo("#AddEditFunnels", 400);
	return false;
}

function showEditFunnels()
{
	$("#EditFunnels").show();
	$("#FunnelForm").hide();
	piwikHelper.lazyScrollTo("#AddEditFunnels", 400);
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
		var steps = funnel.steps;
		initFunnelForm("Funnels.updateFunnel", _pk_translate('Funnels_UpdateFunnel_js'), goalName, goalId, steps, funnel.idfunnel);
		showAddNewFunnel();
		return false;
	});
	
	$('a[name=linkDeleteFunnel]').click( function() {
		var funnelId = $(this).attr('id');
		var funnel = piwik.funnels[funnelId];
    if(confirm(sprintf(_pk_translate('Funnels_DeleteFunnelConfirm_js'), '"'+funnel.goal_name+'"')))
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
	var ajaxRequest = piwikHelper.getStandardAjaxConf('funnelAjaxLoading', 'funnelAjaxError');
	piwikHelper.lazyScrollTo("#AddEditFunnels", 400);
	
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
	var ajaxRequest = piwikHelper.getStandardAjaxConf('funnelAjaxLoading', 'funnelAjaxError');
	piwikHelper.lazyScrollTo("#AddEditFunnels", 400);
	var parameters = {};
	
	parameters.idSite = piwik.idSite;
  // Updating an existing funnel for a goal
  parameters.idGoal = $('input[name=goalId]').val();
  // New funnel 
  if (parameters.idGoal == ''){
	  parameters.idGoal = $('[name=goal_id]').val();
  }
  parameters.idFunnel = $('input[name=funnelIdUpdate]').val();
  
  parameters.steps = {};
  // Funnel steps
  $('input[name=step_name]').each(function(index){
     
    var id_parts = this.id.split('_');
    var id = id_parts[id_parts.length - 1];
    var name = $(this).val();
    var url = $("#step_url_" + id).val();
    if (url != '' || name != ''){
      parameters.steps[id] = {};
      parameters.steps[id]['name'] = encodeURIComponent(name);
      parameters.steps[id]['url'] = encodeURIComponent(url);
      parameters.steps[id]['id'] = id;
    }
  });
  
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