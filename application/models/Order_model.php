<?php
/**
 * Created by PhpStorm.
 * User: yushaojun
 * Date: 5/10/2016
 * Time: 6:19 PM
 */
class Order_model extends CI_Model{
    public function __construct()
    {
        parent::__construct();
    }

    public function create_order(){
        
    }

    public function update_order_state(){
        
    }
}

class orderState{
    const UNPAID = 'UNPAID';
    const PAID = 'PAID';
    const UNPRINTED = 'UNPRINTED';
    const PRINTED = 'PRINTED';
    const DELIVERING = 'DELIVERING';
    const DONE = 'DONE';
    const CANCELED = 'CANCELED';
}