<?
$class='\\model\\'.$_POST['model'];
$table=new $class();
$table->noresults="no data";
$table->results="behold the ".$table->table." table";
echo $table->showTable('*');
?>
