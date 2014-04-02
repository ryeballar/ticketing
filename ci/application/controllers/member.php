<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Controller {

	private $member;

	function __construct() {
		parent::__construct();
	}

	function signup() {
		$this->form_validation->run(function() {
			$member = $this->input->post('member');
			$this->table_membership->insert($member);
			S($this->table_membership->get_last_record());
		});
	}

	function login() {
		$this->form_validation->run(function() {
			S($this->member);
		});
	}

	function _check_login($password) {
		$email = set_value('member[member_email]', '');
		$this->member = $this->table_membership->
			where('member_email', $email, 'password', $password)->
			get_single();

		if(empty($this->member)) {
			$this->form_validation->set_message('_check_login', 'Email or Passowrd is incorrect');
			return false;
		}

		return true;
	}

	function _check_email_unique($email) {
		$count = $this->table_membership->where('member_email', $email)->count();
		if($count > 0)  {
			$this->form_validation->set_message('_check_email_unique', 'Email address is already taken');
			return false;
		}

		return true;
	}

	function _check_password_confirmed($confirm) {
		$password = set_value('member[password]', '');
		if($password != $confirm) {
			$this->form_validation->set_message('_check_password_confirmed', 'Password confirmation did not match');
			return false;	
		}

		return true;
	}
}