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
			'translationAvailable' => true,
			'TrackerPlugin' => true, // this plugin must be loaded during the stats logging
		);
	}
	
	/**
	 * @throws Exception if non-recoverable error
	 */
	public function install()
	{
	
	  $funnels_table_spec = '	`idsite` int(11) NOT NULL,
			                      `idgoal` int(11) NOT NULL,
			                      `idfunnel` int(11) NOT NULL, 
			                      PRIMARY KEY  (`idsite`,`idgoal`, `idfunnel`) ';
	  self::createTable('funnels', $funnels_table_spec);
  
    $funnel_steps_table_spec = " `idfunnel` int(11) NOT NULL, 
		                             `idstep` int(11) NOT NULL, 
		                             `name` varchar(50) NOT NULL,
		                             `match_attribute` varchar(20) NOT NULL,
	                               `pattern` varchar(255) NOT NULL,
	                               `pattern_type` varchar(10) NOT NULL,
	                               `case_sensitive` tinyint(4) NOT NULL,
	                               `deleted` tinyint(4) NOT NULL default '0',
		                             PRIMARY KEY  (`idfunnel`, `idstep`) ";
		self::createTable('funnel_steps', $funnel_steps_table_spec);
	}
	
	/**
	 * @throws Exception if non-recoverable error
	 */
	public function uninstall()
	{
		$sql = "DROP TABLE ". Piwik::prefixTable('funnels') ;
		Piwik_Exec($sql);		
		$sql =  "DROP TABLE ". Piwik::prefixTable('funnel_steps') ;
		Piwik_Exec($sql);	
	}

	function createTable( $tablename, $spec ) 
	{
	  // we catch the exceptions in order to check if it's just 
		// a '[table|column] already exists' error
	  try{
			$sql = "CREATE TABLE ". Piwik::prefixTable($tablename)." ( $spec )  DEFAULT CHARSET=utf8 " ;
			Piwik_Exec($sql);
		} catch(Exception $e){
			// mysql code error 1050:table already exists
			// see bug #153 http://dev.piwik.org/trac/ticket/153
			// OK if just reinstalling the plugin
			if(!Zend_Registry::get('db')->isErrNo($e, '1050'))
			{
				throw $e;
			}
		}
	  
	}
	
}

