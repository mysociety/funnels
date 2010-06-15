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

/**
 *
 * @package Piwik_Funnels
 */
class Piwik_Funnels extends Piwik_Plugin
{
	/**
	 * Return information about this plugin.
	 *
	 * @see Piwik_Plugin
	 *
	 * @return array
	 */
	public function getInformation()
	{
		return array(
			'name' => 'Funnels',
			'description' => Piwik_Translate('Funnels_PluginDescription'),
			'author' => 'mySociety',
			'author_homepage' => 'http://mysociety.org/',
			'version' => '0.1',
			'homepage' => 'http://github.com/mysociety/funnels',
			'translationAvailable' => true,
			'TrackerPlugin' => true, // this plugin must be loaded during the stats logging
		);
	}
	
	function getListHooksRegistered()
	{
		$hooks = array(
			'Menu.add' => 'addMenus',
		);
		return $hooks;
	}
	
	function addMenus()
	{
		$idSite = Piwik_Common::getRequestVar('idSite');
	 	$funnels = Piwik_Funnels_API::getInstance()->getFunnels($idSite);
		$goalsWithoutFunnels = Piwik_Funnels_API::getInstance()->getGoalsWithoutFunnels($idSite);
		if(count($funnels) == 0 && count($goalsWithoutFunnels) > 0)
		{	
			Piwik_AddMenu('Funnels', 'Add a new Funnel', array('module' => 'Funnels', 'action' => 'addNewFunnel'));
		} else {
			Piwik_AddMenu('Funnels_Funnels', 'Funnels_Overview', array('module' => 'Funnels'));	
		}
	}
	
	/**
	 * @throws Exception if non-recoverable error
	 */
	public function install()
	{
		$funnels_table_spec = "`idsite` int(11) NOT NULL,
		                       `idgoal` int(11) NOT NULL,
		            		   `idfunnel` int(11) NOT NULL, 
		 					   `deleted` tinyint(4) NOT NULL default '0',
		                      	PRIMARY KEY  (`idsite`,`idgoal`, `idfunnel`) ";
		self::createTable('funnel', $funnels_table_spec);
  
		$funnel_steps_table_spec = "`idsite` int(11) NOT NULL,
									`idfunnel` int(11) NOT NULL, 
                         			`idstep` int(11) NOT NULL, 
                         			`name` varchar(50) NOT NULL,
                         			`match_attribute` varchar(20) NOT NULL,
                          			`pattern` varchar(255) NOT NULL,
                          			`pattern_type` varchar(10) NOT NULL,
                          			`case_sensitive` tinyint(4) NOT NULL,
                          			`deleted` tinyint(4) NOT NULL default '0',
                         			PRIMARY KEY  (`idfunnel`, `idsite`, `idstep`) ";
		self::createTable('funnel_step', $funnel_steps_table_spec);
		
		$log_table_spec = "`idvisit` int(11) NOT NULL,
	                      `idsite` int(11) NOT NULL,
	                      `visitor_idcookie` char(32) NOT NULL,
                    	  `server_time` datetime NOT NULL,
                    	  `idaction_url` int(11) default NULL,
                    	  `idlink_va` int(11) default NULL,
                    	  `referer_idvisit` int(10) unsigned default NULL,
                    	  `referer_visit_server_date` date default NULL,
                    	  `referer_type` int(10) unsigned default NULL,
                    	  `referer_name` varchar(70) default NULL,
                    	  `referer_keyword` varchar(255) default NULL,
                    	  `visitor_returning` tinyint(1) NOT NULL,
                    	  `location_country` char(3) NOT NULL,
                    	  `location_continent` char(3) NOT NULL,
                    	  `url` text NOT NULL,
                    	  `idgoal` int(11) NOT NULL,
                    	  `idfunnel` int(11) NOT NULL, 
                    	  `idstep` int(11) NOT NULL, 
                    	  PRIMARY KEY  (`idvisit`, `idstep`),
                    	  INDEX index_idsite_datetime ( idsite, server_time ) ";
		self::createTable('log_funnel_step', $log_table_spec);
	}
	
	/**
	 * @throws Exception if non-recoverable error
	 */
	public function uninstall()
	{
		$sql = "DROP TABLE ". Piwik::prefixTable('funnel') ;
		Piwik_Exec($sql);    
		$sql =  "DROP TABLE ". Piwik::prefixTable('funnel_step') ;
		Piwik_Exec($sql);  
		$sql =  "DROP TABLE ". Piwik::prefixTable('log_funnel_step') ;
		Piwik_Exec($sql);
	}

	function createTable( $tablename, $spec ) 
	{
		$sql = "CREATE TABLE IF NOT EXISTS ". Piwik::prefixTable($tablename)." ( $spec )  DEFAULT CHARSET=utf8 " ;
		Piwik_Exec($sql);
	}
	
}

