<?php if(is_array($retorno['Filtros'])) { ?>
<tr>
<?php
 
	foreach($retorno['Filtros'] as $id => $item) 
	{ 
	
?>
	<th width="<?php echo $item['tamanho'];?>" class="icons" align="left" name="lbl_<?php echo $item['nome'];?>" id="lbl_<?php echo $item['nome'];?>">
	<?php if($item['tipo'] != "empty") { ?>
		<label class="filtro_order" for="<?php echo $item['nome'];?>"><span class="ui-icon ui-icon-triangle-2-n-s"></span><?php echo $item['descricao'];?></label><BR style="clear:both; ">
		<?php }else{ ?>
		<?php echo $item['descricao'];?>
		<BR style="clear:both; ">
		<?php }?>
<?php 		

		switch ($item['tipo'])
		{
			case 'select':
				if(is_array($item['itens']))
				{
	?>
					<select name="<?php echo $item['nome'];?>" class="filtro_combobox" id="<?php echo $item['nome'];?>">
	<?php 
					foreach($item['itens'] as $key => $text)
					{
						
						if($item['valor'] == $key)
						{
							$selected = 'selected="selected"';
						}
						else
							$selected = '';
	?>
						<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $text;?></option>
	<?php
					}
	?>
					</select>
	<?php
				}
							
				break;
									
			case 'text':
				
					
?>
					<input type="text" name="<?php echo $item['nome'];?>" id="<?php echo $item['nome'];?>" class="filtro_texto" 
					<?php if(!empty($item['tamanho_texto'])) echo 'size="'.$item['tamanho_texto'].'"'?>
					/>
<?php
				break;
				
			default:
				break;
		}
?>
	</th>
<?php 		
		}
?>
</tr>		
<?php } ?>