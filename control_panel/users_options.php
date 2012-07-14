<?php include 'control_panel/admin_auth.php'; ?>
<h2><?php echo __("Usuários"); ?></h2>
<br>
<div id="navigator_users_list" class="navigator_container navigator_users_list">
	<?php echo $retorno['Filters']['Navigator']['action'];?>
	<div id="#navbar"><?php echo $retorno['Filters']['Navigator']['navbar'];?></div>
	<br>
	<table width="100%" id="ListUsers">
		<thead>
			<?php echo $retorno['Filters']['Navigator']['header'];?>
		</thead>
		<tbody id="ListUsernames" >
			<?php include "users_list.php";?>		
		</tbody>
	</table>	
</div>
	
<div id="bt_holder1">
	<?php echo __("Com marcados: "); ?>
	<input type="button" name="SendReminder" id="SendReminder" value="<?php echo __("Enviar Lembrete");?>" class="bt_action bt_list_submit">
</div>
<br>