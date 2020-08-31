<?php
require('model/form.php');

$form=new form();
if($_POST['action']=='initial'){
    $exists=$form->fetchData("*","Name='Name' OR Name='Type'");
    if($exists->num_rows==0)$form->insertData($init=1);
    echo '<form id="date" class="form" method="POST" action="html/ajax.php">'.
        $form->formInputFields().
        '<input class="btn btn-secondary my-2 my-sm-0" value="save" type="submit">
                <input class="btn btn-secondary my-2 my-sm-0" value="reset" type="reset">
        </form>';
}
?>
