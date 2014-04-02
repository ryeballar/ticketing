<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Accomodation extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$ac_id = $this->input->get('ac_id');
		$data = $this->table_accomodation->alias('acc')->
			join('airline_accomodation alc', 'alc.accom_id = acc.accom_id')->
			get_multiple();
		echo json_encode($data);
	}
}