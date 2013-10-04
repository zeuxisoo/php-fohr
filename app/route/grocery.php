<?php
if (defined("IN_APPS") === false) exit("Access Dead");

use App\Middleware\Route;

$app->get('/grocery/buy', Route::require_login(), function() use ($app) {
	$app->render('grocery/buy.html');
})->name('grocery.buy');

$app->get('/grocery/sell', Route::require_login(), function() use ($app) {
	$app->render('grocery/sell.html');
})->name('grocery.sell');

$app->get('/grocery/work', Route::require_login(), function() use ($app) {
	$app->render('grocery/work.html');
})->name('grocery.work');
