<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class Cache {

	private static $cacher = null;

	public static function set_cacher($cacher) {
		self::$cacher = $cacher;
	}
	
	public static function get_cacher() {
		return self::$cacher;
	}
	
	public static function instance() {
		return self::get_cacher();
	}
	
	public static function add($name, $value) {
		return self::$cacher->add($name, $value);
	}
	
	public static function set($name, $value) {
		self::$cacher->set($name, $value);
	}
	
	public static function get($name) {
		return self::$cacher->get($name);
	}
	
	public static function delete($name) {
		return self::$cacher->delete($name);
	}
	
	public static function clear() {
	
	}
	
}
?>