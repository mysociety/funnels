<?php
if(!defined("PIWIK_PATH_TEST_TO_ROOT")) {
	define('PIWIK_PATH_TEST_TO_ROOT', getcwd().'/../../..');
}
if(!defined('PIWIK_CONFIG_TEST_INCLUDED'))
{
	require_once PIWIK_PATH_TEST_TO_ROOT . "/tests/config_test.php";
}

require_once PIWIK_PATH_TEST_TO_ROOT . '/tests/core/Database.test.php';

class Test_Piwik_Funnels extends Test_Database
{
    function setUp()
    {
    	parent::setUp();
    
    }
    

}