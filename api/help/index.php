<?php

	$projeto = "Lunch Poker";

	$dominio = "http://mydomain.com";
	$base = "/api";

	//pegando o conteúdo do .json para a variável 
	$arquivo = file_get_contents("infos.json");
	 
	//decodificando os dados armazenados para um array 
	$json = json_decode($arquivo);

	/*echo "<pre>";
	print_r($json);
	echo "</pre>";*/

?>
<html>
	<head>
		<title><?=$projeto?> - Help</title>
		<meta charset="utf-8">
    	<link rel="stylesheet" href="estilo.css" type="text/css"/>
	</head>
	<body>
		<center>
			<br><br>
			<h1><?=$projeto?></h1>
			<h2>Referências de Endpoint</h2>
			<h3>Base URL: <i><?=$dominio.$base?></i></h3>
			<br>
			<table id="tabela">
				<tr>
					<th>Método</th>
					<th style="width: 100px;">Endpoint</th>
					<th style="width: 350px;">Retorno</th>
					<th>Parâmetros</th>
					<th style="width: 100px;background-color: #4682B4;">Teste de API</th>
				</tr>

				<?

					foreach ($json as $item) {?>
						
						<tr>
							<td><center><?=$item->metodo?></center></td>
							<td><?=$item->endpoint?></td>
							<td>
								<code>
									<?=$item->retorno?>
								</code>
							</td>
							<td>
								<?=$item->parametros?>
							</td>
							<td style="background-color: #012169;"><center><a target="_blank" href="<?=$dominio.$base.$item->endpoint.$item->request?>">REQUEST</a></center></td>
						</tr>


					<?}

				?>
			</table>
			<br><br><br>
		</center>
	</body>
</html>