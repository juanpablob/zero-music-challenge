<?php
    
    class Score_model extends CI_Model {
        
        public function __construct() {
            parent::__construct();
        }
        
        public function register_score($data) {
            $this->db->insert('scores', $data);
        }
        
        public function get_top_week() {
            $this->db->select('(SELECT SUM(score) FROM scores ORDER BY WEEK(created), user_id, GROUP BY user_id, WEEK(created)'), false)
            $query = $this->db->get('scores');
            
            print_r($query);
        }
        
    }
    
?>