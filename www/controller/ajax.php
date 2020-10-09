<?php
$class='\\model\\'.$_POST['class'];
$form=new $class();
switch($_POST['action']){
case 'select':
    $form->noresults="no data";
    $form->results="behold the ".$form->table." table";
    echo $form->showTable('*');
    break;
case 'save':
    $form->insertData();
    break;
case 'destroy':
    $form->truncateTable();
    break;
    //define('SOME_FILE', str_replace('//', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__) . '/index.php')));
    //echo SOME_FILE;
}

?>
