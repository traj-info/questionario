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
if(!empty($_REQUEST['SavePage2']) || !empty($_REQUEST['NextPageSurvey']))
{

		if($action == 'edit')
		{
			
			if(isset($_REQUEST['question15']))
			{
				
				
				$item = array_fill(0, 2, null);
				$el_null = 0;
				
				for($i = 0; $i<2; $i++)
				{
					$flag_erro = false;
					$item[$i] = (isset($_REQUEST['question15'][$i]))?trim(FilterData($_REQUEST['question15'][$i])):null;

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
				
				$sv_question15 = implode(';',$item);
				
				if($el_null != 0)
				{
					$msgErrorSurvey[15] = __("Preencha todos os campos da questão 13 com valores numéricos").'<BR>';
					if($el_null == 2) $sv_question15 = null;
				}
				
			}
			else 
			{
				$msgErrorSurvey[15] = __("Preencha todos os campos da questão 13 com valores numéricos").'<BR>';
				$sv_question15 = null;
			}
			
			
			
			if(isset($_REQUEST['question16']))
			{
				
				
				$item = array_fill(0, 5, null);
				$el_null = 0;
				
				for($i = 0; $i<5; $i++)
				{
					$flag_erro = false;
					$item[$i] = (isset($_REQUEST['question16'][$i]))?trim(FilterData($_REQUEST['question16'][$i])):"";

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
				
				$sv_question16 = implode(';',$item);
				
				if($el_null != 0)
				{
					$msgErrorSurvey[16] = __("Preencha todos os campos da questão 14 com valores numéricos").'<BR>';
					if($el_null == 5) $sv_question16 = null;
				}
				
			}
			else 
			{
				$msgErrorSurvey[16] = __("Preencha todos os campos da questão 14 com valores numéricos").'<BR>';
				$sv_question16 = null;
			}
			
			
			
			if(isset($_REQUEST['question17']))
			{
				$item = array_fill(0, 13, null);
				$el_null = 0;
				
				for($i = 0; $i<13; $i++)
				{
					$item[$i] = (isset($_REQUEST['question17'][$i]))?trim(FilterData($_REQUEST['question17'][$i])):null;
					
					if(is_null($item[$i]) || empty($item[$i])) $el_null++;		
					else $item[$i] = str_replace(';','/',$item[$i]);
				}
				
				$sv_question17 = implode(';',$item);
				
				if($el_null > 12)
				{
					$msgErrorSurvey[17] = __("Selecione pelo menos um item da questão 15").'<BR>';
				}
			}
			else 
			{
				$msgErrorSurvey[17] = __("Selecione pelo menos um item da questão 15").'<BR>';
				$sv_question17 = null;
			}
			
			
			if(isset($_REQUEST['question18']))
			{
				$item = array_fill(0, 13, null);
				$el_null = 0;
				
				for($i = 0; $i<13; $i++)
				{
					$item[$i] = (isset($_REQUEST['question18'][$i]))?trim(FilterData($_REQUEST['question18'][$i])):null;
					$item[$i] = (empty($item[$i]))?null:$item[$i];
					
					
					if(is_null($item[$i])) $el_null++;
					$item[$i] = str_replace(';','/',$item[$i]);		
				
				}
				
				$sv_question18 = implode(';',$item);
				
				if($el_null == 13) // todos os campos nulos
				{
					$msgErrorSurvey[18] = __("A questão 16 não pode ser deixada em branco.").'<BR>';
				}
			}
			else 
			{
				$msgErrorSurvey[18] = __("A questão 16 não pode ser deixada em branco.").'<BR>';
				$sv_question18 = null;
			}
			
			
			
			
			/*
			 * Questao 17 -> só permite 1, 5 e N como resposta
			 * Sao 5 itens
			 */
		

				
			unset($item);	
			if(!isset($_REQUEST['question19']))
			{
				$_REQUEST['question19'] = array(0=>null,1=>null,2=>null,3=>null,4=>null);
			}
			
				$item[0] = (isset($_REQUEST['question19'][0]) && (($_REQUEST['question19'][0] >= '1' && $_REQUEST['question19'][0] <= '5') || $_REQUEST['question19'][0] == 'N' || $_REQUEST['question19'][0] == 'n'))?$_REQUEST['question19'][0]:'';
				$item[1] = (isset($_REQUEST['question19'][1]) && (($_REQUEST['question19'][1] >= '1' && $_REQUEST['question19'][1] <= '5') || $_REQUEST['question19'][1] == 'N' || $_REQUEST['question19'][1] == 'n'))?$_REQUEST['question19'][1]:'';
				$item[2] = (isset($_REQUEST['question19'][2]) && (($_REQUEST['question19'][2] >= '1' && $_REQUEST['question19'][2] <= '5') || $_REQUEST['question19'][2] == 'N' || $_REQUEST['question19'][2] == 'n'))?$_REQUEST['question19'][2]:'';
				$item[3] = (isset($_REQUEST['question19'][3]) && (($_REQUEST['question19'][3] >= '1' && $_REQUEST['question19'][3] <= '5') || $_REQUEST['question19'][3] == 'N' || $_REQUEST['question19'][3] == 'n' ))?$_REQUEST['question19'][3]:'';
				$item[4] = (isset($_REQUEST['question19'][4]) && (($_REQUEST['question19'][4] >= '1' && $_REQUEST['question19'][4] <= '5') || $_REQUEST['question19'][4] == 'N' || $_REQUEST['question19'][4] == 'n'))?$_REQUEST['question19'][4]:'';
				
				
				if($item[0] == null && $item[1] == null && $item[2] == null && $item[3] == null && $item[4] == null )
				{
					$sv_question19 = null;
				}
				else 
				{
					$sv_question19 = implode(';',$item);
				}
				
				$msgErrorSurvey[19]  = null;
				
				if(!isset($item[0]))$msgErrorSurvey[19] .= __("Resposta inv&aacute;lida na questão 17, sobre Anorretal").'||';
				if(!isset($item[1]))$msgErrorSurvey[19] .= __("Resposta inv&aacute;lida na questão 17, sobre Es&ocirc;fago").'||';
				if(!isset($item[2]))$msgErrorSurvey[19] .= __("Resposta inv&aacute;lida na questão 17, sobre Gastroduodenal").'||';
				if(!isset($item[3]))$msgErrorSurvey[19] .= __("Resposta inv&aacute;lida na questão 17, sobre Mediastino").'||';
				if(!isset($item[4]))$msgErrorSurvey[19] .= __("Resposta inv&aacute;lida na questão 17, sobre Pancreato-biliar-ampular").'||';
				
			
				if(is_null($msgErrorSurvey[19])) unset($msgErrorSurvey[19]);
				
			if(!isset($_REQUEST['question20']))
			{
					$_REQUEST['question20'] = array_fill(0, 26, null);
			}	
			
			
				for($i = 0; $i <= 5; $i++)
				{
					$item[$i] = (isset($_REQUEST['question20'][$i]) && (($_REQUEST['question20'][$i] >= '1' && $_REQUEST['question20'][$i] <= '6') || $_REQUEST['question20'][$i] == 'N' || $_REQUEST['question20'][$i] == 'n' ))?$_REQUEST['question20'][$i]:'';
				}
				
				for($i = 6; $i <= 9; $i++)
				{
					$item[$i] = (isset($_REQUEST['question20'][$i]) && (($_REQUEST['question20'][$i] >= '1' && $_REQUEST['question20'][$i] <= '4') || $_REQUEST['question20'][$i] == 'N' || $_REQUEST['question20'][$i] == 'n' ))?$_REQUEST['question20'][$i]:'';
				}
				
				
				for($i = 10; $i <= 14; $i++)
				{
					$item[$i] = (isset($_REQUEST['question20'][$i]) && (($_REQUEST['question20'][$i] >= '1' && $_REQUEST['question20'][$i] <= '5') || $_REQUEST['question20'][$i] == 'N' || $_REQUEST['question20'][$i] == 'n' ))?$_REQUEST['question20'][$i]:'';
				}
				
				
				for($i = 15; $i <= 18; $i++)
				{
					$item[$i] = (isset($_REQUEST['question20'][$i]) && (($_REQUEST['question20'][$i] >= '1' && $_REQUEST['question20'][$i] <= '4') || $_REQUEST['question20'][$i] == 'N' || $_REQUEST['question20'][$i] == 'n' ))?$_REQUEST['question20'][$i]:'';
				}
				
				for($i = 19; $i <= 25; $i++)
				{
					$item[$i] = (isset($_REQUEST['question20'][$i]) && (($_REQUEST['question20'][$i] >= '1' && $_REQUEST['question20'][$i] <= '7') || $_REQUEST['question20'][$i] == 'N' || $_REQUEST['question20'][$i] == 'n' ))?$_REQUEST['question20'][$i]:'';
				}
				
				
				$cont = 0;
				
				for($i = 0; $i <= 25; $i++)
				{
					//if(is_null($item[$i])) $cont++;	
				}
				
				if($cont > 0)
				{
					$sv_question20 = null;
				}
				else
				{
					$sv_question20 = implode(';',$item);
				}
				
				$msgErrorSurvey[20]  = null;
				
				/*
				if($item[0] == null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Anorretal - sub-item sobre C&acirc;ncer retal").'||';
				if($item[1]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Anorretal - sub-item sobre Incontin&ecirc;ncia fecal e / ou f&iacute;stulas").'||';
				if($item[2]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Anorretal - sub-item sobre C&acirc;ncer  anal").'||';
				if($item[3]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Anorretal - sub-item sobre Endometriose").'||';
				if($item[4]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Anorretal - sub-item sobre Les&otilde;es subepiteliais").'||';
				if($item[5]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Anorretal - sub-item sobre Outros").'||';
				
				
				if($item[6]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Es&ocirc;fago - sub-item sobre Barrett").'||';
				if($item[7]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Es&ocirc;fago - sub-item sobre C&acirc;ncer ").'||';
				if($item[8]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Es&ocirc;fago - sub-item sobre Les&otilde;es subepiteliais").'||';
				if($item[9]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Es&ocirc;fago - sub-item sobre Outros").'||';
				
				if($item[10]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Gastroduodenal - sub-item sobre Adenocarcinoma").'||';
				if($item[11]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Gastroduodenal - sub-item sobre Linfoma").'||';
				if($item[12]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Gastroduodenal - sub-item sobre Tumor ou Linfonodos (perig&aacute;stricos ou periduodenais) ").'||';
				if($item[13]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Gastroduodenal - sub-item sobre Les&otilde;es subepiteliais").'||';				
				if($item[14]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Gastroduodenal - sub-item sobre Outros").'||';
				
				if($item[15]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Mediastino - sub-item sobre Linfonodos (exceto o estadiamento de c&acirc;ncer de pulm&atilde;o)").'||';
				if($item[16]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Mediastino - sub-item sobre Tumor mediastinal").'||';
				if($item[17]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Mediastino - sub-item sobre Estadiamento de c&acirc;ncer de pulm&atilde;o").'||';
				if($item[18]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Mediastino - sub-item sobre Outros").'||';
				
				if($item[19]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Pancreato-biliar-ampular - sub-item sobre Pancreatite aguda / cr&ocirc;nica").'||';
				if($item[20]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Pancreato-biliar-ampular - sub-item sobre Tumor/c&acirc;ncer ampular ").'||';
				if($item[21]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Pancreato-biliar-ampular - sub-item sobre Pseudocisto de p&acirc;ncreas").'||';
				if($item[22]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Pancreato-biliar-ampular - sub-item sobre Tumores cisticos do p&acirc;ncreas").'||';
				if($item[23]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Pancreato-biliar-ampular - sub-item sobre Microlit&iacute;ase ou coledocolit&iacute;ase").'||';
				if($item[24]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Pancreato-biliar-ampular - sub-item sobre Tumor/ c&acirc;ncer pancre&aacute;tico").'||';
				if($item[25]== null)$msgErrorSurvey[20] .= __("Resposta inv&aacute;lida na questão 18, sobre Pancreato-biliar-ampular - sub-item sobre Outros").'||';
			*/
				if(is_null($msgErrorSurvey[20])) unset($msgErrorSurvey[20]);
			
			
			
			
			if(isset($_REQUEST['question21'][0]))
			{
				$sv_question21 = $_REQUEST['question21'][0];
				
				
				if( $_REQUEST['question21'][0] === '0')
				{
					if(isset($_REQUEST['question22']))
					{
					
						$item = array_fill(0, 2, null);
						$el_null = 0;
						
						for($i = 0; $i<2; $i++)
						{
							$flag_preg = false;
						
							$item[$i] = (isset($_REQUEST['question22'][$i]))?trim(FilterData($_REQUEST['question22'][$i])):null;
							
							if(!preg_match("/^[0-9]+$/", $item[$i]))
							{
								$el_null++;
								$flag_preg = true;
							}
							
							if((strpos($item[$i], '0') !== 0) && ((is_null($item[$i]) || (!is_numeric($item[$i]))))) $el_null++;
							else // é numérico... mas o intervalo de valores está correto? Não precisa checar por valores <0 porque o campo já não aceita sinal
							{
								if(((int)$item[$i]) > 100) $el_null++;
							}
							$item[$i] = (int)$item[$i];
							if((empty($item[$i]) && (strpos($item[$i], '0') !== 0)) || $flag_preg)
							{
								$item[$i] = null;
							}
						
						}
						
						$sv_question22 = implode(';',$item);					
						
						if ($el_null > 0)
						{
							$msgErrorSurvey[22] = __("Preencha os campos da questão 20 com valores numéricos de 0 a 100.");
							if($el_null >= 2) $sv_question22 = null;
						}
					}
				}
				else 
				{
					$sv_question22 = null;
				}
				
				
			}
			else
			{
				$sv_question21 = null;
				$msgErrorSurvey[21] = __("Questão 19 não selecionada. A questão 20 depende da resposta dessa questão.");
			
			
				if(isset($_REQUEST['question22']))
				{
					
					$item = array_fill(0, 2, null);
					$el_null = 0;
					
					for($i = 0; $i<2; $i++)
					{
						$flag_preg = false;
					
						$item[$i] = (isset($_REQUEST['question22'][$i]))?trim(FilterData($_REQUEST['question22'][$i])):null;
						
						if(!preg_match("/^[0-9]+$/", $item[$i]))
						{
							$el_null++;
							$flag_preg = true;
						}
						
						if((strpos($item[$i], '0') !== 0) && ((is_null($item[$i]) || (!is_numeric($item[$i]))))) $el_null++;
						else // é numérico... mas o intervalo de valores está correto? Não precisa checar por valores <0 porque o campo já não aceita sinal
						{
							if(((int)$item[$i]) > 100) $el_null++;
						}
						$item[$i] = (int)$item[$i];
						if((empty($item[$i]) && (strpos($item[$i], '0') !== 0)) || $flag_preg)
						{
							$item[$i] = null;
						}
					
					}
					$sv_question22 = implode(';',$item);					
					
					if ($el_null > 0)
					{
						$msgErrorSurvey[22] = __("Preencha os campos da questão 20 com valores numéricos de 0 a 100.");
						if($el_null >= 2) $sv_question22 = null;
					}
					
				}
				else
				{
					$sv_question22 = null;
				}
			}
			
		
			
			if(isset($_REQUEST['question23']))
			{
				$item = array_fill(0,6, null);
				$el_null = 0;
				
				for($i = 0; $i<6; $i++)
				{
					$item[$i] = (isset($_REQUEST['question23'][$i]))?trim(FilterData($_REQUEST['question23'][$i])):null;
					
					
					if(is_null($item[$i])) $el_null++;
					else $item[$i] = str_replace(';','/',$item[$i]);		
				
				}
				
				$sv_question23 = implode(';',$item);
				
				if($el_null > 4)
				{
					$msgErrorSurvey[23] = __("Selecione pelo menos um item da questão 21").'<BR>';
				}
			}
			else 
			{
				$msgErrorSurvey[23] = __("Selecione pelo menos um item da questão 21").'<BR>';
				$sv_question23 = null;
			}
			
			
			if(isset($_REQUEST['question24']))
			{
				$item = array_fill(0,6, null);
				$el_null = 0;
				
				for($i = 0; $i<6; $i++)
				{
					$item[$i] = (isset($_REQUEST['question24'][$i]))?trim(FilterData($_REQUEST['question24'][$i])):null;
					
					
					if(is_null($item[$i])) $el_null++;
					else $item[$i] = str_replace(';','/',$item[$i]);		
				
				}
				
				$sv_question24 = implode(';',$item);
				
				if($el_null > 4)
				{
					$msgErrorSurvey[24] = __("Selecione pelo menos um item da questão 22").'<BR>';
				}
			}
			else 
			{
				$msgErrorSurvey[24] = __("Selecione pelo menos um item da questão 22").'<BR>';
				$sv_question24 = null;
			}
			
			
			if(isset($_REQUEST['question25']))
			{
				$item = array_fill(0,6, null);
				$el_null = 0;
				
				for($i = 0; $i<6; $i++)
				{
					$item[$i] = (isset($_REQUEST['question25'][$i]))?trim(FilterData($_REQUEST['question25'][$i])):null;
					
					
					if(is_null($item[$i])) $el_null++;
					else $item[$i] = str_replace(';','/',$item[$i]);		
				
				}
				
				$sv_question25 = implode(';',$item);
				
				if($el_null > 4)
				{
					$msgErrorSurvey[25] = __("Selecione pelo menos um item da questão 23").'<BR>';
				}
			}
			else 
			{
				$msgErrorSurvey[25] = __("Selecione pelo menos um item da questão 23").'<BR>';
				$sv_question25 = null;
			}
			
			
			
			if(isset($_REQUEST['question26'][0]))
			{
				$sv_question26 = $_REQUEST['question26'][0];
			}
			else
			{
				$sv_question26 = null;
				$msgErrorSurvey[26]= __("Selecione um item da Questão 24");
			}
			
			
			
			
			
			//salva no banco
			$query = " UPDATE surveys
						SET
						";
			
			
				
				if(!isset($msgErrorSurvey[19])) $query .= " question19 = '".trim(FilterData($sv_question19))."',";
				if(!isset($msgErrorSurvey[20])) $query .= "	question20 = '".trim(FilterData($sv_question20))."',";				

				if(!isset($msgErrorSurvey[21])) $query .= "	question21 = '".trim(FilterData($sv_question21))."',";
					
				if($sv_question22 != null) $query .= "	question22 = '".trim(FilterData($sv_question22))."',";
				else  $query .= "	question22 = null,";
			
				
				//$query .= "	question22 = '".trim(FilterData($sv_question22))."',";
					
				if(!isset($msgErrorSurvey[26])) $query .= "	question26 = '".trim(FilterData($sv_question26))."',";				
				
				
				$query .="	question15 = '".trim(FilterData($sv_question15))."',
							question16 = '".trim(FilterData($sv_question16))."',
							question17 = '".trim(FilterData($sv_question17))."',
							question18 = '".trim(FilterData($sv_question18))."',
							question23 = '".trim(FilterData($sv_question23))."',
							question24 = '".trim(FilterData($sv_question24))."',
							question25 = '".trim(FilterData($sv_question25))."',
							
							
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
				
					if(!empty($_REQUEST['SavePage2']))
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
						
					//encode questao 19 e envia via url
					if(isset($_REQUEST['question19']))
					{
						$question19_url = null;
						
						for($i = 0; $i <= 6; $i++)
						{
							$item = (isset($_REQUEST['question19'][$i]))?$_REQUEST['question19'][$i]:null;
							
							$question19_url .= '&question19['.$i.']='.$item;
						}
					}
					
					//encode questao 20 e envia via url
					if(isset($_REQUEST['question20']))
					{
						$question20_url = null;
						
						for($i = 0; $i <= 26; $i++)
						{
							$item = (isset($_REQUEST['question20'][$i]))?$_REQUEST['question20'][$i]:null;
							
							$question20_url .= '&question20['.$i.']='.$item;
						}
					}
					
					
					
					#Chamaremos a funcao criada para direcionar as mensagens.
					errorMsg(INDEX, 'survey', 'survey_page2', $errorMessage, $_REQUEST['lang'], "&user_id=".$user_id."&action=".$action."".$question19_url."".$question20_url);
				}	
				
			}
			
		}
		else 
		{
			$successMessage = "";
		}
		
		
		successMsg(INDEX,'survey' , 'survey_page3', $successMessage, $_REQUEST['lang'], "&user_id=".$user_id."&action=".$action);
		
		
		
}	
		
	




// BUSCANDO VALORES PREENCHIDOS ANTERIORMENTE PARA ESSE USUARIO

$query = "SELECT 
				question15, 
				question16,
				question17,
				question18,
				question19,
				question20,
				question21,
				question22,
				question23,
				question24,
				question25,
				question26
				 
		FROM surveys 
		WHERE user_id=".$user_id." 
		ORDER BY id LIMIT 1 " ;


$DataSurvey = $connection->GetResult($query);


if(!empty($DataSurvey['question15']))
{
	$Question15 = explode(';',$DataSurvey['question15']);
}
else
{
	$Question15 = array(0 => null, 1 => null );
}	

			
if(!empty($DataSurvey['question16']))
{
	$Question16 = explode(';',$DataSurvey['question16']);
}
else
{
	$Question16 = array(0 => null, 1 => null,2 => null, 3 => null, 4 => null );
}	


			
			
if(!empty($DataSurvey['question17']))
{
	$Question17 = explode(';',$DataSurvey['question17']);
}
else
{
	$Question17 = array(
						0 => null, 1 => null,2 => null, 3 => null, 4 => null, 5 => null, 
						6 => null, 7 => null,8 => null, 9 => null, 10 => null, 11 => null, 
						12 => null
					);
}			
			
		
if(!empty($DataSurvey['question18']))
{
	$Question18 = explode(';',$DataSurvey['question18']);
}
else
{
	$Question18 = array(
						0 => null, 1 => null,2 => null, 3 => null, 4 => null, 5 => null, 
						6 => null, 7 => null,8 => null, 9 => null, 10 => null, 11 => null, 
						12 => null
					);
}			



			
if(!empty($DataSurvey['question19']))
{
	$Question19 = explode(';',$DataSurvey['question19']);
}
else
{
	$Question19 = array_fill(0,5,null);
	
	for($i = 0; $i< 5; $i++)
	{
		$Question19[$i] = (isset($_REQUEST['question19'][$i]))?$_REQUEST['question19'][$i]:null;
	}
}			
			
			
if(!empty($DataSurvey['question20']))
{
	$Question20 = explode(';',$DataSurvey['question20']);
}
else
{
	$Question20 = array_fill(0, 26, null );
	
	for($i = 0; $i< 26; $i++)
	{
		$Question20[$i] = (isset($_REQUEST['question20'][$i]))?$_REQUEST['question20'][$i]:null;
	}
}					
			
if($DataSurvey['question21'] != null)
{
	$Question21[0] = $DataSurvey['question21'];
}
else
{
	$Question21[0] = null;
}
			
			
if(!empty($DataSurvey['question22']))
{
	$Question22 = explode(';',$DataSurvey['question22']);
}
else
{
	$Question22 = array(0 => null, 1 => null);
}			

			
if(!empty($DataSurvey['question23']))
{
	$Question23 = explode(';',$DataSurvey['question23']);
}
else
{
	$Question23 = array(0 => null, 1 => null,2 => null, 3 => null, 4 => null, 5 => null );
}			
	
			
		
if(!empty($DataSurvey['question24']))
{
	$Question24 = explode(';',$DataSurvey['question24']);
}
else
{
	$Question24 = array(0 => null, 1 => null,2 => null, 3 => null, 4 => null, 5 => null );
}			
			
if(!empty($DataSurvey['question25']))
{
	$Question25 = explode(';',$DataSurvey['question25']);
}
else
{
	$Question25 = array(0 => null, 1 => null,2 => null, 3 => null, 4 => null, 5 => null );
}			

			
if($DataSurvey['question26'] != null)
{
	$Question26[0] = $DataSurvey['question26'];
}
else
{
	$Question26[0] = null;
}


	

?>
<h3><?php echo __("Página");?> 3</h3>
<form method="post" action="index.php?module=survey&page=survey_page2&lang=<?php echo $_REQUEST['lang'];?>" name="frm_survey_page2" id="frm_survey_page2" class="frm_survey">
<br>

<br>
<p class="question_header"><?php echo __("13. Quantas FNA voce realizou em 2011?");?></p>
<br>
<table border=0 id="tbl_list_question15" class="tbl_question_body">
	<tr>
		<th><label class="question_item_header"><?php echo __(" A. Alta ");?></label></th>
		<td><input type="text" name="question15[0]" value="<?php echo $Question15[0];?>" class="question15 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __(" B. Baixa ");?></label></th>
		<td><input type="text" name="question15[1]" value="<?php echo $Question15[1];?>" class="question15 question_text"></td>
	</tr>
</table>

<p class="question_header"><?php echo __("14. Quantas complicacoes ocorreram em suas EUS apos seu periodo de treinamento ?");?></p>
<br>
<table border=0 id="tbl_list_question16" class="tbl_question_body">
	<tr>
		<th><label class="question_item_header"><?php echo __(" - sangramentos (que necessitaram de terapeutica ou internacao");?></label></th>
		<td><input type="text" name="question16[0]" value="<?php echo $Question16[0];?>" class="question16 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __(" - infeccoes ");?></label></th>
		<td><input type="text" name="question16[1]" value="<?php echo $Question16[1];?>" class="question16 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __(" - perfuracoes ");?></label></th>
		<td><input type="text" name="question16[2]" value="<?php echo $Question16[2];?>" class="question16 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __(" - complicacoes relacionadas a sedacao ");?></label></th>
		<td><input type="text" name="question16[3]" value="<?php echo $Question16[3];?>" class="question16 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __(" - outras ");?></label></th>
		<td><input type="text" name="question16[4]" value="<?php echo $Question16[4];?>" class="question16 question_text"></td>
	</tr>
</table>

<br>

<p class="question_header"><?php echo __("15. Qual(is) equipamento(s) voce usa? (assinale todas as aplicaveis)");?></p>
<?php 

$question17_checked_0 = "";
$question17_checked_1 = "";
$question17_checked_2 = "";
$question17_checked_3 = "";
$question17_checked_4 = "";
$question17_checked_5 = "";
$question17_checked_6 = "";
$question17_checked_7 = "";
$question17_checked_8 = "";
$question17_checked_9 = "";
$question17_checked_10 = "";
$question17_checked_11 = "";

if($Question17[0] === '0')	$question17_checked_0 = "checked='checked'";
if($Question17[1] === '1') 	$question17_checked_1 = "checked='checked'";
if($Question17[2] === '2')  $question17_checked_2 = "checked='checked'";
if($Question17[3] === '3')  $question17_checked_3 = "checked='checked'";
if($Question17[4] === '4')  $question17_checked_4 = "checked='checked'";
if($Question17[5] === '5')  $question17_checked_5 = "checked='checked'";
if($Question17[6] === '6')  $question17_checked_6 = "checked='checked'";
if($Question17[7] === '7')  $question17_checked_7 = "checked='checked'";
if($Question17[8] === '8')  $question17_checked_8 = "checked='checked'";
if($Question17[9] === '9')  $question17_checked_9 = "checked='checked'";
if($Question17[10] === '10')  $question17_checked_10 = "checked='checked'";
if($Question17[11] === '11')  $question17_checked_11 = "checked='checked'";


?>
<br>
<table border=1 id="tbl_list_question17a" class="tbl_question_body">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th><label class="question_item_header"><?php echo __("FUJINON");?></label></th>
			<th><label class="question_item_header"><?php echo __("OLYMPUS");?></label></th>
			<th><label class="question_item_header"><?php echo __("PENTAX");?></label></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th><label class="question_item_header"><?php echo __("RADIAL MECANICO");?></label></th>
			<td><input type="checkbox" class="cbox_item_table question17" name="question17[0]" value="0" <?php echo $question17_checked_0;?>></td>
			<td><input type="checkbox" class="cbox_item_table question17" name="question17[1]" value="1" <?php echo $question17_checked_1;?>></td>
			<td><input type="checkbox" class="cbox_item_table question17" name="question17[2]" value="2" <?php echo $question17_checked_2;?>></td>
		</tr>
		<tr>
			<th><label class="question_item_header"><?php echo __("RADIAL ELETRONICO")?></label></th>
			<td><input type="checkbox" class="cbox_item_table question17" name="question17[3]" value="3" <?php echo $question17_checked_3;?>></td>
			<td><input type="checkbox" class="cbox_item_table question17" name="question17[4]" value="4" <?php echo $question17_checked_4;?>></td>
			<td><input type="checkbox" class="cbox_item_table question17" name="question17[5]" value="5" <?php echo $question17_checked_5;?>></td>
		</tr>
		<tr>
			<th><label class="question_item_header"><?php echo __("LINEAR")?></label></th>
			<td><input type="checkbox" class="cbox_item_table question17" name="question17[6]" value="6" <?php echo $question17_checked_6;?>></td>
			<td><input type="checkbox" class="cbox_item_table question17" name="question17[7]" value="7" <?php echo $question17_checked_7;?>></td>
			<td><input type="checkbox" class="cbox_item_table question17" name="question17[8]" value="8" <?php echo $question17_checked_8;?>></td>
		</tr>
		<tr>
			<th><label class="question_item_header"><?php echo __("MINIPROBES")?></label></th>
			<td><input type="checkbox" class="cbox_item_table question17" name="question17[9]" value="9" <?php echo $question17_checked_9;?>></td>
			<td><input type="checkbox" class="cbox_item_table question17" name="question17[10]" value="10" <?php echo $question17_checked_10;?>></td>
			<td><input type="checkbox" class="cbox_item_table question17" name="question17[11]" value="11" <?php echo $question17_checked_11;?>></td>
		</tr>
	</tbody>
</table>
<br>
<table border=0 id="tbl_list_question17b" class="tbl_question_body">
	<tr>
		<th><label class="question_item_header"><?php echo __("Outros ");?></label></th>
		<td><input type="text" name="question17[12]" value="<?php echo $Question17[12];?>" class="question17 question_text"></td>
	</tr>
</table>

<br>
<div id="q18">
<p class="question_header"><?php echo __("16. Qual(is) agulha(s) voce mais utiliza? (em ordem de frequencia, assinalar todos os campos que se aplicam)");?>
<?php 
/*
$question18_checked_0 = "";
$question18_checked_1 = "";
$question18_checked_2 = "";
$question18_checked_3 = "";
$question18_checked_4 = "";
$question18_checked_5 = "";
$question18_checked_6 = "";
$question18_checked_7 = "";
$question18_checked_8 = "";
$question18_checked_9 = "";
$question18_checked_10 = "";
$question18_checked_11 = "";

if($Question18[0] === '0')	$question18_checked_0 = "checked='checked'";
if($Question18[1] === '1') 	$question18_checked_1 = "checked='checked'";
if($Question18[2] === '2')  $question18_checked_2 = "checked='checked'";
if($Question18[3] === '3')  $question18_checked_3 = "checked='checked'";
if($Question18[4] === '4')  $question18_checked_4 = "checked='checked'";
if($Question18[5] === '5')  $question18_checked_5 = "checked='checked'";
if($Question18[6] === '6')  $question18_checked_6 = "checked='checked'";
if($Question18[7] === '7')  $question18_checked_7 = "checked='checked'";
if($Question18[8] === '8')  $question18_checked_8 = "checked='checked'";
if($Question18[9] === '9')  $question18_checked_9 = "checked='checked'";
if($Question18[10] === '10')  $question18_checked_10 = "checked='checked'";
if($Question18[11] === '11')  $question18_checked_11 = "checked='checked'";

*/
?>
<br>
</p>
<script type="text/javascript">

	$(document).ready(function(){
		$('#q18 #left_container div').each(function(i){
			$(this).children('.classificacao').html((i+1) + 'º'); //update span text with initial position 
		});
	});

	$(function() {
		$( "#q18 .container" ).sortable({
			placeholder: "ui-state-highlight",
			connectWith: "#q18 .container",
			update:  function (event, ui) {
				
				// (empty) todos os values dos inputs
				$('#tbl_list_question18a input').each(function(i){
					$(this).val('');
				});
				
				$('#q18 #left_container div').each(function(i){
					
					$(this).children('.classificacao').html((i+1) + 'º'); //update span text with new position 

					// atualiza posição dos itens movidos à esquerda
					var item_id = $(this).attr('id');
					$('.question18').eq(item_id).val(i+1);
				});
				
				$('#q18 #right_container div').each(function(i){
					
					$(this).children('.classificacao').html(''); //delete span text 
				});
				
			}
			
		}).disableSelection();;
	});
	
</script>
<div class="max_container">
	<div class="instrucoes_drag" id="instrucoes_q18">
	<?php echo __('Arraste para o quadro da esquerda os itens desejados, ordenando-os conforme sua preferência (mais acima = mais frequente). Deixe no quadro da direita os itens que não se aplicam.'); ?>
	</div>
	<div class="container" id="left_container">
	<?php
	$tit_18[0] = 'BOSTON 19';
	$tit_18[1] = 'COOK 19';
	$tit_18[2] = 'MEDI-GLOBE 19';
	$tit_18[3] = 'OLYMPUS 19';
	$tit_18[4] = 'BOSTON 22';
	$tit_18[5] = 'COOK 22';
	$tit_18[6] = 'MEDI-GLOBE 22';
	$tit_18[7] = 'OLYMPUS 22';
	$tit_18[8] = 'BOSTON 25';
	$tit_18[9] = 'COOK 25';
	$tit_18[10] = 'MEDI-GLOBE 25';
	$tit_18[11] = 'OLYMPUS 25';
	
	for($i=0; $i<12; $i++)
	{
		$q18_cod[$i] = $i;
		$q18_pos[$i] = (is_null($Question18[$i]) || empty($Question18[$i])) ? 100 : (int)$Question18[$i];
	}
	
	array_multisort($q18_pos, SORT_ASC, $q18_cod);
	
	/*
	print_array($q18_cod);
	print_array($q18_pos);
	exit();
	*/
	
	foreach($q18_pos as $i => $pos)
	{
		if($pos < 100) // "not empty"
		{
			echo '<div class="sortable_item" id="' . $q18_cod[$i] . '"><span class="classificacao"></span>' . $tit_18[$q18_cod[$i]] . '</div>';
		}
	}
	
	?>
	

	</div><!-- #left_container -->
	<div class="container" id="right_container">
	<?php
	foreach($q18_pos as $i => $pos)
	{
		if($pos == 100) // "not empty"
		{
			echo '<div class="sortable_item" id="' . $q18_cod[$i] . '"><span class="classificacao"></span>' . $tit_18[$q18_cod[$i]] . '</div>';
		}
	}
	
	?>
	</div><!-- #right_container -->
</div><!-- #max_container -->
<div class="clear"></div>






<table border=1 id="tbl_list_question18a" class="tbl_question_body">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th><label class="question_item_header"><?php echo __("BOSTON");?></label></th>
			<th><label class="question_item_header"><?php echo __("COOK");?></label></th>
			<th><label class="question_item_header"><?php echo __("MEDI-GLOBE");?></label></th>
			<th><label class="question_item_header"><?php echo __("OLYMPUS");?></label></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th><label class="question_item_header"><?php echo __("19");?></label></th>
			<td><input type="text" class="txt_item_table question18" name="question18[0]" id="question18[0]" value="<?php echo $Question18[0];?>"></td>
			<td><input type="text" class="txt_item_table question18" name="question18[1]" id="question18[1]" value="<?php echo $Question18[1];?>"></td>
			<td><input type="text" class="txt_item_table question18" name="question18[2]" id="question18[2]" value="<?php echo $Question18[2];?>"></td>
			<td><input type="text" class="txt_item_table question18" name="question18[3]" id="question18[3]" value="<?php echo $Question18[3];?>"></td>
		</tr>
		<tr>
			<th><label class="question_item_header"><?php echo __("22")?></label></th>
			<td><input type="text" class="txt_item_table question18" name="question18[4]" id="question18[4]" value="<?php echo $Question18[4];?>"></td>
			<td><input type="text" class="txt_item_table question18" name="question18[5]" id="question18[5]" value="<?php echo $Question18[5];?>"></td>
			<td><input type="text" class="txt_item_table question18" name="question18[6]" id="question18[6]" value="<?php echo $Question18[6];?>"></td>
			<td><input type="text" class="txt_item_table question18" name="question18[7]" id="question18[7]" value="<?php echo $Question18[7];?>"></td>
		</tr>
		<tr>
			<th><label class="question_item_header"><?php echo __("25")?></label></th>
			<td><input type="text" class="txt_item_table question18" name="question18[8]" id="question18[8]" value="<?php echo $Question18[8];?>"></td>
			<td><input type="text" class="txt_item_table question18" name="question18[9]" id="question18[9]" value="<?php echo $Question18[9];?>"></td>
			<td><input type="text" class="txt_item_table question18" name="question18[10]" id="question18[10]" value="<?php echo $Question18[10];?>"></td>
			<td><input type="text" class="txt_item_table question18" name="question18[11]" id="question18[11]" value="<?php echo $Question18[11];?>"></td>
		</tr>
	</tbody>
</table>

<br>
<table border=0 id="tbl_list_question18b" class="tbl_question_body">
	<tr>
		<th><label class="question_item_header"><?php echo __("Outros ");?></label></th>
		<td><input type="text" name="question18[12]" id="question18[12]" value="<?php echo $Question18[12];?>" class="question18 question_text"></td>
	</tr>
</table>
</div><!-- #q18 -->
<br>

<div id="q19">
<p class="question_header"><?php echo __("17. Classifique a frequencia das indicacoes para suas EUS segundo os segmentos anatomicos (1 = mais frequente / 5= menos  frequente /  N= nunca tive)");?></p>
<script type="text/javascript">

	$(document).ready(function(){
		$('#q19 #left_container div').each(function(i){
			$(this).children('.classificacao').html((i+1) + 'º'); //update span text with initial position 
		});
	});

	$(function() {
		$( "#q19 .container" ).sortable({
			placeholder: "ui-state-highlight",
			connectWith: "#q19 .container",
			update:  function (event, ui) {
				
				// (empty) todos os values dos inputs
				$('#tbl_list_question19 input').each(function(i){
					$(this).val('');
				});
				
				$('#q19 #left_container div').each(function(i){
					
					$(this).children('.classificacao').html((i+1) + 'º'); //update span text with new position 

					// atualiza posição dos itens movidos à esquerda
					var item_id = $(this).attr('id');
					$('.question19').eq(item_id).val(i+1);
				});
				
				$('#q19 #right_container div').each(function(i){
					
					$(this).children('.classificacao').html(''); //delete span text 
				});
				
			}
		}).disableSelection();;
	});
	
</script>
<div class="max_container">
	<div class="instrucoes_drag" id="instrucoes_q18">
	<?php echo __('Arraste para o quadro da esquerda os itens desejados, ordenando-os conforme sua preferência (mais acima = mais frequente). Deixe no quadro da direita os itens que não se aplicam.'); ?>
	</div>
	<div class="container" id="left_container">
	<?php
	$tit_19[0] = __(" Anorretal ");
	$tit_19[1] = __(" Esofago ");
	$tit_19[2] = __(" Gastroduodenal ");
	$tit_19[3] = __(" Mediastino ");
	$tit_19[4] = __(" Pancreato-biliar-ampular ");
	
	for($i=0; $i<5; $i++)
	{
		$q19_cod[$i] = $i;
		$q19_pos[$i] = (is_null($Question19[$i]) || empty($Question19[$i]) || ($Question19[$i] == 'n') || ($Question19[$i] == 'N')) ? 100 : (int)$Question19[$i];
	}
	
	array_multisort($q19_pos, SORT_ASC, $q19_cod);
	
	/*
	print_array($q18_cod);
	print_array($q18_pos);
	exit();
	*/
	
	foreach($q19_pos as $i => $pos)
	{
		if($pos < 100) // "not empty"
		{
			echo '<div class="sortable_item" id="' . $q19_cod[$i] . '"><span class="classificacao"></span>' . $tit_19[$q19_cod[$i]] . '</div>';
		}
	}
	
	?>
	

	</div><!-- #left_container -->
	<div class="container" id="right_container">
	<?php
	foreach($q19_pos as $i => $pos)
	{
		if($pos == 100) // "not empty"
		{
			echo '<div class="sortable_item" id="' . $q19_cod[$i] . '"><span class="classificacao"></span>' . $tit_19[$q19_cod[$i]] . '</div>';
		}
	}
	
	?>
	</div><!-- #right_container -->
</div><!-- #max_container -->
<div class="clear"></div>


<table border=0 id="tbl_list_question19" class="tbl_question_body">
	<tr>
		<td><input type="text" name="question19[0]" id="question19[0]" value="<?php echo $Question19[0];?>" class="question19 question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Anorretal ");?></label></th>
	</tr>
	<tr>
		<td><input type="text" name="question19[1]" id="question19[1]" value="<?php echo $Question19[1];?>" class="question19 question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Esofago ");?></label></th>
	</tr>
	<tr>		
		<td><input type="text" name="question19[2]" id="question19[2]" value="<?php echo $Question19[2];?>" class="question19 question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Gastroduodenal ");?></label></th>
	</tr>
	<tr>		
		<td><input type="text" name="question19[3]" id="question19[3]" value="<?php echo $Question19[3];?>" class="question19 question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Mediastino ");?></label></th>
	</tr>
	<tr>
		<td><input type="text" name="question19[4]" id="question19[4]" value="<?php echo $Question19[4];?>" class="question19 question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Pancreato-biliar-ampular ");?></label></th>
	</tr>
</table>
</div><!-- #q19 -->
<br>

<p class="question_header"><?php echo __("18. Classifique a frequencia de indicacoes para suas EUS em cada segmento anatomico especifico:");?></p>

<div id="q20a">
<p class="question_subheader"><?php echo __("a) Anorretal (1 = mais frequente / 6 = menos frequente / N= nunca tive):");?></p>
<script type="text/javascript">

	$(document).ready(function(){
		$('#q20a #left_container div').each(function(i){
			$(this).children('.classificacao').html((i+1) + 'º'); //update span text with initial position 
		});
	});

	$(function() {
		$( "#q20a .container" ).sortable({
			placeholder: "ui-state-highlight",
			connectWith: "#q20a .container",
			update:  function (event, ui) {
				
				// (empty) todos os values dos inputs
				$('#tbl_list_question20a input').each(function(i){
					$(this).val('');
				});
				
				$('#q20a #left_container div').each(function(i){
					
					$(this).children('.classificacao').html((i+1) + 'º'); //update span text with new position 

					// atualiza posição dos itens movidos à esquerda
					var item_id = $(this).attr('id');
					$('.question20a').eq(item_id).val(i+1);
				});
				
				$('#q20a #right_container div').each(function(i){
					
					$(this).children('.classificacao').html(''); //delete span text 
				});
				
			}
		}).disableSelection();;
	});
	
</script>
<div class="max_container">
	<div class="instrucoes_drag">
	<?php echo __('Arraste para o quadro da esquerda os itens desejados, ordenando-os conforme sua preferência (mais acima = mais frequente). Deixe no quadro da direita os itens que não se aplicam.'); ?>
	</div>
	<div class="container" id="left_container">
	<?php
	$tit_20a[0] = __(" Cancer retal ");
	$tit_20a[1] = __(" Incontinencia fecal e / ou fistulas ");
	$tit_20a[2] = __(" Cancer anal ");
	$tit_20a[3] = __(" Endometriose ");
	$tit_20a[4] = __(" Lesoes subepiteliais ");
	$tit_20a[5] = __(" Outros ");
	
	for($i=0; $i<6; $i++)
	{
		$q20a_cod[$i] = $i;
		$q20a_pos[$i] = (is_null($Question20[$i]) || empty($Question20[$i]) || ($Question20[$i] == 'n') || ($Question20[$i] == 'N')) ? 100 : (int)$Question20[$i];
	}
	
	array_multisort($q20a_pos, SORT_ASC, $q20a_cod);
	

	foreach($q20a_pos as $i => $pos)
	{
		if($pos < 100) // "not empty"
		{
			echo '<div class="sortable_item" id="' . $q20a_cod[$i] . '"><span class="classificacao"></span>' . $tit_20a[$q20a_cod[$i]] . '</div>';
		}
	}
	
	?>
	

	</div><!-- #left_container -->
	<div class="container" id="right_container">
	<?php
	foreach($q20a_pos as $i => $pos)
	{
		if($pos == 100) // "not empty"
		{
			echo '<div class="sortable_item" id="' . $q20a_cod[$i] . '"><span class="classificacao"></span>' . $tit_20a[$q20a_cod[$i]] . '</div>';
		}
	}
	
	?>
	</div><!-- #right_container -->
</div><!-- #max_container -->
<div class="clear"></div>
<table border=0 id="tbl_list_question20a" class="tbl_question_body">
	<tr>
		<td><input type="text" name="question20[0]" id="question20[0]" value="<?php echo $Question20[0];?>" class="question20a question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Cancer retal ");?></label></th>
	</tr>
	<tr>
		<td><input type="text" name="question20[1]" id="question20[1]" value="<?php echo $Question20[1];?>" class="question20a question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Incontinencia fecal e / ou fistulas ");?></label></th>
	</tr>
	<tr>		
		<td><input type="text" name="question20[2]" id="question20[2]" value="<?php echo $Question20[2];?>" class="question20a question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Cancer anal ");?></label></th>
	</tr>
	<tr>		
		<td><input type="text" name="question20[3]" id="question20[3]" value="<?php echo $Question20[3];?>" class="question20a question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Endometriose ");?></label></th>
	</tr>
	<tr>
		<td><input type="text" name="question20[4]" id="question20[4]" value="<?php echo $Question20[4];?>" class="question20a question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Lesoes subepiteliais ");?></label></th>
	</tr>
	<tr>
		<td><input type="text" name="question20[5]" id="question20[5]" value="<?php echo $Question20[5];?>" class="question20a question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Outros ");?> </label></th>
	</tr>
</table>

<br>
</div><!-- #q20a -->
<div id="q20b">

<p class="question_subheader"><?php echo __("b) Esofago (1 = mais frequente / 4 = menos frequente / N= nunca tive):");?></p>

<script type="text/javascript">

	$(document).ready(function(){
		$('#q20b #left_container div').each(function(i){
			$(this).children('.classificacao').html((i+1) + 'º'); //update span text with initial position 
		});
	});

	$(function() {
		$( "#q20b .container" ).sortable({
			placeholder: "ui-state-highlight",
			connectWith: "#q20b .container",
			update:  function (event, ui) {
				
				// (empty) todos os values dos inputs
				$('#tbl_list_question20b input').each(function(i){
					$(this).val('');
				});
				
				$('#q20b #left_container div').each(function(i){
					
					$(this).children('.classificacao').html((i+1) + 'º'); //update span text with new position 

					// atualiza posição dos itens movidos à esquerda
					var item_id = $(this).attr('id');
					$('.question20b').eq(item_id - 6).val(i+1);
				});
				
				$('#q20b #right_container div').each(function(i){
					
					$(this).children('.classificacao').html(''); //delete span text 
				});
				
			}
		}).disableSelection();;
	});
	
</script>
<div class="max_container">
	<div class="instrucoes_drag">
	<?php echo __('Arraste para o quadro da esquerda os itens desejados, ordenando-os conforme sua preferência (mais acima = mais frequente). Deixe no quadro da direita os itens que não se aplicam.'); ?>
	</div>
	<div class="container" id="left_container">
	<?php
	$tit_20b[6] = __(" Barrett");
	$tit_20b[7] = __(" Cancer");
	$tit_20b[8] = __(" Lesoes subepiteliais");
	$tit_20b[9] = __(" Outros ");
	
	$j = 6;
	for($i=0; $i<4; $i++)
	{
		$q20b_cod[$i] = $j;
		$q20b_pos[$i] = (is_null($Question20[$j]) || empty($Question20[$j]) || ($Question20[$j] == 'n') || ($Question20[$j] == 'N')) ? 100 : (int)$Question20[$j];
		$j++;
	}
	
	array_multisort($q20b_pos, SORT_ASC, $q20b_cod);
	

	foreach($q20b_pos as $i => $pos)
	{
		if($pos < 100) // "not empty"
		{
			echo '<div class="sortable_item" id="' . $q20b_cod[$i] . '"><span class="classificacao"></span>' . $tit_20b[$q20b_cod[$i]] . '</div>';
		}
	}
	
	?>
	

	</div><!-- #left_container -->
	<div class="container" id="right_container">
	<?php
	foreach($q20b_pos as $i => $pos)
	{
		if($pos == 100) // "not empty"
		{
			echo '<div class="sortable_item" id="' . $q20b_cod[$i] . '"><span class="classificacao"></span>' . $tit_20b[$q20b_cod[$i]] . '</div>';
		}
	}
	
	?>
	</div><!-- #right_container -->
</div><!-- #max_container -->
<div class="clear"></div>
<table border=0 id="tbl_list_question20b" class="tbl_question_body">
	<tr>
		<td><input type="text" name="question20[6]" id="question20[6]" value="<?php echo $Question20[6];?>" class="question20b question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Barrett");?></label></th>
	</tr>
	<tr>
		<td><input type="text" name="question20[7]" id="question20[7]" value="<?php echo $Question20[7];?>" class="question20b question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Cancer");?></label></th>
	</tr>
	<tr>		
		<td><input type="text" name="question20[8]" id="question20[8]" value="<?php echo $Question20[8];?>" class="question20b question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Lesoes subepiteliais");?></label></th>
	</tr>
	<tr>
		<td><input type="text" name="question20[9]" id="question20[9]" value="<?php echo $Question20[9];?>" class="question20b question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Outros ");?> </label></th>
	</tr>
</table>
 
 </div><!-- #q20b -->
 <div id="q20c">
<br>

<p class="question_subheader"><?php echo __("c) Gastroduodenal (1 = mais frequente / 5 = menos frequente / N= nunca tive):");?></p>
<script type="text/javascript">

	$(document).ready(function(){
		$('#q20c #left_container div').each(function(i){
			$(this).children('.classificacao').html((i+1) + 'º'); //update span text with initial position 
		});
	});

	$(function() {
		$( "#q20c .container" ).sortable({
			placeholder: "ui-state-highlight",
			connectWith: "#q20c .container",
			update:  function (event, ui) {
				
				// (empty) todos os values dos inputs
				$('#tbl_list_question20c input').each(function(i){
					$(this).val('');
				});
				
				$('#q20c #left_container div').each(function(i){
					
					$(this).children('.classificacao').html((i+1) + 'º'); //update span text with new position 

					// atualiza posição dos itens movidos à esquerda
					var item_id = $(this).attr('id');
					$('.question20c').eq(item_id - 10).val(i+1);
				});
				
				$('#q20c #right_container div').each(function(i){
					
					$(this).children('.classificacao').html(''); //delete span text 
				});
				
			}
		}).disableSelection();;
	});
	
</script>
<div class="max_container">
	<div class="instrucoes_drag">
	<?php echo __('Arraste para o quadro da esquerda os itens desejados, ordenando-os conforme sua preferência (mais acima = mais frequente). Deixe no quadro da direita os itens que não se aplicam.'); ?>
	</div>
	<div class="container" id="left_container">
	<?php
	$tit_20c[10] = __(" Adenocarcinoma");
	$tit_20c[11] = __(" Linfoma");
	$tit_20c[12] = __(" Tumor ou Linfonodos (perigastricos ou periduodenais) ");
	$tit_20c[13] = __(" Lesoes subepiteliais ");
	$tit_20c[14] = __(" Outros ");
	
	$j = 10;
	for($i=0; $i<5; $i++)
	{
		$q20c_cod[$i] = $j;
		$q20c_pos[$i] = (is_null($Question20[$j]) || empty($Question20[$j]) || ($Question20[$j] == 'n') || ($Question20[$j] == 'N')) ? 100 : (int)$Question20[$j];
		$j++;
	}
	
	array_multisort($q20c_pos, SORT_ASC, $q20c_cod);
	

	foreach($q20c_pos as $i => $pos)
	{
		if($pos < 100) // "not empty"
		{
			echo '<div class="sortable_item" id="' . $q20c_cod[$i] . '"><span class="classificacao"></span>' . $tit_20c[$q20c_cod[$i]] . '</div>';
		}
	}
	
	?>
	

	</div><!-- #left_container -->
	<div class="container" id="right_container">
	<?php
	foreach($q20c_pos as $i => $pos)
	{
		if($pos == 100) // "not empty"
		{
			echo '<div class="sortable_item" id="' . $q20c_cod[$i] . '"><span class="classificacao"></span>' . $tit_20c[$q20c_cod[$i]] . '</div>';
		}
	}
	
	?>
	</div><!-- #right_container -->
</div><!-- #max_container -->
<div class="clear"></div>
<table border=0 id="tbl_list_question20c" class="tbl_question_body">
	<tr>
		<td><input type="text" name="question20[10]" id="question20[10]" value="<?php echo $Question20[10];?>" class="question20c question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Adenocarcinoma");?></label></th>
	</tr>
	<tr>
		<td><input type="text" name="question20[11]" id="question20[11]" value="<?php echo $Question20[11];?>" class="question20c question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Linfoma");?></label></th>
	</tr>
	<tr>		
		<td><input type="text" name="question20[12]" id="question20[12]" value="<?php echo $Question20[12];?>" class="question20c question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Tumor ou Linfonodos (perigastricos ou periduodenais) ");?></label></th>
	</tr>
	<tr>		
		<td><input type="text" name="question20[13]" id="question20[13]" value="<?php echo $Question20[13];?>" class="question20c question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Lesoes subepiteliais ");?></label></th>
	</tr>
	<tr>
		<td><input type="text" name="question20[14]" id="question20[14]" value="<?php echo $Question20[14];?>" class="question20c question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Outros ");?> </label></th>
	</tr>
</table>
 </div><!-- #q20c -->
 <div id="q20d">
<br>

<p class="question_subheader"><?php echo __("d) Mediastino (1 = mais frequente / 4= menos frequente / N= nunca tive):");?></p>
<script type="text/javascript">

	$(document).ready(function(){
		$('#q20d #left_container div').each(function(i){
			$(this).children('.classificacao').html((i+1) + 'º'); //update span text with initial position 
		});
	});

	$(function() {
		$( "#q20d .container" ).sortable({
			placeholder: "ui-state-highlight",
			connectWith: "#q20d .container",
			update:  function (event, ui) {
				
				// (empty) todos os values dos inputs
				$('#tbl_list_question20d input').each(function(i){
					$(this).val('');
				});
				
				$('#q20d #left_container div').each(function(i){
					
					$(this).children('.classificacao').html((i+1) + 'º'); //update span text with new position 

					// atualiza posição dos itens movidos à esquerda
					var item_id = $(this).attr('id');
					$('.question20d').eq(item_id - 15).val(i+1);
				});
				
				$('#q20d #right_container div').each(function(i){
					
					$(this).children('.classificacao').html(''); //delete span text 
				});
				
			}
		}).disableSelection();;
	});
	
</script>
<div class="max_container">
	<div class="instrucoes_drag">
	<?php echo __('Arraste para o quadro da esquerda os itens desejados, ordenando-os conforme sua preferência (mais acima = mais frequente). Deixe no quadro da direita os itens que não se aplicam.'); ?>
	</div>
	<div class="container" id="left_container">
	<?php
	$tit_20d[15] = __("Linfonodos (exceto o estadiamento de cancer de pulmao)");
	$tit_20d[16] = __(" Tumor mediastinal");
	$tit_20d[17] = __(" Estadiamento de cancer de pulmao ");
	$tit_20d[18] = __(" Outros ");
	
	$j = 15;
	for($i=0; $i<4; $i++)
	{
		$q20d_cod[$i] = $j;
		$q20d_pos[$i] = (is_null($Question20[$j]) || empty($Question20[$j]) || ($Question20[$j] == 'n') || ($Question20[$j] == 'N')) ? 100 : (int)$Question20[$j];
		$j++;
	}
	
	array_multisort($q20d_pos, SORT_ASC, $q20d_cod);
	

	foreach($q20d_pos as $i => $pos)
	{
		if($pos < 100) // "not empty"
		{
			echo '<div class="sortable_item" id="' . $q20d_cod[$i] . '"><span class="classificacao"></span>' . $tit_20d[$q20d_cod[$i]] . '</div>';
		}
	}
	
	?>
	

	</div><!-- #left_container -->
	<div class="container" id="right_container">
	<?php
	foreach($q20d_pos as $i => $pos)
	{
		if($pos == 100) // "not empty"
		{
			echo '<div class="sortable_item" id="' . $q20d_cod[$i] . '"><span class="classificacao"></span>' . $tit_20d[$q20d_cod[$i]] . '</div>';
		}
	}
	
	?>
	</div><!-- #right_container -->
</div><!-- #max_container -->
<div class="clear"></div>
<table border=0 id="tbl_list_question20d" class="tbl_question_body">
	<tr>
		<td><input type="text" name="question20[15]" id="question20[15]" value="<?php echo $Question20[15];?>" class="question20d question_text"></td>
		<th><label class="question_item_header"><?php echo __("Linfonodos (exceto o estadiamento de cancer de pulmao)");?></label></th>
	</tr>
	<tr>
		<td><input type="text" name="question20[16]" id="question20[16]" value="<?php echo $Question20[16];?>" class="question20d question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Tumor mediastinal");?></label></th>
	</tr>
	<tr>		
		<td><input type="text" name="question20[17]" id="question20[17]" value="<?php echo $Question20[17];?>" class="question20d question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Estadiamento de cancer de pulmao ");?></label></th>
	</tr>
	<tr>
		<td><input type="text" name="question20[18]" id="question20[18]" value="<?php echo $Question20[18];?>" class="question20d question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Outros ");?> </label></th>
	</tr>
</table>
</div><!-- #q20d -->
<div id="q20e">
<br>

<p class="question_subheader"><?php echo __("e) Pancreato-biliar-ampular (1 = mais frequente / 7=  menos frequente / N= nunca tive):");?></p>
<script type="text/javascript">

	$(document).ready(function(){
		$('#q20e #left_container div').each(function(i){
			$(this).children('.classificacao').html((i+1) + 'º'); //update span text with initial position 
		});
	});

	$(function() {
		$( "#q20e .container" ).sortable({
			placeholder: "ui-state-highlight",
			connectWith: "#q20e .container",
			update:  function (event, ui) {
				
				// (empty) todos os values dos inputs
				$('#tbl_list_question20e input').each(function(i){
					$(this).val('');
				});
				
				$('#q20e #left_container div').each(function(i){
					
					$(this).children('.classificacao').html((i+1) + 'º'); //update span text with new position 

					// atualiza posição dos itens movidos à esquerda
					var item_id = $(this).attr('id');
					$('.question20e').eq(item_id - 19).val(i+1);
				});
				
				$('#q20e #right_container div').each(function(i){
					
					$(this).children('.classificacao').html(''); //delete span text 
				});
				
			}
		}).disableSelection();;
	});
	
</script>
<div class="max_container">
	<div class="instrucoes_drag">
	<?php echo __('Arraste para o quadro da esquerda os itens desejados, ordenando-os conforme sua preferência (mais acima = mais frequente). Deixe no quadro da direita os itens que não se aplicam.'); ?>
	</div>
	<div class="container" id="left_container">
	<?php
	$tit_20e[19] = __("Pancreatite aguda / cronica");
	$tit_20e[20] = __(" Tumor/cancer ampular ");
	$tit_20e[21] = __(" Pseudocisto de pancreas");
	$tit_20e[22] = __(" Tumores cisticos do pancreas ");
	$tit_20e[23] = __(" Microlitiase ou coledocolitiase ");
	$tit_20e[24] = __(" Tumor/ cancer pancreatico");
	$tit_20e[25] = __(" Outros ");
	
	$j = 19;
	for($i=0; $i<7; $i++)
	{
		$q20e_cod[$i] = $j;
		$q20e_pos[$i] = (is_null($Question20[$j]) || empty($Question20[$j]) || ($Question20[$j] == 'n') || ($Question20[$j] == 'N')) ? 100 : (int)$Question20[$j];
		$j++;
	}
	
	array_multisort($q20e_pos, SORT_ASC, $q20e_cod);
	

	foreach($q20e_pos as $i => $pos)
	{
		if($pos < 100) // "not empty"
		{
			echo '<div class="sortable_item" id="' . $q20e_cod[$i] . '"><span class="classificacao"></span>' . $tit_20e[$q20e_cod[$i]] . '</div>';
		}
	}
	
	?>
	

	</div><!-- #left_container -->
	<div class="container" id="right_container">
	<?php
	foreach($q20e_pos as $i => $pos)
	{
		if($pos == 100) // "not empty"
		{
			echo '<div class="sortable_item" id="' . $q20e_cod[$i] . '"><span class="classificacao"></span>' . $tit_20e[$q20e_cod[$i]] . '</div>';
		}
	}
	
	?>
	</div><!-- #right_container -->
</div><!-- #max_container -->
<div class="clear"></div>
<table border=0 id="tbl_list_question20e" class="tbl_question_body">
	<tr>
		<td><input type="text" name="question20[19]" id="question20[19]" value="<?php echo $Question20[19];?>" class="question20e question_text"></td>
		<th><label class="question_item_header"><?php echo __("Pancreatite aguda / cronica");?></label></th>
	</tr>
	<tr>
		<td><input type="text" name="question20[20]" id="question20[20]" value="<?php echo $Question20[20];?>" class="question20e question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Tumor/cancer ampular ");?></label></th>
	</tr>
	<tr>		
		<td><input type="text" name="question20[21]" id="question20[21]" value="<?php echo $Question20[21];?>" class="question20e question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Pseudocisto de pancreas");?></label></th>
	</tr>
	<tr>
		<td><input type="text" name="question20[22]" id="question20[22]" value="<?php echo $Question20[22];?>" class="question20e question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Tumores cisticos do pancreas ");?> </label></th>
	</tr>
	<tr>
		<td><input type="text" name="question20[23]" id="question20[23]" value="<?php echo $Question20[23];?>" class="question20e question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Microlitiase ou coledocolitiase ");?></label></th>
	</tr>
	<tr>		
		<td><input type="text" name="question20[24]" id="question20[24]" value="<?php echo $Question20[24];?>" class="question20e question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Tumor/ cancer pancreatico");?></label></th>
	</tr>
	<tr>
		<td><input type="text" name="question20[25]" id="question20[25]" value="<?php echo $Question20[25];?>" class="question20e question_text"></td>
		<th><label class="question_item_header"><?php echo __(" Outros");?> </label></th>
	</tr>
</table>
</div><!-- #q20e -->
<br>

<p class="question_header"><?php echo __("19. Voce verifica os resultados (bioquimicos, culturas, citologicos e/ou histologicos) apos suas FNA?");?></p>
<?php 
$question21_checked_0 = "";
$question21_checked_1 = "";

if($Question21[0] === '0') 
{
	$question21_checked_0 = "checked='checked'";
	$question21_checked_1 = "";
}
else if($Question21[0] === '1')
{
	$question21_checked_0 = "";
	$question21_checked_1 = "checked='checked'";
}
?>
<p>
	<input type="radio" name="question21[0]" class="question21 question_radio" value="0"  <?php echo $question21_checked_0;?>><label class="question_item_header"><?php echo __(" SIM");?></label>
	<br>
	<input type="radio" name="question21[0]" class="question21 question_radio" value="1"  <?php echo $question21_checked_1;?>><label class="question_item_header"><?php echo __(" NAO - Se a sua resposta foi nao, nao responda a proxima questao");?></label>
</p>


<br>


<p  class="question_header"><?php echo __("20. Qual e o seu percentual total de positividade de diagnostico (bioquimicos, culturas, citologicos e/ou histologicos) obtido por FNA?");?>
<table border=0 id="tbl_list_question20" class="tbl_question_body">
	<tr>
		<th><label class="question_item_header"><?php echo __(" Em lesoes solidas ");?></label></th>
		<td><input type="text" name="question22[0]" value="<?php echo $Question22[0];?>" class="question22 question_text"></td>
	</tr>
	<tr>
		<th><label class="question_item_header"><?php echo __(" Em lesoes cisticas ");?></label></th>
		<td><input type="text" name="question22[1]" value="<?php echo $Question22[1];?>" class="question22 question_text"></td>
	</tr>
</table>

<br>

<p class="question_header"><?php echo __("21. Quem  e o responsavel pela sedacao do paciente  durante suas EUS?(assinale todas as aplicaveis)");?></p>

<?php 

$question23_checked_0 = "";
$question23_checked_1 = "";
$question23_checked_2 = "";
$question23_checked_3 = "";
$question23_checked_4 = "";

if($Question23[0] === '0') $question23_checked_0 = "checked='checked'";
if($Question23[1] === '1') $question23_checked_1 = "checked='checked'";
if($Question23[2] === '2') $question23_checked_2 = "checked='checked'";
if($Question23[3] === '3') $question23_checked_3 = "checked='checked'";
if($Question23[4] === '4' || !empty($Question23[5])) $question23_checked_4 = "checked='checked'";

?>
<p>
	<input type="checkbox" name="question23[0]" class="question23 question_cbox" value="0" <?php echo $question23_checked_0;?>><label class="question_item_header"><?php echo __(" Uma enfermeira com formacao especifica em anestesia");?></label>
	<br>
	<input type="checkbox" name="question23[1]" class="question23 question_cbox" value="1" <?php echo $question23_checked_1;?>><label class="question_item_header"><?php echo __(" Um anestesista");?></label>
	<br>
	<input type="checkbox" name="question23[2]" class="question23 question_cbox" value="2" <?php echo $question23_checked_2;?>><label class="question_item_header"><?php echo __(" Um medico nao-anestesista (que nao esta realizando a EUS)");?></label>
	<br>
	<input type="checkbox" name="question23[3]" class="question23 question_cbox" value="3" <?php echo $question23_checked_3;?>><label class="question_item_header"><?php echo __(" Voce mesmo");?></label>
	<br>
	<input type="checkbox" name="question23[4]" class="question23 question_cbox" value="4" <?php echo $question23_checked_4;?>><label class="question_item_header"><?php echo __(" Outros");?></label>
	<input type="text" name="question23[5]" value="<?php echo $Question23[5];?>" class="question23 question_text">
</p>

<br>

<p class="question_header"><?php echo __("22. Voce indica sedacao com propofol para suas EUS? (assinale todas as aplicaveis)");?></p>
<?php 
$question24_checked_0 = "";
$question24_checked_1 = "";
$question24_checked_2 = "";
$question24_checked_3 = "";
$question24_checked_4 = "";

if($Question24[0] === '0')	$question24_checked_0 = "checked='checked'";
if($Question24[1] === '1') $question24_checked_1 = "checked='checked'";
if($Question24[2] === '2') $question24_checked_2 = "checked='checked'";
if($Question24[3] === '3') $question24_checked_3 = "checked='checked'";
if($Question24[4] === '4' || !empty($Question24[5])) $question24_checked_4 = "checked='checked'";

?>
<p>
	<input type="checkbox" name="question24[0]" class="question24 question_cbox" value="0" <?php echo $question24_checked_0;?>><label class="question_item_header"><?php echo __(" Na maioria ou todos os meus exames ");?></label>
	<br>
	<input type="checkbox" name="question24[1]" class="question24 question_cbox" value="1" <?php echo $question24_checked_1;?>><label class="question_item_header"><?php echo __(" Somente quando o paciente apresenta condicoes de dificil sedacao com outras drogas");?></label>
	<br>
	<input type="checkbox" name="question24[2]" class="question24 question_cbox" value="2" <?php echo $question24_checked_2;?>><label class="question_item_header"><?php echo __(" Somente em procedimentos terapeuticos");?></label>
	<br>
	<input type="checkbox" name="question24[3]" class="question24 question_cbox" value="3" <?php echo $question24_checked_3;?>><label class="question_item_header"><?php echo __(" Nao utilizo propofol (se voce nao utiliza propofol, pule a proxima questao)");?></label>
	<br>
	<input type="checkbox" name="question24[4]" class="question24 question_cbox" value="4" <?php echo $question24_checked_4;?>><label class="question_item_header"><?php echo __(" Outros ");?></label>
	<input type="text" name="question24[5]" value="<?php echo $Question24[5];?>" class="question20 question_text">
</p>

<br>

<p class="question_header"><?php echo __('23. Quem e o responsavel pela sedacao "com propofol" durantes suas EUS? (assinale todas as aplicaveis)');?></p>
<?php 

$question25_checked_0 = "";
$question25_checked_1 = "";
$question25_checked_2 = "";
$question25_checked_3 = "";
$question25_checked_4 = "";

if($Question25[0] === '0') $question25_checked_0 = "checked='checked'";
if($Question25[1] === '1') $question25_checked_1 = "checked='checked'";
if($Question25[2] === '2') $question25_checked_2 = "checked='checked'";
if($Question25[3] === '3') $question25_checked_3 = "checked='checked'";
if($Question25[4] === '4' || !empty($Question25[5])) $question25_checked_4 = "checked='checked'";


?>
<p>
	<input type="checkbox" name="question25[0]" class="question5 question_cbox" value="0" <?php echo $question25_checked_0;?>><label class="question_item_header"><?php echo __(" Uma enfermeira com formacao especifica em anestesia");?></label>
	<br>
	<input type="checkbox" name="question25[1]" class="question5 question_cbox" value="1" <?php echo $question25_checked_1;?>><label class="question_item_header"><?php echo __(" Um anestesista");?></label>
	<br>
	<input type="checkbox" name="question25[2]" class="question5 question_cbox" value="2" <?php echo $question25_checked_2;?>><label class="question_item_header"><?php echo __(" Um medico nao-anestesista (que nao esta realizando a EUS)");?></label>
	<br>
	<input type="checkbox" name="question25[3]" class="question5 question_cbox" value="3" <?php echo $question25_checked_3;?>><label class="question_item_header"><?php echo __(" Voce mesmo");?></label>
	<br>
	<input type="checkbox" name="question25[4]" class="question5 question_cbox" value="4" <?php echo $question25_checked_4;?>><label class="question_item_header"><?php echo __(" Outros ");?></label>
	<input type="text" name="question25[5]" value="<?php echo $Question25[5];?>" class="question20 question_text">
</p>

<br>

<p class="question_header"><?php echo __("24. Atualmente, voce treina outros medicos em EUS?");?></p>
<?php 

$question26_checked_0 = "";
$question26_checked_1 = "";

if($Question26[0] === '0') 
{
	$question26_checked_0 = "checked='checked'";
	$question26_checked_1 = "";
}
else if($Question26[0] === '1')
{
	$question26_checked_0 = "";
	$question26_checked_1 = "checked='checked'";
}
?>
<p>
	<input type="radio" name="question26[0]" class="question26 question_radio" value="0" <?php echo $question26_checked_0;?>><label class="question_item_header"><?php echo __(" SIM");?></label>
	<br>
	<input type="radio" name="question26[0]" class="question26 question_radio" value="1" <?php echo $question26_checked_1;?>><label class="question_item_header"><?php echo __(" NAO");?></label>
</p>

<br>


<?php 
if($action == 'edit')
{
?>
	<input type="submit" name="SavePage2" id="SavePage2" value="<?php echo __("Salvar e Sair");?>" class="bt_submit bt_save_survey">
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