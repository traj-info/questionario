<?php include 'control_panel/admin_auth.php'; ?>

<?php 

 $_REQUEST['lang'] = (isset( $_REQUEST['lang']))? $_REQUEST['lang']:LANG_DEFAULT;
 
	#Consultando a tabela de settings
	$query = "SELECT * FROM settings";
	
	#Retorno da consulta armazenado em variavel
	$consulta = $connection->GetAllResults($query);
	
	
	if(is_array($consulta))
	{
		
?>
<h2><?php echo __("Configurações"); ?></h2>
<form name="form_settings" class="form frm" method="post" action="index.php?module=control_panel&page=settings">
	<table class="tabela tabela_settings" id="tabela_settings">
		<thead>
			<tr>
				<th><?php echo __('Descricao');?></th>
				<td><?php echo __('Valor');?></td>
			</tr>
		</thead>
		<tbody>
		<?php 
			foreach($consulta as $key => $value)
			{
				#se $value['edit'] == 1, o campo pode ser editado, caso contrario, nao pode.
				//$value['value'] = utf8_encode($value['value']);
		?>
			<tr>
				<th><?php echo $value['param'];?></th>
				<td><?php echo ($value['edit'] == 1)?'<input type="text" name="Param['.$value['param'].']" value="'.$value['value'].'" id="'.$value['param'].'" class="param"> ':$value['value'];?></td>
			</tr>		
		<?php 
			}
		?>
		</tbody>
	</table>
	<br>
	<input type="submit" value="<?php echo __("Salvar")?>" class="bt_submit" name="bt_salvar" id="bt_salvar">
</form>
<?php 
}

if(isset($_POST["bt_salvar"]))
{
	if($_POST['Param'])
	{
		$erro = 0;
		
		foreach($_POST['Param'] as $key => $value)
		{
			$conteudo = trim(FilterData($value));
			
			$update = "UPDATE settings SET value='".$conteudo."' WHERE param = '".$key."' AND edit = 1";
			$retval = $connection->Query($update);
			
			if(! $retval ) $erro++;
		}
		
		#Verificacao da query, se nao houver retorno, abortar com mensagem de erro.
		if($erro > 0 )
		{ 
			$errorMessage = urlencode(__("Configuracoes nao alteradas com sucesso."));
			
			#Chamaremos a funcao criada para direcionar as mensagens.
			errorMsg(INDEX, $module, $page, $errorMessage, $_REQUEST['lang']);
			
		}
		#Caso contrario, exibir mensagem de sucesso
		else 
		{
			$successMessage = urlencode(__("Alterado com sucesso"));
				
			#Chamaremos a funcao criada para direcionar as mensagens.
			successMsg(INDEX, $module, $page, $successMessage, $_REQUEST['lang']);
		}
		
	}
}

?>