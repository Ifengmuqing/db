<?php
defined('BASEPATH') or exit('No direct script access allowed');
//APPSECRET

class testJsonp extends MY_Controller
{
	public function index()
	{
        $json='';
		if (!empty($_GET['callback'])) {
            return $_GET['callback'] . '(' . $json . ')'; // jsonp
        }
        return $json;
	}

}
?>