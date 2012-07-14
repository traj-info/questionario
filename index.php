<?php
	/**
	 * Funcionamento
	 * 
	 * Variaveis:
	 * 
	 * $callback = chama funcao que precisa ser executada antes de ter uma saida de texto 
	 * $lang = idioma selecionado
	 * $module = modulo requisitado
	 * $page = pagina do modulo requisitado. se o modulo nao for setado, chama no mesmo diretorio que o index
	 * $msgError = mensagem de erro
	 * $msgSucess = mensagem de sucesso
	 * 
	 */


	# Componentes genericos
	require_once 'widget/paginator/trunk/CNavegacao.php';
	require_once 'widget/jpgraph/src/jpgraph.php';
	require_once 'widget/jpgraph/src/jpgraph_pie.php';
	
	# Bibliotecas
	require_once 'includes/util.php';
	require_once 'includes/TSystemComponent.php';
	require_once 'includes/TDbConnector.php';
	require_once 'includes/constants.php';
	require_once 'includes/class.phpmailer.php';
	require_once 'includes/class.smtp.php';

	# Classes
	require_once 'includes/TRecipients.php';
	require_once 'includes/TUsers.php';

	
	#CENTRALIZACAO DA CONEXAO DO BANCO DE DADOS
	#Obtendo conexao com base de dados
	$connection = new TDbConnector();
	
	#Se houver erro de conexao, abortar com mensagem de erro
	if (!$connection)
		die;
			
	# Verificacao para login
	# Se receber um parametro chamado  $_REQUEST['callback'] = 'iniciaSessao', chama a funcao em utilizadas

	$callback = (empty($_REQUEST['callback']))?"":$_REQUEST['callback'];
	
	
	
	if(!empty($callback)) $callback(); #inicia uma sessao com os parametros de login

	if(!isset($_SESSION)) session_start();
	
	
	#Definindo variaveis que serao utilizadas nas funcoes de errorMsg e successMsg.
	$constantUrl = LOCAL;

	#Configuracao da pagina para ser exibida
	$lang = (empty($_REQUEST['lang']))?LANG_DEFAULT:FilterData($_REQUEST['lang']);
	$module = (empty($_REQUEST['module']))? "":FilterData($_REQUEST['module']);
	$page = (empty($_REQUEST['page']))?PAGE_DEFAULT:FilterData($_REQUEST['page']);

	#Configuracao das mensagens de erro
	$msgError = (empty($_REQUEST['msgError']))?"":FilterData($_REQUEST['msgError']);
	$msgSucess = (empty($_REQUEST['msgSucess']))?"":FilterData($_REQUEST['msgSucess']);
	
	
	$_REQUEST['output'] = (isset($_REQUEST['output']))?$_REQUEST['output']:null;
	
	
	
	#Configuracoes de internacionalizacao do PHP
	putenv("LANG=$lang"); 
	setlocale(LC_ALL, $lang);
	
	bindtextdomain(DOMAIN_LANG, PATH_LANG); 
	textdomain(DOMAIN_LANG);
	bind_textdomain_codeset(DOMAIN_LANG, 'UTF-8');

	
	# Saida HTML so pode ocorrer depois de iniciar a sessao, senao acontece um Warning

	# abre o buffer de captura da saida
	
	
	if($_REQUEST['output'] == 'JPGRAPH')
	{
		#Concatentando modulo com a pagina, exemplo: questionario/index.php?module=user&page=view_data
		$url_include =($module == null)?$page.".php":$module."/".$page.".php";
	
		#verifica se existe o modulo e a pagina
		if(file_exists($url_include))
		{
			#adicao da pagina requisitada
			require_once($url_include);
		}	
	}
	else if($_REQUEST['output']=="CSV")
	{
		header("Content-type: application/csv");		
		header("Content-Disposition: attachment; filename=report.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		
		#Concatentando modulo com a pagina, exemplo: questionario/index.php?module=user&page=view_data
		$url_include =($module == null)?$page.".php":$module."/".$page.".php";
	
		#verifica se existe o modulo e a pagina
		if(file_exists($url_include))
		{
			#adicao da pagina requisitada
			require_once($url_include);
		}
	}
	else	
	{
	# conforme a necessidade, o output e alterado. como nao e utilizado templates, foi adicionado um if
	if(empty($_REQUEST['output']))
	{
		ob_start();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo __("Questionario Cientifico");?></title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<!--  jquery core -->
<script src="js/jquery/jquery-1.7.2.min.js" type="text/javascript"></script>
<!--  jquery ui -->
<script src="js/jquery/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>
<!-- Custom jquery scripts -->
<script src="js/jquery/custom_jquery.js" type="text/javascript"></script>
<link rel='stylesheet' id='main-css-css'  href='css/style.css' type='text/css' media='all' />
<script type="text/javascript">
$(document).ready(function() {
  $(".exp_content").hide();
  $(".exp_heading").click(function()
  {
	var novoRotulo = $(this).next(".exp_content").is(':visible') ? '[+]' : '[-]';
    $(this).next(".exp_content").slideToggle(500);
	$(this).children('.seletor_toggle').html(novoRotulo);
  });
  
  $("#link_toggle").click(function()
  {
	if($('#link_toggle').html() == '[ Expandir tudo ]')
	{
		$('#link_toggle').html('[ Retrair tudo ]');;
		$('.seletor_toggle').html('[-]');
		$(".exp_content").show(500);	
	}
	else
	{
		$('#link_toggle').html('[ Expandir tudo ]');;
		$('.seletor_toggle').html('[+]');
		$(".exp_content").hide(500);	
	}	
	
  });
});
</script>
</head>
<body>
	<div id="background">
		<div id="wrapper">
			<div id="top">
				<h1 id="logo-principal"><?php echo __("Questionario Cientifico");?></h1>
				<div id="menu">
					<ul id="top-menu">
						<li id="mn-inicio"><a href="index.php?lang=<?php echo $lang;?>"><?php echo __("Página inicial")?></a></li>					
						<?php 
							if(!empty($_SESSION['server_usuarioId'])) 
							{ 
						?>
									<li id="mn-principal"><a href="index.php?module=user&page=modelo_main&lang=<?php echo $lang;?>"><?php echo __("Menu principal");?></a></li>
									<li id="mn-editar-dados"><a href="index.php?module=user&page=edit_data&lang=<?php echo $lang;?>"><?php echo __("Meus dados pessoais");?></a></li>
									
								
									<li id="mn-sair"><a href="index.php?module=user&page=logout"><?php echo __("Sair")?></a></li>
						<?php 
						
					
							} 
							else 
							{
								echo '<li id="mn-login"><a href="'.LOGIN.'&lang='.$lang.'">'.__("Login").'</a></li>';
								echo '<li id="mn-cadastrar"><a href="'.SIGNUP.'&lang='.$lang.'">'.__("Cadastrar").'</a></li>';	
							}
							
						 ?>	</ul>
						 	<ul id="change_lang">
						 <?php 
							$ListLang = unserialize(LANGS);
							
							if(is_array($ListLang))
							{
								foreach($ListLang as $key => $content)
								{
									$selected = ($lang == $key)?" lang_selected ":"";
									
									
									$PosStringLang = stripos($_SERVER["REQUEST_URI"],"lang=");
									
									if($PosStringLang !== false)
									{
										
										$url = substr($_SERVER["REQUEST_URI"],0,$PosStringLang+5);
										$url .=$key;
										
										
										if(stripos(substr($_SERVER["REQUEST_URI"],$PosStringLang+7),"_BR") !== false || stripos(substr($_SERVER["REQUEST_URI"],$PosStringLang+7),"_ES") !== false || stripos(substr($_SERVER["REQUEST_URI"],$PosStringLang+7),"_US") !== false)
										{
											$url .= substr($_SERVER["REQUEST_URI"],$PosStringLang+10);
										}
										else 
										{									
										
											$url .= substr($_SERVER["REQUEST_URI"],$PosStringLang+7);
										}
										
									}
									else
									{
										$url = $_SERVER["REQUEST_URI"];
										$url .= (stripos($_SERVER["REQUEST_URI"],"?") == false)?"?":"&";
										$url .= "lang=".$key;
									}
									
									echo '<li class="lang_item '.$selected.'"><a href="'.$url.'">'.$content.'</a></li>';
								}
								
							}
							
						 ?>
						 	</ul>
			
					
				</div>
				<!-- end #menu -->
			</div>
			<!-- end #top -->
			<div class="clear"></div>
			<div id="content">
				<div id="main">
<?php

	}#fim do if(!$_REQUEST['output'])
	
	



	#Concatentando modulo com a pagina, exemplo: questionario/index.php?module=user&page=view_data
	$url_include =($module == null)?$page.".php":$module."/".$page.".php";

	#verifica se existe o modulo e a pagina
	if(file_exists($url_include))
	{
		if(empty($_REQUEST['output']))
		{
			echo "<div class='contentIncluded module_$module page_$page'>";
		}

		#exibicao das mensagens de erro e de sucesso
		if(isset($msgError) && $msgError != "")
		{
			if(empty($_REQUEST['output']))
			{
				echo '<div id="message_error" class="message message_error">';
			}
			
			if(strpos( $msgError,"||") === false)
			{
				echo ($msgError);
			}
			else
			{
				$list = explode("||",$msgError);
				if(is_array($list))
				{
					foreach($list as $id => $value)
					{
						echo $value."<BR>";
					}
				}
				
			} 	
				
			
			if(empty($_REQUEST['output']))
			{
				echo "</div> <!-- end #msg -->";
			}
		} 
		else							
		{
			if(isset($msgSucess) && $msgSucess != "")
			{
				if(empty($_REQUEST['output']))
					echo '<div id="message_sucess" class="message message_sucess">';
				
				echo $msgSucess;
				
				if(empty($_REQUEST['output']))
					echo "</div> <!-- end #msg -->";
			}
		}
		
		#adicao da pagina requisitada
		require_once($url_include);
		
		
		if(empty($_REQUEST['output']))
		{
			echo "</div><!-- end #contentIncluded -->";
		}

	}
	
	if(empty($_REQUEST['output']))
	{
?>				</div>
				<!-- end #main -->
			</div>
			<!-- end #content -->
			<div class="clear"></div>
			<div id="footer">
				<a class="logo-trajettoria" href="http://www.trajettoria.com" target="_blank">Trajettoria</a>
			</div>
			<!-- end #footer -->
		</div>
		<!-- end #wrapper -->
	</div>
	<!-- end #background -->
</body>
</html>
<?php
	}#captura o conteudo
	$content = ob_get_contents();
		
	#fecha o buffer de captura
	ob_end_clean();
	
	#imprime o conteudo
	echo $content;
	
	}
	
	
	
?>