<?php
if (defined("IN_APPS") === false) exit("Access Dead");

$app->get('/forging/refine', function() use ($app) {
	$app->render('forging/refine.html');
})->name('forging.refine');

$app->get('/forging/create', function() use ($app) {
	$app->render('forging/create.html');
})->name('forging.create');
