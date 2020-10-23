<?
namespace controller;
class nummus {

    function nummusInterface($request){
        if($_GET['status']=='paid'){
            $nummus = new \model\nummus();
            $nummus->fetchCGUrl();
            $token=json_decode(\model\form::curl($nummus->s_url, "application/json")['data']);
            if(!isset($token->Code))
                $records=array_reverse($token->records);
                if($records[0]->RespCode!=0)$_SESSION['msg']="payment is pending";
        }else{
            $_SESSION['msg']=["cancel" => "payment has been canceled",
                              "timeout" => "payment has timed out",
                              "failed" => "payment has failed",
                              "pending" => "payment is pending"
                             ][$_GET['status']];
        }
        Router::redirect(Request::base_url());
    }

    function nummusAPI($request){
        if($request->status=='paid'){
            $nummus = new \model\nummus();
            $nummus->fetchCGUrl();
            $token=json_decode(\model\form::curl($nummus->s_url, "application/json")['data']);
            if(empty($_POST))die($token->Resp);
            else die('took you long enough');
        }else{
            $_SESSION['msg']=["cancel" => "payment has been canceled",
                              "timeout" => "payment has timed out",
                              "failed" => "payment has failed",
                              "pending" => "payment is pending"
                             ][$request->status];
        }
        Router::redirect(Request::base_url());
    }

}

if(!empty($_POST['amount'])){
    $nummus = new \model\nummus();
    $transaction = new \model\transactions();
    $transaction->insertData();
    $_SESSION['transactionID']=$transaction->mysql->insert_id;
    $nummus->fetchCGUrl();
    $nummus->c_url.='/'.$_POST['amount'];
    $token = json_decode(\model\form::curl($nummus->c_url, "application/json")['data']);
    $returnUrl = Request::base_url().'/service/nummus.php';
    //$returnUrl=urlencode(Request::base_url().'/service/nummus');
    if($token->Code == 0){
        echo '<form id="authorize" method="get" action="'.$nummus->gateway.'">
            <div class="spinner-border"></div>
            <input name="TokenID" type="hidden" value="'.$token->Resp.'"/>
            <input name="returnURL" type="hidden" value="'.$returnUrl.'"/>
            <input name="Desc" type="hidden" value="NummusWebapp#'.$transaction->mysql->insert_id.'"/>
            </form>
';
    }else
        die($token->Resp);
}
?>
