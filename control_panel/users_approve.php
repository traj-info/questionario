<?php include 'control_panel/admin_auth.php'; ?>
<?php 

if(!empty($_REQUEST['id']))
{
	
	$query = "SELECT * FROM users WHERE id = ".$_REQUEST['id'];
	$InfoUser = $connection->GetResult($query);
	
	
?>
<h2><?php echo __("Aprovar usuário"); ?></h2>
	<form name="form_approve_user" id="form_approve_user" class="form form_approve_user form_users" method="post" 
		action="index.php?module=control_panel&page=users_approve&lang=<?php echo $_REQUEST['lang'];?>">
		<table class="table_users table_form" id="table_approve_user">
			<tbody>
				<tr>
					<td><label class="table_item_description"><?php echo __("Nome")?></label></td>
					<td><?php echo $InfoUser['name'];?></td>
				</tr>	
				<tr>
					<td><label class="table_item_description"><?php echo __("Sobrenome")?></label></td>
					<td><?php echo $InfoUser['lastname'];?></td>
				</tr>	
				<tr>
					<td><label class="table_item_description"><?php echo __("Usuario")?></label></td>
					<td><?php echo $InfoUser['username'];?></td>
				</tr>		
				<tr>
					<td><label class="table_item_description"><?php echo __("E-mail")?></label></td>
					<td><?php echo $InfoUser['email'];?></td>
				</tr>
			</tbody>
		</table>
			<br><div id="bt_holder1">
		<p><?php echo __("Deseja aprovar esse usuario?");?></p>
		<input type="hidden" name="user_id" value="<?php echo $_REQUEST['id'];?>">
		<input type="submit" name='ConfirmApproveUser' value="<?php echo __("Confirmar");?>" class="bt_confirm">
		<input type="submit" name='CancelApproveUser' value="<?php echo __("Cancelar");?>" class="bt_cancel">
		</div>
	</form>
<?php 

}
?>
<?php 


$_REQUEST['ConfirmApproveUser'] = (isset($_REQUEST['ConfirmApproveUser']))?$_REQUEST['ConfirmApproveUser']:null;
$_REQUEST['CancelApproveUser'] = (isset($_REQUEST['CancelApproveUser']))?$_REQUEST['CancelApproveUser']:null;


   //usar nomes dos botoes pq ao traduzi-los, nao sera possivel saber o valor
	if($_REQUEST['ConfirmApproveUser'] || $_REQUEST['CancelApproveUser'])
	{
		if(!empty($_REQUEST['ConfirmApproveUser']))
		{
					
				if(isset($_REQUEST['user_id']))
				{	
					
					$query = "UPDATE users SET user_status_id = ".APROVADO." WHERE id = ".$_REQUEST['user_id'];
					$retval = $connection->Query($query);		
				
				}
				else 
				{
					$retval = null;
					
				}
				
				
				#Verificacao da query, se nao houver retorno, abortar com mensagem de erro.
				if(! $retval )
				{ 
					$errorMessage = urlencode(__("Usuario nao aprovado. Tente novamente."));			
				
					#Chamaremos a funcao criada para direcionar as mensagens.
					errorMsg(INDEX, "control_panel", "users", $errorMessage, $_REQUEST['lang']);
				
				}
				#Caso contrario, exibir mensagem de sucesso
				else 
				{
					$successMessage = urlencode(__("Usuario aprovado"));
						
					#Chamaremos a funcao criada para direcionar as mensagens.
					successMsg(INDEX, "control_panel", "users", $successMessage, $_REQUEST['lang']);
				}	
				
				
		}		
		else if(!empty($_REQUEST['CancelApproveUser']))
		{
		
			$successMessage = urlencode(__("Operacao cancelada."));
					
			#Chamaremos a funcao criada para direcionar as mensagens.
			successMsg(INDEX, "control_panel", "users", $successMessage, $_REQUEST['lang']);
		
		}	
		
	}



?>