<?php
defined('BASEPATH') or exit('No direct script access allowed');
class TestMsg extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $this->config->load('sms'); //引用config文件夹下sms.php中的值
        $sms = new Sms(
            $this->config->item('accessKeyID'),
            $this->config->item('accessKeySecret')
        );
        $code = $this->config->item('code'); //n为第几个模板CODE,参照sms.php中的配置
        $code =$code[0];
        $response = $sms->sendSms(
            '18316492757', // 短信接收者
            $this->config->item('sign'), // 短信签名
            $code, // 短信模板编号
            array('code' => '1234') //短信模板中的变量,name以及code为变量名
        );
        if ($response->Code == 'OK') {
            /**发送成功后的操作**/
            echo "发送成功";
        }
    }
}
