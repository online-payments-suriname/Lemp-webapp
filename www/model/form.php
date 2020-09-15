<?php
namespace model;

class form extends table{
    var $table, //table associated with the form
        $columns;//columns that model the fields of the form

    function __construct ($table, $columns){
        parent::__construct();
        $this->table=$table;
        $this->columns=$columns;
        $this->createTable();
    }

    function columnType($type){
        switch($type){
        case 'text':
        case 'email':
            return 'VARCHAR(255)';
        }
    }

    function formInputFields(){
        //create input fields depending on the values in the database
        $exists=$this->initialize($this->columns);
        if($exists==0)$this->insertData($init=1);
        //create fields based on the values of saved in the database
        foreach($this->columns as $key => $value){
            $fields.='<input class="form-control mr-sm-2" name="'.strtolower($key).'" type="'.strtolower($value).'">';
        }
        return $fields;
    }

    function initialize($columns){
        //check wheter the fields are in the database
        foreach($columns as $key => $value)
            $where.="Name='".$key."' OR ";
        $where=substr($where,0,-4);
        $exists=$this->fetchData("*",$where);
        return $exists->num_rows;
    }

    function tableInitVals($key){
        //insert the columns we need in the database
        if($key=='Name')
            return "'".$key."', '".$this->columns[$key]."'), ";
        elseif($key=='Type')
            return "('".$key."','".$this->columns[$key]."',";
    }

    function validinput(){
        //only return true if the input value is valid
        return true;
    }

}
?>
