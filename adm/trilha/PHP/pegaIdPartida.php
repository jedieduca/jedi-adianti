<?php

	header("Access-Control-Allow-Origin: *");
	
     $tema = trim($_GET['tema']);

	 require_once 'dbConn.php';

	 if (!empty($tema)) 
	    $query = "select count(*)+1 id from partidasPerguntas where dtJogo=CURRENT_DATE and tema=".$tema;
	 $stmt = $pdo->prepare($query);
	 $stmt->execute();

	foreach($stmt as $row) 
    {
	 	echo $row['id']; 
	}
  
?>
