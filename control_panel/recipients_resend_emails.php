<?php include 'control_panel/admin_auth.php'; ?>

<?php 
if(!isset($_REQUEST['recipient'])){
	$errorMessage = urlencode(__('Selecione um destinatario'));
	#Chamaremos a funcao criada para direcionar as mensagens.
	errorMsg($constantUrl, 'control_panel', 'recipients', $errorMessage, $_REQUEST['lang']);
}
?>
<?php 
	$_REQUEST['lang'] = (isset($_REQUEST['lang']))?$_REQUEST['lang']:LANG_DEFAULT;

 	$SendStatus = unserialize(EMAIL_STATUS);
 	$LangList = unserialize(LANGS);

?>
<h2><?php echo __("Reenvio de e-mail")?></h2>
<br>
<form name="form_resend_recipients" id="form_resend_recipients" class="form form_resend_recipients form_recipients" method="post" 
	action="index.php?module=control_panel&page=recipients_resend_emails">
	<table class="table_recipients table_form tabela" id="table_resend_recipients">
		<thead>
			<tr>
				<th colspan="4"><label class="table_title"></label></th>
			</tr>		
		</thead>
		<tbody>
			<tr>
				<th><label class="table_item_description"><?php echo __("Nome")?></label></th>
				<th><label class="table_item_description"><?php echo __("E-mail")?></label></th>
				<th><label class="table_item_description"><?php echo __("Idioma")?></label></th>
				<th><label class="table_item_description"><?php echo __("Status")?></label></th>
			</tr>
			<?php
			 
			 	
				if(is_array($_REQUEST['recipient']))
				{	
					foreach ($_REQUEST['recipient'] as $id => $content)
					{
						$select = "SELECT * FROM recipients WHERE id = ".$content;
						$InfoRecipient = $connection->GetResult($select);
						
						if(is_array($InfoRecipient))
						{
			?>
			<tr>
				<td><?php echo $InfoRecipient['name'];?><input type="hidden" name="recipient[]" value="<?php echo $content;?>"></td>
				<td><?php echo $InfoRecipient['email'];?></td>
				<td><?php echo $LangList[$InfoRecipient['lang']];?></td>
				<td><?php echo $SendStatus[$InfoRecipient['send_verification']];?></td>
			</tr>
			<?php 
						}
					}
				}
			 ?>
		</tbody>
	</table>
	
	<br><div id="bt_holder1">
	<p><?php echo __("Deseja realmente reenviar o e-mail para esses destinatarios?");?></p>
	<input type="submit" name='ConfirmResendEmail' value='Confirmar' class="bt_confirm">
	<input type="submit" name='CancelResendEmail' value='Cancelar' class="bt_cancel"></div>
</form>

<?php 
$_REQUEST['CancelResendEmail'] = (isset($_REQUEST['CancelResendEmail']))?$_REQUEST['CancelResendEmail']:null;
$_REQUEST['ConfirmResendEmail'] = (isset($_REQUEST['ConfirmResendEmail']))?$_REQUEST['ConfirmResendEmail']:null;

if($_REQUEST['CancelResendEmail'] || $_REQUEST['ConfirmResendEmail'])
{
	if($_REQUEST['ConfirmResendEmail'])
	{
				
			$erro = 0;
			
			if(is_array($_REQUEST['recipient']))
			{	
				foreach ($_REQUEST['recipient'] as $id => $content)
				{
					$query = "UPDATE recipients SET send_verification = 0 WHERE id = ".$content;
					$retval = $connection->Query($query);	
					if(! $retval ) $erro++;			
				}
			}
			
			#Verificacao da query, se nao houver retorno, abortar com mensagem de erro.
			if($erro > 0 )
			{ 
				$errorMessage = urlencode(__("Falha na adicao dos destinatarios para reenvio de e-mail. Tente novamente."));			
			
				#Chamaremos a funcao criada para direcionar as mensagens.
				errorMsg(INDEX, 'control_panel', 'recipients', $errorMessage, $_REQUEST['lang']);
			
			}
			#Caso contrario, exibir mensagem de sucesso
			else 
			{
				$successMessage = urlencode(__("Destinatarios adicionados para reenvio de e-mail."));
					
				#Chamaremos a funcao criada para direcionar as mensagens.
				successMsg(INDEX, 'control_panel', 'recipients', $successMessage, $_REQUEST['lang']);
			}
			
	}
	else
	if($_REQUEST['CancelResendEmail'])
	{		
			$successMessage = urlencode(__("Reenvio cancelado com sucesso."));			
			
			#Chamaremos a funcao criada para direcionar as mensagens.
			successMsg(INDEX, 'control_panel', 'recipients', $successMessage, $_REQUEST['lang']);
		
	}	
	
}



?>
