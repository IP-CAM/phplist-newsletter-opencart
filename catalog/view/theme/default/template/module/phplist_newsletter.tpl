<div class="box">
	<div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="box-content">
		<div class="box-product phplist-subscribe-page">
		<?php
		extract($setting);
		?>
		<input type="hidden" name="phplist_specific_subscribe_page[<?php echo $module; ?>]" value="<?php echo $subscribe_page; ?>">
		<?php echo html_entity_decode($custom_form_element); ?>
		</div>
	</div>
</div>	