<?php
defined('BASEPATH') or exit('No direct script access allowed');
//APPSECRET

class Daibei extends MY_Controller
{
	public function index()
	{
	    $name="qing";
		$this->load->view('index.html');
		
	}
	public function sendMessage()
	{
		$phone = trim($this->input->get("phone"));
		$sendcode = $this->generate_code();
		if($this->sendMsg($phone, $sendcode)){
			$data = array(
				'phone'=>$phone,
				'code' => $sendcode
			);
			$_SESSION['check'] = $data;
			echo json_encode($data);
		}else{
			$data = array(
				'code' => -1
			);
			echo json_encode($data);
		}
	}
	public function checkCode()
	{
		if (isset($_SESSION['check'])) {
			$this->load->model('Daibei_model', 'daibei');
			$hasPhone = $this->daibei->getCommon('stranger', array('applyPhoneNum' => $_SESSION['check']['phone']), 0);
			if ($hasPhone) {
				echo json_encode(array(
					'status' => 'repeat',
				));
			}else {
				$time = time();
				$res = $this->daibei->insertCommon('stranger', array('applyPhoneNum' => $_SESSION['check']['phone'], 'applyTime' => $time));
				if ($res) {
					echo json_encode(array(
						'status' => 'ok',
					));
				} else {
					echo json_encode(array(
						'status' => 'fail',
					));
				}
			}
		}

	}
	// public function sendMessage()
	// {
	// 	$this->load->model("Common_model","c");
	// 	$phone = trim($this->input->get("phone"));
	// 	$phoneres=$this->c->get_what("sms",array('phone'=>$phone));
	// 	$ipres=$this->c->get_what("sms",array('phone'=>$phone));
	// 	p($phoneres);
	// 	p($ipres);
	// 	// if (!isset($_SESSION['last_access']) || (time() - $_SESSION['last_access']) > 60){
			
	// 	// }else{

	// 	// }
	// 	if(!$ipres && !$phoneres){
			
	// 		$sendcode= $this->generate_code();
	// 		$ip=real_ip();
	// 		p($phone);
	// 		p($ip);
	// 		p($sendcode);
	// 		if($this->sendMsg($phone, $sendcode)){
	// 			echo "发送成功";
	// 			$this->c->insert_what("sms",array('phone'=>$phone,'ip'=>$ip,'expireTime'=>time()));
	// 			$_SESSION['last_access'] = time();
	// 		}else{
	// 			echo "请稍后再试";
	// 		};
	// 	}
		
	// }
	
	private function generate_code($length = 4)
	{
		return rand(pow(10, ($length - 1)), pow(10, $length) - 1);
	}
	// 调用发送短信接口
	private function sendMsg($phone, $codenum)
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
			return true;
        }else{
			return false;
		}
	}
	public function testUpload(){
		$data=array(
			'status'=>"1"
		);
		echo json_encode($data);
	}
}
?>