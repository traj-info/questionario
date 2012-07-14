<form action="index.php?module=user&page=signup&lang=<?php echo $_REQUEST['lang'];?>" method="post" name="frmSignup" id="frmSignup" class="frm_signup frm">
    <h2><?php echo __("Formulario de Cadastro");?></h2>

	<p class="top-alert"><?php echo __("Se você já possui cadastro, "); ?><a href="<?php echo LOGIN ?>&lang=<?php echo $lang; ?>"><?php echo __("efetue seu login."); ?></a></p>
	
	<label for="name" id="lblName" class="frm_label"><?php echo __("Nome:");?> </label> 
	<input type="text" name="name" id="name" class="inp_txt frm_inp" /> 
	<br /> 
	
	<label for="surname" id="lblSurname" class="frm_label"><?php echo __("Sobrenome:");?></label> 
	<input type="text" name="surname" id="surname" class="inp_txt frm_inp"/> 
	<br /> 
	
	<label for="user" id="lblUser" class="frm_label"><?php echo __("Username:");?> </label> 
	<input type="text" name="user" id="user" class="inp_txt frm_inp"  /> 
	<br /> 
	
	<label for="emailCadastro"	id="lblEmailCadastro" class="frm_label"><?php echo __("Email:");?> </label> 
	<input type="text" name="emailCadastro" id="emailCadastro" class="inp_email frm_inp email" /> 
	<br /> 
	
	<label for="pass" id="lblPass" class="frm_label"><?php echo __("Senha:");?></label> 
	<input type="password" name="pass"	id="pass" class="inp_pwd frm_inp" /> 
	<br /> 
	
	<label for="pass2" id="lblPass2" class="frm_label"><?php echo __("Digite sua senha novamente:");?> </label> 
	<input type="password" name="pass2" id="pass2" class="inp_pwd frm_inp"/>
	<br /> 
	<div class="clear"></div>
	<input type="submit" value="<?php echo __("Cadastrar");?>" id="submit" name="submit" class="bt_submit frm_bt" />

</form>
<?php

/**
 * @since 17/Maio/2012
 * @package Modelo Questionario
 * Caso contrario, realizar todo o procedimento de cadastro
 * 1 - Verificar se o usuario ja existe, caso exista, abortar procedimento com mensagem de erro.
 * 2 - Inserir dados preenchidos na base de dados.
 */

#Verificar se formulario foi submetido
if(isset($_POST['submit']) && $_POST['submit'] != "") 
{

	#Obter valores do formulario utilizando funcoes TRIM(Quebra de espaco) e FilterData(Evitar Injection)
	$nome = trim(FilterData($_POST['name']));
	$sobrenome = trim(FilterData($_POST['surname']));
	$username = trim(FilterData($_POST['user']));
	$password = sha1(trim(FilterData($_POST['pass'])));
	$passwordValidate = sha1(trim(FilterData($_POST['pass2'])));
	$created = NowDatetime();
	$mail_to = trim(FilterData($_POST['emailCadastro']));
	
	#Definindo variaveis que serao utilizadas nas funcoes de errorMsg e successMsg.
	$constantUrl = LOCAL;
	$module = "user";
	$page = "signup";

	#Variavel que gera a chave para ativacao de email
	$chave = create_guid();

	#Variaveis que serao utilizadas no SendMail()
	$toName = $nome;
	$toMail = $mail_to;
	$subject = __("Ativacao da Conta");
	$body = "<p>".__("Caro(a)")." $toName</p><br/>";
	$body .= "<p>".__("O link abaixo ativara sua conta. Clique no link ou copie e cole o endereco no seu navegador.")."</p> <br/>";
	$body .= "<p>Link: <a href='".INDEX."?module=user&page=activate&key=$chave&lang=".$_REQUEST['lang']."'>".INDEX."?module=user&page=activate&key=$chave&lang=".$_REQUEST['lang']."</a></p>";
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
	
	
	

	preg_match('/[^A-Za-z0-9]/', $username, $matches);
	
	if(count($matches) > 0)
	{

		$errorMessage = urlencode(__('Usuário inválido. Não utilize espaço ou caracteres especiais.'));
		
		#Chamaremos a funcao criada para direcionar as mensagens.
		errorMsg($constantUrl, $module, $page, $errorMessage, $_REQUEST['lang']);
		
	}
	else 	
	#Se os campos de senha forem incompativeis, abortar com mensagem de erro
	if ($password != $passwordValidate || empty($_POST['pass'])) 
	{
		$errorMessage = urlencode(__('Senhas incompativeis, redigite.'));
		
		#Chamaremos a funcao criada para direcionar as mensagens.
		errorMsg($constantUrl, $module, $page, $errorMessage, $_REQUEST['lang']);
	}
	else
	{
		
		/*
		 * 1 - Verificar se o usuario ja existe, caso exista, abortar procedimento com mensagem de erro.
		 * 2 - Inserir dados preenchidos na base de dados.
		 */
			
			if(empty($username))
			{
				$errorMessage = urlencode(__('Usuario invalido.'));
				
				#Chamaremos a funcao criada para direcionar as mensagens.
				errorMsg($constantUrl, $module, $page, $errorMessage, $_REQUEST['lang']);
			}
			else if(empty($mail_to) || validaEmail($mail_to) == false)
			{
				$errorMessage = urlencode(__('Email invalido.'));
				
				#Chamaremos a funcao criada para direcionar as mensagens.
				errorMsg($constantUrl, $module, $page, $errorMessage, $_REQUEST['lang']);
			}
			else 
			{
				$query = "SELECT count(*) as contador FROM users WHERE username = '".$username."'";
				$list_user = $connection->GetResult($query);
				
				if($list_user['contador'] > 0)
				{
					$errorMessage = urlencode(__('Usuario j&aacute; existe.'));
				
					#Chamaremos a funcao criada para direcionar as mensagens.
					errorMsg($constantUrl, $module, $page, $errorMessage, $_REQUEST['lang']);
				}
				else 
				{
					$query = "SELECT count(*) as contador FROM users WHERE email = '".$mail_to."'";
					$list_email = $connection->GetResult($query);
					
					if($list_email['contador'] > 0)
					{
						$errorMessage = urlencode(__('E-mail j&aacute; cadastrado.'));
				
						#Chamaremos a funcao criada para direcionar as mensagens.
						errorMsg($constantUrl, $module, $page, $errorMessage, $_REQUEST['lang']);
					}
					else
					{
						
								$sent = SendMail($toName, $toMail, $subject, $body);
								
								if (!$sent) 
								{
									$errorMessage = urlencode(__('Falha no envio de email. Tente novamente, por favor.'));
									
									#Chamaremos a funcao criada para direcionar as mensagens.
									errorMsg($constantUrl, $module, $page, $errorMessage, $_REQUEST['lang']);
								}
								else 
								{
									#Caso contrario, inserir os dados no banco de dados      (Inserir key tambem)
									$sql = "	INSERT INTO  
													`users` 
												(  
													`name` ,  
													`lastname` ,  
													`username` ,  
													`password` ,  
													`user_status_id` ,  
													`credential_id` ,  
													`created` ,  
													`modified` ,  
													`key`,
													`email`,
													`lang`
												)
												VALUES 
												(
													'$nome',  
													'$sobrenome',  
													'$username', 
													'$password',  
													".INATIVO.",  
													".USER.",  
													'$created', 
													NULL ,  
													'$chave',
													'$mail_to',
													'".$_REQUEST['lang']."'
												)";
								
								
								
									#Commit da query de insert
									$retval = $connection->Query($sql);
								
									#Verificacao da query, se nao houver retorno, abortar com mensagem de erro.
									if(! $retval )
									{ 
										$errorMessage = urlencode(__('Falha no cadastro. Tente novamente, por favor.'));
									
										#Chamaremos a funcao criada para direcionar as mensagens.
										errorMsg($constantUrl, $module, $page, $errorMessage, $_REQUEST['lang']);
										
									}
									#Caso contrario, exibir mensagem de sucesso
									else
									{
										
										
										$successMessage = urlencode(__("Seu cadastro foi realizado com sucesso e um email foi enviado para ativacao"));
											
										#Chamaremos a funcao criada para direcionar as mensagens.
										successMsg($constantUrl, $module, $page, $successMessage, $_REQUEST['lang']);
										
										
									}
							}
								
						
					}
					
				}
			}
			
	}

}

?>