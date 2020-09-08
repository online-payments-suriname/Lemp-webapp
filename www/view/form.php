<?php
require('model/form.php');

$form=new form('columns',array('Name'=>'text','Type'=>'text'));
if($_POST['action']=='initial'){
    echo '<form id="date" class="form" method="POST" action="html/ajax.php">'.
                $form->formInputFields().
                '<input class="btn btn-secondary my-2 my-sm-0" value="save" type="submit">
                <input class="btn btn-secondary my-2 my-sm-0" value="reset" type="reset">
        </form>';
}
?>
