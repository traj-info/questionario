<?php include 'control_panel/admin_auth.php'; ?>
<?php 
if(!isset($_REQUEST['users'])){
	$errorMessage = urlencode(__('Selecione um usuário'));
	#Chamaremos a funcao criada para direcionar as mensagens.
	errorMsg($constantUrl, 'control_panel', 'users', $errorMessage, $_REQUEST['lang']);
}
?>

<?php 
	$_REQUEST['lang'] = (isset($_REQUEST['lang']))?$_REQUEST['lang']:LANG_DEFAULT;


?>
<h2><?php echo __("Envio de lembrete por e-mail")?></h2>
<br>
<form name="form_send_reminder" id="form_send_reminder" class="form form_send_reminder form_recipients" method="post" 
	action="index.php?module=control_panel&page=users_send_reminder">
	<table class="table_users table_form tabela" id="table_send_reminder">
		<thead>
			<tr>
				<th colspan="4"><label class="table_title"></label></th>
			</tr>		
		</thead>
		<tbody>
			<tr>
				<th><label class="table_item_description"><?php echo __("Nome")?></label></th>
				<th><label class="table_item_description"><?php echo __("E-mail")?></label></th>
				<th><label class="table_item_description"><?php echo __("Status do Usuário")?></label></th>
				<th><label class="table_item_description"><?php echo __("Status do Questionário")?></label></th>
			</tr>
			<?php
			 
			 	
				if(is_array($_REQUEST['users']))
				{	
					$str_list = implode(',',$_REQUEST['users']);
			
					$query = " 	
								SELECT 
									users.*, 
									userstatus.description as user_status_description,
									(CASE WHEN surveystatus.id is null THEN ".NOT_STARTED."
									ELSE surveystatus.id
									END) as survey_status_id,
									COALESCE(surveystatus.description,'".SURVEY_MSG_NOT_STARTED."') as user_survey_description
									
							  	FROM users 
							  	INNER JOIN userstatus ON userstatus.id = users.user_status_id
							  	LEFT JOIN surveys ON surveys.user_id = users.id
							  	LEFT JOIN surveystatus ON surveystatus.id = surveys.survey_status_id 
								WHERE users.id IN (".$str_list.")";
					
					$list_users = $connection->GetAllResults($query);
					
					if(isset($list_users))
					{
					
						foreach ($list_users as $id => $conteudo)
						{
							?>
							<tr>
								<td><?php echo $conteudo['name'];?><input type="hidden" name="users[]" value="<?php echo $conteudo['id'];?>"></td>
								<td><?php echo $conteudo['email'];?></td>
								<td><?php echo $conteudo['user_status_description'];?></td>
								<td><?php echo $conteudo['user_survey_description'];?></td>
							</tr>
							<?php 
							
						}
					}
				}
			 ?>
		</tbody>
	</table>
	
	<br><div id="bt_holder1">
	<p><?php echo __("Deseja realmente enviar um lembrete por e-mail para esses destinatarios?");?></p>
	<input type="submit" name='ConfirmSendReminderEmail' value='Confirmar' class="bt_confirm">
	<input type="submit" name='CancelSendReminderEmail' value='Cancelar' class="bt_cancel"></div>
</form>

<?php 
$_REQUEST['CancelSendReminderEmail'] = (isset($_REQUEST['CancelSendReminderEmail']))?$_REQUEST['CancelSendReminderEmail']:null;
$_REQUEST['ConfirmSendReminderEmail'] = (isset($_REQUEST['ConfirmSendReminderEmail']))?$_REQUEST['ConfirmSendReminderEmail']:null;

if($_REQUEST['CancelSendReminderEmail'] || $_REQUEST['ConfirmSendReminderEmail'])
{
	if($_REQUEST['ConfirmSendReminderEmail'])
	{
		
		if(isset($_REQUEST['users']))
		{
			$str_list = implode(',',$_REQUEST['users']);
			
			$query = " SELECT * FROM users WHERE id IN (".$str_list.")";
			$list_users = $connection->GetAllResults($query);
			
			if(is_array($list_users))
			{
				$error = array();
				
				foreach($list_users as $id => $conteudo)
				{
					
					$mail_to = $conteudo['email'];
					$nameQuery = $conteudo['name'];
					$toName = $nameQuery;
					
					if($conteudo['lang'] == 'es_ES')
					{
					
						#Variaveis que serao utilizadas no SendMail()
						$toMail = $mail_to;
						$subject = "Recordatorio para llenar el cuestionario de trabajo de investigación sobre la práctica de Ultrasonido Endoscópico (EUS) en América Latina";
						$body = "<p>Estimado(a) ". $nameQuery . ",</p><br/>";
						$body .= "<p><em>----- " . __('Esta é uma mensagem automática do sistema.') . " -----</em></p>";
						$body .= "<p>"."La práctica de trabajo de investigación (EUS) viene creciendo en los últimos años, pero su real status en América Latina es desconocido. De ahí que elaboramos un  cuestionario con el objetivo de obtener informaciones de todos los médicos que realizan EUS en los diferentes  países de América Latina. ".": <br/>";
						$body .= "<p>"." Por favor, visite el siguiente enlace:".'<a href="'.URL.'">'.URL.'</a> y finalice el cuestionario. Agradecemos  su valiosa contribuición. <br/>';
						$body .=  "<p>"."No le llevará más de 8 minutos de su tiempo.".'</p>';
						$body .= "<p>". "Gracias.".'</p><br>';
						
						$body .= "<hr><p><strong>" . __("Pesquisa sobre a prática da Ecoendoscopia (EUS) na América Latina") . "</strong></p><br>";
						$body .= "<p><strong>" . __("Diretoria do CLEUS 2010-2012") . "</strong>";
						$body .= "<br />" . __("Cecilia Castillo (Chile)");
						$body .= "<br />" . __("José Ricardo Ruíz Obaldía (Panamá)");
						$body .= "<br />" . __("Lucio G. B. Rossini (Brasil)");
						$body .= "<br />" . __("Wallia Wever (Venezuela)") . "</p><Br>";
						$body .= "<p><strong>" . __("Colaboradores da pesquisa") . "</strong>";
						$body .= "<br />" . __("Juliana Marques Drigo (Brasil)");
						$body .= "<br />" . __("Sheila Fillipi (Brasil)") . "</p><br>";
						$body .= "<p>" . __("Inicialmente este questionário foi enviado a todos os médicos que se tornaram amigos do CLEUS/SIED (Capítulo Latinoamericano de Ecoendoscopia - ") . '<strong><a href="http://www.cleus.org" target="_blank">www.cleus.org</a></strong>' . __(" / Sociedade Interamericana de Endoscopia Digestiva - ") . '<strong><a href="http://www.e-sied.org" target="_blank">www.e-sied.org</a></strong>' . __(").") . "</p><br>";
						$body .= "<p><strong>" . __("Solicitamos seu apoio incentivando os seus colegas que realizam EUS e ainda não são amigos do CLEUS a preencher este questionário. Divulgue aos seus colegas nosso endereço!") . "</strong></p><br>";
						$body .= "<p><strong>" . __("Se você tem alguma dúvida, por favor, não hesite em nos comunicar: ") . '<br/><a href="mailto:cleus.encuesta@gmail.com" target="_blank">cleus.encuesta@gmail.com</a></strong></p><br><br>';
						
		
					}
					else if($conteudo['lang'] == 'en_US')
					{
						
						#Variaveis que serao utilizadas no SendMail()
						$toMail = $mail_to;
						$subject = "Reminder to fill out the survey questionnaire on the EUS practice survey in Latin America ";
						$body = "<p>Dear ". $nameQuery . ",</p><br/>";
						$body .= "<p><em>----- " . __('Esta é uma mensagem automática do sistema.') . " -----</em></p>";
						$body .= "<p>"."EUS practice has been growing in the last few years; however the real status of EUS in Latin America is unknown. In view of this gap, we have designed this questionnaire with the aim of gathering information from all doctors that perform EUS in various countries in Latin America.".": <br/>";
						$body .= "<p>"."Please visit the link:".'<a href="'.URL.'">'.URL.'</a> and complete the survey. We count on your valuable contribution as you respond to this questionnaire. <br/>';
						$body .=  "<p>"."This questionnaire will take approximately 8 minutes of your time.".'</p>';
						$body .= "<p>". "Thank you.".'</p><br>';
						
						$body .= "<hr><p><strong>" . __("Pesquisa sobre a prática da Ecoendoscopia (EUS) na América Latina") . "</strong></p><br>";
						$body .= "<p><strong>" . __("Diretoria do CLEUS 2010-2012") . "</strong>";
						$body .= "<br />" . __("Cecilia Castillo (Chile)");
						$body .= "<br />" . __("José Ricardo Ruíz Obaldía (Panamá)");
						$body .= "<br />" . __("Lucio G. B. Rossini (Brasil)");
						$body .= "<br />" . __("Wallia Wever (Venezuela)") . "</p><Br>";
						$body .= "<p><strong>" . __("Colaboradores da pesquisa") . "</strong>";
						$body .= "<br />" . __("Juliana Marques Drigo (Brasil)");
						$body .= "<br />" . __("Sheila Fillipi (Brasil)") . "</p><br>";
						$body .= "<p>" . __("Inicialmente este questionário foi enviado a todos os médicos que se tornaram amigos do CLEUS/SIED (Capítulo Latinoamericano de Ecoendoscopia - ") . '<strong><a href="http://www.cleus.org" target="_blank">www.cleus.org</a></strong>' . __(" / Sociedade Interamericana de Endoscopia Digestiva - ") . '<strong><a href="http://www.e-sied.org" target="_blank">www.e-sied.org</a></strong>' . __(").") . "</p><br>";
						$body .= "<p><strong>" . __("Solicitamos seu apoio incentivando os seus colegas que realizam EUS e ainda não são amigos do CLEUS a preencher este questionário. Divulgue aos seus colegas nosso endereço!") . "</strong></p><br>";
						$body .= "<p><strong>" . __("Se você tem alguma dúvida, por favor, não hesite em nos comunicar: ") . '<br/><a href="mailto:cleus.encuesta@gmail.com" target="_blank">cleus.encuesta@gmail.com</a></strong></p><br><br>';
						
					}			
					else 
					{
						#Variaveis que serao utilizadas no SendMail()
						$toMail = $mail_to;
						$subject = "Lembrete para preenchimento do questionário de pesquisa sobre a prática da Ecoendoscopia (EUS) na América Latina";
						$body = "<p>Caro(a) ". $nameQuery . ",</p><br/>";
						$body .= "<p><em>----- " . __('Esta é uma mensagem automática do sistema.') . " -----</em></p>";
						$body .= "<p>"."A prática da ecoendoscopia (EUS) vem crescendo nos últimos anos, mas o seu real status na América Latina é desconhecido. Pensando nesta lacuna, elaboramos um questionário com o objetivo de coletar informações de todos os médicos que realizam ecoendoscopia nos diversos países da América Latina. ".": <br/>";
						$body .= "<p>"."Por favor, acesse o link:.".'<a href="'.URL.'">'.URL.'</a> e finalize o questionário. Contamos com a sua valiosa contribuição. <br/>';
						$body .=  "<p>"."Ele não tomará mais do que 8 minutos do seu tempo.".'</p>';
						$body .= "<p>". "Obrigado.".'</p><br>';
						
						$body .= "<hr><p><strong>" . __("Pesquisa sobre a prática da Ecoendoscopia (EUS) na América Latina") . "</strong></p><br>";
						$body .= "<p><strong>" . __("Diretoria do CLEUS 2010-2012") . "</strong>";
						$body .= "<br />" . __("Cecilia Castillo (Chile)");
						$body .= "<br />" . __("José Ricardo Ruíz Obaldía (Panamá)");
						$body .= "<br />" . __("Lucio G. B. Rossini (Brasil)");
						$body .= "<br />" . __("Wallia Wever (Venezuela)") . "</p><Br>";
						$body .= "<p><strong>" . __("Colaboradores da pesquisa") . "</strong>";
						$body .= "<br />" . __("Juliana Marques Drigo (Brasil)");
						$body .= "<br />" . __("Sheila Fillipi (Brasil)") . "</p><br>";
						$body .= "<p>" . __("Inicialmente este questionário foi enviado a todos os médicos que se tornaram amigos do CLEUS/SIED (Capítulo Latinoamericano de Ecoendoscopia - ") . '<strong><a href="http://www.cleus.org" target="_blank">www.cleus.org</a></strong>' . __(" / Sociedade Interamericana de Endoscopia Digestiva - ") . '<strong><a href="http://www.e-sied.org" target="_blank">www.e-sied.org</a></strong>' . __(").") . "</p><br>";
						$body .= "<p><strong>" . __("Solicitamos seu apoio incentivando os seus colegas que realizam EUS e ainda não são amigos do CLEUS a preencher este questionário. Divulgue aos seus colegas nosso endereço!") . "</strong></p><br>";
						$body .= "<p><strong>" . __("Se você tem alguma dúvida, por favor, não hesite em nos comunicar: ") . '<br/><a href="mailto:cleus.encuesta@gmail.com" target="_blank">cleus.encuesta@gmail.com</a></strong></p><br><br>';
						
					}
					
					/**  ---------------- SEND MAIL FORM ----------------
					 * 	Utilizando funcao SendMail com PHPMailer do arquivo util.php
					 */
					$sent = SendMail($toName, $toMail, $subject, $body);
					
					if (!isset($sent)) 
					{
						$insert = "INSERT INTO `accesslogs` 
									(
										description,
										date,
										id
										
									)
									VALUES
									(
										'Falha no envio de lembrete ao usuário',
										'".date("Y-m-d H:i:s")."',
										".$conteudo['id']."			
									)
						";
						
						$retval = $connection->Query($insert);
								
						$error[] = $mail_to;
					}
					else 
					{
						$insert = "INSERT INTO `accesslogs` 
									(
										description,
										date,
										id
										
									)
									VALUES
									(
										'Sucesso no envio de lembrete ao usuário',
										'".date("Y-m-d H:i:s")."',
										".$conteudo['id']."			
									)
						";
						
						$retval = $connection->Query($insert);
					}
				
				}
				
				if(count($error) > 0)
				{
					//$error[] = $mail_to;
				
					$list_email_error = implode("||",$error);
					
					
					$errorMessage = urlencode(__('Falha no envio para os e-mails:||').$list_email_error.__('.||||Tente novamente.'));
		
					#Chamaremos a funcao criada para direcionar as mensagens.
					errorMsg($constantUrl, 'control_panel', 'users', $errorMessage, $_REQUEST['lang']);
					
				
				}
				else 
				{
				
					
					//ADD LOG do usuário
					
					
					$successMessage = urlencode(__('Operacao realizada com sucesso, e-mails enviados.'));
					
					#Chamaremos a funcao criada para direcionar as mensagens.
					successMsg($constantUrl, 'control_panel', 'users', $successMessage, $_REQUEST['lang']);
				}
				
				
			}
		}
	
	}
	else
	if($_REQUEST['CancelSendReminderEmail'])
	{		
			$successMessage = urlencode(__("Envio de lembrete cancelado com sucesso."));			
			
			#Chamaremos a funcao criada para direcionar as mensagens.
			successMsg(INDEX, 'control_panel', 'users', $successMessage, $_REQUEST['lang']);
		
	}	
	
}



?>
