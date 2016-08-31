<?php
/**
 * Created by PhpStorm.
 * User: 96853
 * Date: 5/7/2016
 * Time: 9:17 PM
 */
class User_model extends CI_Model{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * function login
     * @param
     * @return ture,false
     * @author yushaojun
     */
    public function login($cellphone,$password){
        $this->db->where('cellphone',$cellphone);
        $this->db->where('password',md5($password));
        try{
            $res = $this->db->get('user');
            if ($res->result_array()){
                $this->userInfo2session($cellphone);
                return true;
            }else{
                return false;
            }
        }catch (Exception $e){
            return false;
        }

    }

    public function wetchat_login($cellphone){
        $this->userInfo2session($cellphone);
        return true;
    }

    public function signup($cellphone,$password,$school){
        $this->db->where('cellphone',$cellphone);
        $res = $this->db->get('user')->result_array();
        if (count($res) != 0){
            throw new Exception('手机号已存在');
        }
        try{
            $this->db->insert('user',array(
                'cellphone'=>$cellphone,
                'password'=>md5($password),
                'school'=>$school
            ));
            $this->userInfo2session($cellphone);
        }catch (Exception $e){
        }

        if ($this->db->affected_rows() == 1){
            return true;
        }else{
            return false;
        }
    }


    /**
     * function userInfo2session 获取用户信息，存到session中
     * @param
     * @return
     * @author yushaojun
     */
    public function userInfo2session($cellphone){
        $this->db->where('cellphone',$cellphone);
        $res = $this->db->get('user')->result_array();
        if ($res){
            $res = $res[0];
            $this->session->set_userdata($res);
        }
    }

    /**
     * function logout 登出
     * @param
     * @return
     * @author yushaojun
     */
    public function logout(){
        $this->session->sess_destroy();
    }
}