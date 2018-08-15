<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

//令牌
define('TOKEN', 'daibei');
//公众号id
define("APPID", "wx95cc3f7007b469c9");
//公众号
define("APPSECRET", "e53307a8e2f3543b6ab782309ca3b6e2");

//微信平台上设置的url
class App extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    //判断请求是否来自微信
    private function valid()
    {
        $token = TOKEN;
        //签名
        $signature = $this->input->get('signature');
        //时间戳
        $timestamp = $this->input->get('timestamp');
        //随机数
        $nonce = $this->input->get('nonce');
        //组合数组
        $tmp_arr = array($token, $timestamp, $nonce);
        //字典排序
        sort($tmp_arr);
        //链接成字符串
        $tmp_str = implode($tmp_arr);
        $tmp_str = sha1($tmp_str);
        if ($tmp_str == $signature) {
            return true;
        } else {
            return false;
        }
    }
    //入口
    public function main()
    {
        $isValid = false;
        $token = TOKEN;
        //签名
        $signature = $this->input->get('signature', true);
        //时间戳
        $timestamp = $this->input->get('timestamp', true);
        //随机数
        $nonce = $this->input->get('nonce', true);
        //组合数组
        $tmp_arr = array($token, $timestamp, $nonce);
        //字典排序
        sort($tmp_arr);
        //链接成字符串
        $tmp_str = implode($tmp_arr);
        $tmp_str = sha1($tmp_str);
        if ($tmp_str == $signature) {
            $isValid = true;
        }
        if ($isValid) {
            $echostr = $this->input->get('echostr');
            if (!empty($echostr)) {
                echo $echostr;
                // $this->load->view('valid_view',array('echostr'=>$echostr));
            } else {
                //获取消息
                $post_str = file_get_contents('php://input');
                if (!empty($post_str)) {
                    $post_obj = simplexml_load_string($post_str, 'SimpleXMLElement', LIBXML_NOCDATA);
                    $msg_type = trim($post_obj->MsgType);
                    switch ($msg_type) {
                        case 'event':
                            $this->handleEvent($post_obj);
                            break;
                        case 'text':
                            $this->handleText($post_obj);
                            break;
                        default:
                            $this->handleText($post_obj);
                            break;
                    }
                } else {
                    echo '';
                }
            }
        } else {
            echo '';
        }
    }

    //关键字处理
    private function handleText($post_obj)
    {
        // $keyword = trim($post_obj->Content);
        // if ($keyword == '') {
        //     $content = '';
        //     $this->responseMsg($post_obj, $content);
        // } else {
        //     $content = trim($post_obj->Content);
        //     $this->responseMsg($post_obj, $content);
        // }
    }
    //回复文本消息
    private function responseMsg($post_obj, $content)
    {
        $from_username = $post_obj->FromUserName;
        $to_username = $post_obj->ToUserName;
        $type = "text";
        $data = array(
            'to' => $from_username,
            'from' => $to_username,
            'type' => $type,
            'content' => $content,
        );
        $this->load->view('weixin/response_view', $data);
    }
    //回复图片
    private function responsePic($post_obj)
    {
        $from_username = $post_obj->FromUserName;
        $to_username = $post_obj->ToUserName;
        $type = "image";
        $media_id = "tKIDs5IfqD_gxFzS_pqXp18XRO4GAfiktp6MgeeltMk74oly2LSkp8mYE4lHbApW";
        $data = array(
            'to' => $from_username,
            'from' => $to_username,
            'type' => $type,
            'media_id' => $media_id,
            'content' => $content,
        );
        // $this->load->view('response_pic_view',$data);
    }
    //回复音乐
    private function responseMusic($post_obj)
    {
        $from_username = $post_obj->FromUserName;
        $to_username = $post_obj->ToUserName;
        $title = "轨迹";
        $desc = "歌手：徐薇";
        $music_url = "http://wx.cfuwu.cn/music/1.mp3";
        $hq_music_url = "http://wx.cfuwu.cn/music/1.mp3";
        $media_id = "Z0FmJx2q0T3GCg21B-cmE2UddKeSg1dZ5Xom03iGdbUuqghUlcSPxJBjJsw0n4jz";
        $type = 'music';
        $data = array(
            'to' => $from_username,
            'from' => $to_username,
            'title' => $title,
            'type' => $type,
            'desc' => $desc,
            'media_id' => $media_id,
            'music_url' => $music_url,
            'hq_music_url' => $hq_music_url,
            'media_id' => $media_id,
        );
        $this->load->view('response_music_view', $data);
    }
    //回复图文
    private function responseNews($post_obj)
    {
        $from_username = $post_obj->FromUserName;
        $to_username = $post_obj->ToUserName;
        $type = 'news';
        $items = array();
        $items[] = array(
            'title' => '标题1',
            'desc' => '内容1',
            'picUrl' => 'http://wx.cfuwu.cn/images/0.jpg',
            'url' => 'http://www.baidu.com',
        );
        $items[] = array(
            'title' => '标题2', 'desc' => '内容2', 'picUrl' => 'http://wx.cfuwu.cn/images/1.jpg', 'url' => 'http://www.baidu.com',
        );
        $data = array(
            'to' => $from_username,
            'from' => $to_username,
            'type' => $type,
            'count' => count($items),
            'items' => $items,
        );
        $this->load->view('response_news_view', $data);
    }
    //事件分发
    private function handleEvent($post_obj)
    {
        switch (trim($post_obj->Event)) {
            case "subscribe":
                // $content = "欢迎关注广州助银小秘书微信公众号";
                // $this->responseMsg($post_obj, $content);
                $this->sendBindMsg($post_obj->FromUserName);

                break;
            case "CLICK":
                $EventKey = trim($post_obj->EventKey);
                $openid = $post_obj->FromUserName;
                $ID_number = $ID_number[0]->ID_number;
                switch ($EventKey) {
                    case '1':
                        break;
                    case '2':
                        break;
                    default:

                        break;
                }
                break;
            default:
                $content = "";
                $this->responseMsg($post_obj, $content);
                break;
        }
    }
    private function isBinded($openid)
    {
        $this->load->model('Common_model', 'c');
        $query = $this->c->get_row("weixinfocus", array('openid' => $openid));
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
    private function sendBindMsg($openid)
    {
        $this->load->model('Common_model', 'c');
        $query = $this->c->get_row("weixinfocus", array('openid' => $openid));
        if ($query) {
            $this->sendKFMsg($openid, "欢迎关注广州助银小秘书微信公众号");
            $this->sendKFMsg($openid, "欢迎回来");
        } else {
            $this->sendKFMsg($openid, "欢迎关注广州助银小秘书微信公众号");
            $bind_url = site_url("app/bind");
            $msg = '您还没有验证身份,完成<a href=\"' . $bind_url . '\">身份验证</a> \n即可免费开通以下功能 \n\n 1.查询每月还款信息 \n 2.查询我的贷款信息\n 3.放款/还款/逾期微信提醒 \n\n<a href=\"' . $bind_url . '\">立即验证身份</a>\n 身份验证将在贷呗金融官方验证页面中进行。';
            $this->sendKFMsg($openid, $msg);
        }
    }
    //创建菜单
    public function createMenu()
    {

        $applyUrl = site_url('m/Apply');
        $pageIndexUrl = site_url('app/bind');
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $access_token;
        $data = '{
            "button": [
                {
                    "name": "立即贷款",
                    "sub_button": [
                        {
                            "type": "view",
                            "name": "立即申请",
                            "url":"' . $applyUrl . '",
                            "sub_button": [ ]
                        },
                        {
                            "type": "view",
                            "name": "月供计算器",
                            "url":"' . $pageIndexUrl . '",
                            "sub_button": [ ]
                        }
                    ]
                },
                {
                    "name": "我的贷款",
                    "sub_button": [
                        {
                            "type": "view",
                            "name": "进度查询",
                            "url": "http://www.daibei360.com/app/process",
                            "sub_button": [ ]
                        },
                        {
                            "type": "view",
                            "name": "还款明细",
                            "url": "http://www.daibei360.com/app/backDetials",
                            "sub_button": [ ]
                        }
                    ]
                },
                {
                    "name": "联系我们",
                    "sub_button": [
                        {
                            "type": "view",
                            "name": "联系我们",
                            "url": "http://www.daibei360.com/weixin/aboutus",
                            "sub_button": [ ]
                        }
                    ]
                },

            ]
        }';
        $res = $this->curl_post2($url, $data);
        var_dump($res);
    }

    //上传图片到微信服务器,ci需要使用相对路径
    public function uploadImg()
    {
        $TOKEN = $this->getAccessToken();
        $URL = 'http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=' . $TOKEN . '&type=image';
        var_dump(base_url('static/img/girl.jpg'));
        $data['file'] = new CURLFile(realpath('./static/img/logo.png'));
        $result = $this->curl_post2($URL, $data);
        $data = @json_decode($result, true);
        var_dump($data);

    }
    //上传缩略图到微信服务器,type为thumb
    public function uploadThumb()
    {
        $TOKEN = $this->getAccessToken();
        $URL = 'http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=' . $TOKEN . '&type=thumb';
        $data['file'] = new CURLFile(realpath('./static/img/girl.jpg'));
        $result = $this->curl_post2($URL, $data);
        $data = @json_decode($result, true);
        var_dump($data);

    }
    private function getAccessToken()
    {

        //获取ACCESS_TOKEN
        $TOKEN_URL = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . APPID . "&secret=" . APPSECRET;
        $json = file_get_contents($TOKEN_URL);
        $res = json_decode($json, true);
        P($res);
        $ACCESS_TOKEN = $res['access_token'];
        return $ACCESS_TOKEN;
    }
    private function curl_post($url, $data = null)
    {

        $ch = curl_init($url);
        //设置请求的参数
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1); //设置请求方式为post
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        //执行发送
        $res = curl_exec($ch);
        //返回
        return $res;
    }

    //以snsapi_userinfo为scope发起的网页授权,获取用户信息入口
    public function pageindex()
    {
        $path = "http://www.daibei360.com/weixin/showApplyDetials";
        //scope=snsapi_userinfo用户需要确认授权,scope=snsapi_base静默授权,
        $redirect_uri = urlencode('http://www.daibei360.com/app/getOpenId');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . APPID . "&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=$path#wechat_redirect";
        header("Location:" . $url);
    }
    //以snsapi_userinfo为scope发起的网页授权,获取用户信息入口
    public function bind()
    {
        $path = "http://www.daibei360.com/weixin/bind";
        //scope=snsapi_userinfo用户需要确认授权,scope=snsapi_base静默授权,
        $redirect_uri = urlencode('http://www.daibei360.com/app/getOpenId');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . APPID . "&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=$path#wechat_redirect";
        header("Location:" . $url);
    }
    public function process()
    {
        $path = "http://www.daibei360.com/weixin/process";
        //scope=snsapi_userinfo用户需要确认授权,scope=snsapi_base静默授权,
        $redirect_uri = urlencode('http://www.daibei360.com/app/getOpenId');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . APPID . "&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=$path#wechat_redirect";
        header("Location:" . $url);
    }
    public function backDetials()
    {
        $path = "http://www.daibei360.com/weixin/backDetials";
        //scope=snsapi_userinfo用户需要确认授权,scope=snsapi_base静默授权,
        $redirect_uri = urlencode('http://www.daibei360.com/app/getOpenId');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . APPID . "&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=$path#wechat_redirect";
        header("Location:" . $url);
    }
    //获取用户openid
    public function getOpenId()
    {
        $code = $this->input->get('code');
        //授权结束后的回调网址
        $path = $this->input->get('state');
        //第一步:取全局access_token
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . APPID . "&secret=" . APPSECRET;
        $token = $this->getJson($url);

        //第二步:取得openid
        $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . APPID . "&secret=" . APPSECRET . "&code=$code&grant_type=authorization_code";
        $oauth2 = $this->getJson($oauth2Url);
        $access_token = $token["access_token"];
        $openid = $oauth2['openid'];
//    $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
        //            $userinfo =$this->getJson($get_user_info_url);
        // $this->session->set_userdata(array('openid' => $openid));
        $_SESSION['openid'] = $openid;
        header('Location:' . $path);
    }
    public function getJson($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }

    private function curl_post2($url, $data = null)
    {
        $ch = curl_init();
        //设置请求的参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //执行发送
        $res = curl_exec($ch);
        //返回
        return $res;
    }
    /*
    模板消息开始
     */
    //设置所属行业

    public function setIndustry()
    {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=' . $access_token;
        $data = '{
          "industry_id1":"1",
          "industry_id2":"4"
       }';
        $res = $this->curl_post2($url, $data);
        var_dump($res);
    }
    // 获取设置的行业的信息
    public function getIndustry()
    {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/template/get_industry?access_token=' . $access_token;
        $res = $this->curl_post2($url);
        var_dump($res);

    }
    //获取模板id
    public function getTemplateId()
    {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=' . $access_token;
        $data = '{
           "template_id_short":"TM00015"
        }';
        $res = $this->curl_post2($url, $data);
        var_dump($res);
    }
    //获取模板列表
    public function getTmpList()
    {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=' . $access_token;
        $res = $this->curl_post2($url);
        var_dump($res);
    }
    //删除模板
    public function delTemplateId()
    {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token=' . $access_token;
        $data = '{
            "template_id":"NUv3odbypZORcS9Ucfrpg06OOBF8h-OtKkJtDj386y0"
        }';
        $res = $this->curl_post2($url, $data);
        var_dump($res);
    }
    //发送模板消息
    public function sendMessage()
    {

        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token;
        $data = '{
               "touser":"o8Eziwlio4kZu8QocQ71PF6UgrtI",
               "template_id":"pe5COCsamIwlEt3dJdwcSAQNIEZka8gsKK8bgeXhTnU",
               "url":"http://weixin.qq.com/download",
               "data":{
                       "first": {
                           "value":"恭喜你购买成功！",
                           "color":"#173177"
                       },
                       "orderMoneySum":{
                           "value":"39.8元",
                           "color":"#173177"
                       },
                       "orderProductName": {
                           "value":"巧克力",
                           "color":"#173177"
                       },
                       "Remark":{
                           "value":"欢迎再次购买！",
                           "color":"#173177"
                       }
               }
        } ';
        $res = $this->curl_post2($url, $data);
        var_dump($res);
    }
    //获取素材总数
    public function getTotalMeterial()
    {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=' . $access_token;
        $res = $this->curl_post2($url);
        var_dump($res);
    }
    /*
     *群发消息
     */
    //根据标签群发
    public function sendGroupMessage()
    {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=' . $access_token;
        $data = '{
               "filter":{
                  "is_to_all":false,
                  "tag_id":102
               },
               "text":{
                  "content":"群发文本消息测试"
               },
                "msgtype":"text"
            }';

        $res = $this->curl_post2($url, $data);
        var_dump($res);
    }
    //自定义菜单查询
    public function getMenu()
    {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token=' . $access_token;
        $res = $this->curl_post2($url);
        var_dump($res);
    }
    public function sendRemindMsg($object)
    {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token;
        $data = "{
                       \"touser\":\"$object->openid\",
                       \"template_id\":\"gAHw7ImxlXFAQDJGdiEqiq6cThTLfRLF4Qhq9w9-PTY\",
                       \"url\":\"http://weixin.qq.com/download\",
                       \"data\":{
                               \"Name\":{
                                   \"value\":\"$object->Name\\n\",
                                   \"color\":\"#173177\"
                               },
                               \"CNO\": {
                                   \"value\":\"$object->CNO\\n\",
                                   \"color\":\"#173177\"
                               }
                       }
                } ";
        $res = $this->curl_post2($url, $data);
        var_dump($res);
    }
    public function sendExpiryMsg($object)
    {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token;
        $data = "{
                       \"touser\":\"$object->openid\",
                       \"template_id\":\"GBVa7OOfxjJmVo4X65TEAryArlP4-nm9vEQET1ShwtI\",
                       \"url\":\"http://weixin.qq.com/download\",
                       \"data\":{
                               \"Name\":{
                                   \"value\":\"$object->Name\\n\",
                                   \"color\":\"#173177\"
                               },
                               \"CNO\": {
                                   \"value\":\"$object->CNO\\n\",
                                   \"color\":\"#173177\"
                               }
                       }
                } ";
        $res = $this->curl_post2($url, $data);
        var_dump($res);
    }
    // 添加客服账号
    public function addKF()
    {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/customservice/kfaccount/add?access_token=' . $access_token;
        $data = "{
            \"kf_account\" : \"xiaoqing@daibei360_\",
            \"nickname\" : \"客服小青\",
            \"password\" : \"xiaoqing\",
       } ";
        $res = $this->curl_post2($url, $data);
        var_dump($res);
    }
    // 发送客服消息
    public function sendKFMsg($openid, $msg)
    {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $access_token;
        $data = "{
            \"touser\":\"$openid\",
            \"msgtype\":\"text\",
            \"text\":
            {
                 \"content\":\"$msg\"
            }
        }";
        $res = $this->curl_post2($url, $data);
        var_dump($res);
    }

}
