<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'abstract_table_model.php';

class table_airline_accomodation extends abstract_table_model {

	function __construct() {
		parent::__construct('airline_accomodation', array('al_id', 'accom_id'));
	}

}