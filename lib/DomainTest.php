<?php
require_once('Test.php');

class DomainTest extends Test {
    private $oDomain;
    
    public function __construct($oDomain) {
        parent::__construct($oDomain->getName());
        $this->oDomain = $oDomain;
        if(!$this->oDomain->isValid()) {
            $this->setFailed();
            $this->append('Invalid domain: '.$this->oDomain->getWildcard());
        } else {
            $this->append('Set to '.$this->oDomain->getBase());
        }
    }
}
