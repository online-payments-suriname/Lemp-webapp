<?php
$form=new model\nummus();
if($_POST['action']=='home'){
    if(isset($_SESSION['msg'])){
        echo $form->errorMessage($_SESSION['msg']);
        unset($_SESSION['msg']);
    }
    echo '<form id="date" class="form" method="POST" action="ajax">
            <div class="form-fields">'.
                $form->formInputFields().
            '</div>
                <input class="btn btn-dark my-2 my-sm-0 save-btn" value="save" type="submit">
                <input class="btn btn-secondary my-2 my-sm-0 reset-btn" value="reset" type="reset">
          </form>';
}
?>
