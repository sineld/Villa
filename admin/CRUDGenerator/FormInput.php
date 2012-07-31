<?php

class FormInput {
       public $type;
       public $name;
       public $display;

       protected $typeMap = array(
                'integer'=>'text',
                'string'=>'text',
                'datetime'=>'text',
                'timestamp'=>'text',
                'text'=>'textarea'
       );

        public function __construct($type, $name, $displayName=null) {
                $this->name = $name;
                if ($displayName) {
                        $this->display = $displayName;
                        $this->type = $this->typeMap[$type];
                        return;
                }

                $this->type = $type;
        }

        public function render($default='') {
                switch ($this->type) {
                       case 'text':
                                return "<p><b>{$this->display}</b></p>\n\t<input type='text' name='{$this->name}' value='".$default."' />\n\n";
						break;

                        case 'textarea':
                                return "<p><b>{$this->display}</b></p>\n\t<textarea name='{$this->name}'>".$default."</textarea>\n\n";
                        break;

                        case 'hidden':
                                return "<input type='hidden' name='{$this->name}' value='".$default."' />\n\n";
                        break;
                }
        }
}

?>