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
class Piwik_Funnels_API 
{
	static private $instance = null;
	static public function getInstance()
	{
		if (self::$instance == null)
		{            
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}
	
	public function getFunnels( $idSite )
	{
		$funnel_table = Piwik_Common::prefixTable('funnel');
		$goal_table = Piwik_Common::prefixTable('goal');
		$funnel_step_table = Piwik_Common::prefixTable('funnel_step');
		$funnels = Piwik_FetchAll("SELECT ".$funnel_table.".*, ".$goal_table.".name as goal_name
								   FROM   ".$funnel_table.", ".$goal_table." 
								   WHERE  ".$funnel_table.".idsite = ?
								   AND    ".$funnel_table.".idgoal = ".$goal_table.".idgoal
								   AND    ".$funnel_table.".deleted = 0", array($idSite));
		$funnelsById = array();
		foreach($funnels as &$funnel)
		{
			$funnel_steps = Piwik_FetchAll("SELECT *
											FROM   ".$funnel_step_table."
											WHERE  idsite = ?
											AND idfunnel = ?
											AND deleted = 0", array($idSite, $funnel['idfunnel']));
			$funnel['steps'] = $funnel_steps;
			$funnelsById[$funnel['idfunnel']] = $funnel;
		}
		return $funnelsById;
	}
	
	public function getFunnelsByGoal( $idSite )
	{
		$funnelsByGoal = array();
		$funnels = $this->getFunnels( $idSite );
		foreach($funnels as &$funnel)
		{
			$funnelsByGoal[$funnel['idgoal']] = $funnel;
		}
		return $funnelsByGoal;
	}
	
	public function getGoalsWithoutFunnels( $idSite )
	{
		$goals = Piwik_Goals_API::getInstance()->getGoals( $idSite );
		$funnelsByGoal = $this->getFunnelsByGoal( $idSite );
		$goalsWithoutFunnels = array();
		foreach($goals as &$goal)
		{			
			if(!array_key_exists($goal['idgoal'], $funnelsByGoal)) {
			   $goalsWithoutFunnels[$goal['idgoal']] = $goal;
			}
		}
		return $goalsWithoutFunnels;
	}
	
	public function addFunnel( $idSite, $idGoal, $steps )
	{
		Piwik::checkUserHasAdminAccess($idSite);
		// save in db
		$idFunnel = Piwik_FetchOne("SELECT max(idfunnel) + 1 
						     		FROM ".Piwik::prefixTable('funnel')." 
							     	WHERE idsite = ?", $idSite);
		if($idFunnel == false)
		{
			$idFunnel = 1;
		}
		Piwik_Query("INSERT INTO " . Piwik::prefixTable('funnel')."
					(idsite, idgoal, idfunnel)
					VALUES (?, ?, ?)", array($idSite, $idGoal, $idFunnel));
		Piwik_Common::regenerateCacheWebsiteAttributes($idSite);
		$this->updateFunnel($idSite, $idGoal, $idFunnel, $steps);
		return $idFunnel;
	}
	
	public function updateFunnel( $idSite, $idGoal, $idFunnel, $steps=array())
	{
		Piwik::checkUserHasAdminAccess($idSite);
		$currentStepIds = array();
		
		foreach($steps as &$step){
			$idStep = $step['id'];
			if (! is_numeric($idStep))
			{
				continue;
			}
			$currentStepIds[] = $idStep;
			$name = $this->checkName($step['name']);
			$url = $this->checkUrl($step['url']);
			$exists = Piwik_FetchOne("SELECT idstep
									FROM ".Piwik::prefixTable('funnel_step')." 
									WHERE idsite = ? 
									AND idfunnel = ?
									AND idstep = ?", array($idSite, $idFunnel, $idStep));
			if ($exists){
				Piwik_Query("UPDATE ".Piwik::prefixTable('funnel_step')."
							 SET name = ?, url = ?, deleted = 0
							 WHERE idsite = ? AND idstep = ? AND idfunnel = ?", 
							 array($name, $url, $idSite, $idStep, $idFunnel));	
			} else {
				Piwik_Query("INSERT INTO ". Piwik::prefixTable('funnel_step')."
							 (idsite, idfunnel, idstep, name, url) 
							 VALUES (?, ?, ?, ?, ?)", 
   							 array($idSite, $idFunnel, $idStep, $name, $url));
			}
		}
		// Any steps not currently defined should be set to deleted
		$whereClause = " WHERE idsite = ? AND idfunnel = ? ";
		$params = array($idSite, $idFunnel);
		if (count($currentStepIds) > 0) 
		{
			$currentStepIds = join(', ', $currentStepIds);
			$whereClause .= "AND idstep not in ($currentStepIds)";
		}
		Piwik_Query("UPDATE ". Piwik::prefixTable('funnel_step')."
					 SET deleted = 1
					 $whereClause", $params);
		Piwik_Common::regenerateCacheWebsiteAttributes($idSite);
	}
	
	public function deleteFunnel( $idSite, $idGoal, $idFunnel )
	{
		Piwik::checkUserHasAdminAccess($idSite);
		Piwik_Query("UPDATE ".Piwik::prefixTable('funnel')."
										SET deleted = 1
										WHERE idsite = ? 
										AND idgoal = ?
										AND idfunnel = ?",
									array($idSite, $idGoal, $idFunnel));
		Piwik_Common::regenerateCacheWebsiteAttributes($idSite);
	}
	
	private function checkName($name)
	{
		return urldecode($name);
	}
	
	private function checkUrl($url)
	{
		return urldecode($url);
	}
	
}