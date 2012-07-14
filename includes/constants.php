<?php 
	#DECIMAL SEPARATOR
	define("DS", ",");
	// remoto: ","
	// local: "."

	#PATH LANG
	define("PATH_LANG", "lang/");
	define("DOMAIN_LANG", "messages");

	#REAL PATH
	define("REAL_PATH", "/var/www/vhosts/trajettoria.com/httpdocs/clientes/questionario/");	

	#DEFININDO URL SITE/SISTEMA
	define("URL", "http://www.cleus-encuesta.com/");

	#CAMINHO PARA INDEX
	define("INDEX", "http://www.cleus-encuesta.com/index.php");

	#CAMINHO PARA INDEX LOCALHOST
	define("LOCAL", "http://www.cleus-encuesta.com/index.php");

	#CAMINHO PARA LOGIN
	define("LOGIN", LOCAL."?module=user&page=login");
	
	#MODULO PARA LOGIN
	define("MOD_LOGIN", "user");
	
	#PAGE PARA LOGIN
	define("PAGE_LOGIN", "login");
	
	
	
	#CAMINHO PARA SIGNUP
	define("SIGNUP", "index.php?module=user&page=signup");
	
	#CAMINHO PARA MAIN PAGE 
	define("MAIN_USER", "index.php?module=user&page=modelo_main");
	
	
	
	
	#CREDENCIAL DO USUARIO NO SISTEMA
	define("USER", "1");
	define("ADMIN", "2");
	define("SUPERADMIN", "3");

	#STATUS DO USUARIO
	define("INATIVO", "1");
	define("ATIVO", "2");
	define("APROVADO", "3");
	define("NEGADO", "4");

	
	define('LANG_DEFAULT','pt_BR');
	define('PAGE_DEFAULT','welcome');
	
	define('PAGE_USER_DEFAULT','modelo_main');
	define('MOD_USER_DEFAULT','user');
	
	#AUTH USER
	define('MOD_USER_NIVEL', 1);
	define('MOD_USER_STATUS', 2);
	
	#AUTH ADMIN
	define('MOD_CONTROL_PANEL_NIVEL', 2);
	
	#EMAIL STATUS
	define('NOT_SENT', 0);
	define('SENT', 1);
	
	#SURVEY STATUS
	define('NOT_STARTED', 1);
	define('STARTED', 2);
	define('FINISHED', 3);
	
	define('SURVEY_MSG_NOT_STARTED','N&atilde;o iniciado');
	
	#SURVEY PAGE FINAL
	define('SURVEY_MSG_FINAL', 'survey_page_final');
	define('SURVEY_PAGE_INITIAL', 'survey_introduction');
	
	#SURVEY PAGE
	define('SURVEY_PAGES', serialize(array('1' => 'survey_introduction','2' => 'survey_page1', '3' => 'survey_page2','4' => 'survey_page3')));
	
	#for navigator, variable to use as default of items per page
	define('NUMBER_PER_PAGE',60);
	
	
	#LANGS
	define ("LANGS", serialize (array ("pt_BR" => "Portugu&ecirc;s", "es_ES" => "Espa&ntilde;ol", "en_US" => "English"))); // $langs = unserialize(LANGS);
	define ("EMAIL_STATUS", serialize (array ("0" => "N&atilde;o enviado", "1" => "Enviado"))); // $langs = unserialize(LANGS);
	
	
	#REPORT 
	define ("REPORT_USER_STATUS", serialize (array ("3" => "Ativo"))); // $langs = unserialize(LANGS);
	define ("REPORT_SURVEY_STATUS", serialize (array ("3" => "Conclu&iacute;do"))); // $langs = unserialize(LANGS);
	
	
	define("REPORT_MSG_EMPTY_VALUES","Para esses status de usu&aacute;rio e de question&aacute;rio, n&atilde;o foram encontradas respostas para essa quest&atilde;o");
	
	
?>