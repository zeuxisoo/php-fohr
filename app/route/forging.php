<?php
if (defined("IN_APPS") === false) exit("Access Dead");

use App\Middleware\Route;

$app->get('/forging/refine', Route::require_login(), function() use ($app) {
	$app->render('forging/refine.html');
})->name('forging.refine');

$app->get('/forging/create', Route::require_login(), function() use ($app) {
	$app->render('forging/create.html');
})->name('forging.create');
