<?php
class Response {

    private $iStatus = 200;
    private $sBody = '';
    
    public function setStatus($iStatus) {
        $this->iStatus = $iStatus;
    }
    
    public function append($sText) {
        $this->sBody .= $sText;
    }
    
    public function respond() {
        http_response_code($this->iStatus);
        echo $this->sBody;
    }
}