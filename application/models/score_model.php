<?php
    
    class Score_model extends CI_Model {
        
        public function __construct() {
            parent::__construct();
        }
        
        public function register_score($data) {
            echo 'printr';
            print_r($data);
            $this->db->insert('scores', $data);
        }
        
        public function get_top_week() {
            $query = $this->db->query('SELECT SUM(score) FROM scores GROUP BY user_id ORDER BY WEEK(created)');
            
            print_r($query->result());
        }
        
    }
    
?>