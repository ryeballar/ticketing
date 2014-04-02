<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'abstract_table_model.php';

class table_booking extends abstract_table_model {

	function __construct() {
		parent::__construct('booking', 'bk_id');
	}

}