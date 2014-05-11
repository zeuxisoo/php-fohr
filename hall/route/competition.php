<?php
if (defined("IN_APPS") === false) exit("Access Dead");

use Hall\Middleware\Route;

$app->get('/competition/index', Route::requireLogin(), function() use ($app) {
	$app->render('competition/index.html');
})->name('competition.index');
