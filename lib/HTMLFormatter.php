<?php
class HTMLFormatter {

    public function start() {
    ?>
    <html>
<head>
<style>

table {border-collapse: collapse; border: 0; width: 100%; box-shadow: 1px 2px 3px #ccc;}
td, th {border: 1px solid #666; font-size: 75%; text-align: left; vertical-align: baseline; padding: 4px 5px;}
h1 {font-size: 150%;}
h2 {font-size: 125%;}

</style>
</head>

<body>
<h1>Network Tester</h1>
<?php
    }

  public function show($oTestSuite) {
    ?>
    <h1><?php echo $oTestSuite->getName() ?></h1>
    <table>
    <tr><th>Check</th><th>Result</th></tr>
    <?php foreach($oTestSuite->getTests() as $oTest) { ?>
    <tr><th><?php echo $oTest->getName()?></th>
    <td>
        <?php
        echo $oTest->failed() ? 'FAILED: ' : 'OK: ';
        echo $oTest->getMessage();
        ?>
    </td>
    </tr>
    <?php } ?>
    </table>
    <?php
    }
    
    public function end() {
    ?>
    
</body>
</html>
<?php
    }

}