<?php
require_once(__DIR__.'/Test.php');

class RoutingTest extends Test {

    private $sUrl;
    private $iErrorNumber = 0;
    private $sError = '';

    public function __construct($sName, $sUrl) {
        parent::__construct($sName);
        $this->sUrl = $sUrl;
        $this->run();
    }

    public function run() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $this->sUrl);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        $json = curl_exec($ch);
        if(curl_errno($ch)) {
            $this->iErrorNumber = curl_errno($ch);
            $this->sError = curl_error($ch);
            $this->setFailed();
            $this->append("Error for ".$this->sUrl." : $this->iErrorNumber : $this->sError");
        } else {
            $this->append(" HTTP CODE ".curl_getinfo($ch, CURLINFO_HTTP_CODE).' : '.$this->sUrl);
            $this->append("\n");
            $this->parse($json);
        }

        curl_close($ch);
    }

    public function parse($json) {
        $obj = json_decode($json);
        foreach($obj->results as $result) {
            $this->append($result->passed ? "OK " : "FAILED ");
            $this->append($result->test.' ');
            $this->append($result->message);
            $this->append("\n");
            if(!$result->passed) {
                $this->setFailed();
            }
        }
    }
}
