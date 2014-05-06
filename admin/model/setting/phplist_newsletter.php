<?php
class ModelSettingPHPListNewsletter extends Model {

	public function createTable(){
		$sql= "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "newsletter` (
		`newsletter_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`email` varchar(255) NOT NULL,
		`attributes` text NOT NULL,
		PRIMARY KEY (`newsletter_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		$query = $this->db->query($sql);
	}
}
?>