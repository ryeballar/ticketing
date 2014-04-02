<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Flight_Schedule extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$date = $this->input->get('dept_date');
		$ac_id = $this->input->get('ac_id');

		$data = $this->table_flight_schedule->alias('fs')->
			join(
				'airline al', 'fs.al_id = al.al_id',
				'flight_destination fd', 'fd.fd_id = fs.fd_id')->
			where('al.ac_id', $ac_id, 'fs_dep > ', $date)->
			get_multiple();

		foreach($data as $out_key => $value) {
			foreach($value as $key => $val) {
				if($key == 'fd_place_id_from')
					$data[$out_key]['place_from'] = $this->table_places->get_by_id($val);
				elseif($key == 'fd_place_id_to')
					$data[$out_key]['place_to'] = $this->table_places->get_by_id($val);
			}

			$data[$out_key]['accomodations'] = $this->table_accomodation->alias('accom')->
				join('airline_accomodation al_ac', 'al_ac.accom_id = accom.accom_id',
					 'airline al', 'al.al_id = al_ac.al_id')->
				where('al_ac.al_id', $value['al_al_id'])->get_multiple();
		}

		echo json_encode($data);
	}
}