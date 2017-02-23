<?php
class JSONFormatter {

    public function start() {
    }

    public function show($oTestSuite) {
    
        header('Content-Type: application/json');
    
        echo '
        { "suite" : "'.$oTestSuite->getName().'",
          "results" : [
        ';
        
        $c = '';
        
        foreach($oTestSuite->getTests() as $oTest) {
          echo $c.'{';
          echo ' "test" : "'.$oTest->getName().'",';
          echo ' "passed" : '.($oTest->failed() ? 'false' : 'true').',';
          echo ' "message" : "'.$oTest->getMessage().'"';
          echo '}';
          $c = ',';
        }
        
        echo ']}';
    }
    
    public function end() {
    }
}