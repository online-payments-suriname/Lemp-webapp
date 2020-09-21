<?
require('controller/autoloader.php');
if(!empty($_POST['amount'])){
    $nummus = new model\nummus();
    $result=$nummus->fetchData('*');
    while($row=$result->fetch_assoc()){
        $c_url=$row['Payment_Gateway'].'/'.$row['Token_Request_Path'].'/'.$row['Merchant_Email'].'/'.$row['Merchant_Password'].'/01';
        $gateway=$row['Payment_Gateway'].'/'.$row['QR_Path'].'?TokenID=';
    }
    $c_url.='/'.$_POST['amount'];
    $curl=curl_init($c_url);
    //curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
    $result = curl_exec($curl);
    $response = json_decode($result);
    $gateway .= $response->Resp.'&returnURL=http://localhost:8090/controller/nummus.php';
    header('Location:'.$gateway);
}
if(isset($_POST['key']))
    die('hey');
?>
