<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'abstract_table_model.php';

class table_airline extends abstract_table_model {

	function __construct() {
		parent::__construct('airline', 'al_id');
	}

}