<?php
    
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class Check_session {
        
        var $ci;
        
        public function __construct() {
            $this->ci =& get_instance();
            
            return;
        }
        
        public function check_session() {
            /*if($this->ci->session->userdata('logged') == false) {
                App:index();
            }*/
        }
        
    }
    
?>