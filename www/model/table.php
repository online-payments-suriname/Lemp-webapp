<?php
namespace model;
abstract class table extends data{

    var $invalid,//message to show if the input is invalid
        $sortable,//array containing the columns that are sortable
        $customTableControls=array();

    function __construct ($table, $columns){
        //inherit the initialized parameters from parent class
        parent::__construct();
        $this->table=$table;
        $this->columns=$columns;
        $this->createTable();
        $this->fetchColumns();
    }

    function columnType($type){
        switch($type){
        case 'text':
        case 'email':
        case 'password':
            return 'VARCHAR(255)';
        }
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
            if($this->sortable!=''&&in_array($id, $this->sortable))$id.=$this->sortColumn($id);
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

    function tableCellContent($id, $value, $i){
        if(array_key_exists($id, $this->customTableControls)){
            $tbrow.='<td class="'.$i.'" align="left"><input type="'.$this->customTableControls[$id].'" value="'.$value.'"></td>';
        }else{
            $tbrow='<td class="'.$i.'" align="left">'.$value.'</td>';
        }
        return $tbrow;
    }

    function getTableRow($row){
    //get the table rows and format it
        $tbrow='<tr>';
        $i=0;
        foreach($row as $id =>$value){
            if($value=='')$value='&nbsp;';
            $tbrow.=$this->tableCellContent($id, $value, $i);
            $i++;
        }
        return $tbrow.'</tr>';
    }

    function getTable($result){
    //get the table together
        $data='<table class="table table-striped table-bordered table-hover">';
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
        $data.='<div id="'.$_POST['action'].'">';
        $data.=$table;
        $data.='</div>';
        return $data;
    }

    function showTable($columns, $where='', $groupby='', $orderby='', $limit=''){
    //validate input fetch and show the actual table, show an error if there are no results
        if(!$this->validinput())
            return $this->errorMessage($this->invalid);//do not continue if the input is invalid
        $result=$this->fetchData($columns, $where, $groupby, $orderby, $limit);
        if($result->num_rows==0)
            return $this->errorMessage($this->noresults);//show an error message if there are no results
        $table=$this->getTable($result);
        if($table===0)
            return $this->errorMessage($this->noresults);//show an error message if there are no results
        $data=$this->showData($table);
        return $data;
    }

}
?>
