<?php
namespace Hall\Controller;

use Model;
use Zeuxisoo\Core\Validator;
use Hall\Helper\Controller;
use Hall\Helper\Secure;

class Index extends Controller {

    public function index() {
        $auth_token = $this->slim->getCookie('auth_token');

        if (empty($_SESSION['user']['email']) === false || empty($auth_token) === false) {
            $this->slim->redirect($slim->urlFor('home.index'));
        }else{
            $this->slim->render('index.html');
        }
    }

    public function signup() {
        if ($this->slim->request->isPost() === true) {
            $email    = $this->slim->request->post('email');
            $password = $this->slim->request->post('password');

            $validator = Validator::factory($_POST);
            $validator->add('email', '請輸入你的電郵地址')->rule('required')
                      ->add('password', '請輸入你的密碼')->rule('required')
                      ->add('email', '電郵地址格式不正確')->rule('valid_email')
                      ->add('password', '密碼必須 8 位以上')->rule('min_length', 8);

            $valid_type    = 'error';
            $valid_message = '';

            if ($validator->inValid() === true) {
                $valid_message = $validator->first_error();
            }else if (Model::factory('User')->filter('findByEmail', $email)->count() >= 1) {
                $valid_message = "電郵地址已被註冊";
            }else{
                $config = $this->slim->config('app.config');

                Model::factory('User')->create(array(
                    'email'     => $email,
                    'password'  => password_hash($password, PASSWORD_BCRYPT),
                    'money'     => $config['game']['start_money'],
                    'time'      => $config['game']['start_time'],
                    'create_at' => time()
                ))->save();

                $valid_type    = "success";
                $valid_message = "註冊成功";
            }

            $this->slim->flash($valid_type, $valid_message);
            $this->slim->redirect($this->slim->urlFor('index.signup'));
        }else{
            $this->slim->render('index/signup.html');
        }
    }

    public function signin() {
        $email    = $this->slim->request->post('email');
        $password = $this->slim->request->post('password');
        $remember = $this->slim->request->post('remember');

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
            $user   = Model::factory('User')->filter('findByEmail', $email)->findOne();
            $config = $this->slim->config('app.config');

            if (empty($user) === true)  {
                $valid_message = "找不到此帳號";
            }else if (password_verify($password, $user->password) === false) {
                $valid_message = "密碼不正確";
            }else{
                if ($remember === 'y') {
                    $signin_token = hash('sha256', mt_rand().Secure::randomString(8));

                    // Update user sign in token
                    $user->signin_token = $signin_token;
                    $user->update_at    = time();
                    $user->save();

                    // Make auth token to cookie for remember
                    $this->slim->setCookie(
                        'auth_token',
                        Secure::createKey($user->id, $signin_token, $config['cookie']['secret_key']),
                        time() + $config['cookie']['life_time']
                    );
                }

                // Initial user session
                $user->initSession();

                $valid_type     = "success";
                $valid_message  = "登入成功";
                $valid_redirect = "home.index";
            }
        }

        $this->slim->flash($valid_type, $valid_message);
        $this->slim->redirect($this->slim->urlFor($valid_redirect));
    }

    public function signout() {
        unset($_SESSION['user']);

        $this->slim->deleteCookie('auth_token');
        $this->slim->redirect($this->slim->urlFor('index.index'));
    }
}
