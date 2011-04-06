<?php
require_once dirname(__FILE__).'/kernel/init.php';

Valid_Helper::need_user_logged();

include_once View::display("town.html");
?>