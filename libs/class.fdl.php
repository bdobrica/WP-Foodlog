<?php
class WP_FDL_Nutrient {
	private $ID;
	private $keys;
	private $data;
	private $value;

	public function __construct ($data = null, $value = 0.00) {
		global $wpdb;
		$this->keys = array (
			'nutrient',
			'unit'
			);
		$this->value = $value;

		if (is_numeric($data)) {
			$sql = $wpdb->prepare ('select * from `'.$wpdb->prefix.'nutrients` where id=%d;', (int) $data);
			$data = $wpdb->get_row ($sql, ARRAY_A);
			$this->ID = (int) $this->data['id'];
			}
		if (is_array($data)) {
			foreach ($this->keys as $key)
				$this->data[$key] = $data[$key];
			}
		}

	public function get ($key = '', $value = '') {
		global $wpdb;
		if (in_array($key, $this->keys)) return $this->data[$key];
		if ($key == 'value') return $this->value;
		return $this->ID;
		}

	public function set ($key = '', $value = '') {
		global $wpdb;
		if ($key == 'value') {
			$this->value = (float) $value;
			return TRUE;
			}
		if (is_array ($key)) {
			$update = array ();
			foreach ($key as $_k => $_v) {
				if (!in_array($key, $this->keys)) continue;
				$update[] = $wpdb->prepare ($_k.'=%s', $_v);
				$this->data[$_k] = $_v;
				}
			if ($this->ID)
				$wpdb->query ($wpdb->prepare('update `'.$wpdb->prefix.'nutrients` set '.implode(',',$update).' where id=%d;', $this->id));
			}
		else {
			if (!in_array($key, $this->keys)) return FALSE;
			$this->data[$key] = $value;
			if ($this->ID)
				$wpdb->query ($wpdb->prepare('update `'.$wpdb->prefix.'nutrients` set '.$key.'=%s where id=%d;', $value, $this->id));
			}
		return TRUE;
		}

	public function save () {
		global $wpdb;
		if ($this->ID) return FALSE;
		$sql = $wpdb->prepare ('insert into `'.$wpdb->prefix.'nutrients` ('.implode(',', $this->keys).') values ('.str_pad('', count($this->keys)*3 - 1, '%s,').');', array_values($this->data));
		$wpdb->query ($sql);
		$this->ID = $wpdb->insert_id;
		}

	public function init ($flag = TRUE) {
		global $wpdb;
		if ($flag)
			$wpdb->query ('CREATE TABLE `'.$wpdb->prefix.'nutrients` (
				`id` int(11) NOT NULL auto_increment,
				`usda` int(11) NOT NULL default 0,
				`nutrient` text NOT NULL,
				`unit` varchar(7) NOT NULL default \'\',
				PRIMARY KEY  (`id`),
				UNIQUE KEY `usda` (`usda`));');
		else
			$wpdb->query ('drop table `'.$wpdb->prefix.'nutrients`;');
		}
	}

class WP_FDL_Food {
	private $ID;
	private $keys;
	private $data;
	private $nutrients;
	private $quantity;

	public function __construct ($data = null, $quantity = 0.00) {
		global $wpdb;
		$this->keys = array (
			'food',
			'foodgroup',
			);
		$this->nutrients = array ();
		$this->quantity = $quantity;

		if (is_numeric($data)) {
			$sql = $wpdb->prepare ('select * from `'.$wpdb->prefix.'foods` where id=%d;', (int) $data);
			$data = $wpdb->get_row ($sql, ARRAY_A);
			$this->ID = (int) $this->data['id'];
			if ($this->ID) {
				$nutrients = new WP_FDL_List ('nutrients', $this);
				$this->nutrients = $nutrients->get();
				}
			}

		if (is_array($data)) {
			foreach ($this->keys as $key)
				$this->data[$key] = $data[$key];
			}
		}

	public function get ($key = '', $value = '') {
		global $wpdb;
		if (in_array($key, $this->keys)) return $this->data[$key];
		if ($key == 'nutrients') return $this->nutrients;
		if ($key == 'quantity') return $this->quantity;
		return $this->ID;
		}

	public function set ($key = '', $value = '') {
		global $wpdb;
		if ($key == 'quantity') {
			$this->quantity = (float) $value;
			return TRUE;
			}
		if (is_array ($key)) {
			$update = array ();
			foreach ($key as $_k => $_v) {
				if (!in_array($key, $this->keys)) continue;
				$update[] = $wpdb->prepare ($_k.'=%s', $_v);
				$this->data[$_k] = $_v;
				}
			if ($this->ID)
				$wpdb->query ($wpdb->prepare('update `'.$wpdb->prefix.'foods` set '.implode(',',$update).' where id=%d;', $this->id));
			}
		else {
			if (!in_array($key, $this->keys)) return FALSE;
			$this->data[$key] = $value;
			if ($this->ID)
				$wpdb->query ($wpdb->prepare('update `'.$wpdb->prefix.'foods` set '.$key.'=%s where id=%d;', $value, $this->id));
			}
		return TRUE;
		}

	public function save () {
		global $wpdb;
		if ($this->ID) return FALSE;
		$sql = $wpdb->prepare ('insert `'.$wpdb->prefix.'foods` into ('.implode(',', $this->keys).') values ('.str_pad('', count($this->keys)*3 - 1, '%s,').');', array_values($this->data));
		$wpdb->query ($sql);
		$this->ID = $wpdb->insert_id;
		}

	public function init ($flag = TRUE) {
		global $wpdb;
		if ($flag) {
			$wpdb->query ('CREATE TABLE `'.$wpdb->prefix.'foods` (
				`id` int(11) NOT NULL auto_increment,
				`usda` int(11) NOT NULL default 0,
				`food` text NOT NULL,
				`name` text NOT NULL,
				`keywords` text NOT NULL,
				`foodgroup` int(11) NOT NULL default 0,
				PRIMARY KEY  (`id`),
				UNIQUE KEY `usda` (`usda`),
				FULLTEXT KEY `food` (`food`));');
			}
		else {
			$wpdb->query ('drop table `'.$wpdb->prefix.'foods`;');
			}
		}
	}

class WP_FDL_Group {
	private $ID;
	private $keys;
	private $data;

	public function __construct ($data = null) {
		global $wpdb;
		$this->keys = array (
			);
		if (is_numeric($data)) {
			$sql = $wpdb->prepare ('select * from where id=%d;', (int) $data);
			$data = $wpdb->get_row ($sql, ARRAY_A);
			$this->ID = (int) $this->data['id'];
			}
		if (is_array($data)) {
			foreach ($this->keys as $key)
				$this->data[$key] = $data[$key];
			}
		}

	public function get ($key = '', $value = '') {
		global $wpdb;
		if (in_array($key, $this->keys)) return $this->data[$key];
		return $this->ID;
		}

	public function set ($key = '', $value = '') {
		global $wpdb;
		if (is_array ($key)) {
			$update = array ();
			foreach ($key as $_k => $_v) {
				if (!in_array($key, $this->keys)) continue;
				$update[] = $wpdb->prepare ($_k.'=%s', $_v);
				$this->data[$_k] = $_v;
				}
			if ($this->ID)
				$wpdb->query ($wpdb->prepare('update set '.implode(',',$update).' where id=%d;', $this->id));
			}
		else {
			if (!in_array($key, $this->keys)) return FALSE;
			$this->data[$key] = $value;
			if ($this->ID)
				$wpdb->query ($wpdb->prepare('update set '.$key.'=%s where id=%d;', $value, $this->id));
			}
		return TRUE;
		}

	public function save () {
		global $wpdb;
		if ($this->ID) return FALSE;
		$sql = $wpdb->prepare ('insert into ('.implode(',', $this->keys).') values ('.str_pad('', count($this->keys)*3 - 1, '%s,').');', array_values($this->data));
		$wpdb->query ($sql);
		$this->ID = $wpdb->insert_id;
		}

	public function init ($flag = TRUE) {
		global $wpdb;
		if ($flag) {
			$wpdb->query ('create table (
				id int not null primary key auto_increment,
				);');
			}
		else {
			$wpdb->query ('drop table;');
			}
		}
	}

class WP_FDL_Meal {
	private $ID;
	private $keys;
	private $data;
	private $foods;

	public function __construct ($data = null) {
		global $wpdb;
		$this->keys = array (
			);
		if (is_numeric($data)) {
			$sql = $wpdb->prepare ('select * from where id=%d;', (int) $data);
			$data = $wpdb->get_row ($sql, ARRAY_A);
			$this->ID = (int) $this->data['id'];
			}
		if (is_array($data)) {
			foreach ($this->keys as $key)
				$this->data[$key] = $data[$key];
			}
		}

	public function get ($key = '', $value = '') {
		global $wpdb;
		if (in_array($key, $this->keys)) return $this->data[$key];
		return $this->ID;
		}

	public function set ($key = '', $value = '') {
		global $wpdb;
		if (is_array ($key)) {
			$update = array ();
			foreach ($key as $_k => $_v) {
				if (!in_array($key, $this->keys)) continue;
				$update[] = $wpdb->prepare ($_k.'=%s', $_v);
				$this->data[$_k] = $_v;
				}
			if ($this->ID)
				$wpdb->query ($wpdb->prepare('update set '.implode(',',$update).' where id=%d;', $this->id));
			}
		else {
			if (!in_array($key, $this->keys)) return FALSE;
			$this->data[$key] = $value;
			if ($this->ID)
				$wpdb->query ($wpdb->prepare('update set '.$key.'=%s where id=%d;', $value, $this->id));
			}
		return TRUE;
		}

	public function save () {
		global $wpdb;
		if ($this->ID) return FALSE;
		$sql = $wpdb->prepare ('insert into ('.implode(',', $this->keys).') values ('.str_pad('', count($this->keys)*3 - 1, '%s,').');', array_values($this->data));
		$wpdb->query ($sql);
		$this->ID = $wpdb->insert_id;
		}

	public function init ($flag = TRUE) {
		global $wpdb;
		if ($flag) {
			$wpdb->query ('create table `'.$wpdb->prefix.'meals` (
id int not null primary key auto_increment,
uid int not null default 0,
type enum(\'breakfast\',\'brunch\',\'lunch\',\'dinner\',\'supper\',\'snack\') not null default \'snack\',
comment text not null,
stamp int not null);');
			}
		else {
			$wpdb->query ('drop table `'.$wpdb->prefix.'meals`;');
			}
		}
	}

class WP_FDL_User {
	private $ID;
	private $keys;
	private $data;

	public function __construct ($data = null) {
		global $wpdb;
		$this->keys = array (
			);
		if (is_numeric($data)) {
			$sql = $wpdb->prepare ('select * from where id=%d;', (int) $data);
			$data = $wpdb->get_row ($sql, ARRAY_A);
			$this->ID = (int) $this->data['id'];
			}
		if (is_array($data)) {
			foreach ($this->keys as $key)
				$this->data[$key] = $data[$key];
			}
		}

	public function get ($key = '', $value = '') {
		global $wpdb;
		if (in_array($key, $this->keys)) return $this->data[$key];
		return $this->ID;
		}

	public function set ($key = '', $value = '') {
		global $wpdb;
		if (is_array ($key)) {
			$update = array ();
			foreach ($key as $_k => $_v) {
				if (!in_array($key, $this->keys)) continue;
				$update[] = $wpdb->prepare ($_k.'=%s', $_v);
				$this->data[$_k] = $_v;
				}
			if ($this->ID)
				$wpdb->query ($wpdb->prepare('update set '.implode(',',$update).' where id=%d;', $this->id));
			}
		else {
			if (!in_array($key, $this->keys)) return FALSE;
			$this->data[$key] = $value;
			if ($this->ID)
				$wpdb->query ($wpdb->prepare('update set '.$key.'=%s where id=%d;', $value, $this->id));
			}
		return TRUE;
		}

	public function save () {
		global $wpdb;
		if ($this->ID) return FALSE;
		$sql = $wpdb->prepare ('insert into ('.implode(',', $this->keys).') values ('.str_pad('', count($this->keys)*3 - 1, '%s,').');', array_values($this->data));
		$wpdb->query ($sql);
		$this->ID = $wpdb->insert_id;
		}

	public function init ($flag = TRUE) {
		global $wpdb;
		if ($flag) {
			$wpdb->query ('create table (
				id int not null primary key auto_increment,
				);');
			}
		else {
			$wpdb->query ('drop table;');
			}
		}
	}

class WP_FDL_List {
	private $data;

	public function __construct ($what = '', $filter = null) {
		global $wpdb;
		$this->data = array ();
		if ($what == 'nutrients') {
			if (is_numeric($filter))
				$sql = $wpdb->prepare ('select * from `'.$wpdb->prefix.'contents` where food=%d;', $filter);
			else
			if (is_object($filter))
				$sql = $wpdb->prepare ('select * from `'.$wpdb->prefix.'contents` where food=%d;', $filter->get());
			
			$rows = $wpdb->get_results($sql);
			if (!empty($data))
				foreach ($rows as $row)
					$this->data[] = new WP_FDL_Nutrient ((int) $row->nutrient, (float) $row->value);
			}
		}

	public function get ($key = '', $value = '') {
		global $wpdb;
		return $this->data;
		}

	public function init ($flag = TRUE) {
		global $wpdb;
		if ($flag) {
			$wpdb->query ('CREATE TABLE `'.$wpdb->prefix.'contents` (
				`id` int(11) NOT NULL auto_increment,
				`food` int(11) NOT NULL default 0,
				`nutrient` int(11) NOT NULL default 0,
				`value` float(10,3) NOT NULL default 0.000,
				PRIMARY KEY  (`id`),
				UNIQUE KEY `food` (`food`,`nutrient`));');
			$wpdb->query ('CREATE TABLE `'.$wpdb->prefix.'');
			}
		else {
			$wpdb->query ('drop table `'.$wpdb->prefix.'contents`;');
			}
		}
	}
?>
