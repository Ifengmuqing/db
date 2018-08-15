<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Man_model extends CI_Model
{

    public function __construc()
    {

        parent::__construct();

    }
    function addRecond($name, $phone, $productName, $applyTime, $need, $rate, $costType, $fact, $percent, $remark)
    {
        $query = $this->db->query("insert into applylist (name,phone,productName,applyTime,need,rate,costType,fact,percent,remark) values('$name','$phone','$productName','$applyTime','$need','$rate','$costType','$fact','$percent','$remark')");
        return $query;
    }
    function addRecondNew($name, $phone, $identity, $productName, $applyTime, $need, $rate, $costType, $fact, $percent, $remark)
    {
        $query = $this->db->query("insert into applylist (name,phone,identity,productName,applyTime,need,rate,costType,fact,percent,remark) values('$name','$phone','$identity','$productName','$applyTime','$need','$rate','$costType','$fact','$percent','$remark')");
        return $query;
    }
    function updateRecond($name, $phone, $productName, $need, $rate, $state, $costType, $fact, $percent, $remark, $applyTime, $fileTime, $checkingTime, $loanedTime, $paiedTime, $id)
    {
        // $query = $this->db->query("update  applylist (name,phone,productName,time,need,rate,costType,fact,percent,remark,applyTime,fileTime,checkingTime,loanedTime,paiedTime) values('$name','$phone','$productName','$time','$need','$rate','$costType','$fact','$percent','$remark','$applyTime','$fileTime', '$checkingTime','$loanedTime','$paiedTime')");
        $query = $this->db->query("UPDATE applylist SET name = '$name',phone='$phone',productName='$productName',need='$need', rate='$rate',state='$state',costType='$costType', fact='$fact',percent='$percent',remark='$remark',applyTime='$applyTime',fileTime='$fileTime',checkingTime='$checkingTime',loanedTime='$loanedTime',paiedTime='$paiedTime' WHERE id = '$id'");
        return $query;
    }
    function getRecond()
    {
        $query = $this->db->query("select * from applylist");
        return $query->result_array();
    }
    function getOneRecond($id)
    {
        $query = $this->db->query("SELECT * FROM applylist where id='$id'");
        return $query->row_array();
    }
    function getProcessDetials($id)
    {
        $query = $this->db->query("select * from applylist where id='$id' ");
        return $query->row_array();
    }
    function getProcessDetialsByID($identity)
    {
        $query = $this->db->query("select * from applylist where identity='$identity'");
        return $query->row_array();
    }
    function delete($id)
    {
        $query = $this->db->query("delete from applylist where id='$id'");
        return $query;
    }
    function hasOpenid($openid)
    {
        $query = $this->db->query("select * from weixin where openid='$openid'");
        return $this->db->affected_rows();
    }
    function getWeixinList()
    {
        $query = $this->db->query("select * from weixin where state=0");
        return $query->result_array();
    }
    function getAllByOpenid($openid)
    {
        $query = $this->db->query("select * from applylist,weixin where identity='$id'");
        return $query->row_array();
    }
    function getCommon($table_name, $array, $offset = 0)
    {
         $query=$this->db->get_where($table_name, $array, $offset);
         return $query->row_array();
    }
    function updateCommon($tabel_name,$array,$where)
    {
        $sql=$this->db->update_string($tabel_name, $array, $where);
        $query=$this->db->query($sql);
        return $query;
    }
    function getStrangers()
      {
            $query = $this->db->query("select stranger.id,stranger.applyname,stranger.applyPhoneNum,stranger.applyTime,stranger.cID,city.cname from stranger left join city on stranger.cid=city.cid  ORDER BY stranger.applyTime DESC");
            return $query->result_array();
      }
    // 获取客户列表
    function getCustomers(){
        // $query= $this->db->query("select * from customers order by uid desc");
        // return $query->result_array();
        $query= $this->db->query("select * from customers order by uid desc");
        return $query->result_array();
    }
    function getProcess($uid){
        // $query= $this->db->query("select * from customers order by uid desc");
        // return $query->result_array();
        $query= $this->db->query("select * from loan where uid=$uid order by id asc");
        return $query->result_array();
    }
      // 获取客户列表
      function getCustomersNew(){
        // $query= $this->db->query("select * from customers order by uid desc");
        // return $query->result_array();
        $query = $this->db->query("select customers.uid,customers.date,customers.name,customers.ifweixin,customers.phone,customers.requirement,customers.remark,customers.costType,customers.percent,customers.cost,customers.income,loan.process,loan.bankProduct,loan.quota,loan.applyQuota from customers left join loan on customers.uid=loan.uid order by customers.date desc,customers.uid desc");
        return $query->result_array();
    }
      // 获取客户列表
    function getCust()
    {
        // $query= $this->db->query("select * from customers order by uid desc");
        // return $query->result_array();
        $query = $this->db->query("select customers.uid,customers.date,customers.name,customers.ifweixin,customers.phone,customers.requirement,customers.remark,customers.costType,customers.percent,customers.cost,customers.income,loan.process,loan.bankProduct,loan.quota,loan.applyQuota from customers left join loan on customers.uid=loan.uid order by customers.date desc,customers.uid desc");
        return $query->result_array();
    }
    // 搜索
    function search($keyword){
        $sql= $query = $this->db->query("select customers.uid,customers.date,customers.name,customers.ifweixin,customers.phone,customers.requirement,customers.remark,customers.costType,customers.percent,customers.cost,customers.income,loan.process,loan.bankProduct,loan.quota,loan.applyQuota from customers left join loan on customers.uid=loan.uid where customers.name like '%{$keyword}%'  or customers.phone like '%{$keyword}%' or customers.identity like '%{$keyword}%' order by customers.date desc,customers.uid desc");
        // $sql="select * from   where 字段一 like '%搜索词%'  or 字段二 like '%搜索词%' or 字段三 like '%搜索词%'"
        return $query->result_array();
    }
    // 进度筛选
    function searchProcess($process){
        $query = $this->db->query("select customers.uid,customers.date,customers.name,customers.ifweixin,customers.phone,customers.requirement,customers.remark,customers.costType,customers.percent,customers.cost,customers.income,loan.process,loan.bankProduct,loan.quota,loan.applyQuota from customers left join loan on customers.uid=loan.uid where loan.process='{$process}' order by customers.date desc,customers.uid desc");
        return $query->result_array();
    }
    //广告费列表，年份降序
    function getAllAD(){
        $query = $this->db->query("select * from adcost order by year desc");
        return $query->result_array();
    }
    // 获取月份收入
      function monthIncome($month){
        $query=$this->db->query("select * from  customers where incomeDate like '%$month%'");
        return $query->result_array();
          // $sql="select * from   where 字段一 like '%搜索词%'  or 字段二 like '%搜索词%' or 字段三 like '%搜索词%'"
    }
     // 获取年份客户来源
     function yearOrigin($year){
        $query=$this->db->query("select * from  customers where date like '%$year%'");
        return $query->result_array();
          // $sql="select * from   where 字段一 like '%搜索词%'  or 字段二 like '%搜索词%' or 字段三 like '%搜索词%'"
    }
    // 获取年月产品
    function getProducts($yearMonth){
        $query=$this->db->query("select * from  loan where loanedingTime like '%$yearMonth%'");
        return $query->result_array();
          // $sql="select * from   where 字段一 like '%搜索词%'  or 字段二 like '%搜索词%' or 字段三 like '%搜索词%'"
    }
    
}
?>