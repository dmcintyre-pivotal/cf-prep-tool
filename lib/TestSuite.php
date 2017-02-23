<?php
class TestSuite {

    private $sName;

    protected $aTests = array();
    
    public function __construct($sName) {
        $this->sName = $sName;
    }
    
    public function getName() {
        return $this->sName;
    }
    
    public function test($oTest) {
        $this->aTests[] = $oTest;
        return $oTest;
    }
    
    public function getTests() {
        return $this->aTests;
    }
    
    public function failed() {
        foreach($this->aTests as $oTest) {
            if($oTest->failed()) {
                return true;
            }
        }
        return false;
    }
}