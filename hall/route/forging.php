<?php
if (defined("IN_APPS") === false) exit("Access Dead");

use Hall\Middleware\Route;

$app->get('/forging/refine', Route::requireLogin(), function() use ($app) {
	$app->render('forging/refine.html');
})->name('forging.refine');

$app->get('/forging/create', Route::requireLogin(), function() use ($app) {
	$app->render('forging/create.html');
})->name('forging.create');
