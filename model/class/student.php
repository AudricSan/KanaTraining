<?php

namespace Kanatraining;

class Student
{
    private int $_ID;
    private string $_Name;
    private string $_Avatar;
    private string $_RegisterDate;
    private int $_GlobalXp;
    private int $_StreakDays;
    private int $_HighScore;
    private int $_LastScore;

    //Constructeur
    public function __construct($id, $name, $avatar, $registerDate, $globalXp, $streakDays, $highScore, $lastScore)
    {
        $this->_ID           = intval($id);
        $this->_Name         = $name;
        $this->_Avatar       = $avatar;
        $this->_RegisterDate = $registerDate;
        $this->_GlobalXp     = $globalXp;
        $this->_StreakDays   = $streakDays;
        $this->_HighScore    = $highScore;
        $this->_LastScore    = $lastScore;
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