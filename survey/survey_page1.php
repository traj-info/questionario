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
if(!empty($_REQUEST['SavePage1']) || !empty($_REQUEST['NextPageSurvey']))
{

	
	
		if($action == 'edit')
		{
			
			if(isset($_REQUEST['question3'][0]))
			{
				$sv_question3 = $_REQUEST['question3'][0];
			}
			else
			{
				$sv_question3 = null;
				$msgErrorSurvey[3] = __("Questão 1 não preenchida");
			}
			
			if(isset($_REQUEST['question4'][0]))
			{
				$sv_question4 = $_REQUEST['question4'][0];
			}
			else
			{
				$sv_question4 = null;
				$msgErrorSurvey[4] = __("Questão 2 não preenchida");
			}
			
			if(isset($_REQUEST['question5']))
			{
			
				$item[0] = (isset($_REQUEST['question5'][0]))?$_REQUEST['question5'][0]:null;
				$item[1] = (isset($_REQUEST['question5'][1]))?$_REQUEST['question5'][1]:null;
				
				if(!isset($item[0]) && !isset($item[1]))
				{
					$msgErrorSurvey[5] = __("Selecione um item da questão 3");
					$sv_question5 = null;
				}
				else 
				{
					$sv_question5 = implode(';',$item);
				}
				
				
				
			}
			else 
			{
				$sv_question5 = null;
				$msgErrorSurvey[5] = __("Selecione pelo menos um item da questão 3");
			}
			
			
			
			
			
			if(isset($_REQUEST['question6'][0]))
			{
				$sv_question6 = $_REQUEST['question6'][0];
			}
			else
			{
				$sv_question6 = null;
				$msgErrorSurvey[6]= __("Questão 4 não preenchida");
			}
			
			
			
			if(isset($_REQUEST['question7']))
			{
				$item = array_fill(0, 6, null);
				
				$item[0] = (isset($_REQUEST['question7'][0]))?$_REQUEST['question7'][0]:null;
				$item[1] = (isset($_REQUEST['question7'][1]))?$_REQUEST['question7'][1]:null;
				$item[2] = (isset($_REQUEST['question7'][2]))?$_REQUEST['question7'][2]:null;
				$item[3] = (isset($_REQUEST['question7'][3]))?$_REQUEST['question7'][3]:null;
				$item[4] = (isset($_REQUEST['question7'][4]))?$_REQUEST['question7'][4]:null;
				$item[5] = (isset($_REQUEST['question7'][5]))?$_REQUEST['question7'][5]:null;
				
				$sv_question7 = implode(';',$item);
				
				
				if(!isset($item[0]) && !isset($item[1])&& !isset($item[2])&& !isset($item[3])&& !isset($item[4])&& !isset($item[5]))
				{
					$msgErrorSurvey[7]= __("Selecione pelo menos um item da questão 5");
					$sv_question7 = null;
				}
				else 
				{
					$sv_question7 = implode(';',$item);
					
				}
				
				
			}
			else 
			{
				$sv_question7 = null;
				$msgErrorSurvey[7]= __("Selecione pelo menos um item da questão 5");
			}
			
			
			
			
			
			
			
			if(isset($_REQUEST['question8']))
			{
				$item = array_fill(0, 7, null);
				
				$item[0] = (isset($_REQUEST['question8'][0]))?$_REQUEST['question8'][0]:null;
				$item[1] = (isset($_REQUEST['question8'][1]))?$_REQUEST['question8'][1]:null;
				$item[2] = (isset($_REQUEST['question8'][2]))?$_REQUEST['question8'][2]:null;
				$item[3] = (isset($_REQUEST['question8'][3]))?$_REQUEST['question8'][3]:null;
				$item[4] = (isset($_REQUEST['question8'][4]))?$_REQUEST['question8'][4]:null;
				$item[5] = (isset($_REQUEST['question8'][5]))?$_REQUEST['question8'][5]:null;
				$item[6] = (isset($_REQUEST['question8'][6]))?$_REQUEST['question8'][6]:null;
				
				$sv_question8 = implode(';',$item);
				
				if(!isset($item[0]) && !isset($item[1])&& !isset($item[2])&& !isset($item[3])&& !isset($item[4])&& !isset($item[5])&& !isset($item[6]))
				{
					$msgErrorSurvey[8]= __("Selecione pelo menos um item da questão 6");
					$sv_question8 = null;
				}
				else 
				{
					$sv_question8 = implode(';',$item);
					
				}
				
			}
			else 
			{
				$msgErrorSurvey[8]= __("Selecione pelo menos um item da questão 6");
				$sv_question8 = null;
			}
			
		
			
			if(is_array($_REQUEST['question9']))
			{
				$item = array_fill(0, 6, null);
				$inst_null = 0;
				$cid_null = 0;
				
				for($i = 0; $i<6; $i++)
				{
					$item[$i] = (isset($_REQUEST['question9'][$i]) && !empty($_REQUEST['question9'][$i]))?trim(FilterData($_REQUEST['question9'][$i])):null;
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
					$sv_question9 = null;
					$msgErrorSurvey[9] = __("Preencha pelo menos uma instituição e uma cidade/País na questão 7");
				}
				else
				{
					$sv_question9 = implode(';',$item);
				}
			}
			else 
			{
				$msgErrorSurvey[9] = __("Preencha pelo menos uma instituição e uma cidade/País na questão 7");
				$sv_question9 = null;
			}
			
			if(!isset($_REQUEST['question10']))
			{
				$_REQUEST['question10'] = array(0=>null,1=>null,2=>null,3=>null,4=>null,5=>null,6=>null);
			}
				$item = array_fill(0, 7, null);
			
				$item[0] = (isset($_REQUEST['question10'][0]))?$_REQUEST['question10'][0]:null;
				$item[1] = (isset($_REQUEST['question10'][1]))?$_REQUEST['question10'][1]:null;
				$item[2] = (isset($_REQUEST['question10'][2]))?$_REQUEST['question10'][2]:null;
				$item[3] = (isset($_REQUEST['question10'][3]))?$_REQUEST['question10'][3]:null;
				$item[4] = (isset($_REQUEST['question10'][4]))?$_REQUEST['question10'][4]:null;
				$item[5] = (isset($_REQUEST['question10'][5]))?$_REQUEST['question10'][5]:null;
				$item[6] = (isset($_REQUEST['question10'][6]))?$_REQUEST['question10'][6]:null;

				
				$sv_question10 = implode(';',$item);
				
				$msgErrorSurvey[10]  = null;
				
				if(!isset($item[0]))$msgErrorSurvey[10] .= __("Selecione um item do sub-item A da questão 8").'||';
				if(!isset($item[1]))$msgErrorSurvey[10] .= __("Selecione um item do sub-item B da questão 8").'||';
				if(!isset($item[2]))$msgErrorSurvey[10] .= __("Selecione um item do sub-item C da questão 8").'||';
				if(!isset($item[3]))$msgErrorSurvey[10] .= __("Selecione um item do sub-item D da questão 8").'||';
				if(!isset($item[4]))$msgErrorSurvey[10] .= __("Selecione um item do sub-item E da questão 8").'||';
				if(!isset($item[5]))$msgErrorSurvey[10] .= __("Selecione um item do sub-item F da questão 8").'||';
				if(!isset($item[6]))$msgErrorSurvey[10] .= __("Selecione um item do sub-item G da questão 8").'||';

				
				if(is_null($msgErrorSurvey[10]))
				{
					unset($msgErrorSurvey[10]);
				}
				else 
				{
					$msgErrorSurvey[10] = substr($msgErrorSurvey[10],0,-2);
				}
			
				
			if(isset($_REQUEST['question11'][0]))
			{
				$sv_question11 = trim(FilterData($_REQUEST['question11'][0]));
				
				if(empty($sv_question11) && (strpos($sv_question11, '0') === false))
				{
					$sv_question11 = null;
					$msgErrorSurvey[11]= __("Preencha a questão 9");
				}
				else // não está vazio...
				{
					if((preg_match("/^[0-9]+$/", $sv_question11))) // é um número inteiro...
					{
						if((int)$sv_question11 > 60) // ... mas é maior que 60 - erro
						{
							$sv_question11 = null;
							$msgErrorSurvey[11]= __("Preencha a questão 9 com um valor numérico de 0 a 60.");
						}
						else // tudo ok
						{
							$sv_question11 = (int)$sv_question11;	
						}
					}
					else // erro - preencheu errado
					{
						$sv_question11 = null;
						$msgErrorSurvey[11]= __("Preencha a questão 9 com um valor numérico de 0 a 60.");
					}
				}
			}
			else // campo vazio
			{
				$sv_question11 = null;
				$msgErrorSurvey[11]= __("Preencha a questão 9");
			}
			
			
			
			
			
			if(isset($_REQUEST['question12'][0]))
			{
				$sv_question12 = $_REQUEST['question12'][0];
			}
			else
			{
				$sv_question12 = null;
				$msgErrorSurvey[12]= __("Questão 10 não preenchida");
			}
			
			
			if(is_array($_REQUEST['question13']))
			{
				
				$item = array_fill(0, 6, null);
				$el_null = 0;
				
				for($i = 0; $i<6; $i++)
				{
					$flag_erro = false;
					$item[$i] = (isset($_REQUEST['question13'][$i]))?trim(FilterData($_REQUEST['question13'][$i])):null;
					
					if(!is_numeric($item[$i]) && !empty($item[$i]) && (strpos(trim($item[$i]), '0') !== 0)) $flag_erro = true;
					if((!preg_match("/^[0-9]+$/", trim($item[$i])))) $flag_erro = true;
					
					if($flag_erro) 
					{	
						$el_null++;
						$item[$i] = null;
					}
					else
					{
						$item[$i] = (int)$item[$i];
					}
				}
				
				$sv_question13 = implode(';',$item);
				
				if($el_null != 0)
				{
					$msgErrorSurvey[13] = __("Preencha todos os campos da questão 11 com valores numéricos.").'<BR>';
					
					if($el_null == 6) $sv_question13 = null;
				}
				
				
			}
			else 
			{
				$msgErrorSurvey[13] = __("Preencha todos os campos da questão 11 com valores numéricos").'<BR>';
			}
			
			
		
			
			
			if(isset($_REQUEST['question14']))
			{
				
				
				$item = array_fill(0, 2, null);
				$el_null = 0;
				
				for($i = 0; $i<2; $i++)
				{
				
					$flag_erro = false;
					$item[$i] = (isset($_REQUEST['question14'][$i]))?trim(FilterData($_REQUEST['question14'][$i])):null;
					
					if(!is_numeric($item[$i]) && !empty($item[$i]) && (strpos(trim($item[$i]), '0') !== 0)) $flag_erro = true;
					if((!preg_match("/^[0-9]+$/", trim($item[$i])))) $flag_erro = true;
				
					if($flag_erro) 
					{	
						$el_null++;
						$item[$i] = null;
					}
					else
					{
						$item[$i] = (int)$item[$i];
					}
				}
				
				$sv_question14 = implode(';',$item);
				
				if($el_null != 0)
				{
					$msgErrorSurvey[14] = __("Preencha todos os campos da questão 12 com valores numéricos").'<BR>';
					if($el_null == 6) $sv_question14 = null;
				}
				
			}
			else 
			{
				$msgErrorSurvey[14] = __("Preencha todos os campos da questão 12 com valores numéricos").'<BR>';
				$sv_question14 = null;
			}
			
			
				//salva no banco
				$query = " UPDATE surveys
							SET ";
				
				
				
				if(!isset($msgErrorSurvey[3])) $query .= "	question3 = '".trim(FilterData($sv_question3))."',";
				if(!isset($msgErrorSurvey[4])) $query .= "	question4 = '".trim(FilterData($sv_question4))."',";				
				if(!isset($msgErrorSurvey[6])) $query .= "	question6 = '".trim(FilterData($sv_question6))."',";
				if(!isset($msgErrorSurvey[9])) $query .= "	question9 = '".trim(FilterData($sv_question9))."',";
				if(empty($msgErrorSurvey[10])) $query .= "	question10 = '".trim(FilterData($sv_question10))."',";		
				if(!isset($msgErrorSurvey[11])) $query .= "	question11 = '".trim(FilterData($sv_question11))."',";		
				if(!isset($msgErrorSurvey[12])) $query .= "question12 = '".trim(FilterData($sv_question12))."',";				
							
					$query .= "
								question5 = '".trim(FilterData($sv_question5))."',								
								question7 = '".trim(FilterData($sv_question7))."',
								question8 = '".trim(FilterData($sv_question8))."',		
								question13 = '".trim(FilterData($sv_question13))."',
								question14 = '".trim(FilterData($sv_question14))."',
								
								modified = '".NowDatetime()."'
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
						
						if(!empty($_REQUEST['SavePage1']))
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
							
						//encode questao 10 e envia via url
						if(isset($_REQUEST['question10']))
						{
							$question10_url = null;
							
							for($i = 0; $i <= 7; $i++)
							{
								$item = (isset($_REQUEST['question10'][$i]))?$_REQUEST['question10'][$i]:null;
								
								$question10_url .= '&question10['.$i.']='.$item;
							}
						}
						
						//encode questao 10 e envia via url
						if(isset($_REQUEST['question9']))
						{
							$question9_url = null;
							
							for($i = 0; $i < 6; $i++)
							{
								$item = (isset($_REQUEST['question9'][$i]))?urlencode($_REQUEST['question9'][$i]):null;
								
								$question9_url .= '&question9['.$i.']='.$item;
							}
						}
						
						
						
						
						#Chamaremos a funcao criada para direcionar as mensagens.
						errorMsg(INDEX, 'survey', 'survey_page1', $errorMessage, $_REQUEST['lang'], "&user_id=".$user_id."&action=".$action."".$question10_url."".$question9_url);
					}		
					
				
				}
		
			
		}
		else 
		{
			$successMessage = "";
		}
		
		successMsg(INDEX,'survey', 'survey_page2', $successMessage, $_REQUEST['lang'], "&user_id=".$user_id."&action=".$action);
		
		
		
}	
		
	




// BUSCANDO VALORES PREENCHIDOS ANTERIORMENTE PARA ESSE USUARIO

$query = "SELECT 
				question3, 
				question4,
				question5,
				question6,
				question7,
				question8,
				question9,
				question10,
				question11,
				question12,
				question13,
				question14
				 
		FROM surveys 
		WHERE user_id=".$user_id." 
		ORDER BY id LIMIT 1 " ;


$DataSurvey = $connection->GetResult($query);

if($DataSurvey['question3'] != null)
{
	$Question3[0] = $DataSurvey['question3'];
}
else
{
	$Question3[0] = null;
}



if($DataSurvey['question4'] != null)
{
	$Question4[0] = $DataSurvey['question4'];
}
else
{
	$Question4[0] = null;
}


if(!empty($DataSurvey['question5']))
{
	$Question5 = explode(';',$DataSurvey['question5']);
}
else
{
	$Question5 = array(0 => null, 1 => null );
}

if($DataSurvey['question6'] != null)
{
	$Question6[0] = $DataSurvey['question6'];
}
else
{
	$Question6[0] = null;
}


if(!empty($DataSurvey['question7']))
{
	$Question7 = explode(';',$DataSurvey['question7']);
}
else
{
	$Question7 = array(0 => null, 1 => null,2 => null, 3 => null, 4 => null, 5 => null );
}

			
if(!empty($DataSurvey['question8']))
{
	$Question8 = explode(';',$DataSurvey['question8']);
}
else
{
	$Question8 = array(0 => null, 1 => null,2 => null, 3 => null, 4 => null, 5 => null, 6 => null );
}

if(!empty($DataSurvey['question9']))
{
	
	$Question9 = explode(';',$DataSurvey['question9']);
}
else
{
	$Question9 = array_fill(0,6,null);
	
	for($i = 0; $i<6; $i++)
	{
		$Question9[$i] = (isset($_REQUEST['question9'][$i]))?$_REQUEST['question9'][$i]:null;
	}
	
	
}

if($DataSurvey['question10'] != null)
{
	$Question10 = explode(';',$DataSurvey['question10']);
	
}
else
{
	$Question10 = array_fill(0,8,null);
	
	for($i = 0; $i< 8; $i++)
	{
		$Question10[$i] = (isset($_REQUEST['question10'][$i]))?$_REQUEST['question10'][$i]:null;
	}
	
}

if($DataSurvey['question11'] != null)
{
	$Question11[0] = $DataSurvey['question11'];
}
else
{
	$Question11[0] = null;
}

if($DataSurvey['question12'] != null)
{
	$Question12[0] = $DataSurvey['question12'];
}
else
{
	$Question12[0] = null;
}			
			
if(!empty($DataSurvey['question13']))
{
	$Question13 = explode(';',$DataSurvey['question13']);
}
else
{
	$Question13 = array(0 => null, 1 => null,2 => null, 3 => null, 4 => null, 5 => null);
}			
		
if(!empty($DataSurvey['question14']))
{
	$Question14 = explode(';',$DataSurvey['question14']);
}
else
{
	$Question14 = array(0 => null, 1 => null);
}			
			

?>

<h3><?php echo __("Página");?> 2</h3>
<form method="post" action="index.php?module=survey&page=survey_page1&lang=<?php echo $_REQUEST['lang'];?>" name="frm_survey_page1" id="frm_survey_page1" class="frm_survey">
<br>
<p class="question_header"><?php echo __("1. Sexo:");?></p>
<?php 

$question3_checked_0 = "";
$question3_checked_1 = "";

if($Question3[0] === '0') 
{
	$question3_checked_0 = "checked='checked'";
	$question3_checked_1 = "";
}
else if($Question3[0] === '1')
{
	$question3_checked_0 = "";
	$question3_checked_1 = "checked='checked'";
}
?>
<p>
	<input type="radio" name="question3[0]" class="question3 question_radio" value="0" <?php echo $question3_checked_0;?>><label class="question_item_header"><?php echo __(" Masculino");?></label>
	<br>
	<input type="radio" name="question3[0]" class="question3 question_radio" value="1" <?php echo $question3_checked_1;?>><label class="question_item_header"><?php echo __(" Feminino");?></label>
</p>

<br>

<p class="question_header"><?php echo __("2. Idade:");?></p>
<?php 

	$question4_checked_0 = "";
	$question4_checked_1 = "";
	$question4_checked_2 = "";
	$question4_checked_3 = "";
	$question4_checked_4 = "";
	
if($Question4[0] === '0') 
{
	$question4_checked_0 = "checked='checked'";
	$question4_checked_1 = "";
	$question4_checked_2 = "";
	$question4_checked_3 = "";
	$question4_checked_4 = "";
}
else if($Question4[0] === '1')
{
	$question4_checked_0 = "";
	$question4_checked_1 = "checked='checked'";
	$question4_checked_2 = "";
	$question4_checked_3 = "";
	$question4_checked_4 = "";
}
else if($Question4[0] === '2')
{
	$question4_checked_0 = "";
	$question4_checked_1 = "";
	$question4_checked_2 = "checked='checked'";
	$question4_checked_3 = "";
	$question4_checked_4 = "";
}
else if($Question4[0] === '3')
{
	$question4_checked_0 = "";
	$question4_checked_1 = "";
	$question4_checked_2 = "";
	$question4_checked_3 = "checked='checked'";
	$question4_checked_4 = "";
}
else if($Question4[0] === '4')
{
	$question4_checked_0 = "";
	$question4_checked_1 = "";
	$question4_checked_2 = "";
	$question4_checked_3 = "";
	$question4_checked_4 = "checked='checked'";
}


?>
<p>
	<input type="radio" name="question4[0]" class="question4 question_radio" value="0" <?php echo $question4_checked_0;?>><label class="question_item_header" ><?php echo __("<= 32");?></label>
	<br>
	<input type="radio" name="question4[0]" class="question4 question_radio" value="1" <?php echo $question4_checked_1;?>><label class="question_item_header"><?php echo __("33-42");?></label>
	<br>
	<input type="radio" name="question4[0]" class="question4 question_radio" value="2" <?php echo $question4_checked_2;?>><label class="question_item_header" ><?php echo __("43-52");?></label>
	<br>
	<input type="radio" name="question4[0]" class="question4 question_radio" value="3" <?php echo $question4_checked_3;?>><label class="question_item_header"><?php echo __("53-62");?></label>
	<br>
	<input type="radio" name="question4[0]" class="question4 question_radio" value="4" <?php echo $question4_checked_4;?>><label class="question_item_header"><?php echo __("> 62");?></label>
</p>

<br>

<p class="question_header"><?php echo __("3. Formacao medica (assinale todas as aplicaveis):");?></p>
<?php 

$question5_checked_0 = "";
$question5_checked_1 = "";

if($Question5[0] === '0') $question5_checked_0 = "checked='checked'";
if($Question5[1] === '1') $question5_checked_1 = "checked='checked'";

?>
<p>
	<input type="checkbox" name="question5[0]" class="question5 question_cbox" value="0" <?php echo $question5_checked_0;?>><label class="question_item_header"><?php echo __(" Gastroenterologia");?></label>
	<br>
	<input type="checkbox" name="question5[1]" class="question5 question_cbox" value="1" <?php echo $question5_checked_1;?>><label class="question_item_header"><?php echo __(" Cirurgia");?></label>
</p>

<br>

<p class="question_header"><?php echo __("4. Voce realiza ou foi treinado em CPRE?");?></p>
<?php 

	$question6_checked_0 = "";
	$question6_checked_1 = "";
	$question6_checked_2 = "";
	
if($Question6[0] === '0')
{
	$question6_checked_0 = "checked='checked'";
	$question6_checked_1 = "";
	$question6_checked_2 = "";
}
else if($Question6[0] === '1') 
{
	$question6_checked_0 = "";
	$question6_checked_1 = "checked='checked'";
	$question6_checked_2 = "";
}
else if($Question6[0] === '2') 
{
	$question6_checked_0 = "";
	$question6_checked_1 = "";
	$question6_checked_2 = "checked='checked'";
}

?>
<p>
	<input type="radio" name="question6[0]" class="question6 question_radio" value="0" <?php echo $question6_checked_0;?>><label class="question_item_header"><?php echo __(" Sim, eu realizo.");?></label>
	<br>
	<input type="radio" name="question6[0]" class="question6 question_radio" value="1" <?php echo $question6_checked_1;?>><label class="question_item_header"><?php echo __(" Nao.");?></label>
	<br>
	<input type="radio" name="question6[0]" class="question6 question_radio" value="2" <?php echo $question6_checked_2;?>><label class="question_item_header"><?php echo __(" Eu fui treinado, mas nao realizo mais.");?></label>
</p>

<br>

<p class="question_header"><?php echo __("5. Como voce caracterizaria a sua pratica atual em EUS? (assinale todas as aplicaveis):");?></p>
<?php 

$question7_checked_0 = "";
$question7_checked_1 = "";
$question7_checked_2 = "";
$question7_checked_3 = "";
$question7_checked_4 = "";
$question7_checked_5 = "";


if($Question7[0] === '0') $question7_checked_0 = "checked='checked'";
if($Question7[1] === '1') $question7_checked_1 = "checked='checked'";
if($Question7[2] === '2') $question7_checked_2 = "checked='checked'";
if($Question7[3] === '3') $question7_checked_3 = "checked='checked'";
if($Question7[4] === '4') $question7_checked_4 = "checked='checked'";
if($Question7[5] === '5') $question7_checked_5 = "checked='checked'";

?>
<br>
<table border=1 id="tbl_list_question7" class="tbl_question_body">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th><label class="question_item_header"><?php echo __("HOSPITAL");?></label></th>
			<th><label class="question_item_header"><?php echo __("AMBIENTE NAO HOSPITALAR");?></label></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th><label class="question_item_header"><?php echo __("Medico funcionario de uma instituicao do governo");?></label></th>
			<td><input type="checkbox" class="cbox_item_table question7" name="question7[0]" value="0" <?php echo $question7_checked_0;?>></td>
			<td><input type="checkbox" class="cbox_item_table question7" name="question7[1]" value="1" <?php echo $question7_checked_1;?>></td>
		</tr>
		<tr>
			<th><label class="question_item_header"><?php echo __("Medico funcionario de uma instituicao privada")?></label></th>
			<td><input type="checkbox" class="cbox_item_table question7" name="question7[2]" value="2" <?php echo $question7_checked_2;?>></td>
			<td><input type="checkbox" class="cbox_item_table question7" name="question7[3]" value="3" <?php echo $question7_checked_3;?>></td>
		</tr>
		<tr>
			<th><label class="question_item_header"><?php echo __("Pratica independente")?></label></th>
			<td><input type="checkbox" class="cbox_item_table question7" name="question7[4]" value="4" <?php echo $question7_checked_4;?>></td>
			<td><input type="checkbox" class="cbox_item_table question7" name="question7[5]" value="5" <?php echo $question7_checked_5;?>></td>
		</tr>
	</tbody>
</table>

<br>

<p class="question_header"><?php echo __("6. Como voce foi treinado em EUS? (assinale todas as aplicaveis):");?></p>
<?php 

$question8_checked_0 = "";
$question8_checked_1 = "";
$question8_checked_2 = "";
$question8_checked_3 = "";
$question8_checked_4 = "";
$question8_checked_5 = "";
$question8_checked_6 = "";

if($Question8[0] === '0')	$question8_checked_0 = "checked='checked'";
if($Question8[1] === '1') $question8_checked_1 = "checked='checked'";
if($Question8[2] === '2') $question8_checked_2 = "checked='checked'";
if($Question8[3] === '3') $question8_checked_3 = "checked='checked'";
if($Question8[4] === '4') $question8_checked_4 = "checked='checked'";
if($Question8[5] === '5') $question8_checked_5 = "checked='checked'";
if($Question8[6] === '6') $question8_checked_6 = "checked='checked'";

?>

<p>
	<input type="checkbox" name="question8[0]" class="question8 question_cbox" value="0"  <?php echo $question8_checked_0;?>><label class="question_item_header"><?php echo __(" Autodidata");?></label>
	<br>
	<input type="checkbox" name="question8[1]" class="question8 question_cbox" value="1" <?php echo $question8_checked_1;?>><label class="question_item_header"><?php echo __(" Observando pessoalmente endossonografistas experientes");?></label>
	<br>
	<input type="checkbox" name="question8[2]" class="question8 question_cbox" value="2" <?php echo $question8_checked_2;?>><label class="question_item_header" ><?php echo __(" Observando endossonografistas em cursos e congressos");?></label>
	<br>
	<input type="checkbox" name="question8[3]" class="question8 question_cbox" value="3" <?php echo $question8_checked_3;?>><label class="question_item_header"><?php echo __(" Durante a minha formacao em gastroenterologia ou cirurgia ou endoscopia gastrointestinal");?></label>
	<br>
	<input type="checkbox" name="question8[4]" class="question8 question_cbox" value="4" <?php echo $question8_checked_4;?>><label class="question_item_header"><?php echo __(" Estagio formal hands-on em EUS (<3 meses) apos a minha formacao em gastroenterologia ou cirurgia ou endoscopia gastrointestinal ");?></label>
	<br>
	<input type="checkbox" name="question8[5]" class="question8 question_cbox" value="5" <?php echo $question8_checked_5;?>><label class="question_item_header"><?php echo __(" Estagio formal hands-on em EUS (3-6 meses) apos a minha formacao em gastroenterologia ou cirurgia ou endoscopia gastrointestinal ");?></label>
	<br>
	<input type="checkbox" name="question8[6]" class="question8 question_cbox" value="6" <?php echo $question8_checked_6;?>><label class="question_item_header"><?php echo __(" Estagio formal hands-on em EUS (>6 meses) apos a minha formacao em gastroenterologia ou cirurgia ou endoscopia gastrointestinal  ");?></label>
</p>

<br>

<p class="question_header"><?php echo __("7. Onde voce foi treinado em EUS?");?></p>
<br>
<table border=0 id="tbl_list_question9" class="tbl_question_body">
	<tr>
		<th><label class="question_item_header"><?php echo __("Instituicao 1: ");?></label></th>
		<td><input type="text" name="question9[0]" value="<?php echo $Question9[0];?>" class="question9 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __("Cidade/Pais: ");?></label></th>
		<td><input type="text" name="question9[1]" value="<?php echo $Question9[1];?>" class="question9 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __("Instituicao 2: ");?></label></th>
		<td><input type="text" name="question9[2]" value="<?php echo $Question9[2];?>" class="question9 question_text"></td>
	</tr><tr>
		<th><label class="question_item_header"><?php echo __("Cidade/Pais: ");?></label></th>
		<td><input type="text" name="question9[3]" value="<?php echo $Question9[3];?>" class="question9 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __("Instituicao 3: ");?></label></th>
		<td><input type="text" name="question9[4]" value="<?php echo $Question9[4];?>" class="question9 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __("Cidade/Pais: ");?></label></th>
		<td><input type="text" name="question9[5]" value="<?php echo $Question9[5];?>" class="question9 question_text"></td>
	</tr>
</table>

<br>

<p class="question_header"><?php echo __('8. Aproximadamente quantas EUS "hands-on" voce realizou sob a supervisao de outro endossonografista durante o seu treinamento?');?></p>
<?php 

	$question10_checked_0 = "";
	$question10_checked_1 = "";
	$question10_checked_2 = "";
	$question10_checked_3 = "";
	$question10_checked_4 = "";
	$question10_checked_5 = "";
	
if($Question10[0] === '0')
{
	$question10_checked_0 = "checked='checked'";
	$question10_checked_1 = "";
	$question10_checked_2 = "";
	$question10_checked_3 = "";
	$question10_checked_4 = "";
	$question10_checked_5 = "";
}
else if($Question10[0] === '1') 
{
	$question10_checked_0 = "";
	$question10_checked_1 = "checked='checked'";
	$question10_checked_2 = "";
	$question10_checked_3 = "";
	$question10_checked_4 = "";
	$question10_checked_5 = "";
}
else if($Question10[0] === '2') 
{
	$question10_checked_0 = "";
	$question10_checked_1 = "";
	$question10_checked_2 = "checked='checked'";
	$question10_checked_3 = "";
	$question10_checked_4 = "";
	$question10_checked_5 = "";
}
else if($Question10[0] === '3') 
{
	$question10_checked_0 = "";
	$question10_checked_1 = "";
	$question10_checked_2 = "";
	$question10_checked_3 = "checked='checked'";
	$question10_checked_4 = "";
	$question10_checked_5 = "";
}
else if($Question10[0] === '4') 
{
	$question10_checked_0 = "";
	$question10_checked_1 = "";
	$question10_checked_2 = "";
	$question10_checked_3 = "";
	$question10_checked_4 = "checked='checked'";
	$question10_checked_5 = "";
}
else if($Question10[0] === '5') 
{
	$question10_checked_0 = "";
	$question10_checked_1 = "";
	$question10_checked_2 = "";
	$question10_checked_3 = "";
	$question10_checked_4 = "";
	$question10_checked_5 = "checked='checked'";
}

?>
<p class="question_subheader"><?php echo __("A. Anorretal")?></p>
<p>
	<input type="radio" name="question10[0]" class="question10 question10A  question_radio" value="0" <?php echo $question10_checked_0;?>><label class="question_item_header"><?php echo __("nenhuma");?></label>
	<input type="radio" name="question10[0]" class="question10 question10A  question_radio" value="1" <?php echo $question10_checked_1;?>><label class="question_item_header"><?php echo __("<= 5");?></label> 
	<input type="radio" name="question10[0]" class="question10 question10A  question_radio" value="2" <?php echo $question10_checked_2;?>><label class="question_item_header"><?php echo __("6-10");?></label>   
	<input type="radio" name="question10[0]" class="question10 question10A  question_radio" value="3" <?php echo $question10_checked_3;?>><label class="question_item_header"><?php echo __("11-20");?></label>   
	<input type="radio" name="question10[0]" class="question10 question10A  question_radio" value="4" <?php echo $question10_checked_4;?>><label class="question_item_header"><?php echo __("21-50");?></label>  
	<input type="radio" name="question10[0]" class="question10 question10A  question_radio" value="5" <?php echo $question10_checked_5;?>><label class="question_item_header"><?php echo __(">50");?></label>   
</p>

<p class="question_subheader"><?php echo __("B. Esofago")?></p>
<?php 

	$question10_checked_6 = "";
	$question10_checked_7 = "";
	$question10_checked_8 = "";
	$question10_checked_9 = "";
	$question10_checked_10 = "";
	$question10_checked_11 = "";
	
if($Question10[1] === '6')
{
	$question10_checked_6 = "checked='checked'";
	$question10_checked_7 = "";
	$question10_checked_8 = "";
	$question10_checked_9 = "";
	$question10_checked_10 = "";
	$question10_checked_11 = "";
}
else if($Question10[1] === '7') 
{
	$question10_checked_6 = "";
	$question10_checked_7 = "checked='checked'";
	$question10_checked_8 = "";
	$question10_checked_9 = "";
	$question10_checked_10 = "";
	$question10_checked_11 = "";
}
else if($Question10[1] === '8') 
{
	$question10_checked_6 = "";
	$question10_checked_7 = "";
	$question10_checked_8 = "checked='checked'";
	$question10_checked_9 = "";
	$question10_checked_10 = "";
	$question10_checked_11 = "";
}
else if($Question10[1] === '9') 
{
	$question10_checked_6 = "";
	$question10_checked_7 = "";
	$question10_checked_8 = "";
	$question10_checked_9 = "checked='checked'";
	$question10_checked_10 = "";
	$question10_checked_11 = "";
}
else if($Question10[1] === '10') 
{
	$question10_checked_6 = "";
	$question10_checked_7 = "";
	$question10_checked_8 = "";
	$question10_checked_9 = "";
	$question10_checked_10 = "checked='checked'";
	$question10_checked_11 = "";
}
else if($Question10[1] === '11') 
{
	$question10_checked_6 = "";
	$question10_checked_7 = "";
	$question10_checked_8 = "";
	$question10_checked_9 = "";
	$question10_checked_10 = "";
	$question10_checked_11 = "checked='checked'";
}

?>

<p>
	<input type="radio" name="question10[1]" class="question10 question10B  question_radio" value="6" <?php echo $question10_checked_6;?>><label class="question_item_header"><?php echo __("nenhuma");?></label>
	<input type="radio" name="question10[1]" class="question10 question10B  question_radio" value="7" <?php echo $question10_checked_7;?>><label class="question_item_header"><?php echo __("<= 5");?></label> 
	<input type="radio" name="question10[1]" class="question10 question10B  question_radio" value="8" <?php echo $question10_checked_8;?>><label class="question_item_header"><?php echo __("6-10");?></label>   
	<input type="radio" name="question10[1]" class="question10 question10B  question_radio" value="9" <?php echo $question10_checked_9;?>><label class="question_item_header"><?php echo __("11-20");?></label>   
	<input type="radio" name="question10[1]" class="question10 question10B  question_radio" value="10" <?php echo $question10_checked_10;?>><label class="question_item_header"><?php echo __("21-50");?></label>  
	<input type="radio" name="question10[1]" class="question10 question10B  question_radio" value="11" <?php echo $question10_checked_11;?>><label class="question_item_header"><?php echo __(">50");?></label>   
</p>


<p class="question_subheader"><?php echo __("C. Gastroduodenal ")?></p>
<?php 

	$question10_checked_12 = "";
	$question10_checked_13 = "";
	$question10_checked_14 = "";
	$question10_checked_15 = "";
	$question10_checked_16 = "";
	$question10_checked_17 = "";
	
	
if($Question10[2] === '12')
{
	$question10_checked_12 = "checked='checked'";
	$question10_checked_13 = "";
	$question10_checked_14 = "";
	$question10_checked_15 = "";
	$question10_checked_16 = "";
	$question10_checked_17 = "";
	
}
else if($Question10[2] === '13') 
{
	$question10_checked_12 = "";
	$question10_checked_13 = "checked='checked'";
	$question10_checked_14 = "";
	$question10_checked_15 = "";
	$question10_checked_16 = "";
	$question10_checked_17 = "";
	
}
else if($Question10[2] === '14') 
{
	$question10_checked_12 = "";
	$question10_checked_13 = "";
	$question10_checked_14 = "checked='checked'";
	$question10_checked_15 = "";
	$question10_checked_16 = "";
	$question10_checked_17 = "";
	
}
else if($Question10[2] === '15') 
{
	$question10_checked_12 = "";
	$question10_checked_13 = "";
	$question10_checked_14 = "";
	$question10_checked_15 = "checked='checked'";
	$question10_checked_16 = "";
	$question10_checked_17 = "";
	
}
else if($Question10[2] === '16') 
{
	$question10_checked_12 = "";
	$question10_checked_13 = "";
	$question10_checked_14 = "";
	$question10_checked_15 = "";
	$question10_checked_16 = "checked='checked'";
	$question10_checked_17 = "";
}
else if($Question10[2] === '17') 
{
	$question10_checked_12 = "";
	$question10_checked_13 = "";
	$question10_checked_14 = "";
	$question10_checked_15 = "";
	$question10_checked_16 = "";
	$question10_checked_17 = "checked='checked'";
}

?>
<p>
	<input type="radio" name="question10[2]" class="question10 question10C  question_radio" value="12" <?php echo $question10_checked_12;?>><label class="question_item_header"><?php echo __("nenhuma");?></label>
	<input type="radio" name="question10[2]" class="question10 question10C  question_radio" value="13" <?php echo $question10_checked_13;?>><label class="question_item_header"><?php echo __("<= 5");?></label> 
	<input type="radio" name="question10[2]" class="question10 question10C  question_radio" value="14" <?php echo $question10_checked_14;?>><label class="question_item_header"><?php echo __("6-10");?></label>   
	<input type="radio" name="question10[2]" class="question10 question10C  question_radio" value="15" <?php echo $question10_checked_15;?>><label class="question_item_header"><?php echo __("11-20");?></label>   
	<input type="radio" name="question10[2]" class="question10 question10C  question_radio" value="16" <?php echo $question10_checked_16;?>><label class="question_item_header"><?php echo __("21-50");?></label>  
	<input type="radio" name="question10[2]" class="question10 question10C  question_radio" value="17" <?php echo $question10_checked_17;?>><label class="question_item_header"><?php echo __(">50");?></label>   
</p>

<p class="question_subheader"><?php echo __("D. Mediastino ")?></p>
<?php 

	$question10_checked_18 = "";
	$question10_checked_19 = "";
	$question10_checked_20 = "";
	$question10_checked_21 = "";
	$question10_checked_22 = "";
	$question10_checked_23 = "";
	
if($Question10[3] === '18')
{
	$question10_checked_18 = "checked='checked'";
	$question10_checked_19 = "";
	$question10_checked_20 = "";
	$question10_checked_21 = "";
	$question10_checked_22 = "";
	$question10_checked_23 = "";
}
else if($Question10[3] === '19') 
{
	$question10_checked_18 = "";
	$question10_checked_19 = "checked='checked'";
	$question10_checked_20 = "";
	$question10_checked_21 = "";
	$question10_checked_22 = "";
	$question10_checked_23 = "";
}
else if($Question10[3] === '20') 
{
	$question10_checked_18 = "";
	$question10_checked_19 = "";
	$question10_checked_20 = "checked='checked'";
	$question10_checked_21 = "";
	$question10_checked_22 = "";
	$question10_checked_23 = "";
}
else if($Question10[3] === '21') 
{
	$question10_checked_18 = "";
	$question10_checked_19 = "";
	$question10_checked_20 = "";
	$question10_checked_21 = "checked='checked'";
	$question10_checked_22 = "";
	$question10_checked_23 = "";
}
else if($Question10[3] === '22') 
{
	$question10_checked_18 = "";
	$question10_checked_19 = "";
	$question10_checked_20 = "";
	$question10_checked_21 = "";
	$question10_checked_22 = "checked='checked'";
	$question10_checked_23 = "";
}
else if($Question10[3] === '23') 
{
	$question10_checked_18 = "";
	$question10_checked_19 = "";
	$question10_checked_20 = "";
	$question10_checked_21 = "";
	$question10_checked_22 = "";
	$question10_checked_23 = "checked='checked'";
}

?>
<p>
	<input type="radio" name="question10[3]" class="question10 question10D  question_radio" value="18" <?php echo $question10_checked_18;?>><label class="question_item_header"><?php echo __("nenhuma");?></label>
	<input type="radio" name="question10[3]" class="question10 question10D  question_radio" value="19" <?php echo $question10_checked_19;?>><label class="question_item_header"><?php echo __("<= 5");?></label> 
	<input type="radio" name="question10[3]" class="question10 question10D  question_radio" value="20" <?php echo $question10_checked_20;?>><label class="question_item_header" ><?php echo __("6-10");?></label>   
	<input type="radio" name="question10[3]" class="question10 question10D  question_radio" value="21" <?php echo $question10_checked_21;?>><label class="question_item_header"><?php echo __("11-20");?></label>   
	<input type="radio" name="question10[3]" class="question10 question10D  question_radio" value="22" <?php echo $question10_checked_22;?>><label class="question_item_header"><?php echo __("21-50");?></label>  
	<input type="radio" name="question10[3]" class="question10 question10D  question_radio" value="23" <?php echo $question10_checked_23;?>><label class="question_item_header"><?php echo __(">50");?></label>   
</p>

<p class="question_subheader"><?php echo __("E. Pancreato-biliar-ampular  ")?></p>
<?php 

	$question10_checked_24 = "";
	$question10_checked_25 = "";
	$question10_checked_26 = "";
	$question10_checked_27 = "";
	$question10_checked_28 = "";
	$question10_checked_29 = "";
	
if($Question10[4] === '24')
{
	$question10_checked_24 = "checked='checked'";
	$question10_checked_25 = "";
	$question10_checked_26 = "";
	$question10_checked_27 = "";
	$question10_checked_28 = "";
	$question10_checked_29 = "";
}
else if($Question10[4] === '25') 
{
	$question10_checked_24 = "";
	$question10_checked_25 = "checked='checked'";
	$question10_checked_26 = "";
	$question10_checked_27 = "";
	$question10_checked_28 = "";
	$question10_checked_29 = "";
}
else if($Question10[4] === '26') 
{
	$question10_checked_24 = "";
	$question10_checked_25 = "";
	$question10_checked_26 = "checked='checked'";
	$question10_checked_27 = "";
	$question10_checked_28 = "";
	$question10_checked_29 = "";
}
else if($Question10[4] === '27') 
{
	$question10_checked_24 = "";
	$question10_checked_25 = "";
	$question10_checked_26 = "";
	$question10_checked_27 = "checked='checked'";
	$question10_checked_28 = "";
	$question10_checked_29 = "";
}
else if($Question10[4] === '28') 
{
	$question10_checked_24 = "";
	$question10_checked_25 = "";
	$question10_checked_26 = "";
	$question10_checked_27 = "";
	$question10_checked_28 = "checked='checked'";
	$question10_checked_29 = "";
}
else if($Question10[4] === '29') 
{
	$question10_checked_24 = "";
	$question10_checked_25 = "";
	$question10_checked_26 = "";
	$question10_checked_27 = "";
	$question10_checked_28 = "";
	$question10_checked_29 = "checked='checked'";
}

?>
<p>
	<input type="radio" name="question10[4]" class="question10 question10E  question_radio" value="24" <?php echo $question10_checked_24;?>><label class="question_item_header"><?php echo __("nenhuma");?></label>
	<input type="radio" name="question10[4]" class="question10 question10E  question_radio" value="25" <?php echo $question10_checked_25;?>><label class="question_item_header"><?php echo __("<= 5");?></label> 
	<input type="radio" name="question10[4]" class="question10 question10E  question_radio" value="26" <?php echo $question10_checked_26;?>><label class="question_item_header"><?php echo __("6-10");?></label>   
	<input type="radio" name="question10[4]" class="question10 question10E  question_radio" value="27" <?php echo $question10_checked_27;?>><label class="question_item_header"><?php echo __("11-20");?></label>   
	<input type="radio" name="question10[4]" class="question10 question10E  question_radio" value="28" <?php echo $question10_checked_28;?>><label class="question_item_header"><?php echo __("21-50");?></label>  
	<input type="radio" name="question10[4]" class="question10 question10E  question_radio" value="29" <?php echo $question10_checked_29;?>><label class="question_item_header"><?php echo __(">50");?></label>   
</p>

<p class="question_subheader"><?php echo __("F. Puncao ecoguiada (FNA) - alta e baixa  ")?></p>
<?php 

	$question10_checked_30 = "";
	$question10_checked_31 = "";
	$question10_checked_32 = "";
	$question10_checked_33 = "";
	$question10_checked_34 = "";
	$question10_checked_35 = "";
	$question10_checked_36 = "";
	
if($Question10[5] === '30')
{
	$question10_checked_30 = "checked='checked'";
	$question10_checked_31 = "";
	$question10_checked_32 = "";
	$question10_checked_33 = "";
	$question10_checked_34 = "";
	$question10_checked_35 = "";
	$question10_checked_36 = "";
}
else if($Question10[5] === '31') 
{
	$question10_checked_30 = "";
	$question10_checked_31 = "checked='checked'";
	$question10_checked_32 = "";
	$question10_checked_33 = "";
	$question10_checked_34 = "";
	$question10_checked_35 = "";
	$question10_checked_36 = "";
}
else if($Question10[5] === '32') 
{
	$question10_checked_30 = "";
	$question10_checked_31 = "";
	$question10_checked_32 = "checked='checked'";
	$question10_checked_33 = "";
	$question10_checked_34 = "";
	$question10_checked_35 = "";
	$question10_checked_36 = "";
}
else if($Question10[5] === '33') 
{
	$question10_checked_30 = "";
	$question10_checked_31 = "";
	$question10_checked_32 = "";
	$question10_checked_33 = "checked='checked'";
	$question10_checked_34 = "";
	$question10_checked_35 = "";
	$question10_checked_36 = "";
}
else if($Question10[5] === '34') 
{
	$question10_checked_30 = "";
	$question10_checked_31 = "";
	$question10_checked_32 = "";
	$question10_checked_33 = "";
	$question10_checked_34 = "checked='checked'";
	$question10_checked_35 = "";
	$question10_checked_36 = "";
}
else if($Question10[5] === '35') 
{
	$question10_checked_30 = "";
	$question10_checked_31 = "";
	$question10_checked_32 = "";
	$question10_checked_33 = "";
	$question10_checked_34 = "";
	$question10_checked_35 = "checked='checked'";
	$question10_checked_36 = "";
}
else if($Question10[5] === '36') 
{
	$question10_checked_30 = "";
	$question10_checked_31 = "";
	$question10_checked_32 = "";
	$question10_checked_33 = "";
	$question10_checked_34 = "";
	$question10_checked_35 = "";
	$question10_checked_36 = "checked='checked'";
}

?>
<p>
	<input type="radio" name="question10[5]" class="question10 question10F  question_radio" value="30" <?php echo $question10_checked_30;?>><label class="question_item_header"><?php echo __("nenhuma");?></label>
	<input type="radio" name="question10[5]" class="question10 question10F  question_radio" value="31" <?php echo $question10_checked_31;?>><label class="question_item_header"><?php echo __("<= 5");?></label> 
	<input type="radio" name="question10[5]" class="question10 question10F  question_radio" value="32" <?php echo $question10_checked_32;?>><label class="question_item_header"><?php echo __("6-10");?></label>   
	<input type="radio" name="question10[5]" class="question10 question10F  question_radio" value="33" <?php echo $question10_checked_33;?>><label class="question_item_header"><?php echo __("11-20");?></label>   
	<input type="radio" name="question10[5]" class="question10 question10F  question_radio" value="34" <?php echo $question10_checked_34;?>><label class="question_item_header"><?php echo __("21-50");?></label>  
	<input type="radio" name="question10[5]" class="question10 question10F  question_radio" value="35" <?php echo $question10_checked_35;?>><label class="question_item_header"><?php echo __("51-100");?></label>
	<input type="radio" name="question10[5]" class="question10 question10F  question_radio" value="36" <?php echo $question10_checked_36;?>><label class="question_item_header"><?php echo __(">100");?></label>   
</p>

<p class="question_subheader"><?php echo __("G. Terapeutica - alta e baixa ( neurolise/bloqueio do plexo celiaco, drenagens, etc ...)")?></p>
<?php 

	$question10_checked_37 = "";
	$question10_checked_38 = "";
	$question10_checked_39 = "";
	$question10_checked_40 = "";
	$question10_checked_41 = "";
	$question10_checked_42 = "";
	$question10_checked_43 = "";
	$question10_checked_44 = "";
	
if($Question10[6] === '37')
{
	$question10_checked_37 = "checked='checked'";
	$question10_checked_38 = "";
	$question10_checked_39 = "";
	$question10_checked_40 = "";
	$question10_checked_41 = "";
	$question10_checked_42 = "";
	$question10_checked_43 = "";
	$question10_checked_44 = "";
}
else if($Question10[6] === '38') 
{
	$question10_checked_37 = "";
	$question10_checked_38 = "checked='checked'";
	$question10_checked_39 = "";
	$question10_checked_40 = "";
	$question10_checked_41 = "";
	$question10_checked_42 = "";
	$question10_checked_43 = "";
	$question10_checked_44 = "";
}
else if($Question10[6] === '39') 
{
	$question10_checked_37 = "";
	$question10_checked_38 = "";
	$question10_checked_39 = "checked='checked'";
	$question10_checked_40 = "";
	$question10_checked_41 = "";
	$question10_checked_42 = "";
	$question10_checked_43 = "";
	$question10_checked_44 = "";
}
else if($Question10[6] === '40') 
{
	$question10_checked_37 = "";
	$question10_checked_38 = "";
	$question10_checked_39 = "";
	$question10_checked_40 = "checked='checked'";
	$question10_checked_41 = "";
	$question10_checked_42 = "";
	$question10_checked_43 = "";
	$question10_checked_44 = "";
}
else if($Question10[6] === '41') 
{
	$question10_checked_37 = "";
	$question10_checked_38 = "";
	$question10_checked_39 = "";
	$question10_checked_40 = "";
	$question10_checked_41 = "checked='checked'";
	$question10_checked_42 = "";
	$question10_checked_43 = "";
	$question10_checked_44 = "";
}
else if($Question10[6] === '42') 
{
	$question10_checked_37 = "";
	$question10_checked_38 = "";
	$question10_checked_39 = "";
	$question10_checked_40 = "";
	$question10_checked_41 = "";
	$question10_checked_42 = "checked='checked'";
	$question10_checked_43 = "";
	$question10_checked_44 = "";
}
else if($Question10[6] === '43') 
{
	$question10_checked_37 = "";
	$question10_checked_38 = "";
	$question10_checked_39 = "";
	$question10_checked_40 = "";
	$question10_checked_41 = "";
	$question10_checked_42 = "";
	$question10_checked_43 = "checked='checked'";
	$question10_checked_44 = "";
}
else if($Question10[6] === '44') 
{
	$question10_checked_37 = "";
	$question10_checked_38 = "";
	$question10_checked_39 = "";
	$question10_checked_40 = "";
	$question10_checked_41 = "";
	$question10_checked_42 = "";
	$question10_checked_43 = "";
	$question10_checked_44 = "checked='checked'";
}

?>
<p>
	<input type="radio" name="question10[6]" class="question10 question10G  question_radio" value="37" <?php echo $question10_checked_37;?>><label class="question_item_header"><?php echo __("nenhuma");?></label>
	<input type="radio" name="question10[6]" class="question10 question10G  question_radio" value="38" <?php echo $question10_checked_38;?>><label class="question_item_header"><?php echo __("01");?></label> 
	<input type="radio" name="question10[6]" class="question10 question10G  question_radio" value="39" <?php echo $question10_checked_39;?>><label class="question_item_header"><?php echo __("02");?></label>   
	<input type="radio" name="question10[6]" class="question10 question10G  question_radio" value="40" <?php echo $question10_checked_40;?>><label class="question_item_header"><?php echo __("03");?></label>   
	<input type="radio" name="question10[6]" class="question10 question10G  question_radio" value="41" <?php echo $question10_checked_41;?>><label class="question_item_header"><?php echo __("04-08");?></label>  
	<input type="radio" name="question10[6]" class="question10 question10G  question_radio" value="42" <?php echo $question10_checked_42;?>><label class="question_item_header"><?php echo __("09-15");?></label>
	<input type="radio" name="question10[6]" class="question10 question10G  question_radio" value="43" <?php echo $question10_checked_43;?>><label class="question_item_header"><?php echo __("16-25");?></label>
	<input type="radio" name="question10[6]" class="question10 question10G  question_radio" value="44" <?php echo $question10_checked_44;?>><label class="question_item_header"><?php echo __(">25");?></label>   
</p>



<p class="question_header"><?php echo __("9. Ha quantos anos voce  ja realiza EUS sem a supervisao de outro endossonografista?");?></p>
<p>
	<input type="text" name="question11[0]" value="<?php echo $Question11[0];?>" class="question9 question_text">
</p>

<br>

<p class="question_header"><?php echo __("10. Quantas EUS voce  ja realizou sem a supervisao de outro endossonografista?");?></p>
<?php 

	$question12_checked_0 = "";
	$question12_checked_1 = "";
	$question12_checked_2 = "";
	$question12_checked_3 = "";
	$question12_checked_4 = "";
	$question12_checked_5 = "";
	
if($Question12[0] === '0') 
{
	$question12_checked_0 = "checked='checked'";
	$question12_checked_1 = "";
	$question12_checked_2 = "";
	$question12_checked_3 = "";
	$question12_checked_4 = "";
	$question12_checked_5 = "";
}
else if($Question12[0] === '1')
{
	$question12_checked_0 = "";
	$question12_checked_1 = "checked='checked'";
	$question12_checked_2 = "";
	$question12_checked_3 = "";
	$question12_checked_4 = "";
	$question12_checked_5 = "";
}
else if($Question12[0] === '2')
{
	$question12_checked_0 = "";
	$question12_checked_1 = "";
	$question12_checked_2 = "checked='checked'";
	$question12_checked_3 = "";
	$question12_checked_4 = "";
	$question12_checked_5 = "";
}
else if($Question12[0] === '3')
{
	$question12_checked_0 = "";
	$question12_checked_1 = "";
	$question12_checked_2 = "";
	$question12_checked_3 = "checked='checked'";
	$question12_checked_4 = "";
	$question12_checked_5 = "";
}
else if($Question12[0] === '4')
{
	$question12_checked_0 = "";
	$question12_checked_1 = "";
	$question12_checked_2 = "";
	$question12_checked_3 = "";
	$question12_checked_4 = "checked='checked'";
	$question12_checked_5 = "";
}
else if($Question12[0] === '5')
{
	$question12_checked_0 = "";
	$question12_checked_1 = "";
	$question12_checked_2 = "";
	$question12_checked_3 = "";
	$question12_checked_4 = "";
	$question12_checked_5 = "checked='checked'";
}
?>
<p>
	<input type="radio"  name="question12[0]" class="question12 question_radio" value="0" <?php echo $question12_checked_0;?>><label class="question_item_header"><?php echo __(" <= 100");?></label>
	<br>
	<input type="radio"  name="question12[0]" class="question12 question_radio" value="1" <?php echo $question12_checked_1;?>><label class="question_item_header"><?php echo __(" 101-250");?></label>
	<br>
	<input type="radio"  name="question12[0]" class="question12 question_radio" value="2" <?php echo $question12_checked_2;?>><label class="question_item_header"><?php echo __(" 251-500");?></label>
	<br>
	<input type="radio"  name="question12[0]" class="question12 question_radio" value="3" <?php echo $question12_checked_3;?>><label class="question_item_header"><?php echo __(" 501-1000");?></label>
	<br>
	<input type="radio"  name="question12[0]" class="question12 question_radio" value="4" <?php echo $question12_checked_4;?>><label class="question_item_header"><?php echo __(" 1001-5000");?></label>
	<br>
	<input type="radio"  name="question12[0]" class="question12 question_radio" value="5" <?php echo $question12_checked_5;?>><label class="question_item_header"><?php echo __(" > 5000");?></label>
	<br>
</p>

<br>

<p class="question_header"><?php echo __("11. Quantas EUS com FNA e terapeuticas voce ja realizou durante a sua carreira sem a supervisao de outro endossonografista?");?></p>
<br>
<table border=0 id="tbl_list_question13" class="tbl_question_body">
	<tr>
		<th><label class="question_item_header"><?php echo __(" FNA ");?></label></th>
		<td><input type="text" name="question13[0]" value="<?php echo $Question13[0];?>" class="question13 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __(" Neurolise/ bloqueio celiaco  ");?></label></th>
		<td><input type="text" name="question13[1]" value="<?php echo $Question13[1];?>" class="question13 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __(" Drenagem de pseudocisto ");?></label></th>
		<td><input type="text" name="question13[2]" value="<?php echo $Question13[2];?>" class="question13 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __(" Drenagem de abscessos ");?></label></th>
		<td><input type="text" name="question13[3]" value="<?php echo $Question13[3];?>" class="question13 question_text"></td>
	</tr>	
	<tr>
		<th><label class="question_item_header"><?php echo __(" Drenagem biliar ");?> </label></th>
		<td><input type="text" name="question13[4]" value="<?php echo $Question13[4];?>" class="question13 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __(" Drenagem pancreatica ");?></label></th>
		<td><input type="text" name="question13[5]" value="<?php echo $Question13[5];?>" class="question13 question_text"></td>
	</tr>
</table>

<br>

<p class="question_header"><?php echo __("12. Quantas EUS voce realizou em 2011?");?></p>
<br>
<table border=0 id="tbl_list_question13" class="tbl_question_body">
	<tr>
		<th><label class="question_item_header"><?php echo __(" A. EUS alta ");?></label></th>
		<td><input type="text" name="question14[0]" value="<?php echo $Question14[0];?>" class="question13 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __(" B. EUS baixa ");?></label></th>
		<td><input type="text" name="question14[1]" value="<?php echo $Question14[1];?>" class="question13 question_text"></td>
	</tr>
</table>

<br>


<?php 
if($action == 'edit')
{
?>
	<input type="submit" name="SavePage1" id="SavePage1" value="<?php echo __("Salvar e Sair");?>" class="bt_submit bt_save_survey">
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
