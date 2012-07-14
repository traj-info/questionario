<?php include 'control_panel/admin_auth.php'; ?>
<h2><?php echo __("Relat&oacute;rio das Respostas do Question&aacute;rio")?></h2>

<p id="obs_report_survey"><strong>Esta é a PÁGINA 1 do relatório em formato HTML, para demonstração da funcionalidade. Demais páginas terão seu acesso liberado após excluídos os dados inseridos como teste no banco de dados.</strong></p>
<?php  

$lang = (isset($_REQUEST['lang']))?$_REQUEST['lang']:LANG_DEFAULT; 

#Lista de tipos de usuários
$query = "SELECT * FROM userstatus ORDER BY id";
$ListUserStatus = $connection->GetAllResults($query);

#Lista de tipos de status do questionário
$query = "SELECT * FROM surveystatus ORDER BY id";
$ListSurveyStatus = $connection->GetAllResults($query);


if(isset($_POST['bt_save_setup']))
{
	$_SESSION['report_user_status'] = (isset($_POST['user_status']))?$_POST['user_status']:null;
	$_SESSION['report_survey_status'] = (isset($_POST['survey_status']))?$_POST['survey_status']:null;
	
}

// E qdo o usuário não marcar nenhum?

$ReportUser = (isset($_SESSION['report_user_status']))?$_SESSION['report_user_status']:unserialize(REPORT_USER_STATUS);	
$ReportSurvey = (isset($_SESSION['report_survey_status']))?$_SESSION['report_survey_status']:unserialize(REPORT_SURVEY_STATUS);


?>
<form name="frm_config_report" id="frm_config_report" class="frm" method="post" action="index.php?module=control_panel&page=reports_survey_answers&lang=<?php echo $lang;?>">

<p id="report_setup"><?php echo __("Configurações do relatório:")?></p>
<div class="report_setup_div" id="setup_user_status">
<p class="setup_title" id="setup_title_user"><?php echo __("Status do Usuário")?></p>
<div class="report_setup_options" id="setup_user_options">
				<?php 
				if(isset($ListUserStatus))
				{
					$checked = '';
					
					foreach($ListUserStatus as $id => $content)
					{
						if(array_key_exists ($content['id'] , $ReportUser) == true)	$checked = 'checked="checked"';
						else 	$checked = '';
						
						echo '<input type="checkbox" name="user_status['.$content['id'].']" value="'.$content['id'].'" class="op_status user_status" '.$checked.'> '.utf8_encode(ucfirst(strtolower($content['description']))).'<br>';
					}	
				}				
				?>
</div><!-- #setup_user_options -->
</div><!-- #setup_user_status -->
<div class="report_setup_div" id="setup_survey_status">
<p class="setup_title" id="setup_title_survey"><?php echo __("Status do Questionário")?></p>
<div class="report_setup_options" id="setup_survey_options">
				<?php 
				if(isset($ListSurveyStatus))
				{
					foreach($ListSurveyStatus as $id => $content)
					{
						if(array_key_exists ($content['id'] , $ReportSurvey) == true)	$checked = 'checked="checked"';
						else 	$checked = '';
						echo '<input type="checkbox" name="survey_status['.$content['id'].']" value="'.$content['id'].'"  class="op_status survey_status" '.$checked.'> '.utf8_encode($content['description']).'<br>';
					}	
				}				
				?>
</div><!-- #setup_survey_options -->
</div><!-- #setup_survey_status -->
<div class="clear"></div>
<input type="submit" name="bt_save_setup" id="bt_save_setup" value="<?php echo __("Salvar configurações");?>">
</form>

<div class="clear"></div>
<div id="message_sucess" class="message message_sucess">
		<span id="msg_report"><?php echo __("Configurações selecionadas:")?></span><br/>
		<?php 
		if(isset($ReportUser))
		{
			echo __("Status do <strong>usu&aacute;rio</strong>: ");
			echo "<ul class='setup_list' id='setup_list_user'>";
			foreach($ReportUser as $id => $value)
			{
				if(isset($ListUserStatus[($id-1)]))
				{
					echo '<li>' . utf8_encode(ucfirst(strtolower($ListUserStatus[($id-1)]['description']))) . '</li>';
				}
			}
			echo "</ul>";
		}
		if(isset($ReportSurvey))
		{
			echo __("Status do <strong>questionário</strong>: ");
			echo "<ul class='setup_list' id='setup_list_survey'>";
			foreach($ReportSurvey as $id => $value)
			{
				if(isset($ListSurveyStatus[($id-1)]))
				{
					echo "<li>" . utf8_encode(ucfirst(strtolower($ListSurveyStatus[($id-1)]['description'])))."</li>";
				}
			}			
			echo "</ul>";
		}
		?>
</div>
<?php 

$user_list = array_keys($ReportUser);
$where_user_list = implode(',', $user_list);
$survey_list = array_keys($ReportSurvey);
$where_survey_list = implode(',', $survey_list);

$query  = "
		SELECT 
				COUNT(*) as total 
		FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";


$where = " WHERE users.credential_id = ".USER."	";

if(isset($where_user_list))	$where .= ' AND users.user_status_id IN ('.$where_user_list.')';

if(isset($where_survey_list)) 
{
	$where .= ' AND (surveys.survey_status_id IN ('.$where_survey_list.') ';

	if(array_key_exists(NOT_STARTED,$ReportSurvey) == true)
	{
		$where .= 'OR surveys.survey_status_id is null';	
	}	
	
	$where .= ')';
}


$query = $query." ".$where;

$total_usuarios = $connection->GetResult($query);




?>

<p id="report_total_pessoas"><?php echo __("Total de pessoas avaliadas: ") ; ?><span id="num_pessoas"><?php echo $total_usuarios['total'];?></span></p>
<?php 
//**************************************************************************************
//QUESTAO 1


$query  = "
		SELECT 
				COUNT(*) as total_masc 
		FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";

$query = $query." ".$where. " AND question3 = 0";

$list_masc = $connection->GetResult($query);




$query  = "SELECT 
				COUNT(*) as total_fem 
			FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND question3 = 1";
$list_fem = $connection->GetResult($query);


$total_sexo = $list_masc['total_masc'] + $list_fem['total_fem'];


$porcento_masc = ($total_sexo > 0)?number_format((($list_masc['total_masc']/$total_sexo)*100),1,",",""):0;
$porcento_fem = ($total_sexo > 0)?number_format((($list_fem['total_fem']/$total_sexo)*100),1,",",""):0;



?>
<div id="toggle_controllers">
<a id="link_toggle">[ Expandir tudo ]</a>
</div>
<div id="survey_report">

	<div class="questao" id="q1">
		<div class="enunciado exp_heading"><?php echo __("1. Sexo:");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		<div class="clear"></div>
		
		<div class="rp1_left">

			<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Valores Absolutos</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
			<tr>
				<th>Masculino</th>
				<td><?php echo $list_masc['total_masc']; ?></td>
				<td><?php echo $porcento_masc;?>%</td>
			</tr>
			<tr>
				<th>Feminino</th>
				<td><?php echo $list_fem['total_fem']; ?></td>
				<td><?php echo $porcento_fem;?>%</td>
			</tr>
			<tr>
				<th>Totais</th>
				<td><?php echo $total_sexo; ?></td>
				<td><?php echo ($total_sexo >0)?100:0;?>%</td>
			</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		<div class="rp1_right">
		<?php 
		if($total_sexo > 0)
		{
		?>
		
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_masc, $porcento_fem))));
			$leg = urlencode(htmlentities(serialize(array('Masc', 'Fem'))));

			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />

		
		<?php 
		}
		?>
		</div><!-- #rp1_right -->
		
		<div class="clear"></div>
		<?php 
		if($total_sexo <= 0)
		{
		?>
		<div id="message_sucess" class="message message_sucess">
		<p><?php echo __("Os valores não permitem a criação de um gráfico")?></p>
		</div>
		<?php 
		}
		?>
		</div><!-- #exp_content -->
	</div><!-- # questao -->
	
	
	
<?php 
//**************************************************************************************
//QUESTAO 2

$query  = "SELECT 
			COUNT(*) as total 
		  FROM users
		  LEFT JOIN surveys ON surveys.user_id = users.id";

$query = $query." ".$where. " AND question4 = 0";
$list_a = $connection->GetResult($query);



$query  = "SELECT 
				COUNT(*) as total 
			FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND question4 = 1";
$list_b = $connection->GetResult($query);



$query  = " SELECT COUNT(*) as total 
			FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND question4 = 2";			
$list_c = $connection->GetResult($query);


$query  = " SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id	";
$query = $query." ".$where. " AND question4 = 3";
$list_d = $connection->GetResult($query);

$query  = " SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND question4 = 4";
$list_e = $connection->GetResult($query);

	
$total_idade = $list_a['total'] + $list_b['total'] + $list_c['total'] + $list_d['total'] + $list_e['total'];

$porcento_a = ($total_idade > 0)?number_format((($list_a['total']/$total_idade)*100),1,",",""):0;
$porcento_b = ($total_idade > 0)?number_format((($list_b['total']/$total_idade)*100),1,",",""):0;
$porcento_c = ($total_idade > 0)?number_format((($list_c['total']/$total_idade)*100),1,",",""):0;
$porcento_d = ($total_idade > 0)?number_format((($list_d['total']/$total_idade)*100),1,",",""):0;
$porcento_e = ($total_idade > 0)?number_format((($list_e['total']/$total_idade)*100),1,",",""):0;
?>


	<div class="questao" id="q2">
	<div class="enunciado exp_heading"><?php echo __("2. Idade:");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		<div class="rp1_left">

			<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Valores Absolutos</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><=32</th>
					<td><?php echo $list_a['total']; ?></td>
					<td><?php echo $porcento_a;?>%</td>
				</tr>
				<tr>
					<th>33-42</th>
					<td><?php echo $list_b['total']; ?></td>
					<td><?php echo $porcento_b;?>%</td>
				</tr>
				<tr>
					<th>43-52</th>
					<td><?php echo $list_c['total']; ?></td>
					<td><?php echo $porcento_c;?>%</td>
				</tr>
				<tr>
					<th>53-62</th>
					<td><?php echo $list_d['total']; ?></td>
					<td><?php echo $porcento_d;?>%</td>
				</tr>
				<tr>
					<th>>62</th>
					<td><?php echo $list_e['total']; ?></td>
					<td><?php echo $porcento_e;?>%</td>
				</tr>
				<tr>
					<th>Totais</th>
					<td><?php echo $total_idade; ?></td>
					<td><?php echo ($total_idade > 0)?100:0;?>%</td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		<div class="rp1_right">
		<?php 
		if($total_idade > 0)
		{
		?>
		
		<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_a, $porcento_b, $porcento_c, $porcento_d, $porcento_e))));
			$leg = urlencode(htmlentities(serialize(array('<=32', '33-42', '43-52', '53-62','>62'))));
		?>

		<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />

		
		<?php 
		}
		
		?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		<?php 
		if($total_idade <= 0)
		{
		?>
		<div id="message_sucess" class="message message_sucess">
		<p><?php echo __("Os valores não permitem a criação de um gráfico")?></p>
		</div>
		<?php 
		}
		?></div>
	</div><!-- # questao -->
		
	

<?php 
//**************************************************************************************
//QUESTAO 3

$query  = " SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question5 LIKE '%0%'";
$list_a = $connection->GetResult($query);

$query  = " SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question5 LIKE '%1%'";
$list_b = $connection->GetResult($query);



$total = $list_a['total'] + $list_b['total'];

$porcento_a = ($total > 0)?number_format((($list_a['total']/$total)*100),1,",",""):0;
$porcento_b = ($total > 0)?number_format((($list_b['total']/$total)*100),1,",",""):0;

?>


	<div class="questao" id="q3">
		<div class="enunciado exp_heading"><?php echo __("3. Formacao medica (assinale todas as aplicaveis):");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		<div class="rp1_left">

			<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Valores Absolutos</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><?php echo __(" Gastroenterologia");?></th>
					<td><?php echo $list_a['total']; ?></td>
					<td><?php echo $porcento_a;?>%</td>
				</tr>
				<tr>
					<th><?php echo __(" Cirurgia");?></th>
					<td><?php echo $list_b['total']; ?></td>
					<td><?php echo $porcento_b;?>%</td>
				</tr>
				<tr>
					<th>Totais</th>
					<td><?php echo $total; ?></td>
					<td><?php echo ($total>0)?100:0;?>%</td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php 
		if($total > 0)
		{
		?>
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_a, $porcento_b))));
			$leg = urlencode(htmlentities(serialize(array('Gastroenterologia', 'Cirurgia'))));

			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />
		<?php 
		}
		?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		<?php 
		if($total <= 0)
		{
		?>
		<div id="message_sucess" class="message message_sucess">
		<p><?php echo __("Os valores não permitem a criação de um gráfico")?></p>
		</div>
		<?php 
		}
		?>
		</div>
	</div><!-- # questao -->	

	
<?php 
//**************************************************************************************
//QUESTAO 4

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question6 = 0";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question6 = 1";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question6 = 2";
$list_c = $connection->GetResult($query);

$total = $list_a['total'] + $list_b['total'] + $list_c['total'];

$porcento_a = ($total > 0)?number_format((($list_a['total']/$total)*100),1,",",""):0;
$porcento_b = ($total > 0)?number_format((($list_b['total']/$total)*100),1,",",""):0;
$porcento_c = ($total > 0)?number_format((($list_c['total']/$total)*100),1,",",""):0;

?>


	<div class="questao" id="q4">
		<div class="enunciado exp_heading"><?php echo __("4. Voce realiza ou foi treinado em CPRE?");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		<div class="rp1_left">

			<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Valores Absolutos</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>a. <?php echo __(" Sim, eu realizo.");?></th>
					<td><?php echo $list_a['total']; ?></td>
					<td><?php echo ($porcento_a);?>%</td>
				</tr>
				<tr>
					<th>b. <?php echo __(" Nao.");?></th>
					<td><?php echo $list_b['total']; ?></td>
					<td><?php echo ($porcento_b);?>%</td>
				</tr>
				<tr>
					<th>c. <?php echo __(" Eu fui treinado, mas nao realizo mais.");?></th>
					<td><?php echo $list_c['total']; ?></td>
					<td><?php echo $porcento_c;?>%</td>
				</tr>
				<tr>
					<th>Totais</th>
					<td><?php echo $total; ?></td>
					<td><?php echo ($total>0)?100:0?>%</td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_a,$porcento_b, $porcento_c))));
			$leg = urlencode(htmlentities(serialize(array('a', 'b', 'c'))));

			
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		<?php 
		if($total <= 0)
		{
		?>
		<div id="message_sucess" class="message message_sucess">
		<p><?php echo __("Os valores não permitem a criação de um gráfico")?></p>
		</div>
		<?php 
		}
		?></div>
	</div><!-- # questao -->	
	


	
<?php 
//**************************************************************************************
//QUESTAO 5



$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question7 LIKE '%0%'";
$list_a1 = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question7 LIKE '%1%'";
$list_a2 = $connection->GetResult($query);


$total_a = $list_a1['total'] + $list_a2['total'];


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question7 LIKE '%2%'";
$list_b1 = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question7 LIKE '%3%'";
$list_b2 = $connection->GetResult($query);


$total_b = $list_b1['total'] + $list_b2['total'];



$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question7 LIKE '%4%'";
$list_c1 = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question7 LIKE '%5%'";
$list_c2 = $connection->GetResult($query);

$total_c = $list_c1['total'] + $list_c2['total'];


$total_colA = ($list_a1['total'] + $list_b1['total'] + $list_c1['total']);
$total_colB = ($list_a2['total'] + $list_b2['total'] + $list_c2['total']);


$porcento_a1 = ($total_colA > 0)?number_format((($list_a1['total']/$total_colA)*100),1,",",""):0;
$porcento_b1 = ($total_colA > 0)?number_format((($list_b1['total']/$total_colA)*100),1,",",""):0;
$porcento_c1 = ($total_colA > 0)?number_format((($list_c1['total']/$total_colA)*100),1,",",""):0;

$array_dataA = array($porcento_a1, $porcento_b1, $porcento_c1);
$array_legA = array('a', 'b', 'c');
$titleA = "Hospital";

$porcento_a2 = ($total_colB > 0)?number_format((($list_a2['total']/$total_colB)*100),1,",",""):0;
$porcento_b2 = ($total_colB > 0)?number_format((($list_b2['total']/$total_colB)*100),1,",",""):0;
$porcento_c2 = ($total_colB > 0)?number_format((($list_c2['total']/$total_colB)*100),1,",",""):0;


$array_dataB = array($porcento_a2,$porcento_b2,$porcento_c2);
$array_legB = array('a', 'b', 'c');
$titleB = "Ambiente não hospitalar";

?>

	<div class="questao" id="q5">
		<div class="enunciado exp_heading"><?php echo __("5. Como voce caracterizaria a sua pratica atual em EUS? (assinale todas as aplicaveis):");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		<div class="rp1_left">

			<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Hospital</th>
					<th>Ambiente não hospitalar</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>a. Médico funcionário de uma instituição do governo</th>
					<td><?php echo $list_a1['total']; ?> (<?php echo $porcento_a1;?>%)</td>
					<td><?php echo $list_a2['total']; ?> (<?php echo $porcento_a2;?>%)</td>
				</tr>
				<tr>
					<th>b. Médico funcionário de uma instituição privada</th>
					<td><?php echo $list_b1['total']; ?> (<?php echo $porcento_b1;?>%)</td>
					<td><?php echo $list_b2['total']; ?> (<?php echo $porcento_b2;?>%)</td>
				</tr>
				<tr>
					<th>c. Prática independente</th>
					<td><?php echo $list_c1['total']; ?> (<?php echo $porcento_c1;?>%)</td>
					<td><?php echo $list_c2['total']; ?> (<?php echo $porcento_c2;?>%)</td>
				</tr>	
				<tr>
					<th>Totais</th>
					<td><?php echo $total_colA; ?> (<?php echo ($total_colA>0)?100:0;?>%)</td>
					<td><?php echo $total_colB; ?> (<?php echo ($total_colB>0)?100:0;?>%)</td>
				</tr>					
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
	
			<?php 
			$dataA = urlencode(htmlentities(serialize($array_dataA)));
			$legA = urlencode(htmlentities(serialize($array_legA)));
			$titleA = urlencode(htmlentities(serialize($titleA)));

			$dataB = urlencode(htmlentities(serialize($array_dataB)));
			$legB = urlencode(htmlentities(serialize($array_legB)));
			$titleB = urlencode(htmlentities(serialize($titleB)));
			
			
			
			?>
<?php if($total_colA > 0) {?>
	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$dataA&leg=$legA&title=$titleA"?>" />
<?php } ?>
<?php if($total_colB > 0) {?>
	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$dataB&leg=$legB&title=$titleB"?>" />
<?php } ?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		<?php 
		if($total_colA <= 0 || $total_colB <= 0)
		{
		?>
		<div id="message_sucess" class="message message_sucess">
		<p><?php echo __("Alguns valores não permitiram a criação de um gráfico")?></p>
		</div>
		<?php 
		}
		?>
		</div>
	</div><!-- # questao -->	

<?php 
//**************************************************************************************
//QUESTAO 6

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question8 LIKE '%0%'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question8 LIKE '%1%'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question8 LIKE '%2%'";
$list_c = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question8 LIKE '%3%'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question8 LIKE '%4%'";
$list_e = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question8 LIKE '%5%'";
$list_f = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question8 LIKE '%6%'";
$list_g = $connection->GetResult($query);




$total = $list_a['total'] + $list_b['total'] + $list_c['total'] + $list_d['total'] + $list_e['total'] + $list_f['total'] + $list_g['total'];

$porcento_a = ($total > 0)?number_format((($list_a['total']/$total)*100),1,",",""):0;
$porcento_b = ($total > 0)?number_format((($list_b['total']/$total)*100),1,",",""):0;
$porcento_c = ($total > 0)?number_format((($list_c['total']/$total)*100),1,",",""):0;
$porcento_d = ($total > 0)?number_format((($list_d['total']/$total)*100),1,",",""):0;
$porcento_e = ($total > 0)?number_format((($list_e['total']/$total)*100),1,",",""):0;
$porcento_f = ($total > 0)?number_format((($list_f['total']/$total)*100),1,",",""):0;
$porcento_g = ($total > 0)?number_format((($list_g['total']/$total)*100),1,",",""):0;

?>

	<div class="questao" id="q6">
		<div class="enunciado exp_heading"><?php echo __("6. Como voce foi treinado em EUS? (assinale todas as aplicaveis):");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		<div class="rp1_left">

			<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Valores Absolutos</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>a. <?php echo __(" Autodidata");?></th>
					<td><?php echo $list_a['total']; ?></td>
					<td><?php echo ($porcento_a);?>%</td>
				</tr>
				<tr>
					<th>b. <?php echo __(" Observando pessoalmente endossonografistas experientes");?></th>
					<td><?php echo $list_b['total']; ?></td>
					<td><?php echo ($porcento_b);?>%</td>
				</tr>
				<tr>
					<th>c. <?php echo __(" Observando endossonografistas em cursos e congressos");?></th>
					<td><?php echo $list_c['total']; ?></td>
					<td><?php echo $porcento_c;?>%</td>
				</tr>
				<tr>
					<th>d. <?php echo __(" Durante a minha formacao em gastroenterologia ou cirurgia ou endoscopia gastrointestinal");?></th>
					<td><?php echo $list_d['total']; ?></td>
					<td><?php echo $porcento_d;?>%</td>
				</tr>	
				<tr>
					<th>e. <?php echo __(" Estagio formal hands-on em EUS (<3 meses) apos a minha formacao em gastroenterologia ou cirurgia ou endoscopia gastrointestinal ");?></th>
					<td><?php echo $list_e['total']; ?></td>
					<td><?php echo $porcento_e;?>%</td>
				</tr>	
				<tr>
					<th>f. <?php echo __(" Estagio formal hands-on em EUS (3-6 meses) apos a minha formacao em gastroenterologia ou cirurgia ou endoscopia gastrointestinal ");?></th>
					<td><?php echo $list_f['total']; ?></td>
					<td><?php echo $porcento_f;?>%</td>
				</tr>	
				<tr>
					<th>g. <?php echo __(" Estagio formal hands-on em EUS (>6 meses) apos a minha formacao em gastroenterologia ou cirurgia ou endoscopia gastrointestinal  ");?></th>
					<td><?php echo $list_g['total']; ?></td>
					<td><?php echo $porcento_g;?>%</td>
				</tr>	
							
				<tr>
					<th>Totais</th>
					<td><?php echo $total; ?></td>
					<td><?php echo ($total>0)?100:0?>%</td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_a,$porcento_b, $porcento_c, $porcento_d, $porcento_e, $porcento_f, $porcento_g ))));
			$leg = urlencode(htmlentities(serialize(array('a', 'b', 'c','d','e','f','g'))));

			
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		<?php 
		if($total <= 0)
		{
		?>
		<div id="message_sucess" class="message message_sucess">
		<p><?php echo __("Os valores não permitem a criação de um gráfico")?></p>
		</div>
		<?php 
		}
		?></div>
	</div><!-- # questao -->	
	
	
<?php 
//**************************************************************************************
//QUESTAO 7
?>


	<div class="questao" id="q7">
		<div class="enunciado exp_heading"><?php echo __("7. Onde voce foi treinado em EUS?");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		<div class="rp1_left">
		Em campos do tipo texto aberto, a tabulação automática não é possível.
			
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		</div>
	</div>
<?php 
//**************************************************************************************
//QUESTAO 8A

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question10 LIKE '0;%'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10 LIKE '1;%'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10 LIKE '2;%'";
$list_c = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '3;%'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '4;%'";
$list_e = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '5;%'";
$list_f = $connection->GetResult($query);


$total = $list_a['total'] + $list_b['total'] + $list_c['total'] + $list_d['total'] + $list_e['total'] + $list_f['total'];

$porcento_a = ($total > 0)?number_format((($list_a['total']/$total)*100),1,",",""):0;
$porcento_b = ($total > 0)?number_format((($list_b['total']/$total)*100),1,",",""):0;
$porcento_c = ($total > 0)?number_format((($list_c['total']/$total)*100),1,",",""):0;
$porcento_d = ($total > 0)?number_format((($list_d['total']/$total)*100),1,",",""):0;
$porcento_e = ($total > 0)?number_format((($list_e['total']/$total)*100),1,",",""):0;
$porcento_f = ($total > 0)?number_format((($list_f['total']/$total)*100),1,",",""):0;

?>


	<div class="questao" id="q8a">
		<div class="enunciado exp_heading"><?php echo __('8. Aproximadamente quantas EUS "hands-on" voce realizou sob a supervisao de outro endossonografista durante o seu treinamento?');?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		<div class="enunciado"><?php echo __("A. Anorretal")?></div>
		<div class="rp1_left">

			<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Valores Absolutos</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><?php echo __("nenhuma");?></th>
					<td><?php echo $list_a['total']; ?></td>
					<td><?php echo ($porcento_a);?>%</td>
				</tr>
				<tr>
					<th><?php echo __("<= 5");?></th>
					<td><?php echo $list_b['total']; ?></td>
					<td><?php echo ($porcento_b);?>%</td>
				</tr>
				<tr>
					<th><?php echo __("6-10");?></th>
					<td><?php echo $list_c['total']; ?></td>
					<td><?php echo $porcento_c;?>%</td>
				</tr>
				<tr>
					<th><?php echo __("11-20");?></th>
					<td><?php echo $list_d['total']; ?></td>
					<td><?php echo $porcento_d;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __("21-50");?></th>
					<td><?php echo $list_e['total']; ?></td>
					<td><?php echo $porcento_e;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __(">50");?></th>
					<td><?php echo $list_f['total']; ?></td>
					<td><?php echo $porcento_f;?>%</td>
				</tr>	
						
				<tr>
					<th>Totais</th>
					<td><?php echo $total; ?></td>
					<td><?php echo ($total>0)?100:0?>%</td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_a,$porcento_b, $porcento_c, $porcento_d, $porcento_e, $porcento_f))));
			$leg = urlencode(htmlentities(serialize(array(__("nenhuma"), __("<= 5"), __("6-10"),__("11-20"),__("21-50"),__(">50")))));

			
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		<?php 
		if($total <= 0)
		{
		?>
		<div id="message_sucess" class="message message_sucess">
		<p><?php echo __("Os valores não permitem a criação de um gráfico")?></p>
		</div>
		<?php 
		}
		?>
<?php 	
//**************************************************************************************
//QUESTAO 8B

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question10 LIKE '%;6;%'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10 LIKE '%;7;%'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10 LIKE '%;8;%'";
$list_c = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;9;%'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;10;%'";
$list_e = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;11;%'";
$list_f = $connection->GetResult($query);


$total = $list_a['total'] + $list_b['total'] + $list_c['total'] + $list_d['total'] + $list_e['total'] + $list_f['total'];

$porcento_a = ($total > 0)?number_format((($list_a['total']/$total)*100),1,",",""):0;
$porcento_b = ($total > 0)?number_format((($list_b['total']/$total)*100),1,",",""):0;
$porcento_c = ($total > 0)?number_format((($list_c['total']/$total)*100),1,",",""):0;
$porcento_d = ($total > 0)?number_format((($list_d['total']/$total)*100),1,",",""):0;
$porcento_e = ($total > 0)?number_format((($list_e['total']/$total)*100),1,",",""):0;
$porcento_f = ($total > 0)?number_format((($list_f['total']/$total)*100),1,",",""):0;

?>

		<div class="clear"></div>
		<div class="enunciado"><?php echo __("B. Esofago")?></div>
		<div class="rp1_left">

			<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Valores Absolutos</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><?php echo __("nenhuma");?></th>
					<td><?php echo $list_a['total']; ?></td>
					<td><?php echo ($porcento_a);?>%</td>
				</tr>
				<tr>
					<th><?php echo __("<= 5");?></th>
					<td><?php echo $list_b['total']; ?></td>
					<td><?php echo ($porcento_b);?>%</td>
				</tr>
				<tr>
					<th><?php echo __("6-10");?></th>
					<td><?php echo $list_c['total']; ?></td>
					<td><?php echo $porcento_c;?>%</td>
				</tr>
				<tr>
					<th><?php echo __("11-20");?></th>
					<td><?php echo $list_d['total']; ?></td>
					<td><?php echo $porcento_d;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __("21-50");?></th>
					<td><?php echo $list_e['total']; ?></td>
					<td><?php echo $porcento_e;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __(">50");?></th>
					<td><?php echo $list_f['total']; ?></td>
					<td><?php echo $porcento_f;?>%</td>
				</tr>	
							
				<tr>
					<th>Totais</th>
					<td><?php echo $total; ?></td>
					<td><?php echo ($total>0)?100:0?>%</td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_a,$porcento_b, $porcento_c, $porcento_d, $porcento_e, $porcento_f))));
			$leg = urlencode(htmlentities(serialize(array(__("nenhuma"), __("<= 5"), __("6-10"),__("11-20"),__("21-50"),__(">50")))));

			
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		<?php 
		if($total <= 0)
		{
		?>
		<div id="message_sucess" class="message message_sucess">
		<p><?php echo __("Os valores não permitem a criação de um gráfico")?></p>
		</div>
		<?php 
		}
		?>
<?php 	
//**************************************************************************************
//QUESTAO 8c

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question10 LIKE '%;12;%'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10 LIKE '%;13;%'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10 LIKE '%;14;%'";
$list_c = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;15;%'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;16;%'";
$list_e = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;17;%'";
$list_f = $connection->GetResult($query);


$total = $list_a['total'] + $list_b['total'] + $list_c['total'] + $list_d['total'] + $list_e['total'] + $list_f['total'];

$porcento_a = ($total > 0)?number_format((($list_a['total']/$total)*100),1,",",""):0;
$porcento_b = ($total > 0)?number_format((($list_b['total']/$total)*100),1,",",""):0;
$porcento_c = ($total > 0)?number_format((($list_c['total']/$total)*100),1,",",""):0;
$porcento_d = ($total > 0)?number_format((($list_d['total']/$total)*100),1,",",""):0;
$porcento_e = ($total > 0)?number_format((($list_e['total']/$total)*100),1,",",""):0;
$porcento_f = ($total > 0)?number_format((($list_f['total']/$total)*100),1,",",""):0;

?>

		<div class="clear"></div>
		<div class="enunciado"><?php echo __("C. Gastroduodenal ")?></div>
		<div class="rp1_left">

			<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Valores Absolutos</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><?php echo __("nenhuma");?></th>
					<td><?php echo $list_a['total']; ?></td>
					<td><?php echo ($porcento_a);?>%</td>
				</tr>
				<tr>
					<th><?php echo __("<= 5");?></th>
					<td><?php echo $list_b['total']; ?></td>
					<td><?php echo ($porcento_b);?>%</td>
				</tr>
				<tr>
					<th><?php echo __("6-10");?></th>
					<td><?php echo $list_c['total']; ?></td>
					<td><?php echo $porcento_c;?>%</td>
				</tr>
				<tr>
					<th><?php echo __("11-20");?></th>
					<td><?php echo $list_d['total']; ?></td>
					<td><?php echo $porcento_d;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __("21-50");?></th>
					<td><?php echo $list_e['total']; ?></td>
					<td><?php echo $porcento_e;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __(">50");?></th>
					<td><?php echo $list_f['total']; ?></td>
					<td><?php echo $porcento_f;?>%</td>
				</tr>	

				<tr>
					<th>Totais</th>
					<td><?php echo $total; ?></td>
					<td><?php echo ($total>0)?100:0?>%</td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_a,$porcento_b, $porcento_c, $porcento_d, $porcento_e, $porcento_f))));
			$leg = urlencode(htmlentities(serialize(array(__("nenhuma"), __("<= 5"), __("6-10"),__("11-20"),__("21-50"),__(">50")))));

			
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		<?php 
		if($total <= 0)
		{
		?>
		<div id="message_sucess" class="message message_sucess">
		<p><?php echo __("Os valores não permitem a criação de um gráfico")?></p>
		</div>
		<?php 
		}
		?>
			

<?php 	
//**************************************************************************************
//QUESTAO 8D

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question10 LIKE '%;18;%'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10 LIKE '%;19;%'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10 LIKE '%;20;%'";
$list_c = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;21;%'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;22;%'";
$list_e = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;23;%'";
$list_f = $connection->GetResult($query);


$total = $list_a['total'] + $list_b['total'] + $list_c['total'] + $list_d['total'] + $list_e['total'] + $list_f['total'];

$porcento_a = ($total > 0)?number_format((($list_a['total']/$total)*100),1,",",""):0;
$porcento_b = ($total > 0)?number_format((($list_b['total']/$total)*100),1,",",""):0;
$porcento_c = ($total > 0)?number_format((($list_c['total']/$total)*100),1,",",""):0;
$porcento_d = ($total > 0)?number_format((($list_d['total']/$total)*100),1,",",""):0;
$porcento_e = ($total > 0)?number_format((($list_e['total']/$total)*100),1,",",""):0;
$porcento_f = ($total > 0)?number_format((($list_f['total']/$total)*100),1,",",""):0;

?>

		<div class="clear"></div>
		<div class="enunciado"><?php echo __("D. Mediastino ")?></div>
		<div class="rp1_left">

			<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Valores Absolutos</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><?php echo __("nenhuma");?></th>
					<td><?php echo $list_a['total']; ?></td>
					<td><?php echo ($porcento_a);?>%</td>
				</tr>
				<tr>
					<th><?php echo __("<= 5");?></th>
					<td><?php echo $list_b['total']; ?></td>
					<td><?php echo ($porcento_b);?>%</td>
				</tr>
				<tr>
					<th><?php echo __("6-10");?></th>
					<td><?php echo $list_c['total']; ?></td>
					<td><?php echo $porcento_c;?>%</td>
				</tr>
				<tr>
					<th><?php echo __("11-20");?></th>
					<td><?php echo $list_d['total']; ?></td>
					<td><?php echo $porcento_d;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __("21-50");?></th>
					<td><?php echo $list_e['total']; ?></td>
					<td><?php echo $porcento_e;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __(">50");?></th>
					<td><?php echo $list_f['total']; ?></td>
					<td><?php echo $porcento_f;?>%</td>
				</tr>	
				
				<tr>
					<th>Totais</th>
					<td><?php echo $total; ?></td>
					<td><?php echo ($total>0)?100:0?>%</td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_a,$porcento_b, $porcento_c, $porcento_d, $porcento_e, $porcento_f))));
			$leg = urlencode(htmlentities(serialize(array(__("nenhuma"), __("<= 5"), __("6-10"),__("11-20"),__("21-50"),__(">50")))));

			
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		<?php 
		if($total <= 0)
		{
		?>
		<div id="message_sucess" class="message message_sucess">
		<p><?php echo __("Os valores não permitem a criação de um gráfico")?></p>
		</div>
		<?php 
		}
		?>
			
	

<?php 	
//**************************************************************************************
//QUESTAO 8E

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question10 LIKE '%;24;%'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10 LIKE '%;25;%'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10 LIKE '%;26;%'";
$list_c = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;27;%'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;28;%'";
$list_e = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;29;%'";
$list_f = $connection->GetResult($query);


$total = $list_a['total'] + $list_b['total'] + $list_c['total'] + $list_d['total'] + $list_e['total'] + $list_f['total'];

$porcento_a = ($total > 0)?number_format((($list_a['total']/$total)*100),1,",",""):0;
$porcento_b = ($total > 0)?number_format((($list_b['total']/$total)*100),1,",",""):0;
$porcento_c = ($total > 0)?number_format((($list_c['total']/$total)*100),1,",",""):0;
$porcento_d = ($total > 0)?number_format((($list_d['total']/$total)*100),1,",",""):0;
$porcento_e = ($total > 0)?number_format((($list_e['total']/$total)*100),1,",",""):0;
$porcento_f = ($total > 0)?number_format((($list_f['total']/$total)*100),1,",",""):0;

?>

		<div class="clear"></div>
		<div class="enunciado"><?php echo __("E. Pancreato-biliar-ampular  ")?></div>
		<div class="rp1_left">

			<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Valores Absolutos</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><?php echo __("nenhuma");?></th>
					<td><?php echo $list_a['total']; ?></td>
					<td><?php echo ($porcento_a);?>%</td>
				</tr>
				<tr>
					<th><?php echo __("<= 5");?></th>
					<td><?php echo $list_b['total']; ?></td>
					<td><?php echo ($porcento_b);?>%</td>
				</tr>
				<tr>
					<th><?php echo __("6-10");?></th>
					<td><?php echo $list_c['total']; ?></td>
					<td><?php echo $porcento_c;?>%</td>
				</tr>
				<tr>
					<th><?php echo __("11-20");?></th>
					<td><?php echo $list_d['total']; ?></td>
					<td><?php echo $porcento_d;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __("21-50");?></th>
					<td><?php echo $list_e['total']; ?></td>
					<td><?php echo $porcento_e;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __(">50");?></th>
					<td><?php echo $list_f['total']; ?></td>
					<td><?php echo $porcento_f;?>%</td>
				</tr>	
				
				<tr>
					<th>Totais</th>
					<td><?php echo $total; ?></td>
					<td><?php echo ($total>0)?100:0?>%</td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_a,$porcento_b, $porcento_c, $porcento_d, $porcento_e, $porcento_f))));
			$leg = urlencode(htmlentities(serialize(array(__("nenhuma"), __("<= 5"), __("6-10"),__("11-20"),__("21-50"),__(">50")))));

			
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		<?php 
		if($total <= 0)
		{
		?>
		<div id="message_sucess" class="message message_sucess">
		<p><?php echo __("Os valores não permitem a criação de um gráfico")?></p>
		</div>
		<?php 
		}
		?>
			
	
<?php 	
//**************************************************************************************
//QUESTAO 8F

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question10 LIKE '%;30;%'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10 LIKE '%;31;%'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10 LIKE '%;32;%'";
$list_c = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;33;%'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;34;%'";
$list_e = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;35;%'";
$list_f = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;36;%'";
$list_g = $connection->GetResult($query);


$total = $list_a['total'] + $list_b['total'] + $list_c['total'] + $list_d['total'] + $list_e['total'] + $list_f['total'] + $list_g['total'];

$porcento_a = ($total > 0)?number_format((($list_a['total']/$total)*100),1,",",""):0;
$porcento_b = ($total > 0)?number_format((($list_b['total']/$total)*100),1,",",""):0;
$porcento_c = ($total > 0)?number_format((($list_c['total']/$total)*100),1,",",""):0;
$porcento_d = ($total > 0)?number_format((($list_d['total']/$total)*100),1,",",""):0;
$porcento_e = ($total > 0)?number_format((($list_e['total']/$total)*100),1,",",""):0;
$porcento_f = ($total > 0)?number_format((($list_f['total']/$total)*100),1,",",""):0;
$porcento_g = ($total > 0)?number_format((($list_g['total']/$total)*100),1,",",""):0;

?>

		<div class="clear"></div>
		<div class="enunciado"><?php echo __("F. Puncao ecoguiada (FNA) - alta e baixa  ")?></div>
		<div class="rp1_left">

			<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Valores Absolutos</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><?php echo __("nenhuma");?></th>
					<td><?php echo $list_a['total']; ?></td>
					<td><?php echo ($porcento_a);?>%</td>
				</tr>
				<tr>
					<th><?php echo __("<= 5");?></th>
					<td><?php echo $list_b['total']; ?></td>
					<td><?php echo ($porcento_b);?>%</td>
				</tr>
				<tr>
					<th><?php echo __("6-10");?></th>
					<td><?php echo $list_c['total']; ?></td>
					<td><?php echo $porcento_c;?>%</td>
				</tr>
				<tr>
					<th><?php echo __("11-20");?></th>
					<td><?php echo $list_d['total']; ?></td>
					<td><?php echo $porcento_d;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __("21-50");?></th>
					<td><?php echo $list_e['total']; ?></td>
					<td><?php echo $porcento_e;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __("51-100");?></th>
					<td><?php echo $list_f['total']; ?></td>
					<td><?php echo $porcento_f;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __(">100");?></th>
					<td><?php echo $list_g['total']; ?></td>
					<td><?php echo $porcento_g;?>%</td>
				</tr>
					
				<tr>
					<th>Totais</th>
					<td><?php echo $total; ?></td>
					<td><?php echo ($total>0)?100:0?>%</td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_a,$porcento_b, $porcento_c, $porcento_d, $porcento_e, $porcento_f, $porcento_g))));
			$leg = urlencode(htmlentities(serialize(array(__("nenhuma"), __("<= 5"), __("6-10"),__("11-20"),__("21-50"),__("51-100"), __(">100") ))));

			
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		<?php 
		if($total <= 0)
		{
		?>
		<div id="message_sucess" class="message message_sucess">
		<p><?php echo __("Os valores não permitem a criação de um gráfico")?></p>
		</div>
		<?php 
		}
		?>
			
	

<?php 	
//**************************************************************************************
//QUESTAO 8G

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question10 LIKE '%;37'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10 LIKE '%;38'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10 LIKE '%;39'";
$list_c = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;40'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;41'";
$list_e = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;42'";
$list_f = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;43'";
$list_g = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question10  LIKE '%;44'";
$list_h = $connection->GetResult($query);

$total = $list_a['total'] + $list_b['total'] + $list_c['total'] + $list_d['total'] + $list_e['total'] + $list_f['total'] + $list_g['total'] + $list_h['total'];

$porcento_a = ($total > 0)?number_format((($list_a['total']/$total)*100),1,",",""):0;
$porcento_b = ($total > 0)?number_format((($list_b['total']/$total)*100),1,",",""):0;
$porcento_c = ($total > 0)?number_format((($list_c['total']/$total)*100),1,",",""):0;
$porcento_d = ($total > 0)?number_format((($list_d['total']/$total)*100),1,",",""):0;
$porcento_e = ($total > 0)?number_format((($list_e['total']/$total)*100),1,",",""):0;
$porcento_f = ($total > 0)?number_format((($list_f['total']/$total)*100),1,",",""):0;
$porcento_g = ($total > 0)?number_format((($list_g['total']/$total)*100),1,",",""):0;
$porcento_h = ($total > 0)?number_format((($list_h['total']/$total)*100),1,",",""):0;

?>

		<div class="clear"></div>
		<div class="enunciado"><?php echo __("G. Terapeutica - alta e baixa ( neurolise/bloqueio do plexo celiaco, drenagens, etc ...)")?></div>
		<div class="rp1_left">

			<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Valores Absolutos</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><?php echo __("nenhuma");?></th>
					<td><?php echo $list_a['total']; ?></td>
					<td><?php echo ($porcento_a);?>%</td>
				</tr>
				<tr>
					<th><?php echo __("01");?></th>
					<td><?php echo $list_b['total']; ?></td>
					<td><?php echo ($porcento_b);?>%</td>
				</tr>
				<tr>
					<th><?php echo __("02");?></th>
					<td><?php echo $list_c['total']; ?></td>
					<td><?php echo $porcento_c;?>%</td>
				</tr>
				<tr>
					<th><?php echo __("03");?></th>
					<td><?php echo $list_d['total']; ?></td>
					<td><?php echo $porcento_d;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __("04-08");?></th>
					<td><?php echo $list_e['total']; ?></td>
					<td><?php echo $porcento_e;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __("09-15");?></th>
					<td><?php echo $list_f['total']; ?></td>
					<td><?php echo $porcento_f;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __("16-25");?></th>
					<td><?php echo $list_g['total']; ?></td>
					<td><?php echo $porcento_g;?>%</td>
				</tr>
				<tr>
					<th><?php echo __(">25");?></th>
					<td><?php echo $list_h['total']; ?></td>
					<td><?php echo $porcento_h;?>%</td>
				</tr>
									
				<tr>
					<th>Totais</th>
					<td><?php echo $total; ?></td>
					<td><?php echo ($total>0)?100:0?>%</td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_a,$porcento_b, $porcento_c, $porcento_d, $porcento_e, $porcento_f, $porcento_g,$porcento_h))));
			$leg = urlencode(htmlentities(serialize(array(__("nenhuma"), __("01"), __("02"),__("03"),__("04-08"),__("09-15"),__("16-25"),__(">25")))));

			
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		<?php 
		if($total <= 0)
		{
		?>
		<div id="message_sucess" class="message message_sucess">
		<p><?php echo __("Os valores não permitem a criação de um gráfico")?></p>
		</div>
		<?php 
		}
		?>
	</div><!-- # exp_content -->
	</div><!-- # questao -->	
	<?php include 'control_panel/reports_survey_answers_2.php';?>	
	<?php include 'control_panel/reports_survey_answers_3.php';?>	
</div><!-- #survey_report -->
