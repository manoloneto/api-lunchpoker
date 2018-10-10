<?php

	ini_set('memory_limit','500M');
	header('Content-type: application/json');
	date_default_timezone_set('America/Sao_Paulo');

	include '../../class/connection.php';
	include '../../class/database.php';
	include '../../pretty_json.php';

	$return = array();

	$db = new Database();

	if($_GET["locais"] != ""){

		$locais = filter_input(INPUT_GET, 'locais', FILTER_SANITIZE_STRING);
		$locais = explode(",", $locais);

		$dados = array();
		$dados["data"] = date("Y-m-d");

		$r = $db->insert('salas', $dados);

		if($r){

			$r = $db->sql("select * from salas order by id desc limit 1");

			$sala = mysqli_fetch_array($r, MYSQLI_ASSOC);

			$dados = array();
			$dados["idSala"] = $sala["id"];

			foreach ($locais as $local) {
				$dados["idLocal"] = $local;
				$db->insert("salas_has_locais", $dados);
			}

			$return["status"] = true;
			$return["message"] = "";

			$id = $sala["id"];

			$r = $db->sql("select l.* from locais l, salas_has_locais sl WHERE l.id = sl.idLocal AND sl.idSala = '$id' order by l.nome ASC");

			$return["data"] = array();
			$sala["locais"] = array();
			$sala["data"] = strtotime($sala["data"]);
					
			while($item = mysqli_fetch_array($r, MYSQLI_ASSOC)){
				$local = array();
				$local['id'] = $item['id'];
				$local['nome'] = $item['nome'];
				$local['tipo'] = $item['tipo'];
				$local['data'] = strtotime($item["data"]);

				array_push($sala["locais"], $local);
			}

			$return["data"] = $sala;

		}else{
			$return["status"] = false;
			$return["message"] = "Erro ao criar sala.";
			$return["data"] = null;
		}

	}else{
		$return["status"] = false;
		$return["message"] = "Você precisa informar os locais para criação da sala.";
		$return["data"] = null;
	}

	print_r(pretty_json(json_encode($return)));

?>