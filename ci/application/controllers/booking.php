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

		$sql = "
			SELECT distinct al.al_name, ac.ac_id, ac.ac_name, pf.place_name as place_from, pt.place_name as place_to, fs.fs_dep
				FROM flight_schedule fs
				INNER JOIN airline al
					ON al.al_id = fs.al_id
				INNER JOIN airline_company ac
					ON ac.ac_id = al.ac_id
				INNER JOIN flight_destination fd
				 	ON fd.fd_id = fs.fd_id
				INNER JOIN places pf
					ON pf.place_id = fd.place_id_from
				INNER JOIN places pt
					ON pt.place_id = fd.place_id_to
			WHERE fs.fs_id = ?
		";

		$query = $this->db->query($sql, array($fs_id));

		if($query->num_rows() > 0) {
			$flight_data = $query->row_array();

			$email_message = $this->load->view('ticket-email', array(
				'at_id' => $data['at_id'],
				'flight' => $flight_data,
				'passenger' => $passenger,
				'accom' => $accom
			), true);

			$data['email'] = $this->email_model->send(
				'New Ticket Created in Softeng Ticketing System', 
				$email_message, array($passenger['ps_email']));
		}

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