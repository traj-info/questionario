<?php include 'control_panel/admin_auth.php'; ?>
<?php 

$query = "SELECT * FROM surveys";
$rows = $connection->GetAllResults($query);
if(!$rows) exit('Nenhum registro encontrado.');

$data = NowDatetime();

$xls = "Relatório gerado em $data\r\n";







$xls .= "#;nome;sobrenome;email;data_cadastro;data_modificacao_cadastro;idioma;nivel_acesso;status_usuario;status_questionario;";
$xls .= "instituicao_1;cidade_pais_1;instituicao_2;cidade_pais_2;instituicao_3;cidade_pais_3;q1_sexo;q2_idade;q3_formacao_medica_gastroenterologia;q3_formacao_medica_cirurgia;q4_cpre;";
$xls .= "q5a_governo_hospital;q5a_governo_nao_hospitalar;q5b_privada_hospital;q5b_privada_nao_hospitalar;q5c_independente_hospital;q5c_independente_nao_hospitalar;";
$xls .= "q6_treinamento_eus;q7_instituicao_1;q7_cidade_pais_1;q7_instituicao_2;q7_cidade_pais_2;q7_instituicao_3;q7_cidade_pais_3;q8a_anorretal;q8b_esofago;q8c_gastroduodenal;q8d_mediastino;q8e_pancreato_biliar_ampular;q8f_fna;q8g_terapeutica;q9_anos_realizacao_eus;q10_qtde_eus;";
$xls .= "q11a_fna;q11b_neurolise_bloq_celiaco;q11c_pseudocisto;q11d_abscessos;q11e_biliar;q11f_pancreatica;q12a_eus_alta;q12b_eus_baixa;q13a_fna_alta;q13b_fna_baixa;q14a_compl_sangramentos;q14b_compl_infeccoes;q14c_compl_perfuracoes;q14d_compl_sedacao;q14e_compl_outras;";
$xls .= "q15a_radial_mecanico_fujinon;q15a_radial_mecanico_olympus;q15a_radial_mecanico_pentax;";
$xls .= "q15b_radial_eletronico_fujinon;q15b_radial_eletronico_olympus;q15b_radial_eletronico_pentax;";
$xls .= "q15c_linear_fujinon;q15c_linear_olympus;q15c_linear_pentax;";
$xls .= "q15d_miniprobes_fujinon;q15d_miniprobes_olympus;q15d_miniprobes_pentax;";
$xls .= "q15e_outros;";
$xls .= "q16a_agulha19_boston;q16a_agulha19_cook;q16a_agulha19_medi_globe;q16a_agulha19_olympus;";
$xls .= "q16b_agulha22_boston;q16b_agulha22_cook;q16b_agulha22_medi_globe;q16b_agulha22_olympus;";
$xls .= "q16c_agulha25_boston;q16c_agulha25_cook;q16c_agulha25_medi_globe;q16c_agulha25_olympus;";
$xls .= "q16d_outros;q17a_anorretal;q17b_esofago;q17c_gastroduodenal;q17d_mediastino;q17e_pancreato_biliar_ampular;q18a_anorretal_A-CANCER-RETAL;q18a_anorretal_B-INCONTINENCIA-FECAL-FISTULAS;q18a_anorretal_C-CANCER-ANAL;q18a_anorretal_D-ENDOMETRIOSE;q18a_anorretal_E-LESOES-SUBEPITELIAIS;q18a_anorretal_F-OUTROS;q18b_esofago_A-BARRETT;q18b_esofago_B-CANCER;q18b_esofago_C-LESOES-SUBEPITELIAIS;q18b_esofago_D-OUTROS;q18c_gastroduodenal_A-ADENOCARCINOMA;q18c_gastroduodenal_B-LINFOMA;q18c_gastroduodenal_C-TUMOR-OU-LINFONODOS;q18c_gastroduodenal_D-LESOES-SUBEPITELIAIS;q18c_gastroduodenal_E-OUTROS;q18d_mediastino_A-LINFONODOS;q18d_mediastino_B-TUMOR-MEDIASTINAL;q18d_mediastino_C-ESTADIAMENTO-CA-PULMAO;q18d_mediastino_D-OUTROS;q18e_pancr_A-PANCREATITE;q18e_pancr_B-TUMOR-AMPULAR;q18e_pancr_C-PSEUDOCISTO;q18e_pancr_D-TUMORES-CISTICOS;q18e_pancr_E-MICROLITIASE-COLEDOCOLITIASE;q18e_pancr_F-TUMOR-PANCREATICO;q18e_pancr_G-OUTROS;q19_verifica_resultados;q20a_lesoes_solidas;q20b_lesos_cisticas;q21_responsavel_sedacao;q21_responsavel_sedacao_outros;q22_sedacao_propofol;q22_sedacao_propofol_outros;q23_responsavel_propofol;q23_responsavel_propofol_outros;q24_treina_medicos_em_eus;q25a_anorretal;q25b_esofago;q25c_gastroduodenal;q25d_mediastino;q25e_pancreato_biliar_ampular;q25f_puncao_ecoguiada;q25g_terapeutica;q26_tempo_estagio;q27a;q27b;q27c;q27d;q27e;q27f;q28a_diagnosticos;q28a_diagnosticos_naosei;q28b_fna;q28b_fna_naosei;q29a_diagnosticos;q29a_diagnosticos_naosei;q29b_fna;q29b_fna_naosei;q30;q31;q32;q33;q34\r\n";


foreach($rows as $i => $row)
{
	$query = "	SELECT 
						users.*, 
						userstatus.description as user_status_description,
						credentials.description as user_credential_description,
						(CASE WHEN surveystatus.id is null THEN ".NOT_STARTED."
						ELSE surveystatus.id
						END) as survey_status_id,
						COALESCE(surveystatus.description,'".SURVEY_MSG_NOT_STARTED."') as user_survey_description

				FROM users
				INNER JOIN credentials ON credentials.id = users.credential_id
				INNER JOIN userstatus ON userstatus.id = users.user_status_id
				LEFT JOIN surveys ON surveys.user_id = users.id
				LEFT JOIN surveystatus ON surveystatus.id = surveys.survey_status_id
				WHERE users.id = " . $row['user_id'] . " ORDER BY users.id LIMIT 1";
	

	$user = $connection->GetResult($query);


	$numero = $i + 1;
	
	$nome = $user['name'];
	$sobrenome = $user['lastname'];
	$email = $user['email'];
	$idioma = $user['lang'];
	
	$credential = utf8_encode($user['user_credential_description']);
	$user_status = utf8_encode($user['user_status_description']);
	$status_questionario =  utf8_encode($user['user_survey_description']);
	
	$data_cadastro = $user['created'];
	$data_mod_cadastro = $user['modified'];
	
	
	$temp = explode(";", $row['question1']);
	$instituicao_1 = $temp[0];
	$cidade_pais_1 = $temp[1];
	$instituicao_2 = $temp[2];
	$cidade_pais_2 = $temp[3];
	$instituicao_3 = $temp[4];
	$cidade_pais_3 = $temp[5];
	
	
	
	$q1_sexo = traduz_sexo($row['question3']);
	$q2_idade = traduz_n($row['question4']);
	
	
	$temp = explode(";", $row['question5']);
	$q3_formacao_medica_g = traduz_q3($temp[0]);
	$q3_formacao_medica_c = traduz_q3($temp[1]);
	
	
	
	$q4_cpre = traduz_n($row['question6']);
	
	$temp = explode(";", $row['question7']);
	
	$q5a_governo_hosp = traduz_q5_governo($temp[0], "");
	$q5a_governo_n_hosp = traduz_q5_governo("", $temp[1]);
	
	$q5b_privada_hosp = traduz_q5_privada($temp[2], "");
	$q5b_privada_n_hosp = traduz_q5_privada("", $temp[3]);
	
	$q5c_independente_hosp = traduz_q5_independente($temp[4], "");
	$q5c_independente_n_hosp  = traduz_q5_independente("", $temp[5]);
	
	
	$q6_treinamento_eus = traduz_letras($row['question8']);
	
	
	
	$temp = explode(";", $row['question9']);
	$q7_instituicao_1 = $temp[0];
	$q7_cidade_pais_1 = $temp[1];
	$q7_instituicao_2 = $temp[2];
	$q7_cidade_pais_2 = $temp[3];
	$q7_instituicao_3 = $temp[4];
	$q7_cidade_pais_3 = $temp[5];
	
	
	$temp = explode(";", $row['question10']);
	$q8a_anorretal = traduz_n($temp[0]);
	$q8b_esofago = traduz_n(($temp[1] - 6));
	$q8c_gastroduodenal = traduz_n(($temp[2] - 12));
	$q8d_mediastino = traduz_n(($temp[3] - 18));
	$q8e_pancreato_biliar_ampular = traduz_n(($temp[4] - 24));
	$q8f_fna = traduz_n(($temp[5] - 30));
	$q8g_terapeutica = traduz_n(($temp[6] - 37));
	
	$q9_anos_realizacao_eus = $row['question11'];
	
	$q10_qtde_eus = traduz_n($row['question12']);
	
	$temp = explode(";", $row['question13']);
	$q11a_fna = $temp[0];
	$q11b_neurolise_blow_celiaco = $temp[1];
	$q11c_pseudocisto = $temp[2];
	$q11d_abscessos = $temp[3];
	$q11e_biliar = $temp[4];
	$q11f_pancreatica = $temp[5];
	
	$temp = explode(";", $row['question14']);
	$q12a_eus_alta = $temp[0];
	$q12b_eus_baixa = $temp[1];
	
	$temp = explode(";", $row['question15']);
	$q13a_fna_alta = $temp[0];
	$q13b_fna_baixa = $temp[1];
	
	$temp = explode(";", $row['question16']);
	$q14a_compl_sangramentos = $temp[0];
	$q14b_compl_infeccoes = $temp[1];
	$q14c_compl_perfuracoes = $temp[2];
	$q14d_compl_sedacao = $temp[3];
	$q14e_compl_outras = $temp[4];
	
	
	
	$temp = explode(";", $row['question17']);
	
	$q15a_radial_mecanico_f = traduz_q15($temp[0], "", "");
	$q15a_radial_mecanico_o = traduz_q15("", $temp[1], "");
	$q15a_radial_mecanico_p = traduz_q15("", "", $temp[2]);
	
	$q15b_radial_eletronico_f = traduz_q15($temp[3], "", "");
	$q15b_radial_eletronico_o = traduz_q15("", $temp[4], "");
	$q15b_radial_eletronico_p = traduz_q15("", "", $temp[5]);
	
	
	$q15c_linear_f = traduz_q15($temp[6], "", "");
	$q15c_linear_o = traduz_q15("", $temp[7], "");
	$q15c_linear_p = traduz_q15("", "", $temp[8]);
	
	$q15d_miniprobes_f = traduz_q15($temp[9], "","");
	$q15d_miniprobes_o = traduz_q15("", $temp[10], "");
	$q15d_miniprobes_p = traduz_q15("", "", $temp[11]);
	
	$q15e_outros = $temp[12];
	
	$temp = explode(";", $row['question18']);
	
	$q16a_agulha19_b = $temp[0];
	$q16a_agulha19_c = $temp[1];
	$q16a_agulha19_m = $temp[2];
	$q16a_agulha19_o = $temp[3];
	
	$q16b_agulha22_b = $temp[4] ;
	$q16b_agulha22_c = $temp[5];
	$q16b_agulha22_m = $temp[6];
	$q16b_agulha22_o = $temp[7];
	
	$q16c_agulha25_b = $temp[8];
	$q16c_agulha25_c = $temp[9];
	$q16c_agulha25_m = $temp[10];
	$q16c_agulha25_o = $temp[11];
	
	$q16d_outros = $temp[12];
	
	
	$temp = explode(";", $row['question19']);
	$q17a_anorretal = $temp[0];
	$q17b_esofago = $temp[1];
	$q17c_gastroduodenal = $temp[2];
	$q17d_mediastino = $temp[3];
	$q17e_pancreato_biliar_ampular = $temp[4];
	
	$temp = explode(";", $row['question20']);
	$q18a_anorretal_A_CANCER_RETAL = $temp[0];
	$q18a_anorretal_B_INCONTINENCIA_FECAL_FISTULAS = $temp[1];
	$q18a_anorretal_C_CANCER_ANAL = $temp[2];
	$q18a_anorretal_D_ENDOMETRIOSE = $temp[3];
	$q18a_anorretal_E_LESOES_SUBEPITELIAIS = $temp[4];
	$q18a_anorretal_F_OUTROS = $temp[5];
	$q18b_esofago_A_BARRETT = $temp[6];
	$q18b_esofago_B_CANCER = $temp[7];
	$q18b_esofago_C_LESOES_SUBEPITELIAIS = $temp[8];
	$q18b_esofago_D_OUTROS = $temp[9];
	$q18c_gastroduodenal_A_ADENOCARCINOMA = $temp[10];
	$q18c_gastroduodenal_B_LINFOMA = $temp[11];
	$q18c_gastroduodenal_C_TUMOR_OU_LINFONODOS = $temp[12];
	$q18c_gastroduodenal_D_LESOES_SUBEPITELIAIS  = $temp[13];
	$q18c_gastroduodenal_E_OUTROS = $temp[14];
	$q18d_mediastino_A_LINFONODOS = $temp[15];
	$q18d_mediastino_B_TUMOR_MEDIASTINAL = $temp[16];
	$q18d_mediastino_C_ESTADIAMENTO_CA_PULMAO = $temp[17];
	$q18d_mediastino_D_OUTROS = $temp[18];
	$q18e_pancr_A_PANCREATITE = $temp[19];
	$q18e_pancr_B_TUMOR_AMPULAR = $temp[20];
	$q18e_pancr_C_PSEUDOCISTO = $temp[21];
	$q18e_pancr_D_TUMORES_CISTICOS = $temp[22];
	$q18e_pancr_E_MICROLITIASE_COLEDOCOLITIASE = $temp[23];
	$q18e_pancr_F_TUMOR_PANCREATICO = $temp[24];
	$q18e_pancr_G_OUTROS = $temp[25];
	
	$q19_verifica_resultados = traduz_sim_nao($row['question21']);
	
	$temp = explode(";", $row['question22']);
	$q20a_lesoes_solidas = $temp[0];
	$q20b_lesoes_cisticas = $temp[1];
	
	$temp = explode(";", $row['question23']);
	$q21_responsavel_sedacao = traduz_letras($row['question23']);
	$q21_responsavel_sedacao_outros = $temp[5];
	/*
	if(strpos($q21_responsavel_sedacao, "e") !== 0)
	{
		$q21_responsavel_sedacao .= " | Outros: " . $temp[5];
	}
	*/
	
	$temp = explode(";", $row['question24']);
	$q22_sedacao_propofol = traduz_letras($row['question24']);
	$q22_sedacao_propofol_outros =  $temp[5];
	/*
	if(strpos($q22_sedacao_propofol, "e") !== 0)
	{
		$q22_sedacao_propofol .= " | Outros: " . $temp[5];
	}
	*/
	
	
	$temp = explode(";", $row['question25']);
	$q23_responsavel_propofol = traduz_letras($row['question25']);
	$q23_responsavel_propofol_outros =  $temp[5];
	/*
	if(strpos($q23_responsavel_propofol, "e") !== 0)
	{
		$q23_responsavel_propofol .= " | Outros: " . $temp[5];
	}
	*/
	
	$q24_treina_medicos_em_eus = traduz_sim_nao($row['question26']);
	
	$temp = explode(";", $row['question27']);
	$q25a_anorretal = traduz_n($temp[0]);
	$q25b_esofago = traduz_n(($temp[1] - 6));
	$q25c_gastroduodenal = traduz_n(($temp[2] - 12));
	$q25d_mediastino = traduz_n(($temp[3] - 18));
	$q25e_pancreato_biliar_ampular = traduz_n(($temp[4] - 24));
	$q25f_puncao_ecoguiada = traduz_n(($temp[5] - 30));
	$q25g_terapeutica = traduz_n(($temp[6] - 37));
	
	$q26_tempo_estagio = traduz_n($row['question28']);
	
	$temp = explode(";", $row['question29']);
	$q27a = traduz_sim_nao($temp[0]);
	$q27b = traduz_sim_nao(($temp[1] - 2));
	$q27c = traduz_sim_nao(($temp[2] - 4));
	$q27d = traduz_sim_nao(($temp[3] - 6));
	$q27e = traduz_sim_nao(($temp[4] - 8));
	$q27f = ($temp[5]);
	
	$temp = explode(";", $row['question30']);
	$q28a_diagnosticos = $temp[0];
	$q28a_diagnosticos_naosei = ($temp[1] != "") ? "não sei" : "";
	$q28b_fna = $temp[2];
	$q28b_fna_naosei = ($temp[3] != "") ? "não sei" : "";
	
	$temp = explode(";", $row['question31']);
	$q29a_diagnosticos = $temp[0];
	$q29a_diagnosticos_naosei = ($temp[1] != "") ? "não sei" : "";
	$q29b_fna = $temp[2];
	$q29b_fna_naosei = ($temp[3] != "") ? "não sei" : "";
	
	$q30 = $row['question32'];
	$q31 = $row['question33'];
	$q32 = $row['question34'];
	$q33 = $row['question35'];
	$q34 = $row['question36'];
	
	$xls .= "$numero;$nome;$sobrenome;$email;$data_cadastro;$data_mod_cadastro;$idioma;$credential;$user_status;$status_questionario;";
	$xls .= "$instituicao_1;$cidade_pais_1;$instituicao_2;$cidade_pais_2;$instituicao_3;$cidade_pais_3;";
	$xls .= "$q1_sexo;$q2_idade;$q3_formacao_medica_g;$q3_formacao_medica_c;$q4_cpre;";
	$xls .= "$q5a_governo_hosp;$q5a_governo_n_hosp;$q5b_privada_hosp;$q5b_privada_n_hosp;$q5c_independente_hosp;$q5c_independente_n_hosp;";
	$xls .= "$q6_treinamento_eus;$q7_instituicao_1;$q7_cidade_pais_1;$q7_instituicao_2;$q7_cidade_pais_2;$q7_instituicao_3;$q7_cidade_pais_3;";
	$xls .= "$q8a_anorretal;$q8b_esofago;$q8c_gastroduodenal;$q8d_mediastino;$q8e_pancreato_biliar_ampular;$q8f_fna;$q8g_terapeutica;";
	$xls .= "$q9_anos_realizacao_eus;$q10_qtde_eus;";
	$xls .= "$q11a_fna;$q11b_neurolise_blow_celiaco;$q11c_pseudocisto;$q11d_abscessos;$q11e_biliar;$q11f_pancreatica;";
	$xls .= "$q12a_eus_alta;$q12b_eus_baixa;";
	$xls .= "$q13a_fna_alta;$q13b_fna_baixa;";
	$xls .= "$q14a_compl_sangramentos;$q14b_compl_infeccoes;$q14c_compl_perfuracoes;$q14d_compl_sedacao;$q14e_compl_outras;";
	$xls .= "$q15a_radial_mecanico_f;$q15a_radial_mecanico_o;$q15a_radial_mecanico_p;";
	$xls .= "$q15b_radial_eletronico_f;$q15b_radial_eletronico_o;$q15b_radial_eletronico_p;";
	$xls .= "$q15c_linear_f;$q15c_linear_o;$q15c_linear_p;";
	$xls .= "$q15d_miniprobes_f;$q15d_miniprobes_o;$q15d_miniprobes_p;";
	$xls .= "$q15e_outros;";
	$xls .= "$q16a_agulha19_b;$q16a_agulha19_c;$q16a_agulha19_m;$q16a_agulha19_o;";
	$xls .= "$q16b_agulha22_b;$q16b_agulha22_c;$q16b_agulha22_m;$q16b_agulha22_o;";
	$xls .= "$q16c_agulha25_b;$q16c_agulha25_c;$q16c_agulha25_m;$q16c_agulha25_o;";
	$xls .= "$q16d_outros;";
	$xls .= "$q17a_anorretal;$q17b_esofago;$q17c_gastroduodenal;$q17d_mediastino;$q17e_pancreato_biliar_ampular;";
	$xls .= "$q18a_anorretal_A_CANCER_RETAL;$q18a_anorretal_B_INCONTINENCIA_FECAL_FISTULAS;$q18a_anorretal_C_CANCER_ANAL;$q18a_anorretal_D_ENDOMETRIOSE;$q18a_anorretal_E_LESOES_SUBEPITELIAIS;$q18a_anorretal_F_OUTROS;";
	$xls .= "$q18b_esofago_A_BARRETT;$q18b_esofago_B_CANCER;$q18b_esofago_C_LESOES_SUBEPITELIAIS;$q18b_esofago_D_OUTROS;$q18c_gastroduodenal_A_ADENOCARCINOMA;$q18c_gastroduodenal_B_LINFOMA;$q18c_gastroduodenal_C_TUMOR_OU_LINFONODOS;$q18c_gastroduodenal_D_LESOES_SUBEPITELIAIS;$q18c_gastroduodenal_E_OUTROS;$q18d_mediastino_A_LINFONODOS;$q18d_mediastino_B_TUMOR_MEDIASTINAL;$q18d_mediastino_C_ESTADIAMENTO_CA_PULMAO;$q18d_mediastino_D_OUTROS;$q18e_pancr_A_PANCREATITE;$q18e_pancr_B_TUMOR_AMPULAR;$q18e_pancr_C_PSEUDOCISTO;$q18e_pancr_D_TUMORES_CISTICOS;$q18e_pancr_E_MICROLITIASE_COLEDOCOLITIASE;$q18e_pancr_F_TUMOR_PANCREATICO;$q18e_pancr_G_OUTROS;$q19_verifica_resultados;$q20a_lesoes_solidas;$q20b_lesoes_cisticas;$q21_responsavel_sedacao;$q21_responsavel_sedacao_outros;$q22_sedacao_propofol;$q2_sedacao_propofol_outros;$q23_responsavel_propofol;$q23_responsavel_propofol_outros;$q24_treina_medicos_em_eus;$q25a_anorretal;$q25b_esofago;$q25c_gastroduodenal;$q25d_mediastino;$q25e_pancreato_biliar_ampular;$q25f_puncao_ecoguiada;$q25g_terapeutica;$q26_tempo_estagio;$q27a;$q27b;$q27c;$q27d;$q27e;$q27f;$q28a_diagnosticos;$q28a_diagnosticos_naosei;$q28b_fna;$q28b_fna_naosei;$q29a_diagnosticos;$q29a_diagnosticos_naosei;$q29b_fna;$q29b_fna_naosei;$q30;$q31;$q32;$q33;$q34\r\n";
	
	

}



//$xls = str_replace("\r\n", "<br><br>", $xls);

echo $xls;

function traduz_status_questionario($id)
{
	switch($id)
	{
		case 1:
		return "Não iniciado";
		break;
		
		case 2:
		return "Iniciado";
		break;
		
		case 3:
		return "Concluído";
		break;
	}
}

function traduz_sexo($g)
{
	if($n == NULL && strpos($n, '0') !== 0) return '';

	switch($g)
	{
		case 0: return 'Masculino';
		case 1: return 'Feminino';
	}
}

function traduz_sim_nao($n)
{
	if($n == NULL && strpos($n, '0') !== 0) return '';

	switch($n)
	{
		case 0: return 'Sim';
		case 1: return 'Não';
	}
}

function traduz_q3($n)
{

	if($n == NULL && strpos($n, '0') !== 0) return '';
	
	switch($n)
	{
		case 0: return "a";
		case 1: return "b";
	}
	return;
}

function traduz_n($n)
{
	if($n == NULL && strpos($n, '0') !== 0) return '';

	switch($n)
	{
		case 0: return "a";
		case 1: return "b";
		case 2: return "c";
		case 3: return "d";
		case 4: return "e";
		case 5: return "f";
		case 6: return "g";
		case 7: return "h";
		case 8: return "i";
		case 9: return "j";
	}
}

function traduz_q5_governo($a, $b)
{
	return ($a != '' || $b != '' || $c != '') ? 'X' : '';
	
}

function traduz_q5_privada($a, $b)
{
	return ($a != '' || $b != '' || $c != '') ? 'X' : '';
}

function traduz_q5_independente($a, $b)
{
	return ($a != '' || $b != '' || $c != '') ? 'X' : '';
}

function traduz_letras($m)
{
	$temp = explode(";", $m);
	$retorno = "";
	$i = 0;
	foreach($temp as $t)
	{
		if($t != "" && preg_match("/^[0-9]+$/", $t))
		{
			if($i > 0)
			{
				$retorno .= ', ';
			}
			$retorno .= traduz_n($t);
			$i++;
		}
	}
	return $retorno;
}

function traduz_q15($a, $b, $c)
{
	return ($a != '' || $b != '' || $c != '') ? 'X' : '';
}

function traduz_q15b($a, $b, $c)
{
	/*
	$temp1 = ($a == 3) ? "FUJINON" : "";
	$temp2 = ($b == 4) ? "OLYMPUS" : "";
	$temp3 = ($c == 5) ? "PENTAX" : "";
	
	return "$temp1, $temp2, $temp3";
	*/
	if(!empty($a)) return "FUJINON";
	if(!empty($b)) return "OLYMPUS";
	if(!empty($c)) return "PENTAX";
	
	return;
}

function traduz_q15c($a, $b, $c)
{
	/*
	$temp1 = ($a == 6) ? "FUJINON" : "";
	$temp2 = ($b == 7) ? "OLYMPUS" : "";
	$temp3 = ($c == 8) ? "PENTAX" : "";
	
	return "$temp1, $temp2, $temp3";
	*/
	if(!empty($a)) return "FUJINON";
	if(!empty($b)) return "OLYMPUS";
	if(!empty($c)) return "PENTAX";
	
	return;
}

function traduz_q15d($a, $b, $c)
{
	/*
	$temp1 = ($a == 9) ? "FUJINON" : "";
	$temp2 = ($b == 10) ? "OLYMPUS" : "";
	$temp3 = ($c == 11) ? "PENTAX" : "";
	
	return "$temp1, $temp2, $temp3";
	*/
	if(!empty($a)) return "FUJINON";
	if(!empty($b)) return "OLYMPUS";
	if(!empty($c)) return "PENTAX";
	
	return;
}

?>


