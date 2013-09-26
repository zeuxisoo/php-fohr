<?php
if (defined("IN_APPS") === false) exit("Access Dead");

use Zeuxisoo\Core\Validator;

use App\Model\User;
use App\Helper\Common;
use App\Helper\Login;

$app->get('/', function() use ($app) {
	$auth_token = $app->getCookie('auth_token');

	if (empty($_SESSION['user']['email']) === false || empty($auth_token) === false) {
		$app->redirect($app->urlFor('home.index'));
	}else{
		$app->render('index.html');
	}
})->name('index.index');

$app->map('/signup', function() use ($app) {
	if ($app->request->isPost() === true) {
		$email    = $app->request->post('email');
		$password = $app->request->post('password');

		$validator = Validator::factory($_POST);
		$validator->add('email', '請輸入你的電郵地址')->rule('required')
				  ->add('password', '請輸入你的密碼')->rule('required')
				  ->add('email', '電郵地址格式不正確')->rule('valid_email')
				  ->add('password', '密碼必須 8 位以上')->rule('min_length', 8);

		$valid_type    = 'error';
		$valid_message = '';

		if ($validator->inValid() === true) {
			$valid_message = $validator->first_error();
		}else if (User::exists_email($email) === true) {
			$valid_message = "電郵地址已被註冊";
		}else{
			$config = $app->config('app.config');

			User::create(array(
				'email'    => $email,
				'password' => password_hash($password, PASSWORD_BCRYPT),
				'money'    => $config['game']['start_money'],
				'time'     => $config['game']['start_time']
			));

			$valid_type    = "success";
			$valid_message = "註冊成功";
		}

		$app->flash($valid_type, $valid_message);
		$app->redirect($app->urlFor('index.signup'));
	}else{
		$app->render('index/signup.html');
	}
})->name('index.signup')->via('GET', 'POST');

$app->post('/signin', function() use ($app) {
	$email    = $app->request->post('email');
	$password = $app->request->post('password');
	$remember = $app->request->post('remember');

	$validator = Validator::factory($_POST);
	$validator->add('email', '請輸入你的電郵地址')->rule('required')
			  ->add('password', '請輸入你的密碼')->rule('required')
			  ->add('email', '電郵地址格式不正確')->rule('valid_email');

	$valid_type     = 'error';
	$valid_message  = '';
	$valid_redirect = 'index.index';

	if ($validator->inValid() === true) {
		$valid_message = $validator->first_error();
	}else{
		$user   = User::find_by_email($email);
		$config = $app->config('app.config');

		if (empty($user) === true)  {
			$valid_message = "找不到此帳號";
		}else if (password_verify($password, $user->password) === false) {
			$valid_message = "密碼不正確";
		}else{
			if ($remember === 'y') {
				$signin_token = hash('sha256', mt_rand().Common::random_string(8));

				// Update user sign in token
				$user->signin_token = $signin_token;
				$user->save();

				// Make auth token to cookie for remember
				$app->setCookie(
					'auth_token',
					Login::create_key($user->id, $signin_token, $config['cookie']['secret_key']),
					time() + $config['cookie']['life_time']
				);
			}

			$_SESSION['user'] = array(
				'id' => $user->id,
				'email' => $user->email
			);

			$valid_type     = "success";
			$valid_message  = "登入成功";
			$valid_redirect = "home.index";
		}
	}

	$app->flash($valid_type, $valid_message);
	$app->redirect($app->urlFor($valid_redirect));
})->name('index.signin');

$app->get('/signout', function() use ($app) {
	unset($_SESSION['user']);

	$app->deleteCookie('auth_token');
	$app->redirect($app->urlFor('index.index'));
})->name('index.signout');
