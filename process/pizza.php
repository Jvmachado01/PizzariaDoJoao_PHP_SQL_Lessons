<?php
    include_once("con.php");


    // Detectar o método que está sendo enviado
    $method = $_SERVER["REQUEST_METHOD"];

    // GET para trazer os dados
    if ($method === "GET") {
        
        $bordasQuery = $con->query("SELECT * FROM bordas;");
        // busca todos os sabores e coloca no array dentro da variável $bordas
        $bordas = $bordasQuery->fetchAll();
        
        $massasQuery = $con->query("SELECT * FROM massas;");
        $massas = $massasQuery->fetchAll();
        
        $saboresQuery = $con->query("SELECT * FROM sabores;");
        $sabores = $saboresQuery->fetchAll();      
         
        // POST para remoção/atualização (criação do pedido)
    } else if ($method === "POST") {
        
        $data = $_POST;
        

        // Desmembrar os dados do POST
        $borda = $data["borda"];
        $massa = $data["massa"];
        $sabores = $data["sabores"];

        // Validação de sabores máximos
        if (count($sabores) > 3) {

            $_SESSION["msg"] = "Selecione no máximo 3 sabores!";
            $_SESSION["status"] = "warning";
        } else {
            
            // Salvando borda e massa na pizza
            // prepare para podermos filtrar os dados que serão inseridos no DB
            $stmt = $con->prepare("INSERT INTO pizzas (borda_id, massa_id) VALUES (:borda, :massa)");

            // Filtrando inputs
            $stmt->bindParam(":borda", $borda, PDO::PARAM_INT);
            $stmt->bindParam(":massa", $massa, PDO::PARAM_INT);

            // Preparada a query, agora devemos executá-la
            $stmt->execute();

            // Resgatando  id da última pizza
            $pizzaId = $con->lastInsertId();

            $stmt = $con->prepare("INSERT INTO pizza_sabor (pizza_id, sabor_id) VALUES (:pizza, :sabor)");

             // repetição até terminar de salvar todos os sabores
             foreach($sabores as $sabor) {
                // filtrando os inputs
                $stmt->bindParam(":pizza", $pizzaId, PDO::PARAM_INT);
                $stmt->bindParam(":sabor", $sabor, PDO::PARAM_INT);
        
                $stmt->execute();
        
             }

             // Criar o pedido da pizza
             $stmt = $con->prepare("INSERT INTO pedidos (pizza_id, status_id) VALUES (:pizza, :status)");

             // Status: sempre inicia com 1, que é em produção
             $statusId = 1;

             // Filtrar inputs
            $stmt->bindParam(":pizza", $pizzaId);
            $stmt->bindParam(":status", $statusId);

            $stmt->execute();

            // Exibir mensagem de sucesso
            $_SESSION["msg"] = "Pedido realizado com sucesso";
            $_SESSION["status"] = "success";

        }

        // Retorna para a página home
        header("Location: ..");
        

    }
?> 




















