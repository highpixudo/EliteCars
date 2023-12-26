<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elitecars";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão com a base de dados: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pass_atual = $_POST["senha-atual"];
    $pass = $_POST["senha-nova"];
    $confirm_pass = $_POST["senha-nova-confirm"];
    $username = $_SESSION["username"];

    $sql = "SELECT pass FROM utilizadores WHERE user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (password_verify($pass_atual, $row["pass"]) && $pass == $confirm_pass) {
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        
        $sql_update = "UPDATE utilizadores SET pass = ? WHERE user = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $hashed_password, $username);
        $stmt_update->execute();
        
        if ($stmt_update->affected_rows > 0) {
            header("Location: conta.php");
        } else {
            echo "Erro ao atualizar a senha.";
        }

        $stmt_update->close();
    } else {
        echo 'erro';
    }

    $stmt->close();
}
?>
