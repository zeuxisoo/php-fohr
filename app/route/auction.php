<?php
if (defined("IN_APPS") === false) exit("Access Dead");

$app->get('/auction/index', function() use ($app) {
	$app->render('auction/index.html');
})->name('auction.index');
