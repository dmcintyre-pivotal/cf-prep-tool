<?php
require_once('Response.php');
require_once('IPAddress.php');
require_once('WildcardDomain.php');
require_once('Test.php');
require_once('TestSuite.php');
require_once('DomainTest.php');
require_once('RoutingTest.php');
require_once('RoutingTestSuite.php');
require_once('Settings.php');
require_once('HTMLFormatter.php');
require_once('TextFormatter.php');
require_once('JSONFormatter.php');

$oEnv = new Settings('conf/settings.ini');
$oSettingsChecks = new TestSuite('settings.ini');
$aDomains = array();

$aDomains[] = new WildcardDomain('Global Apps Domain', $oEnv->value('globalAppsDomain'));
$aDomains[] = new WildcardDomain('Global System Domain', $oEnv->value('globalSystemDomain'));
$aDomains[] = new WildcardDomain('Local Apps Domain', $oEnv->value('localAppsDomain'));
$aDomains[] = new WildcardDomain('Local System Domain', $oEnv->value('localSystemDomain'));

foreach($aDomains as $oDomain) {
    $oSettingsChecks->test(new DomainTest($oDomain));
}

$aRouterIps = $oEnv->array('goRouters');


$oRouterIPsCheck = $oSettingsChecks->test(new Test('Router IPs'));

foreach($aRouterIps as $sIP) {
    $oIP = new IPAddress($sIP);
    if(!$oIP->isValid()) {
        $oRouterIPsCheck->setFailed('Invalid IP '.$sIP);
    }
}

$oRouterIPsCheck->append(' Set to '.join(',', $aRouterIps));
