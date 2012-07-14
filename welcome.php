<h2><?php echo __("Pesquisa sobre a prática da Ecoendoscopia (EUS) na América Latina");?></h2>

<p><?php echo __("Caros colegas,");?></p>

<p><?php echo __("A prática da ecoendoscopia (EUS) vem crescendo nos últimos anos, mas o seu real status na América Latina é desconhecido. Pensando nesta lacuna, elaboramos um questionário com o objetivo de coletar informações de todos os médicos que realizam ecoendoscopia nos diversos países da América Latina.");?> </p>

<p><?php echo __("Contamos com a sua valiosa contribuição respondendo este questionário.");?><br />
<strong><?php echo __("Ele não tomará mais do que 8 minutos do seu tempo.");?></strong></p>

<?php 
if(empty($_SESSION['server_usuarioId'])){ 
?>
<p><strong><?php echo __("ATENÇÃO: Para manter todas as suas informações e respostas estritamente confidenciais, inicialmente você precisará fazer um cadastro rápido que lhe permitirá receber no seu e-mail uma senha para acesso ao questionário.");?></strong> <a href="<?php echo SIGNUP ?>&lang=<?php echo $lang; ?>"><?php echo __("Clique aqui para se cadastrar.")?></a></p>
<?php } ?>
<p><?php echo __("Por favor, responda o questionário apenas se você realiza EUS.");?></p>

<p><?php echo __("Obrigado.");?></p>

<?php 
if(empty($_SESSION['server_usuarioId'])){ 
?>
<ul class="main_menu menu_index">
	<li id="welcome-signup"><?php echo __("Se ainda não possui cadastro, "); ?><a href="<?php echo SIGNUP ?>&lang=<?php echo $lang; ?>"><?php echo __("cadastre-se"); ?></a></li>
	<li id="welcome-login"><?php echo __("Se você já possui cadastro, "); ?><a href="<?php echo LOGIN ?>&lang=<?php echo $lang; ?>"><?php echo __("efetue seu login"); ?></a></li>
	
</ul>

<?php } ?>

<p><strong><?php echo __("Diretoria do CLEUS 2010-2012");?></strong>
<br /><?php echo __("Cecilia Castillo (Chile)");?>
<br /><?php echo __("José Ricardo Ruíz Obaldía (Panamá)");?>
<br /><?php echo __("Lucio G. B. Rossini (Brasil)");?>
<br /><?php echo __("Wallia Wever (Venezuela)");?></p>
<p><strong><?php echo __("Colaboradores da pesquisa");?></strong>
<br /><?php echo __("Juliana Marques Drigo (Brasil)");?>
<br /><?php echo __("Sheila Fillipi (Brasil)");?></p>
<p><?php echo __("Inicialmente este questionário foi enviado a todos os médicos que se tornaram amigos do CLEUS/SIED (Capítulo Latinoamericano de Ecoendoscopia - ");?><strong><a href="http://www.cleus.org" target="_blank">www.cleus.org</a></strong><?php echo __(" / Sociedade Interamericana de Endoscopia Digestiva - ");?><strong><a href="http://www.e-sied.org" target="_blank">www.e-sied.org</a></strong><?php echo __("). Se você ainda não é amigo do CLEUS, algum colega o indicou e por este motivo você está recebendo este questionário.");?></p>
<p><strong><?php echo __("Solicitamos seu apoio incentivando os seus colegas que realizam EUS e ainda não são amigos do CLEUS a preencher este questionário. Divulgue aos seus colegas nosso endereço!");?></strong></p>
<p><strong><?php echo __("Se você tem alguma dúvida, por favor, não hesite em nos comunicar: ");?><br/><a href="mailto:cleus.encuesta@gmail.com" target="_blank">cleus.encuesta@gmail.com</a></strong></p>


<div class="clear"></div>