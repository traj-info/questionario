<?php

	destroiSessao();
	

	$username = (empty($_POST['username']))?"":FilterData($_POST['username']);
	$password = (empty($_POST['password']))?"":sha1(FilterData($_POST['password']));
	
	if(!empty($username) && !empty($password))
	{
		$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND (user_status_id != ".INATIVO." AND user_status_id != ".NEGADO.") LIMIT 1";
		$login = $connection->GetResult($query);
		
		 $msgLogin = (!$login)?__("Usu&aacute;rio ou senha inv&aacute;lidos"):"";
		
	}
	else if(isset($_POST['username']) && isset($_POST['password']))
	{
		$login = null;
		$msgLogin = __("Usu&aacute;rio ou senha inv&aacute;lidos");
	}
	else 
	{
		$login = null;
		$msgLogin = null;
	}



	if (!$login) 
	{
?>	
	<h2><?php echo __("Login"); ?></h2>
	<form action="<?php echo LOGIN;?>&lang=<?php echo $_REQUEST['lang'];?>" method="post" name="frmLogin" id="frmLogin" class="frm_login frm">
	<p class="top-alert"><?php echo __("Se você não possui cadastro, "); ?><a href="<?php echo SIGNUP ?>&lang=<?php echo $lang; ?>"><?php echo __("cadastre-se."); ?></a></p>	
		<div id="login-holder">

			<div class="clear"></div>
			<?php if(isset($msgLogin) && $msgLogin != "") : ?>
			<div class="message_error" id="msgLogin">
				<?php echo $msgLogin;?>
			</div>
			<?php endif; ?>

			<div id="loginbox">

				<div id="login-inner">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<th><label for="lbl_username" id="lbl_username" class="frm_label"><?php echo __("Usuario");?></label></th>
							<td><input type="text" class="inp_txt frm_inp" id="username" name="username" />
							</td>
						</tr>
						<tr>
							<th><label for="lbl_password" id="lbl_password" class="frm_label"><?php echo __("Senha");?></label></th>
							<td><input type="password" value=""
								onfocus="this.value=''" class="login-inp" id="password"
								name="password" />
							</td>
						</tr>
						<tr>
							<th></th>
							<td><input type="submit" class="submit-login bt_submit frm_bt" value="<?php echo __("Enviar");?>" /></td>
						</tr>
					</table>
				</div>
				<!--  end #login-inner -->
				<div class="clear"></div>
			</div>
			<!--  end #loginbox -->

			<br><br>
			<div id="forgotbox">
				
				<a id="link-forgot" href="<?php echo INDEX;?>?module=user&page=forgot_password&lang=<?php echo $_REQUEST['lang'];?>"><?php echo __("Esqueci minha senha");?></a>				
			</div>
			<!--  end #forgotbox -->

		</div>
		<!-- End #login-holder -->

	</form>

<?php

	} else {
		
		
	
		destroiSessao();
		
		#Iniciar uma Sessao (session e similar a uma gaveta movel)
		session_start();
	
		#Atribuindo resultados da consulta a variaveis que serao utilizadas na sessao
		$_SESSION['idQuery'] = $login['id'];
		$_SESSION['userQuery'] = $login['username'];
		$_SESSION['credentialQuery'] = $login['credential_id'];
		$_SESSION['statusQuery'] = $login['user_status_id'];
		//$_SESSION['userLang'] = $login['lang'];
		//$_SESSION['callback'] = 'iniciaSessao';
		
		
		//redireciona(MAIN_USER."&idQuery=$idQuery&userQuery=$userQuery&credentialQuery=$credentialQuery&statusQuery=$statusQuery&callback=iniciaSessao&lang=".$userLang);
		redireciona(MAIN_USER.'&callback=iniciaSessao&lang='.$login['lang']);
	}



?>