<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

	function __construct($rules = array()) {
		parent::__construct($rules);
	}

	function error_array() {
		return $this->_error_array;
	}

	function has_error() {
		return !empty($this->_error_array);
	}

	function run($cb, $key = false) {
		if(($key && parent::run($key)) || parent::run()) {
			$this->success_with_ajax($cb());
			return;
		}
		$this->fail_with_ajax();
	}

	function success_with_ajax($array = array()) {
		$CI = & get_instance();
		$data['success'] = true;
		if(!empty($array))
			$data = array_merge($data, $array);
		echo json_encode($data);
	}

	function fail_with_ajax() {
		$CI = & get_instance();
		$data['success'] = false;
		$data['errors'] = $this->_error_array;
		$data['file'] = $_FILES;
		echo json_encode($data);
	}

	function upload($config) {
		$CI = & get_instance();
		$CI->load->library('upload', $config);
		$data['success'] = $CI->upload->do_upload();
		if($data['success'])
			$data['upload_data'] = $CI->upload->data();
		else
			$data['errors'] = $CI->upload->display_errors('', '');
		return $data;
	}
}