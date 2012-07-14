<?php
/**
 * Classe para consulta dos dados dos destinatarios
 * Essa classe possui tambem  estrutura para se comunicar com a classe de paginacao
 * @author Elaine
 *
 */
class TRecipients 
{
	
	private $ConnectorDB;
	private $obj_Navigator;
	
	public function __construct(TDbConnector $connector)
	{
		$this->ConnectorDB = $connector;
		$this->obj_Navigator = new Navegacao();
	}
	
	public function SearchRecipients($Form = "", $LimiteTamanho = 0, $LimiteInicio = 0)
	{
		#Consultando a tabela de recipients
		$query = "SELECT * FROM recipients 
				 
				  WHERE 1 = 1 "; //para facilitar a adicao de filtros
		
		/** Filtros **/
		if(is_array($Form))
		{
			
			if($Form['rec_name']) $query .= " AND name LIKE '%".trim(FilterData($Form['rec_name']))."%' ";
			if($Form['rec_email']) $query .= " AND email LIKE '%".trim(FilterData($Form['rec_email']))."%' ";
			if($Form['rec_lang'] && $Form['rec_lang'] != '-1' )  $query .= " AND lang = '".trim(FilterData($Form['rec_lang']))."' ";
			if($Form['rec_status'] != '-1') $query .= " AND send_verification = ".(int)trim(FilterData($Form['rec_status']))." ";
			

			
			/** Order **/
			
			if(!empty($Form['campo_order']))
			{
				$query .= " ORDER BY ".$Form['campo_order']." ".$Form['tipo_order'];
			}
			else
				$query .= " ORDER BY name ASC ";
			
			if($LimiteTamanho > 0)
			{
				
					$query .= " LIMIT ".$LimiteTamanho." OFFSET ".$LimiteInicio; 
			}
		}
		
		
		
		return $this->ConnectorDB->GetAllResults($query);
		
	}
	
	 public function CountSearchRecipients($Form = "")
	 {
	 	#Consultando a tabela de recipients
		$query = "SELECT count(*) as total FROM recipients 
					WHERE 1 = 1 ";
		
		
		
	 	/** Filtros **/
		if(is_array($Form))
		{
			
			if($Form['rec_name']) $query .= " AND name LIKE '%".trim(FilterData($Form['rec_name']))."%' ";
			if($Form['rec_email']) $query .= " AND email LIKE '%".trim(FilterData($Form['rec_email']))."%' ";
			if($Form['rec_lang'] && $Form['rec_lang'] != '-1' )  $query .= " AND lang = '".trim(FilterData($Form['rec_lang']))."' ";
			if($Form['rec_status'] != '-1') $query .= " AND send_verification = ".(int)trim(FilterData($Form['rec_status']))." ";

		}
		
		
		$Resultado = $this->ConnectorDB->GetResult($query);
		
		return $Resultado['total'];
	 }
	
 	public function Navigator()
	{
	 	return $this->obj_Navigator;
	}
	
	
	
	public function FilterRecipients($lang, $retorno = null)
	{
		
		/** CONFIGURACAO DA NAVEGACAO **/
	 	
	 	
	 	$LangList = $this->TrataTipoItem(unserialize(LANGS));
	 	$StatusRecList = $this->TrataTipoItem(unserialize(EMAIL_STATUS));
	 	
	 	
	 	$this->obj_Navigator->AdicionaItem("text",'rec_name', __("Nome"), "",'20px');
	 	$this->obj_Navigator->AdicionaItem("text",'rec_email', __("E-mail"), "",'20px');
	 	$this->obj_Navigator->AdicionaItem("select",'rec_lang', __("Idioma"), "-1",'20px' ,$LangList);
	 	$this->obj_Navigator->AdicionaItem("select",'rec_status', __("Status"), "-1",'20px' ,$StatusRecList);
	 	$this->obj_Navigator->AdicionaItem("empty",'rec_options',__("Opções"),"",'10px',"");
		$this->obj_Navigator->AdicionaItem("empty",'rec_checar',__("Marcar"),"",'10px',"");
	 	
	 	
	 	$this->obj_Navigator->AdicionaItemRegPorPagina(30);
	 	$this->obj_Navigator->AdicionaItemRegPorPagina(60);
	 	$this->obj_Navigator->AdicionaItemRegPorPagina(100);
	 	$this->obj_Navigator->AdicionaItemRegPorPagina(500);
	 	
	 	$this->obj_Navigator->AdicionaRegPagPadrao(NUMBER_PER_PAGE);
	 	
	 	$this->obj_Navigator->ConfiguraFiltro(
	 				'index.php',
	 				'module=control_panel&page=recipients&lang='.$lang.'&command=SearchRecipients&output=JQUERY',
	 				'ListRec',
	 				'index.php', //atualiza paginador
	 				'module=control_panel&page=recipients&lang='.$lang.'&command=UpdateNavigator&output=JQUERY',
	 				'index.php', //muda pagina
	 				'module=control_panel&page=recipients&lang='.$lang.'&command=ChangePage&output=JQUERY'
	 				);
	 	
	 	$retorno['Navigator'] = $this->obj_Navigator->Header($retorno);
	 	
	 	return $retorno;
		
	} 
	
	public function FilterFieldsNavigator($Campos)
	{
		
	 	$Campos['rec_name'] = (empty($Campos['rec_name']))?"":$Campos['rec_name'];
		$Campos['rec_email'] = (empty($Campos['rec_email']))?"":$Campos['rec_email'];
		$Campos['rec_lang'] = (empty($Campos['rec_lang']))?"":$Campos['rec_lang'];
		$Campos['rec_status'] = (!isset($Campos['rec_status']))?"-1":$Campos['rec_status'];
		
		$Campos['order'] = (empty($Campos['order']))?"":$Campos['order'];
		$Campos['tipo_order'] = (empty($Campos['tipo_order']))?"":$Campos['tipo_order'];
		
		
	 	$Form['rec_name'] = $Campos['rec_name'];
		$Form['rec_email'] = $Campos['rec_email'];
		$Form['rec_lang'] = $Campos['rec_lang'];
		$Form['rec_status'] = $Campos['rec_status'];
		
		
		switch ($Campos['order'])
		{
			case "rec_name":
				$Form['campo_order'] = "recipients.name";
				break;
			case "rec_email":
				$Form['campo_order'] = "recipients.email";
				break;	
			case "rec_lang":
				$Form['campo_order'] = "recipients.lang";
				break;
			case "rec_status":
				$Form['campo_order'] = "recipients.send_verification";
				break;
			default:
				$Form['campo_order'] = "recipients.name";
				break;
		}
		
		
		
		$Form['tipo_order'] = $Campos['tipo_order'];

		
	 	return $Form;
	 	
	 }
	 

	 
	 private function TrataTipoItem($items, $description = "")
	 {
	 	$lista['-1'] = "-";
	 	
	 	if(is_array($items))
	 	{
	 		foreach($items as $id => $value)
	 		{
	 			if(!empty($description))
	 				$lista[$id] = ucfirst($value[$description]);
	 			else 
	 				$lista[$id] = ucfirst($value);
	 		}
	 	}
	 	
	 	return $lista;
	 }
}
?>