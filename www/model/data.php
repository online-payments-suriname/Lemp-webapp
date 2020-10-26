<?php
namespace model;
abstract class data{
    var $mysql,//mysql object
        $sqldie,//if this is 1 it prints the query
        $result,//result from query
        $results,//message to show if there are results
        $noresults;//message to show if there are no results
    private $database;

    function __construct (){
        $this->database='test';
        $this->mysql = new \mysqli("mariadb","root","root", $this->database) or die("Connection failed: " . mysqli_connect_error());
        $this->sqldie=0;
    }

    function fetchData($columns, $where='', $groupby='', $orderby='', $limit=''){
    //fetch data to build the table
        $where=($where!="")?" WHERE ".$where:"";
        $groupby=($groupby!="")?" GROUP BY ".$groupby:"";
        $orderby=($orderby!="")?" ORDER BY ".$orderby:"";
        $limit=($limit!="")?" LIMIT ".$limit:"";
        if($this->table=='')$this->sqldie=1;
        $query="SELECT ".$columns." FROM ".$this->table.$where.$groupby.$orderby.$limit;
        if($this->sqldie)die($query);
        $result=$this->mysql->query($query) or die($this->mysql->error);
        return $result;
    }

    function pr($array){
        //easy function to print an array in an easy to read format
        print "<pre>";
        print_r($array);
        echo "</pre>";
    }

    function cs($string, $char){
        //cut string from start till $char
        //for example cut('data.php','.php') will return 'data'
        return substr($string, 0, strpos($string, $char));
    }

    abstract function validinput();
    //return false when the input is invalid

    function errorMessage($message){
        //show the errormessage in an alert
        return '<div class="alert alert-danger">'.$message.'</div>';
    }

    function createTable(){
        foreach($this->columns as $key => $value){
            $columns.=', '.$key.' '.$this->columnType($value);
        }
        if($this->table=='')$this->sqldie=1;
        $query="CREATE TABLE IF NOT EXISTS ".$this->table." (Id INT AUTO_INCREMENT PRIMARY KEY".$columns.", Insert_date TIMESTAMP, Active TINYINT(1) DEFAULT 1);";
        if($this->sqldie)die($query);
        $this->mysql->query($query) or die($this->mysql->error);
    }

    function fetchColumns(){
        $query="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name='".$this->table."' AND table_schema='".$this->database."'";
        $result=$this->mysql->query($query) or die($this->mysql->error);
        $missing_columns=array_diff(array_keys($this->columns),array_column($result->fetch_all(),'0'));
        if(!empty($missing_columns)){
            $missing_columns=array_intersect_key($this->columns, array_flip($missing_columns));
            foreach($missing_columns as $key => $value){
                $columns.=$key.' '.$this->columnType($value).', ';
            }
            $after_key=$this->array_key_before(array_key_first($missing_columns));
            $query="ALTER TABLE ".$this->table." ADD COLUMN ".substr($columns, 0, -2)." AFTER ".$after_key.";";
            $this->mysql->query($query) or die($this->mysql->error);
        }
    }

    function array_key_before($key){
        $key=array_search($key,array_keys($this->columns))-1;
        return ($key==-1)?'Id':array_keys($this->columns)[$key];
    }

    function insertData(){
        $values='';
        foreach($this->columns as $key => $value){
            $keys.=$this->mysql->real_escape_string($key).",";
            $values.="'".$this->mysql->real_escape_string($_POST[strtolower($key)])."',";
        }
        $keys=substr($keys, 0, -1);
        $values=substr($values, 0, -1);
        if($this->table=='')$this->sqldie=1;
        $query="INSERT INTO ".$this->table." (".$keys.") VALUES (".$values.")";
        if($this->sqldie)die($query);
        $this->mysql->query($query) or die($this->mysql->error);
    }

    function truncateTable(){
        if($this->table=='')$this->sqldie=1;
        $query="TRUNCATE ".$this->table.";";
        if($this->sqldie)die($query);
        $this->mysql->query($query) or die($this->mysql->error);
    }
}
?>
