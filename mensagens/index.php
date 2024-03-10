<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/mensagens.css">
    <link rel="stylesheet" href="../css/nav_bar.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../js/scripts.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>EliteCars - Veículos</title>
</head>

<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "elitecars";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão com a base de dados: " . $conn->connect_error);
}

$secretKey = bin2hex(random_bytes(32));

session_start();

$sessionLifetime = 1800; // 30 minutos em segundos

// ajustar o tempo de vida da sessão se a checkbox "lembrar-me" estiver marcada
if (isset($_COOKIE['remember_me']) && !empty($_COOKIE['remember_me'])) {
    $sessionLifetime = 60 * 60 * 24 * 30; // 30 dias em segundos
}

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $sessionLifetime)) {
    // a sessão expirou
    session_unset();
    session_destroy();
}

$_SESSION['last_activity'] = time();
?>

<body>
    <div class="topnav" id="myTopnav">
        <a href="../" class="logo">ELITE</a>
        <div class="nav-buttons">
            <?php
            if (isset($_SESSION["username"])) {
                echo '<a href="../" class="register" id="home">Início</a>';
                echo '<a href="../veiculos" class="register" id="cars">Veículos</a>';
                echo '<a href="" class="register" id="about">Sobre</a>';
                echo '<a href="../conta" class="register" id="account">Conta</a>';
            } else {
                echo '<a href="../signup" class="register">Criar conta</a>';
                echo '<a href="../login" class="login">Entrar</a>';
            }
            ?>
        </div>
    </div>

    <?php
    if (!isset($_SESSION["username"])) {
        echo '<h1 style="text-align: center;">Não tem sessão iniciada, por favor <a href="../login">inicie sessão</a></h1>';
        return;
    }
    ?>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user"]) && isset($_POST["message"])) {
        $user = $_SESSION["username"];
        $message = $_POST["message"];

        $stmt = $conn->prepare("INSERT INTO chat_messages (user, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $user, $message);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_GET["user"])) {
        $selectedUser = $_GET["user"];
        $result = $conn->prepare("SELECT * FROM chat_messages WHERE user = ? ORDER BY timestamp DESC LIMIT 10");
        $result->bind_param("s", $selectedUser);
        $result->execute();
        $result = $result->get_result();
    } else {
        $result = $conn->query("SELECT * FROM chat_messages ORDER BY timestamp DESC LIMIT 10");
    }
    ?>

    <div id="chat-container">
        <div id="chat-messages">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<p><strong>" . htmlspecialchars($row['user']) . ":</strong> " . htmlspecialchars($row['message']) . "</p>";
                }
            } else {
                echo "<p>Nenhuma mensagem disponível para este usuário.</p>";
            }
            ?>
        </div>

        <form id="chat-form" method="post" action="">
            <input type="text" name="message" placeholder="Digite sua mensagem" required>
            <button type="submit">Enviar</button>
        </form>
    </div>

</body>