<?php 

destroiSessao();

$msg = urlencode(__('Logout efetuado com sucesso'));
$lang = (empty($_REQUEST['lang']))?LANG_DEFAULT:FilterData($_REQUEST['lang']);
	
successMsg(LOCAL, "", "", $msg, $lang)


?>