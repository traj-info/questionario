<h2><?php echo __("Pesquisa sobre a pratica da Ecoendoscopia (EUS) na America Latina");?></h2>

<?php 
if(($_SESSION['server_nivel'] >= MOD_CONTROL_PANEL_NIVEL) &&  isset($_SESSION['server_usuarioId']))	
{
	$user_id = (empty($_REQUEST['user_id']))?0:$_REQUEST['user_id'];
	$action = (empty($_REQUEST['action']))?'view':$_REQUEST['action'];
?>
	<ul id="menu_survey_page">
<?php 
$ListSurveyPage = unserialize(SURVEY_PAGES);

if(is_array($ListSurveyPage))
{
	foreach($ListSurveyPage as $key => $content)
	{
		$index_survey = INDEX.'?module=control_panel&page=surveys&survey_page='.$content.'&lang='.$_REQUEST['lang'].'&action='.$action.'&user_id='.$user_id;
		echo '<li><a href="'.$index_survey.'">'. $key.'</a></li>';	
		
	}
}
?>
</ul>
	
	

<?php
}
else 
{
?>
<ul id="menu_survey_page">
<?php 
$ListSurveyPage = unserialize(SURVEY_PAGES);

if(is_array($ListSurveyPage))
{
	foreach($ListSurveyPage as $key => $content)
	{
		$index_survey = INDEX.'?module=survey&page='.$content.'&lang='.$_REQUEST['lang'];
		echo '<li><a href="'.$index_survey.'">'. $key.'</a></li>';	
		
	}
}
?>
</ul>

<?php 
}
?>







<?php 


if(($_SESSION['server_nivel'] >= MOD_CONTROL_PANEL_NIVEL) &&  isset($_SESSION['server_usuarioId']))	
{
	


	//ADD INFO USUARIO
	include_once 'control_panel/surveys_edit_user.php';


}
else if( isset($_SESSION['server_usuarioId']) && $_SESSION['server_nivel'] >= MOD_USER_NIVEL)
{
	$user_id = $_SESSION['server_usuarioId'];
	
	$query = "	SELECT * 
				FROM surveys 
				INNER JOIN surveystatus ON surveystatus.id = surveys.survey_status_id
				WHERE user_id = ".$user_id;
	
	$surveys = $connection->GetResult($query);
	
	if($surveys['editable'] == true)
	{
		$action = 'edit';
	}
	else 
	{
		$action = 'view';
	}
}






?>
