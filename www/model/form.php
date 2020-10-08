<?php
namespace model;

class form extends table{
    var $table, //table associated with the form
        $columns,//columns that model the fields of the form
        $customTableControls=array();

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

    function curl($c_url, $content_type){
        $curl=curl_init($c_url);
        //curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: ".$content_type));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $result = curl_exec($curl);
        $this->response = json_decode($result);
    }


}
?>
