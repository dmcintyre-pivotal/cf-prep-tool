<?php
class WildcardDomain {

    private $sName;
    private $sWildcard;
    
    public function __construct($sName, $sWildcard) {
        $this->sName = $sName;
        $this->sWildcard = $sWildcard;
    }
    
    public function isValid() {
        return strpos($this->sWildcard, '*.') === 0;
    }
    
    public function getBase() {
        return substr($this->sWildcard, 2);
    }
    
    public function getName() {
        return $this->sName;
    }
    
    public function getWildcard() {
        return $this->sWildcard;
    }
    
    public function hosts($sHostname) {
        $sBase = $this->getBase();
        $iPos = strpos($sHostname, $sBase);
        if($iPos !== 0) {
            $sLeading = substr($sHostname, 0, $iPos);
            $sTrailing = substr($sHostname, $iPos);
            if($sTrailing != $sBase) {
                return false;
            }
            
            if(substr($sLeading, -1) != '.') {
                return false;
            }
            
            return true;
        }
        
        return false;
    }
}