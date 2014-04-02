<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'abstract_table_model.php';

class table_passenger extends abstract_table_model {

	function __construct() {
		parent::__construct('passenger', 'ps_id');
	}

}