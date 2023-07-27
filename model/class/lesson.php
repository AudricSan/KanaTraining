<?php

namespace Kanatraining;

class Lesson {
    private int $_ID;
    private string $_Name;
    private string $_Description;

    //Constructeur
    public function __construct($id, $name, $description) {
        $this->_ID        = intval($id);
        $this->_Name      = $name;
        $this->_Description = $description;
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