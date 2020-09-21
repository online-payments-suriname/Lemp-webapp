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
        case 'password':
            return 'VARCHAR(255)';
        }
    }

    function formInputFields(){
        //create input fields depending on the values in the database
        foreach($this->columns as $key => $value){
            $fields.='
                        <label class="form-label" for="'.strtolower($key).'">'.$key.'</label>
                        <input class="form-control mr-sm-2" name="'.strtolower($key).'" type="'.strtolower($value).'">
                      ';
        }
        return $fields;
    }

    function validinput(){
        //only return true if the input value is valid
        return true;
    }

}
?>
