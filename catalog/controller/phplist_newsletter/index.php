<?php
if( ! class_exists("simple_html_dom") && ! class_exists("RESTRequestProxy") ){
require_once(DIR_SYSTEM . "library/phplist/simplehtmldom_1_5/simple_html_dom.php");	
require_once(DIR_SYSTEM . "library/phplist/RESTRequestProxy.class.php");
}
class ControllerPHPListNewsletterIndex extends Controller {
	
	public function index(){
		if ( ($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$POST= $this->request->post;
			extract($POST);
			
			$this->load->model('setting/phplist_newsletter');
			
			$feedback= array();
			switch ($type) {
			case "subscribe":
			    // Check Users
				$res= $this->model_setting_phplist_newsletter->getByEmail($POST['email']);
				if( empty($res) ){
					// PHPList Integration - Create / Add New Subscriber
					$phplist_specific_subscribe_page= html_entity_decode($POST['subscribe_page_url']);
					unset($POST['subscribe_page_url']);
					$data= $POST;
					
					$data= http_build_query($data);
					$proxy = new RESTRequestProxy($phplist_specific_subscribe_page);
					$data= $proxy->create($data);

					$html = str_get_html($data);
					$elements= "";
					foreach( $html->find('.missing') as $element ){
						$elements= $element->innertext;
					}
					$html->clear();
					
					if( ! empty($elements) ){
						$feedback= array('type'=>'errors', 'msg'=>$elements);
						break;
					}
					
					$POST['attributes']= array();
					$reg_attributes= "#^attribute#";
					foreach( $POST as $key=>$value ){
						if( preg_match($reg_attributes, $key) ){
							$POST['attributes'][$key]= $value;
						}
					}
					
					// Create new user
					$res= $this->model_setting_phplist_newsletter->add($POST);
					
					// Send Feedback to User
					if( ! $res ){
						$feedback= array('type'=>'errors', 'msg'=>'Error');
					}else{
						// Set Feedback
						$feedback= array('type'=>'success', 'msg'=>'Thanks to be our subscriber');
					}
				}
				else{
					// Set Feedback
					$feedback= array('type'=>'success', 'msg'=>'You\'ve been added to be subscriber.');
				}
			    break;
			}
			// Output Data
			echo json_encode($feedback);
			exit;
		}
	}
}
?>