<?php
class Test {

    private $sName;
    private $sMessage;
    private $bFailed = false;
    
    public function __construct($sName) {
        $this->sName = $sName;
    }
    
    public function append($sMessage) {
        $this->sMessage .= $sMessage;
    }
    
    public function getName() {
        return $this->sName;
    }
    
    public function getMessage() {
        return $this->sMessage;
    }
    
    public function setFailed() {
        $this->bFailed = true;
    }
    
    public function failed() {
        return $this->bFailed;
    }
}