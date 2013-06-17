<?php
    
    function shuffle_with_keys(&$array) {
        $aux = array();
        $keys = array_keys($array); 
        
    	shuffle($keys);
        
        foreach($keys as $key) {
            $aux[$key] = $array[$key];
            
            unset($array[$key]); 
    	}
        
    	$array = $aux;
    }
    
?>