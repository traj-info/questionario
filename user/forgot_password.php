<h2><?php echo __("Esqueci minha senha");?></h2>
<form action="index.php?module=user&page=forgot_password&lang=<?php echo $_REQUEST['lang'];?>" method="post" name="frmForgot" id="frmForgot" class="frm_signup frm">
	<label for="email" id="email" class="frm_label"><?php echo __("Informe seu email"); ?></label> 
	<input type="text"	name="email" id="email" class="inp_email"/> 
	<input type="submit" name="enviar" id="enviar" value="<?php echo __("Enviar"); ?>" class="bt_submit frm_bt" />
</form>

<?php 

if(isset($_POST['enviar']) && $_POST['enviar'] != "") 
{
	
	#Recuperando email digitado no formulario
	$mail_to = FilterData($_POST['email']);				
	
	#Consulta
	$query = "SELECT id, name, username FROM users WHERE email = '$mail_to' AND credential_id != ".SUPERADMIN." LIMIT 1";
	$result = $connection->GetResult($query);

	#Verificar se o email digitado consta no banco, se nao existir, exibir mensagem de erro
	if (!$result) 
	{
		$errorMessage = urlencode(__('Seu email nao foi encontrado na base de dados'));
		
		#Chamaremos a funcao criada para direcionar as mensagens.
		errorMsg($constantUrl, $module, $page, $errorMessage, $_REQUEST['lang']);
	}
	else 
	{

		$idQuery = $result['id'];
		$nameQuery = $result['name'];
		$userQuery = $result['username'];
		$toName = $nameQuery;
		
		#Variavel senha temporaria e uma funcao de util.php que gera caracteres aleatorios
		$senhaTemporaria = geraSenha(10);

		#Variaveis que serao utilizadas no SendMail()
		$toMail = $mail_to;
		$subject = __("Alteracao de Senha");
		$body = "<p>".__("Ola"). ", " . $nameQuery . "</p><br/>";
		$body .= "<p>".__("Recebemos a solicitacao de mudanca de senha e abaixo segue uma nova senha temporaria junto de seu username").": <br/>";
		$body .=  __("Usuario").": <strong>". $userQuery."</strong><br> ".__("Senha").": <strong>$senhaTemporaria</strong></p>";
		$body .= "<p>".__("Apos realizar o login no sistema, por gentileza altere a senha novamente por questoes de seguranca.")."</p><br/>";
		$body .= "<hr><p><strong>" . __("Pesquisa sobre a prática da Ecoendoscopia (EUS) na América Latina") . "</strong></p><br>";
		$body .= "<p><strong>" . __("Diretoria do CLEUS 2010-2012") . "</strong>";
		$body .= "<br />" . __("Cecilia Castillo (Chile)");
		$body .= "<br />" . __("José Ricardo Ruíz Obaldía (Panamá)");
		$body .= "<br />" . __("Lucio G. B. Rossini (Brasil)");
		$body .= "<br />" . __("Wallia Wever (Venezuela)") . "</p><Br>";
		$body .= "<p><strong>" . __("Colaboradores da pesquisa") . "</strong>";
		$body .= "<br />" . __("Juliana Marques Drigo (Brasil)");
		$body .= "<br />" . __("Sheila Fillipi (Brasil)") . "</p><br>";
		$body .= "<p>" . __("Inicialmente este questionário foi enviado a todos os médicos que se tornaram amigos do CLEUS/SIED (Capítulo Latinoamericano de Ecoendoscopia - ") . '<strong><a href="http://www.cleus.org" target="_blank">www.cleus.org</a></strong>' . __(" / Sociedade Interamericana de Endoscopia Digestiva - ") . '<strong><a href="http://www.e-sied.org" target="_blank">www.e-sied.org</a></strong>' . __(").") . "</p><br>";
		$body .= "<p><strong>" . __("Solicitamos seu apoio incentivando os seus colegas que realizam EUS e ainda não são amigos do CLEUS a preencher este questionário. Divulgue aos seus colegas nosso endereço!") . "</strong></p><br>";
		$body .= "<p><strong>" . __("Se você tem alguma dúvida, por favor, não hesite em nos comunicar: ") . '<br/><a href="mailto:cleus.encuesta@gmail.com" target="_blank">cleus.encuesta@gmail.com</a></strong></p><br><br>';
		
		$modified = NowDatetime();		
		$senhaCriptograda = sha1($senhaTemporaria);

		#Realizar Query para update de senha na base de dados
		$queryPass = "UPDATE users SET password = '$senhaCriptograda', modified = '$modified' WHERE id = $idQuery LIMIT 1";

		#Commit da query de insert
		$retval = $connection->Query($queryPass);

		if (!$retval)
		{
			$errorMessage = urlencode(__('Nova senha nao foi gerada. Tente novamente, por favor'));

			#Chamaremos a funcao criada para direcionar as mensagens.
			errorMsg($constantUrl, $module, $page, $errorMessage, $_REQUEST['lang']);
		}
		
		/**  ---------------- SEND MAIL FORM ----------------
		 * 	Utilizando funcao SendMail com PHPMailer do arquivo util.php
		 */
		$sent = SendMail($toName, $toMail, $subject, $body);
		
		if (!$sent) 
		{
			$errorMessage = urlencode(__('Falha no envio de email. Tente novamente'));

			#Chamaremos a funcao criada para direcionar as mensagens.
			errorMsg($constantUrl, $module, $page, $errorMessage, $_REQUEST['lang']);
			
		} 
		else 
		{
			$successMessage = urlencode(__('Operacao realizada com sucesso, email enviado.'));
			
			#Chamaremos a funcao criada para direcionar as mensagens.
			successMsg($constantUrl, $module, $page, $successMessage, $_REQUEST['lang']);
		}

	}
}

?>
