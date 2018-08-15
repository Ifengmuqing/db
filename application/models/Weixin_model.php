<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Weixin_model extends CI_Model
{

    public function __construc()
    {

        parent::__construct();

    }

    function hasOpenid($openid)
    {
        $query = $this->db->query("select * from weixinfocus where openid=$openid");
        return $query;
    }
    function addUser($name, $identity, $phone, $openid, $time)
    {
        $query = $this->db->query("insert into weixinfocus (username,identity,phone,openid,time)values('$name','$identity','$phone','$openid','$time')");
        return $query;
    }
    // 设置weixin的state和loanInfo状态
    function setUL($identity)
    {  
         //更新数据，被更新的数据会被自动转换和过滤
        $data = array('state' => 1, 'loanInfo' => 1);
        $where = "identity = '{$identity}'";
        $sql=$this->db->update_string('weixinfocus', $data, $where);
        $query = $this->db->query($sql);
        return $query;
    }
    function getIdentity($openid)
    {
        $query = $this->db->query("select identity from weixinfocus where openid='$openid'");
        return $query->row_array();
    }
      // 获取客户贷款信息列表
    function getLoan($identity){
        return $query->result_array();
    }
      // 获取客户信息
    function getCustomersInfo($uid){
        // $query= $this->db->query("select * from customers order by uid desc");
        // return $query->result_array();
        $query = $this->db->query("select customers.uid,customers.date,customers.name,customers.ifweixin,customers.phone,customers.requirement,customers.remark,customers.costType,customers.percent,customers.cost,customers.income,loan.process,loan.bankProduct,loan.* from customers left join loan on customers.uid=loan.uid where customers.uid='{$uid}' order by customers.uid desc,loan.id desc");
        return $query->result_array();
    }
  
}
?>