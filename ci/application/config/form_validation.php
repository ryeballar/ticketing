<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

function rule($field, $label,$rule) {
	return array(
		'field' => $field,
		'label' => $label,
		'rules' => $rule
	);
}

function normal_text($additional = false) {
	$normal = 'trim|required|xss_clean';
	if($additional) $normal .= "|$additional";
	return $normal;
}

$config['member/signup'] = array(
	rule('member[member_name]', 'Name', normal_text('max_length[50]')),
	rule('member[member_email]', 'Email', normal_text('valid_email|max_length[50]|callback__check_email_unique')),
	rule('member[password]', 'Password', normal_text('max_length[50]|md5')),
	rule('confirm_password', 'Confirm Password', normal_text('md5|max_length[50]|callback__check_password_confirmed'))
);

$config['member/login'] = array(
	rule('member[member_email]', 'Email', normal_text('valid_email|max_length[50]')),
	rule('member[password]', 'Password', normal_text('max_length[50]|md5|callback__check_login')),
);

