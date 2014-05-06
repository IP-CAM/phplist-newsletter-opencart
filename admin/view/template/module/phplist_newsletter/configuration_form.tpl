<?php echo $header; ?>
<script>
$(document).ready( function(){
	// Set Tabulasi
	$('#languages a').tabs();
	
	$('button, .buttons a').button();
	
	Feedback= {
		'container': null,
		'default': function(data){
			console.log("Default");
		},
		handleErrors: function(data){
			msg_num= 0;
			$.each(data, function(key, value){
				msg_num++;
				if( typeof(value) === "object" ){
					$.each(value, function(subkey, subvalue){
						console.log(typeof(subkey));
						error_msg= "";
						for(var i = 0, l = subvalue.length; i < l; i++) {
							error_msg+= '<div>'+subvalue[i]+'</div>';
							
						}
						$('<div class="error">'+error_msg+'</div>').insertAfter($('input[name="' + key + 
						'[' + subkey + ']"]'));
					});
				}else{
					$('<div class="error">'+value+'</div>').insertAfter($('input[name="' + key + '"]'));
					console.log(key + ': ' + value);
				}
			});
			
			if( msg_num != 0 ){
				alert("There're still errors. Please correct it.");
			}
		},
		'save': function(response){
			console.log(response);
			if( response.type == "error" ){
				this.handleErrors(response.data);
			}else if( response.type == "warning" ){
				$('<div class="warning">'+response.data+'</div>').insertBefore($('.box'));
			}else if( response.type == "redirect" ){
				if( response.url == "" ){
					window.location.reload();
				}else{
					window.location.href= decodeURIComponent(response.url);
				}
			}
		},
	}
	
	Ajax= {
		'type': "default",
		beforeSend: function(){
			$('.error, .warning').remove();
		},
		send: function(url, data){
			AjaxObj= this;
			$.ajax({
				dataType: 'json',
				type: 'POST',
				url: url,
				data: data,
                beforeSend: function(){
					AjaxObj.beforeSend();
					AjaxObj.blockUI();
                },
                complete: function(){
					AjaxObj.unBlock();
                },
				success: function(data){
					type= AjaxObj.type;
					Feedback[type](data);
				}
			});
		},
		blockUI: function(){
			$.blockUI({ 
				message: '<h1>Loading...</h1>',
			});
		},
		unBlock: function(){
			$.unblockUI(); 
		},
	}
	
	$('#btn-save').live('click', function(e){
		$('#form').submit();
	})	
})
</script>
<script>
jQuery(document).ready( function($){
	function testField( field, nilai){
		$(':input[name="'+field+'"],:text[name="'+field+'"][name^="'+field+'"],:password[name="'+field+'"][name^="'+field+'"],textarea[name="'+field+'"][name^="'+field+'"]').val(nilai);
		$(':input[name="'+field+'"],:input[name^="'+field+'"]').val(nilai);
		$('select[name="' + field + '"] option[value="' + nilai + '"]').attr({ 'selected': 'selected' });
	}
	
//	testField("phplist_newsletter_configuration[phplist_site_url]", "www.phplist.loc");
})
</script>
<style>
.ui-button-text-only .ui-button-text {
padding: .2em 1em;
}
.label,
.badge {
  display: inline-block;
  padding: 2px 4px;
  font-size: 11.844px;
  font-weight: bold;
  line-height: 14px;
  color: #ffffff;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  white-space: nowrap;
  vertical-align: baseline;
  background-color: #999999;
}

.label {
  -webkit-border-radius: 3px;
     -moz-border-radius: 3px;
          border-radius: 3px;
}

.badge {
  padding-right: 9px;
  padding-left: 9px;
  -webkit-border-radius: 9px;
     -moz-border-radius: 9px;
          border-radius: 9px;
}

.label:empty,
.badge:empty {
  display: none;
}

a.label:hover,
a.label:focus,
a.badge:hover,
a.badge:focus {
  color: #ffffff;
  text-decoration: none;
  cursor: pointer;
}
.label-info,
.badge-info {
  background-color: #3a87ad;
}
.label-info a {
color: #FFF;
cursor: pointer;
border-color: rgba(0, 0, 0, 0);
-webkit-border-radius: 0;
-moz-border-radius: 0;
border-radius: 0;
}
.label-info a:hover, .label-info a:focus {
color: #FFF;
text-decoration: underline;
background-color: rgba(0, 0, 0, 0);
}
</style>
<div id="content">
	<div class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
	<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
		<h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>
		<div class="buttons">
		<a class="" id="btn-save"><?php echo $button_save; ?></a>
		<a href="<?php echo $cancel; ?>" class="" id="btn-cancel"><?php echo $button_cancel; ?></a>
		</div>
		</div>
		<div class="content">
		<?php
		extract($modules);
		?>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<input type="hidden" name="store_id" value="<?php echo isset($store_id) ? $store_id : 0; ?>" />
			<input type="hidden" name="action" value="save" />
			<table class="form">
			<tr>
				<td>
				<label for="phplist_site_url">
				<span class="required">*</span> <?php echo $text_phplist_site_url; ?>
				</label>
				</td>
				<td>
				<input type="text" name="phplist_newsletter_configuration[phplist_site_url]" id="phplist_newsletter_configuration[phplist_site_url]" size="100" 
				value="<?php echo isset($phplist_site_url) ? $phplist_site_url : ""; ?>" />
				<?php if( isset($errors['phplist_site_url']) ){ ?>
				<?php echo implode("", $errors['phplist_site_url']); ?>
				<?php } ?>
				</td>
			</tr>
			</table>
			</form>
		</div>
	</div>
</div>
<?php echo $footer; ?> 