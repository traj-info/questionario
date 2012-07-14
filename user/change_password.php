<?php include 'user/user_auth.php'; ?>
<?php 
#Verificar se formulario foi submetido
if(isset($_POST['alterarSenha']) && $_POST['alterarSenha'] != "") 
{
	
	#Obtendo id de sessao do usuario atual
	$id_session = $_SESSION['server_usuarioId'];

	#Obtendo valor do campo senhaNova para validacao de redigitacao
	$senhaNova = trim(FilterData($_POST['senhaNova']));
	$senhaNovaValidate = trim(FilterData($_POST['senhaNova2']));

	#Obtem a senha atual do usuario
	$senhaAtual = sha1($_POST['senhaAtual']);

	if(empty($senhaNova) || empty($_POST['senhaNova']) || empty($_POST['senhaNova2']))
	{
		$errorMessage = urlencode(__('Senhas invalidas'));
		
		#Chamaremos a funcao criada para direcionar as mensagens.
		errorMsg($constantUrl, $_REQUEST['module'], $_REQUEST['page'], $errorMessage, $_REQUEST['lang']);
		
	}	
	else
	
	#Caso as senhas sejam incompativeis, abortar com mensagem de erro
	if ($senhaNova != $senhaNovaValidate) 
	{
		$errorMessage = urlencode(__('Senhas incompativeis, redigite.'));
		
		#Chamaremos a funcao criada para direcionar as mensagens.
		errorMsg($constantUrl, $_REQUEST['module'], $_REQUEST['page'], $errorMessage, $_REQUEST['lang']);
	}
	else 
	{
		
		if(empty($id_session))
		{
			$errorMessage = urlencode(__('Senhas incompativeis, redigite.'));
		
			#Chamaremos a funcao criada para direcionar as mensagens.
			errorMsg($constantUrl, $_REQUEST['module'], $_REQUEST['page'], $errorMessage, $_REQUEST['lang']);
		}
		
		#Define a nova senha de acordo com o que foi digitado no campo de id 'senhaNova'
		$senhaNovaCript = sha1($_POST['senhaNova']);

		#Realiza a consulta no banco onde o filtro precisa ser igual a senha atual
		$query = "SELECT password FROM users WHERE id = '$id_session'";
		$result = $connection->GetResult($query);

		#Obtem valores do resultado da consulta e armazena em variaveis
		$passQuery = $result['password'];
		$modified = NowDatetime();
	
		#Validacao de Passwords
		if ($passQuery == $senhaAtual) 
		{

			#Altera a senha na base dados
			$queryPass = "UPDATE users SET password = '$senhaNovaCript', modified = '$modified' WHERE id = $id_session LIMIT 1";
		
			$retval = $connection->Query($queryPass);

			#Se a Query constatar erro, abortar conexao, caso contrario, mensagem de sucesso
			if (!$retval)
			{
				$errorMessage = urlencode(__('Informacoes nao alteradas.'));
		
				#Chamaremos a funcao criada para direcionar as mensagens.
				errorMsg($constantUrl, $_REQUEST['module'], $_REQUEST['page'], $errorMessage, $_REQUEST['lang']);
			}
			else
			{
				$successMessage = urlencode(__('Sua senha foi modificada com sucesso'));

				#Chamaremos a funcao criada para direcionar as mensagens.
				successMsg($constantUrl, MOD_USER_DEFAULT, PAGE_USER_DEFAULT, $successMessage, $_REQUEST['lang']);
			}
			 	
		}
		else
		{
			$errorMessage = urlencode(__('Senhas nao coincidem'));

			#Chamaremos a funcao criada para direcionar as mensagens.
			errorMsg($constantUrl, $_REQUEST['module'], $_REQUEST['page'], $errorMessage, $_REQUEST['lang']);
		}	
	}
}
?>

<h2><?php echo __("Alterar Senha");?></h2>

<form action="index.php?module=user&page=change_password" method="post" id="frmChangePassword" name="frmChangePassword" class="frm">
	<label for="senhaAtual" id="lblAtual" class="frm_label"><?php echo __("Informe sua senha atual"); ?>:</label>
	<input type="password" id="senhaAtual" name="senhaAtual" class="frm_inp inp_pwd" />
	<br/>
	
	<label for="senhaNova" id="lblNova"><?php echo __("Nova senha"); ?>:</label>
	<input type="password" id="senhaNova" name="senhaNova" class="frm_inp inp_pwd" />
	<br/>
	
	<label for="senhaNova2" id="lblNova2"><?php echo __("Digite novamente"); ?>:</label>
	<input type="password" id="senhaNova2" name="senhaNova2" class="frm_inp inp_pwd" />
	<br/>
	
	<input type="submit" value="<?php echo __("Alterar"); ?>" id="alterarSenha" name="alterarSenha" class="bt_submit frm_bt"/>

</form>
