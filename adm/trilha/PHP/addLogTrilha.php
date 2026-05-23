
<?php 
    //addLog.php
    header("Access-Control-Allow-Origin: *");

	//if($_SERVER['REQUEST_METHOD']=='POST'){
		
        //Getting values
        //$data = date("d/m/Y");
        $idPartida= $_GET['idPartida'];
        $login = $_GET['login'];
        $tema = $_GET['tema'];
        $jogador = $_GET['jogador'];
        $idade = $_GET['idade'];
        $numJogada = $_GET['numJogada'];
		$pergunta = $_GET['pergunta'];
		$respCerta = $_GET['respCerta'];
		$respDada = $_GET['respDada'];
        $tempoGasto = $_GET['tempoGasto'];

        echo $idPartida;
        echo $login;
        echo $tema;
        echo $jogador;
        echo $idade;
        echo $numJogada;
        echo $pergunta;
        echo $respCerta;
        echo $respDada;
        echo $tempoGasto;


		require_once('dbConn.php');
		// Preparando comando
        $sql = "INSERT INTO logPerguntas (dtJogo, idPartida, usuario, tema, jogador, idade, numJogada, pergunta, respCerta, respDada, tempoGasto)";
        $sql.= " VALUES (curdate(), :idPartida, :usuario, :tema, :jogador, :idade, :numJogada, :pergunta, :respCerta, :respDada, :tempoGasto)";
		$stmt = $pdo->prepare($sql);


        // Definindo parâmetros
        //$stmt->bindParam(':data', curdate(), PDO::PARAM_STR);
        $stmt->bindParam(':idPartida', $idPartida, PDO::PARAM_INT);
        $stmt->bindParam(':usuario', $login, PDO::PARAM_STR);
        $stmt->bindParam(':tema', $tema, PDO::PARAM_STR);
        $stmt->bindParam(':jogador', $jogador, PDO::PARAM_STR);
        $stmt->bindParam(':idade', $idade, PDO::PARAM_INT);
        $stmt->bindParam(':numJogada', $numJogada, PDO::PARAM_INT);		
        $stmt->bindParam(':pergunta', $pergunta, PDO::PARAM_STR);
		$stmt->bindParam(':respCerta', $respCerta, PDO::PARAM_STR);
		$stmt->bindParam(':respDada', $respDada, PDO::PARAM_STR);
        $stmt->bindParam(':tempoGasto', strval($tempoGasto), PDO::PARAM_STR);
		// Executando e exibindo resultado
		$stmt->execute();

    echo 1;
?>
