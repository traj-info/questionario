<?php include 'user/user_auth.php'; ?>
	
<?php 

#Obtendo o id do usuario iniciado na sessao

	


if(($_SESSION['server_nivel'] >= MOD_CONTROL_PANEL_NIVEL) &&  isset($_SESSION['server_usuarioId']))	
{
	$user_id = (empty($_REQUEST['user_id']))?$_SESSION['server_usuarioId']:$_REQUEST['user_id'];
}
else
{
	$user_id = $_SESSION['server_usuarioId'];
}

if(isset($_POST['edit_data']) && ($_POST['edit_data'] != "")) 
{
		
	#Obtendo valores dos inputs e armazenando em variaveis que serao persistidas no banco de dados(Query)
	$nomeAlterado = (isset($_POST['editarNome']))?FilterData($_POST['editarNome']):null;
	$sobrenomeAlterado = (isset($_POST['editarSobreNome']))?FilterData($_POST['editarSobreNome']):null;
	$userAlterado = (isset($_POST['editarUser']))?FilterData($_POST['editarUser']):null;
	$emailAlterado = (isset($_POST['editarEmail']))?FilterData($_POST['editarEmail']):null;
	$langAlterado = (isset($_POST['editarLang']))?FilterData($_POST['editarLang']):null;
	
	$modified = NowDatetime();
	
	if(empty($userAlterado))
	{
		$errorMessage = urlencode(__('Usu&aacute;rio n&atilde;o pode estar em branco.'));
		
		#Chamaremos a funcao criada para direcionar as mensagens.
		errorMsg($constantUrl, $_REQUEST['module'], $_REQUEST['page'], $errorMessage, $langAlterado, '&user_id='.$user_id);
	}
	else if(validaEmail($emailAlterado) == false)
	{
		
		$errorMessage = urlencode(__('E-mail inv&aacute;lido'));
		
		#Chamaremos a funcao criada para direcionar as mensagens.
		errorMsg($constantUrl, $_REQUEST['module'], $_REQUEST['page'], $errorMessage, $langAlterado, '&user_id='.$user_id);
	}
	else 
	{
		
		
		
		
		
		
		
		
		$query = "SELECT count(*) as contador FROM users WHERE username = '".$userAlterado."' AND id != $user_id";
				$list_user = $connection->GetResult($query);
				
				if($list_user['contador'] > 0)
				{
					$errorMessage = urlencode(__('Usuario j&aacute; existe.'));
				
					#Chamaremos a funcao criada para direcionar as mensagens.
					errorMsg($constantUrl, $_REQUEST['module'], $_REQUEST['page'], $errorMessage, $langAlterado, '&user_id='.$user_id);
				}
				else 
				{
					$query = "SELECT count(*) as contador FROM users WHERE email = '".$emailAlterado."' AND id != $user_id";
					
					
					$list_email = $connection->GetResult($query);
					
					if($list_email['contador'] > 0)
					{
						$errorMessage = urlencode(__('E-mail j&aacute; cadastrado.'));
				
						#Chamaremos a funcao criada para direcionar as mensagens.
						errorMsg($constantUrl, $_REQUEST['module'], $_REQUEST['page'], $errorMessage, $langAlterado, '&user_id='.$user_id);
					}
					else
					{
	
						#Realizando Query de update
						$editQuery = " UPDATE users SET 
											name = '$nomeAlterado',
											lastname = '$sobrenomeAlterado', 
											username = '$userAlterado',
											email = '$emailAlterado',
											lang = '$langAlterado',
											modified = '$modified' 
										WHERE id = $user_id 
										LIMIT 1";
						
						#Commit da query de update
						$retval = $connection->Query($editQuery);
						
						#Se nao houver retorno, abortar conexao.
						if (!$retval)
						{
							$errorMessage = urlencode(__('Informacoes nao alteradas.'));
							
							#Chamaremos a funcao criada para direcionar as mensagens.
							errorMsg($constantUrl, $_REQUEST['module'], $_REQUEST['page'], $errorMessage, $langAlterado);
						}
						else
						{
							if(($_SESSION['server_nivel'] >= MOD_CONTROL_PANEL_NIVEL) &&  isset($_SESSION['server_usuarioId']) && $user_id != $_SESSION['server_usuarioId'])	
							{
								$successMessage = urlencode(__("Alteracoes realizadas com sucesso"));
								
								#Chamaremos a funcao criada para direcionar as mensagens.
								successMsg($constantUrl, 'control_panel', 'users', $successMessage, $langAlterado);
							}
							else 
							{
								$successMessage = urlencode(__("Alteracoes realizadas com sucesso"));
								
								#Chamaremos a funcao criada para direcionar as mensagens.
								successMsg($constantUrl, MOD_USER_DEFAULT, PAGE_USER_DEFAULT, $successMessage, $langAlterado, '&user_id='.$user_id);
							}
						}
					}
				}
	}
}
?>
<?php

	

	#Consultando na tabela usuarios o respectivo id(id_user)
	$query = "SELECT * FROM users WHERE id = $user_id LIMIT 1";
	
	#Retorno da consulta armazenado em variavel
	$consulta = $connection->GetResult($query);

	#Valores retornardos do array sao armazenados em respectivas variaveis sugestivas
	$nameQuery = $consulta['name'];
	$lastnameQuery = $consulta['lastname'];
	$userQuery = $consulta['username'];
	$emailQuery = $consulta['email'];
	$langQuery = $consulta['lang'];
	$passQuery = $consulta['password'];

?>

<h2><?php echo __("Editar Dados");?></h2>

<form action="index.php?module=user&page=edit_data" method="post" id="frmEdit" name="frmEdit" class="frm_edit frm">

<?php 
if($user_id == $_SESSION['server_usuarioId'])	
{
	
?>
<p><a href="index.php?module=user&page=change_password&lang=<?php echo $_REQUEST['lang'];?>"><?php echo __("Para alterar senha, clique aqui.");?></a>
<?php }?>
</p>
	<table>
		<tr>
			<td><label for="editarNome" id="lblNomeEdit"><?php echo __("Nome:")?></label></td>
			<td><input type="text" id="editarNome" name="editarNome" value="<?php echo $nameQuery; ?>" /> </td>
		</tr>

		<tr>
			<td><label for="editarSobreNome" id="lblSobrenomeEdit"><?php echo __("Sobrenome:")?> </label></td>
			<td><input type="text" id="editarSobreNome" name="editarSobreNome" value="<?php echo $lastnameQuery; ?>" /> </td>
		</tr>

		<tr>
			<td><label for="editarUser" id="lblUserEdit"><?php echo __("Usuario:")?></label></td>
			<td><input type="text" id="editarUser" name="editarUser" value="<?php echo $userQuery; ?>" /> </td>
		</tr>
		<tr>
			<td><label for="editarEmail" id="lblUserEdit"><?php echo __("E-mail:")?></label></td>
			<td><input type="text" id="editarEmail" name="editarEmail" value="<?php echo $emailQuery; ?>" /> </td>
		</tr>
		<tr>
			<td><label for="editarLang" id="lblUserEdit"><?php echo __("Idioma:")?></label></td>
			<td>
				<?php 
					
					$ListLang = unserialize(LANGS);
					
					if(is_array($ListLang))
					{
						
						echo '<select name="editarLang" class="list_lang" id="lang">';
						foreach($ListLang as $key => $content)
						{
							if($key == $langQuery) $selected = 'selected="selected"';
							else $selected = "";
							
							echo '<option value="'.$key.'" '.$selected.'>'.$content.'</option>';
							
						}
						echo '</select>';
					}
				
				?>
				</td>
		</tr>
	</table>	
	<br>
	<input type="hidden" value="<?php echo $user_id?>" name="user_id">
	<input type="submit" value="<?php echo __("Salvar alteracoes");?>" id="edit_data" name="edit_data" />
</form>

