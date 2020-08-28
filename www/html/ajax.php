<?php
require('base/data.php');

$data= new data();
switch($_POST['action']){
case 'select':
    $data->noresults="no data";
    $data->results="behold the columns table";
    echo $data->showTable('columns', '*');
    break;
case 'save':
    $data->columns=array('Name','Type');
    $data->insertData('columns');
    break;
case 'destroy':
    $data->truncateTable('columns');
    break;
case 'initial':
    $data->createTable('columns', 'Name VARCHAR(255), Type VARCHAR(255)');
    //define('SOME_FILE', str_replace('//', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__) . '/index.php')));
    //echo SOME_FILE;
    die($_POST['test']);
    break;
}
?>
