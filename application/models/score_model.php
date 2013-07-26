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
            $query = $this->db->query('SELECT user_id, SUM(score) as score FROM scores GROUP BY user_id, WEEK(created) ORDER BY WEEK(created), score LIMIT 7');
            
            print_r($query->result());
        }
        
    }
    
?>