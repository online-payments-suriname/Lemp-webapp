<?php
session_start();
class data{
    var $mysql,//mysql object
        $sqldie,//if this is 1 it prints the query
        $result,//result from query
        $results,//message to show if there are results
        $noresults,//message to show if there are no results
        $invalid,//message to show if the input is invalid
        $sortable;//array containing the columns that are sortable

    function __construct (){
        $this->sqldie=0;
        $this->mysql = new mysqli("localhost","root","root", "test") or die("Connection failed: " . mysqli_connect_error());
    }

    function fetchData($table, $columns, $where, $groupby, $orderby, $limit){
    //fetch data to build the table
        $where=($where!="")?" WHERE ".$where:"";
        $groupby=($groupby!="")?" GROUP BY ".$groupby:"";
        $orderby=($orderby!="")?" ORDER BY ".$orderby:"";
        $limit=($limit!="")?" LIMIT ".$limit:"";
        $query="SELECT ".$columns." FROM ".$table.$where.$groupby.$orderby.$limit;
        if($this->sqldie)die($query);
        $result=$this->mysql->query($query) or die($this->mysql->error);
        return $result;
    }

    function sortColumn($id){
        //set the sort order for a particular column
        $arrow='up';
        $order='asc';
        if($_POST['linkId']==$id){
            $arrow=$_POST['order']=='desc'?'down':$arrow;
            $order=$_POST['order']=='desc'?$_POST['order']:$order;
        }
        $sort='<a id="'.$id.'" class="pl-2 alnk '.$id.'" href="desc"><span class="fas fa-arrow-'.$arrow.'"></span></a>';
        return $sort;
    }

    function setSort($id, $alias){
        //set the sorting order for groupby with rollup
        $sort=(!empty($_POST['linkId'])&&$_POST['linkId']==$id)?$_POST['linkId'].' '.strtoupper($_POST['order']):$id;
        if(!empty($alias)){
            $aliaslen=strlen($id);
            $sort=substr_replace($sort, $alias, 0, $aliaslen);
        }
        return $sort;
    }

    function getTableHeader($row){
    //get columns name for the tableheader and format it
        $rows='<thead class="thead-dark"><tr>';
        //pl-sm-2=>padding left sm spacer*0.5
        //class lnk utilizes href to load the page via ajax
        //class sort sets the sorting direction based on the direction at click time
        $i=0;
        foreach($row as $id => $value){
            if(in_array($id, $this->sortable))$id.=$this->sortColumn($id);
            $rows.='<th class="th '.$i.' sticky-top" align="left">'.$id.'</th>';
            $i++;
        }
        $tableheader= $rows.'</tr></thead>';
        return $tableheader;
    }

    function filterRow($row, $string){
    //filter rows out of the table
        if($string == '')
            return false;
        $string=strtoupper($string);
        foreach($row as $id => $value){
            if(strpos(strtoupper($value), $string) !== false)
                $this->filterCell=$value;
        }
        if(empty($this->filterCell))
            return true;
        if(!in_array($this->filterCell,$row)){
            return true;
        }
    }

    function getTableRow($row){
    //get the table rows and format it
        $tbrow='<tr>';
        $i=0;
        foreach($row as $id =>$value){
            if($value=='')$value='&nbsp;';
            $tbrow.='<td class="'.$i.'" align="left">'.$value.'</td>';
            $i++;
        }
        return $tbrow.'</tr>';
    }

    function getTable($result){
    //get the table together
        $data='<table class="table table-striped table-bordered">';
        $i=0;
        while($row=$result->fetch_assoc()){
            if($this->filterRow($row, $_POST['criteria']))continue;
            if($i==0)$data.=$this->getTableHeader($row);
            $i++;
            $data.=$this->getTableRow($row);
        }
        $data.='</table>';
        if($i==0)
            return $i;//return that that are no rows
        return $data;
    }

    function showData($table){
    //show the result thing table including success message
        $data='<div class="alert alert-success">'.$this->results."</div>";
        $data.='<div id="respond" class="responsive-header no-responsive-header sticky-top" data-syncscroll="data-table">'.$table.'</div>';
        $data.='<div id="'.$_POST['action'].'" class="table-responsive" data-syncscroll="data-table">';
        $data.=$table;
        $data.='</div>';
        return $data;
    }

    function showTable($table, $columns, $where, $groupby, $orderby){
    //validate input fetch and show the actual table, show an error if there are no results
        if(!$this->validinput())
            return $this->errorMessage($this->invalid);//do not continue if the input is invalid
        $result=$this->fetchData($table, $columns, $where, $groupby, $orderby);
        if($result->num_rows==0)
            return $this->errorMessage($this->noresults);//show an error message if there are no results
        $table=$this->getTable($result);
        if($table===0)
            return $this->errorMessage($this->noresults);//show an error message if there are no results
        $data=$this->showData($table);
        return $data;
    }

    function pr($array){
        //easy function to print an array in an easy to read format
        print "<pre>";
        print_r($array);
        echo "</pre>";
    }

    function validinput(){
    //return false when the input is invalid
        return true;
    }

    function errorMessage($message){
        //show the errormessage in an alert
        return '<div class="alert alert-danger">'.$message.'</div>';
    }

    function createTable($table, $columns){
        $columns=($columns!="")?', '.$columns:'';
        $query="CREATE TABLE IF NOT EXISTS ".$table." (Id INT AUTO_INCREMENT PRIMARY KEY".$columns.", Insert_date TIMESTAMP);";
        if($this->sqldie)die($query);
        $this->mysql->query($query) or die($this->mysql->error);
    }

    function insertData($table, $column, $val){
        $value=$this->mysql->real_escape_string($val);
        $query="INSERT INTO ".$table." (".$column.") VALUES ('".$value."')";
        if($this->sqldie)die($query);
        $this->mysql->query($query) or die($this->mysql->error);
    }

    function truncateTable($table){
        $query="TRUNCATE ".$table.";";
        if($this->sqldie)die($query);
        $this->mysql->query($query) or die($this->mysql->error);
    }
}
?>
