<?php include 'control_panel/admin_auth.php'; ?>
<h2><?php echo __("Adicionar Destinatário"); ?></h2>
<form name="form_add_recipients" id="form_add_recipients" class="form form_add_recipients form_recipients" method="post" 
	action="index.php?module=control_panel&page=recipients_add&lang=<?php echo $_REQUEST['lang'];?>">
	<table class="table_recipients table_form tabela" id="table_add_recipients">
		<tbody>
			<tr>
				<th><label class="table_item_description"><?php echo __("Nome")?></label></th>
				<td><input type="text" name="Recipient[name]" value="" id="recipient_name" class="param name"></td>
			</tr>		
			<tr>
				<th><label class="table_item_description"><?php echo __("E-mail")?></label></th>
				<td><input type="text" name="Recipient[email]" value="" id="recipient_email" class="param email"></td>
			</tr>	
			<tr>
				<th><label class="table_item_description"><?php echo __("Idioma")?></label></th>
				<td>
				<?php 
					
					$ListLang = unserialize(LANGS);
					
					if(is_array($ListLang))
					{
						
						echo '<select name="Recipient[lang]" class="list_lang" id="lang">';
						foreach($ListLang as $key => $content)
						{
							echo '<option value="'.$key.'">'.$content.'</option>';
							
						}
						echo '</select>';
					}
				
				?>
				</td>
				
			</tr>		
		</tbody>
	</table>
	<br><div id="bt_holder1">
	<input type="submit" value="<?php echo __("Salvar")?>" class="button bt_save" name="bt_save" id="bt_save">
	<input type="submit" value="<?php echo __("Cancelar")?>" class="button bt_cancel" name="bt_cancel" id="bt_cancel"></div>
</form>
<?php 

if(isset($_POST["bt_save"]))
{
	if($_POST['Recipient'])
	{
	
		
		#Obter valores do formulario utilizando funcoes TRIM(Quebra de espaco) e FilterData(Evitar Injection)
		$nome = trim(FilterData($_POST['Recipient']['name']));
		$email = trim(FilterData($_POST['Recipient']['email']));
		$lang = trim(FilterData($_POST['Recipient']['lang']));
		$chave = create_guid(); #Variavel que gera a chave para ativacao de email
		$created = NowDatetime();
		$status = NOT_SENT;
		
		$query = "SELECT * FROM recipients WHERE email = '".$email."' ";
		$list = $connection->GetAllResults($query);
		
		if(!is_array($list))
		{
		
		
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
	
			$retval = $connection->Query($query);
				
			#Verificacao da query, se nao houver retorno, abortar com mensagem de erro.
			if(! $retval )
			{ 
				$errorMessage = urlencode(__("Destinatario nao adicionado"));			
			
				#Chamaremos a funcao criada para direcionar as mensagens.
				errorMsg(INDEX, $module, $page, $errorMessage, $_REQUEST['lang']);
			
			}
			#Caso contrario, exibir mensagem de sucesso
			else 
			{
				$successMessage = urlencode(__("Destinatario adicionado com sucesso"));
					
				#Chamaremos a funcao criada para direcionar as mensagens.
				successMsg(INDEX,'control_panel' , 'recipients', $successMessage, $_REQUEST['lang']);
			}	
		
		}
		else
		{
			$errorMessage = urlencode(__("Destinat&aacute;rio j&aacute; adicionado"));			
			
			#Chamaremos a funcao criada para direcionar as mensagens.
			errorMsg(INDEX, $module, $page, $errorMessage, $_REQUEST['lang']);
		}
	}
}
else if(isset($_POST['bt_cancel']))
{
	successMsg(INDEX,'control_panel' , 'recipients', "", $_REQUEST['lang']);
}
?>