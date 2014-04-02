<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Airline extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	function get_max_seats() {
		$data = $this->table_airline->select_max('al_max')->get_single();
		echo json_encode($data);
	}
}