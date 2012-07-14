<?php
/**
 * @author Renato Zuma Bange
 */

define("REAL_PATH", "/var/www/vhosts/trajettoria.com/httpdocs/clientes/questionario/");	

//define("REAL_PATH", "C:/AppServ/www/questionario/");	
define("LOG_FILE", "/var/www/vhosts/trajettoria.com/httpdocs/clientes/questionario/log_send_emails.txt");	

$line = "";
function add_log($str)
{
	global $line;
	$agora = nowDateTime();
	$line .= "[$agora] $str\r\n";
	return true;
}

function save_log()
{
	global $line;
	return file_put_contents(LOG_FILE, $line, FILE_APPEND);
}
 
// Require files
// -=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
require_once(REAL_PATH . 'includes/constants.php');						# define system configuration constants
require_once(REAL_PATH . 'includes/TDbConnector.php');                   # manipulate all database queries
require_once(REAL_PATH . 'includes/util.php');                           # provide some usefull functions we will be needing
require_once(REAL_PATH . 'includes/class.phpmailer.php');                # help us sending e-mails around

// Prepare recipients
// -=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$conn = new TDbConnector();                                     # connect to db

$now = NowDatetime();                                           # get current date/time using MySQL date format
$dateArr = explode($now, ':');                                  # explode current date/time to get rid of minutes and seconds
$currentHour = ($dateArr[0] . ':00:00');                        # current hour starts at...
$nextHour = ($dateArr[0] . ':59:59');                           # current hour ends at...

$res1 =                                                         # how many emails have we already sent at current hour?
     $conn->GetResult("SELECT COUNT(*)
                         AS qtySentEmails
                         FROM sentemails
                         WHERE sent_status = 1
                         AND date BETWEEN '$currentHour' AND '$nextHour'"); 

$res2 =                                                         # how many e-mails an hour are we able to send?
     $conn->GetResult("SELECT value
                         AS maxEmailsHour
                         FROM settings
                         WHERE param='email_maxEmailsHour'");


add_log('PONTO 1');

if ($res1['qtySentEmails']>=$res2['maxEmailsHour']) {           # ...if we have sent enough e-mails

add_log('Já enviou emails suficientes. About to die.');

   die();                                                       # exit script
}else {                                                         # ...else - we may have failed sending some earlier or this is the first time we try it at current hour. Let's check...
   $res =                                                       # how many e-mails have we failed to send at current hour?
        $conn->GetResult("SELECT COUNT(*)
                          AS qtyFailedRecipients
                          FROM sentemails
                          WHERE sent_status = 0
                          AND date BETWEEN '$currentHour' AND '$nextHour'");

add_log('PONTO 2');
						  
   if ($res['qtyFailedRecipients'] > 0) {                       # ...if we have failed sending any
      $failedRecipients_ids =                                   # create array of failed recipients_ids
                            $conn->GetAllResults("SELECT recipient_id
                                              FROM sentemails
                                              WHERE sent_status = 0
                                              AND date BETWEEN '$currentHour' AND '$nextHour'");
add_log('Temos emails que falharam...');                                              
	  $matches = "";
	  if(is_array($failedRecipients_ids))
	  {
	  add_log('failedRecipients_ids é um array');
			foreach($failedRecipients_ids as $r)
			{
				$matches[] = $r['recipient_id'];
			}
	  }
	  
      $matches = implode(',', $matches);           # organize results with commas in a single String
add_log('matches: $matches');
      $recipients =                                             # use above String to get all information about the recipients that we will try to e-mail AGAIN
                  $conn->GetAllResults("SELECT *
                                        FROM recipients
                                        WHERE id IN ( '$matches' )
                                        LIMIT 0," . $res2['maxEmailsHour']);
                                        
   }else {                                                      # ...else - that's the last possibility: it's certainly the first time we are running this script at current hour
add_log('Primeira vez que o script está sendo rodado nesta hora');   
   
																# get all information about the recipients that we will try to e-mail this time, limit recipients by max allowed per hour
   $recipients = $conn->GetAllResults("SELECT *, recipients.id
										FROM recipients
										LEFT JOIN sentemails ON sentemails.recipient_id = recipients.id
										WHERE send_verification =0
										GROUP BY recipients.id
										HAVING COUNT( sentemails.id ) <3
                                        LIMIT 0," . $res2['maxEmailsHour']);          
   }
}

// Prepare PHPMailer stuff (SMTP server connection and sender info)
// -=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$email_settings = $conn->GetAllResults("SELECT * FROM settings");
									  
add_log('Configurações supostamente obtidas.');  
if(is_array($email_settings))
{

	foreach($email_settings as $setting) {							# iterate over email settings to define which value corresponds to each param

	add_log('inside foreach email_settings. ' . $setting['param'] . " | " . $setting['value']);  

		switch ($setting['param']) {								
		case 'email_host':
			$host = $setting['value'];								# $host = SMTP server address
			
			add_log('email_host: ' . $host);  
			break;
		case 'email_username':
			$username = $setting['value'];							# $username = SMTP server user
			break;
		case 'email_password':
			$password = $setting['value'];							# $password = SMTP server password
			break;
		case 'email_fromName':
			$fromName =utf8_encode($setting['value']);							# $fromName = sender name (could be administrator's)
			break;
		case 'email_fromEmail':
			$fromEmail = $setting['value'];							# $fromEmail = sender e-mail (could be administrator's)
			break;
		case 'email_port':
			$port = $setting['value'];								# $port = SMTP port for the server
			break;
		case 'email_SMTPSecure':
			$SMTPSecure = $setting['value'];						# $SMTPSecure = prefix to the server
			break;
		case 'email_SMTPAuth':
			$SMTPAuth = $setting['value'];							# $SMTPAuth = does it use SMTP auth? (optional)
		}
	}
}
else //email_settings is not array
{
	add_log('email_settings is NOT array');
	save_log();
	exit();
}
add_log('Obteve as configurações de envio de emails');

// Send e-mails
// -=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
if(is_array($recipients))
{

add_log('recipients é um array');
	foreach($recipients as $recipient) {


		$contents = file_get_contents(REAL_PATH . 'email/templates/' . $recipient['lang'] . '.htm');	# get contents for body according to recipient language
		$body = str_replace('[[KEY]]', $recipient['key'], $contents);				# replace key place holder with actual recipient key
		$body = str_replace('[[NAME]]', $recipient['name'], $body);					# replace name place holder with actual recipient name
		$body = str_replace('[[URL]]', URL, $body);										# replace url place holder with actual url
		$body = str_replace('[[SIGNUP]]', URL."".SIGNUP, $body);										# replace url place holder with actual url
		
		


		if($recipient['lang'] == 'es_ES') $assunto = 'Trabajo de investigación sobre la práctica de Ultrasonido Endoscópico (EUS) en América Latina';
		else if($recipient['lang'] == 'en_US') $assunto = 'EUS practice survey in Latin America';
		else $assunto = 'Pesquisa sobre a prática da Ecoendoscopia (EUS) na América Latina';


		add_log('Vai mandar email para ' . $recipient['email'] . ' (id: ' . $recipient['id'] . ')');
		$sent = SendMail($recipient['name'], $recipient['email'], $assunto, $body, $fromName, $fromEmail, $host, $SMTPAuth, $SMTPSecure, $port, $username, $password);
																			# send e-mail setting all parameters

		// Update database tables
		// -=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		if ($sent)															# If sent, change flags sent_status (sentemails table) and send_verification (recipients table) to '1'.
		{
		add_log('Email enviado');
			$conn->Query("INSERT INTO sentemails (date, sent_status, recipient_id) VALUES ('$now', 1, '$recipient[id]')");
			$conn->Query("UPDATE recipients SET send_verification=1 WHERE id='$recipient[id]'");
		
		} else																# Else, make sure we will be able to verify what to send again later.
		{
		add_log('Email NÃO enviado');
			$conn->Query("INSERT INTO sentemails (date, sent_status, recipient_id) VALUES ('$now', 0, '$recipient[id]')");
		}
	}
} // end if is_array

add_log('Fim do script');
add_log('========================================================');
save_log();
//echo $line;
?>
