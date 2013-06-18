<?php
    
    class Song_model extends CI_Model {
        
        public function __construct() {
            parent::__construct();
        }
        
        public function get_songs() {
            $this->db->select('id, title, filename');
            $this->db->limit(4);
            $this->db->order_by('id', 'random');
            
            $trivia_songs = $this->db->get('songs');
            
            return $trivia_songs->result();
        }
        
        public function get_answers() {
            $songs = $this->get_songs();
            $songs_id = array(
                0   => $songs[0]->id,
                1   => $songs[1]->id,
                2   => $songs[2]->id,
                3   => $songs[3]->id
            );
            
            $this->db->select('id, title, filename');
            $this->db->where_not_in('id', $songs_id);
            $this->db->limit(12);
            $this->db->order_by('id', 'random');
            
            $trivia_answers = $this->db->get('songs');
            
            $result = array(
                'songs'     => $songs,
                'answers'   => $trivia_answers->result()
            );
            
            return $result;
        }
        
    }
    
?>