<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
function refresh($url = '') {
	redirect(base_url($url), 'refresh');
}

function S($key = null, $value = null) {
	$CI = & get_instance();
	if($key == null)
		$CI->session->sess_destroy();
	elseif(is_array($key))
		$CI->session->set_userdata($key);
	elseif($value === null)
		return $CI->session->userdata($key);
	elseif($value === false)
		$CI->session->unset_userdata($key);
	else
		$CI->session->set_userdata($key, $value);
}

function P($key) {
	$CI = & get_instance();
	return $CI->input->post($key);
}

function G($key) {
	$CI = & get_instance();
	return $CI->input->get($key);
}

function C($key) {
	$CI = & get_instance();
	return $CI->input->cookie($key);
}

function get_current_date_time() {
	return date('Y-m-d H:i:s');
}

function get_current_date() {
	return date('Y-m-d');
}