<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

abstract class abstract_table_model extends CI_Model {

	const GETTER_SINGLE_PREFIX = "get_single_by_";
	const GETTER_MULTIPLE_PREFIX = "get_multiple_by_";
	const COUNT_PREFIX ="count_by_";

	private $table;
	private $primary;
	private $table_alias = '';

	private $like_type = 'both';
	private $join_type = 'inner';

	private $tables_to_join__ = array();

	private $select_string = '';
	private $select_append_string = '';

	private $q = '';

	function __construct($table, $primary) {
		parent::__construct();
		$this->table = $table;
		$this->primary = $primary;
		$this->tables_to_join__[] = array($table, '');
	}

	function __call($method, $arguments) {
		if($this->is_call_helper_valid($method, $arguments, self::GETTER_SINGLE_PREFIX))
			return $this->get_single();
		elseif($this->is_call_helper_valid($method, $arguments, self::GETTER_SINGLE_PREFIX))
			return $this->get_multiple();
		elseif($this->is_call_helper_valid($method, $arguments, self::COUNT_PREFIX))
			return $this->count();
		
		show_404();
	}

	private function is_call_helper_valid($method, $args, $prefix) {
		$len = strlen($prefix);
		$method_len = strlen($method);
		if($method_len >  $len &&
			substr($method, 0, $len) == $prefix) {
			$params = explode('_', substr($method, $len));
			$count = count($args);
			if($count == count($params)) {
				for($i = 0; $i < $count; $i++) {
					$this->where($params[$i], $args[$i]);
				}
				return true;
			}
		}
		return false;
	}

	function free() {
		$this->q->free_result();
		return $this;
	}

	function from($table) {
		$this->table = $table;
		return $this;
	}

	function alias($alias) {
		if($this->table_alias == '') {
			$this->tables_to_join__[0][1] = $alias;
			$this->table_alias = $alias;
		}
		return $this;
	}

	function insert_id() {
		return $this->db->insert_id();
	}

	function affected_rows() {
		return $this->db->affected_rows();
	}

	function last_query() {
		return $this->db->last_query();
	}

	function get_multiple() {
		if(count($this->tables_to_join__) > 1) {
			$select_fields = array();
			$implode_temp = array(null, '.', null, ' as ', null, '_', null);
			foreach($this->tables_to_join__ as $table) {
				$table_name = $table[0];
				$alias = $table[1];
				$fields = $this->db->list_fields($table_name);
				$length = count($fields);
				for($i = 0; $i < $length; $i++) {
					$implode_temp[0] = $implode_temp[4] = $alias;
					$implode_temp[2] = $implode_temp[6] = $fields[$i];
					$select_fields[] = implode('', $implode_temp);
				}
			}
			$select = implode(',', $select_fields);

			if($this->select_append_string != '')
				$this->db->select($select.','.$this->select_append_string);
			elseif($this->select_string != '')
				$this->db->select($this->select_string);
			else
				$this->db->select($select);

			$save_table = $this->tables_to_join__[0];
			$this->tables_to_join__ = array($save_table);
			$table = implode(' ', $save_table);

			$query = $this->db->get($table);
		} else {
			if($this->table_alias != '') {
				$query = $this->db->get($this->table.' '.$this->table_alias);
			} else {
				$query = $this->db->get($this->table);
			}
		}

		if($query->num_rows() > 0) {
			$result = $query->result_array();
			$this->q = $query;
			return $result;
		}
		return array();
	}

	function get_single() {
		if(count($this->tables_to_join__) > 1) {
			$select_fields = array();
			$implode_temp = array(null, '.', null, ' as ', null, '_', null);
			foreach($this->tables_to_join__ as $table) {
				$table_name = $table[0];
				$alias = $table[1];
				$fields = $this->db->list_fields($table_name);
				$length = count($fields);
				for($i = 0; $i < $length; $i++) {
					$implode_temp[0] = $implode_temp[4] = $alias;
					$implode_temp[2] = $implode_temp[6] = $fields[$i];
					$select_fields[] = implode('', $implode_temp);
				}
			}
			$select = implode(',', $select_fields);

			if($this->select_append_string != '')
				$this->db->select($select.','.$this->select_append_string);
			elseif($this->select_string != '')
				$this->db->select($this->select_string);
			else
				$this->db->select($select);

			$save_table = $this->tables_to_join__[0];
			$this->tables_to_join__ = array($save_table);
			$table = implode(' ', $save_table);

			$query = $this->db->get($table);
		} else {
			if($this->table_alias != '') {
				$query = $this->db->get($this->table.' '.$this->table_alias);
			} else {
				$query = $this->db->get($this->table);
			}
		}
		if($query->num_rows() > 0) { 
			$q = $query;
			return $query->row_array();
		}
		return array();
	}

	function get_by_id($id) {
		return $this->where_id($id)->get_single();
	}

	function get_by_ids() {
		call_user_func_array(array($this, 'where_ids'), func_get_args());
		return $this->get_single();
	}

	function get_last_record() {
		return $this->get_by_id($this->insert_id());
	}

	function select($select_string) {
		$this->db->select($select_string);
		$this->select_string = $select_string;
		return $this;
	}

	function add_select($select_string) {
		if($this->select_string == '')
			$this->select_append_string = $select_append_string;
		else
			$this->select_append_string .= ', '.$select_append_string;
	}

	function select_max() {
		call_user_func_array(array($this->db, 'select_max'), func_get_args());
		return $this;
	}

	function select_min() {
		call_user_func_array(array($this->db, 'select_min'), func_get_args());
		return $this;
	}

	function select_avg() {
		call_user_func_array(array($this->db, 'select_avg'), func_get_args());
		return $this;	
	}

	function select_sum() {
		call_user_func_array(array($this->db, 'select_sum'), func_get_args());
		return $this;		
	}

	function set_join($join_type) {
		$this->join_type = $join_type;
	}

	function join() {
		$count = func_num_args();
		$args = func_get_args();

		for($i = 0; $i < $count; $i += 2) {
			$exploded = explode(' ', $args[$i]);

			if(count($exploded > 0)) {
				$this->tables_to_join__[] = array($exploded[0], $exploded[1]);
			} else {
				$this->tables_to_join__[] = array($args[0][0], '');
			}

			$this->db->join($args[$i], $args[$i+1]);
		}

		return $this;
	}

	function where() {
		$count = func_num_args();
		$args = func_get_args();
		for($i = 0; $i < $count; $i += 2) {
			switch($args[$i]) {
				case 'or': $this->db->or_where($args[++$i], $args[$i+1]); break;
				default: $this->db->where($args[$i], $args[$i+1]);
			}
			
		}
		return $this;
	}

	function where_in() {
		$count = func_num_args();
		$args = func_get_args();
		for($i = 0; $i < $count; $i += 2) {
			switch($args[$i]) {
				case 'or': $this->db->or_where_in($args[++$i], $args[$i+1]); break;
				default: $this->db->where_in($args[$i], $args[$i+1]);
			}
			
		}
		return $this;
	}

	function where_not_in() {
		$count = func_num_args();
		$args = func_get_args();
		for($i = 0; $i < $count; $i += 2) {
			switch($args[$i]) {
				case 'or': $this->db->or_where_not_in($args[++$i], $args[$i+1]); break;
				default: $this->db->where_not_in($args[$i], $args[$i+1]);
			}
			
		}
		return $this;
	}

	function where_id($id) {
		$this->db->where($this->table.'.'.$this->primary, $id);
		return $this;
	}

	function where_ids() {
		$count = func_num_args();
		$ids = func_get_args();
		for($i = 0; $i < $count; $i++)
			$this->db->where($this->table.'.'.$this->primary[$i], $ids[$i]);
		return $this;
	}

	function set_like($like_type) {
		$this->like_type = $like_type;
		return $this;
	}

	function like() {
		$count = func_num_args();
		$args= func_get_args();
		for($i = 0; $i < $count; $i++) {
			switch($args[$i]) {
				case 'or':$this->db->or_like($args[++$i], $args[++$i], $this->like_type); break;
				default: $this->db->like($args[$i], $args[++$i], $this->like_type);
			}
		}

		return $this;
	}

	function not_like() {
		$count = func_num_args();
		$args = func_get_args();
		for($i = 0; $i < $count; $i++) {
			switch($args[$i]) {
				case 'or': $this->db->or_not_like($args[++$i], $args[++$i], $this->like_type); break;
				default: $this->db->not_like($args[$i], $args[++$i], $this->like_type);
			}
		}

		return $this;
	}

	function group_by() {
		foreach(func_get_args() as $field)
			$this->db->group_by($field);
		return $this;
	}

	function distinct() {
		$this->db->distinct();
		return $this;
	}

	function having() {
		$count = func_num_args();
		$args = func_get_args();

		for($i = 0; $i < $count; $i += 2) {
			switch($args[$i]) {
				case 'or': $this->db->or_having($args[++$i], $args[++$i]); break;
				default: $this->db->having($args[$i], $args[++$i]);
			}
		}

		return $this;
	}

	function order_by() {
		foreach(func_get_args() as $order)
			$this->db->order_by($order);
		return $this;
	}

	function limit($limit, $offset = 0) {
		$this->db->limit($limit, $offset);
		return $this;
	}

	function count() {
		return $this->db->count_all_results($this->table);
	}

	function insert($data = false) {
		if($data)
			return $this->db->insert($this->table, $data);
		return $result = $this->db->insert($this->table);
	}

	function insert_batch($data) {
		return $this->db->insert_batch($this->table, $data);
	}

	function set() {
		$count = func_num_args();
		$args = func_get_args();
		for($i = 0; $i < $count; $i += 2)
			$this->db->set($args[$i], $args[$i+1]);
		return $this;
	}

	function update($data = false) {
		if($data)
			return $this->db->update($this->table, $data);
		return $this->db->update($this->table);
	}

	function update_batch($data) {
		return $this->db->update_batch($this->table, $data);
	}

	function delete() {
		call_user_func_array(array($this, 'where'), func_get_args());
		return $this->db->delete($this->table);
	}

	function start_cache() {
		$this->db->start_cache();
		return $this;
	}

	function stop_cache() {
		$this->db->stop_cache();
		return $this;
	}

	function flush_cache() {
		$this->db->flush_cache();
		return $this;
	}

	function trans_start($test_mode = false) {
		$this->db->trans_start($test_mode);
		return $this;
	}

	function trans_complete() {
		$this->db->trans_complete();
		return $this;
	}

	function trans_off() {
		$this->db->trans_off();
		return $this;
	}

	function trans_begin() {
		$this->db->trans_begin();
		return $this;
	}

	function compiled() {
		return $this->db->compiled_select();
	}

	function query($sql, $data) {
		$query = $this->db->query($sql, $data);
		if($query->num_rows() > 0)
			return $query->result_array();
		return array();
	}

}