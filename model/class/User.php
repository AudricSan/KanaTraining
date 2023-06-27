<?php

namespace Kanatraining;

class User {
    private int $_id;
    private string $_displayName;
    private string $_avatar;
    
    //Constructeur
    public function __construct($Cid, $Cname, $Cavatar) {
        $this->_id          = intval($Cid);
        $this->_displayName = $Cname;
        $this->_avatar      = $Cavatar;
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