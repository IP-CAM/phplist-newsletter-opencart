<?php
class RESTRequestProxy {

  public $service;

  public function __construct($service_url) {
    $this->service = $service_url;
  }
  
  public function create($data) {
     return $this->exec("POST", $data);
  }
  
  public function get() {
     return $this->exec("GET");
  }

  private function exec($method="GET", $post=false) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->service);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $data = curl_exec($ch);

    if ( $data === false ){
		throw new Exception("Error making REST request. ".curl_error($ch));	
	}

    if ( curl_getinfo( $ch, CURLINFO_HTTP_CODE ) >= 400 ){
		throw new Exception("An HTTP error ". curl_getinfo( $ch, CURLINFO_HTTP_CODE ) . " occurred");
	}

    curl_close($ch);
	return $data;
  }

};

?>