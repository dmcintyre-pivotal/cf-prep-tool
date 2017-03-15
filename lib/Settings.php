<?php
class Settings {

    private $aEnv;
  
    public function __construct($sFile) {
    
        if(!file_exists($sFile)) {
            throw new Exception("Cannot open ini file $sFile");
        }

        $this->aEnv = parse_ini_file($sFile);
    }
    
    public function value($sName) {
        return $this->aEnv[$sName];
    }
    
    public function asArray($sName) {
        return explode(',', $this->aEnv[$sName]);
    }
}
