<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class Language {

	const NOT_FOUND_LANGUAGE_FOLDER = 1;
	const COMPILE_ALL = 1;
	const COMPILE_RENEW = 2;

	private static $language_root = "";
	private static $language_name = "";
	private static $language_folder = "";
	private static $language_access_string = "IN_APPS";
	private static $cached_language_file_path = "";
	private static $cache_name = "system_language";

	public static function set_settings($settings) {
		self::$language_root = $settings['language_root'];
		self::$language_name = $settings['language_name'];
		self::$language_folder = $settings['language_root']."/".$settings['language_name'];
		self::$cached_language_file_path = CACHE_ROOT."/language/".self::$language_name.".php";
		
		if (isset($settings['language_access_string']) === true) {
			self::$language_access_string = $settings['language_access_string'];
		}
		
		self::init_cache();
	}

	public static function init_cache() {
		$language_folder = self::$language_folder;

		if (is_dir($language_folder)) {
		
			// If cache file not exists, make all language file to single file
			if (file_exists(self::$cached_language_file_path) === false) {
				self::compile(glob($language_folder."/*.php"), self::COMPILE_ALL);
			}else{
				// Search which language file was modifited and just make it
				$renew_language_list = array();
				
				foreach(glob($language_folder."/*.php") as $file_path) {
					if (filemtime($file_path) > filemtime(self::$cached_language_file_path)) {
						$renew_language_list[] = $file_path;
					}
				}
				
				if (empty($renew_language_list) === false) {
					self::compile($renew_language_list, self::COMPILE_RENEW);
				}
			}
			
			return true;
			
		}else{
			self::show_error(self::NOT_FOUND_LANGUAGE_FOLDER, $language_folder);
		}
	}
	
	public static function compile($language_file_path_list, $compile_mode) {
		$system_language = array();
		
		// Include compile file
		foreach($language_file_path_list as $file_path) {
			include $file_path;
			
			if ($compile_mode === self::COMPILE_ALL) {
				$system_language = array_merge($system_language, $lang);
			}elseif ($compile_mode === self::COMPILE_RENEW) {
				include_once self::$cached_language_file_path;
				$cache[self::$cache_name] = array_merge($cache[self::$cache_name], $lang);
			}
		}

		// Redefine variable, case by compile mode
		if ($compile_mode === self::COMPILE_RENEW) {
			$system_language = $cache[self::$cache_name];			
		}
		
		// Make a language cache
		if (is_array($system_language) === true) {
			file_put_contents(
				self::$cached_language_file_path,
				"<?php if(!defined('".self::$language_access_string."')) exit('Access Denied');\n\$cache['".self::$cache_name."'] = ".var_export($system_language, true).";\n".'?>'
			);
		}
	}

	public static function get_text($key) {
		include self::$cached_language_file_path;
		
		// If cache data is null, then delete it and re-create it
		if (isset($cache[self::$cache_name]) === true && $cache[self::$cache_name] === null) {
			self::delete_cache();
			self::init_cache();
			self::get_text($key, $blank_it);
		}

		if (isset($cache[self::$cache_name][$key]) === true && empty($cache[self::$cache_name][$key]) === false) {
			$value = $cache[self::$cache_name][$key];
		}else{
			$value = $key;
		}
		
		// Support string format
		$args = func_get_args();
		if (count($args) > 1) {
			$value = call_user_func_array("sprintf", $args);
		}
		
		return $value;
	}
	
	public static function delete_cache() {
		@unlink(self::$cached_language_file_path);
	}

	private static function show_error($type, $message) {
		$label = "";

		switch($type) {
			case self::NOT_FOUND_LANGUAGE_FOLDER;
				$label = "Not found language folder";
				break;
		}

		exit("<strong>".$label."</strong>:".$message);
	}

}
?>