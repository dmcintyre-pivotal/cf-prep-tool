<?php
/**
* Emulate the GoRouter /health endpoint
* Supposed to return a 200 with body 'ok' 
**/
require_once('./lib/common.php');

$oResponse = new Response();

$aRouterIps = $oEnv->asArray('goRouters');
$aHealthyHosts = $oEnv->asArray('healthyHosts');

$sHost = $_SERVER['SERVER_NAME'];
$sUri = $_SERVER['REQUEST_URI'];

$iPort = $oEnv->value('healthPort');
$iStatus = $oEnv->value('unhealthyStatus');
$sEndpoint = $oEnv->value('healthEndpoint');

/**
* This script should only be called by IP address on the specified health port
**/

if(!in_array($sHost, $aRouterIps)) {
    $oResponse->setStatus(400);
    $oResponse->append('Health check failed: illegal source IP '.$sHost);
} else if($_SERVER['SERVER_PORT'] != $iPort) {
    $oResponse->setStatus();
    $oResponse->append('Health check failed: illegal port '.$iPort);
} else if($sUri != $sEndpoint) {
    $oResponse->setStatus();
    $oResponse->append('Health check failed: unexpected uri '.$sUri);
} else if(in_array($sHost, $aHealthyHosts)) {
    $oResponse->setStatus(200);
    $oResponse->append('ok');
} else {
    $oResponse->setStatus($iStatus);
    $oResponse->append($iStatus == 200 ? 'ok' : 'error');
}

$oResponse->respond();
