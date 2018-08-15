<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Apply extends CI_Controller
{
	public function index()
	{
		$data['sess_id']= $_SESSION['sid'] = mt_rand(1000, 9999);
		$this->load->view('m/apply.html',$data);
	}
	public function getApplyList()
	{
		$this->load->model('Apply_model', 'am');
		$res = $this->am->getAll();
		$data = array(
			'data' => $res,
		);
		$this->load->view('m/applyList.html', $data);
	}
	public function deleteItem()
	{
		header("Access-Control-Allow-Origin: *");
		$mid = trim($this->input->post('uid'));
		$this->load->model('Apply_model', 'am');
		$n = $this->am->delItem($mid);
		if ($n == true) {
			$arr = array('success' => 'ok');
			echo json_encode($arr);
		} else {
			$arr = array('success' => 'fail');
			echo json_encode($arr);
		}
	}
	public function HandleApply()
	{

		if(isset($_POST['sid']) && isset($_SESSION['sid'])){
			if ($_POST['sid'] != '' && isset($_SESSION['sid'])) {
				unset($_SESSION['sid']);
				$name = trim($this->input->post('username'));
				$phone = trim($this->input->post('phonenom'));
				$age = trim($this->input->post('age'));
				$city = trim($this->input->post('city'));
				$time = time();
				$this->load->model('apply_model', 'apply');
				$this->apply->addApply($name, $phone, $age, $city, $time);
				$this->load->view('m/applySuccess.html');
	
			} else {
				$data = array(
					'icon' => 'weui-icon-success',
					'where' => 'admin',
					'result' => '请勿重新提交'
				);
				$this->load->view('m/msg.html', $data);
			}
		}else{
			redirect('m/Apply', 'refresh');
		}
		
	}
}
?>