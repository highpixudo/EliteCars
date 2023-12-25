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

            $query = "SELECT email FROM reset_tokens WHERE token = '$token' AND tempo_expirar > NOW()";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $email_token = $row['email'];
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $sql = "UPDATE utilizadores SET pass = '$hashed_password' WHERE email = '$email_token'";

                if ($conn->query($sql) === TRUE) {
                    header("Location: login.php");
                } else {
                    echo "Erro na atualização da senha: " . $conn->error;
                }
            } else {
                echo "Token inválido ou expirado.";
            }
        }
    }
}

$conn->close();
?>
