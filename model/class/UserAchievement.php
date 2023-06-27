<?php

namespace Kanatraining;

class UserAchievement {
    private int $_id;
    private int $_User;
    private int $_Achievement;

    //Constructeur
    public function __construct($Cid, $CUser, $CAchievement) {
        $this->_id          = intval($Cid);
        $this->_User        = intval($CUser);
        $this->_Achievement = intval($CAchievement);
    }

    //SUPER SETTER
    public function __set($prop, $value) {
        if (property_exists($this, $prop)) {
            return $this->$prop = $value;
        }
    }

    //SUPER GETTER
    public function __get($prop) {
        if (property_exists($this, $prop)) {
            return $this->$prop;
        }
    }
}