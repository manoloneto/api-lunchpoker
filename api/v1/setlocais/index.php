<?php

	ini_set('memory_limit','500M');
	header('Content-type: application/json');
	date_default_timezone_set('America/Sao_Paulo');

	include '../../class/connection.php';
	include '../../class/database.php';
	include '../../pretty_json.php';

	$return = array();

	if($_GET["nome"] != "" && $_GET["tipo"] != ""){

		if($_GET["nome"] == "Restaurante" && $_GET["tipo"] == "RESTAURANTE"){
			$return["status"] = true;
			$return["message"] = "Local salvo com sucesso!";
			$return["data"] = null;
		}else{
			$dados = array();
			$dados["nome"] = filter_input(INPUT_GET, 'nome', FILTER_SANITIZE_STRING);
			$dados["tipo"] = filter_input(INPUT_GET, 'tipo', FILTER_SANITIZE_STRING);
			$dados["data"] = date("Y-m-d");

			$db = new Database();

			$r = $db->insert('locais', $dados);

			if($r){
				$return["status"] = true;
				$return["message"] = "Local salvo com sucesso!";
			}else{
				$return["status"] = false;
				$return["message"] = "Erro ao salvar local.";
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