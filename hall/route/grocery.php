<?php
if (defined("IN_APPS") === false) exit("Access Dead");

use Hall\Middleware\Route;

$app->get('/grocery/buy', Route::requireLogin(), function() use ($app) {
	$app->render('grocery/buy.html');
})->name('grocery.buy');

$app->get('/grocery/sell', Route::requireLogin(), function() use ($app) {
	$app->render('grocery/sell.html');
})->name('grocery.sell');

$app->get('/grocery/work', Route::requireLogin(), function() use ($app) {
	$app->render('grocery/work.html');
})->name('grocery.work');
