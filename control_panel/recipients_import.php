<?php include 'control_panel/admin_auth.php'; ?>
<h2><?php echo __("Importar lista de destinatários"); ?></h2>
<p><?php echo __("O sistema reconhe arquivos no formato .csv, com valores separados por ponto-e-vírgula, codificados em UTF-8, sendo 1 destinatário por linha. Em cada linha, o layout esperado é: nome;email;lang. Lang admite os seguintes valores: pt_BR, en_US ou es_ES, correspondendo ao idioma em que será enviado o e-mail. A primeira linha é o cabeçalho e será ignorada durante a importação."); ?></p><br>
<p><?php echo __("Contate a Trajettoria TI caso tenha dúvidas sobre como gerar o arquivo de destinatários no formato apropriado."); ?></p>

<br>
<form action="index.php?module=control_panel&page=recipients_import&lang=<?php echo $_REQUEST['lang'];?>" method="post" 
	enctype="multipart/form-data" name="form_import_recipients" id="form_import form_import_recipients upload">
  <table class="table_recipients table_form tabela" id="table_import_recipients">
		<tbody>
			<tr>
				<td><label>Selecione o arquivo:</label></td>
				<td>
					<input type="file" name="arquivo" value="<?php echo __("Buscar Arquivo")?>" id="arquivo" class="bt_file_upload"/>
				</td>
			</tr>
		</tbody>
 </table>
 <div id="bt_holder1">
 <input type="submit" value="<?php echo __("Enviar")?>" class="bt_save bt_submit " name="bt_save" id="bt_save">
 <input type="submit" value="<?php echo __("Cancelar")?>" class="bt_cancel bt_submit " name="bt_cancel" id="bt_cancel">
 </div>
</form>

<?php 


if(isset($_REQUEST['bt_save']))
{
	if(is_array($_FILES))
	{	
		
		
		// Uso de foreach pois como o nome do botao sera traduzido, nao da para prever o nome
		foreach($_FILES as $key => $file)
		{
			if(!empty($file['tmp_name']))
			{
				
				$ext = substr($file['name'], -4, 4);
				
				if($ext != '.csv')
				{
					$errorMessage = urlencode(__("Formato de arquivo inv&aacute;lido."));			
					
					#Chamaremos a funcao criada para direcionar as mensagens.
					errorMsg(INDEX, $module, $page, $errorMessage, $_REQUEST['lang']);
				}
				else 
				{
					$data = file_get_contents($file['tmp_name']);
				
				
					$rows = explode("\n", $data);
					
					$erro = 0;
					$ListError = null;
					
					if(is_array($rows))
					{
						foreach($rows as $id => $value)
						{
							//pula o header 
							if($id > 0)
							{
								$items = explode(';', $value);
								
								
								
								// VALIDA DADOS
								$nome = (isset($items[0]))?trim(FilterData($items[0])):null;
								$email = (isset($items[1]))?trim(FilterData($items[1])):null;
								$lang = (isset($items[2]))?substr(trim(FilterData($items[2])),0,-2):null; //está com o caracter de retorno de carro
								$chave = create_guid(); #Variavel que gera a chave para ativacao de email
								$created = NowDatetime();
								$status = NOT_SENT;
								
								$msg = '';
								
								
								
								if(validaEmail($email) == false)
								{
									
									$ListError[] =  'Linha '.($id+1).': e-mail inválido';	
									
								}
								else if($lang != 'pt_BR' && $lang != 'es_ES' && $lang != 'en_US')   
								{
									$ListError[] =  'Linha '.($id+1).': idioma inválido'; 
									
								}
								else if(!empty($nome)&&!empty($email)&&!empty($lang))
								{
									
									//Verifica se ja existe o registro
									$select = "SELECT * FROM recipients WHERE name = '".$nome."' AND email = '".$email."'";
									$Lista = $connection->GetAllResults($select);
									
									if(is_array($Lista))
									{
										
										$query = "UPDATE recipients SET lang = '".$lang."' WHERE name = '".$nome."' AND email = '".$email."'";
									}						
									else 
									{
									
										//INSERE NO BANCO
										
										$query = "INSERT INTO recipients 
										(
											name,
											email,
											lang,
											recipients.key,
											send_verification,
											created
										)
										VALUES
										(
											'".$nome."',
											'".$email."',
											'".$lang."',
											'".$chave."',
											".$status.",
											'".$created."'
										)";
						
									
									}
									
									$retval = $connection->Query($query);
									
									if(! $retval ) $erro++;
								
								} //fim da verificacao de coluna vazia
								else 
								{
										$ListError[] =  'Linha '.($id+1).': campo(s) em branco'; 
								}
							} //fim do pulo do header
							
							
						}//fim do foreach para ler as linhas
						
						if(is_array($ListError))
						{
							echo '<div class="message message_error" id="message_error">';
						
							foreach($ListError as $id => $value)
							{
								echo $value.'<br>';
							}
							
							if(count($ListError) < (count($rows)-1))
							{
								echo '<br>Os demais registros foram incluídos com sucesso.<br>';
							}
							echo '</div>';
							exit;
						}
						
						
						
						#Verificacao da query, se nao houver retorno, abortar com mensagem de erro.
						if($erro > 0 )
						{ 
							$errorMessage = urlencode(__("Alguns destinatarios n&atilde;o foram importados. Tente novamente."));			
						
							#Chamaremos a funcao criada para direcionar as mensagens.
							errorMsg(INDEX, $module, $page, $errorMessage, $_REQUEST['lang']);
						
						}
						#Caso contrario, exibir mensagem de sucesso
						else 
						{
							$successMessage = urlencode(__("Destinatarios importados com sucesso"));
								
							#Chamaremos a funcao criada para direcionar as mensagens.
							successMsg(INDEX, $module, $page, $successMessage, $_REQUEST['lang']);
						}
					
					} // fim do if se ele nao e vazio
					else
					{
						$errorMessage = urlencode(__("Arquivo invalido. Tente novamente."));			
						
						#Chamaremos a funcao criada para direcionar as mensagens.
						errorMsg(INDEX, $module, $page, $errorMessage, $_REQUEST['lang']);
					}
				}
			
			
			}// fim do if se o arquivo nao existir
			else
			{
				$errorMessage = urlencode(__("Selecione um arquivo."));			
				
				#Chamaremos a funcao criada para direcionar as mensagens.
				errorMsg(INDEX, $module, $page, $errorMessage, $_REQUEST['lang']);
			}
		}
	}
	else
	{
		$errorMessage = urlencode(__("Selecione um arquivo."));			
		
		#Chamaremos a funcao criada para direcionar as mensagens.
		errorMsg(INDEX, $module, $page, $errorMessage, $_REQUEST['lang']);
	}
			
}
else if(isset($_REQUEST['bt_cancel']))
{
	successMsg(INDEX, 'control_panel', 'recipients', '', $_REQUEST['lang']);
}

?>