<?php

namespace Kanatraining;

class User {
    private int $_id;
    private string $_name;
    private int $_viewer;
    private string $_type;
    private string $_email;
    private string $_avatar;
    private string $_best;

    //Constructeur
    public function __construct($id, $name, $viewer, $type, $email, $profileImage, $best) {
        $this->_id     = intval($id);
        $this->_name   = $name;
        $this->_viewer = intval($viewer);
        $this->_type   = $type;
        $this->_email  = $email;
        $this->_avatar = $profileImage;
        $this->_best   = $best;
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
;