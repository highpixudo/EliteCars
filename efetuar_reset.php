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
    if (isset($_GET['token']) && !empty($_GET['token'])) {
        $token = $_GET['token'];
        $password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];

        if ($password != $confirm_password) {
            $error_message = "As palavras-passe não coincidem.";
        } else {

            $query = "SELECT email FROM reset_tokens WHERE token = ? AND tempo_expirar > NOW()";
            $stmt = $conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param("s", $token);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $email_token = $row['email'];
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "UPDATE utilizadores SET pass = ? WHERE email = ?";
                    $stmt_update = $conn->prepare($sql);


                    if ($stmt_update) {
                        $stmt_update->bind_param("ss", $hashed_password, $email_token);
                        if ($stmt_update->execute()) {
                            header("Location: login.php");
                        } else {
                            echo "Erro na atualização da senha: " . $stmt_update->error;
                        }
                        $stmt_update->close();
                    } else {
                        echo "Erro na preparação da consulta de atualização: " . $conn->error;
                    }
                } else {
                    echo "Token inválido ou expirado.";
                }

                $stmt->close();
            } else {
                echo "Erro na preparação da consulta: " . $conn->error;
            }
        }
    }
}

$conn->close();
?>