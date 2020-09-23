<?php
$form=new model\nummus();
?>
<form id="date" class="form element-block" method="POST" action="ajax">
    <div class="form-fields">
    <?=$form->formInputFields()?>
    </div>
    <input class="btn btn-dark my-2 my-sm-0 save-btn" value="save" type="submit">
    <input class="btn btn-secondary my-2 my-sm-0 reset-btn" value="reset" type="reset">
</form>

