<?php
defined('BASEPATH') or exit('No direct script access allowed');
class admin extends CI_Controller
{
    public function __construct(){  
        parent::__construct();
        $this->load->model('Man_model', 'man');
        $this->load->model('Common_model', 'c');
    } 
    public function logout(){
        unset($_SESSION['user']);
        redirect("admin/login");
    }
    public function index()
    {
        $this->isLogin();
        $data['sess_id']= $_SESSION['sid'] = mt_rand(1000, 9999);
        $this->load->view('weixin/main.html',$data);
    }
    public function login(){
        $this->load->view("weixin/login.html");
    }
    // 登录
    public function loginSession(){
        $this->load->view("weixin/loginSession.html");
    }
    public function handleLogin(){
        $username=addslashes(trim($this->input->post('username')));
        $password=trim($this->input->post('password'));
        $passwordMD5=md5($password);
        $this->load->model("Login_model",'lg');
        $res=$this->lg->isUser($username,$passwordMD5);
        if($res){
            $_SESSION['user']=$username;
            setcookie ( "username", $username, time () + 3600 * 24 * 365 );  
            setcookie ( "password", $password, time () + 3600 * 24 * 365 );  
            redirect('admin');
        }else{
             redirect(site_url("admin/loginSession"));
       }
    }
    // 判断是否登录
    private function isLogin()
    {
        if (empty($_SESSION['user'])) {
            if (empty($_COOKIE['username']) || empty($_COOKIE['password'])) {
                redirect('admin/login'); // 转到登录页面，记录请求的url，登录后跳转过去，用户体验好。  
            } else {
                $this->load->model("Login_model", 'lg');
                $res = $this->lg->isUser($username, $password);
                $user = $res['usernamers']; // 去取用户的个人资料  
                if (empty($user)) {
                    redirect('admin/login');
                } else {
                    $_SESSION['user'] = $user; // 用户名和密码对了，把用户的个人资料放到session里面  
                }
            }
        }
    }
    // 客户详情页
    public function customerDetials(){
        $this->isLogin();
        $uid=$this->input->get("uid");
        $userinfo=$this->c->get_row('customers',array('uid'=>$uid));
        // $process=$this->c->get_what('loan',array('uid'=>$uid));
        $process=$this->man->getProcess($uid);
        $loanedrecord=$this->c->get_what('loanedrecord',array('uid'=>$uid));
        $imgDataState=false;
        $imgData=$this->c->get_row('customerimages',array('uid'=>$uid));
        if($imgData!=null){
            $imgDataState=true;
        }
        $data=array(
            'userinfo'=>$userinfo,
            'process'=>$process,
            'record'=>$loanedrecord,
            'imgDataState'=>$imgDataState
        );
		$this->load->view("weixin/customerDetials.html",$data);
    }
    // 新增客户
    public function HandleAdd()
    {   
        $this->isLogin();
        if (isset($_SESSION['sid']) && isset($_POST['sid'])) {
            $applyDate =trim($this->input->post('applyDate'));
            $name = trim($this->input->post('name'));
            $identity = trim($this->input->post('identity'));
            $phone = trim($this->input->post('phone'));
            $origin = trim($this->input->post('origin'));
            $requirement = trim($this->input->post('requirement'));
            $costType = trim($this->input->post('costType'));
            $percent = trim($this->input->post('percent'));
            $fact = trim($this->input->post('fact'));
            $remark = trim($this->input->post('remark'));            
            $res=$this->c->insert_what("customers",array(
                'date'=>$applyDate,
                'name'=>$name,
                'identity'=>$identity,
                'phone'=>$phone,
                'source'=>$origin,
                'requirement'=>$requirement,
                'costType'=>$costType,
                'percent'=>$percent,
                'cost'=>$fact,
                'remark'=>$remark,
            ));
            if ($res) {
                echo json_encode(array(
                    'state'=>"ok"
                ));
            } else {
                echo json_encode(array(
                    'state'=>"fail"
                ));
            }
        }

    }
    // 新增进度信息   
    public function handleAddLoan(){
        $this->isLogin();
        $uid =trim($this->input->post('uid'));
        $applyTime=trim($this->input->post('applyTime'));
        $bankProduct = trim($this->input->post('bankProduct'));
        $rate = trim($this->input->post('rate'));
        $process = trim($this->input->post('process'));
        $quota = trim($this->input->post('quota'));
        $applyQuota = trim($this->input->post('applyQuota'));
        $dealTime = trim($this->input->post('dealTime'));
        $checkTime = trim($this->input->post('checkTime'));
        $loanedingTime = trim($this->input->post('loanedingTime'));
        $remark = trim($this->input->post('remark'));
        $res=$this->c->insert_what("loan",array(
            'uid'=>$uid,
            'applyTime'=>$applyTime,
            'bankProduct'=>$bankProduct,
            'rate'=>$rate,
            'process'=>$process,
            'quota'=>$quota,
            'applyQuota'=>$applyQuota,
            'dealTime'=>$dealTime,
            'checkTime'=>$checkTime,
            'loanedingTime'=>$loanedingTime,
            'remark'=>$remark,
        ));
        if ($res) {
            echo json_encode(array(
                'state'=>"ok"
            ));
        } else {
            echo json_encode(array(
                'state'=>"fail"
            ));
        }
    }
    // 修改贷款信息   
    public function handleUpdateLoan(){
        $this->isLogin();
        $id =trim($this->input->post('id'));
        $uid=trim($this->input->post('uid'));
        $bankProduct = trim($this->input->post('bankProduct'));
        $rate = trim($this->input->post('rate'));
        $process = trim($this->input->post('process'));
        $quota = trim($this->input->post('quota'));
        $applyQuota = trim($this->input->post('applyQuota'));
        $dealTime = trim($this->input->post('dealTime'));
        $checkTime = trim($this->input->post('checkTime'));
        $loanedingTime = trim($this->input->post('loanedingTime'));
        $remark = trim($this->input->post('remark'));

        $res=$this->c->update_what("loan", array('id'=>$id),array(
            'id'=>$id,
            'bankProduct'=>$bankProduct,
            'rate'=>$rate,
            'process'=>$process,
            'quota'=>$quota,
            'applyQuota'=>$applyQuota,
            'dealTime'=>$dealTime,
            'checkTime'=>$checkTime,
            'loanedingTime'=>$loanedingTime,
            'remark'=>$remark,
        ));
        if ($res) {
            echo json_encode(array(
                'state'=>"ok"
            ));
        } else {
            echo json_encode(array(
                'state'=>"fail"
            ));
        }
    }
    //调取
    public function updateLoan(){
        $id=$this->input->get("id");
        $uid=$this->input->get("uid");
        $name=$this->input->get("name");
        $process=$this->c->get_row('loan',array('id'=>$id));
        $data=array(
            'uid'=>$uid,
            'id'=>$id,
            'name'=>$name,
            'process'=>$process
        );
        $this->load->view('weixin/updateLoan.html',$data);
    }
    // 删除贷款信息
    public function delLoan(){
        $this->isLogin();
        $loanid=$this->input->post('loanid');
        $query=$this->c->delete_what('loan',array('id'=>$loanid));
        if($query){
            echo json_encode(array(
                'state'=>'ok'
            ));
        }else{
            echo json_encode(array(
                'state'=>'fail'
            ));
        }  
    }
    // X年 X月 转成月份数
    function countMonth($term=''){
        $month=0;
        if(preg_match('/\d+/',$term,$arr)){
            $month+=12*$arr[0];
        }
        if(preg_match('/\s\d+/',$term,$arr)){
            $month+= $arr[0];
        }
        return $month;
    }
    // 查找
    function findNum($str){
        if(preg_match('/\d+/',$str,$arr)){
            return $arr[0];
        }
    }
    //新增还款记录
    public function handleAddLoanRecord(){
        $this->isLogin();
        $uid =trim($this->input->post('uid'));
        $bank = trim($this->input->post('bank'));
        $loanMoney = trim($this->input->post('loanMoney'));
        $backType = trim($this->input->post('backType'));
        $rate = trim($this->input->post('rate'));
        $term = trim($this->input->post('term'));
        $loanedingDate = trim($this->input->post('loanedingDate'));
        $backDate = trim($this->input->post('backDate'));
        $perBack = trim($this->input->post('perBack'));
        // 计算月份数
        $month=$this->countMonth($term);
        // 获取还款日
        $loanTypeNum=0;
        $backDateNum=$this->findNum($backDate);
        if($backType=="等额等息(月利率)"){
            $loanTypeNum=0;
        }else if($backType=="等额本息(年利率)"){
            $loanTypeNum=1;
        }else if($backType=="先息后本(月利率)"){
            $loanTypeNum=2;
        }
        $res=$this->c->insert_what("loanedrecord",array(
            'uid'=>$uid,
            'bank'=>$bank,
            'loanMoney'=>$loanMoney,
            'backType'=>$loanTypeNum,
            'rate'=>$rate,
            'term'=>$month,
            'loanedingDate'=>$loanedingDate,
            'backDate'=>$backDateNum,
            'perBack'=>$perBack,
        ));  
        if ($res) {
            echo json_encode(array(
                'state'=>"ok"
            ));
        } else {
            echo json_encode(array(
                'state'=>"fail"
            ));
        }
    }
    //更新还款记录
    public function updateLoanRecord(){
        $id =trim($this->input->post('id'));
        $uid =trim($this->input->post('uid'));
        $bank = trim($this->input->post('bank'));
        $loanMoney = trim($this->input->post('loanMoney'));
        $backType = trim($this->input->post('backType'));
        $rate = trim($this->input->post('rate'));
        $term = trim($this->input->post('term'));
        $loanedingDate = trim($this->input->post('loanedingDate'));
        $backDate = trim($this->input->post('backDate'));
        $perBack = trim($this->input->post('perBack'));
        // 计算月份数
        $month=$this->countMonth($term);
        // 获取还款日
        $backDateNum=$this->findNum($backDate);
        $loanTypeNum=0;
        if($backType==="等额等息(月利率)"){
            $loanTypeNum=0;
        }else if($backType==="等额本息(年利率)"){
            $loanTypeNum=1;
        }else if($backType==="先息后本(月利率)"){
            $loanTypeNum=2;
        }
        $res=$this->c->update_what("loanedrecord",array('id'=>$id),array(
            'bank'=>$bank,
            'loanMoney'=>$loanMoney,
            'backType'=>$loanTypeNum,
            'rate'=>$rate,
            'term'=>$month,
            'loanedingDate'=>$loanedingDate,
            'backDate'=>$backDateNum,
            'perBack'=>$perBack,
        ));
        if ($res) {
            echo json_encode(array(
                'state'=>"ok"
            ));
        } else {
            echo json_encode(array(
                'state'=>"fail"
            ));
        }
    }
    public function HandleAddNew()
    {
        $this->isLogin();
        if (isset($_SESSION['add'])) {
            $name = trim($this->input->post('name'));
            $phone = trim($this->input->post('phone'));
            $applyTime = strtotime(trim($this->input->post('applyTime')));
            $productName = trim($this->input->post('productName'));
            $identity = trim($this->input->post('identity'));
            $need = trim($this->input->post('need'));
            $rate = trim($this->input->post('rate'));
            $costType = trim($this->input->post('costtype'));
            $percent = trim($this->input->post('percent'));
            $fact = trim($this->input->post('fact'));
            $remark = trim($this->input->post('remark'));
            $res = $this->man->addRecondNew($name, $phone, $identity, $productName, $applyTime, $need, $rate, $costType, $fact, $percent, $remark);
            if ($res) {
                $this->load->model("weixin_model", 'wx');
                $this->wx->setUL($identity);
                $data = array(
                    'icon' => 'weui-icon-success',
                    'where' => $_SERVER['HTTP_REFERER'],
                    'result' => '添加成功'
                );
                $this->load->view('weixin/msg.html', $data);
                unset($_SESSION['add']);
            } else {

            }

        } else {
            $data = array(
                'icon' => 'weui-icon-success',
                'where' => '',
                'result' => '请勿重新提交'
            );
            $this->load->view('weixin/msg2.html', $data);
        }

    }
  
    public function noprocess()
    {
        $this->load->view('weixin/noprocess.html');
    }
    public function HandleModify()
    {
        $this->isLogin();
        if (isset($_SESSION['modify'])) {
            $name = trim($this->input->post('name'));
            $phone = trim($this->input->post('phone'));
            $productName = trim($this->input->post('productName'));
            $need = trim($this->input->post('need'));
            $rate = trim($this->input->post('rate'));
            $costType = trim($this->input->post('costtype'));
            $percent = trim($this->input->post('percent'));
            $fact = trim($this->input->post('fact'));
            $remark = trim($this->input->post('remark'));
            $applyTime = strtotime(trim($this->input->post('applyTime')));
            $fileTime = strtotime(trim($this->input->post('fileTime')));
            $checkingTime = strtotime(trim($this->input->post('checkingTime')));
            $loanedTime = strtotime(trim($this->input->post('loanedTime')));
            $paiedTime = strtotime(trim($this->input->post('paiedTime')));
            $id = trim($this->input->post('id'));
            $state = trim($this->input->post('state'));
            $this->load->model('Man_model', 'man');
            if ($this->man->updateRecond($name, $phone, $productName, $need, $rate, $state, $costType, $fact, $percent, $remark, $applyTime, $fileTime, $checkingTime, $loanedTime, $paiedTime, $id)) {
                $data = array(
                    'icon' => 'weui-icon-success',
                    'where' => 'admin',
                    'result' => '更新成功'
                );
                $this->load->view('weixin/msg.html', $data);
            }
            unset($_SESSION['modify']);
        } else {
            $data = array(
                'icon' => 'weui-icon-success',
                'where' => 'admin',
                'result' => '请勿重新提交'
            );
            $this->load->view('weixin/msg.html', $data);
        }


    }
    public function add()
    {
        $this->isLogin();
        $_SESSION['add'] = 1;
        $name = trim($this->input->get('username'));
        $phone = trim($this->input->get('phone'));
        $identity = trim($this->input->get('identity'));
        $data = array(
            'name' => $name,
            'phone' => $phone,
            'identity' => $identity
        );
        $this->load->view('weixin/add.html', $data);
    }
    public function Modify()
    {
        $this->isLogin();
        $_SESSION['modify'] = 1;
        $id = trim($this->input->get('id'));
        $this->load->model('Man_model', 'man');
        $res = $this->man->getOneRecond($id);
        $data = array(
            'data' => $res,
        );
        $this->load->view("weixin/modify.html", $data);
    }
    // 获取申请者列表
    public function getStrangers()
	{
        $this->isLogin();
        $res = $this->man->getStrangers();
        for ($i = 0, $len = count($res); $i < $len; $i++) {
            $res[$i]['applyTime'] = date("Y-m-d", $res[$i]['applyTime']);
        }
        echo json_encode($res);
		// $this->load->view('m/applyList.html', $data);
    }
    // 获取客户列表
    public function getCustomers(){
        $this->isLogin();
        // $res=$this->c->get_what('customers');
        // $res=$this->man->getCustomers();
        $res=$this->man->getCust();
        $uidArray=array();
        $customers=array();
        // 数据比较选择
        foreach($res as $key =>$value){       
            if(!in_array($value['uid'],$uidArray)){
                array_push($uidArray,$value['uid']);
                array_push($customers,$value);
            }else{
                foreach($customers as $k=>$v){
                    if($v['uid']==$value['uid']){
                        // $pn=intval($value['process']);
                        // $po=intval($v['process']);
                        // if($po>$pn){
                        //     $customers[$k]=$value;
                        // }
                        $pn=intval($value['process']);
                        $po=intval($v['process']);
                        if($pn!=2 && $po==2){

                        }else{
                            $customers[$k]=$value;
                        }
                    }
                }
            }
        }  
        // 数据处理
        foreach($customers as $key=>$value){
            // loan表没有对应记录处理
            if($value['bankProduct']==null){
                $customers[$key]['bankProduct'] ="";
            }
            // 是否关注微信处理
            if($value['ifweixin']==null ||$value['ifweixin']==0){
                $customers[$key]['weixinClass'] ="noFocus";
            }else{
                $customers[$key]['weixinClass'] ="isFocus";
            }
            // 进度显示处理
            if($value['process']==null){
                $customers[$key]['process'] ="待处理";
                $customers[$key]['btnClassName'] ="loan_normal";
                if($value['costType']==0){
                    $customers[$key]['showMoney']=($value['requirement']*$value['percent']*10000/100);
                }else{
                    $customers[$key]['showMoney']=$value['cost'];
                }
            }else if($value['process']==0){
                $customers[$key]['process'] ="已申请";
                $customers[$key]['btnClassName'] ="loan_normal";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota'];
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }
            }else if(($value['process']==1)){
                $customers[$key]['process'] ="完成进件";
                $customers[$key]['btnClassName'] ="loan_normal";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }
             
            }else if(($value['process']==2)){
                $customers[$key]['process'] ="系统审核中";
                $customers[$key]['btnClassName'] ="loan_checking";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }
              
            }else if(($value['process']==3)){
                $customers[$key]['process'] ="已放款";
                $customers[$key]['btnClassName'] ="loan_success";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }
              
            }else if(($value['process']==4)){
                $customers[$key]['process'] ="终审被拒";
                $customers[$key]['btnClassName'] ="loan_fail";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }  
            }
            // 已收款判断
            if($value['income']>0){
                $customers[$key]['showMoney']=$value['income'];
                $customers[$key]['incomeClass']="incomeClass";
            }else{
                $customers[$key]['incomeClass']="";
            }         
            // 取整显示
            $customers[$key]['showMoney']=intval($customers[$key]['showMoney']);

        }
        echo json_encode($customers);
        // p($customers);
    }
    // 获取
    public function getApplyList()
    {
        $this->isLogin();
        $this->load->model('Man_model', 'man');
        $res = $this->man->getRecond();
        for ($i = 0, $len = count($res); $i < $len; $i++) {
            $res[$i]['applyTime'] = date("Y-m-d", $res[$i]['applyTime']);
        }
        echo json_encode($res);
    }
    public function delete()
    {
        $this->isLogin();
        $id = trim($this->input->get('id'));
        $this->load->model('Man_model', 'man');
        $applyInfo = $this->man->getCommon('applylist', array('id' => $id), 0);
        $identity=$applyInfo['identity'];
        $this->man->updateCommon('weixin', array('loanInfo'=>0), "identity='{$identity}'");
        $res = $this->man->delete($id);
        if ($res) {
            $data = array(
                'icon' => 'weui-icon-success',
                'where' => 'admin',
                'result' => '删除成功'
            );
            $this->load->view('weixin/msg.html', $data);
        }

    }
    // 新增贷款进度
    public function addLoan(){
        $uid=trim($this->input->get("uid"));
        $name=trim($this->input->get("name"));
        $data=array(
            'uid'=>$uid,
            'name'=>$name
        );
        $this->load->view('weixin/addLoan.html',$data);
    }
    // 贷后管理
    public function loanedRecord(){
        $uid=trim($this->input->get("uid"));
        $name=trim($this->input->get("name"));
        $data=array(
            'uid'=>$uid,
            'name'=>$name
        );
        $this->load->view('weixin/loanedRecord.html',$data);
    }
    // 更新还款信息
    public function updateRecord(){
        $this->isLogin();
        $id=$this->input->get('id');
        $uid=$this->input->get('uid');
        $name=$this->input->get('name');
        $record=$this->c->get_row("loanedrecord",array('id'=>$id));
        $data=array(
            'uid'=>$uid,
            'id'=>$id,
            'name'=>$name,
            'record'=>$record
        );
        $this->load->view('weixin/updateRecord.html',$data);
    }
    // 删除进度信息
    public function delRecord(){
        $this->isLogin();
        $recordid=$this->input->post('recordid');
        $query=$this->c->delete_what('loanedrecord',array('id'=>$recordid));
        if($query){
            echo json_encode(array(
                'state'=>'ok'
            ));
        }else{
            echo json_encode(array(
                'state'=>'fail'
            ));
        }  
    }
    //删除客户
    public function delCustomer(){
        $this->isLogin();
        $uid=trim($this->input->post('uid'));
        $query=$this->c->delete_what('loanedrecord',array('uid'=>$uid));
        $query2=$this->c->delete_what('customers',array('uid'=>$uid));
        $query4=$this->c->get_what('customerimages',array('uid'=>$uid));
        $query3=$this->c->delete_what('loan',array('uid'=>$uid));
        $query5=$this->c->delete_what('weixinfocus',array('uid'=>$uid));

        if($query4){
            for($i=0;$i<count($query4);$i++){
                unlink($query4[$i]['real_url']);
                unlink($query4[$i]['real_thumb_url']);
                $this->c->delete_what('customerimages',array('id'=>$query4[$i]['id']));
            }
        }
        if($query && $query2 && $query3){
          echo json_encode(array('state' => 'ok'));

        }else{
          echo json_encode(array('state' => 'fail'));
        }
    }
    public function delCustomertest(){
        $this->isLogin();
        $uid=trim($this->input->get('uid'));
        $query=$this->c->delete_what('loanedrecord',array('uid'=>$uid));
        $query2=$this->c->delete_what('customers',array('uid'=>$uid));
        $query3=$this->c->delete_what('loan',array('uid'=>$uid));
        $query4=$this->c->get_what('customerimages',array('uid'=>$uid));
        if($query4){
            for($i=0;$i<count($query4);$i++){
                unlink($query4[$i]['real_url']);
                unlink($query4[$i]['real_thumb_url']);
                $this->c->delete_what('customerimages',array('id'=>$query4[$i]['id']));
            }
        }
        if($query && $query2 && $query3){
          echo json_encode(array('state' => 'ok'));

        }else{
          echo json_encode(array('state' => 'fail'));
        }
    }
        //保留两位小数
    public function toFix($num){
            return number_format($num, 2, '.', '');
    }
    // 删除陌生客户
    public function delStranger(){
        $this->isLogin();
        $id=trim($this->input->get('id'));
        $query=$this->c->delete_what('stranger',array('id'=>$id));
        if($query){
          echo json_encode(array('state' =>'ok'));
        }else{
          echo json_encode(array('state' => 'fail'));
        }
    }
    // 客户详情页单个属性修改
    public function modifyItem(){
        $this->isLogin();
        $table=trim($this->input->get('table'));
        $uid=trim($this->input->get('uid'));
        $where=trim($this->input->get('where'));
        $value=trim($this->input->get('value'));

        $query=$this->c->update_what($table,array('uid'=>$uid),array(
            $where=>$value
        ));
        if($query){
            echo json_encode(array('state' =>'ok'));
          }else{
            echo json_encode(array('state' => 'fail'));
          }
    }
    // 客户图片
    public function customerImg(){
        $uid=$this->input->get('uid');
        $uploaded=$this->c->get_what("customerimages",array('uid'=>$uid));
        $data=array(
            'uid'=>$uid,
            'uploaded'=>$uploaded
        );
        $this->load->view("weixin/customerImg.html",$data);
    }
    // 图片上传
    // public function handleUploadImg(){
    //     $imgData = $_REQUEST['images'];
    //     $uid=$this->input->post('uid');
    //     if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $imgData, $result)) {
    //         $type = $result[2];
    //         $rand = rand(1000, 9999);
    //         $new_file = './uploads/' . $rand . '.' . $type;
    //         $url='uploads/'.$rand.'.'.$type;
    //         $real_url=$_SERVER['DOCUMENT_ROOT'].'/'.$url;
            
    //         if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $imgData)))) {
    //             $data=array(
    //                 'uid'=>$uid,
    //                 'name'=>$rand.$type,
    //                 'url'=>base_url($url),
    //                 'real_url'=>$real_url
    //             );
    //             $query=$this->c->insert_what("customerimages",$data);
    //             // dealthumb($real_url);
    //         }
    //     }
    // }
    public function handleUploadImg(){
        $imgData = $_REQUEST['images'];
        $uid=$this->input->post('uid');
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $imgData, $result)) {
            $type = $result[2];
            $file_name =date('YmdHis', time()).rand(10000, 99999);
            $new_file = './uploads/' . $file_name . '.' . $type;
            $url='uploads/'.$file_name.'.'.$type;
            $thumb_url='uploads/'.$file_name.'_thumb.'.$type;
            $real_url=$_SERVER['DOCUMENT_ROOT'].'/'.$url;
            $real_thumb_url=$_SERVER['DOCUMENT_ROOT'].'/'.$thumb_url;
            
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $imgData)))) {
                $this->dealthumb($real_url);
                $data=array(
                    'uid'=>$uid,
                    'name'=>$file_name,
                    'url'=>base_url($url),
                    'thumb_url'=>base_url($thumb_url),
                    'real_url'=>$real_url,
                    'real_thumb_url'=>$real_thumb_url
                    
                );
                $query=$this->c->insert_what("customerimages",$data);
                // dealthumb($real_url);
            }
        }
    }
  /** 
     * 生成缩略图 
     * @param   $path 原图的本地路径 
     * @return  null 创建一个 原图_thumb.扩展名 的文件 
     * 
     */  
    public function dealthumb($path){  
  
        $config['image_library'] = 'gd2';  
        $config['source_image'] = $path;  
        $config['create_thumb'] = TRUE;  
        //生成的缩略图将在保持纵横比例 在宽度和高度上接近所设定的width和height  
        $config['maintain_ratio'] = TRUE;  
        $config['width'] = 80;  
        $config['height'] = 80;  
  
        $this->load->library('image_lib', $config);  
  
        $this->image_lib->resize();  
        $this->image_lib->clear();
    }
    /* 
     * 处理图像旋转 
     */  
    public function transroate($path,$imgpath){  
  
        $this->load->library('image_lib');  
        //(必须)设置图像库  
        $config['image_library'] = 'gd2';  
  
        $newname = time().'_rote.jpg';  
        //设置图像的目标名/路径  
        $config['new_image'] =$imgpath.$newname;  
        //(必须)设置原始图像的名字/路径  
        $config['source_image'] = $path;  
        //决定新图像的生成是要写入硬盘还是动态的存在  
        $config['dynamic_output'] = FALSE;  
        //设置图像的品质。品质越高，图像文件越大  
        $config['quality'] = '90%';  
        //有5个旋转选项 逆时针90 180 270 度 vrt 竖向翻转 hor 横向翻转  
        $config['rotation_angle'] = 'vrt';  
  
        $this->image_lib->initialize($config);  
  
        if(@$this->image_lib->rotate()){  
            $this->image_lib->clear();  
            return $config['new_image'];  
        }else{  
            $this->image_lib->clear();  
            return '';  
        }  
    }  
  
    /** 
     * 处理图像水印 
     */  
    public function overlay($path,$imgpath){  
  
        $this->load->library('image_lib');  
        $newname = time().'_over.jpg';  
        //设置新图像名称  
        $config['new_image'] =$imgpath.$newname;  
        //调用php gd库 绘图  
        $config['image_library'] = 'gd2';  
        //源图像 本地地址  
        $config['source_image'] = $path;  
        //覆盖文字  
        $config['wm_text'] = 'Copyright 2015 - Friker';  
        //覆盖类型 文字/图像  
        $config['wm_type'] = 'text';  
        //文字字体类型  
        //$config['wm_font_path'] = 'C:\Windows\Fonts\vrinda.ttf';  
        //字体大小  
        $config['wm_font_size'] = '16';  
        //字体颜色  
        $config['wm_font_color'] = 'ff0000';  
        //垂直方向距离顶端距离  
        $config['wm_vrt_alignment'] = '20';  
        //水平方向距离左端距离  
        $config['wm_hor_alignment'] = 'center';  
        //padding  
        $config['wm_padding'] = '20';  
  
        $this->image_lib->initialize($config);  
  
        if($this->image_lib->watermark()){  
            $this->image_lib->clear();  
            return $config['new_image'];  
        }else{  
            $this->image_lib->clear();  
            return '';  
        }  
    }  
  
     /** 
      *   处理图片上传 
      *   文件上传类 通过前台 上传文件 
      */  
  
    public function uploadfile(){  
        //文件上传部分  
        // 处理文件  
        // $data = '';  
        $this->load->helper('url');  
        $formpic = key($_FILES);  
        //文件处理部分  
        if(false === empty($_FILES[$formpic]['tmp_name'])){  
            //设置文件上传的路径  
            $upload['upload_path'] = "./public/img/";  
            //限制文件上传的类型  
            $upload['allowed_types'] = 'jpeg|jpg|gif|png';  
            //限制文件上传的大小  
            $upload['max_size'] = 2048;  
            //设置文件上传的路径  
            $upload['file_name'] = date('YmdHis', time()).rand(10000, 99999);  
  
            //加载文件上传配置信息  
            $this->load->library('upload', $upload);  
            //处理文件上传  
            $this->upload->do_upload($formpic);  
  
            //返回文件上传信息  
            $image = $this->upload->data();  
            /* 
              'file_name' => string '2015071702051718388.jpg' (length=23) 
              'file_type' => string 'image/jpeg' (length=10) 
              'file_path' => string 'E:/wamp/www/testci/public/img/' (length=30) 
              'full_path' => string 'E:/wamp/www/testci/public/img/2015071702051718388.jpg' (length=53) 
              'raw_name' => string '2015071702051718388' (length=19) 
              'orig_name' => string '2015071702051718388.jpg' (length=23) 
              'client_name' => string 'u=415761610,1548338330&fm=116&gp=0.jpg' (length=38) 
              'file_ext' => string '.jpg' (length=4) 
              'file_size' => float 3.74 
              'is_image' => boolean true 
              'image_width' => int 146 
              'image_height' => int 220 
              'image_type' => string 'jpeg' (length=4) 
              'image_size_str' => string 'width="146" height="220"' (length=24) 
             */  
            //var_dump($image);  
            //返回文件上传名字  
            $data = $image['file_name'];  
            $this->dealthumb($image['full_path']);  
            $this->overlay($image['full_path'],$image['file_path']);  
            $this->transroate($image['full_path'],$image['file_path']);//  
  
            $thumbdata = '';  
            //生成缩略图名称  
            $pos = strripos($image['file_name'], ".");  
            $newname = substr($image['file_name'], 0,$pos)."_thumb".substr($image['file_name'], $pos);  
            if(file_exists($image['file_path'].$newname)){  
                $thumbdata = $newname;  
            }  
        }  
  
        //$dirroot = $_SERVER['DOCUMENT_ROOT'];  
        //$this->dealthumb($dirroot."/public/img/".$data);  
  
        //上传失败  
        if(!$data){  
            echo json_encode(array('status'=>0,'msg'=>"上传失败！"));  
        }else{  
        //上传成功  
            echo json_encode(array(  
                'name'=>$data,  
                'pic'=>base_url()."public/img/".$data,  
                'picthumb'=>$thumbdata == '' ?$data:$thumbdata  
                ));  
        }  
    }  
    public function delImg(){
        $arr=$this->input->post('arr');
        for($i=0;$i<count($arr);$i++){
            $filename=$this->c->get_row("customerimages",array("id"=>$arr[$i]));
            unlink($filename['real_url']);
            unlink($filename['real_thumb_url']);
            $this->c->delete_what('customerimages',array('id'=>$arr[$i]));
        }
        echo json_encode($arr);
    }
    public function test(){
        $res=$this->man->getCustomersNew();
        $uidArray=array();
        $customers=array();
        // $showMoneyArr=array();
        // p($res);
        foreach($res as $key =>$value){       
            if(!in_array($value['uid'],$uidArray)){
                array_push($uidArray,$value['uid']);
                array_push($customers,$value);
            }else{
                foreach($customers as $k=>$v){
                    if($v['uid']==$value['uid']){
                        $pn=intval($value['process']);
                        $po=intval($v['process']);
                        if($po>=$pn){
                            $customers[$k]=$value;
                        }
                    }
                }
            }
        }
        foreach($customers as $key=>$value){
            if($value['bankProduct']==null){
                $customers[$key]['bankProduct'] ="";
                
            }
            if($value['ifweixin']==null ||$value['ifweixin']==0){
                $customers[$key]['weixinClass'] ="noFocus";
            }else{
                $customers[$key]['weixinClass'] ="isFocus";
            }
            if($value['process']==null){
                $customers[$key]['process'] ="待处理";
                $customers[$key]['btnClassName'] ="normal";
                if($value['costType']==0){
                    $customers[$key]['showMoney']=($value['requirement']*$value['percent']*10000/100);
                }else{
                    $customers[$key]['showMoney']=$value['cost'];
                  
                }
            }else if($value['process']==0){
                $customers[$key]['process'] ="已申请";
                $customers[$key]['btnClassName'] ="normal";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=$value['percent']*$value['quota'];
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/$value['requirement']*10000)*$value['quota'];
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=$value['percent']*$value['applyQuota'];
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/$value['requirement'])*$value['applyQuota'];
                    }
                }
            }else if(($value['process']==1)){
                $customers[$key]['process'] ="完成进件";
                $customers[$key]['btnClassName'] ="normal";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=$value['percent']*$value['quota'];
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/$value['requirement'])*$value['quota'];
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=$value['percent']*$value['applyQuota'];
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/$value['requirement'])*$value['applyQuota'];
                    }
                }
             
            }else if(($value['process']==2)){
                $customers[$key]['process'] ="系统审核中";
                $customers[$key]['btnClassName'] ="checking";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=$value['percent']*$value['quota'];
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/$value['requirement'])*$value['quota'];
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=$value['percent']*$value['applyQuota'];
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/$value['requirement'])*$value['applyQuota'];
                    }
                }
              
            }else if(($value['process']==3)){
                $customers[$key]['process'] ="已放款";
                $customers[$key]['btnClassName'] ="success";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=$value['percent']*$value['quota'];
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/$value['requirement'])*$value['quota'];
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=$value['percent']*$value['applyQuota'];
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/$value['requirement'])*$value['applyQuota'];
                    }
                }
              
            }else if(($value['process']==4)){
                $customers[$key]['process'] ="终审被拒";
                $customers[$key]['btnClassName'] ="fail";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=$value['percent']*$value['quota'];
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/$value['requirement'])*$value['quota'];
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=$value['percent']*$value['applyQuota'];
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/$value['requirement'])*$value['applyQuota'];
                    }
                }     
            }
        }
        // p($customers);
    }
    public function income_statistics(){
        $this->isLogin();
        $res=$this->c->get_all_order('customers','incomeDate','desc');
        $year_arr=array();
        foreach($res as $key=>$value){
            $date=$value['incomeDate'];
            if($date!=''){
                preg_match('/^[0-9]{4}/', $date, $year);
                if(!in_array($year[0],$year_arr)){
                    array_push($year_arr,$year[0]);
                }
            }
        };
        $nowyear=$year_arr[0];
        $adcost=$this->c->get_what("adcost",array('year'=>$nowyear));
        $Jan=$this->calSum($this->man->monthIncome($nowyear."-01"));
        $Feb=$this->calSum($this->man->monthIncome($nowyear."-02"));
        $Mar=$this->calSum($this->man->monthIncome($nowyear."-03"));
        $Apr=$this->calSum($this->man->monthIncome($nowyear."-04"));
        $May=$this->calSum($this->man->monthIncome($nowyear."-05"));
        $Jun=$this->calSum($this->man->monthIncome($nowyear."-06"));
        $Jul=$this->calSum($this->man->monthIncome($nowyear."-07"));
        $Aug=$this->calSum($this->man->monthIncome($nowyear."-08"));
        $Sept=$this->calSum($this->man->monthIncome($nowyear."-09"));
        $Oct=$this->calSum($this->man->monthIncome($nowyear."-10"));
        $Nov=$this->calSum($this->man->monthIncome($nowyear."-11"));
        $Dec=$this->calSum($this->man->monthIncome($nowyear."-12"));
        $monthArray=array($Jan,$Feb, $Mar,$Apr,$May, $Jun,$Jul,$Aug,$Sept,$Oct,$Nov,$Dec);
        $monthTotal=$Jan+$Feb+$Mar+$Apr+$May+$Jun+$Jul+$Aug+$Sept+$Oct+$Nov+$Dec;
        // p($adcost);
        $adTotal=0;
        foreach($adcost as $key=>$value){
        //    p($value);
            foreach($value as $k=>$v){
              
                if($value[$k]!=$value['id']){
                   if($value[$k]!=$value['year']){
                        $adTotal+=$v;
                   }
                }
            }
        }
        $data=array(
            'year'=>$year_arr,
            'adcost'=>$adcost,
            'monthArray'=>$monthArray,
            'adTotal'=>$adTotal/10000,
            'monthTotal'=>$monthTotal
        );
        $this->load->view("weixin/income_statistics.html",$data);
    }
    public function getStatistics(){
        $this->isLogin();
        $year=$this->input->post('year');
        $res=$this->c->get_all_order('customers','date','desc');
        $nowyear=$year;
        $adcost=$this->c->get_what("adcost",array('year'=>$nowyear));
        $Jan=$this->calSum($this->man->monthIncome($nowyear."-01"));
        $Feb=$this->calSum($this->man->monthIncome($nowyear."-02"));
        $Mar=$this->calSum($this->man->monthIncome($nowyear."-03"));
        $Apr=$this->calSum($this->man->monthIncome($nowyear."-04"));
        $May=$this->calSum($this->man->monthIncome($nowyear."-05"));
        $Jun=$this->calSum($this->man->monthIncome($nowyear."-06"));
        $Jul=$this->calSum($this->man->monthIncome($nowyear."-07"));
        $Aug=$this->calSum($this->man->monthIncome($nowyear."-08"));
        $Sept=$this->calSum($this->man->monthIncome($nowyear."-09"));
        $Oct=$this->calSum($this->man->monthIncome($nowyear."-10"));
        $Nov=$this->calSum($this->man->monthIncome($nowyear."-11"));
        $Dec=$this->calSum($this->man->monthIncome($nowyear."-12"));
        $monthArray=array($Jan,$Feb, $Mar,$Apr,$May, $Jun,$Jul,$Aug,$Sept,$Oct,$Nov,$Dec);
        $monthTotal=$Jan+$Feb+$Mar+$Apr+$May+$Jun+$Jul+$Aug+$Sept+$Oct+$Nov+$Dec;
        // p($adcost);
        $adTotal=0;
        $adArray=array();
        foreach($adcost as $key=>$value){
        //    p($value);
            foreach($value as $k=>$v){
              
                if($value[$k]!=$value['id']){
                   if($value[$k]!=$value['year']){
                        $adTotal+=$v;
                        array_push($adArray,$this->toFix($v/10000));
                   }
                }
            }
        }
        $monthArray=array_reverse($monthArray);
        // $adArray=array_reverse($adArray);
        $data=array(
            'adcost'=>$adcost,
            'monthArray'=>$monthArray,
            'adTotal'=>$this->toFix($adTotal/10000),
            'monthTotal'=>$monthTotal,
            'adArray'=>$adArray
        );
        echo json_encode($data);
    }
    private function calSum($monthIncome){
        $FanSum=0;
        foreach($monthIncome as $key=>$value){
            if($value['income']>0){
                $FanSum+=$value['income']/10000;
            }
        }
        return $FanSum;
    }
    public function customer_orign(){
        $this->isLogin();
        $year_arr=array();
        $source=array();
        $res=$this->c->get_all_order('customers','date','desc');
        foreach($res as $key=>$value){
            $date=$value['date'];
            if($date!=''){
                preg_match('/^[0-9]{4}/', $date, $year);
                if(!in_array($year[0],$year_arr)){
                    array_push($year_arr,$year[0]);
                }
            }
        };
        // $nowyearcount=$this->c->get_what("customers",array('year'=>$nowyear));
        $nowyear=$year_arr[0];
        $yearOriginArr=$this->man->yearOrigin($nowyear);

        foreach($yearOriginArr as $key=>$value){
            if($value['source']!=''){
                array_push($source,$value['source']);
            }
           
        }
        $sourceArr=array_count_values($source);
        asort($sourceArr);
        $nameArr=array();
        $timeArr=array();
        foreach($sourceArr as $key=>$value){
            array_push($nameArr,$key);
            array_push($timeArr,$value);
        }
        $data=array(
            'nameArr'=>$nameArr,
            'timeArr'=>$timeArr,
            'year'=>$year_arr,
        );
        $this->load->view("weixin/customer_orign.html",$data);
    }
    public function product_statistics(){
        $this->isLogin();
        $year_arr=array();
        $month_arr=array();
        $yearMonth_arr=array();
        $res=$this->c->get_all_order('loan','loanedingTime','desc');
        $products=array();
        $productTotal=array();
        foreach($res as $key=>$value){
            if($value['quota']>0){
                array_push($products,$value);    
            }
        }
        foreach($products as $key=>$value){
            $loanedingTime=$value['loanedingTime'];
            if($loanedingTime!=''){
                preg_match('/^[0-9]{4}/', $loanedingTime, $year);
                preg_match('/(?<=\-)[0-9]{2}(?=\-)/', $loanedingTime, $month);
                $yearMonth=$year[0]."-".$month[0];
                
                if(!in_array($yearMonth,$yearMonth_arr)){
                    array_push($yearMonth_arr,$yearMonth);
                }
               
            }
        };
        $yearProduct=$this->man->getProducts($yearMonth_arr[0]);
        foreach($yearProduct as $key=>$value){
            if(!in_array($value['bankProduct'],$productTotal)){
                $productTotal[$value['bankProduct']]= $value['quota'];
            }else{
                $productTotal[$value['bankProduct']]+= intval($value['quota']);
            }
        }

        $nameArr=array();
        $totalArr=array();
        foreach($productTotal as $key=>$value){
            array_push($nameArr,$key);
            array_push($totalArr,$value);
        }
        $data=array(
            'nameArr'=>$nameArr,
            'totalArr'=>$totalArr,
            'year'=>$yearMonth_arr,
        );
        $this->load->view("weixin/product_statistics.html",$data);
    }
    public function getProducts(){
        $productTotal=array();
        $year=$this->input->post("year");
        $yearProduct=$this->man->getProducts($year);
        foreach($yearProduct as $key=>$value){
            if(!in_array($value['bankProduct'],$productTotal)){
                $productTotal[$value['bankProduct']]= $value['quota'];
            }else{
                $productTotal[$value['bankProduct']]+= intval($value['quota']);
            }
        }
        $nameArr=array();
        $totalArr=array();
        foreach($productTotal as $key=>$value){
            array_push($nameArr,$key);
            array_push($totalArr,$value);
        }
        $data=array(
            'nameArr'=>$nameArr,
            'totalArr'=>$totalArr,
        );
        echo json_encode($data);
    }
    public function getOrigins(){
        $this->isLogin();
        $source=array();
        $year_arr=array();
        $year=$this->input->post('year');
        $nowyear=$year;
        $yearOriginArr=$this->man->yearOrigin($nowyear);
        foreach($yearOriginArr as $key=>$value){
            if($value['source']!=''){
                array_push($source,$value['source']);
            }
           
        }
        $sourceArr=array_count_values($source);
        asort($sourceArr);
        $nameArr=array();
        $timeArr=array();
        foreach($sourceArr as $key=>$value){
            array_push($nameArr,$key);
            array_push($timeArr,$value);
        }
        $data=array(
            'nameArr'=>$nameArr,
            'timeArr'=>$timeArr,
            'year'=>$year_arr,
        );
        echo json_encode($data);
    }
    public function search(){
       
        $keyword=trim($this->input->get("keyword"));
        $res=$this->man->search($keyword);
        $uidArray=array();
        $customers=array();
        // 数据比较选择
        foreach($res as $key =>$value){       
            if(!in_array($value['uid'],$uidArray)){
                array_push($uidArray,$value['uid']);
                array_push($customers,$value);
            }else{
                foreach($customers as $k=>$v){
                    if($v['uid']==$value['uid']){
                        // $pn=intval($value['process']);
                        // $po=intval($v['process']);
                        // if($po>$pn){
                        //     $customers[$k]=$value;
                        // }
                        $pn=intval($value['process']);
                        $po=intval($v['process']);
                        if($pn!=2 && $po==2){

                        }else{
                            $customers[$k]=$value;
                        }
                    }
                }
            }
        } 
        // 数据处理。。。
        // p($res);
        foreach($customers as $key=>$value){
            // loan表没有对应记录处理
            if($value['bankProduct']==null){
                $customers[$key]['bankProduct'] ="";
            }
            // 是否关注微信处理
            if($value['ifweixin']==null ||$value['ifweixin']==0){
                $customers[$key]['weixinClass'] ="noFocus";
            }else{
                $customers[$key]['weixinClass'] ="isFocus";
            }
            // 进度显示处理
            if($value['process']==null){
                $customers[$key]['process'] ="待处理";
                $customers[$key]['btnClassName'] ="loan_normal";
                if($value['costType']==0){
                    $customers[$key]['showMoney']=($value['requirement']*$value['percent']*10000/100);
                }else{
                    $customers[$key]['showMoney']=$value['cost'];
                }
            }else if($value['process']==0){
                $customers[$key]['process'] ="已申请";
                $customers[$key]['btnClassName'] ="loan_normal";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }
            }else if(($value['process']==1)){
                $customers[$key]['process'] ="完成进件";
                $customers[$key]['btnClassName'] ="loan_normal";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }
             
            }else if(($value['process']==2)){
                $customers[$key]['process'] ="系统审核中";
                $customers[$key]['btnClassName'] ="loan_checking";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }
              
            }else if(($value['process']==3)){
                $customers[$key]['process'] ="已放款";
                $customers[$key]['btnClassName'] ="loan_success";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }
              
            }else if(($value['process']==4)){
                $customers[$key]['process'] ="终审被拒";
                $customers[$key]['btnClassName'] ="loan_fail";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }  
            }
             // 已收款判断
            if ($value['income'] > 0) {
                $customers[$key]['showMoney'] = $value['income'];
                $customers[$key]['incomeClass'] = "incomeClass";
            } else {
                $customers[$key]['incomeClass'] = "";
            }
            // 取整显示
            $customers[$key]['showMoney']=intval($customers[$key]['showMoney']);
        }
        echo json_encode($customers);  
    }
    public function searchProcess(){
        $process=trim($this->input->get("process"));
        $res=$this->man->searchProcess($process);
        $uidArray=array();
        $customers=array();
        foreach($res as $key =>$value){       
            if(!in_array($value['uid'],$uidArray)){
                array_push($uidArray,$value['uid']);
                array_push($customers,$value);
            }else{
                foreach($customers as $k=>$v){
                    if($v['uid']==$value['uid']){
                        // $pn=intval($value['process']);
                        // $po=intval($v['process']);
                        // if($po>$pn){
                        //     $customers[$k]=$value;
                        // }
                        $pn=intval($value['process']);
                        $po=intval($v['process']);
                        if($pn!=2 && $po==2){

                        }else{
                            $customers[$k]=$value;
                        }
                    }
                }
            }
        }
        foreach($customers as $key=>$value){
            // loan表没有对应记录处理
            if($value['bankProduct']==null){
                $customers[$key]['bankProduct'] ="";
            }
            // 是否关注微信处理
            if($value['ifweixin']==null ||$value['ifweixin']==0){
                $customers[$key]['weixinClass'] ="noFocus";
            }else{
                $customers[$key]['weixinClass'] ="isFocus";
            }
            // 进度显示处理
            if($value['process']==null){
                $customers[$key]['process'] ="待处理";
                $customers[$key]['btnClassName'] ="loan_normal";
                if($value['costType']==0){
                    $customers[$key]['showMoney']=($value['requirement']*$value['percent']*10000/100);
                }else{
                    $customers[$key]['showMoney']=$value['cost'];
                }
            }else if($value['process']==0){
                $customers[$key]['process'] ="已申请";
                $customers[$key]['btnClassName'] ="loan_normal";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }
            }else if(($value['process']==1)){
                $customers[$key]['process'] ="完成进件";
                $customers[$key]['btnClassName'] ="loan_normal";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }
             
            }else if(($value['process']==2)){
                $customers[$key]['process'] ="系统审核中";
                $customers[$key]['btnClassName'] ="loan_checking";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }
              
            }else if(($value['process']==3)){
                $customers[$key]['process'] ="已放款";
                $customers[$key]['btnClassName'] ="loan_success";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }
              
            }else if(($value['process']==4)){
                $customers[$key]['process'] ="终审被拒";
                $customers[$key]['btnClassName'] ="loan_fail";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $customers[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $customers[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }  
            }
            // 已收款判断
            if($value['income']>0){
                $customers[$key]['showMoney']=$value['income'];
                $customers[$key]['incomeClass']="incomeClass";
            }else{
                $customers[$key]['incomeClass']="";
            }
               // 取整显示
               $customers[$key]['showMoney']=intval($customers[$key]['showMoney']);
        }
        
        echo json_encode($customers);
    }
    public function searchNoProcess(){
        $res=$this->man->getCustomersNew();
        $uidArray=array();
        $customers = array();
        $daichuli=array();
          // 数据比较选择
        foreach($res as $key =>$value){       
            if(!in_array($value['uid'],$uidArray)){
                array_push($uidArray,$value['uid']);
                array_push($customers,$value);
            }else{
                foreach($customers as $k=>$v){
                    if($v['uid']==$value['uid']){
                        // $pn=intval($value['process']);
                        // $po=intval($v['process']);
                        // if($po>$pn){
                        //     $customers[$k]=$value;
                        // }
                        $pn=intval($value['process']);
                        $po=intval($v['process']);
                        if($pn!=2 && $po==2){

                        }else{
                            $customers[$k]=$value;
                        }
                    }
                }
            }
        }
        foreach($customers as $key=>$value){
            if($value['process']==null){
                array_push($daichuli,$value);
            }
        }
        foreach($daichuli as $key=>$value){
            // loan表没有对应记录处理
            if($value['bankProduct']==null){
                $daichuli[$key]['bankProduct'] ="";
            }
            // 是否关注微信处理
            if($value['ifweixin']==null ||$value['ifweixin']==0){
                $daichuli[$key]['weixinClass'] ="noFocus";
            }else{
                $daichuli[$key]['weixinClass'] ="isFocus";
            }
            // 进度显示处理
            if($value['process']==null){
                $daichuli[$key]['process'] ="待处理";
                $daichuli[$key]['btnClassName'] ="loan_normal";
                if($value['costType']==0){
                    $daichuli[$key]['showMoney']=($value['requirement']*$value['percent']*10000/100);
                }else{
                    $daichuli[$key]['showMoney']=$value['cost'];
                }
            }
            // 已收款判断
            if ($value['income'] > 0) {
                $daichuli[$key]['showMoney'] = $value['income'];
                $daichuli[$key]['incomeClass'] = "incomeClass";
            } else {
                $daichuli[$key]['incomeClass'] = "";
            }
               // 取整显示
               $daichuli[$key]['showMoney']=intval($customers[$key]['showMoney']);
        }
        echo json_encode($daichuli);
    }
    public function searchIncome(){
        $res=$this->man->getCustomersNew();
        $uidArray=array();
        $customers=array();
        $incomeArr=array();
        // 数据比较选择
        foreach($res as $key =>$value){       
            if(!in_array($value['uid'],$uidArray)){
                array_push($uidArray,$value['uid']);
                array_push($customers,$value);
            }else{
                foreach($customers as $k=>$v){
                    if($v['uid']==$value['uid']){
                        // $pn=intval($value['process']);
                        // $po=intval($v['process']);
                        // if($po>$pn){
                        //     $customers[$k]=$value;
                        // }
                        $pn=intval($value['process']);
                        $po=intval($v['process']);
                        if($pn!=2 && $po==2){

                        }else{
                            $customers[$k]=$value;
                        }
                    }
                }
            }
        }
        foreach($customers as $key=>$value){
            if($value['income']>0){
                array_push($incomeArr,$value);
            }
        }
        foreach($incomeArr as $key=>$value){
            // loan表没有对应记录处理
            if($value['bankProduct']==null){
                $incomeArr[$key]['bankProduct'] ="";
            }
            // 是否关注微信处理
            if($value['ifweixin']==null ||$value['ifweixin']==0){
                $incomeArr[$key]['weixinClass'] ="noFocus";
            }else{
                $incomeArr[$key]['weixinClass'] ="isFocus";
            }
            // 进度显示处理
            if($value['process']==null){
                $incomeArr[$key]['process'] ="待处理";
                $incomeArr[$key]['btnClassName'] ="loan_normal";
                if($value['costType']==0){
                    $incomeArr[$key]['showMoney']=($value['requirement']*$value['percent']*10000/100);
                }else{
                    $incomeArr[$key]['showMoney']=$value['cost'];
                }
            }else if($value['process']==0){
                $incomeArr[$key]['process'] ="已申请";
                $incomeArr[$key]['btnClassName'] ="loan_normal";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $incomeArr[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $incomeArr[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota'];
                    }
                }else{
                    if($value['costType']==0){
                        $incomeArr[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $incomeArr[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }
            }else if(($value['process']==1)){
                $incomeArr[$key]['process'] ="完成进件";
                $incomeArr[$key]['btnClassName'] ="loan_normal";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $incomeArr[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $incomeArr[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $incomeArr[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $incomeArr[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }
             
            }else if(($value['process']==2)){
                $incomeArr[$key]['process'] ="系统审核中";
                $incomeArr[$key]['btnClassName'] ="loan_checking";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $incomeArr[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $incomeArr[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $incomeArr[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $incomeArr[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }
              
            }else if(($value['process']==3)){
                $incomeArr[$key]['process'] ="已放款";
                $incomeArr[$key]['btnClassName'] ="loan_success";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $incomeArr[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $incomeArr[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $incomeArr[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $incomeArr[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }
              
            }else if(($value['process']==4)){
                $incomeArr[$key]['process'] ="终审被拒";
                $incomeArr[$key]['btnClassName'] ="loan_fail";
                if($value['quota']>0){
                    if($value['costType']==0){
                        $incomeArr[$key]['showMoney']=($value['percent']/100)*$value['quota']*10000;
                    }else{
                        $incomeArr[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['quota']*10000;
                    }
                }else{
                    if($value['costType']==0){
                        $incomeArr[$key]['showMoney']=($value['percent']/100)*$value['applyQuota']*10000;
                    }else{
                        $incomeArr[$key]['showMoney']=($value['cost']/($value['requirement']*10000))*$value['applyQuota']*10000;
                    }
                }  
            }
            // 已收款判断
            if($value['income']>0){
                $incomeArr[$key]['showMoney']=$value['income'];
                $incomeArr[$key]['incomeClass']="incomeClass";
            }else{
                $incomeArr[$key]['incomeClass']="";
            }
               // 取整显示
            $incomeArr[$key]['showMoney']=intval($incomeArr[$key]['showMoney']);
        }
        echo json_encode($incomeArr);
        // p($incomeArr);
    }
    public function adcost(){
        $this->isLogin();
        $data['sess_id']= $_SESSION['sid'] = mt_rand(1000, 9999);
        $this->load->view("weixin/adcost.html",$data);
     
    }
    public function addADcost(){
        $this->isLogin();
        if(isset($_POST['sid']) && isset($_SESSION['sid'])){
             if($_POST['sid']==$_SESSION['sid']){
                $year=trim($this->input->post('year'));
                $Jan=trim($this->input->post('Jan'));
                $Feb=trim($this->input->post('Feb'));
                $Mar=trim($this->input->post('Mar'));
                $Apr=trim($this->input->post('Apr'));
                $May=trim($this->input->post('May'));
                $Jun=trim($this->input->post('Jun'));
                $Jul=trim($this->input->post('Jul'));
                $Aug=trim($this->input->post('Aug'));
                $Sept=trim($this->input->post('Sept'));
                $Oct=trim($this->input->post('Oct'));
                $Nov=trim($this->input->post('Nov'));
                $Dec=trim($this->input->post('Dec'));
                $res=$this->c->insert_what("adcost",array(
                    'year'=>$year,
                    'Jan'=>$Jan,
                    'Feb'=>$Feb,
                    'Mar'=>$Mar,
                    'Apr'=>$Apr,
                    'May'=>$May,
                    'Jun'=>$Jun,
                    'Jul'=>$Jul,
                    'Aug'=>$Aug,
                    'Sept'=>$Sept,
                    'Oct'=>$Oct,
                    'Nov'=>$Nov,
                    'Dec'=>$Dec,
                ));
                unset($_SESSION['sid']);
               echo json_encode(
                   array(
                       'state'=>'ok'
                   )
               );
             }else{
                echo json_encode(
                    array(
                        'state'=>'fail'
                    )
                );
            }
             
        }else{
            echo json_encode(
                array(
                    'state'=>'fail'
                )
            );
        }
        
    }
    public function updateADcost(){
        $this->isLogin();
        if(isset($_POST['sid']) && isset($_SESSION['sid'])){
             if($_POST['sid']==$_SESSION['sid']){
                $id=trim($this->input->post('id'));
                $Jan=trim($this->input->post('Jan'));
                $Feb=trim($this->input->post('Feb'));
                $Mar=trim($this->input->post('Mar'));
                $Apr=trim($this->input->post('Apr'));
                $May=trim($this->input->post('May'));
                $Jun=trim($this->input->post('Jun'));
                $Jul=trim($this->input->post('Jul'));
                $Aug=trim($this->input->post('Aug'));
                $Sept=trim($this->input->post('Sept'));
                $Oct=trim($this->input->post('Oct'));
                $Nov=trim($this->input->post('Nov'));
                $Dec=trim($this->input->post('Dec'));
                $res=$this->c->update_what("adcost",array('id'=>$id),array(
                    'Jan'=>$Jan,
                    'Feb'=>$Feb,
                    'Mar'=>$Mar,
                    'Apr'=>$Apr,
                    'May'=>$May,
                    'Jun'=>$Jun,
                    'Jul'=>$Jul,
                    'Aug'=>$Aug,
                    'Sept'=>$Sept,
                    'Oct'=>$Oct,
                    'Nov'=>$Nov,
                    'Dec'=>$Dec,
                ));
                unset($_SESSION['sid']);
                p($res);
             }
        }
    }
    public function updatead(){
        $this->isLogin();
        $id=trim($this->input->get('id'));
        $data['sess_id']= $_SESSION['sid'] = mt_rand(1000, 9999);
        $ad=$this->c->get_row('adcost',array('id'=>$id));
        $data['ad']=$ad;
        $data['id']=$id;
        $this->load->view("weixin/updatead.html",$data);
     
    }
     //删除客户
     public function delad(){
        $this->isLogin();
        $aid=trim($this->input->post('aid'));
        $query=$this->c->delete_what('adcost',array('id'=>$aid));
        if($query){
          echo json_encode(array('state' => 'ok'));

        }else{
          echo json_encode(array('state' => 'fail'));
        }
    }
    public function adlist(){
        $this->isLogin();
        // $data['list']=$this->c->get_what("adcost");
        $data['list']=$this->man->getAllAD();
        $this->load->view('weixin/adlist.html',$data);
    }
}
?>