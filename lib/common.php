<?php
require_once(__DIR__.'/Response.php');
require_once(__DIR__.'/IPAddress.php');
require_once(__DIR__.'/WildcardDomain.php');
require_once(__DIR__.'/Test.php');
require_once(__DIR__.'/TestSuite.php');
require_once(__DIR__.'/DomainTest.php');
require_once(__DIR__.'/RoutingTest.php');
require_once(__DIR__.'/RoutingTestSuite.php');
require_once(__DIR__.'/Settings.php');
require_once(__DIR__.'/HTMLFormatter.php');
require_once(__DIR__.'/TextFormatter.php');
require_once(__DIR__.'/JSONFormatter.php');

$oEnv = new Settings(__DIR__.'/../conf/settings.ini');
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
