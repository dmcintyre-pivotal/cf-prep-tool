<?php
/**
* Use this script to help test the network environment prior to a PCF installation
*
**/
require_once('./lib/common.php');

$oFormatter = new TextFormatter();

if(!$oSettingsChecks->failed()) {

    $oRoutingSuite = new RoutingTestSuite("Routing tests");
    foreach($aDomains as $oDomain) {
        $oRoutingSuite->test(new RoutingTest("HTTP ".$oDomain->getName()." test", "http://toto.".$oDomain->getBase().'/json'));
    }
    foreach($aDomains as $oDomain) {
        $oRoutingSuite->test(new RoutingTest("HTTPS ".$oDomain->getName()." test", "https://tata.".$oDomain->getBase().'/json'));
    }
}

include('./lib/index-txt.tpl');

if($oSettingsChecks->failed() || $oRoutingSuite->failed()) {

    echo "*** Some tests failed. Check the log **\n";
    exit(1);
    
}

echo "*** All test passed ***\n";
