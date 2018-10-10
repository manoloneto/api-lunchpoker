<?php

	class Connection
	{
		
		function open()
		{
			$server = "localhost";
			$usuario = "USERNAME";
			$senha = "PASSWORD";
			$banco = "DATABASE";
			
			return new mysqli($server, $usuario, $senha, $banco);
		}

		function close($link)
		{
			return mysqli_close($link);
		}
		
	}

?>