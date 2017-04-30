<?php
    
    class Facebook {
        
        private $signed_request_algorithm = 'HMAC-SHA256';
        
        /*
        | Constructor
        |-------------------------------------------
        */
        public function __constructor() {
            
        }
        
        /*
        | Parse Signed Request
        |-------------------------------------------
        */
        public function parse_signed_request($signed_request, $app_secret) {
            list($encoded_sig, $payload) = explode('.', $signed_request, 2);

            // decode the data
            $sig = $this->base64_url_decode($encoded_sig);
            $data = json_decode($this->base64_url_decode($payload), true);

            if (strtoupper($data['algorithm']) !== $this->signed_request_algorithm) {
            log_message('error', 'Unknown algorithm. Expected ' . $this->signed_request_algorithm);
            return null;
            }

            // check sig
            $expected_sig = hash_hmac('sha256', $payload,
            $app_secret, $raw = true);
            if ($sig !== $expected_sig) {
            log_message('error', 'FB: Bad Signed JSON signature! ' . $this->signed_request_algorithm);
            return null;
            }

            return $data;
        }
        
        /*
        | Base64 URL Decode
        |-------------------------------------------
        */
        public function base64_url_decode($input) {
            return base64_decode(strtr($input, '-_', '+/'));
        }
    
    }
    
?>