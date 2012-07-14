$(function() {

			$(".bt_list_submit").click(function(){
				//passar lang via url
				var url_local = location.href;
				var index_lang = url_local.indexOf('lang=');
				
				var str_lang = url_local.substring(index_lang+5, index_lang+10);
				
				
				
				
				switch ($(this).attr('name'))
				{
				
				
					case 'DeleteRecipients':
						window.location.href = 'index.php?module=control_panel&page=recipients_delete&lang='+str_lang+'&'+$('.cb_list_submit').serialize();
						break;
					case 'ResendRecipients':
						window.location.href = 'index.php?module=control_panel&page=recipients_resend_emails&lang='+str_lang+'&'+$('.cb_list_submit').serialize();
						break;
						
					case 'ForgotPassword':
						window.location.href = 'index.php?module=user&page=forgot_password&lang='+str_lang;
						break;
					
					case 'SendReminder':
						window.location.href = 'index.php?module=control_panel&page=users_send_reminder&lang='+str_lang+'&'+$('.cb_list_submit').serialize();
						break;
						
				}
				
								
			});
			
			
			ControlButtonDataGridRecipients();
			
			

		
			
});
	
		
function ControlButtonDataGridRecipients()
{

	var n = $(".cbox_recipient").length;
	
	if(n > 0)
	{
		$("#DeleteRecipients").show();
		$("#ResendRecipients").show();
	}
	else
	{
		$("#DeleteRecipients").hide();
		$("#ResendRecipients").hide();	
	}
}
