<?php

	ini_set('memory_limit','500M');
	header('Content-type: application/json');
	date_default_timezone_set('America/Sao_Paulo');

	include '../../class/connection.php';
	include '../../class/database.php';
	include '../../pretty_json.php';

	$return = array();

	$db = new Database();

	if($_GET["sala"] != ""){

		$id = filter_input(INPUT_GET, 'sala', FILTER_SANITIZE_STRING);
		$hoje = date("Y-m-d");

		$r = $db->select("salas", " WHERE id = '$id'");
		$sala = mysqli_fetch_array($r, MYSQLI_ASSOC);

		$r = $db->sql("select l.* from locais l, salas s, salas_has_locais sl WHERE l.id = sl.idLocal AND sl.idSala = '$id' AND sl.idSala = s.id AND s.data = '$hoje' order by l.nome ASC");

		$sala["locais"] = array();
		$sala["data"] = strtotime($sala["data"]);

		$count = 0;
				
		while($item = mysqli_fetch_array($r, MYSQLI_ASSOC)){
			$local = array();
			$local['id'] = $item['id'];
			$local['nome'] = html_entity_decode($item['nome'],ENT_QUOTES);
			$local['tipo'] = $item['tipo'];
			$local['data'] = strtotime($item["data"]);

			array_push($sala["locais"], $local);

			$count++;
		}

		if($count == 0){
			$return["status"] = false;
			$return["message"] = "Sala não encontrada.";
			$return["data"] = null;
		}else{
			$return["status"] = true;
			$return["message"] = "";
			$return["data"] = array();
			$return["data"] = $sala;
		}

	}else{
		$return["status"] = false;
		$return["message"] = "Você precisa informar a sala que deseja acessar.";
		$return["data"] = null;
	}

	print_r(pretty_json(json_encode($return)));

?>