<?php

$_REQUEST['lang'] = (empty($_REQUEST['lang']))?LANG_DEFAULT:$_REQUEST['lang'];

if(isset($_REQUEST['callback'])) redireciona(MAIN_USER.'&lang='.$_REQUEST['lang']);




# Verifica se nao ha a variavel da sessao que identifica o usuario
if (!isset($_SESSION['server_usuarioId'])) 
{															
	# Destroi a sessao por seguranca
	destroiSessao();																					
	echo "<script type='text/javascript'>
		  document.location.href='".LOGIN."'
		  </script>";   																				
	exit;
}


$nivel_necessario = MOD_USER_NIVEL;																					#determina o nivel de acesso do usario (1-User, 2-Admin, 3-SuperAdmin)																											
$status_necessario = MOD_USER_STATUS;																					#determina uma variavel de referencia ao status do user, onde: 1-Inativo, 2-Ativo, 3-Aprovado, 4-Negado																											


if (!isset($_SESSION['server_usuarioId']) OR ($_SESSION['server_nivel'] < $nivel_necessario) OR ($_SESSION['server_status'] < $status_necessario)) 
{
	# Destroi a sessao por seguranca
	destroiSessao();		
	$msgErr = urlencode(__('Acesso Restrito.'));
	
	echo "<script type='text/javascript'>
		  document.location.href='".LOGIN."&msgError=$msgErr'
		  </script>";																					# Redireciona o visitante para login
	exit;
}

$insert = " INSERT INTO accesslogs
			(
				description,
				date,
				user_id
			)
			VALUES
			(
				'". $_SERVER['SERVER_NAME']."".$_SERVER['REQUEST_URI']."',
				'".date("Y-m-d H:i:s")."',
				".$_SESSION['server_usuarioId']."
			)";

$connection->Query($insert);
?>