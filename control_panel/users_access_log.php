<?php include 'control_panel/admin_auth.php'; ?>
<h2><?php echo __("Log de acesso do usu&aacute;rio")?></h2>
<br>
<?php 

if($_REQUEST['id'])
{
	$query = "SELECT * FROM accesslogs WHERE user_id = ".trim(FilterData($_REQUEST['id']))." ORDER BY date DESC";
	$ListAccess	= $connection->GetAllResults($query);
	
	if(is_array($ListAccess))
	{
?>
		<table id="AccessLogs" class="TableList">
			<thead>
				<tr>
					<th><?php echo __("Data");?></th>
					<th><?php echo __("Endereco Acessado");?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach($ListAccess as $key => $content)
					{
?>
						<tr>
							<td><?php echo $content['date'];?></td>
							<td><?php echo $content['description'];?></td>
						</tr>
<?php 	 
					}
				?>
			</tbody>			
		</table>

<?php 	
		
	}
	else 
	{
		
		echo __("Usuario nunca acessou o sistema");
	}
	
}

?>