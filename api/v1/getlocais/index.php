<?php

	ini_set('memory_limit','500M');
	header('Content-type: application/json');
	date_default_timezone_set('America/Sao_Paulo');

	include '../../class/connection.php';
	include '../../class/database.php';
	include '../../pretty_json.php';

	$tipo = filter_input(INPUT_GET, 'tipo', FILTER_SANITIZE_STRING);

	$return = array();
	$data = array();

	$db = new Database();

	if($tipo == ""){
		$retorno = $db->select('locais', " ORDER BY nome ASC");
	}else{
		$retorno = $db->select('locais', " WHERE tipo = '$tipo' ORDER BY nome ASC");
	}

	$count = 0;
			
	while($item = mysqli_fetch_array($retorno, MYSQLI_ASSOC)){
		$local = array();
		$local['id'] = $item['id'];
		$local['nome'] = html_entity_decode($item['nome'],ENT_QUOTES);
		$local['tipo'] = $item['tipo'];
		$local['data'] = strtotime($item["data"]);

		array_push($data, $local);

		$count++;
	}

	if($count == 0){
		$return["status"] = false;
		if($tipo == ""){
			$return["message"] = "Nenhum local cadastrado.";
		}else{
			$return["message"] = "Nenhum local cadastrado com o filtro informado.";
		}
		$return["data"] = null;
	}else{
		$return["status"] = true;
		$return["message"] = "";
		$return["data"] = array();
		$return["data"] = $data;
	}

	print_r(pretty_json(json_encode($return)));

?>