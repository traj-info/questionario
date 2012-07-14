<?php
	
	include 'user/user_auth.php'; //para gravar o log



	if (!isset($_SESSION['server_usuarioId']))
	{																# Verifica se nao ha a variavel da sessao que identifica o usuario
		destroiSessao();											# Destroi a sessao por seguranca
		echo "<script type='text/javascript'>
			  document.location.href='".LOGIN."'
			  </script>";   										# Redireciona o visitante de volta pro login
		exit;
	}
	
	
	$nivel_necessario = MOD_CONTROL_PANEL_NIVEL;																					#determina o nivel de acesso do usario (1-User, 2-Admin, 3-SuperAdmin)																											
	$status_necessario = MOD_USER_STATUS;																					#determina uma variavel de referencia ao status do user, onde: 1-Inativo, 2-Ativo, 3-Aprovado, 4-Negado																											
	
	if (!isset($_SESSION['server_usuarioId']) OR ($_SESSION['server_nivel'] < $nivel_necessario) OR ($_SESSION['server_status'] < $status_necessario)) {
		
		
		
		destroiSessao();		
		$msgErr = urlencode('Acesso Restrito.');															# Destroi a sessao por seguranca
		
		echo "<script type='text/javascript'>
			  document.location.href='".LOGIN."&msgErr=$msgErr'
			  </script>";																					# Redireciona o visitante para login
		exit;
	}
	

	



?>