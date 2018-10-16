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

		$sala = filter_input(INPUT_GET, 'sala', FILTER_SANITIZE_STRING);

		$r = $db->select("locais_has_votos", " WHERE idSala = '$sala'");

		$votos = array();

		while($item = mysqli_fetch_array($r, MYSQLI_ASSOC)){
			$local = array();
			$votos[$item["idLocal"]] = $votos[$item["idLocal"]]+1;
		}

		$value = max($votos);
		$key = "";
		$key = array_search($value, $votos);

		$r = $db->select("locais", " WHERE id = '$key'");
		$local = mysqli_fetch_array($r, MYSQLI_ASSOC);

		$local["nome"] = html_entity_decode($local['nome'],ENT_QUOTES);

		if($key == ""){
			$return["status"] = false;
			$return["message"] = "Erro ao validar votos.";
			$return["data"] = null;
		}else{
			$return["status"] = true;
			$return["message"] = "";
			$return["data"] = array();
			$return["data"] = $local;

			$r = $db->delete("salas", $sala);

			try {

			    $id_app             = "4bc1092d-8a63-43d1-9b43-df9953e13165";
			    $key_app            = "M2ViNjQ5YzQtYmNlMS00ZDcyLTk3YWEtZGE1OGQ3ZGI4ZmI1";

				function sendMessage($push,$key_app) {

				    $fields = json_encode($push);

				    $key = "Authorization: Basic ".$key_app;
				    
				    $ch = curl_init();
				    curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
				    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $key));
				    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				    curl_setopt($ch, CURLOPT_HEADER, FALSE);
				    curl_setopt($ch, CURLOPT_POST, TRUE);
				    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
				    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

				    $response = curl_exec($ch);
				    curl_close($ch);
				    
				    return $response;
				}

				$mensagem = "O local escolhido foi: ".$local["nome"];

			    $contents = array();
			    $contents['en'] = $mensagem;
			    $contents['pt'] = $mensagem;

				/* array para enviar dados extras ao app,
				adicione nesse array tudo que for considerado
				dados extras, ou seja, que não for parâmetro
				para a onesignal, mas que deve ser entregue ao app */
				$data = array();
				$data["local"] = $local["id"];

				/* cria o objeto do push que será enviado */
				$push = array(
			      'app_id' => $id_app,
			      'contents' => $contents,
			      'filters' => array(array("field" => "tag", "key" => "sala", "relation" => "=", "value" => $sala)),
			      'data' => $data
			    );

				/* verifica se o push vai ser enviado agora,
				ou em uma hora pré-definida pelo usuário */
				if(!empty($send_after)){
					$push['send_after'] = $send_after;
				}else{
					$push = array(
				      'app_id' => $id_app,
				      'contents' => $contents,
				      'ios_badgeType' => 'Increase',
				      'ios_badgeCount' => '1',
				      'filters' => array(array("field" => "tag", "key" => "sala", "relation" => "=", "value" => $sala)),
				      'data' => $data

				    );
				}
				
				$push['isAndroid'] = true;
			   
			   	/* cria o título do push com o que foi informado
			   	se o título vier sem nada, carrega o nome do
			   	app no lugar do título do push */
			   	$titulo = "Sala ".$sala;
			    $headings = array();

			    $headings['en'] = $titulo;
			    $headings['pt'] = $titulo;

			    $push['headings'] = $headings;

			    /* envia o push para o app */
			  	$response = sendMessage($push,$key_app);

			  	/* mostra o retorno da onesignal na tela
			  	por favor, não remova esse código */
			  	/*$return["allresponses"] = $response;
				$return = json_encode($return);

				$data = json_decode($response, true);
				print_r($data);
				$id = $data['id'];
				print_r($id);

				print("\n\nJSON received:\n");
				print($return);
				print("\n");*/

			}catch(Exception $e) {
				//código
			}
		}

	}else{
		$return["status"] = false;
		$return["message"] = "Você precisa informar a sala que deseja acessar.";
		$return["data"] = null;
	}

	print_r(pretty_json(json_encode($return)));

?>