<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Place extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data = $this->table_places->get_multiple();
		echo json_encode($data);
	}
}