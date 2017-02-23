Test Results
================
<?php

$oFormatter->show($oSettingsChecks);

if(!$oSettingsChecks->failed()) {
    
    $oFormatter->show($oRoutingSuite);
    
}