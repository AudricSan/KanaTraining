<?php

namespace Kanatraining;

class studentLesson
{
    private int $_ID;
    private int $_StudentID;
    private int $_LessonID;

    //Constructeur
    public function __construct($id, $StudentId, $LessonId)
    {
        $this->_ID = intval($id);
        $this->_StudentID = intval($StudentId);
        $this->_LessonID = intval($LessonId);
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
