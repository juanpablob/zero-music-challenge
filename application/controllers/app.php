<?php
    
    class App extends CI_Controller {
        
        /*
        | Constructor
        |-------------------------------------------
        */
        public function __construct() {
            parent::__construct();
            
            /* Models */
            $this->load->model('User_model', 'user_model', true);
            $this->load->model('Song_model', 'song_model', true);
            
            /* Configuration */
            $this->config->load('site', true);
            
            /* Libraries */
            $this->load->library('session');
            $this->load->library('facebook');
            
            /* Helpers */
            $this->load->helper('url');
            $this->load->helper('functions');
            
            /* Facebook */
            if(ENVIRONMENT == 'development') {
                $this->session->userdata['signed_request']['oauth_token'] = '00';
            }
            elseif(ENVIRONMENT == 'testing' || ENVIRONMENT == 'production') {
                if(isset($_POST['signed_request'])) {
                    $this->session->set_userdata('signed_request', $this->facebook->parseSignedRequest($_POST['signed_request'], $this->config->item('secret', 'facebook')));
                }
            }
            
            /* Etc */
            parse_str($_SERVER['QUERY_STRING'], $_REQUEST);
        }
        
        /*
        | Index
        |-------------------------------------------
        */
        public function index() {
            $this->step01();
        }
        
        /*
        | No-Fan
        |-------------------------------------------
        */
        public function no_fan() {
            $data = array(
                'page_title'            => 'Bienvenido',
                'site_name'             => $this->config->item('site_name', 'site'),
                'google_analytics_id'   => $this->config->item('google_analytics_id', 'site')
            );
            
            // Load view
            $this->load->view('layouts/header', $data);
            $this->load->view('nofan.php', $data);
            $this->load->view('layouts/footer', $data);
        }
        
        /*
        | Step01 — Welcome
        |-------------------------------------------
        */
        public function step01() {
            $data = array(
                'page_title'            => 'Bienvenido',
                'site_name'             => $this->config->item('site_name', 'site'),
                'google_analytics_id'   => $this->config->item('google_analytics_id', 'site')
            );
            
            // Load view
            $this->load->view('layouts/header', $data);
            $this->load->view('step01.php', $data);
            $this->load->view('layouts/footer', $data);
        }
        
        /*
        | Step02 — Instructions
        |-------------------------------------------
        */
        public function step02() {
            $data = array(
                'page_title'            => 'Instrucciones',
                'site_name'             => $this->config->item('site_name', 'site'),
                'google_analytics_id'   => $this->config->item('google_analytics_id', 'site')
            );
            
            // Load view
            $this->load->view('layouts/header', $data);
            $this->load->view('step02.php', $data);
            $this->load->view('layouts/footer', $data);
        }
        
        /*
        | Step03 — Game
        |-------------------------------------------
        */
        public function step03() {
            $data = array(
                'page_title'            => 'A jugar!',
                'site_name'             => $this->config->item('site_name', 'site'),
                'google_analytics_id'   => $this->config->item('google_analytics_id', 'site')
            );
            
            // Load view
            $this->load->view('layouts/header', $data);
            $this->load->view('step03.php', $data);
            $this->load->view('layouts/footer', $data);
        }
        
        /*
        | Step04 — Game
        |-------------------------------------------
        */
        public function step04() {
            $data = array(
                'page_title'            => 'Muy bien!',
                'site_name'             => $this->config->item('site_name', 'site'),
                'google_analytics_id'   => $this->config->item('google_analytics_id', 'site')
            );
            
            // Load view
            $this->load->view('layouts/header', $data);
            $this->load->view('step04.php', $data);
            $this->load->view('layouts/footer', $data);
        }
        
        /*
        | Ranking
        |-------------------------------------------
        */
        public function ranking() {
            $data = array(
                'page_title'            => 'Ranking',
                'site_name'             => $this->config->item('site_name', 'site'),
                'google_analytics_id'   => $this->config->item('google_analytics_id', 'site')
            );
            
            // Load view
            $this->load->view('layouts/header', $data);
            $this->load->view('ranking.php', $data);
            $this->load->view('layouts/footer', $data);
        }
        
        /*
        | Trivia
        |-------------------------------------------
        */
        public function trivia() {
            $trivia = array();
            
            $options = $this->song_model->get_answers();
            
            $options['answers'] = array_chunk($options['answers'], (int)ceil(count($options['answers']) / 4));
            
            $s = 0;
            $x = 1;
            
            foreach($options['songs'] as $song) {
                $trivia[$s]['answers'][0]['id'] = $song->id;
                $trivia[$s]['answers'][0]['title'] = $song->title;
                $trivia[$s]['answers'][0]['filename'] = $song->filename;
                
                foreach($options['answers'][$s] as $answer) {
                    $trivia[$s]['answers'][$x]['id'] = $answer->id;
                    $trivia[$s]['answers'][$x]['title'] = $answer->title;
                    $trivia[$s]['answers'][$x]['filename'] = $answer->filename;
                    
                    $x++;
                }
                
                $trivia[$s]['correct']['id'] = $song->id;
                $trivia[$s]['correct']['title'] = $song->title;
                $trivia[$s]['correct']['filename'] = $song->filename;
                
                $s++;
            }
            
            shuffle($trivia[0]['answers']);
            shuffle($trivia[1]['answers']);
            shuffle($trivia[2]['answers']);
            shuffle($trivia[3]['answers']);
            
            echo json_encode($trivia);
        }
        
    }
    
?>