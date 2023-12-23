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

    $response = array('success' => true, 'username' => $username, 'filename' => $filename);
    echo json_encode($response);

    $sql = "UPDATE utilizadores SET foto = '$uploadPath' WHERE user = '{$_SESSION['username']}'";

    if ($conn->query($sql) === TRUE) {
        exit();
    }

    header("Location: conta.php");
} else {
    $response = array('success' => false);
    echo json_encode($response);
}

$conn->close();
?>
