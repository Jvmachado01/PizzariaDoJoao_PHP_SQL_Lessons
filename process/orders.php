<?php
    include_once("con.php");
    

    $method = $_SERVER["REQUEST_METHOD"];

    if ($method === "GET") {

        $pedidosQuery = $con->query("SELECT * FROM pedidos;");

        // Vai trazer pizza_id e status_id
        $pedidos = $pedidosQuery->fetchAll();

        // Array que vai receber a pizza montada com massa, borda e sabores
        $pizzas = [];

        // Montando pizza
        foreach($pedidos as $pedido) {

            $pizza = [];
            // Definir um array para a pizza

            $pizza["id"] = $pedido["pizza_id"];

            // RESGATANDO A PIZZA
            $pizzaQuery = $con->prepare("SELECT * FROM pizzas 
            WHERE id = :pizza_id");

            $pizzaQuery->bindParam(":pizza_id", $pizza["id"]);     

            // Vai trazer a pizza isolada com borda_id e massa_id
            $pizzaQuery->execute();            
            $pizzaData = $pizzaQuery->fetch(PDO::FETCH_ASSOC);


            // RESGATANDO A BORDA
            $bordaQuery = $con->prepare("SELECT * FROM bordas WHERE id = :borda_id");
            $bordaQuery->bindParam(":borda_id", $pizzaData["borda_id"]);
            $bordaQuery->execute();            
            $borda = $bordaQuery->fetch(PDO::FETCH_ASSOC);
            $pizza["borda"] = $borda["tipo"];

             // RESGATANDO A MASSA
             $massaQuery = $con->prepare("SELECT * FROM massas WHERE id = :massa_id");
             $massaQuery->bindParam(":massa_id", $pizzaData["massa_id"]);
             $massaQuery->execute();            
             $massa = $massaQuery->fetch(PDO::FETCH_ASSOC);
             $pizza["massa"] = $massa["tipo"];

             // RESGATANDO OS SABORES DA PIZZA
             $saboresQuery = $con->prepare("SELECT * FROM pizza_sabor WHERE pizza_id = :pizza_id");
             $saboresQuery->bindParam(":pizza_id", $pizza["id"]);
             $saboresQuery->execute(); 
             $sabores = $saboresQuery->fetchAll(PDO::FETCH_ASSOC);

             // RESGATANDO O NOME DOS SABORES
             $saboresDaPizza = [];
             $saborQuery = $con->prepare("SELECT * FROM sabores WHERE id = :sabor_id");
             
            foreach($sabores as $sabor) {
                $saborQuery->bindParam(":sabor_id", $sabor["sabor_id"]);
                $saborQuery->execute();                
                $saborPizza = $saborQuery->fetch(PDO::FETCH_ASSOC);
                array_push($saboresDaPizza, $saborPizza["nome"]);
             }
             
             $pizza["sabores"] = $saboresDaPizza;

             // ADICIONAR O STATUS DO PEDIDO
             $pizza["status"] = $pedido["status_id"];

             // ADICONAR O ARRAY DE PIZZA NO ARRAY DAS PIZZAS
             array_push($pizzas, $pizza);

                        

        }

        // RESGATANDO OS STATUS
        $statusQuery = $con->query("SELECT * FROM status;");
        $status = $statusQuery->fetchAll();
        

    } else if ($method === "POST") {
        
        // Verificando o tipo do POST
        $type = $_POST["type"];

        // REMOVENDO PEDIDOS
        if ($type === "delete") {
            
            $pizzaId = $_POST["id"];
    
            $deleteQuery = $con->prepare("DELETE FROM pedidos WHERE pizza_id = :pizza_id;");
            $deleteQuery->bindParam(":pizza_id", $pizzaId, PDO::PARAM_INT);
            $deleteQuery->execute();
    
            $_SESSION["msg"] = "Pedido removido com sucesso!";
            $_SESSION["status"] = "success";

                // ATUALIZAR STATUS DO PEDIDO
        } else if ($type === "update") {
            
            $pizzaId = $_POST["id"];
            $statusId = $_POST["status"];
      
            $updateQuery = $con->prepare("UPDATE pedidos SET status_id = :status_id WHERE pizza_id = :pizza_id");
      
            $updateQuery->bindParam(":pizza_id", $pizzaId, PDO::PARAM_INT);
            $updateQuery->bindParam(":status_id", $statusId, PDO::PARAM_INT);
      
            $updateQuery->execute();
      
            $_SESSION["msg"] = "Pedido atualizado com sucesso!";
            $_SESSION["status"] = "success";
        }

        // retorna para dashboard
        header("Location: ../dashboard.php");  
    }     
     
?>