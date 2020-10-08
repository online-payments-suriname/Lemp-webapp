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
            $this->c_url=$row['Payment_Gateway'].'/'.$row['Token_Request_Path'].'/'.$row['Merchant_Email'].'/'.$row['Merchant_Password'].'/'.$_SESSION['transactionID'];
            $this->gateway=$row['Payment_Gateway'].'/'.$row['QR_Path'].'?TokenID=';
            //build url to query status
            $this->s_url=$row['Payment_Gateway'].'/PaymentStatus/'.$row['Merchant_Email'].'/'.$row['Merchant_Password'].'/'.$_SESSION['transactionID'];
        }
    }

}
?>
