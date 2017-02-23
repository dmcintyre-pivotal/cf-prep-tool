<?php
class IPAddress {

    private $sAddr;
    
    public function __construct($sAddr) {
        $this->sAddr = $sAddr;
    }
    
    public function isValid() {


    // The regular expression checks for any number between 0 and 255 beginning with a dot (repeated 3 times)
    // followed by another number between 0 and 255 at the end. The equivalent to an IPv4 address.
    //It does not allow leading zeros
    return (bool) preg_match('/^(?:(?:25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])'.
    '\.){3}(?:25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]?|[0-9])$/', $this->sAddr);
    }
}