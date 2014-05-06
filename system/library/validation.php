<?php
require_once("zend_library/Stdlib/ErrorHandler.php");
require_once("zend_library/Stdlib/StringWrapper/StringWrapperInterface.php");
require_once("zend_library/Stdlib/StringWrapper/AbstractStringWrapper.php");
require_once("zend_library/Stdlib/StringWrapper/Intl.php");

require_once("zend_library/Stdlib/StringUtils.php");
require_once("zend_library/Stdlib/StringWrapper/MbString.php");

require_once("zend_library/Filter/FilterInterface.php");
require_once("zend_library/Filter/AbstractFilter.php");
require_once("zend_library/Filter/Digits.php");

require_once("zend_library/Translator/TranslatorAwareInterface.php");

require_once("zend_library/Validator/ValidatorInterface.php");
require_once("zend_library/Validator/AbstractValidator.php");
require_once("zend_library/Validator/Ip.php");
require_once("zend_library/Validator/Hostname.php");
require_once("zend_library/Validator/StringLength.php");
require_once("zend_library/Validator/Regex.php");
require_once("zend_library/Validator/NotEmpty.php");

require_once("zend_library/Validator/Digits.php");

class Validation {
	private $messages= array();
	private $inside= FALSE;
	public $tag_open_error; 
	public $tag_close_error; 
	
	function __construct($fields=""){
		if( !empty($fields) ){
			$this->fields= $fields;
		}else{
			$error = 'Validation Class need fields variable';
			throw new Exception($error);
		}
	}

	public function set_inside($status=FALSE){
		$this->inside= $status;
	}
		
	public function validate($POST){
		foreach( $POST as $key=>$value ){
			if( $this->inside ){
				if( is_array($value) ){
				foreach( $value as $sub_key=>$sub_value ){
					if( array_key_exists($sub_key, $this->fields) ){
						if( (isset($this->fields[$sub_key]['required']) && 
						$this->fields[$sub_key]['required']) || 
						! $this->is_array_empty($POST[$key][$sub_key]) ){
							foreach( $this->fields[$sub_key]['validators'] as $validation ){
								$validation_type= 'Zend\Validator\\' . $validation['name'];
								$validator= new $validation_type($validation['constructor']);
								$validator->setMessage($validation['message']);
								
								// Validation Name
								$all_error= "";
								if( is_array($POST[$key][$sub_key]) ){
									foreach( $POST[$key][$sub_key] as $sub_sub_key=>$sub_sub_value ){
										if( ! $validator->isValid($sub_sub_value) ){
											$error_msg= current($validator->getMessages());
											$this->messages[$key][$sub_key][$sub_sub_key][]= $error_msg;
										}
									}
								}
								else{
									if( !$validator->isValid($POST[$key][$sub_key]) ){
										$error_msg= current($validator->getMessages());
										$this->messages[$key][$sub_key][]= $error_msg;
									}
								}
							}
						}
					}else{
						if( is_array($sub_value) ){
						foreach( $sub_value as $grand_key=>$grand_value ){
							if( array_key_exists($grand_key, $this->fields) ){
								if( (isset($this->fields[$grand_key]['required']) && 
								$this->fields[$grand_key]['required']) || 
								! $this->is_array_empty($POST[$key][$sub_key][$grand_key]) ){
									foreach( $this->fields[$grand_key]['validators'] as $validation ){
										$validation_type= 'Zend\Validator\\' . $validation['name'];
										$validator= new $validation_type($validation['constructor']);
										$validator->setMessage($validation['message']);
										
										// Validation Name
										$all_error= "";						
										if( is_array($POST[$key][$sub_key][$grand_key]) ){
											foreach( $POST[$key][$sub_key][$grand_key] as $grand_key_post=>$value_post ){
												if( is_array($POST[$key][$sub_key][$grand_key][$grand_key_post]) ){
													foreach( $POST[$key][$sub_key][$grand_key][$grand_key_post] as $subkey_post=>$subvalue_post ){
														if( (isset($this->fields[$grand_key]['required']) && 
														$this->fields[$grand_key]['required']) || ! empty($subvalue_post) ){
															if( !$validator->isValid($subvalue_post) ){
																$error_msg= current($validator->getMessages());
																$this->messages[$key][$sub_key][$grand_key][$grand_key_post][$subkey_post][]= $error_msg;
															}
														}
													}
												}else{
													if( (isset($this->fields[$grand_key]['required']) && 
													$this->fields[$grand_key]['required']) || 
													! empty($value_post) ){
														if( ! $validator->isValid($value_post) ){
															$error_msg= current($validator->getMessages());
															$this->messages[$key][$sub_key][$grand_key][$grand_key_post][]= $error_msg;
														}
													}
												}
											}
										}else{
											if( !$validator->isValid($POST[$key][$sub_key][$grand_key]) ){
												$error_msg= current($validator->getMessages());
												$this->messages[$key][$sub_key][$grand_key][]= $error_msg;
											}
										}
									}
								}						
							}
						}
						}
					}

				}
				}
			}
			else{
				if( array_key_exists($key, $this->fields) ){
					if( (isset($this->fields[$key]['required']) && $this->fields[$key]['required']) || 
					! $this->is_array_empty($POST[$key]) ){				
						foreach( $this->fields[$key]['validators'] as $validation ){
							$validation_type= 'Zend\Validator\\' . $validation['name'];
							$validator= new $validation_type($validation['constructor']);
							$validator->setMessage($validation['message']);
							
							// Validation Name
							$all_error= "";
							if( is_array($POST[$key]) ){
								foreach( $POST[$key] as $key_post=>$value_post ){
									if( is_array($POST[$key][$key_post]) ){
										foreach( $POST[$key][$key_post] as $subkey_post=>$subvalue_post ){
											if( !$validator->isValid($subvalue_post) ){
												$error_msg= current($validator->getMessages());
												$this->messages[$key][$key_post][$subkey_post][]= $error_msg;
											}
										}
									}else{
										if( ! $validator->isValid($value_post) ){
											$error_msg= current($validator->getMessages());
											$this->messages[$key][$key_post][]= $error_msg;
										}
									}
								}
							}else{
								if( !$validator->isValid($POST[$key]) ){
									$error_msg= current($validator->getMessages());
									$this->messages[$key][]= $error_msg;
								}
							}
						}
					}
				}
			}
		}
		return $this->messages;
	}
	
	// Recursion Checking for Empty values - support String and Array
	function is_array_empty($InputVariable)
	{
	   $Result = true;

	   if (is_array($InputVariable) && count($InputVariable) > 0)
	   {
	      foreach ($InputVariable as $Value)
	      {
	         $Result = $Result && $this->is_array_empty($Value);
	      }
	   }
	   else
	   {
	      $Result = empty($InputVariable);
	   }

	   return $Result;
	}
	
	function get_error_messages($errors, $key="")
	{
		if( ! empty($errors) ){
			$errors_msg= array();
			if( is_array($errors) && count($errors) > 0)
			{
				foreach( $errors as $subkey=>$value )
				{
					$errors_msg[$subkey]= $this->get_error_messages($value, $subkey);
				}
			}
			else
			{
				$errors_msg= $this->tag_open_error . $errors . $this->tag_close_error;
			}
			return $errors_msg;
		}
	}
		
	public function _validate($POST){
		foreach( $this->fields as $field ){
			$field_name= $field['name'];
			if( (isset($field['required']) && $field['required']) || 
			! empty($POST[$field_name]) ){
				foreach( $field['validators'] as $validation ){
					$validation_type= 'Zend\Validator\\' . $validation['name'];
					$validator= new $validation_type($validation['constructor']);
					$validator->setMessage($validation['message']);
					
					// Validation Name
					$all_error= "";
					if( is_array($POST[$field_name]) ){
						foreach( $POST[$field_name] as $key=>$value ){
							if( is_array($POST[$field_name][$key]) ){
								foreach( $POST[$field_name][$key] as $subkey=>$subvalue ){
									if( !$validator->isValid($subvalue) ){
										$error_msg= current($validator->getMessages());
										$this->messages[$field_name][$key][$subkey][]= $error_msg;
									}
								}
							}else{
								if( !$validator->isValid($value) ){
									$error_msg= current($validator->getMessages());
									$this->messages[$field_name][$key][]= $error_msg;
								}
							}
						}
					}else{
						if( !$validator->isValid($POST[$field_name]) ){
							$error_msg= current($validator->getMessages());
							$this->messages[$field_name]= $error_msg;
						}
					}
				}
			}
		}
		
		return $this->messages;
	}
}
?>