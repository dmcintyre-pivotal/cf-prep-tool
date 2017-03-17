<?php
/**
* Use this script to help test the network environment prior to a PCF installation
*
**/
require_once(__DIR__.'/common.php');

$oSystemChecks = new TestSuite('Networking');

if(!$oSettingsChecks->failed()) {

    $oIPCheck = $oSystemChecks->test(new Test('Source IP'));

    if(!in_array($_SERVER['SERVER_ADDR'], $aRouterIps)) {
        $oIPCheck->setFailed();
        $oIPCheck->append('Unexpected Local IP '.$_SERVER['SERVER_ADDR']);
    } else {
        $oIPCheck->append('Request on '.$_SERVER['SERVER_ADDR']);
    }
    
    $oHostCheck = $oSystemChecks->test(new Test('Request host'));

    $sHost = $_SERVER['HTTP_HOST'];
    /**
    * Hostname must be in one of the apps or sys domain.
    * Only allow an actual IP if the request URL is for the health endpoint
    **/
    $bHosted = false;
    foreach($aDomains as $oDomain) {
        if($oDomain->hosts($sHost)) {
            $oHostCheck->append('Host '.$sHost.' in domain '.$oDomain->getName());
            $bHosted = true;
        }
    }
    
    if(!$bHosted) {
        $oHostCheck->setFailed();
        $oHostCheck->append('Host '.$sHost.' is not in any domain');
    }
    
    
    if($oEnv->value('enableForwardedFor')) {
        $oForwardedForCheck = $oSystemChecks->test(new Test('X-FORWARDED-FOR'));
        if(!array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $oForwardedForCheck->setFailed();
            $oForwardedForCheck->append('Header not set');
        } else {
            $oForwardedForCheck->append('Set to '.$_SERVER['HTTP_X_FORWARDED_FOR']);
        }
    }
    
    if($oEnv->value('enableForwardedPort')) {
        $oForwardedProtoCheck = $oSystemChecks->test(new Test('X-FORWARDED-PROTO'));
        if(!array_key_exists('HTTP_X_FORWARDED_PROTO', $_SERVER)) {
            $oForwardedProtoCheck->setFailed();
            $oForwardedProtoCheck->append('Header not set');
        } else {
            $oForwardedProtoCheck->append('Set to '.$_SERVER['HTTP_X_FORWARDED_PROTO']);
        }
    }
}

