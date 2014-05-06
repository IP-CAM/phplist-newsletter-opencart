jQuery(document).ready( function($){
	phc_phplist_newsletter_params= {
	action: "",
	}
	
    PHC_PHPList_Newsletter_Feedback= {
		selector: {},
        'default': function(data){
			console.log(data);
        },
        'alert': function(data){
			alert(data.msg);
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

	$('.phplist-subscribe-page form').find('input[type="submit"]').removeAttr('onclick');
	$('.phplist-subscribe-page form').submit( function(e){
		e.preventDefault();
		$obj= $(this);
		
		subscribe_page_url= $obj.prev('input[name^=phplist_specific_subscribe_page]').val();
		url= $obj.attr('action');
		data= $obj.serialize() + "&subscribe=" + encodeURIComponent($obj.find('input[type="submit"]').val()) + "&type=subscribe" + "&subscribe_page_url=" + encodeURIComponent(subscribe_page_url);
		PHC_PHPList_Newsletter_Ajax.type= "default";
		PHC_PHPList_Newsletter_Ajax.type= "alert";
		PHC_PHPList_Newsletter_Ajax.send(url, data);
	});
})