<?
require('controller/autoloader.php');
if(!empty($_POST['amount'])){
    $nummus = new model\nummus();
    $nummus->fetchCGUrl();
    $nummus->c_url.='/'.$_POST['amount'];
    $nummus->curl($nummus->c_url);
    if($nummus->response->Code==0)
        $nummus->gateway .= $nummus->response->Resp.'&returnURL=http://localhost:8090/controller/nummus.php';
    else
        die($nummus->response->Resp);
    header('Location:'.$nummus->gateway);
}
if(!empty($_GET)){
    if($_GET['status']=='paid'){
        $nummus = new model\nummus();
        $nummus->fetchCGUrl();
        $nummus->curl($nummus->s_url);
        print $nummus->response->Resp;
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
        header('location:http://localhost:8090');
    }
}
?>
