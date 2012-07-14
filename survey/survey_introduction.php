<?php include 'survey/survey_breadcrumb.php';?>
<?php 

if(($_SESSION['server_nivel'] >= MOD_CONTROL_PANEL_NIVEL) &&  isset($_SESSION['server_usuarioId']))	
{
	$user_id = (empty($_REQUEST['user_id']))?0:$_REQUEST['user_id'];
}
else
{
	$user_id = $_SESSION['server_usuarioId'];
}

$msgErrorSurvey = null;

// SALVA PRIMEIRO PARA EXECUTAR APENAS UMA VEZ A BUSCA NO BANCO
if(!empty($_REQUEST['SavePageIntroduction']) || !empty($_REQUEST['NextPageSurvey']))
{

		if($action == 'edit')
		{
			// da implode em question1
			
			if(is_array($_REQUEST['question1']))
			{		
				
				
				$item = array_fill(0, 6, null);
				$inst_null = 0;
				$cid_null = 0;
				
				for($i = 0; $i<6; $i++)
				{
					
					$item[$i] = (isset($_REQUEST['question1'][$i]))?trim(FilterData($_REQUEST['question1'][$i])):null;
					$item[$i] = (empty($item[$i]))?null:$item[$i];					
					
					if($i%2 == 0) 
					{	if(is_null($item[$i])) $inst_null++;
						else $item[$i] = str_replace(';','/',$item[$i]);
					}
					else
					{ 
						if(is_null($item[$i])) $cid_null++;
						else $item[$i] = str_replace(';','/',$item[$i]);
					}
				
				}
				
				
				
				if($inst_null > 2 || $cid_null > 2)
				{
					$sv_question1 = null;
					$msgErrorSurvey = __("Preencha pelo menos uma instituição e uma cidade/País onde voc&ecirc; pratica EUS");
				}
				else 
				{
					$sv_question1 = implode(';',$item);
				}
				
				
				
			
			}
			else 
			{
				$sv_question1 = null;
				$msgErrorSurvey = __("Preencha pelo menos uma instituição e uma cidade/País onde voc&ecirc; pratica EUS");
			}
			
			//$sv_question2 = $_REQUEST['question2'][0];
			
			if(empty($msgErrorSurvey))
			{
			
				//salva no banco
				/*
				$query = " UPDATE surveys
							SET
							
								question1 = '".trim(FilterData($sv_question1))."',
								question2 = '".trim(FilterData($sv_question2))."',
								modified = '".NowDatetime()."',
								survey_status_id = ".STARTED."
							WHERE
								user_id = ".$user_id." ";
				*/
				
				$query = " UPDATE surveys
							SET
							
								question1 = '".trim(FilterData($sv_question1))."',
								modified = '".NowDatetime()."',
								survey_status_id = ".STARTED."
							WHERE
								user_id = ".$user_id." ";
			
				$retval = $connection->Query($query);
				
				
				
				if(!$retval)
				{
					$errorMessage = urlencode(__("Informacoes nao foram salvas com sucesso"));
					
					#Chamaremos a funcao criada para direcionar as mensagens.
					errorMsg(INDEX, 'survey', 'survey_main', $errorMessage, $_REQUEST['lang']);
				}
				else 
				{
					$successMessage = urlencode(__("Informacoes salvas com sucesso"));
					
					if(!empty($_REQUEST['SavePageIntroduction']))
					{
						if(($_SESSION['server_nivel'] >= MOD_CONTROL_PANEL_NIVEL) &&  isset($_SESSION['server_usuarioId']))	
						{
							#Chamaremos a funcao criada para direcionar as mensagens.
							successMsg(INDEX,'control_panel' , 'users', $successMessage, $_REQUEST['lang'], "&user_id=".$user_id."&action=".$action);
						}
						else 
						{
							
							#Chamaremos a funcao criada para direcionar as mensagens.
							successMsg(INDEX,'user' , 'modelo_main', $successMessage, $_REQUEST['lang'], "&user_id=".$user_id."&action=".$action);
						}
					}
					
				}
			}
			else 
			{
				$errorMessage = urlencode($msgErrorSurvey);
				
				if(isset($_REQUEST['question1']))
				{
					$question1_url = null;
					
					for($i = 0; $i <= 7; $i++)
					{
						$item = (isset($_REQUEST['question1'][$i]))?$_REQUEST['question1'][$i]:null;
						
						$question1_url .= '&question1['.$i.']='.$item;
					}
				}
						
					
				#Chamaremos a funcao criada para direcionar as mensagens.
				errorMsg(INDEX, 'survey', 'survey_introduction', $errorMessage, $_REQUEST['lang'], "&user_id=".$user_id."&action=".$action."".$question1_url);
			}
		}
		else 
		{
			$successMessage = "";
		}
		
		
		
		successMsg(INDEX,'survey' , 'survey_page1', $successMessage, $_REQUEST['lang'], "&user_id=".$user_id."&action=".$action);
		
		
		
		
		
	
}



// BUSCANDO VALORES PREENCHIDOS ANTERIORMENTE PARA ESSE USUARIO

$query = "SELECT question1, question2 FROM surveys WHERE user_id=".$user_id." ORDER BY id LIMIT 1 " ;
$DataSurvey = $connection->GetResult($query);


if(!empty($DataSurvey['question1']))
{
	$Question1 = explode(';',$DataSurvey['question1']);
}
else
{
	$Question1 = array_fill(0,6,null);
	
	for($i = 0; $i< 6; $i++)
	{
		$Question1[$i] = (isset($_REQUEST['question1'][$i]))?$_REQUEST['question1'][$i]:null;
	}
	

}

if(!empty($DataSurvey['question2']))
{
	$Question2[0] = $DataSurvey['question2'];
}
else
{
	$Question2[0] = null;
}


?>
<h3><?php echo __("Página");?> 1</h3>

<form method="post" action="index.php?module=survey&page=survey_introduction&lang=<?php echo $_REQUEST['lang'];?>" name="frm_survey_introduction" id="frm_survey_introduction" class="frm_survey">
<p class="question_header"><?php echo __("Instituicao (oes) onde voce pratica EUS:");?></p>
<br>
<table border=0 id="tbl_list_question1" class="tbl_question_body">
	<tr>
		<th><label class="question_item_header"><?php echo __("Instituicao 1: ");?></label></th>
		<td><input type="text" name="question1[0]" value="<?php echo $Question1[0];?>" class="question1 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __("Cidade/Pais: ");?></label></th>
		<td><input type="text" name="question1[1]" value="<?php echo $Question1[1];?>" class="question1 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __("Instituicao 2: ");?></label></th>
		<td><input type="text"  name="question1[2]" value="<?php echo $Question1[2];?>" class="question1 question_text"></td>
	</tr><tr>
		<th><label class="question_item_header"><?php echo __("Cidade/Pais: ");?></label></th>
		<td><input type="text"  name="question1[3]" value="<?php echo $Question1[3];?>" class="question1 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __("Instituicao 3: ");?></label></th>
		<td><input type="text" name="question1[4]" value="<?php echo $Question1[4];?>" class="question1 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __("Cidade/Pais: ");?></label></th>
		<td><input type="text" name="question1[5]" value="<?php echo $Question1[5];?>" class="question1 question_text"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<!-- 
	<tr>
		<th><label class="question_item_header"><?php //echo __("E-mail principal: ");?></label></th>
		<td><input type="text" name="question2[0]" value="<?php //echo $Question2[0];?>" class="question2 question_text"></td>
	</tr>
	 -->
</table>

<br>
<p class="survey_notice"><?php echo __('"Note que voce podera salvar sua pesquisa a qualquer momento, durante as suas respostas, e retornar em um segundo momento para termina-la".')?></p>

<?php 
if($action == 'edit')
{
?>
	<input type="submit" name="SavePageIntroduction" id="SavePageIntroduction" value="<?php echo __("Salvar e Sair");?>" class="bt_submit bt_save_survey">
	<input type="submit" name="NextPageSurvey" id="NextPageSurvey" value="<?php echo __("Salvar e Continuar");?>" class="bt_submit bt_next_page_survey">
<?php 
}
else 
{
?>
	<input type="submit" name="NextPageSurvey" id="NextPageSurvey" value="<?php echo __("Visualizar Pr&oacute;xima P&aacute;gina");?>" class="bt_submit bt_next_page_survey">
<?php 
}
?>

	<input type="hidden" name="action" id="action" value="<?php echo $action;?>">
	<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
</form>
