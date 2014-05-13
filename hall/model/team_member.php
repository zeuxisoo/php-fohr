<?php
namespace Hall\Model;

class TeamMember extends \Model {
    public static $_table = 'team_member';

    // Association
    public function user() {
        return $this->belongs_to('User');
    }

    // Filter
    public static function findByCharacterName($orm, $character_name) {
        return $orm->where_equal('character_name', $character_name);
    }
}
