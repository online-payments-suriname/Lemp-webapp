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

}
?>
