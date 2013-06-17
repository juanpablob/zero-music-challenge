<?php
    
    class Facebook {
        
        const SIGNED_REQUEST_ALGORITHM = 'HMAC-SHA256';
        
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
        public function parse_signed_request($signed_request) {
list($encoded_sig, $payload) = explode('.', $signed_request, 2);

// decode the data
$sig = $this->base64UrlDecode($encoded_sig);
$data = json_decode($this->base64_url_decode($payload), true);

if (strtoupper($data['algorithm']) !== $this->SIGNED_REQUEST_ALGORITHM) {
log_message('error', 'Unknown algorithm. Expected ' . $this->SIGNED_REQUEST_ALGORITHM);
return null;
}

// check sig
$expected_sig = hash_hmac('sha256', $payload,
$this->getAppSecret(), $raw = true);
if ($sig !== $expected_sig) {
log_message('error', 'FB: Bad Signed JSON signature! ' . $this->SIGNED_REQUEST_ALGORITHM);
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