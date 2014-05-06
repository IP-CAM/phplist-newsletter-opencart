<?php echo $header; ?>
<script>
$(document).ready( function(){
	$('button, .buttons a, .filter a').button();
})
</script>
<script>
$(document).ready( function(){
	phc_phplist_newsletter_params= {
	action: "",
	ajaxurl: "<?php echo $get_subscribe_form; ?>",
	}
	
    PHC_PHPList_Newsletter_Feedback= {
		selector: {},
        'default': function(data){
			console.log(data);
        },
        'alert': function(data){
			alert(data.msg);
        },
        'populate_form_to_textarea': function(data){
			if( data.type == "success" ){
				this.selector.text(data.html);
			}
        },		
    }
	
    PHC_PHPList_Newsletter_Ajax= {
		'form': '',
        'type': "default",
		'extra': "default",
		container: '',
        send: function(url, data){
            PHC_PHPList_Newsletter_Ajax= this;
            $.ajax({
				async: false,
                dataType: 'json',
                type: 'POST',
                url: url,
                data: data,
                beforeSend: function(){
					PHC_PHPList_Newsletter_Ajax.blockUI();
                },
                complete: function(){
					PHC_PHPList_Newsletter_Ajax.unBlock();
                },
                success: function(data){
					PHC_PHPList_Newsletter_Ajax.unBlock();
                    type= PHC_PHPList_Newsletter_Ajax.type;
                    PHC_PHPList_Newsletter_Feedback[type](data);
                }
            });
        },
		loading: function(url, data){
			container= this.container;
			$(container).load(url + ' ' + container, data);
		},
		blockUI: function(){
			$.blockUI({
				message: "",
			});
		},
		unBlock: function(){
			$.unblockUI(); 
		},
	}
	
	$('#btn-add-module').live('click', function(e){
		e.preventDefault();
		num_table_row= parseInt($('#phplist-newsletter-module-list tbody tr').length) - 1;
		if( num_table_row > 0 ){
			num_table_row= parseInt($('#phplist-newsletter-module-list tbody tr:last-child').attr('data-row-id')) + 1;
		}
		$cloneObj= $('#phplist-newsletter-module-list tbody tr:first-child').clone();
		cloneObj_html= String($cloneObj.html());
		new_module= cloneObj_html.replace(/row_id/gi, num_table_row);
		$(new_module).appendTo('#phplist-newsletter-module-list tbody')
		.wrapAll('<tr data-row-id="' + num_table_row + '">').find('select, input, textarea').removeAttr('disabled');
	})
	
	$('.btn-remove').live('click', function(e){
		e.preventDefault();
		$obj= $(this);
		$obj.parent().parent().remove();
	});
	
	$('.phc-phplist-newsletter').live('change', function(e){
		e.preventDefault();
		$obj= $(this);
		console.log($obj);
		$textarea= $obj.parent().next().find('textarea');
		subscribe_page_url= $('option:selected', $obj).val();
		if( subscribe_page_url != undefined && subscribe_page_url != "" ){
			data= {
			subscribe_page_url: encodeURIComponent(subscribe_page_url),
			action: phc_phplist_newsletter_params.action,
			}
			
			PHC_PHPList_Newsletter_Feedback.selector= $textarea;
			PHC_PHPList_Newsletter_Ajax.type= "default";
			PHC_PHPList_Newsletter_Ajax.type= "populate_form_to_textarea";
			PHC_PHPList_Newsletter_Ajax.send(phc_phplist_newsletter_params.ajaxurl, data);
		}else{
			$textarea.empty();
		}
		
	});	
})
</script>
<style>
.list thead td a, .list thead td, .list tfoot td {
padding: 7px;
}
.ui-button-text-only .ui-button-text {
padding: .2em 1em;
}
.textarea-subscriber-form {
width: 100%;
height: 100px;
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
	  <a onclick="$('#form').submit();" class=""><?php echo $button_save; ?></a>
	  <a href="<?php echo $cancel; ?>" class=""><?php echo $button_cancel; ?></a>
	  </div>
    </div>
    <div class="content">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<input type="hidden" name="store_id" value="<?php echo isset($store_id) ? $store_id : 0; ?>" />
		<input type="hidden" name="action" value="save" />
		<table id="phplist-newsletter-module-list" class="list">
			<thead>
			<tr>
			<td><?php echo $entry_layout; ?></td>
			<td><?php echo $entry_position; ?></td>
			<td><?php echo $entry_subscribe_pages; ?></td>
			<td><?php echo $entry_custom_form_element; ?></td>
			<td><?php echo $entry_status; ?></td>
			<td><?php echo $entry_sort_order; ?></td>
			<td>&nbsp;</td>
			</tr>
			</thead>
			<tbody>
			<tr data-row-id="row_id" style="display: none;">
			<td>
			<select name="phplist_newsletter_module[row_id][layout_id]" disabled="disabled">
			<?php
			foreach( $layouts as $key=>$layout ){
			?>
				<option value="<?php echo $layout['layout_id']; ?>">
				<?php echo $layout['name']; ?></option>
			<?php
			}
			?>
			</select>
			</td>
			<td>
			<select name="phplist_newsletter_module[row_id][position]" disabled="disabled">
			<?php
			foreach( $positions as $key=>$position ){
			?>
				<option value="<?php echo $key; ?>"><?php echo $position; ?></option>
			<?php
			}
			?>
			</select>
			</td>
			<td>
			<select class="phc-phplist-newsletter" name="phplist_newsletter_module[row_id][subscribe_page]" disabled="disabled">
			<option value=""><?php echo $txt_select; ?></option>
			<?php
			foreach( $phplist_subscribe_pages as $subscribe_page ){
			?>
				<option value="<?php echo $subscribe_page['href']; ?>"><?php echo $subscribe_page['innertext']; ?></option>
			<?php
			}
			?>
			</select>
			</td>
			<td>
			<textarea class="textarea-subscriber-form" id="" name="phplist_newsletter_module[row_id][custom_form_element]" disabled="disabled"></textarea>
			</td>
			<td>
			<select name="phplist_newsletter_module[row_id][status]" disabled="disabled">
			<?php
			$statuses= array("1"=>"enabled", "0"=>"disabled");
			foreach( $statuses as $key=>$value ){
			?>
				<option value="<?php echo $key; ?>"><?php echo ucfirst($value); ?></option>
			<?php
			}
			?>
			</select>
			</td>
			<td class="right">
			<input type="text" name="phplist_newsletter_module[row_id][sort_order]" 
			value="" size="3" disabled="disabled" />
			</td>
			<td>
			<a href="#" class="button btn-remove"><?php echo $button_remove; ?></a>
			</td>
			</tr>
			<?php
			foreach( $modules as $row_id=>$conf ){
			?>
				<tr data-row-id="<?php echo $row_id; ?>">
				<td>
				<select name="phplist_newsletter_module[<?php echo $row_id; ?>][layout_id]">
				<?php
				foreach( $layouts as $key=>$layout ){
					$selected= "";
					$layout_id= ( isset($conf['layout_id']) ) ? $conf['layout_id']: "";
					if( $layout['layout_id'] == $layout_id ){
						$selected= " selected=\"selected\"";
					}
				?>
					<option value="<?php echo $layout['layout_id']; ?>"<?php echo $selected; ?>>
					<?php echo $layout['name']; ?></option>
				<?php
				}
				?>
				</select>
				</td>
				<td>
				<select name="phplist_newsletter_module[<?php echo $row_id; ?>][position]">
				<?php
				foreach( $positions as $key=>$position ){
					$selected= "";
					$cur_position= ( isset($conf['position']) ) ? $conf['position']: "";
					if( $key == $cur_position ){
						$selected= " selected=\"selected\"";
					}
				?>
					<option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo $position; ?></option>
				<?php
				}
				?>
				</select>
				</td>
				<td>
				<select class="phc-phplist-newsletter" 
				name="phplist_newsletter_module[<?php echo $row_id; ?>][subscribe_page]">
				<option value=""><?php echo $txt_select; ?></option>
				<?php
				foreach( $phplist_subscribe_pages as $subscribe_page ){
					$selected= "";
					$curr_subscribe_page= ( isset($conf['subscribe_page']) ) ? $conf['subscribe_page']: "";
					$subscribe_page['href']= str_replace("&", "&amp;", $subscribe_page['href']);
					if( $subscribe_page['href'] == $curr_subscribe_page ){
						$selected= " selected=\"selected\"";
					}
				?>
					<option value="<?php echo $subscribe_page['href']; ?>"<?php echo $selected; ?>><?php echo $subscribe_page['innertext']; ?></option>
				<?php
				}
				?>
				</select>
				</td>
				<td>
				<textarea class="textarea-subscriber-form" id="" name="phplist_newsletter_module[<?php echo $row_id; ?>][custom_form_element]"><?php echo ( isset($conf['custom_form_element']) ) ?  $conf['custom_form_element'] : ""; ?></textarea>
				</td>
				<td>
				<select name="phplist_newsletter_module[<?php echo $row_id; ?>][status]">
				<?php
				$statuses= array("1"=>"enabled", "0"=>"disabled");
				foreach( $statuses as $key=>$value ){
					$selected= "";
					$status= ( isset($conf['status']) ) ? $conf['status']: "";
					if( $key == $status ){
						$selected= " selected=\"selected\"";
					}
				?>
					<option value="<?php echo $key; ?>"<?php echo $selected; ?>>
					<?php echo ucfirst($value); ?></option>
				<?php
				}
				?>
				</select>						
				</td>
				<td class="right">
				<input type="text" name="phplist_newsletter_module[<?php echo $row_id; ?>][sort_order]" 
				value="<?php echo ( isset($conf['sort_order']) ) ?  $conf['sort_order'] : ""; ?>" size="3" />
				</td>
				<td>
				<a href="#" class="button btn-remove"><?php echo $button_remove; ?></a>
				</td>
				</tr>
			<?php
			}
			?>
			</tbody>
			<!-- Button Adding Module -->
			<tfoot>
			<tr>
			<td colspan="6">&nbsp;</td>
			<td>
			<a href="#" id="btn-add-module" class="button"><?php echo $button_add_module; ?></a>
			</td>
			</tr>
			</tfoot>
		</table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>