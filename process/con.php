<?php

    // Inicialização de sessão para exibir mensagens para o usuário
    session_start();

    // Variáveis para a conexão do DB
    $user = "root";
    $pass = "sprt@#3382";
    $db = "pizzariadojoao";
    $host = "127.0.0.1";

    try {
        $con = new PDO("mysql:host={$host};dbname={$db}", $user, $pass);
        // habilitar os erros 
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


    } catch (PDOException $err) {
        print "Erro: " . $err->getMessage() . "</br>";
        die();
    }

?>