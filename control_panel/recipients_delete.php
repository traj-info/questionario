<?php include 'control_panel/admin_auth.php'; ?>
<?php 
if(!isset($_REQUEST['recipient'])){
	$errorMessage = urlencode(__('Selecione um destinatario'));
	#Chamaremos a funcao criada para direcionar as mensagens.
	errorMsg($constantUrl, 'control_panel', 'recipients', $errorMessage, $_REQUEST['lang']);
}
?>
<?php 

$_REQUEST['ConfirmRecipiensDelete'] = (isset( $_REQUEST['ConfirmRecipiensDelete']))? $_REQUEST['ConfirmRecipiensDelete']:null;
$_REQUEST['CancelRecipiensDelete'] = (isset( $_REQUEST['CancelRecipiensDelete']))? $_REQUEST['CancelRecipiensDelete']:null;

if($_REQUEST['ConfirmRecipiensDelete'] || $_REQUEST['CancelRecipiensDelete'])
{
		if(!empty($_REQUEST['ConfirmRecipiensDelete']))
		{		
			$erro = 0;
			
			if(is_array($_REQUEST['recipient']))
			{	
				foreach ($_REQUEST['recipient'] as $id => $content)
				{
					$query = "DELETE FROM recipients WHERE id = ".$content;
					$retval = $connection->Query($query);
				
					if(! $retval ) $erro++;									
				}
			}
			
			#Verificacao da query, se nao houver retorno, abortar com mensagem de erro.
			if($erro > 0 )
			{ 
				$errorMessage = urlencode(__("Destinatario(s) n&atilde;o excluido(s). Tente novamente."));			
			
				#Chamaremos a funcao criada para direcionar as mensagens.
				errorMsg(INDEX, 'control_panel', 'recipients', $errorMessage, $_REQUEST['lang']);
			
			}
			#Caso contrario, exibir mensagem de sucesso
			else 
			{
				$successMessage = urlencode(__("Destinat&aacute;rio(s) exclu&iacute;do(s) com sucesso"));
					
				#Chamaremos a funcao criada para direcionar as mensagens.
				successMsg(INDEX, 'control_panel', 'recipients', $successMessage, $_REQUEST['lang']);
			}
			
		}	
		else 
		if(!empty($_REQUEST['CancelRecipiensDelete']))
		{
			
			$successMessage = urlencode(__("Exclus&atilde;o cancelada com sucesso."));			
			
			#Chamaremos a funcao criada para direcionar as mensagens.
			successMsg(INDEX, 'control_panel', 'recipients', $successMessage, $_REQUEST['lang']);
			
			
		}	
	
}

else
{

?>
<?php 

 	$SendStatus = unserialize(EMAIL_STATUS);
 	$LangList = unserialize(LANGS);

?>
<h2><?php echo __("Excluir Destinatário(s)"); ?></h2>
<form name="form_delete_recipients" id="form_delete_recipients" class="form form_delete_recipients form_recipients" 
	method="post" action="index.php?module=control_panel&page=recipients_delete&lang=<?php echo $_REQUEST['lang'];?>">
	
	<table class="table_recipients table_form tabela" id="table_delete_recipients">
		<thead>
			<tr>
				<th colspan="4"></th>
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
			 
			 	
				if(isset($_REQUEST['recipient']) && is_array($_REQUEST['recipient']))
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
			<?php 		}
					}
				}
			 ?>
		</tbody>
	</table>
	
	<br>
	<p><?php echo __("Deseja realmente excluir esses destinatarios?");?></p>
	<input type="submit" name='ConfirmRecipiensDelete' value='<?php echo __("Confirmar");?>' class="bt_confirm">
	<input type="submit" name='CancelRecipiensDelete' value='<?php echo __("Cancelar");?>' class="bt_cancel">
</form>
<?php 
}
?>
