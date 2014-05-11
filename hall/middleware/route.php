<?php
namespace Hall\Middleware;

use Slim\Slim;

use Hall\Helper\Login as LoginHelper;
use Hall\Helper\User as UserHelper;

class Route {
	public static function requireLogin() {
		return function() {
			$app            = Slim::getInstance();
			$valid_type     = 'error';
			$valid_message  = '';
			$valid_redirect = '';
			$auth_token     = $app->getCookie('auth_token');

			if (empty($_SESSION['user']) === false && empty($_SESSION['user']['id']) === false) {
				$user    = \Model::factory('User')->findOne(hexdec($_SESSION['user']['id']));
				$config  = $app->config('app.config');

				if (empty($user) === true) {
					$valid_message = '找不到這個用戶';
				}else{
					UserHelper::initSession($user);

					$valid_type = "success";
				}
			}elseif (isset($auth_token) === true && empty($auth_token) === false) {
				list($user_id, $signin_token, $auth_key) = explode(":", LoginHelper::makeAuth($auth_token, "DECODE"));

				$user    = \Model::factory('User')->findOne(hexdec($user_id));
				$config  = $app->config('app.config');

				if (empty($user) === true) {
					$valid_message = '找不到這個用戶';
				}else if (hash('sha256', $user_id.$signin_token.$config['cookie']['secret_key']) !== $auth_key) {
					$valid_message = '無法識別用戶身份';
				}else{
					UserHelper::initSession($user);

					$valid_type     = "success";
					$valid_redirect = $app->router()->getCurrentRoute()->getName();
				}
			}else{
				$valid_message = "請先登入";
			}

			switch($valid_type) {
				case "error":
					if (empty($_SESSION['user']) === false) {
						unset($_SESSION['user']);
						$app->deleteCookie('auth_token');
					}

					$app->flash($valid_type, $valid_message);
					$app->redirect($app->urlFor('index.index'));
					break;
				default:
					if (empty($valid_redirect) === false) {
						$app->redirect($app->urlFor($valid_redirect));
					}
					break;
			}
		};
	}
}
