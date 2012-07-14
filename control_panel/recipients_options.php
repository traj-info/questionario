<?php include 'control_panel/admin_auth.php'; ?>
<h2><?php echo __("Destinatários"); ?></h2>
<br>
<ul id="recipients_menu" class="recipients_menu menu">
	<li><a href="?module=control_panel&page=recipients_add&lang=<?php echo $_REQUEST['lang'];?>"><?php echo __('Adicionar Destinatario');?></a></li>
	<li><a href="?module=control_panel&page=recipients_import&lang=<?php echo $_REQUEST['lang'];?>"><?php echo __('Importar Destinatarios');?></a></li>
</ul>
<div style="clear:both"></div>
<br>
<div id="navigator_recipients_list" class="navigator_container navigator_recipients_list">
	<?php echo $retorno['Filters']['Navigator']['action'];?>
	<div id="#navbar"><?php echo $retorno['Filters']['Navigator']['navbar'];?></div>
	<br>
	<table width="100%" id="ListRecipients" class="datagrid">
		<thead>
			<?php echo $retorno['Filters']['Navigator']['header'];?>
		</thead>
		<tbody id="ListRec">
			<?php include "recipients_list.php";?>
		</tbody>
	</table><div id="bt_holder1">
	<?php echo __("Com marcados: "); ?>
	<input type="button" name="DeleteRecipients" id="DeleteRecipients" value="<?php echo __("Excluir");?>" class="bt_action bt_list_submit">
	<input type="button" name="ResendRecipients" id="ResendRecipients" value="<?php echo __("Reenviar");?>" class="bt_action bt_list_submit">
	</div>
</div>

<br>