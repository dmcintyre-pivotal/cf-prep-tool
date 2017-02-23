<?php
class TextFormatter {

    public function start() {
    }
    
    public function end() {
    }

    public function show($oTestSuite) {
    
        echo "================================================================================================\n";
        echo $oTestSuite->getName()."\n";
        echo "================================================================================================\n";
        echo str_pad("Check", 32, ' ');
        echo ' | ';
        echo "Result\n";
        echo "------------------------------------------------------------------------------------------------\n";
        foreach($oTestSuite->getTests() as $oTest) {
        
            echo str_pad($oTest->getName(), 32, ' ');
            echo ' | ';
            echo $oTest->failed() ? 'FAILED: ' : 'OK: ';
            $bFirst = true;
            foreach(explode("\n", $oTest->getMessage()) as $sLine) {
                if(!$bFirst) { 
                    echo str_pad(' ', 32, ' ');
                    echo ' | ';
                }
                $bFirst = false;
                
                echo $sLine;
                echo "\n";
            }
            
        }
        echo "------------------------------------------------------------------------------------------------\n";
        echo "\n";
    }
}