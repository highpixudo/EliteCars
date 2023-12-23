<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elitecars";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão com a base de dados: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password != $confirm_password) {
        $error_message = "As palavras-passe não coincidem.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO utilizadores (user, email, pass, foto) VALUES ('$username', '$email', '$hashed_password', 'assets/user-img.png')";
        if ($conn->query($sql) === TRUE) {
            header("Location: login.php");
        }
    }
}

$conn->close();
?>