<?php include 'control_panel/admin_auth.php'; ?>

<?php 

$_REQUEST['ConfirmSurveyStatus'] = (empty($_REQUEST['ConfirmSurveyStatus']))?null:$_REQUEST['ConfirmSurveyStatus'];
$_REQUEST['CancelSurveyStatus'] = (empty($_REQUEST['CancelSurveyStatus']))?null:$_REQUEST['CancelSurveyStatus'];

   //usar nomes dos botoes pq ao traduzi-los, nao sera possivel saber o valor
	if($_REQUEST['ConfirmSurveyStatus'] || $_REQUEST['CancelSurveyStatus'])
	{
		if(!empty($_REQUEST['ConfirmSurveyStatus']))
		{
					
				if(isset($_REQUEST['user_id']) && isset($_REQUEST['survey_status_id']))
				{	
					
					$query = "UPDATE surveys SET survey_status_id = ".$_REQUEST['survey_status_id']." WHERE user_id = ".$_REQUEST['user_id'];
					$retval = $connection->Query($query);		
				
				}
				else 
				{
					$retval = null;
					
				}
				
				#Verificacao da query, se nao houver retorno, abortar com mensagem de erro.
				if(! $retval )
				{ 
					$errorMessage = urlencode(__("Status n&atilde;o alterado. Tente novamente, por favor."));			
				
					#Chamaremos a funcao criada para direcionar as mensagens.
					errorMsg(INDEX, "control_panel", "users", $errorMessage, $_REQUEST['lang']);
				
				}
				#Caso contrario, exibir mensagem de sucesso
				else 
				{
					$successMessage = urlencode(__("Status alterado."));
						
					#Chamaremos a funcao criada para direcionar as mensagens.
					successMsg(INDEX, "control_panel", "users", $successMessage, $_REQUEST['lang']);
				}	
				
				
		}		
		else if(!empty($_REQUEST['CancelSurveyStatus']))
		{
		
			$successMessage = urlencode(__("Operacao cancelada."));
					
			#Chamaremos a funcao criada para direcionar as mensagens.
			successMsg(INDEX, "control_panel", "users", $successMessage, $_REQUEST['lang']);
		
		}	
		
	}



?>
<?php 

if(!empty($_REQUEST['user_id']))
{
	
	$query = "	SELECT * 
				FROM users 
				INNER JOIN surveys ON surveys.user_id = users.id
				WHERE users.id = ".$_REQUEST['user_id'];
	$InfoUser = $connection->GetResult($query);
	
	$query = "	SELECT * 
				FROM surveystatus
				ORDER BY id";
	$ListSurveyStatus = $connection->GetAllResults($query);
	
	
	if($InfoUser)
	{	
		
?>
<h2><?php echo __("Status do Question&aacute;rio do Usu&aacute;rio")?></h2>
	<form name="form_approve_user" id="form_approve_user" class="form form_approve_user form_users" method="post" 
		action="index.php?module=control_panel&page=surveys_change_status&lang=<?php echo $_REQUEST['lang'];?>">
		<table class="table_users table_form" id="table_approve_user">
			<thead>
				<tr>
					<td colspan="2"><label class="table_title"></label></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><label class="table_item_description"><?php echo __("Nome")?></label></td>
					<td><?php echo $InfoUser['name'];?></td>
				</tr>	
				<tr>
					<td><label class="table_item_description"><?php echo __("Sobrenome")?></label></td>
					<td><?php echo $InfoUser['lastname'];?></td>
				</tr>	
				<tr>
					<td><label class="table_item_description"><?php echo __("Usuario")?></label></td>
					<td><?php echo $InfoUser['username'];?></td>
				</tr>		
				<tr>
					<td><label class="table_item_description"><?php echo __("E-mail")?></label></td>
					<td><?php echo $InfoUser['email'];?></td>
				</tr>
				<tr>
					<td><label class="table_item_description"><?php echo __("Status do Question&aacute;rio")?></label></td>
					<td>
					<?php 
						if(is_array($ListSurveyStatus))
						{
					?>
							<select name="survey_status_id" id="survey_status_id">
					<?php 
							foreach($ListSurveyStatus as $key => $value)
							{
								if($InfoUser['survey_status_id'] == $value['id']) $selected = 'selected="selected"';
								else $selected = '';
								
								echo '<option value="'.$value['id'].'" '.$selected.'>'.htmlentities($value['description']).'</option>';	
							}
					?>
							</select>
					<?php 
						}
					
					?>
					
					</td>
				</tr>
				
			</tbody>
		</table>
			<br><div id="bt_holder1">
		<p><?php echo __("Deseja modificar o status do questionário desse usuario?");?></p>
		<input type="hidden" name="user_id" value="<?php echo $_REQUEST['user_id'];?>">
		<input type="submit" name='ConfirmSurveyStatus' value="<?php echo __("Mudar Status");?>" class="bt_confirm">
		<input type="submit" name='CancelSurveyStatus' value="<?php echo __("Cancelar");?>" class="bt_cancel"></div>
	</form>
<?php 
	}
	else 
	{
					$errorMessage = urlencode(__("Usuário não respondeu o questionário ainda."));			
				
					#Chamaremos a funcao criada para direcionar as mensagens.
					errorMsg(INDEX, "control_panel", "users", $errorMessage, $_REQUEST['lang']);
	}
?>

<?php 
}
?>