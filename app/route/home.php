<?php
if (defined("IN_APPS") === false) exit("Access Dead");

use App\Middleware\Route;
use App\Model\User;

$app->get('/home/index', Route::require_login(), function() use ($app) {
	$user = User::get($_SESSION['user']['id']);

	if (empty($user->team_name) === true) {
		$app->render('home/first.html');
	}else{
		$app->render('home/index.html');
	}
})->name('home.index');
