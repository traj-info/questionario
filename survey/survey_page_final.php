<?php include 'survey/survey_breadcrumb.php';?>
<?php 

$_REQUEST['ConfirmApproveSurvey'] = (isset($_REQUEST['ConfirmApproveSurvey']))?$_REQUEST['ConfirmApproveSurvey']:null;
$_REQUEST['CancelApproveSurvey']  = (isset($_REQUEST['CancelApproveSurvey']))?$_REQUEST['CancelApproveSurvey']:null;


if(($_SESSION['server_nivel'] >= MOD_CONTROL_PANEL_NIVEL) &&  isset($_SESSION['server_usuarioId']))	
{
	$user_id = (empty($_REQUEST['user_id']))?0:$_REQUEST['user_id'];
}
else
{
	$user_id = $_SESSION['server_usuarioId'];
}


$query = "SELECT 
			survey_status_id
		FROM surveys
		WHERE user_id = ".$user_id;
			
$surveys = $connection->GetResult($query); //$survey_page['page']



//vem de breadcrumb
if($surveys['survey_status_id'] == FINISHED)
{
	?>
	
<h3><?php echo __("Question&aacute;rio finalizado com sucesso!");?></h3>
<p>
<?php echo __("Nos apreciamos o seu tempo.");?>
<br><br> 
<?php echo __("Solicitamos seu apoio incentivando os seus colegas que realizam ecoendoscopia e ainda nao sao amigos do CLEUS a preencher este questionario. Envie este e-mail para seu colega para ele poder participar da pesquisa.");?>
<br><br>
<?php echo __("Se voce tem alguma duvida ou comentarios, por favor, nao hesite em nos comunicar: ")." <a href='mailto:cleus.encuesta@uol.com.br'>cleus.encuesta@uol.com.br</a>";?>
<br><br>
<?php echo __("Obrigado.");?>
</p>
	<?php
}
else if($_REQUEST['ConfirmApproveSurvey'] || $_REQUEST['CancelApproveSurvey'] )
{
		if(!empty($_REQUEST['ConfirmApproveSurvey']))
		{
//	salva no banco
	$query = " 	UPDATE surveys
				SET
					survey_status_id = ".FINISHED.",
					modified = '".NowDatetime()."'
				WHERE
					user_id = ".$user_id." ";
	
	$retval = $connection->Query($query);


?>

<h3><?php echo __("Question&aacute;rio finalizado com sucesso!.");?></h3>
<p>
<?php echo __("Nos apreciamos o seu tempo.");?>
<br><br> 
<?php echo __("Solicitamos seu apoio incentivando os seus colegas que realizam ecoendoscopia e ainda nao sao amigos do CLEUS a preencher este questionario. Envie este e-mail para seu colega para ele poder participar da pesquisa.");?>
<br><br>
<?php echo __("Se voce tem alguma duvida ou comentarios, por favor, nao hesite em nos comunicar: ")." <a href='mailto:cleus.encuesta@uol.com.br'>cleus.encuesta@uol.com.br</a>";?>
<br><br>
<?php echo __("Obrigado.");?>
</p>
<?php
		} 
		else
		{
?>
<p><?php echo __("O question&aacute;rio ainda pode ser alterado.");?></p>
<p><?php echo __("Verifique se as respostas est&atilde;o corretas e finalize o question&aacute;rio por favor.");?></p>
<br>
<a href="index.php?module=survey&page=survey_main&lang=<?php echo $_REQUEST['lang'];?>"><?php echo __("Preencher Question&aacute;rio");?></a>
<?php 			
		}
		
}
else
{
	
	##VERIFICAR SE TODAS AS QUESTOES OBRIGATORIAS FORAM PREENCHIDAS
	
	$query = "	SELECT count(*) as contador 
				FROM surveys 
				WHERE 
				user_id = ".$user_id." AND
				question1 is not null AND
				
				question3 is not null AND
				question4 is not null AND
				question5 is not null AND
				question6 is not null AND
				question7 is not null AND
				question8 is not null AND
				question9 is not null AND				
				question10 is not null AND
				question11 is not null AND
				question12 is not null AND
				question13 is not null AND
				question14 is not null AND
				question15 is not null AND
				question16 is not null AND
				question17 is not null AND
				question18 is not null AND				
				question19 is not null AND
				question20 is not null AND
				question21 is not null AND
				
				question23 is not null AND
				question24 is not null AND
				question25 is not null AND				
				question26 is not null AND
				question27 is not null AND
				question28 is not null 
				
				";
	
	$list = $connection->GetResult($query);
	
	if($list['contador'] > 0)
	{
	
	
	
?>
	

	<form name="form_approve_survey" id="form_approve_survey" class="form form_approve_survey form_surveys" method="post" 
		action="index.php?module=survey&page=survey_page_final&lang=<?php echo $_REQUEST['lang'];?>">
		<p><?php echo __("Deseja realmente finalizar o questin&aacute;rio?");?></p>
		<p><?php echo __("Ap&oacute;s finalizado, o question&aacute;rio n&atilde;o poder&aacute; ser alterado.");?></p>
		<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
		<input type="submit" name='ConfirmApproveSurvey' value="<?php echo __("Confirmar");?>" class="bt_confirm">
		<input type="submit" name='CancelApproveSurvey' value="<?php echo __("Cancelar");?>" class="bt_cancel">
	</form>
	
<?php 
	}
	else 
	{
		
		$errorMessage = urlencode(__("Algumas quest&otilde;es s&atilde;o de preenchimento obrigat&oacute;rio para finalizar o question&aacute;rio."));
				
		#Chamaremos a funcao criada para direcionar as mensagens.
		errorMsg(INDEX, 'survey', 'survey_main', $errorMessage, $_REQUEST['lang'], "&user_id=".$user_id."&action=".$action);
	}
	
}
?>
