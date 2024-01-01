<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elitecars";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $sobrenome = $_POST["sobrenome"];
    $user = $_POST["user"];
    $email = $_POST["mail"];
    $num_tel = $_POST["numtele"];
    $nacionalidade = $_POST["nacionalidade"];

    $sql = "UPDATE utilizadores SET nome=?, sobrenome=?, user=?, email=?, num_tel=?, nacionalidade=? WHERE id=?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssssi", $nome, $sobrenome, $user, $email, $num_tel, $nacionalidade, $_SESSION['user_id']);

        if ($stmt->execute()) {
            echo "Dados atualizados com sucesso!";
        } else {
            echo "Erro ao atualizar dados: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Erro na preparação da consulta: " . $conn->error;
    }
}

$conn->close();
?>