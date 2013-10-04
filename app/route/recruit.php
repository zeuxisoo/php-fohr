<?php
if (defined("IN_APPS") === false) exit("Access Dead");

$app->get('/recruit/index', function() use ($app) {
	$base_jobs = array(
		array('name' => '戰士', 'image_boy' => 'warrior_boy.gif', 'image_girl' => 'warrior_girl.gif', 'price' => 2000),
		array('name' => '法師', 'image_boy' => 'socerer_boy.gif', 'image_girl' => 'socerer_girl.gif', 'price' => 2000),
		array('name' => '牧師', 'image_boy' => 'pastor_boy.gif',  'image_girl' => 'pastor_girl.gif',  'price' => 2500),
		array('name' => '獵人', 'image_boy' => 'hunter_boy.gif',  'image_girl' => 'hunter_girl.gif',  'price' => 4000),
	);

	$app->render('recruit/index.html', array(
		'base_jobs' => $base_jobs
	));
})->name('recruit.index');
