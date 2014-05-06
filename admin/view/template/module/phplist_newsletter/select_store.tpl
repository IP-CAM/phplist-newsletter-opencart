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
		<h1><img src="view/image/product.png" alt=""> <?php echo $heading_title; ?></h1>
		<div class="buttons">
		<a class="" id="btn-save"><?php echo $text_btn_set; ?></a>
		<a href="<?php echo $cancel; ?>" class="" id="btn-cancel"><?php echo $button_cancel; ?></a>
		</div>
		</div>
		<div class="content">
		<?php
		extract($modules);
		?>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="form">
			<tr>
				<td>
				<label for="store_id">
				<span class="required">*</span> <?php echo $entry_multi_store; ?>
				</label>
				</td>
				<td>
				<select name="store_id" id="store_id">
				<?php
				foreach( $stores as $store ){
				?>
					<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
				<?php
				}
				?>
				</select>
				</td>
			</tr>
			</table>
			</form>
		</div>
	</div>
</div>
<?php echo $footer; ?> 