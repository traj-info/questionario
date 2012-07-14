<?php include 'control_panel/admin_auth.php'; ?>

<?php 

$retorno['Recipients'] = (isset($retorno['Recipients']))?$retorno['Recipients']:null;


if(is_array($retorno['Recipients']))
{ 

?>

<?php 
	
	$SendStatus = unserialize(EMAIL_STATUS);
	$LangList = unserialize(LANGS);

	foreach($retorno['Recipients'] as $id => $conteudo)
	{
		//utilizar utf8_encode porque a lista e retornada via ajax e precisa ser em utf8
?>
	<tr id="<?php echo $id;?>">
		<td class="rc_name"><?php echo ($conteudo['name']);?></td>
		<td class="rc_email"><?php echo ($conteudo['email']);?></td>
		<td class="rc_lang"><?php echo $LangList[$conteudo['lang']];?></td>
		<td class="rc_send_status"><?php echo ($SendStatus[$conteudo['send_verification']]);?></td>
		<td class="rc_options">
		<ul id="menu_recipient">	
			<li><a href="index.php?module=control_panel&page=recipients_sent_emails_log&lang=<?php echo $_REQUEST['lang'];?>&recipient_id=<?php echo $conteudo['id'];?>"><?php echo __('Ver log');?></a></li>
		</ul>
		</td>
		<td class="rc_checar">
			<input type="checkbox" name="recipient[]" class="cbox_recipient cb_list_submit" value="<?php echo $conteudo['id'];?>">
		</td>
	</tr>
<?php
	} 
?>	


	
<?php 
}?>
