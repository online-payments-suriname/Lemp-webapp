<?php
require('base/data.php');

class form extends data{
    var $table='columns',
        $columns=array('Name','Type'),
        $columntypes=array('text','text');

    function __construct (){
        $this->mysql = new mysqli("localhost","root","root", "test") or die("Connection failed: " . mysqli_connect_error());
        $this->createTable('Name VARCHAR(255), Type VARCHAR(255)');
    }

    function formInputFields(){
        foreach($this->columns as $key => $value){
            $where.="Name='".$value."' OR ";
        }
        $where=substr($where, 0, -4);
        $result=$this->fetchData('*',$where);
        while($row=$result->fetch_assoc()){
            echo '<input class="form-control mr-sm-2" name="'.strtolower($row[$this->columns[0]]).'" type="'.$row[$this->columns[1]].'">';
        }
    }

    function tableInitVals($key){
        $columns=array_combine($this->columns,$this->columntypes);
        if($key=='Name')
            return "'".$key."', '".$columns[$key]."'), ";
        elseif($key=='Type')
            return "('".$key."','".$columns[$key]."',";
    }

}
?>
