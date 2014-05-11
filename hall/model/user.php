<?php
namespace Hall\Model;

class User extends \Model {
    public static $_table = 'user';

    // Association
    public function team_members() {
        return $this->has_many('TeamMember');
    }

    // Filter
    public static function findByEmail($orm, $email) {
        return $orm->where_equal('email', $email);
    }

    public static function findByTeamName($orm, $team_name) {
        return $orm->where_equal('team_name', $team_name);
    }

    // Extra
    public function initSession() {
        $_SESSION['user'] = array(
            'id'        => $this->id,
            'email'     => $this->email,
            'team_name' => $this->team_name,
            'money'     => $this->money,
            'time'      => $this->time,
        );
    }

    public function takeMoney($money) {
        $this->money -= $money;
        $this->save();

        $this->updateMoneyInSession($this->money);
    }

    public function updateMoneyInSession($value) {
        $_SESSION['user']['money'] = $value;
    }
}
