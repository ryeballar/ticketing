<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Airline_Company extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		echo json_encode($this->table_airline_company->
				select('ac_id as ac_ac_id, ac_name as ac_ac_name')->get_multiple());
	}

	function search_by_agency() {
		$agency_id = $this->input->get('agency_id');
		$data = $this->table_airline_company->alias('ac')->
			join('airline_company_agency aca', 'ac.ac_id = aca.ac_id')->
			where('ac.ac_id', $agency_id)->get_multiple();
		echo json_encode($data);
	}

}