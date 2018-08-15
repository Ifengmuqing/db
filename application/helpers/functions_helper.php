<?php
/**
 * 格式化var_dump
 *@param  $res 传入的参数
 */
function p($res){
    echo "<pre>";
    var_dump($res);
    echo "</pre>";
}
/**
 * CSRF防范
 */
function csrf_hidden(){
    $ci = &get_instance();
    $name = $ci->security->get_csrf_token_name();
    $val = $ci->security->get_csrf_hash();
    echo "<input type=\"hidden\" name=\"$name\" value=\"$val\" />";
   }
/**
 * 获得用户的真实IP地址（服务器）
 *
 * @return  string
 */
function real_ip()
{
    static $realip = NULL;

    if ($realip !== NULL) {
        return $realip;
    }

    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach ($arr as $ip) {
                $ip = trim($ip);

                if ($ip != 'unknown') {
                    $realip = $ip;

                    break;
                }
            }
        } elseif (isset($_SERVER['HTTP_CLIENT_IP']))//
        {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $realip = $_SERVER['REMOTE_ADDR'];
            } else {
                $realip = '0.0.0.0';
            }
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $realip = getenv('HTTP_CLIENT_IP');
        } else {
            $realip = getenv('REMOTE_ADDR');
        }
    }

    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

    return $realip;
}

/**
 * 带有单位的文件大小
 *
 * @param  int $size2   文件大小（字节）
 * @return  
 */
function fsize($size2)
{
    if ($size2 < 1024) {
        return $size2 . '字节';//字节
    } elseif ($size2 >= 1024 && $size2 < pow(1024, 2)) {
        return sprintf('%.2f', $size2 / 1024) . 'KB';  //6.970703125   (6.97KB)//KB
    } elseif ($size2 >= pow(1024, 2) && $size2 < pow(1024, 3)) {
        return sprintf('%.2f', $size2 / pow(1024, 2)) . 'MB';//MB
    } elseif ($size2 >= pow(1024, 3) && $size2 < pow(1024, 4)) {
        return sprintf('%.2f', $size2 / pow(1024, 3)) . 'GB';//GB
    }
}

/**
 * 文件上传
 *
 * @param  string $path   保存的目录
 * @param  string $size   文件限制大小
 * @param  string $oldimg   旧图片地址
 * @return  
 */
function upload_file($path = './uploads/', $size = 2097152, $oldimg = '')
{

//考虑photo(file的名字)
//文件大小判断
//考虑文件保存的目录
//记得重新命名文件的名字（年月日时分秒+5位随机数+后缀名）

	//-- 得到数组下标
    $key_arr = array_keys($_FILES);
  // print_r($_FILES);die();
    $key = $key_arr[0];  //photo

	// --文件上传
    if ($_FILES[$key]['error'] != 4) {	// 如果有选择文件

		 //考虑内容：网络是否有问题，是否有选择文件，文件大小（提示）、类型限制、文件名（不能重复）
        if ($_FILES[$key]['error'] == 3) { //错误：网络中断（线上会出现）
            echo '<script>alert("网络出错！");history.go(-1);</script>';
            exit;  //终止程序（die;）
        }

        if ($_FILES[$key]['size'] > $size) { //大小超过隐藏域MAX_FILE_SIZE限制的大小
            echo '<script>alert("文件大小不能超过' . fsize($size) . '！");history.go(-1);</script>';
            exit;  //终止程序（die;）
        }

		//定义新的名字
        $fname = $_FILES[$key]['name'];  //php03.rar  要改名
        $extend = pathinfo($fname, PATHINFO_EXTENSION);  //jpg   //pathinfo_extension

		//图片：gif/png/jpg/jpeg
    // return in_array($extend,array('gif','png','jpg','jpeg'));die();
        if (!in_array($extend, array('gif', 'png', 'jpg', 'jpeg'))) {
            echo '<script>alert("请上传图片类型的文件！");history.go(-1);</script>';
            exit;  //终止程序（die;）
        }

		//文件名$fname，要改名：时间格式+后缀名   mt_rand()  生成随机数（保证图片名不一样）
        $new_name = date('YmdHis') . mt_rand(10000, 99999) . '.' . $extend;

        if (!file_exists($path)) {  //如果保存的目录不存在，要帮他创建
            mkdir($path, 0777);
        }

		//研究：模拟表单提交
        if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
			// 成功上传头像
			//copy($_FILES[$key]['tmp_name'],'./uploads/'.$fname);
            move_uploaded_file($_FILES[$key]['tmp_name'], $path . $new_name);

			// 删除旧图片
            if ($oldimg != '') {
                @unlink($oldimg);   //$oldimg 旧图片的路径
            }

        } else {
            echo '<script>alert("非法上传！");history.go(-1);</script>';
            exit;  //终止程序（die;）
        }
            //  $path =>  ./uploads/   ../uploads/
            // $path = str_replace('../', '', $path);
            // $path = str_replace('./', '', $path);

        $path = str_replace(array('../', './'), '', $path);
        return $path . $new_name;//图片路径
    } else {
            // $oldimg = str_replace('../', '', $oldimg);
            // $oldimg = str_replace('./', '', $oldimg);

        $oldimg = str_replace(array('../', './'), '', $oldimg);
        return $oldimg;  //返回原图的地址
    }

}

/**
 * 跳转函数
 *
 * @param  string $msg   提示信息
 * @param  string $url     跳转地址
 * @return  
 */
// function jump($msg,$url=''){
//     if($url != ''){
//         echo '<script>alert("'.$msg.'");location.href="'.$url.'";</script>';
//     }else{
//         echo '<script>alert("'.$msg.'");history.go(-1);</script>'; 
//     } 
//     exit;  //终止后面的程序
// }
function jump($msg, $url = '')
{
    header("location:jump.php?msg=$msg&url=$url");
}
/**
 * 添加记录函数
 * @param string $table   数据表名（如：'admin'）
 * @param array $data   一维关联数组
 （格式：array('字段名'=>值,'字段名'=>值)）
 * @param author lin teacher
 * @return boolean
 */
function insert($table, $data)
{
    $key_arr = array_keys($data);  // 提取数组的下标
    $val_arr = array_values($data); // 提取数组的值
    $key_str = implode(',', $key_arr);
    $val_str = "'" . implode("','", $val_arr) . "'";
    $query = mysql_query("insert into $table ($key_str) values ($val_str)");
    if ($query) {
        if (mysql_insert_id() > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return '请检查SQL语句，错误信息为：' . mysql_error();  //mysql错误信息
    }
}
/**
 * 删除记录函数
 * @param string $table   数据表名（如：'admin'）
 * @param string $where   条件（格式：'主键id=2'）
 * @param author lin teacher
 * @return boolean
 */
function del($table, $where)
{
    $query = mysql_query("delete from $table where $where");
    if ($query) {
        if (mysql_affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return '请检查SQL语句，错误信息为：' . mysql_error();  //mysql错误信息
    }
}
/**
 * 修改数据函数
 *
 * @param  array $data   修改数据（一维关联数组）
 * @param  string $table     数据表名
 * @param  string $where     条件（如：id=1）
 * @return  
 */
function update($table, $data, $where)
{
    $str = '';
    foreach ($data as $key => $value) {
        $str .= $key . "='" . $value . "',";
    }
    $str = rtrim($str, ',');
    $query = mysql_query("UPDATE $table SET $str WHERE $where");
    if ($query) {
        if (mysql_affected_rows() > 0) {
            return true;
        } else {
            return false; //操作的记录不成功，不代表sql语句错误
        }
    } else {
        return '请检查SQL语句，错误信息为：' . mysql_error();  //mysql错误信息
    }
}
/*
 *  获取单条记录
 *
 * @param  string $sql   SQL查询语句
 * @return  
 */
function get_one($sql)
{
    $query = mysql_query($sql);
    if ($query) {
        $info = @mysql_fetch_assoc($query);
    } else {
        return "SQL错误信息：" . mysql_error();
    }
    return $info;
}

/**
 * 获取多条记录函数
 * @param string $sql   sql语句
 * @param author lin teacher
 * @return string/array
 */
function get_all($sql)
{
    $query = mysql_query($sql);
    if ($query) {
        $info = array();
        while ($data = mysql_fetch_assoc($query)) {
            $info[] = $data;
        }
    } else {
        return '请检查SQL语句，错误信息为：' . mysql_error();  //mysql错误信息
    }

    return $info;
}


/*
 *  统计记录总数
 *
 * @param  string $sql   SQL查询语句
 * @return  
 */
function get_total($sql)
{
    $query = mysql_query($sql);
    $total = @mysql_num_rows($query);
    return $total;
}

/*
 *  删除单条记录
 *
 * @param  string $sql   SQL查询语句
 * @return  
 */
function delete($sql)
{
    mysql_query($sql);
    return mysql_affected_rows();
}


function get_url()
{
    $str = $_SERVER['PHP_SELF'] . '?';
    foreach ($_GET as $key => $value) {
        if ($key == 'pn') {
            continue;
        }
        $str .= $key . '=' . $value . '&';
    }
    return $str;
}
// echo get_url(); ///admin/pro_img.php?pid=2&a=123&
// exit;
/*
 *  分页
 *
 * @param  int $pn   当前页码
 * @param  int $total   总记录数
 * @param  int $limit   每页限制显示多少条
 * @param  int $size    限制显示多少个页码(必须是奇数，从1开始)
 * @param  string $clsname    样式（div的class名）
 * @return  
 */
function page($pn, $total, $limit, $size = 5, $clsname = 'meneame')
{
    //总页数
    $pn_num = ceil($total / $limit);

    //分页页码（字符串形式）
    $str = '<div class="' . $clsname . '"><a href="' . get_url() . 'pn=1">首页</a>';
    if ($pn > 1) {
        $str .= '<a href="' . get_url() . 'pn=' . ($pn - 1) . '">上一页</a>';
    } else {
        $str .= '<span class="disabled">上一页</span>';
    }
    //页码....
    //ceil(2.5) => 3    floor(2.5) => 2
    // $pn 当前页码    $pn_num 总页数   $size 规定的奇数页码(假设3)
    if ($pn < ceil($size / 2)) {    // (第1块)  
        $begin = 1;
        $end = $size > $pn_num ? $pn_num : $size;   //谁小就取谁
    } elseif ($pn > $pn_num - floor($size / 2)) {  //(第3块)
        $begin = $pn_num - $size + 1 <= 0 ? 1 : $pn_num - $size + 1;
        $end = $pn_num;
    } else {    //(第2块)
        $begin = $pn - floor($size / 2);
        $end = $pn + floor($size / 2);
    }

    //页码：   
    for ($i = $begin; $i <= $end; $i++) {
        if ($i == $pn) {
            $str .= '<span class="current">' . $i . '</span>';
        } else {
            $str .= '<a href="' . get_url() . 'pn=' . $i . '">' . $i . '</a>&nbsp;';
        }
    }

    if ($pn < $pn_num) {
        $str .= '<a href="' . get_url() . 'pn=' . ($pn + 1) . '">下一页</a>';
    } else {
        $str .= '<span class="disabled">下一页</span>';
    }
    $str .= '<a href="' . get_url() . 'pn=' . $pn_num . '">最后一页</a></div>';

    return $str;
}


/*判断后台是否有登录*/
function check_alogin()
{
    if (!isset($_SESSION['aid'])) {
        echo '<script>alert("访问受限！");location.href="login.php";</script>';
        exit;
    }
}

/**
 * 字符截取（对中文、英文都可以进行截取）
 * @param string $string   字符串
 * @param int $start       字符串截取开始位置
 * @param int $length      字符串截取长度(多少个中文、英文)
 * @param string $charset  字符串编码
 * @param string $dot      截取操作发生时，在被截取字符串最后边增加的字符串
 * @param author lin teacher
 * @return string
 */
function str_cut(&$string, $start, $length, $charset = "utf-8", $dot = '...')
{
    if (function_exists('mb_substr')) {  // mb_  扩展（php.ini 开启扩展）  mb_substr 截取字符
        if (mb_strlen($string, $charset) > $length) {//mb_strlen 按字符获取长度
            return mb_substr($string, $start, $length, $charset) . $dot;
        }
        return mb_substr($string, $start, $length, $charset);//按字符截取字符串

    } else if (function_exists('iconv_substr')) {
        if (iconv_strlen($string, $charset) > $length) {
            return iconv_substr($string, $start, $length, $charset) . $dot;
        }
        return iconv_substr($string, $start, $length, $charset);
    }

    $charset = strtolower($charset);  //转小写
    switch ($charset) {
        case "utf-8":
            preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $ar);
            if (func_num_args() >= 3) { //func_num_args()  返回函数的参数个数
                if (count($ar[0]) > $length) {
                    return join("", array_slice($ar[0], $start, $length)) . $dot;
                }
                return join("", array_slice($ar[0], $start, $length));
            } else {
                return join("", array_slice($ar[0], $start));//join()=>implode()
            }
            break;
        default:
            $start = $start * 2;
            $length = $length * 2;
            $strlen = strlen($string);
            for ($i = 0; $i < $strlen; $i++) {
                if ($i >= $start && $i < ($start + $length)) {
                    if (ord(substr($string, $i, 1)) > 129) $tmpstr .= substr($string, $i, 2);
                    else $tmpstr .= substr($string, $i, 1);
                }
                if (ord(substr($string, $i, 1)) > 129) $i++; //返回字符的 ASCII 码值 
            }
            if (strlen($tmpstr) < $strlen) $tmpstr .= $dot;

            return $tmpstr;
    }
}
//逐月递加
function add_month($a)
{
    if ( ($a > 201601 && $a < 201612) || ($a >= 201701 && $a < 201712)) {
        return $a + 1;
    } else if ($a = 201612) {
        return $a + 89;
    }
}
//文件上传
///文件上传////文件上传/////文件上传/////文件上传/文件上传文件上传文件上传///////
///////
//构建上传文件信息  
function getFiles()
{
    $i = 0;
    foreach ($_FILES as $file) {  
        //因为这时$_FILES是个三维数组，并且上传单文件或多文件时，数组的第一维的类型不同，这样就可以拿来判断上传的是单文件还是多文件  
        if (is_string($file['name'])) {  
        //如果是单文件  
            $files[$i] = $file;
            $i++;
        } elseif (is_array($file['name'])) {  
        //如果是多文件  
            foreach ($file['name'] as $key => $val) {
                $files[$i]['name'] = $file['name'][$key];
                $files[$i]['type'] = $file['type'][$key];
                $files[$i]['tmp_name'] = $file['tmp_name'][$key];
                $files[$i]['error'] = $file['error'][$key];
                $files[$i]['size'] = $file['size'][$key];
                $i++;
            }
        }
    }
    return $files;
}   
//针对于单文件、多个单文件、多文件的上传  
  
//默认允许上传的文件只为图片类型，并且只有这些图片类型:$allowExt=array('jpeg','jpg','png','gif');并且检查上传的文件是否为真实的图片$flag=true  
//默认上传保存的文件夹为本地的'uploads'文件夹，允许上传文件的大小最大为2M  
function uploadFile($fileInfo, $path = './uploads', $flag = true, $allowExt = array('jpeg', 'jpg', 'png', 'gif'), $maxSize = 2097152)
{  
  
    //判断错误号  
    if ($fileInfo['error'] === UPLOAD_ERR_OK) {  
        //检测上传文件的大小  
        if ($fileInfo['size'] > $maxSize) {
            $res['mes'] = $fileInfo['name'] . '上传文件过大';
        }
        $ext = getExt($fileInfo['name']);  
        //检测上传文件的文件类型  
        if (!in_array($ext, $allowExt)) {
            $res['mes'] = $fileInfo['name'] . '非法文件类型';
        }  
        //检测是否是真实的图片类型  
        if ($flag) {
            if (!getimagesize($fileInfo['tmp_name'])) {
                $res['mes'] = $fileInfo['name'] . '不是真实图片类型';
            }
        }  
        //检测文件是否是通过HTTP POST上传上来的  
        if (!is_uploaded_file($fileInfo['tmp_name'])) {
            $res['mes'] = $fileInfo['name'] . '文件不是通过HTTP POST方式上传上来的';
        }
        if (@$res) return $res; //如果要不显示错误信息的话，用if( @$res ) return $res;  
          
        //$path='./uploads';  
        //如果没有这个文件夹，那么就创建一  
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            chmod($path, 0777);
        }  
          
        //新文件名唯一  
        $uniName = getUniName();
        $destination = $path . '/' . $uniName . '.' . $ext;  
          
        //@符号是为了不让客户看到错误信，也可以删除  
        if (!@move_uploaded_file($fileInfo['tmp_name'], $destination)) {
            $res['mes'] = $fileInfo['name'] . '文件移动失败';
        }
        $res['mes'] = $fileInfo['name'] . '上传成功';
        $res['dest'] = $destination;
        $res['sumname'] = $uniName . '.' . $ext;
        return $res;
    } else {  
        //匹配错误信息  
        //注意！错误信息没有5  
        switch ($fileInfo['error']) {
            case 1:
                $res['mes'] = '上传文件超过了PHP配置文件中upload_max_filesize选项的值';
                break;
            case 2:
                $res['mes'] = '超过了HTML表单MAX_FILE_SIZE限制的大小';
                break;
            case 3:
                $res['mes'] = '文件部分被上传';
                break;
            case 4:
                $res['mes'] = '没有选择上传文件';
                break;
            case 6:
                $res['mes'] = '没有找到临时目录';
                break;
            case 7:
                $res['mes'] = '文件写入失败';
                break;
            case 8:
                $res['mes'] = '上传的文件被PHP扩展程序中断';
                break;

        }
        return $res;
    }
} 
//得到文件扩展名  
function getExt($filename)
{
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}  
  
//产生唯一字符串  
function getUniName()
{  
    // return md5(uniqid(microtime(true),true));
    $numbers = range(100000, 999999);
    shuffle($numbers);
    $one = md5($numbers[0]);
    $result = substr($one, 2, 2);
    $aaa = date('YmdHi', microtime(true)) . $result;
    return $aaa;

}
//删除数组中的一个元素
function array_remove_value(&$arr, $var)
{
    foreach ($arr as $key => $value) {
        if (is_array($value)) {
            array_remove_value($arr[$key], $var);
        } else {
            $value = trim($value);
            if ($value == $var) {
                unset($arr[$key]);
            } else {
                $arr[$key] = $value;
            }
        }
    }
}

?>