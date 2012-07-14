<style type="text/css">
	.icons {margin: 0; padding: 0;}
	.icons label {margin: 2px; position: relative; padding: 4px 0; float: left;  list-style: none; border:none; background:none;}
	.icons span.ui-icon {float: left; margin: 0 2px;}
</style>

<?php echo $retorno['Script'];?>
<div id="NavBar">
<table width="100%" class="TableClean">

    <tbody>
        <tr class="Alt">
          <th align="Center">P&aacute;gina</th>
          <th align="Center">N&ordm; de Paginas</th>
          <th align="Center">N&ordm; de Registros</th>
          <th align="Center">Resultados</th>
        </tr>
        <tr>
          <td align="Center">
           <button id="iniPag" class="MudaPagina">&lt;&lt;</button>
           <button id="antPag" class="MudaPagina">&lt;</button>
		   <input type="text" name="PaginaAtual" id="PaginaAtual" class="" value="<?php echo $retorno['PagAtual'];?>" size="5" />
           <button id="proxPag" class="MudaPagina">&gt;</button>
           <button id="fimPag" class="MudaPagina">&gt;&gt;</button>
		   <input type="hidden" name="RegistroInicial" id="RegistroInicial" value="<?php echo $retorno['RegistroInicial'];?>" />
          </td>
          <td align="Center"><label for="TotalPaginas" id="TotalPaginas"><?php echo $retorno['TotalPaginas'];?></label></td>
          <td align="Center"><label for="TotalRegistros" id="TotalRegistros"><?php echo $retorno['TotalRegistros'];?></label></td>
          <td align="Center">
		  <?php if(is_array($retorno['ItemRegPagina'])) { ?>
           <select name="NavLimit" id="NavLimit">		   
			   <?php 
			   
			   		foreach($retorno['ItemRegPagina'] as $id => $valor) { 
			   			if($retorno['TotalRegPagina'] == $valor) $selected = "selected='selected'";
						else  $selected = "";
			   ?>
					<option value="<?php echo $id;?>" <?php echo $selected;?>><?php echo $valor;?> por p&aacute;g.</option>
			   <?php } ?>
           </select>
		   <?php } ?>
          </td>  
      </tr>
  </tbody>
       </table>
	   </div>