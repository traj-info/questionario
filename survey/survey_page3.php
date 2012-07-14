<?php include 'survey/survey_breadcrumb.php';?>
<script type="text/javascript">
function zeraValor(obj, cod)
{
	if(obj.checked == true)
	{
		document.getElementById(cod).value = "";
	}
	return false;
}

function cancelaCheck(cod)
{
	document.getElementById(cod).checked = false;
	return false;
}
</script>
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
if(!empty($_REQUEST['SavePage3']) || !empty($_REQUEST['NextPageSurvey']))
{

		if($action == 'edit')
		{
			if(!isset($_REQUEST['question27']))
			{
				$_REQUEST['question27'] = array_fill(0, 7, null);
			}
			
			
				$item[0] = (isset($_REQUEST['question27'][0]))?$_REQUEST['question27'][0]:null;
				$item[1] = (isset($_REQUEST['question27'][1]))?$_REQUEST['question27'][1]:null;
				$item[2] = (isset($_REQUEST['question27'][2]))?$_REQUEST['question27'][2]:null;
				$item[3] = (isset($_REQUEST['question27'][3]))?$_REQUEST['question27'][3]:null;
				$item[4] = (isset($_REQUEST['question27'][4]))?$_REQUEST['question27'][4]:null;
				$item[5] = (isset($_REQUEST['question27'][5]))?$_REQUEST['question27'][5]:null;
				$item[6] = (isset($_REQUEST['question27'][6]))?$_REQUEST['question27'][6]:null;

				$cont = 0;
				
				for($i = 0; $i <= 6; $i++)
				{
					if(is_null($item[$i])) $cont++;
					$item[$i] = FilterData($item[$i]);
				}
				
				if($cont > 0)
				{
					$sv_question27 = null;
				}
				else
				{
					$sv_question27 = implode(';',$item);
				
				}
				
				
				$msgErrorSurvey[27]  = null;
				
				if(!isset($item[0]))$msgErrorSurvey[27] .= __("Selecione um item do sub-item A da questão 25").'||';
				if(!isset($item[1]))$msgErrorSurvey[27] .= __("Selecione um item do sub-item B da questão 25").'||';
				if(!isset($item[2]))$msgErrorSurvey[27] .= __("Selecione um item do sub-item C da questão 25").'||';
				if(!isset($item[3]))$msgErrorSurvey[27] .= __("Selecione um item do sub-item D da questão 25").'||';
				if(!isset($item[4]))$msgErrorSurvey[27] .= __("Selecione um item do sub-item E da questão 25").'||';
				if(!isset($item[5]))$msgErrorSurvey[27] .= __("Selecione um item do sub-item F da questão 25").'||';
				if(!isset($item[6]))$msgErrorSurvey[27] .= __("Selecione um item do sub-item G da questão 25").'||';

				
				if(is_null($msgErrorSurvey[27])) unset($msgErrorSurvey[27]);
				
			
			
			
			
			unset($item);
			if(isset($_REQUEST['question28'][0]))
			{
				$sv_question28 = FilterData($_REQUEST['question28'][0]);
			}
			else
			{
				$sv_question28 = null;
				$msgErrorSurvey[28] = __("Questão 26 não selecionada");
			}
			
			unset($item);
			if(isset($_REQUEST['question29']))
			{
				
				
				$item = array_fill(0, 6, null);
				$el_null = 0;
				
				
				for($i = 0; $i<6; $i++)
				{
					$item[$i] = (isset($_REQUEST['question29'][$i]))?trim(FilterData($_REQUEST['question29'][$i])):null;
					//$item[$i] = (!empty($item[$i]))?$item[$i]:null;
					
					if(is_null($item[$i])) $el_null++;
					else $item[$i] = str_replace(';','/',$item[$i]);
				
					$item[$i] = FilterData($item[$i]);
				}
				
				$sv_question29 = implode(';',$item);
				
				if($el_null != 0)
				{
					$msgErrorSurvey[29] = __("Preencha todos os campos da questão 27").'<BR>';
				}
				
			}
			else 
			{
				$msgErrorSurvey[29] = __("Preencha todos os campos da questão 27").'<BR>';
				$sv_question29 = null;
			}
			
			
			
			unset($item);
			$item = array_fill(0, 4, null);
			if(is_array($_REQUEST['question30']))
			{
				$item[0] = (isset($_REQUEST['question30'][0]))?$_REQUEST['question30'][0]:null;
				$item[1] = (isset($_REQUEST['question30'][1]))?$_REQUEST['question30'][1]:null;
				$item[2] = (isset($_REQUEST['question30'][2]))?$_REQUEST['question30'][2]:null;
				$item[3] = (isset($_REQUEST['question30'][3]))?$_REQUEST['question30'][3]:null;

				$not_num = 0;
				
				for($i = 0; $i < 4; $i++)
				{
					if(!is_numeric($item[$i]) && ($i%2 == 0) && !empty($item[$i]) && (strpos(trim($item[$i]), '0') !== 0)) $not_num++;
					if(($i%2 == 0) && (!empty($item[$i])) && (!preg_match("/^[0-9]+$/", trim($item[$i])))) $not_num++;
					if(($i%2 == 0) && (!empty($item[$i])) && (strpos(trim($item[$i])) !== 0)) $item[$i] = (int)trim($item[$i]);
					
					$item[$i] = FilterData($item[$i]);
				}
				
				if($not_num >0)
				{
					$msgErrorSurvey[30] = __("Preencha a questão 28 com valores numéricos.").'<BR>';
				}
				
				
				if($item[0] == null && $item[1] == null && $item[2] == null && $item[3] == null)
				{
					$sv_question30 = null;
				}
				else
				{
					$sv_question30 = implode(';',$item);
				}
			}
			
			unset($item);
			$item = array_fill(0, 4, null);
			if(is_array($_REQUEST['question31']))
			{
				$item[0] = (isset($_REQUEST['question31'][0]))?$_REQUEST['question31'][0]:null;
				$item[1] = (isset($_REQUEST['question31'][1]))?$_REQUEST['question31'][1]:null;
				$item[2] = (isset($_REQUEST['question31'][2]))?$_REQUEST['question31'][2]:null;
				$item[3] = (isset($_REQUEST['question31'][3]))?$_REQUEST['question31'][3]:null;
				
				$not_num = 0;
				
				for($i = 0; $i < 4; $i++)
				{
					if(!is_numeric($item[$i]) && ($i%2 == 0) && !empty($item[$i]) && (strpos(trim($item[$i]), '0') !== 0)) $not_num++;
					if(($i%2 == 0) && (!empty($item[$i])) && (!preg_match("/^[0-9]+$/", trim($item[$i])))) $not_num++;
					if(($i%2 == 0) && (!empty($item[$i])) && (strpos(trim($item[$i])) !== 0)) $item[$i] = (int)trim($item[$i]);
					
					$item[$i] = FilterData($item[$i]);
				}
				
				if($not_num >0)
				{
					$msgErrorSurvey[31] = __("Preencha a questão 29 com valores numéricos").'<BR>';
				}
				
				if($item[0] == null && $item[1] == null && $item[2] == null && $item[3] == null)
				{
					$sv_question31 = null;
				}
				else
				{
					$sv_question31 = implode(';',$item);
				}
				
				
			}
			
			
			if(isset($_REQUEST['question32'][0]))
			{
				$_REQUEST['question32'][0] = trim(FilterData($_REQUEST['question32'][0]));
				if(!preg_match("/^(\d{1,})(\.\d{2})?$/", $_REQUEST['question32'][0]) && !empty($_REQUEST['question32'][0]))
				{
					$msgErrorSurvey[32] = __("Preencha a questão 30 com valores do tipo 999.99 - ponto como separador decimal.").'<BR>';
					$sv_question32 = null;
				}
				else
				{
					if(empty($_REQUEST['question32'][0]))
						$sv_question32 = null;
					else
						$sv_question32 = number_format((float)$_REQUEST['question32'][0], 2, ".", "");
				}
			}
			else
			{
				$sv_question32 = null;
				
			}
			
			
			if(isset($_REQUEST['question33'][0]))
			{
				$_REQUEST['question33'][0] = trim(FilterData($_REQUEST['question33'][0]));
				if(!preg_match("/^(\d{1,})(\.\d{2})?$/", $_REQUEST['question33'][0]) && !empty($_REQUEST['question33'][0]))
				{
					$msgErrorSurvey[32] = __("Preencha a questão 31 com valores do tipo 999.99 - ponto como separador decimal.").'<BR>';
					$sv_question33 = null;
				}
				else
				{
					if(empty($_REQUEST['question33'][0]))
						$sv_question33 = null;
					else
						$sv_question33 = number_format((float)$_REQUEST['question33'][0], 2, ".", "");
				}
			}
			else
			{
				$sv_question33 = null;
				
			}
			
			if(isset($_REQUEST['question34'][0]))
			{
				$_REQUEST['question34'][0] = trim(FilterData($_REQUEST['question34'][0]));
				if(!preg_match("/^(\d{1,})(\.\d{2})?$/", $_REQUEST['question34'][0]) && !empty($_REQUEST['question34'][0]))
				{
					$msgErrorSurvey[32] = __("Preencha a questão 32 com valores do tipo 999.99 - ponto como separador decimal.").'<BR>';
					$sv_question34 = null;
				}
				else
				{
					if(empty($_REQUEST['question34'][0]))
						$sv_question34 = null;
					else
						$sv_question34 = number_format((float)$_REQUEST['question34'][0], 2, ".", "");
				}
			}
			else
			{
				$sv_question34 = null;
				
			}
			
			if(isset($_REQUEST['question35'][0]))
			{
				$_REQUEST['question35'][0] = trim(FilterData($_REQUEST['question35'][0]));
				if(!preg_match("/^(\d{1,})(\.\d{2})?$/", $_REQUEST['question35'][0]) && !empty($_REQUEST['question35'][0]))
				{
					$msgErrorSurvey[32] = __("Preencha a questão 33 com valores do tipo 999.99 - ponto como separador decimal.").'<BR>';
					$sv_question35 = null;
				}
				else
				{
					if(empty($_REQUEST['question35'][0]))
						$sv_question35 = null;
					else
						$sv_question35 = number_format((float)$_REQUEST['question35'][0], 2, ".", "");
				}
			}
			else
			{
				$sv_question35 = null;
				
			}
			
			if(isset($_REQUEST['question36'][0]))
			{
				$_REQUEST['question36'][0] = trim(FilterData($_REQUEST['question36'][0]));
				if(!preg_match("/^(\d{1,})(\.\d{2})?$/", $_REQUEST['question36'][0]) && !empty($_REQUEST['question36'][0]))
				{
					$msgErrorSurvey[32] = __("Preencha a questão 34 com valores do tipo 999.99 - ponto como separador decimal.").'<BR>';
					$sv_question36 = null;
				}
				else
				{
					if(empty($_REQUEST['question36'][0]))
						$sv_question36 = null;
					else
						$sv_question36 = number_format((float)$_REQUEST['question36'][0], 2, ".", "");
				}
			}
			else
			{
				$sv_question36 = null;
				
			}
			
			
			//salva no banco
			$query = " UPDATE surveys
						SET
						";

			/** CAMPOS OBRIGATÓRIOS **/
			if(!isset($msgErrorSurvey[27])) $query .= " question27 = '".trim(FilterData($sv_question27))."',";
			if(!isset($msgErrorSurvey[28])) $query .= " question28 = '".trim(FilterData($sv_question28))."',";
			if(!isset($msgErrorSurvey[29])) $query .= " question29 = '".trim(FilterData($sv_question29))."',";					
			
			
			/** CAMPOS OPCIONAIS **/				
			if(!isset($msgErrorSurvey[30])) 	$query .= " question30 = '".trim(FilterData($sv_question30))."',";
			if(!isset($msgErrorSurvey[31])) 	$query .= " question31 = '".trim(FilterData($sv_question31))."',";
			if(!isset($msgErrorSurvey[32])) 	$query .= " question32 = '".trim(FilterData($sv_question32))."',";
			if(!isset($msgErrorSurvey[33])) 	$query .= " question33 = '".trim(FilterData($sv_question33))."',";
			if(!isset($msgErrorSurvey[34])) 	$query .= " question34 = '".trim(FilterData($sv_question34))."',";
			if(!isset($msgErrorSurvey[35])) 	$query .= " question35 = '".trim(FilterData($sv_question35))."',";
			if(!isset($msgErrorSurvey[36])) 	$query .= " question36 = '".trim(FilterData($sv_question36))."',";
			
							
			$query .="		modified = '".NowDatetime()."'
						WHERE
							user_id = ".$user_id." ";
			
			$retval = $connection->Query($query);
			
			
			
			if(!$retval)
			{
				$errorMessage = urlencode(__("Informacoes nao foram salvas com sucesso"));
				
				#Chamaremos a funcao criada para direcionar as mensagens.
				errorMsg(INDEX, 'survey', 'survey_main', $errorMessage, $_REQUEST['lang'], "&user_id=".$user_id."&action=".$action);
			}
			else 
			{
				
				if(empty($msgErrorSurvey))
				{
						
					$successMessage = urlencode(__("Informacoes salvas com sucesso"));
				
					if(!empty($_REQUEST['SavePage3']))
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
				else 
				{
					$msgErrorSurvey = implode("||",$msgErrorSurvey);
					
					$errorMessage = urlencode($msgErrorSurvey);
					
					//encode questao 27 e envia via url
					if(isset($_REQUEST['question27']))
					{
						$question27_url = null;
						
						for($i = 0; $i <= 6; $i++)
						{
							$item = (isset($_REQUEST['question27'][$i]))?$_REQUEST['question27'][$i]:null;
							
							$question27_url .= '&question27['.$i.']='.$item;
						}
					}
					
						
					#Chamaremos a funcao criada para direcionar as mensagens.
					errorMsg(INDEX, 'survey', 'survey_page3', $errorMessage, $_REQUEST['lang'], "&user_id=".$user_id."&action=".$action."".$question27_url);
				}	
				
				
			}
			
		}
		
		
			
		$successMessage = "";
		
		successMsg(INDEX,'survey' , 'survey_page_final', $successMessage, $_REQUEST['lang'], "&user_id=".$user_id."&action=".$action);
		
		
		
}	
		
	


// BUSCANDO VALORES PREENCHIDOS ANTERIORMENTE PARA ESSE USUARIO

$query = "SELECT 
				question27, 
				question28,
				question29,
				question30,
				question31,
				question32,
				question33,
				question34,
				question35,
				question36
				 
		FROM surveys 
		WHERE user_id=".$user_id." 
		ORDER BY id LIMIT 1 " ;


$DataSurvey = $connection->GetResult($query);



if(!empty($DataSurvey['question27']))
{
	$Question27 = explode(';',$DataSurvey['question27']);
}
else
{	
	$Question27 = array_fill(0, 8, null );
	
	for($i = 0; $i< 8; $i++)
	{
		$Question27[$i] = (isset($_REQUEST['question27'][$i]))?$_REQUEST['question27'][$i]:null;
	}
}						

if($DataSurvey['question28'] != null)
{
	$Question28[0] = $DataSurvey['question28'];
}
else
{
	$Question28[0] = null;
}

if(!empty($DataSurvey['question29']))
{
	$Question29 = explode(';',$DataSurvey['question29']);
}
else
{
	$Question29 = array(0 => null, 1 => null,2 => null, 3 => null ,4 => null,5 => null, 6 => null);
}						
			
			
if(!empty($DataSurvey['question30']))
{
	$Question30 = explode(';',$DataSurvey['question30']);
}
else
{
	$Question30 = array(0 => null, 1 => null,2 => null, 3 => null );
}				
			
if(!empty($DataSurvey['question31']))
{
	$Question31 = explode(';',$DataSurvey['question31']);
}
else
{
	$Question31 = array(0 => null, 1 => null,2 => null, 3 => null );
}		

			
if(!empty($DataSurvey['question32']))
{
	$Question32[0] = $DataSurvey['question32'];
}
else
{
	$Question32[0] = null;
}

if(!empty($DataSurvey['question33']))
{
	$Question33[0] = $DataSurvey['question33'];
}
else
{
	$Question33[0] = null;
}


if(!empty($DataSurvey['question34']))
{
	$Question34[0] = $DataSurvey['question34'];
}
else
{
	$Question34[0] = null;
}

if(!empty($DataSurvey['question35']))
{
	$Question35[0] = $DataSurvey['question35'];
}
else
{
	$Question35[0] = null;
}

if(!empty($DataSurvey['question36']))
{
	$Question36[0] = $DataSurvey['question36'];
}
else
{
	$Question36[0] = null;
}

?>
<h3><?php echo __("Página");?> 4</h3>
<form method="post" action="index.php?module=survey&page=survey_page3&lang=<?php echo $_REQUEST['lang'];?>" name="frm_survey_page3" id="frm_survey_page3" class="frm_survey">

<br>
<p class="question_header"><?php echo __('25. Na sua opiniao, atualmente, qual e a experiencia minima que deve ser aplicada em estagios "hands-on" em EUS?');?></p>

<p class="question_subheader"><?php echo __("A. Anorretal")?></p>
<?php 

	$question27_checked_0 = "";
	$question27_checked_1 = "";
	$question27_checked_2 = "";
	$question27_checked_3 = "";
	$question27_checked_4 = "";
	$question27_checked_5 = "";
	
if($Question27[0] === '0')
{
	$question27_checked_0 = "checked='checked'";
	$question27_checked_1 = "";
	$question27_checked_2 = "";
	$question27_checked_3 = "";
	$question27_checked_4 = "";
	$question27_checked_5 = "";
}
else if($Question27[0] === '1') 
{
	$question27_checked_0 = "";
	$question27_checked_1 = "checked='checked'";
	$question27_checked_2 = "";
	$question27_checked_3 = "";
	$question27_checked_4 = "";
	$question27_checked_5 = "";
}
else if($Question27[0] === '2') 
{
	$question27_checked_0 = "";
	$question27_checked_1 = "";
	$question27_checked_2 = "checked='checked'";
	$question27_checked_3 = "";
	$question27_checked_4 = "";
	$question27_checked_5 = "";
}
else if($Question27[0] === '3') 
{
	$question27_checked_0 = "";
	$question27_checked_1 = "";
	$question27_checked_2 = "";
	$question27_checked_3 = "checked='checked'";
	$question27_checked_4 = "";
	$question27_checked_5 = "";
}
else if($Question27[0] === '4') 
{
	$question27_checked_0 = "";
	$question27_checked_1 = "";
	$question27_checked_2 = "";
	$question27_checked_3 = "";
	$question27_checked_4 = "checked='checked'";
	$question27_checked_5 = "";
}
else if($Question27[0] === '5') 
{
	$question27_checked_0 = "";
	$question27_checked_1 = "";
	$question27_checked_2 = "";
	$question27_checked_3 = "";
	$question27_checked_4 = "";
	$question27_checked_5 = "checked='checked'";
}

?>
<p>
	<input type="radio" name="question27[0]" class="question27 question27A  question_radio" value="0" <?php echo $question27_checked_0;?>><label class="question_item_header"><?php echo __("nenhuma");?></label>
	<input type="radio" name="question27[0]" class="question27 question27A  question_radio" value="1" <?php echo $question27_checked_1;?>><label class="question_item_header"><?php echo __("<= 5");?></label> 
	<input type="radio" name="question27[0]" class="question27 question27A  question_radio" value="2" <?php echo $question27_checked_2;?>><label class="question_item_header"><?php echo __("6-10");?></label>   
	<input type="radio" name="question27[0]" class="question27 question27A  question_radio" value="3" <?php echo $question27_checked_3;?>><label class="question_item_header"><?php echo __("11-20");?></label>   
	<input type="radio" name="question27[0]" class="question27 question27A  question_radio" value="4" <?php echo $question27_checked_4;?>><label class="question_item_header"><?php echo __("21-50");?></label>  
	<input type="radio" name="question27[0]" class="question27 question27A  question_radio" value="5" <?php echo $question27_checked_5;?>><label class="question_item_header"><?php echo __(">50");?></label>   
</p>

<p class="question_subheader"><?php echo __("B. Esofago")?></p>
<?php 

	$question27_checked_6 = "";
	$question27_checked_7 = "";
	$question27_checked_8 = "";
	$question27_checked_9 = "";
	$question27_checked_10 = "";
	$question27_checked_11 = "";
	
if($Question27[1] === '6')
{
	$question27_checked_6 = "checked='checked'";
	$question27_checked_7 = "";
	$question27_checked_8 = "";
	$question27_checked_9 = "";
	$question27_checked_10 = "";
	$question27_checked_11 = "";
}
else if($Question27[1] === '7') 
{
	$question27_checked_6 = "";
	$question27_checked_7 = "checked='checked'";
	$question27_checked_8 = "";
	$question27_checked_9 = "";
	$question27_checked_10 = "";
	$question27_checked_11 = "";
}
else if($Question27[1] === '8') 
{
	$question27_checked_6 = "";
	$question27_checked_7 = "";
	$question27_checked_8 = "checked='checked'";
	$question27_checked_9 = "";
	$question27_checked_10 = "";
	$question27_checked_11 = "";
}
else if($Question27[1] === '9') 
{
	$question27_checked_6 = "";
	$question27_checked_7 = "";
	$question27_checked_8 = "";
	$question27_checked_9 = "checked='checked'";
	$question27_checked_10 = "";
	$question27_checked_11 = "";
}
else if($Question27[1] === '10') 
{
	$question27_checked_6 = "";
	$question27_checked_7 = "";
	$question27_checked_8 = "";
	$question27_checked_9 = "";
	$question27_checked_10 = "checked='checked'";
	$question27_checked_11 = "";
}
else if($Question27[1] === '11') 
{
	$question27_checked_6 = "";
	$question27_checked_7 = "";
	$question27_checked_8 = "";
	$question27_checked_9 = "";
	$question27_checked_10 = "";
	$question27_checked_11 = "checked='checked'";
}

?>
<p>
	<input type="radio" name="question27[1]" class="question27 question27B  question_radio" value="6" <?php echo $question27_checked_6;?>><label class="question_item_header"><?php echo __("nenhuma");?></label>
	<input type="radio" name="question27[1]" class="question27 question27B  question_radio" value="7" <?php echo $question27_checked_7;?>><label class="question_item_header"><?php echo __("<= 5");?></label> 
	<input type="radio" name="question27[1]" class="question27 question27B  question_radio" value="8" <?php echo $question27_checked_8;?>><label class="question_item_header"><?php echo __("6-10");?></label>   
	<input type="radio" name="question27[1]" class="question27 question27B  question_radio" value="9" <?php echo $question27_checked_9;?>><label class="question_item_header"><?php echo __("11-20");?></label>   
	<input type="radio" name="question27[1]" class="question27 question27B  question_radio" value="10" <?php echo $question27_checked_10;?>><label class="question_item_header"><?php echo __("21-50");?></label>  
	<input type="radio" name="question27[1]" class="question27 question27B  question_radio" value="11" <?php echo $question27_checked_11;?>><label class="question_item_header"><?php echo __(">50");?></label>   
</p>

<p class="question_subheader"><?php echo __("C. Gastroduodenal ")?></p>
<?php 

	$question27_checked_12 = "";
	$question27_checked_13 = "";
	$question27_checked_14 = "";
	$question27_checked_15 = "";
	$question27_checked_16 = "";
	$question27_checked_17 = "";

	
if($Question27[2] === '12')
{
	$question27_checked_12 = "checked='checked'";
	$question27_checked_13 = "";
	$question27_checked_14 = "";
	$question27_checked_15 = "";
	$question27_checked_16 = "";
	$question27_checked_17 = "";
}
else if($Question27[2] === '13') 
{
	$question27_checked_12 = "";
	$question27_checked_13 = "checked='checked'";
	$question27_checked_14 = "";
	$question27_checked_15 = "";
	$question27_checked_16 = "";
	$question27_checked_17 = "";
}
else if($Question27[2] === '14') 
{
	$question27_checked_12 = "";
	$question27_checked_13 = "";
	$question27_checked_14 = "checked='checked'";
	$question27_checked_15 = "";
	$question27_checked_16 = "";
	$question27_checked_17 = "";
}
else if($Question27[2] === '15') 
{
	$question27_checked_12 = "";
	$question27_checked_13 = "";
	$question27_checked_14 = "";
	$question27_checked_15 = "checked='checked'";
	$question27_checked_16 = "";
	$question27_checked_17 = "";
}
else if($Question27[2] === '16') 
{
	$question27_checked_12 = "";
	$question27_checked_13 = "";
	$question27_checked_14 = "";
	$question27_checked_15 = "";
	$question27_checked_16 = "checked='checked'";
	$question27_checked_17 = "";
}
else if($Question27[2] === '17') 
{
	$question27_checked_12 = "";
	$question27_checked_13 = "";
	$question27_checked_14 = "";
	$question27_checked_15 = "";
	$question27_checked_16 = "";
	$question27_checked_17 = "checked='checked'";
}
	
	
	
?>
<p>
	<input type="radio" name="question27[2]" class="question27 question27C  question_radio" value="12" <?php echo $question27_checked_12;?>><label class="question_item_header"><?php echo __("nenhuma");?></label>
	<input type="radio" name="question27[2]" class="question27 question27C  question_radio" value="13" <?php echo $question27_checked_13;?>><label class="question_item_header"><?php echo __("<= 5");?></label> 
	<input type="radio" name="question27[2]" class="question27 question27C  question_radio" value="14" <?php echo $question27_checked_14;?>><label class="question_item_header"><?php echo __("6-10");?></label>   
	<input type="radio" name="question27[2]" class="question27 question27C  question_radio" value="15" <?php echo $question27_checked_15;?>><label class="question_item_header"><?php echo __("11-20");?></label>   
	<input type="radio" name="question27[2]" class="question27 question27C  question_radio" value="16" <?php echo $question27_checked_16;?>><label class="question_item_header"><?php echo __("21-50");?></label>  
	<input type="radio" name="question27[2]" class="question27 question27C  question_radio" value="17" <?php echo $question27_checked_17;?>><label class="question_item_header"><?php echo __(">50");?></label>   
</p>

<p class="question_subheader"><?php echo __("D. Mediastino ")?></p>
<?php 

	$question27_checked_18 = "";
	$question27_checked_19 = "";
	$question27_checked_20 = "";
	$question27_checked_21 = "";
	$question27_checked_22 = "";
	$question27_checked_23 = "";
	
if($Question27[3] === '18')
{
	$question27_checked_18 = "checked='checked'";
	$question27_checked_19 = "";
	$question27_checked_20 = "";
	$question27_checked_21 = "";
	$question27_checked_22 = "";
	$question27_checked_23 = "";
}
else if($Question27[3] === '19') 
{
	$question27_checked_18 = "";
	$question27_checked_19 = "checked='checked'";
	$question27_checked_20 = "";
	$question27_checked_21 = "";
	$question27_checked_22 = "";
	$question27_checked_23 = "";
}
else if($Question27[3] === '20') 
{
	$question27_checked_18 = "";
	$question27_checked_19 = "";
	$question27_checked_20 = "checked='checked'";
	$question27_checked_21 = "";
	$question27_checked_22 = "";
	$question27_checked_23 = "";
}
else if($Question27[3] === '21') 
{
	$question27_checked_18 = "";
	$question27_checked_19 = "";
	$question27_checked_20 = "";
	$question27_checked_21 = "checked='checked'";
	$question27_checked_22 = "";
	$question27_checked_23 = "";
}
else if($Question27[3] === '22') 
{
	$question27_checked_18 = "";
	$question27_checked_19 = "";
	$question27_checked_20 = "";
	$question27_checked_21 = "";
	$question27_checked_22 = "checked='checked'";
	$question27_checked_23 = "";
}
else if($Question27[3] === '23') 
{
	$question27_checked_18 = "";
	$question27_checked_19 = "";
	$question27_checked_20 = "";
	$question27_checked_21 = "";
	$question27_checked_22 = "";
	$question27_checked_23 = "checked='checked'";
}

?>
<p>
	<input type="radio" name="question27[3]" class="question27 question27D  question_radio" value="18" <?php echo $question27_checked_18;?>><label class="question_item_header"><?php echo __("nenhuma");?></label>
	<input type="radio" name="question27[3]" class="question27 question27D  question_radio" value="19" <?php echo $question27_checked_19;?>><label class="question_item_header"><?php echo __("<= 5");?></label> 
	<input type="radio" name="question27[3]" class="question27 question27D  question_radio" value="20" <?php echo $question27_checked_20;?>><label class="question_item_header"><?php echo __("6-10");?></label>   
	<input type="radio" name="question27[3]" class="question27 question27D  question_radio" value="21" <?php echo $question27_checked_21;?>><label class="question_item_header"><?php echo __("11-20");?></label>   
	<input type="radio" name="question27[3]" class="question27 question27D  question_radio" value="22" <?php echo $question27_checked_22;?>><label class="question_item_header"><?php echo __("21-50");?></label>  
	<input type="radio" name="question27[3]" class="question27 question27D  question_radio" value="23" <?php echo $question27_checked_23;?>><label class="question_item_header"><?php echo __(">50");?></label>   
</p>

<p class="question_subheader"><?php echo __("E. Pancreato-biliar-ampular  ")?></p>
<?php 

	$question27_checked_24 = "";
	$question27_checked_25 = "";
	$question27_checked_26 = "";
	$question27_checked_27 = "";
	$question27_checked_28 = "";
	$question27_checked_29 = "";
	
if($Question27[4] === '24')
{
	$question27_checked_24 = "checked='checked'";
	$question27_checked_25 = "";
	$question27_checked_26 = "";
	$question27_checked_27 = "";
	$question27_checked_28 = "";
	$question27_checked_29 = "";
}
else if($Question27[4] === '25') 
{
	$question27_checked_24 = "";
	$question27_checked_25 = "checked='checked'";
	$question27_checked_26 = "";
	$question27_checked_27 = "";
	$question27_checked_28 = "";
	$question27_checked_29 = "";
}
else if($Question27[4] === '26') 
{
	$question27_checked_24 = "";
	$question27_checked_25 = "";
	$question27_checked_26 = "checked='checked'";
	$question27_checked_27 = "";
	$question27_checked_28 = "";
	$question27_checked_29 = "";
}
else if($Question27[4] === '27') 
{
	$question27_checked_24 = "";
	$question27_checked_25 = "";
	$question27_checked_26 = "";
	$question27_checked_27 = "checked='checked'";
	$question27_checked_28 = "";
	$question27_checked_29 = "";
}
else if($Question27[4] === '28') 
{
	$question27_checked_24 = "";
	$question27_checked_25 = "";
	$question27_checked_26 = "";
	$question27_checked_27 = "";
	$question27_checked_28 = "checked='checked'";
	$question27_checked_29 = "";
}
else if($Question27[4] === '29') 
{
	$question27_checked_24 = "";
	$question27_checked_25 = "";
	$question27_checked_26 = "";
	$question27_checked_27 = "";
	$question27_checked_28 = "";
	$question27_checked_29 = "checked='checked'";
}

?>
<p>
	<input type="radio" name="question27[4]" class="question27 question27E  question_radio" value="24" <?php echo $question27_checked_24;?>><label class="question_item_header"><?php echo __("nenhuma");?></label>
	<input type="radio" name="question27[4]" class="question27 question27E  question_radio" value="25" <?php echo $question27_checked_25;?>><label class="question_item_header"><?php echo __("<= 5");?></label> 
	<input type="radio" name="question27[4]" class="question27 question27E  question_radio" value="26" <?php echo $question27_checked_26;?>><label class="question_item_header"><?php echo __("6-10");?></label>   
	<input type="radio" name="question27[4]" class="question27 question27E  question_radio" value="27" <?php echo $question27_checked_27;?>><label class="question_item_header"><?php echo __("11-20");?></label>   
	<input type="radio" name="question27[4]" class="question27 question27E  question_radio" value="28" <?php echo $question27_checked_28;?>><label class="question_item_header"><?php echo __("21-50");?></label>  
	<input type="radio" name="question27[4]" class="question27 question27E  question_radio" value="29" <?php echo $question27_checked_29;?>><label class="question_item_header"><?php echo __(">50");?></label>   
</p>

<p class="question_subheader"><?php echo __("F. Puncao ecoguiada (FNA) - alta e baixa  ")?></p>
<?php 

	$question27_checked_30 = "";
	$question27_checked_31 = "";
	$question27_checked_32 = "";
	$question27_checked_33 = "";
	$question27_checked_34 = "";
	$question27_checked_35 = "";
	$question27_checked_36 = "";
	
if($Question27[5] === '30')
{
	$question27_checked_30 = "checked='checked'";
	$question27_checked_31 = "";
	$question27_checked_32 = "";
	$question27_checked_33 = "";
	$question27_checked_34 = "";
	$question27_checked_35 = "";
	$question27_checked_36 = "";
}
else if($Question27[5] === '31') 
{
	$question27_checked_30 = "";
	$question27_checked_31 = "checked='checked'";
	$question27_checked_32 = "";
	$question27_checked_33 = "";
	$question27_checked_34 = "";
	$question27_checked_35 = "";
	$question27_checked_36 = "";
}
else if($Question27[5] === '32') 
{
	$question27_checked_30 = "";
	$question27_checked_31 = "";
	$question27_checked_32 = "checked='checked'";
	$question27_checked_33 = "";
	$question27_checked_34 = "";
	$question27_checked_35 = "";
	$question27_checked_36 = "";
}
else if($Question27[5] === '33') 
{
	$question27_checked_30 = "";
	$question27_checked_31 = "";
	$question27_checked_32 = "";
	$question27_checked_33 = "checked='checked'";
	$question27_checked_34 = "";
	$question27_checked_35 = "";
	$question27_checked_36 = "";
}
else if($Question27[5] === '34') 
{
	$question27_checked_30 = "";
	$question27_checked_31 = "";
	$question27_checked_32 = "";
	$question27_checked_33 = "";
	$question27_checked_34 = "checked='checked'";
	$question27_checked_35 = "";
	$question27_checked_36 = "";
}
else if($Question27[5] === '35') 
{
	$question27_checked_30 = "";
	$question27_checked_31 = "";
	$question27_checked_32 = "";
	$question27_checked_33 = "";
	$question27_checked_34 = "";
	$question27_checked_35 = "checked='checked'";
	$question27_checked_36 = "";
}
else if($Question27[5] === '36') 
{
	$question27_checked_30 = "";
	$question27_checked_31 = "";
	$question27_checked_32 = "";
	$question27_checked_33 = "";
	$question27_checked_34 = "";
	$question27_checked_35 = "";
	$question27_checked_36 = "checked='checked'";
}

?>
<p>
	<input type="radio" name="question27[5]" class="question27 question27F  question_radio" value="30"  <?php echo $question27_checked_30;?>><label class="question_item_header"><?php echo __("nenhuma");?></label>
	<input type="radio" name="question27[5]" class="question27 question27F  question_radio" value="31" <?php echo $question27_checked_31;?>><label class="question_item_header"><?php echo __("<= 5");?></label> 
	<input type="radio" name="question27[5]" class="question27 question27F  question_radio" value="32" <?php echo $question27_checked_32;?>><label class="question_item_header"><?php echo __("6-10");?></label>   
	<input type="radio" name="question27[5]" class="question27 question27F  question_radio" value="33" <?php echo $question27_checked_33;?>><label class="question_item_header"><?php echo __("11-20");?></label>   
	<input type="radio" name="question27[5]" class="question27 question27F  question_radio" value="34" <?php echo $question27_checked_34;?>><label class="question_item_header"><?php echo __("21-50");?></label>  
	<input type="radio" name="question27[5]" class="question27 question27F  question_radio" value="35" <?php echo $question27_checked_35;?>><label class="question_item_header"><?php echo __("51-100");?></label>
	<input type="radio" name="question27[5]" class="question27 question27F  question_radio" value="36" <?php echo $question27_checked_36;?>><label class="question_item_header"><?php echo __(">100");?></label>   
</p>

<p class="question_subheader"><?php echo __("G. Terapeutica - alta e baixa ( neurolise/bloqueio do plexo celiaco, drenagens, etc ...)")?></p>
<?php 

	$question27_checked_37 = "";
	$question27_checked_38 = "";
	$question27_checked_39 = "";
	$question27_checked_40 = "";
	$question27_checked_41 = "";
	$question27_checked_42 = "";
	$question27_checked_43 = "";
	$question27_checked_44 = "";
	
if($Question27[6] === '37')
{
	$question27_checked_37 = "checked='checked'";
	$question27_checked_38 = "";
	$question27_checked_39 = "";
	$question27_checked_40 = "";
	$question27_checked_41 = "";
	$question27_checked_42 = "";
	$question27_checked_43 = "";
	$question27_checked_44 = "";
}
else if($Question27[6] === '38') 
{
	$question27_checked_37 = "";
	$question27_checked_38 = "checked='checked'";
	$question27_checked_39 = "";
	$question27_checked_40 = "";
	$question27_checked_41 = "";
	$question27_checked_42 = "";
	$question27_checked_43 = "";
	$question27_checked_44 = "";
}
else if($Question27[6] === '39') 
{
	$question27_checked_37 = "";
	$question27_checked_38 = "";
	$question27_checked_39 = "checked='checked'";
	$question27_checked_40 = "";
	$question27_checked_41 = "";
	$question27_checked_42 = "";
	$question27_checked_43 = "";
	$question27_checked_44 = "";
}
else if($Question27[6] === '40') 
{
	$question27_checked_37 = "";
	$question27_checked_38 = "";
	$question27_checked_39 = "";
	$question27_checked_40 = "checked='checked'";
	$question27_checked_41 = "";
	$question27_checked_42 = "";
	$question27_checked_43 = "";
	$question27_checked_44 = "";
}
else if($Question27[6] === '41') 
{
	$question27_checked_37 = "";
	$question27_checked_38 = "";
	$question27_checked_39 = "";
	$question27_checked_40 = "";
	$question27_checked_41 = "checked='checked'";
	$question27_checked_42 = "";
	$question27_checked_43 = "";
	$question27_checked_44 = "";
}
else if($Question27[6] === '42') 
{
	$question27_checked_37 = "";
	$question27_checked_38 = "";
	$question27_checked_39 = "";
	$question27_checked_40 = "";
	$question27_checked_41 = "";
	$question27_checked_42 = "checked='checked'";
	$question27_checked_43 = "";
	$question27_checked_44 = "";
}
else if($Question27[6] === '43') 
{
	$question27_checked_37 = "";
	$question27_checked_38 = "";
	$question27_checked_39 = "";
	$question27_checked_40 = "";
	$question27_checked_41 = "";
	$question27_checked_42 = "";
	$question27_checked_43 = "checked='checked'";
	$question27_checked_44 = "";
}
else if($Question27[6] === '44') 
{
	$question27_checked_37 = "";
	$question27_checked_38 = "";
	$question27_checked_39 = "";
	$question27_checked_40 = "";
	$question27_checked_41 = "";
	$question27_checked_42 = "";
	$question27_checked_43 = "";
	$question27_checked_44 = "checked='checked'";
}

?>
<p>
	<input type="radio" name="question27[6]" class="question27 question27G  question_radio" value="37" <?php echo $question27_checked_37;?>><label class="question_item_header"><?php echo __("nenhuma");?></label>
	<input type="radio" name="question27[6]" class="question27 question27G  question_radio" value="38" <?php echo $question27_checked_38;?>><label class="question_item_header"><?php echo __("01");?></label> 
	<input type="radio" name="question27[6]" class="question27 question27G  question_radio" value="39" <?php echo $question27_checked_39;?>><label class="question_item_header"><?php echo __("02");?></label>   
	<input type="radio" name="question27[6]" class="question27 question27G  question_radio" value="40" <?php echo $question27_checked_40;?>><label class="question_item_header"><?php echo __("03");?></label>   
	<input type="radio" name="question27[6]" class="question27 question27G  question_radio" value="41" <?php echo $question27_checked_41;?>><label class="question_item_header"><?php echo __("04-08");?></label>  
	<input type="radio" name="question27[6]" class="question27 question27G  question_radio" value="42" <?php echo $question27_checked_42;?>><label class="question_item_header"><?php echo __("09-15");?></label>
	<input type="radio" name="question27[6]" class="question27 question27G  question_radio" value="43" <?php echo $question27_checked_43;?>><label class="question_item_header"><?php echo __("16-25");?></label>
	<input type="radio" name="question27[6]" class="question27 question27G  question_radio" value="44" <?php echo $question27_checked_44;?>><label class="question_item_header"><?php echo __(">25");?></label>   
</p>

<p class="question_header"><?php echo __("26. Na sua opiniao, atualmente,  qual e o tempo minimo de um estagio para a formacao do medico em EUS");?></p>
<?php 

	$question28_checked_0 = "";
	$question28_checked_1 = "";
	$question28_checked_2 = "";
	$question28_checked_3 = "";
	$question28_checked_4 = "";
	
if($Question28[0] === '0') 
{
	$question28_checked_0 = "checked='checked'";
	$question28_checked_1 = "";
	$question28_checked_2 = "";
	$question28_checked_3 = "";
	$question28_checked_4 = "";
}
else if($Question28[0] === '1')
{
	$question28_checked_0 = "";
	$question28_checked_1 = "checked='checked'";
	$question28_checked_2 = "";
	$question28_checked_3 = "";
	$question28_checked_4 = "";
}
else if($Question28[0] === '2')
{
	$question28_checked_0 = "";
	$question28_checked_1 = "";
	$question28_checked_2 = "checked='checked'";
	$question28_checked_3 = "";
	$question28_checked_4 = "";
}
else if($Question28[0] === '3')
{
	$question28_checked_0 = "";
	$question28_checked_1 = "";
	$question28_checked_2 = "";
	$question28_checked_3 = "checked='checked'";
	$question28_checked_4 = "";

}
else if($Question28[0] === '4')
{
	$question28_checked_0 = "";
	$question28_checked_1 = "";
	$question28_checked_2 = "";
	$question28_checked_3 = "";
	$question28_checked_4 = "checked='checked'";
}

?>
<p>
	<input type="radio" name="question28[0]" class="question28 question_radio" value="0" <?php echo $question28_checked_0;?>><label class="question_item_header"><?php echo __(" <= 3 meses ");?></label>
	<br>
	<input type="radio" name="question28[0]" class="question28 question_radio" value="1" <?php echo $question28_checked_1;?>><label class="question_item_header"><?php echo __(" 3-6 meses ");?></label>
	<br>
	<input type="radio" name="question28[0]" class="question28 question_radio" value="2" <?php echo $question28_checked_2;?>><label class="question_item_header"><?php echo __(" 6-9 meses ");?></label>
	<br>
	<input type="radio" name="question28[0]" class="question28 question_radio" value="3" <?php echo $question28_checked_3;?>><label class="question_item_header"><?php echo __("> 9 meses");?></label>
	<br>
	<input type="radio" name="question28[0]" class="question28 question_radio" value="4" <?php echo $question28_checked_4;?>><label class="question_item_header"><?php echo __("O tempo nao e relevante, pois o importante do treinamento e o numero de procedimentos.");?></label>
</p>

<br>
<p class="question_header"><?php echo __("27. Qual sua opiniao em relacao a formacao medica em EUS? (assinale todas as aplicaveis)");?></p>

<p>
	<label class="question_item_header"><?php echo __("O treinamento formal reduz o tempo para adquirir competencia")?></label>
		<?php 
		
		$question29_checked_0 = "";
		$question29_checked_1 = "";
		
		if($Question29[0] === '0') 
		{
			$question29_checked_0 = "checked='checked'";
			$question29_checked_1 = "";
		}
		else if($Question29[0] === '1')
		{
			$question29_checked_0 = "";
			$question29_checked_1 = "checked='checked'";
		}
		?>
		<input type="radio" name="question29[0]" class="question29 question_radio" value="0" <?php echo $question29_checked_0;?>><?php echo __("Sim");?> 
		<input type="radio" name="question29[0]" class="question29 question_radio" value="1" <?php echo $question29_checked_1;?>><?php echo __("Nao");?>
	<br>
	
	<label class="question_item_header"><?php echo __("O treinamento formal e necessario para adquirir competencia ")?></label>
		<?php 
		
		$question29_checked_2 = "";
		$question29_checked_3 = "";
			
		if($Question29[1] === '2') 
		{
			$question29_checked_2 = "checked='checked'";
			$question29_checked_3 = "";
		}
		else if($Question29[1] === '3')
		{
			$question29_checked_2 = "";
			$question29_checked_3 = "checked='checked'";
		}
		?>
		<input type="radio" name="question29[1]" class="question29 question_radio" value="2" <?php echo $question29_checked_2;?>><?php echo __("Sim");?> 
		<input type="radio" name="question29[1]" class="question29 question_radio" value="3" <?php echo $question29_checked_3;?>><?php echo __("Nao");?>
		  
	<br>
	
	<label class="question_item_header"><?php echo __("O treinamento formal e necessario para satisfazer fins legais")?></label>
		<?php 
		
		$question29_checked_4 = "";
		$question29_checked_5 = "";
			
		if($Question29[2] === '4') 
		{
			$question29_checked_4 = "checked='checked'";
			$question29_checked_5 = "";
		}
		else if($Question29[2] === '5')
		{
			$question29_checked_4 = "";
			$question29_checked_5 = "checked='checked'";
		}
		?>
		<input type="radio" name="question29[2]" class="question29 question_radio" value="4" <?php echo $question29_checked_4;?>><?php echo __("Sim");?> 
		<input type="radio" name="question29[2]" class="question29 question_radio" value="5" <?php echo $question29_checked_5;?>><?php echo __("Nao");?>	
	<br>
	
	<label class="question_item_header"><?php echo __("Estrategias de formacao devem depender das leis locais")?></label>
		<?php 
		
		$question29_checked_6 = "";
		$question29_checked_7 = "";
			
		if($Question29[3] === '6') 
		{
			$question29_checked_6 = "checked='checked'";
			$question29_checked_7 = "";
		}
		else if($Question29[3] === '7')
		{
			$question29_checked_6 = "";
			$question29_checked_7 = "checked='checked'";
		}
		?>
		<input type="radio" name="question29[3]" class="question29 question_radio" value="6" <?php echo $question29_checked_6;?>><?php echo __("Sim");?> 
		<input type="radio" name="question29[3]" class="question29 question_radio" value="7" <?php echo $question29_checked_7;?>><?php echo __("Nao");?>	
	<br>
	
	<label class="question_item_header"><?php echo __("Estrategias de formacao devem depender da sociedade de endoscopia")?></label>
		<?php 
		
		$question29_checked_8 = "";
		$question29_checked_9 = "";
			
		if($Question29[4] === '8') 
		{
			$question29_checked_8 = "checked='checked'";
			$question29_checked_9 = "";
		}
		else if($Question29[4] === '9')
		{
			$question29_checked_8 = "";
			$question29_checked_9 = "checked='checked'";
		}
		?>
		<input type="radio" name="question29[4]" class="question29 question_radio" value="8" <?php echo $question29_checked_8;?>><?php echo __("Sim");?> 
		<input type="radio" name="question29[4]" class="question29 question_radio" value="9" <?php echo $question29_checked_9;?>><?php echo __("Nao");?>	
	<br>
	
	<label class="question_item_header"><?php echo __("Outros")?></label>
		<input type="text" name="question29[5]" value="<?php echo $Question29[5];?>" class="question17 question_text">
</p>

<br>

<p class="survey_notice"><?php echo __('"As perguntas a seguir nao sao de preenchimento obrigatorio. Sao questoes que consideramos importantes, contudo, se voce nao se sentir confortavel em responder (uma ou mais destas) voce pode finalizar sua pesquisa neste momento". ')?></p>


<br>

<p class="question_header"><?php echo __("28. Qual percentagem aproximada que suas EUS sao reembolsadas pelas seguradoras?");?></p>
<?php 

$question30_checked_1 = "";
$question30_checked_3 = "";

if($Question30[1] === '1') $question30_checked_1 = "checked='checked'";
if($Question30[3] === '3') $question30_checked_3 = "checked='checked'";

?>
<table border=0 id="tbl_list_question30" class="tbl_question_body">
	<tr>
		<th><label class="question_item_header"><?php echo __("Procedimentos diagnosticos: ")?></label></th>
		<td><input type="text" name="question30[0]" id="question30[0]" onchange="cancelaCheck('question30[1]')" value="<?php echo $Question30[0];?>" class="question30 question_text">%</td>
		<td><input type="checkbox" name="question30[1]" id="question30[1]" onclick="zeraValor(this, 'question30[0]')" class="question30 question_radio" value="1" <?php echo  $question30_checked_1;?>><?php echo __("Nao sei");?></td>		
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __("Procedimentos com FNA:  ")?></label></th>
		<td><input type="text" name="question30[2]" id="question30[2]" onchange="cancelaCheck('question30[3]')" value="<?php echo $Question30[2];?>" class="question30 question_text">%</td>
		<td><input type="checkbox" name="question30[3]" id="question30[3]" onclick="zeraValor(this, 'question30[2]')" class="question30 question_radio" value="3" <?php echo  $question30_checked_3;?>><?php echo __("Nao sei");?></td>		
	</tr>
</table>

<br>

<p class="question_header"><?php echo __("29. Qual percentagem aproximada de seus procedimentos ecoendoscopicos sao reembolsados por programas de saude governamentais ou filantropicos?");?></p>
<?php 

$question31_checked_1 = "";
$question31_checked_3 = "";

if($Question31[1] === '1') $question31_checked_1 = "checked='checked'";
if($Question31[3] === '3') $question31_checked_3 = "checked='checked'";

?>
<table border=0 id="tbl_list_question31" class="tbl_question_body">
	<tr>
		<th><label class="question_item_header"><?php echo __("Procedimentos diagnosticos: ")?></label></th>
		<td><input type="text" name="question31[0]" id="question31[0]" onchange="cancelaCheck('question31[1]')" value="<?php echo $Question31[0];?>" class="question31 question_text">%</td>
		<td><input type="checkbox" name="question31[1]" id="question31[1]" onclick="zeraValor(this, 'question31[0]')" class="question31 question_radio" value="1" <?php echo  $question31_checked_1;?>><?php echo __("Nao sei");?></td>		
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __("Procedimentos com FNA:  ")?></label></th>
		<td><input type="text" name="question31[2]" id="question31[2]" onchange="cancelaCheck('question31[3]')" value="<?php echo $Question31[2];?>" class="question31 question_text">%</td>
		<td><input type="checkbox" name="question31[3]" id="question31[3]" onclick="zeraValor(this, 'question31[2]')" class="question31 question_radio" value="3" <?php echo  $question31_checked_3;?>><?php echo __("Nao sei");?></td>		
	</tr>
</table>

<br>

<p class="question_header"><?php echo __("30. Qual e o custo medio aproximado da EUS (particular) em seu pais? (em dolar- taxa de cambio oficial) ");?></p>
<p><input type="text" name="question32[0]" value="<?php echo $Question32[0];?>" class="question32 question_text"></p>

<br>

<p class="question_header"><?php echo __("31. Qual e o custo medio aproximado da EUS (particular) com FNA  em seu pais? (em dolar- taxa de cambio oficial) ");?></p>
<p><input type="text" name="question33[0]" value="<?php echo $Question33[0];?>" class="question33 question_text"></p>


<br>

<p class="question_header"><?php echo __("32. Qual e o custo medio aproximado de venda de uma agulha de EUS em seu pais? (em dolar- taxa de cambio oficial) ");?></p>
<p><input type="text" name="question34[0]" value="<?php echo $Question34[0];?>" class="question34 question_text"></p>


<br>

<p class="question_header"><?php echo __("33. Qual e o honorario medio aproximado, destinado ao medico, para a realizacao de uma EUS sem puncao em seu pais? (em dolar- taxa de cambio oficial) ");?></p>
<p><input type="text" name="question35[0]" value="<?php echo $Question35[0];?>" class="question35 question_text"></p>


<br>

<p class="question_header"><?php echo __("34. Qual e o honorario medio aproximado, destinado ao medico, para a realizacao de uma EUS com FNA em seu pais? (em dolar- taxa de cambio oficial) ");?></p>
<p><input type="text" name="question36[0]" value="<?php echo $Question36[0];?>" class="question36 question_text"></p>

<br>

<?php 
if($action == 'edit')
{
?>
	<input type="submit" name="SavePage3" id="SavePage3" value="<?php echo __("Salvar e Sair");?>" class="bt_submit bt_save_survey">
	<input type="submit" name="NextPageSurvey" id="NextPageSurvey" value="<?php echo __("Salvar e Finalizar");?>" class="bt_submit bt_next_page_survey">
<?php 
}
else
{
?>
	<input type="submit" name="NextPageSurvey" id="NextPageSurvey" value="<?php echo __("Visualizar &Uacute;ltima P&aacute;gina");?>" class="bt_submit bt_next_page_survey">
<?php 
}
?>
	<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
	<input type="hidden" name="action" id="action" value="<?php echo $action;?>">
</form>