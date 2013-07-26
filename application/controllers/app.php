<?php
    
    class App extends CI_Controller {
        
        /*
        | Variables
        |-------------------------------------------
        */
        public $view_data;
        
        /*
        | Constructor
        |-------------------------------------------
        */
        public function __construct() {
            parent::__construct();
            
            /* Models */
            $this->load->model('User_model', 'user_model', true);
            $this->load->model('Song_model', 'song_model', true);
            $this->load->model('Score_model', 'score_model', true);
            
            /* Configuration */
            $this->config->load('site', true);
            $this->config->load('facebook', true);
            
            /* Libraries */
            $this->load->library('session');
            $this->load->library('facebook');
            
            /* Helpers */
            $this->load->helper('url');
            $this->load->helper('functions');
            
            /* Facebook */
            if(ENVIRONMENT == 'development') {
                
            }
            elseif(ENVIRONMENT == 'testing' || ENVIRONMENT == 'production') {
                if(isset($_POST['signed_request'])) {
                    $this->session->set_userdata('signed_request', $this->facebook->parse_signed_request($_POST['signed_request'], $this->config->item('secret', 'facebook')));
                }
            }
            
            /* Default Data for Views */
            $this->view_data = array(
                'site_name'             => $this->config->item('site_name', 'site'),
                'google_analytics_id'   => $this->config->item('google_analytics_id', 'site'),
                'facebook_config'       => $this->config->item('facebook'),
                'score_scale'           => array(
                    'score_regular'     => 12,
                    'score_plus'        => 21,
                    'score_minus'       => 7
                )
            );
            
            /* Etc */
            parse_str($_SERVER['QUERY_STRING'], $_REQUEST);
        }
        
        /*
        | Index
        |-------------------------------------------
        */
        public function index() {
            redirect('/app/step01', 'refresh');
        }
        
        /*
        | No-Fan
        |-------------------------------------------
        */
        public function no_fan() {
            // Load view
            $this->load->view('layouts/header', $this->view_data);
            $this->load->view('nofan.php', $this->view_data);
            $this->load->view('layouts/footer', $this->view_data);
        }
        
        /*
        | Step01 — Welcome
        |-------------------------------------------
        */
        public function step01() {
            $signed_request = $this->session->userdata['signed_request'];
            
            if($signed_request['page']['liked'] == 1) {
                $this->view_data['page_title'] = 'Bienvenido!';
                
                // Load view
                $this->load->view('layouts/header', $this->view_data);
                $this->load->view('step01.php', $this->view_data);
                $this->load->view('layouts/footer', $this->view_data);
            }
            else {
                redirect('/app/no_fan', 'refresh');
            }
        }
        
        /*
        | Step02 — Instructions
        |-------------------------------------------
        */
        public function step02() {
            $this->check_session();
            
            $this->view_data['page_title'] = 'Instrucciones';
            
            // Load view
            $this->load->view('layouts/header', $this->view_data);
            $this->load->view('step02.php', $this->view_data);
            $this->load->view('layouts/footer', $this->view_data);
        }
        
        /*
        | Step03 — Game
        |-------------------------------------------
        */
        public function step03() {
            $this->check_session();
            
            $this->view_data['page_title'] = 'A jugar!';
            $this->view_data['user_info'] = $this->session->userdata['user_info'];
            
            // Load view
            $this->load->view('layouts/header', $this->view_data);
            $this->load->view('step03.php', $this->view_data);
            $this->load->view('layouts/footer', $this->view_data);
        }
        
        /*
        | Step04 — End
        |-------------------------------------------
        */
        public function step04() {
            $this->check_session();
            
            $this->view_data['page_title'] = 'Resultado';
            $this->view_data['user_info'] = $this->session->userdata['user_info'];
            $this->view_data['correct_answers'] = $this->session->userdata('correct_answers');;
            
            // Load view
            $this->load->view('layouts/header', $this->view_data);
            $this->load->view('step04.php', $this->view_data);
            $this->load->view('layouts/footer', $this->view_data);
        }
        
        /*
        | Ranking
        |-------------------------------------------
        */
        public function ranking() {
            $this->view_data['page_title'] = 'Ranking';
            $this->view_data['top_users'] = $this->user_model->get_top_users();
            
            // Load view
            $this->load->view('layouts/header', $this->view_data);
            $this->load->view('ranking.php', $this->view_data);
            $this->load->view('layouts/footer', $this->view_data);
        }
        
        /*
        | Trivia
        |-------------------------------------------
        */
        public function trivia() {
            $this->check_session();
            
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
            
            header('Content-Type: application/json');
            echo json_encode($trivia);
        }
        
        /*
        | Update Score
        |-------------------------------------------
        */
        public function update_score() {
            $this->check_session();
            
            // Fetch answer tracking
            $answers = array(
                'regular'   => $_POST['regular'],
                'plus'      => $_POST['plus'],
                'minus'     => $_POST['minus']
            );
            
            // Get current score
            $score = $this->session->userdata['user_info']->score;
            
            // Calculate new score
            $score = $score + $answers['regular'] * $this->view_data['score_scale']['score_regular'];
            $score = $score + $answers['plus'] * $this->view_data['score_scale']['score_plus'];
            $score = $score + $answers['minus'] * - $this->view_data['score_scale']['score_minus'];
            
            // Update new score in db
            $this->user_model->update_user($this->session->userdata['user_info']->id, array('score' => $score));
            
            // Grab new score in session
            // FIX TO UPDATE/WRITE AN ARRAY KEY IN SESSION
            $user_info = $this->session->userdata['user_info'];
            $user_info->score = $score;
            $this->session->set_userdata('user_info', $user_info);
            
            // Grab how many correct answers in session
            $this->session->set_userdata('correct_answers', 4 - $answers['minus']);
            
            // Register score instance
            $this->score_model->register_score($this->session->userdata['user_info']->id, $this->session->userdata['user_info']->score);
        }
        
        public function test() {
            $this->score_model->get_top_week();
        }
        
        /*
        | Register Score
        |-------------------------------------------
        */
        private function register_score($user_id, $score) {
            $data = array(
                'user_id' => $user_id,
                'score' => $score
            );
            
            $this->score_model->register_score($data);
        }
        
        /*
        | FB Session (Login)
        |-------------------------------------------
        */
        public function fb_session($signed_request, $access_token) {
            if(isset($signed_request) && isset($access_token)) {
                // Fetch user data
                $signed_request = $this->facebook->parse_signed_request($signed_request, $this->config->item('secret', 'facebook'));
                $access_token = $access_token;
                
                $fb_user = json_decode(file_get_contents('https://graph.facebook.com/me?access_token=' . $access_token));
                
                // Order user data
                $fb_user = array(
                    'fb_uid'            => $fb_user->id,
                    'firstname'         => $fb_user->first_name,
                    'lastname'          => $fb_user->last_name,
                    'displayname'       => $fb_user->name,
                    'username'          => (isset($fb_user->username)) ? $fb_user->username : $fb_user->id,
                    'gender'            => $fb_user->gender,
                    'birthday'          => $fb_user->birthday,
                    'location'          => (isset($fb_user->hometown)) ? $fb_user->hometown->name : '',
                    'email'             => $fb_user->email,
                    'timezone'          => $fb_user->timezone,
                    'locale'            => $fb_user->locale,
                    'verified'          => $fb_user->verified
                );
                
                // Register/Check user data in database and save in session
                $user_info = $this->user_model->check_user($fb_user);
                
                if($user_info) {
                    $this->session->set_userdata('logged', true);
                    $this->session->set_userdata('user_info', $user_info);
                }
                else {
                    $this->session->set_userdata('logged', false);
                }
                
                // JSON response
                $response = array();
                
                if($this->session->userdata('logged') === true) {
                    $response['logged'] = true;
                }
                else {
                    $response['logged'] = false;
                    $response['error'] = 'Oops! No hemos podido iniciar tu sesión debido a un problema técnico. Por favor intenta más tarde, si el problema persiste, por favor avísanos a usuarios@grupodial.cl — Gracias!';
                }
                
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        }
        
        /*
        | Check Session
        |-------------------------------------------
        */
        public function check_session() {
            if(!$this->session->userdata('logged') || $this->session->userdata('logged') == false) {
                redirect('/', 'refresh');
            }
        }
        
        /*
        | Logout
        |-------------------------------------------
        */
        public function logout() {
            $this->session->sess_destroy();
            
            redirect('/', 'refresh');
        }
        
        /*
        | FB Channel
        |-------------------------------------------
        */
        public function fb_channel() {
            $this->load->view('fb_channel.php');
        }
        
    }
    
?>