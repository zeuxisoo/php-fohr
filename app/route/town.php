<?php
if (defined("IN_APPS") === false) exit("Access Dead");

use App\Middleware\Route;

$app->get('/town/index', Route::require_login(), function() use ($app) {
	$app->render('town/index.html');
})->name('town.index');
