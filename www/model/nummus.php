<?php
namespace model;

class nummus extends form{
    var $table='nummus',
        $columns=array('Merchant_Email' => 'email',
        'Merchant_Password' => 'password',
        'API_key' => 'password',
        'Payment_Gateway' => 'text',
        'Token_Request_Path' => 'text',
        'QR_Path' => 'text',
        'Status_Query_Path' => 'text');

    function __construct (){
        parent::__construct($this->table, $this->columns);
    }

    function fetchCGUrl(){
        $result=$this->fetchData('*');
        while($row=$result->fetch_assoc()){
            //build url to fetchtoken
            $this->c_url=$row['Payment_Gateway'].'/'.$row['Token_Request_Path'].'/'.$row['Merchant_Email'].'/'.$row['Merchant_Password'].'/'.$_SESSION['transactionID'];
            //build url to build qr code
            $this->gateway=$row['Payment_Gateway'].'/'.$row['QR_Path'];
            //build url to query status
            $this->s_url=$row['Payment_Gateway'].'/'.$row['Status_Query_Path'].'/'.$row['Merchant_Email'].'/'.$row['Merchant_Password'].'/'.$_SESSION['transactionID'];
        }
    }

}
?>
