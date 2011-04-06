<?php
if (defined("IN_APPS") === false) exit("Access Dead");

abstract class Cacher {

	abstract public function add($name, $value);
	abstract public function set($name, $value);
	abstract public function get($name);
	abstract public function delete($name);
	abstract public function clear();
	
}
?>