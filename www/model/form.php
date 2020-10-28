<?php
namespace model;

class form extends table{
    var $table, //table associated with the form
        $columns,//columns that model the fields of the form
        $hiddenFields=array(),//fields that are of type hidden 'key' => 'value' where value is the default value;
        $fieldProperties=array(),
        $required="Please fill in all the required fields";

    function __construct ($table, $columns){
        parent::__construct($table, $columns);
    }

    function formInputFields(){
        //create input fields depending on the values in the database
        foreach($this->columns as $key => $value){
           if(array_key_exists($key, $this->hiddenFields)){
                $fields .= '<input class="form-control mr-sm-2" name="'.strtolower($key).'" type="hidden" value="'.$this->hiddenFields[$key].'">';
            }elseif(array_key_exists($key, $this->fieldProperties)){
                $fields.='
                          <label class="form-label" for="'.strtolower($key).'">'.$key.'</label>
                          <input class="form-control mr-sm-2" name="'.strtolower($key).'" type="'.strtolower($value).'"'.$this->fieldProperties[$key].'>
                         ';
            }else{
                $fields.='
                          <label class="form-label" for="'.strtolower($key).'">'.$key.'</label>
                          <input class="form-control mr-sm-2" name="'.strtolower($key).'" type="'.strtolower($value).'">
                         ';
            }
        }
        return $fields;
    }

    function addProp($properties, $property, $field, $i){
        array_push($properties[$property[$i]],$field);
        $i++;
        if(isset($property[$i]))
            $properties=$this->addProp($properties, $property, $field, $i);
        return $properties;
    }

    function fetchFieldProps(){
        $properties=array('required'=>array(), 'hidden' => array());
        foreach($this->fieldProperties as $field => $property){
            $property=explode(' ', $property);
            $properties=$this->addProp($properties, $property, $field, 0);
        }
        return $properties;
    }

    function validinput($valid=true){
        if($valid===$this->required){
            echo $this->errorMessage($this->required);
            $valid=false;
        }
        //only return true if the input value is valid
        return $valid;
    }

    function requiredKey($key, $value){

        if(in_array($key,$this->fetchFieldProps()['required'])&&empty($_POST[strtolower($key)])){
            return $this->required;
        }
            //only return true if the input value is valid
            return true;
    }

    function curl($c_url, $headers, $post=false){
        $curl=curl_init($c_url);
        if($post){
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if(is_array($headers))
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        else
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: ".$headers));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $result = curl_exec($curl);
        return array(
            'data'     => $result,
            'redirect' => curl_getinfo($curl, CURLINFO_REDIRECT_URL),
            'status'   => curl_getinfo($curl, CURLINFO_HTTP_CODE)
        );
    }


}
?>
