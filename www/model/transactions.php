<?php
namespace model;

class transactions extends table{
    var $table='transactions',
        $columns=array('amount' => 'text');

    function __construct (){
        parent::__construct($this->table, $this->columns);
    }

    function validinput(){
        return true;
    }

}
?>
