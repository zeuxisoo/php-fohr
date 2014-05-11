<?php
if (defined("IN_APPS") === false) exit("Access Dead");

use Hall\Middleware\Route;

$app->get('/town/index', Route::requireLogin(), function() use ($app) {
	$app->render('town/index.html');
})->name('town.index');
