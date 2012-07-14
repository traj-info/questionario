<?php

class TUsers
{
	
	private $ConnectorDB;
	private $obj_Navigator;
	private $user_credential;
	
	public function __construct(TDbConnector $connector, $nivel_user)
	{
		$this->ConnectorDB = $connector;
		$this->obj_Navigator = new Navegacao();
		$this->user_credential = $nivel_user;
	}
	
	public function SearchUsers($Form = "", $LimiteTamanho = 0, $LimiteInicio = 0)
	{
		#Consultando a tabela de users
		$query = "SELECT 
						users.*, 
						userstatus.description as user_status_description,
						credentials.description as user_credential_description,
						(CASE WHEN surveystatus.id is null THEN ".NOT_STARTED."
						ELSE surveystatus.id
						END) as survey_status_id,
						COALESCE(surveystatus.description,'".SURVEY_MSG_NOT_STARTED."') as user_survey_description
						
				  FROM users 
				  INNER JOIN userstatus ON userstatus.id = users.user_status_id
				  INNER JOIN credentials ON credentials.id = users.credential_id
				  LEFT JOIN surveys ON surveys.user_id = users.id
				  LEFT JOIN surveystatus ON surveystatus.id = surveys.survey_status_id 
				  WHERE 1 = 1 "; //para facilitar a adicao de filtros
		
		if($this->user_credential != SUPERADMIN)
		{
			$query .= " AND credential_id != ".SUPERADMIN;
		}
		
		/** Filtros **/
		if(is_array($Form))
		{
			
			if($Form['user_name']) $query .= " AND name LIKE '%".trim(FilterData($Form['user_name']))."%' ";
			if($Form['user_lastname']) $query .= " AND lastname LIKE '%".trim(FilterData($Form['user_lastname']))."%' ";
			if($Form['user_username']) $query .= " AND username LIKE '%".trim(FilterData($Form['user_username']))."%' ";
			if($Form['user_email']) $query .= " AND email LIKE '%".trim(FilterData($Form['user_email']))."%' ";
			//if($Form['user_lang'] && $Form['user_lang'] != '-1' )  $query .= " AND lang = '".trim(FilterData($Form['user_lang']))."' ";
			if($Form['user_status'] != '-1') $query .= " AND user_status_id = ".(int)trim(FilterData($Form['user_status']))." ";
			if($Form['user_credential'] != '-1') $query .= " AND credential_id = ".(int)trim(FilterData($Form['user_credential']))." ";
			if($Form['survey_status'] != '-1')
			{
				if($Form['survey_status'] == NOT_STARTED)
				{
						$query .= " AND (survey_status_id = ".(int)trim(FilterData($Form['survey_status']))." OR survey_status_id is null)";
				}
				else
				{				
					$query .= " AND survey_status_id = ".(int)trim(FilterData($Form['survey_status']))." ";
				}
			}
			
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
	
	 public function CountSearchUsers($Form = "")
	 {
	 	#Consultando a tabela de recipients
		$query = "SELECT count(*) as total 
				FROM users
				INNER JOIN userstatus ON userstatus.id = users.user_status_id
				INNER JOIN credentials ON credentials.id = users.credential_id 
				LEFT JOIN surveys ON surveys.user_id = users.id
				LEFT JOIN surveystatus ON surveystatus.id = surveys.survey_status_id 
				 
				  
				WHERE 1 = 1 ";
		
	 	if($this->user_credential != SUPERADMIN)
		{
			$query .= " AND credential_id != ".SUPERADMIN;
		}
		
		
	 	/** Filtros **/
		if(is_array($Form))
		{
			
			if($Form['user_name']) $query .= " AND name LIKE '%".trim(FilterData($Form['user_name']))."%' ";
			if($Form['user_lastname']) $query .= " AND lastname LIKE '%".trim(FilterData($Form['user_lastname']))."%' ";
			if($Form['user_username']) $query .= " AND username LIKE '%".trim(FilterData($Form['user_username']))."%' ";
			if($Form['user_email']) $query .= " AND email LIKE '%".trim(FilterData($Form['user_email']))."%' ";
			//if($Form['user_lang'] && $Form['user_lang'] != '-1' )  $query .= " AND lang = '".trim(FilterData($Form['user_lang']))."' ";
			if($Form['user_status'] != '-1') $query .= " AND user_status_id = ".(int)trim(FilterData($Form['user_status']))." ";
			if($Form['user_credential'] != '-1') $query .= " AND credential_id = ".(int)trim(FilterData($Form['user_credential']))." ";
			if($Form['survey_status'] != '-1')
			{
				if($Form['survey_status'] == NOT_STARTED)
				{
						$query .= " AND (survey_status_id = ".(int)trim(FilterData($Form['survey_status']))." OR survey_status_id is null)";
				}
				else
				{				
					$query .= " AND survey_status_id = ".(int)trim(FilterData($Form['survey_status']))." ";
				}
			}
		}
		
	
		$Resultado = $this->ConnectorDB->GetResult($query);
		
		return $Resultado['total'];
	 }
	
 	public function Navigator()
	{
	 	return $this->obj_Navigator;
	}
	
	
	
	public function FilterUsers($lang, $retorno = null)
	{
		
		/** CONFIGURACAO DA NAVEGACAO **/
	 	
	 	
	 	$LangList = $this->TrataTipoItem(unserialize(LANGS));
	 	$StatusUserList = $this->TrataTipoItem($this->ListUserStatus(), 'description');
	 	$CredentialList = $this->TrataTipoItem($this->ListCredentials(), 'description');
	 	$SurveyStatusList = $this->TrataTipoItem($this->ListSurveyStatus(), 'description');
	 	
	 	
	 	
	 	$this->obj_Navigator->AdicionaItem("text",'user_name', __("Nome"), "",'20px');
	 	$this->obj_Navigator->AdicionaItem("text",'user_lastname', __("Sobrenome"), "",'20px');
	 	$this->obj_Navigator->AdicionaItem("text",'user_username', __("Usuario"), "",'20px');
	 	$this->obj_Navigator->AdicionaItem("text",'user_email', __("E-mail"), "",'20px');
	 	
	 	//$this->obj_Navigator->AdicionaItem("select",'user_lang', __("Idioma"), "-1",'20px' ,$LangList);
	 	$this->obj_Navigator->AdicionaItem("select",'user_status', __("Status"), "-1",'20px' ,$StatusUserList);
	 	$this->obj_Navigator->AdicionaItem("select",'user_credential', __("Credencial"), "-1",'20px',$CredentialList);
	 	$this->obj_Navigator->AdicionaItem("select",'survey_status',__("Status Questionário"),"",'20px',$SurveyStatusList);
	 	$this->obj_Navigator->AdicionaItem("empty",'user_options',__("Opções"),"",15,"");
	 	
	 	
	 	$this->obj_Navigator->AdicionaItemRegPorPagina(30);
	 	$this->obj_Navigator->AdicionaItemRegPorPagina(60);
	 	$this->obj_Navigator->AdicionaItemRegPorPagina(100);
	 	$this->obj_Navigator->AdicionaItemRegPorPagina(500);
	 	
	 	$this->obj_Navigator->AdicionaRegPagPadrao(60);
	 	
	 	$this->obj_Navigator->ConfiguraFiltro(
	 				'index.php',
	 				'module=control_panel&page=users&lang='.$lang.'&command=SearchUsers&output=JQUERY',
	 				'ListUsernames',
	 				'index.php', //atualiza paginador
	 				'module=control_panel&page=users&lang='.$lang.'&command=UpdateNavigator&output=JQUERY',
	 				'index.php', //muda pagina
	 				'module=control_panel&page=users&lang='.$lang.'&command=ChangePage&output=JQUERY'
	 				);
	 	
	 	$retorno['Navigator'] = $this->obj_Navigator->Header($retorno);
	 	
	 	return $retorno;
		
	} 
	
	public function FilterFieldsUsers($Campos)
	{
		
		$Campos['user_name'] = (!isset($Campos['user_name']))?"":$Campos['user_name'];
		$Campos['user_lastname'] = (!isset($Campos['user_lastname']))?"":$Campos['user_lastname'];
		$Campos['user_username'] = (!isset($Campos['user_username']))?"":$Campos['user_username'];
		$Campos['user_email'] = (!isset($Campos['user_email']))?"":$Campos['user_email'];
		
		//$Campos['user_lang'] = (!isset($Campos['user_lang']))?"-1":$Campos['user_lang'];
		$Campos['user_status'] = (!isset($Campos['user_status']))?"-1":$Campos['user_status'];
		$Campos['user_credential'] = (!isset($Campos['user_credential']))?"-1":$Campos['user_credential'];
		$Campos['survey_status'] = (!isset($Campos['survey_status']))?"-1":$Campos['survey_status'];
		
		
		$Campos['order'] = (!isset($Campos['order']))?"":$Campos['order'];
		$Campos['tipo_order'] = (!isset($Campos['tipo_order']))?"":$Campos['tipo_order'];
		
		
	 	
	 	$Form['user_name'] = $Campos['user_name'];
		$Form['user_lastname'] = $Campos['user_lastname'];
		$Form['user_username'] = $Campos['user_username'];
		$Form['user_email'] = $Campos['user_email'];		
		//$Form['user_lang'] = $Campos['user_lang'];
		$Form['user_status'] = $Campos['user_status'];
		$Form['user_credential'] = $Campos['user_credential'];
		$Form['survey_status'] = $Campos['survey_status'];
		
		switch ($Campos['order'])
		{
			case "user_name":
				$Form['campo_order'] = "users.name";
				break;
			case "user_lastname":
				$Form['campo_order'] = "users.lastname";
				break;	
			case "user_username":
				$Form['campo_order'] = "users.username";
				break;
			case "user_lang":
				$Form['campo_order'] = "users.lang";
				break;
			case "user_status":
				$Form['campo_order'] = "userstatus.description";
				break;
			case "user_credential":
				$Form['campo_order'] = "credentials.description";
				break;
			case "survey_status":
				$Form['campo_order'] = "surveystatus.description";
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
	 				$lista[$id] = mb_strtoupper(utf8_encode($value[$description]),'UTF-8');
	 			else 
	 				$lista[$id] = ($id);
	 		}
	 	}
	 	
	 	return $lista;
	 }
	 
	 public function ListUserStatus()
	 {
	 	$select = "SELECT * FROM userstatus ORDER BY id";
	 	$list =  $this->ConnectorDB->GetAllResults($select);
	 	
	 	foreach($list as $value)
	 	{
	 		$item[$value['id']] = $value;
	 	}
	 	
	 	return $item;
	 }
	 
 	 public function ListCredentials()
	 {
	 	$select = "SELECT * FROM credentials ";
	 	
	 	if($this->user_credential != SUPERADMIN)
		{
			$select .= " WHERE id != ".SUPERADMIN;
		}
		
	 	$select .= " ORDER BY id";
	 	$list =  $this->ConnectorDB->GetAllResults($select);
	 	
	 	foreach($list as $value)
	 	{
	 		$item[$value['id']] = $value;
	 	}
	 	
	 	return $item;
	 }
	 
	 public function ListSurveyStatus()
	 {
	 	$select = "	SELECT * 
					FROM surveystatus
					ORDER BY id";
		$list =  $this->ConnectorDB->GetAllResults($select);
	 	
	 	foreach($list as $value)
	 	{
	 		$item[$value['id']] = ($value);
	 	}
	 	
	 	return $item;
	
	 }
	 
}
?>