<?php
require_once realpath("../../kernel/init.php");

Valid_Helper::need_user_logged();

if (Request::is_post() === true) {



}else{
	include_once View::display('town/forging/create.html');
}
?>