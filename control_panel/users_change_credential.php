<?php include 'control_panel/admin_auth.php'; ?>
<?php 

if(!empty($_REQUEST['user_id']))
{
	
 

$_REQUEST['ConfirmChangeCredential'] = (isset($_REQUEST['ConfirmChangeCredential']))?$_REQUEST['ConfirmChangeCredential']:null;
$_REQUEST['CancelChangeCredential'] = (isset($_REQUEST['CancelChangeCredential']))?$_REQUEST['CancelChangeCredential']:null;

	if(isset($_REQUEST['ConfirmChangeCredential']) || isset($_REQUEST['CancelChangeCredential']))
	{
		if(!empty($_REQUEST['ConfirmChangeCredential']))
		{			
				if(isset($_REQUEST['user_id']))
				{	
					
					$query = "UPDATE users SET credential_id = ".$_REQUEST['credential']." WHERE id = ".$_REQUEST['user_id'];
					$retval = $connection->Query($query);		
					
				}
				else 
				{
					$retval = null;
					
				}
				
				#Verificacao da query, se nao houver retorno, abortar com mensagem de erro.
				if(! $retval )
				{ 
					$errorMessage = urlencode(__("Credencial não alterada. Tente novamente."));			
				
					#Chamaremos a funcao criada para direcionar as mensagens.
					errorMsg(INDEX, "control_panel", "users", $errorMessage, $_REQUEST['lang']);
				
				}
				#Caso contrario, exibir mensagem de sucesso
				else 
				{
					$successMessage = urlencode(__("Credencial alterada"));
						
					#Chamaremos a funcao criada para direcionar as mensagens.
					successMsg(INDEX, "control_panel", "users", $successMessage, $_REQUEST['lang']);
				}	
		}
		else 
		if(!empty($_REQUEST['CancelChangeCredential']))
		{
			
			$successMessage = urlencode(__("Operacao cancelada."));
					
			#Chamaremos a funcao criada para direcionar as mensagens.
			successMsg(INDEX, "control_panel", "users", $successMessage, $_REQUEST['lang']);	
		}	
		
	}
	
	
	$query = "SELECT * FROM users WHERE id = ".$_REQUEST['user_id'];
	$InfoUser = $connection->GetResult($query);
	
?>
<h2><?php echo __("Mudar n&iacute;vel de acesso do usu&aacute;rio")?></h2>
	<form name="form_credential_user" id="form_credential_user" class="form form_credential_user form_users" method="post" 
		action="index.php?module=control_panel&page=users_change_credential&lang=<?php echo $_REQUEST['lang'];?>">
		<table class="table_users table_form" id="table_users_credentials">
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
				<tr>
					<td><label class="table_item_description"><?php echo __("Credencial")?></label></td>
					<td>
					<?php 
						
						$TUsers = new TUsers($connection, $_SESSION['server_nivel']);
						$ListCredentials = $TUsers->ListCredentials();
						
						if(is_array($ListCredentials))
						{
							
							echo '<select name="credential" class="list_credential" id="credential">';
							foreach($ListCredentials as $key => $content)
							{
								if($InfoUser['credential_id'] == $key) $select = 'selected="selected"'; 
								else $select = "";
								
								echo '<option value="'.$key.'" '.$select.'>'.$content['description'].'</option>';
								
								
								
							}
							echo '</select>';
						}
					
					?>
					</td>
				</tr>
				
			</tbody>
		</table>
			<br><div id="bt_holder1">
		<p><?php echo __("Deseja mudar a credencial desse usuário?");?></p>
		<input type="hidden" name="user_id" value="<?php echo $_REQUEST['user_id'];?>">
		<input type="submit" name='ConfirmChangeCredential' value='Confirmar' class="bt_confirm">
		<input type="submit" name='CancelChangeCredential' value='Cancelar' class="bt_cancel"></div>
	</form>

<?php 
}

?>