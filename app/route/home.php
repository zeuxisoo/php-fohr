<?php
if (defined("IN_APPS") === false) exit("Access Dead");

use Zeuxisoo\Core\Validator;

use App\Middleware\Route;
use App\Model\User;
use App\Model\TeamMember;

$app->get('/home/index', Route::require_login(), function() use ($app) {
	$user = User::get($_SESSION['user']['id']);

	if (empty($user->team_name) === true) {
		$app->render('home/first.html');
	}else{
		$team_members = TeamMember::find_by_user_id($_SESSION['user']['id']);

		$app->render('home/index.html', array(
			'team_members' => $team_members
		));
	}
})->name('home.index');

$app->post('/home/first', Route::require_login(), function() use ($app) {
	$team_name      = $app->request->post('team_name');
	$character_name = $app->request->post('character_name');
	$character_job  = $app->request->post('character_job');

	$validator = Validator::factory($_POST);
	$validator->add('team_name', '請輸入隊伍名稱')->rule('required')
			  ->add('character_name', '請輸入隊員名稱')->rule('required')
			  ->add('character_job', '請選擇隊員職業')->rule('required')
			  ->add('team_name', '隊伍名稱只能在 30 個字元以內')->rule('max_length', 30)
			  ->add('character_name', '隊員名稱只能在 30 個字元以內')->rule('max_length', 30)
			  ->add('character_job', '無法識別隊員職業格式')->rule('custom', function($format) {
			  		return preg_match("/^\d_\d$/", $format) == true;
			  });

	$valid_type    = 'error';
	$valid_message = '';

	if ($validator->inValid() === true) {
		$valid_message = $validator->first_error();
	}else if (User::exists_team_name($team_name) === true) {
		$valid_message = '此隊伍名稱已存在';
	}else if (TeamMember::exists_character_name($character_name) === true) {
		$valid_message = '此角色名稱已經存在';
	}else{
		list($job_id, $character_gender) = explode("_", $character_job);

		if (in_array($job_id, array(1, 2)) === false) {
			$valid_message = '無法識別隊員職業';
		}elseif (in_array($character_gender, array(1, 2)) === false) {
			$valid_message = '無法識別隊員性別';
		}else{
			$user = User::get($_SESSION['user']['id']);
			$user->team_name = $team_name;
			$user->save();

			TeamMember::create(array(
				'user_id'          => $user->id,
				'job_id'           => $job_id,
				'character_name'   => $character_name,
				'character_gender' => $character_gender,
			));

			$valid_type    = "success";
			$valid_message = "初始化隊伍完成";
		}
	}

	$app->flash($valid_type, $valid_message);
	$app->redirect($app->urlFor('home.index'));
})->name('home.first');
