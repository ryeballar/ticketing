<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Booking extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {

	}

	function save() {
		$data = json_decode($this->input->get('data'));
		$passengers = $data->guests;
		$sched = json_decode($this->input->get('sched'));
		$accom = json_decode($this->input->get('accom'));

		if(is_array($passengers)) {
			foreach($passengers as $ps) {
				$this->table_passenger->insert($ps);
				$bk['bk_gross'] = $accom['al_ac_fare'];
				$bk['ps_id'] = $ps['ps_id'];
				$bk['fs_id'] = $sched['fs_fs_id'];
				$bk['accom_id'] = $accom['accom_accom_id'];
				$this->table_booking->insert($bk);
			}
		}

		$info['to'] = $this->table_booking->insert_id();
		$info['from'] = $info['to'] - (count($passengers) - 1);
		echo json_encode($data);
	}

	function search_range() {
		$from = $this->input->get('from');
		$to = $this->input->get('to');

		echo json_encode($this->table_booking->
			where('bk_id >=', $from, 'bk_id <=', $to)->get_multiple());
	}

}