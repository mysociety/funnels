<?php
/**
 * Piwik - Open source web analytics
 * Funnel Plugin - Analyse and visualise goal funnels
 * 
 * @link http://mysociety.org
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 * @version 0.1
 * 
 * @category Piwik_Plugins
 * @package Piwik_Funnels
 */
 
class Piwik_Funnels_Controller extends Piwik_Controller
{		
	const ROUNDING_PRECISION = 2;
	
	function __construct()
	{
		parent::__construct();
		$this->idSite = Piwik_Common::getRequestVar('idSite');
		$this->funnels = Piwik_Funnels_API::getInstance()->getFunnels($this->idSite);
	}

	function addNewFunnel()
	{
		$view = Piwik_View::factory('add_new_funnel');
		$this->setGeneralVariablesView($view);
		$view->goalsPluginDeactived = ! Piwik_PluginsManager::getInstance()->isPluginActivated('Goals');
		$view->userCanEditFunnels = Piwik::isUserHasAdminAccess($this->idSite);
		$view->onlyShowAddNewFunnel = true;
		$view->goalsWithoutFunnels = Piwik_Funnels_API::getInstance()->getGoalsWithoutFunnels($this->idSite);
		echo $view->render();
	}
	
	function index() 
	{
		$view = Piwik_View::factory('overview');
		$this->setGeneralVariablesView($view);
		$view->funnels = $this->funnels;
		$view->funnelsJSON = json_encode($this->funnels);
		$view->userCanEditFunnels = Piwik::isUserHasAdminAccess($this->idSite);
		$view->goalsWithoutFunnels = Piwik_Funnels_API::getInstance()->getGoalsWithoutFunnels($this->idSite);
		echo $view->render();
	}
	
	function funnelReport()
	{
		$idFunnel = Piwik_Common::getRequestVar('idFunnel', null, 'int');
		if(!isset($this->funnels[$idFunnel]))
		{
			Piwik::redirectToModule('Funnels', 'index', array('idFunnel' => null));
		}
		// Set up the view
		$view = Piwik_View::factory('single_funnel');
		$this->setGeneralVariablesView($view);
		
		// Get the funnel and related goal data
		$funnelDefinition = $this->funnels[$idFunnel];
		$idGoal = $funnelDefinition['idgoal'];
		$goal_request = new Piwik_API_Request("method=Goals.get&format=original&idGoal=$idGoal");
		$datatable = $goal_request->process();
		$dataRow = $datatable->getFirstRow();
		$view->goal_conversions = $dataRow->getColumn(Piwik_Goals::getRecordName('nb_conversions', $idGoal));
		$view->name = $funnelDefinition['goal_name'];
		
		// Get the data on each funnel step 
		$funnel_data = $this->getMetricsForFunnel($idFunnel);
		foreach ($funnelDefinition['steps'] as &$step) {
			$recordName = Piwik_Funnels::getRecordName('nb_actions', $idFunnel, $step['idstep']);
			$step['nb_actions'] = $funnel_data->getColumn($recordName);
			$recordName = Piwik_Funnels::getRecordName('nb_next_step_actions', $idFunnel, $step['idstep']);
			$step['nb_next_step_actions'] = $funnel_data->getColumn($recordName);
			$recordName = Piwik_Funnels::getRecordName('percent_next_step_actions', $idFunnel, $step['idstep']);
			$step['percent_next_step_actions'] = round($funnel_data->getColumn($recordName), self::ROUNDING_PRECISION);
			$step['referring_actions'] = array();
			$refUrls = $this->getRefUrls($idFunnel, $step['idstep']);
			
			foreach($refUrls->getRows() as $row) {
				$label = $this->labelOrDefault($row->getColumn('label'), '(entrance)');
				$step['referring_actions'][] = array('label' => $label, 'value' => $row->getColumn('value')); 
			}
			
			$step['next_actions'] = array();
			$nextUrls = $this->getNextUrls($idFunnel, $step['idstep']);
			foreach($nextUrls->getRows() as $row) {
				$label = $this->labelOrDefault($row->getColumn('label'), '(exit)');
				$step['next_actions'][] = array('label' => $label, 'value' => $row->getColumn('value')); 
			}
		}
		
		// What percent of people who visited the first funnel step converted at the end of the funnel?
		$recordName = Piwik_Funnels::getRecordName('conversion_rate', $idFunnel, false);
		$view->conversion_rate = round($funnel_data->getColumn($recordName), self::ROUNDING_PRECISION);

		// Let the view access the funnel steps
		$view->steps = $funnelDefinition['steps'];
		echo $view->render();

	}

	protected function labelOrDefault($label, $default)
	{
		if ($label == '')
		{
			return $default;
		}
		return $label;
	}

	protected function getNextUrls($idFunnel, $idStep)
	{	
		
		$request = new Piwik_API_Request("method=Funnels.getNextUrls&format=original&idFunnel=$idFunnel&idStep=$idStep");
		$dataTable = $request->process();
		return $dataTable;
		
	}
	
	protected function getRefUrls($idFunnel, $idStep)
	{	
		
		$request = new Piwik_API_Request("method=Funnels.getRefUrls&format=original&idFunnel=$idFunnel&idStep=$idStep");
		$dataTable = $request->process();
		return $dataTable;
		
	}
	
	protected function getMetricsForFunnel($idFunnel)
	{
		$request = new Piwik_API_Request("method=Funnels.get&format=original&idFunnel=$idFunnel");
		$dataTable = $request->process();
		$dataRow = $dataTable->getFirstRow();
		
		return $dataRow;
	}
}
