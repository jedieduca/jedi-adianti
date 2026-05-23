<?php 

    header("Access-Control-Allow-Origin: *");

    $idPartida= $_GET['idPartida'];
	  $login = $_GET['login'];
    $tema = $_GET['tema'];
    $jogador = $_GET['jogador'];
    $idade = $_GET['idade'];
    $pontuacao = $_GET['pontuacao'];
    $qtdAcertos = $_GET['qtdAcertos'];
    $qtdErros = $_GET['qtdErros'];
    $tempoGasto= $_GET['tempoGasto'];
    $autoAvaliacao= $_GET['autoAvaliacao'];
    $avaliacaoJogo= $_GET['avaliacaoJogo'];

     require_once 'dbConn.php'; 

		// Preparando comando
      $sql = "INSERT INTO partidasPerguntas (dtJogo, idPartida, login, tema, jogador, idade, pontuacao, qtdAcertos, qtdErros, tempoGasto, autoAvaliacao, avaliacaoJogo)";
      $sql.= " VALUES (curdate(), :idPartida, :login, :tema, :jogador, :idade, :pontuacao, :qtdAcertos, :qtdErros, :tempoGasto, :autoAvaliacao, :avaliacaoJogo)";
      $stmt = $pdo->prepare($sql);

      // Definindo parâmetros
      $stmt->bindParam(':idPartida', $idPartida, PDO::PARAM_INT);
      $stmt->bindParam(':login', $login, PDO::PARAM_STR);
      $stmt->bindParam(':tema', $tema, PDO::PARAM_STR);
      $stmt->bindParam(':jogador', $jogador, PDO::PARAM_STR);
      $stmt->bindParam(':idade', $idade, PDO::PARAM_INT);
      $stmt->bindParam(':pontuacao', strval($pontuacao), PDO::PARAM_STR);	
      $stmt->bindParam(':qtdAcertos', $qtdAcertos, PDO::PARAM_INT);
      $stmt->bindParam(':qtdErros', $qtdErros, PDO::PARAM_INT);		
      $stmt->bindParam(':tempoGasto', strval($tempoGasto), PDO::PARAM_STR);
      $stmt->bindParam(':autoAvaliacao', $autoAvaliacao, PDO::PARAM_STR);
      $stmt->bindParam(':avaliacaoJogo',$avaliacaoJogo, PDO::PARAM_STR);
    // Executando e exibindo resultado
    $stmt->execute();

    echo 1;
?>
