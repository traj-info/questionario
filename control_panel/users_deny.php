<?php include 'control_panel/admin_auth.php'; ?>
<?php 

if(!empty($_REQUEST['id']))
{
	
	$query = "SELECT * FROM users WHERE id = ".$_REQUEST['id'];
	$InfoUser = $connection->GetResult($query);
	
?>
<h2><?php echo __("Bloquear usu&aacute;rio")?></h2>
	<form name="form_deny_user" id="form_deny_user" class="form form_deny_user form_users" method="post" 
			action="index.php?module=control_panel&page=users_deny&lang=<?php echo $_REQUEST['lang'];?>">
		<table class="table_users table_form" id="table_deny_user">
			<thead>
				<tr>
					<td colspan="2"><label class="table_title"></label></td>
				</tr>
			</thead>
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
		<p><?php echo __("Deseja negar acesso a esse usuario?");?></p>
		<input type="hidden" name="user_id" value="<?php echo $_REQUEST['id'];?>">
		<input type="submit" name='ConfirmDenyUser' value='Confirmar' class="bt_confirm">
		<input type="submit" name='CancelDenyUser' value='Cancelar' class="bt_cancel"></div>
	</form>

<?php 
}

$_REQUEST['ConfirmDenyUser'] = (isset($_REQUEST['ConfirmDenyUser']))?$_REQUEST['ConfirmDenyUser']:null;
$_REQUEST['CancelDenyUser'] = (isset($_REQUEST['CancelDenyUser']))?$_REQUEST['CancelDenyUser']:null;


	if($_REQUEST['ConfirmDenyUser'] || $_REQUEST['CancelDenyUser'])
	{
		if(!empty($_REQUEST['ConfirmDenyUser']))
		{
		
					
				if(isset($_REQUEST['user_id']))
				{	
					
					$query = "UPDATE users SET user_status_id = ".NEGADO." WHERE id = ".$_REQUEST['user_id'];
					$retval = $connection->Query($query);		
					
				}
				else 
				{
					$retval = null;
					
				}
				
				
					#Verificacao da query, se nao houver retorno, abortar com mensagem de erro.
				if(! $retval )
				{ 
					$errorMessage = urlencode(__("Usuario nao negado. Tente novamente."));			
				
					#Chamaremos a funcao criada para direcionar as mensagens.
					errorMsg(INDEX, "control_panel", "users", $errorMessage, $_REQUEST['lang']);
				
				}
				#Caso contrario, exibir mensagem de sucesso
				else 
				{
					$successMessage = urlencode(__("Usuario negado"));
						
					#Chamaremos a funcao criada para direcionar as mensagens.
					successMsg(INDEX, "control_panel", "users", $successMessage, $_REQUEST['lang']);
				}	
				
		}
		else if(!empty( $_REQUEST['CancelDenyUser']))
		{
				
			$successMessage = urlencode(__("Operacao cancelada."));
					
			#Chamaremos a funcao criada para direcionar as mensagens.
			successMsg(INDEX, "control_panel", "users", $successMessage, $_REQUEST['lang']);
		
		}
		
	}



?>