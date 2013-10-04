<?php
if (defined("IN_APPS") === false) exit("Access Dead");

$app->get('/recruit/index', function() use ($app) {
	$app->render('recruit/index.html');
})->name('recruit.index');
