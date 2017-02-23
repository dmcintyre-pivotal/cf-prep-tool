<?php

class RoutingTestSuite extends TestSuite {
    public function run() {
        foreach($this->aTests as $oTest) {
            $oTest->run();
        }
    }
}