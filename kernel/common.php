<?php
if (defined("IN_APPS") === false) exit("Access Dead");

function __autoload($class_name) {
	static $instance_cache = array();

	if (in_array($class_name, $instance_cache) === false) {
		foreach(array('core', 'driver', 'helper', 'model', 'cacher') as $directory) {
			$class_path = KERNEL_ROOT.'/'.$directory.'/'.strtolower($class_name).'.php';
			if (is_file($class_path) === true && file_exists($class_path) === true) {
				$instance_cache[$class_name] = $class_path;
				require_once $class_path;
				return true;
			}
		}
	}
	
	return false;
}

function debug_it() {
	$arguments =  func_get_args();
	$display_mode = $arguments[0];
	$total_arguments = count($arguments);

	if (is_array($display_mode) === true) {
		$display_mode = "print_r";
	}

	if (is_array($arguments) === true) {
		foreach($arguments as $argument) {
			switch($display_mode) {
				case "echo":
					echo $argument;
					echo "<br />";
					break;
				case "var_dump":
					echo "<pre>";
					var_dump($argument);
					echo "</pre>";
					echo "<hr />";
					break;
				default:
					echo "<pre>";
					print_r($argument);
					echo "</pre>";
					echo "<hr />";
					break;
			}
		}
	}
	
	debug_back_trace(true);
}

function debug_back_trace($display_trace = false, $traces_to_ignore = 1) {
	$traces = debug_backtrace();
	$ret = array();
	foreach($traces as $i => $call){
		if ($i < $traces_to_ignore ) {
			continue;
		}

		$object = '';
		if (isset($call['class'])) {
			$object = $call['class'].$call['type'];
			if (is_array($call['args'])) {
				foreach ($call['args'] as &$arg) {
					func_get_args($arg);
				}
			}
		}        

		$ret[] = '#'
				.str_pad($i - $traces_to_ignore, 3, ' ')
				.$object.$call['function'].'('.implode(', ', $call['args'])
        		.') called at ['.$call['file'].':'.$call['line'].']';
    }

	if ($display_trace === true) {
		echo nl2br(implode("\n",$ret));
	}else{
		return implode("\n",$ret);
	}
}
?>