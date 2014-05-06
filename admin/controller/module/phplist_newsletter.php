<?php
require_once(DIR_SYSTEM . 'library/validation.php');
if( ! class_exists("simple_html_dom") && ! class_exists("RESTRequestProxy") ){
require_once(DIR_SYSTEM . "library/phplist/simplehtmldom_1_5/simple_html_dom.php");	
require_once(DIR_SYSTEM . "library/phplist/RESTRequestProxy.class.php");
}

class ControllerModulePHPListNewsletter extends Controller {
	private $error= array();
	private $list= array();

	public function get_list_of_pages(){
		$this->language->load('module/phplist_newsletter');
		$this->pages= array(
		"blog_configuration"=>array(
		'label'=>$this->language->get('text_btn_go_to_blog_configuration'),
		'url'=>$this->url->link('module/phplist_newsletter/configuration', 'token=' . $this->session->data['token'], 'SSL')
		),
		"blog_locator"=>array(
		'label'=>$this->language->get('text_btn_go_to_blog_locator'),
		'url'=>$this->url->link('module/phplist_newsletter/locator', 'token=' . $this->session->data['token'], 'SSL')
		),
		);
		
		return $this->pages;
	}
	
	public function index() {
		$this->language->load('module/phplist_newsletter');
		$this->load->model('setting/setting');
		$this->load->model('setting/phplist_newsletter');
		
		$this->model_setting_phplist_newsletter->createTable();
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		// Start Get Text
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['heading_title_dashboard'] = $this->language->get('heading_title_dashboard');
		$this->data['description'] = $this->language->get('description');
		$this->data['text_btn_go_to_lookbook_category'] = $this->language->get('text_btn_go_to_lookbook_category');
		$this->data['text_btn_go_to_lookbook_products'] = $this->language->get('text_btn_go_to_lookbook_products');
		$this->data['text_btn_place_your_lookbook_on_opencart_page'] = $this->language
		->get('text_btn_place_your_lookbook_on_opencart_page');
		
		$this->data['text_btn_back'] = $this->language->get('text_btn_back');
		$this->data['button_save'] = $this->language->get('button_save');
		
		$this->data['pages']= $this->get_list_of_pages();
		// End Get Text
		
		$this->data['edit_link']= $this->url->link('module/email_template_manager/edit_template', 'token=' . 
		$this->session->data['token'], 'SSL');
	
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];	
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
				
		// BreadCrumb Trail
  		$this->data['breadcrumbs'] = array();
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/phplist_newsletter', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . 
		$this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->template = 'module/phplist_newsletter/dashboard.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	public function configuration(){
    	$this->language->load('module/phplist_newsletter/configuration_form');

    	$this->document->setTitle($this->language->get('heading_title')); 
		
		$this->load->model('setting/setting');
		$this->load->model('setting/store');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ( isset($this->request->post['action']) && 
		$this->request->post['action'] == "save" ) && $this->validateConfiguration()) {		
			$POST= $this->request->post;
			$store_id= ( isset($this->request->post['store_id']) ) ? $this->request->post['store_id'] : 0;
			unset($POST['store_id'], $POST['action']);
			
			$this->model_setting_setting->editSetting('phplist_newsletter_configuration', $POST, $store_id);
						
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('module/phplist_newsletter', 'token=' . 
			$this->session->data['token'], 'SSL'));
		}
		
    	$this->getConfigurationForm();
  	}
	
	protected function getConfigurationForm(){
		$store_id= ( isset($this->request->post['store_id']) ) ? $this->request->post['store_id'] : 0;
		$this->data['heading_title'] = ( isset($this->request->post['store_id']) ) ? 
		$this->language->get('heading_title') : $this->language->get('heading_title_multi_store');
//    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_phplist_site_url'] = $this->language->get('text_phplist_site_url');
		$this->data['entry_multi_store'] = $this->language->get('entry_multi_store');
		$this->data['text_btn_set'] = $this->language->get('text_btn_set');
		
    	$this->data['text_label_name'] = $this->language->get('text_label_name');
    	$this->data['text_label_image'] = $this->language->get('text_label_image');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
    	$this->data['text_browse'] = $this->language->get('text_browse');
    	$this->data['text_clear'] = $this->language->get('text_clear');
		
    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    	
 		if( isset($this->error['warning']) ){
			$this->data['error_warning'] = $this->error['warning'];
		} 
		else {
			$this->data['error_warning'] = '';
		}
		
		// Start BreadCrumb Trail
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_modules'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);		

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_phplist_newsletter'),
			'href'      => $this->url->link('module/phplist_newsletter', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_phplist_newsletter_configuration'),
			'href'      => $this->url->link('module/phplist_newsletter/configuration', 'token=' . 
			$this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		// End BreadCrumb Trail
				
		// Start Button Url
		$this->data['action'] = $this->url->link('module/phplist_newsletter/configuration', 'token=' . 
		$this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('module/phplist_newsletter/', 'token=' . $this->session->data['token'], 'SSL');
		// End Button Url
		
		// Get MultiStore Support
		$stores= array(
		array('store_id'=>0, 'name'=>'Default'),
		);
		$stores_extended= $this->model_setting_store->getStores();
		$stores= array_merge($stores, $stores_extended);
		$this->data['stores']= $stores;
		$this->data['store_id']= $store_id;
		
		// Start Load Configuration
		unset($this->request->post['store_id']);
		$this->data['modules'] = array();
		if (isset($this->request->post['phplist_newsletter_configuration']) ) {
			$this->data['modules'] = $this->request->post['phplist_newsletter_configuration'];
		} elseif ($this->config->get('phplist_newsletter_configuration')) { 
			$res = $this->model_setting_setting->getSetting('phplist_newsletter_configuration', $store_id);
			$this->data['modules'] = ( ! empty($res['phplist_newsletter_configuration']) ) ? $res['phplist_newsletter_configuration'] : array();
		}
		
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		// Get Error
		$this->data['errors'] = $this->error;
		
		// End Load Configuration
		
		if( $this->request->server['REQUEST_METHOD'] != "POST" ){
			$this->template = 'module/phplist_newsletter/select_store.tpl';
		}else{
			$this->template = 'module/phplist_newsletter/configuration_form.tpl';
		}		
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function locator(){
		$this->language->load('module/phplist_newsletter/locator');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/extension');
		$this->load->model('setting/setting');
		$this->load->model('setting/store');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ( isset($this->request->post['action']) && 
		$this->request->post['action'] == "save" ) && $this->validateLocator()) {
			$POST= $this->request->post;

			$store_id= ( isset($this->request->post['store_id']) ) ? $this->request->post['store_id'] : 0;
			unset($POST['store_id'], $POST['action']);
			$this->model_setting_setting->editSetting('phplist_newsletter', $POST, $store_id);
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('module/phplist_newsletter', 'token=' . 
			$this->session->data['token'], 'SSL'));			
		}
		
		$store_id= ( isset($this->request->post['store_id']) ) ? $this->request->post['store_id'] : 0;
		$this->data['heading_title'] = ( isset($this->request->post['store_id']) ) ? 
		$this->language->get('heading_title') : $this->language->get('heading_title_multi_store');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$positions= array(
		'content_top'=>$this->language->get('text_content_top'),
		'content_bottom'=>$this->language->get('text_content_bottom'),
		'column_left'=>$this->language->get('text_column_left'),
		'column_right'=>$this->language->get('text_column_right'),
		);
		$this->data['positions'] = $positions;
		
		$this->data['entry_category'] = $this->language->get('entry_category'); 
		$this->data['entry_dimension'] = $this->language->get('entry_dimension');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_subscribe_pages'] = $this->language->get('entry_subscribe_pages');
		$this->data['entry_custom_form_element'] = $this->language->get('entry_custom_form_element');
		$this->data['txt_select'] = $this->language->get('txt_select');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_multi_store'] = $this->language->get('entry_multi_store');
		$this->data['text_btn_set'] = $this->language->get('text_btn_set');
		
		// Get PHPList Subscribe Pages
		$this->data['phplist_subscribe_pages'] = $this->_get_subscribe_pages($store_id);
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		// Get MultiStore Support
		$stores= array(
		array('store_id'=>0, 'name'=>'Default'),
		);
		$stores_extended= $this->model_setting_store->getStores();
		$stores= array_merge($stores, $stores_extended);
		$this->data['stores']= $stores;
		$this->data['store_id']= $store_id;
		
 		if( isset($this->error['warning']) ){
			$this->data['error_warning'] = $this->error['warning'];
		} 
		else {
			$this->data['error_warning'] = '';
		}
		
		// Start BreadCrumb Trail
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_modules'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_phplist_newsletter'),
			'href'      => $this->url->link('module/phplist_newsletter', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_phplist_newsletter_locator'),
			'href'      => $this->url->link('module/phplist_newsletter/locator', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		// End BreadCrumb Trail
				
		// Start Button Url
		$this->data['action'] = $this->url->link('module/phplist_newsletter/locator', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('module/phplist_newsletter/', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['get_subscribe_form'] = html_entity_decode($this->url->link('module/phplist_newsletter/_get_subscribe_form', 'token=' . $this->session->data['token'], 'SSL'));
		
		// End Button Url
		
		// Start Load Configuration
		$this->data['modules'] = array();
		if (isset($this->request->post['phplist_newsletter_module']) ) {
			$this->data['modules'] = $this->request->post['phplist_newsletter_module'];
		} elseif ($this->config->get('phplist_newsletter_module')) { 
			$res = $this->model_setting_setting->getSetting('phplist_newsletter', $store_id);
			$this->data['modules'] = ( ! empty($res['phplist_newsletter_module']) ) ? $res['phplist_newsletter_module'] : array();
		}
		
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		// Get Error
		$this->data['errors'] = $this->error;
		
		// End Load Configuration
		
		if( isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || 
		( $this->request->server['HTTPS'] == '1' )) ){
			$base_url= HTTPS_SERVER;
		}else{
			$base_url= HTTP_SERVER;
		}
		$script_url= $base_url . "view/javascript/jquery/jquery.blockUI.js";
		$this->document->addScript($script_url);
		
		if( $this->request->server['REQUEST_METHOD'] != "POST" ){
			$this->template = 'module/phplist_newsletter/select_store.tpl';
		}else{
			$this->template = 'module/phplist_newsletter/locator.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	function _get_subscribe_pages($store_id=""){
		$this->load->model('setting/setting');
		$phplist_newsletter_configuration= $this->model_setting_setting->getSetting('phplist_newsletter_configuration', 
		$store_id);
		extract($phplist_newsletter_configuration);
		$phplist_site_url= isset($phplist_newsletter_configuration['phplist_site_url']) ? $phplist_newsletter_configuration['phplist_site_url'] : "";
				
		$phplist_subscribe_pages= array();
		if( ! empty($phplist_site_url) ){
			$proxy = new RESTRequestProxy($phplist_site_url);
			$data= $proxy->get();

			$html = str_get_html($data);
			foreach( $html->find('.content p a') as $element ){
				$exclude= "[poweredby|\./\?p=unsubscribe]";
				if( ! preg_match($exclude, $element->attr['href']) ){
					$elem= array();
					$href= str_replace("./", $phplist_site_url, $element->attr['href']);
					$elem['href']= $href;
					$elem['innertext']= $element->innertext;
					$phplist_subscribe_pages[]= $elem;
				}
			}
			$html->clear();
		}
		
		return $phplist_subscribe_pages;
	}
	
	function _get_subscribe_form(){
		if ( ($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$POST= $this->request->post;
			$phplist_specific_subscribe_page= urldecode($POST['subscribe_page_url']);
			$phplist_form_action= $POST['action'];
			$proxy = new RESTRequestProxy($phplist_specific_subscribe_page);	
			$data= $proxy->get();
			
			// Get Form Parameters
			$html = str_get_html($data);
			$elements= "";
			foreach( $html->find('form[name=subscribeform]') as $element ){
				$element->attr['action']= $phplist_form_action;
				$elements= $element;
			}
			$form= $elements->__toString();
			$html->clear();
			
			// Output Data
			$feedback= array("type"=>"success", "html"=>$form);
			echo json_encode($feedback);
			exit;
		}
	}
	
	protected function validateConfiguration(){
		$this->language->load('module/phplist_newsletter/configuration_form');
		
    	if( !$this->user->hasPermission('modify', 'module/phplist_newsletter') ){
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		// @todo comment back constructor to use ALLOW_DNS only
		$POST= $this->request->post;
		$fields= array(
		'phplist_site_url'=>array(
		'required'=>TRUE,
		'validators'=>array(
			array(
	        'name'=>'Hostname',
			'constructor'=>array('allow'=>Zend\Validator\Hostname::ALLOW_DNS, 'useIdnCheck'=>false),
//			'constructor'=>array('allow'=>Zend\Validator\Hostname::ALLOW_DNS|Zend\Validator\Hostname::ALLOW_LOCAL, 'useIdnCheck'=>false),
			'message'=>"Site URL is invalid. Please correct it.",
	        ),
			),
		),
		);
		
		try {
			$validation= new Validation($fields);
			$messages= $validation->validate($POST['phplist_newsletter_configuration']);
			$validation->tag_open_error= '<span class="error">';
			$validation->tag_close_error= '</span>';
			$messages= $validation->get_error_messages($messages);
		}catch(Exception $e){
			echo "<pre>";
			print_r($e);
			echo "</pre>";
		}
		// Store Error Messages
		$this->error= $messages;
		
		if( !$this->error ){
			return true;
		}else{
			return false;
		}	
  	}
	
	protected function validateLocator() {
		if( !$this->user->hasPermission('modify', 'module/phplist_newsletter') ){
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$POST= $this->request->post;
		if (isset($this->request->post['phplist_newsletter_module'])) {
		}
				
		if( !$this->error ){
			return true;
		}else{
			return false;
		}	
	}
	
	public function dumpOutput($data) {
		header('content-type: text/plain');
		print_r($data);
		exit;
	}	
}
?>