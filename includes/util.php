<?php

function check_cpf($cpf)
{
	$dv_informado = substr($cpf, 9,2);
	for($i=0; $i<=8; $i++)
	{
		$digito[$i] = substr($cpf, $i,1);
	}

	/*Agora sera calculado o valor do decimo digito de verificacao*/
	$posicao = 10;
	$soma = 0;
	for($i=0; $i<=8; $i++)
	{
		$soma = $soma + $digito[$i] * $posicao;
		$posicao = $posicao - 1;
	}
	$digito[9] = $soma % 11;
	if($digito[9] < 2)
	{
		$digito[9] = 0;
	}
	else
	{
		$digito[9] = 11 - $digito[9];
	}
	/*Agora sera calculado o valor do decimo primeiro digito de verificacao*/
	$posicao = 11;
	$soma = 0;
	for ($i=0; $i<=9; $i++)
	{
		$soma = $soma + $digito[$i] * $posicao;
		$posicao = $posicao - 1;
	}
	$digito[10] = $soma % 11;
	if ($digito[10] < 2)
	{
		$digito[10] = 0;
	}
	else
	{
		$digito[10] = 11 - $digito[10];
	}
	/*Nessa parte do script sera verificado se o digito verificador e igual ao informado pelo
	 usuario*/
	$dv = $digito[9] * 10 + $digito[10];
	return ($dv == $dv_informado);
}

function SendMail($toName, $toMail, $subject, $body, $fromName=NULL, $fromMail=NULL, $host=NULL, $smtpAuth=NULL, $smtpSecure=NULL, $port=NULL, $username=NULL, $password=NULL)
{	
	 
	// IF any optional param is NULL, prepare PHPMailer stuff (SMTP server connection and sender info)
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	if (empty($fromName) || empty($fromMail) || empty($host) || empty($smtpAuth) || empty($smtpSecure) || empty($port) || empty($username) || empty($password))
	{
		$conn = new TDbConnector();
		$email_settings = 												# get only the settings we want (params starting with 'email')
						$conn->GetAllResults("SELECT * 
									  		  FROM settings 
									  		  WHERE param LIKE ( 'email_%' )");
		foreach($email_settings as $setting) {							# iterate over email settings to define which value corresponds to each param 
   			switch ($setting['param']) {								
			case 'email_host':
        		$host = $setting['value'];								# $host = SMTP server address
        		break;
   			case 'email_username':
        		$username = $setting['value'];							# $username = SMTP server user
        		break;
   			case 'email_password':
        		$password = $setting['value'];							# $password = SMTP server password
        		break;
   			case 'email_fromName':
        		$fromName = $setting['value'];							# $fromName = sender name (could be administrator's)
        		break;
   			case 'email_fromEmail':
        		$fromMail = $setting['value'];							# $fromEmail = sender e-mail (could be administrator's)
        		break;
   			case 'email_port':
        		$port = $setting['value'];								# $setting = SMTP port for the server
        		break;
   			case 'email_SMTPSecure':
        		$smtpSecure = $setting['value'];						# $SMTPSecure = prefix to the server
        		break;
   			case 'email_SMTPAuth':
        		$smtpAuth = $setting['value'];							# $SMTPAuth = does it use SMTP auth? (optional)
   			}
		}
		
	
		
		
	}

	// Create PHPMailer object
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail = new PHPMailer();

	// Define server connection info
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->IsSMTP(); 												# Will be SMTP
	$mail->Host 		= $host; 									# SMTP server add
	$mail->SMTPAuth 	= $smtpAuth; 								# Does it use SMTP auth? (optional)
	$mail->SMTPSecure 	= $smtpSecure; 								# Sets the prefix to the server
	$mail->Port			= $port;									# Set the SMTP port for the server
	$mail->Username		= $username;								# SMTP server user
	$mail->Password 	= $password; 								# SMTP server password

	// Define sender
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->From = $fromMail;										# Your e-mail
	$mail->FromName = $fromName; 									# Your name

	// Define receiver(s)
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->AddAddress($toMail, $toName);
	//$mail->AddCC('ciclano@site.net', 'Ciclano'); // CC
	//$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // BCC

	// Define msg type
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->IsHTML(true); 											# Will be HTML
	$mail->CharSet = 'utf-8'; // Charset (optional)

	// And finally the MESSAGE (Subject and Body)
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->Subject  = $subject;		 								# Subject
	$mail->Body = $body;											# Body
	$mail->AltBody = "";											# Alternative Body for non-HTML content
								

	// Define attachment (optional)
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	// $mail[$i]->AddAttachment($fileName, $fileName);  				# Add attachment (fileName, newFileName)

	// Send e-mail
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$sent = $mail->Send();											# Pa!

	// Cleaners
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();
	
	return $sent;
	
}
function FindString($csv, $needle) {
	;
}
function GetPassword($length)
{
	$valid_chars = 'abcdxywzABCDZYWZ0123456789';
	$max = strlen($valid_chars) - 1;
	for($i=0; $i < $length; $i++)
	{
		$password_nocoded .= $valid_chars{mt_rand(0, $max)};
	}
	return $password_nocoded;
}

function PrimeirosCaracteres($str, $n)
{
	$temp = (strlen($str) > $n) ? "..." : "";
	return substr($str, 0, $n) . $temp;
}

function remove_accents($string) {
	$replacements = array(
	  'a' => 'a',
	  'a' => 'a',
	  'a' => 'a',
	  'a' => 'a',
	  'e' => 'e',
	  '�' => 'e',
	  'e' => 'e',
	  'i' => 'i',
	  '�' => 'i',
	  'o' => 'o',
	  '�' => 'o',
	  'o' => 'o',
	  'o' => 'o',
	  '�' => 'o',
	  'u' => 'u',
	  '�' => 'u',
	  '�' => 'u',
	  'A' => 'A',
	  'A' => 'A',
	  'A' => 'A',
	  'A' => 'A',
	  'E' => 'E',
	  '�' => 'E',
	  'E' => 'E',
	  '�' => 'I',
	  'I' => 'I',
	  '�' => 'I',
	  'O' => 'O',
	  '�' => 'O',
	  '�' => 'O',
	  'U' => 'U',
	  '�' => 'U',
	  '�' => 'U',
	);
	return strtr($string, $replacements);
}

function NowDatetime()
{
	return date("Y-m-d H:i:s");
}

function FilterData($variable)
{
	#gera warning se a conexao com o banco nao estiver estabelecida
	return mysql_real_escape_string(strip_tags($variable));
}

function create_guid($namespace = '') {
	static $guid = '';
	$uid = uniqid("", true);
	$data = $namespace;
	$data .= $_SERVER['REQUEST_TIME'];
	$data .= $_SERVER['HTTP_USER_AGENT'];
	$data .= $_SERVER['REMOTE_ADDR'];
	$data .= $_SERVER['REMOTE_PORT'];
	$hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
	$guid = substr($hash,  0,  8) .
	'-' .
	substr($hash,  8,  4) .
	'-' .
	substr($hash, 12,  4) .
	'-' .
	substr($hash, 16,  4) .
	'-' .
	substr($hash, 20, 12);
	return $guid;
}

$key = create_guid();

function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
	$lmin = 'abcdefghijklmnopqrstuvwxyz';
	$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$num = '1234567890';
	$simb = '!@#$%*-';
	$retorno = '';
	$caracteres = '';

	$caracteres .= $lmin;
	if ($maiusculas) $caracteres .= $lmai;
	if ($numeros) $caracteres .= $num;
	if ($simbolos) $caracteres .= $simb;

	$len = strlen($caracteres);
	for ($n = 1; $n <= $tamanho; $n++) {
		$rand = mt_rand(1, $len);
		$retorno .= $caracteres[$rand-1];
	}
	return $retorno;
}

function redireciona($link){
	
	header("Location:".$link);
	//echo "<script type='text/javascript'>document.location.href='$link'</script>";
}
function __($text) {
	return gettext($text);
}

/**
 * 
 * Enter description here ...
 * @param unknown_type $constantUrl
 * @param unknown_type $module
 * @param unknown_type $page
 * @param unknown_type $successMessage
 * @param unknown_type $lang
 * @param string $other_parameter começar a string com & <param> = value
 */
function successMsg($constantUrl, $module, $page, $successMessage, $lang , $other_parameter = null)
{
	echo "<script type='text/javascript'>document.location.href='".$constantUrl."?module=$module&page=$page&msgSucess=$successMessage&lang=$lang".$other_parameter."'</script>";
}

/**
 * 
 * Enter description here ...
 * @param unknown_type $constantUrl
 * @param unknown_type $module
 * @param unknown_type $page
 * @param unknown_type $successMessage
 * @param unknown_type $lang
 * @param string $other_parameter começar a string com & <param> = value
 */
function errorMsg($constantUrl, $module, $page, $errorMessage, $lang, $other_parameter = null) 
{
	echo "<script type='text/javascript'>document.location.href='".$constantUrl."?module=$module&page=$page&msgError=$errorMessage&lang=$lang".$other_parameter."'</script>";
}


function iniciaSessao()
{
	
	if(!isset($_SESSION)) session_start();
	
	$idQuery =  $_SESSION['idQuery'];
	$userQuery = $_SESSION['userQuery'];
	$credentialQuery = $_SESSION['credentialQuery'];
	$statusQuery = $_SESSION['statusQuery'];
	
	destroiSessao();
	
	#Iniciar uma Sessao (session e similar a uma gaveta movel)
	session_start();

		
	#Gravo as informacoes das variaveis dentro das sessoes
	$_SESSION['server_usuarioId'] = $idQuery;
	$_SESSION['server_usuario'] = $userQuery;
	$_SESSION['server_nivel'] = $credentialQuery;
	$_SESSION['server_status'] = $statusQuery;
	

	

}

function destroiSessao()
{
	if (isset($_SESSION))
	{
		/*
		unset($_SESSION['server_usuarioId']);
		unset($_SESSION['server_usuario']);
		unset($_SESSION['server_nivel']);
		unset($_SESSION['server_status']);
		*/
		session_unset();	
		session_destroy();
		
		
	}
}

#valida email
function validaEmail($email) 
{
	$conta = "^[a-zA-Z0-9\._-]+@";
	$domino = "[a-zA-Z0-9\._-]+.";
	$extensao = "([a-zA-Z]{2,4})$";
	
	$pattern = '/'.$conta.$domino.$extensao.'/';
	
	if (preg_match($pattern, $email))
		return true;
	else
		return false;
}

// Function to calculate square of value - mean
function sd_square($x, $mean) { return pow($x - $mean,2); }

// Function to calculate standard deviation (uses sd_square)    
function sd($array) 
{
	if((count($array)-1) == 0 ) return 0;
	// square root of sum of squares devided by N-1
	return sqrt(array_sum(array_map("sd_square", $array, array_fill(0,count($array), (array_sum($array) / count($array)) ) ) ) / (count($array)-1) );
}


function parte_inteira($str, $delimiter = ',') // $str no formato '...999,999...'
{
	$exploded = explode($delimiter, $str);
	
	/*
	echo "<br>>>>>>> DENTRO DE PARTE INTEIRA <<<<<<<<<br>";
	echo "str: $str | delimiter: $delimiter | return: " . $exploded[0];
	echo "<br><Br>";
	*/
	
	return $exploded[0];
}

// Ex.: value = 90, n_per_group = 30 --> considerando uma escala com itens agrupados de 30 em 30, o valor 90 estaria no 3o. elemento, índice 2 de uma matriz com chave iniciando em zero --> return 2
function find_group($value, $n_per_group, $valor_inicial_x=NULL) 
{
	if($valor_inicial_x)
	{
		return parte_inteira(($value - $valor_inicial_x)/($n_per_group), DS);	
	}
	else
	{
		return parte_inteira($value/($n_per_group), DS);
	}
}

function print_array($arr)
{
	if(is_array($arr))
	{
		echo "<br><pre>";
		print_r($arr);
		echo "</pre><br>";
	}
}
 ?>
