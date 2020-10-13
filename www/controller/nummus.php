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
            switch($_GET['status']){
            case 'cancel':
                $message='payment has been canceled';
                break;
            case 'timeout':
                $message='payment has timed out';
                break;
            case 'failed':
                $message='payment has failed';
                break;
            case 'pending':
                $message='payment is pending';
                break;
            }
            $_SESSION['msg']=$message;
        }
        Router::redirect($request->requestScheme.'://'.$request->httpHost);
    }

    function nummusAPI($request){
        if($request->status=='paid'){
            $nummus = new \model\nummus();
            $nummus->fetchCGUrl();
            $token=json_decode(\model\form::curl($nummus->s_url, "application/json")['data']);
            if(empty($_POST))die($token->Resp);
            else die('took you long enough');
        }else{
            switch($request->status){
            case 'cancel':
                $message='payment has been canceled';
                break;
            case 'timeout':
                $message='payment has timed out';
                break;
            case 'failed':
                $message='payment has failed';
                break;
            case 'pending':
                $message='payment is pending';
                break;
            }
            $_SESSION['msg']=$message;
        }
        Router::redirect($request->requestScheme.'://'.$request->httpHost);
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
    if($nummus->response->Code==0)
        $nummus->gateway .= $nummus->response->Resp.'&Desc=Nummuswebapp&returnURL='.$returnUrl;
    else
        die($nummus->response->Resp);
    echo '<form id="authorize" method="POST" action="'.$nummus->gateway.'">
          <div class="spinner-border"></div>
          </form>
         ';
    }else
        die($token->Resp);
}
?>
