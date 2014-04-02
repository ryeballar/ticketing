<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'abstract_table_model.php';

class table_flight_schedule extends abstract_table_model {

	function __construct() {
		parent::__construct('flight_schedule', 'fs_id');
	}


}