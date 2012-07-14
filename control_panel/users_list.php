<?php include 'control_panel/admin_auth.php'; ?>
<?php


$retorno['Users'] = (isset($retorno['Users']))?$retorno['Users']:null;



if(is_array($retorno['Users']))
{ 

?>

<?php 

	$TUsers = new TUsers($connection, $_SESSION['server_nivel']);
	$LangList = unserialize(LANGS);

	foreach($retorno['Users'] as $id => $conteudo)
	{
		
?>
	

	<tr id="<?php echo $id;?>">
		<td class="tt_name">
			<input type="checkbox" name="users[]" class="cbox_user cb_list_submit" value="<?php echo $conteudo['id'];?>">
			<?php echo utf8_encode($conteudo['name']);?>
			</td>
		<td class="tt_lastname"><?php echo utf8_encode($conteudo['lastname']);?></td>
		<td class="tt_username"><?php echo utf8_encode($conteudo['username']);?></td>
		<td class="tt_email"><a href="mailto:<?php echo utf8_encode($conteudo['email']);?>"><?php echo utf8_encode($conteudo['email']);?></a></td>
		<td class="tt_status"><?php echo utf8_encode($conteudo['user_status_description']);?></td>
		<td class="tt_credential"><?php echo utf8_encode($conteudo['user_credential_description']);?></td>
		<td class="tt_survey_status"><?php echo utf8_encode($conteudo['user_survey_description']);?></td>		
		<td class="tt_options"><?php echo __("opções..."); ?><ul id="ul_options_user">
			<li class="op_view_data"><a href="index.php?module=user&page=view_data&user_id=<?php echo $conteudo['id']; ?>&lang=<?php echo $_REQUEST['lang'];?>"><?php echo (__("Ver Dados do Usu&aacute;rio"))?></a></li>
			<?php 
			
			if($conteudo['user_status_id'] == INATIVO)
			{
			?>
			<li class="op_activate"><a href="index.php?module=control_panel&page=users_activate&id=<?php echo $conteudo['id']; ?>&lang=<?php echo $_REQUEST['lang'];?>"><?php echo (__("Ativar Usuario"))?></a></li>
			<?php
			}else if($conteudo['user_status_id'] == ATIVO) {
			?>
			
			<li class="op_approve"><a href="index.php?module=control_panel&page=users_approve&id=<?php echo $conteudo['id']; ?>&lang=<?php echo $_REQUEST['lang'];?>"><?php echo (__("Aprovar Usuario"))?></a></li>
			<li class="op_deny"><a href="index.php?module=control_panel&page=users_deny&id=<?php echo $conteudo['id']; ?>&lang=<?php echo $_REQUEST['lang'];?>"><?php echo (__("Bloquear Usuario"))?></a></li>
			
			<?php 
			} else if($conteudo['user_status_id'] == APROVADO){
			?>
			<li class="op_deny"><a href="index.php?module=control_panel&page=users_deny&id=<?php echo $conteudo['id']; ?>&lang=<?php echo $_REQUEST['lang'];?>"><?php echo (__("Bloquear Usuario"))?></a></li>
			<?php 	
			} else if($conteudo['user_status_id'] == NEGADO){
			?>
			<li class="op_approve"><a href="index.php?module=control_panel&page=users_approve&id=<?php echo $conteudo['id']; ?>&lang=<?php echo $_REQUEST['lang'];?>"><?php echo (__("Aprovar Usuario"))?></a></li>
			<?php
			}
			?>
			<li class="op_change_credential"><a href="index.php?module=control_panel&page=users_change_credential&user_id=<?php echo $conteudo['id']; ?>&lang=<?php echo $_REQUEST['lang'];?>"><?php echo (__("Mudar Nivel de Usuario"))?></a></li>
			<li class="op_access_log"><a href="index.php?module=control_panel&page=users_access_log&id=<?php echo $conteudo['id']; ?>&lang=<?php echo $_REQUEST['lang'];?>"><?php echo (__("Ver Log de Acesso"))?></a></li>
				
				<?php if($conteudo['survey_status_id'] != NOT_STARTED){	?>
					<li class="op_survey_view"><a href="index.php?module=control_panel&page=surveys&lang=<?php echo $_REQUEST['lang'];?>&user_id=<?php echo $conteudo['id']; ?>"><?php echo (__('Ver Respostas do Questionario'));?></a></li>
				<?php }?>			
				
				<?php if($conteudo['user_credential_description'] != 'SUPERADMIN'){	?>
					<li class="op_survey_status"><a href="index.php?module=control_panel&page=surveys_change_status&lang=<?php echo $_REQUEST['lang'];?>&user_id=<?php echo $conteudo['id']; ?>"><?php echo (__('Mudar Status do Questionario'));?></a></li>
				<?php }?>			
		</ul>
		</td>
	</tr>
<?php
	} 
?>	

<?php }?>
