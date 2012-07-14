<?php require_once('user/user_auth.php'); ?>
<h2><?php echo __("Menu principal"); ?></h2>
<div id="saudacao">
<p>
	<?php echo __("Ola,");?><strong>
	<?php echo $_SESSION['server_usuario']; ?></strong>
	<br>
	<?php echo __("Seja bem-vindo!");?>
</p>
</div>
<?php
if ($_SESSION['server_nivel'] < ADMIN) :
?>
<ul id="user_menu" class="main_menu">
	<li id="mn-preencher"><a href="index.php?module=survey&page=survey_main&lang=<?php echo $_REQUEST['lang'];?>"><?php echo __("Preencher/Ver Questionario");?></a></li>
	<li id="mn-editar-dados"><a href="index.php?module=user&page=edit_data&lang=<?php echo $_REQUEST['lang'];?>"><?php echo __("Meus dados pessoais");?></a></li>
	<li id="mn-alterar-senha"><a href="index.php?module=user&page=change_password&lang=<?php echo $_REQUEST['lang'];?>"><?php echo __("Alterar Senha");?></a></li>
</ul>
<?php else : require_once('control_panel/dashboard.php') ?>
<?php endif; ?>


