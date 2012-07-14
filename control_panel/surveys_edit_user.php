<?php include 'control_panel/admin_auth.php'; ?>

<?php
if(!empty($_REQUEST['user_id']))
{
	
	//$_REQUEST['action'] = (empty($_REQUEST['action']))?'view':$_REQUEST['action'];
	
	$query = "	SELECT users.*, COALESCE(surveystatus.description,'".SURVEY_MSG_NOT_STARTED."') as survey_status 
				FROM users 
				LEFT JOIN surveys ON surveys.user_id = users.id
				LEFT JOIN surveystatus ON surveystatus.id = surveys.survey_status_id
				WHERE users.id = ".$_REQUEST['user_id'];
	
	$InfoUser = $connection->GetResult($query);
	
	
	if($InfoUser)
	{	
		
?>

<a href="index.php?module=control_panel&page=surveys_change_status&lang=<?php echo $_REQUEST['lang'];?>&user_id=<?php echo $user_id;?>"><?php echo __("Para mudar o status desse questionário, clique aqui")?></a>
<br>
<br>	
	<form name="form_approve_user" id="form_approve_user" class="form form_approve_user form_users" method="post" 
		action="index.php?module=control_panel&page=surveys&lang=<?php echo $_REQUEST['lang'];?>">
		<table class="table_users table_form" id="table_approve_user">
			<thead>
				<tr>
					<th colspan="2"><label class="table_title"><?php echo __("Informa&ccedil;&otilde;es do usu&aacute;rio consultado")?></label></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><label class="table_item_description"><?php echo __("Nome")?></label></th>
					<td><?php echo $InfoUser['name'];?></td>
				</tr>	
				<tr>
					<th><label class="table_item_description"><?php echo __("Sobrenome")?></label></th>
					<td><?php echo $InfoUser['lastname'];?></td>
				</tr>	
				<tr>
					<th><label class="table_item_description"><?php echo __("Usuario")?></label></th>
					<td><?php echo $InfoUser['username'];?></td>
				</tr>		
				<tr>
					<th><label class="table_item_description"><?php echo __("E-mail")?></label></th>
					<td><?php echo $InfoUser['email'];?></td>
				</tr>
				<tr>
					<th><label class="table_item_description"><?php echo __("Status do Questionario")?></label></th>
					<td><?php echo htmlentities($InfoUser['survey_status']);?></td>
				</tr>
				
			</tbody>
		</table>
		<br>
		<input type="hidden" name="user_id" value="<?php echo $_REQUEST['user_id'];?>">
		<input type="hidden" name="action" value="<?php echo ($action == 'edit')?'view':'edit';?>">
		<input type="submit" name='ChangeSurveyUser' value="<?php echo ($action =='view')?__("Editar Questionário"):__("Visualizar apenas");?>" class="">
	</form>
<?php 
	}
	else 
	{
	//	echo __("O usu&atilde;rio ainda n&atilde;o )
	}
?>
	<?php if($action == 'edit') { ?>
	<div class="message message_error" id="message_error">
			<p><label id="msg_edit_survey"><?php echo __("Aten&ccedil;&atilde;o: voc&ecirc; pode editar o question&aacute;rio")?></label></p>
	</div>
	<?php } else { ?>
	
	<div class="message message_sucess" id="message_sucess">
			<p><label id="msg_view_survey"><?php echo __("Voc&ecirc; est&aacute; apenas visualizando o question&aacute;rio")?></label></p>
	</div>
	<?php }?>

<?php 
}
?>


