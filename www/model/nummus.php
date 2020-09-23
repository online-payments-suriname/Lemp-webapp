<?php
namespace model;

class nummus extends form{
    var $table='nummus',
        $columns=array('Merchant_Email' => 'email',
        'Merchant_Password' => 'password',
        'Payment_Gateway' => 'text',
        'Token_Request_Path' => 'text',
        'QR_Path' => 'text');

    function __construct (){
        parent::__construct($this->table, $this->columns);
    }

    function fetchCGUrl(){
        $result=$this->fetchData('*');
        while($row=$result->fetch_assoc()){
            //build url to fetchtoken
            $this->c_url=$row['Payment_Gateway'].'/'.$row['Token_Request_Path'].'/'.$row['Merchant_Email'].'/'.$row['Merchant_Password'].'/01';
            $this->gateway=$row['Payment_Gateway'].'/'.$row['QR_Path'].'?TokenID=';
            //build url to query status
            $this->s_url=$row['Payment_Gateway'].'/PaymentStatus/'.$row['Merchant_Email'].'/'.$row['Merchant_Password'].'/01';
        }
    }

    function curl($c_url){
        $curl=curl_init($c_url);
        //curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $result = curl_exec($curl);
        $this->response = json_decode($result);
    }


}
?>
