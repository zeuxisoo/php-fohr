<?php
namespace Hall\Controller;

use Model;
use Zeuxisoo\Core\Validator;
use Hall\Base\Controller;

class Home extends Controller {

    public function index() {
        $user = Model::factory('User')->findOne($_SESSION['user']['id']);

        if (empty($user->team_name) === true) {
            $this->slim->render('home/first.html');
        }else{
            $team_members = Model::factory('TeamMember')->where_equal('user_id', $_SESSION['user']['id'])->findMany();

            $this->slim->render('home/index.html', array(
                'team_members' => $team_members
            ));
        }
    }

    public function first() {
        $team_name      = $this->slim->request->post('team_name');
        $character_name = $this->slim->request->post('character_name');
        $character_job  = $this->slim->request->post('character_job');

        $validator = Validator::factory($_POST);
        $validator->add('team_name', '請輸入隊伍名稱')->rule('required')
                  ->add('character_name', '請輸入隊員名稱')->rule('required')
                  ->add('character_job', '請選擇隊員職業')->rule('required')
                  ->add('team_name', '隊伍名稱只能在 30 個字元以內')->rule('max_length', 30)
                  ->add('character_name', '隊員名稱只能在 30 個字元以內')->rule('max_length', 30)
                  ->add('character_job', '無法識別隊員職業格式')->rule('custom', function($format) {
                        return preg_match("/^([A-Za-z]+)_(boy|girl)$/", $format) == true;
                  });

        $valid_type    = 'error';
        $valid_message = '';

        if ($validator->inValid() === true) {
            $valid_message = $validator->firstError();
        }else if (Model::factory('User')->filter('findByTeamName', $team_name)->count() >= 1) {
            $valid_message = '此隊伍名稱已存在';
        }else if (Model::factory('TeamMember')->filter('findByCharacterName', $character_name)->count() >= 1) {
            $valid_message = '此隊員名稱已經存在';
        }else{
            list($job_name, $character_gender) = explode("_", $character_job);

            $job_name         = strtolower($job_name);
            $character_gender = strtolower($character_gender);

            if (in_array($job_name, array('warrior', 'socerer')) === false) {
                $valid_message = '無法識別隊員職業';
            }elseif (in_array($character_gender, array('boy', 'girl')) === false) {
                $valid_message = '無法識別隊員性別';
            }else{
                $user = Model::factory('User')->findOne($_SESSION['user']['id']);
                $user->team_name = $team_name;
                $user->save();

                Model::factory('TeamMember')->create(array(
                    'user_id'          => $user->id,
                    'job_name'         => $job_name,
                    'character_name'   => $character_name,
                    'character_gender' => $character_gender,
                ))->save();

                $valid_type    = "success";
                $valid_message = "初始化隊伍完成";
            }
        }

        $this->slim->flash($valid_type, $valid_message);
        $this->slim->redirect($this->slim->urlFor('home.index'));
    }

}
