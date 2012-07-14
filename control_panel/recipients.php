<?php include 'control_panel/admin_auth.php'; ?>

<?php 

# instancia das classes recipients
$TRecipients = new TRecipients($connection);


/**
 * Paginador de grid de dados:
 * Com base nos comandos, o paginador vai mudando a lista de dados
 * Ele utiliza jSON e Ajax para atualizar a pagina
 */



/**
 * Iniciando variávies
 */
$command = (empty($_REQUEST['command']))?"":$_REQUEST['command'];
$lang = (empty($_REQUEST['lang']))?LANG_DEFAULT:$_REQUEST['lang'];

$PaginaAtual = (empty($_REQUEST['PaginaAtual']))?0:$_REQUEST['PaginaAtual'];
$TotalRegistros = (empty($_REQUEST['TotalRegistros']))?0:$_REQUEST['TotalRegistros'];
$NavLimit = (empty($_REQUEST['NavLimit']))?NUMBER_PER_PAGE:$_REQUEST['NavLimit'];
$RegistroInicial = (empty($_REQUEST['RegistroInicial']))?0:$_REQUEST['RegistroInicial'];
$MudaPagina = (empty($_REQUEST['MudaPagina']))?"":$_REQUEST['MudaPagina'];



switch($command)
{
	
	
	case 'SearchRecipients':	
		
		#transforma os campos do paginador em um array
		$Form = $TRecipients->FilterFieldsNavigator($_REQUEST);
		
		#atualiza as informacoes do total de registros e paginas para gerar o offset
		$InfoNavBar = $TRecipients->Navigator()->AtualizaNavegacao($PaginaAtual, $TotalRegistros, $NavLimit, $RegistroInicial,"");
		$Offset = $InfoNavBar['RegistroInicial'];			
		
		#retorna a lista da busca
		$retorno['Recipients'] = $TRecipients->SearchRecipients($Form,$NavLimit,$Offset);		
		include_once 'control_panel/recipients_list.php';
		break;
		
	case 'UpdateNavigator':
		
		#transforma os campos do paginador em um array
		$Form = $TRecipients->FilterFieldsNavigator($_REQUEST);
		
		#nao alterar as chaves dos vetores abaixo!
		$retorno['TotalRegistros'] = $TRecipients->CountSearchRecipients($Form);		
		$retorno['TotalPaginas'] = $TRecipients->Navigator()->TotalPaginas($retorno['TotalRegistros'], $NavLimit);

		#retorna um vetor com essas chaves TotalRegistros e TotalPaginas no formato json para o javascript ler
		echo json_encode($retorno);
		
		break;
		
	case 'ChangePage':
		
		#variaveis vem do paginador
		$InfoNavBar = $TRecipients->Navigator()->AtualizaNavegacao($PaginaAtual, $TotalRegistros, $NavLimit, $RegistroInicial,$MudaPagina);
		
		#nao alterar as chaves dos vetores abaixo!
		$retorno['PaginaAtual'] = $InfoNavBar['PaginaAtual'];
		$retorno['RegistroInicial'] = $InfoNavBar['RegistroInicial'];
		$retorno['TotalRegistros'] = $TotalRegistros;
		
		#retorna um vetor com essas chaves PaginaAtual, TotalRegistros e TotalPaginas no formato json para o javascript ler
		echo json_encode($retorno);
		
		break;	
		
	default:
		
		$Form = $TRecipients->FilterFieldsNavigator($_REQUEST);
		
		#nao alterar as chaves dos vetores abaixo!
		$retorno['TotalRegistros'] = $TRecipients->CountSearchRecipients($Form);		
		$retorno['TotalPaginas'] = $TRecipients->Navigator()->TotalPaginas($retorno['TotalRegistros'], $NavLimit);
		
		#atualiza as informacoes do total de registros e paginas para gerar o offset
		$InfoNavBar = $TRecipients->Navigator()->AtualizaNavegacao($PaginaAtual, $TotalRegistros, $NavLimit, $RegistroInicial,"");
		$Offset = $InfoNavBar['RegistroInicial'];			
		
		#retorna a lista da busca
		$retorno['Recipients'] = $TRecipients->SearchRecipients($Form,$NavLimit,$Offset);		
		
		#gera os filtros que sao o cabecalho do datagrid
		$retorno['Filters'] = $TRecipients->FilterRecipients($lang,$retorno);
		include_once 'control_panel/recipients_options.php';
		
		break;
}
