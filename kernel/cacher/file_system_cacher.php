<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class File_System_Cacher extends Cacher {

	private $cache_root = "";

	public function __construct($config) {
		$this->cache_root = $config['cache_root'];
	}

	public function add($name, $value) {
		$cache_file_path = self::get_cache_file_path($name);
		
		$cache = array();
		
		if (file_exists($cache_file_path) === true && is_file($cache_file_path) === true) {
			require_once $cache_file_path;
			
			if (isset($cache[$name]) === true) {
				return false;
			}
		}
		
		$cache[$name] = $value;
		
		file_put_contents(
			$cache_file_path, 
			"<?php if(!defined('IN_APPS')) exit('Access Denied');\n".'$'."cache['".$name."'] = ".var_export($cache[$name], true)."?>"
		);
		
		return true;
	}

	public function set($name, $value) {
		$cache_file_path = self::get_cache_file_path($name);
		
		$cache = array();
		
		if (file_exists($cache_file_path) === true && is_file($cache_file_path) === true) {
			require_once $cache_file_path;
		}
		
		$cache[$name] = $value;
		
		file_put_contents(
			$cache_file_path, 
			"<?php\nif(!defined('IN_APPS')) exit('Access Denied');\n".'$'."cache['".$name."'] = ".var_export($cache[$name], true)."?>"
		);
	}
	
	public function get($name) {
		static $cache_datas = array();
	
		$cache_file_path = self::get_cache_file_path($name);
		
		if (file_exists($cache_file_path) === true && is_file($cache_file_path) === true) {
			require_once $cache_file_path;
			
			if (isset($cache[$name]) === true) {
				$cache_datas[$name] = $cache[$name];
				return $cache[$name];
			}
			
			if (isset($cache_datas[$name]) === true) {
				return $cache_datas[$name];
			}
		}
		
		return "";
	}
	
	public function delete($name) {
		@unlink($this->cache_root.'/'.$name.'.php');
	}
	
	public function clear() {
	}

	private function get_cache_file_path($name) {
		return $this->cache_root.'/'.$name.'.php';
	}

}
?>