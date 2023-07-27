<?php

namespace Kanatraining;

class Achievement
{
    private int $_ID;
    private string $_Name;
    private string $_Icon;
    private string $_Description;
    private string $_Condition;

    //Constructeur
    public function __construct($id, $name, $icon, $description, $cond)
    {
        $this->_ID          = intval($id);
        $this->_Name        = $name;
        $this->_Icon        = $icon;
        $this->_Description = $description;
        $this->_Condition   = $cond;
    }

    //SUPER SETTER
    public function __set($prop, $value)
    {
        if (property_exists($this, $prop)) {
            return $this->$prop = $value;
        }
    }

    //SUPER GETTER
    public function __get($prop)
    {
        if (property_exists($this, $prop)) {
            return $this->$prop;
        }
    }
}