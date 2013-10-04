<?php
if (defined("IN_APPS") === false) exit("Access Dead");

$app->get('/competition/index', function() use ($app) {
	$app->render('competition/index.html');
})->name('competition.index');
