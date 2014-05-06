<?php
if( ! class_exists("simple_html_dom") && ! class_exists("RESTRequestProxy") ){
require_once(DIR_SYSTEM . "library/phplist/simplehtmldom_1_5/simple_html_dom.php");	
require_once(DIR_SYSTEM . "library/phplist/RESTRequestProxy.class.php");
}
class ControllerModulePHPListNewsletter extends Controller {
	
	protected function index($setting) {
		static $module = 0;

		$this->load->model('setting/setting');
		
		$this->language->load('module/phplist_newsletter');
		
		// Start Get Text for Module
    	$this->data['heading_title'] = $this->language->get('heading_title');
		// End Get Text for Module
		
		// Set PHPList Newsletter Configuration
		$setting['custom_form_element']= $this->_add_action_url_subscribe_form($setting);
		$this->data['setting']= $setting;
		
		$this->data['module']= $module++;
		
		$scripts= array("catalog/view/javascript/jquery/jquery.blockUI.js", 
		"catalog/view/javascript/phplist_newsletter/phc-phplist-newsletter-ajax.js");
		foreach( $scripts as $script ){
			$this->document->addScript($script);
		}
				
		if( file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/phplist_newsletter.tpl') ){
			$this->template = $this->config->get('config_template') . '/template/module/phplist_newsletter.tpl';
		}else{
			$this->template = 'default/template/module/phplist_newsletter.tpl';
		}
		
		$this->render();
	}
	
	function _add_action_url_subscribe_form($setting){
		extract($setting);
		$phplist_form_action= $this->url->link('phplist_newsletter/index', '', 'SSL');		
		
		// Get Form Parameters
		$html = str_get_html(html_entity_decode($custom_form_element));
		$elements= "";
		foreach( $html->find('form[name=subscribeform]') as $element ){
			$element->attr['action']= $phplist_form_action;
			$elements= $element;
		}
		$form= $elements->__toString();
		$html->clear();
		
		return $form;
	}	
}
?>