<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agency extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		echo json_encode($this->table_agency->get_multiple());
	}
}