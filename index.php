<?php
/**
* Use this script to help test the network environment prior to a PCF installation
*
**/
require_once(__DIR__.'/lib/request-checks.php');

$oFormatter = new HTMLFormatter();



$oFormatter->start();
$oFormatter->show($oSettingsChecks);

if(!$oSettingsChecks->failed()) {
    $oFormatter->show($oSystemChecks);
}

$oFormatter->end();
