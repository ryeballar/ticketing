<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Accomodation extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	function get_min_max() {
		$data['min'] = $this->table_airline_accomodation->select_min('fare')->get_single();
		$data['max'] = $this->table_airline_accomodation->select_max('fare')->get_single();
		echo json_encode($data);
	}	
}