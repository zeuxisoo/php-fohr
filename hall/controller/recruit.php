<?php
namespace Hall\Controller;

use Model;
use Zeuxisoo\Core\Validator;
use Hall\Helper\Controller;

class Recruit extends Controller {

    public function index() {
        $base_jobs = array(
            1 => array('name' => '戰士', 'image_boy' => 'warrior_boy.gif', 'image_girl' => 'warrior_girl.gif', 'money' => 2000),
            2 => array('name' => '法師', 'image_boy' => 'socerer_boy.gif', 'image_girl' => 'socerer_girl.gif', 'money' => 2000),
            3 => array('name' => '牧師', 'image_boy' => 'pastor_boy.gif',  'image_girl' => 'pastor_girl.gif',  'money' => 2500),
            4 => array('name' => '獵人', 'image_boy' => 'hunter_boy.gif',  'image_girl' => 'hunter_girl.gif',  'money' => 4000),
        );

        if ($this->slim->request->isPost() === true) {
            $character_job    = $this->slim->request->post("character_job");
            $character_name   = $this->slim->request->post("character_name");
            $character_gender = $this->slim->request->post("character_gender");

            $validator = Validator::factory($_POST);
            $validator->add('character_job', '請輸入隊員職業')->rule('required')
                      ->add('character_name', '請輸入隊員名稱')->rule('required')
                      ->add('character_gender', '請選擇隊員性別')->rule('required')
                      ->add('character_name', '隊員名稱只能在 30 個字元以內')->rule('max_length', 30);

            $valid_type    = 'error';
            $valid_message = '';

            if ($validator->inValid() === true) {
                $valid_message = $validator->first_error();
            }else if (Model::factory('TeamMember')->filter('findByCharacterName', $character_name)->count() >= 1) {
                $valid_message = '此隊員名稱已經存在';
            }else if (in_array($character_job, array(1, 2, 3, 4)) === false) {
                $valid_message = '無法識別隊員職業';
            }else if (in_array($character_gender, array(1, 2)) === false) {
                $valid_message = '無法識別隊員性別';
            }else{
                $user          = Model::factory('User')->findOne($_SESSION['user']['id']);
                $recruit_money = $base_jobs[$character_job]['money'];

                if ($user->money < $recruit_money) {
                    $valid_message = '金錢不足';
                }else{
                    $user->takeMoney($recruit_money);

                    Model::factory('TeamMember')->create(array(
                        'user_id'          => $user->id,
                        'job_id'           => $character_job,
                        'character_name'   => $character_name,
                        'character_gender' => $character_gender,
                    ))->save();

                    $valid_type    = 'success';
                    $valid_message = '聘請完成';
                }
            }

            $this->slim->flash($valid_type, $valid_message);
            $this->slim->redirect($this->slim->urlFor('recruit.index'));
        }else{
            $this->slim->render('recruit/index.html', array(
                'base_jobs' => $base_jobs
            ));
        }
    }

}
