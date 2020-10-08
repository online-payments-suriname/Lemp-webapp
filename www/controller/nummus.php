<?
namespace controller;
class nummus {

    function nummusInterface($request){
        if($_GET['status']=='paid'){
            $nummus = new \model\nummus();
            $nummus->fetchCGUrl();
            $nummus->curl($nummus->s_url, "application/json");
            if(!isset($nummus->response->Code))
                $records=array_reverse($nummus->response->records);
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
            $nummus->curl($nummus->s_url, "application/json");
            if(empty($_POST))die($nummus->response->Resp);
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
    $nummus->fetchCGUrl();
    $nummus->c_url.='/'.$_POST['amount'];
    $nummus->curl($nummus->c_url, "application/json");
    $request = new Request();
    $returnUrl=urlencode($request->requestScheme.'://'.$request->httpHost.'/service/nummus.php');
    //$returnUrl=urlencode($request->requestScheme.'://'.$request->httpHost.'/service/nummus');
    if($nummus->response->Code==0)
        $nummus->gateway .= $nummus->response->Resp.'&Desc=Nummuswebapp&returnURL='.$returnUrl;
    else
        die($nummus->response->Resp);
    echo '<form id="authorize" method="POST" action="'.$nummus->gateway.'">
          <input type="hidden" name="key" value="0"/>
          </form>
         ';
}
?>
