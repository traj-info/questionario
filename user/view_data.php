<?php 

include 'user/user_auth.php';


if(($_SESSION['server_nivel'] >= MOD_CONTROL_PANEL_NIVEL) &&  isset($_SESSION['server_usuarioId']))	
{
	#Obtendo o id do usuario iniciado na sessao
	$user_id = (empty($_REQUEST['user_id']))?0:$_REQUEST['user_id'];	
}
else 
{
	#Obtendo o id do usuario iniciado na sessao
	$user_id = $_SESSION['server_usuarioId'];
}




#Consultando na tabela usuarios o respectivo id(id_user)
$query = "	SELECT 
				users.*, 
				userstatus.description as user_status
			FROM users
			INNER JOIN userstatus ON userstatus.id = users.user_status_id 
			WHERE users.id = $user_id
			LIMIT 1";

#Retorno da consulta armazenado em variavel
$consulta = $connection->GetResult($query);

#Valores retornardos do array sao armazenados em respectivas variaveis sugestivas
$nameQuery = $consulta['name'];
$lastnameQuery = $consulta['lastname'];
$userQuery = $consulta['username'];
$emailQuery = $consulta['email'];
$createdQuery = $consulta['created'];
$lastModifiedQuery = $consulta['modified'];
$chaveQuery = $consulta ['key']; 
$statusQuery = $consulta['user_status'];
?>
<h2><?php echo __("Consulta de Dados");?></h2>
<br>
<table border="1" id="tbl_datagrid_user" class="tbl_datagrid tbl">
	<tr>
		<td><label for="UserName" id="UserName" class="tbl_label"><?php echo __("Nome:");?> </label></td>
		<td><?php echo $nameQuery?></td>
	</tr>
	<tr>
		<td><label for="UserLastname" id="UserLastname" class="tbl_label"><?php echo __("Sobrenome:");?> </label></td>
		<td><?php echo $lastnameQuery?></td>
	</tr>	
	<tr>
		<td><label for="UserUsername" id="UserUsername" class="tbl_label"><?php echo __("Usuario:");?> </label></td>
		<td><?php echo $userQuery?></td>
	</tr>
	<tr>
		<td><label for="UserEmail" id="UserEmail" class="tbl_label"><?php echo __("E-mail:");?> </label></td>
		<td><a href="mailto:<?php echo $emailQuery?>"><?php echo $emailQuery?></a></td>
	</tr>
	<tr>
		<td><label for="UserStatus" id="UserStatus" class="tbl_label"><?php echo __("Status:");?> </label></td>
		<td><?php echo $statusQuery?></td>
	</tr>	
	<tr>
		<td><label for="DateCreated" id="DateCreated" class="tbl_label"><?php echo __("Data de criacao:");?> </label></td>
		<td><?php echo $createdQuery?></td>
	</tr>	
	<tr>
		<td><label for="DateModified" id="DateModified"><?php echo __("Ultima modificacao:");?> </label></td>
		<td><?php echo $lastModifiedQuery?></td>
	</tr>	
	<tr>
		<td><label for="KeyAccess" id="KeyAccess" class="tbl_label"><?php echo __("Chave de privacidade:");?> </label></td>
		<td><?php echo $chaveQuery?></td>
	</tr>
</table>

<?php 
if(($_SESSION['server_nivel'] >= MOD_CONTROL_PANEL_NIVEL) &&  isset($_SESSION['server_usuarioId']))	
{
?>
<br>
<a href="index.php?module=user&page=edit_data&user_id=<?php echo $user_id; ?>&lang=<?php echo $_REQUEST['lang'];?>"><?php echo (__("Clique aqui para editar dados do Usu&aacute;rio"))?></a></li>
<?php 
}
?>