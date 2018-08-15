<?php
defined('BASEPATH') or exit('No direct script access allowed');
define("APPKEY", "23720021");
//APPSECRET
define("APPSECRET", "516b14e12c826208e0638425f30c28f3");
define("APPCODE", "e39fb3fc4a7f401fb1e7ac45a12ed1b4");
class Weixin extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Man_model', 'man');
        $this->load->model('Common_model', 'c');
    }
    public function index()
    {
        $this->load->view("weixin/newBind.html");
    }
    // 客户贷款详情
    public function showApplyDetials()
    {
        if (isset($_SESSION['openid'])) {
            $openid = $_SESSION['openid'];
            $data = array(
                'openid' => $openid,
            );
            $res = $this->c->get_row("weixinfocus",array(
                'openid'=>$openid
            ));
            if ($res) {
                $uid=$res['uid'];
                $res=$this->c->get_what('loanedrecord',array('uid'=>$uid));
                $data=array(
                    'res'=>$res
                );
                $this->load->view('weixin/loanList.html', $data);  
            } else {
                $this->load->view('weixin/newBind.html', $data);
            }
        }
    }
    // 页面绑定
    public function bind()
    {
        if (isset($_SESSION['openid'])) {
            $openid = $_SESSION['openid'];
            $data = array(
                'openid' => $openid,
            );
            $this->load->view('weixin/newBind.html', $data);
        }
    }
    public function testbind()
    {
        $_SESSION['openid']="oeL4l1d4yxIPZMyILp1vUmniO2eU";
        if (isset($_SESSION['openid'])) {
            $openid = $_SESSION['openid'];
            $data = array(
                'openid' => $openid,
            );
            $this->load->view('weixin/newBind.html', $data);
        }
    }
    // 还款明细
    public function backDetials(){
        $openid = $_SESSION['openid'];
        $data = array(
            'openid' => $openid,
        );
        $res = $this->c->get_row("weixinfocus",array(
            'openid'=>$openid
        ));
        if ($res) {
            $uid=$res['uid'];
            $res=$this->c->get_what('loanedrecord',array('uid'=>$uid));
            if($res){
                $data=array(
                    'res'=>$res
                );
                $this->load->view('weixin/loanList.html', $data); 
            }else{
                $this->load->view('weixin/noback2.html'); 
            }
             
        } else {
            $this->load->view('weixin/newBind.html', $data);
        }
    }

    public function testbackDetials(){
       
        $openid = "oeL4l1d4yxIPZMyILp1vUmniO2eU";
        $data = array(
            'openid' => $openid,
        );
        $res = $this->c->get_row("weixinfocus",array(
            'openid'=>$openid
        ));
        if ($res) {
          
            $uid=$res['uid'];
            $res=$this->c->get_what('loanedrecord',array('uid'=>$uid));
            // p($res);
            if($res){
                $data=array(
                    'res'=>$res
                );
                $this->load->view('weixin/loanList.html', $data); 
            }else{
                $this->load->view('weixin/noback2.html'); 
            }
             
        } else {
            $this->load->view('weixin/newBind.html', $data);
        }
    }
    // 微信绑定
    public function handleBind()
    {
        $name = trim($this->input->post("name"));
        $identity = trim($this->input->post("identity"));
        $phone = trim($this->input->post("phone"));
        $openid = trim($this->input->post("openid"));
        $code = trim($this->input->post('code'));
        $time = time();
        if (isset($_SESSION['check'])) {
            if ($code == $_SESSION['check']['code'] && $phone == $_SESSION['check']['phone']) {
                $query=$this->c->get_row("customers",array('name'=>$name,'identity'=>$identity,'phone'=>$phone));
                if($query){
                    $uid=$query['uid'];
                    $this->c->insert_what("weixinfocus",array(
                        'username'=>$name,
                        'identity'=>$identity,
                        'phone'=>$phone,
                        'openid'=>$openid,
                        'time'=>$time,
                        'uid'=>$uid
                    ));
                    $this->c->update_what("customers",array('uid'=>$uid),array('ifweixin'=>1));
                    $data = array(
                        'icon' => 'weui-icon-success',
                        'result' => '恭喜您，绑定成功!',
                    );
                    unset($_SESSION['check']);
                    $this->load->view('weixin/msg3.html', $data);
                }else{
                    $data = array(
                        'icon' => 'weui-icon-warn',
                        'result' => '绑定失败',
                    );
                    $this->load->view('weixin/msg2.html', $data);
                }
               
            } else {
                $data = array(
                    'icon' => 'weui-icon-warn',
                    'result' => '绑定失败',
                );
                $this->load->view('weixin/msg2.html', $data);
            }
        } else {
            $data = array(
                'icon' => 'weui-icon-warn',
                'result' => '绑定失败',
            );
            $this->load->view('weixin/msg2.html', $data);
        }
    }
    // 发送短信
    public function sendMessage()
    {
        $phone = trim($this->input->get("phone"));
        $code = $this->generate_code();
        echo json_encode(
            array(
                'code'=>$code
            )
        );
        $this->sendMsg($phone, $code);
       
        $_SESSION['check'] = array(
            'phone' => $phone,
            'code' => $code,
        );
       
        // $_SESSION['check'] = array(
        //     'phone' => $phone,
        //     'code' => 1234
        // );
    }
    // 关于我们
    public function aboutus()
    {
        $this->load->view('weixin/aboutus.html');
    }
    // 进度信息
    public function process()
    {
        
        if (isset($_SESSION['openid'])) {
            $openid = $_SESSION['openid'];
            $data = array(
                'openid' => $openid,
            );
            $res = $this->c->get_row("weixinfocus",array(
                'openid'=>$openid
            ));
            if ($res) {
                $uid=$res['uid'];
                $this->load->model("weixin_model","wx");
                $res=$this->wx->getCustomersInfo($uid);
                if($res[0]['id']!=null){
                    $data=array(
                        'res'=>$res[0]
                    );
                    $this->load->view('weixin/process.html', $data);  
                }else{
                    $this->load->view('weixin/noprocess.html');  
                }                
            } else {
                $this->load->view('weixin/newBind.html', $data);
            }
        }
    }
    public function testprocess()
    {
        $_SESSION['openid']="oeL4l1U7cOIgqbQZneolBdMCxPls";
        if (isset($_SESSION['openid'])) {
            $openid = $_SESSION['openid'];
            $data = array(
                'openid' => $openid,
            );
            $res = $this->c->get_row("weixinfocus",array(
                'openid'=>$openid
            ));
            if ($res) {
                
                $uid=$res['uid'];
                $this->load->model("weixin_model","wx");
                $res=$this->wx->getCustomersInfo($uid);

                if($res[0]['id']!=null){
                    $data=array(
                        'res'=>$res[0]
                    );
                    $this->load->view('weixin/process.html', $data);  
                }else{
                    $this->load->view('weixin/noprocess.html');  
                }   
                
                
            } else {
                $this->load->view('weixin/newBind.html', $data);
            }
        }
    }
    // 生成随机数，$length为长度
    private function generate_code($length = 4)
    {
        return rand(pow(10, ($length - 1)), pow(10, $length) - 1);
    }
    // 调用发送短信接口
    public function sendMsg($phone, $codenum)
    {
        $this->config->load('sms'); //引用config文件夹下sms.php中的值
        $sms = new Sms(
            $this->config->item('accessKeyID'),
            $this->config->item('accessKeySecret')
        );
        $code = $this->config->item('code'); //n为第几个模板CODE,参照sms.php中的配置
        $code =$code[0];
        $response = $sms->sendSms(
            $phone, // 短信接收者
            $this->config->item('sign'), // 短信签名
            $code, // 短信模板编号
            array('code' => $codenum) //短信模板中的变量,name以及code为变量名
        );
        if ($response->Code == 'OK') {
            /**发送成功后的操作**/
            // echo "发送成功";
        }
    }
    // 贷款列表
    public function loanList()
    {
        $this->load->view('weixin/loanList.html');
    }
	//保留两位小数
	public function toFix($num){
		  return number_format($num, 2, '.', '');
    }
    // 还款明细
    public function loanDetials()
    {
		$id = $this->input->get('id');
		$loanInfo = $this->c->get_row('loanedrecord', array('id' => $id));
		$loanMoney=$loanInfo['loanMoney']*10000;
		$backType = $loanInfo['backType'];
		$backDate = $loanInfo['backDate'];
		$term=$loanInfo['term'];
		$rate=$loanInfo['rate'];
		$loanedingDate = $loanInfo['loanedingDate'];
		$perBack = $loanInfo['perBack'];
		$remain = array();
		$Interest = $loanMoney*($rate/ 100); 
		preg_match('/^[0-9]{4}/', $loanedingDate, $year);
            preg_match('/(?<=\-)[0-9]{2}(?=\-)/', $loanedingDate, $month);
            preg_match('/(?<=\-)[0-9]{2}$/', $loanedingDate, $day);
            $backDateArray = array();
            $nowyear = (intval($year[0]));
            $nowmonth = (intval($month[0]));
            $day = str_pad($backDate, 2, "0", STR_PAD_LEFT);
            $nowtermback = $loanMoney;
            if ($perBack == 0 || $perBack == '') {
                $perBack = $loanMoney * ($rate / 100) + $loanMoney / $term;
                $perBack = $this->toFix($perBack);
            }
            for ($i = 1; $i <= $term; $i++) {
                $nowmonth = $nowmonth + 1;
                if (($nowmonth) > 12) {
                    $nowmonth = $nowmonth - 12;
                    $m = $nowmonth;
                    $nowyear = $nowyear + 1;
                    $sm = str_pad($m, 2, "0", STR_PAD_LEFT);
                    $date = $nowyear . $sm . $day;
                    array_push($backDateArray, $date);
                } else {
                    $m = $nowmonth;
                    $sm = str_pad($m, 2, "0", STR_PAD_LEFT);
                    $date = $nowyear . $sm . $day;
                    array_push($backDateArray, $date);
                }

            }
        if ($backType == 0) {
            
            for ($i = 1; $i <= $term; $i++) {
                $nowtermback = $nowtermback - ($loanMoney / $term);
                $nowtermback = $this->toFix($nowtermback);
                array_push($remain, $nowtermback);
            }
            $remain[count($remain) - 1] = 0;
            $data = array(
                'loanInfo' => $loanInfo,
                'backDateArray' => $backDateArray,
				'remain' => $remain,
				'perBack'=>$perBack,
				'Interest'=>$Interest
            );
		}else if($backType == 1){
			$InterestArr=array();
			$preBackArr=array();
			$dkm     = $term; //贷款月数，20年就是240个月
			$dkTotal = $loanMoney; //贷款总额
			$dknl    = $rate/100;  //贷款年利率
			$emTotal = $dkTotal * $dknl / 12 * pow(1 + $dknl / 12, $dkm) / (pow(1 + $dknl / 12, $dkm) - 1); //每月还款金额
			$lxTotal = 0; //总利息
			for ($i = 0; $i < $dkm; $i++) {
				$lx      = $dkTotal * $dknl / 12;   //每月还款利息
				$em      = $emTotal - $lx;  //每月还款本金
				$lm      = $dkTotal - $em;  //剩余本金
				$dkTotal = $dkTotal - $em;
				$lxTotal = $lxTotal + $lx;
				array_push($InterestArr,$this->toFix($lx));
				array_push($preBackArr,$this->toFix($em+$lx));
				array_push($remain,$this->toFix($lm));
			}
			$remain[count($remain) - 1] = 0;	
			$data = array(
                'loanInfo' => $loanInfo,
                'backDateArray' => $backDateArray,
				'remain' => $remain,
				'preBackArr'=>$preBackArr,
				'InterestArr'=>$InterestArr
            );		
		}else if($backType == 2){
			$data = array(
                'loanInfo' => $loanInfo,
                'backDateArray' => $backDateArray,
				'loanMoney' => $loanMoney,
				'perBack'=>$Interest,
				'Interest'=>$Interest
            );
		}
        $this->load->view('weixin/loanDetials.html', $data);
    }
    // 客户贷款列表
    public function loan()
    {
        $this->load->view('weixin/loan.html');
	}
	public function debx()
	{
		$dkm     = 24; //贷款月数，20年就是240个月
		$dkTotal = 10000; //贷款总额
		$dknl    = 0.085;  //贷款年利率
		$emTotal = $dkTotal * $dknl / 12 * pow(1 + $dknl / 12, $dkm) / (pow(1 + $dknl / 12, $dkm) - 1); //每月还款金额
		$lxTotal = 0; //总利息
		for ($i = 0; $i < $dkm; $i++) {
		$lx      = $dkTotal * $dknl / 12;   //每月还款利息
		$em      = $emTotal - $lx;  //每月还款本金
		$lm      = $dkTotal - $em;  //剩余本金
		echo "第" . ($i + 1) . "期", " 本金:", $this->toFix($em) , " 利息:" .$this->toFix($lx) , " 总额:".$this->toFix($emTotal) , "剩余本金".$this->toFix($lm). "<br />";
		$dkTotal = $dkTotal - $em;
		$lxTotal = $lxTotal + $lx;
		}
		echo "总利息:" . $lxTotal;
    }
    // 贷款计算器
    public function counter(){
        $this->load->view("weixin/counter.html");
    }
}
