<?php
require('table.php');

class form extends table{
    var $table, //table associated with the form
        $columns,//columns of the form
        $columntypes;//the types of data the various formfields accept

    function __construct ($table, $columns, $columntypes){
        $this->mysql = new mysqli("localhost","root","root", "test") or die("Connection failed: " . mysqli_connect_error());
        $this->table=$table;
        $this->columns=$columns;
        $this->columntypes=$columntypes;
        $this->createTable('Name VARCHAR(255), Type VARCHAR(255)');
    }

    function formInputFields(){
        foreach($this->columns as $key => $value){
            $where.="Name='".$value."' OR ";
        }
        $exists=$this->initialize($this->columns);
        if($exists==0)$this->insertData($init=1);
        $where=substr($where, 0, -4);
        $inputs=$this->fetchFields($where);
        return $inputs;
    }

    function fetchFields($where){
        $result=$this->fetchData('*',$where);
        while($row=$result->fetch_assoc()){
            $fields.='<input class="form-control mr-sm-2" name="'.strtolower($row[$this->columns[0]]).'" type="'.$row[$this->columns[1]].'">';
        }
        return $fields;
    }

    function initialize($columns){
        foreach($columns as $key => $value)
            $where.="Name='".$value."' OR ";
        $where=substr($where,0,-4);
        $exists=$this->fetchData("*",$where);
        return $exists->num_rows;
    }

    function tableInitVals($key){
        $columns=array_combine($this->columns,$this->columntypes);
        if($key=='Name')
            return "'".$key."', '".$columns[$key]."'), ";
        elseif($key=='Type')
            return "('".$key."','".$columns[$key]."',";
    }

    function validinput(){
        return true;
    }

}
?>
