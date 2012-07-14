<?php 

#Obtendo o id do usuario que recebeu o email para ativacao
$keyQuery = (empty($_REQUEST['key']))?0:FilterData($_REQUEST['key']);
$lang = (empty($lang))?LANG_DEFAULT:$_REQUEST['lang'];
$modified = NowDatetime();

#Query para alteracao do status do usuario, passando de Inativo para Ativo.
$query = "UPDATE users SET user_status_id = ".ATIVO.", modified = '$modified' WHERE users.key = '$keyQuery' LIMIT 1";

#Commit da query de update
$retval = $connection->Query($query);

if (!$retval)
{
	$errorMessage = urlencode(__('Usuario nao ativado'));
	errorMsg(INDEX, "", "", $errorMessage, $lang);
	
}
else 
{

?>
	<p><?php echo __("Parabens, seu cadastro foi ativado com sucesso.");?></p>
	<p><?php echo __("Voce sera redirecionado para realizar o Login no site");?></p>
<?php 

	$successMessage = urlencode(__('Usuario Ativado com Sucesso'));
	successMsg(INDEX, MOD_LOGIN, PAGE_LOGIN, $successMessage, $lang);

}
?>