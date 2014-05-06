<?php
class ModelSettingPHPListNewsletter extends Model {
	public function add($user) {
		extract($user);
		$this->db->query("INSERT INTO " . DB_PREFIX . "newsletter SET email = '" . $this->db->escape($email) . 
		"', attributes = '" . $this->db->escape(serialize($attributes)) . "'");

		$newsletter_id = $this->db->getLastId();
		
		$this->language->load('mail/phplist_newsletter');

		$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

		$message = $this->language->get('text_your_subscription_has_been_added') . "\n";
		$message .= $this->language->get('text_I_hope_however_that_you_enjoy_our_newsletter') . "\n\n";
		$message .= $this->language->get('text_regards') . "\n\n";
		$message .= $this->config->get('config_name');

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');				
		$mail->setTo($email);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();
		
		return $newsletter_id;
	}

	public function getByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsletter WHERE email = '" . $this->db->escape($email) . 
		"'");

		return $query->row;
	}
}
?>
