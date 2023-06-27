<?php

namespace Kanatraining;

class UserScore {
    private int $_id;
    private string $_User;
    private string $_Dificulty;
    private string $_ScoreHighest;

    //Constructeur
    public function __construct($Cid, $CUser, $CDificulty, $CScoreHighest) {
        $this->_id           = intval($Cid);
        $this->_User         = $CUser;
        $this->_Dificulty    = $CDificulty;
        $this->_ScoreHighest = $CScoreHighest;
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