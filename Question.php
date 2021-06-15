<?php
 class Question
 {
   public $name;
   public $label = array();
   public $count = array();
   public $strValue = array();

   function set_name($name)
   {
     $this->name = $name;
   }

   function get_name()
   {
     return $this->name;
   }

   function get_label()
   {
     return $this->label;
   }

   function get_count()
   {
     return $this->count;
   }

   function get_strValue()
   {
     return $this->strValue;
   }

  }
?>
