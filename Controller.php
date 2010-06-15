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
}
