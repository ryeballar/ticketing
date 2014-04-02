<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Booking extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {

	}

	function create_ticket() {
		$passenger = P('passenger');
		$fs_id = P('fs_id');
		$accom_id = P('accom_id');

		$this->table_passenger->insert($passenger);
		$ps_id = $this->table_passenger->insert_id();

		$flight = $this->table_flight_schedule->get_by_id($fs_id);
		$accom = $this->table_airline_accomodation->
			where('accom_id', $accom_id, 'al_id', $flight['al_id'])->get_single();
		$this->table_airline_ticket->insert(array(
			'at_gross' => $accom['fare'],
			'ps_id' => $ps_id,
			'fs_id' => $fs_id,
			'accom_id' => $accom_id
		));
		$data['at_id'] = $this->table_airline_ticket->insert_id();
		$data['accom'] = $accom;
		echo json_encode($data);
	}

	function save() {
		$passenger = P('passenger');
		$fs_id = P('fs_id');
		$accom_id = P('accom_id');

		$this->table_passenger->insert($passenger);
		$ps_id = $this->table_passenger->insert_id();

		$flight = $this->table_flight_schedule->get_by_id($fs_id);
		$accom = $this->table_airline_accomodation->
			where('accom_id', $accom_id, 'al_id', $flight['al_id'])->get_single();
		$this->table_booking->insert(array(
			'bk_gross' => $accom['fare'],
			'ps_id' => $ps_id,
			'fs_id' => $fs_id,
			'accom_id' => $accom_id
		));
		$data['bk_id'] = $this->table_booking->insert_id();
		$data['accom'] = $accom;
		echo json_encode($data);
	}
}