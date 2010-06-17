<?php
if(!defined("PIWIK_PATH_TEST_TO_ROOT")) {
	define('PIWIK_PATH_TEST_TO_ROOT', getcwd().'/../../..');
}
if(!defined('PIWIK_CONFIG_TEST_INCLUDED'))
{
	require_once PIWIK_PATH_TEST_TO_ROOT . "/tests/config_test.php";
}

require_once PIWIK_PATH_TEST_TO_ROOT . '/tests/core/Database.test.php';

require_once "Funnels/Funnels.php";

class Test_Piwik_Funnels extends Test_Database
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
    public function setUp()
    {
    	parent::setUp();
    
		// setup the access layer
    	$pseudoMockAccess = new FakeAccess;
		FakeAccess::$superUser = true;
		Zend_Registry::set('access', $pseudoMockAccess);
	
    }
    
    public function test_addFunnel()
    {
		
		$idSite = Piwik_SitesManager_API::getInstance()->addSite("test site", "http://www.example.com");
		$idGoal = Piwik_Goals_API::getInstance()->addGoal($idSite, "test goal", 'url', 'test', 'contains', 0, 0);
		$steps = array(1 => array('name' => 'step one', 'url' => 'http://www.example.com/step_one', 'id' => 1));
		$idFunnel = Piwik_Funnels_API::getInstance()->addFunnel($idSite, $idGoal, $steps);
    	$this->assertIsA( $idFunnel,'int');
		$funnels = Piwik_Funnels_API::getInstance()->getFunnels($idSite);
	    $this->assertTrue(count($funnels)===1);
		$funnel = $funnels[$idFunnel];
		$this->assertEqual($funnel['idsite'], $idSite);
		$this->assertEqual($funnel['idgoal'], $idGoal);
		$steps = $funnel['steps'];
		$this->assertTrue(count($steps)===1);
		$step = $steps[0];
		$this->assertEqual($step['name'], 'step one');
		$this->assertEqual($step['url'], 'http://www.example.com/step_one');
		$this->assertEqual($step['idstep'], 1);
		
    }	
    

}