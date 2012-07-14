<?php include 'control_panel/admin_auth.php'; ?>

<h2><?php echo __("Relat&oacute;rios")?></h2>
<br>
<ul class="main_menu" id="report_menu">
	<li id="mn-export-data"><a href="index.php?module=control_panel&page=report_export&output=CSV"><?php echo __("Exportar Dados para .CSV")?></a></li>
	<li id="mn-estatistics-survey-access"><a href=""><?php echo __("Relat&oacute;rio de Acesso dos Usu&aacute;rios")?></a></li>
	<li id="mn-estatistics-survey-answers"><a href="index.php?module=control_panel&page=reports_survey_answers"><?php echo __("Relat&oacute;rio no Navegador")?></a></li>	
</ul>