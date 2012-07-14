<?php include 'control_panel/admin_auth.php'; ?>

<?php 

if(!empty($_REQUEST['recipient_id']))
{

?>

<?php
	$query = "SELECT * FROM recipients WHERE id = ".$_REQUEST['recipient_id'];
	
	$InfoRecipient = $connection->GetResult($query);
	$ListStatusEmail = unserialize(EMAIL_STATUS);
	
	if(is_array($InfoRecipient))
	{
?>

<h2><?php echo __("Informacoes sobre o destinatario");?></h2>
<p><em><?php echo __("NOTA: a contagem de visualizações baseia-se no carregamento de uma imagem no e-mail enviado. Se o software leitor de e-mail possuir mecanismo de bloqueio de carregamento de imagens, sua leitura não é computada."); ?></em></p><br>
<table class="list_log tabela" id="recipient_info">
	<tbody>
	<tr>
		<th><?php echo __("Nome")?></th>
		<td><?php echo $InfoRecipient['name']?></td>
	</tr>
	<tr>
		<th><?php echo __("Email")?></th>
		<td><?php echo $InfoRecipient['email']?></td>
	</tr>	
	<tr>
		<th><?php echo __("Total de visualizacoes")?></th>
		<td><?php echo $InfoRecipient['total_views']?></td>
	</tr>
	<tr>
		<th><?php echo __("Ultima visualizacao")?></th>
		<td><?php echo (empty($InfoRecipient['last_view']))?"-":$InfoRecipient['last_view'];?></td>
	</tr>
	<tr>
		<th><?php echo __("Status")?></th>
		<td><?php echo $ListStatusEmail[$InfoRecipient['send_verification']]?></td>
	</tr>
	<tr>
		<th><?php echo __("Data de criacao")?></th>
		<td><?php echo $InfoRecipient['created']?></td>
	</tr>
	</tbody>
</table>
<?php } ?>




<?php
	$query = "SELECT * FROM sentemails WHERE recipient_id = ".$_REQUEST['recipient_id']." ORDER BY date DESC";
	
	$ListSentEmail = $connection->GetAllResults($query);
	$ListStatusEmail = unserialize(EMAIL_STATUS);
	
	if(is_array($ListSentEmail))
	{
?>

<h2><?php echo __("Log de envio de e-mail")?></h2>


	<table class="list_log" id="sent_info">
		<thead>
			<tr>
				<th><?php echo __("Data")?></th>
				<th><?php echo __("Status")?></th>
			</tr>
		</thead>
		<tbody>
		<?php 
			foreach($ListSentEmail as $id => $content)
			{
		?>
			<tr>
				<td><?php echo $content['date']?></td>
				<td><?php echo $ListStatusEmail[$content['sent_status']]?></td>
			</tr>
		<?php 					
			}		
		?>		
		</tbody>
	</table>
	
<?php 
	}
	else 
	{ 
		echo __("Sem log de envio de e-mail");
	}
?>
	
	
<?php	
}

?>