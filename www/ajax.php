<?php
require('base/data.php');

$data= new data();
switch($_POST['action']){
case 'select':
    $data->noresults="no data";
    echo $data->showTable('testtable', '*', '', '', '');
    break;
case 'save':
    $data->insertData('testtable', 'Value', $_POST['test']);
    break;
case 'destroy':
    $data->truncateTable('testtable');
    break;
case 'initial':
    //$data->createTable('testtable', 'Value VARCHAR(255)');
    die($_POST['test']);
    break;
}
?>
