<?php
    
    class User_model extends CI_Model {
        
        public function __construct() {
            parent::__construct();
        }
        
        public function get_user($key = array()) {
            $query = $this->db->get_where('users', $key);
            $count = $query->num_rows();
            
            if($count > 0) {
                foreach($query->result() as $row) {
                    return $row;
                    //print_r($row);
                }
            }
        }
        
        public function save_user($data) {
            $this->db->insert('users', $data);
            
            return $this->db->insert_id();
        }
        
        public function update_user($user_id, $data = array()) {
            $this->db->where('id', $user_id);
            $this->db->update('users', $data);
        }
        
        public function check_user($data) {
            $query = $this->db->get_where('users', array('fb_uid' => $data['fb_uid']));
            $count = $query->num_rows();
            
            if($count > 0) {
                return $this->get_user(array('fb_uid' => $data['fb_uid']));
            }
            else {
                $saved_user_id = $this->save_user($data);
                
                return $this->get_user(array('id' => $saved_user_id));
            }
        }
        
        public function get_top_users() {
            $this->db->select('firstname, lastname, score');
            $this->db->limit(7);
            $this->db->order_by('score', 'desc');
            
            $top_users = $this->db->get('users');
            
            return $top_users->result();
        }
        
    }
    
?>