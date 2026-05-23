<?php
     
     $tema = $_GET['tema'];
	 
     require_once 'dbConn.php';

      if (!empty($tema)) 
      {
         $query = $query = "select nome from tema where Codigo=" . $tema;
         $stmt = $pdo->prepare($query);
         $stmt->execute();

         foreach($stmt as $row) 
         {
            echo utf8_encode($row['nome']); 
         }
      }
      
?>

