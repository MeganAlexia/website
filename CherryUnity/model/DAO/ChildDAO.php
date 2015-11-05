<?php


class ChildDAO extends UserDAO {
    
    public function __construct ($client) {
        parent::__construct($client);
    }
    
    protected function getArrayWithUserData ($child) {
        $array = parent::getArrayWithUserData($child);
        $array['teachingContent'] = array ('L' => $child.getTeachingContent()); 
        return $array;
    } 
    
    protected function getUser () {
        return new Child();
    }
    
    protected function fillUserAttributes ($childDTO, $child) {
        parent::fillUserAttributes($childDTO, $child);
        $user.setTeachingContent($childDTO['Item']['teachingContent']['L']);
    }
}