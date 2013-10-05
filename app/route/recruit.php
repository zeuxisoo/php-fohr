<?php
if (defined("IN_APPS") === false) exit("Access Dead");

use Zeuxisoo\Core\Validator;

use App\Middleware\Route;
use App\Model\User;
use App\Model\TeamMember;
use App\Helper\User as UserHelper;

$base_jobs = array(
	1 => array('name' => '戰士', 'image_boy' => 'warrior_boy.gif', 'image_girl' => 'warrior_girl.gif', 'money' => 2000),
	2 => array('name' => '法師', 'image_boy' => 'socerer_boy.gif', 'image_girl' => 'socerer_girl.gif', 'money' => 2000),
	3 => array('name' => '牧師', 'image_boy' => 'pastor_boy.gif',  'image_girl' => 'pastor_girl.gif',  'money' => 2500),
	4 => array('name' => '獵人', 'image_boy' => 'hunter_boy.gif',  'image_girl' => 'hunter_girl.gif',  'money' => 4000),
);

$app->get('/recruit/index', Route::requireLogin(), function() use ($app, $base_jobs) {
	$app->render('recruit/index.html', array(
		'base_jobs' => $base_jobs
	));
})->name('recruit.index');

$app->post('/recruit/index', Route::requireLogin(), function() use ($app, $base_jobs) {
	$character_job    = $app->request->post("character_job");
	$character_name   = $app->request->post("character_name");
	$character_gender = $app->request->post("character_gender");

	$validator = Validator::factory($_POST);
	$validator->add('character_job', '請輸入隊員職業')->rule('required')
			  ->add('character_name', '請輸入隊員名稱')->rule('required')
			  ->add('character_gender', '請選擇隊員性別')->rule('required')
			  ->add('character_name', '隊員名稱只能在 30 個字元以內')->rule('max_length', 30);

	$valid_type    = 'error';
	$valid_message = '';

	if ($validator->inValid() === true) {
		$valid_message = $validator->first_error();
	}else if (TeamMember::existsCharacterName($character_name) === true) {
		$valid_message = '此隊員名稱已經存在';
	}else if (in_array($character_job, array(1, 2, 3, 4)) === false) {
		$valid_message = '無法識別隊員職業';
	}else if (in_array($character_gender, array(1, 2)) === false) {
		$valid_message = '無法識別隊員性別';
	}else{
		$user          = User::get($_SESSION['user']['id']);
		$recruit_money = $base_jobs[$character_job]['money'];

		if ($user->money < $recruit_money) {
			$valid_message = '金錢不足';
		}else{
			UserHelper::takeMoney($user, $recruit_money);

			TeamMember::create(array(
				'user_id'          => $user->id,
				'job_id'           => $character_job,
				'character_name'   => $character_name,
				'character_gender' => $character_gender,
			));

			$valid_type    = 'success';
			$valid_message = '聘請完成';
		}
	}

	$app->flash($valid_type, $valid_message);
	$app->redirect($app->urlFor('recruit.index'));
});
