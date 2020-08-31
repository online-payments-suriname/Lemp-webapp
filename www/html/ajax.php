<?php
require('view/form.php');

$data= new form();
switch($_POST['action']){
case 'select':
    $data->noresults="no data";
    $data->results="behold the ".$data->table." table";
    echo $data->showTable('*');
    break;
case 'save':
    $data->insertData();
    break;
case 'destroy':
    $data->truncateTable();
    break;
    //define('SOME_FILE', str_replace('//', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__) . '/index.php')));
    //echo SOME_FILE;
}
?>
