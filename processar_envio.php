<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elitecars";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão com a base de dados: " . $conn->connect_error);
}

if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $username = $_SESSION['username'];
    $filename = $_FILES['file']['name'];
    $uploadPath = 'uploads/' . $username . '/';

    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $uploadPath .= $filename;

    move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath);
    
    $stmt = $conn->prepare("UPDATE utilizadores SET foto = ? WHERE user = ?");
    $stmt->bind_param("ss", $uploadPath, $username);

    if ($stmt->execute()) {
        $response = array('success' => true, 'username' => $username, 'filename' => $filename);
        echo json_encode($response);
        exit();
    } else {
        // Trate erros de execução da consulta adequadamente
        $response = array('success' => false, 'error' => $stmt->error);
        echo json_encode($response);
    }

    $stmt->close();
} else {
    $response = array('success' => false);
    echo json_encode($response);
}

$conn->close();
?>