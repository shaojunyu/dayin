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
                        return true;
                    }
                }else{
                    return true;
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
                        return true;
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
                        return true;
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
                        return true;
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
                        return true;
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
                    return true;
                }
                break;
            default:
                return false;
        }
    }


    /**
     * function calculate_price 计算单价和小计，更新数据库
     * @param
     * @return
     * @author yushaojun
     */
    public function calculate_price($fileMD5){
        if(!$this->session->userdata('cellphone')){
            return false;
        }
        $this->db->where('cellphone',$this->session->userdata('cellphone'));
        $this->db->where('fileMD5',$fileMD5);
        $item = $this->db->get('cart')->result_array();
        if (count($item) == 1){
            $item = $item[0];
            $price = 0;
            $sub_total = 0;

            $paper_count =  (int)( ($item['pages'] + 1) / (($item['isTwoSides'] == 'YES') ? 2 : 1));
            switch ($item['paperSize']){
                case 'A4':
                    if ($item['isTwoSides'] == 'YES'){
                        $price = $paper_count * 0.15;
                    }else{
                        $price = $paper_count * 0.1;
                    }
                    break;
                case 'B4':
                    $price = $paper_count * 0.4;
                    break;
                default:
                    $price = $paper_count * 0.15;
            }
            $sub_total = $price * $item['amount'];

            //更新数据库
            $this->db->where('cellphone',$this->session->userdata('cellphone'));
            $this->db->where('fileMD5',$fileMD5);
            $this->db->update('cart',array('price'=>$price,'subTotal'=>$sub_total));
            return array('price'=>$paper_count,'subTotal'=>$sub_total);
        }else{
            return false;
        }
    }

}

class SettingValues{
    public $paperSize = array('A4','B4');
    public $direction = array('vertical','horizontal');
}