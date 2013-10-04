<?php
if (defined("IN_APPS") === false) exit("Access Dead");

$app->get('/grocery/buy', function() use ($app) {
	$app->render('grocery/buy.html');
})->name('grocery.buy');

$app->get('/grocery/sell', function() use ($app) {
	$app->render('grocery/sell.html');
})->name('grocery.sell');

$app->get('/grocery/work', function() use ($app) {
	$app->render('grocery/work.html');
})->name('grocery.work');
