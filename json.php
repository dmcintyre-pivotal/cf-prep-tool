<?php
/**
* Use this script to help test the network environment prior to a PCF installation
*
**/
require_once('./lib/request-checks.php');

$oFormatter = new JSONFormatter();


$oFormatter->start();

if($oSettingsChecks->failed()) {
    $oFormatter->show($oSettingsChecks);
} else {
    $oFormatter->show($oSystemChecks);
}

$oFormatter->end();
