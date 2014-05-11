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
}
