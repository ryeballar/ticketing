<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Flight extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	function check() {
		$data = $this->table_flight_schedule->where('fs_dep >= ', '2014-01-01')->get_multiple();
		echo json_encode($data);
	}

	function index() {
		$fs_id = G('fs_id');
		$ac_id = G('ac_id');
		$agency_id = G('agency_id');
		$start_date = G('start_date');
		$end_date = G('end_date');
		$price_start = G('price_start');
		$price_end = G('end_date');
		$available_seats = G('available_seats');
		$destination_from = G('destination_from');
		$destination_to = G('destination_to');

		$sql = "
			SELECT fs.fs_id, al.al_id, al.al_name, ac.ac_id, ac.ac_name, fd.place_id_from, fd.place_id_to, fs.fs_dep
				FROM flight_schedule fs
				INNER JOIN airline al
					ON al.al_id = fs.al_id
				INNER JOIN airline_company ac
					ON ac.ac_id = al.ac_id
				INNER JOIN flight_destination fd
				 	ON fd.fd_id = fs.fd_id
		";


		$where = array();
		if($fs_id != false)
			$where['fs.fs_id = '] = $fs_id;
		if($ac_id != false)
			$where['ac.ac_id = '] = $ac_id;
		/*if($start_date != false)
			$where['fs.fs_dep >= '] = $start_date;
		if($end_date != false)
			$where['fs.fs_dep <= '] = $end_date;*/

		$count = count($where);
		if(count($where) > 0) {
			$sql .= " WHERE";
			$i = 0;
			foreach($where as $key => $value) {
				$sql .= " $key ?";
				if($i + 1 < $count)
					$sql .= " AND";
				$i++;
			}
			$query = $this->db->query($sql, array_values($where));
		} else
			$query = $this->db->query($sql);

		$data = array();
		$alt_data = array();
		if($query->num_rows() > 0) {
			$data = $query->result_array();
			foreach($data as $key => $value) {
				$from = $this->table_places->get_by_id($value['place_id_from']);
				$to = $this->table_places->get_by_id($value['place_id_to']);
				$data[$key]['place_from'] = $from['place_name'];
				$data[$key]['place_to'] = $to['place_name'];

				$query = $this->db->query("
					SELECT a.accom_id, a.accom_desc, ac.fare FROM accomodation a
						INNER JOIN airline_accomodation ac
						ON a.accom_id = ac.accom_id
						WHERE ac.al_id = ?
				", array($value['al_id']));

				$accom_data = array();
				if($query->num_rows() > 0)
					$accom_data = $query->result_array();
				$data[$key]['accomodations'] = $accom_data;

				$query = $this->db->query("
					SELECT a.agency_id, agency_name
						FROM agency a
						INNER JOIN airline_company_agency aca
							ON a.agency_id = aca.agency_id
						INNER JOIN airline_company ac
							ON ac.ac_id = aca.ac_id
						WHERE aca.ac_id = ?
				", array($value['ac_id']));

				if($query->num_rows() > 0) {
					$agency_data = $query->result_array();
					$data[$key]['agencies'] = $agency_data;
					if($agency_id) {
						foreach($agency_data as $key => $val) {
							if($val['agency_id'] == $agency_id) {
								$alt_data[] = $data[$key];
								break;
							}
						}
					}
				} else {
					$data[$key]['agencies'] = array();
				}
			}
		}

		if(count($alt_data) > 0)
			echo json_encode($alt_data);
		else
			echo json_encode($data);
	}

}