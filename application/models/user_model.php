<?php
    
    class User_model extends CI_Model {
        
        public function __construct() {
            parent::__construct();
        }
        
        public function save($data) {
            $query = $this->db->get_where('users', array('fb_uid' => $data['fb_uid']));
            $count = $query->num_rows();
            
            if($count === 0) {
                $this->db->insert('users', $data);
                
                return $this->db->insert_id();
            }
            else {
                foreach($query->result() as $row) {
                    return $row->id;
                }
            }
        }
        
        public function get($user_id) {
            $query = $this->db->get_where('users', array('id' => $user_id));
            $query = $query->result();
            
            return $query;
        }
        
    }
    
?>