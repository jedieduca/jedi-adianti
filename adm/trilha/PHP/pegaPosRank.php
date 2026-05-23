<?php

     $tema = $_GET['tema'];
	 $acertos = $_GET['acertos'];
	 $erros = $_GET['erros'];
	 $res = $acertos-$erros;

	 require_once 'dbConn.php';

	 if((!empty($tema)) && (!empty($acertos))) 
	 {
	    $query = "select count(*) qtd from partidasPerguntas where qtdAcertos-qtdErros >= ".$res." and tema=".$tema;
		$stmt = $pdo->prepare($query);
		$stmt->execute();

		foreach($stmt as $row) 
		{
			echo $row['qtd']; 
		}
	}
	      
?>
