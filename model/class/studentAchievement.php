<?php

namespace Kanatraining;

class studentAchievement
{
    private int $_ID;
    private int $_StudentID;
    private int $_AchievementID;

    //Constructeur
    public function __construct($id, $StudentId, $AchievementId)
    {
        $this->_ID = intval($id);
        $this->_StudentID = intval($StudentId);
        $this->_AchievementID = intval($AchievementId);
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