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
	}

	
}
