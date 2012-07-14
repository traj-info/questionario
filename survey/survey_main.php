<?php include 'user/user_auth.php'; ?>
<?php

if(($_SESSION['server_nivel'] >= MOD_CONTROL_PANEL_NIVEL) &&  isset($_SESSION['server_usuarioId']))	
{
	$user_id = (empty($_REQUEST['user_id']))?0:$_REQUEST['user_id'];
}
else
{
	$user_id = $_SESSION['server_usuarioId'];
}



$query = "	SELECT * 
			FROM surveys 
			INNER JOIN surveystatus ON surveystatus.id = surveys.survey_status_id
			WHERE user_id = ".$user_id;

$surveys = $connection->GetResult($query);

if(empty($surveys))
{
		
	$query = "	INSERT INTO surveys
				(
					user_id,
					survey_status_id
					
				)
				VALUES
				(
					".$user_id.",
					".NOT_STARTED."
				)";
				
	
	$surveys = $connection->Query($query);
	
	
			
}


//verifica ultima questao
$query = "SELECT 
			(	
				CASE 
					WHEN question1 IS NULL THEN '1'
					WHEN question3 IS NULL || question4 IS NULL || question5 IS NULL || question6 IS NULL || question7 IS NULL || question8 IS NULL || question9 IS NULL || question10 IS NULL || question11 IS NULL || question12 IS NULL || question13 IS NULL || question14 IS NULL THEN '2'
					WHEN question15 IS NULL || question16 IS NULL || question17 IS NULL || question18 IS NULL || question19 IS NULL || question20 IS NULL || question21 IS NULL ||  question23 IS NULL || question24 IS NULL || question25 IS NULL || question26 IS NULL THEN '3'
					WHEN question27 IS NULL || question28 IS NULL || question29 IS NULL || question30 IS NULL || question31 IS NULL || question32 IS NULL || question33 IS NULL || question34 IS NULL || question35 IS NULL || question36 IS NULL THEN '4'
					ELSE '5'
				END
			) AS page,
			survey_status_id
		FROM surveys
		WHERE user_id = ".$user_id;
			
$survey_page = $connection->GetResult($query); //$survey_page['page']

$ListPage = unserialize(SURVEY_PAGES);

if ($_SESSION['server_nivel'] >= MOD_CONTROL_PANEL_NIVEL)
{
	
		if(isset($_REQUEST['survey_page']))
		{
			$url = 'survey/'.$_REQUEST['survey_page'].".php";
		}
		else 
		{
			$url = 'survey/'.SURVEY_PAGE_INITIAL.".php";
		}
	
}
else 
{
	
	if($survey_page['survey_status_id'] == FINISHED)
	{
		$url = 'survey/'.SURVEY_MSG_FINAL.'.php';
	}
	else
	{
		$pagina = ($survey_page['page'] == '5') ? '1' : $survey_page['page'];
		$url = 'survey/'.$ListPage[$pagina].".php";
	}
}

if(file_exists($url))
{
	include $url;
}
	


?>