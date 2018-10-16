<?php

	ini_set('memory_limit','500M');
	header('Content-type: application/json');
	date_default_timezone_set('America/Sao_Paulo');

	include '../../class/connection.php';
	include '../../class/database.php';
	include '../../pretty_json.php';

	$return = array();

	if($_GET["sala"] != "" && $_GET["local"] != ""){

		if($_GET["sala"] == "0" && $_GET["local"] == "0"){
			$return["status"] = true;
			$return["message"] = "Voto salvo com sucesso!";
			$return["data"] = null;
		}else{
			$dados = array();
			$dados["idSala"] = filter_input(INPUT_GET, 'sala', FILTER_SANITIZE_STRING);
			$dados["idLocal"] = filter_input(INPUT_GET, 'local', FILTER_SANITIZE_STRING);

			$db = new Database();

			$r = $db->insert('locais_has_votos', $dados);

			if($r){
				$return["status"] = true;
				$return["message"] = "Voto salvo com sucesso!";
			}else{
				$return["status"] = false;
				$return["message"] = "Erro ao salvar voto.";
			}

			$return["data"] = null;
		}

	}else{
		$return["status"] = false;
		$return["message"] = "Dados inválidos.";
		$return["data"] = null;
	}

	print_r(pretty_json(json_encode($return)));

?>