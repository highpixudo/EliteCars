<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "elitecars";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die ("Erro na conexão com a base de dados: " . $conn->connect_error);
}

session_start();

if (isset ($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $sql_update_last_activity = "UPDATE utilizadores SET ultima_atividade = NOW() WHERE id = ?";
    $stmt_update_last_activity = $conn->prepare($sql_update_last_activity);

    if ($stmt_update_last_activity) {
        $stmt_update_last_activity->bind_param("s", $user_id);
        $stmt_update_last_activity->execute();
        $stmt_update_last_activity->close();

    }
}
?>