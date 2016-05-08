<?php
/**
 * Created by PhpStorm.
 * User: yushaojun
 * Date: 5/8/2016
 * Time: 7:01 PM
 */

class Cart_model extends CI_Model{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('cellphone')){

        }
    }

    public function add_item($fileName,$fileMD5){
        if(!$this->session->userdata('cellphone')){
            return false;
        }
        $this->db->where('cellphone',$this->session->userdata('cellphone'));
        $this->db->where('fileName',$fileName);
        $this->db->where('fileMd5',$fileMD5);
        $this->db->get('cart');
        if ($this->db->affected_rows() == 1){
            return true;
        }else{
            $this->db->insert('cart',array(
                'cellphone'=>$this->session->userdata('cellphone'),
                'fileName'=>$fileName,
                'fileMD5'=>$fileMD5
            ));
            if ($this->db->affected_rows() == 1){
                return true;
            }else{
                return false;
            }
        }
    }

    public function delete_item($fileMD5){
        if(!$this->session->userdata('cellphone')){
            return false;
        }
        $this->db->where('cellphone',$this->session->userdata('cellphone'));
        $this->db->where('fileMd5',$fileMD5);
        $this->db->delete('cart');
        return true;
    }
}