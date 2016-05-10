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

    /**
     * function printSettings
     * @param $fileMD5,$setting,$setting_value
     * @return
     * @author yushaojun
     */
    public function printSettings($fileMD5,$setting,$setting_value){
        if(!$this->session->userdata('cellphone')){
            return false;
        }
        switch ($setting){
            case 'paperSize':
                if (in_array($setting_value,array('A4','B4'))){
                    $this->db->where('cellphone',$this->session->userdata('cellphone'));
                    $this->db->where('fileMD5',$fileMD5);
                    $this->db->update('cart',array('paperSize'=>$setting_value));
                    if ($this->db->affected_rows() == 1){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
                break;

            case 'isTwoSides':
                if (in_array($setting_value,array('YES','NO'))){
                    $this->db->where('cellphone',$this->session->userdata('cellphone'));
                    $this->db->where('fileMD5',$fileMD5);
                    $this->db->update('cart',array('isTwoSides'=>$setting_value));
                    if ($this->db->affected_rows() == 1){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
                break;

            case 'amount':
                if ($setting_value > 0){
                    $this->db->where('cellphone',$this->session->userdata('cellphone'));
                    $this->db->where('fileMD5',$fileMD5);
                    $this->db->update('cart',array('amount'=>$setting_value));
                    if ($this->db->affected_rows() == 1){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
                break;

            case 'pptPerPage':
                if ($setting_value > 0){
                    $this->db->where('cellphone',$this->session->userdata('cellphone'));
                    $this->db->where('fileMD5',$fileMD5);
                    $this->db->update('cart',array('pptPerPage'=>$setting_value));
                    if ($this->db->affected_rows() == 1){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
                break;

            case 'direction':
                if (in_array($setting_value,array('vertical','horizontal'))){
                    $this->db->where('cellphone',$this->session->userdata('cellphone'));
                    $this->db->where('fileMD5',$fileMD5);
                    $this->db->update('cart',array('direction'=>$setting_value));
                    if ($this->db->affected_rows() == 1){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
                break;

            case 'remark':
                $this->db->where('cellphone',$this->session->userdata('cellphone'));
                $this->db->where('fileMD5',$fileMD5);
                $this->db->update('cart',array('remark'=>$setting_value));
                if ($this->db->affected_rows() == 1){
                    return true;
                }else{
                    return false;
                }
                break;
            default:
                return false;
        }
    }
}

class SettingValues{
    public $paperSize = array('A4','B4');
    public $direction = array('vertical','horizontal');
}