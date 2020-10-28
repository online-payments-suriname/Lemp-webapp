<?
$table= new \model\transactions();
$table->noresults="no data";
$table->results="behold the ".$table->table." table";
echo $table->showTable('*');
?>
