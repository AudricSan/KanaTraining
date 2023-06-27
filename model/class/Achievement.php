<?php

namespace Kanatraining;

class Achievement {
    private int $_id;
    private string $_Name;
    private string $_Icon;
    private string $_Condition;

    //Constructeur
    public function __construct($Cid, $Cname, $Cicon, $Ccond) {
        $this->_id        = intval($Cid);
        $this->_Name      = $Cname;
        $this->_Icon      = $Cicon;
        $this->_Condition = $Ccond;
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