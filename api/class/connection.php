<?php

	class Connection
	{
		
		function open()
		{
			$server = "localhost";
			$usuario = "hills981_lunch";
			$senha = "batatadoce";
			$banco = "hills981_poker";
			
			return new mysqli($server, $usuario, $senha, $banco);
		}

		function close($link)
		{
			return mysqli_close($link);
		}
		
	}

?>