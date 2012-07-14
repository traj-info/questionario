<?php
//-------------------------------------FJR Webdesign---------------------------------------
// Arquivo: TDbConnector.php
// Versao: 1.1 - 19/04/2008
// Autor: Francisco Jr
// Descricao: Define a classe TDbConnector, de gerenciamento de conexoes e operacoes em banco
// de dados MySQL.
//-----------------------------------------------------------------------------------------
// Log de modificacoes:
// MODELO: vX.Y - DD/MM/AAAA - Autor - Modificacoes
// v1.1 - 19/04/2008 - Francisco Jr. - Retiradas funcoes ou parametros de funcoes que serviam apenas ao antigo FJR System.
//-----------------------------------------------------------------------------------------
// Atencao! O conteudo deste arquivo nao pode ser modificado sem previa autorizacao
// do desenvolvedor.
// Copyright (C) 2008 FJR Webdesign. Todos os direitos reservados
//-----------------------------------------------------------------------------------------

require_once 'TSystemComponent.php';

class TDbConnector extends TSystemComponent
{
    var $dbname;
    var $dblink;
    var $lastQuery;
    var $results;
    var $withPag;
    var $first = 0;
    var $limit = 0;
    var $paginatorObj;
    var $pagNumResults;

    //-----------------------------------------------------------------------------------------

    // Constructor Function: TDbConnector, purpose: Connect to specified database
    function TDbConnector($strdbname="")
    {
        // Load settings from parent class
        $settings = TSystemComponent::GetSettings();

        // Separate settings into variables
        $dbhost = $settings['dbhost'];
        $dbusername = $settings['dbusername'];
        $dbpassword = $settings['dbpassword'];
		if($strdbname == "") $strdbname = $settings['dbname'];
        $this->dbname = $strdbname;

        // Connect to database
        $this->dblink = mysql_connect($dbhost, $dbusername, $dbpassword);
        mysql_select_db($strdbname, $this->dblink);
        register_shutdown_function(array(&$this,'Close'));
    }

    //-----------------------------------------------------------------------------------------

    // Function: SetDbName, purpose: change the current db
    function SetDbName($strdbname)
    {
        $this->dbname = $strdbname;
        mysql_select_db($strdbname, $this->dblink);
    }

    //-----------------------------------------------------------------------------------------
    
    // Function: Query, purpose: Execute a query
    function Query($strQuery)
    {
        // If we're using the paginator, run query first without limits
    	if (isset($this->withPag) && $this->withPag)
        {
            // Modify the query to only selection count(*)
    		$countQuery = preg_replace('/SELECT .* FROM/','SELECT COUNT(*) FROM',$strQuery);

            // Run it
            $this->results = mysql_query($countQuery, $this->link);

            // Store the number of results
            $this->pagNumResults = @mysql_fetch_array($this->results);
            $this->pagNumResults = $this->pagNumResults['COUNT(*)'];

            // Handle limits for pagination.
            if ($this->limit != 0)
            {
                $strQuery.= " LIMIT $this->first, $this->limit";
		    }
        }

    	$this->lastQuery = $strQuery;
    	$this->results = mysql_query($strQuery, $this->dblink);
    	$this->withPag = false;

    	return $this->results;
    }
    
    //-----------------------------------------------------------------------------------------
    
    // Function FetchArray, purpose: Get array of query results
    function FetchArray($result)
    {
        return @mysql_fetch_array($result);
    }

    //-----------------------------------------------------------------------------------------

    // Function FetchAll, purpose: Get array of all query results
    function FetchAll($result)
    {
        $all = null;
        while($row = @mysql_fetch_array($result))
        {
            $all[] = $row;
        }
        return $all;
    }
    //-----------------------------------------------------------------------------------------
    
    // Function: Close, purpose: Close db connection; shutdown function previously registered within constructor
    function Close()
    {
        mysql_close($this->dblink);
    }
    
    //-----------------------------------------------------------------------------------------
    
    function SetFirst($rowNum)
    {
    	if ($rowNum > 0)
        {
    		$this->first = $rowNum;
    	}
    }

    //-----------------------------------------------------------------------------------------
    
    function SetLimit($rowNum)
    {
	    $this->limit = $rowNum;
    }

    //-----------------------------------------------------------------------------------------
    
    // Function: MovePointer, Purpose: Move the internal pointer of a result
    function MovePointer($result,$position)
    {
        mysql_data_seek($result, $position);
    	return true;
    }

    //-----------------------------------------------------------------------------------------
    
    // Function: GetResult, Purpose: Perform query + fetch_array automatically
    function GetResult($query)
    {
    	$doQuery = $this->Query($query);
	    return $this->FetchArray($doQuery);
    }
    //-----------------------------------------------------------------------------------------

    // Function: GetAllResults, Purpose: Perform query + FetchAll automatically
    function GetAllResults($query)
    {
    	$doQuery = $this->Query($query);
	    return $this->FetchAll($doQuery);
    }
    //-----------------------------------------------------------------------------------------
    
    //*** Function: GetLastResult, Purpose: Return the last result object ***
    function GetLastResult()
    {
    	return $this->results;
    }

    //-----------------------------------------------------------------------------------------
    
    // Function: GetLastQuery, Purpose: Returns the last database query, for debugging
    function GetLastQuery()
    {
	   return $this->lastQuery;
    }

    //-----------------------------------------------------------------------------------------
    
    // Function: GetNumRows, Purpose: Return row count
    function GetNumRows($result = null)
    {
    	if ($result == null)
        {
    		return @mysql_num_rows($this->results);
    	}
        else
        {
    		return @mysql_num_rows($result);
    	}
    }

    //-----------------------------------------------------------------------------------------
    
    // Function: GetPagNumResults, Purpose: Return pagNumResults
    function GetPagNumResults()
    {
	    return $this->pagNumResults;
    }

    //-----------------------------------------------------------------------------------------
    
    // Function: GetInsertID, Purpose: Get the ID of insert
    function GetInsertID($result = null)
    {
    	if ($result == null)
        {
    		return mysql_insert_id($this->dblink);
    	}
        else
        {
    		return mysql_insert_id($result);
    	}
    }

    //-----------------------------------------------------------------------------------------
    
    //*** Function: ListDBs, Purpose: List all DBs available ***
    function ListDBs()
    {
    	$databases = Array();
    	$list = mysql_list_dbs($this->dblink);
    	while($getDB = mysql_fetch_object($list))
        {
    	     $databases[] = $getDB->Database;
    	}
    	return $databases;
    }

    //-----------------------------------------------------------------------------------------
    
    // Function: Paginate, Purpose: Paginate the current query or set withPag to false
    function Paginate($bool = true,$perPage = 1)
    {
    	$this->withPag = $bool;
    	if($bool == true)
        {
    		$this->paginatorObj = new TPaginator($this,$perPage);
    	}
    }

    //-----------------------------------------------------------------------------------------
    
    // Function: ShowNavLinks, Purpose: Echo the paginator's nav links
    function ShowNavLinks()
    {
    	$this->paginatorObj->ShowBox($this);
    }
}

//*********************************************************************************************

////////////////////////////////////////////////////////////////////////////////////////
// Class: TPaginator
// Purpose: Manage pagination of query registries. Called from a TDbConnector object
// (function Paginate or ShowNavLinks)
////////////////////////////////////////////////////////////////////////////////////////

class TPaginator extends TSystemComponent
{
	var $totalRecords;
	var $perPage;
	var $limitsSet = false;

    //-----------------------------------------------------------------------------------------

	function TPaginator(&$connector,$perPage)
    {

		$this->perPage = $perPage;

		if (isset($_GET['pageNum']) && is_numeric($_GET['pageNum'])){
			$currentPage = $_GET['pageNum'];
		}else{
			$currentPage = 1;
		}

		if (is_numeric($currentPage)){
			$connector->setFirst(($currentPage - 1) * $perPage);
		}else{
			$connector->setFirst(0);
		}

		//if (is_numeric($perPage) && $perPage > 0){
		$connector->setLimit($perPage);
	}

    //-----------------------------------------------------------------------------------------

	function ShowBox(&$connector)         // MODIFICAR ESSA FUNCAO!!!
    {

		if (isset($_GET['pageNum']) && is_numeric($_GET['pageNum'])){
			$currentPage = $_GET['pageNum'];
		}else{
			$currentPage = 1;
		}

		$base = SystemComponent::modURL('action','');

		$startURL = SystemComponent::modURL('pageNum','1',$base);

		$prevURL = SystemComponent::modURL('pageNum',$currentPage - 1,$base);

		$nextURL = SystemComponent::modURL('pageNum',$currentPage + 1,$base);

		$lastPage = ceil($connector->getPagNumResults() / $this->perPage);

		$lastURL = SystemComponent::modURL('pageNum',$lastPage,$base);

		echo '<div class="navLinks">';											// Start Table
		if ($currentPage > 1){ echo '<a href="'.$startURL.'"><< First </a>'; 			// First Link
		echo '&nbsp;<a href="'.$prevURL.'">< Previous</a>';	}							// Previous Link
		if ($currentPage > 1 && $currentPage < $lastPage){								// Spacer
			echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
		}
		if ($currentPage < $lastPage){echo '<a href = "'.$nextURL.'">Next ></a> &nbsp;';// Next Link
		echo '<a href="'.$lastURL.'">Last >></a>';}										// Last Link
		echo '</div>';														// End Table
	}
}
?>
