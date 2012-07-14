<?php require_once('control_panel/admin_auth.php'); ?>

<?php 
/*
 * Itens do Menu 
 * 
 * 
 * - configuracoes
 * - destinatarios
 * - usuarios
 * 		- questionarios
 * - relatorios
 * - log de envio de e-mails
 * - log de acessos ao sistema
 * 
 * 
 * */
?>

<ul id="control_panel_menu" class="main_menu control_panel">
	<li id="mn-settings"><a href="index.php?module=control_panel&page=settings&lang=<?php echo $_REQUEST['lang'];?>"><?php echo __('Configuracoes');?></a></li>
	<li id="mn-recipients"><a href="index.php?module=control_panel&page=recipients&lang=<?php echo $_REQUEST['lang'];?>"><?php echo __('Destinatarios');?></a></li>
	<li id="mn-users"><a href="index.php?module=control_panel&page=users&lang=<?php echo $_REQUEST['lang'];?>"><?php echo __('Usuarios');?></a></li>
	<li id="mn-reports"><a href="index.php?module=control_panel&page=reports&lang=<?php echo $_REQUEST['lang'];?>"><?php echo __('Relatorios');?></a></li>
	<li id="mn-editar-dados"><a href="index.php?module=user&page=edit_data&lang=<?php echo $_REQUEST['lang'];?>"><?php echo __("Meus dados pessoais");?></a></li>
	<li id="mn-alterar-senha"><a href="index.php?module=user&page=change_password&lang=<?php echo $_REQUEST['lang'];?>"><?php echo __("Alterar Senha");?></a></li>
</ul>




