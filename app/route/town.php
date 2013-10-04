<?php
if (defined("IN_APPS") === false) exit("Access Dead");

$app->get('/town/index', function() use ($app) {
	$app->render('town/index.html');
})->name('town.index');
