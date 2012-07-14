<?php

//**************************************************************************************
//QUESTAO 9

unset($matriz);
unset($datax);
unset($datay);
unset($min_value);
unset($max_value);
unset($max_x);
unset($valor_inicial_x);

// ETAPA 1: Ler todos os valores
$query  = "SELECT question11 FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where . " AND question11 IS NOT NULL";
$flag_stop = true;
$rows = $connection->GetAllResults($query);
if(is_array($rows))
{
	$matriz = "";
	$n = 0;
	$flag_stop = true;
	foreach($rows as $key => $row)
	{
		if($row['question11'] == '') continue;
		$n++;
		$flag_stop = false;
		$matriz[0][] = (float)$row['question11'];
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
			
			/*
			echo "max_value[item]: " . $max_value[$item] . "<br>";
			echo "n_per_group[item]: " . $n_per_group[$item] . "<br>"; 
			echo "max_x[item]: " . $max_x[$item] . "<Br>"; 
			echo "valor_inicial_x[item]: " . $valor_inicial_x[$item] . "<Br>"; 
			*/
			
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
			
			//print_array($datay[$item]);

		}
	} // end if(!flag_stop)
} // end is(is_array(rows))
// ETAPA 3: definir títulos de cada subitem -- NÃO SE APLICA A ESTA QUESTÃO PORQUE ELA NÃO POSSUI SUBITENS
//$titulos[0] = "item único";


?>
	<div class="questao" id="q9">
		<div class="enunciado exp_heading"><?php echo __("9. Ha quantos anos voce  ja realiza EUS sem a supervisao de outro endossonografista?");?><a class="seletor_toggle">[+]</a></div>
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
			$titlex = urlencode(htmlentities("anos"));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("250"));
			
			//echo "<br>=======================================<br>" . URL."/control_panel/reports_survey_answers_graph.php?type=verticalbar&output=JPGRAPH&x-data=$datax_row&y-data=$datay_row&x-title=$titlex&y-title=$titley&width=$width&height=$height";
			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=verticalbar&output=JPGRAPH&x-data=$datax_row&y-data=$datay_row&x-title=$titlex&y-title=$titley&width=$width&height=$height"?>" />

			<div class="rp1_sumario">N = <?php echo $n; ?> &nbsp;&nbsp;&nbsp;&nbsp; MÉDIA = <?php echo $mean[$item]; ?> &nbsp;&nbsp;&nbsp;&nbsp; DESVIO-PADRÃO = <?php echo $sd[$item]; ?></div><!-- #rp1_sumario -->
		</div><!-- #rp1_center -->
		<div class="clear"></div>
		<?php } } // FIM LOOPING PARA CADA SUBITEM ?>

		
		</div>

	</div><!-- # questao -->
	<?php 
//**************************************************************************************
//QUESTAO 10

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question12 = 0";
$list_a = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question12 = 1";
$list_b = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question12 = 2";
$list_c = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question12 = 3";
$list_d = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question12 = 4";
$list_e = $connection->GetResult($query);


$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question12 = 5";
$list_f = $connection->GetResult($query);


$total = $list_a['total'] + $list_b['total'] + $list_c['total'] + $list_d['total'] + $list_e['total'] + $list_f['total'];

$porcento_a = ($total > 0)?number_format((($list_a['total']/$total)*100),1,",",""):0;
$porcento_b = ($total > 0)?number_format((($list_b['total']/$total)*100),1,",",""):0;
$porcento_c = ($total > 0)?number_format((($list_c['total']/$total)*100),1,",",""):0;
$porcento_d = ($total > 0)?number_format((($list_d['total']/$total)*100),1,",",""):0;
$porcento_e = ($total > 0)?number_format((($list_e['total']/$total)*100),1,",",""):0;
$porcento_f = ($total > 0)?number_format((($list_f['total']/$total)*100),1,",",""):0;


?>

	<div class="questao" id="q10">
		<div class="enunciado exp_heading"><?php echo __("10. Quantas EUS voce  ja realizou sem a supervisao de outro endossonografista?");?><a class="seletor_toggle">[+]</a></div>
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
					<th><?php echo __(" <= 100");?></th>
					<td><?php echo $list_a['total']; ?></td>
					<td><?php echo ($porcento_a);?>%</td>
				</tr>
				<tr>
					<th><?php echo __(" 101-250");?></th>
					<td><?php echo $list_b['total']; ?></td>
					<td><?php echo ($porcento_b);?>%</td>
				</tr>
				<tr>
					<th><?php echo __(" 251-500");?></th>
					<td><?php echo $list_c['total']; ?></td>
					<td><?php echo $porcento_c;?>%</td>
				</tr>
				<tr>
					<th><?php echo __(" 501-1000");?></th>
					<td><?php echo $list_d['total']; ?></td>
					<td><?php echo $porcento_d;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __(" 1001-5000");?></th>
					<td><?php echo $list_e['total']; ?></td>
					<td><?php echo $porcento_e;?>%</td>
				</tr>	
				<tr>
					<th><?php echo __(" > 5000");?></th>
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
			$leg = urlencode(htmlentities(serialize(array(__(" <= 100"), __(" 101-250"), __(" 251-500"),__(" 501-1000"),__(" 1001-5000"),__(" > 5000")))));

			
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
//QUESTAO 11

// ETAPA 1: Ler todos os valores
$query  = "SELECT question13 FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where." AND question13 is not null";

$rows = $connection->GetAllResults($query);
if(is_array($rows))
{
	$matriz = "";
	$n = 0;
	foreach($rows as $key => $row)
	{
		if($row['question13'] == "") continue;
		$n++;
		$exploded = explode(";", $row['question13']);
		if(is_array($exploded))
		{
			foreach($exploded as $key2 => $value)
			{
				$matriz[$key2][] = (int)$value;
			}
		}
	}
}

// ETAPA 2: calcular média, sd, max_value para cada item (de A a F) dentro da questão 11, e montar matriz de labels (eixo-x) e valores (eixo-y)
$mean = "";
$sd = "";
$max_value = "";
$max_x = 12;
$datay = "";
$datax = "";
if(isset($matriz))
{
	foreach($matriz as $item => $m)
	{
		$max_value[$item] = 0;
		$mean[$item] = 0;
		
		
		if($n > 1)
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
		
		
		//echo "<bR>================================================================<bR>";	
		
		// criação de datax (labels eixo-x)
		$datax[$item] = "";
		for($i=0; $i<($max_x+1); $i++)
		{
			if($n_per_group[$item] == 1) $datax[$item][] = $i;
			else $datax[$item][] = ($i*$n_per_group[$item] ) . "-" . (($i+1) * $n_per_group[$item] - 1);
			
			//echo "<br>item no eixo-X: " . ($i*$n_per_group[$item] ) . "-" . (($i+1) * $n_per_group[$item] - 1) . " |||| Valor de i: $i |||| Valor de n_per_group[item]: " . $n_per_group[$item] . " |||| valor de item: $item";
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
$titulos[0] = "a. FNA";
$titulos[1] = "b. Neurólise/bloqueio celíaco";
$titulos[2] = "c. Drenagem de pseudocisto";
$titulos[3] = "d. Drenagem de abscessos";
$titulos[4] = "e. Drenagem biliar";
$titulos[5] = "f. Drenagem pancreática";

?>
	<div class="questao" id="q11">
		<div class="enunciado exp_heading"><?php echo __("11. Quantas EUS com FNA e terapeuticas voce ja realizou durante a sua carreira sem a supervisao de outro endossonografista?");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		
		
		
		<?php
		// LOOPING PARA CADA SUBITEM DESTA QUESTÃO
		for($item=0; $item<6; $item++)
		{
			echo '<div class="enunciado">' . $titulos[$item] . '</div><div class="clear"></div>';
			echo '<div class="rp1_center">';
		if(isset($datay[$item]) && isset($datax[$item]) )
		{	
			$datay_row = urlencode(htmlentities(serialize($datay[$item])));
			$datax_row = urlencode(htmlentities(serialize($datax[$item])));
			$titley = urlencode(htmlentities("número de marcações"));
			$titlex = urlencode(htmlentities("quantidade"));
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
//QUESTAO 12

// ETAPA 1: Ler todos os valores
$query  = "SELECT question14 FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where.'AND question14 is not null';

$rows = $connection->GetAllResults($query);
unset($n_item);
$n_item[0] = 0;
$n_item[1] = 0;
if(is_array($rows))
{
	$matriz = "";
	$n = 0;
	foreach($rows as $key => $row)
	{
		$n++;
		$exploded = explode(";", $row['question14']);
		if(is_array($exploded))
		{
			foreach($exploded as $key2 => $value)
			{
				if($value == "") continue;
				$matriz[$key2][] = (int)$value;
				$n_item[$key2]++;
			}
		}
	}
}

// ETAPA 2: calcular média, sd, max_value para cada item (de A a B) dentro da questão 12, e montar matriz de labels (eixo-x) e valores (eixo-y)
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
		$mean[$item] = number_format($mean[$item] / $n_item[$item], 2, ",", "");
		
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
$titulos[0] = "a. EUS alta";
$titulos[1] = "b. EUS baixa";


?>
	<div class="questao" id="q12">
		<div class="enunciado exp_heading"><?php echo __("12. Quantas EUS voce realizou em 2011?");?><a class="seletor_toggle">[+]</a></div>
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
			$titlex = urlencode(htmlentities("quantidade de EUS"));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("250"));
			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=verticalbar&output=JPGRAPH&x-data=$datax_row&y-data=$datay_row&x-title=$titlex&y-title=$titley&width=$width&height=$height"?>" />

			<div class="rp1_sumario">N = <?php echo $n_item[$item]; ?> &nbsp;&nbsp;&nbsp;&nbsp; MÉDIA = <?php echo $mean[$item]; ?> &nbsp;&nbsp;&nbsp;&nbsp; DESVIO-PADRÃO = <?php echo $sd[$item]; ?></div><!-- #rp1_sumario -->
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
//QUESTAO 13

// ETAPA 1: Ler todos os valores
$query  = "SELECT question15 FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where . " AND (question15 IS NOT NULL AND question15 != '')";


$rows = $connection->GetAllResults($query);

unset($n_item);
$n_item[0] = 0;
$n_item[1] = 0;
if(is_array($rows))
{
	$matriz = "";
	$n = 0;
	foreach($rows as $key => $row)
	{
		$n++;
		$exploded = explode(";", $row['question15']);
		if(is_array($exploded))
		{
			foreach($exploded as $key2 => $value)
			{
				if($value == "") continue;
				$matriz[$key2][] = (int)$value;
				$n_item[$key2]++;
			}
		}
	}
}

// ETAPA 2: calcular média, sd, max_value para cada item (de A a B) dentro da questão 13, e montar matriz de labels (eixo-x) e valores (eixo-y)
$mean = "";
$sd = "";
$max_value = "";
$max_x = 20;
$datay = "";
$datax = "";
if(isset($matriz))
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
	$mean[$item] = number_format($mean[$item] / $n_item[$item], 2, ",", "");
	
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

// ETAPA 3: definir títulos de cada subitem
$titulos[0] = "a. Alta";
$titulos[1] = "b. Baixa";


?>
	<div class="questao" id="q13">
		<div class="enunciado exp_heading"><?php echo __("13. Quantas FNA voce realizou em 2011?");?><a class="seletor_toggle">[+]</a></div>
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
			$titlex = urlencode(htmlentities("quantidade de FNA"));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("250"));
			
			?>

			<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=verticalbar&output=JPGRAPH&x-data=$datax_row&y-data=$datay_row&x-title=$titlex&y-title=$titley&width=$width&height=$height"?>" />

			<div class="rp1_sumario">N = <?php echo $n_item[$item]; ?> &nbsp;&nbsp;&nbsp;&nbsp; MÉDIA = <?php echo $mean[$item]; ?> &nbsp;&nbsp;&nbsp;&nbsp; DESVIO-PADRÃO = <?php echo $sd[$item]; ?></div><!-- #rp1_sumario -->
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
//QUESTAO 14

// ETAPA 1: Ler todos os valores
$query  = "SELECT question16 FROM users
		LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where." AND question16 is not null AND question16 != ''";

$rows = $connection->GetAllResults($query);
if(is_array($rows))
{
	$matriz = "";
	$n = 0;
	foreach($rows as $key => $row)
	{
		$n++;
		$exploded = explode(";", $row['question16']);
		if(is_array($exploded))
		{
			foreach($exploded as $key2 => $value)
			{
				$matriz[$key2][] = (int)$value;
			}
		}
	}
}

// ETAPA 2: calcular média, sd, max_value para cada item (de A a E) dentro da questão 14, e montar matriz de labels (eixo-x) e valores (eixo-y)
$mean = "";
$sd = "";
$max_value = "";
$max_x = 20;
$datay = "";
$datax = "";
if(isset($matriz))
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

// ETAPA 3: definir títulos de cada subitem
$titulos[0] = "a. sangramentos (que necessitaram de terapêutica ou internação)";
$titulos[1] = "b. infecções";
$titulos[2] = "c. perfurações";
$titulos[3] = "d. complicações relacionadas à sedaçao";
$titulos[4] = "e. outras";


?>
	<div class="questao" id="q14">
		<div class="enunciado exp_heading"><?php echo __("14. Quantas complicacoes ocorreram em suas EUS apos seu periodo de treinamento ?");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		
		
		
		<?php
		// LOOPING PARA CADA SUBITEM DESTA QUESTÃO
		for($item=0; $item<5; $item++)
		{
			echo '<div class="enunciado">' . $titulos[$item] . '</div><div class="clear"></div>';
			echo '<div class="rp1_center">';
			if(isset($datay[$item]) && isset($datax[$item]))	
			{
			$datay_row = urlencode(htmlentities(serialize($datay[$item])));
			$datax_row = urlencode(htmlentities(serialize($datax[$item])));
			$titley = urlencode(htmlentities("quantidade de marcações"));
			$titlex = urlencode(htmlentities("quantidade de complicações"));
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
//QUESTAO 15

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id";
$query = $query." ".$where. " AND  question17 LIKE '%0;%'";
$list_a1 = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question17 LIKE '%1;%'";
$list_a2 = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question17 LIKE '%2;%'";
$list_a3 = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question17 LIKE '%3;%'";
$list_b1 = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question17 LIKE '%4;%'";
$list_b2 = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question17 LIKE '%5;%'";
$list_b3 = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question17 LIKE '%6;%'";
$list_c1 = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question17 LIKE '%7;%'";
$list_c2 = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question17 LIKE '%8;%'";
$list_c3 = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question17 LIKE '%9;%'";
$list_d1 = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question17 LIKE '%10;%'";
$list_d2 = $connection->GetResult($query);

$query  = "SELECT COUNT(*) as total FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where. " AND  question17 LIKE '%11;%'";
$list_d3 = $connection->GetResult($query);

$query  = "SELECT question17 FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where;
$rows = $connection->GetAllResults($query);
$list_outros = 0;
if(is_array($rows))
{
	foreach($rows as $i => $row)
	{
		$exploded = explode(";", $row['question17']);
		if(isset($exploded[12] ))
		{
		$exploded[12] = trim($exploded[12]);
		//echo "<Br>" . $exploded[12];
		}
		if(isset($exploded[12]) & !empty($exploded[12])) $list_outros++;
		
	}
}

$total = $list_a1['total'] + $list_a2['total'] + $list_a3['total'] + $list_b1['total'] + $list_b2['total'] + $list_b3['total'] + $list_c1['total'] + $list_c2['total'] + $list_c3['total'] + $list_d1['total'] + $list_d2['total'] + $list_d3['total'] + $list_outros;

$porcento_a1 = ($total > 0)?number_format((($list_a1['total']/$total)*100),1,",",""):0;
$porcento_a2 = ($total > 0)?number_format((($list_a2['total']/$total)*100),1,",",""):0;
$porcento_a3 = ($total > 0)?number_format((($list_a3['total']/$total)*100),1,",",""):0;

$porcento_b1 = ($total > 0)?number_format((($list_b1['total']/$total)*100),1,",",""):0;
$porcento_b2 = ($total > 0)?number_format((($list_b2['total']/$total)*100),1,",",""):0;
$porcento_b3 = ($total > 0)?number_format((($list_b3['total']/$total)*100),1,",",""):0;

$porcento_c1 = ($total > 0)?number_format((($list_c1['total']/$total)*100),1,",",""):0;
$porcento_c2 = ($total > 0)?number_format((($list_c2['total']/$total)*100),1,",",""):0;
$porcento_c3 = ($total > 0)?number_format((($list_c3['total']/$total)*100),1,",",""):0;

$porcento_d1 = ($total > 0)?number_format((($list_d1['total']/$total)*100),1,",",""):0;
$porcento_d2 = ($total > 0)?number_format((($list_d2['total']/$total)*100),1,",",""):0;
$porcento_d3 = ($total > 0)?number_format((($list_d3['total']/$total)*100),1,",",""):0;

$porcento_outros = ($total > 0)?number_format((($list_outros/$total)*100),1,",",""):0;
?>

	<div class="questao" id="q15">
		<div class="enunciado exp_heading"><?php echo __("15. Qual(is) equipamento(s) voce usa? (assinale todas as aplicaveis)");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		<div class="rp1_left">

			<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>FUJINON</th>
					<th>OLYMPUS</th>
					<th>PENTAX</th>
					<th>Totais</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>RADIAL MECÂNICO</th>
					<td><?php echo $list_a1['total']; ?> (<?php echo ($porcento_a1);?>%)</td>
					<td><?php echo $list_a2['total']; ?> (<?php echo ($porcento_a2);?>%)</td>
					<td><?php echo $list_a3['total']; ?> (<?php echo ($porcento_a3);?>%)</td>
					<td><strong><?php echo ($list_a1['total'] + $list_a2['total'] + $list_a3['total']); ?></strong></td>
				</tr>
				<tr>
					<th>RADIAL ELETRÔNICO</th>
					<td><?php echo $list_b1['total']; ?> (<?php echo ($porcento_b1);?>%)</td>
					<td><?php echo $list_b2['total']; ?> (<?php echo ($porcento_b2);?>%)</td>
					<td><?php echo $list_b3['total']; ?> (<?php echo ($porcento_b3);?>%)</td>
					<td><strong><?php echo ($list_b1['total'] + $list_b2['total'] + $list_b3['total']); ?></strong></td>
				</tr>
				<tr>
					<th>LINEAR</th>
					<td><?php echo $list_c1['total']; ?> (<?php echo ($porcento_c1);?>%)</td>
					<td><?php echo $list_c2['total']; ?> (<?php echo ($porcento_c2);?>%)</td>
					<td><?php echo $list_c3['total']; ?> (<?php echo ($porcento_c3);?>%)</td>
					<td><strong><?php echo ($list_c1['total'] + $list_c2['total'] + $list_c3['total']); ?></strong></td>
				</tr>
				<tr>
					<th>MINIPROBES</th>
					<td><?php echo $list_d1['total']; ?> (<?php echo ($porcento_d1);?>%)</td>
					<td><?php echo $list_d2['total']; ?> (<?php echo ($porcento_d2);?>%)</td>
					<td><?php echo $list_d3['total']; ?> (<?php echo ($porcento_d3);?>%)</td>
					<td><strong><?php echo ($list_d1['total'] + $list_d2['total'] + $list_d3['total']); ?></strong></td>
				</tr>	
				<tr>
					<th>Totais</th>
					<td><strong><?php echo ($list_a1['total'] + $list_b1['total'] + $list_c1['total'] + $list_d1['total']); ?></strong></td>
					<td><strong><?php echo ($list_a2['total'] + $list_b2['total'] + $list_c2['total'] + $list_d2['total']); ?></strong></td>
					<td><strong><?php echo ($list_a3['total'] + $list_b3['total'] + $list_c3['total'] + $list_d3['total']); ?></strong></td>
					<td><strong><?php echo ($list_a1['total'] + $list_b1['total'] + $list_c1['total'] + $list_d1['total'] + $list_a2['total'] + $list_b2['total'] + $list_c2['total'] + $list_d2['total'] + $list_a3['total'] + $list_b3['total'] + $list_c3['total'] + $list_d3['total']); ?></strong></td>
				</tr>
				<tr>
					<th>Outros</th>
					<td colspan="4"><?php echo $list_outros; ?> (<?php echo ($porcento_outros);?>%)</td>
				</tr>				
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize(array($porcento_a1,$porcento_a2, $porcento_a3, $porcento_b1, $porcento_b2, $porcento_b3, $porcento_c1, $porcento_c2,$porcento_c3,$porcento_d1,$porcento_d2,$porcento_d3, $porcento_outros ))));
			$leg = urlencode(htmlentities(serialize(array('Fujinon-Rad. Mec.', 'Olympus-Rad. Mec.', 'Pentax-Rad. Mec.','Fujinon-Rad. Elet.','Olympus-Rad. Elet.','Pentax-Rad. Elet.','Fujinon-Linear', 'Olympus-Linear', 'Pentax-Linear', 'Fujinon-Miniprobes', 'Olympus-Miniprobes', 'Pentax-Miniprobes', 'Outros'))));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("300"));
			$theme = urlencode(htmlentities("pastel"));
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg&width=$width&height=$height&theme=$theme"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		</div>
	</div><!-- # questao -->	
	
	
	
	
<?php 
//**************************************************************************************
//QUESTAO 16

$query  = "SELECT question18 FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where . " AND question18 IS NOT NULL";
$rows = $connection->GetAllResults($query);

$agulhas = array_fill(0, 12, 0);
for($i=0; $i<12; $i++)
{
	$agulhas[$i] = array_fill(0, 14, 0); // penúltima posição armazena o [somatório ( (13-classificação) * qtde)]; última posição armazena o somatório normalizado para um total de 100
}

$n = 0;
$total_outros = 0;
$total_somatorios = 0;
if(is_array($rows))
{
	
	
	// determinar classificação 
	foreach($rows as $i => $row)
	{
		$n++;
		$exploded = explode(";", $row['question18']);
		for($i=0; $i<12; $i++)
		{
			if(isset($exploded[$i]) && !empty($exploded[$i]) && !is_null($exploded[$i])) $agulhas[$i][($exploded[$i] - 1)]++;
		}
		if(isset($exploded[12]) && !empty($exploded[12]) && !is_null($exploded[12])) $total_outros++;
	}
	
	// determinar [somatório ((13-classificação) * qtde)]
	
	for($i=0; $i<12; $i++)
	{
		for($j=0; $j<12; $j++)
		{
			$agulhas[$i][12] += (($agulhas[$i][$j]) * (13 - ($j + 1)));
		}
		$total_somatorios += $agulhas[$i][12];
	}
	
	// normalizar somatórios para um total de 100
	for($i=0; $i<12; $i++)
	{
		$agulhas[$i][13] = number_format(($agulhas[$i][12] / $total_somatorios)*100, 2, ",", "");
	}
	
}

$agulhas[0][14] = "BOSTON-19";
$agulhas[1][14] = "COOK-19";
$agulhas[2][14] = "MEDI-GLOBE-19";
$agulhas[3][14] = "OLYMPUS-19";

$agulhas[4][14] = "BOSTON-22";
$agulhas[5][14] = "COOK-22";
$agulhas[6][14] = "MEDI-GLOBE-22";
$agulhas[7][14] = "OLYMPUS-22";

$agulhas[8][14] = "BOSTON-25";
$agulhas[9][14] = "COOK-25";
$agulhas[10][14] = "MEDI-GLOBE-25";
$agulhas[11][14] = "OLYMPUS-25";

//print_array($agulhas);
//exit();

?>

	<div class="questao" id="q16">
		<div class="enunciado exp_heading"><?php echo __("16. Qual(is) agulha(s) voce mais utiliza? (em ordem de frequencia, assinalar todos os campos que se aplicam)");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		<div class="rp1_left"><p>Para cada tipo de agulha, foi calculado o somatório [(13 - classificação) x quantidade de votos], em que a classificação varia de 1 a 12, sendo 1 a mais utilizada e 12 a menos utilizada. Assim, tem-se uma medida da importância de utilização de cada tipo de agulha.</p>
<br /><p>N = <?php echo $n; ?></p><br />
		
			<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>BOSTON</th>
					<th>COOK</th>
					<th>MEDI-GLOBE</th>
					<th>OLYMPUS</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>19</th>
					<td><?php echo $agulhas[0][12]; ?> (<?php echo $agulhas[0][13]; ?>%)</td>
					<td><?php echo $agulhas[1][12]; ?> (<?php echo $agulhas[1][13]; ?>%)</td>
					<td><?php echo $agulhas[2][12]; ?> (<?php echo $agulhas[2][13]; ?>%)</td>
					<td><?php echo $agulhas[3][12]; ?> (<?php echo $agulhas[3][13]; ?>%)</td>
				</tr>
				<tr>
					<th>22</th>
					<td><?php echo $agulhas[4][12]; ?> (<?php echo $agulhas[4][13]; ?>%)</td>
					<td><?php echo $agulhas[5][12]; ?> (<?php echo $agulhas[5][13]; ?>%)</td>
					<td><?php echo $agulhas[6][12]; ?> (<?php echo $agulhas[6][13]; ?>%)</td>
					<td><?php echo $agulhas[7][12]; ?> (<?php echo $agulhas[7][13]; ?>%)</td>
				</tr>
				<tr>
					<th>25</th>
					<td><?php echo $agulhas[8][12]; ?> (<?php echo $agulhas[8][13]; ?>%)</td>
					<td><?php echo $agulhas[9][12]; ?> (<?php echo $agulhas[9][13]; ?>%)</td>
					<td><?php echo $agulhas[10][12]; ?> (<?php echo $agulhas[10][13]; ?>%)</td>
					<td><?php echo $agulhas[11][12]; ?> (<?php echo $agulhas[11][13]; ?>%)</td>
				</tr>
				<tr>
					<th>Outros*</th>
					<td colspan="4"><?php echo $total_outros; ?> usuário(s) digitou(aram) outro(s) tipo(s) de agulha.</td>
				</tr>				
			</tbody>
			</table>
			<br><p>*(obs.: não entram na enumeração)</p>
			<br><br>
			
			<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Agulha</th>
					<th>Pontuação</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$pontuacao = "";
			$porcentagem = "";
			$titulo = "";
			foreach($agulhas as $key => $agulha)
			{
				$pontuacao[$key] = $agulha[12];
				$porcentagem[$key] = $agulha[13];
				$titulo[$key] = $agulha[14];
			}
			
			array_multisort($pontuacao, SORT_DESC, SORT_NUMERIC, $titulo, $porcentagem);
			foreach($titulo as $key => $t)
			{
			?>
				<tr>
					<th><?php echo ($key + 1); ?>º</th>
					<td><?php echo $t; ?></td>
					<td><?php echo $pontuacao[$key]; ?></td>
					<td><?php echo $porcentagem[$key]; ?>%</td>
				</tr>
			<?php
			}
			?>
					<tr>
					<th>Totais</th>
					<td></td>
					<td><strong><?php echo $total_somatorios; ?></strong></td>
					<td><strong><?php echo ($total_somatorios>0)?100:0;?>%</strong></td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize($porcentagem)));
			$leg = urlencode(htmlentities(serialize($titulo)));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("300"));
			$theme = urlencode(htmlentities("pastel"));
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg&width=$width&height=$height&theme=$theme"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		</div>
	</div><!-- # questao -->		

	
	
	
<?php 
//**************************************************************************************
//QUESTAO 17

$query  = "SELECT question19 FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where . " AND question19 IS NOT NULL";
$rows = $connection->GetAllResults($query);

$itens = array_fill(0, 5, 0);
for($i=0; $i<5; $i++)
{
	$itens[$i] = array_fill(0, 7, 0); // penúltima posição armazena o [somatório ( (6-classificação) * qtde)]; última posição armazena o somatório normalizado para um total de 100
}

$n = 0;
$total_somatorios = 0;

if(is_array($rows))
{
	// determinar classificação 
	foreach($rows as $i => $row)
	{
		$n++;
		$exploded = explode(";", $row['question19']);
		for($i=0; $i<5; $i++)
		{
			if(isset($exploded[$i]) && !empty($exploded[$i]) && !is_null($exploded[$i]) && $exploded[$i] != 'n' && $exploded[$i] != "N") $itens[$i][($exploded[$i] - 1)]++;
		}
	}
	
	// determinar [somatório ((6-classificação) * qtde)]
	
	for($i=0; $i<5; $i++)
	{
		for($j=0; $j<5; $j++)
		{
			$itens[$i][5] += (($itens[$i][$j]) * (6 - ($j + 1)));
		}
		$total_somatorios += $itens[$i][5];
	}
	
	// normalizar somatórios para um total de 100
	for($i=0; $i<5; $i++)
	{
		if($total_somatorios != 0)
		$itens[$i][6] = number_format(($itens[$i][5] / $total_somatorios)*100, 2, ",", "");
		else 		
		$itens[$i][6] = 0;
		
	}
	
}

$itens[0][7] = "Anorretal";
$itens[1][7] = "Esôfago";
$itens[2][7] = "Gastroduodenal";
$itens[3][7] = "Mediastino";
$itens[4][7] = "Pancreato-biliar-ampular";

//print_array($itens);
//exit();

?>

	<div class="questao" id="q17">
		<div class="enunciado exp_heading"><?php echo __("17. Classifique a frequencia das indicacoes para suas EUS segundo os segmentos anatomicos (1 = mais frequente / 5= menos  frequente /  N= nunca tive)");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		<div class="rp1_left"><p>Para cada tipo de indicação, foi calculado o somatório [(6 - classificação) x quantidade de votos], em que a classificação varia de 1 a 5, sendo 1 a mais frequente e 5 a menos frequente. Assim, tem-se uma medida da importância de cada tipo de indicação.</p>
<br /><p>N = <?php echo $n; ?></p><br />
		
		<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Indicação</th>
					<th>Pontuação</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$pontuacao = "";
			$porcentagem = "";
			$titulo = "";
			foreach($itens as $key => $item)
			{
				$pontuacao[$key] = $item[5];
				$porcentagem[$key] = $item[6];
				$titulo[$key] = $item[7];
			}
			
			array_multisort($pontuacao, SORT_DESC, SORT_NUMERIC, $titulo, $porcentagem);
			foreach($titulo as $key => $t)
			{
			?>
				<tr>
					<th><?php echo ($key + 1); ?>º</th>
					<td><?php echo $t; ?></td>
					<td><?php echo $pontuacao[$key]; ?></td>
					<td><?php echo $porcentagem[$key]; ?>%</td>
				</tr>
			<?php
			}
			?>
					<tr>
					<th>Totais</th>
					<td></td>
					<td><strong><?php echo $total_somatorios; ?></strong></td>
					<td><strong><?php echo ($total_somatorios > 0)?100:0;?>%</strong></td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total_somatorios > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize($porcentagem)));
			$leg = urlencode(htmlentities(serialize($titulo)));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("300"));
			$theme = urlencode(htmlentities("pastel"));
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg&width=$width&height=$height&theme=$theme"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		</div>
	</div><!-- # questao -->		
	
	
	<?php 
//**************************************************************************************
//QUESTAO 18A - ANORRETAL

$query  = "SELECT question20 FROM users
			LEFT JOIN surveys ON surveys.user_id = users.id ";
$query = $query." ".$where . " AND question20 IS NOT NULL";
$rows = $connection->GetAllResults($query);

$itens = array_fill(0, 6, 0);
for($i=0; $i<6; $i++)
{
	$itens[$i] = array_fill(0, 9, 0); // penúltima posição armazena o [somatório ( (7-classificação) * qtde)]; última posição armazena o somatório normalizado para um total de 100
}

$n = 0;
$total_somatorios = 0;
if(is_array($rows))
{
	// determinar classificação 
	foreach($rows as $i => $row)
	{
		$n++;
		$exploded = explode(";", $row['question20']);
		for($i=0; $i<6; $i++)
		{
			if(isset($exploded[$i]) && !empty($exploded[$i]) && !is_null($exploded[$i]) && $exploded[$i] != 'n' && $exploded[$i] != "N") $itens[$i][($exploded[$i] - 1)]++;
		}
	}
	
	// determinar [somatório ((7-classificação) * qtde)]
	
	for($i=0; $i<6; $i++)
	{
		for($j=0; $j<6; $j++)
		{
			$itens[$i][6] += (($itens[$i][$j]) * (7 - ($j + 1)));
		}
		$total_somatorios += $itens[$i][6];
	}
	
	// normalizar somatórios para um total de 100
	for($i=0; $i<6; $i++)
	{
		if($total_somatorios > 0)
			$itens[$i][7] = number_format(($itens[$i][6] / $total_somatorios)*100, 2, ",", "");
		else 
			$itens[$i][7] = 0;
	}
	
}

$itens[0][8] = "Câncer retal";
$itens[1][8] = "Incontinência fecal e/ou fístulas";
$itens[2][8] = "Câncer anal";
$itens[3][8] = "Endometriose";
$itens[4][8] = "Lesões subepiteliais";
$itens[5][8] = "Outros";

//print_array($itens);
//exit();

?>

	<div class="questao" id="q18">
		<div class="enunciado exp_heading"><?php echo __("18. Classifique a frequencia de indicacoes para suas EUS em cada segmento anatomico especifico:");?><a class="seletor_toggle">[+]</a></div>
		<div class="exp_content">
		<div class="enunciado"><?php echo __("a) Anorretal (1 = mais frequente / 6 = menos frequente / N= nunca tive):");?></div>
		<div class="clear"></div>
		<div class="rp1_left"><p>Para cada tipo de indicação, foi calculado o somatório [(7 - classificação) x quantidade de votos], em que a classificação varia de 1 a 6, sendo 1 a mais frequente e 6 a menos frequente. Assim, tem-se uma medida da importância de cada tipo de indicação.</p>
<br /><p>N = <?php echo $n; ?></p><br />
		
		<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Indicação</th>
					<th>Pontuação</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$pontuacao = "";
			$porcentagem = "";
			$titulo = "";
			foreach($itens as $key => $item)
			{
				$pontuacao[$key] = $item[6];
				$porcentagem[$key] = $item[7];
				$titulo[$key] = $item[8];
			}
			
			array_multisort($pontuacao, SORT_DESC, SORT_NUMERIC, $titulo, $porcentagem);
			foreach($titulo as $key => $t)
			{
			?>
				<tr>
					<th><?php echo ($key + 1); ?>º</th>
					<td><?php echo $t; ?></td>
					<td><?php echo $pontuacao[$key]; ?></td>
					<td><?php echo $porcentagem[$key]; ?>%</td>
				</tr>
			<?php
			}
			?>
					<tr>
					<th>Totais</th>
					<td></td>
					<td><strong><?php echo $total_somatorios; ?></strong></td>
					<td><strong><?php echo ($total_somatorios > 0 )?100:0?>%</strong></td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total_somatorios > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize($porcentagem)));
			$leg = urlencode(htmlentities(serialize($titulo)));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("300"));
			$theme = urlencode(htmlentities("pastel"));
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg&width=$width&height=$height&theme=$theme"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
	
	
	
<?php 
//**************************************************************************************
//QUESTAO 18B - ESÔFAGO

unset($itens);
unset($exploded);
unset($pontuacao);
unset($porcentagem);
unset($titulo);
unset($total_somatorios);
unset($l);
unset($n);

$itens = array_fill(0, 4, 0);
for($i=0; $i<4; $i++)
{
	$itens[$i] = array_fill(0, 7, 0); // penúltima posição armazena o [somatório ( (5-classificação) * qtde)]; última posição armazena o somatório normalizado para um total de 100
}

$n = 0;
$total_somatorios = 0;
if(is_array($rows))
{
	// determinar classificação 
	foreach($rows as $i => $row)
	{
		$n++;
		$exploded = explode(";", $row['question20']);
		$l = 0;
		for($i=6; $i<10; $i++) // ==== MUDAR AQUI PARA OS SUBITENS DA QUESTÃO 18 ====
		{
			if(isset($exploded[$i]) && !empty($exploded[$i]) && !is_null($exploded[$i]) && $exploded[$i] != 'n' && $exploded[$i] != "N") $itens[$l][($exploded[$i] - 1)]++;
			$l++;
		}
	}
	
	// determinar [somatório ((5-classificação) * qtde)]
	
	for($i=0; $i<4; $i++)
	{
		for($j=0; $j<4; $j++)
		{
			$itens[$i][4] += (($itens[$i][$j]) * (5 - ($j + 1)));
		}
		$total_somatorios += $itens[$i][4];
	}
	
	// normalizar somatórios para um total de 100
	for($i=0; $i<4; $i++)
	{
		if($total_somatorios > 0)
		$itens[$i][5] = number_format(($itens[$i][4] / $total_somatorios)*100, 2, ",", "");
		else 
		$itens[$i][5] =0;
	}
	
}

$itens[0][6] = "Barrett";
$itens[1][6] = "Câncer";
$itens[2][6] = "Lesões subepiteliais";
$itens[3][6] = "Outros";

//print_array($itens);
//exit();

?>

		<div class="enunciado"><?php echo __("b) Esofago (1 = mais frequente / 4 = menos frequente / N= nunca tive):");?></div>
		<div class="clear"></div>
		<div class="rp1_left"><p>Para cada tipo de indicação, foi calculado o somatório [(5 - classificação) x quantidade de votos], em que a classificação varia de 1 a 4, sendo 1 a mais frequente e 4 a menos frequente. Assim, tem-se uma medida da importância de cada tipo de indicação.</p>
<br /><p>N = <?php echo $n; ?></p><br />
		
		<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Indicação</th>
					<th>Pontuação</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$pontuacao = "";
			$porcentagem = "";
			$titulo = "";
			foreach($itens as $key => $item)
			{
				$pontuacao[$key] = $item[4];
				$porcentagem[$key] = $item[5];
				$titulo[$key] = $item[6];
			}
			
			array_multisort($pontuacao, SORT_DESC, SORT_NUMERIC, $titulo, $porcentagem);
			foreach($titulo as $key => $t)
			{
			?>
				<tr>
					<th><?php echo ($key + 1); ?>º</th>
					<td><?php echo $t; ?></td>
					<td><?php echo $pontuacao[$key]; ?></td>
					<td><?php echo $porcentagem[$key]; ?>%</td>
				</tr>
			<?php
			}
			?>
					<tr>
					<th>Totais</th>
					<td></td>
					<td><strong><?php echo $total_somatorios; ?></strong></td>
					<td><strong><?php echo ($total_somatorios> 0)?100:0?>%</strong></td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total_somatorios > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize($porcentagem)));
			$leg = urlencode(htmlentities(serialize($titulo)));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("300"));
			$theme = urlencode(htmlentities("pastel"));
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg&width=$width&height=$height&theme=$theme"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		

<?php 
//**************************************************************************************
//QUESTAO 18C - GASTRODUODENAL

unset($itens);
unset($exploded);
unset($pontuacao);
unset($porcentagem);
unset($titulo);
unset($total_somatorios);
unset($l);
unset($n);

$itens = array_fill(0, 5, 0);
for($i=0; $i<5; $i++)
{
	$itens[$i] = array_fill(0, 8, 0); // penúltima posição armazena o [somatório ( (6-classificação) * qtde)]; última posição armazena o somatório normalizado para um total de 100
}

$n = 0;
$total_somatorios = 0;
if(is_array($rows))
{
	// determinar classificação 
	foreach($rows as $i => $row)
	{
		$n++;
		$exploded = explode(";", $row['question20']);
		$l = 0;
		for($i=10; $i<15; $i++) // ==== MUDAR AQUI PARA OS SUBITENS DA QUESTÃO 18 ====
		{
			if(isset($exploded[$i]) && !empty($exploded[$i]) && !is_null($exploded[$i]) && $exploded[$i] != 'n' && $exploded[$i] != "N") $itens[$l][($exploded[$i] - 1)]++;
			$l++;
		}
	}
	
	// determinar [somatório ((6-classificação) * qtde)]
	
	for($i=0; $i<5; $i++)
	{
		for($j=0; $j<5; $j++)
		{
			$itens[$i][5] += (($itens[$i][$j]) * (6 - ($j + 1)));
		}
		$total_somatorios += $itens[$i][5];
	}
	
	// normalizar somatórios para um total de 100
	for($i=0; $i<5; $i++)
	{
		if($total_somatorios > 0)
		$itens[$i][6] = number_format(($itens[$i][5] / $total_somatorios)*100, 2, ",", "");
		else
		$itens[$i][6] = 0;
	}
	
}

$itens[0][7] = "Adenocarcinoma";
$itens[1][7] = "Linfoma";
$itens[2][7] = "Tumor ou linfonodos (perigástricos ou periduodenais)";
$itens[3][7] = "Lesões subepiteliais";
$itens[4][7] = "Outros";

//print_array($itens);
//exit();

?>

		<div class="enunciado"><?php echo __("c) Gastroduodenal (1 = mais frequente / 5 = menos frequente / N= nunca tive):");?></div>
		<div class="clear"></div>
		<div class="rp1_left"><p>Para cada tipo de indicação, foi calculado o somatório [(6 - classificação) x quantidade de votos], em que a classificação varia de 1 a 5, sendo 1 a mais frequente e 5 a menos frequente. Assim, tem-se uma medida da importância de cada tipo de indicação.</p>
<br /><p>N = <?php echo $n; ?></p><br />
		
		<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Indicação</th>
					<th>Pontuação</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$pontuacao = "";
			$porcentagem = "";
			$titulo = "";
			foreach($itens as $key => $item)
			{
				$pontuacao[$key] = $item[5];
				$porcentagem[$key] = $item[6];
				$titulo[$key] = $item[7];
			}
			
			array_multisort($pontuacao, SORT_DESC, SORT_NUMERIC, $titulo, $porcentagem);
			foreach($titulo as $key => $t)
			{
			?>
				<tr>
					<th><?php echo ($key + 1); ?>º</th>
					<td><?php echo $t; ?></td>
					<td><?php echo $pontuacao[$key]; ?></td>
					<td><?php echo $porcentagem[$key]; ?>%</td>
				</tr>
			<?php
			}
			?>
					<tr>
					<th>Totais</th>
					<td></td>
					<td><strong><?php echo $total_somatorios; ?></strong></td>
					<td><strong><?php echo ($total_somatorios>0)?100:0?>%</strong></td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total_somatorios > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize($porcentagem)));
			$leg = urlencode(htmlentities(serialize($titulo)));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("300"));
			$theme = urlencode(htmlentities("pastel"));
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg&width=$width&height=$height&theme=$theme"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>


<?php 
//**************************************************************************************
//QUESTAO 18D - MEDIASTINO

unset($itens);
unset($exploded);
unset($pontuacao);
unset($porcentagem);
unset($titulo);
unset($total_somatorios);
unset($l);
unset($n);

$itens = array_fill(0, 4, 0);
for($i=0; $i<4; $i++)
{
	$itens[$i] = array_fill(0, 7, 0); // penúltima posição armazena o [somatório ( (5-classificação) * qtde)]; última posição armazena o somatório normalizado para um total de 100
}

$n = 0;
$total_somatorios = 0;
if(is_array($rows))
{
	// determinar classificação 
	foreach($rows as $i => $row)
	{
		$n++;
		$exploded = explode(";", $row['question20']);
		$l = 0;
		for($i=15; $i<19; $i++) // ==== MUDAR AQUI PARA OS SUBITENS DA QUESTÃO 18 ====
		{
			if(isset($exploded[$i]) && !empty($exploded[$i]) && !is_null($exploded[$i]) && $exploded[$i] != 'n' && $exploded[$i] != "N") $itens[$l][($exploded[$i] - 1)]++;
			$l++;
		}
	}
	
	// determinar [somatório ((5-classificação) * qtde)]
	$total_somatorios = 0;
	for($i=0; $i<4; $i++)
	{
		for($j=0; $j<4; $j++)
		{
			$itens[$i][4] += (($itens[$i][$j]) * (5 - ($j + 1)));
		}
		$total_somatorios += $itens[$i][4];
	}
	
	// normalizar somatórios para um total de 100
	for($i=0; $i<4; $i++)
	{
		if($total_somatorios > 0)
		$itens[$i][5] = number_format(($itens[$i][4] / $total_somatorios)*100, 2, ",", "");
		else $itens[$i][5] =0;
	}
	
}

$itens[0][6] = "Linfonodos (exceto de câncer de pulmão)";
$itens[1][6] = "Tumor mediastinal";
$itens[2][6] = "Estadiamento de câncer de pulmão";
$itens[3][6] = "Outros";

//print_array($itens);
//exit();

?>

		<div class="enunciado"><?php echo __("d) Mediastino (1 = mais frequente / 4= menos frequente / N= nunca tive):");?></div>
		<div class="clear"></div>
		<div class="rp1_left"><p>Para cada tipo de indicação, foi calculado o somatório [(5 - classificação) x quantidade de votos], em que a classificação varia de 1 a 4, sendo 1 a mais frequente e 4 a menos frequente. Assim, tem-se uma medida da importância de cada tipo de indicação.</p>
<br /><p>N = <?php echo $n; ?></p><br />
		
		<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Indicação</th>
					<th>Pontuação</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$pontuacao = "";
			$porcentagem = "";
			$titulo = "";
			foreach($itens as $key => $item)
			{
				$pontuacao[$key] = $item[4];
				$porcentagem[$key] = $item[5];
				$titulo[$key] = $item[6];
			}
			
			array_multisort($pontuacao, SORT_DESC, SORT_NUMERIC, $titulo, $porcentagem);
			foreach($titulo as $key => $t)
			{
			?>
				<tr>
					<th><?php echo ($key + 1); ?>º</th>
					<td><?php echo $t; ?></td>
					<td><?php echo $pontuacao[$key]; ?></td>
					<td><?php echo $porcentagem[$key]; ?>%</td>
				</tr>
			<?php
			}
			?>
					<tr>
					<th>Totais</th>
					<td></td>
					<td><strong><?php echo $total_somatorios; ?></strong></td>
					<td><strong><?php echo ($total_somatorios>0)?100:0?>%</strong></td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total_somatorios > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize($porcentagem)));
			$leg = urlencode(htmlentities(serialize($titulo)));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("300"));
			$theme = urlencode(htmlentities("pastel"));
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg&width=$width&height=$height&theme=$theme"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>


<?php 
//**************************************************************************************
//QUESTAO 18E - PANCREATO-BILIAR-AMPULAR

unset($itens);
unset($exploded);
unset($pontuacao);
unset($porcentagem);
unset($titulo);
unset($total_somatorios);
unset($l);
unset($n);

$itens = array_fill(0, 7, 0);
for($i=0; $i<7; $i++)
{
	$itens[$i] = array_fill(0, 10, 0); // penúltima posição armazena o [somatório ( (8-classificação) * qtde)]; última posição armazena o somatório normalizado para um total de 100
}

$n = 0;
$total_somatorios = 0;
if(is_array($rows))
{
	// determinar classificação 
	foreach($rows as $i => $row)
	{
		$n++;
		$exploded = explode(";", $row['question20']);
		$l = 0;
		for($i=19; $i<26; $i++) // ==== MUDAR AQUI PARA OS SUBITENS DA QUESTÃO 18 ====
		{
			if(isset($exploded[$i]) && !empty($exploded[$i]) && !is_null($exploded[$i]) && $exploded[$i] != 'n' && $exploded[$i] != "N") $itens[$l][($exploded[$i] - 1)]++;
			$l++;
		}
	}
	
	// determinar [somatório ((8-classificação) * qtde)]
	$total_somatorios = 0;
	for($i=0; $i<7; $i++)
	{
		for($j=0; $j<7; $j++)
		{
			$itens[$i][7] += (($itens[$i][$j]) * (8 - ($j + 1)));
		}
		$total_somatorios += $itens[$i][7];
	}
	
	// normalizar somatórios para um total de 100
	for($i=0; $i<7; $i++)
	{
		if($total_somatorios >0)
		$itens[$i][8] = number_format(($itens[$i][7] / $total_somatorios)*100, 2, ",", "");
		else 
		$itens[$i][8] =  0;
	}
	
}

$itens[0][9] = "Pancreatite aguda/crônica";
$itens[1][9] = "Tumor/câncer ampular";
$itens[2][9] = "Pseudocisto de pâncreas";
$itens[3][9] = "Tumores císticos do pâncreas";
$itens[4][9] = "Microlitíase ou coledocolitíase";
$itens[5][9] = "Tumor/câncer pancreático";
$itens[6][9] = "Outros";

//print_array($itens);
//exit();

?>

		<div class="enunciado"><?php echo __("e) Pancreato-biliar-ampular (1 = mais frequente / 7=  menos frequente / N= nunca tive):");?></div>
		<div class="clear"></div>
		<div class="rp1_left"><p>Para cada tipo de indicação, foi calculado o somatório [(8 - classificação) x quantidade de votos], em que a classificação varia de 1 a 7, sendo 1 a mais frequente e 7 a menos frequente. Assim, tem-se uma medida da importância de cada tipo de indicação.</p>
<br /><p>N = <?php echo $n; ?></p><br />
		
		<table class="report1">
			<thead>
				<tr>
					<th></th>
					<th>Indicação</th>
					<th>Pontuação</th>
					<th>Porcentagem</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$pontuacao = "";
			$porcentagem = "";
			$titulo = "";
			foreach($itens as $key => $item)
			{
				$pontuacao[$key] = $item[7];
				$porcentagem[$key] = $item[8];
				$titulo[$key] = $item[9];
			}
			
			array_multisort($pontuacao, SORT_DESC, SORT_NUMERIC, $titulo, $porcentagem);
			foreach($titulo as $key => $t)
			{
			?>
				<tr>
					<th><?php echo ($key + 1); ?>º</th>
					<td><?php echo $t; ?></td>
					<td><?php echo $pontuacao[$key]; ?></td>
					<td><?php echo $porcentagem[$key]; ?>%</td>
				</tr>
			<?php
			}
			?>
					<tr>
					<th>Totais</th>
					<td></td>
					<td><strong><?php echo $total_somatorios; ?></strong></td>
					<td><strong><?php echo ($total_somatorios>0)?100:0?>%</strong></td>
				</tr>
			</tbody>
			</table>
		</div><!-- #rp1_left -->
		
		<div class="rp1_right">
		<?php if($total_somatorios > 0) {?>
			<?php 
			$data = urlencode(htmlentities(serialize($porcentagem)));
			$leg = urlencode(htmlentities(serialize($titulo)));
			$width = urlencode(htmlentities("930"));
			$height = urlencode(htmlentities("300"));
			$theme = urlencode(htmlentities("pastel"));
			?>

	<img src="<?php echo URL."/control_panel/reports_survey_answers_graph.php?type=pie3d&output=JPGRAPH&data=$data&leg=$leg&width=$width&height=$height&theme=$theme"?>" />
		<?php }?>
		</div><!-- #rp1_right -->
		<div class="clear"></div>
		</div>
	</div><!-- # questao -->	