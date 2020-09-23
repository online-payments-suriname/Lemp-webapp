<?php
if(isset($_SESSION['msg']))
   echo model\nummus::errorMessage($_SESSION['msg']);
   unset($_SESSION['msg']);
?>
<form id="payment" class="form" method="POST" action="nummus">
                <input class="form-control mr-sm-2" name="amount" type="text" placeholder="Amount">
                <button class="btn btn-dark my-2 my-sm-0" value="pay" type="submit">Pay</button>
          </form>
