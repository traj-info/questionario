<?php 

//**************************************************************************************
//QUESTAO 19


$query  = "
		SELECT 
				COUNT(*) as total_sim
		FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";

$query = $query." ".$where. " AND question21 = 0";

$list_sim = $connection->GetResult($query);




$query  = "SELECT 
				COUNT(*) as total_nao
			FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND question21 = 1";
$list_nao = $connection->GetResult($query);


$total_questoes = $list_sim['total_sim'] + $list_nao['total_nao'];


$porcento_sim = ($total_questoes > 0)?number_format((($list_sim['total_sim']/$total_questoes)*100),1,",",""):0;
$porcento_nao = ($total_questoes > 0)?number_format((($list_nao['total_nao']/$total_questoes)*100),1,",",""):0;



?>
<div id="survey_report">

	<div class="questao" id="q19">
		<div class="enunciado exp_heading"><?php echo __("19. Voce verifica os resultados (bioquimicos, culturas, citologicos e/ou histologicos) apos suas FNA?");?><a class="seletor_toggle">[+]</a></div>
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
				<th>Sim</th>
				<td><?php echo $list_sim['total_sim']; ?></td>
				<td><?php echo $porcento_sim;?>%</td>
			</tr>
			<tr>
				<th>Não</th>
				<td><?php echo $list_nao['total_nao']; ?></td>
				<td><?php echo $porcento_nao;?>%</td>
			</tr>
			<tr>
				<th>Totais</th>
				<td><?php echo $total_questoes; ?></td>
				<td><?php echo ($total_questoes >0)?100:0;?>%</td>
			</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		<div class="rp1_right">
		<?php 
		if($total_questoes > 0)
		{
		?>
		
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_sim, $porcento_nao))));
			$leg = urlencode(htmlentities(serialize(array('Sim', 'Não'))));

			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />

		
		<?php 
		}
		?>
		</div><!-- #rp1_right -->
		
		<div class="clear"></div>
		<?php 
		if($total_questoes <= 0)
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
//QUESTAO 20

// ETAPA 1: Ler todos os valores
$query  = "SELECT question22 FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where . " AND question22 IS NOT NULL";

$rows = $connection->GetAllResults($query);
if(is_array($rows))
{
	$matriz = "";
	$n = 0;
	foreach($rows as $key => $row)
	{
		$n++;
		$exploded = explode(";", $row['question22']);
		if(is_array($exploded))
		{
			foreach($exploded as $key2 => $value)
			{
				$matriz[$key2][] = (int)$value;
			}
		}
	}
}

// ETAPA 2: calcular média, sd, max_value para cada item (de A a B) dentro da questão, e montar matriz de labels (eixo-x) e valores (eixo-y)
$mean = "";
$sd = "";
$max_value = "";
$max_x = 20;
$datay = "";
$datax = "";

if(isset($matriz))
{
	foreach($matriz as $item => $m)
	{
		$max_value[$item] = 0;
		$mean[$item] = 0;
		
		// contador para cálculo da média
		foreach($m as $key2 => $subitem)
		{
			$mean[$item] += $subitem;
			if($max_value[$item] < $subitem) $max_value[$item] = $subitem;
		}
		$mean[$item] = number_format($mean[$item] / $n, 2, ",", "");
		
		// cálculo do desvio-padrão
		$sd[$item] = number_format(sd($m), 2, ",", "");
		
		// cálculo do número de itens a serem agrupados em cada label do eixo-x	
		$n_per_group[$item] = ($max_value[$item] > $max_x) ? parte_inteira(((float)$max_value[$item] / (float)$max_x), DS) : 1;
		if(($n_per_group[$item]*($max_x + 1)-1) < $max_value[$item]) $n_per_group[$item]++;
		
		// criação de datax (labels eixo-x)
		$datax[$item] = "";
		for($i=0; $i<($max_x+1); $i++)
		{
			if($n_per_group[$item] == 1) $datax[$item][] = $i;
			else $datax[$item][] = ($i*$n_per_group[$item] ) . "-" . (($i+1) * $n_per_group[$item] - 1);
		}
		//print_array($datax[$item]);
	
		// criação da matriz datay (valores)
		$datay[$item] = array_fill(0, $max_x + 1, 0);
		foreach($m as $key2 => $subitem)
		{
			$local = find_group($subitem, $n_per_group[$item]);
			$datay[$item][$local]++;
		}
	/*	
		echo "<br>---------------<br>";
		print_array($datay[$item]); 
		echo "ITEM $item | média: " . $mean[$item] . " | sd: " . $sd[$item] . " | max_value: " . $max_value[$item] . " | n_per_group: " . $n_per_group[$item] . " | max_x: " . $max_x . "<br><br>";
	*/
	}
}

// ETAPA 3: definir títulos de cada subitem
$titulos[0] = "Em lesões sólidas";
$titulos[1] = "Em lesões císticas";


?>
	<div class="questao" id="q20">
		<div class="enunciado exp_heading"><?php echo __("20. Qual e o seu percentual total de positividade de diagnostico (bioquimicos, culturas, citologicos e/ou histologicos) obtido por FNA?");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		
		
		
		<?php
		// LOOPING PARA CADA SUBITEM DESTA QUESTÃO
		for($item=0; $item<2; $item++)
		{
			echo '<div class="enunciado">' . $titulos[$item] . '</div><div class="clear"></div>';
			echo '<div class="rp1_center">';

			if(isset($datay[$item]) && isset($datax[$item]))
			{
				$datay_row = urlencode(htmlentities(serialize($datay[$item])));
				$datax_row = urlencode(htmlentities(serialize($datax[$item])));
				$titley = urlencode(htmlentities("quantidade de marcações"));
				$titlex = urlencode(htmlentities("percentual de positividade de diagnóstico por FNA"));
				$width = urlencode(htmlentities("930"));
				$height = urlencode(htmlentities("250"));
				
				?>
	
				<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=verticalbar&output=JPGRAPH&x-data=$datax_row&y-data=$datay_row&x-title=$titlex&y-title=$titley&width=$width&height=$height"?>" />
	
				<div class="rp1_sumario">N = <?php echo $n; ?> &nbsp;&nbsp;&nbsp;&nbsp; MÉDIA = <?php echo $mean[$item]; ?> &nbsp;&nbsp;&nbsp;&nbsp; DESVIO-PADRÃO = <?php echo $sd[$item]; ?></div><!-- #rp1_sumario -->
			<?php 
			}
			else 
			{
				echo REPORT_MSG_EMPTY_VALUES;
			}
			?>
		</div><!-- #rp1_center -->
		<div class="clear"></div>
		<?php } // FIM LOOPING PARA CADA SUBITEM ?>

		
		</div>
	</div><!-- # questao -->	


<?php 
//**************************************************************************************
//QUESTAO 21

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question23 LIKE '%0;%'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question23 LIKE '%1;%'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question23 LIKE '%2;%'";
$list_c = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question23 LIKE '%3;%'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question23 LIKE '%4;%'";
$list_e = $connection->GetResult($query);

$total = $list_a['total'] + $list_b['total'] + $list_c['total'] + $list_d['total'] + $list_e['total'];

$porcento_a = ($total > 0)?number_format((($list_a['total']/$total)*100),1,",",""):0;
$porcento_b = ($total > 0)?number_format((($list_b['total']/$total)*100),1,",",""):0;
$porcento_c = ($total > 0)?number_format((($list_c['total']/$total)*100),1,",",""):0;
$porcento_d = ($total > 0)?number_format((($list_d['total']/$total)*100),1,",",""):0;
$porcento_e = ($total > 0)?number_format((($list_e['total']/$total)*100),1,",",""):0;

?>

	<div class="questao" id="q21">
		<div class="enunciado exp_heading"><?php echo __("21. Quem  e o responsavel pela sedacao do paciente  durante suas EUS?(assinale todas as aplicaveis)");?><a class="seletor_toggle">[+]</a></div>
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
					<th>a. <?php echo __(" Uma enfermeira com formacao especifica em anestesia");?></th>
					<td><?php echo $list_a['total']; ?></td>
					<td><?php echo ($porcento_a);?>%</td>
				</tr>
				<tr>
					<th>b. <?php echo __(" Um anestesista");?></th>
					<td><?php echo $list_b['total']; ?></td>
					<td><?php echo ($porcento_b);?>%</td>
				</tr>
				<tr>
					<th>c. <?php echo __(" Um medico nao-anestesista (que nao esta realizando a EUS)");?></th>
					<td><?php echo $list_c['total']; ?></td>
					<td><?php echo $porcento_c;?>%</td>
				</tr>
				<tr>
					<th>d. <?php echo __(" Voce mesmo");?></th>
					<td><?php echo $list_d['total']; ?></td>
					<td><?php echo $porcento_d;?>%</td>
				</tr>	
				<tr>
					<th>e. <?php echo __(" Outros");?></th>
					<td><?php echo $list_e['total']; ?></td>
					<td><?php echo $porcento_e;?>%</td>
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
			$data = urlencode(htmlentities(serialize(array($porcento_a,$porcento_b, $porcento_c, $porcento_d, $porcento_e))));
			$leg = urlencode(htmlentities(serialize(array('a', 'b', 'c','d','e'))));

			
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
		</div>
	</div><!-- # questao -->	


<?php 
//**************************************************************************************
//QUESTAO 22

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question24 LIKE '%0;%'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question24 LIKE '%1;%'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question24 LIKE '%2;%'";
$list_c = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question24 LIKE '%3;%'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question24 LIKE '%4;%'";
$list_e = $connection->GetResult($query);

$total = $list_a['total'] + $list_b['total'] + $list_c['total'] + $list_d['total'] + $list_e['total'];

$porcento_a = ($total > 0)?number_format((($list_a['total']/$total)*100),1,",",""):0;
$porcento_b = ($total > 0)?number_format((($list_b['total']/$total)*100),1,",",""):0;
$porcento_c = ($total > 0)?number_format((($list_c['total']/$total)*100),1,",",""):0;
$porcento_d = ($total > 0)?number_format((($list_d['total']/$total)*100),1,",",""):0;
$porcento_e = ($total > 0)?number_format((($list_e['total']/$total)*100),1,",",""):0;

?>

	<div class="questao" id="q22">
		<div class="enunciado exp_heading"><?php echo __("22. Voce indica sedacao com propofol para suas EUS? (assinale todas as aplicaveis)");?><a class="seletor_toggle">[+]</a></div>
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
					<th>a. <?php echo __(" Na maioria ou todos os meus exames ");?></th>
					<td><?php echo $list_a['total']; ?></td>
					<td><?php echo ($porcento_a);?>%</td>
				</tr>
				<tr>
					<th>b. <?php echo __(" Somente quando o paciente apresenta condicoes de dificil sedacao com outras drogas");?></th>
					<td><?php echo $list_b['total']; ?></td>
					<td><?php echo ($porcento_b);?>%</td>
				</tr>
				<tr>
					<th>c. <?php echo __(" Somente em procedimentos terapeuticos");?></th>
					<td><?php echo $list_c['total']; ?></td>
					<td><?php echo $porcento_c;?>%</td>
				</tr>
				<tr>
					<th>d. <?php echo __(" Nao utilizo propofol (se voce nao utiliza propofol, pule a proxima questao)");?></th>
					<td><?php echo $list_d['total']; ?></td>
					<td><?php echo $porcento_d;?>%</td>
				</tr>	
				<tr>
					<th>e. <?php echo __(" Outros ");?></th>
					<td><?php echo $list_e['total']; ?></td>
					<td><?php echo $porcento_e;?>%</td>
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
			$data = urlencode(htmlentities(serialize(array($porcento_a,$porcento_b, $porcento_c, $porcento_d, $porcento_e))));
			$leg = urlencode(htmlentities(serialize(array('a', 'b', 'c','d','e'))));

			
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
		</div>
	</div><!-- # questao -->		
	
<?php 
//**************************************************************************************
//QUESTAO 23

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question25 LIKE '%0;%'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question25 LIKE '%1;%'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question25 LIKE '%2;%'";
$list_c = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question25 LIKE '%3;%'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question25 LIKE '%4;%'";
$list_e = $connection->GetResult($query);

$total = $list_a['total'] + $list_b['total'] + $list_c['total'] + $list_d['total'] + $list_e['total'];

$porcento_a = ($total > 0)?number_format((($list_a['total']/$total)*100),1,",",""):0;
$porcento_b = ($total > 0)?number_format((($list_b['total']/$total)*100),1,",",""):0;
$porcento_c = ($total > 0)?number_format((($list_c['total']/$total)*100),1,",",""):0;
$porcento_d = ($total > 0)?number_format((($list_d['total']/$total)*100),1,",",""):0;
$porcento_e = ($total > 0)?number_format((($list_e['total']/$total)*100),1,",",""):0;

?>

	<div class="questao" id="q23">
		<div class="enunciado exp_heading"><?php echo __('23. Quem e o responsavel pela sedacao "com propofol" durantes suas EUS? (assinale todas as aplicaveis)');?><a class="seletor_toggle">[+]</a></div>
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
					<th>a. <?php echo __(" Uma enfermeira com formacao especifica em anestesia");?></th>
					<td><?php echo $list_a['total']; ?></td>
					<td><?php echo ($porcento_a);?>%</td>
				</tr>
				<tr>
					<th>b. <?php echo __(" Um anestesista");?></th>
					<td><?php echo $list_b['total']; ?></td>
					<td><?php echo ($porcento_b);?>%</td>
				</tr>
				<tr>
					<th>c. <?php echo __(" Um medico nao-anestesista (que nao esta realizando a EUS)");?></th>
					<td><?php echo $list_c['total']; ?></td>
					<td><?php echo $porcento_c;?>%</td>
				</tr>
				<tr>
					<th>d. <?php echo __(" Voce mesmo");?></th>
					<td><?php echo $list_d['total']; ?></td>
					<td><?php echo $porcento_d;?>%</td>
				</tr>	
				<tr>
					<th>e. <?php echo __(" Outros ");?></th>
					<td><?php echo $list_e['total']; ?></td>
					<td><?php echo $porcento_e;?>%</td>
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
			$data = urlencode(htmlentities(serialize(array($porcento_a,$porcento_b, $porcento_c, $porcento_d, $porcento_e))));
			$leg = urlencode(htmlentities(serialize(array('a', 'b', 'c','d','e'))));

			
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
		</div>
	</div><!-- # questao -->		
	
<?php 
//**************************************************************************************
//QUESTAO 24


$query  = "
		SELECT 
				COUNT(*) as total_sim
		FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";

$query = $query." ".$where. " AND question26 = 0";

$list_sim = $connection->GetResult($query);




$query  = "SELECT 
				COUNT(*) as total_nao
			FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND question26 = 1";
$list_nao = $connection->GetResult($query);


$total_questoes = $list_sim['total_sim'] + $list_nao['total_nao'];


$porcento_sim = ($total_questoes > 0)?number_format((($list_sim['total_sim']/$total_questoes)*100),1,",",""):0;
$porcento_nao = ($total_questoes > 0)?number_format((($list_nao['total_nao']/$total_questoes)*100),1,",",""):0;



?>
<div id="survey_report">

	<div class="questao" id="q24">
		<div class="enunciado exp_heading"><?php echo __("24. Atualmente, voce treina outros medicos em EUS?");?><a class="seletor_toggle">[+]</a></div>
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
				<th>Sim</th>
				<td><?php echo $list_sim['total_sim']; ?></td>
				<td><?php echo $porcento_sim;?>%</td>
			</tr>
			<tr>
				<th>Não</th>
				<td><?php echo $list_nao['total_nao']; ?></td>
				<td><?php echo $porcento_nao;?>%</td>
			</tr>
			<tr>
				<th>Totais</th>
				<td><?php echo $total_questoes; ?></td>
				<td><?php echo ($total_questoes >0)?100:0;?>%</td>
			</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		<div class="rp1_right">
		<?php 
		if($total_questoes > 0)
		{
		?>
		
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_sim, $porcento_nao))));
			$leg = urlencode(htmlentities(serialize(array('Sim', 'Não'))));

			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />

		
		<?php 
		}
		?>
		</div><!-- #rp1_right -->
		
		<div class="clear"></div>
		<?php 
		if($total_questoes <= 0)
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
//QUESTAO 25A

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question27 LIKE '0;%'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27 LIKE '1;%'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27 LIKE '2;%'";
$list_c = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '3;%'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '4;%'";
$list_e = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '5;%'";
$list_f = $connection->GetResult($query);


$total = $list_a['total'] + $list_b['total'] + $list_c['total'] + $list_d['total'] + $list_e['total'] + $list_f['total'];

$porcento_a = ($total > 0)?number_format((($list_a['total']/$total)*100),1,",",""):0;
$porcento_b = ($total > 0)?number_format((($list_b['total']/$total)*100),1,",",""):0;
$porcento_c = ($total > 0)?number_format((($list_c['total']/$total)*100),1,",",""):0;
$porcento_d = ($total > 0)?number_format((($list_d['total']/$total)*100),1,",",""):0;
$porcento_e = ($total > 0)?number_format((($list_e['total']/$total)*100),1,",",""):0;
$porcento_f = ($total > 0)?number_format((($list_f['total']/$total)*100),1,",",""):0;

?>


	<div class="questao" id="q25a">

		<div class="enunciado exp_heading"><?php echo __('25. Na sua opiniao, atualmente, qual e a experiencia minima que deve ser aplicada em estagios "hands-on" em EUS?');?><a class="seletor_toggle">[+]</a></div>
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
//QUESTAO 25B

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question27 LIKE '%;6;%'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27 LIKE '%;7;%'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27 LIKE '%;8;%'";
$list_c = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;9;%'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;10;%'";
$list_e = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;11;%'";
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
//QUESTAO 25C

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question27 LIKE '%;12;%'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27 LIKE '%;13;%'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27 LIKE '%;14;%'";
$list_c = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;15;%'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;16;%'";
$list_e = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;17;%'";
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
//QUESTAO 25D

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question27 LIKE '%;18;%'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27 LIKE '%;19;%'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27 LIKE '%;20;%'";
$list_c = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;21;%'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;22;%'";
$list_e = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;23;%'";
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
//QUESTAO 25E

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question27 LIKE '%;24;%'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27 LIKE '%;25;%'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27 LIKE '%;26;%'";
$list_c = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;27;%'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;28;%'";
$list_e = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;29;%'";
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
//QUESTAO 25F

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question27 LIKE '%;30;%'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27 LIKE '%;31;%'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27 LIKE '%;32;%'";
$list_c = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;33;%'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;34;%'";
$list_e = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;35;%'";
$list_f = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;36;%'";
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
//QUESTAO 25G

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question27 LIKE '%;37'";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27 LIKE '%;38'";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27 LIKE '%;39'";
$list_c = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;40'";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;41'";
$list_e = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;42'";
$list_f = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;43'";
$list_g = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question27  LIKE '%;44'";
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
		</div>		
	</div><!-- #questao -->
	
	
<?php 
//**************************************************************************************
//QUESTAO 26

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question28 = 0";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question28 = 1";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question28 = 2";
$list_c = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question28 = 3";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question28 = 4";
$list_e = $connection->GetResult($query);


$total = $list_a['total'] + $list_b['total'] + $list_c['total'] + $list_d['total'] + $list_e['total'];

$porcento_a = ($total > 0)?number_format((($list_a['total']/$total)*100),1,",",""):0;
$porcento_b = ($total > 0)?number_format((($list_b['total']/$total)*100),1,",",""):0;
$porcento_c = ($total > 0)?number_format((($list_c['total']/$total)*100),1,",",""):0;
$porcento_d = ($total > 0)?number_format((($list_d['total']/$total)*100),1,",",""):0;
$porcento_e = ($total > 0)?number_format((($list_e['total']/$total)*100),1,",",""):0;
$porcento_f = ($total > 0)?number_format((($list_f['total']/$total)*100),1,",",""):0;


?>

	<div class="questao" id="q26">
		<div class="enunciado exp_heading"><?php echo __("26. Na sua opiniao, atualmente,  qual e o tempo minimo de um estagio para a formacao do medico em EUS");?><a class="seletor_toggle">[+]</a></div>
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
					<th>a. <?php echo __(" <= 3 meses ");?></th>
					<td><?php echo $list_a['total']; ?></td>
					<td><?php echo ($porcento_a);?>%</td>
				</tr>
				<tr>
					<th>b. <?php echo __(" 3-6 meses ");?></th>
					<td><?php echo $list_b['total']; ?></td>
					<td><?php echo ($porcento_b);?>%</td>
				</tr>
				<tr>
					<th>c. <?php echo __(" 6-9 meses ");?></th>
					<td><?php echo $list_c['total']; ?></td>
					<td><?php echo $porcento_c;?>%</td>
				</tr>
				<tr>
					<th>d. <?php echo __("> 9 meses");?></th>
					<td><?php echo $list_d['total']; ?></td>
					<td><?php echo $porcento_d;?>%</td>
				</tr>	
				<tr>
					<th>e. <?php echo __("O tempo nao e relevante, pois o importante do treinamento e o numero de procedimentos.");?></th>
					<td><?php echo $list_e['total']; ?></td>
					<td><?php echo $porcento_e;?>%</td>
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
			$data = urlencode(htmlentities(serialize(array($porcento_a,$porcento_b, $porcento_c, $porcento_d, $porcento_e))));
			$leg = urlencode(htmlentities(serialize(array('a', 'b', 'c', 'd', 'e'))));

			
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
//QUESTAO 27A


$query  = "
		SELECT 
				COUNT(*) as total_sim
		FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";

$query = $query." ".$where. " AND question29 LIKE '0;%'";

$list_sim = $connection->GetResult($query);




$query  = "SELECT 
				COUNT(*) as total_nao
			FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND question29 LIKE '1;%'";
$list_nao = $connection->GetResult($query);


$total_questoes = $list_sim['total_sim'] + $list_nao['total_nao'];


$porcento_sim = ($total_questoes > 0)?number_format((($list_sim['total_sim']/$total_questoes)*100),1,",",""):0;
$porcento_nao = ($total_questoes > 0)?number_format((($list_nao['total_nao']/$total_questoes)*100),1,",",""):0;



?>
<div id="survey_report">

	<div class="questao" id="q27">
		<div class="enunciado exp_heading"><?php echo __("27. Qual sua opiniao em relacao a formacao medica em EUS? (assinale todas as aplicaveis)");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		<div class="enunciado">A. <?php echo __("O treinamento formal reduz o tempo para adquirir competencia")?></div>
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
				<th>Sim</th>
				<td><?php echo $list_sim['total_sim']; ?></td>
				<td><?php echo $porcento_sim;?>%</td>
			</tr>
			<tr>
				<th>Não</th>
				<td><?php echo $list_nao['total_nao']; ?></td>
				<td><?php echo $porcento_nao;?>%</td>
			</tr>
			<tr>
				<th>Totais</th>
				<td><?php echo $total_questoes; ?></td>
				<td><?php echo ($total_questoes >0)?100:0;?>%</td>
			</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		<div class="rp1_right">
		<?php 
		if($total_questoes > 0)
		{
		?>
		
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_sim, $porcento_nao))));
			$leg = urlencode(htmlentities(serialize(array('Sim', 'Não'))));

			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />

		
		<?php 
		}
		?>
		</div><!-- #rp1_right -->
		
		<div class="clear"></div>
		<?php 
		if($total_questoes <= 0)
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
//QUESTAO 27B


$query  = "
		SELECT 
				COUNT(*) as total_sim
		FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";

$query = $query." ".$where. " AND question29 LIKE '%2;%'";

$list_sim = $connection->GetResult($query);




$query  = "SELECT 
				COUNT(*) as total_nao
			FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND question29 LIKE '%3;%'";
$list_nao = $connection->GetResult($query);


$total_questoes = $list_sim['total_sim'] + $list_nao['total_nao'];


$porcento_sim = ($total_questoes > 0)?number_format((($list_sim['total_sim']/$total_questoes)*100),1,",",""):0;
$porcento_nao = ($total_questoes > 0)?number_format((($list_nao['total_nao']/$total_questoes)*100),1,",",""):0;



?>
		<div class="enunciado">B. <?php echo __("O treinamento formal e necessario para adquirir competencia ")?></div>
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
				<th>Sim</th>
				<td><?php echo $list_sim['total_sim']; ?></td>
				<td><?php echo $porcento_sim;?>%</td>
			</tr>
			<tr>
				<th>Não</th>
				<td><?php echo $list_nao['total_nao']; ?></td>
				<td><?php echo $porcento_nao;?>%</td>
			</tr>
			<tr>
				<th>Totais</th>
				<td><?php echo $total_questoes; ?></td>
				<td><?php echo ($total_questoes >0)?100:0;?>%</td>
			</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		<div class="rp1_right">
		<?php 
		if($total_questoes > 0)
		{
		?>
		
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_sim, $porcento_nao))));
			$leg = urlencode(htmlentities(serialize(array('Sim', 'Não'))));

			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />

		
		<?php 
		}
		?>
		</div><!-- #rp1_right -->
		
		<div class="clear"></div>
		<?php 
		if($total_questoes <= 0)
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
//QUESTAO 27C


$query  = "
		SELECT 
				COUNT(*) as total_sim
		FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";

$query = $query." ".$where. " AND question29 LIKE '%4;%'";

$list_sim = $connection->GetResult($query);




$query  = "SELECT 
				COUNT(*) as total_nao
			FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND question29 LIKE '%5;%'";
$list_nao = $connection->GetResult($query);


$total_questoes = $list_sim['total_sim'] + $list_nao['total_nao'];


$porcento_sim = ($total_questoes > 0)?number_format((($list_sim['total_sim']/$total_questoes)*100),1,",",""):0;
$porcento_nao = ($total_questoes > 0)?number_format((($list_nao['total_nao']/$total_questoes)*100),1,",",""):0;



?>
		<div class="enunciado">C. <?php echo __("O treinamento formal e necessario para satisfazer fins legais")?></div>
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
				<th>Sim</th>
				<td><?php echo $list_sim['total_sim']; ?></td>
				<td><?php echo $porcento_sim;?>%</td>
			</tr>
			<tr>
				<th>Não</th>
				<td><?php echo $list_nao['total_nao']; ?></td>
				<td><?php echo $porcento_nao;?>%</td>
			</tr>
			<tr>
				<th>Totais</th>
				<td><?php echo $total_questoes; ?></td>
				<td><?php echo ($total_questoes >0)?100:0;?>%</td>
			</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		<div class="rp1_right">
		<?php 
		if($total_questoes > 0)
		{
		?>
		
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_sim, $porcento_nao))));
			$leg = urlencode(htmlentities(serialize(array('Sim', 'Não'))));

			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />

		
		<?php 
		}
		?>
		</div><!-- #rp1_right -->
		
		<div class="clear"></div>
		<?php 
		if($total_questoes <= 0)
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
//QUESTAO 27D


$query  = "
		SELECT 
				COUNT(*) as total_sim
		FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";

$query = $query." ".$where. " AND question29 LIKE '%6;%'";

$list_sim = $connection->GetResult($query);




$query  = "SELECT 
				COUNT(*) as total_nao
			FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND question29 LIKE '%7;%'";
$list_nao = $connection->GetResult($query);


$total_questoes = $list_sim['total_sim'] + $list_nao['total_nao'];


$porcento_sim = ($total_questoes > 0)?number_format((($list_sim['total_sim']/$total_questoes)*100),1,",",""):0;
$porcento_nao = ($total_questoes > 0)?number_format((($list_nao['total_nao']/$total_questoes)*100),1,",",""):0;



?>
		<div class="enunciado">D. <?php echo __("Estrategias de formacao devem depender das leis locais")?></div>
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
				<th>Sim</th>
				<td><?php echo $list_sim['total_sim']; ?></td>
				<td><?php echo $porcento_sim;?>%</td>
			</tr>
			<tr>
				<th>Não</th>
				<td><?php echo $list_nao['total_nao']; ?></td>
				<td><?php echo $porcento_nao;?>%</td>
			</tr>
			<tr>
				<th>Totais</th>
				<td><?php echo $total_questoes; ?></td>
				<td><?php echo ($total_questoes >0)?100:0;?>%</td>
			</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		<div class="rp1_right">
		<?php 
		if($total_questoes > 0)
		{
		?>
		
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_sim, $porcento_nao))));
			$leg = urlencode(htmlentities(serialize(array('Sim', 'Não'))));

			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />

		
		<?php 
		}
		?>
		</div><!-- #rp1_right -->
		
		<div class="clear"></div>
		<?php 
		if($total_questoes <= 0)
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
//QUESTAO 27E


$query  = "
		SELECT 
				COUNT(*) as total_sim
		FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";

$query = $query." ".$where. " AND question29 LIKE '%8;%'";

$list_sim = $connection->GetResult($query);




$query  = "SELECT 
				COUNT(*) as total_nao
			FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND question29 LIKE '%9;%'";
$list_nao = $connection->GetResult($query);


$total_questoes = $list_sim['total_sim'] + $list_nao['total_nao'];


$porcento_sim = ($total_questoes > 0)?number_format((($list_sim['total_sim']/$total_questoes)*100),1,",",""):0;
$porcento_nao = ($total_questoes > 0)?number_format((($list_nao['total_nao']/$total_questoes)*100),1,",",""):0;



?>
		<div class="enunciado">E. <?php echo __("Estrategias de formacao devem depender da sociedade de endoscopia")?></div>
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
				<th>Sim</th>
				<td><?php echo $list_sim['total_sim']; ?></td>
				<td><?php echo $porcento_sim;?>%</td>
			</tr>
			<tr>
				<th>Não</th>
				<td><?php echo $list_nao['total_nao']; ?></td>
				<td><?php echo $porcento_nao;?>%</td>
			</tr>
			<tr>
				<th>Totais</th>
				<td><?php echo $total_questoes; ?></td>
				<td><?php echo ($total_questoes >0)?100:0;?>%</td>
			</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		<div class="rp1_right">
		<?php 
		if($total_questoes > 0)
		{
		?>
		
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_sim, $porcento_nao))));
			$leg = urlencode(htmlentities(serialize(array('Sim', 'Não'))));

			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg"?>" />

		
		<?php 
		}
		?>
		</div><!-- #rp1_right -->
		
		<div class="clear"></div>
		<?php 
		if($total_questoes <= 0)
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
//QUESTAO 27F


$query  = "
		SELECT 
				question29
		FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";

$query = $query." ".$where. " AND question29 IS NOT NULL";

$rows = $connection->GetAllResults($query);
$n = 0;
if(is_array($rows))
{
	foreach($rows as $row)
	{
		$exploded = explode(";", $row['question29']);
		$itemf = $exploded[5];
		if(isset($itemf) && !empty($itemf) && !is_null($itemf)) $n++;
	}
}
$n = ($n == 0) ? 'Nenhuma resposta neste campo.' : $n . ' resposta(s) neste campo.';

?>
		<div class="enunciado">F. <?php echo __("Outros")?></div>
		<div class="clear"></div>
		<div class="rp1_left">
		<?php echo $n; ?><Br><Br>
		Obs.: Em campos do tipo texto aberto, a tabulação automática não é possível.
			
		</div><!-- #rp1_left -->
		<div class="rp1_right">
	
		</div><!-- #rp1_right -->
		
		<div class="clear"></div>
		
			</div>					
	</div><!-- # questao -->		
	
<?php

//**************************************************************************************
//QUESTAO 28

unset($matriz);

$matriz = array();

// ETAPA 1: Ler todos os valores
$query  = "SELECT question30 FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where . " AND question30 IS NOT NULL";

$rows = $connection->GetAllResults($query); 
if(is_array($rows))
{
	$n = 0;
	foreach($rows as $key => $row)
	{
		if(empty($row['question30'])) continue;
		$n++;
		$exploded = explode(";", $row['question30']);
		
		if(is_array($exploded))
		{
			foreach($exploded as $key2 => $value)
			{
				if(empty($value)) $value = 0;
				$matriz[$key2][] = $value;

			}
		}
	}
}


$n_naosei[0] = 0;
$n_naosei[1] = 0;

if(isset($matriz[1]))
{
	// contador "nao sei" item A
	foreach($matriz[1] as $m)
	{
		if($m == '1') $n_naosei[0]++;
	}
}



// contador "nao sei" item B
$cont_naosei_B = 0;

if(isset($matriz[3]))
{
	foreach($matriz[3] as $m)
	{
		if($m == '3') $n_naosei[1]++;
	}
}




// extrator de valores item A
$n_itens[0] = 0;
$n_itens[1] = 0;
$matriz_valores = "";

if(isset($matriz[0]))
{
	foreach($matriz[0] as $m)
	{
		if($m != "") 
		{
			$matriz_valores[0][] = $m;
			$n_itens[0]++;
		}
	}
}

if(isset($matriz[2]))
{
	// extrator de valores item B
	foreach($matriz[2] as $m)
	{
		if($m != "") 
		{
			$matriz_valores[1][] = $m;
			$n_itens[1]++;
		}
	}
}

unset($matriz);

$matriz = $matriz_valores;
unset($matriz_valores);

// ETAPA 2: calcular média, sd, max_value para cada item (de A a B) dentro da questão, e montar matriz de labels (eixo-x) e valores (eixo-y)
$mean = "";
$sd = "";
$max_value = "";
$max_x = 20;
$datay = "";
$datax = "";

//print_r($matriz);

if(is_array($matriz))
{
	foreach($matriz as $item => $m)
	{
		$max_value[$item] = 0;
	
		if($n_itens[$item] > 1)
		{
			// contador para cálculo da média
			foreach($m as $key2 => $subitem)
			{
				$mean[$item] += $subitem;
				if($max_value[$item] < $subitem) $max_value[$item] = $subitem;
			}
			$mean[$item] = number_format($mean[$item] / $n, 2, ",", "");
			
			// cálculo do desvio-padrão
			$sd[$item] = number_format(sd($m), 2, ",", "");
		}
		else // apenas 1 item
		{
			$mean[$item] = $m[0];
			$sd[$item] = 0;
			$max_value[$item] = $m[0];
		}
	
		// cálculo do número de itens a serem agrupados em cada label do eixo-x	
		$n_per_group[$item] = ($max_value[$item] > $max_x) ? parte_inteira(((float)$max_value[$item] / (float)$max_x), DS) : 1;
		if(($n_per_group[$item]*($max_x + 1)-1) < $max_value[$item]) $n_per_group[$item]++;
		
		// criação de datax (labels eixo-x)
		$datax[$item] = "";
		for($i=0; $i<($max_x+1); $i++)
		{
			if($n_per_group[$item] == 1) $datax[$item][] = $i;
			else $datax[$item][] = ($i*$n_per_group[$item] ) . "-" . (($i+1) * $n_per_group[$item] - 1);
		}
	
		// criação da matriz datay (valores)
		$datay[$item] = array_fill(0, $max_x + 1, 0);
		foreach($m as $key2 => $subitem)
		{
			$local = find_group($subitem, $n_per_group[$item]);
			$datay[$item][$local]++;
		}		
	}
}
// ETAPA 3: definir títulos de cada subitem
$titulos[0] = "A. Procedimentos diagnósticos";
$titulos[1] = "B. Procedimentos com FNA";



?>
	<div class="questao" id="q28">
		<div class="enunciado exp_heading"><?php echo __("28. Qual percentagem aproximada que suas EUS sao reembolsadas pelas seguradoras?");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		
		
		
		<?php
		// LOOPING PARA CADA SUBITEM DESTA QUESTÃO
		for($item=0; $item<2; $item++)
		{
			echo '<div class="enunciado">' . $titulos[$item] . '</div><div class="clear"></div>';
			echo '<div class="rp1_center">';
			
			if(isset($datay[$item]) && isset($datax[$item]))
			{
			$datay_row = urlencode(htmlentities(serialize($datay[$item])));
			$datax_row = urlencode(htmlentities(serialize($datax[$item])));
			$titley = urlencode(htmlentities("quantidade de marcações"));
			$titlex = urlencode(htmlentities("percentual digitado"));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("250"));
			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=verticalbar&output=JPGRAPH&x-data=$datax_row&y-data=$datay_row&x-title=$titlex&y-title=$titley&width=$width&height=$height"?>" />

			<div class="rp1_sumario">N = <?php echo $n_itens[$item]; ?> &nbsp;&nbsp;&nbsp;&nbsp; MÉDIA = <?php echo $mean[$item]; ?> &nbsp;&nbsp;&nbsp;&nbsp; DESVIO-PADRÃO = <?php echo $sd[$item]; ?>
				</div><br>
				<?php 
			}
			
			?>
			
			<div class="rp1_sumario">
			<?php echo $n_naosei[$item]; ?> resposta(s) "não sei" neste item.			
			</div><!-- #rp1_sumario -->
			
		</div><!-- #rp1_center -->
		<div class="clear"></div>
		<?php } // FIM LOOPING PARA CADA SUBITEM ?>

		
		</div>
	</div><!-- # questao -->		
	
	

<?php

//**************************************************************************************
//QUESTAO 29

unset($matriz);
// ETAPA 1: Ler todos os valores
$query  = "SELECT question31 FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where . " AND question31 IS NOT NULL";

$rows = $connection->GetAllResults($query);
if(is_array($rows))
{
	$n = 0;
	foreach($rows as $key => $row)
	{
		if(empty($row['question31'])) continue;
		$n++;
		$exploded = explode(";", $row['question31']);
		
		if(is_array($exploded))
		{
			foreach($exploded as $key2 => $value)
			{
				$matriz[$key2][] = $value;

			}
		}
	}
}

$n_naosei[0] = 0;
$n_naosei[1] = 0;
// contador "nao sei" item A
if(isset($matriz[1] ))
foreach($matriz[1] as $m)
{
	if($m == '1') $n_naosei[0]++;
}

// contador "nao sei" item B
$cont_naosei_B = 0;
if(isset($matriz[2] ))
foreach($matriz[3] as $m)
{
	if($m == '3') $n_naosei[1]++;}

// extrator de valores item A
$n_itens[0] = 0;
$n_itens[1] = 0;
$matriz_valores = "";
if(isset($matriz[0] )) 
foreach($matriz[0] as $m)
{
	if($m != "") 
	{
		$matriz_valores[0][] = $m;
		$n_itens[0]++;
	}
}

// extrator de valores item B
if(isset($matriz[2])) 
foreach($matriz[2] as $m)
{
	if($m != "") 
	{
		$matriz_valores[1][] = $m;
		$n_itens[1]++;
	}
}

unset($matriz);
$matriz = $matriz_valores;
unset($matriz_valores);

// ETAPA 2: calcular média, sd, max_value para cada item (de A a B) dentro da questão, e montar matriz de labels (eixo-x) e valores (eixo-y)
$mean = "";
$sd = "";
$max_value = "";
$max_x = 20;
$datay = "";
$datax = "";

if(is_array($matriz))
foreach($matriz as $item => $m)
{
	$max_value[$item] = 0;

	if($n_itens[$item] > 1)
	{
		// contador para cálculo da média
		foreach($m as $key2 => $subitem)
		{
			$mean[$item] += $subitem;
			if($max_value[$item] < $subitem) $max_value[$item] = $subitem;
		}
		$mean[$item] = number_format($mean[$item] / $n, 2, ",", "");
		
		// cálculo do desvio-padrão
		$sd[$item] = number_format(sd($m), 2, ",", "");
	}
	else // apenas 1 item
	{
		$mean[$item] = $m[0];
		$sd[$item] = 0;
		$max_value[$item] = $m[0];
	}

	// cálculo do número de itens a serem agrupados em cada label do eixo-x	
	$n_per_group[$item] = ($max_value[$item] > $max_x) ? parte_inteira(((float)$max_value[$item] / (float)$max_x), DS) : 1;
	if(($n_per_group[$item]*($max_x + 1)-1) < $max_value[$item]) $n_per_group[$item]++;
	
	// criação de datax (labels eixo-x)
	$datax[$item] = "";
	for($i=0; $i<($max_x+1); $i++)
	{
		if($n_per_group[$item] == 1) $datax[$item][] = $i;
		else $datax[$item][] = ($i*$n_per_group[$item] ) . "-" . (($i+1) * $n_per_group[$item] - 1);
	}

	// criação da matriz datay (valores)
	$datay[$item] = array_fill(0, $max_x + 1, 0);
	foreach($m as $key2 => $subitem)
	{
		$local = find_group($subitem, $n_per_group[$item]);
		$datay[$item][$local]++;
	}		
}

// ETAPA 3: definir títulos de cada subitem
$titulos[0] = "A. Procedimentos diagnósticos";
$titulos[1] = "B. Procedimentos com FNA";



?>
	<div class="questao" id="q29">
		<div class="enunciado exp_heading"><?php echo __("29. Qual percentagem aproximada de seus procedimentos ecoendoscopicos sao reembolsados por programas de saude governamentais ou filantropicos?");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		
		
		
		<?php
		// LOOPING PARA CADA SUBITEM DESTA QUESTÃO
		for($item=0; $item<2; $item++)
		{
			echo '<div class="enunciado">' . $titulos[$item] . '</div><div class="clear"></div>';
			echo '<div class="rp1_center">';
			if(isset($datay[$item]) && isset($datax[$item]))
			{
	
			$datay_row = urlencode(htmlentities(serialize($datay[$item])));
			$datax_row = urlencode(htmlentities(serialize($datax[$item])));
			$titley = urlencode(htmlentities("quantidade de marcações"));
			$titlex = urlencode(htmlentities("percentual digitado"));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("250"));
			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=verticalbar&output=JPGRAPH&x-data=$datax_row&y-data=$datay_row&x-title=$titlex&y-title=$titley&width=$width&height=$height"?>" />

			<div class="rp1_sumario">N = <?php echo $n_itens[$item]; ?> &nbsp;&nbsp;&nbsp;&nbsp; MÉDIA = <?php echo $mean[$item]; ?> &nbsp;&nbsp;&nbsp;&nbsp; DESVIO-PADRÃO = <?php echo $sd[$item]; ?>
			</div><!-- #rp1_sumario -->
			<br>
			
			<?php 
			}
			else
			{
				
			}
			?>
			<div class="rp1_sumario">
			<?php echo $n_naosei[$item]; ?> resposta(s) "não sei" neste item.			
			</div><!-- #rp1_sumario -->
				
		</div><!-- #rp1_center -->
		<div class="clear"></div>
		<?php } // FIM LOOPING PARA CADA SUBITEM ?>

		
		</div>
	</div><!-- # questao -->			
	
<?php

//**************************************************************************************
//QUESTAO 30

unset($matriz);
unset($datax);
unset($datay);
unset($min_value);
unset($max_value);
unset($max_x);
unset($valor_inicial_x);

// ETAPA 1: Ler todos os valores
$query  = "SELECT question32 FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where . " AND question32 IS NOT NULL";

$rows = $connection->GetAllResults($query);
if(is_array($rows))
{
	$matriz = "";
	$n = 0;
	foreach($rows as $key => $row)
	{
		if($row['question32'] == '') continue;
		$n++;
		$matriz[0][] = (float)$row['question32'];
	}
}

// ETAPA 2: calcular média, sd, max_value para a questão 31, e montar matriz de labels (eixo-x) e valores (eixo-y)
$mean = "";
$sd = "";
$datay = "";
$datax = "";
if(isset($matriz) && is_array($matriz))
foreach($matriz as $item => $m)
{
	$max_value[$item] = 0;
	$min_value[$item] = 0;
	
	if($n > 1)
	{
		// contador para cálculo da média
		foreach($m as $key2 => $subitem)
		{
			$mean[$item] += $subitem;
			if($max_value[$item] < $subitem) $max_value[$item] = (int)$subitem + 1;
			if(($min_value[$item] > $subitem) || ($min_value[$item] == 0)) $min_value[$item] = (int)$subitem;
		}
		$mean[$item] = number_format($mean[$item] / $n, 2, ",", "");
		
		// cálculo do desvio-padrão
		$sd[$item] = number_format(sd($m), 2, ",", "");
	}
	else // apenas 1 item
	{
		$mean[$item] = $m[0];
		$sd[$item] = 0;
		$max_value[$item] = (int)$m[0] + 1;
		$min_value[$item] = $max_value[$item] - 10;
	}
	
	if($min_value[$item] < 0) $min_value[$item] = 0;
	$max_x[$item] = 12;
	$valor_inicial_x[$item] = $min_value[$item] - 10;
	if($valor_inicial_x[$item] < 0) $valor_inicial_x[$item] = 0;
	
	// cálculo do número de itens a serem agrupados em cada label do eixo-x	
	$n_per_group[$item] = ($max_value[$item] > ($max_x[$item]+$valor_inicial_x[$item])) ? parte_inteira(((float)($max_value[$item] - $valor_inicial_x[$item])) / ((float)$max_x[$item]), DS) : 1;
	if(($n_per_group[$item]*($max_x[$item] + 1)-1) < $max_value[$item]) $n_per_group[$item]++;
	
	//echo "max_value: " . $max_value[$item] . "<br>";
	//echo "n_per_group: " . $n_per_group[$item]; 
	
	// criação de datax (labels eixo-x)
	$datax[$item] = "";
	for($i=0; $i<($max_x[$item]+1); $i++)
	{
		if($n_per_group[$item] == 1) $datax[$item][] = $i;
		else $datax[$item][] = ($i*$n_per_group[$item] + $valor_inicial_x[$item]) . "-" . (($i+1) * $n_per_group[$item] + $valor_inicial_x[$item] - 1);
	}
	//print_array($datax[$item]);

	// criação da matriz datay (valores)
	$datay[$item] = array_fill(0, $max_x[$item], 0);
	foreach($m as $key2 => $subitem)
	{
		$local = find_group($subitem, $n_per_group[$item], $valor_inicial_x[$item]);
		$local = round($local,2);
		$datay[$item][$local]++;
	}

}

// ETAPA 3: definir títulos de cada subitem -- NÃO SE APLICA A ESTA QUESTÃO PORQUE ELA NÃO POSSUI SUBITENS
//$titulos[0] = "item único";


?>
	<div class="questao" id="q30">
		<div class="enunciado exp_heading"><?php echo __("30. Qual e o custo medio aproximado da EUS (particular) em seu pais? (em dolar- taxa de cambio oficial) ");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		
		
		
		<?php
		// LOOPING PARA CADA SUBITEM DESTA QUESTÃO
		for($item=0; $item<1; $item++)
		{
			//echo '<div class="enunciado">' . $titulos[$item] . '</div><div class="clear"></div>';
			echo '<div class="rp1_center">';
			
			if(isset($datay[$item]) && isset($datax[$item]))
			{
				
			
			$datay_row = urlencode(htmlentities(serialize($datay[$item])));
			$datax_row = urlencode(htmlentities(serialize($datax[$item])));
			$titley = urlencode(htmlentities("quantidade de marcações"));
			$titlex = urlencode(htmlentities("valor US$"));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("250"));
			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=verticalbar&output=JPGRAPH&x-data=$datax_row&y-data=$datay_row&x-title=$titlex&y-title=$titley&width=$width&height=$height"?>" />

			<div class="rp1_sumario">N = <?php echo $n; ?> &nbsp;&nbsp;&nbsp;&nbsp; MÉDIA = <?php echo $mean[$item]; ?> &nbsp;&nbsp;&nbsp;&nbsp; DESVIO-PADRÃO = <?php echo $sd[$item]; ?><br>Ignorando os centavos para plotagem do gráfico.</div><!-- #rp1_sumario -->
			<?php 
			}
			else
			{
				echo REPORT_MSG_EMPTY_VALUES;
			}
			?>
		</div><!-- #rp1_center -->
		<div class="clear"></div>
		<?php } // FIM LOOPING PARA CADA SUBITEM ?>

		</div>
		
	</div><!-- # questao -->

	
	<?php

//**************************************************************************************
//QUESTAO 31

unset($matriz);
unset($datax);
unset($datay);
unset($min_value);
unset($max_value);
unset($max_x);
unset($valor_inicial_x);

// ETAPA 1: Ler todos os valores
$query  = "SELECT question33 FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where . " AND question33 IS NOT NULL";

$rows = $connection->GetAllResults($query);
if(is_array($rows))
{
	$matriz = "";
	$n = 0;
	foreach($rows as $key => $row)
	{
		if($row['question33'] == '') continue;
		$n++;
		$matriz[0][] = (float)$row['question33'];
	}
}

// ETAPA 2: calcular média, sd, max_value para a questão 31, e montar matriz de labels (eixo-x) e valores (eixo-y)
$mean = "";
$sd = "";
$datay = "";
$datax = "";
if(isset($matriz)&& is_array($matriz))
foreach($matriz as $item => $m)
{
	$max_value[$item] = 0;
	$min_value[$item] = 0;
	
	if($n > 1)
	{
		// contador para cálculo da média
		foreach($m as $key2 => $subitem)
		{
			$mean[$item] += $subitem;
			if($max_value[$item] < $subitem) $max_value[$item] = (int)$subitem + 1;
			if(($min_value[$item] > $subitem) || ($min_value[$item] == 0)) $min_value[$item] = (int)$subitem;
		}
		$mean[$item] = number_format($mean[$item] / $n, 2, ",", "");
		
		// cálculo do desvio-padrão
		$sd[$item] = number_format(sd($m), 2, ",", "");
	}
	else // apenas 1 item
	{
		$mean[$item] = $m[0];
		$sd[$item] = 0;
		$max_value[$item] = (int)$m[0] + 1;
		$min_value[$item] = $max_value[$item] - 10;
	}
	
	if($min_value[$item] < 0) $min_value[$item] = 0;
	$max_x[$item] = 12;
	$valor_inicial_x[$item] = $min_value[$item] - 10;
	if($valor_inicial_x[$item] < 0) $valor_inicial_x[$item] = 0;
	
	// cálculo do número de itens a serem agrupados em cada label do eixo-x	
	$n_per_group[$item] = ($max_value[$item] > ($max_x[$item]+$valor_inicial_x[$item])) ? parte_inteira(((float)($max_value[$item] - $valor_inicial_x[$item])) / ((float)$max_x[$item]), DS) : 1;
	if(($n_per_group[$item]*($max_x[$item] + 1)-1) < $max_value[$item]) $n_per_group[$item]++;
	
	//echo "max_value: " . $max_value[$item] . "<br>";
	//echo "n_per_group: " . $n_per_group[$item]; 
	
	// criação de datax (labels eixo-x)
	$datax[$item] = "";
	for($i=0; $i<($max_x[$item]+1); $i++)
	{
		if($n_per_group[$item] == 1) $datax[$item][] = $i;
		else $datax[$item][] = ($i*$n_per_group[$item] + $valor_inicial_x[$item]) . "-" . (($i+1) * $n_per_group[$item] + $valor_inicial_x[$item] - 1);
	}
	//print_array($datax[$item]);

	// criação da matriz datay (valores)
	$datay[$item] = array_fill(0, $max_x[$item], 0);
	foreach($m as $key2 => $subitem)
	{
		$local = find_group($subitem, $n_per_group[$item], $valor_inicial_x[$item]);
		$local = round($local,2);
		$datay[$item][$local]++;
	}

}

// ETAPA 3: definir títulos de cada subitem -- NÃO SE APLICA A ESTA QUESTÃO PORQUE ELA NÃO POSSUI SUBITENS
//$titulos[0] = "item único";


?>
	<div class="questao" id="q31">
		<div class="enunciado exp_heading"><?php echo __("31. Qual e o custo medio aproximado da EUS (particular) com FNA  em seu pais? (em dolar- taxa de cambio oficial) ");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		
		
		
		<?php
		// LOOPING PARA CADA SUBITEM DESTA QUESTÃO
		for($item=0; $item<1; $item++)
		{
			//echo '<div class="enunciado">' . $titulos[$item] . '</div><div class="clear"></div>';
			echo '<div class="rp1_center">';

			if(isset($datay[$item]) && isset($datax[$item]))
			{
				
			
			$datay_row = urlencode(htmlentities(serialize($datay[$item])));
			$datax_row = urlencode(htmlentities(serialize($datax[$item])));
			$titley = urlencode(htmlentities("quantidade de marcações"));
			$titlex = urlencode(htmlentities("valor US$"));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("250"));
			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=verticalbar&output=JPGRAPH&x-data=$datax_row&y-data=$datay_row&x-title=$titlex&y-title=$titley&width=$width&height=$height"?>" />

			<div class="rp1_sumario">N = <?php echo $n; ?> &nbsp;&nbsp;&nbsp;&nbsp; MÉDIA = <?php echo $mean[$item]; ?> &nbsp;&nbsp;&nbsp;&nbsp; DESVIO-PADRÃO = <?php echo $sd[$item]; ?><br>Ignorando os centavos para plotagem do gráfico.</div><!-- #rp1_sumario -->
			<?php 
			}
			else
			{
				echo REPORT_MSG_EMPTY_VALUES;
			}
			?>
		</div><!-- #rp1_center -->
		<div class="clear"></div>
		<?php } // FIM LOOPING PARA CADA SUBITEM ?>

		
		</div>
	</div><!-- # questao -->
	
	
	
	<?php

//**************************************************************************************
//QUESTAO 32

unset($matriz);
unset($datax);
unset($datay);
unset($min_value);
unset($max_value);
unset($max_x);
unset($valor_inicial_x);

// ETAPA 1: Ler todos os valores
$query  = "SELECT question34 FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where . " AND question34 IS NOT NULL";

$rows = $connection->GetAllResults($query);
$flag_stop = true;

if(is_array($rows))
{
	$matriz = "";
	$n = 0;
	
	foreach($rows as $key => $row)
	{
		if($row['question34'] == '') continue;
		$n++;
		$flag_stop = false;
		$matriz[0][] = (float)$row['question34'];
	}

	if(!$flag_stop)
	{
		
		// ETAPA 2: calcular média, sd, max_value para a questão 32, e montar matriz de labels (eixo-x) e valores (eixo-y)
		$mean = "";
		$sd = "";
		$datay = "";
		$datax = "";
		foreach($matriz as $item => $m)
		{
			$max_value[$item] = 0;
			$min_value[$item] = 0;
			
			if($n > 1)
			{
				// contador para cálculo da média
				foreach($m as $key2 => $subitem)
				{
					$mean[$item] += $subitem;
					if($max_value[$item] < $subitem) $max_value[$item] = (int)$subitem + 1;
					if(($min_value[$item] > $subitem) || ($min_value[$item] == 0)) $min_value[$item] = (int)$subitem;
				}
				$mean[$item] = number_format($mean[$item] / $n, 2, ",", "");

				// cálculo do desvio-padrão
				$sd[$item] = number_format(sd($m), 2, ",", "");
			}
			else // apenas 1 item
			{
				$mean[$item] = $m[0];
				$sd[$item] = 0;
				$max_value[$item] = (int)$m[0] + 1;
				$min_value[$item] = $max_value[$item] - 10;
			}
			
			if($min_value[$item] < 0) $min_value[$item] = 0;
			$max_x[$item] = 12;
			$valor_inicial_x[$item] = $min_value[$item] - 10;
			if($valor_inicial_x[$item] < 0) $valor_inicial_x[$item] = 0;
			
			// cálculo do número de itens a serem agrupados em cada label do eixo-x	
			$n_per_group[$item] = ($max_value[$item] > ($max_x[$item]+$valor_inicial_x[$item])) ? parte_inteira(((float)($max_value[$item] - $valor_inicial_x[$item])) / ((float)$max_x[$item]), DS) : 1;
			if(($n_per_group[$item]*($max_x[$item] + 1)-1) < $max_value[$item]) $n_per_group[$item]++;
			
			//echo "max_value: " . $max_value[$item] . "<br>";
			//echo "n_per_group: " . $n_per_group[$item]; 
			
			// criação de datax (labels eixo-x)
			$datax[$item] = "";
			for($i=0; $i<($max_x[$item]+1); $i++)
			{
				if($n_per_group[$item] == 1) $datax[$item][] = $i;
				else $datax[$item][] = ($i*$n_per_group[$item] + $valor_inicial_x[$item]) . "-" . (($i+1) * $n_per_group[$item] + $valor_inicial_x[$item] - 1);
			}
			//print_array($datax[$item]);

			// criação da matriz datay (valores)
			$datay[$item] = array_fill(0, $max_x[$item], 0);
			foreach($m as $key2 => $subitem)
			{
				$local = find_group($subitem, $n_per_group[$item], $valor_inicial_x[$item]);
				$local = round($local,2);
				$datay[$item][$local]++;
			}

		}
	} // end if(!flag_stop)
} // end is(is_array(rows))
// ETAPA 3: definir títulos de cada subitem -- NÃO SE APLICA A ESTA QUESTÃO PORQUE ELA NÃO POSSUI SUBITENS
//$titulos[0] = "item único";


?>
	<div class="questao" id="q32">
		<div class="enunciado exp_heading"><?php echo __("32. Qual e o custo medio aproximado de venda de uma agulha de EUS em seu pais? (em dolar- taxa de cambio oficial) ");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		
		
		
		<?php
		if($flag_stop)
		{
			?>
			<div class="rp1_sumario">Nenhum dado para plotar o gráfico (N = 0).<br/><br/></div><!-- #rp1_sumario -->
		</div><!-- #rp1_center -->
		<div class="clear"></div>
			<?php
		}
		else
		{
		
		// LOOPING PARA CADA SUBITEM DESTA QUESTÃO
		for($item=0; $item<1; $item++)
		{
			//echo '<div class="enunciado">' . $titulos[$item] . '</div><div class="clear"></div>';
			echo '<div class="rp1_center">';
			
			if(isset($datay[$item]) && isset($datax[$item]))
			{
				
			
			$datay_row = urlencode(htmlentities(serialize($datay[$item])));
			$datax_row = urlencode(htmlentities(serialize($datax[$item])));
			$titley = urlencode(htmlentities("quantidade de marcações"));
			$titlex = urlencode(htmlentities("valor US$"));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("250"));
			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=verticalbar&output=JPGRAPH&x-data=$datax_row&y-data=$datay_row&x-title=$titlex&y-title=$titley&width=$width&height=$height"?>" />

			<div class="rp1_sumario">N = <?php echo $n; ?> &nbsp;&nbsp;&nbsp;&nbsp; MÉDIA = <?php echo $mean[$item]; ?> &nbsp;&nbsp;&nbsp;&nbsp; DESVIO-PADRÃO = <?php echo $sd[$item]; ?><br>Ignorando os centavos para plotagem do gráfico.</div><!-- #rp1_sumario -->
			
			<?php 
			}
			else
			{
				echo REPORT_MSG_EMPTY_VALUES;
			}
			?>
			
		</div><!-- #rp1_center -->
		<div class="clear"></div>
		<?php } } // FIM LOOPING PARA CADA SUBITEM ?>

		
		</div>
	</div><!-- # questao -->	
	
	
	<?php

//**************************************************************************************
//QUESTAO 33

unset($matriz);
unset($datax);
unset($datay);
unset($min_value);
unset($max_value);
unset($max_x);
unset($valor_inicial_x);

// ETAPA 1: Ler todos os valores
$query  = "SELECT question35 FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where . " AND question35 IS NOT NULL";
$flag_stop = true;
$rows = $connection->GetAllResults($query);
if(is_array($rows))
{
	$matriz = "";
	$n = 0;
	
	foreach($rows as $key => $row)
	{
		if($row['question35'] == '') continue;
		$n++;
		$flag_stop = false;
		$matriz[0][] = (float)$row['question35'];
	}

	if(!$flag_stop)
	{
		
		// ETAPA 2: calcular média, sd, max_value para a questão 33, e montar matriz de labels (eixo-x) e valores (eixo-y)
		$mean = "";
		$sd = "";
		$datay = "";
		$datax = "";
		foreach($matriz as $item => $m)
		{
			$max_value[$item] = 0;
			$min_value[$item] = 0;
			
			if($n > 1)
			{
				// contador para cálculo da média
				foreach($m as $key2 => $subitem)
				{
					$mean[$item] += $subitem;
					if($max_value[$item] < $subitem) $max_value[$item] = (int)$subitem + 1;
					if(($min_value[$item] > $subitem) || ($min_value[$item] == 0)) $min_value[$item] = (int)$subitem;
				}
				$mean[$item] = number_format($mean[$item] / $n, 2, ",", "");

				// cálculo do desvio-padrão
				$sd[$item] = number_format(sd($m), 2, ",", "");
			}
			else // apenas 1 item
			{
				$mean[$item] = $m[0];
				$sd[$item] = 0;
				$max_value[$item] = (int)$m[0] + 1;
				$min_value[$item] = $max_value[$item] - 10;
			}
			
			if($min_value[$item] < 0) $min_value[$item] = 0;
			$max_x[$item] = 12;
			$valor_inicial_x[$item] = $min_value[$item] - 10;
			if($valor_inicial_x[$item] < 0) $valor_inicial_x[$item] = 0;
			
			// cálculo do número de itens a serem agrupados em cada label do eixo-x	
			$n_per_group[$item] = ($max_value[$item] > ($max_x[$item]+$valor_inicial_x[$item])) ? parte_inteira(((float)($max_value[$item] - $valor_inicial_x[$item])) / ((float)$max_x[$item]), DS) : 1;
			if(($n_per_group[$item]*($max_x[$item] + 1)-1) < $max_value[$item]) $n_per_group[$item]++;
			
			//echo "max_value: " . $max_value[$item] . "<br>";
			//echo "n_per_group: " . $n_per_group[$item]; 
			
			// criação de datax (labels eixo-x)
			$datax[$item] = "";
			for($i=0; $i<($max_x[$item]+1); $i++)
			{
				if($n_per_group[$item] == 1) $datax[$item][] = $i;
				else $datax[$item][] = ($i*$n_per_group[$item] + $valor_inicial_x[$item]) . "-" . (($i+1) * $n_per_group[$item] + $valor_inicial_x[$item] - 1);
			}
			//print_array($datax[$item]);

			// criação da matriz datay (valores)
			$datay[$item] = array_fill(0, $max_x[$item], 0);
			foreach($m as $key2 => $subitem)
			{
				$local = find_group($subitem, $n_per_group[$item], $valor_inicial_x[$item]);
						$local = round($local,2);
				$datay[$item][$local]++;
			}

		}
	} // end if(!flag_stop)
} // end is(is_array(rows))
// ETAPA 3: definir títulos de cada subitem -- NÃO SE APLICA A ESTA QUESTÃO PORQUE ELA NÃO POSSUI SUBITENS
//$titulos[0] = "item único";


?>
	<div class="questao" id="q33">
		<div class="enunciado exp_heading"><?php echo __("33. Qual e o honorario medio aproximado, destinado ao medico, para a realizacao de uma EUS sem puncao em seu pais? (em dolar- taxa de cambio oficial) ");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		
		
		
		<?php
		if($flag_stop)
		{
			?>
			<div class="rp1_sumario">Nenhum dado para plotar o gráfico (N = 0).<br/><br/></div><!-- #rp1_sumario -->
		</div><!-- #rp1_center -->
		<div class="clear"></div>
			<?php
		}
		else
		{
		
		// LOOPING PARA CADA SUBITEM DESTA QUESTÃO
		for($item=0; $item<1; $item++)
		{
			//echo '<div class="enunciado">' . $titulos[$item] . '</div><div class="clear"></div>';
			echo '<div class="rp1_center">';
			
			$datay_row = urlencode(htmlentities(serialize($datay[$item])));
			$datax_row = urlencode(htmlentities(serialize($datax[$item])));
			$titley = urlencode(htmlentities("quantidade de marcações"));
			$titlex = urlencode(htmlentities("valor US$"));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("250"));
			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=verticalbar&output=JPGRAPH&x-data=$datax_row&y-data=$datay_row&x-title=$titlex&y-title=$titley&width=$width&height=$height"?>" />

			<div class="rp1_sumario">N = <?php echo $n; ?> &nbsp;&nbsp;&nbsp;&nbsp; MÉDIA = <?php echo $mean[$item]; ?> &nbsp;&nbsp;&nbsp;&nbsp; DESVIO-PADRÃO = <?php echo $sd[$item]; ?><br>Ignorando os centavos para plotagem do gráfico.</div><!-- #rp1_sumario -->
		</div><!-- #rp1_center -->
		<div class="clear"></div>
		<?php } } // FIM LOOPING PARA CADA SUBITEM ?>

		
		</div>
	</div><!-- # questao -->	
	
	<?php

//**************************************************************************************
//QUESTAO 34

unset($matriz);
unset($datax);
unset($datay);
unset($min_value);
unset($max_value);
unset($max_x);
unset($valor_inicial_x);

// ETAPA 1: Ler todos os valores
$query  = "SELECT question36 FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where . " AND question36 IS NOT NULL";
$flag_stop = true;
$rows = $connection->GetAllResults($query);
if(is_array($rows))
{
	$matriz = "";
	$n = 0;
	$flag_stop = true;
	foreach($rows as $key => $row)
	{
		if($row['question36'] == '') continue;
		$n++;
		$flag_stop = false;
		$matriz[0][] = (float)$row['question36'];
	}

	if(!$flag_stop)
	{
		
		// ETAPA 2: calcular média, sd, max_value para a questão 34, e montar matriz de labels (eixo-x) e valores (eixo-y)
		$mean = "";
		$sd = "";
		$datay = "";
		$datax = "";
		foreach($matriz as $item => $m)
		{
			$max_value[$item] = 0;
			$min_value[$item] = 0;
			
			if($n > 1)
			{
				// contador para cálculo da média
				foreach($m as $key2 => $subitem)
				{
					$mean[$item] += $subitem;
					if($max_value[$item] < $subitem) $max_value[$item] = (int)$subitem + 1;
					if(($min_value[$item] > $subitem) || ($min_value[$item] == 0)) $min_value[$item] = (int)$subitem;
				}
				$mean[$item] = number_format($mean[$item] / $n, 2, ",", "");

				// cálculo do desvio-padrão
				$sd[$item] = number_format(sd($m), 2, ",", "");
			}
			else // apenas 1 item
			{
				$mean[$item] = $m[0];
				$sd[$item] = 0;
				$max_value[$item] = (int)$m[0] + 1;
				$min_value[$item] = $max_value[$item] - 10;
			}
			
			if($min_value[$item] < 0) $min_value[$item] = 0;
			$max_x[$item] = 12;
			$valor_inicial_x[$item] = $min_value[$item] - 10;
			if($valor_inicial_x[$item] < 0) $valor_inicial_x[$item] = 0;
			
			// cálculo do número de itens a serem agrupados em cada label do eixo-x	
			$n_per_group[$item] = ($max_value[$item] > ($max_x[$item]+$valor_inicial_x[$item])) ? parte_inteira(((float)($max_value[$item] - $valor_inicial_x[$item])) / ((float)$max_x[$item]), DS) : 1;
			if(($n_per_group[$item]*($max_x[$item] + 1)-1) < $max_value[$item]) $n_per_group[$item]++;
			
			//echo "max_value: " . $max_value[$item] . "<br>";
			//echo "n_per_group: " . $n_per_group[$item]; 
			
			// criação de datax (labels eixo-x)
			$datax[$item] = "";
			for($i=0; $i<($max_x[$item]+1); $i++)
			{
				if($n_per_group[$item] == 1) $datax[$item][] = $i;
				else $datax[$item][] = ($i*$n_per_group[$item] + $valor_inicial_x[$item]) . "-" . (($i+1) * $n_per_group[$item] + $valor_inicial_x[$item] - 1);
			}
			//print_array($datax[$item]);

			// criação da matriz datay (valores)
			$datay[$item] = array_fill(0, $max_x[$item], 0);
			foreach($m as $key2 => $subitem)
			{
				$local = find_group($subitem, $n_per_group[$item], $valor_inicial_x[$item]);
						$local = round($local,2);
				$datay[$item][$local]++;
			}

		}
	} // end if(!flag_stop)
} // end is(is_array(rows))
// ETAPA 3: definir títulos de cada subitem -- NÃO SE APLICA A ESTA QUESTÃO PORQUE ELA NÃO POSSUI SUBITENS
//$titulos[0] = "item único";


?>
	<div class="questao" id="q34">
		<div class="enunciado exp_heading"><?php echo __("34. Qual e o honorario medio aproximado, destinado ao medico, para a realizacao de uma EUS com FNA em seu pais? (em dolar- taxa de cambio oficial) ");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		
		
		
		<?php
		if($flag_stop)
		{
			?>
			<div class="rp1_sumario">Nenhum dado para plotar o gráfico (N = 0).<br/><br/></div><!-- #rp1_sumario -->
		</div><!-- #rp1_center -->
		<div class="clear"></div>
			<?php
		}
		else
		{
		
		// LOOPING PARA CADA SUBITEM DESTA QUESTÃO
		for($item=0; $item<1; $item++)
		{
			//echo '<div class="enunciado">' . $titulos[$item] . '</div><div class="clear"></div>';
			echo '<div class="rp1_center">';
			
			$datay_row = urlencode(htmlentities(serialize($datay[$item])));
			$datax_row = urlencode(htmlentities(serialize($datax[$item])));
			$titley = urlencode(htmlentities("quantidade de marcações"));
			$titlex = urlencode(htmlentities("valor US$"));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("250"));
			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=verticalbar&output=JPGRAPH&x-data=$datax_row&y-data=$datay_row&x-title=$titlex&y-title=$titley&width=$width&height=$height"?>" />

			<div class="rp1_sumario">N = <?php echo $n; ?> &nbsp;&nbsp;&nbsp;&nbsp; MÉDIA = <?php echo $mean[$item]; ?> &nbsp;&nbsp;&nbsp;&nbsp; DESVIO-PADRÃO = <?php echo $sd[$item]; ?><br>Ignorando os centavos para plotagem do gráfico.</div><!-- #rp1_sumario -->
		</div><!-- #rp1_center -->
		<div class="clear"></div>
		<?php } } // FIM LOOPING PARA CADA SUBITEM ?>

		
		</div>
	</div><!-- # questao -->	