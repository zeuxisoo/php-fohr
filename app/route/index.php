<?php
if (defined("IN_APPS") === false) exit("Access Dead");

$app->get('/', function() use ($app) {
	$app->render('index.html');
});
