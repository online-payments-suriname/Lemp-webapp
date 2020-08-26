<?php
require('base/data.php');

$data= new data();
switch($_POST['action']){
case 'select':
    $data->noresults="no data";
    echo $data->showTable('testtable', '*');
    break;
case 'save':
    $data->insertData('testtable', 'Value', $_POST['test']);
    break;
case 'destroy':
    $data->truncateTable('testtable');
    break;
case 'initial':
    //$data->createTable('testtable', 'Value VARCHAR(255)');
    //define('SOME_FILE', str_replace('//', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__) . '/index.php')));
    //echo SOME_FILE;
    die($_POST['test']);
    break;
}
?>
