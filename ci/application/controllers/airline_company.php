<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Airline_Company extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		echo json_encode($this->table_airline_company->get_multiple());
	}


}